<?php

namespace App\Http\Controllers;

use App\Usuarios;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AboglineAgendarController extends Controller{

    /********************************************************************************** */
    // CONSULTAR INFORMACIÓN GENERAL DE LA PÁGINA
    /********************************************************************************** */

    public function apiAboglineAgendarGetInfo(Request $request){

        //  Parametros de entrada

        $usuario = $request->usuario;
        $perfil = $request->perfil;

        //  Variables iniciales
        
        $response = [];
        $casos = [];
        $pagosPendientes = [];

        //  Consultar casos

        $sqlString = "
            SELECT
                A.*
            FROM
                abogline_casos A
            WHERE
                (usuario = '".$usuario."' OR abogado = '".$usuario."') AND
                estado != 'registrado'
        ";

        $casos = DB::select($sqlString);

        //  Consultar pagos pendientes del cliente

        $sqlString = "
            SELECT
                *
            FROM
                abogline_pagos
            WHERE
                estado = 'pendiente'
        ";

        $pagosPendientes = DB::select($sqlString);

        // Generar respuesta

        array_push(
            $response,
            [
                "casos" => $casos,
                "pagosPendientes" => $pagosPendientes
            ]
        );

        //  Retornar usuario
        return response()->json($response);

    }

    /********************************************************************************** */
    //  AGENDAR
    /********************************************************************************** */

    public function apiAboglineAgendar(Request $request){

        //  Parametros de entrada

        $idCaso = $request->idCaso;
        $perfil = $request->perfil;
        $fechaAgendar = $request->fechaAgendar;
        $usuario = $request->usuario;

        //  Variables iniciales

        $usuarioSolicita = "";
        $usuarioAprueba = "";
        $descripcion = " El ".$perfil." desea una reunión para el día ".$fechaAgendar;
        $idPeticion = "0";

        //  Consultar información del caso

        $sqlString = "
            SELECT
                A.*
            FROM
                abogline_casos A
            WHERE
                id = '".$idCaso."'
        ";

        $sql = DB::select($sqlString);

        foreach($sql as $result){

            // Validar quien solicita y quien aprueba

            if($perfil == "cliente"){

                $usuarioSolicita = $result->usuario;
                $usuarioAprueba = $result->abogado;

            }else{

                $usuarioSolicita = $result->abogado;
                $usuarioAprueba = $result->usuario;

            }

        }

        //  Registrar petición

        $sqlString = "
            INSERT INTO abogline_peticiones VALUES (
                '0',
                '".$idCaso."',
                '".$usuarioSolicita."',
                '".$usuarioAprueba."',
                'agendar',
                'pendiente',
                now(),
                now(),
                '".$descripcion."'
            )
        ";

        DB::insert($sqlString);

        //  Consultar id de la peticion registrada

        $sqlString = "
            SELECT
                MAX(id) AS id
            FROM
                abogline_peticiones
        ";

        $sql = DB::select($sqlString);

        foreach($sql as $result)
            $idPeticion = $result->id;

        //  Registrar agenda

        $sqlString = "
            INSERT INTO abogline_agendas VALUES (
                '0',
                '".$usuario."',
                now(),
                now(),
                'pendiente',
                '".$idCaso."',
                '".$fechaAgendar."',
                '".$idPeticion."'
            )
        ";

        DB::insert($sqlString);

    }

}