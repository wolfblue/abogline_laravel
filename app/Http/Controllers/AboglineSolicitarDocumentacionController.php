<?php

namespace App\Http\Controllers;

use App\Usuarios;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AboglineSolicitarDocumentacionController extends Controller{

    /********************************************************************************** */
    // SOLICITAR DOCUMENTACION
    /********************************************************************************** */

    public function apiAboglineSolicitarDocumentacion(Request $request){

        //  Parametros de entrada

        $idCaso = $request->idCaso;
        $tipoInformacion = $request->tipoInformacion;
        $observacion = $request->observacion;
        $usuario = $request->usuario;

        //  Consultar cliente del caso

        $cliente = "";

        $sqlString = "
            SELECT
                usuario
            FROM
                abogline_casos
            WHERE
                id = '".$idCaso."'
        ";

        $sql = DB::select($sqlString);

        foreach($sql as $result)
            $cliente = $result->usuario;
        
        //  Guardar petición

        $sqlString = "
            INSERT INTO abogline_peticiones VALUES (
                '0',
                '".$idCaso."',
                '".$usuario."',
                '".$cliente."',
                'documentos',
                'pendiente',
                now(),
                now(),
                'El abogado esta solicitando documentos para continuar con el caso'
            )
        ";

        DB::insert($sqlString);

        //  Consultar id de la petición

        $idPeticion = "";

        $sqlString = "
            SELECT
                MAX(id) AS id
            FROM
                abogline_peticiones
        ";

        $sql = DB::select($sqlString);

        foreach($sql as $result)
            $idPeticion = $result->id;

        // Guardar solicitud de documentos

        $sqlString = "
            INSERT INTO abogline_documentos VALUES (
                '0',
                '".$usuario."',
                now(),
                now(),
                'pendiente',
                '".$idCaso."',
                '".$tipoInformacion."',
                '".$observacion."',
                '',
                '".$idPeticion."'
            )
        ";

        DB::insert($sqlString);

    }

}