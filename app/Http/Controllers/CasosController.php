<?php

namespace App\Http\Controllers;

use App\Usuarios;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CasosController extends Controller{

    /**************** */
    // Registrar caso
    /**************** */

    public function apiRegistrarCaso(Request $request){

        //  Parametros de entrada

        $problemas = $request->problemas;
        $trataCaso = $request->trataCaso;
        $cualProblema = $request->cualProblema;
        $proceso = $request->proceso;
        $cuentanos = $request->cuentanos;
        $usuario = $request->usuario;
        $id = $request->id;
        $ciudadProblema = $request->ciudadProblema;

        if(!$id){

            //  Registrar caso

            $sqlString = "
                INSERT INTO casos VALUES (
                    '0',
                    '".$problemas."',
                    '".$trataCaso."',
                    '".$cualProblema."',
                    '".$proceso."',
                    '".$cuentanos."',
                    '".$usuario."',
                    now(),
                    '1',
                    '',
                    '',
                    '',
                    '',
                    '',
                    '',
                    '',
                    '',
                    '',
                    '',
                    '',
                    '',
                    '".$ciudadProblema."'
                )
            ";

            DB::insert($sqlString);

        }else{

            //  Actualizar caso

            $sqlString = "
                UPDATE casos SET 
                    problemas = '".$problemas."',
                    trata_caso = '".$trataCaso."',
                    cual_problema = '".$cualProblema."',
                    proceso = '".$proceso."',
                    cuentanos = '".$cuentanos."',
                    ciudad_problema = '".$ciudadProblema."'
                WHERE
                    id = '".$id."'
            ";

            DB::update($sqlString);

        }

    }

    /****************** */
    //  Consultar casos
    /****************** */

    public function apiConsultarCasos(Request $request){

        //  Parametros de entrada

        $usuario = $request->usuario;
        $trataCaso = $request->trataCaso;
        $cualProblema = $request->cualProblema;
        $id = $request->id;
        $perfil = $request->perfil;
        $ciudadProblema = $request->ciudadProblema;

        //  Condiciones

        $where = "";

        if($usuario && $perfil != "abogado")
            $where .= " AND usuario = '".$usuario."'";

        if($trataCaso)
            $where .= " AND trata_caso = '".$trataCaso."'";

        if($cualProblema)
            $where .= " AND cual_problema = '".$cualProblema."'";

        if($ciudadProblema)
            $where .= " AND ciudad_problema = '".$ciudadProblema."'";

        if($id)
            $where .= " AND id = '".$id."'";

        //  Consultar casos

        if($perfil == "abogado"){

            /*$where .= " AND id IN (
                SELECT
                    id_caso
                FROM
                    casos_usuario
                WHERE
                    abogado = '".$usuario."' AND
                    estado_usuario = 'aceptado' AND
                    estado_abogado = 'aceptado'
            )";*/

        }

        $sqlString = "
            SELECT 
                id,
                problemas,
                trata_caso,
                proceso,
                cuentanos,
                cual_problema,
                (
                    CASE WHEN estado = '1' then 'Registrado' ELSE '' END
                ) AS estado,
                estado AS estado_original,
                paso1_pago_asesoria,
                paso2_asesoria,
                paso3_decision_continuidad,
                paso4_generar_cita,
                paso5_contratacion,
                paso6_firmar_contrato,
                paso7_finalizar_contrato,
                paso8_pagos,
                paso9_reunion_virtual,
                paso10_documentacion,
                paso11_reunion_presencial,
                paso12_informacion,
                usuario,
                ciudad_problema
            FROM 
                casos
            WHERE 
                1=1 ".$where;

        $sql = DB::select($sqlString);

        //  Retornar casos del usuario
        return response()->json($sql);

    }

    /****************** */
    //  Eliminar caso
    /****************** */

    public function apiEliminarCaso(Request $request){

        //  Parametros de entrada
        $id = $request->id;

        //  Eliminar caso

        $sqlString = "DELETE FROM casos WHERE id = '".$id."'";
        DB::delete($sqlString);

    }

    /***************************************************************************** */
    //  Usuario asocia abogado
    /***************************************************************************** */

    public function apiCasosUsuarioAsociarAbogado(Request $request){

        //  Parametros de entada

        $idCaso = $request->idCaso;
        $abogado = $request->abogado;
        $estadoUsuario = $request->estadoUsuario;
        $estadoAbogado = $request->estadoAbogado;

        //  Validar si existe relación

        $existe = 0;

        $sqlString = "
            SELECT
                *
            FROM
                casos_usuario
            WHERE
                id_caso = '".$idCaso."' AND
                abogado = '".$abogado."'
        ";

        $sql = DB::select($sqlString);

        foreach($sql as $result)
            $existe = 1;

        //  Asociar abogado si no existe

        if($existe == 0){

            $sqlString = "
                INSERT INTO casos_usuario VALUES (
                    '".$idCaso."',
                    '".$abogado."',
                    '".$estadoUsuario."',
                    '".$estadoAbogado."'
                )
            ";

            DB::insert($sqlString);

        }else{

            if($estadoUsuario == "aceptado"){

                $sqlString = "
                    UPDATE casos_usuario SET estado_usuario = 'aceptado' WHERE id_caso = '".$idCaso."'
                ";

            }else{

                $sqlString = "
                    UPDATE casos_usuario SET estado_abogado = 'aceptado' WHERE id_caso = '".$idCaso."'
                ";

            }

            DB::update($sqlString);

        }

        //  Actualizar estado del caso

        $estadoUsuario = "pendiente";
        $estadoAbogado = "pendiente";

        $sqlString = "
            SELECT
                *
            FROM
                casos_usuario
            WHERE
                id_caso = '".$idCaso."'
        ";

        $sql = DB::select($sqlString);

        foreach($sql as $result){
            
            $estadoUsuario = $result->estado_usuario;
            $estadoAbogado = $result->estado_abogado;

        }

        if($estadoUsuario == "aceptado" && $estadoAbogado == "aceptado"){

            $sqlString = "UPDATE casos SET estado = '2' WHERE id = '".$idCaso."'";

            DB::update($sqlString);

        }

    }

}