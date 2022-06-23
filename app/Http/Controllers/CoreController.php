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
                casos_usuario.abogado,
                usuarios.consulta,
                usuarios.nombres,
                usuarios.apellidos,
                usuarios.identificacion,
                usuarios.tipo_tp,
                usuarios.tarjeta_licencia,
                usuarios.direccion
            FROM
                casos_usuario,
                usuarios
            WHERE
                casos_usuario.id_caso = '".$idCaso."' AND
                casos_usuario.abogado = usuarios.usuario
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
        $usuario = $request->usuario;
        $abogado = $request->abogado;

        //  Registrar evento

        $sqlString = "
            INSERT INTO calendario VALUES (
                '0',
                '".$idCaso."',
                '".$fechaDesde."',
                '".$fechaHasta."',
                '1',
                '',
                '".$usuario."',
                '".$abogado."',
                'Asesoría'
            )
        ";

        DB::insert($sqlString);

        //  Insertar notificación al abogado

        $idCalendario = "0";

        $sqlString = "
            SELECT
                MAX(id) AS id
            FROM
                calendario
            WHERE
                id_caso = '".$idCaso."'
        ";

        $sql = DB::select($sqlString);

        foreach($sql as $result)
            $idCalendario = $result->id;

        $abogado = "";

        $sqlString = "
            SELECT
                abogado
            FROM
                casos_usuario
            WHERE
                id_caso = '".$idCaso."' AND
                estado_usuario = 'aceptado' AND
                estado_abogado = 'aceptado'
        ";

        $sql = DB::select($sqlString);

        foreach($sql as $result)
            $abogado = $result->abogado;

        $sqlString = "
            INSERT INTO notificaciones VALUES (
                '0',
                '".$abogado."',
                '1',
                'Solicitud de asesoría',
                'El cliente ha solicitado una asesoría para el caso #".$idCaso." para la siguiente fecha: ".$fechaDesde." - ".$fechaHasta."',
                '',
                '',
                'idCaso',
                '".$idCalendario."',
                '".$idCaso."',
                '2'
            )
        ";

        DB::insert($sqlString);

    }

    /********************************************************************************** */
    // ACTIVAR ACTIVIDAD
    /********************************************************************************** */

    public function apiCoreCrearActividad(Request $request){

        //  Parametros de entrada
        
        $idCaso = $request->idCaso;
        $actividad = $request->actividad;
        $aprobacion = $request->aprobacion;
        $usuario = $request->usuario;
        $actividadDesc = $request->actividadDesc;

        //  Validar aprobación

        if($aprobacion == "0"){

            //  Registrar evento

            $sqlString = "UPDATE casos SET ".$actividad." = 'proceso' WHERE id = '".$idCaso."'";
            DB::update($sqlString);

        }else{

            //  Crear solicitud de aprobación

            $sqlString = "
                INSERT INTO solicitudes VALUES (
                    '0',
                    '".$usuario."',
                    'Crear actividad',
                    '".$actividadDesc."',
                    '1',
                    '".$idCaso."',
                    '0'
                )
            ";

            DB::insert($sqlString);

        }

    }

}