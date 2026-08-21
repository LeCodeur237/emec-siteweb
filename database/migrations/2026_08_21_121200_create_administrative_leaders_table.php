<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('administrative_leaders', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('responsibility');
            $table->text('description')->nullable();
            $table->string('image')->nullable();
            $table->date('start_date')->nullable();
            $table->date('end_date')->nullable();
            $table->boolean('active')->default(true)->index();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('administrative_leaders');
    }
};
