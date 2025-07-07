<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class AdminLoged
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        if(!session()->has('user')){
            return redirect()->route('log');
        }else{
            if(session()->get('user')->admin==0){
                return redirect()->route('log');
            }
        }
        return $next($request);
    }
}
