<?php

use App\Support\ApiResponse;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Request;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->alias([
            'role' => \App\Http\Middleware\RoleMiddleware::class,
        ]);
    })

    ->withExceptions(function (Exceptions $exceptions): void {
        $exceptions->shouldRenderJsonWhen(
            fn (Request $request) => $request->is('api/*') || $request->expectsJson(),
        );

        // 401: token tidak ada / tidak valid
        $exceptions->render(function (AuthenticationException $e, Request $request) {
            if ($request->is('api/*')) {
                return ApiResponse::error('UNAUTHENTICATED', 'Token tidak ada atau tidak valid.', 401);
            }
        });

        // 403 / 404 / 405 pada endpoint API memakai format error yang sama
        $exceptions->render(function (HttpExceptionInterface $e, Request $request) {
            if (! $request->is('api/*')) {
                return null;
            }

            return match ($e->getStatusCode()) {
                403 => ApiResponse::error('FORBIDDEN', 'Anda tidak berhak mengakses sumber daya ini.', 403),
                404 => ApiResponse::error('NOT_FOUND', 'Endpoint tidak ditemukan.', 404),
                405 => ApiResponse::error('METHOD_NOT_ALLOWED', 'Method tidak diizinkan.', 405),
                default => null,
            };
        });
    })->create();
