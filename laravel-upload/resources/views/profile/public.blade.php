<x-app-layout>
    <div class="min-h-screen bg-[#08090a] text-white pb-28">

        {{-- Cover + Avatar --}}
        <div class="relative h-56 w-full">
            @if($user->cover_photo)
                <img src="{{ asset('storage/' . $user->cover_photo) }}" class="w-full h-full object-cover">
            @else
                <div class="w-full h-full bg-gradient-to-br from-rose-700/40 via-[#111214] to-[#08090a]"></div>
            @endif
            <div class="absolute inset-0 bg-gradient-to-t from-[#08090a] via-[#08090a]/20 to-transparent"></div>

            <a href="javascript:history.back()"
               class="absolute top-6 left-6 p-3 bg-black/30 backdrop-blur-xl rounded-2xl border border-white/10 hover:bg-rose-500/20 transition-all z-20">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 19l-7-7 7-7"/>
                </svg>
            </a>
        </div>

        <div class="px-6 -mt-16 relative z-10 space-y-6">

            {{-- Profil header --}}
            <div class="flex items-end justify-between">
                <div class="relative">
                    <div class="w-28 h-28 rounded-[2rem] bg-[#111214] border-[5px] border-[#08090a] overflow-hidden shadow-2xl">
                        @if($user->avatar)
                            <img src="{{ asset('storage/' . $user->avatar) }}" class="w-full h-full object-cover">
                        @else
                            <div class="w-full h-full flex items-center justify-center text-4xl font-black italic bg-gradient-to-br from-rose-500 to-rose-700 text-white">
                                {{ substr($user->name, 0, 1) }}
                            </div>
                        @endif
                    </div>
                    <div class="absolute -bottom-2 -right-2 bg-[#08090a] p-1 rounded-xl">
                        <span class="px-2 py-0.5 rounded-lg border {{ $user->rank['border'] }} {{ $user->rank['color'] }} text-[9px] font-black italic tracking-tighter">
                            {{ $user->rank['label'] }}
                        </span>
                    </div>
                </div>

                @if($user->id !== auth()->id())
                    <a href="{{ route('messages.show', $user) }}"
                       class="px-6 py-3 bg-rose-500 hover:bg-rose-600 rounded-2xl font-black uppercase italic text-xs tracking-widest shadow-lg shadow-rose-500/25 transition-all active:scale-95">
                        Message
                    </a>
                @else
                    <a href="{{ route('profile.edit') }}"
                       class="px-6 py-3 bg-white/5 hover:bg-white/10 border border-white/10 rounded-2xl font-black uppercase italic text-xs tracking-widest transition-all">
                        Modifier
                    </a>
                @endif
            </div>

            {{-- Nom + bio --}}
            <div>
                <h1 class="text-3xl font-black italic uppercase tracking-tighter leading-none">{{ $user->name }}</h1>
                @if($user->bio)
                    <p class="text-slate-400 text-sm leading-relaxed mt-2 italic">"{{ $user->bio }}"</p>
                @endif
            </div>

            {{-- Stats rapides --}}
            <div class="grid grid-cols-3 gap-3">
                <div class="bg-[#111214] border border-white/5 p-4 rounded-[2rem] text-center">
                    <p class="text-2xl font-black text-rose-500">{{ $stats['total_workouts'] }}</p>
                    <p class="text-[9px] font-bold uppercase text-slate-600 mt-1 tracking-widest">Séances</p>
                </div>
                <div class="bg-[#111214] border border-white/5 p-4 rounded-[2rem] text-center">
                    <p class="text-2xl font-black text-amber-400">{{ $stats['xp'] }}</p>
                    <p class="text-[9px] font-bold uppercase text-slate-600 mt-1 tracking-widest">XP</p>
                </div>
                <div class="bg-[#111214] border border-white/5 p-4 rounded-[2rem] text-center">
                    <p class="text-2xl font-black text-blue-400">{{ $stats['streak'] }}</p>
                    <p class="text-[9px] font-bold uppercase text-slate-600 mt-1 tracking-widest">Streak</p>
                </div>
            </div>

            {{-- Barre de progression XP --}}
            @php
                $xp = $stats['xp'];
                $ranks = [
                    ['label' => 'NOVICE', 'xp' => 0],
                    ['label' => 'ESPOIR', 'xp' => 100],
                    ['label' => 'PRO', 'xp' => 500],
                    ['label' => 'ÉLITE', 'xp' => 1000],
                ];
                $currentRankIdx = 0;
                $nextRank = null;
                foreach ($ranks as $i => $r) {
                    if ($xp >= $r['xp']) { $currentRankIdx = $i; }
                    elseif (!$nextRank) { $nextRank = $r; }
                }
                $prevXp = $ranks[$currentRankIdx]['xp'];
                $nextXp = $nextRank['xp'] ?? $prevXp + 1000;
                $progress = $nextRank ? min(100, (($xp - $prevXp) / ($nextXp - $prevXp)) * 100) : 100;
            @endphp
            <div class="bg-[#111214] border border-white/5 p-5 rounded-[2rem]">
                <div class="flex justify-between items-center mb-3">
                    <span class="text-[10px] font-black uppercase tracking-widest text-slate-500">Progression</span>
                    @if($nextRank)
                        <span class="text-[10px] font-black text-slate-400">{{ $xp }} / {{ $nextRank['xp'] }} XP → {{ $nextRank['label'] }}</span>
                    @else
                        <span class="text-[10px] font-black text-amber-400">RANG MAX 👑</span>
                    @endif
                </div>
                <div class="h-2 w-full bg-black rounded-full overflow-hidden">
                    <div class="h-full bg-gradient-to-r from-rose-500 to-rose-400 rounded-full shadow-[0_0_10px_rgba(244,63,94,0.4)] transition-all duration-700"
                         style="width: {{ $progress }}%"></div>
                </div>
            </div>

            {{-- Badges débloqués --}}
            <div>
                <div class="flex justify-between items-center mb-4">
                    <h2 class="text-[10px] font-black uppercase tracking-[0.4em] text-slate-600">
                        Badges ({{ $earnedBadges->count() }})
                    </h2>
                </div>

                @if($earnedBadges->count() > 0)
                    <div class="grid grid-cols-2 gap-3">
                        @foreach($earnedBadges as $badge)
                            <div class="bg-[#111214] border p-4 rounded-[2rem] flex items-center gap-3
                                {{ $badge->rarity === 'legendary' ? 'border-amber-500/30 bg-amber-500/5' :
                                   ($badge->rarity === 'epic' ? 'border-purple-500/30 bg-purple-500/5' :
                                   ($badge->rarity === 'rare' ? 'border-blue-500/30 bg-blue-500/5' : 'border-slate-700')) }}">
                                <span class="text-3xl flex-shrink-0">{{ $badge->icon }}</span>
                                <div class="min-w-0">
                                    <p class="font-black text-sm truncate">{{ $badge->name }}</p>
                                    <span class="text-[9px] font-bold uppercase
                                        {{ $badge->rarity === 'legendary' ? 'text-amber-400' :
                                           ($badge->rarity === 'epic' ? 'text-purple-400' :
                                           ($badge->rarity === 'rare' ? 'text-blue-400' : 'text-slate-500')) }}">
                                        {{ ucfirst($badge->rarity) }}
                                    </span>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="bg-[#111214] border border-slate-800 rounded-[2rem] p-8 text-center">
                        <p class="text-slate-500 font-bold uppercase text-sm">Aucun badge encore</p>
                        <p class="text-slate-700 text-xs mt-1">Continue à t'entraîner pour en débloquer !</p>
                    </div>
                @endif
            </div>

            {{-- Badges à débloquer --}}
            @if($lockedBadges->count() > 0)
                <div>
                    <h2 class="text-[10px] font-black uppercase tracking-[0.4em] text-slate-700 mb-4">
                        À débloquer ({{ $lockedBadges->count() }})
                    </h2>
                    <div class="grid grid-cols-2 gap-3 opacity-40">
                        @foreach($lockedBadges as $badge)
                            <div class="bg-[#111214] border border-slate-800 p-4 rounded-[2rem] flex items-center gap-3">
                                <span class="text-3xl flex-shrink-0 grayscale">{{ $badge['icon'] }}</span>
                                <div class="min-w-0">
                                    <p class="font-black text-sm truncate">{{ $badge['name'] }}</p>
                                    <p class="text-[9px] text-slate-600 font-bold truncate">{{ $badge['description'] }}</p>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif

            {{-- Stats détaillées --}}
            <div class="bg-[#111214] border border-white/5 p-6 rounded-[2.5rem]">
                <h3 class="text-[10px] font-black uppercase tracking-[0.4em] text-slate-600 mb-4">Performances</h3>
                <div class="space-y-3">
                    <div class="flex justify-between items-center py-2 border-b border-slate-800">
                        <span class="text-sm font-bold text-slate-400">Volume total</span>
                        <span class="font-black">{{ $stats['total_volume_tons'] }}<span class="text-xs text-slate-600 ml-1">tonnes</span></span>
                    </div>
                    <div class="flex justify-between items-center py-2 border-b border-slate-800">
                        <span class="text-sm font-bold text-slate-400">Max soulevé</span>
                        <span class="font-black">{{ $stats['max_weight'] }}<span class="text-xs text-slate-600 ml-1">kg</span></span>
                    </div>
                    <div class="flex justify-between items-center py-2 border-b border-slate-800">
                        <span class="text-sm font-bold text-slate-400">Photos progrès</span>
                        <span class="font-black">{{ $stats['photos_count'] }}</span>
                    </div>
                    <div class="flex justify-between items-center py-2">
                        <span class="text-sm font-bold text-slate-400">Mesures corporelles</span>
                        <span class="font-black">{{ $stats['metrics_count'] }}</span>
                    </div>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>
