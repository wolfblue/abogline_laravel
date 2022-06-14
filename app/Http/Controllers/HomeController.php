<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

class HomeController extends Controller{

    //  ENVIAR FORMULARIO DE CONTACTENOS

    public function apiHomeEnviarContacto(Request $request){

        //  Parametros de entrada
        $email = $request->email;

        //  Enviar correo electronico

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
        $mail->addAddress('contacto@abogline.com');

        $mail->isHTML(true);
        $mail->CharSet = 'UTF-8';

        $mail->Subject = "Contáctenos";

        $html = "Un usuario desea ponerse en contacto con Abogline, a continuación se envía la información digitada: <br><br>";

        $html.= "<p><b>E-mail: </b>".$email."</p>";

        $mail->Body = $html;

        $mail->send();

    }

    //  ENVIAR HISTORIAL DEL CHAT

    public function apiHomeEnviarChat(Request $request){

        //  Parametros de entrada

        $usuario = $request->usuario;
        $chat = $request->chat;

        //  Consultar correo del usuario

        $sqlString = "
            SELECT
                email
            FROM
                usuarios
            WHERE
                usuario = '".$usuario."'
        ";

        $sql = DB::select($sqlString);

        foreach($sql as $result)
            $email = $result->email;

        //  Enviar historial al correo

        if($email){

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

            $mail->Subject = "Abogline: Historial del chat";

            $mail->Body = $chat;

            $mail->send();

        }

    }

}