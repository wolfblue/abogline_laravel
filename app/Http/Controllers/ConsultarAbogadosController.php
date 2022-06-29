<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

class ConsultarAbogadosController extends Controller{

    /**************** */
    // Registrar caso
    /**************** */

    public function apiConsultarAbogadosEligemeValidar(Request $request){

        //  Parametros de entrada

        $idCaso = $request->idCaso;
        $abogado = $request->abogado;

        //  Consultar casos asociados al abogado y al caso

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

        //  Retornar casos del usuario
        return response()->json($sql);

    }

}