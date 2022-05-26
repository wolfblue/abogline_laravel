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
        $mail->Host = 'smtp.hostinger.com';             //  smtp host
        $mail->SMTPAuth = true;
        $mail->Username = 'administrador@abogline.com';   //  sender username
        $mail->Password = '4riK5YuDZy*E$7h';       // sender password
        $mail->SMTPSecure = 'tls';                  // encryption - ssl/tls
        $mail->Port = 587;                          // port - 587/465

        $mail->setFrom('administrador@abogline.com', 'administrador@abogline.com');
        $mail->addAddress('contacto@abogline.com');

        $mail->isHTML(true);                // Set email content format to HTML

        $mail->Subject = "Contactenos";

        $html = "Un usuario desea ponerse en contacto con Abogline, a continuacion se envia la informacion digitada: <br><br>";

        $html.= "<p><b>Nombres: </b>".$nombres."</p>";
        $html.= "<p><b>Apellidos: </b>".$apellidos."</p>";
        $html.= "<p><b>Telefono de contacto: </b>".$telefono."</p>";
        $html.= "<p><b>E-mail: </b>".$email."</p>";
        $html.= "<p><b>Asunto: </b>".$asunto."</p>";

        $mail->Body = $html;

        $mail->send();

    }

}