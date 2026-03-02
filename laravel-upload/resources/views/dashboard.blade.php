<x-app-layout>
    <div class="min-h-screen bg-[#08090a] text-white pb-24 font-sans selection:bg-rose-500">

        <div class="px-6 pt-12 pb-6 flex items-center justify-between">
            <div>
                <p class="text-[10px] font-black text-rose-500 uppercase tracking-[0.3em] mb-1">Performance Hub</p>
                <h1 class="text-4xl font-black uppercase italic tracking-tighter leading-none">Salut, <span class="text-transparent bg-clip-text bg-gradient-to-r from-white to-slate-500">{{ Auth::user()->name }}</span></h1>
            </div>
            <div class="relative">
                <div class="w-12 h-12 bg-slate-900 border border-slate-800 rounded-2xl flex items-center justify-center shadow-2xl">
                    <svg class="w-6 h-6 text-rose-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                </div>
                <span class="absolute -top-1 -right-1 w-4 h-4 bg-green-500 border-2 border-[#08090a] rounded-full"></span>
            </div>
        </div>

        <div class="px-5 space-y-8">

            <div class="grid grid-cols-2 gap-4">
                <div class="group bg-[#111214] border border-slate-800/50 p-5 rounded-[2.5rem] hover:border-rose-500/50 transition-all duration-500">
                    <div class="w-8 h-8 bg-rose-500/10 rounded-lg flex items-center justify-center mb-3 group-hover:scale-110 transition">
                        <svg class="w-4 h-4 text-rose-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/></svg>
                    </div>
                    <p class="text-[9px] font-bold text-slate-500 uppercase tracking-widest">Volume Hebdo</p>
                    <h3 class="text-2xl font-black mt-1">{{ number_format($totalVolume ?? 0, 0, '.', ' ') }} <span class="text-xs text-slate-600">KG</span></h3>
                </div>

                <div class="group bg-[#111214] border border-slate-800/50 p-5 rounded-[2.5rem] hover:border-blue-500/50 transition-all duration-500">
                    <div class="w-8 h-8 bg-blue-500/10 rounded-lg flex items-center justify-center mb-3 group-hover:scale-110 transition">
                        <svg class="w-4 h-4 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    </div>
                    <p class="text-[9px] font-bold text-slate-500 uppercase tracking-widest">Seances</p>
                    <h3 class="text-2xl font-black mt-1">{{ $workoutCount ?? 0 }}</h3>
                </div>
            </div>

            <div class="relative overflow-hidden bg-gradient-to-br from-rose-600 to-rose-800 rounded-[3rem] p-8 shadow-2xl shadow-rose-900/20">
                <div class="relative z-10 flex justify-between items-center">
                    <div>
                        <h2 class="text-sm font-black uppercase tracking-[0.2em] text-rose-200 mb-1">Poids Actuel</h2>
                        <div class="flex items-baseline gap-2">
                            <span class="text-6xl font-black tracking-tighter">{{ $lastWeight->weight ?? '--' }}</span>
                            <span class="text-2xl font-bold opacity-50 italic text-white">kg</span>
                        </div>
                        <div class="mt-4 flex items-center gap-2 bg-black/20 w-fit px-3 py-1 rounded-full border border-white/10">
                            <span class="text-[10px] font-black uppercase">{{ $weightDiff >= 0 ? '+' : '' }}{{ $weightDiff }} kg</span>
                            <svg class="w-3 h-3 {{ $weightDiff <= 0 ? 'rotate-180 text-green-300' : 'text-orange-300' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 10l7-7m0 0l7 7m-7-7v18"/></svg>
                        </div>
                    </div>
                    <button onclick="toggleWeightModal()" class="w-16 h-16 bg-white rounded-3xl flex items-center justify-center shadow-xl hover:rotate-90 transition-all duration-500 group">
                        <svg class="w-8 h-8 text-rose-600 group-hover:scale-110 transition" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M12 4v16m8-8H4"/></svg>
                    </button>
                </div>
                <div class="absolute -bottom-10 -right-10 w-40 h-40 bg-white/10 rounded-full blur-3xl"></div>

                <!-- Mini Weight Chart -->
                <div class="absolute bottom-4 left-4 right-4 h-20 opacity-60">
                    <canvas id="weightMiniChart"></canvas>
                </div>
            </div>

            <div class="space-y-4 pt-4" x-data="{ showAlternatives: false }">
                <h2 class="text-[10px] font-black uppercase text-slate-600 tracking-[0.4em] px-4">Action Rapide</h2>

                @if($todayRoutine)
                    <div>
                        <form action="{{ route('routines.start-today', $todayRoutine) }}" method="POST" class="block group">
                            @csrf
                            <button type="submit" class="w-full bg-white p-2 rounded-[2.5rem] flex items-center justify-between transition-all duration-300 hover:pr-4">
                                <div class="flex items-center gap-4">
                                    <div class="w-16 h-16 bg-black rounded-[2rem] flex items-center justify-center">
                                        <svg class="w-8 h-8 text-white animate-pulse" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z"/></svg>
                                    </div>
                                    <div class="text-left">
                                        <span class="text-slate-900 text-xl font-black uppercase italic tracking-tight block">{{ $todayRoutine->getTodayWorkout()->name ?? $todayRoutine->name }}</span>
                                        <span class="text-[9px] text-slate-500 font-bold uppercase">{{ $todayRoutine->getTodayWorkout()->exercises->count() }} exercices - Aujourd'hui</span>
                                    </div>
                                </div>
                                <div class="w-12 h-12 rounded-full border-2 border-slate-100 flex items-center justify-center group-hover:bg-black group-hover:border-black transition-all">
                                    <svg class="w-5 h-5 text-slate-300 group-hover:text-white transition" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M9 5l7 7-7 7"/></svg>
                                </div>
                            </button>
                        </form>

                        @if($alternativeRoutines->count() > 0)
                            <button @click="showAlternatives = !showAlternatives"
                                    class="w-full mt-3 py-3 px-4 border-2 border-slate-800 rounded-2xl font-black text-xs uppercase text-slate-400 hover:border-rose-500 hover:text-rose-500 transition-all">
                                <span x-show="!showAlternatives">Choisir une autre seance</span>
                                <span x-show="showAlternatives">Masquer les alternatives</span>
                            </button>
                        @endif
                    </div>

                    <div x-show="showAlternatives"
                         x-transition
                         class="space-y-2 mt-4">
                        @foreach($alternativeRoutines as $routine)
                            <form action="{{ route('routines.start-today', $routine) }}" method="POST">
                                @csrf
                                <button type="submit"
                                        class="w-full bg-[#111214] border border-slate-800 p-4 rounded-2xl flex items-center justify-between hover:border-rose-500 transition-all text-left">
                                    <div>
                                        <h4 class="font-black text-sm">{{ $routine->getTodayWorkout()->name ?? $routine->name }}</h4>
                                        <p class="text-[9px] text-slate-500 font-bold mt-1">{{ $routine->getTodayWorkout()->exercises->count() }} exercices</p>
                                    </div>
                                    <svg class="w-5 h-5 text-slate-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M9 5l7 7-7 7"/>
                                    </svg>
                                </button>
                            </form>
                        @endforeach
                    </div>
                @else
                    <a href="{{ route('routines.index') }}" class="block group">
                        <div class="bg-white p-2 rounded-[2.5rem] flex items-center justify-between transition-all duration-300 hover:pr-4">
                            <div class="flex items-center gap-4">
                                <div class="w-16 h-16 bg-slate-900 rounded-[2rem] flex items-center justify-center">
                                    <svg class="w-8 h-8 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                    </svg>
                                </div>
                                <div class="text-left">
                                    <span class="text-slate-900 text-lg font-black uppercase italic tracking-tight block">Aucune seance planifiee</span>
                                    <span class="text-[9px] text-slate-500 font-bold uppercase">Creer une routine</span>
                                </div>
                            </div>
                            <div class="w-12 h-12 rounded-full border-2 border-slate-100 flex items-center justify-center group-hover:bg-black group-hover:border-black transition-all">
                                <svg class="w-5 h-5 text-slate-300 group-hover:text-white transition" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M9 5l7 7-7 7"/></svg>
                            </div>
                        </div>
                    </a>
                @endif
            </div>

            <div class="space-y-4">
                <div class="flex justify-between items-center px-4">
                    <h2 class="text-[10px] font-black uppercase text-slate-600 tracking-[0.4em]">Historique</h2>
                    <a href="{{ route('stats.index') }}" class="text-[10px] font-black text-rose-500 uppercase italic">Voir tout</a>
                </div>

                <div class="space-y-3">
                    @forelse($recentWorkouts as $workout)
                    <a href="{{ route('workouts.show', $workout) }}"
                       class="bg-[#111214] border border-slate-800/50 p-5 rounded-3xl flex items-center justify-between group hover:border-rose-500/30 hover:bg-slate-900/50 transition-all duration-300 block">
                        <div class="flex items-center gap-4">
                            <div class="w-10 h-10 bg-slate-800 rounded-xl flex items-center justify-center font-black text-rose-500 text-xs">
                                {{ $workout->created_at->format('d') }}
                            </div>
                            <div>
                                <div class="flex items-center gap-2">
                                    <h4 class="font-black uppercase text-sm group-hover:text-rose-500 transition-colors">{{ $workout->title }}</h4>
                                    @if($workout->status === 'completed')
                                        <span class="w-2 h-2 bg-green-500 rounded-full"></span>
                                    @elseif($workout->status === 'in_progress')
                                        <span class="text-[8px] font-black text-amber-500 bg-amber-500/10 px-1.5 py-0.5 rounded-md uppercase">En cours</span>
                                    @endif
                                </div>
                                <p class="text-[10px] font-bold text-slate-500 uppercase tracking-widest">{{ $workout->created_at->diffForHumans() }}</p>
                            </div>
                        </div>
                        <svg class="w-5 h-5 text-slate-700 group-hover:text-rose-500 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M9 5l7 7-7 7"/></svg>
                    </a>
                    @empty
                    <div class="bg-[#111214] border border-slate-800 rounded-3xl p-10 text-center">
                        <p class="text-slate-600 font-bold uppercase text-[10px] tracking-[0.2em]">Aucune séance enregistrée</p>
                        <a href="{{ route('workouts.index') }}" class="inline-block mt-4 px-5 py-2.5 bg-rose-500/10 border border-rose-500/30 text-rose-400 text-xs font-black uppercase rounded-xl hover:bg-rose-500 hover:text-white transition-all">
                            Commencer
                        </a>
                    </div>
                    @endforelse
                </div>
            </div>
        </div>

        <div id="weight-modal" class="fixed inset-0 bg-black/90 backdrop-blur-xl hidden z-50 items-center justify-center p-6 transition-all duration-500">
            <div class="bg-white rounded-[4rem] p-10 w-full max-w-sm relative shadow-[0_0_100px_rgba(225,29,72,0.2)]">
                <button onclick="toggleWeightModal()" class="absolute top-8 right-8 text-slate-300 hover:text-rose-500 transition">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
                <div class="text-center">
                    <p class="text-rose-500 font-black uppercase text-[10px] tracking-[0.3em] mb-4">Mise a jour</p>
                    <h3 class="text-3xl font-black text-slate-900 uppercase italic mb-8">Nouveau Poids</h3>
                    <form action="{{ route('weight.store') }}" method="POST">
                        @csrf
                        <div class="relative mb-10">
                            <input type="number" step="0.1" name="weight" required autofocus
                                   class="bg-transparent border-none text-8xl font-black text-center text-slate-900 focus:ring-0 w-full p-0"
                                   placeholder="00">
                            <span class="absolute bottom-4 right-4 text-2xl font-black text-slate-300 italic">kg</span>
                        </div>
                        <button type="submit" class="w-full bg-slate-900 py-6 rounded-3xl font-black text-white uppercase tracking-[0.2em] shadow-2xl hover:bg-rose-600 transition-colors duration-300 active:scale-95">
                            Confirmer
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        function toggleWeightModal() {
            const modal = document.getElementById('weight-modal');
            if(modal.classList.contains('hidden')) {
                modal.classList.remove('hidden');
                modal.classList.add('flex');
            } else {
                modal.classList.add('hidden');
                modal.classList.remove('flex');
            }
        }

        // Mini Weight Chart
        async function loadMiniWeightChart() {
            try {
                const response = await fetch('/api/stats/weight?period=14');
                const data = await response.json();

                if (data.data && data.data.length > 1) {
                    const ctx = document.getElementById('weightMiniChart').getContext('2d');
                    new Chart(ctx, {
                        type: 'line',
                        data: {
                            labels: data.labels,
                            datasets: [{
                                data: data.data,
                                borderColor: 'rgba(255, 255, 255, 0.3)',
                                backgroundColor: 'rgba(255, 255, 255, 0.1)',
                                fill: true,
                                tension: 0.4,
                                pointRadius: 0
                            }]
                        },
                        options: {
                            responsive: true,
                            maintainAspectRatio: false,
                            plugins: { legend: { display: false } },
                            scales: {
                                x: { display: false },
                                y: { display: false }
                            }
                        }
                    });
                }
            } catch (e) {
                console.log('No weight data available');
            }
        }

        loadMiniWeightChart();
    </script>
</x-app-layout>
