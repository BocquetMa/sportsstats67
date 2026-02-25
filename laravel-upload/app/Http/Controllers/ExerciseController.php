<?php

namespace App\Http\Controllers;

use App\Models\Exercise; // <--- TRÈS IMPORTANT
use Illuminate\Http\Request;

class ExerciseController extends Controller
{
    /**
     * Recherche d'exercices pour l'autocomplétion (AJAX).
     */
    public function search(Request $request)
    {
        $query = $request->input('q', '');
        $limit = min((int) $request->input('limit', 20), 50);

        $exercises = Exercise::query()
            ->when($query, function ($q) use ($query) {
                $q->where('name', 'like', "%{$query}%")
                  ->orWhere('targetMuscles', 'like', "%{$query}%")
                  ->orWhere('bodyParts', 'like', "%{$query}%");
            })
            ->select('id', 'name', 'targetMuscles', 'bodyParts', 'gifUrl')
            ->limit($limit)
            ->get()
            ->map(fn($ex) => [
                'id'            => $ex->id,
                'name'          => $ex->name,
                'targetMuscles' => $ex->targetMuscles,
                'bodyParts'     => $ex->bodyParts,
                'gifUrl'        => $ex->gifUrl,
            ]);

        return response()->json($exercises);
    }

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
