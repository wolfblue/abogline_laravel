<?php

//  Crear un proceso: createProceso
//  Obtener procesos: getProceso
//  Actualizar proceso: procesosUpdate
//  Solicitar consulta: solicitarConsulta

namespace App\Http\Controllers;

use App\procesos;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\ApiNotificaciones;

class ProcesosController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\procesos  $procesos
     * @return \Illuminate\Http\Response
     */
    public function show(procesos $procesos)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\procesos  $procesos
     * @return \Illuminate\Http\Response
     */
    public function edit(procesos $procesos)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\procesos  $procesos
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, procesos $procesos)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\procesos  $procesos
     * @return \Illuminate\Http\Response
     */
    public function destroy(procesos $procesos)
    {
        //
    }

    /************************************************************************** */

    /**
     * Crear un proceso
     */

    public function createProceso(Request $request){

        //  Parametros de entrada

        $email = $request->email;
        $idCaso = $request->idCaso;

        //  Variables iniciales
        $apiNotificaciones = new NotificacionesController();

        //  Consultar siguiente consecutivo

        $sqlString = "
            SELECT
                CASE WHEN MAX(id) IS NULL THEN
                    1
                ELSE
                    MAX(id)+1
                END AS id
            FROM
                procesos
        ";

        $sql = DB::select($sqlString);

        foreach($sql as $result)
            $id = $result->id;

        //  Registrar proceso

        $sqlString = "
            INSERT INTO procesos VALUES (
                '".$id."',
                now(),
                now(),
                '1',
                '$email',
                '$idCaso'
            )
        ";
        
        DB::insert($sqlString);

        //  Consultar id del proceso generado

        $idProceso = "0";

        $sqlString = "SELECT max(id) AS id FROM procesos";
        $sql = DB::select($sqlString);

        foreach($sql as $result)
            $idProceso = $result->id;

        //  Consultar email del cliente del proceso generado

        $emailCliente = "";

        $sqlString = "SELECT email FROM casos WHERE id = '$idCaso'";
        $sql = DB::select($sqlString);

        foreach($sql as $result)
            $emailCliente = $result->email;

        //  Notificar al abogado

        $mensaje = "Aplicó a un nuevo caso, el número del caso es ".$idCaso.", notificaremos al cliente para una pronta respuesta";
        $tipo = "Aplicar a un caso";

        $apiNotificaciones->createNotificacionFunction(
            $email,
            $mensaje,
            $tipo,
            $idCaso
        );

        //  Notificar al cliente

        $mensaje = "Un abogado esta interesado de continuar con el caso número".$idCaso;
        $tipo = "Abogado en espera de aceptación";

        $apiNotificaciones->createNotificacionFunction(
            $emailCliente,
            $mensaje,
            $tipo,
            $idCaso
        );

    }

    /************************************************************************** */

    /**
     * Obtener procesos
     */

    public function getProceso(Request $request){

        //  Variables iniciales

        $where = "";
        $email = $request->email;
        $idCaso = $request->idCaso;

        //  Validar condiciones

        if($email)
            $where .= " AND email = '$email'";

        if($idCaso)
            $where .= " AND id_caso = '$idCaso'";

        //  Consultar procesos

        $sql = DB::select("SELECT * FROM procesos WHERE 1 = 1 $where");

        return response()->json($sql);

    }

    /************************************************************************** */

    /**
     * Actualizar proceso
     */

    public function procesosUpdate(Request $request){

        //  Parametros de entrada

        $email = $request->email;
        $idCaso = $request->idCaso;
        $active = $request->active;

        //  Variables iniciales
        $apiNotificaciones = new NotificacionesController();

        $sqlString = "
            UPDATE
                procesos
            SET
                active = '$active'
            WHERE
                email = '$email' AND
                id_caso = '$idCaso'
            
        ";

        DB::update($sqlString);

        //  Registrar notificación al abogado cuando aceptan el proceso

        if($active == 3){

            //  Notificar al abogado

            $mensaje = "El cliente aceptó continuar con el caso, por favor agendar nueva reunión con el cliente";
            $tipo = "Caso aceptado";

            $apiNotificaciones->createNotificacionFunction(
                $email,
                $mensaje,
                $tipo,
                $idCaso
            );

        }

    }

    /************************************************************************** */

    /**
     * Solicitar consulta
     */

    public function solicitarConsulta(Request $request){

        //  Parametros de entrada

        $idCaso = $request->idCaso;
        $emailCliente = $request->emailCliente;
        $emailAbogado = $request->emailAbogado;
        $emailOrigen = $request->emailOrigen;

        //  Variables iniciales
        $apiNotificaciones = new NotificacionesController();

        //  Validar si ya existe una solicitud

        $new = 0;

        $sqlString = "
            SELECT 
                * 
            FROM 
                procesos 
            WHERE 
                id_caso = '$idCaso' AND  
                email_cliente= '$emailCliente' AND
                email_abogado= '$emailAbogado'
        ";
        $sql = DB::select($sqlString);

        foreach($sql as $result)
            $new = 1;

        //  Registrar proceso si es nuevo

        if($new == 0){

            $sqlString = "
                INSERT INTO procesos VALUES (
                    '0',
                    now(),
                    now(),
                    '1',
                    '".$idCaso."',
                    '".$emailCliente."',
                    '".$emailAbogado."',
                    '0',
                    '".$emailOrigen."'
                )
            ";

            DB::insert($sqlString);

            //  Notificar al cliente

            $abogado = "";

            $sqlString = "
                SELECT
                    fullname
                FROM
                    abogados
                WHERE
                 email = '".$emailAbogado."'
            ";

            $sql = DB::select($sqlString);

            foreach($sql as $result)
                $abogado = $result->fullname;

            $mensaje = "Se ha solicitado una consulta con el abogado ".$abogado." para el caso #".$idCaso;
            $tipo = "Solicitud de consulta para un caso";

            $apiNotificaciones->createNotificacionFunction(
                $emailCliente,
                $mensaje,
                $tipo,
                $idCaso
            );

            //  Notificar al abogado

            $cliente = "";

            $sqlString = "
                SELECT
                    name,
                    lastname
                FROM
                    clientes
                WHERE
                    email = '".$emailCliente."'
            ";

            $sql = DB::select($sqlString);

            foreach($sql as $result)
                $cliente = $result->name." ".$result->lastname;

            $mensaje = "Se ha solicitado una consulta con el cliente ".$cliente." para el caso #".$idCaso;
            $tipo = "Solicitud de consulta para un caso";

            $apiNotificaciones->createNotificacionFunction(
                $emailAbogado,
                $mensaje,
                $tipo,
                $idCaso
            );

        }

        //  Actualizar procesos si ya existe

        if($new == 1){

            $sqlString = "
                UPDATE 
                    procesos 
                SET 
                    status = '1'
                WHERE
                    id_caso = '".$idCaso."'
            ";

            DB::update($sqlString);

            //  Notificar al cliente

            $abogado = "";

            $sqlString = "
                SELECT
                    fullname
                FROM
                    abogados
                WHERE
                    email = '".$emailAbogado."'
            ";

            $sql = DB::select($sqlString);

            foreach($sql as $result)
                $abogado = $result->fullname;

            $mensaje = "El abogado ".$abogado." acepto la consulta para el caso #".$idCaso;
            $tipo = "Aprobación de consulta para un caso";

            $apiNotificaciones->createNotificacionFunction(
                $emailCliente,
                $mensaje,
                $tipo,
                $idCaso
            );

            //  Notificar al abogado

            $cliente = "";

            $sqlString = "
                SELECT
                    name,
                    lastname
                FROM
                    clientes
                WHERE
                    email = '".$emailCliente."'
            ";

            $sql = DB::select($sqlString);

            foreach($sql as $result)
                $cliente = $result->name." ".$result->lastname;

            $mensaje = "El cliente ".$cliente." acepto la consulta para el caso #".$idCaso;
            $tipo = "Aprobación de consulta para un caso";

            $apiNotificaciones->createNotificacionFunction(
                $emailAbogado,
                $mensaje,
                $tipo,
                $idCaso
            );

        }

    }

    /***************************************************************************************** */
    //  RECHAZAR SOLICITUD
    /***************************************************************************************** */

    public function rechazarSolicitud(Request $request){

        //  Parametros de entrada

        $idCaso = $request->idCaso;
        $emailCliente = $request->emailCliente;
        $emailAbogado = $request->emailAbogado;

        //  Variables iniciales
        $apiNotificaciones = new NotificacionesController();

        //  Rechazar solicitud

        $sqlString = "
            UPDATE
                procesos
            SET
                status = '3'
            WHERE
                id_caso = '".$idCaso."' AND
                email_cliente = '".$emailCliente."' AND
                email_abogado = '".$emailAbogado."'
        ";

        DB::update($sqlString);

        //  Notificar al cliente

        $abogado = "";

        $sqlString = "
            SELECT
                fullname
            FROM
                abogados
            WHERE
                email = '".$emailAbogado."'
        ";

        $sql = DB::select($sqlString);

        foreach($sql as $result)
            $abogado = $result->fullname;

        $mensaje = "El abogado ".$abogado." rechazó la solicitud para el caso #".$idCaso;
        $tipo = "Rechazo de solicitud para un caso";

        $apiNotificaciones->createNotificacionFunction(
            $emailCliente,
            $mensaje,
            $tipo,
            $idCaso
        );

        //  Notificar al abogado

        $mensaje = "Se ha rechazado la solicitud para el caso #".$idCaso;
        $tipo = "Rechazo de solicitud para un caso";

        $apiNotificaciones->createNotificacionFunction(
            $emailAbogado,
            $mensaje,
            $tipo,
            $idCaso
        );

    }

}
