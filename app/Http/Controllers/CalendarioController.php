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
      $email_aprobacion = $request->email_aprobacion;

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
          '1',
          '".$date_meeting."',
          '',
          '".$id_caso."',
          '".$email_aprobacion."'
        )
      ";

      DB::insert($sqlString);

      //  Consultar id de la reunion

      $idReunion = '0';

      $sqlString = "
        SELECT
          MAX(id) AS id
        FROM
          calendario
      ";

      $sql = DB::select($sqlString);

      foreach($sql as $result)
        $idReunion = $result->id;

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

      $mensaje = "Se ha solicitado reunion con el cliente ".$cliente."  para el caso #".$id_caso." el dia ".$date_meeting." pendiente aprobacion de la reunion";

      $tipo = "Reunion pendiente de aprobacion";

      $apiNotificaciones->createNotificacionFunction(
        $email_abogado,
        $mensaje,
        $tipo,
        $id_caso,
        $idReunion
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

      $mensaje = "Se ha solicitado reunion con el abogado ".$abogado."  para el caso #".$id_caso." el dia ".$date_meeting." pendiente aprobacion de la reunion";

      $tipo = "Reunion pendiente de aprobacion";

      $apiNotificaciones->createNotificacionFunction(
        $email_cliente,
        $mensaje,
        $tipo,
        $id_caso,
        $idReunion
      );

      DB::insert($sqlString);

    }

    /**************************************************************************************** */
    // CONSULTAR SI UN USUARIO TIENE REUNIONES PENDIENTES POR APROBAR
    /**************************************************************************************** */

    public function getReunionesPendientes(Request $request){

      //  Parametros de entrada

      $idReunion = $request->idReunion;
      $email = $request->email;

      //  Consultar si tiene reuniones pendientes por aprobar

      $sqlString = "
          SELECT
              *
          FROM
              calendario
          WHERE
              id = '".$idReunion."' AND
              status = '1' AND
              email_aprobacion = '".$email."'
      ";

      $sql = DB::select($sqlString);

      return response()->json($sql);

    }

    /**************************************************************************************** */
    // APROBAR REUNION
    /**************************************************************************************** */

    public function aprobarReunion(Request $request){

      //  Parametros de entrada
      $idReunion = $request->idReunion;

      //  Aprobar reunión

      $sqlString = "
        UPDATE 
          calendario
        SET
          status = '2'
        WHERE
          id = '".$idReunion."'
      ";

      DB::update($sqlString);

    }

    /********************************************************************************* */
    // CONSULTAR REUNIONES DE UN USUARIO
    /********************************************************************************* */

    public function getReuniones(Request $request){

      //  Parametros de entrada
      $email = $request->email;

      //  Consultar reuniones

      $sqlString = "
        SELECT
          *
        FROM
          calendario
        WHERE
          email_cliente = '".$email."' OR
          email_abogado = '".$email."'
      ";

      $sql = DB::select($sqlString);

      return response()->json($sql);     

    }

}