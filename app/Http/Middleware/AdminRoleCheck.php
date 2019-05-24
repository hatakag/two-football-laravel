<?php

namespace App\Http\Middleware;

use Closure;
use Symfony\Component\HttpFoundation\Response;

class AdminRoleCheck
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if(auth()->check() && auth()->user()->role == config('constants.role.admin')){
            return $next($request);
        }else{
            return response()->json(config("constants.error_response.FORBIDDEN_RESPONSE"), Response::HTTP_FORBIDDEN);
        }
    }
}
