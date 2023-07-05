<?php
namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class SetSanctumTokenFromCookie
{
    public function handle(Request $request, Closure $next)
    {
        $token = $request->cookie('token');
        
        if ($token) {
            $request->headers->set('Authorization', 'Bearer ' . $token);
        }
        
        return $next($request);
    }
}
