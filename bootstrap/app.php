<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

// 🔐 Import semua middleware custom
use App\Http\Middleware\AuthManual;
use App\Http\Middleware\AdminMiddleware;
use App\Http\Middleware\FinanceMiddleware;

return Application::configure(basePath: dirname(__DIR__))

    // =============================
    // ROUTING
    // =============================
    ->withRouting(
        web: __DIR__ . '/../routes/web.php',
        commands: __DIR__ . '/../routes/console.php',
        health: '/up',
    )

    // =============================
    // MIDDLEWARE ALIAS (PENGGANTI Kernel.php)
    // =============================
    ->withMiddleware(function (Middleware $middleware) {

        $middleware->alias([
            // Auth manual (login custom)
            'auth.manual'  => AuthManual::class,

            // Role middleware
            'role.admin'   => AdminMiddleware::class,
            'role.finance' => FinanceMiddleware::class,
        ]);

    })

    // =============================
    // EXCEPTION HANDLING
    // =============================
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })

    ->create();
