<?php

namespace App\Http\Controllers;

use App\Usuarios;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CoreController extends Controller{

    /********************************************************************************** */
    // REGISTRAR MENSAJE DEL CHAT
    /********************************************************************************** */

    public function apiCoreChatSave(Request $request){

        //  Parametros de entrada
        
        $idCaso = $request->idCaso;
        $usuario = $request->usuario;
        $mensaje = $request->mensaje;

        //  Registrar mensaje

        $sqlString = "
            INSERT INTO chat VALUES (
                '0',
                '".$idCaso."',
                '".$usuario."',
                '".$mensaje."'
            )
        ";

        DB::insert($sqlString);

    }

    /********************************************************************************** */
    // OBTENER MENSAJE DEL CHAT
    /********************************************************************************** */

    public function apiCoreChatGet(Request $request){

        //  Parametros de entrada
        $idCaso = $request->idCaso;

        //  Consultar chat

        $sqlString = "
            SELECT
                *
            FROM
                chat
            WHERE
                id_caso = '".$idCaso."'
        ";

        $sql = DB::select($sqlString);

        //  Retornar casos del usuario
        return response()->json($sql);

    }

    /********************************************************************************** */
    // OBTENER ABOGADO DEL CASO
    /********************************************************************************** */

    public function apiCoreAbogadoGet(Request $request){

        //  Parametros de entrada
        $idCaso = $request->idCaso;

        //  Consultar chat

        $sqlString = "
            SELECT
                abogado
            FROM
                casos_usuario
            WHERE
                id_caso = '".$idCaso."'
        ";

        $sql = DB::select($sqlString);

        //  Retornar casos del usuario
        return response()->json($sql);

    }

    /********************************************************************************** */
    // REGISTRAR EVENTO CALENDARIO
    /********************************************************************************** */

    public function apiCoreCalendarioSave(Request $request){

        //  Parametros de entrada
        
        $idCaso = $request->idCaso;
        $fechaDesde = $request->fechaDesde;
        $fechaHasta = $request->fechaHasta;

        //  Registrar evento

        $sqlString = "
            INSERT INTO calendario VALUES (
                '0',
                '".$idCaso."',
                '".$fechaDesde."',
                '".$fechaHasta."',
                '1',
                ''
            )
        ";

        DB::insert($sqlString);

    }

}