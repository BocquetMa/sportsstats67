<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $workout->title }} — SportStats</title>
    <meta property="og:title" content="{{ $workout->title }} — SportStats">
    <meta property="og:description" content="{{ $workout->user->name }} a terminé {{ $totalSets }} séries · {{ number_format($totalVolume, 0, '.', ' ') }} kg de volume">
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700,800,900&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        body { font-family: 'Figtree', sans-serif; background: #08090a; }
    </style>
</head>
<body class="min-h-screen bg-[#08090a] text-white p-6 pb-16">

    <!-- Header -->
    <div class="max-w-lg mx-auto">
        <div class="flex items-center gap-3 mb-8 pt-4">
            <div class="w-10 h-10 bg-rose-500 rounded-2xl flex items-center justify-center">
                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                </svg>
            </div>
            <span class="text-[10px] font-black uppercase tracking-[0.3em] text-slate-500">SportStats</span>
        </div>

        <!-- Profil utilisateur -->
        <div class="flex items-center gap-4 mb-6">
            <div class="w-14 h-14 rounded-2xl bg-gradient-to-br from-rose-500 to-rose-700 flex items-center justify-center text-xl font-black">
                {{ substr($workout->user->name, 0, 1) }}
            </div>
            <div>
                <h1 class="text-xl font-black">{{ $workout->user->name }}</h1>
                <p class="text-[10px] font-bold uppercase tracking-widest text-slate-500">
                    {{ $workout->completed_at?->format('d/m/Y à H:i') ?? $workout->created_at->format('d/m/Y') }}
                </p>
            </div>
        </div>

        <!-- Titre de la séance -->
        <div class="mb-6">
            <p class="text-[10px] font-black text-rose-500 uppercase tracking-[0.3em] mb-1">Séance terminée</p>
            <h2 class="text-4xl font-black uppercase italic tracking-tighter leading-none">{{ $workout->title }}</h2>
        </div>

        <!-- Stats globales -->
        <div class="grid grid-cols-3 gap-3 mb-8">
            <div class="bg-[#111214] border border-slate-800 p-4 rounded-[2rem] text-center">
                <p class="text-[9px] font-bold text-slate-500 uppercase tracking-widest">Exercices</p>
                <p class="text-3xl font-black mt-1">{{ $sets->count() }}</p>
            </div>
            <div class="bg-[#111214] border border-slate-800 p-4 rounded-[2rem] text-center">
                <p class="text-[9px] font-bold text-slate-500 uppercase tracking-widest">Séries</p>
                <p class="text-3xl font-black mt-1 text-rose-500">{{ $totalSets }}</p>
            </div>
            <div class="bg-[#111214] border border-slate-800 p-4 rounded-[2rem] text-center">
                <p class="text-[9px] font-bold text-slate-500 uppercase tracking-widest">Volume</p>
                <p class="text-2xl font-black mt-1 text-blue-400">{{ number_format($totalVolume / 1000, 1) }}t</p>
            </div>
        </div>

        <!-- Exercices -->
        <div class="space-y-4">
            @foreach($sets as $exerciseId => $exerciseSets)
                @php $exercise = $exerciseSets->first()->exercise; @endphp
                <div class="bg-[#111214] border border-slate-800 rounded-[2.5rem] overflow-hidden">
                    <div class="p-5">
                        <div class="flex items-center gap-4 mb-4">
                            @if($exercise && $exercise->gifUrl)
                                <img src="{{ $exercise->gifUrl }}" alt="{{ $exercise->name }}"
                                     class="w-14 h-14 rounded-xl object-cover bg-slate-900">
                            @else
                                <div class="w-14 h-14 rounded-xl bg-slate-900 flex items-center justify-center">
                                    <svg class="w-7 h-7 text-slate-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                                    </svg>
                                </div>
                            @endif
                            <div>
                                <h3 class="font-black uppercase text-sm">{{ $exercise?->name ?? 'Exercice inconnu' }}</h3>
                                @if($exercise && $exercise->targetMuscles)
                                    <p class="text-[9px] text-slate-500 font-bold mt-0.5">
                                        {{ is_array($exercise->targetMuscles) ? implode(', ', $exercise->targetMuscles) : $exercise->targetMuscles }}
                                    </p>
                                @endif
                            </div>
                        </div>

                        <div class="grid gap-2">
                            @foreach($exerciseSets as $i => $set)
                                <div class="flex items-center justify-between bg-slate-900/50 rounded-xl px-4 py-2">
                                    <span class="text-[9px] font-bold text-slate-500 uppercase">Série {{ $i + 1 }}</span>
                                    <div class="flex gap-4">
                                        @if($set->weight > 0)
                                            <span class="text-sm font-black">{{ $set->weight }} <span class="text-slate-500 font-bold text-xs">kg</span></span>
                                        @endif
                                        <span class="text-sm font-black text-rose-400">{{ $set->reps }} <span class="text-slate-500 font-bold text-xs">reps</span></span>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <!-- CTA -->
        <div class="mt-10 text-center">
            <p class="text-slate-500 text-xs mb-4 font-bold uppercase tracking-wider">Partage ta progression</p>
            <a href="{{ route('register') }}"
               class="inline-block bg-gradient-to-r from-rose-500 to-rose-600 text-white font-black uppercase tracking-[0.15em] px-8 py-4 rounded-[2rem] shadow-2xl shadow-rose-500/30 hover:scale-105 transition-transform">
                Rejoindre SportStats
            </a>
        </div>
    </div>

</body>
</html>
