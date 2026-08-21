<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('donation_campaigns', function (Blueprint $table) {
            $table->id();
            $table->foreignId('social_project_id')->nullable()->constrained()->nullOnDelete();
            $table->string('title');
            $table->text('description')->nullable();
            $table->decimal('goal_amount', 15, 2);
            $table->boolean('active')->default(true)->index();
            $table->date('start_date')->nullable()->index();
            $table->date('end_date')->nullable()->index();
            $table->timestamps();

            $table->index('social_project_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('donation_campaigns');
    }
};
