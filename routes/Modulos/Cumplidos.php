<?php

use App\Http\Controllers\Modulos\Cumplidos\CumplidoAplicacion\CumplidoAplicacionController;
use App\Http\Controllers\Modulos\Cumplidos\CumplidoAplicacion\CumplidoAplicacionProductoController;
use App\Http\Controllers\Modulos\Cumplidos\CumplidoLaborCampo\CumplidoLaborcampoController;
use App\Http\Controllers\Modulos\Cumplidos\CumplidoLaborCampo\CumplidoLaborcampodetallecuadrillaController;
use App\Http\Controllers\Modulos\Cumplidos\CumplidoLaborCampo\CumplidoLaborcampodetalleloteController;
use App\Http\Controllers\Modulos\Cumplidos\CumplidoMaquinaria\CumplidoMaquinariaController;
use App\Http\Controllers\Modulos\Cumplidos\CumplidoOrdenServicio\CumplidoOrdenServicioController;
use App\Http\Controllers\Modulos\Cumplidos\CumplidoOrdenServicio\CumplidoOrdenServicioDetalleController;;

use Illuminate\Support\Facades\Route;
use Illuminate\Foundation\Http\Middleware\HandlePrecognitiveRequests;


/** Rutas de Cumplidos de Maquinaria y Riego */
Route::middleware('auth')->group(function () {

    /**
     * Cumplido Maquinaria
     */

    Route::controller(CumplidoMaquinariaController::class)->group(function () {
        Route::get('/CumpMaquinaria/Explorar', 'index')->name('CumpMaquinaria.Explorar');  // Explorar
        Route::get('/CumpMaquinaria/Crear', 'create')->name('CumpMaquinaria.Crear'); // Vista Crear
        Route::post('/CumpMaquinaria/store', 'store')->name('CumpMaquinaria.store'); // Guardar
        Route::get('/CumpMaquinaria/{slug}/Edit', 'edit')->name('CumpMaquinaria.edit'); // vista Editar
        Route::put('/CumpMaquinaria/update/{slug}', 'update')->name('CumpMaquinaria.update'); // Actualizar
        Route::delete('/CumpMaquinaria/destroy/{slug}', 'destroy')->name('CumpMaquinaria.delete'); // Eliminar
        Route::get('/CumpMaquinaria/ExportPDFindiv/{slug}', 'ExportPDFindiv')->name('CumpMaquinaria.ExportPDFindiv'); // Eliminar
        Route::get('/CumpMaquinaria/Exportar/', 'Exportar')->name('CumpMaquinaria.Exportar'); //
        Route::post('/CumpMaquinaria/consultageneral/', 'consulta')->name('CumpMaquinaria.consulta'); // Vista Exportar Excel publico
        Route::get('/CumpMaquinaria/dataconsulta/', 'dataconsulta')->name('CumpMaquinaria.dataconsulta'); // Vista Exportar Excel publico
        Route::post('/CumpMaquinaria/exportXLSXpost/', 'exportXLSXpost')->name('CumpMaquinaria.exportXLSXpost'); // Vista Exportar Excel publico


        Route::get('/CumpMaquinaria/report', 'report')->name('CumpMaquinaria.report'); // Eliminar
        Route::post('/CumpMaquinaria/DataReport', 'DataReport')->name('CumpMaquinaria.DataReport'); // Eliminar
        Route::post('/CumpMaquinaria/verificar', 'verificar')->name('CumpMaquinaria.verificar'); // Eliminar
        Route::post('/CumpMaquinaria/exportXLSX', 'Exportarxlsx')->name('CumpMaquinaria.Exportarxlsx'); // Eliminar


    });

    /**
     * Cumplido de aplicacion
     */
    Route::controller(CumplidoAplicacionController::class)->group(function () {
        Route::get('/CumplidoAplicacion/Explorar', 'index')->name('CumplidoAplicacion.Explorar');  // Explorar
        Route::get('/CumplidoAplicacion/Crear', 'create')->name('CumplidoAplicacion.crear'); // Vista Crear
        Route::post('/CumplidoAplicacion/store', 'store')->name('CumplidoAplicacion.store'); // Guardar
        Route::get('/CumplidoAplicacion/{slug}/Edit', 'edit')->name('CumplidoAplicacion.edit'); // vista Editar
        Route::put('/CumplidoAplicacion/update/{CumplidoAplicacion}', 'update')->name('CumplidoAplicacion.update'); // Actualizar
        Route::delete('/CumplidoAplicacion/destroy/{CumplidoAplicacion}', 'destroy')->name('CumplidoAplicacion.delete'); // Eliminar
        Route::get('/CumplidoAplicacion/report', 'report')->name('CumplidoAplicacion.report'); // Eliminar
        Route::post('/CumplidoAplicacion/DataReport', 'DataReport')->name('CumplidoAplicacion.DataReport'); // Eliminar
        Route::post('/CumplidoAplicacion/verificar', 'verificar')->name('CumplidoAplicacion.verificar'); // Eliminar
        Route::post('/CumplidoAplicacion/exportXLSX', 'Exportarxlsx')->name('CumplidoAplicacion.Exportarxlsx'); // Eliminar

        //Route::get('/CumplidoAplicacion/reporte', 'reporte')->name('CumplidoAplicacion.reporte'); // Eliminar
        //Route::post('/CumplidoAplicacion/DataReporte', 'DataReporte')->name('CumplidoAplicacion.DataReporte'); // Eliminar
    });

    /**
     * Cumplido de aplicacion
     */
    Route::controller(CumplidoAplicacionProductoController::class)->group(function () {
        //Route::get('/CumplidoAplicacionProducto/Explorar', 'index')->name('CumplidoAplicacionProducto.Explorar');  // Explorar
        //Route::get('/CumplidoAplicacionProducto/Crear', 'create')->name('CumplidoAplicacionProducto.crear'); // Vista Crear
        Route::post('/CumplidoAplicacionProducto/{slug}/store', 'store')->name('CumplidoAplicacionProducto.store'); // Guardar
        Route::post('/CumplidoAplicacionLabor/{slug}/store', 'storeServicios')->name('CumplidoAplicacionProducto.storeServicios'); // Guardar
        //Route::get('/CumplidoAplicacionProducto/{CumplidoAplicacion}/Edit', 'edit')->name('CumplidoAplicacionProducto.edit'); // vista Editar
        //Route::put('/CumplidoAplicacionProducto/update/{CumplidoAplicacion}', 'update')->name('CumplidoAplicacionProducto.update'); // Actualizar
        Route::delete(
            '/CumplidoAplicacionProducto/destroy/{CumplidoAplicacionProducto}',
            'destroy'
        )->name('CumplidoAplicacionProducto.delete'); // Eliminar
        Route::delete('/CumplidoAplicacionLabor/destroy/{CumplidoAplicacionProducto}', 'destroy')->name('CumplidoAplicacionLabor.delete'); // Eliminar
        Route::post('/CumplidoAplicacionProducto/AddProductosPorRecord', 'AddProductosPorRecord')->name('CumplidoAplicacion.AddProductosPorRecord'); // Eliminar
    });


    /**
     * Cumplido Ordenes Servicio
     */
    Route::controller(CumplidoOrdenServicioController::class)->group(function () {
        Route::get('/CumplidoOrdenServicio/Explorar', 'index')->name('CumplidoOrdenServicio.Explorar');  // Explorar
        Route::get('/CumplidoOrdenServicio/Crear', 'create')->name('CumplidoOrdenServicio.crear'); // Vista Crear
        Route::post('/CumplidoOrdenServicio/store', 'store')->name('CumplidoOrdenServicio.store'); // Guardar
        Route::get('/CumplidoOrdenServicio/{slug}/Edit', 'edit')->name('CumplidoOrdenServicio.edit'); // vista Editar
        Route::put('/CumplidoOrdenServicio/update/{cumplidoOrdenServicio}', 'update')->name('CumplidoOrdenServicio.update'); // Actualizar
        Route::delete('/CumplidoOrdenServicio/destroy/{cumplidoOrdenServicio}', 'destroy')->name('CumplidoOrdenServicio.delete'); // Eliminar
        Route::post('/CumplidoOrdenServicio/verificar', 'verificar')->name('CumplidoOrdenServicio.verificar'); // Eliminar

        // Report
        Route::get('/CumplidoOrdenServicio/report', 'report')->name('CumplidoOrdenServicio.report'); // Vista Reporte
        Route::post('/CumplidoOrdenServicio/DataReport', 'DataReport')->name('CumplidoOrdenServicio.DataReport'); // Json
        Route::post('/CumplidoOrdenServicio/exportXLSX', 'Exportarxlsx')->name('CumplidoOrdenServicio.Exportarxlsx'); // Excel
    });

    Route::controller(CumplidoOrdenServicioDetalleController::class)->group(function () {
        Route::put('/CumplidoOrdenServicioDetalle/{cumplidoOrdenServicio}/store', 'store')->name('CumplidoOrdenServicioDetalle.store');  // Explorar
        Route::put('/CumplidoOrdenServicioDetalle/{cumplidoOrdenServicio}/update', 'update')->name('CumplidoOrdenServicioDetalle.update');  // Explorar
        Route::delete('/CumplidoOrdenServicioDetalle/{CumplidoOrdenServicioDetalle}', 'destroy')->name('CumplidoOrdenServicioDetalle.delete');  // Explorar
    });



    /**
     * Cumplido Labores Campo
     */
    Route::controller(CumplidoLaborcampoController::class)->group(function () {
        Route::get('/CumpLaborCampo/Explorar', 'index')->name('CumpLaborCampo.Explorar');  // Explorar
        //Route::post('/CumpLaborCampo/verificar', 'verificar')->name('CumpLaborCampo.verificar'); // Eliminar

        Route::get('/CumpLaborCampo/Crear', 'create')->name('CumpLaborCampo.crear'); // Vista Crear
        Route::post('/CumpLaborCampo/store', 'store')->name('CumpLaborCampo.store'); // Guardar
        Route::get('/CumpLaborCampo/{CumpLaborCampo}/Edit', 'edit')->name('CumpLaborCampo.edit'); // vista Editar
        Route::put('/CumpLaborCampo/update/{CumpLaborCampo}', 'update')->name('CumpLaborCampo.update'); // Actualizar
        Route::delete('/CumpLaborCampo/destroy/{CumpLaborCampo}', 'destroy')->name('CumpLaborCampo.delete'); // Eliminar

        Route::post('/CumpLaborCampo/verificar', 'verificar')->name('CumpLaborCampo.verificar'); // Eliminar

        // Report
        Route::get('/CumpLaborCampo/report', 'report')->name('CumpLaborCampo.report'); // Vista Reporte
        Route::post('/CumpLaborCampo/DataReport', 'DataReport')->name('CumpLaborCampo.DataReport'); // Json
        Route::post('/CumpLaborCampo/exportXLSX', 'Exportarxlsx')->name('CumpLaborCampo.Exportarxlsx'); // Excel    

    });

    Route::controller(CumplidoLaborcampodetallecuadrillaController::class)->group(function () {
        Route::post('/CumpLaborCampo/CumpLaborCampo_cuadrilla', 'store')->name('CumpLaborCampoCuadrilla.store');
        Route::put('/CumpLaborCampo/{reg}/CumpLaborCampo_cuadrilla', 'update')->name('CumpLaborCampoCuadrilla.update');
        Route::delete('/CumpLaborCampo/CumpLaborCampo_cuadrilla/{reg}', 'destroy')->name('CumpLaborCampoCuadrilla.destroy');
    });

    Route::controller(CumplidoLaborcampodetalleloteController::class)->group(function () {
        Route::post('/CumpLaborCampo/CumpLaborCampo_lote', 'store')->name('CumpLaborCampo_lote.store');
        Route::put('/CumpLaborCampo/{reg}/CumpLaborCampo_lote', 'update')->name('CumpLaborCampo_lote.update');
        Route::delete('/CumpLaborCampo/CumpLaborCampo_lote/{reg}', 'destroy')->name('CumpLaborCampo_lote.destroy');
    });
});
