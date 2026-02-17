<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TrainingSet extends Model
{
    protected $fillable = ['workout_id', 'exercise_id', 'reps', 'weight', 'is_assisted', 'rest_time', 'set_number'];

    /**
     * Une série appartient à un exercice
     */
    public function exercise(): BelongsTo
    {
        return $this->belongsTo(Exercise::class);
    }

    /**
     * Une série appartient à une séance
     */
    public function workout(): BelongsTo
    {
        return $this->belongsTo(Workout::class);
    }
}
