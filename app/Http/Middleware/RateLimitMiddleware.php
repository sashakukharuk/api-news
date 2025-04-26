<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\RateLimiter as FacadesRateLimiter;

class RateLimitMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $key = 'limit-' . $request->ip();
        
        if (FacadesRateLimiter::tooManyAttempts($key, $perMinute = 5)) {
            return response()->json([
                'message' => 'Too many requests. Please try again later.',
            ], 429);
        }
        
        FacadesRateLimiter::hit($key, 60);
        
        return $next($request);
    }
}
