<?php

namespace App\Http\Controllers;

use App\Usuarios;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AdminController extends Controller{

    /********************************************************************************** */
    // REGISTRAR CIUDAD
    /********************************************************************************** */

    public function apiAdminCiudadRegister(Request $request){

        //  Parametro de entrada
        $ciudad = $request->ciudad;

        //  Eliminar ciudad si existe

        $sqlString = "DELETE FROM ciudades WHERE ciudad = '".$ciudad."'";
        DB::delete($sqlString);

        //  Registrar ciudad

        $sqlString = "INSERT INTO ciudades VALUES ('".$ciudad."')";
        DB::insert($sqlString);

    }

    /********************************************************************************** */
    // REGISTRAR CIUDAD
    /********************************************************************************** */

    public function apiAdminCiudadGet(Request $request){

        //  Consultar ciudades

        $sqlString = "SELECT * FROM ciudades ORDER BY 1";
        $sql = DB::select($sqlString);

        //  Retornar ciudades
        return response()->json($sql);

    }

    /********************************************************************************** */
    // ELIMINAR CIUDAD
    /********************************************************************************** */

    public function apiAdminCiudadDelete(Request $request){

        //  Parametros de entrada
        $ciudad = $request->ciudad;

        //  Eliminar ciudad

        $sqlString = "DELETE FROM ciudades WHERE ciudad = '".$ciudad."'";
        DB::delete($sqlString);

    }

    /********************************************************************************** */
    // REGISTRAR GENERO
    /********************************************************************************** */

    public function apiAdminGeneroRegister(Request $request){

        //  Parametro de entrada
        $genero = $request->genero;

        //  Eliminar genero si existe

        $sqlString = "DELETE FROM generos WHERE genero = '".$genero."'";
        DB::delete($sqlString);

        //  Registrar genero

        $sqlString = "INSERT INTO generos VALUES ('".$genero."')";
        DB::insert($sqlString);

    }

    /********************************************************************************** */
    // REGISTRAR GENERO
    /********************************************************************************** */

    public function apiAdminGeneroGet(Request $request){

        //  Consultar generos

        $sqlString = "SELECT * FROM generos ORDER BY 1";
        $sql = DB::select($sqlString);

        //  Retornar generos
        return response()->json($sql);

    }

    /********************************************************************************** */
    // ELIMINAR GENERO
    /********************************************************************************** */

    public function apiAdminGeneroDelete(Request $request){

        //  Parametros de entrada
        $genero = $request->genero;

        //  Eliminar genero

        $sqlString = "DELETE FROM generos WHERE genero = '".$genero."'";
        DB::delete($sqlString);

    }

    /********************************************************************************** */
    // REGISTRAR TIPO DE DOCUMENTO
    /********************************************************************************** */

    public function apiAdminTipoDocumentoRegister(Request $request){

        //  Parametro de entrada
        $tipoDocumento = $request->tipoDocumento;

        //  Eliminar tipo de documento si existe

        $sqlString = "DELETE FROM tipos_documentos WHERE tipo_documento = '".$tipoDocumento."'";
        DB::delete($sqlString);

        //  Registrar tipo de documento

        $sqlString = "INSERT INTO tipos_documentos VALUES ('".$tipoDocumento."')";
        DB::insert($sqlString);

    }

    /********************************************************************************** */
    // REGISTRAR TIPO DE DOCUMENTO
    /********************************************************************************** */

    public function apiAdminTipoDocumentoGet(Request $request){

        //  Consultar tipos de documentos

        $sqlString = "SELECT * FROM tipos_documentos ORDER BY 1";
        $sql = DB::select($sqlString);

        //  Retornar tipos_documentos
        return response()->json($sql);

    }

    /********************************************************************************** */
    // ELIMINAR TIPO DE DOCUMENTO
    /********************************************************************************** */

    public function apiAdminTipoDocumentoDelete(Request $request){

        //  Parametros de entrada
        $tipoDocumento = $request->tipoDocumento;

        //  Eliminar tipo de documento

        $sqlString = "DELETE FROM tipos_documentos WHERE tipo_documento = '".$tipoDocumento."'";
        DB::delete($sqlString);

    }

}