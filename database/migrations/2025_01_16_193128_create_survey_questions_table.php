<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('survey_questions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('survey_id')->constrained()->onDelete('cascade');
            $table->string('question');
            $table->text('description')->nullable();
            $table->enum('type', [
                'single_choice',    // Choix unique (radio)
                'multiple_choice',  // Choix multiples (checkboxes)
                'text',            // Réponse texte libre
                'date',            // Date
                'scale',           // Échelle (1-5, 1-10)
                'yes_no'           // Oui/Non
            ]);
            $table->json('options')->nullable(); // Options pour les choix ou paramètres (ex: échelle min/max)
            $table->boolean('is_required')->default(true);
            $table->integer('order')->default(0); // Ordre d'affichage
            $table->timestamps();
            $table->softDeletes(); // RGPD

            $table->index(['survey_id', 'order']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('survey_questions');
    }
};
