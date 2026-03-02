<x-app-layout>
    <div class="min-h-screen bg-[#08090a] text-white pb-24">

        <div class="px-6 pt-12 pb-6">
            <div class="flex items-center justify-between mb-2">
                <div>
                    <p class="text-[10px] font-black text-rose-500 uppercase tracking-[0.3em] mb-1">Training Hub</p>
                    <h1 class="text-4xl font-black uppercase italic tracking-tighter leading-none">Entraînements</h1>
                </div>
                <div class="bg-rose-500 text-white text-xs font-black px-4 py-1.5 rounded-full shadow-lg shadow-rose-500/20 uppercase tracking-widest">
                    {{ $workouts->count() }} Sessions
                </div>
            </div>
        </div>

        <div class="px-5 space-y-8">

            @if(session('success'))
                <x-alert type="success">{{ session('success') }}</x-alert>
            @endif

            @if(session('share_url'))
                <div class="bg-rose-500/10 border border-rose-500/30 rounded-[2rem] p-5" x-data="{copied: false}">
                    <p class="text-[10px] font-black uppercase tracking-widest text-rose-400 mb-2">Lien de partage généré</p>
                    <div class="flex gap-2 items-center">
                        <input type="text" value="{{ session('share_url') }}" readonly
                               class="flex-1 bg-slate-900 border border-slate-700 rounded-xl px-4 py-2 text-xs text-slate-300 font-mono">
                        <button @click="navigator.clipboard.writeText('{{ session('share_url') }}'); copied = true; setTimeout(() => copied = false, 2000)"
                                class="px-4 py-2 bg-rose-500 text-white text-xs font-black rounded-xl active:scale-95 transition-all">
                            <span x-show="!copied">Copier</span>
                            <span x-show="copied">Copié !</span>
                        </button>
                    </div>
                </div>
            @endif

            <div class="relative group">
                <div class="absolute -inset-1 bg-gradient-to-r from-rose-500 to-rose-600 rounded-[3rem] blur opacity-25 group-hover:opacity-50 transition duration-1000"></div>
                <div class="relative bg-[#111214] border border-slate-800 p-8 rounded-[3rem] shadow-2xl">
                    <div class="flex items-center gap-3 mb-6">
                        <div class="p-3 bg-rose-500/10 rounded-2xl border border-rose-500/20">
                            <svg class="w-6 h-6 text-rose-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M12 4v16m8-8H4"/>
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-xl font-black uppercase tracking-tight">Nouvelle Séance</h3>
                            <p class="text-[9px] font-bold text-slate-500 uppercase tracking-widest">Créer un entraînement</p>
                        </div>
                    </div>

                    <form action="{{ route('workouts.store') }}" method="POST" class="flex flex-col md:flex-row gap-4">
                        @csrf
                        <input type="text"
                               name="title"
                               placeholder="Ex: Push Day 💪"
                               class="flex-1 rounded-2xl bg-[#08090a] border-2 border-slate-800 focus:border-rose-500 focus:ring-0 transition-all py-4 px-6 text-lg font-bold text-white placeholder-slate-600"
                               required>

                        <button type="submit"
                                class="bg-gradient-to-r from-rose-500 to-rose-600 hover:from-rose-600 hover:to-rose-700 text-white font-black px-10 py-4 rounded-2xl transition-all shadow-xl shadow-rose-500/20 hover:scale-[1.02] active:scale-95 flex items-center justify-center gap-2 uppercase tracking-wider">
                            <span>🚀</span>
                            <span>Démarrer</span>
                        </button>
                    </form>
                </div>
            </div>

            <div class="bg-[#111214] border border-slate-800 p-6 rounded-[3rem] shadow-xl">
                <div class="flex justify-between items-end mb-6">
                    <div>
                        <p class="text-[10px] font-black text-rose-500 uppercase tracking-[0.3em] mb-1">Base de données</p>
                        <h2 class="text-3xl font-black uppercase italic tracking-tighter leading-none">Catalogue</h2>
                    </div>
                    <div class="text-right">
                        <span class="block text-[10px] font-black text-slate-500 uppercase tracking-widest">{{ $exercises->count() }} Mouvements</span>
                        <span class="text-[9px] text-rose-500/50 font-bold uppercase italic">Disponibles</span>
                    </div>
                </div>

                <form action="{{ route('workouts.index') }}" method="GET" class="relative group">
                    <div class="absolute inset-y-0 left-5 flex items-center pointer-events-none">
                        <svg class="h-5 w-5 text-slate-600 group-focus-within:text-rose-500 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                        </svg>
                    </div>
                    <input type="text"
                           name="search"
                           value="{{ request('search') }}"
                           placeholder="RECHERCHER UN EXERCICE..."
                           class="w-full pl-14 pr-6 py-4 bg-[#08090a] border-2 border-slate-800 rounded-2xl focus:ring-2 focus:ring-rose-500/20 focus:border-rose-500 font-bold text-white placeholder-slate-600 transition-all">
                </form>

                @if(request('search'))
                    <div class="mt-4 pt-4 border-t border-slate-800">
                        <p class="text-[10px] font-bold text-slate-500 uppercase tracking-widest mb-3">Résultats de recherche</p>
                        <div class="space-y-2 max-h-64 overflow-y-auto">
                            @forelse($exercises as $exercise)
                                <div class="bg-[#08090a] p-3 rounded-2xl border border-slate-800 hover:border-rose-500/50 transition-all">
                                    <h4 class="font-black uppercase text-sm">{{ $exercise->name }}</h4>
                                    @if($exercise->targetMuscles)
                                        <p class="text-[9px] text-slate-500 font-bold mt-1">
                                            {{ is_array($exercise->targetMuscles) ? implode(', ', $exercise->targetMuscles) : $exercise->targetMuscles }}
                                        </p>
                                    @endif
                                </div>
                            @empty
                                <p class="text-center text-slate-600 font-bold text-sm py-4">Aucun exercice trouvé</p>
                            @endforelse
                        </div>
                    </div>
                @endif
            </div>

            <div class="space-y-4">
                <div class="flex justify-between items-center">
                    <h2 class="text-[10px] font-black uppercase text-slate-600 tracking-[0.4em]">Historique</h2>
                    <a href="{{ route('profile.history') }}" class="text-[10px] font-black text-rose-500 uppercase italic hover:text-rose-400 transition">Voir tout</a>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    @forelse($workouts as $workout)
                        <div class="group bg-[#111214] rounded-[2.5rem] overflow-hidden border border-slate-800 hover:border-rose-500/50 transition-all duration-500 shadow-lg hover:shadow-2xl hover:shadow-rose-500/10">

                            <div class="bg-gradient-to-br from-rose-500 to-rose-700 p-6 text-white relative overflow-hidden">
                                <div class="absolute top-0 right-0 w-32 h-32 bg-white/10 rounded-full blur-3xl"></div>
                                <div class="relative z-10">
                                    <div class="flex items-start justify-between mb-2">
                                        <h4 class="text-2xl font-black capitalize leading-tight truncate flex-1">
                                            {{ $workout->title }}
                                        </h4>
                                        <span class="text-[9px] font-black bg-black/20 px-2 py-1 rounded-lg uppercase tracking-wider">
                                            {{ $workout->trainingSets->groupBy('exercise_id')->count() }} Ex
                                        </span>
                                    </div>
                                    <p class="text-rose-100 text-xs font-bold">
                                        {{ $workout->created_at->translatedFormat('d F Y') }}
                                    </p>
                                    <p class="text-rose-200 text-[9px] font-bold mt-1">
                                        {{ $workout->created_at->diffForHumans() }}
                                    </p>
                                </div>
                            </div>

                            <div class="p-6 space-y-4">
                                <div class="grid grid-cols-2 gap-3">
                                    <div class="bg-[#08090a] p-3 rounded-2xl border border-slate-800">
                                        <p class="text-[9px] font-bold text-slate-500 uppercase tracking-widest mb-1">Séries</p>
                                        <p class="text-xl font-black">{{ $workout->trainingSets->count() }}</p>
                                    </div>
                                    <div class="bg-[#08090a] p-3 rounded-2xl border border-slate-800">
                                        <p class="text-[9px] font-bold text-slate-500 uppercase tracking-widest mb-1">Volume</p>
                                        <p class="text-xl font-black">{{ number_format($workout->trainingSets->sum(fn($s) => $s->weight * $s->reps), 0) }}<span class="text-xs text-slate-600">kg</span></p>
                                    </div>
                                </div>

                                <div class="flex gap-2">
                                    <a href="{{ route('workouts.show', $workout) }}"
                                       class="group/btn flex-1 inline-flex items-center justify-center px-4 py-4 font-black transition-all duration-200 bg-white text-black rounded-2xl hover:bg-rose-500 hover:text-white active:scale-95 text-sm">
                                        VOIR DÉTAILS
                                    </a>
                                    @if($workout->status === 'completed')
                                        @if($workout->share_token)
                                            <a href="{{ route('workouts.shared', $workout->share_token) }}"
                                               target="_blank"
                                               class="w-14 inline-flex items-center justify-center rounded-2xl bg-slate-800 border border-slate-700 hover:border-rose-500/50 transition-all"
                                               title="Voir le lien public">
                                                <svg class="w-5 h-5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1"/></svg>
                                            </a>
                                        @else
                                            <form action="{{ route('workouts.share', $workout) }}" method="POST">
                                                @csrf
                                                <button type="submit"
                                                        class="w-14 h-full inline-flex items-center justify-center rounded-2xl bg-slate-800 border border-slate-700 hover:border-rose-500/50 transition-all"
                                                        title="Générer un lien de partage">
                                                    <svg class="w-5 h-5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.684 13.342C8.886 12.938 9 12.482 9 12c0-.482-.114-.938-.316-1.342m0 2.684a3 3 0 110-2.684m0 2.684l6.632 3.316m-6.632-6l6.632-3.316m0 0a3 3 0 105.367-2.684 3 3 0 00-5.367 2.684zm0 9.316a3 3 0 105.368 2.684 3 3 0 00-5.368-2.684z"/></svg>
                                                </button>
                                            </form>
                                        @endif
                                    @endif
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="col-span-full">
                            <div class="bg-[#111214] border border-slate-800 rounded-[3rem] p-12 text-center">
                                <div class="w-20 h-20 bg-rose-500/10 rounded-full flex items-center justify-center mx-auto mb-4">
                                    <svg class="w-10 h-10 text-rose-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                                    </svg>
                                </div>
                                <p class="text-slate-400 font-bold uppercase text-sm tracking-wider">Aucun entraînement pour le moment</p>
                                <p class="text-slate-600 text-xs mt-2">Crée ta première séance ci-dessus pour commencer</p>
                            </div>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>

</x-app-layout>
