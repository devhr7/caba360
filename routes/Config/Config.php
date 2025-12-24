<?php

use App\Http\Controllers\Config\Contratista\ContratistaController;
use App\Http\Controllers\Config\Rol\RoleController;

use App\Http\Controllers\Config\Distrito\DistritoController;
use App\Http\Controllers\Config\Finca\FincaController;
use App\Http\Controllers\Config\GrupoMaquinaria\GrupoMaquinaController;
use App\Http\Controllers\Config\GrupoMateriaPrima\GrupoMateriaPrimaController;
use App\Http\Controllers\Config\Labor\LaborController;
use App\Http\Controllers\Config\Lote\LoteController;
use App\Http\Controllers\Config\Maquina\MaquinaController;
use App\Http\Controllers\Config\TipoCultivo\TipoCultivoController;
use App\Http\Controllers\Config\TipoCumplido\TipoCumplidoController;
use App\Http\Controllers\Config\TipoLabor\TipoLaborController;
use App\Http\Controllers\Config\TipoVariedad\TipoVariedadController;
use App\Http\Controllers\Config\MateriaPrima\MateriaPrimaController;
use App\Http\Controllers\Config\Permiso\PermisoController;
use App\Http\Controllers\Config\Usuario\UserController;
use App\Http\Controllers\Modulos\Empleado\EmpleadosController;
use App\Http\Controllers\Modulos\Potreros\PotreroController;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Http\Middleware\HandlePrecognitiveRequests;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use Spatie\Permission\Models\Permission;

/** Rutas de Configuracion los parametros */
Route::middleware('auth')->group(function () {

    Route::controller(PermisoController::class)->group(function () {
        Route::get('/Permisos/Explorar', 'index')->name('Permisos.Explorar');  // Explorar
        Route::get('/Permisos/Crear', 'create')->name('Permisos.create');  // Explorar
        Route::post('/Permisos/store', 'store')->name('Permisos.store');  // Explorar
        Route::get('/Permisos/{name}/Edit', 'edit')->name('Permisos.edit');  // Explorar
        Route::put('/Permisos/update/{name}', 'update')->name('Permisos.update');  // Explorar
        Route::delete('/Permisos/destroy/{name}', 'destroy')->name('Permisos.delete');  // Explorar

    });


    Route::controller(RoleController::class)->group(function () {
        Route::get('/Rol/Explorar', 'index')->name('Rol.Explorar');  // Explorar
        Route::get('/Rol/Crear', 'create')->name('Rol.create');  // Explorar
        Route::post('/Rol/store', 'store')->name('Rol.store');  // Explorar
        Route::get('/Rol/{name}/Edit', 'edit')->name('Rol.edit');  // Explorar
        Route::put('/Rol/update/{name}', 'update')->name('Rol.update');  // Explorar
        Route::delete('/Rol/destroy/{name}', 'destroy')->name('Rol.delete');  // Explorar
        Route::post('/Rol/GestionRol/{rol}', 'GestionPermisos')->name('Rol.Gestion');  // Explorar

    });

    /**
     * Usuarios
     */

    Route::controller(UserController::class)->group(function () {
        Route::get('/User/Explorar', 'index')->name('User.Explorar');  // Explorar
        Route::get('/User/Crear', 'create')->name('User.Crear');  // Explorar
        Route::post('/User/store', 'store')->name('User.store');  // Explorar
        Route::get('/User/{identificacion}/Edit', 'edit')->name('User.edit');  // Explorar
        Route::put('/User/update/{identificacion}', 'update')->name('User.update');  // Explorar
        Route::delete('/User/destroy/{identificacion}', 'destroy')->name('User.delete');  // Explorar
        Route::put('/User/update/{identificacion}/PasswordReset', 'PasswordReset')->name('User.PasswordReset');  // Explorar
    });



    /**
     * Distrito
     */
    Route::controller(DistritoController::class)->group(function () {
        Route::get('/Distrito/Explorar', 'index')->name('Distrito.Explorar');  // Explorar
        Route::get('/Distrito/Crear', 'create')->name('Distrito.Crear'); // Vista Crear
        Route::post('/Distrito/store', 'store')->name('Distrito.store'); // Guardar
        Route::get('/Distrito/{slug}/Edit', 'edit')->name('Distrito.edit'); // vista Editar
        Route::put('/Distrito/update/{slug}', 'update')->name('Distrito.update'); // Actualizar
        Route::delete('/Distrito/destroy/{slug}', 'destroy')->name('Distrito.delete'); // Eliminar
    });

    /**
     * Finca
     */
    Route::controller(FincaController::class)->group(function () {
        Route::get('/Finca/Explorar', 'index')->name('Finca.Explorar');  // Explorar
        Route::get('/Finca/Crear', 'create')->name('Finca.Crear'); // Vista Crear
        Route::post('/Finca/store', 'store')->name('Finca.store'); // Guardar
        Route::get('/Finca/{slug}/Edit', 'edit')->name('Finca.edit'); // vista Editar
        Route::put('/Finca/update/{slug}', 'update')->name('Finca.update'); // Actualizar
        Route::delete('/Finca/destroy/{slug}', 'destroy')->name('Finca.delete'); // Eliminar
        Route::post('/Finca/getOptionsFinca', 'getOptionsFinca')->name('Finca.getOptionsFinca'); // Opciones Finca
        /** Consultas */
        Route::get('/Finca/consultametas', 'dataconsultaHectareasFinca')->name('Finca.HectMeta');
    });

    /**
     * Lote
     */
    Route::controller(LoteController::class)->group(function () {
        Route::get('/Lote/Explorar', 'index')->name('Lote.Explorar');  // Explorar
        Route::get('/Lote/Crear', 'create')->name('Lote.Crear'); // Vista Crear
        Route::post('/Lote/store', 'store')->name('Lote.store'); // Guardar
        Route::get('/Lote/{slug}/Edit', 'edit')->name('Lote.edit'); // vista Editar
        Route::put('/Lote/update/{slug}', 'update')->name('Lote.update'); // Actualizar
        Route::delete('/Lote/destroy/{slug}', 'destroy')->name('Lote.delete'); // Eliminar
        Route::post('/Lote/getOptionsLote', 'getOptionsLote')->name('Lote.getOptionsLote'); // Opciones Lote
        Route::post('/Lote/getDataLote', 'getDataLote')->name('Lote.getDataLote'); // Data Lote
    });

    /**
     * Labores
     */
    Route::controller(LaborController::class)->group(function () {
        Route::get('/Labor/Explorar', 'index')->name('Labor.Explorar');  // Explorar
        Route::get('/Labor/Crear', 'create')->name('Labor.Crear'); // Vista Crear
        Route::post('/Labor/store', 'store')->name('Labor.store'); // Guardar
        Route::get('/Labor/{slug}/Edit', 'edit')->name('Labor.edit'); // vista Editar
        Route::put('/Labor/update/{slug}', 'update')->name('Labor.update'); // Actualizar
        Route::delete('/Labor/destroy/{slug}', 'destroy')->name('Labor.delete'); // Eliminar
        Route::post('/Labor/getOptionsLote', 'getOptionsLote')->name('Labor.getOptionsLote'); // Opciones Lote
        Route::post('/Labor/getDataLote', 'getDataLote')->name('Labor.getDataLote'); // Data Lote
    });

    /**
     * TipoCultivo
     */
    Route::controller(TipoCultivoController::class)->group(function () {
        Route::get('/TipoCultivo/Explorar', 'index')->name('TipoCultivo.Explorar');  // Explorar
        Route::get('/TipoCultivo/Crear', 'create')->name('TipoCultivo.Crear'); // Vista Crear
        Route::post('/TipoCultivo/store', 'store')->name('TipoCultivo.store'); // Guardar
        Route::get('/TipoCultivo/{slug}/Edit', 'edit')->name('TipoCultivo.edit'); // vista Editar
        Route::put('/TipoCultivo/update/{slug}', 'update')->name('TipoCultivo.update'); // Actualizar
        Route::delete('/TipoCultivo/destroy/{slug}', 'destroy')->name('TipoCultivo.delete'); // Eliminar
        Route::post('/TipoCultivo/getOptionsLote', 'getOptionsLote')->name('TipoCultivo.getOptionsLote'); // Opciones Lote
        Route::post('/TipoCultivo/getDataLote', 'getDataLote')->name('TipoCultivo.getDataLote'); // Data Lote
    });

    /**
     * Tipo Variedad
     */
    Route::controller(TipoVariedadController::class)->group(function () {
        Route::get('/TipoVariedad/Explorar', 'index')->name('TipoVariedad.Explorar');  // Explorar
        Route::get('/TipoVariedad/Crear', 'create')->name('TipoVariedad.Crear'); // Vista Crear
        Route::post('/TipoVariedad/store', 'store')->name('TipoVariedad.store'); // Guardar
        Route::get('/TipoVariedad/{slug}/Edit', 'edit')->name('TipoVariedad.edit'); // vista Editar
        Route::put('/TipoVariedad/update/{slug}', 'update')->name('TipoVariedad.update'); // Actualizar
        Route::delete('/TipoVariedad/destroy/{slug}', 'destroy')->name('TipoVariedad.delete'); // Eliminar
        Route::post('/TipoVariedad/getOptionsLote', 'getOptionsLote')->name('TipoVariedad.getOptionsLote'); // Opciones Lote
        Route::post('/TipoVariedad/getDataLote', 'getDataLote')->name('TipoVariedad.getDataLote'); // Data Lote
    });

    /**
     * Grupo Maquinaria
     */
    Route::controller(GrupoMaquinaController::class)->group(function () {
        Route::get('/GrupoMaquina/Explorar', 'index')->name('GrupoMaquina.Explorar');  // Explorar
        Route::get('/GrupoMaquina/Crear', 'create')->name('GrupoMaquina.Crear'); // Vista Crear
        Route::post('/GrupoMaquina/store', 'store')->name('GrupoMaquina.store'); // Guardar
        Route::get('/GrupoMaquina/{slug}/Edit', 'edit')->name('GrupoMaquina.edit'); // vista Editar
        Route::put('/GrupoMaquina/update/{slug}', 'update')->name('GrupoMaquina.update'); // Actualizar
        Route::delete('/GrupoMaquina/destroy/{slug}', 'destroy')->name('GrupoMaquina.delete'); // Eliminar
        Route::post('/GrupoMaquina/getOptionsLote', 'getOptionsLote')->name('GrupoMaquina.getOptionsLote'); // Opciones Lote
        Route::post('/GrupoMaquina/getDataLote', 'getDataLote')->name('GrupoMaquina.getDataLote'); // Data Lote
    });


    /**
     * Maquinaria
     */
    Route::controller(MaquinaController::class)->group(function () {
        Route::get('/Maquinaria/Explorar', 'index')->name('Maquinaria.Explorar');  // Explorar
        Route::get('/Maquinaria/Crear', 'create')->name('Maquinaria.Crear'); // Vista Crear
        Route::post('/Maquinaria/store', 'store')->name('Maquinaria.store'); // Guardar
        Route::get('/Maquinaria/{slug}/Edit', 'edit')->name('Maquinaria.edit'); // vista Editar
        Route::put('/Maquinaria/update/{slug}', 'update')->name('Maquinaria.update'); // Actualizar
        Route::delete('/Maquinaria/destroy/{slug}', 'destroy')->name('Maquinaria.delete'); // Eliminar
        Route::post('/Maquinaria/getOptionsLote', 'getOptionsLote')->name('Maquinaria.getOptionsLote'); // Opciones Lote
        Route::post('/Maquinaria/getDataLote', 'getDataLote')->name('Maquinaria.getDataLote'); // Data Lote
    });

    /**
     * Tipo Cumplido
     */
    Route::controller(TipoCumplidoController::class)->group(function () {
        Route::get('/TipoCumplido/Explorar', 'index')->name('TipoCumplido.Explorar');  // Explorar
        Route::get('/TipoCumplido/Crear', 'create')->name('TipoCumplido.Crear'); // Vista Crear
        Route::post('/TipoCumplido/store', 'store')->name('TipoCumplido.store'); // Guardar
        Route::get('/TipoCumplido/{slug}/Edit', 'edit')->name('TipoCumplido.edit'); // vista Editar
        Route::put('/TipoCumplido/update/{slug}', 'update')->name('TipoCumplido.update'); // Actualizar
        Route::delete('/TipoCumplido/destroy/{slug}', 'destroy')->name('TipoCumplido.delete'); // Eliminar
        Route::post('/TipoCumplido/getOptionsLote', 'getOptionsLote')->name('TipoCumplido.getOptionsLote'); // Opciones Lote
        Route::post('/TipoCumplido/getDataLote', 'getDataLote')->name('TipoCumplido.getDataLote'); // Data Lote
    });

    /**
     * Tipo Labor
     */
    Route::controller(TipoLaborController::class)->group(function () {
        Route::get('/TipoLabor/Explorar', 'index')->name('TipoLabor.Explorar');  // Explorar
        Route::get('/TipoLabor/Crear', 'create')->name('TipoLabor.Crear'); // Vista Crear
        Route::post('/TipoLabor/store', 'store')->name('TipoLabor.store'); // Guardar
        Route::get('/TipoLabor/{slug}/Edit', 'edit')->name('TipoLabor.edit'); // vista Editar
        Route::put('/TipoLabor/update/{slug}', 'update')->name('TipoLabor.update'); // Actualizar
        Route::delete('/TipoLabor/destroy/{slug}', 'destroy')->name('TipoLabor.delete'); // Eliminar
        Route::post('/TipoLabor/getOptionsLote', 'getOptionsLote')->name('TipoLabor.getOptionsLote'); // Opciones Lote
        Route::post('/TipoLabor/getDataLote', 'getDataLote')->name('TipoLabor.getDataLote'); // Data Lote
    });

    /**
     * Labor
     */
    Route::controller(LaborController::class)->group(function () {
        Route::get('/Labor/Explorar', 'index')->name('Labor.Explorar');  // Explorar
        Route::get('/Labor/Crear', 'create')->name('Labor.Crear'); // Vista Crear
        Route::post('/Labor/store', 'store')->name('Labor.store'); // Guardar
        Route::get('/Labor/{slug}/Edit', 'edit')->name('Labor.edit'); // vista Editar
        Route::put('/Labor/update/{slug}', 'update')->name('Labor.update'); // Actualizar
        Route::delete('/Labor/destroy/{slug}', 'destroy')->name('Labor.delete'); // Eliminar
        Route::post('/Labor/getOptionsLote', 'getOptionsLote')->name('Labor.getOptionsLote'); // Opciones Lote
        Route::post('/Labor/getDataLote', 'getDataLote')->name('Labor.getDataLote'); // Data Lote

        Route::get('/Labor/actualizarprecios', 'actualizar')->name('Labor.actualizarprecio'); // Opciones Lote con Scope
        Route::get('/Labor/descargarPlantilla', 'descargarPlantilla')->name('Labor.descargarPlantilla'); // Descargar Plantilla Excel
        Route::post('/Labor/validarExcel', 'validarExcel')->name('Labor.validarExcel'); // Descargar Plantilla Excel
        Route::post('/Labor/importarExcel', 'importarExcel')->name('Labor.importarExcel'); // Importar Excel
    });




    Route::controller(GrupoMateriaPrimaController::class)->group(function () {
        Route::get('/GrupoMateriaPrima/Explorar', 'index')->name('GrupoMateriaPrima.Explorar');  // Explorar
        Route::get('/GrupoMateriaPrima/Crear', 'create')->name('GrupoMateriaPrima.Crear'); // Vista Crear
        Route::post('/GrupoMateriaPrima/store', 'store')->name('GrupoMateriaPrima.store'); // Guardar
        Route::get('/GrupoMateriaPrima/{slug}/Edit', 'edit')->name('GrupoMateriaPrima.edit'); // vista Editar
        Route::put('/GrupoMateriaPrima/update/{slug}', 'update')->name('GrupoMateriaPrima.update'); // Actualizar
        Route::delete('/GrupoMateriaPrima/destroy/{slug}', 'destroy')->name('GrupoMateriaPrima.delete'); // Eliminar
        Route::post('/GrupoMateriaPrima/OptionsMateriaPrimaScope', 'OptionsMateriaPrimaScope')->name('GrupoMateriaPrima.OptionsMateriaPrimaScope'); // Opciones Lote con Scope

    });

    Route::controller(MateriaPrimaController::class)->group(function () {
        Route::get('/MateriaPrima/Explorar', 'index')->name('MateriaPrima.Explorar');  // Explorar
        Route::get('/MateriaPrima/Crear', 'create')->name('MateriaPrima.Crear'); // Vista Crear
        Route::post('/MateriaPrima/store', 'store')->name('MateriaPrima.store'); // Guardar
        Route::get('/MateriaPrima/{slug}/Edit', 'edit')->name('MateriaPrima.edit'); // vista Editar
        Route::put('/MateriaPrima/update/{slug}', 'update')->name('MateriaPrima.update'); // Actualizar
        Route::delete('/MateriaPrima/destroy/{slug}', 'destroy')->name('MateriaPrima.delete'); // Eliminar
        Route::post('/MateriaPrima/getOptionsMateriaPrimaScope', 'scopeOptionsMateriaPrimaScope')->name('MateriaPrima.getOptionsMateriaPrimaScope'); // Opciones Lote con Scope
        Route::get('/MateriaPrima/actualizarprecios', 'actualizarprecio')->name('MateriaPrima.actualizarprecio'); // Opciones Lote con Scope
        Route::get('/MateriaPrima/descargarPlantilla', 'descargarPlantilla')->name('MateriaPrima.descargarPlantilla'); // Descargar Plantilla Excel
        Route::post('/MateriaPrima/validarExcel', 'validarExcel')->name('MateriaPrima.validarExcel'); // Descargar Plantilla Excel
        Route::post('/MateriaPrima/importarExcel', 'importarExcel')->name('MateriaPrima.importarExcel'); // Importar Excel
    });

        /**
     * Distrito
     */
    Route::controller(ContratistaController::class)->group(function () {
        Route::get('/contratista', 'index')->name('contratista.Explorar');  // Explorar
        Route::get('/contratista/Crear', 'create')->name('contratista.crear'); // Vista Crear
        Route::post('/contratista/store', 'store')->name('contratista.store'); // Guardar
        Route::get('/contratista/{slug}/Edit', 'edit')->name('contratista.edit'); // vista Editar
        Route::put('/contratista/update/{slug}', 'update')->name('contratista.update'); // Actualizar
        Route::delete('/contratista/destroy/{slug}', 'destroy')->name('contratista.delete'); // Eliminar
    });



        Route::controller(EmpleadosController::class)->group(function () {
        Route::get('/empleados', 'index')->name('empleados.Explorar');  // Explorar
        Route::get('/empleados/Crear', 'create')->name('empleados.crear'); // Vista Crear
        Route::post('/empleados/store', 'store')->name('empleados.store'); // Guardar
        Route::get('/empleados/{id}/Edit', 'edit')->name('empleados.edit'); // vista Editar
        Route::put('/empleados/update/{id}', 'update')->name('empleados.update'); // Actualizar
        Route::delete('/empleados/destroy/{id}', 'destroy')->name('empleados.delete'); // Eliminar
    });


    Route::controller(PotreroController::class)->group(function () {

        Route::post('/Potrero/getOptionsPotrero', 'getOptionsPotrero')->name('Potrero.getOptionsPotrero'); // Opciones Potreros
    });

});


