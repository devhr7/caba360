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
        Schema::create('consolidadogastos', function (Blueprint $table) {
            $table->id();
            $table->date('fecha')->nullable();
            $table->unsignedBigInteger('reglote_id')->nullable();
            $table->foreign('reglote_id')->references('id')->on('registro_lotes');
            $table->string('comprobante')->nullable();
            $table->double('cantidad')->nullable();
            $table->double('preciounitario')->nullable();
            $table->double('total')->nullable();
            
            $table->unsignedBigInteger('gasto_id')->nullable();
            $table->foreign('gasto_id')->references('id')->on('gastos');
            $table->unsignedBigInteger('tipogasto_id')->nullable();
            $table->foreign('tipogasto_id')->references('id')->on('tipo_gastos');
            $table->unsignedBigInteger('subtipogasto_id')->nullable();
            $table->foreign('subtipogasto_id')->references('id')->on('subtipogastos');
            
            $table->string('observaciones')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('consolidadogastos');
    }
};
