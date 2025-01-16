<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('private_messages', function (Blueprint $table) {
            $table->id();
            $table->foreignId('conversation_id')->constrained()->onDelete('cascade');
            $table->foreignId('sender_id')->constrained('users')->onDelete('cascade');
            $table->text('content');
            $table->enum('type', ['text', 'file', 'system'])->default('text');
            $table->json('metadata')->nullable(); // Pour les fichiers ou métadonnées additionnelles
            $table->timestamp('read_at')->nullable();
            $table->timestamp('edited_at')->nullable();
            $table->timestamp('deleted_by_sender_at')->nullable(); // RGPD : Quand l'expéditeur supprime
            $table->timestamp('deleted_by_receiver_at')->nullable(); // RGPD : Quand le destinataire supprime
            $table->timestamps();
            $table->softDeletes(); // Suppression complète après durée légale
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('private_messages');
    }
};
