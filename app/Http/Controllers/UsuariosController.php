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
                usuario = '".$usuario."'
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
                usuario = '".$usuario."' AND
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

        //  Insertar usuario

        $sqlString = "
            INSERT INTO usuarios VALUES (
                '".$usuario."',
                '".$email."',
                '".$password."'
            )
        ";

        DB::insert($sqlString);
        
    }

}