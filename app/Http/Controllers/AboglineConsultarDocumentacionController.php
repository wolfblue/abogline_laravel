<?php

namespace App\Http\Controllers;

use App\Usuarios;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AboglineConsultarDocumentacionController extends Controller{

    /********************************************************************************** */
    // CONSULTAR INFORMACIÓN GENERAL DE LA PÁGINA
    /********************************************************************************** */

    public function apiAboglineConsultarDocumentacionGetInfo(Request $request){

        //  Parametros de entrada

        $usuario = $request->usuario;
        $perfil = $request->perfil;

        //  Variables iniciales
        
        $response = [];
        $casos = [];

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

        // Generar respuesta

        array_push(
            $response,
            [
                "casos" => $casos
            ]
        );

        //  Retornar usuario
        return response()->json($response);

    }

    /********************************************************************************** */
    // CONSULTAR DOCUMENTOS
    /********************************************************************************** */

    public function apiAboglineConsultarDocumentacionGetDocumentos(Request $request){

        //  Parametros de entrada

        $idCaso = $request->idCaso;

        //  Variables iniciales
        
        $response = [];
        $documentos = [];

        //  Consultar casos

        $sqlString = "
            SELECT
                *
            FROM
                abogline_documentos
            WHERE
                id_caso = '".$idCaso."'
        ";

        $documentos = DB::select($sqlString);

        // Generar respuesta

        array_push(
            $response,
            [
                "documentos" => $documentos
            ]
        );

        //  Retornar usuario
        return response()->json($response);

    }

}