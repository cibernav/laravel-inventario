<?php

namespace App\Http\Middleware;

use Closure;
use Auth;
class CheckRoles
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
        // Modo 1
        // if(\Auth::user()->hasRole($role)){
        //     return $next($request);
        // }


        //Modo 2
        //campo role en tabla user
        //$roles = array_slice(func_get_args(), 2);
        // if(Auth::user()->hasRoles($roles)){
        //     return $next($request);
        // }

        //Modo 3
        $roles = array_slice(func_get_args(), 2);
        if(Auth::user()->hasRole($roles)){
            return $next($request);
        }

        return redirect('/');
    }
}
