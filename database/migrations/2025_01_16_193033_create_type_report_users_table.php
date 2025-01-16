<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('type_report_users', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // Ex: harassment, spam, inappropriate_content
            $table->string('description');
            $table->boolean('active')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('type_report_users');
    }
};
