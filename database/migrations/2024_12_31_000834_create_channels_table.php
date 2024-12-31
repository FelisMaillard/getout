<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('channels', function (Blueprint $table) {
            $table->id();
            $table->foreignId('server_id')->constrained()->onDelete('cascade');
            $table->string('name');
            $table->string('slug');
            $table->text('description')->nullable();
            $table->enum('type', ['text', 'voice', 'announcement'])->default('text');
            $table->boolean('is_private')->default(false);
            $table->timestamps();
            $table->softDeletes();

            // Un nom de channel doit être unique dans un serveur
            $table->unique(['server_id', 'slug']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('channels');
    }
};
