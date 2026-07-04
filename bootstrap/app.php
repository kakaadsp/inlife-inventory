<?php

use App\Http\Middleware\EnsureUserIsActive;
use App\Http\Middleware\RoleMiddleware;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        // Percayai semua proxy (diperlukan untuk Render.com reverse proxy)
        // Agar Laravel bisa mendeteksi HTTPS dengan benar
        $middleware->trustProxies(at: '*');

        // Register named middleware aliases
        $middleware->alias([
            'role'   => RoleMiddleware::class,
            'active' => EnsureUserIsActive::class,
        ]);

        // Append to 'web' group
        $middleware->appendToGroup('web', [
            EnsureUserIsActive::class,
        ]);
    })

    ->withExceptions(function (Exceptions $exceptions) {
        // Render 403 nicely
        $exceptions->render(function (\Illuminate\Auth\Access\AuthorizationException $e, Request $request) {
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Akses ditolak.',
                ], Response::HTTP_FORBIDDEN);
            }
        });
    })->create();
