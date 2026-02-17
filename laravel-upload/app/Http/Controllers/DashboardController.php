<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\WeightLog;
use App\Models\Workout;
use App\Models\Routine;
use App\Models\RoutineDay;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        // 1. Récupérer le dernier poids et celui d'avant pour calculer la tendance
        $weightLogs = $user->weightLogs()->orderBy('date', 'desc')->take(2)->get();
        $lastWeight = $weightLogs->first();
        $previousWeight = $weightLogs->last();

        $weightDiff = 0;
        if ($weightLogs->count() > 1) {
            $weightDiff = $lastWeight->weight - $previousWeight->weight;
        }

        // 2. Calculer le nombre de séances terminées
        $workoutCount = $user->workouts()->where('status', 'completed')->count();

        // 3. Calculer le Volume Total (Poids x Reps de toutes les séries de l'user)
        // On utilise une requête brute pour la performance
        $totalVolume = DB::table('training_sets')
            ->join('workouts', 'training_sets.workout_id', '=', 'workouts.id')
            ->where('workouts.user_id', $user->id)
            ->selectRaw('SUM(weight * reps) as total')
            ->value('total') ?? 0;

        // 4. Récupérer les 3 dernières séances pour l'historique rapide
        $recentWorkouts = $user->workouts()->latest()->take(3)->get();

        // 5. Récupérer les routines avec séance du jour
        $today = RoutineDay::getTodayDay();
        $routines = $user->routines()
            ->with(['days' => function($query) use ($today) {
                $query->where('day_of_week', $today)
                      ->where('is_active', true)
                      ->with('exercises');
            }])
            ->get();

        // Trouver la routine du jour (première avec un workout aujourd'hui)
        $todayRoutine = $routines->first(function($routine) {
            return $routine->days->count() > 0;
        });

        // Alternatives (autres routines)
        $alternativeRoutines = $routines->filter(function($routine) use ($todayRoutine) {
            return $routine->id !== $todayRoutine?->id && $routine->days->count() > 0;
        });

        return view('dashboard', compact(
            'lastWeight',
            'weightDiff',
            'workoutCount',
            'totalVolume',
            'recentWorkouts',
            'todayRoutine',
            'alternativeRoutines',
            'routines'
        ));
    }

    // Méthode pour enregistrer le poids depuis le modal
    public function storeWeight(Request $request)
    {
        $request->validate([
            'weight' => 'required|numeric|min:20|max:300',
        ]);

        Auth::user()->weightLogs()->create([
            'weight' => $request->weight,
            'date' => now()->format('Y-m-d'),
        ]);

        return back()->with('success', 'Poids mis à jour ! ⚖️');
    }
}
