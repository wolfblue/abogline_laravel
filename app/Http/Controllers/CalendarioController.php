<?php

namespace App\Http\Controllers;

use App\Usuarios;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CalendarioController extends Controller{

    //  CONSULTAR NOTIFICACIONES

    public function apiConsultarCalendario(Request $request){

        //  Parametros de entrada
        $usuario = $request->usuario;

        //  Consultar calendario

        $sqlString = "
            SELECT
                fechaDesde,
                fechaHasta,
                descripcion,
                link
            FROM
                calendario
            WHERE
                (
                    usuario = '".$usuario."' OR
                    abogado = '".$usuario."'
                ) AND
                estado = '2'
        ";

        $sql = DB::select($sqlString);

        //  Retornar información del usuario
        return response()->json($sql);

    }

    //  ASIGNAR LINK DE REUNIÓN

    public function apiAsignarLinkReunion(Request $request){

        //  Parametros de entrada
        
        $idSolicitud = $request->idSolicitud;
        $idCalendario = $request->idCalendario;
        $link = $request->link;

        //  Asignar link

        $sqlString = "
            UPDATE 
                calendario
            SET
                link = '".$link."'
            WHERE
                id = '".$idCalendario."'
        ";

        DB::update($sqlString);

        //  Cambiar estado de la solicitud

        $sqlString = "
            UPDATE 
                solicitudes
            SET
                estado = '2'
            WHERE
                id = '".$idSolicitud."'
        ";

        DB::update($sqlString);

    }

}