/**
 * Workout Manager - Alpine.js Component
 * Gère l'état et la logique de l'interface d'entraînement
 */

export function workoutManager(totalExercises, csrfToken) {
    return {
        // État
        currentIndex: 0,
        totalExercises: totalExercises,
        showList: false,
        activeSet: null,
        completedSets: [],
        restMode: false,
        timer: 0,
        restDuration: 90,
        interval: null,
        isLoading: false,
        error: null,

        // Navigation entre exercices
        next() {
            if (this.currentIndex < this.totalExercises - 1) {
                this.currentIndex++;
            }
        },

        prev() {
            if (this.currentIndex > 0) {
                this.currentIndex--;
            }
        },

        goToExercise(index) {
            this.currentIndex = index;
            this.showList = false;
        },

        // Gestion des séries
        async completeSet(setId, restTime = 90) {
            // Récupération des valeurs
            const weightInput = document.getElementById(`weight-${setId}`);
            const repsInput = document.getElementById(`reps-${setId}`);

            if (!weightInput || !repsInput) {
                this.showError('Impossible de trouver les champs de saisie');
                return;
            }

            const weight = parseFloat(weightInput.value);
            const reps = parseInt(repsInput.value);

            // Validation
            if (isNaN(weight) || weight < 0) {
                this.showError('Le poids doit être un nombre positif');
                return;
            }

            if (isNaN(reps) || reps < 1 || reps > 100) {
                this.showError('Les répétitions doivent être entre 1 et 100');
                return;
            }

            this.activeSet = setId;
            this.isLoading = true;
            this.error = null;

            try {
                const response = await fetch(`/sets/${setId}/update`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken,
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({ weight, reps })
                });

                if (!response.ok) {
                    const errorData = await response.json();
                    throw new Error(errorData.message || 'Erreur lors de la mise à jour');
                }

                const data = await response.json();

                // Marquer comme complété
                if (!this.completedSets.includes(setId)) {
                    this.completedSets.push(setId);
                }

                // Démarrer le timer de repos
                this.startRest(restTime);

            } catch (error) {
                console.error('Erreur lors de la mise à jour:', error);
                this.showError(error.message || 'Erreur lors de la sauvegarde');
            } finally {
                this.isLoading = false;
                this.activeSet = null;
            }
        },

        // Gestion du timer de repos
        startRest(seconds) {
            this.restDuration = seconds;
            this.timer = seconds;
            this.restMode = true;

            // Nettoyer l'intervalle existant
            if (this.interval) {
                clearInterval(this.interval);
            }

            // Démarrer le nouveau timer
            this.interval = setInterval(() => {
                this.timer--;

                // Arrêter quand terminé
                if (this.timer <= 0) {
                    this.stopTimer();
                    this.playSound(); // Notification sonore
                }
            }, 1000);
        },

        stopTimer() {
            if (this.interval) {
                clearInterval(this.interval);
                this.interval = null;
            }
            this.restMode = false;
            this.timer = 0;
        },

        addTime(seconds) {
            this.timer += seconds;
        },

        formatTime(seconds) {
            const mins = Math.floor(seconds / 60);
            const secs = seconds % 60;
            return `${mins}:${secs.toString().padStart(2, '0')}`;
        },

        // Notification sonore (optionnel)
        playSound() {
            // Utiliser l'API Web Audio pour un bip simple
            try {
                const audioContext = new (window.AudioContext || window.webkitAudioContext)();
                const oscillator = audioContext.createOscillator();
                const gainNode = audioContext.createGain();

                oscillator.connect(gainNode);
                gainNode.connect(audioContext.destination);

                oscillator.frequency.value = 800;
                oscillator.type = 'sine';

                gainNode.gain.setValueAtTime(0.3, audioContext.currentTime);
                gainNode.gain.exponentialRampToValueAtTime(0.01, audioContext.currentTime + 0.5);

                oscillator.start(audioContext.currentTime);
                oscillator.stop(audioContext.currentTime + 0.5);
            } catch (error) {
                console.warn('Notification sonore non disponible:', error);
            }
        },

        // Gestion des erreurs
        showError(message) {
            this.error = message;
            setTimeout(() => {
                this.error = null;
            }, 5000);
        },

        // Statistiques de la séance
        getCompletedCount() {
            return this.completedSets.length;
        },

        getTotalSets() {
            // Cette valeur devrait être passée ou calculée
            return document.querySelectorAll('[id^="set-row-"]').length;
        },

        getProgress() {
            const total = this.getTotalSets();
            if (total === 0) return 0;
            return Math.round((this.getCompletedCount() / total) * 100);
        },

        // Lifecycle
        init() {
            console.log('Workout Manager initialisé');

            // Charger les séries complétées depuis le localStorage
            this.loadCompletedSets();

            // Nettoyer le timer quand on quitte la page
            window.addEventListener('beforeunload', () => {
                this.stopTimer();
                this.saveCompletedSets();
            });
        },

        loadCompletedSets() {
            const saved = localStorage.getItem('completedSets');
            if (saved) {
                try {
                    this.completedSets = JSON.parse(saved);
                } catch (error) {
                    console.warn('Impossible de charger les séries complétées:', error);
                }
            }
        },

        saveCompletedSets() {
            localStorage.setItem('completedSets', JSON.stringify(this.completedSets));
        }
    }
}

// Export global pour utilisation dans Blade
window.workoutManager = workoutManager;
