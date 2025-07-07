<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

class PHPMailerController extends Controller
{
    public function emailSender($data){
		$url="http://localhost/PracticaCI/index.php/Login/validauser/".$data['user'];
        $mail= new PHPMailer(true);
        try{
            $mail->SMTPDebug = 2;
            $mail->IsSMTP();
            $mail->CharSet="UTF-8";
            $mail->SMTPSecure = 'ssl';
            $mail->Host = 'smtp.gmail.com';
            $mail->Port = 465;
            $mail->Username = 'maldovtest@gmail.com';
            $mail->Password = 'sqjajovmsawmsptq';
            $mail->SMTPAuth = true;
            $mail->SMTPDebug = 0;                      // port - 587/465
            $mail->isHTML(true);

            $mail->setFrom('maldovtest@gmail.com', 'MaviApostes');
            $mail->addAddress($data['user']);

            $mail->Subject = $data['emailSubject'];
            $mail->Body    = $data['emailBody'];
            if( !$mail->send() ) {
                return back()->with("failed", "Email not sent.")->withErrors($mail->ErrorInfo);
            }
            else {
                return back()->with("success", "Email has been sent.");
            }
        } catch (Exception $e) {
            echo("error");exit;
             return back()->with('error','Message could not be sent.');
        }
	}
	public function recupera(){
		$user=$this->input->post('user');
		$params=array(
			'email' => $user,
			'err' => '',
			'ok' => '',
		);
		if(count($_POST)>0){
			if(valid_email($user)){
				$data=$this->user->userExist($user);
				if($data!=null){
					$pswd=$this->randomPswrd();
					$array=array(
						'password'=>$pswd,
						'recupera'=>1,
					);
					$this->user->updateContrasenya($user,$array);
					$ok=$this->emailRecuperacio($user,$pswd);
					if($ok){
						$params['ok']='Email de recuperació de contrasenya ha sigut enviat al correu '.$user.'.';
					}else{
						$params['err']='Email no s\'ha pogut enviar. Verifica que has entrat un e-mail valid.';
					}
				}else{
					$params['err']='L\'usuari no existeix. <br>Verifica que l\'email indicat es correcte';
				}
			}else{
				$params['err']='L\'email indicat no es correcte!';
			}
		}
	}
}
