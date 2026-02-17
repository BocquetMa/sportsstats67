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
        // Partages de routines entre utilisateurs
        Schema::create('routine_shares', function (Blueprint $table) {
            $table->id();
            $table->foreignId('routine_id')->constrained()->onDelete('cascade');
            $table->foreignId('shared_by')->constrained('users')->onDelete('cascade');
            $table->foreignId('shared_with')->nullable()->constrained('users')->onDelete('cascade');
            $table->string('share_code', 10)->unique()->nullable(); // Code pour partage public
            $table->enum('share_type', ['direct', 'link'])->default('direct');
            $table->boolean('is_accepted')->default(false);
            $table->timestamp('accepted_at')->nullable();
            $table->timestamp('expires_at')->nullable();
            $table->timestamps();

            $table->index(['share_code', 'expires_at']);
        });

        // Ajout d'un champ pour marquer les routines comme publiques
        Schema::table('routines', function (Blueprint $table) {
            $table->boolean('is_public')->default(false)->after('name');
            $table->boolean('is_shared_copy')->default(false)->after('is_public');
            $table->foreignId('original_routine_id')->nullable()->constrained('routines')->nullOnDelete()->after('is_shared_copy');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('routines', function (Blueprint $table) {
            $table->dropConstrainedForeignId('original_routine_id');
            $table->dropColumn(['is_public', 'is_shared_copy']);
        });

        Schema::dropIfExists('routine_shares');
    }
};
