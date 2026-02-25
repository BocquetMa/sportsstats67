<?php

namespace App\Http\Controllers;

use App\Models\Routine;
use App\Models\RoutineDay;
use App\Models\RoutineShare;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class RoutineController extends Controller
{
    /**
     * Liste des routines de l'utilisateur
     */
    public function index()
    {
        $routines = Routine::where('user_id', Auth::id())
            ->withCount('days')
            ->with('days.exercises')
            ->latest()
            ->get();

        return view('routines.index', compact('routines'));
    }

    /**
     * Formulaire de création
     */
    public function create()
    {
        return view('routines.create');
    }

    /**
     * Sauvegarde d'une nouvelle routine
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'is_public' => 'boolean',
        ]);

        $routine = Auth::user()->routines()->create([
            'name' => $validated['name'],
            'is_public' => $validated['is_public'] ?? false,
        ]);

        return redirect()->route('routines.edit', $routine)
            ->with('success', 'Routine créée ! Planifie maintenant tes séances.');
    }

    /**
     * Édition d'une routine (planification hebdomadaire)
     */
    public function edit(Routine $routine)
    {
        if ($routine->user_id !== Auth::id()) abort(403);

        $routine->load('days.exercises');
        $days = RoutineDay::$daysInFrench;

        return view('routines.edit', compact('routine', 'days'));
    }

    /**
     * Mise à jour de la routine
     */
    public function update(Request $request, Routine $routine)
    {
        if ($routine->user_id !== Auth::id()) abort(403);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'is_public' => 'boolean',
        ]);

        $routine->update($validated);

        return back()->with('success', 'Routine mise à jour !');
    }

    /**
     * Suppression
     */
    public function destroy(Routine $routine)
    {
        if ($routine->user_id !== Auth::id()) abort(403);

        $routine->delete();

        return redirect()->route('routines.index')
            ->with('success', 'Routine supprimée.');
    }

    /**
     * Ajouter/Modifier un jour de la routine
     */
    public function updateDay(Request $request, Routine $routine, $dayOfWeek)
    {
        if ($routine->user_id !== Auth::id()) abort(403);

        $validated = $request->validate([
            'name' => 'nullable|string|max:255',
            'notes' => 'nullable|string',
            'is_active' => 'boolean',
            'exercises' => 'nullable|array',
            'exercises.*.exercise_id' => 'required_with:exercises|exists:exercises,id',
            'exercises.*.sets_count' => 'required_with:exercises|integer|min:1|max:10',
            'exercises.*.target_reps' => 'nullable|integer|min:1',
            'exercises.*.rest_time' => 'required_with:exercises|integer|min:30|max:600',
        ]);

        DB::transaction(function () use ($routine, $dayOfWeek, $validated) {
            // Créer ou récupérer le jour
            $routineDay = $routine->days()->updateOrCreate(
                ['day_of_week' => $dayOfWeek],
                [
                    'name' => $validated['name'] ?? null,
                    'notes' => $validated['notes'] ?? null,
                    'is_active' => $validated['is_active'] ?? true,
                ]
            );

            // Synchroniser les exercices (vide = effacer tous les exercices du jour)
            $exercisesData = [];
            foreach ($validated['exercises'] ?? [] as $index => $exercise) {
                $exercisesData[$exercise['exercise_id']] = [
                    'sets_count' => $exercise['sets_count'],
                    'target_reps' => $exercise['target_reps'] ?? null,
                    'rest_time' => $exercise['rest_time'],
                    'order' => $index,
                ];
            }

            $routineDay->exercises()->sync($exercisesData);
        });

        return back()->with('success', 'Jour planifié avec succès !');
    }

    /**
     * Démarrer la séance du jour
     */
    public function startToday(Routine $routine)
    {
        if ($routine->user_id !== Auth::id()) abort(403);

        $todayWorkout = $routine->getTodayWorkout();

        if (!$todayWorkout) {
            return back()->with('error', 'Aucune séance planifiée pour aujourd\'hui.');
        }

        // Créer une vraie séance basée sur le jour
        $workout = Auth::user()->workouts()->create([
            'title' => $todayWorkout->name ?? "{$routine->name} - {$todayWorkout->day_name}",
            'routine_id' => $routine->id,
            'status' => 'in_progress'
        ]);

        // Créer les séries vides
        foreach ($todayWorkout->exercises as $exercise) {
            for ($i = 1; $i <= $exercise->pivot->sets_count; $i++) {
                $workout->trainingSets()->create([
                    'exercise_id' => $exercise->id,
                    'set_number' => $i,
                    'reps' => $exercise->pivot->target_reps ?? 0,
                    'weight' => 0,
                ]);
            }
        }

        return redirect()->route('workouts.show', $workout);
    }

    /**
     * Partager une routine
     */
    public function share(Request $request, Routine $routine)
    {
        if ($routine->user_id !== Auth::id()) abort(403);

        $validated = $request->validate([
            'share_type' => 'required|in:direct,link',
            'shared_with' => 'required_if:share_type,direct|exists:users,id',
            'expires_days' => 'nullable|integer|min:1|max:365',
        ]);

        $shareData = [
            'routine_id' => $routine->id,
            'shared_by' => Auth::id(),
            'share_type' => $validated['share_type'],
        ];

        if ($validated['share_type'] === 'direct') {
            $shareData['shared_with'] = $validated['shared_with'];
        } else {
            $shareData['share_code'] = RoutineShare::generateShareCode();
        }

        if (isset($validated['expires_days'])) {
            $shareData['expires_at'] = now()->addDays($validated['expires_days']);
        }

        $share = RoutineShare::create($shareData);

        $message = $validated['share_type'] === 'link'
            ? "Code de partage : {$share->share_code}"
            : 'Routine partagée avec succès !';

        return back()->with('success', $message);
    }

    /**
     * Accepter une routine partagée (copier dans son compte)
     */
    public function acceptShare(Request $request)
    {
        $validated = $request->validate([
            'share_code' => 'required|string|exists:routine_shares,share_code',
        ]);

        $share = RoutineShare::where('share_code', $validated['share_code'])
            ->active()
            ->firstOrFail();

        if ($share->isExpired()) {
            return back()->with('error', 'Ce lien de partage a expiré.');
        }

        $originalRoutine = $share->routine;

        // Copier la routine
        DB::transaction(function () use ($originalRoutine, $share) {
            $newRoutine = Auth::user()->routines()->create([
                'name' => $originalRoutine->name . ' (Copie)',
                'is_shared_copy' => true,
                'original_routine_id' => $originalRoutine->id,
            ]);

            // Copier les jours
            foreach ($originalRoutine->days as $day) {
                $newDay = $newRoutine->days()->create([
                    'day_of_week' => $day->day_of_week,
                    'name' => $day->name,
                    'notes' => $day->notes,
                    'is_active' => $day->is_active,
                ]);

                // Copier les exercices
                $exercisesData = [];
                foreach ($day->exercises as $exercise) {
                    $exercisesData[$exercise->id] = [
                        'sets_count' => $exercise->pivot->sets_count,
                        'target_reps' => $exercise->pivot->target_reps,
                        'rest_time' => $exercise->pivot->rest_time,
                        'order' => $exercise->pivot->order,
                    ];
                }
                $newDay->exercises()->sync($exercisesData);
            }

            // Marquer comme accepté
            $share->update([
                'is_accepted' => true,
                'accepted_at' => now(),
                'shared_with' => Auth::id(),
            ]);
        });

        return redirect()->route('routines.index')
            ->with('success', 'Routine ajoutée à ton compte !');
    }
}
