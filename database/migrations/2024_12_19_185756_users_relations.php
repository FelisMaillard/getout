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
        Schema::create('users_relations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('friend_id')->constrained('users')->onDelete('cascade');
            $table->timestamp('read_at')->nullable();
            $table->enum('status', ['pending', 'accepted', 'blocked'])->default('pending');
            $table->boolean('privacy_consent')->default(false);
            $table->timestamp('privacy_consent_date')->nullable();
            $table->timestamps();
            $table->softDeletes(); // Pour la conservation limitée des données

            $table->unique(['user_id', 'friend_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users_relations');
    }
};
