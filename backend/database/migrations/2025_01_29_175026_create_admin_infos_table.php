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
        Schema::create('admin_infos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('admin_id')->constrained('admins')->onDelete('cascade');
            $table->string('name');
            $table->string('image')->nullable(); // Add image column
            $table->enum('status', ['busy', 'available'])->default('available');
            $table->json('skills')->nullable(); // Store skills as JSON array
            $table->enum('skill_level', ['beginner', 'intermediate', 'advanced', 'expert'])->default('beginner');
            $table->text('signature_products')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('admin_infos');
    }
};