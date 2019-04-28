<?php

namespace App\Http\Middleware;

use Closure;

class UserRoleCheck
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
        if(auth()->check() && auth()->user()->role == config('constants.role.user')){
            return $next($request);
        }else{
            return response()->json([
                'status' => false,
                'message' => 'Invalid role',
                'code' => 101,
            ], 403);
        }
    }
}
