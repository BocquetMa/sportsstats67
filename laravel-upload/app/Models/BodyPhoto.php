<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BodyPhoto extends Model
{
    protected $fillable = [
        'user_id',
        'photo_path',
        'body_part',
        'type',
        'notes',
        'weight_at_photo'
    ];

    protected $casts = [
        'weight_at_photo' => 'decimal:2'
    ];

    /**
     * Une photo appartient à un utilisateur
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Scope pour les photos "avant"
     */
    public function scopeBefore($query)
    {
        return $query->where('type', 'before');
    }

    /**
     * Scope pour les photos "après"
     */
    public function scopeAfter($query)
    {
        return $query->where('type', 'after');
    }

    /**
     * Scope pour les photos de progression
     */
    public function scopeProgress($query)
    {
        return $query->where('type', 'progress');
    }

    /**
     * Scope par partie du corps
     */
    public function scopeBodyPart($query, $part)
    {
        return $query->where('body_part', $part);
    }

    /**
     * Récupérer la photo "avant" correspondante
     */
    public function getBeforePhotoAttribute()
    {
        return self::where('user_id', $this->user_id)
            ->where('body_part', $this->body_part)
            ->where('type', 'before')
            ->where('created_at', '<', $this->created_at)
            ->orderBy('created_at', 'desc')
            ->first();
    }
}
