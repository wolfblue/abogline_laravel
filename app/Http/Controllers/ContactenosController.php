<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

class ContactenosController extends Controller{

    //  ENVIAR FORMULARIO DE CONTACTENOS

    public function apiContactenosEnviarFormulario(Request $request){

        //  Parametros de entrada

        $nombres = $request->nombres;
        $apellidos = $request->apellidos;
        $telefono = $request->telefono;
        $email = $request->email;
        $asunto = $request->asunto;

        //  Correo electronico

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

        $html.= "<p><b>Nombres: </b>".$nombres."</p>";
        $html.= "<p><b>Apellidos: </b>".$apellidos."</p>";
        $html.= "<p><b>Teléfono de contacto: </b>".$telefono."</p>";
        $html.= "<p><b>E-mail: </b>".$email."</p>";
        $html.= "<p><b>Asunto: </b>".$asunto."</p>";

        $mail->Body = $html;

        $mail->send();

    }

}