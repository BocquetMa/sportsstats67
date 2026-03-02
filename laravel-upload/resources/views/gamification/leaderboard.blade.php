<x-app-layout>
    <div class="min-h-screen bg-[#08090a] text-white pb-28">

        <div class="px-6 pt-12 pb-6">
            <p class="text-[10px] font-black text-rose-500 uppercase tracking-[0.3em] mb-1">Classements</p>
            <h1 class="text-4xl font-black uppercase italic tracking-tighter leading-none">Leaderboard</h1>
        </div>

        <div class="px-5 space-y-6">

            {{-- Tabs --}}
            <div class="bg-[#111214] border border-slate-800 p-1.5 rounded-2xl flex gap-1">
                <a href="?type=xp"
                   class="flex-1 py-2.5 rounded-xl text-xs font-black uppercase text-center transition-all
                          {{ $type === 'xp' ? 'bg-rose-500 text-white shadow-lg shadow-rose-500/20' : 'text-slate-500 hover:text-white' }}">
                    XP
                </a>
                <a href="?type=workouts"
                   class="flex-1 py-2.5 rounded-xl text-xs font-black uppercase text-center transition-all
                          {{ $type === 'workouts' ? 'bg-rose-500 text-white shadow-lg shadow-rose-500/20' : 'text-slate-500 hover:text-white' }}">
                    Séances
                </a>
                <a href="?type=volume"
                   class="flex-1 py-2.5 rounded-xl text-xs font-black uppercase text-center transition-all
                          {{ $type === 'volume' ? 'bg-rose-500 text-white shadow-lg shadow-rose-500/20' : 'text-slate-500 hover:text-white' }}">
                    Volume
                </a>
            </div>

            {{-- Ma position --}}
            @php
                $currentUserIdx = null;
                foreach ($users as $i => $u) {
                    if ($u->id === auth()->id()) { $currentUserIdx = $i; break; }
                }
            @endphp

            @if($currentUserIdx !== null)
                <div class="bg-gradient-to-r from-rose-600 to-rose-800 rounded-[2rem] p-5 flex items-center justify-between">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 bg-white/20 rounded-2xl flex items-center justify-center font-black">
                            {{ substr(auth()->user()->name, 0, 1) }}
                        </div>
                        <div>
                            <p class="font-black text-sm uppercase">Ta position</p>
                            <p class="text-rose-200 text-[9px] font-bold uppercase tracking-wider">{{ auth()->user()->name }}</p>
                        </div>
                    </div>
                    <div class="text-right">
                        <p class="text-3xl font-black">#{{ $currentUserIdx + 1 }}</p>
                    </div>
                </div>
            @endif

            {{-- Classement --}}
            <div class="bg-[#111214] border border-slate-800 rounded-[2.5rem] overflow-hidden">
                @forelse($users as $index => $user)
                    <a href="{{ route('profile.public', $user) }}"
                       class="flex items-center gap-4 p-4 border-b border-slate-800/50 last:border-0
                              hover:bg-slate-900/50 transition-all
                              {{ $user->id === auth()->id() ? 'bg-rose-500/5' : '' }}">

                        {{-- Position --}}
                        <div class="w-10 h-10 flex-shrink-0 flex items-center justify-center">
                            @if($index === 0)
                                <span class="text-2xl">🥇</span>
                            @elseif($index === 1)
                                <span class="text-2xl">🥈</span>
                            @elseif($index === 2)
                                <span class="text-2xl">🥉</span>
                            @else
                                <span class="text-sm font-black text-slate-500">#{{ $index + 1 }}</span>
                            @endif
                        </div>

                        {{-- Avatar --}}
                        <div class="w-11 h-11 rounded-2xl overflow-hidden flex-shrink-0">
                            @if($user->avatar)
                                <img src="{{ asset('storage/' . $user->avatar) }}" class="w-full h-full object-cover">
                            @else
                                <div class="w-full h-full bg-gradient-to-br from-rose-500 to-rose-700 flex items-center justify-center font-black text-white text-sm">
                                    {{ substr($user->name, 0, 1) }}
                                </div>
                            @endif
                        </div>

                        {{-- Nom + rang --}}
                        <div class="flex-1 min-w-0">
                            <div class="flex items-center gap-2">
                                <p class="font-black text-sm truncate {{ $user->id === auth()->id() ? 'text-rose-400' : '' }}">
                                    {{ $user->name }}
                                    @if($user->id === auth()->id())
                                        <span class="text-[9px] font-bold text-rose-500/70"> (Toi)</span>
                                    @endif
                                </p>
                            </div>
                            <p class="text-[9px] font-bold uppercase {{ $user->rank['color'] }}">
                                {{ $user->rank['label'] }}
                            </p>
                        </div>

                        {{-- Score --}}
                        <div class="text-right flex-shrink-0">
                            @if($type === 'xp')
                                <p class="text-lg font-black text-amber-400">{{ number_format($user->xp) }}</p>
                                <p class="text-[9px] text-slate-600 font-bold">XP</p>
                            @elseif($type === 'workouts')
                                <p class="text-lg font-black text-blue-400">{{ $user->workouts_count ?? 0 }}</p>
                                <p class="text-[9px] text-slate-600 font-bold">Séances</p>
                            @elseif($type === 'volume')
                                <p class="text-lg font-black text-green-400">{{ round(($user->total_volume ?? 0) / 1000, 1) }}</p>
                                <p class="text-[9px] text-slate-600 font-bold">Tonnes</p>
                            @endif
                        </div>
                    </a>
                @empty
                    <div class="p-12 text-center">
                        <p class="text-slate-600 font-bold uppercase text-sm tracking-wider">Aucun utilisateur pour le moment</p>
                    </div>
                @endforelse
            </div>

        </div>
    </div>
</x-app-layout>
