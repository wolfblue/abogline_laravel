<?php

namespace App\Http\Controllers;

use App\Usuarios;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

class UsuariosController extends Controller{

    /********************************************************************************** */
    // CONSULTAR USUARIO
    /********************************************************************************** */

    public function apiUsuariosGetUser(Request $request){

        //  Parametros de entrada
        $usuario = $request->usuario;

        //  Consultar información del usuario

        $sqlString = "
            SELECT
                *
            FROM
                usuarios
            WHERE
                (
                    usuario = '".$usuario."' OR
                    email = '".$usuario."'
                )
        ";

        $sql = DB::select($sqlString);

        //  Retornar información del usuario
        return response()->json($sql);

    }

    /********************************************************************************** */
    // CONSULTAR USUARIO Y CONTRASEÑA
    /********************************************************************************** */

    public function apiUsuariosGetUserPassword(Request $request){

        //  Parametros de entrada
        
        $usuario = $request->usuario;
        $password = $request->password;
        $recordar = $request->recordar;

        //  Actualizar recordar contraseña

        DB::update("
            UPDATE
                usuarios
            SET
                recordar = ".$recordar."
            WHERE
                usuario = '".$usuario."'
        ");

        //  Consultar información del usuario

        $sqlString = "
            SELECT
                *
            FROM
                usuarios
            WHERE
                (
                    usuario = '".$usuario."' OR
                    email = '".$usuario."'
                )   AND
                password = '".$password."'
        ";

        $sql = DB::select($sqlString);

        //  Retornar información del usuario
        return response()->json($sql);

    }

    /********************************************************************************** */
    // CONSULTAR TITULOS DE UN USUARIO
    /********************************************************************************** */

    public function apiUsuariosGetTitulos(Request $request){

        //  Parametros de entrada
        $usuario = $request->usuario;

        $sqlString = "
            SELECT
                *
            FROM
                titulos_hv_usuario
            WHERE
                usuario = '".$usuario."'
        ";

        $sql = DB::select($sqlString);

        //  Retornar titulos del usuario
        return response()->json($sql);

    }

    /********************************************************************************** */
    // CONSULTAR DOCUMENTOS DE UN USUARIO
    /********************************************************************************** */

    public function apiUsuariosGetDocumentos(Request $request){

        //  Parametros de entrada
        $usuario = $request->usuario;

        $sqlString = "
            SELECT
                *
            FROM
                usuarios_documentos
            WHERE
                usuario = '".$usuario."'
        ";

        $sql = DB::select($sqlString);

        //  Retornar titulos del usuario
        return response()->json($sql);

    }

    /********************************************************************************** */
    // CONSULTAR ABOGADOS
    /********************************************************************************** */

    public function apiUsuariosGetAbogados(Request $request){

        $sqlString = "
            SELECT
                *
            FROM
                usuarios
            WHERE
                perfil = 'abogado'
        ";

        $sql = DB::select($sqlString);

        //  Retornar titulos del usuario
        return response()->json($sql);

    }

    /********************************************************************************** */
    // INSERTAR USUARIO
    /********************************************************************************** */

    public function apiUsuariosInsertUser(Request $request){

        try{

            //  Parametros de entrada

            $usuario = $request->usuario;
            $email = $request->email;
            $password = $request->password;
            $perfil = $request->perfil;

            //  Insertar usuario

            $sqlString = "
                INSERT INTO usuarios VALUES (
                    '".$usuario."',
                    '".$email."',
                    '".$password."',
                    '".$perfil."',
                    '',
                    '',
                    '',
                    '',
                    '',
                    '',
                    '',
                    '',
                    '',
                    '',
                    '',
                    '',
                    now(),
                    'true',
                    'false',
                    'false',
                    'false',
                    'false',
                    '',
                    '',
                    '',
                    'false',
                    'false',
                    'false',
                    'false',
                    '',
                    '',
                    '',
                    '',
                    '',
                    '',
                    '',
                    '',
                    '',
                    '',
                    '',
                    '1',
                    '',
                    0
                )
            ";

            DB::insert($sqlString);

            //  Registrar titulo asignar para abogados

            $sqlString = "
                INSERT INTO titulos_hv_usuario values (
                    '0',
                    '".$usuario."',
                    '',
                    '',
                    ''
                )
            ";

            DB::insert($sqlString);

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
            $mail->addAddress($email);

            $mail->isHTML(true);
            $mail->CharSet = 'UTF-8';

            $mail->Subject = "Bienvenido a Abogline";

            $html = "Usted se ha registrado correctamente como ".$perfil."<br><br>";

            $html.= "<p><b>Usuario: </b>".$usuario."</p>";
            $html.= "<p><b>E-mail: </b>".$email."</p>";

            $mail->Body = $html;

            $mail->send();

            //  Notificación de bienvenida al usuario

            $sqlString = "
                INSERT INTO notificaciones values (
                    '0',
                    '".$usuario."',
                    '1',
                    'Bienvenido a Abogline',
                    'Usted se ha registrado correctamente como ".$perfil."',
                    '',
                    '',
                    '',
                    '0'
                )
            ";

            DB::insert($sqlString);

        }catch (Exception $e){
            return $e->getMessage();
        }
        
    }

    /********************************************************************************** */
    // ACTUALIZAR USUARIO
    /********************************************************************************** */

    public function apiUsuariosUpdateUser(Request $request){

        //  Parametros de entrada

        $nombres = $request->nombres;
        $apellidos = $request->apellidos;
        $usuario = $request->usuario;
        $email = $request->email;
        $tipoIdentificacion = $request->tipoIdentificacion;
        $identificacion = $request->identificacion;
        $genero = $request->genero;
        $telefonoContacto = $request->telefonoContacto;
        $ciudad = $request->ciudad;
        $facebook = $request->facebook;
        $twitter = $request->twitter;
        $instagram = $request->instagram;
        $notificacionEmail = $request->notificacionEmail;
        $notificacionSMS = $request->notificacionSMS;
        $direccion = $request->direccion;
        $municipio = $request->municipio;
        $nacimiento = $request->nacimiento;
        $perfil = $request->perfil;

        //  Actualizar usuario

        $sqlString = "
            UPDATE usuarios SET 
                nombres = '".$nombres."',
                apellidos = '".$apellidos."',
                email = '".$email."',
                tipo_identificacion = '".$tipoIdentificacion."',
                identificacion = '".$identificacion."',
                genero = '".$genero."',
                telefono_contacto = '".$telefonoContacto."',
                ciudad = '".$ciudad."',
                facebook = '".$facebook."',
                twitter = '".$twitter."',
                instagram = '".$instagram."',
                notificacion_email = '".$notificacionEmail."',
                notificacion_sms = '".$notificacionSMS."',
                direccion = '".$direccion."',
                municipio = '".$municipio."',
                nacimiento = '".$nacimiento."'
            WHERE
                usuario = '".$usuario."'
        ";

        DB::update($sqlString);

        //  Validar completa tu perfil

        if(
            $perfil == "cliente" &&
            $nombres &&
            $apellidos &&
            $tipoIdentificacion &&
            $identificacion &&
            $genero &&
            $telefonoContacto &&
            $ciudad &&
            $direccion &&
            $municipio &&
            $nacimiento
        ){

            $sqlString = "
                UPDATE
                    usuarios
                SET
                    completa_perfil = 'true'
                WHERE
                    usuario = '".$usuario."'
            ";

            DB::update($sqlString);

            //  Notificar al cliente el estado completado del perfil

            $sqlString = "
                INSERT INTO notificaciones values (
                    '0',
                    '".$usuario."',
                    '1',
                    'Perfil completado',
                    'Felicitaciones, ha completado su perfil, ahora puede registrar su caso.',
                    '',
                    '',
                    '',
                    '0'
                )
            ";

            DB::insert($sqlString);

        }
        
    }

    /********************************************************************************** */
    // ACTUALIZAR  CONTRASEÑA DEL USUARIO
    /********************************************************************************** */

    public function apiUsuariosUpdateUserPassword(Request $request){

        //  Parametros de entrada

        $usuario = $request->usuario;
        $password = $request->password;

        //  Actualizar contraseña

        $sqlString = "
            UPDATE usuarios SET 
                password = '".$password."'
            WHERE
                usuario = '".$usuario."'
        ";

        DB::update($sqlString);

    }

    /********************************************************************************** */
    // ACTUALIZAR  FOTO DEL USUARIO
    /********************************************************************************** */

    public function apiUsuariosUpdatePhoto(Request $request){

        //  Parametros de entrada

        $usuario = $request->usuario;
        $base64 = $request->base64;
        $ext = $request->ext;

        //  Actualizar ruta de la foto

        $sqlString = "UPDATE usuarios SET foto = 'photo/".$usuario.".".$ext."' WHERE usuario = '".$usuario."'";
        DB::update($sqlString);

        //  Guardar archivo en físico

        file_put_contents("photo/".$usuario.".".$ext, file_get_contents($base64));

    }

    /********************************************************************************** */
    // ACTUALIZAR  CAMPO DE REGISTRO DE USUARIO
    /********************************************************************************** */

    public function apiUsuariosUpdateField(Request $request){

        //  Parametros de entrada

        $usuario = $request->usuario;
        $field = $request->field;
        $value = $request->value;

        //  Actualizar registro

        $sqlString = "UPDATE usuarios SET ".$field." = '".$value."' WHERE usuario = '".$usuario."'";
        DB::update($sqlString);

    }

    /********************************************************************************** */
    // INSERTAR TITULO A UN USUARIO
    /********************************************************************************** */

    public function apiUsuariosInsertTitulo(Request $request){

        //  Parametros de entrada
        $usuario = $request->usuario;
    
        //  Registrar titulo asignar para abogados

        $sqlString = "
            INSERT INTO titulos_hv_usuario values (
                '0',
                '".$usuario."',
                '',
                '',
                ''
            )
        ";

        DB::insert($sqlString);

    }

    /********************************************************************************** */
    // ELIMINAR TITULO DE UN USUARIO
    /********************************************************************************** */

    public function apiUsuariosDeleteTitulo(Request $request){

        //  Parametros de entrada
        $id = $request->id;
    
        //  Registrar titulo asignar para abogados

        $sqlString = "DELETE FROM titulos_hv_usuario WHERE id = '".$id."'";

        DB::delete($sqlString);

    }

    /********************************************************************************** */
    // ACTUALIZAR CAMPO DE TÍTULOS DE UN USUARIO
    /********************************************************************************** */

    public function apiUsuariosUpdateFieldTitulo(Request $request){

        //  Parametros de entrada

        $id = $request->id;
        $field = $request->field;
        $value = $request->value;

        //  Actualizar registro

        $sqlString = "UPDATE titulos_hv_usuario SET ".$field." = '".$value."' WHERE id = '".$id."'";
        DB::update($sqlString);

    }

    /********************************************************************************** */
    // ACTUALIZAR DOCUMENTO DE USUARIO
    /********************************************************************************** */

    public function apiUsuariosUpdateDocumento(Request $request){

        //  Parametros de entrada

        $usuario = $request->usuario;
        $tipo = $request->tipo;
        $base64 = $request->base64;

        //  Eliminar registro actual

        $sqlString = "DELETE FROM usuarios_documentos WHERE tipo = '".$tipo."' ";
        DB::delete($sqlString);

        //  Insertar registro

        $sqlString = "
            INSERT INTO usuarios_documentos VALUES (
                '".$usuario."',
                '".$tipo."',
                '".$base64."'
            )
        ";

        DB::insert($sqlString);

    }

}