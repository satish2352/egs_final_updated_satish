<?php 
namespace App\Http\Middleware;

use Closure;

class SetReferrerPolicy
{
    public function handle($request, Closure $next)
    {
        $response = $next($request);
        // Set the Referrer-Policy header
        $response->header('Referrer-Policy', 'no-referrer');
        return $response;
    }
}
