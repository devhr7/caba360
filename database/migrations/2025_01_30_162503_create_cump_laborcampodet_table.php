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
        Schema::create('cump_laborcampodetlotes', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('cump_laborcampo_id');
            $table->foreign('cump_laborcampo_id')->references('id')->on('cump_laborcampos');

            $table->unsignedBigInteger('lote_id')->nullable();
            $table->foreign('lote_id')->references('id')->on('lotes');

            $table->unsignedBigInteger('reg_lote_id')->nullable();
            $table->foreign('reg_lote_id')->references('id')->on('registro_lotes');

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
        Schema::dropIfExists('cump_laborcampodetlotes');
    }
};
