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
     * @param  \App\notificaciones  $notificaciones
     * @return \Illuminate\Http\Response
     */
    public function show(notificaciones $notificaciones)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\notificaciones  $notificaciones
     * @return \Illuminate\Http\Response
     */
    public function edit(notificaciones $notificaciones)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\notificaciones  $notificaciones
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, notificaciones $notificaciones)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\notificaciones  $notificaciones
     * @return \Illuminate\Http\Response
     */
    public function destroy(notificaciones $notificaciones)
    {
        //
    }

    /************************************************************************** */

    /**
     * Crear una notificación
     */

    public function createNotificacion(Request $request){

        //  Variables iniciales

        $email = $request->email;
        $message = $request->message;

        //  Registrar proceso

        $sqlString = "
            INSERT INTO notificaciones VALUES (
                '0',
                now(),
                now(),
                '1',
                '$email',
                '$message'
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
            $where = " AND active = '1' ";
        
        if($tipo == "2")
            $where = " AND active IN ('1','2')";

        //  Consultar notificaciones del usuario

        $sqlString = "
            SELECT 
                * 
            FROM 
                notificaciones 
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
