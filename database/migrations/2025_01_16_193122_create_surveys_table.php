<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('surveys', function (Blueprint $table) {
            $table->id();
            $table->foreignId('channel_id')->constrained()->onDelete('cascade');
            $table->foreignId('creator_id')->constrained('users')->onDelete('cascade');
            $table->string('title');
            $table->text('description')->nullable();
            $table->timestamp('start_date')->nullable();
            $table->timestamp('end_date')->nullable();
            $table->boolean('is_anonymous')->default(false); // Si les réponses sont anonymes
            $table->boolean('allow_multiple_responses')->default(false); // Si plusieurs réponses sont permises
            $table->boolean('show_results')->default(true); // Si les résultats sont visibles aux participants
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            $table->softDeletes(); // RGPD: conservation limitée

            $table->index(['channel_id', 'is_active']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('surveys');
    }
};
