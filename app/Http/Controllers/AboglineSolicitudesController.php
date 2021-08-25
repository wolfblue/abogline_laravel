<?php

namespace App\Http\Controllers;

use App\Usuarios;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AboglineSolicitudesController extends Controller{

    /********************************************************************************** */
    // CONSULTAR INFORMACIÓN GENERAL DE LA PÁGINA
    /********************************************************************************** */

    public function apiAboglineSolicitudesGetInfo(Request $request){

        //  Parametros de entrada
        $usuario = $request->usuario;

        //  Variables iniciales  
        $response = [];

        //  Consultar solicitudes

        $sqlString = "
            SELECT
                A.id,
                A.id_caso,
                CONCAT(B.nombres,' ',B.apellidos) AS solicita,
                A.tipo,
                A.creado AS fecha,
                A.descripcion
            FROM
                abogline_peticiones A,
                abogline_usuarios B
            WHERE
                A.usuario_solicita = B.usuario AND
                A.estado = 'pendiente' AND
                A.usuario_aprueba = '".$usuario."'
        ";

        $solicitudes = DB::select($sqlString);

        // Generar respuesta

        array_push(
            $response,
            [
                "solicitudes" => $solicitudes
            ]
        );

        //  Retornar usuario
        return response()->json($response);

    }

    /********************************************************************************** */
    // APROBAR SOLICITUD
    /********************************************************************************** */

    public function apiAboglineSolicitudesAprobar(Request $request){

        //  Parametros de entrada
        $idSolicitud = $request->idSolicitud;

        //  Variables iniciales

        $tipoPeticion = "";
        $usuarioSolicita = "";
        $usuarioAprueba = "";
        $cliente = "";
        $abogado = "";
        $idCaso = "0";

        //  Consultar tipo de solicitud

        $sqlString = "
            SELECT
                A.tipo,
                A.usuario_solicita,
                A.usuario_aprueba,
                B.usuario,
                B.id
            FROM
                abogline_peticiones A,
                abogline_casos B
            WHERE
                A.id_caso = B.id AND
                A.id = '".$idSolicitud."'
        ";

        $sql = DB::select($sqlString);

        foreach($sql as $result){

            $tipoPeticion = $result->tipo;
            $usuarioSolicita = $result->usuario_solicita;
            $usuarioAprueba = $result->usuario_aprueba;
            $cliente = $result->usuario;
            $idCaso = $result->id;

        }

        //  Consultar abogado

        if($cliente == $usuarioSolicita)
            $abogado = $usuarioAprueba;
        else
            $abogado = $usuarioSolicita;

        //  Actualizar caso según la petición

        switch($tipoPeticion){

            //  Aplicar al caso

            case "aplicar":

                //  Aprobar solicitud

                $sqlString = "
                    UPDATE
                        abogline_casos A,
                        abogline_peticiones B
                    SET
                        A.estado = 'consulta',
                        A.abogado = '".$abogado."',
                        A.actualizado = now(),
                        B.estado = 'aprobado',
                        B.actualizado = now()
                    WHERE
                        A.id = B.id_caso AND
                        B.id = '".$idSolicitud."'
                ";
        
                DB::update($sqlString);

                //  Generar primer pago

                $sqlString = "
                    INSERT INTO abogline_pagos VALUES (
                        '0',
                        '".$cliente."',
                        now(),
                        now(),
                        'pendiente',
                        'Pago pendiente para la consulta con el abogado del caso ".$idCaso."',
                        '".$idCaso."'
                    )
                ";

                DB::insert($sqlString);

            break;

            //  Aprobar agenda

            case "agendar":

                //  Actualizar agendamiento

                $sqlString = "
                    UPDATE
                        abogline_agendas A,
                        abogline_peticiones B
                    SET
                        A.estado = 'aprobado',
                        A.actualizado = now(),
                        B.estado = 'aprobado',
                        B.actualizado = now()
                    WHERE
                        A.id_PETICION = B.id AND
                        B.id = '".$idSolicitud."'
                ";
        
                DB::update($sqlString);

            break;

        }

    }

    /********************************************************************************** */
    // RECHAZAR SOLICITUD
    /********************************************************************************** */

    public function apiAboglineSolicitudesRechazar(Request $request){

        //  Parametros de entrada
        $idSolicitud = $request->idSolicitud;

        //  Aprobar solicitud

        $sqlString = "
            UPDATE
                abogline_peticiones
            SET
                estado = 'rechazado'
            WHERE
                id = '".$idSolicitud."'
        ";

        DB::update($sqlString);

    }

}