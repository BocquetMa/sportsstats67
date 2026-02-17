<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProfileController extends Controller
{
    // Affiche le profil d'un utilisateur
    public function show(User $user)
    {
        // On charge le nombre de messages envoyés pour les stats
        $user->loadCount('messagesSent');
        return view('profile.show', compact('user'));
    }

    // Formulaire d'édition
    public function edit()
    {
        return view('profile.edit', ['user' => auth()->user()]);
    }

    // Sauvegarde les modifs
    public function update(Request $request)
    {
        $user = auth()->user();

        $request->validate([
            'name' => 'required|string|max:255',
            'bio' => 'nullable|string|max:500',
            'avatar' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'cover_photo' => 'nullable|image|mimes:jpeg,png,jpg|max:3072',
        ]);

        $user->name = $request->name;
        $user->bio = $request->bio;

        // Gestion de l'Avatar
        if ($request->hasFile('avatar')) {
            if ($user->avatar) Storage::disk('public')->delete($user->avatar);
            $user->avatar = $request->file('avatar')->store('avatars', 'public');
        }

        // Gestion de la Bannière
        if ($request->hasFile('cover_photo')) {
            if ($user->cover_photo) Storage::disk('public')->delete($user->cover_photo);
            $user->cover_photo = $request->file('cover_photo')->store('covers', 'public');
        }

        $user->save();

        return redirect()->route('profile.show', $user)->with('success', 'Profil mis à jour !');
    }

    public function updateWorkouts(Request $request)
{
    $user = auth()->user();

    // On vide le planning actuel pour remettre le nouveau
    $user->workouts()->delete();

    if($request->days) {
        foreach($request->days as $day) {
            $user->workouts()->create(['day' => $day]);
        }
    }

    return back()->with('success', 'Planning mis à jour !');
}

public function history()
{
    $user = auth()->user()->load(['metrics', 'workouts']);

    $latest = $user->latestMetric;
    $previous = $user->metrics->skip(1)->first();

    // Calcul de la tendance de poids
    $weightDiff = 0;
    if ($latest && $previous) {
        $weightDiff = $latest->weight - $previous->weight;
    }

    return view('profile.history', [
        'user' => $user,
        'latest' => $latest,
        'weightDiff' => $weightDiff,
        'metrics' => $user->metrics->take(7)->reverse(), // Pour le graphe
    ]);
}

}
