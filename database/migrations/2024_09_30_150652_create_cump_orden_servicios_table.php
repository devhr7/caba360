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
        Schema::create('cump_orden_servicios', function (Blueprint $table) {
            $table->id();
            $table->string('slug')->unique();
            $table->bigInteger("codigo")->unique();
            $table->date("fecha");

            $table->unsignedBigInteger('Responsable_id')->nullable();
            $table->foreign('Responsable_id')->references('id')->on('contratistas');

            $table->unsignedBigInteger('Autorizacion_id')->nullable();
            $table->foreign('Autorizacion_id')->references('id')->on('empleados');

            $table->string('factura')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cump_orden_servicios');
    }
};
