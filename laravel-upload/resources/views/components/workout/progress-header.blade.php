@props(['workout', 'totalExercises'])

<div class="fixed top-0 left-0 right-0 bg-[#08090a]/95 backdrop-blur-xl z-30 p-6 border-b border-slate-800/50">
    <div class="flex justify-between items-center mb-4">
        <div class="flex-1">
            <h1 class="text-xl font-black uppercase italic leading-none text-rose-500 truncate">
                {{ $workout->title }}
            </h1>
            <p class="text-[10px] font-bold uppercase tracking-[0.2em] text-slate-500 mt-1"
               x-text="showList ? 'Aperçu du programme' : 'Exercice ' + (currentIndex + 1) + ' / ' + totalExercises">
            </p>
        </div>

        <button @click="showList = !showList"
                class="bg-[#111214] border px-4 py-2 rounded-2xl text-xs font-black uppercase tracking-widest transition-all active:scale-95"
                :class="showList ? 'border-rose-500 text-rose-500' : 'border-slate-800 text-white hover:border-slate-700'">
            <span x-show="!showList">📋 Liste</span>
            <span x-show="showList">🎯 Focus</span>
        </button>
    </div>

    <div class="h-1.5 w-full bg-slate-900 rounded-full overflow-hidden">
        <div class="h-full bg-gradient-to-r from-rose-500 to-rose-600 transition-all duration-700 shadow-[0_0_10px_rgba(244,63,94,0.6)]"
             :style="'width: ' + ((currentIndex + 1) / totalExercises * 100) + '%'">
        </div>
    </div>
</div>
