<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
 
        public function handle(Request $request, Closure $next): Response
    {
        if (1==1) {
            return response()->json('Your account is inactive');
        }
  
        return $next($request);
    }
}
