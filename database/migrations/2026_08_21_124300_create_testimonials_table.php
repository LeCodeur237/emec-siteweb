<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('testimonials', function (Blueprint $table) {
            $table->id();
            $table->foreignId('social_action_id')->nullable()->constrained()->nullOnDelete();
            $table->string('name')->nullable();
            $table->string('location')->nullable();
            $table->text('quote');
            $table->string('avatar')->nullable();
            $table->boolean('published')->default(false)->index();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('testimonials');
    }
};
