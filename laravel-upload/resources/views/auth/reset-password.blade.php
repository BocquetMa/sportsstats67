<x-guest-layout>
    <div class="min-h-screen flex flex-col justify-center items-center bg-[#0a0a0b] px-4">
        <div class="mb-8 text-center">
            <h1 class="text-3xl font-black italic uppercase tracking-tighter text-white">
                Nouveau<span class="text-rose-500 text-5xl">.</span>Départ
            </h1>
            <p class="text-slate-500 text-[10px] font-bold uppercase tracking-[0.2em]">Sécurisez votre profil</p>
        </div>

        <div class="w-full max-w-md bg-[#111214] border border-slate-800 p-8 rounded-[2.5rem] shadow-2xl">
            <form method="POST" action="{{ route('password.store') }}" class="space-y-5">
                @csrf

                <input type="hidden" name="token" value="{{ $request->route('token') }}">

                <div>
                    <label class="block text-[10px] font-black uppercase text-slate-500 mb-2 ml-4">Email</label>
                    <input type="email" name="email" value="{{ old('email', $request->email) }}" required autofocus
                        class="w-full bg-[#0a0a0b] border-slate-800 border-2 rounded-2xl text-white focus:border-rose-500 focus:ring-0 transition-all placeholder-slate-700">
                    <x-input-error :messages="$errors->get('email')" class="mt-2" />
                </div>

                <div>
                    <label class="block text-[10px] font-black uppercase text-slate-500 mb-2 ml-4">Nouveau mot de passe</label>
                    <input type="password" name="password" required autocomplete="new-password"
                        class="w-full bg-[#0a0a0b] border-slate-800 border-2 rounded-2xl text-white focus:border-rose-500 focus:ring-0 transition-all">
                    <x-input-error :messages="$errors->get('password')" class="mt-2" />
                </div>

                <div>
                    <label class="block text-[10px] font-black uppercase text-slate-500 mb-2 ml-4">Confirmation</label>
                    <input type="password" name="password_confirmation" required autocomplete="new-password"
                        class="w-full bg-[#0a0a0b] border-slate-800 border-2 rounded-2xl text-white focus:border-rose-500 focus:ring-0 transition-all">
                    <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
                </div>

                <div class="pt-2">
                    <button type="submit" class="w-full bg-rose-500 hover:bg-rose-600 text-white font-black py-4 rounded-2xl transition-all transform hover:scale-[1.02] uppercase italic tracking-widest">
                        Réinitialiser
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-guest-layout>
