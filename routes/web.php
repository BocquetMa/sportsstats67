<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\WorkoutController;
use App\Http\Controllers\ExerciseController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\MessageController;
use App\Http\Controllers\RoutineController;

Route::get('/', function () {
    return redirect()->route('login');
});

// Routes protégées par Auth
Route::middleware(['auth', 'verified'])->group(function () {

    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::post('/weight', [DashboardController::class, 'storeWeight'])->name('weight.store');
    Route::get('/weight', fn() => redirect()->route('dashboard'));

    // Profil
    Route::middleware('auth')->group(function () {
        Route::get('/profile/{user}', [ProfileController::class, 'show'])->name('profile.show');
        Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
        Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
        });

    // Exercices
    Route::get('/exercises', [ExerciseController::class, 'index'])->name('exercises.index');

    // Séances (Workouts)
    Route::get('/workouts', [WorkoutController::class, 'index'])->name('workouts.index');
    Route::post('/workouts', [WorkoutController::class, 'store'])->name('workouts.store');
    Route::get('/workouts/{workout}', [WorkoutController::class, 'show'])->name('workouts.show');

    // L'ACTION QUI MANQUAIT : Terminer la séance
    Route::post('/workouts/{workout}/finish', [WorkoutController::class, 'finish'])->name('workout.finish');

    // Gestion des Sets (Séries)
    Route::post('/workouts/{workout}/add-exercise', [WorkoutController::class, 'addExercise'])->name('workouts.add-exercise');
    Route::post('/sets/{trainingSet}/update', [WorkoutController::class, 'updateSet'])->name('sets.update');
    Route::delete('/sets/{set}', [WorkoutController::class, 'destroySet'])->name('sets.destroy');

    // Routines (Programmes d'entraînement)
    Route::get('/routines', [RoutineController::class, 'index'])->name('routines.index');
    Route::get('/routines/create', [RoutineController::class, 'create'])->name('routines.create');
    Route::post('/routines', [RoutineController::class, 'store'])->name('routines.store');
    Route::get('/routines/{routine}/edit', [RoutineController::class, 'edit'])->name('routines.edit');
    Route::put('/routines/{routine}', [RoutineController::class, 'update'])->name('routines.update');
    Route::delete('/routines/{routine}', [RoutineController::class, 'destroy'])->name('routines.destroy');

    // Planification des jours de routine
    Route::put('/routines/{routine}/days/{dayOfWeek}', [RoutineController::class, 'updateDay'])->name('routines.update-day');

    // Démarrage de séance depuis routine
    Route::post('/routines/{routine}/start-today', [RoutineController::class, 'startToday'])->name('routines.start-today');

    // Partage de routines
    Route::post('/routines/{routine}/share', [RoutineController::class, 'share'])->name('routines.share');
    Route::post('/routines/accept-share', [RoutineController::class, 'acceptShare'])->name('routines.accept-share');
});

Route::middleware(['auth'])->group(function () {
    Route::get('/messages', [MessageController::class, 'index'])->name('messages.index');
    Route::get('/messages/{user}', [MessageController::class, 'show'])->name('messages.show');
    Route::post('/messages/{user}', [MessageController::class, 'store'])->name('messages.store');
});

Route::get('/history', [ProfileController::class, 'history'])->name('profile.history');

require __DIR__.'/auth.php';
