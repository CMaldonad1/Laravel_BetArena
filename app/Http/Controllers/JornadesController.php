<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Http\Response;
use \App\Models\Jornada;
use \App\Models\Partits;

class JornadesController extends Controller
{
    public function index(){
        $jornades=Jornada::all();
        return view(
            'uploadJSON',
            array(
                'jornada' => $jornades
            )
        );
    }
    public function uploadJSON(Request $request){
        return view('uploadJSON');
    }
    public function pujarJoranda (Request $request){
        $json=$request->file('novaJornada');
        //verifiquem que l'extensió es correcta
        $ext=$json->extension();
        if($ext!="json"){
            return redirect()->route('/uploadJornada')->with('JSONerrors', 'El document escollit no es un JSON.');
        }
        $jsonName = $json->getClientOriginalName();
        $saveFile = $json->move(public_path('files'), $jsonName);
        $info = json_decode(file_get_contents(public_path() . "/files/" . $jsonName), true);

        //verifiquem que la jornada no existeix
        $exist=Jornada::where('jornada','=',$info['jornada'])->get();
        if($exist->count()!=0){
            return redirect()->route('/uploadJornada')->with('JSONerrors', 'La joranda '.$info['jornada'].' ja existeix. Tria un altre per a carregar al sistema');
        }
        //verifiquem que té tots els partits
        $partits=$info['partits'];
        if(sizeof($partits)!=15){
            return redirect()->route('/uploadJornada')->with('JSONerrors', 'El file pujat no conté 15 partits. Revisa\'l i tornal a pujar.');
        }
        //revisem que no hi ha equips que jugin mes d'una vegada
        $pos=1;
        foreach($partits as $partit){
            $local=$partit['local'];
            $visitant=$partit['visitant'];
            if($local==$visitant){
                return redirect()->route('/uploadJornada')->with('JSONerrors', 'Hi han equips que juguen mes d\'una vegada aquesta Jornada!!');
            }else{
                for($i=$pos; $i<count($partits); $i++){
                    if($local==$partits[$i]['local'] || $local==$partits[$i]['visitant'] || $visitant==$partits[$i]['local'] || $visitant==$partits[$i]['visitant']){
                        return redirect()->route('/uploadJornada')->with('JSONerrors', 'Hi han equips que juguen mes d\'una vegada aquesta Jornada!!');
                    }
                }
            }
            $pos++;
        }
        //revisem que els partits no existeixin a la BBDD
        $duplicated=$this->reviewPartits($partits);
        if(!$duplicated){
            //preparem els arrays per a pujar la info a la BBDD
            $dia = date_create_from_format('d/m/Y', $info['inici']);
            $numJornada=$info['jornada'];
            $jornadaInfo=array(
                'jornada'   => $numJornada,
                'inici'     => $dia,
            );
            Jornada::insert($jornadaInfo);
            $partitsInfo=[];
            foreach($partits as $partit){
                $partitsInfo[]=[
                    'jornada'   => $numJornada,
                    'local'     => $partit['local'],
                    'visitant'  => $partit['visitant']
                ];
            }
            Partits::insert($partitsInfo);
            return redirect()->route('/uploadJornada')->with('success', 'El file ha sigut pujat correctament!')->with('data',$info);
        }else{
            return redirect()->route('/uploadJornada')->with('JSONerrors', 'El fitxer conté partits que ja existeixen a la BBDD. Revisa\'l i torna a pujar-lo');
        }

    }
    public function reviewPartits($partits){
        foreach($partits as $partit){
            $exist=Partits::where([
                        ['local','=',$partit['local']],
                        ['visitant','=',$partit['visitant']]
                    ])->get();
            if($exist->count()!=0){
                return true;
            }
        }
        return false;
    }
    public function resultsView(Request $request){
        $jornades=Jornada::all();
        $partits=array();

        if($request->session()->has('jornada')){
            $partits=Partits::where('jornada','=',$request->session()->get('jornada'))->get();
        }
        return view(
            'resultats',
            array(
                'jornada' => $jornades,
                'partits' => $partits,
            )
        );
    }
    public function updateResults(Request $request){
        $jornada=$request->session()->get('jornada');
        foreach($request->get('partit_id') as $p){
            if(!is_null($request->get($p))){
                Partits::where([
                    ['jornada','=',$request->session()->get('jornada')],
                    ['id','=',$p]
                ])
                ->update(array('resultat' => $request->get($p)));
            }
        }
        return redirect()->route('/resultats')->with('success', 'Els resultats han sigut guardats correctament!');
    }
}
