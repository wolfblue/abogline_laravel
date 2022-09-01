<?php

namespace App\Http\Controllers;

use App\Usuarios;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

class AdminController extends Controller{

    // REGISTRAR CIUDAD

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

    // REGISTRAR CIUDAD

    public function apiAdminCiudadGet(Request $request){

        //  Consultar ciudades

        $sqlString = "SELECT * FROM ciudades ORDER BY 1";
        $sql = DB::select($sqlString);

        //  Retornar ciudades
        return response()->json($sql);

    }

    // ELIMINAR CIUDAD

    public function apiAdminCiudadDelete(Request $request){

        //  Parametros de entrada
        $ciudad = $request->ciudad;

        //  Eliminar ciudad

        $sqlString = "DELETE FROM ciudades WHERE ciudad = '".$ciudad."'";
        DB::delete($sqlString);

    }

    // REGISTRAR GENERO

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

    // CONSULTAR GENEROS

    public function apiAdminGeneroGet(Request $request){

        //  Consultar generos

        $sqlString = "SELECT * FROM generos ORDER BY 1";
        $sql = DB::select($sqlString);

        //  Retornar generos
        return response()->json($sql);

    }

    // ELIMINAR GENERO

    public function apiAdminGeneroDelete(Request $request){

        //  Parametros de entrada
        $genero = $request->genero;

        //  Eliminar genero

        $sqlString = "DELETE FROM generos WHERE genero = '".$genero."'";
        DB::delete($sqlString);

    }

    // REGISTRAR TIPO DE DOCUMENTO

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

    // REGISTRAR TIPO DE DOCUMENTO

    public function apiAdminTipoDocumentoGet(Request $request){

        //  Consultar tipos de documentos

        $sqlString = "SELECT * FROM tipos_documentos ORDER BY 1";
        $sql = DB::select($sqlString);

        //  Retornar tipos_documentos
        return response()->json($sql);

    }

    // ELIMINAR TIPO DE DOCUMENTO

    public function apiAdminTipoDocumentoDelete(Request $request){

        //  Parametros de entrada
        $tipoDocumento = $request->tipoDocumento;

        //  Eliminar tipo de documento

        $sqlString = "DELETE FROM tipos_documentos WHERE tipo_documento = '".$tipoDocumento."'";
        DB::delete($sqlString);

    }

    // REGISTRAR MUNICIPIO

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

    // CONSULTAR MUNICIPIOS

    public function apiAdminMunicipioGet(Request $request){

        //  Consultar municipios

        $sqlString = "SELECT * FROM municipios ORDER BY 1";
        $sql = DB::select($sqlString);

        //  Retornar municipios
        return response()->json($sql);

    }

    // ELIMINAR MUNICIPIO

    public function apiAdminMunicipioDelete(Request $request){

        //  Parametros de entrada
        $municipio = $request->municipio;

        //  Eliminar municipio

        $sqlString = "DELETE FROM municipios WHERE municipio = '".$municipio."'";
        DB::delete($sqlString);

    }

    // ACTUALIZAR ADMIN

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

    // CONSULTAR ADMINISTRACIÓN

    public function apiAdminGetContenido(Request $request){

        //  Parametros de entrada
        $tipo = $request->tipo;

        //  Consultar contenido

        $sqlString = "SELECT * FROM admin WHERE tipo = '".$tipo."'";
        $sql = DB::select($sqlString);

        //  Retornar municipios
        return response()->json($sql);

    }

    // REGISTRAR TITULO

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

    // CONSULTAR TITULOS

    public function apiAdminTituloGet(Request $request){

        //  Consultar titulos

        $sqlString = "SELECT * FROM titulos_hv ORDER BY 1";
        $sql = DB::select($sqlString);

        //  Retornar titulos
        return response()->json($sql);

    }

    // ELIMINAR TITULO

    public function apiAdminTituloDelete(Request $request){

        //  Parametros de entrada
        $titulo = $request->titulo;

        //  Eliminar titulo

        $sqlString = "DELETE FROM titulos_hv WHERE titulo = '".$titulo."'";
        DB::delete($sqlString);

    }

    // CONSULTAR USUARIOS

    public function apiAdminGetUsuarios(Request $request){

        //  Consultar usuarios

        $sqlString = "
            SELECT 
                *,
                FORMAT(consulta, 0) AS consulta_format
            FROM 
                usuarios 
            ORDER BY 
                usuario
        ";

        $sql = DB::select($sqlString);

        //  Retornar titulos
        return response()->json($sql);

    }

    // CONSULTAR DOCUMENTOS DE UN USUARIO

    public function apiAdminGetDocumentosUser(Request $request){

        //  Parametros de entrada
        $usuario = $request->usuario;

        //  Consultar usuarios

        $sqlString = "SELECT * FROM usuarios_documentos WHERE usuario = '".$usuario."' ORDER BY tipo";
        $sql = DB::select($sqlString);

        //  Retornar titulos
        return response()->json($sql);

    }

    // APROBAR ABOGADO

    public function apiAdminAprobarAbogado(Request $request){

        //  Parametros de entrada
        $usuario = $request->usuario;

        //  Aprobar abogado

        $sqlString = "
            UPDATE 
                usuarios
            SET
                estado = '2'
            WHERE
                usuario = '".$usuario."'
        ";

        DB::update($sqlString);

        //  Enviar correo electronico

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

        $mail->Subject = "Abogline: Has sido aprobado, ahora puedes usar nuestros servicios";

        $html = "Se ha verificado la información y ha sido aprobado, ahora puede usar de nuestros servicios de Abogline";

        $mail->Body = $html;

        $mail->send();

        //  Notificación de bienvenida al usuario

        $sqlString = "
            INSERT INTO notificaciones values (
                '0',
                '".$usuario."',
                '1',
                'Has sido aprobado, ahora puedes usar nuestros servicios',
                'Se ha verificado la información y ha sido aprobado, ahora puede usar de nuestros servicios de Abogline',
                '',
                '',
                '',
                '0',
                '0',
                '1',
                '1'
            )
        ";

        DB::insert($sqlString);

    }

    // RECHAZAR ABOGADO

    public function apiAdminRechazarAbogado(Request $request){

        //  Parametros de entrada

        $usuario = $request->usuario;
        $motivo = $request->motivo;

        //  Consultar usuarios

        $sqlString = "
            UPDATE 
                usuarios
            SET
                estado = '3',
                motivo_rechazo = '".$motivo."'
            WHERE
                usuario = '".$usuario."'
        ";

        DB::update($sqlString);

    }

    // BLOQUEAR USUARIO

    public function apiAdminBloquearUsuario(Request $request){

        //  Parametros de entrada
        $usuario = $request->usuario;

        //  Consultar usuarios

        $sqlString = "
            UPDATE 
                usuarios
            SET
                estado = '4'
            WHERE
                usuario = '".$usuario."'
        ";
        DB::update($sqlString);

    }

    // CONSULTAR SOLICITUDES

    public function apiConsultarSolicitudes(Request $request){

        //  Insertar nuevas solicitudes de reunión por generar link

        $sqlString = "
            SELECT
                id,
                id_caso,
                usuario
            FROM
                calendario
            WHERE
                link = '' AND
                estado = '2' AND
                id NOT IN (
                    SELECT
                        id_calendario
                    FROM
                        solicitudes
                )
        ";

        $sql = DB::select($sqlString);

        foreach($sql as $result){

            $idCalendario = $result->id;
            $idCaso = $result->id_caso;
            $usuario = $result->usuario;

            $sqlString = "
                INSERT INTO solicitudes VALUES (
                    '0',
                    '".$usuario."',
                    'Reunion',
                    'Generar link para reunión',
                    '1',
                    '".$idCaso."',
                    '".$idCalendario."'
                )
            ";

            DB::insert($sqlString);

        }

        //  Consultar solicitudes

        $sqlString = "
            SELECT
                solicitudes.id,
                solicitudes.usuario,
                solicitudes.tipo_solicitud,
                solicitudes.solicitud,
                solicitudes.estado,
                solicitudes.id_calendario,
                usuarios.email,
                usuarios.perfil,
                solicitudes.id_caso,
                (
                    select 
                        date_format(fechaDesde,'%d-%m-%Y %r')  
                    from 
                        calendario
                    where
                        id = solicitudes.id_calendario
                ) AS fechaDesde
            FROM
                solicitudes,
                usuarios
            WHERE
                solicitudes.usuario = usuarios.usuario AND
                solicitudes.estado = 1
            ORDER BY
                solicitudes.id DESC
        ";

        $sql = DB::select($sqlString);

        return response()->json($sql);

    }

    // APROBAR SOLICITUD

    public function apiAprobarSolicitud(Request $request){

        //  Parametros de entrada
        $id = $request->id;

        //  Aprobar solicitud
        DB::update("UPDATE solicitudes SET estado = 'Aprobado' WHERE id = '".$id."'");

        //  Validar solicitud

        $sqlString = "
            SELECT
                solicitud,
                id_caso
            FROM
                solicitudes
            WHERE
                id = '".$id."'
        ";

        $sql = DB::select($sqlString);

        foreach($sql as $result){

            $solicitud = $result->solicitud;
            $idCaso = $result->id_caso;

            //  Consultar cliente

            $cliente = "";
            
            $sqlString2 = "
                SELECT
                    usuario
                FROM
                    casos
                WHERE
                    id = '".$idCaso."'
            ";

            $sql2 = DB::select($sqlString2);

            foreach($sql2 as $result2)
                $cliente = $result2->usuario;
                
            //  Consultar abogado

            $abogado = "";

            $sqlString2 = "
                SELECT
                    abogado
                FROM
                    casos_usuario
                WHERE
                    id_caso = '".$idCaso."' AND
                    estado_usuario = 'aceptado' AND
                    estado_abogado = 'aceptado'
            ";

            $sql2 = DB::select($sqlString2);

            foreach($sql2 as $result2)
                $abogado = $result2->abogado;

            switch($solicitud){

                case "Finalizar contrato":

                    //  Registrar actividad

                    $sqlString = "
                        INSERT INTO actividades VALUES (
                            '0',
                            '6',
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
                            '6',
                            '".$abogado."',
                            '".$idCaso."',
                            now(),
                            '1'
                        )
                    ";

                    DB::insert($sqlString);
                
                break;

                case "Pagos":

                    //  Registrar actividad

                    $sqlString = "
                        INSERT INTO actividades VALUES (
                            '0',
                            '7',
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
                            '7',
                            '".$abogado."',
                            '".$idCaso."',
                            now(),
                            '1'
                        )
                    ";

                    DB::insert($sqlString);
                
                break;

            }

        }

    }

    // RECHAZAR SOLICITUD

    public function apiRechazarSolicitud(Request $request){

        //  Parametros de entrada
        $id = $request->id;

        //  Aprobar solicitud
        DB::update("UPDATE solicitudes SET estado = 'Rechazado' WHERE id = '".$id."'");

    }

    // CONSULTAR ADMIN

    public function apiAdminConsulta(Request $request){

        //  Consultar admin

        $sqlString = "
            SELECT
                *
            FROM
                admin
        ";

        $sql = DB::select($sqlString);

        return response()->json($sql);

    }

    //  CONSULTAR CONTRATOS

    public function apiAdminConsultarContratos(Request $request){

        //  Consultar contratos

        $sqlString = "
            SELECT
                *
            FROM
                contratos
            ORDER BY
                id_caso
        ";

        $sql = DB::select($sqlString);

        //  Retornar resultado
        return response()->json($sql);

    }

    //  RECHAZAR CONTRATO AL ABOGADO

    public function apiAdminRechazarContrato(Request $request){

        //  Parametros de entrada

        $idCaso = $request->idCaso;
        $observacion = $request->observacion;

        //  Rechazar contrato al abogado

        $sqlString = "
            UPDATE
                contratos
            SET
                estado = '1',
                observacion = '".$observacion."'
            WHERE
                id_caso = '".$idCaso."'
        ";

        DB::update($sqlString);

        //  Notificar al abogado el rechazo

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
            INSERT INTO notificaciones values (
                '0',
                '".$abogado."',
                '1',
                'Contrato rechazado',
                'Abogline rechazó el contrato del caso #".$idCaso.", por favor validar las observaciones en el seguimiento',
                '',
                '',
                '',
                '0',
                '0',
                '1',
                '1'
            )
        ";

        DB::insert($sqlString);

    }

    //  APROBAR CONTRATO

    public function apiAdminAprobarContrato(Request $request){

        //  Parametros de entrada
        $idCaso = $request->idCaso;

        //  Aprobar contrato al abogado

        $sqlString = "
            UPDATE
                contratos
            SET
                estado = '3'
            WHERE
                id_caso = '".$idCaso."'
        ";

        DB::update($sqlString);

        //  Notificar al abogado

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
            INSERT INTO notificaciones values (
                '0',
                '".$abogado."',
                '1',
                'Contrato aprobado',
                'Abogline aprobó el contrato del caso #".$idCaso.", esta pendiente por parte del cliente completar la información',
                '',
                '',
                '',
                '0',
                '0',
                '1',
                '1'
            )
        ";

        DB::insert($sqlString);

    }

    //  RECHAZAR CONTRATO AL CLIENTE

    public function apiAdminRechazarContratoCliente(Request $request){

        //  Parametros de entrada

        $idCaso = $request->idCaso;
        $observacion = $request->observacion;

        //  Rechazar contrato al abogado

        $sqlString = "
            UPDATE
                contratos
            SET
                estado = '3',
                observacion = '".$observacion."'
            WHERE
                id_caso = '".$idCaso."'
        ";

        DB::update($sqlString);

        //  Notificar al cliente el rechazo

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

        $sqlString = "
            INSERT INTO notificaciones values (
                '0',
                '".$cliente."',
                '1',
                'Contrato rechazado',
                'Abogline rechazó el contrato del caso #".$idCaso.", por favor validar las observaciones en el seguimiento',
                '',
                '',
                '',
                '0',
                '0',
                '1',
                '1'
            )
        ";

        DB::insert($sqlString);

    }

    //  APROBAR CONTRATO CLIENTE

    public function apiAdminAprobarContratoCliente(Request $request){

        //  Parametros de entrada
        $idCaso = $request->idCaso;

        //  Aprobar contrato al abogado

        $sqlString = "
            UPDATE
                contratos
            SET
                estado = '5'
            WHERE
                id_caso = '".$idCaso."'
        ";

        DB::update($sqlString);

        //  Notificar al cliente

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

        $sqlString = "
            INSERT INTO notificaciones values (
                '0',
                '".$cliente."',
                '1',
                'Contrato aprobado',
                'Abogline aprobó el contrato del caso #".$idCaso.", pronto se enviaran los documentos para firmar',
                '',
                '',
                '',
                '0',
                '0',
                '1',
                '1'
            )
        ";

        DB::insert($sqlString);

    }

    //  APROBAR SOLICITUD DE DOCUMENTOS

    public function apiAdminAprobarSolicitudDocumentos(Request $request){

        //  Parametros de entrada
        
        $idSolicitud = $request->idSolicitud;
        $idCaso = $request->idCaso;

        //  Aprobar solicitud del caso

        $sqlString = "
            UPDATE 
                documentos
            SET
                estado = '2'
            WHERE
                id_caso = '".$idCaso."' AND
                estado = '1'
        ";

        DB::update($sqlString);

        //  ACTUALIZAR ESTADO DE LA SOLICITUD

        $sqlString = "
            UPDATE
                solicitudes
            SET
                estado = '0'
            WHERE
                id = '".$idSolicitud."'
        ";

        DB::update($sqlString);

    }

    //  RECHAZAR SOLICITUD DE DOCUMENTOS

    public function apiAdminRechazarSolicitudDocumentos(Request $request){

        //  Parametros de entrada

        $idCaso = $request->idCaso;
        $motivo = $request->motivo;
        $idSolicitud = $request->idSolicitud;

        //  Rechazar solicitud
        
        $sqlString = "
            UPDATE
                documentos
            SET
                estado = '0',
                observacion = '".$motivo."'
            WHERE
                id_caso = '".$idCaso."' AND
                estado = '1'
        ";

        DB::update($sqlString);

        //  Consultar abogado del caso

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

        //  Notificar al abogado

        $sqlString = "
            INSERT INTO notificaciones VALUES (
                '0',
                '".$abogado."',
                '1',
                'Rechazado solicitud de documentos',
                'Se rechazo la solicitud de documentos por motivo: ".$motivo."',
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

        //  Actualizar estado de la solicitud

        $sqlString = "
            UPDATE 
                solicitudes
            SET
                estado = '0'
            WHERE
                id = '".$idSolicitud."'
        ";

        DB::update($sqlString);

    }

}