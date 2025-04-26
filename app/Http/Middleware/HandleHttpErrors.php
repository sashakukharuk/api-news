<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class HandleHttpErrors
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        if ($response instanceof Response) {
            $status = $response->getStatusCode();

            if (in_array($status, [403, 404])) {
                $messages = [
                    403 => 'Access denied.',
                    404 => 'Resource not found.',
                ];

                $message = $messages[$status] ?? 'Server error.';

                return response()->json([
                    'message' => $message,
                ], $status);
            }
        }

        return $response;
    }
}
