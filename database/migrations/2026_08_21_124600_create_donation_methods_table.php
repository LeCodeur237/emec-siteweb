<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('donation_methods', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('type', 50)->index();
            $table->string('provider')->nullable()->index();
            $table->string('account_name')->nullable();
            $table->string('account_number')->nullable();
            $table->text('instructions')->nullable();
            $table->boolean('active')->default(true)->index();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('donation_methods');
    }
};
