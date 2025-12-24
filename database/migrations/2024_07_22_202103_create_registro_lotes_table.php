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
        Schema::create('registro_lotes', function (Blueprint $table) {
            $table->id();
            $table->string('slug')->unique();
            $table->unsignedBigInteger('status_id')->nullable();
            $table->foreign('status_id')->references('id')->on('statuses');
            $table->string('Codigo')->unique();
            $table->string('NombreLote')->nullable();
            $table->double('Hect')->nullable();
            $table->date('FechaInicio')->nullable();
            $table->date('FechaSiembra')->nullable();
            $table->date('FechaGerminacion')->nullable();
            $table->date('FechaRecoleccion')->nullable();
            $table->date('FechaVenta')->nullable();

            $table->double('TotalIngresos')->nullable();
            $table->double('TotalCostos')->nullable();


            // TipoCultivo
            $table->unsignedBigInteger('TipoCultivo_id')->nullable();
            $table->foreign('TipoCultivo_id')->references('id')->on('tipo_cultivos');

            // TipoVariedad
            $table->unsignedBigInteger('TipoVariedad_id')->nullable();
            $table->foreign('TipoVariedad_id')->references('id')->on('tipo_variedads');

            // Lote
            $table->unsignedBigInteger('lote_id')->nullable();
            $table->foreign('lote_id')->references('id')->on('lotes');
            // Finca
            $table->unsignedBigInteger('finca_id')->nullable();
            $table->foreign('finca_id')->references('id')->on('fincas');
            // Distrito
            $table->unsignedBigInteger('distrito_id')->nullable();
            $table->foreign('distrito_id')->references('id')->on('distritos');




            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('registro_lotes');
    }
};
