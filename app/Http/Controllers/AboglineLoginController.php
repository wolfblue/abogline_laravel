<?php

namespace App\Http\Controllers;

use App\Usuarios;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AboglineLoginController extends Controller{

    /********************************************************************************** */
    // CONSULTAR USUARIO ACTIVO
    /********************************************************************************** */

    public function apiAboglineLoginConsultarUsuarioActivo(Request $request){

        // Parametros de entrada

        $usuarioEmail = $request->usuarioEmail;
        $password = $request->password;

        // Consultar usuario

        $sqlString = "
            SELECT
                *
            FROM
                abogline_usuarios
            WHERE
                (usuario = '".$usuarioEmail."' OR email = '".$usuarioEmail."') AND
                password = '".$password."' AND
                estado = 'activo'
        ";

        $sql = DB::select($sqlString);

        //  Retornar usuario
        return response()->json($sql);

    }

}