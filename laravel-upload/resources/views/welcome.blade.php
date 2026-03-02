<x-guest-layout>
    {{-- Le slot header est obligatoire pour le layout de base de Laravel Breeze --}}
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight hidden">
            {{ __('Welcome') }}
        </h2>
    </x-slot>

    <div class="min-h-screen bg-[#08090a] text-white flex flex-col items-center justify-center px-6 relative overflow-hidden">
        
        {{-- Décoration de fond (Cercle de lumière rose) --}}
        <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-[500px] h-[500px] bg-rose-500/10 rounded-full blur-[120px] -z-0"></div>

        <div class="relative z-10 flex flex-col items-center">
            {{-- Badge --}}
            <p class="text-[10px] font-black text-rose-500 uppercase tracking-[0.3em] mb-4 text-center">
                Sport App Evolution
            </p>

            {{-- Titre Principal --}}
            <h1 class="text-6xl md:text-8xl font-black uppercase italic tracking-tighter leading-none text-center mb-8">
                PUSH YOUR <span class="text-rose-500 drop-shadow-[0_0_15px_rgba(244,63,94,0.3)]">LIMITS</span>
            </h1>

            {{-- Sous-titre --}}
            <p class="text-slate-400 font-bold uppercase text-sm tracking-wider mb-12 text-center max-w-md">
                Prêt à transformer tes efforts en résultats ? Suis tes entraînements avec précision.
            </p>

            {{-- Boutons d'actions --}}
            <div class="flex flex-col sm:flex-row gap-4 w-full max-w-md">
                @if (Route::has('login'))
                    @auth
                        <a href="{{ url('/dashboard') }}"
                           class="flex-1 bg-gradient-to-r from-rose-500 to-rose-600 text-white font-black px-8 py-5 rounded-2xl transition-all shadow-xl shadow-rose-500/20 hover:scale-[1.02] text-center uppercase tracking-wider">
                            Accéder au Dashboard
                        </a>
                    @else
                        <a href="{{ route('login') }}"
                           class="flex-1 bg-gradient-to-r from-rose-500 to-rose-600 text-white font-black px-8 py-5 rounded-2xl transition-all shadow-xl shadow-rose-500/20 hover:scale-[1.02] text-center uppercase tracking-wider">
                            Se connecter
                        </a>

                        @if (Route::has('register'))
                            <a href="{{ route('register') }}"
                               class="flex-1 bg-white text-black font-black px-8 py-5 rounded-2xl transition-all hover:bg-slate-200 text-center uppercase tracking-wider">
                                S'inscrire
                            </a>
                        @endif
                    @endauth
                @endif
            </div>
        </div>
    </div>
</x-guest-layout>
