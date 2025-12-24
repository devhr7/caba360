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
        Schema::table('cump_orden_servicio_dets', function (Blueprint $table) {
            //
                    $table->unsignedBigInteger('potrero_id')->nullable()->after('id');
                    $table->foreign('potrero_id')->references('id')->on('potreros');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('cump_orden_servicio_dets', function (Blueprint $table) {
            //
            $table->dropForeign(['potrero_id']);
            $table->dropColumn('potrero_id');
        });
    }
};
