<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('survey_answer_items', function (Blueprint $table) {
            $table->uuid();
            $table->foreignId('survey_response_id')->constrained()->onDelete('cascade');
            $table->foreignId('survey_question_id')->constrained()->onDelete('cascade');
            $table->text('answer_text')->nullable(); // Pour les réponses texte
            $table->json('answer_data')->nullable(); // Pour les autres types (choix multiples, etc.)
            $table->timestamps();
            $table->softDeletes(); // RGPD

            // Une seule réponse par question par réponse
            $table->unique(['survey_response_id', 'survey_question_id'], 'unique_question_answer');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('survey_answer_items');
    }
};
