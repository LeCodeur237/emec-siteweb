<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('social_projects', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('slug')->unique();
            $table->text('short_description')->nullable();
            $table->longText('description')->nullable();
            $table->string('image')->nullable();
            $table->decimal('goal_amount', 15, 2)->nullable();
            $table->decimal('raised_amount', 15, 2)->default(0);
            $table->unsignedInteger('beneficiaries_count')->default(0);
            $table->date('deadline')->nullable()->index();
            $table->string('status', 30)->default('draft')->index();
            $table->boolean('featured')->default(false)->index();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('social_projects');
    }
};
