<?php

namespace App\Http\Controllers;

use App\Usuarios;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AboglineAbogadosController extends Controller{

    /********************************************************************************** */
    // CONSULTAR INFORMACIÓN GENERAL DE LA PÁGINA
    /********************************************************************************** */

    public function apiAboglineAbogadosGetInfo(Request $request){

        //  Parametros de entrada
        
        $usuario = $request->usuario;
        $perfil = $request->perfil;

        //  Variables iniciales 

        $response = [];
        $abogados = [];
        $casos = [];
        
        //  Consultar abogados

        $sqlString = "
            SELECT
                *
            FROM
                abogline_usuarios
            WHERE
                perfil = 'abogado' AND
                usuario NOT IN (
                    SELECT
                        usuario_aprueba
                    FROM
                        abogline_peticiones
                    WHERE
                        estado = 'pendiente' AND
                        tipo = 'aplicar' AND
                        usuario_solicita = '".$usuario."'
                )
        ";

        $abogados = DB::select($sqlString);

        //  Consultar casos

        $sqlString = "
            SELECT
                *
            FROM
                abogline_casos
            WHERE
                estado NOT IN ('registrado','consulta') AND
                usuario = '".$usuario."'
        ";

        $casos = DB::select($sqlString);

        // Generar respuesta

        array_push(
            $response,
            [
                "abogados" => $abogados,
                "casos" => $casos
            ]
        );

        //  Retornar usuario
        return response()->json($response);

    }

    /********************************************************************************** */
    // CONSULTAR INFORMACIÓN GENERAL DE LA PÁGINA
    /********************************************************************************** */

    public function apiAboglineAbogadosAplicar(Request $request){

        //  Parametros de entrada
        
        $usuario = $request->usuario;
        $idCaso = $request->idCaso;
        $abogado = $request->abogado;

        //  Variables iniciales
        $descripcion = "El cliente desea una consulta para el caso";

        //  Aplicar caso

        $sqlString = "
            INSERT INTO abogline_peticiones VALUES (
                '0',
                '".$idCaso."',
                '".$usuario."',
                '".$abogado."',
                'aplicar',
                'pendiente',
                now(),
                now(),
                '".$descripcion."'
            )
        ";

        DB::insert($sqlString);

    }

}