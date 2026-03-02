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
    Schema::create('workouts', function (Blueprint $table) {
        $table->id();
        $table->foreignId('user_id')->constrained()->onDelete('cascade');
        $table->string('title'); // ex: "Lundi - Pectoraux"
        $table->timestamps();
        $table->foreignId('routine_id')->nullable()->constrained(); // Si c'est basé sur une routine
        $table->timestamp('started_at')->nullable();
        $table->timestamp('completed_at')->nullable();
        $table->string('status')->default('draft');
    });
}
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('workouts');
    }
};
