<?php

namespace App\Http\Controllers;

use App\Usuarios;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AboglineCalendarController extends Controller{

    /********************************************************************************** */
    // CONSULTAR INFORMACIÓN GENERAL DE LA PÁGINA
    /********************************************************************************** */

    public function apiAboglineCalendarGetInfo(Request $request){

        //  Parametros de entrada
        $usuario = $request->usuario;

        //  Variables iniciales
        $response = [];
        $agenda = [];

        //  Consultar perfil del usuario

        $sqlString = "
            SELECT
                fecha
            FROM
                abogline_agendas A,
                abogline_peticiones B
            WHERE
                A.id_peticion = B.id AND
                (
                    usuario_solicita = '".$usuario."' OR
                    usuario_aprueba = '".$usuario."'
                ) AND
                A.estado = 'aprobado'
        ";

        $agenda = DB::select($sqlString);


        // Generar respuesta

        array_push(
            $response,
            [
                "agenda" => $agenda
            ]
        );

        //  Retornar usuario
        return response()->json($response);

    }

}