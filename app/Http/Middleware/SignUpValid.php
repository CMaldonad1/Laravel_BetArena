<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

class SignUpValid
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
                'email'     => 'required|unique:Usuari,email',
                'nom'       => 'required',
                'password'  => 'required|min:8',
                'password2' => 'in:'.$request->input('password'),
            ],
            [
                'email.required'        => 'E-mail no ha sigut introduït',
                'email.unique'          => 'E-mail introduït ja esta registrat. Revisa la teva bustia de correu electronic.',
                'nom.required'          => 'El camp nom está vuit',
                'password.required'     => 'Has d\'indicar una contrasenya',
                'password.min'          => 'Contrasenya ha de tindre 8 caracters minim',
                'password2.in'          => 'Les contrasenyes introduïdes no son iguals, torna a introduïrles.'
            ]
        );
        return $next($request);
    }
}
