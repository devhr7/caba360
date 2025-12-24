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
        Schema::create('materia_primas', function (Blueprint $table) {
            $table->id();
            // Slug
            $table->string('slug')->unique();
            // Grupo MPrima
            $table->unsignedBigInteger('GrupoMPrima_id')->nullable();
            $table->foreign('GrupoMPrima_id')->references('id')->on('grupo_materia_primas');
            // MPrima
            $table->string('MateriaPrima')->nullable();
            
            $table->unsignedBigInteger('UnidadMedida_id')->nullable();
            $table->foreign('UnidadMedida_id')->references('id')->on('unidad_medidas');
            $table->double('PrecioUnit')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('materia_primas');
    }
};
