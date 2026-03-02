<x-app-layout>
    <div class="min-h-screen bg-[#08090a] text-white p-6 pb-24">
        <div class="max-w-xl mx-auto">

            <div class="mb-8">
                <p class="text-[10px] font-black text-rose-500 uppercase tracking-[0.3em] mb-1">Mon Compte</p>
                <h1 class="text-4xl font-black uppercase italic tracking-tighter leading-none">Profil</h1>
            </div>

            @if(session('success'))
                <div class="mb-6 bg-green-500/10 border border-green-500/30 text-green-400 px-5 py-4 rounded-2xl text-sm font-bold">
                    {{ session('success') }}
                </div>
            @endif

            @if($errors->any())
                <div class="mb-6 bg-red-500/10 border border-red-500/30 text-red-400 px-5 py-4 rounded-2xl text-sm font-bold">
                    <ul class="list-disc list-inside space-y-1">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                @csrf
                @method('PATCH')

                {{-- Bannière --}}
                <div class="space-y-2">
                    <label class="block text-[10px] font-black uppercase tracking-widest text-slate-500">Photo de couverture</label>
                    <label class="relative h-32 w-full rounded-[2rem] border-2 border-dashed border-slate-800 overflow-hidden group cursor-pointer block">
                        @if($user->cover_photo)
                            <img src="{{ asset('storage/' . $user->cover_photo) }}" class="w-full h-full object-cover absolute inset-0">
                            <div class="absolute inset-0 bg-black/50 flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity">
                                <span class="text-xs font-black text-white uppercase tracking-widest">Changer la bannière</span>
                            </div>
                        @else
                            <div class="absolute inset-0 flex flex-col items-center justify-center bg-slate-900 group-hover:bg-slate-800 transition-colors">
                                <svg class="w-8 h-8 text-slate-700 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                <span class="text-xs font-bold text-slate-500 italic">Clique pour ajouter une bannière</span>
                            </div>
                        @endif
                        <input type="file" name="cover_photo" class="sr-only" accept="image/*">
                    </label>
                </div>

                {{-- Avatar --}}
                <div class="flex items-center gap-6">
                    <label class="relative w-20 h-20 rounded-3xl overflow-hidden cursor-pointer group flex-shrink-0">
                        @if($user->avatar)
                            <img src="{{ asset('storage/' . $user->avatar) }}" class="w-full h-full object-cover">
                        @else
                            <div class="w-full h-full bg-gradient-to-br from-rose-500 to-rose-700 flex items-center justify-center">
                                <span class="text-3xl font-black text-white">{{ substr($user->name, 0, 1) }}</span>
                            </div>
                        @endif
                        <div class="absolute inset-0 bg-black/50 flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"/></svg>
                        </div>
                        <input type="file" name="avatar" class="sr-only" accept="image/*">
                    </label>
                    <div>
                        <p class="font-black italic uppercase text-sm">Photo de profil</p>
                        <p class="text-[10px] text-slate-500 uppercase font-bold mt-1">JPG, PNG · Max 2 MB</p>
                        <p class="text-[10px] text-slate-600 mt-1">Clique sur la photo pour changer</p>
                    </div>
                </div>

                {{-- Infos de base --}}
                <div class="space-y-4">
                    <div>
                        <label class="block text-[10px] font-black uppercase tracking-widest text-slate-500 mb-2">Nom d'affichage</label>
                        <input type="text" name="name" value="{{ old('name', $user->name) }}"
                               placeholder="Ton nom..."
                               class="w-full bg-[#111214] border-2 border-slate-800 rounded-2xl px-5 py-4 focus:border-rose-500 transition-all outline-none font-bold text-white placeholder-slate-600"
                               required>
                        @error('name')
                            <p class="text-red-400 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-[10px] font-black uppercase tracking-widest text-slate-500 mb-2">Bio</label>
                        <textarea name="bio" rows="3"
                                  placeholder="Parle de toi, tes objectifs..."
                                  class="w-full bg-[#111214] border-2 border-slate-800 rounded-2xl px-5 py-4 focus:border-rose-500 transition-all outline-none font-medium text-white placeholder-slate-600 resize-none">{{ old('bio', $user->bio) }}</textarea>
                        @error('bio')
                            <p class="text-red-400 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                {{-- Stats en lecture seule --}}
                <div class="bg-[#111214] border border-slate-800 rounded-[2rem] p-5">
                    <h3 class="text-[10px] font-black uppercase tracking-widest text-slate-500 mb-4">Tes Stats</h3>
                    <div class="grid grid-cols-3 gap-3">
                        <div class="text-center">
                            <p class="text-2xl font-black text-rose-500">{{ $user->xp }}</p>
                            <p class="text-[9px] font-bold uppercase text-slate-500 mt-1">XP Total</p>
                        </div>
                        <div class="text-center">
                            <p class="text-2xl font-black">{{ $user->workouts()->where('status', 'completed')->count() }}</p>
                            <p class="text-[9px] font-bold uppercase text-slate-500 mt-1">Séances</p>
                        </div>
                        <div class="text-center">
                            <p class="text-2xl font-black {{ $user->rank['color'] }}">{{ $user->rank['label'] }}</p>
                            <p class="text-[9px] font-bold uppercase text-slate-500 mt-1">Rang</p>
                        </div>
                    </div>
                </div>

                <button type="submit"
                        class="w-full bg-gradient-to-r from-rose-500 to-rose-600 py-5 rounded-2xl font-black uppercase italic tracking-widest shadow-lg shadow-rose-500/20 active:scale-95 transition-all hover:from-rose-600 hover:to-rose-700">
                    Sauvegarder les changements
                </button>
            </form>

            {{-- Zone de danger --}}
            <div class="mt-10 border-t border-slate-800 pt-6">
                <h2 class="text-[10px] font-black uppercase tracking-widest text-slate-600 mb-4">Zone de danger</h2>

                <a href="{{ route('routines.index') }}"
                   class="block w-full text-center py-4 bg-[#111214] border border-slate-800 rounded-2xl font-black text-sm uppercase tracking-wider text-slate-400 hover:border-rose-500/50 hover:text-rose-500 transition-all mb-3">
                    Gérer mes Routines
                </a>

                <a href="{{ route('leaderboard') }}"
                   class="block w-full text-center py-4 bg-[#111214] border border-slate-800 rounded-2xl font-black text-sm uppercase tracking-wider text-slate-400 hover:border-rose-500/50 hover:text-rose-500 transition-all">
                    Voir le Classement
                </a>
            </div>

        </div>
    </div>
</x-app-layout>
