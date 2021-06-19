<?php

//  Registrar usuario: createUser

namespace App\Http\Controllers;

use App\Usuarios;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\ApiNotificaciones;

class UsuariosController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return Usuarios::all();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        return Usuarios::create($request->all());
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Usuarios  $usuarios
     * @return \Illuminate\Http\Response
     */
    public function show(Usuarios $usuarios)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Usuarios  $usuarios
     * @return \Illuminate\Http\Response
     */
    public function edit(Usuarios $usuarios)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Usuarios  $usuarios
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Usuarios $usuarios)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Usuarios  $usuarios
     * @return \Illuminate\Http\Response
     */
    public function destroy(Usuarios $usuarios)
    {
        //
    }

    /**
     * Obtener información de un usuario
     */

    public function getUser(Request $request){

        //  Variables iniciales

        $where = "";
        $user = $request->user;
        $email = $request->email;
        $password = $request->password;
        $login = $request->login;

        //  Validar condiciones

        if($password){
            $where .= "AND password='".$password."'";
        }

        if($login)
            $sql = DB::select("SELECT * FROM usuarios WHERE ( user = '$email' or email = '".$email."' ) ".$where);
        else
            $sql = DB::select("SELECT * FROM usuarios WHERE ( user = '$user' or email = '".$email."' ) ".$where);

        return response()->json($sql);

    }

    /************************************************************************************** */

    /**
     * Registrar usuario
     */

    public function createUser(Request $request){

        //  Parametros de entrada

        $active = $request->active;
        $user = $request->user;
        $email = $request->email;
        $password = $request->password;
        $profile = $request->profile;

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
                usuarios
        ";

        $sql = DB::select($sqlString);

        foreach($sql as $result)
            $id = $result->id;

        //  Registrar usuario

        $sqlString = "
            INSERT INTO usuarios VALUES (
                '".$id."',
                now(),
                now(),
                '".$active."',
                '".$email."',
                '".$user."',
                '".$password."',
                '".$profile."'
            )
        ";

        DB::insert($sqlString);

        //  Notificar al usuario

        $mensaje = "Se ha registrado correctamente, bienvenido a Abogline";
        $tipo = "Autenticación";
        $idCaso = "";

        $apiNotificaciones->createNotificacionFunction(
            $email,
            $mensaje,
            $tipo,
            $idCaso
        );

    }

}
