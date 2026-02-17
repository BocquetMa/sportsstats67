<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Catalogue des Exercices') }}
            </h2>
            <span class="bg-indigo-100 text-indigo-800 text-xs font-medium px-2.5 py-0.5 rounded-full">
                {{ $exercises->total() }} exercices dispos
            </span>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            <div class="mb-8">
                <form action="{{ route('exercises.index') }}" method="GET" class="flex gap-2">
                    <div class="relative flex-1">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                            </svg>
                        </div>
                        <input type="text" name="search" value="{{ request('search') }}"
                            class="block w-full pl-10 pr-3 py-2 border border-gray-300 rounded-md leading-5 bg-white placeholder-gray-500 focus:outline-none focus:ring-1 focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                            placeholder="Rechercher un muscle (ex: pectorals), un équipement ou un nom...">
                    </div>
                    <button type="submit" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                        Filtrer
                    </button>
                </form>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
                @forelse($exercises as $exercise)
                    <div class="bg-white rounded-xl shadow-sm hover:shadow-md transition-shadow duration-300 overflow-hidden border border-gray-100">
                        <div class="h-48 bg-gray-50 flex items-center justify-center p-2">
                            <img src="{{ $exercise->gifUrl }}" alt="{{ $exercise->name }}" class="max-h-full max-w-full object-contain" loading="lazy">
                        </div>

                        <div class="p-4">
                            <div class="text-xs font-bold text-indigo-600 uppercase tracking-wide mb-1">
                                {{ is_array($exercise->bodyParts) ? $exercise->bodyParts[0] : $exercise->bodyParts }}
                            </div>
                            <h3 class="text-gray-900 font-bold text-lg mb-2 capitalize leading-tight h-12 overflow-hidden">
                                {{ $exercise->name }}
                            </h3>

                            <div class="flex flex-wrap gap-1 mb-3">
                                @foreach($exercise->targetMuscles as $muscle)
                                    <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-green-100 text-green-800">
                                        {{ $muscle }}
                                    </span>
                                @endforeach
                                <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-gray-100 text-gray-800">
                                    {{ is_array($exercise->equipments) ? $exercise->equipments[0] : $exercise->equipments }}
                                </span>
                            </div>

                            <button class="w-full mt-2 bg-gray-50 text-gray-700 py-2 rounded-lg text-sm font-semibold hover:bg-indigo-600 hover:text-white transition-colors">
                                Voir détails
                            </button>
                        </div>
                    </div>
                @empty
                    <div class="col-span-full text-center py-10 bg-white rounded-lg shadow">
                        <p class="text-gray-500">Aucun exercice trouvé pour cette recherche.</p>
                    </div>
                @endforelse
            </div>

            <div class="mt-8">
                {{ $exercises->appends(request()->query())->links() }}
            </div>
        </div>
    </div>
</x-app-layout>
