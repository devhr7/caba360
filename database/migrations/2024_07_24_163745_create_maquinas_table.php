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
        Schema::create('maquinas', function (Blueprint $table) {
            $table->id();
            $table->string('slug')->unique();
            $table->string('CodMaq')->unique();
            $table->string('placa')->nullable();
            // Grupo Maquina
            $table->unsignedBigInteger('GrupoMaq_id')->nullable();
            $table->foreign('GrupoMaq_id')->references('id')->on('grupo_maquinas');


            $table->string('modelo')->nullable();
            $table->string('marca')->nullable();



            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('maquinas');
    }
};
