<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Cache;

class CacheResponseMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $key = 'response_' . md5($request->url());

        if (Cache::has($key)) {
            return response(Cache::get($key));
        }

        $response = $next($request);
        Cache::put($key, $response->getContent(), 60);

        return $response;
    }
}
