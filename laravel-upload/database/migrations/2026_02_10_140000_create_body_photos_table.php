<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('body_photos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('photo_path');
            $table->string('body_part')->default('full_body'); // full_body, chest, back, legs, arms, abs
            $table->string('type')->default('progress'); // progress, before, after
            $table->text('notes')->nullable();
            $table->decimal('weight_at_photo', 5, 2)->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('body_photos');
    }
};
