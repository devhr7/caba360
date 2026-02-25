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
        Schema::create('cumplido_aplicacion_productos', function (Blueprint $table) {
            $table->id();

            // Slug
            $table->string('slug')->unique();

            //CumplidoAplicacion
            $table->unsignedBigInteger('CumplidoAplicacion_id')->nullable();
            $table->foreign('CumplidoAplicacion_id')->references('id')->on('cumplido_aplicacions');

            //Grupo Producto Producto
            $table->unsignedBigInteger('GrupoMateriaPrima_id')->nullable();
            $table->foreign('GrupoMateriaPrima_id')->references('id')->on('grupo_materia_primas');

            //Producto
            $table->unsignedBigInteger('producto_id')->nullable();
            $table->foreign('producto_id')->references('id')->on('materia_primas');

            $table->unsignedBigInteger('UnidadMedida_id')->nullable();
            $table->foreign('UnidadMedida_id')->references('id')->on('unidad_medidas');

            //Producto
            $table->unsignedBigInteger('labor_id')->nullable();
            $table->foreign('labor_id')->references('id')->on('labors');


            //Dosis
            $table->double('Dosis_por_Ha')->nullable();
            $table->double('cantidad_Total')->nullable();

            //Dosis
            $table->double('PrecioUnit')->nullable();
            $table->double('PrecioTotal')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cumplido_aplicacion_productos');
    }
};
