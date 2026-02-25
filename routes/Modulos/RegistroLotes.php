<?php

use App\Http\Controllers\Modulos\RegistroLote\RegistroLotesController;
use Illuminate\Support\Facades\Route;


/** Rutas de Configuracion los parametros */
Route::middleware('auth')->group(function () {

    /**
     * Lote
     */
    Route::controller(RegistroLotesController::class)->group(function () {
        Route::get('/RegLote/Explorar', 'index')->name('RegLote.Explorar');  // Explorar
        Route::get('/RegLote/Crear', 'create')->name('RegLote.Crear'); // Vista Crear
        Route::post('/RegLote/store', 'store')->name('RegLote.store'); // Guardar
        Route::post('/RegLote/store_auto', 'store_auto')->name('RegLote.store_auto'); // Guardar
        Route::get('/RegLote/{slug}/Edit', 'edit')->name('RegLote.edit'); // vista Editar
        Route::put('/RegLote/update/{slug}', 'update')->name('RegLote.update'); // Actualizar
        Route::delete('/RegLote/destroy/{slug}', 'destroy')->name('RegLote.delete'); // Eliminar

        Route::get('/RegLote/{slug}/RegistroLote', 'RegistroLote')->name('RegLote.RegistroLote');
        Route::get('/RegLote/generarPDF/{slug}', 'exportPDF')->name('RegLote.GenerarPDF'); // Eliminar
        Route::get('/RegLote/generarExcel/{slug}', 'exportXLSX')->name('RegLote.exportXLSX'); // Eliminar
        Route::post('/RegLote/get_data_RegLote', 'get_data_RegLote')->name('RegLote.get_data_RegLote'); // Data Lote
        Route::post('/RegLote/getOptionsRegLoteActivo', 'getOptionsRegLoteActivo')->name('RegLote.getOptionsRegLoteActivo'); // Data Lote
        Route::post('/RegLote/getOptionsRegLote', 'getOptionsRegLote')->name('RegLote.getOptionsRegLote'); // Data Lote


        Route::post('/RegLote/getRegLoteActivo', 'getRegLoteActivo')->name('RegLote.getRegLoteActivo'); // Data Lote
        Route::post('/RegLote/getHectLote', 'getHectLote')->name('RegLote.getHectLote'); // Data Lote

        /** Consulta  */
        Route::get('/RegLote/consulta/Siembra', 'dataconsultaSiembra')->name('RegLote.dataconsultaSiembra'); // Data Lote
        Route::get('/RegLote/consulta/dataconsultaActivoSinSiembra', 'dataconsultaActivoSinSiembra')->name('RegLote.dataconsultaActivoSinSiembra'); // Data Lote
        Route::get('/RegLote/consulta/dataconsultaActivosConSiembraSinRecoleccion', 'dataconsultaActivosConSiembraSinRecoleccion')->name('RegLote.dataconsultaActivosConSiembraSinRecoleccion'); // Data Lote
        Route::get('/RegLote/consulta/DataChartEstadosLotes', 'DataChartEstadosLotes')->name('RegLote.DataChartEstadosLotes'); // Data Lote
        Route::get('/RegLote/consulta/DataChartStatusLoteCrecimientoCuidado', 'DataChartStatusLoteCrecimientoCuidado')->name('RegLote.DataChartStatusLoteCrecimientoCuidado'); // Data Lote


    });
});
