<?php

namespace App\Http\Controllers;

use App;
use App\Usuarios;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;


class CoreController extends Controller{

    // REGISTRAR MENSAJE DEL CHAT

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

    // OBTENER MENSAJE DEL CHAT

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

    // OBTENER ABOGADO DEL CASO

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
                usuarios.direccion,
                usuarios.ciudad
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

    // REGISTRAR EVENTO CALENDARIO

    public function apiCoreCalendarioSave(Request $request){

        //  Parametros de entrada
        
        $idCaso = $request->idCaso;
        $fechaDesde = $request->fechaDesde;
        $fechaHasta = $request->fechaHasta;
        $usuario = $request->usuario;
        $abogado = $request->abogado;
        $titleReunion = $request->titleReunion;

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
                '".$titleReunion."'
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
                'Solicitud de ".$titleReunion."',
                'El cliente ha solicitado ".$titleReunion." para el caso #".$idCaso." para la siguiente fecha: ".$fechaDesde." - ".$fechaHasta."',
                '',
                '',
                'idCaso',
                '".$idCalendario."',
                '".$idCaso."',
                '3',
                '1'
            )
        ";

        DB::insert($sqlString);

    }

    // ACTIVAR ACTIVIDAD

    public function apiCoreCrearActividad(Request $request){

        //  Parametros de entrada
        
        $idCaso = $request->idCaso;
        $actividad = $request->actividad;
        $aprobacion = $request->aprobacion;
        $usuario = $request->usuario;
        $actividadDesc = $request->actividadDesc;
        $motivo = $request->motivo;

        //  Validar motivo

        if($motivo)
            $actividadDesc .= ", motivo: ".$motivo;

        //  Validar tipo de actividad

        $tipoActividad = "";

        switch($actividad){

            case "paso7_finalizar_contrato":
                $tipoActividad = "6";
            break;

            case "paso8_pagos":
                $tipoActividad = "7";
            break;

            case "paso9_reunion_virtual":
                $tipoActividad = "8";
            break;

            case "paso10_documentacion":
                $tipoActividad = "9";
            break;

            case "paso11_reunion_presencial":
                $tipoActividad = "10";
            break;

            case "paso12_informacion":
                $tipoActividad = "11";
            break;

        }

        //  Consultar cliente

        $cliente = "";
        
        $sqlString = "
            SELECT
                usuario
            FROM
                casos
            WHERE
                id = '".$idCaso."'
        ";

        $sql = DB::select($sqlString);

        foreach($sql as $result)
            $cliente = $result->usuario;
            
        //  Consultar abogado

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

        //  Validar aprobación

        if($aprobacion == "0"){

            //  Registrar actividad

            $sqlString = "
                INSERT INTO actividades VALUES (
                    '0',
                    '".$tipoActividad."',
                    '".$cliente."',
                    '".$idCaso."',
                    now(),
                    '1'
                )
            ";

            DB::insert($sqlString);

            $sqlString = "
                INSERT INTO actividades VALUES (
                    '0',
                    '".$tipoActividad."',
                    '".$abogado."',
                    '".$idCaso."',
                    now(),
                    '1'
                )
            ";

            DB::insert($sqlString);

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
                *,
                DATE_FORMAT(date, '%b %d/%Y') AS date_format
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

        //  Parametros de entrada

        $title = $request->title;
        $description = $request->description;
        $value = $request->value;
        $usuario = $request->usuario;

        //  Generar token

        $client = new \GuzzleHttp\Client([
            'base_uri' => 'https://apify.epayco.co/',
            'headers' => [
                'Content-Type' => 'application/json',
                'public_key' => '6e52effbd61ab02ad221e621310d7155',
                'Authorization' => 'Basic aW5mb2Fib2dhZG9zLmZhY3R1cmFjaW9uQGdtYWlsLmNvbTpBYm9nYWRvczIwMjIq'
            ]
        ]);

        $response = $client->post('login/mail');
        $parsedResponse = \json_decode($response->getBody(), true);
        $token = $parsedResponse['token'];

        $client = new \GuzzleHttp\Client([
            'base_uri' => 'https://apify.epayco.co/',
            'headers' => [
                'Content-Type' => 'application/json',
                'public_key' => '6e52effbd61ab02ad221e621310d7155',
                'Authorization' => 'Bearer '.$token
            ]
        ]);

        $sqlString = "
            SELECT
                email
            FROM
                usuarios
            WHERE
                usuario = '".$usuario."'
        ";

        $sql = DB::select($sqlString);

        foreach($sql as $result)
            $email = $result->email;

        $response = $client->post('collection/link/create',[
            'json' => [
                'quantity' => '1',
                'onePayment' => true,
                'amount' => $value,
                'currency' => 'COP',
                'id' => '0',
                'base' => '0',
                'description' => $description,
                'title' => $title,
                'typeSell' => '1',
                'tax' => '0',
                'email' => $email
            ],
        ]);

        $parsedResponse = \json_decode($response->getBody(), true);
        $data = $parsedResponse['data'];
        $link = $data['routeLink'];
        
        return response()->json($link);

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

    //  FINALIZAR ACTIVIDAD

    public function apiCoreFinalizarActividad(Request $request){

        //  Parametros de entrada

        $idActividad = $request->idActividad;
        $idActividadCrear = $request->idActividadCrear;
        $cliente = $request->cliente;
        $abogado = $request->abogado;
        $idCaso = $request->idCaso;
        $tipo = $request->tipo;

        //  Validar descripción de la actividad

        $descripcionActividad = "";

        switch($idActividadCrear){

            case "1":
                $descripcionActividad = "Pago de asesoría";
            break;

            case "2":
                $descripcionActividad = "Solicitar asesoría";
            break;

            case "3":
                $descripcionActividad = "Decisión de continuidad";
            break;

            case "4":
                $descripcionActividad = "Generar cita contratación";
            break;

            case "5":
                $descripcionActividad = "Contratación";
            break;

        }

        //  Finalizar actividad

        DB::update("
            UPDATE 
                actividades 
            SET 
                estado = '2'
            WHERE 
                tipo = '".$tipo."'
        ");

        //  Crear actividad

        if($idActividadCrear){

            $sqlString = "
                INSERT INTO actividades VALUES (
                    '0',
                    '".$idActividadCrear."',
                    '".$cliente."',
                    '".$idCaso."',
                    now(),
                    '1'
                )
            ";

            DB::insert($sqlString);

            $sqlString = "
                INSERT INTO actividades VALUES (
                    '0',
                    '".$idActividadCrear."',
                    '".$abogado."',
                    '".$idCaso."',
                    now(),
                    '1'
                )
            ";

            DB::insert($sqlString);

            //  Notificar

            $sqlString = "
                INSERT INTO notificaciones VALUES (
                    '0',
                    '".$cliente."',
                    '1',
                    'Actividad registrada ".$descripcionActividad."',
                    'Se ha registrado nueva actividad ".$descripcionActividad." para el caso #".$idCaso."',
                    '',
                    '',
                    '',
                    '0',
                    '".$idCaso."',
                    '1',
                    '1'
                )
            ";

            DB::insert($sqlString);


            $sqlString = "
                INSERT INTO notificaciones VALUES (
                    '0',
                    '".$abogado."',
                    '1',
                    'Actividad registrada ".$descripcionActividad."',
                    'Se ha registrado nueva actividad ".$descripcionActividad." para el caso #".$idCaso."',
                    '',
                    '',
                    '',
                    '0',
                    '".$idCaso."',
                    '1',
                    '1'
                )
            ";

            DB::insert($sqlString);

        }

    }

    //  FINALIZAR CASO

    public function apiCoreFinalizarCaso(Request $request){

        //  Parametros de entrada
        $idCaso = $request->idCaso;

        //  Finalizar caso

        $sqlString = "
            UPDATE
                casos
            SET
                estado = '0'
            WHERE
                id = '".$idCaso."'
        ";

        DB::update($sqlString);

    }

    //  DESICIÓN CONTINUIDAD SI

    public function apiCoreContinuarCaso(Request $request){

        //  Parametros de entrada

        $idCaso = $request->idCaso;
        $cliente = $request->cliente;
        $abogado = $request->abogado;

        //  Actualizar estado del caso

        $sqlString = "
            UPDATE
                casos
            SET
                estado = '3'
            WHERE
                id = '".$idCaso."'
        ";

        DB::update($sqlString);

        //  Actualizar estado actividad desición de continuidad

        $sqlString = "
            UPDATE 
                actividades
            SET
                ESTADO = '2'
            WHERE
                id_caso = '".$idCaso."' AND
                tipo = '3'
        ";

        DB::update($sqlString);

        //  Insertar actividad generar cita al cliente

        $sqlString = "
            INSERT INTO actividades VALUES (
                '0',
                '4',
                '".$cliente."',
                '".$idCaso."',
                now(),
                '1'
            )
        ";

        DB::insert($sqlString);

        //  Insertar actividad generar cita al abogado

        $sqlString = "
            INSERT INTO actividades VALUES (
                '0',
                '4',
                '".$abogado."',
                '".$idCaso."',
                now(),
                '1'
            )
        ";

        DB::insert($sqlString);

        //  Notificar

        $sqlString = "
            INSERT INTO notificaciones VALUES (
                '0',
                '".$cliente."',
                '1',
                'Actividad registrada Generar cita contratación',
                'Se ha registrado nueva actividad Generar cita contratación para el caso #".$idCaso."',
                '',
                '',
                '',
                '0',
                '".$idCaso."',
                '1',
                '1'
            )
        ";

        DB::insert($sqlString);

        $sqlString = "
            INSERT INTO notificaciones VALUES (
                '0',
                '".$abogado."',
                '1',
                'Actividad registrada Generar cita contratación',
                'Se ha registrado nueva actividad Generar cita contratación para el caso #".$idCaso."',
                '',
                '',
                '',
                '0',
                '".$idCaso."',
                '1',
                '1'
            )
        ";

        DB::insert($sqlString);

    }

    //  CERRAR EL CASO COMO DESICIÓN DE CONTINUIDAD

    public function apiCoreCerrarCaso(Request $request){

        //  Parametros de entrada

        $idCaso = $request->idCaso;
        $observacion1 = $request->observacion1;
        $observacion2 = $request->observacion2;
        $observacion3 = $request->observacion3;
        $observacion4 = $request->observacion4;
        $comentario = $request->comentario;
        $cliente = $request->cliente;
        $abogado = $request->abogado;

        //  Cerrar el caso

        $sqlString = "
            UPDATE
                casos
            SET
                estado = '4'
            WHERE
                id = '".$idCaso."'
        ";

        DB::update($sqlString);

        //  Notificar al cliente

        $sqlString = "
            INSERT INTO notificaciones values (
                '0',
                '".$cliente."',
                '1',
                'Caso cerrado #".$idCaso."',
                'Se ha cerrado el caso #".$idCaso." a partir de desición de continuidad',
                '',
                '',
                '',
                '0',
                '".$idCaso."',
                '1',
                '1'
            )
        ";

        DB::insert($sqlString);

        //  Enviar correo electronico cliente

        $sqlString = "
            SELECT
                email
            FROM
                usuarios
            WHERE
                usuario = '".$cliente."'
        ";

        $sql = DB::select($sqlString);

        foreach($sql as $result)
            $email = $result->email;

        $mail = new PHPMailer(true);

        $mail->SMTPDebug = 0;
        $mail->isSMTP();
        $mail->Host = 'smtp.hostinger.com';
        $mail->SMTPAuth = true;
        $mail->Username = 'administrador@abogline.com';
        $mail->Password = '4riK5YuDZy*E$7h';
        $mail->SMTPSecure = 'tls';
        $mail->Port = 587;

        $mail->setFrom('administrador@abogline.com', 'administrador@abogline.com');
        $mail->addAddress($email);

        $mail->isHTML(true);
        $mail->CharSet = 'UTF-8';

        $mail->Subject = "Abogline: Se ha cerrado el caso #".$idCaso." en desición de continuidad";

        $html = "Se ha cerrado el caso #".$idCaso." correctamente por el proceso de desición de continuidad.";

        $mail->Body = $html;

        $mail->send();

        //  Notificar al abogado

        $sqlString = "
            INSERT INTO notificaciones values (
                '0',
                '".$abogado."',
                '1',
                'Caso cerrado #".$idCaso."',
                'El clienteSe ha cerrado el caso #".$idCaso." a partir de desición de continuidad',
                '',
                '',
                '',
                '0',
                '".$idCaso."',
                '1',
                '1'
            )
        ";

        DB::insert($sqlString);

        //  Enviar correo electronico cliente

        $sqlString = "
            SELECT
                email
            FROM
                usuarios
            WHERE
                usuario = '".$abogado."'
        ";

        $sql = DB::select($sqlString);

        foreach($sql as $result)
            $email = $result->email;

        $mail = new PHPMailer(true);

        $mail->SMTPDebug = 0;
        $mail->isSMTP();
        $mail->Host = 'smtp.hostinger.com';
        $mail->SMTPAuth = true;
        $mail->Username = 'administrador@abogline.com';
        $mail->Password = '4riK5YuDZy*E$7h';
        $mail->SMTPSecure = 'tls';
        $mail->Port = 587;

        $mail->setFrom('administrador@abogline.com', 'administrador@abogline.com');
        $mail->addAddress($email);

        $mail->isHTML(true);
        $mail->CharSet = 'UTF-8';

        $mail->Subject = "Abogline: Se ha cerrado el caso #".$idCaso." en desición de continuidad";

        $html = "El cliente ha cerrado el caso #".$idCaso." correctamente por el proceso de desición de continuidad.";

        $mail->Body = $html;

        $mail->send();

        //  Finalizar actividad

        $sqlString = "
            UPDATE 
                actividades
            SET
                ESTADO = '2'
            WHERE
                id_caso = '".$idCaso."' AND
                tipo = '3'
        ";

        DB::update($sqlString);

    }

    //  GUARDAR CONTRATO

    public function apiCoreSaveContrato(Request $request){

        //  Parametros de entrada
        
        $idCaso = $request->idCaso;
        $contratoNombreCliente = $request->contratoNombreCliente;
        $contratoIdentificacionCliente = $request->contratoIdentificacionCliente;
        $contratoLugarCliente = $request->contratoLugarCliente;
        $contratoDireccionCliente = $request->contratoDireccionCliente;
        $contratoNombreAbogado = $request->contratoNombreAbogado;
        $contratoIdentificacionAbogado = $request->contratoIdentificacionAbogado;
        $contratoLugarAbogado = $request->contratoLugarAbogado;
        $contratoDireccionAbogado = $request->contratoDireccionAbogado;
        $objetoContrato = $request->objetoContrato;
        $contratoPrecio = $request->contratoPrecio;
        $contratoMetodoPago = $request->contratoMetodoPago;
        $contratoMetodoPago2 = $request->contratoMetodoPago2;
        $contratoPorcentaje = $request->contratoPorcentaje;
        $clausulaAdicional = $request->clausulaAdicional;
        $contratoLicenciaAbogado = $request->contratoLicenciaAbogado;
        $perfil = $request->perfil;
        $borrador = $request->borrador;

        //  Validar estado por perfil

        switch($perfil){

            case "abogado":

                if($borrador == "1")
                    $estado = "1";
                else
                    $estado = "2";

            break;

            case "administrador":

                $estado = "2";

            break;

            case "cliente":

                if($borrador == "1")
                    $estado = "3";
                else
                    $estado = "4";

            break;

        }

        //  Eliminar contrato
        DB::delete("DELETE FROM contratos WHERE id_caso = '".$idCaso."'");

        //  Insertar contrato

        DB::insert("
            INSERT INTO contratos VALUES (
                '".$idCaso."',
                '".$contratoNombreCliente."',
                '".$contratoIdentificacionCliente."',
                '".$contratoLugarCliente."',
                '".$contratoDireccionCliente."',
                '".$contratoNombreAbogado."',
                '".$contratoIdentificacionAbogado."',
                '".$contratoLugarAbogado."',
                '".$contratoDireccionAbogado."',
                '".$objetoContrato."',
                '".$contratoPrecio."',
                '".$contratoMetodoPago."',
                '".$contratoMetodoPago2."',
                '".$contratoPorcentaje."',
                '".$clausulaAdicional."',
                '".$contratoLicenciaAbogado."',
                '".$estado."',
                ''
            )
        ");

    }

    //  CONSULTAR CONTRATO DEL CASO

    public function apiCoreGetContrato(Request $request){

        //  Parametros de entrada
        $idCaso = $request->idCaso;

        //  Consultar contrato
        
        $sqlString = "
            SELECT
                *
            FROM
                contratos
            WHERE
                id_caso = '".$idCaso."'
        ";

        $sql = DB::select($sqlString);

        //  Retornar respuesta
        return response()->json($sql);

    }

    //  GENERAR CONTRATO

    public function apiCoreGenerarContrato(Request $request){

        //  Parametros de entrada
        $idCaso = $request->idCaso;

        //  Consultar información del contrato

        $sqlString = "
            SELECT
                *
            FROM
                contratos
            WHERE
                id_caso = '".$idCaso."'
        ";

        $sql = DB::select($sqlString);

        foreach($sql as $result){

            $nombreCliente = $result->contrato_nombre_cliente;
            $ciudadCliente = $result->contrato_lugar_cliente;
            $identificacionCliente = $result->contrato_identificacion_cliente;
            $nombreAbogado = $result->contrato_nombre_abogado;
            $identificacionAbogado = $result->contrato_identificacion_abogado;
            $ciudadAbogado = $result->contrato_lugar_abogado;
            $tarjetaAbogado = $result->contrato_licencia_abogado;
            $precioContrato = number_format($result->contrato_precio,0);
            $metodoPago = $result->contrato_metodo_pago;
            $clausulaAdicional = $result->clausula_adicional;

            if($result->contrato_metodo_pago2)
                $metodoPago .= " ".$result->contrato_metodo_pago2;

            if($result->contrato_porcentaje)
                $metodoPago .= " ".$result->contrato_porcentaje."%";

        }

        //  Generar pdf contrato

        $pdf = App::make('dompdf.wrapper')->setPaper("a4", 'portrait');

        $html = "

            <div style='margin:4%;font-size:18px;line-height: 25px;'>
                
                <br/><br/><br/>
                
                <center>
                    <b>CONTRATO DE PRESTACION DE SERVICIOS PROFESIONALES DE ABOGADO</b>
                </center>
                
                <br/><br/><br/>

                <p>
                    De una parte <b><u>$nombreCliente</u></b>, mayor de edad, domiciliado en la ciudad de <b><u>$ciudadCliente</u></b>, 
                    identificado con cédula de ciudadanía número <b><u>$identificacionCliente</u></b> de <b><u>$ciudadCliente</u></b>, 
                    que para efectos de este documento se denominará XXXXXXX, y por otra, <b><u>$nombreAbogado</u></b>, igualmente mayor de edad y domiciliado en la ciudad de <b><u>$ciudadAbogado</u></b>, 
                    identificado con cédula de ciudadanía número <b><u>$identificacionAbogado</u></b> expedida en <b><u>$ciudadAbogado</u></b>, 
                    abogado titulado, en ejercicio, con tarjeta profesional número <b><u>$tarjetaAbogado</u></b> 
                    del Consejo Superior de la Judicatura quien para efectos de este contrato se denominará EL MANDATARIO, 
                    hemos convenido celebrar el presente CONTRATO DE PRESTACIÓN DE SERVICIOS PROFESIONALES, el cual se regula por las cláusulas 
                    que a continuación se indican y por las disposiciones del Código Civil y comercial aplicables a la materia:
                </p>
                
                <br/><br/>
                
                <center>
                    <b>CLAUSULAS</b>
                </center>
                
                <br/><br/>
                
                <p>
                    <b>PRIMERA:</b> 
                    EL Abogado se obliga de manera independiente a: (Sección OBJETO DEL CONTRATO)
                </p>
                
                <br/><br/>
                
                <p>
                    <b>SEGUNDA:</b> 
                    EL MANDANTE cancelará, como contraprestación, por concepto de honorarios la suma de ($$precioContrato), 
                    los cuales serán pagaderos de la siguiente manera:
                </p>
                
                <br/><br/>
                
                <p>$metodoPago</p>
                
                <br/><br/>
                
                <p>
                    <b>TERCERA:</b> 
                    Lorem ipsum dolor sit amet, consectetur adipiscing elit. Morbi tempor diam et nunc hendrerit consectetur. Morbi lacinia ante et lectus volutpat, 
                    a suscipit dolor blandit. Phasellus interdum ac sapien eget blandit. 
                    Phasellus cursus, metus sed facilisis sodales, odio diam euismod arcu, at pellentesque dui augue a massa. 
                    Donec mattis convallis luctus. Pellentesque blandit neque eu enim blandit faucibus. 
                    Suspendisse volutpat nulla id arcu molestie, eget efficitur magna interdum. Morbi at molestie mi, ut lobortis nulla..
                </p>
                
                <br/><br/>
                
                <p>
                    <b>CUARTA:</b> 
                    Lorem ipsum dolor sit amet, consectetur adipiscing elit. Morbi tempor diam et nunc hendrerit consectetur. Morbi lacinia ante et lectus volutpat, 
                    a suscipit dolor blandit. Phasellus interdum ac sapien eget blandit. Phasellus cursus, metus sed facilisis sodales, odio diam euismod arcu, 
                    at pellentesque dui augue a massa. Donec mattis convallis luctus. Pellentesque blandit neque eu enim blandit faucibus. 
                    Suspendisse volutpat nulla id arcu molestie, eget efficitur magna interdum. Morbi at molestie mi, ut lobortis nulla.
                </p>
                
                <br/><br/>
                
                <p>
                    <b>QUINTA:</b> 
                    Lorem ipsum dolor sit amet, consectetur adipiscing elit. Morbi tempor diam et nunc hendrerit consectetur. Morbi lacinia ante et lectus volutpat, 
                    a suscipit dolor blandit. Phasellus interdum ac sapien eget blandit. Phasellus cursus, metus sed facilisis sodales, odio diam euismod arcu, 
                    at pellentesque dui augue a massa. Donec mattis convallis luctus. Pellentesque blandit neque eu enim blandit faucibus. 
                    Suspendisse volutpat nulla id arcu molestie, eget efficitur magna interdum. Morbi at molestie mi, ut lobortis nulla.
                </p>
                
                <br/><br/>
                
                <p>
                    <b>SEXTA:</b>
                    $clausulaAdicional
                </p>
                
                <br/><br/>
                
                <p>
                    <b>SEPTIMA: MERITO EJECUTIVO.</b> 
                    Lorem ipsum dolor sit amet, consectetur adipiscing elit. Morbi tempor diam et nunc hendrerit consectetur. Morbi lacinia ante et lectus volutpat, a suscipit dolor blandit. Phasellus interdum ac sapien eget blandit. Phasellus cursus, metus sed facilisis sodales, odio diam euismod arcu, at pellentesque dui augue a massa. Donec mattis convallis luctus. Pellentesque blandit neque eu enim blandit faucibus. Suspendisse volutpat nulla id arcu molestie, eget efficitur magna interdum. Morbi at molestie mi, ut lobortis nulla.
                </p>
                
                <br/><br/>

                <p>
                    En señal de conformidad se suscribe el presente documento en tres ejemplares del mismo tenor, por las partes intervinientes, el día ".date('d')." del mes de ".date('M')." del año ".date('Y').". 
                </p>
                
                <br/><br/>
                
                <table style='width:100%;font-weight:bold;'>
                    
                    <tr>
                    
                    <td style='width:50%;'>
                    
                        <br/><br/><br/><br/>
                    
                        $nombreCliente<br/>
                        CC $identificacionCliente de $ciudadCliente
                    
                    </td>
                    
                    <td>
                    
                        <br/><br/><br/><br/>
                        
                        $nombreAbogado<br/>
                        CC $identificacionAbogado de $ciudadAbogado <br/>
                        # Tarjeta $tarjetaAbogado
                        
                    </td>
                    
                    </tr>
                    
                    <tr>
                    
                    <td>
                    
                        <br/><br/><br/><br/><br/><br/>
                        
                        DIEGO ANDRES MOLINA ACEVEDO<br>
                        CC 1.023.962.482 DE BOGOTÁ<br>
                        REPRESENTANTE LEGAL<br>
                        INFOABOGADOS SAS (ABOGLINE)
                        
                    </td>
                    
                    <td></td>
                    
                    </tr>
                    
                </table>
        
            </div>
        ";

        $pdf->loadHTML($html);

        $path = public_path('contratos/');
        $fileName =  $idCaso.".pdf" ;

        //  Borrar archivo si existe

        if(file_exists($path . '' . $fileName))
            unlink($path . '' . $fileName);

        $pdf->save($path . '' . $fileName);

    }

    //  CONSULTAR CLIENTE DE UN CASO

    public function apiCoreConsultarCliente(Request $request){

        //  Parametros de entrada
        $idCaso = $request->idCaso;

        //  Consultar cliente del caso

        $sqlString = "
            SELECT 
                *
            FROM
                usuarios
            WHERE
                usuario IN (
                    SELECT
                        usuario
                    FROM
                        casos
                    WHERE
                        id = '".$idCaso."'
                )
        ";

        $sql = DB::select($sqlString);

        //  Retornar respuesta
        return response()->json($sql);

    }

    //  REGISTRAR SOLICITUD DE DOCUMENTOS

    public function apiCoreSolicitarDocumentos(Request $request){

        //  Parametros de entrada

        $idCaso = $request->idCaso;
        $cliente = $request->cliente;
        $abogado = $request->abogado;
        $estado = $request->estado;
        $documentos = $request->documentos;
        $motivo = $request->motivo;

        //  Separar documentos
        $documentosData = explode("|",$documentos);

        //  Registrar solicitud de documentos

        for($i = 0;$i < count($documentosData); $i++){

            $sqlString = "
                INSERT INTO documentos VALUES (
                    '0',
                    '".$idCaso."',
                    '".$cliente."',
                    '".$abogado."',
                    '".$estado."',
                    '".$documentosData[$i]."',
                    '".$motivo."',
                    '',
                    ''
                )
            ";

            DB::insert($sqlString);

        }

        //  Solicitud al administrador

        $sqlString = "
            INSERT INTO solicitudes VALUES (
                '0',
                '".$abogado."',
                'Solicitud de documentos',
                'Se requieren unos documentos para continuar con el caso por el motivo: ".$motivo."',
                '1',
                '".$idCaso."',
                '0'
            )
        ";

        DB::insert($sqlString);

    }

    //  CONSULTAR SOLICITUD DE DOCUMENTOS

    public function apiCoreGetSolicitudDocumentos(Request $request){

        //  Parametros de entrada
        $idCaso = $request->idCaso;

        //  Consultar documentos solicitados

        $sqlString = "
            SELECT
                *
            FROM
                documentos
            WHERE
                id_caso = '".$idCaso."'
        ";

        $sql = DB::select($sqlString);

        //  Retornar respuesta
        return response()->json($sql);

    }

    //  ACTUALIZAR DOCUMENTO SOLICITADO

    public function apiCoreUpdateDocumentoSolicitado(Request $request){

        //  Parametros de entrada
        
        $idDocumento = $request->idDocumento;
        $base64 = $request->base64;

        //  Actualizar documento

        $sqlString = "
            UPDATE
                documentos
            SET
                documento = '".$base64."'
            WHERE
                id = '".$idDocumento."'
        ";

        DB::update($sqlString);

    }

    //  CONFIRMAR CARGA DE DOCUMENTOS

    public function apiCoreConfirmarCargaDocumentos(Request $request){

        //  Parametros de entrada
        $idCaso = $request->idCaso;

        //  Confirmar carga de documentos

        $sqlString = "
            UPDATE
                documentos
            SET
                estado = '3'
            WHERE
                id_caso = '".$idCaso."' AND
                estado = '2'
        ";

        DB::update($sqlString);

    }

}