@extends('layouts.app')

@section('title', 'Évolution & Progression')

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
@endpush

@section('content')
<div class="min-h-screen bg-slate-900 text-white p-6">
    <div class="max-w-7xl mx-auto">
        <h1 class="text-3xl font-bold mb-8">📈 Évolution & Progression</h1>

        <!-- Onglets -->
        <div class="flex gap-2 mb-6 overflow-x-auto">
            <button onclick="showTab('weight')" class="tab-btn px-4 py-2 bg-slate-800 rounded-lg hover:bg-slate-700 transition" data-tab="weight">
                ⚖️ Poids & Mesures
            </button>
            <button onclick="showTab('1rm')" class="tab-btn px-4 py-2 bg-slate-800 rounded-lg hover:bg-slate-700 transition" data-tab="1rm">
                🏋️ 1RM Calculator
            </button>
            <button onclick="showTab('progress')" class="tab-btn px-4 py-2 bg-slate-800 rounded-lg hover:bg-slate-700 transition" data-tab="progress">
                📊 Progression
            </button>
            <button onclick="showTab('photos')" class="tab-btn px-4 py-2 bg-slate-800 rounded-lg hover:bg-slate-700 transition" data-tab="photos">
                📸 Photos Avant/Après
            </button>
            <button onclick="showTab('calories')" class="tab-btn px-4 py-2 bg-slate-800 rounded-lg hover:bg-slate-700 transition" data-tab="calories">
                🔥 Calories
            </button>
        </div>

        <!-- Tab: Poids & Mesures -->
        <div id="tab-weight" class="tab-content space-y-6">
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <!-- Graphique poids -->
                <div class="lg:col-span-2 bg-slate-800 rounded-xl p-6">
                    <div class="flex justify-between items-center mb-4">
                        <h2 class="text-xl font-semibold">Évolution du poids</h2>
                        <select id="weight-period" class="bg-slate-700 rounded px-3 py-1 text-sm" onchange="loadBodyMetrics()">
                            <option value="30">30 jours</option>
                            <option value="90" selected>90 jours</option>
                            <option value="180">6 mois</option>
                            <option value="365">1 an</option>
                        </select>
                    </div>
                    <canvas id="weightChart" height="200"></canvas>
                </div>

                <!-- Ajouter mesure -->
                <div class="bg-slate-800 rounded-xl p-6">
                    <h2 class="text-xl font-semibold mb-4">📏 Nouvelle mesure</h2>
                    <form action="{{ route('evolution.body-metric.store') }}" method="POST" class="space-y-4">
                        @csrf
                        <div>
                            <label class="block text-sm text-slate-400 mb-1">Poids (kg)</label>
                            <input type="number" step="0.1" name="weight" required class="w-full bg-slate-700 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                        </div>
                        <div>
                            <label class="block text-sm text-slate-400 mb-1">% Graisse</label>
                            <input type="number" step="0.1" name="body_fat" class="w-full bg-slate-700 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                        </div>
                        <div class="grid grid-cols-2 gap-2">
                            <div>
                                <label class="block text-xs text-slate-400 mb-1">Poitrine</label>
                                <input type="number" step="0.1" name="chest" class="w-full bg-slate-700 rounded-lg px-3 py-2 text-sm">
                            </div>
                            <div>
                                <label class="block text-xs text-slate-400 mb-1">Tour de taille</label>
                                <input type="number" step="0.1" name="waist" class="w-full bg-slate-700 rounded-lg px-3 py-2 text-sm">
                            </div>
                            <div>
                                <label class="block text-xs text-slate-400 mb-1">Hanches</label>
                                <input type="number" step="0.1" name="hips" class="w-full bg-slate-700 rounded-lg px-3 py-2 text-sm">
                            </div>
                            <div>
                                <label class="block text-xs text-slate-400 mb-1">Bras</label>
                                <input type="number" step="0.1" name="arms" class="w-full bg-slate-700 rounded-lg px-3 py-2 text-sm">
                            </div>
                            <div>
                                <label class="block text-xs text-slate-400 mb-1">Cuisses</label>
                                <input type="number" step="0.1" name="thighs" class="w-full bg-slate-700 rounded-lg px-3 py-2 text-sm">
                            </div>
                            <div>
                                <label class="block text-xs text-slate-400 mb-1">Mollets</label>
                                <input type="number" step="0.1" name="calves" class="w-full bg-slate-700 rounded-lg px-3 py-2 text-sm">
                            </div>
                        </div>
                        <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 rounded-lg py-2 font-semibold transition">
                            Enregistrer
                        </button>
                    </form>
                </div>
            </div>

            <!-- Graphique mesures -->
            <div class="bg-slate-800 rounded-xl p-6">
                <h2 class="text-xl font-semibold mb-4">📐 Mesures corporelles</h2>
                <canvas id="measurementsChart" height="100"></canvas>
            </div>
        </div>

        <!-- Tab: 1RM Calculator -->
        <div id="tab-1rm" class="tab-content hidden">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <div class="bg-slate-800 rounded-xl p-6">
                    <h2 class="text-xl font-semibold mb-4">🏋️ Calculateur 1RM</h2>
                    <form id="1rm-form" class="space-y-4">
                        <div>
                            <label class="block text-sm text-slate-400 mb-1">Poids levé (kg)</label>
                            <input type="number" step="0.5" id="rm-weight" required class="w-full bg-slate-700 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                        </div>
                        <div>
                            <label class="block text-sm text-slate-400 mb-1">Répétitions</label>
                            <input type="number" id="rm-reps" required class="w-full bg-slate-700 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                        </div>
                        <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 rounded-lg py-2 font-semibold transition">
                            Calculer
                        </button>
                    </form>

                    <div id="1rm-results" class="mt-6 hidden">
                        <h3 class="font-semibold mb-3">Résultats (moyenne)</h3>
                        <div class="text-4xl font-bold text-blue-400 mb-4" id="rm-average">- kg</div>

                        <div class="grid grid-cols-2 gap-2 text-sm">
                            <div class="bg-slate-700 rounded p-2">
                                <span class="text-slate-400">Epley:</span>
                                <span id="rm-epley" class="font-semibold ml-2">-</span>
                            </div>
                            <div class="bg-slate-700 rounded p-2">
                                <span class="text-slate-400">Brzycki:</span>
                                <span id="rm-brzycki" class="font-semibold ml-2">-</span>
                            </div>
                            <div class="bg-slate-700 rounded p-2">
                                <span class="text-slate-400">Lander:</span>
                                <span id="rm-lander" class="font-semibold ml-2">-</span>
                            </div>
                            <div class="bg-slate-700 rounded p-2">
                                <span class="text-slate-400">Lombardi:</span>
                                <span id="rm-lombardi" class="font-semibold ml-2">-</span>
                            </div>
                        </div>

                        <h3 class="font-semibold mt-6 mb-3">Pourcentages</h3>
                        <div class="space-y-2" id="rm-percentages"></div>
                    </div>
                </div>

                <!-- Personal Bests -->
                <div class="bg-slate-800 rounded-xl p-6">
                    <h2 class="text-xl font-semibold mb-4">🏆 Personal Bests</h2>
                    <canvas id="pbChart" height="250"></canvas>
                </div>
            </div>
        </div>

        <!-- Tab: Progression -->
        <div id="tab-progress" class="tab-content hidden">
            <div class="bg-slate-800 rounded-xl p-6">
                <div class="flex gap-4 mb-6">
                    <select id="progress-exercise" class="bg-slate-700 rounded-lg px-4 py-2 flex-1">
                        <option value="">Sélectionner un exercice</option>
                    </select>
                    <select id="progress-limit" class="bg-slate-700 rounded-lg px-4 py-2">
                        <option value="10">10 dernières</option>
                        <option value="20" selected>20 dernières</option>
                        <option value="50">50 dernières</option>
                    </select>
                </div>
                <canvas id="progressChart" height="100"></canvas>
            </div>
        </div>

        <!-- Tab: Photos -->
        <div id="tab-photos" class="tab-content hidden">
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <!-- Upload photo -->
                <div class="bg-slate-800 rounded-xl p-6">
                    <h2 class="text-xl font-semibold mb-4">📸 Upload photo</h2>
                    <form action="{{ route('evolution.photos.upload') }}" method="POST" enctype="multipart/form-data" class="space-y-4">
                        @csrf
                        <div>
                            <label class="block text-sm text-slate-400 mb-1">Photo</label>
                            <input type="file" name="photo" accept="image/*" required class="w-full bg-slate-700 rounded-lg px-4 py-2">
                        </div>
                        <div>
                            <label class="block text-sm text-slate-400 mb-1">Partie du corps</label>
                            <select name="body_part" required class="w-full bg-slate-700 rounded-lg px-4 py-2">
                                <option value="full_body">Corps entier</option>
                                <option value="chest">Poitrine</option>
                                <option value="back">Dos</option>
                                <option value="legs">Jambes</option>
                                <option value="arms">Bras</option>
                                <option value="abs">Abdominaux</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm text-slate-400 mb-1">Type</label>
                            <select name="type" required class="w-full bg-slate-700 rounded-lg px-4 py-2">
                                <option value="progress">Progression</option>
                                <option value="before">Avant</option>
                                <option value="after">Après</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm text-slate-400 mb-1">Notes</label>
                            <textarea name="notes" rows="2" class="w-full bg-slate-700 rounded-lg px-4 py-2"></textarea>
                        </div>
                        <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 rounded-lg py-2 font-semibold transition">
                            Upload
                        </button>
                    </form>
                </div>

                <!-- Timeline photos -->
                <div class="lg:col-span-2 bg-slate-800 rounded-xl p-6">
                    <h2 class="text-xl font-semibold mb-4">📅 Timeline</h2>
                    <div id="photos-timeline" class="space-y-4 max-h-[600px] overflow-y-auto">
                        @forelse($photos->get('full_body', collect()) as $photo)
                        <div class="flex gap-4 p-4 bg-slate-700 rounded-lg">
                            <img src="{{ Storage::url($photo->photo_path) }}" alt="Photo" class="w-32 h-32 object-cover rounded">
                            <div class="flex-1">
                                <div class="flex items-center gap-2 mb-2">
                                    <span class="px-2 py-1 text-xs rounded {{ $photo->type === 'before' ? 'bg-red-500' : ($photo->type === 'after' ? 'bg-green-500' : 'bg-blue-500') }}">
                                        {{ $photo->type }}
                                    </span>
                                    <span class="text-sm text-slate-400">{{ $photo->created_at->format('d/m/Y') }}</span>
                                    @if($photo->weight_at_photo)
                                    <span class="text-sm text-slate-400">{{ $photo->weight_at_photo }} kg</span>
                                    @endif
                                </div>
                                @if($photo->notes)
                                <p class="text-sm text-slate-300">{{ $photo->notes }}</p>
                                @endif
                                <form action="{{ route('evolution.photos.delete', $photo) }}" method="POST" class="mt-2">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="text-red-400 text-sm hover:underline">Supprimer</button>
                                </form>
                            </div>
                        </div>
                        @empty
                        <p class="text-slate-400 text-center py-8">Aucune photo uploadée</p>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>

        <!-- Tab: Calories -->
        <div id="tab-calories" class="tab-content hidden">
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-6">
                <div class="bg-slate-800 rounded-xl p-6 text-center">
                    <p class="text-slate-400 mb-1">Cette semaine</p>
                    <p class="text-3xl font-bold text-orange-400">{{ number_format($weeklyCalories) }}</p>
                    <p class="text-sm text-slate-400">kcal brûlées</p>
                </div>
                <div class="bg-slate-800 rounded-xl p-6 text-center">
                    <p class="text-slate-400 mb-1">Ce mois</p>
                    <p class="text-3xl font-bold text-orange-400">{{ number_format($monthlyCalories) }}</p>
                    <p class="text-sm text-slate-400">kcal brûlées</p>
                </div>
                <div class="bg-slate-800 rounded-xl p-6 text-center">
                    <p class="text-slate-400 mb-1">Moyenne/jour</p>
                    <p class="text-3xl font-bold text-blue-400">{{ $monthlyCalories > 0 ? number_format($monthlyCalories / 30) : 0 }}</p>
                    <p class="text-sm text-slate-400">kcal</p>
                </div>
            </div>

            <div class="bg-slate-800 rounded-xl p-6">
                <h2 class="text-xl font-semibold mb-4">🔥 Calories quotidiennes (30 derniers jours)</h2>
                <canvas id="caloriesChart" height="100"></canvas>
            </div>
        </div>
    </div>
</div>

<script>
// Tab switching
function showTab(tabId) {
    document.querySelectorAll('.tab-content').forEach(el => el.classList.add('hidden'));
    document.querySelectorAll('.tab-btn').forEach(el => el.classList.remove('bg-blue-600'));
    document.getElementById('tab-' + tabId).classList.remove('hidden');
    document.querySelector(`[data-tab="${tabId}"]`).classList.add('bg-blue-600');

    // Load chart data for tab
    if (tabId === 'weight') loadBodyMetrics();
    if (tabId === 'progress') loadProgress();
    if (tabId === 'calories') loadCalories();
}

// Body Metrics Chart
let weightChart, measurementsChart;

function loadBodyMetrics() {
    const period = document.getElementById('weight-period').value;
    fetch(`/evolution/body-metrics?period=${period}`)
        .then(r => r.json())
        .then(data => {
            if (weightChart) weightChart.destroy();
            if (measurementsChart) measurementsChart.destroy();

            // Weight chart
            const weightCtx = document.getElementById('weightChart').getContext('2d');
            weightChart = new Chart(weightCtx, {
                type: 'line',
                data: {
                    labels: data.labels,
                    datasets: [{
                        label: 'Poids (kg)',
                        data: data.weight,
                        borderColor: '#60a5fa',
                        backgroundColor: 'rgba(96, 165, 250, 0.1)',
                        fill: true,
                        tension: 0.4
                    }]
                },
                options: {
                    responsive: true,
                    plugins: { legend: { display: false } },
                    scales: {
                        y: { grid: { color: 'rgba(255,255,255,0.1)' } },
                        x: { grid: { display: false } }
                    }
                }
            });

            // Measurements chart
            const measCtx = document.getElementById('measurementsChart').getContext('2d');
            const datasets = [];
            const colors = { chest: '#ef4444', waist: '#f97316', hips: '#eab308', arms: '#22c55e', thighs: '#06b6d4' };

            Object.keys(colors).forEach(part => {
                if (data[part] && data[part].length > 0) {
                    datasets.push({
                        label: part.charAt(0).toUpperCase() + part.slice(1),
                        data: data[part],
                        borderColor: colors[part],
                        tension: 0.4
                    });
                }
            });

            measurementsChart = new Chart(measCtx, {
                type: 'line',
                data: { labels: data.labels, datasets },
                options: {
                    responsive: true,
                    scales: {
                        y: { grid: { color: 'rgba(255,255,255,0.1)' } },
                        x: { grid: { display: false } }
                    }
                }
            });
        });
}

// 1RM Calculator
document.getElementById('1rm-form').addEventListener('submit', function(e) {
    e.preventDefault();
    const weight = document.getElementById('rm-weight').value;
    const reps = document.getElementById('rm-reps').value;

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
            percentagesDiv.innerHTML = Object.entries(data.percentages)
                .map(([pct, weight]) => `
                    <div class="flex items-center gap-2">
                        <span class="w-12 text-sm text-slate-400">${pct}%</span>
                        <div class="flex-1 bg-slate-700 rounded-full h-2">
                            <div class="bg-blue-500 h-2 rounded-full" style="width: ${pct}%"></div>
                        </div>
                        <span class="w-16 text-right font-semibold">${weight} kg</span>
                    </div>
                `).join('');
        });
});

// Load Personal Bests
fetch('/evolution/personal-bests')
    .then(r => r.json())
    .then(data => {
        const ctx = document.getElementById('pbChart').getContext('2d');
        new Chart(ctx, {
            type: 'bar',
            data: {
                labels: data.map(d => d.exercise_name?.substring(0, 15) || 'Ex'),
                datasets: [{
                    label: 'Max (kg)',
                    data: data.map(d => d.max_weight),
                    backgroundColor: 'rgba(96, 165, 250, 0.7)',
                    borderColor: '#60a5fa',
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                indexAxis: 'y',
                scales: {
                    x: { grid: { color: 'rgba(255,255,255,0.1)' } },
                    y: { grid: { display: false } }
                }
            }
        });
    });

// Progress Chart
function loadProgress() {
    const exerciseId = document.getElementById('progress-exercise').value;
    const limit = document.getElementById('progress-limit').value;
    if (!exerciseId) return;

    fetch(`/evolution/progress?exercise_id=${exerciseId}&limit=${limit}`)
        .then(r => r.json())
        .then(data => {
            const ctx = document.getElementById('progressChart').getContext('2d');
            new Chart(ctx, {
                type: 'line',
                data: {
                    labels: data.labels,
                    datasets: [
                        {
                            label: 'Max weight',
                            data: data.max_weights,
                            borderColor: '#60a5fa',
                            yAxisID: 'y'
                        },
                        {
                            label: 'Est. 1RM',
                            data: data.estimated_1rm,
                            borderColor: '#22c55e',
                            yAxisID: 'y'
                        }
                    ]
                },
                options: {
                    responsive: true,
                    scales: {
                        y: { grid: { color: 'rgba(255,255,255,0.1)' } },
                        x: { grid: { display: false } }
                    }
                }
            });
        });
}

// Calories Chart
let caloriesChart;

function loadCalories() {
    if (caloriesChart) return;
    fetch('/evolution/calories')
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
                        borderWidth: 1
                    }]
                },
                options: {
                    responsive: true,
                    scales: {
                        y: { grid: { color: 'rgba(255,255,255,0.1)' } },
                        x: { grid: { display: false } }
                    }
                }
            });
        });
}

// Load exercises for progress dropdown
fetch('/exercises')
    .then(r => r.json())
    .then(data => {
        const select = document.getElementById('progress-exercise');
        // Add your exercises here if needed
    });
</script>
@endsection
