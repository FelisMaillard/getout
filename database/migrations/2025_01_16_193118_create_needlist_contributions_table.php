<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('needlist_contributions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('needlist_item_id')->constrained()->onDelete('cascade');
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->integer('quantity_promised');
            $table->text('note')->nullable();
            $table->boolean('is_fulfilled')->default(false);
            $table->timestamp('fulfilled_at')->nullable();
            $table->timestamps();
            $table->softDeletes(); // RGPD

            // Un utilisateur ne peut contribuer qu'une fois à un item
            $table->unique(['needlist_item_id', 'user_id'], 'unique_contribution');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('needlist_contributions');
    }
};
