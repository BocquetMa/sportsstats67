<x-guest-layout>
    <div class="min-h-screen flex flex-col justify-center items-center bg-[#0a0a0b] px-4">
        <div class="mb-6">
            <div class="w-16 h-16 bg-rose-500/10 border border-rose-500/20 rounded-full flex items-center justify-center">
                <svg class="w-8 h-8 text-rose-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                </svg>
            </div>
        </div>

        <div class="mb-8 text-center">
            <h1 class="text-2xl font-black italic uppercase tracking-tighter text-white">
                Zone Sécurisée<span class="text-rose-500">.</span>
            </h1>
            <p class="text-slate-500 text-[10px] font-bold uppercase tracking-[0.2em] mt-2 max-w-[280px] mx-auto leading-relaxed">
                Veuillez confirmer votre accès avant de continuer.
            </p>
        </div>

        <div class="w-full max-w-md bg-[#111214] border border-slate-800 p-8 rounded-[2.5rem] shadow-2xl">
            <form method="POST" action="{{ route('password.confirm') }}" class="space-y-6">
                @csrf

                <div>
                    <label class="block text-[10px] font-black uppercase text-slate-500 mb-2 ml-4">Mot de passe actuel</label>
                    <input type="password" name="password" required autocomplete="current-password"
                        class="w-full bg-[#0a0a0b] border-slate-800 border-2 rounded-2xl text-white focus:border-rose-500 focus:ring-0 transition-all placeholder-slate-700"
                        placeholder="••••••••">
                    <x-input-error :messages="$errors->get('password')" class="mt-2" />
                </div>

                <button type="submit" class="w-full bg-rose-500 hover:bg-rose-600 text-white font-black py-4 rounded-2xl transition-all transform hover:scale-[1.02] uppercase italic tracking-widest">
                    Confirmer l'accès
                </button>
            </form>
        </div>
    </div>
</x-guest-layout>
