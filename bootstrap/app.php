<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use App\Http\Middleware\LogRequestMiddleware;
use Illuminate\Support\Facades\Log;
use App\Http\Middleware\HandleHttpErrors;
use Sentry\State\HubInterface;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        channels: __DIR__.'/../routes/channels.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->append(LogRequestMiddleware::class);
        $middleware->append(HandleHttpErrors::class);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        $exceptions->report(function (Throwable $e) {
            if (app()->bound(HubInterface::class)) {
                $eventId = app(HubInterface::class)->captureException($e);
            } else {
                $eventId = null;
            }
        
            Log::error('Unhandled exception', [
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'sentry_event_id' => $eventId,
            ]);
        });
    })->create();
