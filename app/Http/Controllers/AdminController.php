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
    // CONSULTAR GENEROS
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

    /********************************************************************************** */
    // REGISTRAR MUNICIPIO
    /********************************************************************************** */

    public function apiAdminMunicipioRegister(Request $request){

        //  Parametro de entrada
        $municipio = $request->municipio;

        //  Eliminar municipio si existe

        $sqlString = "DELETE FROM municipios WHERE municipio = '".$municipio."'";
        DB::delete($sqlString);

        //  Registrar municipio

        $sqlString = "INSERT INTO municipios VALUES ('".$municipio."')";
        DB::insert($sqlString);

    }

    /********************************************************************************** */
    // CONSULTAR MUNICIPIOS
    /********************************************************************************** */

    public function apiAdminMunicipioGet(Request $request){

        //  Consultar municipios

        $sqlString = "SELECT * FROM municipios ORDER BY 1";
        $sql = DB::select($sqlString);

        //  Retornar municipios
        return response()->json($sql);

    }

    /********************************************************************************** */
    // ELIMINAR MUNICIPIO
    /********************************************************************************** */

    public function apiAdminMunicipioDelete(Request $request){

        //  Parametros de entrada
        $municipio = $request->municipio;

        //  Eliminar municipio

        $sqlString = "DELETE FROM municipios WHERE municipio = '".$municipio."'";
        DB::delete($sqlString);

    }

    /********************************************************************************** */
    // ACTUALIZAR ADMIN
    /********************************************************************************** */

    public function apiAdminUpdate(Request $request){

        //  Parametros de entrada

        $tipo = $request->tipo;
        $contenido = $request->contenido;

        //  Actualizar acerca de
        
        $sqlString = "DELETE FROM admin WHERE tipo = '".$tipo."'";
        DB::delete($sqlString);

        $sqlString = "INSERT INTO admin VALUES ('".$tipo."','".$contenido."')";
        DB::insert($sqlString);

    }

    /********************************************************************************** */
    // CONSULTAR ADMINISTRACIÓN
    /********************************************************************************** */

    public function apiAdminGetContenido(Request $request){

        //  Parametros de entrada
        $tipo = $request->tipo;

        //  Consultar contenido

        $sqlString = "SELECT * FROM admin WHERE tipo = '".$tipo."'";
        $sql = DB::select($sqlString);

        //  Retornar municipios
        return response()->json($sql);

    }

    /********************************************************************************** */
    // REGISTRAR TITULO
    /********************************************************************************** */

    public function apiAdminTituloRegister(Request $request){

        //  Parametro de entrada
        $titulo = $request->titulo;

        //  Eliminar titulo si existe

        $sqlString = "DELETE FROM titulos_hv WHERE titulo = '".$titulo."'";
        DB::delete($sqlString);

        //  Registrar titulo

        $sqlString = "INSERT INTO titulos_hv VALUES ('".$titulo."')";
        DB::insert($sqlString);

    }

    /********************************************************************************** */
    // CONSULTAR TITULOS
    /********************************************************************************** */

    public function apiAdminTituloGet(Request $request){

        //  Consultar titulos

        $sqlString = "SELECT * FROM titulos_hv ORDER BY 1";
        $sql = DB::select($sqlString);

        //  Retornar titulos
        return response()->json($sql);

    }

    /********************************************************************************** */
    // ELIMINAR TITULO
    /********************************************************************************** */

    public function apiAdminTituloDelete(Request $request){

        //  Parametros de entrada
        $titulo = $request->titulo;

        //  Eliminar titulo

        $sqlString = "DELETE FROM titulos_hv WHERE titulo = '".$titulo."'";
        DB::delete($sqlString);

    }

    /********************************************************************************** */
    // CONSULTAR USUARIOS
    /********************************************************************************** */

    public function apiAdminGetUsuarios(Request $request){

        //  Consultar usuarios

        $sqlString = "SELECT * FROM usuarios ORDER BY usuario";
        $sql = DB::select($sqlString);

        //  Retornar titulos
        return response()->json($sql);

    }

    /********************************************************************************** */
    // CONSULTAR DOCUMENTOS DE UN USUARIO
    /********************************************************************************** */

    public function apiAdminGetDocumentosUser(Request $request){

        //  Parametros de entrada
        $usuario = $request->usuario;

        //  Consultar usuarios

        $sqlString = "SELECT * FROM usuarios_documentos WHERE usuario = '".$usuario."' ORDER BY tipo";
        $sql = DB::select($sqlString);

        //  Retornar titulos
        return response()->json($sql);

    }

    /********************************************************************************** */
    // APROBAR ABOGADO
    /********************************************************************************** */

    public function apiAdminAprobarAbogado(Request $request){

        //  Parametros de entrada
        $usuario = $request->usuario;

        //  Consultar usuarios

        $sqlString = "
            UPDATE 
                usuarios
            SET
                estado = '2'
            WHERE
                usuario = '".$usuario."'
        ";
        DB::update($sqlString);

    }

    /********************************************************************************** */
    // RECHAZAR ABOGADO
    /********************************************************************************** */

    public function apiAdminRechazarAbogado(Request $request){

        //  Parametros de entrada
        $usuario = $request->usuario;

        //  Consultar usuarios

        $sqlString = "
            UPDATE 
                usuarios
            SET
                estado = '3'
            WHERE
                usuario = '".$usuario."'
        ";
        DB::update($sqlString);

    }

}