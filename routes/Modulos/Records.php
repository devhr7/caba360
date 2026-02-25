<?php

use App\Http\Controllers\Modulos\Records\RecordVisita\ProductoRecordVisitasController;
use App\Http\Controllers\Modulos\Records\RecordVisita\RecordVisitaController;
use Illuminate\Support\Facades\Route;


/** Rutas de Configuracion los parametros */
Route::middleware('auth')->group(function () {

    /**
     * Record Visita
     */
    Route::controller(RecordVisitaController::class)->group(function () {
        Route::get('/RecordVisita/Explorar', 'index')->name('RecordVisita.Explorar');  // Explorar
        Route::get('/RecordVisita/Crear', 'create')->name('RecordVisita.crear'); // Vista Crear
        Route::post('/RecordVisita/store', 'store')->name('RecordVisita.store'); // Guardar
        Route::get('/RecordVisita/{slug}/Edit', 'edit')->name('RecordVisita.edit'); // vista Editar
        Route::put('/RecordVisita/update/{RecordVisita}', 'update')->name('RecordVisita.update'); // Actualizar
        Route::delete('/RecordVisita/destroy/{slug}', 'destroy')->name('RecordVisita.delete'); // Eliminar
        // Endpoint para obtener la información de un record
        Route::post('/RecordVisita/getrecordinfo', 'getrecordinfo')->name('RecordVisita.getrecordinfo');
        
    });

    /**
     * Productos de la Visita
     *
     */

    Route::controller(ProductoRecordVisitasController::class)->group(function () {
        Route::get('/RecordVisita/{slug}/Producto/Explorar', 'index')->name('RecordVisita.Producto.Explorar');  // Explorar
        //Route::get('/RecordVisita/{slug}/Producto/Crear', 'create')->name('RecordVisita.Producto.Crear'); // Vista Crear
        Route::post('/RecordVisita/{slug}/Producto/store', 'store')->name('RecordVisita.Producto.store'); // Guardar
        //Route::get('/RecordVisita/{slug}/Producto/{slug2}/Edit', 'edit')->name('RecordVisita.Producto.edit'); // vista Editar
        //Route::put('/RecordVisita/{slug}/Producto/update/{slug2}', 'update')->name('RecordVisita.Producto.update'); // Actualizar
        Route::delete('/RecordVisita/{productoRecordVisitas}/Producto/destroy', 'destroy')->name('RecordVisita.Producto.delete'); // Eliminar
    });
});
