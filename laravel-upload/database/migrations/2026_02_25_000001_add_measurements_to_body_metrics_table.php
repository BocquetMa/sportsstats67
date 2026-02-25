<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('body_metrics', function (Blueprint $table) {
            foreach (['chest', 'waist', 'hips', 'arms', 'thighs', 'calves'] as $column) {
                if (!Schema::hasColumn('body_metrics', $column)) {
                    $table->decimal($column, 5, 1)->nullable();
                }
            }
        });
    }

    public function down(): void
    {
        Schema::table('body_metrics', function (Blueprint $table) {
            $table->dropColumn(['chest', 'waist', 'hips', 'arms', 'thighs', 'calves']);
        });
    }
};
