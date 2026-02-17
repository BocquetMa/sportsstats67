<x-app-layout>
    <div class="py-6 bg-[#0a0a0b] min-h-screen">
        <div class="max-w-2xl mx-auto px-4">
            <div class="flex justify-between items-center mb-8">
                <h2 class="text-white font-black italic uppercase text-3xl tracking-tighter">Messages</h2>
                <div class="bg-rose-500 text-white text-[10px] font-bold px-2 py-1 rounded uppercase">Live</div>
            </div>

            <div class="space-y-3">
                @forelse($users as $u)
                    <a href="{{ route('messages.show', $u) }}" class="flex items-center gap-4 p-4 bg-[#111214] border border-slate-800 rounded-2xl hover:border-rose-500/50 transition-all group">
                        <div class="relative">
                            <div class="w-14 h-14 bg-gradient-to-br from-slate-700 to-slate-900 rounded-full flex items-center justify-center font-black text-white border-2 border-slate-800 group-hover:border-rose-500">
                                {{ substr($u->name, 0, 1) }}
                            </div>
                            <div class="absolute bottom-0 right-0 w-4 h-4 bg-green-500 border-4 border-[#111214] rounded-full"></div>
                        </div>

                        <div class="flex-1 min-w-0">
                            <div class="flex justify-between items-baseline">
                                <h3 class="text-white font-bold uppercase italic text-sm truncate">{{ $u->name }}</h3>
                                @if($u->last_message)
                                    <span class="text-[10px] text-slate-500 font-medium">
                                        {{ $u->last_message->created_at->diffForHumans(short: true) }}
                                    </span>
                                @endif
                            </div>

                            <p class="text-slate-400 text-xs truncate mt-1 font-medium">
                                @if($u->last_message)
                                    @if($u->last_message->sender_id === auth()->id())
                                        <span class="text-rose-500 font-bold">Moi:</span>
                                    @endif
                                    {{ $u->last_message->content }}
                                @else
                                    <span class="italic text-slate-600 text-[10px] uppercase tracking-widest">Démarrer une nouvelle discussion</span>
                                @endif
                            </p>
                        </div>

                        <div class="text-slate-700 group-hover:text-rose-500 transition-colors">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7"/></svg>
                        </div>
                    </a>
                @empty
                    <div class="text-center py-20">
                        <p class="text-slate-500 uppercase font-black italic">Aucun utilisateur trouvé</p>
                    </div>
                @endforelse
            </div>
        </div>
    </div>
</x-app-layout>
