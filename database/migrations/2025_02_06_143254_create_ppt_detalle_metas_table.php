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
        Schema::create('ppt_detalle_metas', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('ppt_id')->nullable();
            $table->foreign('ppt_id')->references('id')->on('ppt_generals');

            $table->unsignedBigInteger('finca_id')->nullable();
            $table->foreign('finca_id')->references('id')->on('fincas');

            $table->double('hect_mensual')->nullable();
            $table->double('rend_bultoxhect')->nullable();
            $table->timestamps();
            
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ppt_detalle_metas');
    }
};
