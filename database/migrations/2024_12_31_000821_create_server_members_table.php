<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('server_members', function (Blueprint $table) {
            $table->id();
            $table->foreignId('server_id')->constrained()->onDelete('cascade');
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->enum('role', ['owner', 'admin', 'moderator', 'member'])->default('member');
            $table->boolean('privacy_consent')->default(false);
            $table->timestamp('privacy_consent_date')->nullable();
            $table->timestamp('last_read_at')->nullable();
            $table->timestamps();
            $table->softDeletes();

            // Un utilisateur ne peut être qu'une fois dans un serveur
            $table->unique(['server_id', 'user_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('server_members');
    }
};
