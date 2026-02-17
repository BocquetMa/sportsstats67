<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RoutineDay extends Model
{
    use HasFactory;

    protected $fillable = [
        'routine_id',
        'day_of_week',
        'name',
        'notes',
        'is_active'
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    /**
     * Jours de la semaine en français
     */
    public static $daysInFrench = [
        'monday' => 'Lundi',
        'tuesday' => 'Mardi',
        'wednesday' => 'Mercredi',
        'thursday' => 'Jeudi',
        'friday' => 'Vendredi',
        'saturday' => 'Samedi',
        'sunday' => 'Dimanche',
    ];

    public function routine()
    {
        return $this->belongsTo(Routine::class);
    }

    public function exercises()
    {
        return $this->belongsToMany(Exercise::class, 'routine_day_exercises')
            ->withPivot(['sets_count', 'target_reps', 'rest_time', 'order'])
            ->orderByPivot('order');
    }

    /**
     * Récupère le nom du jour en français
     */
    public function getDayNameAttribute()
    {
        return self::$daysInFrench[$this->day_of_week] ?? $this->day_of_week;
    }

    /**
     * Scope pour récupérer les jours actifs
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Récupère le jour correspondant à aujourd'hui
     */
    public static function getTodayDay()
    {
        $days = ['sunday', 'monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday'];
        return $days[now()->dayOfWeek];
    }
}
