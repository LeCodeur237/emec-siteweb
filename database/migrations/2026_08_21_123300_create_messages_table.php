<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('messages', function (Blueprint $table) {
            $table->id();
            $table->foreignId('preacher_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('message_category_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('message_series_id')->nullable()->constrained()->nullOnDelete();
            $table->string('title');
            $table->string('slug')->unique();
            $table->text('excerpt')->nullable();
            $table->longText('content')->nullable();
            $table->date('preached_at')->nullable()->index();
            $table->string('duration')->nullable();
            $table->string('youtube_video_id')->nullable();
            $table->string('youtube_url')->nullable();
            $table->string('audio_url')->nullable();
            $table->string('pdf_url')->nullable();
            $table->string('thumbnail')->nullable();
            $table->boolean('featured')->default(false)->index();
            $table->string('status', 30)->default('draft')->index();
            $table->unsignedInteger('views')->default(0);
            $table->timestamps();

            $table->index('preacher_id');
            $table->index('message_category_id');
            $table->index('message_series_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('messages');
    }
};
