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
        Schema::create('cumplido_maquinarias', function (Blueprint $table) {
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


            // Interno o Externo
            $table->boolean('Externo')->nullable();
            $table->string('Finca')->nullable();
            $table->string('Lote')->nullable();


            //Maquina
            $table->unsignedBigInteger('maquina_id')->nullable();
            $table->foreign('maquina_id')->references('id')->on('maquinas');

            //Labor
            $table->unsignedBigInteger('tipolabor_id')->nullable();
            $table->foreign('tipolabor_id')->references('id')->on('tipo_labors');

            //Labor
            $table->unsignedBigInteger('labor_id')->nullable();
            $table->foreign('labor_id')->references('id')->on('labors');



            // Operario
            $table->unsignedBigInteger('operario_id')->nullable();
            $table->foreign('operario_id')->references('id')->on('empleados');

            $table->double('HorometroInicial')->nullable();
            $table->double('HorometroFinal')->nullable();
            $table->double('HorometroTotal')->nullable();
            $table->double('GalCombustible')->nullable();

            $table->double('cant')->nullable();
            $table->double('valor_unit')->nullable();
            $table->double('valor_total')->nullable();
            $table->string('garantia')->nullable();

            $table->string('Observaciones')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cumplido_maquinarias');
    }
};
