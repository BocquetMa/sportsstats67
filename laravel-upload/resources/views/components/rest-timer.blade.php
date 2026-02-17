<div x-data="{
    timer: 0,
    interval: null,
    startTimer(seconds) {
        clearInterval(this.interval);
        this.timer = seconds;
        this.interval = setInterval(() => {
            if (this.timer > 0) this.timer--;
            else clearInterval(this.interval);
        }, 1000);
    }
}"
@start-rest.window="startTimer($event.detail)"
class="fixed bottom-5 right-5 z-50">

    <template x-if="timer > 0">
        <div class="bg-indigo-600 text-white p-4 rounded-full shadow-2xl flex items-center gap-3 animate-bounce">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
            <span class="font-bold text-xl" x-text="Math.floor(timer / 60) + ':' + (timer % 60).toString().padStart(2, '0')"></span>
            <button @click="timer = 0" class="bg-indigo-800 rounded-full p-1 hover:bg-red-500">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>
    </template>
</div>
