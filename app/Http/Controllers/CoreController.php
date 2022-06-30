<?php

namespace App\Http\Controllers;

use App\Usuarios;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CoreController extends Controller{

    /********************************************************************************** */
    // REGISTRAR MENSAJE DEL CHAT
    /********************************************************************************** */

    public function apiCoreChatSave(Request $request){

        //  Parametros de entrada
        
        $idCaso = $request->idCaso;
        $usuario = $request->usuario;
        $mensaje = $request->mensaje;

        //  Registrar mensaje

        $sqlString = "
            INSERT INTO chat VALUES (
                '0',
                '".$idCaso."',
                '".$usuario."',
                '".$mensaje."'
            )
        ";

        DB::insert($sqlString);

    }

    /********************************************************************************** */
    // OBTENER MENSAJE DEL CHAT
    /********************************************************************************** */

    public function apiCoreChatGet(Request $request){

        //  Parametros de entrada
        $idCaso = $request->idCaso;

        //  Consultar chat

        $sqlString = "
            SELECT
                *
            FROM
                chat
            WHERE
                id_caso = '".$idCaso."'
        ";

        $sql = DB::select($sqlString);

        //  Retornar casos del usuario
        return response()->json($sql);

    }

    /********************************************************************************** */
    // OBTENER ABOGADO DEL CASO
    /********************************************************************************** */

    public function apiCoreAbogadoGet(Request $request){

        //  Parametros de entrada
        $idCaso = $request->idCaso;

        //  Consultar chat

        $sqlString = "
            SELECT
                casos_usuario.abogado,
                FORMAT(usuarios.consulta, 0) AS consulta,
                usuarios.nombres,
                usuarios.apellidos,
                usuarios.identificacion,
                usuarios.tipo_tp,
                usuarios.tarjeta_licencia,
                usuarios.direccion
            FROM
                casos_usuario,
                usuarios
            WHERE
                casos_usuario.id_caso = '".$idCaso."' AND
                casos_usuario.abogado = usuarios.usuario
        ";

        $sql = DB::select($sqlString);

        //  Retornar casos del usuario
        return response()->json($sql);

    }

    /********************************************************************************** */
    // REGISTRAR EVENTO CALENDARIO
    /********************************************************************************** */

    public function apiCoreCalendarioSave(Request $request){

        //  Parametros de entrada
        
        $idCaso = $request->idCaso;
        $fechaDesde = $request->fechaDesde;
        $fechaHasta = $request->fechaHasta;
        $usuario = $request->usuario;
        $abogado = $request->abogado;

        //  Registrar evento

        $sqlString = "
            INSERT INTO calendario VALUES (
                '0',
                '".$idCaso."',
                '".$fechaDesde."',
                '".$fechaHasta."',
                '1',
                '',
                '".$usuario."',
                '".$abogado."',
                'Asesoría'
            )
        ";

        DB::insert($sqlString);

        //  Insertar notificación al abogado

        $idCalendario = "0";

        $sqlString = "
            SELECT
                MAX(id) AS id
            FROM
                calendario
            WHERE
                id_caso = '".$idCaso."'
        ";

        $sql = DB::select($sqlString);

        foreach($sql as $result)
            $idCalendario = $result->id;

        $abogado = "";

        $sqlString = "
            SELECT
                abogado
            FROM
                casos_usuario
            WHERE
                id_caso = '".$idCaso."' AND
                estado_usuario = 'aceptado' AND
                estado_abogado = 'aceptado'
        ";

        $sql = DB::select($sqlString);

        foreach($sql as $result)
            $abogado = $result->abogado;

        $sqlString = "
            INSERT INTO notificaciones VALUES (
                '0',
                '".$abogado."',
                '1',
                'Solicitud de asesoría',
                'El cliente ha solicitado una asesoría para el caso #".$idCaso." para la siguiente fecha: ".$fechaDesde." - ".$fechaHasta."',
                '',
                '',
                'idCaso',
                '".$idCalendario."',
                '".$idCaso."',
                '2'
            )
        ";

        DB::insert($sqlString);

    }

    /********************************************************************************** */
    // ACTIVAR ACTIVIDAD
    /********************************************************************************** */

    public function apiCoreCrearActividad(Request $request){

        //  Parametros de entrada
        
        $idCaso = $request->idCaso;
        $actividad = $request->actividad;
        $aprobacion = $request->aprobacion;
        $usuario = $request->usuario;
        $actividadDesc = $request->actividadDesc;

        //  Validar aprobación

        if($aprobacion == "0"){

            //  Registrar evento

            $sqlString = "UPDATE casos SET ".$actividad." = 'proceso' WHERE id = '".$idCaso."'";
            DB::update($sqlString);

        }else{

            //  Crear solicitud de aprobación

            $sqlString = "
                INSERT INTO solicitudes VALUES (
                    '0',
                    '".$usuario."',
                    'Crear actividad',
                    '".$actividadDesc."',
                    '1',
                    '".$idCaso."',
                    '0'
                )
            ";

            DB::insert($sqlString);

        }

    }

    //  CONSULTAR ACTIVIDADES

    public function apiCoreConsultarActividades(Request $request){

        //  Parametros de entrada

        $usuario = $request->usuario;
        $idCaso = $request->idCaso;

        //  Consultar actividades del usuario

        $sqlString = "
            SELECT
                *
            FROM
                actividades
            WHERE
                usuario = '".$usuario."' AND
                id_caso = '".$idCaso."'
        ";

        $sql = DB::select($sqlString);

        //  Retornar casos del usuario
        return response()->json($sql);       

    }

    //  GENERAR LINK DE PAGOS

    public function apiCoreGenerarTokenPagos(Request $request){

        //  Generar token

        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => "https://apify.epayco.co/login/mail",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_POSTFIELDS => "",
            CURLOPT_HTTPHEADER => array(
                "Authorization: Basic aW5mb2Fib2dhZG9zLmZhY3R1cmFjaW9uQGdtYWlsLmNvbTpBYm9nYWRvczIwMjIq",
                "Content-Type: application/json",
                "Postman-Token: e4c7aa07-067f-4a42-ae36-6cc05f853aab",
                "cache-control: no-cache",
                "public_key: 6e52effbd61ab02ad221e621310d7155"
            ),
        ));

        $response = curl_exec($curl);
        $responseData = json_decode($response,true);
        $token = $responseData["token"];
        
        return response()->json($token);    

    }

    //  GENERAR LINK DE PAGOS

    public function apiCoreGenerarLinkPagos(Request $request){

        //  Parametros de entrada

        $pagoValor = $request->pagoValor;
        $pagoTitulo = $request->pagoTitulo;
        $pagoDescripcion = $request->pagoDescripcion;
        $token = $request->token;

        //  Generar link de pagos

        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => "https://apify.epayco.co/collection/link/create",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_POSTFIELDS => "{\n  \"quantity\": 1,\n  \"onePayment\":true,\n  \"amount\": \"$pagoValor\",\n  \"currency\": \"COP\",\n  \"id\": 0,\n  \"base\": \"0\",\n  \"description\": \"$pagoDescripcion\",\n  \"title\": \"$pagoTitulo\",\n  \"typeSell\": \"1\",\n  \"tax\": \"0\",\n  \"email\": \"infoabogados.facturacion@gmail.com\"\n}",
            CURLOPT_HTTPHEADER => array(
                "Authorization: Bearer ,Bearer $token",
                "Content-Type: application/json",
                "Postman-Token: a27ca10f-3c0c-44a4-8497-0d3b864af80d",
                "cache-control: no-cache"
            ),
        ));

        $response = curl_exec($curl);
        
        return response()->json($response);

    }

}