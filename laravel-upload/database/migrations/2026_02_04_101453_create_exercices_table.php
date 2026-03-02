<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
{
    Schema::create('exercises', function (Blueprint $table) {
        $table->id();
        $table->string('exerciseId')->unique(); // ID de l'API (ex: ztAa1RK)
        $table->string('name');
        $table->string('gifUrl');
        $table->json('targetMuscles');
        $table->json('bodyParts');
        $table->json('equipments');
        $table->json('secondaryMuscles')->nullable();
        $table->json('instructions')->nullable();
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('exercices');
    }
};
