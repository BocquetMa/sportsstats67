<div class="relative w-full">
    <div class="relative">
        <input wire:model.live.debounce.300ms="search"
               type="text"
               class="w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 py-3 pl-4"
               placeholder="🔎 Chercher un exercice (ex: Bench, Squat, Pectoraux...)">

        <div wire:loading class="absolute right-3 top-3">
            <svg class="animate-spin h-5 w-5 text-indigo-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
            </svg>
        </div>
    </div>

    @if(!empty($results))
        <div class="absolute z-50 w-full mt-1 bg-white border border-gray-200 rounded-lg shadow-xl overflow-hidden">
            @foreach($results as $exercise)
                <button type="button"
                        wire:click="selectExercise({{ $exercise->id }}, '{{ addslashes($exercise->name) }}')"
                        class="w-full flex items-center p-3 hover:bg-indigo-50 border-b last:border-0 transition text-left">
                    <img src="{{ $exercise->gifUrl }}" class="w-12 h-12 rounded bg-gray-100 object-contain mr-4">
                    <div>
                        <p class="font-bold text-gray-900 capitalize">{{ $exercise->name }}</p>
                        <p class="text-xs text-gray-500">{{ is_array($exercise->targetMuscles) ? implode(', ', $exercise->targetMuscles) : $exercise->targetMuscles }}</p>
                    </div>
                </button>
            @endforeach
        </div>
    @endif

    @if($selectedExercise)
        <div class="mt-2 p-2 bg-green-50 border border-green-200 rounded flex justify-between items-center text-sm text-green-700">
            <span>Exercice choisi : <strong>{{ $selectedExercise['name'] }}</strong></span>
            <button type="button" wire:click="$set('selectedExercise', null)" class="text-red-500 hover:underline">Annuler</button>
        </div>
    @endif
</div>
