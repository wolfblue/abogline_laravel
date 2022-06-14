<?php

namespace App\Http\Controllers;

use App\Usuarios;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class NotificacionesController extends Controller{

    //  CONSULTAR NOTIFICACIONES

    public function apiConsultarNotificaciones(Request $request){

        //  Parametros de entrada
        $usuario = $request->usuario;

        //  Consultar notificaciones

        $sqlString = "
            SELECT 
                id,
                estado,
                notificacion,
                notificacion_descripcion,
                link,
                link_descripcion,
                variable_session,
                id_calendario
            FROM
                notificaciones
            WHERE
                usuario = '".$usuario."' AND
                estado = '1'
        ";

        $sql = DB::select($sqlString);

        //  Retornar información del usuario
        return response()->json($sql);

    }

    //  APROBAR NOTIFICACIÓN REUNIÓN

    public function apiAprobarNotificacionReunion(Request $request){

        //  Parametros de entrada
        
        $idNotificacion = $request->idNotificacion;
        $idCalendario = $request->idCalendario;

        //  Aprobar calendario

        $sqlString = "
            UPDATE
                calendario
            SET
                estado = '2'
            WHERE
                id = '".$idCalendario."'
        ";

        DB::update($sqlString);

        //  Quitar notificación

        $sqlString = "
            UPDATE
                notificaciones
            SET
                estado = '2'
            WHERE
                id = '".$idNotificacion."'
        ";

        DB::update($sqlString);

    }    

    //  RECHAZAR NOTIFICACIÓN REUNIÓN

    public function apiRechazarNotificacionReunion(Request $request){

        //  Parametros de entrada
        
        $idNotificacion = $request->idNotificacion;
        $idCalendario = $request->idCalendario;

        //  Rechazar calendario

        $sqlString = "
            UPDATE
                calendario
            SET
                estado = '3'
            WHERE
                id = '".$idCalendario."'
        ";

        DB::update($sqlString);

        //  Quitar notificación

        $sqlString = "
            UPDATE
                notificaciones
            SET
                estado = '2'
            WHERE
                id = '".$idNotificacion."'
        ";

        DB::update($sqlString);

    }

    //  ELIMINAR NOTIFICACIÓN

    public function apiEliminarNotificacion(Request $request){

        try{

            //  Parametros de entrada
            $id = $request->id;

            //  Eliminar notificación

            $sqlString = "
                DELETE FROM
                    notificaciones
                WHERE
                    id = '".$id."'
            ";

            DB::delete($sqlString);

        }catch(Exception $e){
            return $e->getMessage();
        }

    }

}