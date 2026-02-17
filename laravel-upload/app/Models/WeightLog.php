<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WeightLog extends Model
{
    // Ajoute cette ligne :
    protected $fillable = ['user_id', 'weight', 'date'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
