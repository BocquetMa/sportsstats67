<x-guest-layout>
    <div class="min-h-screen flex flex-col justify-center items-center bg-[#0a0a0b] px-4">
        <div class="mb-6 relative">
            <div class="w-20 h-20 bg-rose-500/10 border-2 border-dashed border-rose-500/40 rounded-full flex items-center justify-center animate-spin-slow">
                <svg class="w-10 h-10 text-rose-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                </svg>
            </div>
        </div>

        <div class="mb-8 text-center">
            <h1 class="text-3xl font-black italic uppercase tracking-tighter text-white">
                Vérification<span class="text-rose-500">.</span>
            </h1>
            <p class="text-slate-500 text-[10px] font-bold uppercase tracking-[0.2em] mt-3 max-w-[300px] mx-auto leading-relaxed">
                Merci de nous avoir rejoint ! Clique sur le lien qu'on vient de t'envoyer par email pour valider ton profil.
            </p>
        </div>

        <div class="w-full max-w-md bg-[#111214] border border-slate-800 p-8 rounded-[2.5rem] shadow-2xl text-center">

            @if (session('status') == 'verification-link-sent')
                <div class="mb-6 p-4 bg-green-500/10 border border-green-500/20 rounded-2xl text-green-500 font-bold text-[10px] uppercase tracking-widest">
                    Un nouveau lien a été envoyé à ton adresse email.
                </div>
            @endif

            <div class="space-y-4">
                <form method="POST" action="{{ route('verification.send') }}">
                    @csrf
                    <button type="submit" class="w-full bg-rose-500 hover:bg-rose-600 text-white font-black py-4 rounded-2xl transition-all transform hover:scale-[1.02] uppercase italic tracking-widest">
                        Renvoyer l'email
                    </button>
                </form>

                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="text-slate-500 text-[10px] font-bold uppercase hover:text-white transition-colors">
                        Se déconnecter
                    </button>
                </form>
            </div>
        </div>
    </div>
</x-guest-layout>

<style>
    @keyframes spin-slow {
        from { transform: rotate(0deg); }
        to { transform: rotate(360deg); }
    }
    .animate-spin-slow {
        animation: spin-slow 8s linear infinite;
    }
</style>
