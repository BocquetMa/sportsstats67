<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Routine extends Model
{
    protected $fillable = ['user_id', 'name'];

    // La routine appartient à un utilisateur
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    // Une routine contient plusieurs exercices programmés
    public function routineExercises(): HasMany
    {
        return $this->hasMany(RoutineExercise::class)->orderBy('order');
    }

    // Les séances réelles basées sur cette routine
    public function workouts(): HasMany
    {
        return $this->hasMany(Workout::class);
    }
}