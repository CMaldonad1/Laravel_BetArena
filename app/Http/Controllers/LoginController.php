<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use \Illuminate\Http\Response;
use \App\Models\Jornada;
use \App\Models\Partits;
use \App\Models\Usuari;
use \App\Models\Aposta;
use \App\Http\Controllers\PHPMailerController;

class LoginController extends Controller
{
    public function index(Request $request){
        $jornades=Jornada::all();
        $partits=array();
        if($request->session()->has('user')){
            if($request->session()->get('user')->pswrdreset==1){
                return redirect()->route('/changepswrd')->with('warning', 'Estas en procediment de recuperació de contrasenya, es obligatori ficar una de nova abans de sortir!');
            }
        }
        $apostes=$this->generateResults($request);

        return view(
            'login',
            array(
                'jornada' => $jornades,
                'partits' => $apostes,
            )
        );
    }
    public function generateResults(Request $request){
        $apostes=[];
        if($request->session()->has('jornada')){

            $partits=Partits::where('jornada','=',$request->session()->get('jornada'))->orderBy('id')->get();
            foreach($partits as $p){
                $apostes[]=[
                    'local'     =>$p->local,
                    'visitant'  =>$p->visitant,
                    'resultat'  =>$p->resultat,
                    'guanya'    =>Aposta::where([['partit','=',$p->id],['aposta','=','1']])->count(),
                    'empata'    =>Aposta::where([['partit','=',$p->id],['aposta','=','X']])->count(),
                    'perd'      =>Aposta::where([['partit','=',$p->id],['aposta','=','2']])->count()
                ];
            }
        }
        return $apostes;
    }
    public function selectJornada(Request $request){
        $jornada=$request->input('jornada');
        if(isset($request->tanca)){
            Jornada::where('jornada',$jornada)->update(array('tancada' => 1));
        }else{
            if($jornada!="0"){
                $request->session()->put('jornada', $jornada);
            }else{
                session()->forget('jornada');
            }
        }
        return redirect()->back();
    }
    public function verification(){
        return redirect()->route('log');
    }
    public function logout(){
        session()->forget('user');
        session()->forget('jornada');
        return redirect()->route('log');
    }
    public function registre(){
        return view('signup');
    }
    public function recupera(){
        return view('recover');
    }
    public function canviPswrd(){
        return view('updatePswrd');
    }
    public function updateNewPswrd(Request $request){
        $email=$request->session()->get('user')->email;
        $request->validate(
            [
                'password'      => 'required|min:8|not_in:'.$request->session()->get('user')->password,
                'password2'     => 'in:'.$request->input('password'),
            ],
            [
                'password.required'     => 'Has d\'indicar una contrasenya',
                'password.not_in'       => 'Introdueix una contrasenya diferent a l\'anterior',
                'password.min'          => 'Contrasenya ha de tindre 8 caracters minim',
                'password2.in'          => 'Les contrasenyes introduïdes no son iguals, torna a introduïrles.'
            ]
        );
        $pswrd=$request->input('password');
        $data=array(
            'password'      => $pswrd,
            'pswrdreset'    => 0,
        );
        Usuari::where('email',$email)->update($data);
        $user=Usuari::where('email','=',$email)->first();
        session()->put('user', $user);

        return redirect()->route('/changepswrd')->with('msg', 'La teva contrasenya ha sigut actualitzada correctament!');
    }
    public function recuperarContrasenya(Request $request){
        $email=$request->input('email');
        $pswrd=$this->randomPswrd();
        $data=array(
            'password'  => $pswrd,
            'pswrdreset'=> 1
        );
        Usuari::where('email',$email)->update($data);
        $mail=array(
            'user'          => $email,
            'emailSubject'  => "MaviApostes - Recuperació de contrasenya",
            'emailBody'     => "Benvigut/da usuari/a,<br> troba a continuació la nova contrasenya
                                asignada a la seva compta: ".$pswrd.".<br><br>Una vegada loginat haurás
                                d'indicar una nova contrasenya.<br><br>Molta sort en les teves apostes!",
        );
        $phpmail=new PHPMailerController();
        $phpmail->emailSender($mail);
        return redirect()->route('/recover')->with('msg', 'E-mail de recuperació de contrasenya enviat. Revisa la teva bùstia de correu electronic');
    }
    public function validaUsuari(Request $request, $email){
        $data=array(
            'validat'=> 1
        );
        Usuari::where('email',$email)->update($data);
        return redirect()->route('log')->with('valid', 'El teu compte '.$email.' ha sigut validat correctament');
    }
    public function registrarUsuari(Request $request){
        $email=$request->input('email');
        $data=array(
            'email'     => $email,
            'password'  => $request->input('password'),
            'nom'       => ucfirst($request->input('nom')),
            'cognom'    => ucfirst($request->input('cognom')),
            'admin'     => 0,
            'validat'   => 0,
            'pswrdreset'=> 0
        );
        Usuari::insert($data);
        $mail=array(
            'user'          => $email,
            'emailSubject'  => "Confirmació de registre a MaviApostes",
            'emailBody'     => "Benvigut/da a la teva casa d'apostes,<br> per a validar el teu compte has de fer click
                                    al seguent link: <br> <a href=".route('/valida', ['email'=>$email]).">CONFIRMA EL TEU REGISTRE</a>",
        );
        $phpmail=new PHPMailerController();
        $phpmail->emailSender($mail);
        return redirect()->route('/signup')->with('msg', 'Compte '.$email.' creat correctament. Revisa la teva bùstia de correu electronic');
    }
    public function randomPswrd()
    {
        $alphabet = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890';
        $password = array();
        $alpha_length = strlen($alphabet) - 1;
        for ($i = 0; $i < 11; $i++)
        {
            $n = rand(0, $alpha_length);
            $password[] = $alphabet[$n];
        }
        return implode($password);
    }
}
