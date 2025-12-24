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
        Schema::create('clasif_cc_has_fincas', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('Clasif_CC_id')->nullable();
            $table->foreign('Clasif_CC_id')->references('id')->on('clasificacion_centro_costos')->onDelete('set null');
            $table->unsignedBigInteger('finca_id')->nullable();
            $table->foreign('finca_id')->references('id')->on('fincas')->onDelete('set null');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('clasif_cc_has_fincas');
    }
};
