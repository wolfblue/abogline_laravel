<?php

namespace App\Http\Controllers;

use App\Usuarios;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

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
    // INSERTAR USUARIO
    /********************************************************************************** */

    public function apiUsuariosInsertUser(Request $request){

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
                'true',
                '',
                '',
                '',
                ''
            )
        ";

        DB::insert($sqlString);
        
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
                notificacion_sms = '".$notificacionSMS."'
            WHERE
                usuario = '".$usuario."'
        ";

        DB::update($sqlString);
        
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
}