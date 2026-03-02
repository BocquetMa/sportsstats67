<x-app-layout>
    <div class="min-h-screen bg-[#08090a] text-white pb-28">

        <div class="px-6 pt-12 pb-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-[10px] font-black text-rose-500 uppercase tracking-[0.3em] mb-1">Base de données</p>
                    <h1 class="text-4xl font-black uppercase italic tracking-tighter leading-none">Exercices</h1>
                </div>
                <span class="bg-rose-500 text-white text-xs font-black px-4 py-1.5 rounded-full uppercase tracking-widest">
                    {{ $exercises->total() }}
                </span>
            </div>
        </div>

        <div class="px-5 space-y-6">

            {{-- Barre de recherche --}}
            <form action="{{ route('exercises.index') }}" method="GET">
                <div class="relative group">
                    <div class="absolute inset-y-0 left-5 flex items-center pointer-events-none">
                        <svg class="h-5 w-5 text-slate-600 group-focus-within:text-rose-500 transition-colors"
                             fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3"
                                  d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                        </svg>
                    </div>
                    <input type="text" name="search" value="{{ request('search') }}"
                           placeholder="Muscle, équipement, nom..."
                           class="w-full pl-14 pr-6 py-4 bg-[#111214] border-2 border-slate-800 rounded-2xl focus:ring-2 focus:ring-rose-500/20 focus:border-rose-500 font-bold text-white placeholder-slate-600 transition-all">
                    @if(request('search'))
                        <a href="{{ route('exercises.index') }}"
                           class="absolute inset-y-0 right-5 flex items-center text-slate-500 hover:text-white transition">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                            </svg>
                        </a>
                    @endif
                </div>
            </form>

            @if(request('search'))
                <p class="text-[10px] font-bold text-slate-500 uppercase tracking-widest px-1">
                    {{ $exercises->total() }} résultat{{ $exercises->total() > 1 ? 's' : '' }} pour
                    "<span class="text-white">{{ request('search') }}</span>"
                </p>
            @endif

            {{-- Grille d'exercices --}}
            <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">
                @forelse($exercises as $exercise)
                    <div class="group bg-[#111214] border border-slate-800 rounded-[2rem] overflow-hidden hover:border-rose-500/40 transition-all duration-300">

                        {{-- Image --}}
                        <div class="aspect-square bg-slate-900 relative overflow-hidden">
                            @if($exercise->gifUrl)
                                <img src="{{ $exercise->gifUrl }}"
                                     alt="{{ $exercise->name }}"
                                     class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500"
                                     loading="lazy">
                            @else
                                <div class="w-full h-full flex items-center justify-center text-slate-700">
                                    <svg class="w-12 h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                                    </svg>
                                </div>
                            @endif
                            <div class="absolute inset-0 bg-gradient-to-t from-[#111214] to-transparent opacity-40"></div>

                            {{-- Badge muscle cible --}}
                            @if($exercise->targetMuscles)
                                <div class="absolute top-2 left-2">
                                    <span class="text-[8px] font-black bg-rose-500/80 text-white px-2 py-0.5 rounded-lg uppercase tracking-wider backdrop-blur-sm">
                                        {{ is_array($exercise->targetMuscles) ? $exercise->targetMuscles[0] : $exercise->targetMuscles }}
                                    </span>
                                </div>
                            @endif
                        </div>

                        {{-- Infos --}}
                        <div class="p-3">
                            <h3 class="font-black text-sm capitalize leading-tight line-clamp-2 group-hover:text-rose-400 transition-colors">
                                {{ $exercise->name }}
                            </h3>

                            @if($exercise->equipments)
                                <p class="text-[9px] font-bold text-slate-600 uppercase mt-1 truncate">
                                    {{ is_array($exercise->equipments) ? $exercise->equipments[0] : $exercise->equipments }}
                                </p>
                            @endif

                            @if($exercise->targetMuscles && is_array($exercise->targetMuscles) && count($exercise->targetMuscles) > 1)
                                <div class="flex flex-wrap gap-1 mt-2">
                                    @foreach(array_slice($exercise->targetMuscles, 0, 2) as $muscle)
                                        <span class="text-[8px] font-bold bg-slate-800 text-slate-400 px-2 py-0.5 rounded-lg">
                                            {{ $muscle }}
                                        </span>
                                    @endforeach
                                </div>
                            @endif
                        </div>
                    </div>
                @empty
                    <div class="col-span-full">
                        <div class="bg-[#111214] border border-slate-800 rounded-[2.5rem] p-12 text-center">
                            <p class="text-slate-500 font-bold uppercase text-sm">Aucun exercice trouvé</p>
                            @if(request('search'))
                                <a href="{{ route('exercises.index') }}"
                                   class="inline-block mt-4 px-6 py-3 bg-rose-500/10 border border-rose-500/30 text-rose-400 text-xs font-black uppercase rounded-xl hover:bg-rose-500 hover:text-white transition-all">
                                    Voir tous les exercices
                                </a>
                            @endif
                        </div>
                    </div>
                @endforelse
            </div>

            {{-- Pagination --}}
            @if($exercises->hasPages())
                <div class="flex justify-center gap-2">
                    @if($exercises->onFirstPage())
                        <span class="px-4 py-2 bg-slate-800 text-slate-600 rounded-xl text-sm font-bold cursor-not-allowed">
                            ← Préc.
                        </span>
                    @else
                        <a href="{{ $exercises->previousPageUrl() }}"
                           class="px-4 py-2 bg-[#111214] border border-slate-800 text-white rounded-xl text-sm font-bold hover:border-rose-500/50 transition-all">
                            ← Préc.
                        </a>
                    @endif

                    <span class="px-4 py-2 bg-rose-500 text-white rounded-xl text-sm font-black">
                        {{ $exercises->currentPage() }} / {{ $exercises->lastPage() }}
                    </span>

                    @if($exercises->hasMorePages())
                        <a href="{{ $exercises->nextPageUrl() }}"
                           class="px-4 py-2 bg-[#111214] border border-slate-800 text-white rounded-xl text-sm font-bold hover:border-rose-500/50 transition-all">
                            Suiv. →
                        </a>
                    @else
                        <span class="px-4 py-2 bg-slate-800 text-slate-600 rounded-xl text-sm font-bold cursor-not-allowed">
                            Suiv. →
                        </span>
                    @endif
                </div>
            @endif

        </div>
    </div>
</x-app-layout>
