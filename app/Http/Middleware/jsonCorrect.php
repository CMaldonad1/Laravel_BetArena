<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use \App\Models\Usuari;

class jsonCorrect
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
        $request->validate(
            [
                'novaJornada'           => 'required',
            ],
            [
                'novaJornada.required'  => 'Selecciona un fitxer per a poder continuar!',
            ]
        );

        return $next($request);
    }
}
