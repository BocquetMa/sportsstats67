<x-app-layout>
@php
    $allDayExercises = [];
    $allDayNames     = [];
    foreach ($days as $dayKey => $dayName) {
        $routineDay = $routine->days->firstWhere('day_of_week', $dayKey);
        $allDayExercises[$dayKey] = $routineDay
            ? $routineDay->exercises->map(fn($ex) => [
                'exercise_id' => $ex->id,
                'name'        => $ex->name,
                'sets_count'  => $ex->pivot->sets_count ?? 3,
                'target_reps' => $ex->pivot->target_reps ?? 10,
                'rest_time'   => $ex->pivot->rest_time ?? 90,
            ])->values()->toArray()
            : [];
        $allDayNames[$dayKey] = $routineDay?->name ?? '';
    }
@endphp

    <div class="min-h-screen bg-[#08090a] text-white pb-24"
         x-data="routineEditor({{ json_encode($allDayExercises) }}, {{ json_encode($allDayNames) }}, {{ $routine->id }})">

        <div class="px-6 pt-12 pb-6">
            <a href="{{ route('routines.index') }}" class="inline-flex items-center text-slate-400 hover:text-white transition mb-4">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                </svg>
                Retour
            </a>
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-[10px] font-black text-rose-500 uppercase tracking-[0.3em] mb-1">Planification</p>
                    <h1 class="text-4xl font-black uppercase italic tracking-tighter leading-none">{{ $routine->name }}</h1>
                </div>
                <form action="{{ route('routines.destroy', $routine) }}" method="POST" onsubmit="return confirm('Supprimer cette routine ?')">
                    @csrf @method('DELETE')
                    <button type="submit" class="text-red-400 hover:text-red-300 text-xs font-black uppercase">
                        🗑️ Supprimer
                    </button>
                </form>
            </div>
        </div>

        <div class="px-5 space-y-6">

            {{-- Paramètres --}}
            <div class="bg-[#111214] border border-slate-800 p-6 rounded-[3rem]">
                <h2 class="text-lg font-black uppercase mb-4">Paramètres de la Routine</h2>
                <form action="{{ route('routines.update', $routine) }}" method="POST" class="flex gap-4">
                    @csrf @method('PUT')
                    <input type="text" name="name" value="{{ $routine->name }}"
                           class="flex-1 rounded-2xl bg-[#08090a] border-2 border-slate-800 focus:border-rose-500 focus:ring-0 py-3 px-5 text-white font-bold" required>
                    <button type="submit" class="bg-rose-500 hover:bg-rose-600 text-white font-black px-8 py-3 rounded-2xl transition-all active:scale-95">
                        Sauvegarder
                    </button>
                </form>
            </div>

            {{-- Grille des jours --}}
            <div class="grid grid-cols-7 gap-2">
                @foreach($days as $dayKey => $dayName)
                    @php
                        $routineDay = $routine->days->firstWhere('day_of_week', $dayKey);
                        $isToday    = $dayKey === \App\Models\RoutineDay::getTodayDay();
                    @endphp
                    <button @click="selectDay('{{ $dayKey }}')"
                            :class="selectedDay === '{{ $dayKey }}' ? 'border-rose-500 bg-rose-500/10 text-white' : 'border-slate-800 hover:border-slate-700 text-slate-400'"
                            class="group relative py-3 px-1 rounded-2xl border-2 transition-all text-center">
                        @if($isToday)
                            <span class="absolute -top-1.5 -right-1.5 w-3 h-3 bg-rose-500 rounded-full animate-pulse"></span>
                        @endif
                        <div class="text-[9px] font-black uppercase tracking-wider mb-1">
                            {{ substr($dayName, 0, 3) }}
                        </div>
                        <div class="text-lg font-black"
                             :class="(dayExercises['{{ $dayKey }}'] || []).length > 0 ? 'text-rose-400' : 'opacity-20'"
                             x-text="(dayExercises['{{ $dayKey }}'] || []).length > 0 ? (dayExercises['{{ $dayKey }}'] || []).length : '·'">
                        </div>
                    </button>
                @endforeach
            </div>

            {{-- Panel de chaque jour --}}
            @foreach($days as $dayKey => $dayName)
                <div x-show="selectedDay === '{{ $dayKey }}'"
                     x-transition:enter="transition ease-out duration-200"
                     x-transition:enter-start="opacity-0 translate-y-2"
                     x-transition:enter-end="opacity-100 translate-y-0"
                     class="bg-[#111214] border border-slate-800 rounded-[3rem] p-6">

                    <div class="flex items-center justify-between mb-6">
                        <h2 class="text-2xl font-black uppercase italic">{{ $dayName }}</h2>
                        <button @click="selectedDay = null" class="text-slate-500 hover:text-white p-2">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                            </svg>
                        </button>
                    </div>

                    <div class="space-y-5">
                        <input type="text"
                               x-model="dayNames['{{ $dayKey }}']"
                               placeholder="Nom de la séance (ex: Jambes Push)..."
                               class="w-full rounded-2xl bg-[#08090a] border-2 border-slate-800 focus:border-rose-500 focus:ring-0 py-3 px-5 text-white font-bold">

                        {{-- Recherche exercice --}}
                        <div>
                            <label class="block text-[10px] font-black text-slate-500 uppercase tracking-widest mb-3">Exercices</label>

                            <div class="relative mb-3">
                                <input type="text"
                                       x-model="searchQuery"
                                       @input="fetchExercises(searchQuery)"
                                       @keydown.escape="searchQuery = ''; searchResults = []"
                                       placeholder="Rechercher un exercice..."
                                       autocomplete="off"
                                       class="w-full rounded-2xl bg-[#08090a] border-2 border-slate-800 focus:border-rose-500 focus:ring-0 py-3 px-5 text-white font-bold pr-12">

                                <div class="absolute right-4 top-1/2 -translate-y-1/2 pointer-events-none">
                                    <svg x-show="!searching" class="w-4 h-4 text-slate-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                                    </svg>
                                    <div x-show="searching" class="w-4 h-4 border-2 border-rose-500 border-t-transparent rounded-full animate-spin"></div>
                                </div>

                                <div x-show="searchQuery.length >= 2"
                                     @click.outside="searchResults = []; searchQuery = ''"
                                     class="absolute top-full left-0 right-0 mt-2 bg-[#0d0e10] border-2 border-slate-800 rounded-2xl max-h-60 overflow-y-auto z-50 shadow-2xl">

                                    <template x-if="!searching && searchResults.length === 0">
                                        <div class="p-5 text-center">
                                            <p class="text-slate-500 text-sm">Aucun exercice trouvé pour "<span x-text="searchQuery" class="text-white"></span>"</p>
                                        </div>
                                    </template>

                                    <template x-for="ex in searchResults" :key="ex.id">
                                        <button type="button"
                                                @mousedown.prevent="addExercise('{{ $dayKey }}', ex.id, ex.name)"
                                                class="w-full text-left px-4 py-3 hover:bg-slate-800/80 transition-colors border-b border-slate-800/50 last:border-0 flex items-center gap-3">
                                            <div class="w-8 h-8 rounded-lg bg-rose-500/10 flex-shrink-0 flex items-center justify-center">
                                                <svg class="w-4 h-4 text-rose-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                                                </svg>
                                            </div>
                                            <div>
                                                <p class="font-black text-sm" x-text="ex.name"></p>
                                                <p class="text-[9px] text-slate-500 font-bold mt-0.5"
                                                   x-text="Array.isArray(ex.targetMuscles) ? ex.targetMuscles.join(', ') : (ex.targetMuscles || '')"></p>
                                            </div>
                                        </button>
                                    </template>
                                </div>
                            </div>

                            {{-- Liste des exercices --}}
                            <div class="space-y-2">
                                <template x-for="(exercise, index) in (dayExercises['{{ $dayKey }}'] || [])" :key="index">
                                    <div class="bg-[#08090a] p-4 rounded-2xl border border-slate-800">
                                        <div class="flex items-center justify-between mb-3">
                                            <h4 class="font-black uppercase text-sm" x-text="exercise.name"></h4>
                                            <button type="button" @click="removeExercise('{{ $dayKey }}', index)"
                                                    class="text-slate-600 hover:text-red-400 transition-colors">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                                </svg>
                                            </button>
                                        </div>
                                        <div class="grid grid-cols-3 gap-2">
                                            <div>
                                                <label class="block text-[9px] font-bold text-slate-500 uppercase mb-1">Séries</label>
                                                <input type="number" x-model.number="exercise.sets_count" min="1" max="10"
                                                       class="w-full rounded-xl bg-[#111214] border border-slate-700 focus:border-rose-500 focus:ring-0 py-2 px-3 text-white font-bold text-center">
                                            </div>
                                            <div>
                                                <label class="block text-[9px] font-bold text-slate-500 uppercase mb-1">Reps</label>
                                                <input type="number" x-model.number="exercise.target_reps" min="1"
                                                       class="w-full rounded-xl bg-[#111214] border border-slate-700 focus:border-rose-500 focus:ring-0 py-2 px-3 text-white font-bold text-center">
                                            </div>
                                            <div>
                                                <label class="block text-[9px] font-bold text-slate-500 uppercase mb-1">Repos (s)</label>
                                                <input type="number" x-model.number="exercise.rest_time" min="30" max="600" step="15"
                                                       class="w-full rounded-xl bg-[#111214] border border-slate-700 focus:border-rose-500 focus:ring-0 py-2 px-3 text-white font-bold text-center">
                                            </div>
                                        </div>
                                    </div>
                                </template>

                                <div x-show="(dayExercises['{{ $dayKey }}'] || []).length === 0"
                                     class="text-center py-6 text-slate-700 text-sm border-2 border-dashed border-slate-800 rounded-2xl">
                                    Aucun exercice — recherche ci-dessus pour en ajouter
                                </div>
                            </div>
                        </div>

                        {{-- Feedback sauvegarde --}}
                        <div x-show="saveMessage" x-transition
                             class="bg-green-500/10 border border-green-500/30 rounded-2xl p-3 text-green-400 text-sm font-bold text-center"
                             x-text="saveMessage"></div>

                        <div class="flex gap-3 pt-2">
                            <button type="button" @click="selectedDay = null"
                                    class="flex-1 py-4 bg-slate-900 border border-slate-800 rounded-2xl font-black uppercase text-sm hover:bg-slate-800 transition-all">
                                Annuler
                            </button>
                            <button type="button"
                                    @click="saveDay('{{ $dayKey }}')"
                                    :disabled="saving"
                                    class="flex-1 py-4 bg-gradient-to-r from-rose-500 to-rose-600 text-white rounded-2xl font-black uppercase text-sm shadow-lg shadow-rose-500/20 transition-all active:scale-95 disabled:opacity-60 flex items-center justify-center gap-2">
                                <div x-show="saving" class="w-4 h-4 border-2 border-white border-t-transparent rounded-full animate-spin"></div>
                                <span x-text="saving ? 'Sauvegarde...' : 'Sauvegarder'"></span>
                            </button>
                        </div>
                    </div>
                </div>
            @endforeach

            <div x-show="!selectedDay" class="text-center py-10 text-slate-700">
                <p class="text-sm font-bold uppercase tracking-wider">Sélectionne un jour pour planifier</p>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
    function routineEditor(initialExercises, initialNames, routineId) {
        return {
            selectedDay: null,
            dayExercises: initialExercises,
            dayNames: initialNames,
            routineId: routineId,
            searchQuery: '',
            searchResults: [],
            searching: false,
            saving: false,
            saveMessage: '',
            searchTimeout: null,
            csrfToken: '{{ csrf_token() }}',

            selectDay(day) {
                this.selectedDay = day;
                this.searchQuery = '';
                this.searchResults = [];
                if (!this.dayExercises[day]) this.dayExercises[day] = [];
                if (this.dayNames[day] === undefined) this.dayNames[day] = '';
            },

            async fetchExercises(query) {
                clearTimeout(this.searchTimeout);
                if (query.length < 2) {
                    this.searchResults = [];
                    this.searching = false;
                    return;
                }
                this.searchTimeout = setTimeout(async () => {
                    this.searching = true;
                    try {
                        const res = await fetch(
                            '/api/exercises/search?q=' + encodeURIComponent(query) + '&limit=25',
                            { headers: { 'X-Requested-With': 'XMLHttpRequest' } }
                        );
                        this.searchResults = res.ok ? await res.json() : [];
                    } catch {
                        this.searchResults = [];
                    }
                    this.searching = false;
                }, 300);
            },

            addExercise(day, exerciseId, exerciseName) {
                if (!this.dayExercises[day]) this.dayExercises[day] = [];
                if (this.dayExercises[day].some(e => e.exercise_id === exerciseId)) {
                    this.searchQuery = '';
                    this.searchResults = [];
                    return;
                }
                this.dayExercises[day].push({
                    exercise_id: exerciseId,
                    name:        exerciseName,
                    sets_count:  3,
                    target_reps: 10,
                    rest_time:   90,
                });
                this.searchQuery = '';
                this.searchResults = [];
            },

            removeExercise(day, index) {
                this.dayExercises[day].splice(index, 1);
            },

            async saveDay(dayKey) {
                this.saving = true;
                this.saveMessage = '';
                try {
                    const res = await fetch(`/routines/${this.routineId}/days/${dayKey}`, {
                        method: 'PUT',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': this.csrfToken,
                            'Accept': 'application/json',
                        },
                        body: JSON.stringify({
                            name: this.dayNames[dayKey] || '',
                            exercises: (this.dayExercises[dayKey] || []).map((ex, i) => ({
                                exercise_id: ex.exercise_id,
                                sets_count:  ex.sets_count,
                                target_reps: ex.target_reps,
                                rest_time:   ex.rest_time,
                            })),
                        }),
                    });
                    if (res.ok) {
                        this.saveMessage = '✓ Jour sauvegardé !';
                        setTimeout(() => this.saveMessage = '', 3000);
                    } else {
                        const data = await res.json().catch(() => ({}));
                        this.saveMessage = data.message || 'Erreur lors de la sauvegarde.';
                    }
                } catch {
                    this.saveMessage = 'Erreur réseau.';
                }
                this.saving = false;
            },
        };
    }
    </script>
    @endpush

</x-app-layout>
