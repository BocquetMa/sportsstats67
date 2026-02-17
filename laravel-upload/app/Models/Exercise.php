<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Exercise extends Model
{
    protected $fillable = [
        'exerciseId', 'name', 'gifUrl', 'targetMuscles',
        'bodyParts', 'equipments', 'secondaryMuscles', 'instructions'
    ];

    protected $casts = [
        'targetMuscles' => 'array',
        'bodyParts' => 'array',
        'equipments' => 'array',
        'secondaryMuscles' => 'array',
        'instructions' => 'array',
    ];

}
