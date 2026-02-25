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
        Schema::create('cump_laborcampos', function (Blueprint $table) {
            $table->id();
            $table->string('codigo');
            $table->date('fecha');
            $table->unsignedBigInteger('finca_id')->nullable();
            $table->foreign('finca_id')->references('id')->on('fincas');
            $table->unsignedBigInteger('labor_id')->nullable();
            $table->foreign('labor_id')->references('id')->on('labors');
            $table->unsignedBigInteger('unidadmedida_id')->nullable();
            $table->foreign('unidadmedida_id')->references('id')->on('unidad_medidas');
            $table->double('cantidadtotal')->nullable();
            $table->date('fecha_cierre')->nullable();
            $table->boolean('verificado')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cumplido_laborcampos');
    }
};
