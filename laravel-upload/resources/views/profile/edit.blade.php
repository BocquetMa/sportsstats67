<x-app-layout>
    <div class="min-h-screen bg-[#08090a] text-white p-6 pb-24">
        <div class="max-w-xl mx-auto">
            <h1 class="text-2xl font-black italic uppercase tracking-tighter mb-8">Paramètres Profil</h1>

            <form action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data" class="space-y-8">
                @csrf
                @method('PATCH')

                <div class="space-y-2">
                    <label class="text-[10px] font-black uppercase tracking-widest text-slate-500">Photo de couverture</label>
                    <div class="relative h-32 w-full rounded-2xl border-2 border-dashed border-slate-800 overflow-hidden group">
                        <input type="file" name="cover_photo" class="absolute inset-0 opacity-0 cursor-pointer z-10">
                        <div class="absolute inset-0 flex items-center justify-center bg-slate-900 group-hover:bg-slate-800 transition-colors">
                            <span class="text-xs font-bold text-slate-500 italic">Clique pour changer la bannière</span>
                        </div>
                    </div>
                </div>

                <div class="flex items-center gap-6">
                    <div class="relative w-20 h-20 rounded-3xl bg-rose-500 overflow-hidden">
                        @if($user->avatar)
                            <img src="{{ asset('storage/' . $user->avatar) }}" class="w-full h-full object-cover">
                        @endif
                        <input type="file" name="avatar" class="absolute inset-0 opacity-0 cursor-pointer">
                    </div>
                    <div>
                        <p class="font-black italic uppercase text-sm">Photo de profil</p>
                        <p class="text-[10px] text-slate-500 uppercase font-bold">Format JPG, PNG (Max 2MB)</p>
                    </div>
                </div>

                <div class="space-y-4">
                    <input type="text" name="name" value="{{ $user->name }}" placeholder="Nom d'affichage"
                           class="w-full bg-[#111214] border-2 border-slate-800 rounded-2xl px-4 py-4 focus:border-rose-500 transition-all outline-none font-bold">

                    <textarea name="bio" rows="4" placeholder="Ta bio..."
                              class="w-full bg-[#111214] border-2 border-slate-800 rounded-2xl px-4 py-4 focus:border-rose-500 transition-all outline-none font-medium">{{ $user->bio }}</textarea>
                </div>

                <div class="space-y-4 pt-6 border-t border-white/5">
    <label class="text-[10px] font-black uppercase tracking-widest text-slate-500 text-center block">Tes jours d'entraînement</label>
    <div class="flex flex-wrap gap-3 justify-center">
        @foreach(['Lundi', 'Mardi', 'Mercredi', 'Jeudi', 'Vendredi', 'Samedi', 'Dimanche'] as $day)
            <label class="cursor-pointer group">
                <input type="checkbox" name="days[]" value="{{ $day }}" class="hidden peer" {{ in_array($day, $user->workouts->pluck('day')->toArray()) ? 'checked' : '' }}>
                <div class="px-4 py-2 rounded-xl border-2 border-slate-800 bg-[#111214] text-xs font-black italic uppercase peer-checked:border-rose-500 peer-checked:text-rose-500 transition-all group-active:scale-90">
                    {{ substr($day, 0, 2) }}
                </div>
            </label>
        @endforeach
    </div>
</div>

                <button type="submit" class="w-full bg-rose-500 py-5 rounded-2xl font-black uppercase italic tracking-widest shadow-lg shadow-rose-500/20 active:scale-95 transition-all">
                    Mettre à jour l'utilisateur
                </button>
            </form>
        </div>
    </div>
</x-app-layout>
