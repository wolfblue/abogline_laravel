<?php

namespace App\Http\Controllers;

use App\Usuarios;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AboglineRegisterCasoController extends Controller{

    /********************************************************************************** */
    // CONSULTAR INFORMACIÓN GENERAL DE LA PÁGINA
    /********************************************************************************** */

    public function apiAboglineRegisterCasoGetInfo(Request $request){

        //  Parametros de entrada
        $idCaso = $request->idCaso;

        //  Variables iniciales

        $response = [];
        $listas = [];
        $caso = [];

        // Consultar listas de selección

        $sqlString = "
            SELECT
                *
            FROM
                abogline_listas
            WHERE
                tipo1 = '1'
        ";

        $listas = DB::select($sqlString);

        //  Consultar información del caso cuando es edición

        if($idCaso != "0"){

            $sqlString = "
                SELECT
                    *
                FROM
                    abogline_casos
                WHERE
                    id = '".$idCaso."'
            ";

            $caso = DB::select($sqlString);

        }

        // Generar respuesta

        array_push(
            $response,
            [
                "listas" => $listas,
                "caso" => $caso
            ]
        );

        //  Retornar usuario
        return response()->json($response);

    }

    /********************************************************************************** */
    // REGISTRAR CASO
    /********************************************************************************** */

    public function apiAboglineRegisterCaso(Request $request){

        // Parametros de entada

        $idCaso = $request->idCaso;
        $usuario = $request->usuario;
        $campo1 = $request->campo1;
        $campo2 = $request->campo2;
        $campo3 = $request->campo3;
        $campo4 = $request->campo4;
        $campo5 = $request->campo5;
        $campo6 = $request->campo6;
        $campo7 = $request->campo7;

        //  Validar campos indefinidos

        if($campo3 == "undefined")
            $campo3 = "";

        if($campo4 == "undefined")
            $campo4 = "0";
        
        if($campo5 == "undefined")
            $campo5 = "";

        if($campo6 == "undefined")
            $campo6 = "";

        if($idCaso == "0"){

            //  Registrar caso

            $sqlString = "
                INSERT INTO abogline_casos VALUES (
                    0,
                    '".$usuario."',
                    now(),
                    now(),
                    'registrado',
                    '".$campo1."',
                    '".$campo2."',
                    '".$campo3."',
                    '".$campo4."',
                    '".$campo5."',
                    '".$campo6."',
                    '".$campo7."',
                    ''
                )
            ";

            DB::insert($sqlString);

        }else{

            //  Actualizar caso

            $sqlString = "
                UPDATE abogline_casos SET
                    actualizado = now(),
                    campo1 = '".$campo1."',
                    campo2 = '".$campo2."',
                    campo3 = '".$campo3."',
                    campo4 = '".$campo4."',
                    campo5 = '".$campo5."',
                    campo6 = '".$campo6."',
                    campo7 = '".$campo7."'
                WHERE
                    id = '".$idCaso."'
            ";

            DB::update($sqlString);

        }

    }

}