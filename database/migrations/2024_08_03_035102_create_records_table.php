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
        Schema::create('records', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreignId('breed_id')->references('id')->on('breeds')->onDelete('cascade');
            $table->integer('morning');
            $table->integer('evening');
            $table->integer('price');
            $table->text('notes')->nullable()->default('');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('records');
    }
};
