<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('survey_responses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('survey_id')->constrained()->onDelete('cascade');
            $table->foreignId('user_id')->nullable()->constrained()->onDelete('set null'); // Nullable pour les sondages anonymes
            $table->json('metadata')->nullable(); // Info de l'appareil, etc. pour détection de fraude
            $table->timestamps();
            $table->softDeletes(); // RGPD

            // Si les réponses multiples ne sont pas autorisées
            $table->unique(['survey_id', 'user_id'], 'unique_survey_response');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('survey_responses');
    }
};
