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
    Schema::table('workouts', function (Blueprint $table) {
        $table->foreignId('routine_id')->nullable()->constrained()->onDelete('set null');
        $table->string('status')->default('in_progress'); // pour savoir si elle est finie
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('workouts', function (Blueprint $table) {
            //
        });
    }
};
