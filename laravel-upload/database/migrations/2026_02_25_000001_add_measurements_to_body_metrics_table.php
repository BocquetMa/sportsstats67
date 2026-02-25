<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('body_metrics', function (Blueprint $table) {
            $table->decimal('chest', 5, 1)->nullable()->after('objective');
            $table->decimal('waist', 5, 1)->nullable()->after('chest');
            $table->decimal('hips', 5, 1)->nullable()->after('waist');
            $table->decimal('arms', 5, 1)->nullable()->after('hips');
            $table->decimal('thighs', 5, 1)->nullable()->after('arms');
            $table->decimal('calves', 5, 1)->nullable()->after('thighs');
        });
    }

    public function down(): void
    {
        Schema::table('body_metrics', function (Blueprint $table) {
            $table->dropColumn(['chest', 'waist', 'hips', 'arms', 'thighs', 'calves']);
        });
    }
};
