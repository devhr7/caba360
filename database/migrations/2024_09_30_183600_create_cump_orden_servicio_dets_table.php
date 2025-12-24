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
        Schema::create('cump_orden_servicio_dets', function (Blueprint $table) {
            $table->id();
            // Relacion cumplido Orden Servicio
            $table->unsignedBigInteger('CumplidoOrdenServicio_id');
            $table->foreign('CumplidoOrdenServicio_id')->references('id')->on('cump_orden_servicios');

            $table->unsignedBigInteger('TipoCentroCosto_id');
            $table->foreign('TipoCentroCosto_id')->references('id')->on('clasificacion_centro_costos');

            $table->boolean('Interno')->default(true);

            $table->unsignedBigInteger('finca_id')->nullable();
            $table->foreign('finca_id')->references('id')->on('fincas');

            $table->string('DestinoServicio')->nullable(); // Externo

            $table->unsignedBigInteger('Lote_id')->nullable();
            $table->foreign('Lote_id')->references('id')->on('lotes');

            $table->unsignedBigInteger('RegLote_id')->nullable();
            $table->foreign('RegLote_id')->references('id')->on('registro_lotes');

            $table->unsignedBigInteger('Labor_id')->nullable();
            $table->foreign('Labor_id')->references('id')->on('labors');

            $table->string("DetalleLabor")->nullable();

            $table->double("Cantidad")->nullable();

            $table->unsignedBigInteger('UnidadMedida_id')->nullable();
            $table->foreign('UnidadMedida_id')->references('id')->on('unidad_medidas');

            $table->double("ValorUnit")->nullable();
            $table->double("Total")->nullable();
            $table->string("Observaciones")->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cump_orden_servicio_dets');
    }
};
