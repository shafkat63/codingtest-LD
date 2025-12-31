<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ApiTokenMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Check if the user is authenticated via Sanctum
        if (!$request->user()) {
            return response()->json([
                'success' => false,
                'error_code' => 1003,
                'message' => 'Invalid or missing API token.'
            ], 200); // Always 200 for API response
        }

        return $next($request);
    }
}
