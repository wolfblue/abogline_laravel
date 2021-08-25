<?php

namespace App\Http\Controllers;

use App\Usuarios;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AboglineProfileController extends Controller{

    /********************************************************************************** */
    // CONSULTAR INFORMACIÓN GENERAL DE LA PÁGINA
    /********************************************************************************** */

    public function apiAboglineProfileGetInfo(Request $request){

        //  Parametros de entrada
        $usuario = $request->usuario;

        //  Variables iniciales

        $response = [];
        $perfil = [];

        //  Consultar perfil del usuario

        $sqlString = "
            SELECT
                *
            FROM
                abogline_usuarios
            WHERE
                usuario = '".$usuario."'
        ";

        $perfil = DB::select($sqlString);

        // Generar respuesta

        array_push(
            $response,
            [
                "perfil" => $perfil
            ]
        );

        //  Retornar usuario
        return response()->json($response);

    }

    /********************************************************************************** */
    // ACTUALIZAR INFORMACIÓN DEL USUARIO CLIENTE
    /********************************************************************************** */

    public function apiAboglineProfileUpdateUserCliente(Request $request){

        //  Parametros de entrada
        
        $usuario = $request->usuario;
        $nombres = $request->nombres;
        $apellidos = $request->apellidos;
        $tipoIdentificacion = $request->tipoIdentificacion;
        $identificacion = $request->identificacion;
        $email = $request->email;
        $password = $request->password;
        $telefonoContacto = $request->telefonoContacto;

        //  Actualizar información

        $sqlString = "
            UPDATE
                abogline_usuarios
            SET
                nombres = '".$nombres."',
                apellidos = '".$apellidos."',
                tipo_identificacion = '".$tipoIdentificacion."',
                identificacion = '".$identificacion."',
                email = '".$email."',
                telefono_contacto = '".$telefonoContacto."'
            WHERE
                usuario = '".$usuario."'
        ";

        DB::update($sqlString);

        //  Actualizar contraseña

        if($password){

            $sqlString = "
                UPDATE
                    abogline_usuarios
                SET
                    password = '".$password."'
                WHERE
                    usuario = '".$usuario."'
            ";

            DB::update($sqlString); 

        }

    }

    /********************************************************************************** */
    // ACTUALIZAR INFORMACIÓN DEL USUARIO ABOGADO
    /********************************************************************************** */

    public function apiAboglineProfileUpdateUserAbogado(Request $request){

        //  Parametros de entrada
        
        $usuario = $request->usuario;
        $nombres = $request->nombres;
        $apellidos = $request->apellidos;
        $tipoIdentificacion = $request->tipoIdentificacion;
        $identificacion = $request->identificacion;
        $email = $request->email;
        $password = $request->password;
        $telefonoContacto = $request->telefonoContacto;
        $genero = $request->genero;
        $direccion = $request->direccion;
        $abogadoCon = $request->abogadoCon;
        $universidad = $request->universidad;
        $licencia = $request->licencia;
        $experiencia = $request->experiencia;
        $anosExperiencia = $request->anosExperiencia;
        $investigacion = $request->investigacion;
        $ramas = $request->ramas;
        $cualRama = $request->cualRama;
        $costoConsulta = $request->costoConsulta;

        //  Actualizar información

        $sqlString = "
            UPDATE
                abogline_usuarios
            SET
                nombres = '".$nombres."',
                apellidos = '".$apellidos."',
                tipo_identificacion = '".$tipoIdentificacion."',
                identificacion = '".$identificacion."',
                email = '".$email."',
                telefono_contacto = '".$telefonoContacto."',
                genero = '".$genero."',
                direccion = '".$direccion."',
                abogado_con = '".$abogadoCon."',
                universidad = '".$universidad."',
                licencia = '".$licencia."',
                experiencia = '".$experiencia."',
                anos_experiencia = '".$anosExperiencia."',
                investigacion = '".$investigacion."',
                ramas = '".$ramas."',
                cual_rama = '".$cualRama."',
                costo_consulta = '".$costoConsulta."'
            WHERE
                usuario = '".$usuario."'
        ";

        DB::update($sqlString);

        //  Actualizar contraseña

        if($password){

            $sqlString = "
                UPDATE
                    abogline_usuarios
                SET
                    password = '".$password."'
                WHERE
                    usuario = '".$usuario."'
            ";

            DB::update($sqlString); 

        }

    }

}