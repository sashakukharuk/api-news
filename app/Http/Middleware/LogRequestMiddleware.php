<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Throwable;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class LogRequestMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $startTime = microtime(true);

        $user = Auth::user();
        $userId = $user ? $user->id : null;

        $context = [
            'user_id' => $userId,
            'ip' => $request->ip(),
            'method' => $request->method(),
            'url' => $request->fullUrl(),
            'route_name' => optional($request->route())->getName(),
            'controller_action' => optional($request->route())->getActionName(),
        ];

        Log::info('Incoming request', $context);

        try {
            $response = $next($request);

            $executionTimeMs = round((microtime(true) - $startTime) * 1000, 2);

            Log::info('Response sent', array_merge($context, [
                'status' => $response->status(),
                'execution_time_ms' => $executionTimeMs,
            ]));

            return $response;

        } catch (Throwable $e) {
            $executionTimeMs = round((microtime(true) - $startTime) * 1000, 2);

            Log::error('Exception occurred', array_merge($context, [
                'error_message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'execution_time_ms' => $executionTimeMs,
            ]));

            throw $e;
        }    
    }
}
