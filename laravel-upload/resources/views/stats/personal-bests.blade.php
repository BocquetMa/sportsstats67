<x-app-layout>
    <div class="min-h-screen bg-[#08090a] text-white pb-28">

        <div class="px-6 pt-12 pb-6">
            <p class="text-[10px] font-black text-rose-500 uppercase tracking-[0.3em] mb-1">Records</p>
            <h1 class="text-4xl font-black uppercase italic tracking-tighter leading-none">Personal <span class="text-transparent bg-clip-text bg-gradient-to-r from-rose-500 to-orange-400">Bests</span></h1>
        </div>

        <div class="px-5 space-y-4">

            @if($personalBests->isEmpty())
                <div class="bg-[#111214] border border-slate-800 rounded-[2.5rem] p-12 text-center">
                    <p class="text-slate-500 font-bold uppercase text-sm tracking-wider">Aucun record enregistré</p>
                    <p class="text-slate-600 text-xs mt-2">Complète des séances pour voir tes records ici</p>
                    <a href="{{ route('workouts.index') }}"
                       class="inline-block mt-6 px-6 py-3 bg-rose-500 text-white text-xs font-black uppercase rounded-2xl hover:bg-rose-600 transition-all">
                        Commencer une séance
                    </a>
                </div>
            @else
                <div class="bg-[#111214] border border-slate-800/50 rounded-[2.5rem] overflow-hidden">
                    @foreach($personalBests as $index => $pb)
                        <div class="flex items-center gap-4 p-4 border-b border-slate-800/50 last:border-0">

                            {{-- Rang --}}
                            <div class="w-10 h-10 flex-shrink-0 flex items-center justify-center">
                                @if($index === 0)
                                    <span class="text-2xl">🥇</span>
                                @elseif($index === 1)
                                    <span class="text-2xl">🥈</span>
                                @elseif($index === 2)
                                    <span class="text-2xl">🥉</span>
                                @else
                                    <span class="text-sm font-black text-slate-600">#{{ $index + 1 }}</span>
                                @endif
                            </div>

                            {{-- Nom de l'exercice --}}
                            <div class="flex-1 min-w-0">
                                <p class="font-black text-sm capitalize truncate">
                                    {{ $pb['exercise_name'] ?? 'Exercice inconnu' }}
                                </p>
                                <p class="text-[9px] font-bold text-slate-600 uppercase tracking-widest mt-0.5">
                                    Vol. max: {{ number_format($pb['max_volume']) }} kg
                                </p>
                            </div>

                            {{-- Poids max --}}
                            <div class="text-right flex-shrink-0">
                                <p class="text-xl font-black text-rose-500">{{ $pb['max_weight'] }}</p>
                                <p class="text-[9px] text-slate-600 font-bold uppercase">kg max</p>
                            </div>
                        </div>
                    @endforeach
                </div>

                <a href="{{ route('evolution.index') }}"
                   class="flex items-center justify-center gap-2 py-4 bg-[#111214] border border-slate-800 rounded-2xl font-black text-xs uppercase tracking-wider text-slate-400 hover:border-rose-500/50 hover:text-rose-500 transition-all">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/>
                    </svg>
                    Voir l'évolution complète
                </a>
            @endif

        </div>
    </div>
</x-app-layout>
