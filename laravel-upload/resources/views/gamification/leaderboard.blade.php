@extends('layouts.app')

@section('title', 'Classements')

@section('content')
<div class="min-h-screen bg-slate-900 text-white p-6">
    <div class="max-w-4xl mx-auto">
        <h1 class="text-3xl font-bold mb-8">🏆 Classements</h1>

        <!-- Type de classement -->
        <div class="flex gap-2 mb-6">
            <a href="?type=xp" class="px-4 py-2 rounded-lg transition {{ $type === 'xp' ? 'bg-yellow-600' : 'bg-slate-800 hover:bg-slate-700' }}">
                🏅 XP
            </a>
            <a href="?type=workouts" class="px-4 py-2 rounded-lg transition {{ $type === 'workouts' ? 'bg-yellow-600' : 'bg-slate-800 hover:bg-slate-700' }}">
                💪 Entraînements
            </a>
            <a href="?type=volume" class="px-4 py-2 rounded-lg transition {{ $type === 'volume' ? 'bg-yellow-600' : 'bg-slate-800 hover:bg-slate-700' }}">
                🏋️ Volume
            </a>
        </div>

        <div class="bg-slate-800 rounded-xl overflow-hidden">
            @foreach($users as $index => $user)
            <div class="flex items-center gap-4 p-4 {{ $index < 3 ? 'bg-slate-700/50' : '' }} border-b border-slate-700 last:border-0">
                <!-- Rang -->
                <div class="w-12 text-center">
                    @if($index === 0)
                    <span class="text-2xl">🥇</span>
                    @elseif($index === 1)
                    <span class="text-2xl">🥈</span>
                    @elseif($index === 2)
                    <span class="text-2xl">🥉</span>
                    @else
                    <span class="text-xl font-bold text-slate-500">{{ $index + 1 }}</span>
                    @endif
                </div>

                <!-- Avatar -->
                <div class="w-12 h-12 rounded-full overflow-hidden flex-shrink-0">
                    @if($user->avatar)
                    <img src="{{ Storage::url($user->avatar) }}" alt="{{ $user->name }}" class="w-full h-full object-cover">
                    @else
                    <div class="w-full h-full bg-gradient-to-br from-blue-500 to-purple-600 flex items-center justify-center font-bold">
                        {{ substr($user->name, 0, 1) }}
                    </div>
                    @endif
                </div>

                <!-- Nom et rang -->
                <div class="flex-1">
                    <div class="flex items-center gap-2">
                        <a href="{{ route('profile.public', $user) }}" class="font-semibold hover:text-blue-400 transition">
                            {{ $user->name }}
                        </a>
                        @if($user->xp >= 1000)
                        <span class="px-2 py-0.5 text-xs rounded bg-amber-500/20 text-amber-400">ÉLITE</span>
                        @elseif($user->xp >= 500)
                        <span class="px-2 py-0.5 text-xs rounded bg-rose-500/20 text-rose-400">PRO</span>
                        @elseif($user->xp >= 100)
                        <span class="px-2 py-0.5 text-xs rounded bg-blue-500/20 text-blue-400">ESPOIR</span>
                        @endif
                    </div>
                </div>

                <!-- Score -->
                <div class="text-right">
                    @if($type === 'xp')
                    <p class="text-xl font-bold text-yellow-400">{{ number_format($user->xp) }}</p>
                    <p class="text-xs text-slate-400">XP</p>
                    @elseif($type === 'workouts')
                    <p class="text-xl font-bold text-blue-400">{{ $user->workouts_count ?? 0 }}</p>
                    <p class="text-xs text-slate-400">workouts</p>
                    @elseif($type === 'volume')
                    <p class="text-xl font-bold text-green-400">{{ round(($user->total_volume ?? 0) / 1000, 1) }}t</p>
                    <p class="text-xs text-slate-400">volume</p>
                    @endif
                </div>
            </div>
            @endforeach

            @if($users->isEmpty())
            <div class="p-8 text-center text-slate-400">
                Aucun utilisateur dans ce classement pour le moment
            </div>
            @endif
        </div>

        <!-- Ton classement -->
        @php
        $currentUserRank = $users->search(function ($u) {
            return $u->id === auth()->id();
        });
        @endphp

        @if($currentUserRank !== false)
        <div class="mt-6 bg-gradient-to-r from-blue-600 to-purple-600 rounded-xl p-4 flex items-center justify-between">
            <div class="flex items-center gap-4">
                <span class="text-2xl">👤</span>
                <div>
                    <p class="font-semibold">Ton classement</p>
                    <p class="text-sm opacity-80">{{ $users[$currentUserRank]->name }}</p>
                </div>
            </div>
            <div class="text-right">
                <p class="text-2xl font-bold">#{{ $currentUserRank + 1 }}</p>
            </div>
        </div>
        @endif
    </div>
</div>
@endsection
