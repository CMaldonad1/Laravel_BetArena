<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use \App\Models\Jornada;
use \App\Models\Partits;
use \App\Models\Aposta;
class ApostesController extends Controller
{
    public function apostesView(Request $request){
        $jornades=Jornada::all();
        $partits=array();
        if($request->session()->has('jornada')){
            $partits=DB::select("(SELECT pa.*, ap.aposta as aposta FROM partit pa
                                left join aposta ap
                                    on pa.id=ap.partit
                                where pa.jornada=".$request->session()->get('jornada')."
                                and ap.usuari='".session()->get('user')->email."'
                                union
                                SELECT *, '' as aposta FROM `partit`
                                where jornada=".$request->session()->get('jornada')."
                                and id not in (SELECT pr.id FROM partit pr
                                left join aposta aps
                                    on pr.id=aps.partit
                                    where pr.jornada=".$request->session()->get('jornada')."
                                    and aps.usuari='".session()->get('user')->email."'))
                                ORDER BY id, aposta desc");
        }
        return view(
            'useraposta',
            array(
                'jornada' => $jornades,
                'partits' => $partits,
            )
        );
    }
    public function ferAposta(Request $request){
        $user=session()->get('user')->email;
        $aposta=[];
        foreach($request->get('partit_id') as $p){
            if(!is_null($request->get($p))){
                $aposta[]=[
                    'usuari'    => $user,
                    'partit'    => $p,
                    'aposta'    => $request->get($p)
                ];
            }
        }
        Aposta::upsert($aposta, ['usuari', 'partit'],['aposta' ]);
        return redirect()->route('/aposta')->with('success', 'Les teves apostes han sigut guardades correctament!');
    }
}
