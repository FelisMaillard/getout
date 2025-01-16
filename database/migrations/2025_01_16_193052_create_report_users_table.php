<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('report_users', function (Blueprint $table) {
            $table->id();
            $table->foreignId('reporter_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('reported_user_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('type_report_id')->constrained('type_report_users');
            $table->text('description')->nullable();
            $table->json('evidence')->nullable(); // URLs des preuves, captures d'écran, etc.
            $table->enum('status', ['pending', 'investigating', 'resolved', 'rejected'])->default('pending');
            $table->text('resolution_note')->nullable();
            $table->foreignId('resolved_by')->nullable()->constrained('users');
            $table->timestamp('resolved_at')->nullable();
            $table->timestamps();
            $table->softDeletes(); // Conservation limitée pour RGPD

            // Limite de signalement : un utilisateur ne peut pas signaler le même utilisateur
            // pour le même type dans un intervalle donné
            $table->unique(['reporter_id', 'reported_user_id', 'type_report_id'], 'unique_user_report');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('report_users');
    }
};
