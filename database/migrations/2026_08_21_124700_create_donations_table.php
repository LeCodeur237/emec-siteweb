<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('donations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('donation_campaign_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('donation_method_id')->nullable()->constrained()->nullOnDelete();
            $table->string('donor_name')->nullable();
            $table->string('donor_email')->nullable();
            $table->string('donor_phone')->nullable();
            $table->decimal('amount', 15, 2);
            $table->string('currency', 3)->default('XAF');
            $table->string('transaction_reference')->nullable()->unique();
            $table->string('status', 30)->default('pending')->index();
            $table->boolean('anonymous')->default(false);
            $table->dateTime('paid_at')->nullable()->index();
            $table->timestamps();

            $table->index('donation_campaign_id');
            $table->index('donation_method_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('donations');
    }
};
