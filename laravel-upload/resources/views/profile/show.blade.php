<x-app-layout>
    <div class="min-h-screen bg-[#08090a] text-white pb-32">

        <div class="relative h-72 w-full">
            @if($user->cover_photo)
                <img src="{{ asset('storage/' . $user->cover_photo) }}" class="w-full h-full object-cover">
            @else
                <div class="w-full h-full bg-gradient-to-br from-rose-600/30 via-[#111214] to-[#08090a]"></div>
            @endif

            <div class="absolute inset-0 bg-gradient-to-t from-[#08090a] to-transparent"></div>

            <a href="javascript:history.back()" class="absolute top-6 left-6 p-3 bg-black/20 backdrop-blur-xl rounded-2xl border border-white/5 hover:bg-rose-500/20 transition-all z-20">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 19l-7-7 7-7"/></svg>
            </a>
        </div>

        <div class="px-6 -mt-20 relative z-10">

            <div class="flex items-end justify-between mb-8">
                <div class="relative">
                    <div class="w-32 h-32 rounded-[2.5rem] bg-[#111214] border-[6px] border-[#08090a] overflow-hidden shadow-2xl">
                        @if($user->avatar)
                            <img src="{{ asset('storage/' . $user->avatar) }}" class="w-full h-full object-cover">
                        @else
                            <div class="w-full h-full flex items-center justify-center text-4xl font-black italic text-rose-500 bg-[#1a1b1e]">
                                {{ substr($user->name, 0, 1) }}
                            </div>
                        @endif
                    </div>
                    <div class="absolute -bottom-2 -right-2 bg-[#08090a] p-1 rounded-xl">
                        <span class="px-3 py-1 rounded-lg border {{ $user->rank['border'] ?? 'border-slate-800' }} {{ $user->rank['color'] ?? 'text-slate-500' }} text-[10px] font-black italic tracking-tighter shadow-lg">
                            {{ $user->rank['label'] ?? 'NOVICE' }}
                        </span>
                    </div>
                </div>

                <div class="flex gap-3 pb-2">
                    @if($user->id === auth()->id())
                        <a href="{{ route('profile.edit') }}" class="bg-white/5 hover:bg-white/10 backdrop-blur-md border border-white/10 px-6 py-3 rounded-2xl font-black uppercase italic text-[10px] tracking-widest transition-all">
                            Paramètres
                        </a>
                    @else
                        <a href="{{ route('messages.show', $user) }}" class="bg-rose-500 hover:bg-rose-600 px-8 py-3 rounded-2xl font-black uppercase italic text-[10px] tracking-widest shadow-lg shadow-rose-500/25 transition-all">
                            Message
                        </a>
                    @endif
                </div>
            </div>

            <div class="space-y-4">
                <div>
                    <h1 class="text-4xl font-black italic uppercase tracking-tighter leading-none">{{ $user->name }}</h1>
                    <div class="flex items-center gap-2 mt-2">
                        <div class="h-1 w-12 bg-rose-500 rounded-full"></div>
                        <span class="text-xs font-bold text-slate-500 uppercase tracking-[0.3em]">Athlète Certifié</span>
                    </div>
                </div>

                <p class="text-slate-400 text-sm leading-relaxed max-w-sm italic font-medium">
                    "{{ $user->bio ?? 'La performance est un langage silencieux.' }}"
                </p>
            </div>

            <div class="grid grid-cols-2 gap-4 mt-10">
                <div class="bg-[#111214] border border-white/5 p-6 rounded-[2rem] relative overflow-hidden group">
                    <div class="absolute -right-4 -top-4 w-16 h-16 bg-rose-500/5 rounded-full group-hover:scale-150 transition-transform duration-500"></div>
                    <span class="block text-3xl font-black italic text-white">{{ $user->xp ?? 0 }}</span>
                    <span class="text-[10px] uppercase font-black text-slate-600 tracking-[0.2em]">Total XP Point</span>
                </div>

                <div class="bg-[#111214] border border-white/5 p-6 rounded-[2rem] relative overflow-hidden group">
                    <div class="absolute -right-4 -top-4 w-16 h-16 bg-blue-500/5 rounded-full group-hover:scale-150 transition-transform duration-500"></div>
                    <span class="block text-3xl font-black italic text-white">{{ $user->created_at->diffForHumans(null, true) }}</span>
                    <span class="text-[10px] uppercase font-black text-slate-600 tracking-[0.2em]">D'ancienneté</span>
                </div>
            </div>

            <div class="mt-8 bg-[#111214] border border-white/5 p-6 rounded-[2rem]">
                <div class="flex justify-between items-center mb-4">
                    <span class="text-[10px] font-black uppercase tracking-widest text-slate-400">Progression Rang</span>
                    <span class="text-[10px] font-black text-rose-500 uppercase italic">Objectif: 1000 XP</span>
                </div>
                <div class="w-full h-3 bg-black rounded-full overflow-hidden border border-white/5 p-[2px]">
                    <div class="h-full bg-gradient-to-r from-rose-500 to-rose-300 rounded-full shadow-[0_0_15px_rgba(244,63,94,0.4)]"
                         style="width: {{ min((($user->xp ?? 0) / 1000) * 100, 100) }}%"></div>
                </div>
            </div>

            <div class="mt-8 bg-[#111214]/50 border border-white/5 p-6 rounded-[2rem]">
                <div class="flex justify-between items-end mb-6">
                    <div>
                        <h3 class="text-[10px] font-black uppercase tracking-[0.3em] text-slate-600">Planning Hebdo</h3>
                        <p class="text-[9px] text-slate-500 uppercase mt-1 font-bold">Disponibilité à la salle</p>
                    </div>
                    <span class="text-[10px] font-black text-rose-500 uppercase tracking-widest italic bg-rose-500/10 px-3 py-1 rounded-lg">
                        Fréquence : {{ $user->workouts->count() }} / 7
                    </span>
                </div>

                <div class="flex justify-between gap-2">
                    @php
                        $days = ['Lundi', 'Mardi', 'Mercredi', 'Jeudi', 'Vendredi', 'Samedi', 'Dimanche'];
                        $userWorkouts = $user->workouts->pluck('day')->toArray();
                    @endphp

                    @foreach($days as $day)
                        @php $isTraining = in_array($day, $userWorkouts); @endphp
                        <div class="flex-1 flex flex-col items-center gap-3">
                            <div class="w-full h-16 rounded-2xl border transition-all duration-500 {{ $isTraining ? 'border-rose-500 bg-rose-500 shadow-[0_0_20px_rgba(244,63,94,0.3)]' : 'border-white/5 bg-[#08090a]' }} flex items-center justify-center">
                                @if($isTraining)
                                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                                    </svg>
                                @else
                                    <div class="w-1.5 h-1.5 rounded-full bg-slate-800"></div>
                                @endif
                            </div>
                            <span class="text-[10px] font-black uppercase {{ $isTraining ? 'text-rose-500' : 'text-slate-600' }}">
                                {{ substr($day, 0, 1) }}
                            </span>
                        </div>
                    @endforeach
                </div>
            </div>

        </div>

        @include('layouts.navigation')
    </div>
</x-app-layout>
