@props(['show' => false])

<div x-show="restMode"
     x-cloak
     x-transition:enter="transition ease-out duration-300"
     x-transition:enter-start="opacity-0"
     x-transition:enter-end="opacity-100"
     x-transition:leave="transition ease-in duration-200"
     x-transition:leave-start="opacity-100"
     x-transition:leave-end="opacity-0"
     class="fixed inset-0 bg-black/95 backdrop-blur-xl flex flex-col items-center justify-center z-50 p-10">

    <div class="text-center space-y-8">
        <div>
            <p class="text-rose-500 font-black uppercase text-xs tracking-[0.3em] mb-4">Repos</p>
            <div class="text-8xl font-black italic tabular-nums tracking-tighter" x-text="formatTime(timer)">00:00</div>
        </div>

        <div class="w-64 h-2 bg-slate-900 rounded-full overflow-hidden">
            <div class="h-full bg-gradient-to-r from-rose-500 to-rose-600 transition-all duration-1000 ease-linear"
                 :style="'width: ' + ((timer / restDuration) * 100) + '%'"></div>
        </div>

        <div class="flex gap-4 w-full max-w-sm">
            <button @click="stopTimer()"
                    class="flex-1 py-5 rounded-3xl bg-slate-900 border border-slate-800 font-black uppercase text-sm tracking-wider hover:bg-slate-800 transition-all active:scale-95">
                Passer
            </button>
            <button @click="addTime(30)"
                    class="flex-1 py-5 rounded-3xl border-2 border-rose-500 font-black uppercase text-sm tracking-wider hover:bg-rose-500/10 transition-all active:scale-95">
                +30s
            </button>
        </div>
    </div>
</div>
