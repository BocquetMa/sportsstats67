@props(['label', 'value', 'unit' => '', 'icon' => null])

<div class="bg-[#08090a] p-3 rounded-2xl border border-slate-800 hover:border-rose-500/30 transition-all group">
    @if($icon)
        <div class="w-8 h-8 bg-rose-500/10 rounded-lg flex items-center justify-center mb-2 group-hover:scale-110 transition">
            {!! $icon !!}
        </div>
    @endif

    <p class="text-[9px] font-bold text-slate-500 uppercase tracking-widest mb-1">{{ $label }}</p>
    <p class="text-xl font-black">
        {{ $value }}<span class="text-xs text-slate-600 ml-1">{{ $unit }}</span>
    </p>
</div>
