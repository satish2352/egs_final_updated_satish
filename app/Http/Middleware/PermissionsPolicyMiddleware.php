<?php

namespace App\Http\Middleware;

use Closure;

class PermissionsPolicyMiddleware
{
    public function handle($request, Closure $next)
    {
        $response = $next($request);
        
        $permissionsPolicy = "camera=(), microphone=(), geolocation=()";

        $response->headers->set('Permissions-Policy', $permissionsPolicy);

        return $response;
    }
}
