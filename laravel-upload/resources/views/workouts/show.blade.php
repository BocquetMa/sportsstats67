<x-app-layout>
    <div class="min-h-screen bg-[#08090a] text-white p-6 pb-52"
         x-data="workoutManager({{ $workout->trainingSets->groupBy('exercise_id')->count() }}, '{{ csrf_token() }}', {{ $workout->id }})"
         x-init="init()">

        <x-workout.progress-header :workout="$workout" :totalExercises="$workout->trainingSets->groupBy('exercise_id')->count()" />

        <div class="mt-28">
            {{-- Mode Focus: exercice par exercice --}}
            <div x-show="!showList">
                @foreach($workout->trainingSets->groupBy('exercise_id') as $exerciseId => $sets)
                    @php $exercise = $sets->first()->exercise; @endphp
                    <x-workout.exercise-card
                        :exercise="$exercise"
                        :sets="$sets"
                        :record="$records[$exerciseId] ?? null"
                        :index="$loop->index" />
                @endforeach
            </div>

            {{-- Mode Liste: vue d'ensemble --}}
            <div x-show="showList"
                 x-transition:enter="transition ease-out duration-300"
                 x-transition:enter-start="opacity-0"
                 x-transition:enter-end="opacity-100"
                 class="space-y-4">

                {{-- Carte progression --}}
                <div class="bg-[#111214] border border-slate-800 p-6 rounded-[2.5rem] mb-6">
                    <div class="flex items-center justify-between mb-4">
                        <div>
                            <p class="text-[10px] font-black text-rose-500 uppercase tracking-[0.3em]">Progression</p>
                            <h3 class="text-2xl font-black" x-text="getCompletedCount() + ' / ' + getTotalSets() + ' séries'"></h3>
                        </div>
                        <div class="text-right">
                            <p class="text-3xl font-black" x-text="getProgress() + '%'"></p>
                            <p class="text-[9px] text-slate-500 font-bold uppercase">Complété</p>
                        </div>
                    </div>
                    <div class="h-3 w-full bg-slate-900 rounded-full overflow-hidden">
                        <div class="h-full bg-gradient-to-r from-rose-500 to-rose-600 transition-all duration-500"
                             :style="'width: ' + getProgress() + '%'"></div>
                    </div>
                    <p x-show="isAllCompleted()" class="text-center text-green-400 text-xs font-black uppercase mt-3 tracking-wider">
                        Toutes les séries complétées ! 🎉
                    </p>
                </div>

                @foreach($workout->trainingSets->groupBy('exercise_id') as $exerciseId => $sets)
                    <div @click="goToExercise({{ $loop->index }})"
                         class="bg-[#111214] p-4 rounded-[2rem] border-2 flex items-center gap-4 transition-all cursor-pointer hover:bg-slate-900"
                         :class="currentIndex === {{ $loop->index }} ? 'border-rose-500' : 'border-slate-800'">

                        <div class="w-16 h-16 rounded-2xl overflow-hidden bg-black flex-shrink-0">
                            @if($sets->first()->exercise->gifUrl)
                                <img src="{{ $sets->first()->exercise->gifUrl }}"
                                     alt="{{ $sets->first()->exercise->name }}"
                                     class="w-full h-full object-cover"
                                     loading="lazy">
                            @else
                                <div class="w-full h-full flex items-center justify-center text-slate-700 text-lg">
                                    🏋️
                                </div>
                            @endif
                        </div>

                        <div class="flex-1 min-w-0">
                            <h3 class="font-black uppercase italic text-sm truncate">
                                {{ $sets->first()->exercise->name }}
                            </h3>
                            <p class="text-[9px] text-slate-500 font-bold">
                                {{ $sets->count() }} Séries
                            </p>
                        </div>

                        <div class="flex-shrink-0 flex items-center gap-2">
                            <div class="w-8 h-8 rounded-full bg-slate-900 flex items-center justify-center text-[10px] font-black">
                                {{ $loop->index + 1 }}
                            </div>
                            <svg class="w-5 h-5 text-slate-600 transition-transform"
                                 :class="currentIndex === {{ $loop->index }} ? 'rotate-90' : ''"
                                 fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M9 5l7 7-7 7"/>
                            </svg>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        {{-- Timer de repos --}}
        <x-workout.timer-modal />

        {{-- Toast erreur --}}
        <div x-show="error"
             x-cloak
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0 -translate-y-2"
             x-transition:enter-end="opacity-100 translate-y-0"
             class="fixed top-6 left-1/2 transform -translate-x-1/2 z-50 bg-red-500 text-white px-6 py-3 rounded-2xl font-bold shadow-xl max-w-xs text-center text-sm">
            <p x-text="error"></p>
        </div>

        {{-- Loading spinner --}}
        <div x-show="isLoading"
             x-cloak
             class="fixed inset-0 bg-black/50 backdrop-blur-sm flex items-center justify-center z-40">
            <div class="bg-[#111214] border border-slate-800 p-8 rounded-3xl text-center">
                <div class="animate-spin w-12 h-12 border-4 border-rose-500 border-t-transparent rounded-full mx-auto"></div>
                <p class="text-sm font-bold text-slate-400 mt-4 uppercase tracking-wider">Sauvegarde...</p>
            </div>
        </div>

        {{-- Bouton Terminer la séance --}}
        <div class="fixed bottom-24 left-0 right-0 p-6 z-40">
            @if($workout->status === 'completed')
                <div class="flex gap-3">
                    <a href="{{ route('profile.history') }}"
                       class="flex-1 py-4 rounded-3xl bg-green-500/10 border-2 border-green-500/50 text-green-400 font-black uppercase italic text-sm tracking-widest text-center transition-all hover:bg-green-500 hover:text-white">
                        ✓ Séance Terminée
                    </a>
                </div>
            @else
                <form action="{{ route('workout.finish', $workout) }}" method="POST"
                      onsubmit="return confirm('Terminer cette séance ? Toutes les données seront sauvegardées.')">
                    @csrf
                    <button type="submit"
                            class="w-full py-4 rounded-3xl font-black uppercase italic text-sm tracking-widest transition-all active:scale-95"
                            :class="isAllCompleted()
                                ? 'bg-green-500 text-white shadow-lg shadow-green-500/20 hover:bg-green-600'
                                : 'bg-rose-500/10 border-2 border-rose-500/50 text-rose-500 hover:bg-rose-500 hover:text-white'">
                        <span x-show="isAllCompleted()">🏆 Terminer — Toutes les séries OK !</span>
                        <span x-show="!isAllCompleted()">✓ Terminer la Séance</span>
                    </button>
                </form>
            @endif
        </div>

    </div>

    @vite('resources/js/workout-manager.js')
</x-app-layout>
