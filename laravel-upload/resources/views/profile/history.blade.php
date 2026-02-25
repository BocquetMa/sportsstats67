<x-app-layout>
    <div class="min-h-screen bg-[#08090a] text-white p-6 pb-32">

        <div class="mb-8">
            <h1 class="text-3xl font-black italic uppercase tracking-tighter">Performance</h1>
            <p class="text-[10px] font-bold text-slate-500 uppercase tracking-widest">
                Objectif : {{ $latest->objective ?? 'Non défini' }}
            </p>
        </div>

        <div class="bg-[#111214] rounded-[2.5rem] border border-white/5 p-6 mb-6">
            <div class="flex justify-between items-center mb-6">
                <div>
                    <span class="text-[10px] font-black text-slate-500 uppercase tracking-[0.2em]">Poids Actuel</span>
                    <div class="flex items-baseline gap-2">
                        <span class="text-4xl font-black italic">{{ $latest->weight ?? '--' }}</span>
                        <span class="text-rose-500 font-bold italic text-sm">kg</span>
                    </div>
                </div>
                <div class="text-right">
                    @if($weightDiff > 0)
                        <span class="block text-[10px] font-black text-rose-500 uppercase">+{{ $weightDiff }}kg</span>
                    @else
                        <span class="block text-[10px] font-black text-green-500 uppercase">{{ $weightDiff }}kg</span>
                    @endif
                    <span class="text-[9px] text-slate-600 font-bold uppercase italic">Depuis la dernière fois</span>
                </div>
            </div>

            <div class="flex items-end gap-2 h-16 w-full opacity-50 px-2">
                @forelse($metrics as $m)
                    <div class="flex-1 bg-rose-500/20 rounded-t-sm" style="height: {{ ($m->weight / 150) * 100 }}%"></div>
                @empty
                    <div class="w-full text-center text-[10px] text-slate-700 uppercase">Pas assez de données</div>
                @endforelse
            </div>
        </div>

        <div class="space-y-4 mb-8">
            <h3 class="text-[10px] font-black uppercase tracking-[0.3em] text-slate-600 ml-2">Progression Force</h3>

            {{-- Ici on boucle sur les exercices favoris de l'utilisateur --}}
            <div class="bg-[#111214] p-5 rounded-[2rem] border border-white/5 flex items-center justify-between">
                <div class="flex items-center gap-4">
                    <div class="w-10 h-10 rounded-xl bg-black flex items-center justify-center text-rose-500">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                    </div>
                    <div>
                        <p class="text-xs font-black uppercase italic">Développé Couché</p>
                        <p class="text-[10px] text-slate-500 font-bold">Record : 85kg</p>
                    </div>
                </div>
                <div class="text-right">
                    <span class="text-xs font-black text-green-500">+5.4%</span>
                </div>
            </div>
        </div>

        <div class="mt-10 border-t border-white/5 pt-8">
            <h3 class="text-[10px] font-black uppercase tracking-[0.3em] text-center text-slate-500 mb-6">Mise à jour rapide</h3>
            <div class="grid grid-cols-2 gap-4">
                <button class="bg-white/5 border border-white/10 p-4 rounded-2xl text-[10px] font-black uppercase italic hover:bg-rose-500/10 transition-all">
                    Log Poids
                </button>
                <button class="bg-white/5 border border-white/10 p-4 rounded-2xl text-[10px] font-black uppercase italic hover:bg-rose-500/10 transition-all">
                    Nouveau PR
                </button>
            </div>
        </div>

    </div>
</x-app-layout>
