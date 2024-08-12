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
        Schema::create('farmersupplies', function (Blueprint $table) {
            $table->id();
            $table->foreignId('supply_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('reciever_id')->constrained('users')->onDelete('cascade');
            $table->string('breed');
            $table->string('morning')->nullable()->default(0);
            $table->string('evening')->nullable()->default(0);
            $table->string('total')->nullable()->default(0);
            $table->string('price')->nullable()->default(0);
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('farmersupplies');
    }
};
