<?php

//  Crear un proceso: createProceso
//  Obtener procesos: getProceso
//  Actualizar proceso: procesosUpdate

namespace App\Http\Controllers;

use App\procesos;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

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

        //  Variables iniciales

        $email = $request->email;
        $idCaso = $request->idCaso;

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

        //  Registrar notificación al abogado

        $sqlString = "
            INSERT INTO notificaciones VALUES (
                '0',
                now(),
                now(),
                '1',
                '$email',
                'Aplicó a un nuevo caso, el número del caso es $idCaso, notificaremos al cliente para una pronta respuesta'
            )
        ";
        
        DB::insert($sqlString);

        //  Registrar notificación al cliente

        $sqlString = "
            INSERT INTO notificaciones VALUES (
                '0',
                now(),
                now(),
                '1',
                '$emailCliente',
                'Un abogado esta interesado de continuar con el caso número $idCaso'
            )
        ";
        
        DB::insert($sqlString);

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

        //  Variables iniciales

        $email = $request->email;
        $idCaso = $request->idCaso;
        $active = $request->active;

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

            $sqlString = "
                INSERT INTO notificaciones VALUES (
                    '0',
                    now(),
                    now(),
                    '1',
                    '$email',
                    'El cliente aceptó continuar con el caso, por favor agendar nueva reunión con el cliente'
                )
            ";

            DB::insert($sqlString);

        }

    }

}
