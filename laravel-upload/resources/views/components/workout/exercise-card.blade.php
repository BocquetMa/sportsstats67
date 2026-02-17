@props(['exercise', 'sets', 'record' => null, 'index'])

<div x-show="currentIndex === {{ $index }}"
     x-transition:enter="transition ease-out duration-300"
     x-transition:enter-start="opacity-0 translate-x-full"
     x-transition:enter-end="opacity-100 translate-x-0"
     class="space-y-6">

    <div class="bg-[#111214] rounded-[2.5rem] border border-slate-800 overflow-hidden shadow-2xl">
        <div class="relative w-full aspect-video bg-slate-900 border-b border-slate-800">
            @if($exercise->image_url)
                <img src="{{ $exercise->image_url }}"
                     alt="{{ $exercise->name }}"
                     class="w-full h-full object-cover"
                     loading="lazy">
            @else
                <div class="w-full h-full flex items-center justify-center text-slate-700 italic text-sm">
                    Aucune démo disponible
                </div>
            @endif
            <div class="absolute inset-0 bg-gradient-to-t from-[#111214] to-transparent opacity-60"></div>
        </div>

        <div class="p-6 space-y-6">
            <div class="flex justify-between items-start gap-4">
                <h2 class="text-3xl font-black uppercase italic tracking-tighter leading-tight flex-1">
                    {{ $exercise->name }}
                </h2>

                @if($record)
                    <div class="bg-rose-500/10 border border-rose-500/20 p-3 rounded-2xl text-right flex-shrink-0">
                        <p class="text-[9px] font-black text-rose-500 uppercase italic mb-1">Record Personnel</p>
                        <p class="text-sm font-mono font-bold">{{ $record->weight }}kg × {{ $record->reps }}</p>
                    </div>
                @endif
            </div>

            <div class="space-y-3">
                @foreach($sets as $set)
                    <x-workout.set-input :set="$set" :loop="$loop" />
                @endforeach
            </div>
        </div>
    </div>

    <div class="flex gap-4">
        <button @click="prev()"
                :disabled="currentIndex === 0"
                class="flex-1 py-5 rounded-3xl bg-slate-900 border border-slate-800 font-black uppercase tracking-widest text-xs disabled:opacity-20 disabled:cursor-not-allowed hover:bg-slate-800 transition-all active:scale-95">
            ← Précédent
        </button>
        <button @click="next()"
                :disabled="currentIndex === totalExercises - 1"
                class="flex-1 py-5 rounded-3xl bg-white text-black font-black uppercase tracking-widest text-xs disabled:opacity-20 disabled:cursor-not-allowed hover:bg-rose-500 hover:text-white transition-all active:scale-95">
            Suivant →
        </button>
    </div>
</div>
