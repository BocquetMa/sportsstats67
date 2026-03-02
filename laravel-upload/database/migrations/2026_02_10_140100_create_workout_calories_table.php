<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('workout_calories', function (Blueprint $table) {
            $table->id();
            $table->foreignId('workout_id')->constrained()->onDelete('cascade');
            $table->integer('estimated_calories'); // Calories brûlées estimées
            $table->decimal('met_value', 4, 2)->nullable(); // MET value used for calculation
            $table->integer('duration_minutes')->nullable(); // Durée de l'entraînement
            $table->timestamps();
        });

        // Ajouter des mesures corporelles supplémentaires
        Schema::table('body_metrics', function (Blueprint $table) {
            $table->decimal('chest', 5, 2)->nullable()->after('body_fat');
            $table->decimal('waist', 5, 2)->nullable()->after('chest');
            $table->decimal('hips', 5, 2)->nullable()->after('waist');
            $table->decimal('arms', 5, 2)->nullable()->after('hips');
            $table->decimal('thighs', 5, 2)->nullable()->after('arms');
            $table->decimal('calves', 5, 2)->nullable()->after('thighs');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('workout_calories');

        Schema::table('body_metrics', function (Blueprint $table) {
            $table->dropColumn(['chest', 'waist', 'hips', 'arms', 'thighs', 'calves']);
        });
    }
};
