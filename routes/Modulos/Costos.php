<?php


use App\Http\Controllers\Modulos\Costos\Gastos\ConsolidadogastoController;
use App\Http\Controllers\Modulos\Costos\Ingresos\ConsolidadoingresoController;
use App\Http\Controllers\Modulos\Costos\Parametros\SubtipogastoController;
use App\Http\Controllers\Modulos\Costos\Presupuesto\PptDetalleCostoController;
use App\Http\Controllers\Modulos\Costos\Presupuesto\PptDetalleMetaController;
use App\Http\Controllers\Modulos\Costos\Presupuesto\PptGeneralController;
use App\Http\Controllers\Modulos\Costos\Report\ReporteCostosController;
use App\Http\Controllers\Modulos\Records\RecordVisita\ProductoRecordVisitasController;
use App\Http\Controllers\Modulos\Records\RecordVisita\RecordVisitaController;
use App\Models\ppt_detalle_costo;
use Illuminate\Support\Facades\Route;


/** Rutas de Configuracion los parametros */
Route::middleware('auth')->group(function () {

    /**
     * Gastos
     */

    Route::controller(ConsolidadogastoController::class)->group(function () {
        Route::get('gastos/importar', 'importar_movimientos')->name('costos.gastos.importar');  // Explorar
        Route::post('gastos/importar/validar', 'importar_movimientos_validacion')->name('costos.gastos.importar.validar');  // Validar
        Route::post('gastos/importar/subir', 'importar_movimientos_subir')->name('costos.gastos.importar.subir');  // Subir
        Route::get('gastos/finca/{id}', 'obtenerNombreFinca')->name('costos.gastos.finca');  // Obtener nombre de la finca
    });

    Route::controller(ConsolidadoingresoController::class)->group(function () {
        Route::get('ingresos/importar', 'importar_movimientos')->name('costos.ingresos.importar');  // Explorar
        Route::post('ingresos/importar/validar', 'importar_movimientos_validacion')->name('costos.ingresos.importar.validar');  // Validar
        Route::post('ingresos/importar/subir', 'importar_movimientos_subir')->name('costos.ingresos.importar.subir');  // Subir
    });

    /**
     * Reporte
     */

    Route::controller(ReporteCostosController::class)->group(function () {
        Route::get('Reporte/ReportGastosDetallado', 'GastosPorLoteDetallado')->name('costos.report.gastosporlotedetallado');
        Route::post('Reporte/ReportGastosDetallado/data', 'GastosPorLoteDetalladoData')->name('costos.report.GastosPorLoteDetalladoData');
        Route::post('Reporte/ReportGastosDetallado/export', 'exportGastosPorLoteDetalladoPDF')->name('costos.report.GastosPorLoteDetalladoExport');

        Route::get('Reporte/ReportGastosPorLote', 'GastosPorLote')->name('costos.report.GastosPorLote');
        Route::post('Reporte/ReportGastos/data', 'GastosPorLoteData')->name('costos.report.GastosPorLoteData');
        Route::get('Reporte/ReportGastos/{RegLote}/ppt', 'CostoxLote_PPT')->name('costos.report.CostoxLote_PPT');
    });

    /**
     * Records
     */

    Route::controller(PptGeneralController::class)->group(function () {
        Route::get('Presupuesto/', 'index')->name('costos.ppt.index');
        Route::get('Presupuesto/create', 'create')->name('costos.ppt.create');
        Route::post('Presupuesto/store', 'store')->name('costos.ppt.store');
        Route::get('Presupuesto/edit/{id}', 'edit')->name('costos.ppt.edit');
        Route::put('Presupuesto/update/{id}', 'update')->name('costos.ppt.update');
        Route::delete('Presupuesto/destroy/{id}', 'destroy')->name('costos.ppt.destroy');
    });

    Route::controller(PptDetalleCostoController::class)->group(function () {
        Route::get('Presupuesto/costo/', 'index')->name('costos.ppt.costo.index');
        Route::get('Presupuesto/costo/create', 'create')->name('costos.ppt.costo.create');
        Route::post('Presupuesto/costo/store', 'store')->name('costos.ppt.costo.store');
        Route::get('Presupuesto/costo/edit/{id}', 'edit')->name('costos.ppt.costo.edit');
        Route::put('Presupuesto/costo/update/{id}', 'update')->name('costos.ppt.costo.update');
        Route::delete('Presupuesto/costo/destroy/{id}', 'destroy')->name('costos.ppt.costo.destroy');
    });

    Route::controller(PptDetalleMetaController::class)->group(function () {
        Route::get('Presupuesto/meta/', 'index')->name('costos.ppt.meta.index');
        Route::get('Presupuesto/meta/create', 'create')->name('costos.ppt.meta.create');
        Route::post('Presupuesto/meta/store', 'store')->name('costos.ppt.meta.store');
        Route::get('Presupuesto/meta/edit/{id}', 'edit')->name('costos.ppt.meta.edit');
        Route::put('Presupuesto/meta/update/{id}', 'update')->name('costos.ppt.meta.update');
        Route::delete('Presupuesto/meta/destroy/{id}', 'destroy')->name('costos.ppt.meta.destroy');
    });

    Route::controller(SubtipogastoController::class)->group(function () {
        Route::get('Param/subtipogasto', 'index')->name('costos.param.subtipogasto.index');
        Route::get('Param/subtipogasto/create', 'create')->name('costos.param.subtipogasto.create');
        Route::post('Param/subtipogasto/store', 'store')->name('costos.param.subtipogasto.store');
        Route::get('Param/subtipogasto/edit/{id}', 'edit')->name('costos.param.subtipogasto.edit');
        Route::put('Param/subtipogasto/update/{id}', 'update')->name('costos.param.subtipogasto.update');
        Route::delete('Param/subtipogasto/destroy/{id}', 'destroy')->name('costos.param.subtipogasto.destroy');
    });
});
