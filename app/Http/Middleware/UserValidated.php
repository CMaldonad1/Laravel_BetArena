<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use \App\Models\Usuari;
use \Illuminate\Database\Eloquent\ModelNotFoundException;
class UserValidated
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
        $mail=$request->get('email');
        try{
            $exist=Usuari::where([
                ['email','=',$mail],
                ['validat','=',0]])->firstOrFail();
        }catch(ModelNotFoundException $e){
            return redirect()->route('log')->with('valid', 'Aquest usuari ja està validat! Pots fer Login o recuperar contrasenya si no la recordes.');
        }
        return $next($request);
    }
}
