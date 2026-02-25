<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Routine extends Model
{
    protected $fillable = [
        'user_id',
        'name',
        'is_public',
        'is_shared_copy',
        'original_routine_id'
    ];

    protected $casts = [
        'is_public' => 'boolean',
        'is_shared_copy' => 'boolean',
    ];

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

    // Jours planifiés de la routine
    public function days(): HasMany
    {
        return $this->hasMany(RoutineDay::class)->orderByRaw("
            CASE day_of_week
                WHEN 'monday'    THEN 1
                WHEN 'tuesday'   THEN 2
                WHEN 'wednesday' THEN 3
                WHEN 'thursday'  THEN 4
                WHEN 'friday'    THEN 5
                WHEN 'saturday'  THEN 6
                WHEN 'sunday'    THEN 7
                ELSE 8
            END
        ");
    }

    // Partages de cette routine
    public function shares(): HasMany
    {
        return $this->hasMany(RoutineShare::class);
    }

    // Routine originale (si c'est une copie partagée)
    public function originalRoutine(): BelongsTo
    {
        return $this->belongsTo(Routine::class, 'original_routine_id');
    }

    // Copies de cette routine (si elle a été partagée)
    public function copies(): HasMany
    {
        return $this->hasMany(Routine::class, 'original_routine_id');
    }

    /**
     * Récupère la séance du jour (si planifiée)
     */
    public function getTodayWorkout()
    {
        $today = RoutineDay::getTodayDay();
        return $this->days()->where('day_of_week', $today)->where('is_active', true)->first();
    }
}

