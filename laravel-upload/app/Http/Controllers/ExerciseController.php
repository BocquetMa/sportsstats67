<?php

namespace App\Http\Controllers;

use App\Models\Exercise; // <--- TRÈS IMPORTANT
use Illuminate\Http\Request;

class ExerciseController extends Controller
{
    /**
     * Affiche la liste des exercices avec recherche et pagination.
     */
    public function index(Request $request)
    {
        // 1. On récupère le terme de recherche s'il existe
        $search = $request->input('search');

        // 2. On prépare la requête
        $exercises = Exercise::query()
            ->when($search, function ($query, $search) {
                return $query->where('name', 'like', "%{$search}%")
                             ->orWhere('targetMuscles', 'like', "%{$search}%")
                             ->orWhere('bodyParts', 'like', "%{$search}%");
            })
            ->latest() // Les derniers ajoutés en premier
            ->paginate(12); // On en affiche 12 par page

        // 3. On renvoie la vue avec les données
        return view('exercises.index', compact('exercises'));
    }
}
