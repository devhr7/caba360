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
        Schema::create('record_visitas', function (Blueprint $table) {
            $table->id();
            $table->string('slug')->unique();
            $table->string('Codigo')->unique();
            $table->date('fecha')->nullable();

            // Distrito
            $table->unsignedBigInteger('distrito_id')->nullable();
            $table->foreign('distrito_id')->references('id')->on('distritos');
            // Finca
            $table->unsignedBigInteger('finca_id')->nullable();
            $table->foreign('finca_id')->references('id')->on('fincas');
            // lote
            $table->unsignedBigInteger('lote_id')->nullable();
            $table->foreign('lote_id')->references('id')->on('lotes');
            //Registro Lote
            $table->unsignedBigInteger('RegLote_id')->nullable();
            $table->foreign('RegLote_id')->references('id')->on('registro_lotes');
            // Agricultor Encargado
            $table->unsignedBigInteger('AgricultorEncargado_id')->nullable();
            $table->foreign('AgricultorEncargado_id')->references('id')->on('empleados');
            // Ingeniero Agronomo
            $table->unsignedBigInteger('IngenieroAgronomo_id')->nullable();
            $table->foreign('IngenieroAgronomo_id')->references('id')->on('empleados');

            $table->string('dias_emergencia',255)->nullable();
            $table->double('hect_aplicacion')->nullable();
            $table->string('diagnostico',255)->nullable();
            $table->string('observaciones',255)->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('record_visitas');
    }
};
