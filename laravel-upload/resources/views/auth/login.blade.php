<x-guest-layout>
    <div class="min-h-screen flex flex-col justify-center items-center bg-[#0a0a0b] px-4">
        <div class="mb-8 text-center">
            <h1 class="text-4xl font-black italic uppercase tracking-tighter text-white">
                Sport<span class="text-rose-500 text-5xl">.</span>Stats
            </h1>
            <p class="text-slate-500 text-xs font-bold uppercase tracking-[0.3em]">Accès Athlète</p>
        </div>

        <div class="w-full max-w-md bg-[#111214] border border-slate-800 p-8 rounded-[2.5rem] shadow-2xl">
            <form method="POST" action="{{ route('login') }}" class="space-y-6">
                @csrf

                <div>
                    <label class="block text-[10px] font-black uppercase text-slate-500 mb-2 ml-4">Email</label>
                    <input type="email" name="email" :value="old('email')" required autofocus
                        class="w-full bg-[#0a0a0b] border-slate-800 border-2 rounded-2xl text-white focus:border-rose-500 focus:ring-0 transition-all placeholder-slate-700"
                        placeholder="nom@exemple.com">
                    <x-input-error :messages="$errors->get('email')" class="mt-2" />
                </div>

                <div>
                    <div class="flex justify-between items-center mb-2 ml-4">
                        <label class="text-[10px] font-black uppercase text-slate-500">Mot de passe</label>
                        @if (Route::has('password.request'))
                            <a href="{{ route('password.request') }}" class="text-[9px] font-bold text-rose-500 uppercase hover:underline">Oublié ?</a>
                        @endif
                    </div>
                    <input type="password" name="password" required
                        class="w-full bg-[#0a0a0b] border-slate-800 border-2 rounded-2xl text-white focus:border-rose-500 focus:ring-0 transition-all"
                        placeholder="••••••••">
                    <x-input-error :messages="$errors->get('password')" class="mt-2" />
                </div>

                <div class="flex items-center ml-4">
                    <input id="remember_me" type="checkbox" name="remember" class="rounded border-slate-800 bg-[#0a0a0b] text-rose-500 focus:ring-rose-500">
                    <span class="ml-2 text-[10px] font-bold text-slate-500 uppercase">Rester connecté</span>
                </div>

                <button type="submit" class="w-full bg-rose-500 hover:bg-rose-600 text-white font-black py-4 rounded-2xl transition-all transform hover:scale-[1.02] uppercase italic tracking-widest">
                    Se Connecter
                </button>
            </form>

            <div class="mt-8 text-center">
                <p class="text-slate-500 text-[10px] font-bold uppercase">Pas encore de compte ?</p>
                <a href="{{ route('register') }}" class="text-white font-black uppercase italic hover:text-rose-500 transition-colors">Créer un profil athlète</a>
            </div>
        </div>
    </div>
</x-guest-layout>
