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
        Schema::create('withdraw_supplies', function (Blueprint $table) {
            $table->id();
            $table->foreignId('farmer_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreignId('milkman_id')->references('id')->on('users')->onDelete('cascade');
            $table->string('date');
            $table->string('withdraw');
            $table->string('description');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('withdraw_supplies');
    }
};
