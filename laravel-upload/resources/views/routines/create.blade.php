<x-app-layout>
    <div class="min-h-screen bg-[#08090a] text-white pb-24">

        <div class="px-6 pt-12 pb-6">
            <a href="{{ route('routines.index') }}" class="inline-flex items-center text-slate-400 hover:text-white transition mb-4">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                </svg>
                Retour
            </a>
            <div>
                <p class="text-[10px] font-black text-rose-500 uppercase tracking-[0.3em] mb-1">Nouveau Programme</p>
                <h1 class="text-4xl font-black uppercase italic tracking-tighter leading-none">Créer une Routine</h1>
            </div>
        </div>

        <div class="px-5">
            <div class="bg-[#111214] border border-slate-800 p-8 rounded-[3rem] shadow-xl max-w-2xl mx-auto">
                <form action="{{ route('routines.store') }}" method="POST" class="space-y-6">
                    @csrf

                    <div>
                        <label class="block text-sm font-bold text-slate-400 uppercase tracking-wider mb-2">
                            Nom de la Routine
                        </label>
                        <input type="text"
                               name="name"
                               placeholder="Ex: Programme PPL, Fullbody 3x..."
                               class="w-full rounded-2xl bg-[#08090a] border-2 border-slate-800 focus:border-rose-500 focus:ring-0 transition-all py-4 px-6 text-lg font-bold text-white placeholder-slate-600"
                               required>
                        @error('name')
                            <p class="text-red-400 text-xs mt-2">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="flex items-center gap-3 bg-[#08090a] p-4 rounded-2xl border border-slate-800">
                        <input type="checkbox"
                               name="is_public"
                               id="is_public"
                               value="1"
                               class="w-5 h-5 rounded bg-slate-900 border-slate-700 text-rose-500 focus:ring-rose-500/20">
                        <label for="is_public" class="flex-1">
                            <span class="font-black text-sm">Rendre publique</span>
                            <p class="text-[10px] text-slate-500 font-bold">Les autres pourront découvrir cette routine</p>
                        </label>
                    </div>

                    <div class="flex gap-4">
                        <a href="{{ route('routines.index') }}"
                           class="flex-1 py-4 px-6 bg-slate-900 border border-slate-800 rounded-2xl font-black text-center uppercase hover:bg-slate-800 transition-all">
                            Annuler
                        </a>
                        <button type="submit"
                                class="flex-1 py-4 px-6 bg-gradient-to-r from-rose-500 to-rose-600 hover:from-rose-600 hover:to-rose-700 text-white rounded-2xl font-black uppercase shadow-xl shadow-rose-500/20 transition-all active:scale-95">
                            Créer & Planifier
                        </button>
                    </div>
                </form>
            </div>

            <div class="mt-8 bg-[#111214] border border-slate-800 p-6 rounded-[2.5rem] max-w-2xl mx-auto">
                <h3 class="text-sm font-black uppercase tracking-wider text-slate-400 mb-3">💡 Prochaines étapes</h3>
                <ul class="space-y-2 text-sm text-slate-300">
                    <li class="flex items-center gap-2">
                        <span class="w-1.5 h-1.5 bg-rose-500 rounded-full"></span>
                        Planifie les jours de la semaine (ex: Lundi = Jambes)
                    </li>
                    <li class="flex items-center gap-2">
                        <span class="w-1.5 h-1.5 bg-rose-500 rounded-full"></span>
                        Ajoute les exercices pour chaque jour
                    </li>
                    <li class="flex items-center gap-2">
                        <span class="w-1.5 h-1.5 bg-rose-500 rounded-full"></span>
                        Partage avec tes amis si tu veux
                    </li>
                </ul>
            </div>
        </div>
    </div>

    @include('layouts.navigation')
</x-app-layout>
