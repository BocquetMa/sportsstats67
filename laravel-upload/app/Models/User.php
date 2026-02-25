<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use App\Models\BodyMetric;
use App\Models\BodyPhoto;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'avatar',
        'cover_photo',
        'bio',
        'xp'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function weightLogs()
    {
        return $this->hasMany(WeightLog::class);
    }

    public function workouts()
    {
        return $this->hasMany(Workout::class);
    }

    public function routines()
    {
        return $this->hasMany(Routine::class);
    }

    public function metrics()
{
    return $this->hasMany(BodyMetric::class)->orderBy('created_at', 'desc');
}

public function latestMetric()
{
    return $this->hasOne(BodyMetric::class)->latestOfMany();
}

public function bodyPhotos()
{
    return $this->hasMany(BodyPhoto::class);
}

public function messagesSent()
{
    return $this->hasMany(Message::class, 'sender_id');
}

public function messagesReceived()
{
    return $this->hasMany(Message::class, 'receiver_id');
}

public function getRankAttribute()
{
    if ($this->xp >= 1000) return ['label' => 'ÉLITE', 'color' => 'text-amber-400', 'border' => 'border-amber-400'];
    if ($this->xp >= 500) return ['label' => 'PRO', 'color' => 'text-rose-500', 'border' => 'border-rose-500'];
    if ($this->xp >= 100) return ['label' => 'ESPOIR', 'color' => 'text-blue-400', 'border' => 'border-blue-400'];

    return ['label' => 'NOVICE', 'color' => 'text-slate-500', 'border' => 'border-slate-800'];
}

    }
