<x-app-layout>
    <div class="min-h-screen bg-[#08090a] text-white pb-28">

        <div class="px-6 pt-12 pb-6">
            <p class="text-[10px] font-black text-rose-500 uppercase tracking-[0.3em] mb-1">Analytics</p>
            <h1 class="text-4xl font-black uppercase italic tracking-tighter leading-none">Évolution</h1>
        </div>

        <div class="px-5 space-y-5">

            {{-- Tabs --}}
            <div class="flex gap-2 overflow-x-auto pb-1 scrollbar-hide">
                <button onclick="showTab('weight')" data-tab="weight"
                        class="tab-btn flex-shrink-0 px-4 py-2.5 rounded-2xl text-xs font-black uppercase tracking-wider transition-all">
                    Poids
                </button>
                <button onclick="showTab('1rm')" data-tab="1rm"
                        class="tab-btn flex-shrink-0 px-4 py-2.5 rounded-2xl text-xs font-black uppercase tracking-wider transition-all">
                    1RM
                </button>
                <button onclick="showTab('progress')" data-tab="progress"
                        class="tab-btn flex-shrink-0 px-4 py-2.5 rounded-2xl text-xs font-black uppercase tracking-wider transition-all">
                    Progression
                </button>
                <button onclick="showTab('photos')" data-tab="photos"
                        class="tab-btn flex-shrink-0 px-4 py-2.5 rounded-2xl text-xs font-black uppercase tracking-wider transition-all">
                    Photos
                </button>
                <button onclick="showTab('calories')" data-tab="calories"
                        class="tab-btn flex-shrink-0 px-4 py-2.5 rounded-2xl text-xs font-black uppercase tracking-wider transition-all">
                    Calories
                </button>
            </div>

            {{-- Tab: Poids & Mesures --}}
            <div id="tab-weight" class="tab-content space-y-4">

                {{-- Graphique poids --}}
                <div class="bg-[#111214] border border-slate-800/50 p-6 rounded-[2.5rem]">
                    <div class="flex justify-between items-center mb-4">
                        <h2 class="text-[10px] font-black uppercase text-slate-400 tracking-[0.3em]">Évolution du Poids</h2>
                        <select id="weight-period" onchange="loadBodyMetrics()"
                                class="bg-[#08090a] border border-slate-700 text-xs text-slate-400 rounded-xl px-3 py-1.5">
                            <option value="30">30 jours</option>
                            <option value="90" selected>90 jours</option>
                            <option value="180">6 mois</option>
                            <option value="365">1 an</option>
                        </select>
                    </div>
                    <div class="relative h-48">
                        <canvas id="weightChart"></canvas>
                    </div>
                </div>

                {{-- Ajouter mesure --}}
                <div class="bg-[#111214] border border-slate-800/50 p-6 rounded-[2.5rem]">
                    <h2 class="text-[10px] font-black uppercase text-slate-400 tracking-[0.3em] mb-4">Nouvelle Mesure</h2>
                    <form action="{{ route('evolution.body-metric.store') }}" method="POST" class="space-y-4">
                        @csrf
                        <div class="grid grid-cols-2 gap-3">
                            <div class="col-span-2">
                                <label class="block text-[9px] font-bold text-slate-500 uppercase tracking-widest mb-1.5">Poids (kg) *</label>
                                <input type="number" step="0.1" name="weight" required placeholder="75.5"
                                       class="w-full bg-[#08090a] border border-slate-800 rounded-2xl px-4 py-3 text-white text-sm font-bold focus:outline-none focus:border-rose-500 transition-colors">
                            </div>
                            <div>
                                <label class="block text-[9px] font-bold text-slate-500 uppercase tracking-widest mb-1.5">% Graisse</label>
                                <input type="number" step="0.1" name="body_fat" placeholder="15"
                                       class="w-full bg-[#08090a] border border-slate-800 rounded-2xl px-4 py-3 text-white text-sm font-bold focus:outline-none focus:border-rose-500 transition-colors">
                            </div>
                            <div>
                                <label class="block text-[9px] font-bold text-slate-500 uppercase tracking-widest mb-1.5">Poitrine (cm)</label>
                                <input type="number" step="0.1" name="chest" placeholder="95"
                                       class="w-full bg-[#08090a] border border-slate-800 rounded-2xl px-4 py-3 text-white text-sm font-bold focus:outline-none focus:border-rose-500 transition-colors">
                            </div>
                            <div>
                                <label class="block text-[9px] font-bold text-slate-500 uppercase tracking-widest mb-1.5">Taille (cm)</label>
                                <input type="number" step="0.1" name="waist" placeholder="80"
                                       class="w-full bg-[#08090a] border border-slate-800 rounded-2xl px-4 py-3 text-white text-sm font-bold focus:outline-none focus:border-rose-500 transition-colors">
                            </div>
                            <div>
                                <label class="block text-[9px] font-bold text-slate-500 uppercase tracking-widest mb-1.5">Hanches (cm)</label>
                                <input type="number" step="0.1" name="hips" placeholder="95"
                                       class="w-full bg-[#08090a] border border-slate-800 rounded-2xl px-4 py-3 text-white text-sm font-bold focus:outline-none focus:border-rose-500 transition-colors">
                            </div>
                            <div>
                                <label class="block text-[9px] font-bold text-slate-500 uppercase tracking-widest mb-1.5">Bras (cm)</label>
                                <input type="number" step="0.1" name="arms" placeholder="36"
                                       class="w-full bg-[#08090a] border border-slate-800 rounded-2xl px-4 py-3 text-white text-sm font-bold focus:outline-none focus:border-rose-500 transition-colors">
                            </div>
                            <div>
                                <label class="block text-[9px] font-bold text-slate-500 uppercase tracking-widest mb-1.5">Cuisses (cm)</label>
                                <input type="number" step="0.1" name="thighs" placeholder="55"
                                       class="w-full bg-[#08090a] border border-slate-800 rounded-2xl px-4 py-3 text-white text-sm font-bold focus:outline-none focus:border-rose-500 transition-colors">
                            </div>
                            <div>
                                <label class="block text-[9px] font-bold text-slate-500 uppercase tracking-widest mb-1.5">Mollets (cm)</label>
                                <input type="number" step="0.1" name="calves" placeholder="38"
                                       class="w-full bg-[#08090a] border border-slate-800 rounded-2xl px-4 py-3 text-white text-sm font-bold focus:outline-none focus:border-rose-500 transition-colors">
                            </div>
                        </div>
                        <button type="submit"
                                class="w-full bg-rose-500 hover:bg-rose-600 active:scale-95 rounded-2xl py-3.5 font-black text-sm uppercase tracking-wider transition-all">
                            Enregistrer la Mesure
                        </button>
                    </form>
                </div>

                {{-- Graphique mesures corporelles --}}
                <div class="bg-[#111214] border border-slate-800/50 p-6 rounded-[2.5rem]">
                    <h2 class="text-[10px] font-black uppercase text-slate-400 tracking-[0.3em] mb-4">Mesures Corporelles</h2>
                    <div class="relative h-48">
                        <canvas id="measurementsChart"></canvas>
                    </div>
                </div>
            </div>

            {{-- Tab: 1RM Calculator --}}
            <div id="tab-1rm" class="tab-content hidden space-y-4">

                <div class="bg-[#111214] border border-slate-800/50 p-6 rounded-[2.5rem]">
                    <h2 class="text-[10px] font-black uppercase text-slate-400 tracking-[0.3em] mb-4">Calculateur 1RM</h2>
                    <form id="1rm-form" class="space-y-4">
                        <div class="grid grid-cols-2 gap-3">
                            <div>
                                <label class="block text-[9px] font-bold text-slate-500 uppercase tracking-widest mb-1.5">Poids (kg)</label>
                                <input type="number" step="0.5" id="rm-weight" required placeholder="100"
                                       class="w-full bg-[#08090a] border border-slate-800 rounded-2xl px-4 py-3 text-white text-sm font-bold focus:outline-none focus:border-rose-500 transition-colors">
                            </div>
                            <div>
                                <label class="block text-[9px] font-bold text-slate-500 uppercase tracking-widest mb-1.5">Répétitions</label>
                                <input type="number" id="rm-reps" required placeholder="5"
                                       class="w-full bg-[#08090a] border border-slate-800 rounded-2xl px-4 py-3 text-white text-sm font-bold focus:outline-none focus:border-rose-500 transition-colors">
                            </div>
                        </div>
                        <button type="submit"
                                class="w-full bg-rose-500 hover:bg-rose-600 active:scale-95 rounded-2xl py-3.5 font-black text-sm uppercase tracking-wider transition-all">
                            Calculer mon 1RM
                        </button>
                    </form>

                    <div id="1rm-results" class="mt-6 hidden">
                        <div class="text-center py-4 border-t border-slate-800">
                            <p class="text-[9px] font-bold text-slate-500 uppercase tracking-widest mb-1">Estimation Moyenne</p>
                            <p class="text-5xl font-black text-rose-500" id="rm-average">-- kg</p>
                        </div>

                        <div class="grid grid-cols-2 gap-2 mt-4">
                            <div class="bg-[#08090a] border border-slate-800 rounded-2xl p-3 text-center">
                                <p class="text-[8px] font-bold text-slate-600 uppercase">Epley</p>
                                <p class="font-black text-sm mt-0.5" id="rm-epley">--</p>
                            </div>
                            <div class="bg-[#08090a] border border-slate-800 rounded-2xl p-3 text-center">
                                <p class="text-[8px] font-bold text-slate-600 uppercase">Brzycki</p>
                                <p class="font-black text-sm mt-0.5" id="rm-brzycki">--</p>
                            </div>
                            <div class="bg-[#08090a] border border-slate-800 rounded-2xl p-3 text-center">
                                <p class="text-[8px] font-bold text-slate-600 uppercase">Lander</p>
                                <p class="font-black text-sm mt-0.5" id="rm-lander">--</p>
                            </div>
                            <div class="bg-[#08090a] border border-slate-800 rounded-2xl p-3 text-center">
                                <p class="text-[8px] font-bold text-slate-600 uppercase">Lombardi</p>
                                <p class="font-black text-sm mt-0.5" id="rm-lombardi">--</p>
                            </div>
                        </div>

                        <div class="mt-4 space-y-2" id="rm-percentages"></div>
                    </div>
                </div>

                {{-- Personal Bests --}}
                <div class="bg-[#111214] border border-slate-800/50 p-6 rounded-[2.5rem]">
                    <h2 class="text-[10px] font-black uppercase text-slate-400 tracking-[0.3em] mb-4">Records Personnels</h2>
                    <div class="relative h-56">
                        <canvas id="pbChart"></canvas>
                    </div>
                </div>
            </div>

            {{-- Tab: Progression --}}
            <div id="tab-progress" class="tab-content hidden space-y-4">

                <div class="bg-[#111214] border border-slate-800/50 p-6 rounded-[2.5rem]">
                    <h2 class="text-[10px] font-black uppercase text-slate-400 tracking-[0.3em] mb-4">Progression par Exercice</h2>
                    <div class="space-y-3 mb-4">
                        <input type="text" id="progress-search" placeholder="Rechercher un exercice..."
                               oninput="searchExercises(this.value)"
                               class="w-full bg-[#08090a] border border-slate-800 rounded-2xl px-4 py-3 text-white text-sm font-bold focus:outline-none focus:border-rose-500 transition-colors placeholder-slate-600">
                        <div id="exercise-suggestions" class="hidden bg-[#08090a] border border-slate-800 rounded-2xl overflow-hidden max-h-40 overflow-y-auto"></div>
                        <div class="flex items-center justify-between">
                            <p class="text-[9px] font-bold text-slate-600 uppercase" id="selected-exercise-label">Aucun exercice sélectionné</p>
                            <select id="progress-limit" onchange="loadProgressChart()"
                                    class="bg-[#08090a] border border-slate-700 text-xs text-slate-400 rounded-xl px-3 py-1.5">
                                <option value="10">10 séances</option>
                                <option value="20" selected>20 séances</option>
                                <option value="50">50 séances</option>
                            </select>
                        </div>
                    </div>
                    <div class="relative h-48">
                        <canvas id="progressChart"></canvas>
                    </div>
                </div>
            </div>

            {{-- Tab: Photos --}}
            <div id="tab-photos" class="tab-content hidden space-y-4">

                {{-- Upload --}}
                <div class="bg-[#111214] border border-slate-800/50 p-6 rounded-[2.5rem]">
                    <h2 class="text-[10px] font-black uppercase text-slate-400 tracking-[0.3em] mb-4">Uploader une Photo</h2>
                    <form action="{{ route('evolution.photos.upload') }}" method="POST" enctype="multipart/form-data" class="space-y-3">
                        @csrf
                        <div>
                            <label class="block text-[9px] font-bold text-slate-500 uppercase tracking-widest mb-1.5">Photo *</label>
                            <input type="file" name="photo" accept="image/*" required
                                   class="w-full bg-[#08090a] border border-slate-800 rounded-2xl px-4 py-3 text-slate-400 text-sm file:mr-3 file:py-1 file:px-3 file:rounded-xl file:border-0 file:bg-rose-500 file:text-white file:text-xs file:font-black file:uppercase focus:outline-none">
                        </div>
                        <div class="grid grid-cols-2 gap-3">
                            <div>
                                <label class="block text-[9px] font-bold text-slate-500 uppercase tracking-widest mb-1.5">Zone</label>
                                <select name="body_part" required
                                        class="w-full bg-[#08090a] border border-slate-800 rounded-2xl px-4 py-3 text-white text-sm font-bold focus:outline-none focus:border-rose-500">
                                    <option value="full_body">Corps entier</option>
                                    <option value="chest">Poitrine</option>
                                    <option value="back">Dos</option>
                                    <option value="legs">Jambes</option>
                                    <option value="arms">Bras</option>
                                    <option value="abs">Abdominaux</option>
                                </select>
                            </div>
                            <div>
                                <label class="block text-[9px] font-bold text-slate-500 uppercase tracking-widest mb-1.5">Type</label>
                                <select name="type" required
                                        class="w-full bg-[#08090a] border border-slate-800 rounded-2xl px-4 py-3 text-white text-sm font-bold focus:outline-none focus:border-rose-500">
                                    <option value="progress">Progression</option>
                                    <option value="before">Avant</option>
                                    <option value="after">Après</option>
                                </select>
                            </div>
                        </div>
                        <div>
                            <label class="block text-[9px] font-bold text-slate-500 uppercase tracking-widest mb-1.5">Notes</label>
                            <textarea name="notes" rows="2" placeholder="Contexte, poids du jour..."
                                      class="w-full bg-[#08090a] border border-slate-800 rounded-2xl px-4 py-3 text-white text-sm font-bold focus:outline-none focus:border-rose-500 resize-none placeholder-slate-600"></textarea>
                        </div>
                        <button type="submit"
                                class="w-full bg-rose-500 hover:bg-rose-600 active:scale-95 rounded-2xl py-3.5 font-black text-sm uppercase tracking-wider transition-all">
                            Uploader
                        </button>
                    </form>
                </div>

                {{-- Timeline photos --}}
                <div class="bg-[#111214] border border-slate-800/50 p-6 rounded-[2.5rem]">
                    <h2 class="text-[10px] font-black uppercase text-slate-400 tracking-[0.3em] mb-4">Timeline</h2>
                    <div class="space-y-3">
                        @forelse($photos->get('full_body', collect()) as $photo)
                            <div class="flex gap-4 p-4 bg-[#08090a] border border-slate-800 rounded-2xl">
                                <img src="{{ Storage::url($photo->photo_path) }}"
                                     alt="Photo"
                                     class="w-24 h-24 object-cover rounded-xl flex-shrink-0">
                                <div class="flex-1 min-w-0">
                                    <div class="flex items-center gap-2 mb-2 flex-wrap">
                                        <span class="text-[8px] font-black uppercase tracking-wider px-2 py-0.5 rounded-lg
                                            {{ $photo->type === 'before' ? 'bg-red-500/20 text-red-400' : ($photo->type === 'after' ? 'bg-green-500/20 text-green-400' : 'bg-blue-500/20 text-blue-400') }}">
                                            {{ $photo->type }}
                                        </span>
                                        <span class="text-[9px] text-slate-500">{{ $photo->created_at->format('d/m/Y') }}</span>
                                        @if($photo->weight_at_photo)
                                            <span class="text-[9px] text-slate-500 font-bold">{{ $photo->weight_at_photo }} kg</span>
                                        @endif
                                    </div>
                                    @if($photo->notes)
                                        <p class="text-xs text-slate-400 truncate">{{ $photo->notes }}</p>
                                    @endif
                                    <form action="{{ route('evolution.photos.delete', $photo) }}" method="POST" class="mt-2">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="text-[9px] font-bold text-red-500/70 uppercase tracking-wider hover:text-red-400 transition-colors">
                                            Supprimer
                                        </button>
                                    </form>
                                </div>
                            </div>
                        @empty
                            <div class="text-center py-10">
                                <p class="text-slate-600 font-bold uppercase text-xs tracking-wider">Aucune photo uploadée</p>
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>

            {{-- Tab: Calories --}}
            <div id="tab-calories" class="tab-content hidden space-y-4">

                {{-- Stats rapides --}}
                <div class="grid grid-cols-3 gap-3">
                    <div class="bg-[#111214] border border-slate-800/50 p-4 rounded-[2rem] text-center">
                        <p class="text-[8px] font-bold text-slate-500 uppercase tracking-widest">Cette semaine</p>
                        <p class="text-2xl font-black text-orange-400 mt-1">{{ number_format($weeklyCalories) }}</p>
                        <p class="text-[8px] text-slate-600 font-bold uppercase">kcal</p>
                    </div>
                    <div class="bg-[#111214] border border-slate-800/50 p-4 rounded-[2rem] text-center">
                        <p class="text-[8px] font-bold text-slate-500 uppercase tracking-widest">Ce mois</p>
                        <p class="text-2xl font-black text-orange-400 mt-1">{{ number_format($monthlyCalories) }}</p>
                        <p class="text-[8px] text-slate-600 font-bold uppercase">kcal</p>
                    </div>
                    <div class="bg-[#111214] border border-slate-800/50 p-4 rounded-[2rem] text-center">
                        <p class="text-[8px] font-bold text-slate-500 uppercase tracking-widest">Moy/jour</p>
                        <p class="text-2xl font-black text-blue-400 mt-1">{{ $monthlyCalories > 0 ? number_format($monthlyCalories / 30) : 0 }}</p>
                        <p class="text-[8px] text-slate-600 font-bold uppercase">kcal</p>
                    </div>
                </div>

                {{-- Graphique calories --}}
                <div class="bg-[#111214] border border-slate-800/50 p-6 rounded-[2.5rem]">
                    <h2 class="text-[10px] font-black uppercase text-slate-400 tracking-[0.3em] mb-4">Calories Quotidiennes (30 jours)</h2>
                    <div class="relative h-48">
                        <canvas id="caloriesChart"></canvas>
                    </div>
                </div>
            </div>

        </div>
    </div>

    @push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        // Config commune Chart.js
        Chart.defaults.color = '#64748b';
        Chart.defaults.font.family = 'ui-sans-serif, system-ui';

        // --- Tab switching ---
        let activeTab = 'weight';

        function showTab(tabId) {
            document.querySelectorAll('.tab-content').forEach(el => el.classList.add('hidden'));
            document.querySelectorAll('.tab-btn').forEach(el => {
                el.classList.remove('bg-rose-500', 'text-white', 'shadow-lg', 'shadow-rose-500/20');
                el.classList.add('text-slate-500', 'hover:text-white');
            });

            document.getElementById('tab-' + tabId).classList.remove('hidden');
            const btn = document.querySelector(`[data-tab="${tabId}"]`);
            btn.classList.add('bg-rose-500', 'text-white', 'shadow-lg', 'shadow-rose-500/20');
            btn.classList.remove('text-slate-500', 'hover:text-white');

            activeTab = tabId;
            if (tabId === 'weight') loadBodyMetrics();
            if (tabId === '1rm') loadPersonalBests();
            if (tabId === 'calories') loadCalories();
        }

        // --- Poids & Mesures ---
        let weightChart, measurementsChart;

        function loadBodyMetrics() {
            const period = document.getElementById('weight-period').value;
            fetch(`/evolution/body-metrics?period=${period}`)
                .then(r => r.json())
                .then(data => {
                    if (weightChart) weightChart.destroy();
                    if (measurementsChart) measurementsChart.destroy();

                    const weightCtx = document.getElementById('weightChart').getContext('2d');
                    weightChart = new Chart(weightCtx, {
                        type: 'line',
                        data: {
                            labels: data.labels,
                            datasets: [{
                                label: 'Poids (kg)',
                                data: data.weight,
                                borderColor: '#f43f5e',
                                backgroundColor: 'rgba(244, 63, 94, 0.1)',
                                fill: true,
                                tension: 0.4,
                                pointBackgroundColor: '#f43f5e',
                                pointBorderColor: '#08090a',
                                pointBorderWidth: 2,
                                pointRadius: 4
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

                    const measCtx = document.getElementById('measurementsChart').getContext('2d');
                    const colors = {
                        chest: '#f43f5e',
                        waist: '#f97316',
                        hips: '#eab308',
                        arms: '#22c55e',
                        thighs: '#06b6d4'
                    };
                    const labels = { chest: 'Poitrine', waist: 'Taille', hips: 'Hanches', arms: 'Bras', thighs: 'Cuisses' };
                    const datasets = [];

                    Object.keys(colors).forEach(part => {
                        if (data[part] && data[part].length > 0) {
                            datasets.push({
                                label: labels[part],
                                data: data[part],
                                borderColor: colors[part],
                                tension: 0.4,
                                pointRadius: 3
                            });
                        }
                    });

                    measurementsChart = new Chart(measCtx, {
                        type: 'line',
                        data: { labels: data.labels, datasets },
                        options: {
                            responsive: true,
                            maintainAspectRatio: false,
                            plugins: { legend: { position: 'bottom', labels: { boxWidth: 10, font: { size: 10 } } } },
                            scales: {
                                x: { grid: { display: false } },
                                y: { grid: { color: '#1e293b' } }
                            }
                        }
                    });
                })
                .catch(() => {});
        }

        // --- 1RM Calculator ---
        document.getElementById('1rm-form').addEventListener('submit', function(e) {
            e.preventDefault();
            const weight = document.getElementById('rm-weight').value;
            const reps = document.getElementById('rm-reps').value;

            if (!weight || !reps || reps < 1) return;

            fetch(`/evolution/1rm?weight=${weight}&reps=${reps}`)
                .then(r => r.json())
                .then(data => {
                    document.getElementById('1rm-results').classList.remove('hidden');
                    document.getElementById('rm-average').textContent = data.average + ' kg';
                    document.getElementById('rm-epley').textContent = data.epley + ' kg';
                    document.getElementById('rm-brzycki').textContent = data.brzycki + ' kg';
                    document.getElementById('rm-lander').textContent = data.lander + ' kg';
                    document.getElementById('rm-lombardi').textContent = data.lombardi + ' kg';

                    const percentagesDiv = document.getElementById('rm-percentages');
                    percentagesDiv.innerHTML = '<p class="text-[9px] font-bold text-slate-500 uppercase tracking-widest mb-2">Pourcentages</p>' +
                        Object.entries(data.percentages).map(([pct, w]) => `
                            <div class="flex items-center gap-3 py-1">
                                <span class="text-xs font-black text-slate-400 w-10">${pct}%</span>
                                <div class="flex-1 bg-slate-800 rounded-full h-1.5">
                                    <div class="bg-rose-500 h-1.5 rounded-full" style="width: ${pct}%"></div>
                                </div>
                                <span class="text-xs font-black text-white w-16 text-right">${w} kg</span>
                            </div>
                        `).join('');
                })
                .catch(() => {});
        });

        // --- Personal Bests ---
        let pbChart;
        function loadPersonalBests() {
            if (pbChart) return;
            fetch('/evolution/personal-bests', { headers: { 'Accept': 'application/json' } })
                .then(r => r.json())
                .then(data => {
                    if (!data.length) return;
                    const ctx = document.getElementById('pbChart').getContext('2d');
                    pbChart = new Chart(ctx, {
                        type: 'bar',
                        data: {
                            labels: data.map(d => d.exercise_name?.substring(0, 18) || 'Ex'),
                            datasets: [{
                                label: 'Max (kg)',
                                data: data.map(d => d.max_weight),
                                backgroundColor: 'rgba(244, 63, 94, 0.7)',
                                borderColor: '#f43f5e',
                                borderWidth: 1,
                                borderRadius: 6
                            }]
                        },
                        options: {
                            responsive: true,
                            maintainAspectRatio: false,
                            indexAxis: 'y',
                            plugins: { legend: { display: false } },
                            scales: {
                                x: { grid: { color: '#1e293b' } },
                                y: { grid: { display: false }, ticks: { font: { size: 10 } } }
                            }
                        }
                    });
                })
                .catch(() => {});
        }

        // --- Progression par exercice ---
        let progressChart;
        let selectedExerciseId = null;

        let searchTimeout;
        function searchExercises(query) {
            clearTimeout(searchTimeout);
            const suggestionsDiv = document.getElementById('exercise-suggestions');

            if (!query || query.length < 2) {
                suggestionsDiv.classList.add('hidden');
                return;
            }

            searchTimeout = setTimeout(() => {
                fetch(`/api/exercises/search?q=${encodeURIComponent(query)}&limit=8`)
                    .then(r => r.json())
                    .then(data => {
                        if (!data.length) {
                            suggestionsDiv.classList.add('hidden');
                            return;
                        }
                        suggestionsDiv.innerHTML = data.map(ex => {
                            const muscle = Array.isArray(ex.targetMuscles) ? ex.targetMuscles[0] : (ex.targetMuscles || '');
                            const safeName = ex.name.replace(/'/g, "\\'");
                            return `
                                <button type="button"
                                        onclick="selectExercise(${ex.id}, '${safeName}')"
                                        class="w-full text-left px-4 py-2.5 text-sm font-bold hover:bg-slate-800 transition-colors border-b border-slate-800 last:border-0 capitalize">
                                    ${ex.name}
                                    <span class="text-[9px] text-slate-500 font-normal ml-1">${muscle}</span>
                                </button>
                            `;
                        }).join('');
                        suggestionsDiv.classList.remove('hidden');
                    })
                    .catch(() => {});
            }, 300);
        }

        function selectExercise(id, name) {
            selectedExerciseId = id;
            document.getElementById('progress-search').value = name;
            document.getElementById('exercise-suggestions').classList.add('hidden');
            document.getElementById('selected-exercise-label').textContent = name;
            loadProgressChart();
        }

        function loadProgressChart() {
            if (!selectedExerciseId) return;
            const limit = document.getElementById('progress-limit').value;

            fetch(`/evolution/progress?exercise_id=${selectedExerciseId}&limit=${limit}`)
                .then(r => r.json())
                .then(data => {
                    if (progressChart) progressChart.destroy();
                    const ctx = document.getElementById('progressChart').getContext('2d');
                    progressChart = new Chart(ctx, {
                        type: 'line',
                        data: {
                            labels: data.labels,
                            datasets: [
                                {
                                    label: 'Poids Max (kg)',
                                    data: data.max_weights,
                                    borderColor: '#f43f5e',
                                    backgroundColor: 'rgba(244, 63, 94, 0.1)',
                                    fill: true,
                                    tension: 0.4,
                                    pointRadius: 4
                                },
                                {
                                    label: '1RM Estimé (kg)',
                                    data: data.estimated_1rm,
                                    borderColor: '#22c55e',
                                    borderDash: [5, 5],
                                    tension: 0.4,
                                    pointRadius: 3
                                }
                            ]
                        },
                        options: {
                            responsive: true,
                            maintainAspectRatio: false,
                            plugins: { legend: { position: 'bottom', labels: { boxWidth: 10, font: { size: 10 } } } },
                            scales: {
                                x: { grid: { display: false } },
                                y: { grid: { color: '#1e293b' } }
                            }
                        }
                    });
                })
                .catch(() => {});
        }

        // Fermer les suggestions en cliquant ailleurs
        document.addEventListener('click', function(e) {
            if (!e.target.closest('#progress-search') && !e.target.closest('#exercise-suggestions')) {
                document.getElementById('exercise-suggestions').classList.add('hidden');
            }
        });

        // --- Calories ---
        let caloriesChart;

        function loadCalories() {
            if (caloriesChart) return;
            fetch('/evolution/calories?period=30')
                .then(r => r.json())
                .then(data => {
                    const ctx = document.getElementById('caloriesChart').getContext('2d');
                    caloriesChart = new Chart(ctx, {
                        type: 'bar',
                        data: {
                            labels: data.labels,
                            datasets: [{
                                label: 'Calories',
                                data: data.data,
                                backgroundColor: 'rgba(249, 115, 22, 0.7)',
                                borderColor: '#f97316',
                                borderWidth: 1,
                                borderRadius: 6
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
                })
                .catch(() => {});
        }

        // Initialiser le premier onglet
        showTab('weight');
    </script>
    @endpush
</x-app-layout>
