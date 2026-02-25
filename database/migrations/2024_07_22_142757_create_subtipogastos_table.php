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
        Schema::create('subtipogastos', function (Blueprint $table) {
            $table->id();
            $table->string('slug')->unique();
            $table->string('codigo1');
            $table->string('codigo')->unique();
            $table->unsignedBigInteger('gasto_id');
            $table->foreign('gasto_id')->references('id')->on('gastos');

            $table->unsignedBigInteger('tipogasto_id');
            $table->foreign('tipogasto_id')->references('id')->on('tipo_gastos');

            $table->string('nombre');
            $table->double('valorunitario')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('subtipogastos');
    }
};
