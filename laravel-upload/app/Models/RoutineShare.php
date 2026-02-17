<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class RoutineShare extends Model
{
    use HasFactory;

    protected $fillable = [
        'routine_id',
        'shared_by',
        'shared_with',
        'share_code',
        'share_type',
        'is_accepted',
        'accepted_at',
        'expires_at'
    ];

    protected $casts = [
        'is_accepted' => 'boolean',
        'accepted_at' => 'datetime',
        'expires_at' => 'datetime',
    ];

    public function routine()
    {
        return $this->belongsTo(Routine::class);
    }

    public function sharedBy()
    {
        return $this->belongsTo(User::class, 'shared_by');
    }

    public function sharedWith()
    {
        return $this->belongsTo(User::class, 'shared_with');
    }

    /**
     * Génère un code de partage unique
     */
    public static function generateShareCode()
    {
        do {
            $code = strtoupper(Str::random(8));
        } while (self::where('share_code', $code)->exists());

        return $code;
    }

    /**
     * Vérifie si le partage est expiré
     */
    public function isExpired()
    {
        return $this->expires_at && $this->expires_at->isPast();
    }

    /**
     * Scope pour les partages actifs
     */
    public function scopeActive($query)
    {
        return $query->where(function ($q) {
            $q->whereNull('expires_at')
              ->orWhere('expires_at', '>', now());
        });
    }
}
