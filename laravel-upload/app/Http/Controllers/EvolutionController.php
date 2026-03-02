<?php

namespace App\Http\Controllers;

use App\Models\BodyMetric;
use App\Models\BodyPhoto;
use App\Models\WorkoutCalorie;
use App\Models\TrainingSet;
use App\Models\Workout;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class EvolutionController extends Controller
{
    /**
     * Affiche la page d'évolution avec tous les graphiques
     */
    public function index()
    {
        $user = Auth::user();

        // Récupérer les mesures corporelles
        $bodyMetrics = $user->metrics()->take(30)->get();

        // Récupérer les photos
        $photos = $user->bodyPhotos()->latest()->get()->groupBy('body_part');

        // Calories de la semaine
        $weeklyCalories = WorkoutCalorie::whereHas('workout', function ($q) use ($user) {
                $q->where('user_id', $user->id);
            })
            ->where('created_at', '>=', now()->subWeek())
            ->sum('estimated_calories');

        // Total calories mensuel
        $monthlyCalories = WorkoutCalorie::whereHas('workout', function ($q) use ($user) {
                $q->where('user_id', $user->id);
            })
            ->where('created_at', '>=', now()->subMonth())
            ->sum('estimated_calories');

        return view('stats.evolution', compact('bodyMetrics', 'photos', 'weeklyCalories', 'monthlyCalories'));
    }

    /**
     * Graphique d'évolution du poids et mesures corporelles
     */
    public function bodyMetricsChart(Request $request)
    {
        $user = Auth::user();
        $period = $request->get('period', 90); // jours

        $metrics = $user->metrics()
            ->where('created_at', '>=', now()->subDays($period))
            ->orderBy('created_at', 'asc')
            ->get();

        return response()->json([
            'labels' => $metrics->map(function ($m) {
                return $m->created_at->format('d/m/Y');
            })->toArray(),
            'weight' => $metrics->pluck('weight')->toArray(),
            'body_fat' => $metrics->pluck('body_fat')->filter()->toArray(),
            'chest' => $metrics->pluck('chest')->filter()->toArray(),
            'waist' => $metrics->pluck('waist')->filter()->toArray(),
            'hips' => $metrics->pluck('hips')->filter()->toArray(),
            'arms' => $metrics->pluck('arms')->filter()->toArray(),
            'thighs' => $metrics->pluck('thighs')->filter()->toArray(),
        ]);
    }

    /**
     * Ajouter une mesure corporelle
     */
    public function storeBodyMetric(Request $request)
    {
        $request->validate([
            'weight' => 'required|numeric',
            'body_fat' => 'nullable|numeric',
            'chest' => 'nullable|numeric',
            'waist' => 'nullable|numeric',
            'hips' => 'nullable|numeric',
            'arms' => 'nullable|numeric',
            'thighs' => 'nullable|numeric',
            'calves' => 'nullable|numeric',
        ]);

        $user = Auth::user();
        $user->metrics()->create($request->all());

        return back()->with('success', 'Mesure enregistrée avec succès');
    }

    /**
     * Calculateur de 1RM
     */
    public function oneRepMax(Request $request)
    {
        $request->validate([
            'weight' => 'required|numeric|min:0',
            'reps' => 'required|integer|min:1',
        ]);

        $weight = $request->get('weight');
        $reps = $request->get('reps');

        // Formules 1RM
        $epley = $weight * (1 + $reps / 30);
        $brzycki = $weight * (36 / (37 - $reps));
        $lander = (100 * $weight) / (101.3 - 2.67123 * $reps);
        $lombardi = $weight * pow($reps, 0.10);

        // Moyenne des formules
        $average = round(($epley + $brzycki + $lander + $lombardi) / 4);

        return response()->json([
            'epley' => round($epley),
            'brzycki' => round($brzycki),
            'lander' => round($lander),
            'lombardi' => round($lombardi),
            'average' => $average,
            'percentages' => $this->calculatePercentages($average),
        ]);
    }

    /**
     * Calculer les pourcentages du 1RM
     */
    private function calculatePercentages($oneRm): array
    {
        $percentages = [];
        foreach ([95, 90, 85, 80, 75, 70, 65, 60, 55, 50] as $percent) {
            $percentages[$percent] = round($oneRm * $percent / 100);
        }
        return $percentages;
    }

    /**
     * Historique des progrès par exercice (graphique linéaire)
     */
    public function exerciseProgress(Request $request)
    {
        $user = Auth::user();
        $exerciseId = $request->get('exercise_id');
        $limit = $request->get('limit', 20);

        if (!$exerciseId) {
            return response()->json(['error' => 'Exercise ID required'], 400);
        }

        $progress = TrainingSet::whereHas('workout', function ($q) use ($user) {
                $q->where('user_id', $user->id)->where('status', 'completed');
            })
            ->where('exercise_id', $exerciseId)
            ->where('weight', '>', 0)
            ->select('exercise_id', 'workout_id', 'weight', 'reps', 'created_at')
            ->get()
            ->groupBy('workout_id')
            ->map(function ($sets) {
                $maxWeight = $sets->max('weight');
                $totalVolume = $sets->sum(function ($set) {
                    return $set->weight * $set->reps;
                });
                $bestSet = $sets->sortByDesc(function ($s) {
                    return $s->weight * $s->reps;
                })->first();
                return [
                    'max_weight' => $maxWeight,
                    'total_volume' => $totalVolume,
                    'estimated_1rm' => round($this->calculate1RM($maxWeight, $bestSet->reps)),
                    'date' => $sets->first()->created_at->format('Y-m-d'),
                ];
            })
            ->take($limit)
            ->values();

        return response()->json([
            'labels' => $progress->pluck('date')->map(function ($date) {
                return \Carbon\Carbon::parse($date)->format('d/m');
            })->toArray(),
            'max_weights' => $progress->pluck('max_weight')->toArray(),
            'volumes' => $progress->pluck('total_volume')->toArray(),
            'estimated_1rm' => $progress->pluck('estimated_1rm')->toArray(),
        ]);
    }

    /**
     * Calcul simple du 1RM estimé
     */
    private function calculate1RM($weight, $reps): float
    {
        if ($reps == 1) return $weight;
        return $weight * (1 + $reps / 30);
    }

    /**
     * Photos avant/après - Timeline
     */
    public function photosTimeline(Request $request)
    {
        $user = Auth::user();
        $bodyPart = $request->get('body_part', 'full_body');

        $photos = $user->bodyPhotos()
            ->where('body_part', $bodyPart)
            ->orderBy('created_at', 'asc')
            ->get();

        // Grouper par année/mois
        $timeline = $photos->groupBy(function ($photo) {
            return $photo->created_at->format('Y-m');
        });

        return response()->json([
            'photos' => $photos->map(function ($p) {
                return [
                    'id' => $p->id,
                    'url' => Storage::url($p->photo_path),
                    'type' => $p->type,
                    'weight' => $p->weight_at_photo,
                    'date' => $p->created_at->format('d/m/Y'),
                ];
            })->toArray(),
            'timeline' => $timeline->keys()->toArray(),
        ]);
    }

    /**
     * Upload d'une photo
     */
    public function uploadPhoto(Request $request)
    {
        $request->validate([
            'photo' => 'required|image|max:10240', // 10MB max
            'body_part' => 'required|string',
            'type' => 'required|string|in:before,after,progress',
            'notes' => 'nullable|string',
        ]);

        $user = Auth::user();
        $path = $request->file('photo')->store('body-photos/' . $user->id, 'public');

        $weight = $user->latestMetric?->weight;

        $user->bodyPhotos()->create([
            'photo_path' => $path,
            'body_part' => $request->get('body_part'),
            'type' => $request->get('type'),
            'notes' => $request->get('notes'),
            'weight_at_photo' => $weight,
        ]);

        return back()->with('success', 'Photo uploadée avec succès');
    }

    /**
     * Supprimer une photo
     */
    public function deletePhoto(BodyPhoto $photo)
    {
        if ($photo->user_id !== Auth::id()) {
            abort(403);
        }

        Storage::delete($photo->photo_path);
        $photo->delete();

        return back()->with('success', 'Photo supprimée');
    }

    /**
     * Calories brûlées par entraînement
     */
    public function caloriesBurned(Request $request)
    {
        $user = Auth::user();
        $period = $request->get('period', 30); // jours

        $calories = WorkoutCalorie::whereHas('workout', function ($q) use ($user) {
                $q->where('user_id', $user->id);
            })
            ->where('created_at', '>=', now()->subDays($period))
            ->orderBy('created_at', 'asc')
            ->get()
            ->groupBy(function ($c) {
                return $c->created_at->format('Y-m-d');
            })
            ->map(function ($items) {
                return $items->sum('estimated_calories');
            });

        return response()->json([
            'labels' => $calories->keys()->map(function ($date) {
                return \Carbon\Carbon::parse($date)->format('d/m');
            })->toArray(),
            'data' => $calories->values()->toArray(),
            'total' => $calories->sum(),
            'daily_average' => $calories->count() > 0 ? round($calories->sum() / $calories->count()) : 0,
        ]);
    }

    /**
     * Sauvegarder les calories d'un entraînement
     */
    public function storeCalories(Request $request)
    {
        $request->validate([
            'workout_id' => 'required|exists:workouts,id',
            'estimated_calories' => 'required|integer',
            'duration_minutes' => 'nullable|integer',
        ]);

        $user = Auth::user();

        // Vérifier que l'entraînement appartient à l'utilisateur
        $workout = Workout::where('id', $request->workout_id)
            ->where('user_id', $user->id)
            ->firstOrFail();

        WorkoutCalorie::create([
            'workout_id' => $workout->id,
            'estimated_calories' => $request->estimated_calories,
            'duration_minutes' => $request->duration_minutes,
            'met_value' => $request->met_value ?? 6.0,
        ]);

        // Ajouter XP pour l'entraînement complété
        $xpEarned = min(50, floor($request->estimated_calories / 10));
        $user->increment('xp', $xpEarned);

        return back()->with('success', "Calories enregistrées + {$xpEarned} XP");
    }

    /**
     * Graphique calories mensuelles
     */
    public function monthlyCalories()
    {
        $user = Auth::user();

        $driver = \Illuminate\Support\Facades\DB::getDriverName();
        $monthExpr = $driver === 'mysql'
            ? "DATE_FORMAT(created_at, '%Y-%m') as month"
            : "strftime('%Y-%m', created_at) as month";

        $monthly = WorkoutCalorie::whereHas('workout', function ($q) use ($user) {
                $q->where('user_id', $user->id);
            })
            ->where('created_at', '>=', now()->subMonths(6))
            ->selectRaw("{$monthExpr}, SUM(estimated_calories) as total")
            ->groupBy('month')
            ->orderBy('month', 'asc')
            ->get();

        return response()->json([
            'labels' => $monthly->pluck('month')->map(function ($m) {
                return \Carbon\Carbon::parse($m . '-01')->format('M Y');
            })->toArray(),
            'data' => $monthly->pluck('total')->toArray(),
        ]);
    }

    /**
     * Personal Bests par exercice
     */
    public function personalBests(Request $request)
    {
        $user = Auth::user();

        $pb = TrainingSet::whereHas('workout', function ($q) use ($user) {
                $q->where('user_id', $user->id)->where('status', 'completed');
            })
            ->where('weight', '>', 0)
            ->with('exercise')
            ->select('exercise_id', DB::raw('MAX(weight * reps) as max_volume'), DB::raw('MAX(weight) as max_weight'))
            ->groupBy('exercise_id')
            ->orderByDesc('max_weight')
            ->limit(20)
            ->get()
            ->map(function ($set) {
                return [
                    'exercise_name' => $set->exercise?->name,
                    'max_weight' => $set->max_weight,
                    'max_volume' => $set->max_volume,
                    'exercise_id' => $set->exercise_id,
                ];
            });

        if ($request->wantsJson()) {
            return response()->json($pb);
        }

        return view('stats.personal-bests', ['personalBests' => $pb]);
    }
}
