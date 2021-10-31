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
  
});