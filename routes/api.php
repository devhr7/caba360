<?php

use App\Http\Controllers\Modulos\Costos\Report\ReporteCostosController;
use App\Http\Controllers\Modulos\Cumplidos\CumplidoAplicacion\Api\CumplidoAplicacionController;
use App\Http\Controllers\Modulos\Cumplidos\CumplidoLaborCampo\CumplidoLaborcampoController;
use App\Http\Controllers\Modulos\Cumplidos\CumplidoMaquinaria\Api\CumplidoMaquinariaController;
use App\Http\Controllers\Modulos\Cumplidos\CumplidoOrdenServicio\Api\ApiCumplidoOrdenServicioController;
use App\Http\Controllers\Modulos\Records\RecordVisita\RecordVisitaController;
use App\Http\Controllers\Modulos\RegistroLote\Api\RegistroLotesController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

// frLAcWGEwy6SdqiqUTfFeVShBW2cQ0FMNI74cvJXf4bfc06b
Route::prefix('api')->group(function () {
    Route::controller(CumplidoAplicacionController::class)->group(function () {
        Route::get('CumplidoAplicacion/getAllCumplidosAplicacion', 'getAllCumplidosAplicacion')->name('api.cumplidoaplicacion.getAllCumplidosAplicacion')->middleware('auth:sanctum');  // Explorar
        Route::get('CumplidoAplicacion/getAllCumplidosAplicacionDetalle', 'getAllCumplidosAplicacionDetalle')->name('api.cumplidoaplicacion.getAllCumplidosAplicacionDetalle')->middleware('auth:sanctum');  // Explorar
    });

    Route::controller(CumplidoMaquinariaController::class)->group(function () {
        Route::get('CumplidoMaquinaria/getAllCumplidosMaquinaria', 'getAllCumplidosMaquinaria')->name('api.cumplidomaquinaria.getAllCumplidosMaquinaria')->middleware('auth:sanctum');  // Explorar
    });

    Route::controller(ApiCumplidoOrdenServicioController::class)->group(function () {
        Route::get('ordenservicio/getAllOrdenesServicio', 'getAllOrdenesServicio')->name('api.ordenservicio.getAllOrdenesServicio')->middleware('auth:sanctum');  // Explorar

    });

    Route::controller(ApiCumplidoOrdenServicioController::class)->group(function () {
        Route::get('getAllOrdServYCumpApli', 'getAllOrdServYCumpApli')->name('api.getAllOrdServYCumpApli')->middleware('auth:sanctum');  // Explorar

    });

    Route::controller(RegistroLotesController::class)->group(function () {
        Route::get('RegistroLotes/LotesActivos', 'LotesActivos')->name('api.registrolotes.LotesActivos')->middleware('auth:sanctum');  // Explorar
    });

    Route::controller(ReporteCostosController::class)->group(function () {
        Route::get('ReporteCumplidos/getCumplidosapi', 'getCumplidosapi')->name('api.reportecumplidos.getCumplidosapi')->middleware('auth:sanctum');  // Explorar
    });

    Route::controller(CumplidoLaborcampoController::class)->group(function () {
        Route::get('CumplidoLaborCampo/getCumplidosLaborCampo', 'getCumplidosLaborCampo')->name('api.cumplidolaborcampo.getCumplidosLaborCampo')->middleware('auth:sanctum');  // Explorar
    });


    Route::controller(RecordVisitaController::class)->group(function () {
        Route::get('RecordVisita/getrecords', 'getrecords')->name('api.RecordVisita.getrecords')->middleware('auth:sanctum');  // Explorar
    });
});
