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

Route::apiResource("usuarios","UsuariosController");
Route::apiResource("abogados","AbogadosController");
Route::apiResource("clientes","ClientesController");
Route::match(['post', 'options'], "getUser", "UsuariosController@getUser");
Route::match(['post', 'options'], "getDataAbogados", "AbogadosController@getDataAbogados");
Route::match(['post', 'options'], "getDataClientes", "ClientesController@getDataClientes");
Route::match(['post', 'options'], "getDataCaso", "CasosController@getDataCaso");
Route::match(['post', 'options'], "getProceso", "ProcesosController@getProceso");
Route::match(['post', 'options'], "getNotificacion", "NotificacionesController@getNotificacion");
Route::match(['post', 'options'], "abogadosUpdate", "AbogadosController@abogadosUpdate");
Route::match(['post', 'options'], "clientesUpdate", "ClientesController@clientesUpdate");
Route::match(['post', 'options'], "casosUpdate", "CasosController@casosUpdate");
Route::match(['post', 'options'], "procesosUpdate", "ProcesosController@procesosUpdate");
Route::match(['post', 'options'], "createProceso", "ProcesosController@createProceso");
Route::match(['post', 'options'], "createNotificacion", "NotificacionesController@createNotificacion");
Route::match(['post', 'options'], "deleteNotificacion", "NotificacionesController@deleteNotificacion");

//  UsuariosController
Route::match(['post', 'options'], "createUser", "UsuariosController@createUser");