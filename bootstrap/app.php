<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Middleware\HandleCors;
use App\Http\Middleware\CorsMiddleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        //   
        // $middleware->alias([
        //     'cors' => HandleCors::class,  // Asegura que CORS esté habilitado
        // ]);

        $middleware->append(CorsMiddleware::class);


    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();
