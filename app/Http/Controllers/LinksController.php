<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

class LinksController extends Controller{

    //  CONSULTAR Y APROBAR USUARIO PENDIENTE POR CORREO

    public function apiLinksAprobarUsuario(Request $request){

        //  Parametros de entrada
        $md5 = $request->md5;

        //  Consultar usuarios con el md5

        $total = 0;

        $sqlString = "
            SELECT
                *
            FROM
                usuarios
            WHERE
                md5 = '".$md5."'
        ";

        $sql = DB::select($sqlString);

        foreach($sql as $result)
            $total += 1;

        //  Actualizar estado de usuario encontrado

        $sqlString = "
            UPDATE
                usuarios
            SET
                estado = '1'
            WHERE
                md5 = '".$md5."'
        ";

        DB::update($sqlString);

        //  Retornar total de usuarios encontrados

        return $total;

    }

    //  RESTABLECER CONTRASEÑA

    public function apiLinksRestablecerPassword(Request $request){

        //  Parametros de entrada

        $md5 = $request->md5;
        $password = $request->password;

        //  Restablecer contraseña

        $sqlString = "
            UPDATE
                usuarios
            SET
                password = '".$password."'
            WHERE
                md5 = '".$md5."'
        ";

        DB::update($sqlString);

    }

}