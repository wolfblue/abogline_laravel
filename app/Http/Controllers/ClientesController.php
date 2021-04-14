<?php

namespace App\Http\Controllers;

use App\abogados;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ClientesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return clientes::all();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        return clientes::create($request->all());
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\clientes  $clientes
     * @return \Illuminate\Http\Response
     */
    public function show(clientes $clientes)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\clientes  $clientes
     * @return \Illuminate\Http\Response
     */
    public function edit(clientes $clientes)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\clientes  $clientes
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, clientes $clientes)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\clientes  $clientes
     * @return \Illuminate\Http\Response
     */
    public function destroy(clientes $clientes)
    {
        //
    }

    /**
     * Actualizar información del abogado
     */

    public function clientesUpdate(Request $request){

        try{

            //  Variables iniciales

            $email = $request->email;
            $name = $request->name;
            $lastname = $request->lastname;
            $idType = $request->idType;
            $identification = $request->identification;
            $email2 = $request->email2;
            $password = $request->password;
            $phone = $request->phone;

            //  Validar si es nuevo registro o actualización

            $actualizacion = 0;

            $sqlString = "SELECT * FROM clientes WHERE email = '".$email."'";
            $sql = DB::select($sqlString);
        
            foreach($sql as $result)
                $actualizacion = 1;

            if($actualizacion == 0){

                //  Nuevo registro

                $sqlString = "
                    INSERT INTO clientes VALUES (
                        '0',
                        now(),
                        now(),
                        '1',
                        '".$email."',
                        '".$name."',
                        '".$lastname."',
                        '".$idType."',
                        '".$identification."',
                        '".$email2."',
                        '".$phone."'
                    )
                ";

                DB::insert($sqlString);

            }else{

                //  Actualización

                $sqlString = "
                    UPDATE clientes SET
                        updated_at = now(),
                        name = '".$name."',
                        lastname = '".$lastname."',
                        idType = '".$idType."',
                        identification = '".$identification."',
                        email2 = '".$email2."',
                        phone = '".$phone."'
                    WHERE
                        email = '".$email."'
                ";

                DB::update($sqlString);

            }

            //  Actualizar contraseña

            if($password){

                $sqlString = "
                    UPDATE 
                        usuarios
                    SET
                        password = '".$password."'
                    WHERE
                        email = '".$email."'
                    ";

                    DB::update($sqlString);

            }

            return response()->json(true);

        }catch (Exception $e) {}

    }

    /**
     * Obtener datos del cliente
     */

    public function getDataClientes(Request $request){

        try{

            //  Variables iniciales

            $email = $request->email;

            //  Consultar información del cliente

            $sql = DB::select("SELECT * FROM clientes WHERE email = '".$email."'");

            return response()->json($sql);

        }catch (Exception $e) {}

    }

}
