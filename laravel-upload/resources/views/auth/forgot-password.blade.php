<x-guest-layout>
    <div class="min-h-screen flex flex-col justify-center items-center bg-[#0a0a0b] px-4">
        <div class="mb-8 text-center">
            <h1 class="text-3xl font-black italic uppercase tracking-tighter text-white">
                Récupération<span class="text-rose-500 text-5xl">.</span>
            </h1>
            <p class="text-slate-500 text-[10px] font-bold uppercase tracking-[0.2em] mt-2 max-w-[250px] mx-auto leading-relaxed">
                Entrez votre email pour recevoir un lien de réinitialisation.
            </p>
        </div>

        <div class="w-full max-w-md bg-[#111214] border border-slate-800 p-8 rounded-[2.5rem] shadow-2xl">
            <x-auth-session-status class="mb-6 text-green-500 font-bold text-xs uppercase text-center tracking-widest" :status="session('status')" />

            <form method="POST" action="{{ route('password.email') }}" class="space-y-6">
                @csrf

                <div>
                    <label class="block text-[10px] font-black uppercase text-slate-500 mb-2 ml-4">Email de l'athlète</label>
                    <input type="email" name="email" :value="old('email')" required autofocus
                        class="w-full bg-[#0a0a0b] border-slate-800 border-2 rounded-2xl text-white focus:border-rose-500 focus:ring-0 transition-all placeholder-slate-700"
                        placeholder="nom@exemple.com">
                    <x-input-error :messages="$errors->get('email')" class="mt-2" />
                </div>

                <button type="submit" class="w-full bg-rose-500 hover:bg-rose-600 text-white font-black py-4 rounded-2xl transition-all transform hover:scale-[1.02] uppercase italic tracking-widest">
                    Envoyer le lien
                </button>
            </form>

            <div class="mt-8 text-center">
                <a href="{{ route('login') }}" class="text-slate-500 text-[10px] font-bold uppercase hover:text-white transition-colors italic">
                    <span class="text-rose-500">←</span> Retour à la connexion
                </a>
            </div>
        </div>
    </div>
</x-guest-layout>
