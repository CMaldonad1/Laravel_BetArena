<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use \App\Models\Usuari;
class EmailExists
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
        $emails=Usuari::select('email')->pluck('email');

        $request->validate(
            [
                'email'     => 'required|exists:Usuari,email',
            ],
            [
                'email.required'        => 'E-mail no ha sigut introduït',
                'email.exists'          => 'E-mail introduït no pertany a cap compte actiu.',
            ]
        );
        return $next($request);
    }
}
