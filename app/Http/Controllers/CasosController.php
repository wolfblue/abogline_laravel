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
                    '1'
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
                    cuentanos = '".$cuentanos."'
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

        //  Condiciones

        $where = "";

        if($usuario)
            $where .= " AND usuario = '".$usuario."'";

        if($trataCaso)
            $where .= " AND trata_caso = '".$trataCaso."'";

        if($cualProblema)
            $where .= " AND cual_problema = '".$cualProblema."'";

        if($id)
            $where .= " AND id = '".$id."'";

        //  Consultar casos

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
                ) AS estado
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

}