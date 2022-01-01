<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
*/

Route::group(['middleware' => ['cors']], function () {

  //  Administrador
  
  Route::match(['post', 'options'], "apiAdminCiudadRegister", "AdminController@apiAdminCiudadRegister");
  Route::match(['post', 'options'], "apiAdminCiudadGet", "AdminController@apiAdminCiudadGet");
  Route::match(['post', 'options'], "apiAdminCiudadDelete", "AdminController@apiAdminCiudadDelete");
  Route::match(['post', 'options'], "apiAdminGeneroRegister", "AdminController@apiAdminGeneroRegister");
  Route::match(['post', 'options'], "apiAdminGeneroGet", "AdminController@apiAdminGeneroGet");
  Route::match(['post', 'options'], "apiAdminGeneroDelete", "AdminController@apiAdminGeneroDelete");
  Route::match(['post', 'options'], "apiAdminTipoDocumentoRegister", "AdminController@apiAdminTipoDocumentoRegister");
  Route::match(['post', 'options'], "apiAdminTipoDocumentoGet", "AdminController@apiAdminTipoDocumentoGet");
  Route::match(['post', 'options'], "apiAdminTipoDocumentoDelete", "AdminController@apiAdminTipoDocumentoDelete");
  Route::match(['post', 'options'], "apiAdminMunicipioRegister", "AdminController@apiAdminMunicipioRegister");
  Route::match(['post', 'options'], "apiAdminMunicipioGet", "AdminController@apiAdminMunicipioGet");
  Route::match(['post', 'options'], "apiAdminMunicipioDelete", "AdminController@apiAdminMunicipioDelete");
  Route::match(['post', 'options'], "apiAdminUpdate", "AdminController@apiAdminUpdate");
  Route::match(['post', 'options'], "apiAdminGetContenido", "AdminController@apiAdminGetContenido");
  Route::match(['post', 'options'], "apiAdminTituloRegister", "AdminController@apiAdminTituloRegister");
  Route::match(['post', 'options'], "apiAdminTituloGet", "AdminController@apiAdminTituloGet");
  Route::match(['post', 'options'], "apiAdminTituloDelete", "AdminController@apiAdminTituloDelete");

  //  Casos

  Route::match(['post', 'options'], "apiRegistrarCaso", "CasosController@apiRegistrarCaso");
  Route::match(['post', 'options'], "apiConsultarCasos", "CasosController@apiConsultarCasos");
  Route::match(['post', 'options'], "apiEliminarCaso", "CasosController@apiEliminarCaso");

  //  Usuarios

  Route::match(['post', 'options'], "apiUsuariosGetUser", "UsuariosController@apiUsuariosGetUser");
  Route::match(['post', 'options'], "apiUsuariosInsertUser", "UsuariosController@apiUsuariosInsertUser");
  Route::match(['post', 'options'], "apiUsuariosGetUserPassword", "UsuariosController@apiUsuariosGetUserPassword");
  Route::match(['post', 'options'], "apiUsuariosUpdateUser", "UsuariosController@apiUsuariosUpdateUser");
  Route::match(['post', 'options'], "apiUsuariosUpdateUserPassword", "UsuariosController@apiUsuariosUpdateUserPassword");
  Route::match(['post', 'options'], "apiUsuariosUpdatePhoto", "UsuariosController@apiUsuariosUpdatePhoto");
  Route::match(['post', 'options'], "apiUsuariosUpdateField", "UsuariosController@apiUsuariosUpdateField");
  
});