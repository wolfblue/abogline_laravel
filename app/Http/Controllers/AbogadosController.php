<?php

//  Obtener todos los abogados: getAbogados

namespace App\Http\Controllers;

use App\abogados;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AbogadosController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return abogados::all();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        return abogados::create($request->all());
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\abogados  $abogados
     * @return \Illuminate\Http\Response
     */
    public function show(abogados $abogados)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\abogados  $abogados
     * @return \Illuminate\Http\Response
     */
    public function edit(abogados $abogados)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\abogados  $abogados
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, abogados $abogados)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\abogados  $abogados
     * @return \Illuminate\Http\Response
     */
    public function destroy(abogados $abogados)
    {
        //
    }

    /*********************************************************************************** */

    /**
     * Obtener todos los abogados
     */

     public function getAbogados(Request $request){

        //  Variables iniciales
        $idCaso = $request->idCaso;

        //  Consultar abogados que no se halla enviado solicitud

        $sqlString = "
            SELECT 
                * 
            FROM 
                abogados 
            WHERE 
                email NOT IN (
                    SELECT
                        email_abogado
                    FROM
                        procesos
                    WHERE
                        id_caso = '".$idCaso."' AND
                        status != '0'
                ) AND
            active = '1'
        ";
        
        $sql = DB::select($sqlString);

        return response()->json($sql);

     } 

    /*********************************************************************************** */

    /**
     * Actualizar información del abogado
     */

    public function abogadosUpdate(Request $request){

        try{

            //  Variables iniciales

            $email = $request->email;
            $fullname = $request->fullname;
            $gender = $request->gender;
            $identification = $request->identification;
            $address = $request->address;
            $document1 = $request->document1;
            $university = $request->university;
            $license = $request->license;
            $experience = $request->experience;
            $years = $request->years;
            $investigate = $request->investigate;
            $pleasures = $request->pleasures;
            $pleasures_other = $request->pleasures_other;
            $price = $request->price;
            $cv = $request->cv;
            $format = $request->format;
            $photo = $request->photo;
            $formatPhoto = $request->formatPhoto;

            //  Guardar archivo en físico

            if($cv)
                file_put_contents("cv/".$identification.".".$format, file_get_contents($cv));

            $cv = "cv/".$identification.".".$format;

            //  Guardar foto en físico

            if($photo)
                file_put_contents("cv/".$identification.".".$formatPhoto, file_get_contents($photo));

            $photo = "cv/".$identification.".".$formatPhoto;

            //  Validar si es nuevo registro o actualización

            $actualizacion = 0;

            $sqlString = "SELECT * FROM abogados WHERE email = '".$email."'";
            $sql = DB::select($sqlString);
        
            foreach($sql as $result)
                $actualizacion = 1;

            if($actualizacion == 0){

                //  Nuevo registro

                $sqlString = "
                    INSERT INTO abogados VALUES (
                        '0',
                        now(),
                        now(),
                        '1',
                        '".$email."',
                        '".$fullname."',
                        '".$gender."',
                        '".$identification."',
                        '".$address."',
                        '".$document1."',
                        '".$university."',
                        '".$license."',
                        '".$experience."',
                        '".$years."',
                        '".$investigate."',
                        '".$pleasures."',
                        '".$pleasures_other."',
                        '".$price."',
                        '".$cv."',
                        '".$photo."'
                    )
                ";

                DB::insert($sqlString);

            }else{

                //  Actualización

                $sqlString = "
                    UPDATE abogados SET
                        updated_at = now(),
                        fullname = '".$fullname."',
                        gender = '".$gender."',
                        identification = '".$identification."',
                        address = '".$address."',
                        document1 = '".$document1."',
                        university = '".$university."',
                        license = '".$license."',
                        experience = '".$experience."',
                        years = '".$years."',
                        investigate = '".$investigate."',
                        pleasures = '".$pleasures."',
                        pleasures_other = '".$pleasures_other."',
                        price = '".$price."',
                        cv = '".$cv."',
                        photo = '".$photo."'
                    WHERE
                        email = '".$email."'
                ";

                DB::update($sqlString);

            }

            return response()->json($sql);

        }catch (Exception $e) {}

    }

    /**
     * Obtener datos del abogado
     */

    public function getDataAbogados(Request $request){

        try{

            //  Variables iniciales

            $email = $request->email;
            $abogados = array();

            //  Consultar información del abogado

            $sqlString = "
                SELECT 
                    * 
                FROM 
                    abogados 
                WHERE 
                    email = '".$email."'
            ";

            $sql = DB::select($sqlString);

            foreach($sql as $result){

                //  Field gender descripción

                $genderDesc = "";

                switch($result->gender){

                    case "1":
                        $genderDesc = "Femenino";
                    break;

                    case "2":
                        $genderDesc = "Masculino";
                    break;

                }

                //  Field document1 descripción

                $document1Desc = "";

                switch($result->document1){

                    case "1":
                        $document1Desc = "Licencia temporal";
                    break;

                    case "2":
                        $document1Desc = "Tarjeta profesional";
                    break;

                }

                //  Field experience descripción

                $experienceDesc = "";

                switch($result->experience){

                    case "1":
                        $experienceDesc = "No";
                    break;

                    case "2":
                        $experienceDesc = "Si";
                    break;

                }

                //  Field investigate descripción

                $investigateDesc = "";

                switch($result->investigate){

                    case "1":
                        $investigateDesc = "No";
                    break;

                    case "2":
                        $investigateDesc = "Si";
                    break;

                }

                //  Construir array abogado

                $abogado['id'] = $result->id;
                $abogado['created_at'] = $result->created_at;
                $abogado['updated_at'] = $result->updated_at;
                $abogado['active'] = $result->active;
                $abogado['email'] = $result->email;
                $abogado['fullname'] = $result->fullname;
                $abogado['gender'] = $result->gender;
                $abogado['genderDesc'] = $genderDesc;
                $abogado['identification'] = $result->identification;
                $abogado['address'] = $result->address;
                $abogado['document1'] = $result->document1;
                $abogado['document1Desc'] = $document1Desc;
                $abogado['university'] = $result->university;
                $abogado['license'] = $result->license;
                $abogado['experience'] = $result->experience;
                $abogado['experienceDesc'] = $experienceDesc;
                $abogado['years'] = $result->years;
                $abogado['investigate'] = $result->investigate;
                $abogado['investigateDesc'] = $investigateDesc;
                $abogado['pleasures'] = $result->pleasures;
                $abogado['pleasures_other'] = $result->pleasures_other;
                $abogado['price'] = $result->price;
                $abogado['cv'] = $result->cv;
                $abogado['photo'] = $result->photo;

                array_push($abogados,$abogado);

            }

            return response()->json($abogados);

        }catch (Exception $e) {}

    }

    /************************************************************************************** */

    /**
     * Consultar los casos en proceso de un abogado
     */

    public function getAbogadoCasosProceso(Request $request){

        //  Variables iniciales

        $emailAbogado = $request->emailAbogado;
        $casos = [];

        try{

            //  Consultar casos

            $sqlString = "
                SELECT 
                    *
                FROM 
                    casos
                WHERE 
                    active = '1' AND
                    id IN (
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

        }catch (Exception $e) {}

    }

}
