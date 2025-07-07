<?php

namespace App\Http\Middleware;

use Illuminate\Http\Request;
use \App\Models\Usuari;
use \Illuminate\Database\Eloquent\ModelNotFoundException;
use Closure;
class Logs
{
    public function handle(Request $request, Closure $next)
    {
        $request->validate(
            [
                'pass'  => 'required',
                'user'  => 'required',
            ],
            [
                'pass.required' => 'Introdueix una contrasenya',
                'user.required' => 'El camp e-mail no pot estar vuit'
            ]
        );

        $user=$request->input('user');
        $pswrd=$request->input('pass');
        try{
            $exist=Usuari::where([
                ['email','=',$user],
                ['password','=',$pswrd]])->firstOrFail();
        }catch(ModelNotFoundException $e){
            return redirect()->route('log')->with('mail',$user)->with('logerr', 'Informació erronea! Verifica e-mail i/o contrasenya');
        }

        if($exist['validat']==0){
            return redirect()->route('log')->with('mail',$user)->with('logerr', 'Usuari no ha estat validat! Revisa el teu correu.');
        }else{
            session()->put('user', $exist);
            if($exist['pswrdreset']==1){
                return redirect()->route('/changepswrd');
            }else{
                return redirect()->route('log');
            }

        }
    }
}
