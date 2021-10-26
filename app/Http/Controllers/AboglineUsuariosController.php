<?php

namespace App\Http\Controllers;

use App\Usuarios;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AboglineUsuariosController extends Controller{

    /********************************************************************************** */
    // CONSULTAR INFORMACIÓN GENERAL DE LA PÁGINA
    /********************************************************************************** */

    public function apiAboglineUsuariosGetInfo(Request $request){

        //  Variables iniciales
        $response = [];

        //  Consultar usuarios

        $sqlString = "
            SELECT
                *
            FROM
                abogline_usuarios
        ";

        $usuarios = DB::select($sqlString);

        // Generar respuesta

        array_push(
            $response,
            [
                "usuarios" => $usuarios
            ]
        );

        //  Retornar usuario
        return response()->json($response);

    }

    /********************************************************************************** */
    // CONSULTAR INFORMACIÓN GENERAL DE LA PÁGINA
    /********************************************************************************** */

    public function apiAboglineUsuariosUpdate(Request $request){

        //  Parametros de entrada

        $usuario = $request->usuario;
        $nombres = $request->nombres;
        $apellidos = $request->apellidos;

        //  Actualizar usuario

        $sqlString = "
            UPDATE
                abogline_usuarios
            SET
                nombres = '".$nombres."',
                apellidos = '".$apellidos."'
            WHERE
                usuario = '".$usuario."'
        ";

        DB::update($sqlString);

    }

}