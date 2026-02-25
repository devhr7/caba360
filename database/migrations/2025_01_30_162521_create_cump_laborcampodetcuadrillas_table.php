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
        Schema::create('cump_laborcampodetcuadrillas', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('cump_laborcampo_id');
            $table->foreign('cump_laborcampo_id')->references('id')->on('cump_laborcampos');

            $table->unsignedBigInteger('personal_id');
            $table->foreign('personal_id')->references('id')->on('empleados');

            $table->double('cantidad')->nullable();
            $table->string('observaciones')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cumplido_laborcampodetallecuadrillas');
    }
};
