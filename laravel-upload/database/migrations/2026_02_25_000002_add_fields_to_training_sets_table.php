<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('training_sets', function (Blueprint $table) {
            $table->integer('set_number')->nullable()->after('exercise_id');
            $table->timestamp('completed_at')->nullable()->after('rest_time');
        });
    }

    public function down(): void
    {
        Schema::table('training_sets', function (Blueprint $table) {
            $table->dropColumn(['set_number', 'completed_at']);
        });
    }
};
