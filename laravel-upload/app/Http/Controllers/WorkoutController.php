<?php

namespace App\Http\Controllers;

use App\Models\Workout;
use App\Models\Exercise;
use App\Models\TrainingSet;
use App\Models\Routine;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class WorkoutController extends Controller
{
    /**
     * Affiche la liste des séances (Routines) disponibles pour commencer.
     */
    public function index(Request $request)
    {
        $userId = Auth::id();

        // 1. Récupère les routines (tes programmes enregistrés)
        $routines = Routine::where('user_id', $userId)->get();

        // 2. Récupère l'historique des séances (avec les relations pour éviter les bugs d'affichage)
        $workouts = Workout::where('user_id', $userId)
            ->with(['trainingSets'])
            ->orderBy('created_at', 'desc')
            ->get();

        // 3. Récupère le CATALOGUE d'exercices (C'est ce qui manquait !)
        $exerciseQuery = Exercise::query();

        // Ajout de la recherche si présente dans l'URL
        if ($request->has('search')) {
            $exerciseQuery->where('name', 'like', '%' . $request->search . '%');
        }

        $exercises = $exerciseQuery->get();

        // On renvoie tout à la vue
        return view('workouts.index', compact('routines', 'workouts', 'exercises'));
    }

    /**
     * Crée une nouvelle séance vide (libre).
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
        ]);

        $workout = Auth::user()->workouts()->create([
            'title' => $validated['title'],
            'status' => 'in_progress'
        ]);

        return redirect()->route('workouts.show', $workout)
            ->with('success', 'Séance créée ! Ajoute des exercices pour commencer.');
    }

    /**
     * Initialise une séance réelle à partir d'une Routine existante.
     */
    public function storeFromRoutine(Routine $routine)
    {
        // Sécurité : vérifier que la routine appartient à l'utilisateur
        if ($routine->user_id !== Auth::id()) abort(403);

        // 1. Créer la séance réelle (le log)
        $workout = Auth::user()->workouts()->create([
            'title' => $routine->name,
            'routine_id' => $routine->id,
            'status' => 'in_progress'
        ]);

        // 2. On boucle sur la config de la routine pour créer les séries vides
        foreach ($routine->routineExercises as $setup) {
            for ($i = 1; $i <= $setup->sets_count; $i++) {
                $workout->trainingSets()->create([
                    'exercise_id' => $setup->exercise_id,
                    'set_number' => $i,
                    'reps' => 0,
                    'weight' => 0,
                ]);
            }
        }

        return redirect()->route('workout.show', $workout);
    }

    /**
     * Affiche l'interface de la séance en cours (Focus mode).
     */
    public function show(Workout $workout)
    {
        // Sécurité
        if ($workout->user_id !== Auth::id()) abort(403);

        $workout->load('trainingSets.exercise');

        // Récupération des Records Personnels (PR)
        $records = TrainingSet::whereIn('exercise_id', $workout->trainingSets->pluck('exercise_id'))
            ->whereHas('workout', function($q) {
                $q->where('user_id', Auth::id())->where('status', 'completed');
            })
            ->selectRaw('exercise_id, MAX(weight) as max_weight, MAX(reps) as max_reps')
            ->groupBy('exercise_id')
            ->get()
            ->keyBy('exercise_id');

        return view('workouts.show', compact('workout', 'records'));
    }

    /**
     * Mise à jour d'une série en temps réel (via AJAX/Alpine.js).
     */
    public function updateSet(Request $request, TrainingSet $trainingSet)
    {
        // Validation simple
        $validated = $request->validate([
            'weight' => 'required|numeric',
            'reps' => 'required|integer',
        ]);

        $trainingSet->update([
            'weight' => $validated['weight'],
            'reps' => $validated['reps'],
            'completed_at' => now(),
        ]);

        return response()->json(['status' => 'success', 'message' => 'Set validé']);
    }

    /**
     * Ajout manuel d'une série (si besoin pendant la séance).
     */
    public function addExercise(Request $request, Workout $workout)
    {
        if ($workout->user_id !== Auth::id()) abort(403);

        $request->validate([
            'exercise_id' => 'required|exists:exercises,id',
            'reps' => 'required|integer|min:1',
            'weight' => 'required|numeric|min:0',
        ]);

        $workout->trainingSets()->create([
            'exercise_id' => $request->exercise_id,
            'reps' => $request->reps,
            'weight' => $request->weight,
            'is_assisted' => $request->has('is_assisted'),
            'rest_time' => $request->rest_time ?? 60,
        ]);

        return back()->with('success', 'Série ajoutée ! 💪');
    }

    /**
     * Supprime une série spécifique.
     */
    public function destroySet(TrainingSet $set)
    {
        if ($set->workout->user_id !== Auth::id()) abort(403);

        $set->delete();
        return back()->with('success', 'Série supprimée !');
    }

    /**
     * Clôture la séance.
     */
    public function finish(Workout $workout)
    {
        if ($workout->user_id !== Auth::id()) abort(403);

        $workout->update([
            'status' => 'completed',
            'completed_at' => now(),
        ]);

        // Calculer le volume de la séance pour l'XP
        $volume = $workout->trainingSets->sum(fn($s) => $s->weight * $s->reps);
        $setsCount = $workout->trainingSets->count();

        // XP: 50 de base + 1 XP par série complétée (max 150)
        $xpEarned = min(150, 50 + $setsCount);
        Auth::user()->increment('xp', $xpEarned);

        return redirect()->route('profile.history')
            ->with('success', "Séance terminée ! +{$xpEarned} XP gagnés 💪");
    }

    /**
     * Génère ou retourne un lien de partage pour un workout terminé.
     */
    public function share(Workout $workout)
    {
        if ($workout->user_id !== Auth::id()) abort(403);
        if ($workout->status !== 'completed') {
            return back()->with('error', 'Tu peux partager uniquement les séances terminées.');
        }

        if (!$workout->share_token) {
            $workout->update(['share_token' => Str::random(32)]);
        }

        $url = route('workouts.shared', $workout->share_token);
        return back()->with('share_url', $url);
    }

    /**
     * Page publique d'un workout partagé.
     */
    public function showShared(string $token)
    {
        $workout = Workout::where('share_token', $token)
            ->where('status', 'completed')
            ->with(['trainingSets.exercise', 'user'])
            ->firstOrFail();

        $sets = $workout->trainingSets->groupBy('exercise_id');

        $totalVolume = $workout->trainingSets->sum(fn($s) => $s->weight * $s->reps);
        $totalSets   = $workout->trainingSets->count();

        return view('workouts.shared', compact('workout', 'sets', 'totalVolume', 'totalSets'));
    }
}
