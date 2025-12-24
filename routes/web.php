<?php

use App\Http\Controllers\MenuController;
use App\Http\Controllers\Modulos\Cumplidos\CumplidoAplicacion\Api\CumplidoAplicacionController;
use App\Http\Controllers\Modulos\Cumplidos\CumplidoMaquinaria\Api\CumplidoMaquinariaController;
use App\Http\Controllers\Modulos\Cumplidos\CumplidoOrdenServicio\Api\ApiCumplidoOrdenServicioController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use Illuminate\Support\Facades\Auth;

/** 
Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
});
 */

Route::get('/', function () {

    if (Auth::check()) {
        return redirect()->intended('dashboard');
    }
    return redirect()->route('login');
});

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {
    Route::get('/dashboard', function () {
        return Inertia::render('Dashboard');
    })->name('dashboard');
});

Route::get('/menu-items', [MenuController::class, 'getMenuItems'])->name('menu-items');
Route::post('/expanded-keys', [MenuController::class, 'getExpandedKeys'])->name('getExpandedKeys');


Route::controller(CumplidoAplicacionController::class)->group(function () {
    Route::get('CumplidoAplicacion/getAllCumplidosAplicacion', 'getAllCumplidosAplicacion')->name('cumplidoaplicacion.getAllCumplidosAplicacion');  // Explorar
    Route::get('CumplidoAplicacion/getAllCumplidosAplicacionDetalle', 'getAllCumplidosAplicacionDetalle')->name('cumplidoaplicacion.getAllCumplidosAplicacionDetalle');  // Explorar
});


