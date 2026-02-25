<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('training_sets', function (Blueprint $table) {
            if (!Schema::hasColumn('training_sets', 'set_number')) {
                $table->integer('set_number')->nullable();
            }
            if (!Schema::hasColumn('training_sets', 'completed_at')) {
                $table->timestamp('completed_at')->nullable();
            }
        });
    }

    public function down(): void
    {
        Schema::table('training_sets', function (Blueprint $table) {
            $table->dropColumn(['set_number', 'completed_at']);
        });
    }
};
