<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
*/

Route::group(['middleware' => ['cors']], function () {

  //  Usuarios

  Route::match(['post', 'options'], "apiUsuariosGetUser", "UsuariosController@apiUsuariosGetUser");
  Route::match(['post', 'options'], "apiUsuariosInsertUser", "UsuariosController@apiUsuariosInsertUser");
  Route::match(['post', 'options'], "apiUsuariosGetUserPassword", "UsuariosController@apiUsuariosGetUserPassword");
  Route::match(['post', 'options'], "apiUsuariosUpdateUser", "UsuariosController@apiUsuariosUpdateUser");
  Route::match(['post', 'options'], "apiUsuariosUpdateUserPassword", "UsuariosController@apiUsuariosUpdateUserPassword");
  Route::match(['post', 'options'], "apiUsuariosUpdatePhoto", "UsuariosController@apiUsuariosUpdatePhoto");

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
  
});