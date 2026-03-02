<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Workout extends Model
{
    protected $fillable = ['title', 'user_id', 'status', 'routine_id', 'started_at', 'completed_at', 'share_token'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function trainingSets()
    {
        return $this->hasMany(TrainingSet::class)->with('exercise');
    }
}
