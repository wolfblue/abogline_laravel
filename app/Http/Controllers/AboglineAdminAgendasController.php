<?php

namespace App\Http\Controllers;

use App\Usuarios;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AboglineAdminAgendasController extends Controller{

    /********************************************************************************** */
    // CONSULTAR INFORMACIÓN GENERAL DE LA PÁGINA
    /********************************************************************************** */

    public function apiAboglineAdminAgendasGetInfo(Request $request){

        //  Variables iniciales
        $response = [];

        //  Consultar agendas

        $sqlString = "
            SELECT
                A.id,
                A.usuario,
                A.estado,
                A.link,
                A.fecha,
                B.email,
                C.descripcion
            FROM
                abogline_agendas A,    
                abogline_usuarios B,
                abogline_peticiones C
            WHERE
                A.usuario = B.usuario AND
                A.id_peticion = C.id AND
                A.estado = 'aprobado'
        ";

        $agendas = DB::select($sqlString);

        // Generar respuesta

        array_push(
            $response,
            [
                "agendas" => $agendas
            ]
        );

        //  Retornar usuario
        return response()->json($response);

    }

    /********************************************************************************** */
    // ACTUALIZAR AGENDA
    /********************************************************************************** */

    public function apiAboglineAdminAgendaUpdate(Request $request){

        //  Parametros de entrada

        $idAgenda = $request->idAgenda;
        $link = $request->link;

        //  Actualizar agenda

        $sqlString = "
            UPDATE
                abogline_agendas
            SET
                link = '".$link."'
            WHERE
                id = '".$idAgenda."'
        ";

        DB::update($sqlString);

    }

}