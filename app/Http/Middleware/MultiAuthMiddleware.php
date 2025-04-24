<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class MultiAuthMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = null;

        if ($request->bearerToken() && Auth::guard('sanctum')->check()) {
            $user = Auth::guard('sanctum')->user();
        }

        if (!$user && Auth::guard('web')->check()) {
            $user = Auth::guard('web')->user();
        }

        if (!$user) {
            return response()->json([
                'message' => 'Unauthenticated.',
                'error' => 'Authentication required'
            ], 401);
        }

        Auth::setUser($user);

        return $next($request);
    }
} 