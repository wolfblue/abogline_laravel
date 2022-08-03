<?php

namespace App\Http\Controllers;

use App\Usuarios;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

class NotificacionesController extends Controller{

    //  CONSULTAR NOTIFICACIONES

    public function apiConsultarNotificaciones(Request $request){

        //  Parametros de entrada
        $usuario = $request->usuario;

        //  Consultar notificaciones

        $sqlString = "
            SELECT 
                *
            FROM
                notificaciones
            WHERE
                usuario = '".$usuario."' AND
                estado = '1'
            ORDER BY
                id DESC
        ";

        $sql = DB::select($sqlString);

        //  Marcar notificaciones informativas como leídas

        $sqlString = "
            UPDATE
                notificaciones
            SET
                leido = '2'
            WHERE
                tipo_notificacion = '1'
        ";

        DB::update($sqlString);

        //  Retornar información del usuario
        return response()->json($sql);

    }

    //  APROBAR NOTIFICACIÓN REUNIÓN

    public function apiAprobarNotificacionReunion(Request $request){

        //  Parametros de entrada
        
        $idNotificacion = $request->idNotificacion;
        $idCalendario = $request->idCalendario;

        //  Aprobar calendario

        $sqlString = "
            UPDATE
                calendario
            SET
                estado = '2'
            WHERE
                id = '".$idCalendario."'
        ";

        DB::update($sqlString);

        //  Quitar notificación

        $sqlString = "
            UPDATE
                notificaciones
            SET
                estado = '2'
            WHERE
                id = '".$idNotificacion."'
        ";

        DB::update($sqlString);

    }    

    //  RECHAZAR NOTIFICACIÓN REUNIÓN

    public function apiRechazarNotificacionReunion(Request $request){

        //  Parametros de entrada
        
        $idNotificacion = $request->idNotificacion;
        $idCalendario = $request->idCalendario;

        //  Rechazar calendario

        $sqlString = "
            UPDATE
                calendario
            SET
                estado = '3'
            WHERE
                id = '".$idCalendario."'
        ";

        DB::update($sqlString);

        //  Quitar notificación

        $sqlString = "
            UPDATE
                notificaciones
            SET
                estado = '2'
            WHERE
                id = '".$idNotificacion."'
        ";

        DB::update($sqlString);

    }

    //  ELIMINAR NOTIFICACIÓN

    public function apiEliminarNotificacion(Request $request){

        try{

            //  Parametros de entrada
            $id = $request->id;

            //  Eliminar notificación

            $sqlString = "
                DELETE FROM
                    notificaciones
                WHERE
                    id = '".$id."'
            ";

            DB::delete($sqlString);

        }catch(Exception $e){
            return $e->getMessage();
        }

    }

    //  APROBAR NOTIFICACIONES

    public function apiNotificacionesAprobar(Request $request){

        //  Parametros de entrada
        
        $idNotificacion = $request->idNotificacion;
        $tipoNotificacion = $request->tipoNotificacion;
        $idCaso = $request->idCaso;
        $abogado = $request->abogado;
        $idCalendario = $request->idCalendario;

        //  Validar tipo de notifiación

        switch($tipoNotificacion){

            case "2":{

                //  Aprobar solicitud de consulta desde el abogado al cliente
                $this->aprobarSolicitudConsultaCliente($idCaso,$abogado);

            }
            break;

            case "3":{

                //  Aprobar solicitud de reunion
                $this->aprobarSolicitudReunion($idCaso,$abogado,$idCalendario);

            }
            break;

        }

        //  Actualizar notificación

        $sqlString = "
            UPDATE
                notificaciones
            SET
                tipo_notificacion = '1'
            WHERE
                id = '".$idNotificacion."'
        ";

        DB::update($sqlString);

    }

    //  APROBAR SOLICITUD DE CONSULTA DESDE EL ABOGADO AL CLIENTE

    public function aprobarSolicitudConsultaCliente($idCaso,$abogado){

        //  Actualizar relación del caso abogado cliente

        $sqlString = "
            UPDATE
                casos_usuario
            SET
                estado_abogado = 'aceptado'
            WHERE
                id_caso = '".$idCaso."' AND
                abogado = '".$abogado."'
        ";

        DB::update($sqlString);

        //  Actualizar estado del caso

        $sqlString = "
            UPDATE
                casos
            SET
                estado = '2'
            WHERE
                id = '".$idCaso."'
        ";

        DB::update($sqlString);

        //  Enviar notificación al cliente

        $sqlString = "
            SELECT
                usuario,
                email
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

        foreach($sql as $result){

            $cliente = $result->usuario;
            $email = $result->email;

        }

        $sqlString = "
            INSERT INTO notificaciones values (
                '0',
                '".$cliente."',
                '1',
                'Solicitud aceptada para consulta del caso #".$idCaso."',
                'El abogado aceptó la solicitud de consulta para el caso #".$idCaso."',
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

        //  Enviar correo electrónico al cliente

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

        $mail->Subject = "Abogline: Solicitud aceptada para consulta del caso #".$idCaso;

        $html = "El abogado aceptó la solicitud de consulta para el caso #".$idCaso;

        $mail->Body = $html;

        $mail->send();

        //  Registrar actividad de pago de asesoría para el cliente

        $sqlString = "
            INSERT INTO actividades VALUES (
                '0',
                '1',
                '".$cliente."',
                '".$idCaso."',
                now(),
                '1'
            )
        ";

        DB::insert($sqlString);

    }

    //  APROBAR SOLICITUD DE REUNIÓN

    public function aprobarSolicitudReunion($idCaso,$abogado,$idCalendario){

        //  Obtener cliente

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

        //  Notificar al cliente

        $sqlString = "
            INSERT INTO notificaciones values (
                '0',
                '".$cliente."',
                '1',
                'Solicitud aceptada para reunión del caso #".$idCaso."',
                'El abogado aceptó la solicitud de reunión para el caso #".$idCaso."',
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

        //  Aprobar calendario

        $sqlString = "
            UPDATE
                calendario
            SET
                estado = '2'
            WHERE
                id = '".$idCalendario."'
        ";

        DB::update($sqlString);

        //  Validar actividad actual del caso

        $tipoActividad = "2";

        $sqlString = "
            SELECT
                MAX(tipo) AS tipo
            FROM
                actividades
            WHERE
                id_caso = '".$idCaso."'
        ";

        $sql = DB::select($sqlString);

        foreach($sql as $result)
            $tipoActividad = $result->tipo;

        switch($tipoActividad){

            case "2":

                //  Crear actividad desición de continuidad cliente

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

                $sqlString = "
                    INSERT INTO actividades values (
                        '0',
                        '3',
                        '".$cliente."',
                        '".$idCaso."',
                        now(),
                        '1'
                    )
                ";

                DB::insert($sqlString);
                
                //  Crear actividad desición de continuidad abogado

                $sqlString = "
                    INSERT INTO actividades values (
                        '0',
                        '3',
                        '".$abogado."',
                        '".$idCaso."',
                        now(),
                        '1'
                    )
                ";

                DB::insert($sqlString);

                //  Bloquear solicitar asesoría

                $sqlString = "
                    UPDATE
                        actividades
                    SET
                        estado = '2'
                    WHERE
                        id_caso = '".$idCaso."' AND
                        tipo = '2'
                ";

                DB::update($sqlString);

            break;

            case "4":

                //  Insertar actividad generar cita al cliente

                $sqlString = "
                    INSERT INTO actividades VALUES (
                        '0',
                        '5',
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
                        '5',
                        '".$abogado."',
                        '".$idCaso."',
                        now(),
                        '1'
                    )
                ";

                DB::insert($sqlString);

                //  Bloquear generar cita

                $sqlString = "
                    UPDATE
                        actividades
                    SET
                        estado = '2'
                    WHERE
                        id_caso = '".$idCaso."' AND
                        tipo = '4'
                ";

                DB::update($sqlString);

            break;

        }

    }

}