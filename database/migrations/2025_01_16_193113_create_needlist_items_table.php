<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('needlist_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('needlist_id')->constrained()->onDelete('cascade');
            $table->string('name'); // Nom de l'item nécessaire
            $table->text('description')->nullable();
            $table->integer('quantity')->default(1);
            $table->string('unit')->nullable(); // Unité de mesure (ex: "bouteilles", "kg")
            $table->boolean('is_required')->default(true);
            $table->integer('max_contributors')->nullable(); // Nombre max de contributeurs par item
            $table->timestamps();
            $table->softDeletes(); // RGPD
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('needlist_items');
    }
};
