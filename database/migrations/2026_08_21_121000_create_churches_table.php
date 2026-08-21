<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('churches', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->string('baptism_name')->nullable();
            $table->string('city')->nullable()->index();
            $table->string('address')->nullable();
            $table->string('neighborhood')->nullable();
            $table->string('locality')->nullable();
            $table->string('sector')->nullable();
            $table->string('district')->nullable();
            $table->string('circumscription')->nullable();
            $table->string('mission_field')->nullable();
            $table->string('region')->nullable()->index();
            $table->text('description')->nullable();
            $table->text('pastor_vision')->nullable();
            $table->string('contact')->nullable();
            $table->string('map_url')->nullable();
            $table->string('image')->nullable();
            $table->string('status', 30)->default('draft')->index();
            $table->boolean('active')->default(true)->index();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('churches');
    }
};
