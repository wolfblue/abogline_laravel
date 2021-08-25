<?php

namespace App\Http\Controllers;

use App\Usuarios;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AboglineRegisterController extends Controller{

    /********************************************************************************** */
    // REGISTRAR USUARIO
    /********************************************************************************** */

    public function apiAboglineRegisterRegistrarUsuario(Request $request){

        // Parametros de entrada

        $usuario = $request->usuario;
        $email = $request->email;
        $password = $request->password;
        $perfil = $request->perfil;

        // Insertar usuario

        $sqlString = "
            INSERT INTO abogline_usuarios VALUES (
                '".$usuario."',
                now(),
                now(),
                'activo',
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
                '',
                '',
                '',
                ''
            )
        ";

        DB::insert($sqlString);

    }

    /********************************************************************************** */
    // CONSULTAR USUARIO
    /********************************************************************************** */

    public function apiAboglineRegisterConsultarUsuario(Request $request){

        // Parametros de entrada
        $usuario = $request->usuario;

        // Consultar usuario

        $sqlString = "
            SELECT
                *
            FROM
                abogline_usuarios
            WHERE
                usuario = '".$usuario."'
        ";

        $sql = DB::select($sqlString);

        //  Retornar usuario
        return response()->json($sql);

    }

}