<x-guest-layout>
    <div class="min-h-screen flex flex-col justify-center items-center bg-[#0a0a0b] px-4 py-10">
        <div class="mb-8 text-center">
            <h1 class="text-4xl font-black italic uppercase tracking-tighter text-white">
                Rejoindre<span class="text-rose-500 text-5xl">.</span>Le Club
            </h1>
        </div>

        <div class="w-full max-w-md bg-[#111214] border border-slate-800 p-8 rounded-[2.5rem] shadow-2xl">
            <form method="POST" action="{{ route('register') }}" class="space-y-5">
                @csrf

                <div>
                    <label class="block text-[10px] font-black uppercase text-slate-500 mb-2 ml-4">Nom d'athlète</label>
                    <input type="text" name="name" :value="old('name')" required autofocus
                        class="w-full bg-[#0a0a0b] border-slate-800 border-2 rounded-2xl text-white focus:border-rose-500 focus:ring-0 transition-all"
                        placeholder="Ex: Jordan B.">
                    <x-input-error :messages="$errors->get('name')" class="mt-2" />
                </div>

                <div>
                    <label class="block text-[10px] font-black uppercase text-slate-500 mb-2 ml-4">Email</label>
                    <input type="email" name="email" :value="old('email')" required
                        class="w-full bg-[#0a0a0b] border-slate-800 border-2 rounded-2xl text-white focus:border-rose-500 focus:ring-0 transition-all"
                        placeholder="votre@email.com">
                    <x-input-error :messages="$errors->get('email')" class="mt-2" />
                </div>

                <div>
                    <label class="block text-[10px] font-black uppercase text-slate-500 mb-2 ml-4">Mot de passe</label>
                    <input type="password" name="password" required
                        class="w-full bg-[#0a0a0b] border-slate-800 border-2 rounded-2xl text-white focus:border-rose-500 focus:ring-0 transition-all">
                    <x-input-error :messages="$errors->get('password')" class="mt-2" />
                </div>

                <div>
                    <label class="block text-[10px] font-black uppercase text-slate-500 mb-2 ml-4">Confirmer</label>
                    <input type="password" name="password_confirmation" required
                        class="w-full bg-[#0a0a0b] border-slate-800 border-2 rounded-2xl text-white focus:border-rose-500 focus:ring-0 transition-all">
                </div>

                <button type="submit" class="w-full bg-white hover:bg-rose-500 hover:text-white text-black font-black py-4 rounded-2xl transition-all transform hover:scale-[1.02] uppercase italic tracking-widest mt-4">
                    Démarrer l'entraînement
                </button>
            </form>

            <div class="mt-8 text-center border-t border-slate-800 pt-6">
                <a href="{{ route('login') }}" class="text-slate-500 text-[10px] font-bold uppercase hover:text-white transition-colors">Déjà membre ? Se connecter</a>
            </div>
        </div>
    </div>
</x-guest-layout>
