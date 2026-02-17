@extends('layouts.app')

@section('title', 'Profil de ' . $user->name)

@section('content')
<div class="min-h-screen bg-slate-900 text-white">
    <!-- Header avec cover photo -->
    <div class="relative">
        <div class="h-48 bg-gradient-to-r from-blue-600 via-purple-600 to-pink-600"></div>
        @if($user->cover_photo)
        <div class="absolute inset-0 bg-cover" style="background-image: url('{{ Storage::url($user->cover_photo) }}'); opacity: 0.3;"></div>
        @endif
        <div class="absolute bottom-0 left-0 right-0 h-24 bg-gradient-to-t from-slate-900 to-transparent"></div>
    </div>

    <div class="max-w-6xl mx-auto px-6 -mt-20 relative z-10">
        <div class="flex flex-col md:flex-row gap-6">
            <!-- Sidebar Profil -->
            <div class="md:w-72">
                <div class="bg-slate-800 rounded-xl p-6 text-center">
                    @if($user->avatar)
                    <img src="{{ Storage::url($user->avatar) }}" alt="{{ $user->name }}" class="w-32 h-32 rounded-full mx-auto border-4 border-slate-800 object-cover">
                    @else
                    <div class="w-32 h-32 rounded-full mx-auto bg-gradient-to-br from-blue-500 to-purple-600 flex items-center justify-center text-4xl font-bold">
                        {{ substr($user->name, 0, 1) }}
                    </div>
                    @endif

                    <h1 class="text-2xl font-bold mt-4">{{ $user->name }}</h1>

                    <div class="flex items-center justify-center gap-2 mt-2">
                        <span class="px-3 py-1 rounded-full text-sm font-semibold {{ $rank['color'] }} border {{ $rank['border'] }}">
                            {{ $rank['label'] }}
                        </span>
                    </div>

                    @if($user->bio)
                    <p class="text-slate-400 mt-4 text-sm">{{ $user->bio }}</p>
                    @endif

                    <!-- Stats rapides -->
                    <div class="grid grid-cols-2 gap-4 mt-6">
                        <div class="text-center">
                            <p class="text-2xl font-bold text-yellow-400">{{ number_format($stats['xp']) }}</p>
                            <p class="text-xs text-slate-400">XP Total</p>
                        </div>
                        <div class="text-center">
                            <p class="text-2xl font-bold text-blue-400">{{ $stats['streak'] }}</p>
                            <p class="text-xs text-slate-400">Jours streak</p>
                        </div>
                    </div>
                </div>

                <!-- Stats détaillées -->
                <div class="bg-slate-800 rounded-xl p-6 mt-4">
                    <h2 class="font-semibold mb-4">📊 Statistiques</h2>
                    <div class="space-y-3">
                        <div class="flex justify-between">
                            <span class="text-slate-400">Entraînements</span>
                            <span class="font-semibold">{{ $stats['total_workouts'] }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-slate-400">Volume total</span>
                            <span class="font-semibold">{{ $stats['total_volume_tons'] }}t</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-slate-400">Max handled</span>
                            <span class="font-semibold">{{ $stats['max_weight'] }}kg</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-slate-400">Photos</span>
                            <span class="font-semibold">{{ $stats['photos_count'] }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-slate-400">Mesures</span>
                            <span class="font-semibold">{{ $stats['metrics_count'] }}</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Contenu principal -->
            <div class="flex-1 space-y-6">
                <!-- Badges débloqués -->
                <div class="bg-slate-800 rounded-xl p-6">
                    <h2 class="text-xl font-semibold mb-4">🏆 Badges ({{ $earnedBadges->count() }})</h2>

                    @if($earnedBadges->count() > 0)
                    <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 gap-4">
                        @foreach($earnedBadges as $badge)
                        <div class="bg-slate-700 rounded-lg p-4 text-center hover:scale-105 transition cursor-pointer group relative"
                             title="{{ $badge->description }}">
                            <div class="text-4xl mb-2">{{ $badge->icon }}</div>
                            <p class="font-semibold text-sm">{{ $badge->name }}</p>
                            <span class="text-xs px-2 py-1 rounded-full mt-2 inline-block
                                @if($badge->rarity === 'legendary') bg-yellow-500/20 text-yellow-400
                                @elseif($badge->rarity === 'epic') bg-purple-500/20 text-purple-400
                                @elseif($badge->rarity === 'rare') bg-blue-500/20 text-blue-400
                                @else bg-slate-500/20 text-slate-400 @endif">
                                {{ ucfirst($badge->rarity) }}
                            </span>

                            <!-- Tooltip -->
                            <div class="absolute bottom-full left-1/2 -translate-x-1/2 mb-2 w-48 bg-slate-900 p-3 rounded-lg text-xs hidden group-hover:block z-10 border border-slate-700">
                                <p class="font-semibold mb-1">{{ $badge->description }}</p>
                                <p class="text-yellow-400">+{{ $badge->xp_reward }} XP</p>
                            </div>
                        </div>
                        @endforeach
                    </div>
                    @else
                    <p class="text-slate-400 text-center py-8">Aucun badge débloqué pour le moment. Commence à t'entraîner ! 💪</p>
                    @endif
                </div>

                <!-- Badges verrouillés -->
                @if($lockedBadges->count() > 0)
                <div class="bg-slate-800 rounded-xl p-6">
                    <h2 class="text-xl font-semibold mb-4">🔒 Badges à débloquer</h2>
                    <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 gap-4 opacity-50">
                        @foreach($lockedBadges as $badge)
                        <div class="bg-slate-700 rounded-lg p-4 text-center">
                            <div class="text-4xl mb-2 grayscale">🚫</div>
                            <p class="font-semibold text-sm">{{ $badge->name }}</p>
                            <span class="text-xs text-slate-400">{{ $badge->condition_description ?? 'Condition à définir' }}</span>
                        </div>
                        @endforeach
                    </div>
                </div>
                @endif

                <!-- Progression Bar -->
                <div class="bg-slate-800 rounded-xl p-6">
                    <h2 class="text-xl font-semibold mb-4">📈 Progression vers le prochain rang</h2>
                    @php
                    $nextRank = null;
                    $ranks = [
                        ['label' => 'NOVICE', 'xp' => 0],
                        ['label' => 'ESPOIR', 'xp' => 100],
                        ['label' => 'PRO', 'xp' => 500],
                        ['label' => 'ÉLITE', 'xp' => 1000],
                        ['label' => 'CHAMPION', 'xp' => 2500],
                    ];

                    $currentRankIndex = 0;
                    $nextRank = null;

                    foreach ($ranks as $index => $rank) {
                        if ($stats['xp'] >= $rank['xp']) {
                            $currentRankIndex = $index;
                        } else {
                            $nextRank = $rank;
                            break;
                        }
                    }

                    if (!$nextRank && count($ranks) > $currentRankIndex + 1) {
                        $nextRank = $ranks[$currentRankIndex + 1];
                    }

                    $currentXp = $stats['xp'];
                    $prevXp = $ranks[$currentRankIndex]['xp'];
                    $nextXp = $nextRank['xp'] ?? $ranks[$currentRankIndex]['xp'] + 500;
                    $progress = $nextRank ? (($currentXp - $prevXp) / ($nextXp - $prevXp)) * 100 : 100;
                    @endphp

                    <div class="flex items-center justify-between mb-2">
                        <span class="text-sm text-slate-400">{{ $ranks[$currentRankIndex]['label'] }}</span>
                        @if($nextRank)
                        <span class="text-sm text-slate-400">{{ $nextRank['label'] }}</span>
                        @endif
                    </div>
                    <div class="h-4 bg-slate-700 rounded-full overflow-hidden">
                        <div class="h-full bg-gradient-to-r from-blue-500 to-purple-500 transition-all duration-500" style="width: {{ $progress }}%"></div>
                    </div>
                    <p class="text-center mt-2 text-sm">
                        <span class="font-bold">{{ number_format($currentXp) }}</span> XP
                        @if($nextRank)
                        / {{ number_format($nextXp) }} XP pour le prochain rang
                        @else
                        - MAX RANK ! 🎉
                        @endif
                    </p>
                </div>

                <!-- Badges par rarity -->
                @php
                $rarities = ['legendary', 'epic', 'rare', 'common'];
                @endphp

                @foreach($rarities as $rarity)
                @php
                $rarityBadges = $earnedBadges->where('rarity', $rarity);
                @endphp
                @if($rarityBadges->count() > 0)
                <div class="bg-slate-800 rounded-xl p-6">
                    <h2 class="text-xl font-semibold mb-4 capitalize">
                        @if($rarity === 'legendary') 🏅 Légendaires
                        @elseif($rarity === 'epic') 💜 Épiques
                        @elseif($rarity === 'rare') 💎 Rares
                        @else ⚪ Communs
                        @endif
                    </h2>
                    <div class="flex flex-wrap gap-3">
                        @foreach($rarityBadges as $badge)
                        <div class="flex items-center gap-2 bg-slate-700 rounded-full px-4 py-2">
                            <span class="text-2xl">{{ $badge->icon }}</span>
                            <span class="font-medium">{{ $badge->name }}</span>
                        </div>
                        @endforeach
                    </div>
                </div>
                @endif
                @endforeach
            </div>
        </div>
    </div>
</div>
@endsection
