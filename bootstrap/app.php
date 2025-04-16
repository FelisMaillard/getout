<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        // Enregistrer l'alias
        $middleware->alias([
            'ban' => \App\Http\Middleware\CheckUserBan::class
        ]);

        // Ajouter le middleware globalement à toutes les requêtes
        $middleware->append(\App\Http\Middleware\CheckUserBan::class);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();
