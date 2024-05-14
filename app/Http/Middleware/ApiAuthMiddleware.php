<?php
namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class ApiAuthMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        // Check if the request is for login or register endpoints
        if ($request->is('api/login') || $request->is('api/register') || $request->is('api/list-masters') || $request->is('api/list-updated-master') || $request->is('api/reset-password-byemail')) {
            return $next($request);
        }

        // Check if the request contains an authorization token
        if (!$request->bearerToken()) {
            echo $request;
            return response()->json(['error' => 'Unauthorized. Token is missing.'], 401);
        }

        return $next($request);
    }
}
