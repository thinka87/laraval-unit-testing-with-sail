<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class IsEmailVerifiedMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        //abort_if(!auth()->user()->is_admin, 403);
        if (auth()->user()) {
            if(auth()->user()->email_verified_at !=""){
             
                return $next($request);
            }
            else{
                return redirect()->route('verification.notice');
            }
        }else{
           
        }       
    }

}