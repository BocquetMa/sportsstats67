<x-app-layout>
    <div class="min-h-screen bg-[#08090a] text-white pb-24"
         x-data="{
             selectedDay: null,
             exercises: [],
             searchQuery: '',
             searchResults: [],
             searching: false,
             searchTimeout: null,

             async fetchExercises(query) {
                 if (query.length < 2) { this.searchResults = []; return; }
                 this.searching = true;
                 clearTimeout(this.searchTimeout);
                 this.searchTimeout = setTimeout(async () => {
                     try {
                         const res = await fetch('/api/exercises/search?q=' + encodeURIComponent(query) + '&limit=20');
                         this.searchResults = await res.json();
                     } catch (e) { this.searchResults = []; }
                     this.searching = false;
                 }, 300);
             },

             addExercise(exerciseId, exerciseName) {
                 this.exercises.push({
                     exercise_id: exerciseId,
                     name: exerciseName,
                     sets_count: 3,
                     target_reps: 10,
                     rest_time: 90
                 });
                 this.searchQuery = '';
                 this.searchResults = [];
             },

             removeExercise(index) {
                 this.exercises.splice(index, 1);
             }
         }">

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
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="text-red-400 hover:text-red-300 text-xs font-black uppercase">
                        🗑️ Supprimer
                    </button>
                </form>
            </div>
        </div>

        <div class="px-5 space-y-6">

            @if(session('success'))
                <x-alert type="success">{{ session('success') }}</x-alert>
            @endif

            <div class="bg-[#111214] border border-slate-800 p-6 rounded-[3rem]">
                <h2 class="text-lg font-black uppercase mb-4">Paramètres de la Routine</h2>
                <form action="{{ route('routines.update', $routine) }}" method="POST" class="flex gap-4">
                    @csrf
                    @method('PUT')
                    <input type="text"
                           name="name"
                           value="{{ $routine->name }}"
                           class="flex-1 rounded-2xl bg-[#08090a] border-2 border-slate-800 focus:border-rose-500 focus:ring-0 py-3 px-5 text-white font-bold"
                           required>
                    <button type="submit"
                            class="bg-rose-500 hover:bg-rose-600 text-white font-black px-8 py-3 rounded-2xl transition-all active:scale-95">
                        Sauvegarder
                    </button>
                </form>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-7 gap-4">
                @foreach($days as $dayKey => $dayName)
                    @php
                        $routineDay = $routine->days->firstWhere('day_of_week', $dayKey);
                        $hasWorkout = $routineDay && $routineDay->exercises->count() > 0;
                        $isToday = $dayKey === \App\Models\RoutineDay::getTodayDay();
                    @endphp

                    <button @click="selectedDay = '{{ $dayKey }}'"
                            :class="selectedDay === '{{ $dayKey }}' ? 'border-rose-500 bg-rose-500/10' : 'border-slate-800 hover:border-slate-700'"
                            class="group relative p-4 rounded-2xl border-2 transition-all text-center">

                        @if($isToday)
                            <span class="absolute -top-2 -right-2 w-4 h-4 bg-rose-500 rounded-full animate-pulse"></span>
                        @endif

                        <div class="text-[10px] font-black uppercase tracking-wider text-slate-500 mb-1">
                            {{ $dayName }}
                        </div>

                        @if($hasWorkout)
                            <div class="text-2xl font-black">{{ $routineDay->exercises->count() }}</div>
                            <div class="text-[9px] text-slate-500 font-bold">exercices</div>
                        @else
                            <div class="text-3xl opacity-30">-</div>
                        @endif
                    </button>
                @endforeach
            </div>

            @foreach($days as $dayKey => $dayName)
                @php
                    $routineDay = $routine->days->firstWhere('day_of_week', $dayKey);
                @endphp

                <div x-show="selectedDay === '{{ $dayKey }}'"
                     x-transition
                     class="bg-[#111214] border border-slate-800 rounded-[3rem] p-8">

                    <div class="flex items-center justify-between mb-6">
                        <h2 class="text-3xl font-black uppercase italic">{{ $dayName }}</h2>
                        <button @click="selectedDay = null"
                                class="text-slate-400 hover:text-white">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                            </svg>
                        </button>
                    </div>

                    <form action="{{ route('routines.update-day', [$routine, $dayKey]) }}" method="POST" class="space-y-6">
                        @csrf
                        @method('PUT')

                        <div>
                            <label class="block text-sm font-bold text-slate-400 uppercase tracking-wider mb-2">
                                Nom de la Séance (optionnel)
                            </label>
                            <input type="text"
                                   name="name"
                                   value="{{ $routineDay->name ?? '' }}"
                                   placeholder="Ex: Jambes Push, Upper Body..."
                                   class="w-full rounded-2xl bg-[#08090a] border-2 border-slate-800 focus:border-rose-500 focus:ring-0 py-3 px-5 text-white font-bold">
                        </div>

                        <div>
                            <label class="block text-sm font-bold text-slate-400 uppercase tracking-wider mb-2">
                                Notes
                            </label>
                            <textarea name="notes"
                                      rows="2"
                                      placeholder="Notes pour cette séance..."
                                      class="w-full rounded-2xl bg-[#08090a] border-2 border-slate-800 focus:border-rose-500 focus:ring-0 py-3 px-5 text-white font-bold">{{ $routineDay->notes ?? '' }}</textarea>
                        </div>

                        <div>
                            <div class="flex items-center justify-between mb-4">
                                <label class="text-sm font-bold text-slate-400 uppercase tracking-wider">
                                    Exercices
                                </label>
                                <button type="button"
                                        @click="$refs.exerciseSearch.focus()"
                                        class="text-rose-500 hover:text-rose-400 text-xs font-black uppercase">
                                    + Ajouter
                                </button>
                            </div>

                            <div class="relative mb-4">
                                <input type="text"
                                       x-ref="exerciseSearch"
                                       x-model="searchQuery"
                                       @input="fetchExercises(searchQuery)"
                                       placeholder="Rechercher un exercice (ex: squat, bench press...)"
                                       autocomplete="off"
                                       class="w-full rounded-2xl bg-[#08090a] border-2 border-slate-800 focus:border-rose-500 focus:ring-0 py-3 px-5 text-white font-bold">

                                <div x-show="searchQuery.length >= 2"
                                     class="absolute top-full left-0 right-0 mt-2 bg-[#111214] border-2 border-slate-800 rounded-2xl max-h-64 overflow-y-auto z-10">

                                    <div x-show="searching" class="p-4 text-center text-slate-500 text-sm">
                                        Recherche en cours...
                                    </div>

                                    <template x-if="!searching && searchResults.length === 0 && searchQuery.length >= 2">
                                        <div class="p-4 text-center text-slate-500 text-sm">Aucun exercice trouvé</div>
                                    </template>

                                    <template x-for="ex in searchResults" :key="ex.id">
                                        <button type="button"
                                                @click="addExercise(ex.id, ex.name)"
                                                class="w-full text-left p-3 hover:bg-slate-900 transition-colors border-b border-slate-800 last:border-0">
                                            <span class="font-black text-sm" x-text="ex.name"></span>
                                            <p class="text-[9px] text-slate-500 font-bold mt-0.5"
                                               x-text="Array.isArray(ex.targetMuscles) ? ex.targetMuscles.join(', ') : ex.targetMuscles"></p>
                                        </button>
                                    </template>
                                </div>
                            </div>

                            <div class="space-y-3" x-init="exercises = {{ json_encode($routineDay?->exercises->map(fn($ex) => [
                                'exercise_id' => $ex->id,
                                'name' => $ex->name,
                                'sets_count' => $ex->pivot->sets_count,
                                'target_reps' => $ex->pivot->target_reps,
                                'rest_time' => $ex->pivot->rest_time
                            ])->values() ?? []) }}">

                                <template x-for="(exercise, index) in exercises" :key="index">
                                    <div class="bg-[#08090a] p-4 rounded-2xl border border-slate-800">
                                        <input type="hidden" :name="'exercises[' + index + '][exercise_id]'" :value="exercise.exercise_id">

                                        <div class="flex items-center justify-between mb-3">
                                            <h4 class="font-black uppercase text-sm" x-text="exercise.name"></h4>
                                            <button type="button"
                                                    @click="removeExercise(index)"
                                                    class="text-red-400 hover:text-red-300 text-xs">
                                                🗑️
                                            </button>
                                        </div>

                                        <div class="grid grid-cols-3 gap-3">
                                            <div>
                                                <label class="block text-[9px] font-bold text-slate-500 uppercase mb-1">Séries</label>
                                                <input type="number"
                                                       :name="'exercises[' + index + '][sets_count]'"
                                                       x-model="exercise.sets_count"
                                                       min="1"
                                                       max="10"
                                                       class="w-full rounded-xl bg-[#111214] border border-slate-700 focus:border-rose-500 focus:ring-0 py-2 px-3 text-white font-bold text-center">
                                            </div>
                                            <div>
                                                <label class="block text-[9px] font-bold text-slate-500 uppercase mb-1">Reps</label>
                                                <input type="number"
                                                       :name="'exercises[' + index + '][target_reps]'"
                                                       x-model="exercise.target_reps"
                                                       min="1"
                                                       class="w-full rounded-xl bg-[#111214] border border-slate-700 focus:border-rose-500 focus:ring-0 py-2 px-3 text-white font-bold text-center">
                                            </div>
                                            <div>
                                                <label class="block text-[9px] font-bold text-slate-500 uppercase mb-1">Repos (s)</label>
                                                <input type="number"
                                                       :name="'exercises[' + index + '][rest_time]'"
                                                       x-model="exercise.rest_time"
                                                       min="30"
                                                       max="600"
                                                       step="15"
                                                       class="w-full rounded-xl bg-[#111214] border border-slate-700 focus:border-rose-500 focus:ring-0 py-2 px-3 text-white font-bold text-center">
                                            </div>
                                        </div>
                                    </div>
                                </template>

                                <div x-show="exercises.length === 0"
                                     class="text-center py-8 text-slate-600 text-sm">
                                    Aucun exercice pour ce jour
                                </div>
                            </div>
                        </div>

                        <div class="flex gap-4 pt-4">
                            <button type="button"
                                    @click="selectedDay = null"
                                    class="flex-1 py-4 px-6 bg-slate-900 border border-slate-800 rounded-2xl font-black uppercase hover:bg-slate-800 transition-all">
                                Annuler
                            </button>
                            <button type="submit"
                                    class="flex-1 py-4 px-6 bg-gradient-to-r from-rose-500 to-rose-600 hover:from-rose-600 hover:to-rose-700 text-white rounded-2xl font-black uppercase shadow-xl shadow-rose-500/20 transition-all active:scale-95">
                                Sauvegarder {{ $dayName }}
                            </button>
                        </div>
                    </form>
                </div>
            @endforeach

            <div x-show="!selectedDay" class="text-center py-12 text-slate-600">
                <p class="text-sm font-bold uppercase tracking-wider">Sélectionne un jour pour planifier les exercices</p>
            </div>
        </div>
    </div>

</x-app-layout>
