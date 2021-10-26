<?php

namespace App\Http\Controllers;

use App\Usuarios;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AboglineCargarDocumentacionController extends Controller{

    /********************************************************************************** */
    // CONSULTAR INFORMACIÓN GENERAL DE LA PÁGINA
    /********************************************************************************** */

    public function apiAboglineCargarDocumentacionGetInfo(Request $request){

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
    // CONSULTAR PETICIONES DE DOCUMENTOS
    /********************************************************************************** */

    public function apiAboglineCargarDocumentacionPeticiones(Request $request){

        //  Parametros de entrada
        $idCaso = $request->idCaso;

        //  Variables iniciales
        
        $response = [];
        $peticiones = [];

        //  Consultar peticiones de documentos pendientes

        $sqlString = "
            SELECT
                *
            FROM
                abogline_documentos
            WHERE
                estado = 'pendiente' AND
                id_caso = '".$idCaso."'
        ";

        $peticiones = DB::select($sqlString);

        // Generar respuesta

        array_push(
            $response,
            [
                "peticiones" => $peticiones
            ]
        );

        //  Retornar usuario
        return response()->json($response);

    }

    /********************************************************************************** */
    // REGISTRAR RESPUESTA DE DOCUMENTOS
    /********************************************************************************** */

    public function apiAboglineCargarDocumentacionRespuesta(Request $request){

        //  Parametros de entrada
        
        $idDocumento = $request->idDocumento;
        $respuesta = $request->respuesta;
        $extension = $request->extension;
        $peticion = $request->peticion;

        //  Validar tipo de respuesta

        if($extension){

            //  Archivo

            file_put_contents("docs/".$idDocumento.".".$extension, file_get_contents($respuesta));

            $sqlString = "
                UPDATE 
                    abogline_documentos
                SET
                    estado = 'realizado',
                    respuesta = 'docs/".$idDocumento.".".$extension."'
                WHERE
                    id = '".$idDocumento."'
            ";

            DB::update($sqlString);


        }else{

            //  Texto

            $sqlString = "
                UPDATE 
                    abogline_documentos
                SET
                    estado = 'realizado',
                    respuesta = '".$respuesta."'
                WHERE
                    id = '".$idDocumento."'
            ";

            DB::update($sqlString);

        }

        //  Validar si la petición esta resuelta

        $resuelta = true;

        $sqlString = "
            SELECT
                *
            FROM
                abogline_documentos
            WHERE
                estado = 'pendiente' AND
                id_peticion = '".$peticion."'
        ";

        $sql = DB::select($sqlString);

        foreach($sql as $result)
            $resuelta = false;

        if($resuelta == true){

            $sqlString = "
                UPDATE 
                    abogline_peticiones
                SET
                    estado = 'aprobado'
                WHERE
                    id = '".$peticion."'
            ";

            DB::update($sqlString);

        }

    }

}