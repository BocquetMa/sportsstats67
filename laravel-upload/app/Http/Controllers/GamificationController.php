<?php

namespace App\Http\Controllers;

use App\Models\Badge;
use App\Models\User;
use App\Models\Workout;
use App\Models\TrainingSet;
use App\Models\BodyPhoto;
use App\Models\BodyMetric;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class GamificationController extends Controller
{
    /**
     * Affiche le profil public avec stats et badges
     */
    public function profile($userId = null)
    {
        $user = $userId ? User::findOrFail($userId) : Auth::user();

        // Stats de l'utilisateur
        $stats = $this->getUserStats($user);

        // Badges débloqués
        $earnedBadges = $this->checkBadges($user);

        // Badges non débloqués
        $allBadges = Badge::getAllBadges();
        $earnedBadgeNames = $earnedBadges->pluck('name')->toArray();
        $lockedBadges = collect($allBadges)->whereNotIn('name', $earnedBadgeNames);

        return view('profile.public', compact('user', 'stats', 'earnedBadges', 'lockedBadges'));
    }

    /**
     * Récupère les statistiques de l'utilisateur
     */
    private function getUserStats(User $user): array
    {
        $totalWorkouts = $user->workouts()->where('status', 'completed')->count();

        $totalVolume = DB::table('training_sets')
            ->join('workouts', 'training_sets.workout_id', '=', 'workouts.id')
            ->where('workouts.user_id', $user->id)
            ->where('workouts.status', 'completed')
            ->selectRaw('SUM(training_sets.weight * training_sets.reps) as total')
            ->value('total') ?? 0;

        $maxWeight = DB::table('training_sets')
            ->join('workouts', 'training_sets.workout_id', '=', 'workouts.id')
            ->where('workouts.user_id', $user->id)
            ->where('workouts.status', 'completed')
            ->max('weight') ?? 0;

        $photosCount = $user->bodyPhotos()->count();
        $metricsCount = $user->metrics()->count();

        // Calculer le streak
        $streak = $this->calculateStreak($user);

        return [
            'total_workouts' => $totalWorkouts,
            'total_volume' => $totalVolume,
            'total_volume_tons' => round($totalVolume / 1000, 1),
            'max_weight' => $maxWeight,
            'photos_count' => $photosCount,
            'metrics_count' => $metricsCount,
            'streak' => $streak,
            'xp' => $user->xp,
            'rank' => $user->rank,
        ];
    }

    /**
     * Calcule le streak de jours consécutifs
     */
    private function calculateStreak(User $user): int
    {
        $completedWorkouts = $user->workouts()
            ->where('status', 'completed')
            ->where('completed_at', '>=', now()->subDays(365))
            ->orderBy('completed_at', 'desc')
            ->pluck('completed_at')
            ->toArray();

        if (empty($completedWorkouts)) return 0;

        $streak = 0;
        $currentDate = now()->startOfDay();

        foreach ($completedWorkouts as $workoutDate) {
            $workoutDay = \Carbon\Carbon::parse($workoutDate)->startOfDay();

            if ($workoutDay->eq($currentDate) || $workoutDay->eq($currentDate->subDay())) {
                $streak++;
                $currentDate = $workoutDay->subDay();
            } else {
                break;
            }
        }

        return $streak;
    }

    /**
     * Vérifie et attribue les badges
     */
    private function checkBadges(User $user): \Illuminate\Support\Collection
    {
        $stats = $this->getUserStats($user);
        $earnedBadges = collect();

        foreach (Badge::getAllBadges() as $badge) {
            $unlocked = false;

            switch ($badge['condition_type']) {
                case 'workouts_completed':
                    $unlocked = $stats['total_workouts'] >= $badge['condition_value'];
                    break;

                case 'streak_days':
                    $unlocked = $stats['streak'] >= $badge['condition_value'];
                    break;

                case 'total_volume':
                    $unlocked = $stats['total_volume'] >= $badge['condition_value'];
                    break;

                case 'max_weight_exercise':
                    $exerciseName = $badge['exercise_name'] ?? null;
                    if ($exerciseName) {
                        $maxExerciseWeight = TrainingSet::whereHas('workout', function ($q) use ($user) {
                                $q->where('user_id', $user->id)->where('status', 'completed');
                            })
                            ->whereHas('exercise', function ($q) use ($exerciseName) {
                                $q->where('name', 'LIKE', "%{$exerciseName}%");
                            })
                            ->max('weight');
                        $unlocked = $maxExerciseWeight >= $badge['condition_value'];
                    }
                    break;

                case 'body_photos':
                    $unlocked = $stats['photos_count'] >= $badge['condition_value'];
                    break;

                case 'body_metrics':
                    $unlocked = $stats['metrics_count'] >= $badge['condition_value'];
                    break;
            }

            if ($unlocked) {
                $earnedBadges->push((object) $badge);
            }
        }

        return $earnedBadges;
    }

    /**
     * Retourne les badges pour l'API
     */
    public function badges()
    {
        $user = Auth::user();
        $earnedBadges = $this->checkBadges($user);

        return response()->json([
            'xp' => $user->xp,
            'rank' => $user->rank,
            'badges' => $earnedBadges,
        ]);
    }

    /**
     * Leaderboard
     */
    public function leaderboard(Request $request)
    {
        $type = $request->get('type', 'xp'); // xp, workouts, volume

        switch ($type) {
            case 'workouts':
                $users = User::withCount(['workouts' => function ($q) {
                        $q->where('status', 'completed');
                    }])
                    ->orderByDesc('workouts_count')
                    ->limit(50)
                    ->get();
                break;

            case 'volume':
                $users = User::with(['workouts.trainingSets' => function ($q) {
                        $q->whereHas('workout', function ($q2) {
                            $q2->where('status', 'completed');
                        });
                    }])
                    ->get()
                    ->map(function ($user) {
                        $totalVolume = $user->workouts->flatMap(function ($w) {
                            return $w->trainingSets->map(function ($s) {
                                return $s->weight * $s->reps;
                            });
                        })->sum();
                        $user->total_volume = $totalVolume;
                        return $user;
                    })
                    ->sortByDesc('total_volume')
                    ->take(50);
                break;

            default:
                $users = User::orderByDesc('xp')->limit(50)->get();
        }

        return view('gamification.leaderboard', compact('users', 'type'));
    }

    /**
     * Ajouter de l'XP (utilisé après un entraînement)
     */
    public static function addXp(User $user, int $amount): void
    {
        $user->increment('xp', $amount);

        // Vérifier les badges à chaque ajout d'XP
        self::checkAndAwardBadges($user);
    }

    /**
     * Vérifie et award les badges
     */
    private static function checkAndAwardBadges(User $user): void
    {
        $controller = new self();
        $earnedBadges = $controller->checkBadges($user);

        // Ici tu peux ajouter de la logique pour ne pas award plusieurs fois
        // Ex: stocker les badges déjà awardés dans une table pivot
    }
}
