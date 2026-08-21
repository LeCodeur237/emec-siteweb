<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('events', function (Blueprint $table) {
            $table->id();
            $table->foreignId('event_category_id')->nullable()->constrained()->nullOnDelete();
            $table->string('title');
            $table->string('slug')->unique();
            $table->text('description')->nullable();
            $table->string('image')->nullable();
            $table->dateTime('start_at')->index();
            $table->dateTime('end_at')->nullable();
            $table->string('location')->nullable();
            $table->string('city')->nullable()->index();
            $table->boolean('featured')->default(false)->index();
            $table->string('status', 30)->default('draft')->index();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('events');
    }
};
