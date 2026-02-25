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
        Schema::create('cumplido_aplicacions', function (Blueprint $table) {
            $table->id();
            $table->string('slug')->unique();
            $table->string('codigo');
            $table->date('fecha');
            $table->dateTime('HoraInicio')->nullable();
            $table->dateTime('HoraFinal')->nullable();
            $table->unsignedBigInteger('distrito_id')->nullable();
            $table->unsignedBigInteger('finca_id')->nullable();
            $table->unsignedBigInteger('lote_id')->nullable();
            $table->unsignedBigInteger('reg_lote_id')->nullable();
            $table->foreign('distrito_id')->references('id')->on('distritos');
            $table->foreign('finca_id')->references('id')->on('fincas');
            $table->foreign('lote_id')->references('id')->on('lotes');
            $table->foreign('reg_lote_id')->references('id')->on('registro_lotes');
            $table->double('HectLote')->nullable();
            $table->unsignedBigInteger('RecordVisita_id')->nullable();

            $table->unsignedBigInteger('TipoAplicacion_id')->nullable();
            $table->foreign('TipoAplicacion_id')->references('id')->on('labors');

            $table->integer('CodRecordVisita')->nullable();
            $table->integer('CodSalidaAlmacen')->nullable();


            $table->unsignedBigInteger('ResponsableAplicacion_id')->nullable();
            $table->foreign('ResponsableAplicacion_id')->references('id')->on('contratistas');


            // Condiciones Ambientales
            $table->boolean('RiesgoLluvia')->nullable();
            $table->boolean('Brisa')->nullable();
            $table->boolean('HumedadLote')->nullable();

            // Condiciones Equipo
            $table->string('Velocidad')->nullable();
            $table->boolean('Seguridad')->nullable();
            $table->boolean('EnpaquesEntregados')->nullable();

            //Observaciones
            $table->string('Observaciones')->nullable();

            $table->unsignedBigInteger('Coordinador_id')->nullable();
            $table->foreign('Coordinador_id')->references('id')->on('empleados');

            $table->unsignedBigInteger('JefeCampo_id')->nullable();
            $table->foreign('JefeCampo_id')->references('id')->on('empleados');

            $table->boolean('verificado')->nullable();
            $table->string('factura')->nullable();
            $table->date('fecha_cierre')->nullable();



            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cumplido_aplicacions');
    }
};
