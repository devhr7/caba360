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
        Schema::create('reportsiembradetalles', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('reporte_siembra_id');
            $table->foreign('reporte_siembra_id')->references('id')->on('reportsiembras');
            

            
            $table->unsignedBigInteger('finca_id');
            $table->foreign('finca_id')->references('id')->on('fincas');


            $table->unsignedBigInteger('lote_id');
            $table->foreign('lote_id')->references('id')->on('lotes');

            $table->unsignedBigInteger('reg_lote_id');
            $table->foreign('reg_lote_id')->references('id')->on('registro_lotes');

            $table->unsignedBigInteger('tipocultivo_id');
            $table->foreign('tipocultivo_id')->references('id')->on('tipo_cultivos');

            $table->unsignedBigInteger('reportsiembravariedad_id');
            $table->foreign('reportsiembravariedad_id')->references('id')->on('reportsiembravariedads');

            $table->double('hectareas');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reportsiembradetalles');
    }
};
