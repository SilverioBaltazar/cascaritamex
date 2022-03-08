<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/  

Route::get('/', function () {
    return view('sicinar.login.loginInicio');
});

    Route::group(['prefix' => 'control-interno'], function() {
    Route::post('menu', 'usuariosController@actionLogin')->name('login');
    Route::get('status-sesion/expirada', 'usuariosController@actionExpirada')->name('expirada');
    Route::get('status-sesion/terminada','usuariosController@actionCerrarSesion')->name('terminada');
 
    // Auto registro en sistema
    Route::get( 'Autoregistro/usuario'              ,'usuariosController@actionAutoregUsu')->name('autoregusu');
    Route::post('Autoregistro/usuario/registro'     ,'usuariosController@actionRegaltaUsu')->name('regaltausu');
    Route::get( 'Autoregistro/{id}/editarbienvenida','usuariosController@actioneditarBienve')->name('editarbienve');        

    // BackOffice del sistema
    Route::get('BackOffice/usuarios'                ,'usuariosController@actionNuevoUsuario')->name('nuevoUsuario');
    Route::post('BackOffice/usuarios/alta'          ,'usuariosController@actionAltaUsuario')->name('altaUsuario');
    Route::get('BackOffice/buscar/todos'            ,'usuariosController@actionBuscarUsuario')->name('buscarUsuario');        
    Route::get('BackOffice/usuarios/todos'          ,'usuariosController@actionVerUsuario')->name('verUsuarios');
    Route::get('BackOffice/usuarios/{id}/editar'    ,'usuariosController@actionEditarUsuario')->name('editarUsuario');
    Route::put('BackOffice/usuarios/{id}/actualizar','usuariosController@actionActualizarUsuario')->name('actualizarUsuario');
    Route::get('BackOffice/usuarios/{id}/Borrar'    ,'usuariosController@actionBorrarUsuario')->name('borrarUsuario');    
    Route::get('BackOffice/usuario/{id}/activar'    ,'usuariosController@actionActivarUsuario')->name('activarUsuario');
    Route::get('BackOffice/usuario/{id}/desactivar' ,'usuariosController@actionDesactivarUsuario')->name('desactivarUsuario');

    Route::get('BackOffice/usuario/{id}/{id2}/email','usuariosController@actionEmailRegistro')->name('emailregistro');    

    //Catalogos
    //Procesos
    Route::get('proceso/nuevo'      ,'catalogosController@actionNuevoProceso')->name('nuevoProceso');
    Route::post('proceso/nuevo/alta','catalogosController@actionAltaNuevoProceso')->name('AltaNuevoProceso');
    Route::get('proceso/ver/todos'  ,'catalogosController@actionVerProceso')->name('verProceso');
    Route::get('proceso/{id}/editar/proceso','catalogosController@actionEditarProceso')->name('editarProceso');
    Route::put('proceso/{id}/actualizar'    ,'catalogosController@actionActualizarProceso')->name('actualizarProceso');
    Route::get('proceso/{id}/Borrar','catalogosController@actionBorrarProceso')->name('borrarProceso');    
    Route::get('proceso/excel'      ,'catalogosController@exportCatProcesosExcel')->name('downloadprocesos');
    Route::get('proceso/pdf'        ,'catalogosController@exportCatProcesosPdf')->name('catprocesosPDF');
    //Funciones de procesos
    Route::get('funcion/nuevo'      ,'catalogosfuncionesController@actionNuevaFuncion')->name('nuevaFuncion');
    Route::post('funcion/nuevo/alta','catalogosfuncionesController@actionAltaNuevaFuncion')->name('AltaNuevaFuncion');
    Route::get('funcion/ver/todos'  ,'catalogosfuncionesController@actionVerFuncion')->name('verFuncion');
    Route::get('funcion/{id}/editar/funcion','catalogosfuncionesController@actionEditarFuncion')->name('editarFuncion');
    Route::put('funcion/{id}/actualizar'    ,'catalogosfuncionesController@actionActualizarFuncion')->name('actualizarFuncion');
    Route::get('funcion/{id}/Borrar','catalogosfuncionesController@actionBorrarFuncion')->name('borrarFuncion');    
    Route::get('funcion/excel'      ,'catalogosfuncionesController@exportCatFuncionesExcel')->name('downloadfunciones');
    Route::get('funcion/pdf'        ,'catalogosfuncionesController@exportCatFuncionesPdf')->name('catfuncionesPDF');    
    //Actividades
    Route::get('actividad/nuevo'      ,'catalogostrxController@actionNuevaTrx')->name('nuevaTrx');
    Route::post('actividad/nuevo/alta','catalogostrxController@actionAltaNuevaTrx')->name('AltaNuevaTrx');
    Route::get('actividad/ver/todos'  ,'catalogostrxController@actionVerTrx')->name('verTrx');
    Route::get('actividad/{id}/editar/actividad','catalogostrxController@actionEditarTrx')->name('editarTrx');
    Route::put('actividad/{id}/actualizar'      ,'catalogostrxController@actionActualizarTrx')->name('actualizarTrx');
    Route::get('actividad/{id}/Borrar','catalogostrxController@actionBorrarTrx')->name('borrarTrx');    
    Route::get('actividad/excel'      ,'catalogostrxController@exportCatTrxExcel')->name('downloadtrx');
    Route::get('actividad/pdf'        ,'catalogostrxController@exportCatTrxPdf')->name('cattrxPDF');
    //Rubros sociales
    Route::get('cate/nuevo'      ,'catcategoriasController@actionNuevaCate')->name('nuevacate');
    Route::post('cate/alta'      ,'catcategoriasController@actionAltaNuevaCate')->name('altanuevacate');
    Route::get('cate/ver'        ,'catcategoriasController@actionVerCate')->name('vercate');
    Route::get('cate/{id}/editar','catcategoriasController@actionEditarCate')->name('editarcate');
    Route::put('cate/{id}/update','catcategoriasController@actionActualizarCate')->name('actualizarcate');
    Route::get('cate/{id}/Borrar','catcategoriasController@actionBorrarCate')->name('borrarcate');    
    Route::get('cate/excel'      ,'catcategoriasController@exportCateExcel')->name('exportcateexcel');
    Route::get('cate/pdf'        ,'catcategoriasController@exportCatePdf')->name('exportcatepdf');    
    //Imnuebles edo.
    Route::get('inmuebleedo/nuevo'      ,'catalogosinmueblesedoController@actionNuevoInmuebleedo')->name('nuevoInmuebleedo');
    Route::post('inmuebleedo/nuevo/alta','catalogosinmueblesedoController@actionAltaNuevoInmuebleedo')->name('AltaNuevoInmuebleedo');
    Route::get('inmuebleedo/ver/todos'  ,'catalogosinmueblesedoController@actionVerInmuebleedo')->name('verInmuebleedo');
    Route::get('inmuebleedo/{id}/editar/rubro','catalogosinmueblesedoController@actionEditarInmuebleedo')->name('editarInmuebleedo');
    Route::put('inmuebleedo/{id}/actualizar'  ,'catalogosinmueblesedoController@actionActualizarInmuebleedo')->name('actualizarInmuebleedo');
    Route::get('inmuebleedo/{id}/Borrar','catalogosinmueblesedoController@actionBorrarInmuebleedo')->name('borrarInmuebleedo');
    Route::get('inmuebleedo/excel'      ,'catalogosinmueblesedoController@exportCatInmueblesedoExcel')->name('downloadinmueblesedo');
    Route::get('inmuebleedo/pdf'        ,'catalogosinmueblesedoController@exportCatInmueblesedoPdf')->name('catinmueblesedoPDF');
    //tipos de archivos
    Route::get('formato/nuevo'              ,'formatosController@actionNuevoFormato')->name('nuevoFormato');
    Route::post('formato/nuevo/alta'        ,'formatosController@actionAltaNuevoFormato')->name('AltaNuevoFormato');
    Route::get('formato/ver/todos'          ,'formatosController@actionVerFormatos')->name('verFormatos');
    Route::get('formato/{id}/editar/formato','formatosController@actionEditarFormato')->name('editarFormato');
    Route::put('formato/{id}/actualizar'    ,'formatosController@actionActualizarFormato')->name('actualizarFormato');
    Route::get('formato/{id}/Borrar'        ,'formatosController@actionBorrarFormato')->name('borrarFormato');    
    Route::get('formato/excel'              ,'formatosController@exportCatRubrosExcel')->name('downloadrubros');
    Route::get('formato/pdf'                ,'formatosController@exportCatRubrosPdf')->name('catrubrosPDF');     

    //catalogo de documentos
    Route::get('docto/buscar/todos'        ,'doctosController@actionBuscarDocto')->name('buscarDocto');    
    Route::get('docto/nuevo'               ,'doctosController@actionNuevoDocto')->name('nuevoDocto');
    Route::post('docto/nuevo/alta'         ,'doctosController@actionAltaNuevoDocto')->name('AltaNuevoDocto');
    Route::get('docto/ver/todos'           ,'doctosController@actionVerDoctos')->name('verDoctos');
    Route::get('docto/{id}/editar/formato' ,'doctosController@actionEditarDocto')->name('editarDocto');
    Route::put('docto/{id}/actualizar'     ,'doctosController@actionActualizarDocto')->name('actualizarDocto');    
    Route::get('docto/{id}/editar/formato1','doctosController@actionEditarDocto1')->name('editarDocto1');
    Route::put('docto/{id}/actualizar1'    ,'doctosController@actionActualizarDocto1')->name('actualizarDocto1');
    Route::get('docto/{id}/Borrar'         ,'doctosController@actionBorrarDocto')->name('borrarDocto');    
    Route::get('docto/excel'               ,'doctosController@exportCatDoctosExcel')->name('catDoctosExcel');
    Route::get('docto/pdf'                 ,'doctosController@exportCatDoctosPdf')->name('catDoctosPDF');     

    //Municipios sedesem
    Route::get('municipio/ver/todos','catalogosmunicipiosController@actionVermunicipios')->name('verMunicipios');
    Route::get('municipio/excel'    ,'catalogosmunicipiosController@exportCatmunicipiosExcel')->name('downloadmunicipios');
    Route::get('municipio/pdf'      ,'catalogosmunicipiosController@exportCatmunicipiosPdf')->name('catmunicipiosPDF');
    
    //Registro al torneo
    //Ficha del equipo
    Route::get('ficha/nueva'       ,'fichaeqController@actionNuevaFicha')->name('nuevaficha');
    Route::post('ficha/alta'       ,'fichaeqController@actionAltaNuevaFicha')->name('altanuevaficha');
    Route::get('ficha/ver'         ,'fichaeqController@actionVerFicha')->name('verficha');
    Route::get('ficha/buscar'      ,'fichaeqController@actionBuscarFicha')->name('buscarficha');    
    Route::get('ficha/{id}/editar' ,'fichaeqController@actionEditarFicha')->name('editarficha');
    Route::put('ficha/{id}/update' ,'fichaeqController@actionActualizarFicha')->name('actualizarficha');
    Route::get('ficha/{id}/Borrar' ,'fichaeqController@actionBorrarFicha')->name('borrarficha');
    Route::get('ficha/excel'       ,'fichaeqController@exportFichaExcel')->name('exportfichaexcel');
    Route::get('ficha/pdf'         ,'fichaeqController@exportFichaPdf')->name('exportfichapdf');

    Route::get('ficha/{id}/editar1','fichaeqController@actionEditarFicha1')->name('editarficha1');
    Route::put('ficha/{id}/update1','fichaeqController@actionActualizarFicha1')->name('actualizarficha1'); 
 
    //Route::get('oscs5/ver/todas'       ,'oscController@actionVerOsc5')->name('verOsc5');
    //Route::get('oscs5/{id}/editar/oscs','oscController@actionEditarOsc5')->name('editarOsc5');
    //Route::put('oscs5/{id}/actualizar' ,'oscController@actionActualizarOsc5')->name('actualizarOsc5');    

    //Requisitos de operación
    //Metadato del equipo de futbol
    Route::get('padron/nueva'      ,'padronController@actionNuevoPadron')->name('nuevopadron');
    Route::post('padron/alta'      ,'padronController@actionAltaNuevoPadron')->name('altanuevopadron');
    Route::get('padron/ver'        ,'padronController@actionVerPadron')->name('verpadron');
    Route::get('padron/buscar'     ,'padronController@actionBuscarPadron')->name('buscarpadron');    
    Route::get('padron/{id}/editar','padronController@actionEditarPadron')->name('editarpadron');
    Route::put('padron/{id}/update','padronController@actionActualizarPadron')->name('actualizarpadron');
    Route::get('padron/{id}/Borrar','padronController@actionBorrarPadron')->name('borrarpadron');
    Route::get('padron/excel'      ,'padronController@actionExportPadronExcel')->name('exportpadronexcel');
    Route::get('padron/pdf'        ,'padronController@actionExportPadronPdf')->name('exportpadronpdf');

    Route::get('padron/{id}/editar1','padronController@actionEditarPadron1')->name('editarpadron1');
    Route::put('padron/{id}/update1','padronController@actionActualizarPadron1')->name('actualizarpadron1');
    Route::get('padron/{id}/editar2','padronController@actionEditarPadron2')->name('editarpadron2');
    Route::put('padron/{id}/update2','padronController@actionActualizarPadron2')->name('actualizarpadron2');    

    //Concluir registro
    Route::get('concluir_registro/nueva'        ,'regequipoController@actionNuevoEquipo')->name('nuevoequipo');
    Route::post('concluir_registro/alta'        ,'regequipoController@actionAltaNuevoEquipo')->name('altanuevoequipo');
    Route::get('concluir_registro/ver'          ,'regequipoController@actionVerEquipo')->name('verequipo');
    Route::get('concluir_registro/buscar'       ,'regequipoController@actionBuscarEquipo')->name('buscarequipo');    
    Route::get('concluir_registro/{id}/editar'  ,'regequipoController@actionEditarEquipo')->name('editarequipo');
    Route::put('concluir_registro/{id}/update'  ,'regequipoController@actionActualizarEquipo')->name('actualizarequipo');
    Route::get('concluir_registro/{id}/Borrar'  ,'regequipoController@actionBorrarEquipo')->name('borrarequipo');
    Route::get('concluir_registro/equipos/excel','regequipoController@exportEquiposExcel')->name('exportequiposexcel');
    Route::get('concluir_registro/equipos/pdf'  ,'regequipoController@exportEquiposPdf')->name('exportequipospdf');
    Route::get('concluir_registro/{id}/excel'   ,'regequipoController@exportEquipoExcel')->name('exportequipoexcel');
    Route::get('concluir_registro/{id}/pdf'     ,'regequipoController@exportEquipoPdf')->name('exportequipopdf');    


    //Indicadores
    Route::get('indicador/ver/todos'        ,'indicadoresController@actionVerCumplimiento')->name('vercumplimiento');
    Route::get('indicador/buscar/todos'     ,'indicadoresController@actionBuscarCumplimiento')->name('buscarcumplimiento');    
    Route::get('indicador/ver/todamatriz'   ,'indicadoresController@actionVermatrizCump')->name('vermatrizcump');
    Route::get('indicador/buscar/matriz'    ,'indicadoresController@actionBuscarmatrizCump')->name('buscarmatrizcump');      
    Route::get('indicador/ver/todasvisitas' ,'indicadoresController@actionVerCumplimientovisitas')->name('vercumplimientovisitas');
    Route::get('indicador/buscar/allvisitas','indicadoresController@actionBuscarCumplimientovisitas')->name('buscarcumplimientovisitas');    
    Route::get('indicador/{id}/oficiopdf'   ,'indicadoresController@actionOficioInscripPdf')->name('oficioInscripPdf'); 

    //Estadísticas
    //equipos
    Route::get('numeralia/graficaixreg'   ,'estadisticaEqController@actionEqxRegion')->name('eqxregion');
    Route::get('numeralia/graficaixmpio'  ,'estadisticaEqController@actionEqxMpio')->name('eqxmpio');
    Route::get('numeralia/graficaixcate'  ,'estadisticaEqController@actionEqxCate')->name('eqxcate');    
    Route::get('numeralia/graficaixrama'  ,'estadisticaEqController@actionEqxRama')->name('eqxrama'); 
    Route::get('numeralia/filtrobitacora' ,'estadisticaEqController@actionVerBitacora')->name('verbitacora');        
    Route::post('numeralia/bitacora'      ,'estadisticaEqController@actionBitacora')->name('bitacora'); 
    Route::get('numeralia/mapaxmpio'      ,'estadisticaEqController@actiongeorefxmpio')->name('georefxmpio');            
    Route::get('numeralia/mapas'          ,'estadisticaEqController@Mapas')->name('verMapas');        
    Route::get('numeralia/mapas2'         ,'estadisticaEqController@Mapas2')->name('verMapas2');        
    Route::get('numeralia/mapas3'         ,'estadisticaEqController@Mapas3')->name('verMapas3');        

    //padrón
    Route::get('numeralia/graficpadxedo'    ,'estadisticaPadronController@actionPadronxEdo')->name('padronxedo');
    //Route::get('numeralia/graficpadxmpio' ,'estadisticaPadronController@actionPadronxMpio')->name('padronxmpio');
    Route::get('numeralia/graficpadxserv'   ,'estadisticaPadronController@actionPadronxServicio')->name('padronxservicio');
    Route::get('numeralia/graficpadxsexo'   ,'estadisticaPadronController@actionPadronxsexo')->name('padronxsexo');
    Route::get('numeralia/graficpadxedad'   ,'estadisticaPadronController@actionPadronxedad')->name('padronxedad');
    Route::get('numeralia/graficpadxranedad','estadisticaPadronController@actionPadronxRangoedad')->name('padronxrangoedad');

    //Agenda
    Route::get('numeralia/graficaagenda1'     ,'progdilController@actionVerProgdilGraficaxmes')->name('verprogdilgraficaxmes');    
    Route::post('numeralia/graficaagendaxmes' ,'progdilController@actionProgdilGraficaxmes')->name('progdilgraficaxmes');
    Route::get('numeralia/graficaagenda2'     ,'progdilController@actionVerprogdilGraficaxtipo')->name('verprogdilgraficaxtipo');        
    Route::post('numeralia/graficaagendaxtipo','progdilController@actionProgdilGraficaxtipo')->name('progdilgraficaxtipo');

    // Email related routes
    Route::get('mail/ver/todos'        ,'mailController@actionVerContactos')->name('vercontactos');
    Route::get('mail/buscar/todos'     ,'mailController@actionBuscarContactos')->name('buscarcontactos');    
    Route::get('mail/{id}/editar/email','mailController@actionEditarEmail')->name('editaremail');
    //Route::put('mail/{id}/email'     ,'mailController@actionEmail')->name('Email'); 

    Route::get('mail/email'            ,'mailController@actionEmail')->name('Email'); 
    Route::put('mail/emailbienvenida'  ,'mailController@actionEmailBienve')->name('emailbienve'); 
    //Route::post('mail/send'          ,'mailController@send')->name('send');     
});

