<x-app-layout>
    <div class="flex flex-col h-screen bg-[#08090a] text-white">

        <header class="p-6 bg-[#111214] border-b border-slate-800 flex items-center gap-4">
            <a href="{{ route('messages.index') }}" class="text-slate-500">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M15 19l-7-7 7-7"/></svg>
            </a>
            <div class="w-10 h-10 bg-rose-500 rounded-full flex items-center justify-center font-black italic">
                {{ substr($user->name, 0, 1) }}
            </div>
            <div>
                <h2 class="font-black uppercase italic text-sm leading-none">{{ $user->name }}</h2>
                <span class="text-[9px] text-green-500 font-bold uppercase tracking-widest">En ligne</span>
            </div>
        </header>

        <div class="flex-1 overflow-y-auto p-6 space-y-4 no-scrollbar" id="chat-box">
            @foreach($messages as $message)
                <div class="flex {{ $message->sender_id === auth()->id() ? 'justify-end' : 'justify-start' }}">
                    <div class="max-w-[80%] p-4 rounded-[2rem] {{ $message->sender_id === auth()->id() ? 'bg-rose-500 text-white rounded-tr-none' : 'bg-[#111214] border border-slate-800 rounded-tl-none' }}">
                        <p class="text-sm font-medium leading-relaxed">{{ $message->content }}</p>
                        <span class="text-[8px] opacity-50 mt-2 block font-bold uppercase">
                            {{ $message->created_at->format('H:i') }}
                        </span>
                    </div>
                </div>
            @endforeach
        </div>

        <div class="p-6 pb-32 bg-[#08090a]">
            <form id="chat-form" class="relative flex items-center gap-2">
                @csrf
                <input type="text" id="message-input" name="content" placeholder="Écris ton message..." autocomplete="off"
                       class="w-full bg-[#111214] border-slate-800 border-2 rounded-full px-6 py-4 text-sm focus:border-rose-500 focus:ring-0 transition-all">
                <button type="submit" class="absolute right-2 w-12 h-12 bg-rose-500 rounded-full flex items-center justify-center shadow-lg shadow-rose-500/20 active:scale-90 transition-transform">
                    <svg class="w-5 h-5 text-white rotate-90" fill="currentColor" viewBox="0 0 24 24"><path d="M2.01 21L23 12 2.01 3 2 10l15 2-15 2z"/></svg>
                </button>
            </form>
        </div>

        @include('layouts.navigation')
    </div>

    <script type="module">
        // Attendre que Echo soit disponible
        function waitForEcho() {
            return new Promise((resolve) => {
                if (window.Echo) {
                    resolve();
                } else {
                    const interval = setInterval(() => {
                        if (window.Echo) {
                            clearInterval(interval);
                            resolve();
                        }
                    }, 100);
                }
            });
        }

        // Initialiser le chat
        async function initChat() {
            await waitForEcho();

            const chatBox = document.getElementById('chat-box');
            const chatForm = document.getElementById('chat-form');
            const messageInput = document.getElementById('message-input');

            // Scroll vers le bas immédiat
            chatBox.scrollTop = chatBox.scrollHeight;

            // Configuration du canal WebSocket pour recevoir les messages en temps réel
            const senderId = {{ auth()->id() }};
            const receiverId = {{ $user->id }};
            const ids = [senderId, receiverId].sort();
            const channelName = `chat.${ids[0]}.${ids[1]}`;

            console.log('Connexion au canal:', channelName);

            // Écoute des nouveaux messages via Echo (WebSocket)
            window.Echo.private(channelName)
                .listen('MessageSent', (e) => {
                    console.log('Message reçu via WebSocket:', e);

                    // Vérifier si le message vient de l'autre utilisateur
                    if (e.message.sender_id !== senderId) {
                        // Message reçu de l'autre utilisateur
                        const messageHtml = `
                            <div class="flex justify-start">
                                <div class="max-w-[80%] p-4 rounded-[2rem] bg-[#111214] border border-slate-800 rounded-tl-none">
                                    <p class="text-sm font-medium leading-relaxed">${e.message.content}</p>
                                    <span class="text-[8px] opacity-50 mt-2 block font-bold uppercase">Maintenant</span>
                                </div>
                            </div>
                        `;
                        chatBox.insertAdjacentHTML('beforeend', messageHtml);
                        chatBox.scrollTo({ top: chatBox.scrollHeight, behavior: 'smooth' });
                    }
                });

            chatForm.addEventListener('submit', async (e) => {
                e.preventDefault();

                const content = messageInput.value.trim();
                if(!content) return;

                // 1. On prépare les données
                const formData = new FormData(chatForm);

                // 2. On vide l'input tout de suite pour la réactivité (UX)
                messageInput.value = '';

                try {
                    const response = await fetch("{{ route('messages.store', $user) }}", {
                        method: 'POST',
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest',
                            'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value
                        },
                        body: formData
                    });

                    const data = await response.json();

                    if(data.success) {
                        // 3. On ajoute le message dynamiquement sans recharger
                        const messageHtml = `
                            <div class="flex justify-end">
                                <div class="max-w-[80%] p-4 rounded-[2rem] bg-rose-500 text-white rounded-tr-none">
                                    <p class="text-sm font-medium leading-relaxed">${data.message.content}</p>
                                    <span class="text-[8px] opacity-50 mt-2 block font-bold uppercase">Maintenant</span>
                                </div>
                            </div>
                        `;
                        chatBox.insertAdjacentHTML('beforeend', messageHtml);

                        // 4. Scroll fluide vers le nouveau message
                        chatBox.scrollTo({ top: chatBox.scrollHeight, behavior: 'smooth' });
                    }
                } catch (error) {
                    console.error("Erreur d'envoi:", error);
                    alert("Impossible d'envoyer le message.");
                }
            });
        }

        // Démarrer le chat
        initChat();
    </script>
</x-app-layout>
