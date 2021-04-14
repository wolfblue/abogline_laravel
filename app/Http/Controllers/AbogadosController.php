<?php

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

            //  Guardar archivo en físico

            file_put_contents("cv/".$identification.".".$format, file_get_contents($cv));

            $cv = "cv/".$identification.".".$format;

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
                        '".$cv."'
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
                        cv = '".$cv."'
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

                array_push($abogados,$abogado);

            }

            return response()->json($abogados);

        }catch (Exception $e) {}

    }

}
