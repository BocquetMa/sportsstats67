# SportStats

Application web de suivi de performance sportive — suivez vos entraînements, progressez et rivalisez avec vos amis.

![SportStats](https://img.shields.io/badge/Laravel-12-FF2D20?style=for-the-badge&logo=laravel)
![PHP](https://img.shields.io/badge/PHP-8.3-777BB4?style=for-the-badge&logo=php)
![TailwindCSS](https://img.shields.io/badge/TailwindCSS-3-06B6D4?style=for-the-badge&logo=tailwindcss)
![Alpine.js](https://img.shields.io/badge/Alpine.js-3-8BC0D0?style=for-the-badge&logo=alpinedotjs)

---

## Fonctionnalités

### Entraînement
- **Séances libres** — Crée une séance à la volée et ajoute les exercices que tu veux
- **Routines (programmes)** — Planifie ton programme semaine par semaine (Lundi = Jambes, Mercredi = Chest…)
- **Focus Mode** — Interface épurée pendant la séance : une série à la fois, timer de repos intégré
- **Personal Records (PR)** — Tes records personnels s'affichent automatiquement pendant la séance

### Exercices
- **Catalogue de 1300+ exercices** depuis l'API ExerciseDB
- Recherche par nom, muscle cible, partie du corps
- GIFs animés pour chaque exercice

### Progression & Évolution
- **Graphiques de poids** — Évolution de ton poids sur 7/30/90 jours
- **Calcul 1RM** — Estimé avec 4 formules (Epley, Brzycki, Lander, Lombardi)
- **Suivi des mesures** — Tour de poitrine, taille, hanches, bras, cuisses, mollets
- **Photos avant/après** — Timeline de transformation par partie du corps
- **Calories brûlées** — Estimé automatiquement par la méthode MET
- **Personal Bests** — Ton top 20 des records par exercice

### Gamification
- **Système XP** — Gagne de l'XP à chaque entraînement terminé
- **Rangs** — NOVICE → ESPOIR → PRO → ÉLITE selon ton XP
- **Badges** — 10 badges déblocables (Premier Pas, Régularité, Marathonien…)
- **Leaderboard** — Classement global par XP, nombre de séances ou volume

### Social
- **Profil public** — Partage tes stats, badges et rangs
- **Messagerie** — Chat en temps réel entre utilisateurs (Laravel Reverb)
- **Partage de routines** — Partage tes programmes avec un code ou un lien

### Dashboard
- Poids actuel + tendance
- Routine du jour
- Dernières séances
- Volume hebdomadaire

---

## Stack Technique

| Couche | Technologie |
|--------|-------------|
| Backend | Laravel 12 (PHP 8.3) |
| Frontend | Blade + Alpine.js 3 |
| CSS | Tailwind CSS 3 |
| Build | Vite 7 |
| Base de données | MySQL / PostgreSQL / SQLite |
| Temps réel | Laravel Reverb (WebSockets) |
| Exercices | ExerciseDB API (exercisedb.dev) |

---

## Installation

### Prérequis

- PHP 8.2+
- Composer
- Node.js 18+
- MySQL, PostgreSQL, ou SQLite

### 1. Clone du projet

```bash
git clone https://github.com/TON_PSEUDO/sportStats.git
cd sportStats/laravel-upload
```

### 2. Installation des dépendances

```bash
composer install
npm install
```

### 3. Configuration de l'environnement

```bash
cp .env.example .env
php artisan key:generate
```

Modifie le fichier `.env` avec tes paramètres de base de données :

```env
APP_NAME=SportStats
APP_URL=http://localhost

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=sportstats
DB_USERNAME=root
DB_PASSWORD=

FILESYSTEM_DISK=public
```

### 4. Base de données

```bash
php artisan migrate
php artisan storage:link
```

### 5. Import des exercices (1300+ exercices)

```bash
php artisan exercises:import
```

> Nécessite une connexion internet. L'import prend environ 2 minutes. Tu peux aussi lancer :
> ```bash
> php artisan db:seed --class=ExerciseSeeder
> ```

### 6. Build des assets

```bash
npm run build
# ou en développement :
npm run dev
```

### 7. Lancer le serveur

```bash
php artisan serve
```

L'app est disponible sur [http://localhost:8000](http://localhost:8000).

---

## Déploiement sur VPS

### Avec Nginx + PHP-FPM (recommandé)

**Configuration Nginx :**

```nginx
server {
    listen 80;
    server_name ton-domaine.com;
    root /var/www/sportstats/laravel-upload/public;

    add_header X-Frame-Options "SAMEORIGIN";
    add_header X-Content-Type-Options "nosniff";

    index index.php;
    charset utf-8;

    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }

    location = /favicon.ico { access_log off; log_not_found off; }
    location = /robots.txt  { access_log off; log_not_found off; }

    error_page 404 /index.php;

    location ~ \.php$ {
        fastcgi_pass unix:/var/run/php/php8.3-fpm.sock;
        fastcgi_param SCRIPT_FILENAME $realpath_root$fastcgi_script_name;
        include fastcgi_params;
        fastcgi_hide_header X-Powered-By;
    }

    location ~ /\.(?!well-known).* {
        deny all;
    }
}
```

**Variables d'environnement production (`.env`) :**

```env
APP_ENV=production
APP_DEBUG=false
APP_URL=https://ton-domaine.com

SESSION_DRIVER=database
CACHE_STORE=database
QUEUE_CONNECTION=database
```

**Commandes de déploiement :**

```bash
composer install --no-dev --optimize-autoloader
php artisan config:cache
php artisan route:cache
php artisan view:cache
npm run build
php artisan migrate --force
php artisan storage:link
php artisan exercises:import
```

---

## Structure du Projet

```
laravel-upload/
├── app/
│   ├── Console/Commands/
│   │   └── ImportExercises.php      # Import 1300+ exercices
│   ├── Http/Controllers/
│   │   ├── DashboardController.php  # Page d'accueil
│   │   ├── WorkoutController.php    # Séances d'entraînement
│   │   ├── RoutineController.php    # Programmes hebdomadaires
│   │   ├── ExerciseController.php   # Catalogue + recherche AJAX
│   │   ├── EvolutionController.php  # Progression & métriques
│   │   ├── GamificationController.php # XP, badges, leaderboard
│   │   ├── MessageController.php    # Chat temps réel
│   │   ├── ProfileController.php    # Profils utilisateurs
│   │   └── StatsController.php      # Statistiques & graphiques
│   └── Models/
│       ├── User.php
│       ├── Workout.php              # Séances
│       ├── TrainingSet.php          # Séries (sets)
│       ├── Exercise.php             # Catalogue d'exercices
│       ├── Routine.php              # Programmes
│       ├── RoutineDay.php           # Jours de programme
│       ├── BodyMetric.php           # Mesures corporelles
│       ├── BodyPhoto.php            # Photos de progression
│       ├── Badge.php                # Système de badges
│       ├── Message.php              # Messagerie
│       └── WorkoutCalorie.php       # Calories brûlées
├── database/
│   ├── migrations/                  # 21 migrations
│   └── seeders/
│       └── ExerciseSeeder.php       # Import 1300 exercices
├── resources/
│   ├── js/
│   │   ├── app.js
│   │   └── workout-manager.js       # Alpine.js Focus Mode
│   └── views/
│       ├── layouts/
│       │   ├── app.blade.php        # Layout principal dark (#08090a)
│       │   └── navigation.blade.php # Barre de navigation bas
│       ├── dashboard.blade.php
│       ├── workouts/                # Séances (index + show)
│       ├── routines/                # Programmes (index + create + edit)
│       ├── stats/                   # Statistiques + Évolution
│       ├── profile/                 # Profils + historique
│       ├── messages/                # Chat
│       ├── exercises/               # Catalogue d'exercices
│       └── gamification/            # Leaderboard
└── routes/
    └── web.php                      # 55+ routes
```

---

## API Interne (AJAX)

| Méthode | URL | Description |
|---------|-----|-------------|
| GET | `/api/exercises/search?q=squat` | Recherche d'exercices |
| GET | `/api/stats/weight?period=30` | Évolution du poids |
| GET | `/api/stats/volume?weeks=8` | Volume hebdomadaire |
| GET | `/api/stats/exercise?exercise_id=1` | Progression par exercice |
| GET | `/api/stats/dashboard` | Stats globales |
| GET | `/evolution/body-metrics?period=90` | Métriques corporelles |
| GET | `/evolution/personal-bests` | Records personnels |
| GET | `/evolution/1rm?weight=100&reps=5` | Calcul 1RM |
| POST | `/sets/{id}/update` | Mise à jour d'une série (AJAX) |

---

## Commandes Artisan

```bash
# Importer les exercices depuis l'API ExerciseDB (1300+)
php artisan exercises:import

# Limiter l'import à 500 exercices
php artisan exercises:import --limit=500

# Nettoyer le cache
php artisan optimize:clear
```

---

## Licence

MIT — utilise et adapte librement ce projet.
