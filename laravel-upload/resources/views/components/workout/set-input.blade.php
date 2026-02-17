@props(['set', 'loop', 'isCompleted' => false, 'isActive' => false])

<div id="set-row-{{ $set->id }}"
     class="flex items-center gap-3 bg-[#08090a] p-4 rounded-3xl border transition-all duration-300"
     :class="completedSets.includes({{ $set->id }}) ? 'opacity-30 border-green-500/30' : (activeSet == {{ $set->id }} ? 'border-rose-500 bg-rose-500/5' : 'border-transparent')">

    <span class="w-10 h-10 flex-shrink-0 flex items-center justify-center rounded-full bg-slate-900 text-sm font-black italic">
        S{{ $loop->iteration }}
    </span>

    <div class="flex flex-1 items-center justify-center gap-2">
        <input type="number"
               id="weight-{{ $set->id }}"
               value="{{ $set->weight }}"
               min="0"
               step="0.5"
               class="w-16 bg-transparent border-none text-xl font-black focus:ring-0 p-0 text-center"
               x-on:focus="activeSet = {{ $set->id }}">
        <span class="text-slate-700 font-black text-xs">KG</span>

        <input type="number"
               id="reps-{{ $set->id }}"
               value="{{ $set->reps }}"
               min="0"
               max="100"
               class="w-16 bg-transparent border-none text-xl font-black focus:ring-0 p-0 text-center"
               x-on:focus="activeSet = {{ $set->id }}">
        <span class="text-slate-700 font-black text-xs">REPS</span>
    </div>

    <button @click="completeSet({{ $set->id }}, 90)"
            :disabled="completedSets.includes({{ $set->id }})"
            class="w-12 h-12 flex-shrink-0 flex items-center justify-center rounded-2xl transition-all duration-300 disabled:cursor-not-allowed"
            :class="completedSets.includes({{ $set->id }}) ? 'bg-green-500 text-white' : 'bg-white text-black hover:bg-rose-500 hover:text-white'">
        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="4" d="M5 13l4 4L19 7"/>
        </svg>
    </button>
</div>
