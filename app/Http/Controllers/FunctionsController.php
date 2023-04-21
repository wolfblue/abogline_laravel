<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

class FunctionsController extends Controller{

    /**
     * Actividades crear
     */

    public function actividadesCrear(
        $tipo = null,
        $usuario = null,
        $idCaso = null,
        $estado = null
    )
    {

        $sqlString = "
            INSERT INTO actividades VALUES (
                '0',
                '$tipo',
                '$usuario',
                '$idCaso',
                now(),
                '$estado'
            )
        ";

        DB::insert($sqlString);

    }

    /**
     * Casos consultar
     */

    public function casosConsultar($idCaso = null)
    {
    
        $sqlString = "SELECT * FROM casos WHERE id = '$idCaso'";
        $sql = DB::select($sqlString);

        return $sql;

    }

    /**
     * Casos actualizar
     */

    public function casosActualizar($idCaso, $set)
    {
        DB::update("UPDATE casos SET $set WHERE id = '$idCaso'");
    }

    /**
     * Casos usuario consultar
     */

    public function casosUsuarioConsultar($idCaso = null)
    {
    
        $sqlString = "SELECT * FROM casos_usuario WHERE id_caso = '$idCaso'";
        $sql = DB::select($sqlString);

        return $sql;

    }

}