<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class IsAdminMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        //abort_if(!auth()->user()->is_admin, 403);
        if (auth()->user()) {
            if(auth()->user()->is_admin){
                return $next($request);
            }else{
                abort(403);
            } 
        }else{
            return redirect()->route('login');
        }       
    }

}
