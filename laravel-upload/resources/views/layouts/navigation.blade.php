<nav class="fixed bottom-6 left-1/2 -translate-x-1/2 w-[95%] max-w-md bg-[#111214]/80 backdrop-blur-2xl border border-white/5 rounded-[2.5rem] p-2 shadow-2xl z-50">
    <div class="flex justify-around items-center">

        {{-- Dashboard --}}
        <a href="{{ route('dashboard') }}"
           class="p-3 group flex flex-col items-center gap-1"
           title="Dashboard">
            <svg class="w-6 h-6 transition-colors {{ request()->routeIs('dashboard') ? 'text-rose-500' : 'text-slate-500 group-hover:text-rose-500' }}"
                 fill="{{ request()->routeIs('dashboard') ? 'currentColor' : 'none' }}" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
            </svg>
            @if(request()->routeIs('dashboard'))
                <span class="w-1.5 h-1.5 bg-rose-500 rounded-full"></span>
            @endif
        </a>

        {{-- Historique --}}
        <a href="{{ route('profile.history') }}"
           class="p-3 group flex flex-col items-center gap-1"
           title="Historique">
            <svg class="w-6 h-6 transition-colors {{ request()->routeIs('profile.history') ? 'text-rose-500' : 'text-slate-500 group-hover:text-rose-500' }}"
                 fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
            </svg>
            @if(request()->routeIs('profile.history'))
                <span class="w-1.5 h-1.5 bg-rose-500 rounded-full"></span>
            @endif
        </a>

        {{-- Stats --}}
        <a href="{{ route('stats.index') }}"
           class="p-3 group flex flex-col items-center gap-1"
           title="Statistiques">
            <svg class="w-6 h-6 transition-colors {{ request()->routeIs('stats.index') ? 'text-rose-500' : 'text-slate-500 group-hover:text-rose-500' }}"
                 fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
            </svg>
            @if(request()->routeIs('stats.index'))
                <span class="w-1.5 h-1.5 bg-rose-500 rounded-full"></span>
            @endif
        </a>

        {{-- Séances (bouton central) --}}
        <a href="{{ route('workouts.index') }}"
           class="w-14 h-14 {{ request()->routeIs('workouts.*') ? 'bg-white' : 'bg-rose-500' }} rounded-full flex items-center justify-center -translate-y-4 border-4 border-[#08090a] shadow-[0_10px_20px_rgba(244,63,94,0.3)] transition-all active:scale-90"
           title="Entraînements">
            <svg class="w-7 h-7 {{ request()->routeIs('workouts.*') ? 'text-rose-500' : 'text-white' }}"
                 fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M13 10V3L4 14h7v7l9-11h-7z"/>
            </svg>
        </a>

        {{-- Messages --}}
        <a href="{{ route('messages.index') }}"
           class="p-3 group flex flex-col items-center gap-1 relative"
           title="Messages">
            <svg class="w-6 h-6 transition-colors {{ request()->routeIs('messages.*') ? 'text-rose-500' : 'text-slate-500 group-hover:text-rose-500' }}"
                 fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/>
            </svg>
            @if(request()->routeIs('messages.*'))
                <span class="w-1.5 h-1.5 bg-rose-500 rounded-full"></span>
            @endif
        </a>

        {{-- Profil --}}
        <a href="{{ route('profile.edit') }}"
           class="p-3 group flex flex-col items-center gap-1"
           title="Profil">
            <svg class="w-6 h-6 transition-colors {{ request()->routeIs('profile.edit') ? 'text-rose-500' : 'text-slate-500 group-hover:text-rose-500' }}"
                 fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
            </svg>
            @if(request()->routeIs('profile.edit'))
                <span class="w-1.5 h-1.5 bg-rose-500 rounded-full"></span>
            @endif
        </a>

    </div>
</nav>
