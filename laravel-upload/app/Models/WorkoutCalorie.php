<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class WorkoutCalorie extends Model
{
    protected $table = 'workout_calories';

    protected $fillable = [
        'workout_id',
        'estimated_calories',
        'met_value',
        'duration_minutes'
    ];

    protected $casts = [
        'estimated_calories' => 'integer',
        'met_value' => 'decimal:2',
        'duration_minutes' => 'integer'
    ];

    /**
     * Une entrée de calories appartient à un entraînement
     */
    public function workout(): BelongsTo
    {
        return $this->belongsTo(Workout::class);
    }

    /**
     * Calculer les calories avec la formule MET
     * Calories = MET × poids (kg) × durée (heures)
     */
    public static function calculateCalories(float $met, float $weightKg, int $durationMinutes): int
    {
        return (int) round($met * $weightKg * ($durationMinutes / 60));
    }

    /**
     * Estimer le MET basé sur les exercices pratiqués
     */
    public static function estimateMetFromExercises($trainingSets): float
    {
        // MET approximatifs par type d'exercice
        $metByMuscle = [
            'chest' => 6.0,
            'back' => 6.0,
            'legs' => 7.0,
            'shoulders' => 5.5,
            'arms' => 5.0,
            'abs' => 4.5,
            'cardio' => 8.0,
        ];

        $totalMet = 0;
        $count = 0;

        foreach ($trainingSets as $set) {
            if ($set->exercise && $set->exercise->bodyParts) {
                $bodyParts = $set->exercise->bodyParts;
                if (is_array($bodyParts) && count($bodyParts) > 0) {
                    $met = $metByMuscle[$bodyParts[0]] ?? 5.5;
                    $totalMet += $met;
                    $count++;
                }
            }
        }

        return $count > 0 ? $totalMet / $count : 5.5;
    }
}
