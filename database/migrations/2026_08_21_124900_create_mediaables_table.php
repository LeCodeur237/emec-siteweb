<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('mediaables', function (Blueprint $table) {
            $table->foreignId('media_id')->constrained('media')->cascadeOnDelete();
            $table->string('mediaable_type');
            $table->unsignedBigInteger('mediaable_id');

            $table->index(['mediaable_type', 'mediaable_id']);
            $table->unique(['media_id', 'mediaable_type', 'mediaable_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('mediaables');
    }
};
