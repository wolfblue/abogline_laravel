<?php

//  Crear una notificación: createNotificacion
//  Consultar notificaciones: getNotificacion
//  Eliminar notificacion: deleteNotificacion
//  Marcar como leído una notificación: notificacionLeido

namespace App\Http\Controllers;

use App\notificaciones;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class NotificacionesController extends Controller
{


    /************************************************************************** */

    /**
     * Crear una notificación API
     * Parametros:
     *  email: Correo electronico del usuario a notificar
     *  message: Mensaje a notificar
     *  tipo: Tipo de notificación
     *  idCaso: Caso asociado a la notificación
     */

    public function createNotificacionAPI(Request $request){

        //  Variables iniciales

        $email = $request->email;
        $message = $request->message;
        $tipo = $request->tipo;
        $idCaso = $request->idCaso;

        //  Registrar notificación

        $sqlString = "
            INSERT INTO notificaciones VALUES (
                '0',
                now(),
                now(),
                '1',
                '".$email."',
                '".$message."',
                '".$tipo."',
                '".$idCaso."'
            )
        ";
        
        DB::insert($sqlString);

    }

    /************************************************************************** */

    /**
     * Crear una notificación función
     * Parametros:
     *  email: Correo electronico del usuario a notificar
     *  message: Mensaje a notificar
     *  tipo: Tipo de notificación
     *  idCaso: Caso asociado a la notificación
     */

    public function createNotificacionFunction(
        
        $email = null,
        $message = null,
        $tipo = null,
        $idCaso = null
        
    ){

        //  Registrar notificación

        $sqlString = "
            INSERT INTO notificaciones VALUES (
                '0',
                now(),
                now(),
                '1',
                '".$email."',
                '".$message."',
                '".$tipo."',
                '".$idCaso."'
            )
        ";
        
        DB::insert($sqlString);

    }

    /************************************************************************** */

    /**
     * Consultar notificaciones
     */

    public function getNotificacion(Request $request){

        //  Variables iniciales

        $email = $request->email;
        $tipo = $request->tipo;
        $where = "";

        //  Validar parametros

        if($tipo == "1")
            $where = " AND notificaciones.active = '1' ";
        
        if($tipo == "2")
            $where = " AND notificaciones.active IN ('1','2')";

        //  Consultar notificaciones del usuario

        $sqlString = "
            SELECT 
                notificaciones.*,
                procesos.status AS estadoProceso
            FROM 
                notificaciones LEFT JOIN procesos ON notificaciones.id_caso = procesos.id_caso
            WHERE 
                email = '".$email."' $where
            ORDER BY updated_at";

        $sql = DB::select($sqlString);

        return response()->json($sql);

    }

    /************************************************************************** */

    /**
     * Eliminar notificación
     */

    public function deleteNotificacion(Request $request){

        //  Variables iniciales
        $id = $request->id;

        //  Consultar notificaciones del usuario
        DB::update("UPDATE notificaciones SET active = '3', updated_at = now() WHERE id = '".$id."'");

    }

    /************************************************************************** */

    /**
     * Marcar como leído una notificación
     */

    public function notificacionLeido(Request $request){

        //  Variables iniciales
        $id = $request->id;

        //  Marcar como leído
        DB::update("UPDATE notificaciones SET active = '2', updated_at = now() WHERE id = '".$id."'");

    }

}
