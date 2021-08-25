<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

//  Obtener todos los usuarios: GET usuarios
//  Obtener todos los abogados: GET abogados
//  Obtener todos los clientes: GET clientes
//  Obtener información de un usuario: POST getUser
//  Obtener información de un abogado: POST getDataAbogados
//  Obtener información de un cliente: POST getDataClientes
//  Obtener información de un caso: POST getDataCaso
//  Obtener todos los procesos: POST getProceso
//  Obtener todas las notificaciones de un usuario: getNotificacion
//  Actualizar información de un abogado: POST abogadosUpdate
//  Actualizar información de un cliente: POST clientesUpdate
//  Actualizar información de un caso: POST casosUpdate
//  Actualizar proceso: procesosUpdate
//  Crear un proceso: POST createProceso
//  Crear una notificación: POST createNotificacion
//  Eliminar una notificación: deleteNotificacion

Route::group(['middleware' => ['cors']], function () {

  //  AboglineRegisterController
  
  Route::match(['post', 'options'], "apiAboglineRegisterRegistrarUsuario", "AboglineRegisterController@apiAboglineRegisterRegistrarUsuario");
  Route::match(['post', 'options'], "apiAboglineRegisterConsultarUsuario", "AboglineRegisterController@apiAboglineRegisterConsultarUsuario");

  //  AboglineLoginController
  Route::match(['post', 'options'], "apiAboglineLoginConsultarUsuarioActivo", "AboglineLoginController@apiAboglineLoginConsultarUsuarioActivo");

  //  AboglineRegisterCasoController

  Route::match(['post', 'options'], "apiAboglineRegisterCasoGetInfo", "AboglineRegisterCasoController@apiAboglineRegisterCasoGetInfo");
  Route::match(['post', 'options'], "apiAboglineRegisterCaso", "AboglineRegisterCasoController@apiAboglineRegisterCaso");

  //  AboglineConsultarCasoController

  Route::match(['post', 'options'], "apiAboglineConsultarCasoGetInfo", "AboglineConsultarCasoController@apiAboglineConsultarCasoGetInfo");
  Route::match(['post', 'options'], "apiAboglineConsultarCasoAplicarCaso", "AboglineConsultarCasoController@apiAboglineConsultarCasoAplicarCaso");

  //  AboglineProfileController

  Route::match(['post', 'options'], "apiAboglineProfileGetInfo", "AboglineProfileController@apiAboglineProfileGetInfo");
  Route::match(['post', 'options'], "apiAboglineProfileUpdateUserCliente", "AboglineProfileController@apiAboglineProfileUpdateUserCliente");
  Route::match(['post', 'options'], "apiAboglineProfileUpdateUserAbogado", "AboglineProfileController@apiAboglineProfileUpdateUserAbogado");

  //  AboglineSolicitudesController
  
  Route::match(['post', 'options'], "apiAboglineSolicitudesGetInfo", "AboglineSolicitudesController@apiAboglineSolicitudesGetInfo");
  Route::match(['post', 'options'], "apiAboglineSolicitudesAprobar", "AboglineSolicitudesController@apiAboglineSolicitudesAprobar");
  Route::match(['post', 'options'], "apiAboglineSolicitudesRechazar", "AboglineSolicitudesController@apiAboglineSolicitudesRechazar");  

  //  AboglineAgendarController
  
  Route::match(['post', 'options'], "apiAboglineAgendarGetInfo", "AboglineAgendarController@apiAboglineAgendarGetInfo");
  Route::match(['post', 'options'], "apiAboglineAgendar", "AboglineAgendarController@apiAboglineAgendar");

  //  AboglineCalendarController
  Route::match(['post', 'options'], "apiAboglineCalendarGetInfo", "AboglineCalendarController@apiAboglineCalendarGetInfo");

  //  AboglinePagosController
  Route::match(['post', 'options'], "apiAboglinePagosGetInfo", "AboglinePagosController@apiAboglinePagosGetInfo");

  //  AboglineAbogadosController

  Route::match(['post', 'options'], "apiAboglineAbogadosGetInfo", "AboglineAbogadosController@apiAboglineAbogadosGetInfo");
  Route::match(['post', 'options'], "apiAboglineAbogadosAplicar", "AboglineAbogadosController@apiAboglineAbogadosAplicar");
  
});