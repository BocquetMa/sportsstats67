<x-app-layout>
    <div class="min-h-screen bg-[#08090a] text-white pb-24 font-sans selection:bg-rose-500">

        <div class="px-6 pt-12 pb-6">
            <p class="text-[10px] font-black text-rose-500 uppercase tracking-[0.3em] mb-1">Analytics</p>
            <h1 class="text-4xl font-black uppercase italic tracking-tighter leading-none">Tes <span class="text-transparent bg-clip-text bg-gradient-to-r from-rose-500 to-orange-400">Statistiques</span></h1>
        </div>

        <div class="px-5 space-y-6">

            <!-- Weight Chart -->
            <div class="bg-[#111214] border border-slate-800/50 p-6 rounded-[2.5rem]">
                <div class="flex justify-between items-center mb-4">
                    <h2 class="text-[10px] font-black uppercase text-slate-400 tracking-[0.3em]">Évolution du Poids</h2>
                    <select id="weight-period" class="bg-transparent text-xs text-slate-500 border border-slate-700 rounded-lg px-2 py-1">
                        <option value="7">7 jours</option>
                        <option value="30" selected>30 jours</option>
                        <option value="90">90 jours</option>
                    </select>
                </div>
                <div class="relative h-48">
                    <canvas id="weightChart"></canvas>
                </div>
                <div id="weight-stats" class="flex justify-between mt-4 pt-4 border-t border-slate-800">
                    <!-- Rempli via JS -->
                </div>
            </div>

            <!-- Volume Chart -->
            <div class="bg-[#111214] border border-slate-800/50 p-6 rounded-[2.5rem]">
                <h2 class="text-[10px] font-black uppercase text-slate-400 tracking-[0.3em] mb-4">Volume Hebdomadaire</h2>
                <div class="relative h-48">
                    <canvas id="volumeChart"></canvas>
                </div>
            </div>

            <!-- Exercise Progress -->
            <div class="bg-[#111214] border border-slate-800/50 p-6 rounded-[2.5rem]">
                <h2 class="text-[10px] font-black uppercase text-slate-400 tracking-[0.3em] mb-4">Progression par Exercice</h2>
                <select id="exercise-select" class="w-full bg-slate-800 text-white text-sm rounded-xl px-4 py-3 mb-4">
                    <option value="">Sélectionne un exercice</option>
                    @foreach($topExercises as $exercise)
                        <option value="{{ $exercise->exercise_id }}">{{ $exercise->exercise->name }}</option>
                    @endforeach
                </select>
                <div class="relative h-48">
                    <canvas id="exerciseChart"></canvas>
                </div>
            </div>

            <!-- Quick Stats -->
            <div class="grid grid-cols-2 gap-4">
                <div class="bg-[#111214] border border-slate-800/50 p-5 rounded-[2.5rem] text-center">
                    <p class="text-[9px] font-bold text-slate-500 uppercase tracking-widest">Total Séances</p>
                    <h3 class="text-3xl font-black mt-1 text-rose-500" id="stat-workouts">--</h3>
                </div>
                <div class="bg-[#111214] border border-slate-800/50 p-5 rounded-[2.5rem] text-center">
                    <p class="text-[9px] font-bold text-slate-500 uppercase tracking-widest">Volume Total</p>
                    <h3 class="text-3xl font-black mt-1 text-blue-500"><span id="stat-volume">--</span>T</h3>
                </div>
                <div class="bg-[#111214] border border-slate-800/50 p-5 rounded-[2.5rem] text-center">
                    <p class="text-[9px] font-bold text-slate-500 uppercase tracking-widest">Cette Semaine</p>
                    <h3 class="text-3xl font-black mt-1 text-green-500" id="stat-week">--</h3>
                </div>
                <div class="bg-[#111214] border border-slate-800/50 p-5 rounded-[2.5rem] text-center">
                    <p class="text-[9px] font-bold text-slate-500 uppercase tracking-widest">Minutes</p>
                    <h3 class="text-3xl font-black mt-1 text-orange-500" id="stat-minutes">--</h3>
                </div>
            </div>

            <!-- Top Exercises -->
            <div class="bg-[#111214] border border-slate-800/50 p-6 rounded-[2.5rem]">
                <h2 class="text-[10px] font-black uppercase text-slate-400 tracking-[0.3em] mb-4">Exercices les Plus Pratiqués</h2>
                <div class="space-y-3">
                    @foreach($topExercises as $index => $exercise)
                        <div class="flex items-center justify-between p-3 bg-slate-800/50 rounded-xl">
                            <div class="flex items-center gap-3">
                                <span class="w-6 h-6 bg-rose-500 rounded-lg flex items-center justify-center text-xs font-black">{{ $index + 1 }}</span>
                                <span class="font-bold text-sm">{{ $exercise->exercise->name }}</span>
                            </div>
                            <span class="text-xs text-slate-500">{{ $exercise->total_sets }} séries</span>
                        </div>
                    @endforeach
                </div>
            </div>

        </div>

        {{-- Liens rapides --}}
        <div class="grid grid-cols-2 gap-3">
            <a href="{{ route('evolution.index') }}"
               class="flex items-center justify-center gap-2 py-4 bg-[#111214] border border-slate-800 rounded-2xl font-black text-xs uppercase tracking-wider text-slate-400 hover:border-rose-500/50 hover:text-rose-500 transition-all">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/></svg>
                Évolution
            </a>
            <a href="{{ route('evolution.personal-bests') }}"
               class="flex items-center justify-center gap-2 py-4 bg-[#111214] border border-slate-800 rounded-2xl font-black text-xs uppercase tracking-wider text-slate-400 hover:border-rose-500/50 hover:text-rose-500 transition-all"
               id="pb-link">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z"/></svg>
                Records Perso
            </a>
        </div>
    </div>

    @push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        // Configuration commune pour les graphiques
        Chart.defaults.color = '#64748b';
        Chart.defaults.font.family = 'ui-sans-serif, system-ui';

        // Weight Chart
        let weightChart = null;
        async function loadWeightChart(period = 30) {
            const response = await fetch(`/api/stats/weight?period=${period}`);
            const data = await response.json();

            const ctx = document.getElementById('weightChart').getContext('2d');

            if (weightChart) weightChart.destroy();

            weightChart = new Chart(ctx, {
                type: 'line',
                data: {
                    labels: data.labels,
                    datasets: [{
                        label: 'Poids (kg)',
                        data: data.data,
                        borderColor: '#f43f5e',
                        backgroundColor: 'rgba(244, 63, 94, 0.1)',
                        fill: true,
                        tension: 0.4,
                        pointBackgroundColor: '#f43f5e',
                        pointBorderColor: '#fff',
                        pointBorderWidth: 2,
                        pointRadius: 4
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: { display: false }
                    },
                    scales: {
                        x: { grid: { display: false } },
                        y: { grid: { color: '#1e293b' } }
                    }
                }
            });

            // Update stats
            const statsHtml = `
                <div><span class="text-slate-500 text-xs">Actuel</span><p class="font-black text-lg">${data.current || '--'} kg</p></div>
                <div><span class="text-slate-500 text-xs">Début</span><p class="font-black text-lg">${data.start || '--'} kg</p></div>
                <div><span class="text-slate-500 text-xs">Variation</span><p class="font-black text-lg ${data.change > 0 ? 'text-red-400' : 'text-green-400'}">${data.change ? (data.change > 0 ? '+' : '') + data.change + ' kg' : '--'}</p></div>
            `;
            document.getElementById('weight-stats').innerHTML = statsHtml;
        }

        // Volume Chart
        let volumeChart = null;
        async function loadVolumeChart() {
            const response = await fetch('/api/stats/volume?weeks=8');
            const data = await response.json();

            const ctx = document.getElementById('volumeChart').getContext('2d');

            if (volumeChart) volumeChart.destroy();

            volumeChart = new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: data.labels,
                    datasets: [{
                        label: 'Volume (kg)',
                        data: data.data,
                        backgroundColor: '#3b82f6',
                        borderRadius: 8
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: { legend: { display: false } },
                    scales: {
                        x: { grid: { display: false } },
                        y: { grid: { color: '#1e293b' } }
                    }
                }
            });
        }

        // Exercise Chart
        let exerciseChart = null;
        async function loadExerciseChart(exerciseId) {
            if (!exerciseId) {
                if (exerciseChart) exerciseChart.destroy();
                return;
            }

            const response = await fetch(`/api/stats/exercise?exercise_id=${exerciseId}&limit=15`);
            const data = await response.json();

            const ctx = document.getElementById('exerciseChart').getContext('2d');

            if (exerciseChart) exerciseChart.destroy();

            exerciseChart = new Chart(ctx, {
                type: 'line',
                data: {
                    labels: data.labels,
                    datasets: [{
                        label: 'Max (kg)',
                        data: data.max_weights,
                        borderColor: '#10b981',
                        backgroundColor: 'rgba(16, 185, 129, 0.1)',
                        fill: true,
                        tension: 0.4
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: { legend: { display: false } },
                    scales: {
                        x: { grid: { display: false } },
                        y: { grid: { color: '#1e293b' } }
                    }
                }
            });
        }

        // Dashboard Stats
        async function loadDashboardStats() {
            const response = await fetch('/api/stats/dashboard');
            const data = await response.json();

            document.getElementById('stat-workouts').textContent = data.total_workouts;
            document.getElementById('stat-volume').textContent = data.total_volume;
            document.getElementById('stat-week').textContent = data.this_week;
            document.getElementById('stat-minutes').textContent = Math.round(data.total_minutes / 60) + 'h';
        }

        // Event Listeners
        document.getElementById('weight-period').addEventListener('change', (e) => {
            loadWeightChart(e.target.value);
        });

        document.getElementById('exercise-select').addEventListener('change', (e) => {
            loadExerciseChart(e.target.value);
        });

        // Initialize
        loadWeightChart();
        loadVolumeChart();
        loadDashboardStats();
    </script>
    @endpush
</x-app-layout>
