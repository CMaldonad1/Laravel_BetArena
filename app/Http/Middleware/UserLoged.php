<?php

namespace App\Http\Middleware;

use Illuminate\Http\Request;
use \App\Models\Usuari;
use \Illuminate\Database\Eloquent\ModelNotFoundException;
use Closure;
class UserLoged
{
    public function handle(Request $request, Closure $next)
    {
        if(!session()->has('user')){
            return redirect()->route('log');
        }
        return $next($request);
    }
}
