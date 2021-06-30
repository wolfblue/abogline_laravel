<?php

//  Obtener datos del caso: POST getDataCaso
//  Obtener todos los casos que no se ha enviado solicitud: getCasos
//  Actualizar información de un caso: POST casosUpdate

namespace App\Http\Controllers;

use App\casos;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\ApiNotificaciones;

class CasosController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\casos  $casos
     * @return \Illuminate\Http\Response
     */
    public function show(casos $casos)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\casos  $casos
     * @return \Illuminate\Http\Response
     */
    public function edit(casos $casos)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\casos  $casos
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, casos $casos)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\casos  $casos
     * @return \Illuminate\Http\Response
     */
    public function destroy(casos $casos)
    {
        //
    }

    /**
     * Actualizar información del caso
     */

    public function casosUpdate(Request $request){

        try{

            //  Parametros de entrada

            $id = $request->id;
            $email = $request->email;
            $field1 = $request->field1;
            $field2 = $request->field2;
            $field3 = $request->field3;
            $field4 = $request->field4;
            $field5 = $request->field5;
            $field6 = $request->field6;
            $field7 = $request->field7;

            //  Variables iniciales
            $apiNotificaciones = new NotificacionesController();

            //  Validar si es nuevo registro o actualización

            if($id == 0){

                //  Consultar siguiente consecutivo

                $sqlString = "
                    SELECT
                        CASE WHEN MAX(id) IS NULL THEN
                            1
                        ELSE
                            MAX(id)+1
                        END AS id
                    FROM
                        casos
                ";

                $sql = DB::select($sqlString);

                foreach($sql as $result)
                    $id = $result->id;

                //  Nuevo registro

                $sqlString = "
                    INSERT INTO casos VALUES (
                        '".$id."',
                        now(),
                        now(),
                        '1',
                        '".$email."',
                        '".$field1."',
                        '".$field2."',
                        '".$field3."',
                        '".$field4."',
                        '".$field5."',
                        '".$field6."',
                        '".$field7."'
                    )
                ";

                DB::insert($sqlString);

                //  Notificar al usuario

                $mensaje = "Se ha registrado un nuevo caso con el número #".$id;
                $tipo = "Caso registrado";
                $idCaso = $id;

                $apiNotificaciones->createNotificacionFunction(
                    $email,
                    $mensaje,
                    $tipo,
                    $idCaso,
                    '0'
                );

            }else{

                //  Actualización

                $sqlString = "
                    UPDATE casos SET
                        updated_at = now(),
                        field1 = '".$field1."',
                        field2 = '".$field2."',
                        field3 = '".$field3."',
                        field4 = '".$field4."',
                        field5 = '".$field5."',
                        field6 = '".$field6."',
                        field7 = '".$field7."'
                    WHERE
                        id = '".$id."'
                ";

                DB::update($sqlString);

                //  Notificar al usuario

                $mensaje = "Se actualizó la información del caso #".$id;
                $tipo = "Actualización de caso";
                $idCaso = $id;

                $apiNotificaciones->createNotificacionFunction(
                    $email,
                    $mensaje,
                    $tipo,
                    $idCaso,
                    '0'
                );

            }

            return response()->json(true);

        }catch (Exception $e) {}

    }

    /************************************************************************** */

    /**
     * Obtener datos del caso
     */

    public function getDataCaso(Request $request){

        //  Variables iniciales

        $casos = array();
        $email = $request->email;
        $where = "";

        //  Validar parametros

        if($email)
            $where .= " AND email = '$email'";

        try{

            //  Consultar casos

            $sqlString = "
                SELECT
                    *
                FROM
                    casos
                WHERE
                    active = '1' $where
            ";

            $sql = DB::select($sqlString);

            foreach($sql as $result){

                //  Field 1 descripción

                $field1Desc = "";

                switch($result->field1){
                    
                    case "1":
                        $field1Desc = "El estado (Toda entidad pública u organismo adscrito a los gobiernos nacionales regionales y/o locales)";
                    break;

                    case "2":
                        $field1Desc = "Un particular o empresa privada (Tu jefe, un amigo, tu familia, conocidos, tu jefe y empresas que son privadas)";
                    break;

                }

                //  Field 2 descripción

                $field2Desc = "";

                switch($result->field2){
                    
                    case "1":
                        $field2Desc = "Con tu empleo en una entidad pública";
                    break;

                    case "2":
                        $field2Desc = "Con tus impuestos";
                    break;

                    case "3":
                        $field2Desc = "Con tu negocio";
                    break;

                    case "4":
                        $field2Desc = "Con un amigo o conocido";
                    break;

                    case "5":
                        $field2Desc = "Reparación de daños y perjuicios causados por el estado";
                    break;

                    case "6":
                        $field2Desc = "Tu familia";
                    break;

                    case "7":
                        $field2Desc = "Tu jefe o empresa";
                    break;

                    case "8":
                        $field2Desc = "Vulneración a tu salud";
                    break;

                    case "9":
                        $field2Desc = "Vulneración de derechos fundamentales";
                    break;

                    case "10":
                        $field2Desc = "Vulneración de derechos por el estado";
                    break;

                    case "11":
                        $field2Desc = "Un delito o temas con policía";
                    break;

                    case "12":
                        $field2Desc = "Otro";
                    break;

                }

                //  Field 4 descripción

                $field4Desc = "";

                $field4Data = explode(",",$result->field4);

                for($i = 0; $i < count($field4Data); $i++){

                    switch($field4Data[$i]){
                    
                        case "1":

                            if(!$field4Desc)
                                $field4Desc = "Acoso laboral";
                            else
                                $field4Desc .= ", Acoso laboral";

                        break;
    
                        case "2":

                            if(!$field4Desc)
                                $field4Desc = "Alimentos a hijos o conyugues";
                            else
                                $field4Desc .= ", Alimentos a hijos o conyugues";

                        break;
    
                        case "3":

                            if(!$field4Desc)
                                $field4Desc = "Capitulaciones";
                            else
                                $field4Desc .= ", Capitulaciones";

                        break;
    
                        case "4":

                            if(!$field4Desc)
                                $field4Desc = "Con tu EPS";
                            else
                                $field4Desc .= ", Con tu EPS";

                        break;
    
                        case "5":

                            if(!$field4Desc)
                                $field4Desc = "Contratos";
                            else
                                $field4Desc .= ", Contratos";
                            
                        break;
    
                        case "6":

                            if(!$field4Desc)
                                $field4Desc = "Declaración de renta";
                            else
                                $field4Desc .= ", Declaración de renta";

                        break;
    
                        case "7":

                            if(!$field4Desc)
                                $field4Desc = "Deterioro de tu salud a causa de la responsabilidad médica";
                            else
                                $field4Desc .= ", Deterioro de tu salud a causa de la responsabilidad médica";

                        break;
    
                        case "8":

                            if(!$field4Desc)
                                $field4Desc = "Despido sin justa causa";
                            else
                                $field4Desc .= ", Despido sin justa causa";

                        break;
    
                        case "9":
                            
                            if(!$field4Desc)
                                $field4Desc = "Dineros adeudados";
                            else
                                $field4Desc .= ", Dineros adeudados";

                        break;
    
                        case "10":
                            
                            if(!$field4Desc)
                                $field4Desc = "Divorcios";
                            else
                                $field4Desc .= ", Divorcios";

                        break;
    
                        case "11":
                            
                            if(!$field4Desc)
                                $field4Desc = "Emancipación";
                            else
                                $field4Desc .= ", Emancipación";

                        break;
    
                        case "12":
                            
                            if(!$field4Desc)
                                $field4Desc = "Herencias";
                            else
                                $field4Desc .= ", Herencias";

                        break;
    
                        case "13":
                            
                            if(!$field4Desc)
                                $field4Desc = "Inpugnación de paternidad";
                            else
                                $field4Desc .= ", Inpugnación de paternidad";

                        break;
    
                        case "14":

                            if(!$field4Desc)
                                $field4Desc = "Matrimonio";
                            else
                                $field4Desc .= ", Matrimonio";

                        break;
    
                        case "15":
                            
                            if(!$field4Desc)
                                $field4Desc = "Omisión médica";
                            else
                                $field4Desc .= ", Omisión médica";

                        break;
    
                        case "16":
                            
                            if(!$field4Desc)
                                $field4Desc = "Prestaciones sociales (Prima, vacaciones, cesantías, etc)";
                            else
                                $field4Desc .= ", Prestaciones sociales (Prima, vacaciones, cesantías, etc)";

                        break;
    
                        case "17":

                            if(!$field4Desc)
                                $field4Desc = "Problemas de deudas impuestos";
                            else
                                $field4Desc .= ", Problemas de deudas impuestos";

                        break;
    
                        case "18":
                            
                            if(!$field4Desc)
                                $field4Desc = "Saldos y/o pagos adeudados por el empleador";
                            else
                                $field4Desc .= ", Saldos y/o pagos adeudados por el empleador";

                        break;
    
                        case "19":
                            $field4Desc = "Seguridad social (Salud, Pensión ARL)";

                            if(!$field4Desc)
                                $field4Desc = "Seguridad social (Salud, Pensión ARL)";
                            else
                                $field4Desc .= ", Seguridad social (Salud, Pensión ARL)";

                        break;
    
                        case "20":
                            
                            if(!$field4Desc)
                                $field4Desc = "Tus bienes";
                            else
                                $field4Desc .= ", Tus bienes";

                        break;
    
                        case "21":

                            if(!$field4Desc)
                                $field4Desc = "Violencia intrafamiliar";
                            else
                                $field4Desc .= ", Violencia intrafamiliar";

                        break;
    
                        case "22":
                            
                            if(!$field4Desc)
                                $field4Desc = "Otro";
                            else
                                $field4Desc .= ", Otro";

                        break;
    
                    }

                }

                //  Field 7 descripción

                $field7Desc = "";

                switch($result->field7){
                    
                    case "1":
                        $field7Desc = "Si";
                    break;

                    case "2":
                        $field7Desc = "No";
                    break;

                }

                //  Consultar si ya inicio un proceso

                $abogado = "";
                $abogado_fullname = "";
                $hasProceso = "N";
                $emailOrigen = "";

                $sqlString2 = "
                    SELECT
                        case when status = '0' then '' else email_abogado end as abogado,
                        (
                            SELECT
                                fullname
                            FROM
                                abogados
                            WHERE
                                email = procesos.email_abogado
                        ) AS abogado_fullname,
                        email_origen
                    FROM
                        procesos
                    WHERE
                        id_caso = '".$result->id."'
                            
                ";

                $sql2 = DB::select($sqlString2);

                foreach($sql2 as $result2){

                    $abogado = $result2->abogado;
                    $abogado_fullname = $result2->abogado_fullname;
                    $hasProceso = "S";
                    $emailOrigen = $result2->email_origen;
                    
                }

                //  Construir array caso

                $caso['id'] = $result->id;
                $caso['created_at'] = $result->created_at;
                $caso['updated_at'] = $result->updated_at;
                $caso['active'] = $result->active;
                $caso['email'] = $result->email;
                $caso['field1'] = $result->field1;
                $caso['field1Desc'] = $field1Desc;
                $caso['field2'] = $result->field2;
                $caso['field2Desc'] = $field2Desc;
                $caso['field3'] = $result->field3;
                $caso['field4'] = $result->field4;
                $caso['field4Desc'] = $field4Desc;
                $caso['field5'] = $result->field5;
                $caso['field6'] = $result->field6;
                $caso['field7'] = $result->field7;
                $caso['field7Desc'] = $field7Desc;
                $caso['abogado'] = $abogado;
                $caso['hasProceso'] = $hasProceso;
                $caso['abogado_fullname'] = $abogado_fullname;
                $caso['emailOrigen'] = $emailOrigen;

                array_push($casos,$caso);

            }

            return response()->json($casos);

        }catch (Exception $e) {}

    }

    /************************************************************************** */

    /**
     * Obtener todos los casos que no se ha enviado solicitud
     */

    public function getCasos(Request $request){

        //  Variables iniciales

        $emailAbogado = $request->emailAbogado;
        $casos = [];

        //  Consultar casos

        $sqlString = "
            SELECT 
                *
            FROM 
                casos
            WHERE 
                active = '1' AND
                id NOT IN (
                    SELECT
                        id_caso
                    FROM
                        procesos
                    WHERE
                        email_abogado = '".$emailAbogado."' AND
                        status != 0
                )
        ";

        $sql = DB::select($sqlString);

        foreach($sql as $result){

            //  Field 1 descripción

            $field1Desc = "";

            switch($result->field1){
                
                case "1":
                    $field1Desc = "El estado (Toda entidad pública u organismo adscrito a los gobiernos nacionales regionales y/o locales)";
                break;

                case "2":
                    $field1Desc = "Un particular o empresa privada (Tu jefe, un amigo, tu familia, conocidos, tu jefe y empresas que son privadas)";
                break;

            }

            //  Field 2 descripción

            $field2Desc = "";

            switch($result->field2){
                
                case "1":
                    $field2Desc = "Con tu empleo en una entidad pública";
                break;

                case "2":
                    $field2Desc = "Con tus impuestos";
                break;

                case "3":
                    $field2Desc = "Con tu negocio";
                break;

                case "4":
                    $field2Desc = "Con un amigo o conocido";
                break;

                case "5":
                    $field2Desc = "Reparación de daños y perjuicios causados por el estado";
                break;

                case "6":
                    $field2Desc = "Tu familia";
                break;

                case "7":
                    $field2Desc = "Tu jefe o empresa";
                break;

                case "8":
                    $field2Desc = "Vulneración a tu salud";
                break;

                case "9":
                    $field2Desc = "Vulneración de derechos fundamentales";
                break;

                case "10":
                    $field2Desc = "Vulneración de derechos por el estado";
                break;

                case "11":
                    $field2Desc = "Un delito o temas con policía";
                break;

                case "12":
                    $field2Desc = "Otro";
                break;

            }

            //  Field 4 descripción

            $field4Desc = "";

            switch($result->field4){
                
                case "1":
                    $field4Desc = "Acoso laboral";
                break;

                case "2":
                    $field4Desc = "Alimentos a hijos o conyugues";
                break;

                case "3":
                    $field4Desc = "Capitulaciones";
                break;

                case "4":
                    $field4Desc = "Con tu EPS";
                break;

                case "5":
                    $field4Desc = "Contratos";
                break;

                case "6":
                    $field4Desc = "Declaración de renta";
                break;

                case "7":
                    $field4Desc = "Deterioro de tu salud a causa de la responsabilidad médica";
                break;

                case "8":
                    $field4Desc = "Despido sin justa causa";
                break;

                case "9":
                    $field4Desc = "Dineros adeudados";
                break;

                case "10":
                    $field4Desc = "Divorcios";
                break;

                case "11":
                    $field4Desc = "Emancipación";
                break;

                case "12":
                    $field4Desc = "Herencias";
                break;

                case "13":
                    $field4Desc = "Inpugnación de paternidad";
                break;

                case "14":
                    $field4Desc = "Matrimonio";
                break;

                case "15":
                    $field4Desc = "Omisión médica";
                break;

                case "16":
                    $field4Desc = "Prestaciones sociales (Prima, vacaciones, cesantías, etc)";
                break;

                case "17":
                    $field4Desc = "Problemas de deudas impuestos";
                break;

                case "18":
                    $field4Desc = "Saldos y/o pagos adeudados por el empleador";
                break;

                case "19":
                    $field4Desc = "Seguridad social (Salud, Pensión ARL)";
                break;

                case "20":
                    $field4Desc = "Tus bienes";
                break;

                case "21":
                    $field4Desc = "Violencia intrafamiliar";
                break;

                case "22":
                    $field4Desc = "Otro";
                break;

            }

            //  Field 7 descripción

            $field7Desc = "";

            switch($result->field7){
                
                case "1":
                    $field7Desc = "Si";
                break;

                case "2":
                    $field7Desc = "No";
                break;

            }

            //  Construir array caso

            $caso['id'] = $result->id;
            $caso['created_at'] = $result->created_at;
            $caso['updated_at'] = $result->updated_at;
            $caso['active'] = $result->active;
            $caso['email'] = $result->email;
            $caso['field1'] = $result->field1;
            $caso['field1Desc'] = $field1Desc;
            $caso['field2'] = $result->field2;
            $caso['field2Desc'] = $field2Desc;
            $caso['field3'] = $result->field3;
            $caso['field4'] = $result->field4;
            $caso['field4Desc'] = $field4Desc;
            $caso['field5'] = $result->field5;
            $caso['field6'] = $result->field6;
            $caso['field7'] = $result->field7;
            $caso['field7Desc'] = $field7Desc;

            array_push($casos,$caso);

        }

        return response()->json($casos);

    }

    /********************************************************************************* */
    //  OBTENER DATOS DE UN CASO EN ESPECIFICO
    /********************************************************************************* */

    public function getDataCasoEspecifico(Request $request){

        //  Parametros de entrada
        
        $idCaso = $request->idCaso;
        $emailLogin = $request->emailLogin;

        //  Variables iniciales

        $casos = array();

        //  Consultar casos

        $sqlString = "
            SELECT
                *
            FROM
                casos
            WHERE
                active = '1' AND
                id = '".$idCaso."'
        ";

        $sql = DB::select($sqlString);

        foreach($sql as $result){

            //  Field 1 descripción

            $field1Desc = "";

            switch($result->field1){
                
                case "1":
                    $field1Desc = "El estado (Toda entidad pública u organismo adscrito a los gobiernos nacionales regionales y/o locales)";
                break;

                case "2":
                    $field1Desc = "Un particular o empresa privada (Tu jefe, un amigo, tu familia, conocidos, tu jefe y empresas que son privadas)";
                break;

            }

            //  Field 2 descripción

            $field2Desc = "";

            switch($result->field2){
                
                case "1":
                    $field2Desc = "Con tu empleo en una entidad pública";
                break;

                case "2":
                    $field2Desc = "Con tus impuestos";
                break;

                case "3":
                    $field2Desc = "Con tu negocio";
                break;

                case "4":
                    $field2Desc = "Con un amigo o conocido";
                break;

                case "5":
                    $field2Desc = "Reparación de daños y perjuicios causados por el estado";
                break;

                case "6":
                    $field2Desc = "Tu familia";
                break;

                case "7":
                    $field2Desc = "Tu jefe o empresa";
                break;

                case "8":
                    $field2Desc = "Vulneración a tu salud";
                break;

                case "9":
                    $field2Desc = "Vulneración de derechos fundamentales";
                break;

                case "10":
                    $field2Desc = "Vulneración de derechos por el estado";
                break;

                case "11":
                    $field2Desc = "Un delito o temas con policía";
                break;

                case "12":
                    $field2Desc = "Otro";
                break;

            }

            //  Field 4 descripción

            $field4Desc = "";

            $field4Data = explode(",",$result->field4);

            for($i = 0; $i < count($field4Data); $i++){

                switch($field4Data[$i]){
                
                    case "1":

                        if(!$field4Desc)
                            $field4Desc = "Acoso laboral";
                        else
                            $field4Desc .= ", Acoso laboral";

                    break;

                    case "2":

                        if(!$field4Desc)
                            $field4Desc = "Alimentos a hijos o conyugues";
                        else
                            $field4Desc .= ", Alimentos a hijos o conyugues";

                    break;

                    case "3":

                        if(!$field4Desc)
                            $field4Desc = "Capitulaciones";
                        else
                            $field4Desc .= ", Capitulaciones";

                    break;

                    case "4":

                        if(!$field4Desc)
                            $field4Desc = "Con tu EPS";
                        else
                            $field4Desc .= ", Con tu EPS";

                    break;

                    case "5":

                        if(!$field4Desc)
                            $field4Desc = "Contratos";
                        else
                            $field4Desc .= ", Contratos";
                        
                    break;

                    case "6":

                        if(!$field4Desc)
                            $field4Desc = "Declaración de renta";
                        else
                            $field4Desc .= ", Declaración de renta";

                    break;

                    case "7":

                        if(!$field4Desc)
                            $field4Desc = "Deterioro de tu salud a causa de la responsabilidad médica";
                        else
                            $field4Desc .= ", Deterioro de tu salud a causa de la responsabilidad médica";

                    break;

                    case "8":

                        if(!$field4Desc)
                            $field4Desc = "Despido sin justa causa";
                        else
                            $field4Desc .= ", Despido sin justa causa";

                    break;

                    case "9":
                        
                        if(!$field4Desc)
                            $field4Desc = "Dineros adeudados";
                        else
                            $field4Desc .= ", Dineros adeudados";

                    break;

                    case "10":
                        
                        if(!$field4Desc)
                            $field4Desc = "Divorcios";
                        else
                            $field4Desc .= ", Divorcios";

                    break;

                    case "11":
                        
                        if(!$field4Desc)
                            $field4Desc = "Emancipación";
                        else
                            $field4Desc .= ", Emancipación";

                    break;

                    case "12":
                        
                        if(!$field4Desc)
                            $field4Desc = "Herencias";
                        else
                            $field4Desc .= ", Herencias";

                    break;

                    case "13":
                        
                        if(!$field4Desc)
                            $field4Desc = "Inpugnación de paternidad";
                        else
                            $field4Desc .= ", Inpugnación de paternidad";

                    break;

                    case "14":

                        if(!$field4Desc)
                            $field4Desc = "Matrimonio";
                        else
                            $field4Desc .= ", Matrimonio";

                    break;

                    case "15":
                        
                        if(!$field4Desc)
                            $field4Desc = "Omisión médica";
                        else
                            $field4Desc .= ", Omisión médica";

                    break;

                    case "16":
                        
                        if(!$field4Desc)
                            $field4Desc = "Prestaciones sociales (Prima, vacaciones, cesantías, etc)";
                        else
                            $field4Desc .= ", Prestaciones sociales (Prima, vacaciones, cesantías, etc)";

                    break;

                    case "17":

                        if(!$field4Desc)
                            $field4Desc = "Problemas de deudas impuestos";
                        else
                            $field4Desc .= ", Problemas de deudas impuestos";

                    break;

                    case "18":
                        
                        if(!$field4Desc)
                            $field4Desc = "Saldos y/o pagos adeudados por el empleador";
                        else
                            $field4Desc .= ", Saldos y/o pagos adeudados por el empleador";

                    break;

                    case "19":
                        $field4Desc = "Seguridad social (Salud, Pensión ARL)";

                        if(!$field4Desc)
                            $field4Desc = "Seguridad social (Salud, Pensión ARL)";
                        else
                            $field4Desc .= ", Seguridad social (Salud, Pensión ARL)";

                    break;

                    case "20":
                        
                        if(!$field4Desc)
                            $field4Desc = "Tus bienes";
                        else
                            $field4Desc .= ", Tus bienes";

                    break;

                    case "21":

                        if(!$field4Desc)
                            $field4Desc = "Violencia intrafamiliar";
                        else
                            $field4Desc .= ", Violencia intrafamiliar";

                    break;

                    case "22":
                        
                        if(!$field4Desc)
                            $field4Desc = "Otro";
                        else
                            $field4Desc .= ", Otro";

                    break;

                }

            }

            //  Field 7 descripción

            $field7Desc = "";

            switch($result->field7){
                
                case "1":
                    $field7Desc = "Si";
                break;

                case "2":
                    $field7Desc = "No";
                break;

            }

            //  Consultar si ya inicio un proceso

            $abogado = "";
            $abogado_fullname = "";
            $hasProceso = "N";
            $emailOrigen = "";

            $sqlString2 = "
                SELECT
                    case when status = '0' then '' else email_abogado end as abogado,
                    (
                        SELECT
                            fullname
                        FROM
                            abogados
                        WHERE
                            email = procesos.email_abogado
                    ) AS abogado_fullname,
                    email_origen
                FROM
                    procesos
                WHERE
                    id_caso = '".$idCaso."' AND
                    (
                        email_cliente = '".$emailLogin."' OR
                        email_abogado = '".$emailLogin."'
                    )
                        
            ";

            $sql2 = DB::select($sqlString2);

            foreach($sql2 as $result2){

                $abogado = $result2->abogado;
                $abogado_fullname = $result2->abogado_fullname;
                $hasProceso = "S";
                $emailOrigen = $result2->email_origen;
                
            }

            //  Construir array caso

            $caso['id'] = $idCaso;
            $caso['created_at'] = $result->created_at;
            $caso['updated_at'] = $result->updated_at;
            $caso['active'] = $result->active;
            $caso['email'] = $result->email;
            $caso['field1'] = $result->field1;
            $caso['field1Desc'] = $field1Desc;
            $caso['field2'] = $result->field2;
            $caso['field2Desc'] = $field2Desc;
            $caso['field3'] = $result->field3;
            $caso['field4'] = $result->field4;
            $caso['field4Desc'] = $field4Desc;
            $caso['field5'] = $result->field5;
            $caso['field6'] = $result->field6;
            $caso['field7'] = $result->field7;
            $caso['field7Desc'] = $field7Desc;
            $caso['abogado'] = $abogado;
            $caso['hasProceso'] = $hasProceso;
            $caso['abogado_fullname'] = $abogado_fullname;
            $caso['emailOrigen'] = $emailOrigen;

            array_push($casos,$caso);

        }

        return response()->json($casos);

    }

    /***************************************************************************************** */
    //  OBTENER SI YA UN CASO TIENE MERGE
    /***************************************************************************************** */

    public function getMergeCaso(Request $request){
        

        //  Parametros de entrada
        $idCaso = $request->idCaso;

        //  Consultar si un caso tiene merge

        $sqlString = "
            SELECT
                *
            FROM
                procesos
            WHERE
                id_caso = '".$idCaso."' AND
                status = '2'
        ";

        $sql = DB::select($sqlString);

        return response()->json($sql);

    }

}
