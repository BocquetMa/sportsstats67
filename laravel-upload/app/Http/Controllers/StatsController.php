<?php

namespace App\Http\Controllers;

use App\Models\WeightLog;
use App\Models\TrainingSet;
use App\Models\Workout;
use App\Models\Exercise;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class StatsController extends Controller
{
    /**
     * Affiche la page de statistiques détaillée
     */
    public function index()
    {
        $user = Auth::user();

        // Exercices les plus pratiqués
        $topExercises = TrainingSet::whereHas('workout', function ($q) use ($user) {
                $q->where('user_id', $user->id)->where('status', 'completed');
            })
            ->with('exercise')
            ->select('exercise_id', DB::raw('COUNT(*) as total_sets'))
            ->groupBy('exercise_id')
            ->orderByDesc('total_sets')
            ->limit(10)
            ->get();

        return view('stats.index', compact('topExercises'));
    }

    /**
     * Retourne les données du graphique de poids (30 derniers jours)
     */
    public function weightChart(Request $request)
    {
        $user = Auth::user();
        $period = $request->get('period', 30); // jours

        $weightLogs = $user->weightLogs()
            ->where('date', '>=', now()->subDays($period)->format('Y-m-d'))
            ->orderBy('date', 'asc')
            ->get()
            ->groupBy('date')
            ->map(function ($items) {
                return $items->first()->weight;
            });

        return response()->json([
            'labels' => $weightLogs->keys()->map(function ($date) {
                return \Carbon\Carbon::parse($date)->format('d/m');
            })->toArray(),
            'data' => $weightLogs->values()->toArray(),
            'current' => $weightLogs->last(),
            'start' => $weightLogs->first(),
            'change' => $weightLogs->count() >= 2
                ? round($weightLogs->last() - $weightLogs->first(), 1)
                : null,
        ]);
    }

    /**
     * Retourne les données du graphique de progression par exercice
     */
    public function exerciseProgress(Request $request)
    {
        $user = Auth::user();
        $exerciseId = $request->get('exercise_id');
        $limit = $request->get('limit', 10);

        if (!$exerciseId) {
            return response()->json(['error' => 'Exercise ID required'], 400);
        }

        // Récupérer le max de poids par séance pour cet exercice
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
                return [
                    'max_weight' => $maxWeight,
                    'total_volume' => $totalVolume,
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
        ]);
    }

    /**
     * Retourne les données du graphique weekly volume
     */
    public function weeklyVolume(Request $request)
    {
        $user = Auth::user();
        $weeks = $request->get('weeks', 8);

        $driver = DB::getDriverName();
        $weekExpr = $driver === 'mysql'
            ? "DATE_FORMAT(workouts.completed_at, '%Y-%u') as week"
            : "strftime('%Y-%W', workouts.completed_at) as week";

        $volumes = DB::table('training_sets')
            ->join('workouts', 'training_sets.workout_id', '=', 'workouts.id')
            ->where('workouts.user_id', $user->id)
            ->where('workouts.completed_at', '>=', now()->subWeeks($weeks))
            ->selectRaw("{$weekExpr}, SUM(training_sets.weight * training_sets.reps) as total_volume")
            ->groupBy('week')
            ->orderBy('week', 'asc')
            ->get();

        return response()->json([
            'labels' => $volumes->pluck('week')->map(function ($week) {
                return 'S' . substr($week, -2);
            })->toArray(),
            'data' => $volumes->pluck('total_volume')->toArray(),
        ]);
    }

    /**
     * Retourne les stats du dashboard
     */
    public function dashboardStats()
    {
        $user = Auth::user();

        $totalWorkouts = $user->workouts()->where('status', 'completed')->count();
        $totalVolume = DB::table('training_sets')
            ->join('workouts', 'training_sets.workout_id', '=', 'workouts.id')
            ->where('workouts.user_id', $user->id)
            ->where('workouts.status', 'completed')
            ->selectRaw('SUM(training_sets.weight * training_sets.reps) as total')
            ->value('total') ?? 0;

        $totalMinutes = DB::table('workout_sessions')
            ->join('workouts', 'workout_sessions.workout_id', '=', 'workouts.id')
            ->where('workouts.user_id', $user->id)
            ->where('workouts.status', 'completed')
            ->sum('duration_minutes');

        $thisWeekWorkouts = $user->workouts()
            ->where('status', 'completed')
            ->where('completed_at', '>=', now()->startOfWeek())
            ->count();

        return response()->json([
            'total_workouts' => $totalWorkouts,
            'total_volume' => round($totalVolume / 1000, 1), // en tonnes
            'total_minutes' => $totalMinutes,
            'this_week' => $thisWeekWorkouts,
        ]);
    }
}
