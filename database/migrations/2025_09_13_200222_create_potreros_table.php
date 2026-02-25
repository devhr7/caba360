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
        Schema::create('potreros', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('finca_id')->nullable();
            $table->foreign('finca_id')->references('id')->on('fincas')->onDelete('set null');
            $table->string('nombre')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('potreros');
    }
};
