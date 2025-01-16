<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('needlists', function (Blueprint $table) {
            $table->id();
            $table->foreignId('channel_id')->constrained()->onDelete('cascade');
            $table->foreignId('creator_id')->constrained('users')->onDelete('cascade');
            $table->string('title');
            $table->text('description')->nullable();
            $table->timestamp('due_date')->nullable(); // Date limite optionnelle
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            $table->softDeletes(); // RGPD

            // Index pour les performances
            $table->index(['channel_id', 'is_active']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('needlists');
    }
};
