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
        Schema::create('producto_record_visitas', function (Blueprint $table) {
            $table->id();
            // Slug
            $table->string('slug')->unique();

            //Record Visita de Agronomo
            $table->unsignedBigInteger('RecordVisita_id')->nullable();
            $table->foreign('RecordVisita_id')->references('id')->on('record_visitas');

            //Grupo Producto Producto
            $table->unsignedBigInteger('GrupoMateriaPrima_id')->nullable();
            $table->foreign('GrupoMateriaPrima_id')->references('id')->on('grupo_materia_primas');

            //Producto
            $table->unsignedBigInteger('producto_id')->nullable();
            $table->foreign('producto_id')->references('id')->on('materia_primas');
            //Dosis
            $table->double('Dosis_por_Ha')->nullable();
            $table->double('cantidad_Total')->nullable();
            //Fecha Estimada Aplicacion
            $table->date('fecha_estimada_aplicacion')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('producto_record_visitas');
    }
};
