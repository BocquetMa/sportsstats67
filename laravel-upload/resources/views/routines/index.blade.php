<x-app-layout>
    <div class="min-h-screen bg-[#08090a] text-white pb-24">

        <div class="px-6 pt-12 pb-6">
            <div class="flex items-center justify-between mb-2">
                <div>
                    <p class="text-[10px] font-black text-rose-500 uppercase tracking-[0.3em] mb-1">Programmes</p>
                    <h1 class="text-4xl font-black uppercase italic tracking-tighter leading-none">Mes Routines</h1>
                </div>
                <a href="{{ route('routines.create') }}"
                   class="bg-rose-500 hover:bg-rose-600 text-white text-xs font-black px-6 py-3 rounded-2xl shadow-lg shadow-rose-500/20 uppercase tracking-widest transition-all active:scale-95">
                    + Créer
                </a>
            </div>
        </div>

        <div class="px-5 space-y-6" x-data="{ shareModal: null, shareCode: '' }">

            {{-- Import routine partagée --}}
            <div class="bg-[#111214] border border-slate-800 p-6 rounded-[3rem] shadow-xl">
                <h2 class="text-sm font-black uppercase mb-1">Importer une Routine</h2>
                <p class="text-[10px] text-slate-500 font-bold uppercase tracking-wider mb-4">Entrez un code de partage</p>
                <form action="{{ route('routines.accept-share') }}" method="POST" class="flex gap-3">
                    @csrf
                    <input type="text"
                           name="share_code"
                           placeholder="EX: AB12CD34"
                           class="flex-1 rounded-2xl bg-[#08090a] border-2 border-slate-800 focus:border-rose-500 focus:ring-0 transition-all py-3 px-5 text-sm font-bold text-white placeholder-slate-600 uppercase"
                           required>
                    <button type="submit"
                            class="bg-white text-black font-black px-8 py-3 rounded-2xl hover:bg-rose-500 hover:text-white transition-all active:scale-95">
                        Importer
                    </button>
                </form>
            </div>

            {{-- Liste des routines --}}
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                @forelse($routines as $routine)
                    <div class="group bg-[#111214] rounded-[2.5rem] border border-slate-800 hover:border-rose-500/50 transition-all duration-500 shadow-lg overflow-hidden">

                        <div class="bg-gradient-to-br from-rose-500 to-rose-700 p-6 text-white relative overflow-hidden">
                            <div class="absolute top-0 right-0 w-32 h-32 bg-white/10 rounded-full blur-3xl"></div>
                            <div class="relative z-10">
                                <div class="flex items-start justify-between mb-2">
                                    <h3 class="text-2xl font-black uppercase leading-tight flex-1 pr-2">
                                        {{ $routine->name }}
                                    </h3>
                                    <div class="flex flex-col gap-1 flex-shrink-0 items-end">
                                        @if($routine->is_public)
                                            <span class="text-[9px] font-black bg-black/20 px-2 py-1 rounded-lg uppercase tracking-wider">
                                                🌍 Public
                                            </span>
                                        @endif
                                        @if($routine->is_shared_copy)
                                            <span class="text-[9px] font-black bg-black/20 px-2 py-1 rounded-lg uppercase tracking-wider">
                                                📥 Copie
                                            </span>
                                        @endif
                                    </div>
                                </div>
                                <p class="text-rose-100 text-xs font-bold">
                                    {{ $routine->days_count }} {{ Str::plural('jour', $routine->days_count) }} planifié{{ $routine->days_count > 1 ? 's' : '' }}
                                </p>
                            </div>
                        </div>

                        <div class="p-6 space-y-4">
                            {{-- Jours de la semaine --}}
                            <div class="space-y-2">
                                @forelse($routine->days as $day)
                                    <div class="bg-[#08090a] p-3 rounded-2xl border border-slate-800 flex items-center justify-between">
                                        <div class="flex items-center gap-3">
                                            <span class="w-8 h-8 bg-rose-500/10 rounded-lg flex items-center justify-center text-rose-500 font-black text-xs">
                                                {{ substr($day->day_name, 0, 2) }}
                                            </span>
                                            <div>
                                                <h4 class="font-black text-sm">{{ $day->name ?? $day->day_name }}</h4>
                                                <p class="text-[9px] text-slate-500 font-bold">{{ $day->exercises->count() }} exercice{{ $day->exercises->count() > 1 ? 's' : '' }}</p>
                                            </div>
                                        </div>
                                        @if($day->day_of_week === \App\Models\RoutineDay::getTodayDay())
                                            <span class="text-[9px] font-black text-rose-500 bg-rose-500/10 px-2 py-1 rounded-lg animate-pulse">
                                                AUJOURD'HUI
                                            </span>
                                        @endif
                                    </div>
                                @empty
                                    <p class="text-center text-slate-600 text-sm py-4 border-2 border-dashed border-slate-800 rounded-2xl">
                                        Aucun jour planifié
                                    </p>
                                @endforelse
                            </div>

                            {{-- Actions --}}
                            <div class="grid grid-cols-2 gap-3">
                                <a href="{{ route('routines.edit', $routine) }}"
                                   class="py-3 px-4 bg-white text-black rounded-2xl font-black text-xs uppercase text-center hover:bg-rose-500 hover:text-white transition-all active:scale-95">
                                    ⚙️ Éditer
                                </a>

                                @if($routine->getTodayWorkout())
                                    <form action="{{ route('routines.start-today', $routine) }}" method="POST">
                                        @csrf
                                        <button type="submit"
                                                class="w-full py-3 px-4 bg-rose-500 text-white rounded-2xl font-black text-xs uppercase hover:bg-rose-600 transition-all active:scale-95">
                                            ▶️ Démarrer
                                        </button>
                                    </form>
                                @else
                                    <button disabled
                                            class="py-3 px-4 bg-slate-800 text-slate-600 rounded-2xl font-black text-xs uppercase cursor-not-allowed"
                                            title="Aucune séance planifiée aujourd'hui">
                                        Pas aujourd'hui
                                    </button>
                                @endif
                            </div>

                            {{-- Partage --}}
                            <button @click="shareModal = {{ $routine->id }}"
                                    class="w-full py-3 px-4 border-2 border-slate-800 rounded-2xl font-black text-xs uppercase hover:border-rose-500 hover:text-rose-500 transition-all">
                                🔗 Partager cette routine
                            </button>
                        </div>
                    </div>
                @empty
                    <div class="col-span-full">
                        <div class="bg-[#111214] border border-slate-800 rounded-[3rem] p-12 text-center">
                            <div class="w-20 h-20 bg-rose-500/10 rounded-full flex items-center justify-center mx-auto mb-4">
                                <svg class="w-10 h-10 text-rose-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                </svg>
                            </div>
                            <p class="text-slate-400 font-bold uppercase text-sm tracking-wider">Aucune routine créée</p>
                            <p class="text-slate-600 text-xs mt-2 mb-6">Crée ton premier programme d'entraînement</p>
                            <a href="{{ route('routines.create') }}"
                               class="inline-block px-8 py-4 bg-rose-500 text-white font-black text-sm uppercase rounded-2xl hover:bg-rose-600 transition-all active:scale-95">
                                Créer une routine
                            </a>
                        </div>
                    </div>
                @endforelse
            </div>

        </div>

        {{-- Modal de partage --}}
        <div x-show="shareModal !== null"
             x-cloak
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             @click.self="shareModal = null"
             class="fixed inset-0 bg-black/80 backdrop-blur-xl z-50 flex items-end justify-center p-6">

            @foreach($routines as $routine)
                <div x-show="shareModal === {{ $routine->id }}"
                     x-transition:enter="transition ease-out duration-300"
                     x-transition:enter-start="opacity-0 translate-y-8"
                     x-transition:enter-end="opacity-100 translate-y-0"
                     class="bg-[#111214] border border-slate-800 rounded-[3rem] p-8 w-full max-w-sm shadow-2xl">

                    <div class="flex justify-between items-center mb-6">
                        <h3 class="text-xl font-black uppercase italic">Partager</h3>
                        <button @click="shareModal = null" class="text-slate-500 hover:text-white transition">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                        </button>
                    </div>

                    <p class="text-sm font-bold text-slate-400 mb-6">{{ $routine->name }}</p>

                    <form action="{{ route('routines.share', $routine) }}" method="POST" class="space-y-4">
                        @csrf
                        <input type="hidden" name="share_type" value="link">

                        <div>
                            <label class="block text-[10px] font-black uppercase tracking-widest text-slate-500 mb-2">Durée du lien</label>
                            <select name="expires_days"
                                    class="w-full bg-[#08090a] border-2 border-slate-800 rounded-2xl px-4 py-3 text-white font-bold focus:border-rose-500 focus:ring-0">
                                <option value="7">7 jours</option>
                                <option value="30" selected>30 jours</option>
                                <option value="365">1 an</option>
                            </select>
                        </div>

                        <button type="submit"
                                class="w-full py-4 bg-rose-500 text-white rounded-2xl font-black uppercase text-sm hover:bg-rose-600 transition-all active:scale-95">
                            Générer un code
                        </button>
                    </form>
                </div>
            @endforeach
        </div>

    </div>
</x-app-layout>
