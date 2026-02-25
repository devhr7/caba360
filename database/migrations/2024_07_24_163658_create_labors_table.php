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
        Schema::create('labors', function (Blueprint $table) {
            $table->id();
            $table->string('slug')->unique();
            $table->string('labor')->nullable();
            // Tipo Cumplido
            $table->unsignedBigInteger('TipoCumplido_id')->nullable();
            $table->foreign('TipoCumplido_id')->references('id')->on('tipo_cumplidos');
            // Tipo Labor
            $table->unsignedBigInteger('TipoLabor_id')->nullable();
            $table->foreign('TipoLabor_id')->references('id')->on('tipo_labors');

            $table->double('CostoHect')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('labors');
    }
};
