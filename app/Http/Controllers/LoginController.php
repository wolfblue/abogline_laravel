<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

class LoginController extends Controller{

    //  RECORDAR CONTRASEÑA

    public function apiLoginRecordarPassword(Request $request){

        //  Parametros de entrada
        $usuario = $request->usuario;

        //  Consultar contraseña

        $email = "";
        $password = "";

        $sqlString = "
            SELECT
                email,
                password
            FROM
                usuarios
            WHERE
                usuario = '".$usuario."'
        ";

        $sql = DB::select($sqlString);

        foreach($sql as $result){
            $email = $result->email;    
            $password = $result->password;
        }

        //  Enviar correo electronico

        $md5 = md5($usuario);

        if($password){

            $mail = new PHPMailer(true);

            $mail->SMTPDebug = 0;
            $mail->isSMTP();
            $mail->Host = 'smtp.hostinger.com';
            $mail->SMTPAuth = true;
            $mail->Username = 'administrador@abogline.com';
            $mail->Password = '4riK5YuDZy*E$7h';
            $mail->SMTPSecure = 'tls';
            $mail->Port = 587;

            $mail->setFrom('administrador@abogline.com', 'administrador@abogline.com');
            $mail->addAddress($email);

            $mail->isHTML(true);
            $mail->CharSet = 'UTF-8';

            $mail->Subject = "Abogline: Recordar contraseña";

            $html = "A continuación se envía link para restablecer su contraseña en Abogline: <br><br>";

            $html.= "<a href='".\Config::get('values.front')."links?tipo=password&usuario=".$md5."'>".\Config::get('values.front')."links?tipo=password&usuario=".$md5."</a>";

            $mail->Body = $html;

            $mail->send();

        }

    }

}