<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('gallery_channels', function (Blueprint $table) {
            $table->id();
            $table->foreignId('channel_id')->constrained()->onDelete('cascade');
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('file_path'); // Chemin du fichier dans le storage
            $table->string('original_filename'); // Nom original du fichier pour RGPD
            $table->string('mime_type'); // Type MIME pour validation
            $table->integer('file_size'); // Taille en octets
            $table->text('description')->nullable();
            $table->json('metadata')->nullable(); // Pour EXIF et autres métadonnées
            $table->boolean('is_approved')->default(true); // Pour modération éventuelle
            $table->timestamps();
            $table->softDeletes(); // RGPD : conservation limitée

            // Index pour les performances
            $table->index(['channel_id', 'created_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('gallery_channels');
    }
};
