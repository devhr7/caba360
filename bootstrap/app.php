<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Support\Facades\Route;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__ . '/../routes/web.php',
        api: __DIR__ . '/../routes/api.php',
        commands: __DIR__ . '/../routes/console.php',
        health: '/up',
        using: function () {
            Route::middleware('web')
                ->group(base_path('routes/web.php'));

            Route::middleware('api')
                ->group(base_path('routes/api.php'));
            /**
             * ////////////////////////////////////////////////////////////
             * Ruta Personalizadas
             */
            // Config Parametros de la aplicacion
            Route::middleware('web')
                ->prefix('Config')
                ->group(base_path('routes/Config/Config.php'));
            /**
             * Modulos
             */
            // Registro Lotes
            Route::middleware('web')
                ->prefix('Modulos')
                ->group(base_path('routes/Modulos/RegistroLotes.php'));

            // cumplidos
            Route::middleware('web')
                ->prefix('Cumplidos')
                ->group(base_path('routes/Modulos/Cumplidos.php'));

            // Agronomo
            Route::middleware('web')
                ->prefix('Modulos')
                ->group(base_path('routes/Modulos/Records.php'));

            Route::middleware('web')
                ->prefix('Costos')
                ->group(base_path('routes/Modulos/Costos.php'));
        },
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->web(append: [
            \App\Http\Middleware\HandleInertiaRequests::class,
            \Illuminate\Http\Middleware\AddLinkHeadersForPreloadedAssets::class,
        ]);
        $middleware->statefulApi();
        $middleware->alias([
            'role' => \Spatie\Permission\Middleware\RoleMiddleware::class,
            'permission' => \Spatie\Permission\Middleware\PermissionMiddleware::class,
            'role_or_permission' => \Spatie\Permission\Middleware\RoleOrPermissionMiddleware::class,
        ]);

        //
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();
