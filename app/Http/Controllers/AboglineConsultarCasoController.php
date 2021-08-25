<?php

namespace App\Http\Controllers;

use App\Usuarios;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AboglineConsultarCasoController extends Controller{

    /********************************************************************************** */
    // CONSULTAR INFORMACIÓN GENERAL DE LA PÁGINA
    /********************************************************************************** */

    public function apiAboglineConsultarCasoGetInfo(Request $request){

        //  Parametros de entrada
        
        $usuario = $request->usuario;
        $perfil = $request->perfil;

        //  Variables iniciales
        
        $response = [];
        $condiciones = "";

        //  Condiciones cliente

        if($perfil == "cliente")
            $condiciones .= "AND A.usuario = '".$usuario."'";

        //  Condiciones abogado

        if($perfil == "abogado"){

            $condiciones .= "
                AND A.id NOT IN (
                    SELECT
                        id_caso||usuario_solicita
                    FROM
                        abogline_peticiones
                    WHERE
                        estado = 'pendiente' AND
                        tipo = 'aplicar' AND
                        usuario_solicita = '".$usuario."'
                )
                AND A.estado = 'registrado'

            ";

        }

        //  Consultar casos

        $sqlString = "
            SELECT
                A.id,
                (SELECT valor FROM abogline_listas WHERE id = A.campo1) AS campo1Desc,
                (SELECT valor FROM abogline_listas WHERE id = A.campo2) AS campo2Desc,
                A.campo3,
                (SELECT valor FROM abogline_listas WHERE id = A.campo4) AS campo4Desc,
                A.campo5,
                A.campo6,
                (SELECT valor FROM abogline_listas WHERE id = A.campo7) AS campo7Desc,
                A.estado,
                A.usuario
            FROM
                abogline_casos A
            WHERE
                1=1 ".$condiciones."
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
    // APLICAR A UN CASO
    /********************************************************************************** */

    public function apiAboglineConsultarCasoAplicarCaso(Request $request){

        //  Parametros de entrada
        
        $idCaso = $request->idCaso;
        $usuarioSolicita = $request->usuarioSolicita;
        $usuarioAprueba = $request->usuarioAprueba;
        $perfil = $request->perfil;

        //  Variables iniciales
        $descripcion = "";

        //  Construir descripción

        if($perfil == "cliente")
            $descripcion = "El cliente desea una consulta para el caso";
        else
            $descripcion = "El abogado desea aplicar para el caso y así proceder a una consulta";

        //  Aplicar caso

        $sqlString = "
            INSERT INTO abogline_peticiones VALUES (
                '0',
                '".$idCaso."',
                '".$usuarioSolicita."',
                '".$usuarioAprueba."',
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