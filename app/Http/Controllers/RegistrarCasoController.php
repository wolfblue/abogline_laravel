<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class RegistrarCasoController extends Controller{

    //  CONSULTAR NOTIFICACIONES

    public function apiRegistrarCasoConsultarUsuario(Request $request){

        //  Parametros de entrada
        $usuario = $request->usuario;

        //  Consultar usuario

        $sqlString = "
            SELECT
                *
            FROM
                usuarios
            WHERE
                usuario = '".$usuario."'
        ";

        $sql = DB::select($sqlString);

        //  Retornar datos del usuario
        return response()->json($sql);

    }

}