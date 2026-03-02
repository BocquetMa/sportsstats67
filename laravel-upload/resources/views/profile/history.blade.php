<x-app-layout>
    <div class="min-h-screen bg-[#08090a] text-white pb-28">

        {{-- Header --}}
        <div class="px-6 pt-12 pb-6">
            <p class="text-[10px] font-black text-rose-500 uppercase tracking-[0.3em] mb-1">Mon Parcours</p>
            <h1 class="text-4xl font-black uppercase italic tracking-tighter leading-none">Historique</h1>
        </div>

        <div class="px-5 space-y-6">

            {{-- Stats globales --}}
            <div class="grid grid-cols-3 gap-3">
                <div class="bg-[#111214] border border-slate-800/50 p-4 rounded-[2rem] text-center">
                    <p class="text-2xl font-black text-rose-500">{{ $completedCount }}</p>
                    <p class="text-[9px] font-bold uppercase text-slate-500 mt-1 tracking-widest">Séances</p>
                </div>
                <div class="bg-[#111214] border border-slate-800/50 p-4 rounded-[2rem] text-center">
                    <p class="text-2xl font-black text-blue-400">{{ number_format($totalVolume / 1000, 1) }}</p>
                    <p class="text-[9px] font-bold uppercase text-slate-500 mt-1 tracking-widest">Tonnes</p>
                </div>
                <div class="bg-[#111214] border border-slate-800/50 p-4 rounded-[2rem] text-center">
                    <p class="text-2xl font-black text-green-400">{{ $totalSets }}</p>
                    <p class="text-[9px] font-bold uppercase text-slate-500 mt-1 tracking-widest">Séries</p>
                </div>
            </div>

            {{-- Carte poids --}}
            @if($latest)
                <div class="relative overflow-hidden bg-gradient-to-br from-rose-600 to-rose-800 rounded-[2.5rem] p-6 shadow-2xl shadow-rose-900/20">
                    <div class="flex justify-between items-center relative z-10">
                        <div>
                            <p class="text-rose-200 text-[10px] font-black uppercase tracking-[0.2em] mb-1">Poids Actuel</p>
                            <div class="flex items-baseline gap-2">
                                <span class="text-5xl font-black tracking-tighter">{{ $latest->weight }}</span>
                                <span class="text-xl font-bold opacity-60 italic">kg</span>
                            </div>
                            @if($weightDiff != 0)
                                <div class="mt-3 flex items-center gap-2 bg-black/20 w-fit px-3 py-1 rounded-full border border-white/10">
                                    <svg class="w-3 h-3 {{ $weightDiff <= 0 ? 'rotate-180 text-green-300' : 'text-orange-300' }}"
                                         fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 10l7-7m0 0l7 7m-7-7v18"/>
                                    </svg>
                                    <span class="text-[10px] font-black uppercase">{{ $weightDiff >= 0 ? '+' : '' }}{{ $weightDiff }} kg</span>
                                </div>
                            @endif
                        </div>
                        <div class="text-right">
                            <p class="text-rose-200 text-[9px] font-bold uppercase">Objectif</p>
                            <p class="font-black text-sm">{{ $latest->objective ?? 'Non défini' }}</p>
                        </div>
                    </div>

                    {{-- Mini chart poids --}}
                    @if($metrics->count() > 1)
                        <div class="flex items-end gap-1.5 h-12 mt-4 relative z-10 opacity-50">
                            @foreach($metrics as $m)
                                @php $h = $m->weight > 0 ? min(100, max(10, ($m->weight / 150) * 100)) : 10; @endphp
                                <div class="flex-1 bg-white/30 rounded-t-sm transition-all"
                                     style="height: {{ $h }}%"
                                     title="{{ $m->weight }} kg"></div>
                            @endforeach
                        </div>
                    @endif

                    <div class="absolute -bottom-10 -right-10 w-40 h-40 bg-white/10 rounded-full blur-3xl"></div>
                </div>
            @else
                <div class="bg-[#111214] border border-slate-800 rounded-[2.5rem] p-8 text-center">
                    <p class="text-slate-500 font-bold text-sm">Aucune mesure corporelle enregistrée</p>
                    <a href="{{ route('dashboard') }}"
                       class="inline-block mt-4 px-6 py-3 bg-rose-500 text-white font-black text-xs uppercase rounded-2xl hover:bg-rose-600 transition-all">
                        Ajouter mon poids
                    </a>
                </div>
            @endif

            {{-- Historique des séances --}}
            <div>
                <div class="flex justify-between items-center mb-4">
                    <h2 class="text-[10px] font-black uppercase text-slate-600 tracking-[0.4em]">Toutes les Séances</h2>
                    <a href="{{ route('workouts.index') }}"
                       class="text-[10px] font-black text-rose-500 uppercase italic hover:text-rose-400 transition">
                        Nouvelle +
                    </a>
                </div>

                @forelse($workouts as $workout)
                    @php
                        $volume = $workout->trainingSets->sum(fn($s) => $s->weight * $s->reps);
                        $exercisesCount = $workout->trainingSets->groupBy('exercise_id')->count();
                        $setsCount = $workout->trainingSets->count();
                    @endphp
                    <a href="{{ route('workouts.show', $workout) }}"
                       class="group flex items-center gap-4 bg-[#111214] border border-slate-800/50 p-4 rounded-[2rem] mb-3 hover:border-rose-500/40 hover:bg-slate-900/50 transition-all duration-300 block">

                        {{-- Date --}}
                        <div class="w-12 h-12 flex-shrink-0 bg-slate-900 border border-slate-800 rounded-2xl flex flex-col items-center justify-center">
                            <span class="text-[10px] font-bold text-slate-500 uppercase leading-none">
                                {{ $workout->created_at->translatedFormat('M') }}
                            </span>
                            <span class="text-lg font-black leading-none text-white">
                                {{ $workout->created_at->format('d') }}
                            </span>
                        </div>

                        {{-- Infos --}}
                        <div class="flex-1 min-w-0">
                            <div class="flex items-center gap-2">
                                <h4 class="font-black uppercase text-sm truncate group-hover:text-rose-500 transition-colors">
                                    {{ $workout->title }}
                                </h4>
                                @if($workout->status === 'completed')
                                    <span class="flex-shrink-0 w-2 h-2 bg-green-500 rounded-full"></span>
                                @elseif($workout->status === 'in_progress')
                                    <span class="flex-shrink-0 text-[8px] font-black text-amber-500 bg-amber-500/10 px-1.5 py-0.5 rounded-md uppercase">En cours</span>
                                @endif
                            </div>
                            <div class="flex items-center gap-3 mt-1">
                                @if($exercisesCount > 0)
                                    <span class="text-[9px] font-bold text-slate-500">{{ $exercisesCount }} ex.</span>
                                @endif
                                @if($setsCount > 0)
                                    <span class="text-[9px] font-bold text-slate-500">{{ $setsCount }} séries</span>
                                @endif
                                @if($volume > 0)
                                    <span class="text-[9px] font-bold text-slate-500">{{ number_format($volume, 0, '.', ' ') }} kg</span>
                                @endif
                                <span class="text-[9px] font-bold text-slate-600">{{ $workout->created_at->diffForHumans() }}</span>
                            </div>
                        </div>

                        {{-- Flèche --}}
                        <svg class="w-5 h-5 text-slate-700 flex-shrink-0 group-hover:text-rose-500 transition-colors"
                             fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M9 5l7 7-7 7"/>
                        </svg>
                    </a>
                @empty
                    <div class="bg-[#111214] border border-slate-800 rounded-[2.5rem] p-12 text-center">
                        <div class="w-16 h-16 bg-rose-500/10 rounded-full flex items-center justify-center mx-auto mb-4">
                            <svg class="w-8 h-8 text-rose-500/50" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                            </svg>
                        </div>
                        <p class="text-slate-500 font-bold uppercase text-sm">Aucune séance enregistrée</p>
                        <a href="{{ route('workouts.index') }}"
                           class="inline-block mt-4 px-6 py-3 bg-rose-500 text-white font-black text-xs uppercase rounded-2xl hover:bg-rose-600 transition-all active:scale-95">
                            Commencer maintenant
                        </a>
                    </div>
                @endforelse
            </div>

        </div>
    </div>
</x-app-layout>
