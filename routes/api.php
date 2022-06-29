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
  Route::match(['post', 'options'], "apiAdminGetUsuarios", "AdminController@apiAdminGetUsuarios");
  Route::match(['post', 'options'], "apiAdminGetDocumentosUser", "AdminController@apiAdminGetDocumentosUser");
  Route::match(['post', 'options'], "apiAdminAprobarAbogado", "AdminController@apiAdminAprobarAbogado");
  Route::match(['post', 'options'], "apiAdminRechazarAbogado", "AdminController@apiAdminRechazarAbogado");
  Route::match(['post', 'options'], "apiAdminBloquearUsuario", "AdminController@apiAdminBloquearUsuario");
  Route::match(['post', 'options'], "apiConsultarSolicitudes", "AdminController@apiConsultarSolicitudes");
  Route::match(['post', 'options'], "apiAprobarSolicitud", "AdminController@apiAprobarSolicitud");
  Route::match(['post', 'options'], "apiRechazarSolicitud", "AdminController@apiRechazarSolicitud");
  Route::match(['post', 'options'], "apiAdminConsulta", "AdminController@apiAdminConsulta");

  //  Casos

  Route::match(['post', 'options'], "apiRegistrarCaso", "CasosController@apiRegistrarCaso");
  Route::match(['post', 'options'], "apiConsultarCasos", "CasosController@apiConsultarCasos");
  Route::match(['post', 'options'], "apiEliminarCaso", "CasosController@apiEliminarCaso");
  Route::match(['post', 'options'], "apiCasosUsuarioAsociarAbogado", "CasosController@apiCasosUsuarioAsociarAbogado");

  //  Usuarios

  Route::match(['post', 'options'], "apiUsuariosGetUser", "UsuariosController@apiUsuariosGetUser");
  Route::match(['post', 'options'], "apiUsuariosInsertUser", "UsuariosController@apiUsuariosInsertUser");
  Route::match(['post', 'options'], "apiUsuariosGetUserPassword", "UsuariosController@apiUsuariosGetUserPassword");
  Route::match(['post', 'options'], "apiUsuariosUpdateUser", "UsuariosController@apiUsuariosUpdateUser");
  Route::match(['post', 'options'], "apiUsuariosUpdateUserPassword", "UsuariosController@apiUsuariosUpdateUserPassword");
  Route::match(['post', 'options'], "apiUsuariosUpdatePhoto", "UsuariosController@apiUsuariosUpdatePhoto");
  Route::match(['post', 'options'], "apiUsuariosUpdateField", "UsuariosController@apiUsuariosUpdateField");
  Route::match(['post', 'options'], "apiUsuariosGetTitulos", "UsuariosController@apiUsuariosGetTitulos");
  Route::match(['post', 'options'], "apiUsuariosInsertTitulo", "UsuariosController@apiUsuariosInsertTitulo");
  Route::match(['post', 'options'], "apiUsuariosDeleteTitulo", "UsuariosController@apiUsuariosDeleteTitulo");
  Route::match(['post', 'options'], "apiUsuariosUpdateFieldTitulo", "UsuariosController@apiUsuariosUpdateFieldTitulo");
  Route::match(['post', 'options'], "apiUsuariosUpdateDocumento", "UsuariosController@apiUsuariosUpdateDocumento");
  Route::match(['post', 'options'], "apiUsuariosGetDocumentos", "UsuariosController@apiUsuariosGetDocumentos");
  Route::match(['post', 'options'], "apiUsuariosGetAbogados", "UsuariosController@apiUsuariosGetAbogados");

  //  CORE

  Route::match(['post', 'options'], "apiCoreChatSave", "CoreController@apiCoreChatSave");
  Route::match(['post', 'options'], "apiCoreChatGet", "CoreController@apiCoreChatGet");
  Route::match(['post', 'options'], "apiCoreAbogadoGet", "CoreController@apiCoreAbogadoGet");
  Route::match(['post', 'options'], "apiCoreCalendarioSave", "CoreController@apiCoreCalendarioSave");
  Route::match(['post', 'options'], "apiCoreCrearActividad", "CoreController@apiCoreCrearActividad");
  Route::match(['post', 'options'], "apiCoreConsultarActividades", "CoreController@apiCoreConsultarActividades");

  //  NOTIFICACIONES

  Route::match(['post', 'options'], "apiConsultarNotificaciones", "NotificacionesController@apiConsultarNotificaciones");
  Route::match(['post', 'options'], "apiAprobarNotificacionReunion", "NotificacionesController@apiAprobarNotificacionReunion");
  Route::match(['post', 'options'], "apiRechazarNotificacionReunion", "NotificacionesController@apiRechazarNotificacionReunion");
  Route::match(['post', 'options'], "apiEliminarNotificacion", "NotificacionesController@apiEliminarNotificacion");
  Route::match(['post', 'options'], "apiNotificacionesAprobar", "NotificacionesController@apiNotificacionesAprobar");

  //  Calendario

  Route::match(['post', 'options'], "apiConsultarCalendario", "CalendarioController@apiConsultarCalendario");
  Route::match(['post', 'options'], "apiAsignarLinkReunion", "CalendarioController@apiAsignarLinkReunion");

  //  CONTÁCTENOS
  Route::match(['post', 'options'], "apiContactenosEnviarFormulario", "ContactenosController@apiContactenosEnviarFormulario");

  //  LOGIN
  Route::match(['post', 'options'], "apiLoginRecordarPassword", "LoginController@apiLoginRecordarPassword");

  //  HOME
  
  Route::match(['post', 'options'], "apiHomeEnviarContacto", "HomeController@apiHomeEnviarContacto");
  Route::match(['post', 'options'], "apiHomeEnviarChat", "HomeController@apiHomeEnviarChat");
  Route::match(['post', 'options'], "apiHomeConsultarNotificaciones", "HomeController@apiHomeConsultarNotificaciones");

  //  REGISTRAR CASO
  Route::match(['post', 'options'], "apiRegistrarCasoConsultarUsuario", "RegistrarCasoController@apiRegistrarCasoConsultarUsuario");

  //  LINKS
  
  Route::match(['post', 'options'], "apiLinksAprobarUsuario", "LinksController@apiLinksAprobarUsuario");
  Route::match(['post', 'options'], "apiLinksRestablecerPassword", "LinksController@apiLinksRestablecerPassword");

  //  CONSULTAR ABOGADOS
  Route::match(['post', 'options'], "apiConsultarAbogadosEligemeValidar", "ConsultarAbogadosController@apiConsultarAbogadosEligemeValidar");
  
});