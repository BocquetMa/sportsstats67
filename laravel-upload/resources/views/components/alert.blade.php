@props(['type' => 'success', 'dismissible' => true])

@php
    $styles = [
        'success' => 'bg-green-500/10 border-green-500/50 text-green-400',
        'error' => 'bg-red-500/10 border-red-500/50 text-red-400',
        'warning' => 'bg-yellow-500/10 border-yellow-500/50 text-yellow-400',
        'info' => 'bg-blue-500/10 border-blue-500/50 text-blue-400',
    ];

    $icons = [
        'success' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>',
        'error' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>',
        'warning' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>',
        'info' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>',
    ];
@endphp

<div {{ $attributes->merge(['class' => "border-2 rounded-2xl p-4 flex items-start gap-3 {$styles[$type]}"]) }}
     @if($dismissible) x-data="{ show: true }" x-show="show" x-transition @endif>

    <svg class="w-5 h-5 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        {!! $icons[$type] !!}
    </svg>

    <div class="flex-1">
        {{ $slot }}
    </div>

    @if($dismissible)
        <button @click="show = false" class="flex-shrink-0 hover:opacity-70 transition">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
            </svg>
        </button>
    @endif
</div>
