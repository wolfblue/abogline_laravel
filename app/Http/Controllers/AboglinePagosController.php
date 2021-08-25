<?php

namespace App\Http\Controllers;

use App\Usuarios;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AboglinePagosController extends Controller{

    /********************************************************************************** */
    // CONSULTAR INFORMACIÓN GENERAL DE LA PÁGINA
    /********************************************************************************** */

    public function apiAboglinePagosGetInfo(Request $request){

        //  Parametros de entrada
        $usuario = $request->usuario;

        //  Variables iniciales  
        $response = [];
        
        //  Consultar pagos

        $sqlString = "
            SELECT
                *
            FROM
                abogline_pagos
            WHERE
                usuario = '".$usuario."' AND
                estado = 'pendiente'
        ";

        $pagos = DB::select($sqlString);

        // Generar respuesta

        array_push(
            $response,
            [
                "pagos" => $pagos
            ]
        );

        //  Retornar usuario
        return response()->json($response);

    }

}