<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Workout extends Model
{
    protected $fillable = ['title', 'user_id'];

    public function trainingSets()
    {
        return $this->hasMany(TrainingSet::class)->with('exercise');
    }
}
