<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Badge extends Model
{
    protected $fillable = [
        'name',
        'description',
        'icon',
        'condition_type',
        'condition_value',
        'xp_reward',
        'rarity' // common, rare, epic, legendary
    ];

    const BADGES = [
        [
            'name' => 'Premier Pas',
            'description' => 'Compléter ton premier entraînement',
            'icon' => '🎯',
            'condition_type' => 'workouts_completed',
            'condition_value' => 1,
            'xp_reward' => 50,
            'rarity' => 'common'
        ],
        [
            'name' => 'Régularité',
            'description' => '7 jours consécutifs dentraînement',
            'icon' => '🔥',
            'condition_type' => 'streak_days',
            'condition_value' => 7,
            'xp_reward' => 100,
            'rarity' => 'rare'
        ],
        [
            'name' => 'Marathonien',
            'description' => '100 entraînement complétés',
            'icon' => '🏆',
            'condition_type' => 'workouts_completed',
            'condition_value' => 100,
            'xp_reward' => 500,
            'rarity' => 'epic'
        ],
        [
            'name' => 'Légende',
            'description' => '500 entraînement complétés',
            'icon' => '👑',
            'condition_type' => 'workouts_completed',
            'condition_value' => 500,
            'xp_reward' => 2000,
            'rarity' => 'legendary'
        ],
        [
            'name' => 'Force Herculéenne',
            'description' => 'Squat 150kg',
            'icon' => '🦵',
            'condition_type' => 'max_weight_exercise',
            'condition_value' => 150,
            'exercise_name' => 'Squat',
            'xp_reward' => 200,
            'rarity' => 'epic'
        ],
        [
            'name' => 'Dos Large',
            'description' => 'Soulevé 100kg au développé couché',
            'icon' => '💪',
            'condition_type' => 'max_weight_exercise',
            'condition_value' => 100,
            'exercise_name' => 'Bench Press',
            'xp_reward' => 150,
            'rarity' => 'rare'
        ],
        [
            'name' => 'Tonneau',
            'description' => '100 tonnes de volume total',
            'icon' => '🏋️',
            'condition_type' => 'total_volume',
            'condition_value' => 100000, // 100 tonnes en kg
            'xp_reward' => 300,
            'rarity' => 'rare'
        ],
        [
            'name' => 'Mega Tonneau',
            'description' => '500 tonnes de volume total',
            'icon' => '🏆',
            'condition_type' => 'total_volume',
            'condition_value' => 500000,
            'xp_reward' => 1000,
            'rarity' => 'epic'
        ],
        [
            'name' => 'Photogénique',
            'description' => '10 photos de progression uploadées',
            'icon' => '📸',
            'condition_type' => 'body_photos',
            'condition_value' => 10,
            'xp_reward' => 100,
            'rarity' => 'common'
        ],
        [
            'name' => 'Surveillant',
            'description' => '30 jours de mesures corporelles',
            'icon' => '📏',
            'condition_type' => 'body_metrics',
            'condition_value' => 30,
            'xp_reward' => 150,
            'rarity' => 'rare'
        ],
    ];

    public static function getAllBadges()
    {
        return self::BADGES;
    }

    public static function getBadgeByName($name)
    {
        return collect(self::BADGES)->firstWhere('name', $name);
    }
}
