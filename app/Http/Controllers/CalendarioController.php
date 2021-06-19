<?php

//  Agendar reunión:  agendarReunion

namespace App\Http\Controllers;

use App\casos;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\ApiNotificaciones;

class CalendarioController extends Controller
{

  /************************************************************************** */

    /**
     * Agendar reunión
     */

    public function agendarReunion(Request $request){

      //  Parametros de entrada

      $email_cliente = $request->email_cliente;
      $email_abogado = $request->email_abogado;
      $date_meeting = $request->date_meeting;
      $id_caso = $request->id_caso;

      //  Variables iniciales
      $apiNotificaciones = new NotificacionesController();

      //  Insertar agendamiento

      $sqlString = "
        INSERT INTO calendario VALUES (
          '0',
          now(),
          now(),
          '1',
          '".$email_cliente."',
          '".$email_abogado."',
          '0',
          '".$date_meeting."',
          '',
          '".$id_caso."'
        )
      ";

      DB::insert($sqlString);

      //  Notificar al abogado

      $cliente = "";

      $sqlString = "
        SELECT
          name,
          lastname
        FROM
          clientes
        WHERE
          email = '".$email_cliente."'
      ";

      $sql = DB::select($sqlString);

      foreach($sql as $result)
        $cliente = $result->name." ".$result->lastname;

      $mensaje = "Se ha solicitado reunión con el cliente ".$cliente."  para el caso #".$id_caso." el día ".$date_meeting.", pendiente aprobación de la reunión',
      'Reunión pendiente de aprobación";

      $tipo = "";

      $apiNotificaciones->createNotificacionFunction(
        $email_abogado,
        $mensaje,
        $tipo,
        $id_caso
      );

      //  Notificar al cliente

      $abogado = "";

      $sqlString = "
        SELECT
          fullname
        FROM
          abogados
        WHERE
          email = '".$email_abogado."'
      ";

      $sql = DB::select($sqlString);

      foreach($sql as $result)
        $abogado = $result->fullname;

      $mensaje = "Se ha solicitado reunión con el abogado ".$abogado."  para el caso #".$id_caso." el día ".$date_meeting.", pendiente aprobación de la reunión',
      'Reunión pendiente de aprobación";

      $tipo = "";

      $apiNotificaciones->createNotificacionFunction(
        $email_cliente,
        $mensaje,
        $tipo,
        $id_caso
      );

      DB::insert($sqlString);

    }

}