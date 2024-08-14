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
            $table->foreignId('sender_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('reciever_id')->constrained('users')->onDelete('cascade');
            $table->string('paid_amount')->nullable()->default(0);
            $table->string('recieved_amount')->nullable()->default(0);
            $table->string('balance_amount')->nullable()->default(0);
            $table->string('payment_method');
            $table->string('cash')->nullable()->default(0);
            $table->string('upi')->nullable()->default(0);
            $table->string('proof')->nullable();
            $table->string('status')->default('pending');
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
