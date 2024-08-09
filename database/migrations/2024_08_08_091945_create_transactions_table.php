<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('farmer_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreignId('milkman_id')->references('id')->on('users')->onDelete('cascade');
            $table->string('amount')->nullable();
            $table->string('status')->nullable();
            $table->string('payout')->nullable();
            $table->timestamp('scheduled_for')->nullable();
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transactions');
    }
};
