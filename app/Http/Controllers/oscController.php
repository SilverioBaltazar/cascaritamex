<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests\oscRequest;
use App\Http\Requests\osc1Request;
use App\Http\Requests\osc2Request;
use App\Http\Requests\osc5Request;
//use App\Http\Requests\iapsjuridicoRequest;
use App\regOscModel;
//use App\regIapJuridicoModel;
use App\regBitacoraModel;
use App\regMunicipioModel;
use App\regRubroModel;
use App\regEntidadesModel; 
use App\regVigenciaModel;
use App\regInmuebleedoModel;
use App\regPeriodosaniosModel;
use App\regMesesModel;
use App\regDiasModel;
use App\regPfiscalesModel;

// Exportar a excel 
use App\Exports\ExcelExportOsc;
use Maatwebsite\Excel\Facades\Excel;
// Exportar a pdf
use PDF;
//use Options;

class oscController extends Controller
{

    public function actionBuscarOsc(Request $request)
    {
        $nombre       = session()->get('userlog');
        $pass         = session()->get('passlog');
        if($nombre == NULL AND $pass == NULL){
            return view('sicinar.login.expirada');
        }
        $usuario      = session()->get('usuario');
        $rango        = session()->get('rango');
        $ip           = session()->get('ip');
        $arbol_id     = session()->get('arbol_id');

        $regentidades = regEntidadesModel::select('ENTIDADFEDERATIVA_ID','ENTIDADFEDERATIVA_DESC')
                        ->orderBy('ENTIDADFEDERATIVA_ID','asc')
                        ->get();
        $regmunicipio = regMunicipioModel::join('PE_CAT_ENTIDADES_FED',
                                                'PE_CAT_ENTIDADES_FED.ENTIDADFEDERATIVA_ID', '=', 
                                                'PE_CAT_MUNICIPIOS_SEDESEM.ENTIDADFEDERATIVAID')
                        ->select('PE_CAT_MUNICIPIOS_SEDESEM.ENTIDADFEDERATIVAID',
                          'PE_CAT_ENTIDADES_FED.ENTIDADFEDERATIVA_DESC','PE_CAT_MUNICIPIOS_SEDESEM.MUNICIPIOID',
                          'PE_CAT_MUNICIPIOS_SEDESEM.MUNICIPIONOMBRE')
                        ->wherein('PE_CAT_MUNICIPIOS_SEDESEM.ENTIDADFEDERATIVAID',[9, 11, 15, 22])
                        ->orderBy('PE_CAT_MUNICIPIOS_SEDESEM.ENTIDADFEDERATIVAID','DESC')
                        ->orderBy('PE_CAT_MUNICIPIOS_SEDESEM.MUNICIPIONOMBRE','DESC')
                        ->get();
        $regrubro     = regRubroModel::select('RUBRO_ID','RUBRO_DESC')
                        ->orderBy('RUBRO_ID','asc')
                        ->get(); 
        $regvigencia  = regVigenciaModel::select('ANIO_ID', 'ANIO_DESC')->get();
        $reginmuebles = regInmuebleedoModel::select('INM_ID','INM_DESC')->get();   
        $regperiodos  = regPeriodosaniosModel::select('PERIODO_ID', 'PERIODO_DESC')->get();         
        $regmeses     = regMesesModel::select('MES_ID','MES_DESC')->get();      
        $regdias      = regDiasModel::select('DIA_ID','DIA_DESC')->get();                
        $regtotactivas= regOscModel::selectRaw('COUNT(*) AS TOTAL_ACTIVAS')
                        ->where('OSC_STATUS','S')
                        ->get();
        $regtotinactivas=regOscModel::selectRaw('COUNT(*) AS TOTAL_INACTIVAS')
                        ->where('OSC_STATUS','N')
                        ->get();                                                                       
        //**************************************************************//
        // ***** busqueda https://github.com/rimorsoft/Search-simple ***//
        // ***** video https://www.youtube.com/watch?v=bmtD9GUaszw   ***//                            
        //**************************************************************//
        $name  = $request->get('name');    
        $email = $request->get('email');  
        $bio   = $request->get('bio');    
        $regosc = regOscModel::orderBy('OSC_ID', 'ASC')
                  ->name($name)           //Metodos personalizados es equvalente a ->where('IAP_DESC', 'LIKE', "%$name%");
                  ->email($email)         //Metodos personalizados
                  ->bio($bio)             //Metodos personalizados
                  ->paginate(30);
        if($regosc->count() <= 0){
            toastr()->error('No existen registros de OSC.','Lo siento!',['positionClass' => 'toast-bottom-right']);
            //return redirect()->route('nuevaIap');
        }            
        return view('sicinar.osc.verOsc', compact('nombre','usuario','regosc','regentidades','regmunicipio','regrubro','regvigencia','reginmuebles','regtotactivas','regtotinactivas','regperiodos','regmeses','regdias'));
    }

    public function actionVerOsc(){
        $nombre       = session()->get('userlog');
        $pass         = session()->get('passlog');
        if($nombre == NULL AND $pass == NULL){
            return view('sicinar.login.expirada');
        }
        $usuario      = session()->get('usuario');
        $rango        = session()->get('rango');
        $ip           = session()->get('ip');
        $arbol_id     = session()->get('arbol_id');        

        $regentidades = regEntidadesModel::select('ENTIDADFEDERATIVA_ID','ENTIDADFEDERATIVA_DESC')
                           ->orderBy('ENTIDADFEDERATIVA_ID','asc')
                           ->get();
        $regmunicipio = regMunicipioModel::join('PE_CAT_ENTIDADES_FED','PE_CAT_ENTIDADES_FED.ENTIDADFEDERATIVA_ID', '=', 
                                                                       'PE_CAT_MUNICIPIOS_SEDESEM.ENTIDADFEDERATIVAID')
                        ->select('PE_CAT_MUNICIPIOS_SEDESEM.ENTIDADFEDERATIVAID','PE_CAT_ENTIDADES_FED.ENTIDADFEDERATIVA_DESC','PE_CAT_MUNICIPIOS_SEDESEM.MUNICIPIOID','PE_CAT_MUNICIPIOS_SEDESEM.MUNICIPIONOMBRE')
                        ->wherein('PE_CAT_MUNICIPIOS_SEDESEM.ENTIDADFEDERATIVAID',[9, 11, 15, 22])
                        ->orderBy('PE_CAT_MUNICIPIOS_SEDESEM.ENTIDADFEDERATIVAID','DESC')
                        ->orderBy('PE_CAT_MUNICIPIOS_SEDESEM.MUNICIPIONOMBRE','DESC')
                        ->get();
        $regrubro     = regRubroModel::select('RUBRO_ID','RUBRO_DESC')
                        ->orderBy('RUBRO_ID','asc')
                        ->get();      
        $regvigencia  = regVigenciaModel::select('ANIO_ID', 'ANIO_DESC')->get();
        $reginmuebles = regInmuebleedoModel::select('INM_ID','INM_DESC')->get();  
        $regperiodos  = regPeriodosaniosModel::select('PERIODO_ID', 'PERIODO_DESC')->get();         
        $regmeses     = regMesesModel::select('MES_ID','MES_DESC')->get();      
        $regdias      = regDiasModel::select('DIA_ID','DIA_DESC')->get();                                                  
        $regtotactivas= regOscModel::selectRaw('COUNT(*) AS TOTAL_ACTIVAS')
                        ->where('OSC_STATUS','S')
                        ->get();
        $regtotinactivas=regOscModel::selectRaw('COUNT(*) AS TOTAL_INACTIVAS')
                        ->where('OSC_STATUS','N')
                        ->get();                        
        $regosc       = regOscModel::select('OSC_ID','OSC_DESC','OSC_CALLE','OSC_NUM','OSC_DOM1','OSC_DOM2','OSC_DOM3',
                                     'OSC_COLONIA','MUNICIPIO_ID','ENTIDADFEDERATIVA_ID','RUBRO_ID','OSC_REGCONS',
                                     'OSC_RFC','OSC_CP','OSC_FECCONS','OSC_FECCONS2','OSC_FECCONS3','OSC_TELEFONO',
                                     'OSC_EMAIL','OSC_SWEB','OSC_PRES','OSC_REPLEGAL','OSC_SRIO','OSC_TESORERO',
                                     'OSC_OBJSOC_1','OSC_OBJSOC_2','GRUPO_ID','OSC_FECCERTIFIC','OSC_FECCERTIFIC2',
                                     'OSC_OTRAREF','OSC_OBS1','OSC_OBS2','ANIO_ID','OSC_FVP','OSC_FVP2','OSC_FVP3',
                                     'OSC_FRPP',
                                     'INM_ID','PERIODO_ID1','MES_ID1','DIA_ID1','PERIODO_ID2','MES_ID2','DIA_ID2',
                                     'FACEBOOK','WHATSAPP','GOOGLE','YAHOO','TWITTER','INSTAGRAM',
                                     'LINKEDLN','YOUTOBE','PINTEREST','OTRA_REDSOCIAL','RED_SOCIAL',
                                     'OSC_FOTO1','OSC_FOTO2','OSC_FOTO3','OSC_FOTO4','OSC_FOTO5',
                                     'OSC_GEOREF_LATITUD','OSC_GEOREF_LONGITUD','OSC_STATUS',
                                     'OSC_FECREG','OSC_FECREG3','IP','LOGIN','FECHA_M','FECHA_M3','IP_M','LOGIN_M')
                  ->orderBy('OSC_ID','ASC')
                  ->paginate(30);
        if($regosc->count() <= 0){
            toastr()->error('No existen OSC.','Lo siento!',['positionClass' => 'toast-bottom-right']);
            return redirect()->route('nuevaOsc');
        }
        return view('sicinar.osc.verOsc',compact('nombre','usuario','regosc','regentidades', 'regmunicipio', 'regrubro','regvigencia','reginmuebles','regtotactivas','regtotinactivas','regperiodos','regmeses','regdias'));
    }

    public function actionNuevaOsc(){
        $nombre       = session()->get('userlog');
        $pass         = session()->get('passlog');
        if($nombre == NULL AND $pass == NULL){
            return view('sicinar.login.expirada');
        }
        $usuario      = session()->get('usuario');
        $rango        = session()->get('rango');
        $ip           = session()->get('ip');
        $arbol_id     = session()->get('arbol_id');

        $regentidades = regEntidadesModel::select('ENTIDADFEDERATIVA_ID','ENTIDADFEDERATIVA_DESC')                    
                        ->orderBy('ENTIDADFEDERATIVA_ID','asc')
                        ->get();
        $regmunicipio = regMunicipioModel::join('PE_CAT_ENTIDADES_FED','PE_CAT_ENTIDADES_FED.ENTIDADFEDERATIVA_ID', '=', 'PE_CAT_MUNICIPIOS_SEDESEM.ENTIDADFEDERATIVAID')
                        ->select('PE_CAT_MUNICIPIOS_SEDESEM.ENTIDADFEDERATIVAID','PE_CAT_ENTIDADES_FED.ENTIDADFEDERATIVA_DESC','PE_CAT_MUNICIPIOS_SEDESEM.MUNICIPIOID','PE_CAT_MUNICIPIOS_SEDESEM.MUNICIPIONOMBRE')
                        ->wherein('PE_CAT_MUNICIPIOS_SEDESEM.ENTIDADFEDERATIVAID',[9, 11, 15, 22])
                        ->orderBy('PE_CAT_MUNICIPIOS_SEDESEM.ENTIDADFEDERATIVAID','DESC')
                        ->orderBy('PE_CAT_MUNICIPIOS_SEDESEM.MUNICIPIONOMBRE','DESC')
                        ->get();
        $regrubro     = regRubroModel::select('RUBRO_ID','RUBRO_DESC')
                        ->orderBy('RUBRO_ID','asc')
                        ->get();  
        $regvigencia  = regVigenciaModel::select('ANIO_ID', 'ANIO_DESC')
                        ->wherein('ANIO_ID',[0, 99]) 
                        ->get();
        $reginmuebles = regInmuebleedoModel::select('INM_ID','INM_DESC')->get();
        $regperiodos  = regPeriodosaniosModel::select('PERIODO_ID', 'PERIODO_DESC')->get();         
        $regmeses     = regMesesModel::select('MES_ID','MES_DESC')->get();      
        $regdias      = regDiasModel::select('DIA_ID','DIA_DESC')->get();     
        $regosc       = regOscModel::select('OSC_ID','OSC_DESC','OSC_CALLE','OSC_NUM','OSC_DOM1','OSC_DOM2','OSC_DOM3',
                                     'OSC_COLONIA','MUNICIPIO_ID','ENTIDADFEDERATIVA_ID','RUBRO_ID','OSC_REGCONS',
                                     'OSC_RFC','OSC_CP','OSC_FECCONS','OSC_FECCONS2','OSC_FECCONS3','OSC_TELEFONO',
                                     'OSC_EMAIL','OSC_SWEB','OSC_PRES','OSC_REPLEGAL','OSC_SRIO','OSC_TESORERO',
                                     'OSC_OBJSOC_1','OSC_OBJSOC_2','GRUPO_ID','OSC_FECCERTIFIC','OSC_FECCERTIFIC2',
                                     'OSC_OTRAREF','OSC_OBS1','OSC_OBS2','ANIO_ID','OSC_FVP','OSC_FVP2','OSC_FVP3',
                                     'OSC_FRPP',
                                     'INM_ID','PERIODO_ID1','MES_ID1','DIA_ID1','PERIODO_ID2','MES_ID2','DIA_ID2',
                                     'FACEBOOK','WHATSAPP','GOOGLE','YAHOO','TWITTER','INSTAGRAM',
                                     'LINKEDLN','YOUTOBE','PINTEREST','OTRA_REDSOCIAL','RED_SOCIAL',
                                     'OSC_FOTO1','OSC_FOTO2','OSC_FOTO3','OSC_FOTO4','OSC_FOTO5',
                                     'OSC_GEOREF_LATITUD','OSC_GEOREF_LONGITUD','OSC_STATUS',
                                     'OSC_FECREG','OSC_FECREG3','IP','LOGIN','FECHA_M','FECHA_M3','IP_M','LOGIN_M')
                         ->orderBy('OSC_ID','asc')->get();
        //dd($unidades);
        return view('sicinar.osc.nuevaOsc',compact('regrubro','regmunicipio','regentidades','regosc','nombre','usuario','regvigencia','reginmuebles','regperiodos','regmeses','regdias'));
    }

    public function actionAltaNuevaOsc(Request $request){
        //dd($request->all());
        $nombre       = session()->get('userlog');
        $pass         = session()->get('passlog');
        if($nombre == NULL AND $pass == NULL){
            return view('sicinar.login.expirada');
        }
        $usuario      = session()->get('usuario');
        $rango        = session()->get('rango');
        $ip           = session()->get('ip');
        $arbol_id     = session()->get('arbol_id');

        /************ Obtenemos la IP ***************************/                
        if (getenv('HTTP_CLIENT_IP')) {
            $ip = getenv('HTTP_CLIENT_IP');
        } elseif (getenv('HTTP_X_FORWARDED_FOR')) {
            $ip = getenv('HTTP_X_FORWARDED_FOR');
        } elseif (getenv('HTTP_X_FORWARDED')) {
            $ip = getenv('HTTP_X_FORWARDED');
        } elseif (getenv('HTTP_FORWARDED_FOR')) {
            $ip = getenv('HTTP_FORWARDED_FOR');
        } elseif (getenv('HTTP_FORWARDED')) {
            $ip = getenv('HTTP_FORWARDED');
        } else {
            $ip = $_SERVER['REMOTE_ADDR'];
        }        

        /************ ALTA  *****************************/ 
        //dd('periodo_d11=',$request->periodo1,'-mes_d1=',$request->mes1,'-dia_d1=',$request->dia1,'--periodo_d2=',$request->periodo_d2,'-mes_d2=',$request->mes_d2,'-dia_d2=',$request->dia_d2,'-iap_feccons=',$request->iap_feccons);
        //if(!empty($request->periodo_d1) and !empty($request->mes_d1) and !empty($request->dia_d1) ){
        //    toastr()->error('muy bien 1....................','¡ok...........!',['positionClass' => 'toast-bottom-right']);
        //    $mes1 = regMesesModel::ObtMes($request->mes_id1);
        //    $dia1 = regDiasModel::ObtDia($request->dia_id1);                
            ////xiap_feccons = $dia1[0]->dia_desc.'/'.$mes1[0]->mes_mes.'/'.$request->periodo_id1;
        //}   ////endif
        
        //if(!empty($request->periodo_d2) and !empty($request->mes_d2) and !empty($request->dia_d2) ){
        //    toastr()->error('muy bien 2....................','¡ok...........!',['positionClass' => 'toast-bottom-right']);
        //    $mes2 = regMesesModel::ObtMes($request->mes_id2);
        //    $dia2 = regDiasModel::ObtDia($request->dia_id2);        
        //}

        //$mes1 = regMesesModel::ObtMes($request->mes_id1);
        //$dia1 = regDiasModel::ObtDia($request->dia_id1);                
        //$mes2 = regMesesModel::ObtMes($request->mes_id2);
        //$dia2 = regDiasModel::ObtDia($request->dia_id2);                

        $osc_id = regOscModel::max('OSC_ID');
        $osc_id = $osc_id+1;

        $nuevaiap = new regOscModel();
        $name1 =null;
        //Comprobar  si el campo foto1 tiene un archivo asignado:
        if($request->hasFile('osc_foto1')){
           $name1 = $osc_id.'_'.$request->file('osc_foto1')->getClientOriginalName(); 
           //$file->move(public_path().'/images/', $name1);
           //sube el archivo a la carpeta del servidor public/images/
           $request->file('osc_foto1')->move(public_path().'/images/', $name1);
        }
        $name2 =null;
        //Comprobar  si el campo foto2 tiene un archivo asignado:        
        if($request->hasFile('osc_foto2')){
           $name2 = $osc_id.'_'.$request->file('osc_foto2')->getClientOriginalName(); 
           //sube el archivo a la carpeta del servidor public/images/
           $request->file('osc_foto2')->move(public_path().'/images/', $name2);
        }
        $name3 =null;
        //Comprobar  si el campo foto2 tiene un archivo asignado:        
        if($request->hasFile('osc_foto3')){
           $name3 = $osc_id.'_'.$request->file('osc_foto3')->getClientOriginalName(); 
           //sube el archivo a la carpeta del servidor public/images/
           $request->file('osc_foto3')->move(public_path().'/images/', $name3);
        }        

        $nuevaiap->OSC_ID      = $osc_id;
        $nuevaiap->OSC_DESC    = substr(trim(strtoupper($request->osc_desc)),0, 99);
        $nuevaiap->OSC_DOM1    = substr(trim(strtoupper($request->osc_dom1)),0,149);
        $nuevaiap->OSC_DOM2    = substr(trim(strtoupper($request->osc_dom2)),0,149);
        $nuevaiap->OSC_DOM3    = substr(trim(strtoupper($request->osc_dom3)),0,149);
        //$nuevaiap->OSC_OTRAREF = strtoupper($request->osc_otraref);
        //$nuevaiap->OSC_COLONIA = strtoupper($request->osc_colonia);
        $nuevaiap->MUNICIPIO_ID= $request->municipio_id;
        $nuevaiap->ENTIDADFEDERATIVA_ID = $request->entidadfederativa_id;
        $nuevaiap->RUBRO_ID    = $request->rubro_id;
        $nuevaiap->OSC_REGCONS = substr(trim(strtoupper($request->osc_regcons)),0,49);
        $nuevaiap->OSC_RFC     = substr(trim(strtoupper($request->osc_rfc))    ,0,17);
        $nuevaiap->OSC_CP      = $request->osc_cp;
        
        //$nuevaiap->OSC_FECCONS = date('Y/m/d', strtotime(trim($dia1[0]->dia_desc.'/'.$mes1[0]->mes_mes.'/'.$request->periodo_id1) ));
        //$nuevaiap->OSC_FECCONS2= trim($dia1[0]->dia_desc.'/'.$mes1[0]->mes_mes.'/'.$request->periodo_id1);

        //$nuevoiap->PERIODO_ID1 = $request->periodo_id1;                
        //$nuevoiap->MES_ID1     = $request->mes_id1;                
        //$nuevoiap->DIA_ID1     = $request->dia_id1;       
        $nuevaiap->OSC_FECCONS2= substr(trim($request->osc_feccons2),0,10);    
        $nuevaiap->OSC_FRPP    = substr(trim($request->osc_frpp)    ,0,30);    

        $nuevaiap->ANIO_ID     = $request->anio_id;        
        
        //$nuevaiap->IAP_FVP     = date('Y/m/d', strtotime(trim($dia2[0]->dia_desc.'/'.$mes2[0]->mes_mes.'/'.$request->periodo_id2) ));
        //$nuevaiap->IAP_FVP2    = trim($dia2[0]->dia_desc.'/'.$mes2[0]->mes_mes.'/'.$request->periodo_id2);        
        //$nuevoiap->PERIODO_ID2 = $request->periodo_id2;                
        //$nuevoiap->MES_ID2     = $request->mes_id2;                
        //$nuevoiap->DIA_ID2     = $request->dia_id2;  
        $nuevaiap->OSC_FVP2    = substr(trim($request->osc_fvp2),0,10);    

        $nuevaiap->INM_ID      = $request->inm_id;        
        $nuevaiap->OSC_TELEFONO= substr(trim(strtoupper($request->osc_telefono)),0,  59);
        $nuevaiap->OSC_EMAIL   = substr(strtolower($request->osc_email)         ,0, 149);
        $nuevaiap->OSC_SWEB    = substr(trim(strtolower($request->osc_sweb)),    0,  99);
        $nuevaiap->OSC_PRES    = substr(trim(strtoupper($request->osc_pres)),    0,  79);
        $nuevaiap->OSC_REPLEGAL= substr(trim(strtoupper($request->osc_replegal)),0, 149);
        $nuevaiap->OSC_SRIO    = substr(trim(strtoupper($request->osc_srio)),    0,  79);        
        $nuevaiap->OSC_TESORERO= substr(trim(strtoupper($request->osc_tesorero)),0,  79);
        $nuevaiap->OSC_OBJSOC_1= substr(trim(strtoupper($request->osc_objsoc_1)),0,3999);
        $nuevaiap->OSC_OBJSOC_2= substr(trim(strtoupper($request->osc_objsoc_2)),0,3999);
        $nuevaiap->OSC_OBS1    = substr(trim(strtoupper($request->osc_obs)),     0,1999);
        $nuevaiap->FACEBOOK    = $request->facebook;
        $nuevaiap->WHATSAPP    = $request->whatsapp;
        $nuevaiap->GOOGLE      = $request->google;
        $nuevaiap->YAHOO       = $request->yahoo;
        $nuevaiap->TWITTER     = $request->twitter;
        $nuevaiap->INSTAGRAM   = $request->instagram;                
        $nuevaiap->LINKEDLN    = $request->linkedln;
        $nuevaiap->YOUTOBE     = $request->yotobe;
        $nuevaiap->PINTEREST   = $request->pinterest;
        $nuevaiap->OTRA        = $request->otra;        
        $nuevaiap->RED_SOCIAL  = substr(trim(strtoupper($request->red_social)),  0,149);
        $nuevaiap->OSC_GEOREF_LATITUD  = $request->osc_georef_latitud;
        $nuevaiap->OSC_GEOREF_LONGITUD = $request->osc_georef_longitud;
        
        $nuevaiap->OSC_FOTO1   = $name1;
        $nuevaiap->OSC_FOTO2   = $name2;
        $nuevaiap->OSC_FOTO3   = $name3;

        $nuevaiap->IP          = $ip;
        $nuevaiap->LOGIN       = $nombre;         // Usuario ;
        //dd($nuevaiap);
        $nuevaiap->save();
        if($nuevaiap->save() == true){
            toastr()->success('OSC dada de alta.','ok!',['positionClass' => 'toast-bottom-right']);

            /************ Bitacora inicia *************************************/ 
            setlocale(LC_TIME, "spanish");        
            $xip          = session()->get('ip');
            $xperiodo_id  = (int)date('Y');
            $xprograma_id = 1;
            $xmes_id      = (int)date('m');
            $xproceso_id  =         3;
            $xfuncion_id  =      3001;
            $xtrx_id      =       145;    //Alta

            $regbitacora = regBitacoraModel::select('PERIODO_ID', 'MES_ID', 'PROCESO_ID', 'FUNCION_ID', 
                                                    'TRX_ID', 'FOLIO', 'NO_VECES', 'FECHA_REG', 'IP', 'LOGIN', 
                                                    'FECHA_M', 'IP_M', 'LOGIN_M')
                           ->where(['PERIODO_ID' => $xperiodo_id, 'MES_ID' => $xmes_id,'PROCESO_ID' => $xproceso_id, 
                                    'FUNCION_ID' => $xfuncion_id, 'TRX_ID' => $xtrx_id, 
                                    'FOLIO' => $osc_id])
                           ->get();
            if($regbitacora->count() <= 0){              // Alta
                $nuevoregBitacora = new regBitacoraModel();              
                $nuevoregBitacora->PERIODO_ID = $xperiodo_id;    // Año de transaccion 
                $nuevoregBitacora->MES_ID     = $xmes_id;        // Mes de transaccion
                $nuevoregBitacora->PROCESO_ID = $xproceso_id;    // Proceso de apoyo
                $nuevoregBitacora->FUNCION_ID = $xfuncion_id;    // Funcion del modelado de procesos 
                $nuevoregBitacora->TRX_ID     = $xtrx_id;        // Actividad del modelado de procesos
                $nuevoregBitacora->FOLIO      = $osc_id;         // Folio    
                $nuevoregBitacora->NO_VECES   = 1;               // Numero de veces            
                $nuevoregBitacora->IP         = $ip;             // IP
                $nuevoregBitacora->LOGIN      = $nombre;         // Usuario 

                $nuevoregBitacora->save();
                if($nuevoregBitacora->save() == true)
                    toastr()->success('Trx de OSC dada de alta en Bitacora.','¡Ok!',['positionClass' => 'toast-bottom-right']);
                else
                    toastr()->error('Error de trx OSC en bitacora. Por favor volver a interlo.','Ups!',['positionClass' => 'toast-bottom-right']);
            }else{                   
                //*********** Obtine el no. de veces *****************************
                $xno_veces = regBitacoraModel::where(['PERIODO_ID' => $xperiodo_id,'MES_ID'     => $xmes_id, 
                                                      'PROCESO_ID' => $xproceso_id,'FUNCION_ID' => $xfuncion_id, 
                                                      'TRX_ID'     => $xtrx_id,    'FOLIO'      => $osc_id])
                             ->max('NO_VECES');
                $xno_veces = $xno_veces+1;                        
                //*********** Termina de obtener el no de veces *****************************         
                $regbitacora = regBitacoraModel::select('NO_VECES','IP_M','LOGIN_M','FECHA_M')
                               ->where(['PERIODO_ID' => $xperiodo_id,'MES_ID' => $xmes_id,'PROCESO_ID' => $xproceso_id, 
                                        'FUNCION_ID' => $xfuncion_id,'TRX_ID' => $xtrx_id,'FOLIO'      => $osc_id])
                               ->update([
                                         'NO_VECES' => $regbitacora->NO_VECES = $xno_veces,
                                         'IP_M' => $regbitacora->IP           = $ip,
                                         'LOGIN_M' => $regbitacora->LOGIN_M   = $nombre,
                                         'FECHA_M' => $regbitacora->FECHA_M   = date('Y/m/d')  //date('d/m/Y')
                                       ]);
                toastr()->success('Trx de OSC actualizada en Bitacora.','¡Ok!',['positionClass' => 'toast-bottom-right']);
            }
            /************ Bitacora termina *************************************/ 

        }else{
            toastr()->error('Error de trx OSC. Por favor volver a interlo.','Ups!',['positionClass' => 'toast-bottom-right']);
        }
        return redirect()->route('verOsc');
    }

    public function actionVerOsc5(){
        $nombre       = session()->get('userlog');
        $pass         = session()->get('passlog');
        if($nombre == NULL AND $pass == NULL){
            return view('sicinar.login.expirada');
        }
        $usuario      = session()->get('usuario');
        $rango        = session()->get('rango');
        $ip           = session()->get('ip');
        $arbol_id     = session()->get('arbol_id');        

        $regentidades = regEntidadesModel::select('ENTIDADFEDERATIVA_ID','ENTIDADFEDERATIVA_DESC')
                        ->orderBy('ENTIDADFEDERATIVA_ID','asc')
                        ->get();
        $regmunicipio = regMunicipioModel::join('PE_CAT_ENTIDADES_FED',
                                                'PE_CAT_ENTIDADES_FED.ENTIDADFEDERATIVA_ID', '=', 
                                                'PE_CAT_MUNICIPIOS_SEDESEM.ENTIDADFEDERATIVAID')
                        ->select('PE_CAT_MUNICIPIOS_SEDESEM.ENTIDADFEDERATIVAID',
                                 'PE_CAT_ENTIDADES_FED.ENTIDADFEDERATIVA_DESC',
                                 'PE_CAT_MUNICIPIOS_SEDESEM.MUNICIPIOID','PE_CAT_MUNICIPIOS_SEDESEM.MUNICIPIONOMBRE')
                        ->wherein('PE_CAT_MUNICIPIOS_SEDESEM.ENTIDADFEDERATIVAID',[9, 11, 15, 22])
                        ->orderBy('PE_CAT_MUNICIPIOS_SEDESEM.ENTIDADFEDERATIVAID','DESC')
                        ->orderBy('PE_CAT_MUNICIPIOS_SEDESEM.MUNICIPIONOMBRE','DESC')
                        ->get();
        $regrubro     = regRubroModel::select('RUBRO_ID','RUBRO_DESC')
                        ->orderBy('RUBRO_ID','asc')
                        ->get();   
        $regvigencia  = regVigenciaModel::select('ANIO_ID', 'ANIO_DESC')->get();
        $reginmuebles = regInmuebleedoModel::select('INM_ID','INM_DESC')->get(); 
        $regperiodos  = regPeriodosaniosModel::select('PERIODO_ID', 'PERIODO_DESC')->get();         
        $regmeses     = regMesesModel::select('MES_ID','MES_DESC')->get();      
        $regdias      = regDiasModel::select('DIA_ID','DIA_DESC')->get();         
        $regosc       = regOscModel::select('OSC_ID','OSC_DESC','OSC_CALLE','OSC_NUM','OSC_DOM1','OSC_DOM2','OSC_DOM3',
                                     'OSC_COLONIA','MUNICIPIO_ID','ENTIDADFEDERATIVA_ID','RUBRO_ID','OSC_REGCONS',
                                     'OSC_RFC','OSC_CP','OSC_FECCONS','OSC_FECCONS2','OSC_FECCONS3','OSC_TELEFONO',
                                     'OSC_EMAIL','OSC_SWEB','OSC_PRES','OSC_REPLEGAL','OSC_SRIO','OSC_TESORERO',
                                     'OSC_OBJSOC_1','OSC_OBJSOC_2','GRUPO_ID','OSC_FECCERTIFIC','OSC_FECCERTIFIC2',
                                     'OSC_OTRAREF','OSC_OBS1','OSC_OBS2','ANIO_ID','OSC_FVP','OSC_FVP2','OSC_FVP3',
                                     'INM_ID','PERIODO_ID1','MES_ID1','DIA_ID1','PERIODO_ID2','MES_ID2','DIA_ID2',
                                     'FACEBOOK','WHATSAPP','GOOGLE','YAHOO','TWITTER','INSTAGRAM',
                                     'LINKEDLN','YOUTOBE','PINTEREST','OTRA_REDSOCIAL','RED_SOCIAL',
                                     'OSC_FOTO1','OSC_FOTO2','OSC_FOTO3','OSC_FOTO4','OSC_FOTO5',
                                     'OSC_GEOREF_LATITUD','OSC_GEOREF_LONGITUD','OSC_STATUS',
                                     'OSC_FECREG','OSC_FECREG3','IP','LOGIN','FECHA_M','FECHA_M3','IP_M','LOGIN_M')
                        ->where(  'OSC_ID',$arbol_id)
                        ->orderBy('OSC_ID','ASC')
                        ->paginate(30);
                        //dd($regosc,'llave:'.$arbol_id,$rango,$usuario);
        if($regosc->count() <= 0){
            toastr()->error('No existe OSC.','Lo siento!',['positionClass' => 'toast-bottom-right']);
            //return redirect()->route('nuevaIap');
        }
        return view('sicinar.osc.verOsc5',compact('nombre','usuario','regosc','regentidades','regmunicipio','regrubro','regvigencia','reginmuebles','regperiodos','regmeses','regdias'));
    }


    public function actionEditarOsc($id){
        $nombre        = session()->get('userlog');
        $pass          = session()->get('passlog');
        if($nombre == NULL AND $pass == NULL){
            return view('sicinar.login.expirada');
        }
        $usuario       = session()->get('usuario');
        $rango         = session()->get('rango');
        $arbol_id      = session()->get('arbol_id');        

        $regentidades = regEntidadesModel::select('ENTIDADFEDERATIVA_ID','ENTIDADFEDERATIVA_DESC')         
                           ->orderBy('ENTIDADFEDERATIVA_ID','asc')
                           ->get();
        $regmunicipio = regMunicipioModel::join('PE_CAT_ENTIDADES_FED','PE_CAT_ENTIDADES_FED.ENTIDADFEDERATIVA_ID', '=', 'PE_CAT_MUNICIPIOS_SEDESEM.ENTIDADFEDERATIVAID')
                        ->select('PE_CAT_MUNICIPIOS_SEDESEM.ENTIDADFEDERATIVAID','PE_CAT_ENTIDADES_FED.ENTIDADFEDERATIVA_DESC','PE_CAT_MUNICIPIOS_SEDESEM.MUNICIPIOID','PE_CAT_MUNICIPIOS_SEDESEM.MUNICIPIONOMBRE')
                        ->wherein('PE_CAT_MUNICIPIOS_SEDESEM.ENTIDADFEDERATIVAID',[9, 11, 15, 22])
                        ->orderBy('PE_CAT_MUNICIPIOS_SEDESEM.ENTIDADFEDERATIVAID','DESC')
                        ->orderBy('PE_CAT_MUNICIPIOS_SEDESEM.MUNICIPIONOMBRE','DESC')
                        ->get();
        $regrubro     = regRubroModel::select('RUBRO_ID','RUBRO_DESC')
                        ->orderBy('RUBRO_ID','asc')
                        ->get();   
        $regvigencia  = regVigenciaModel::select('ANIO_ID', 'ANIO_DESC')
                        ->wherein('ANIO_ID',[0, 99]) 
                        ->get();        
        $reginmuebles = regInmuebleedoModel::select('INM_ID','INM_DESC')->get();
        $regperiodos  = regPeriodosaniosModel::select('PERIODO_ID', 'PERIODO_DESC')->get();         
        $regmeses     = regMesesModel::select('MES_ID','MES_DESC')->get();      
        $regdias      = regDiasModel::select('DIA_ID','DIA_DESC')->get();         
        $regosc       = regOscModel::select('OSC_ID','OSC_DESC','OSC_CALLE','OSC_NUM','OSC_DOM1','OSC_DOM2','OSC_DOM3',
                                     'OSC_COLONIA','MUNICIPIO_ID','ENTIDADFEDERATIVA_ID','RUBRO_ID','OSC_REGCONS',
                                     'OSC_RFC','OSC_CP','OSC_FECCONS','OSC_FECCONS2','OSC_FECCONS3','OSC_TELEFONO',
                                     'OSC_EMAIL','OSC_SWEB','OSC_PRES','OSC_REPLEGAL','OSC_SRIO','OSC_TESORERO',
                                     'OSC_OBJSOC_1','OSC_OBJSOC_2','GRUPO_ID','OSC_FECCERTIFIC','OSC_FECCERTIFIC2',
                                     'OSC_OTRAREF','OSC_OBS1','OSC_OBS2','ANIO_ID','OSC_FVP','OSC_FVP2','OSC_FVP3',
                                     'OSC_FRPP',
                                     'INM_ID','PERIODO_ID1','MES_ID1','DIA_ID1','PERIODO_ID2','MES_ID2','DIA_ID2',
                                     'FACEBOOK','WHATSAPP','GOOGLE','YAHOO','TWITTER','INSTAGRAM',
                                     'LINKEDLN','YOUTOBE','PINTEREST','OTRA_REDSOCIAL','RED_SOCIAL',
                                     'OSC_FOTO1','OSC_FOTO2','OSC_FOTO3','OSC_FOTO4','OSC_FOTO5',
                                     'OSC_GEOREF_LATITUD','OSC_GEOREF_LONGITUD','OSC_STATUS',
                                     'OSC_FECREG','OSC_FECREG3','IP','LOGIN','FECHA_M','FECHA_M3','IP_M','LOGIN_M')
                        ->where(  'OSC_ID',$id)
                        ->orderBy('OSC_ID','ASC')
                        ->first();
        if($regosc->count() <= 0){
            toastr()->error('No existen registros de OSC.','Lo siento!',['positionClass' => 'toast-bottom-right']);
            return redirect()->route('nuevaIap');
        }
        return view('sicinar.osc.editarOsc',compact('nombre','usuario','regosc','regentidades','regmunicipio','regrubro','regvigencia','reginmuebles','regperiodos','regmeses','regdias'));

    }

    public function actionActualizarOsc(oscRequest $request, $id){
        $nombre        = session()->get('userlog');
        $pass          = session()->get('passlog');
        if($nombre == NULL AND $pass == NULL){
            return view('sicinar.login.expirada');
        }
        $usuario       = session()->get('usuario');
        $rango         = session()->get('rango');
        $ip            = session()->get('ip');
        $arbol_id      = session()->get('arbol_id');        

        // **************** actualizar ******************************
        $regosc = regOscModel::where('OSC_ID',$id);
        if($regosc->count() <= 0)
            toastr()->error('No existe OSC.','¡Por favor volver a intentar!',['positionClass' => 'toast-bottom-right']);
        else{        
            //*************** Actualizar ********************************/
            //xiap_feccons =null;
            //if(!empty($request->periodo_d1) and !empty($request->mes_d1) and !empty($request->dia_d1) ){
            //    //toastr()->error('muy bien 1....................','¡ok...........!',['positionClass' => 'toast-bottom-right']);
            //    $mes1 = regMesesModel::ObtMes($request->mes_id1);
            //    $dia1 = regDiasModel::ObtDia($request->dia_id1);                
                //xiap_feccons = $dia1[0]->dia_desc.'/'.$mes1[0]->mes_mes.'/'.$request->periodo_id1;
            //}   //endif
            //if(!empty($request->periodo_d2) and !empty($request->mes_d2) and !empty($request->dia_d2) ){
            //    $mes2 = regMesesModel::ObtMes($request->mes_id2);
            //    $dia2 = regDiasModel::ObtDia($request->dia_id2);        
            //}

            //$mes1 = regMesesModel::ObtMes($request->mes_id1);
            //$dia1 = regDiasModel::ObtDia($request->dia_id1);                
            //$mes2 = regMesesModel::ObtMes($request->mes_id2);
            //$dia2 = regDiasModel::ObtDia($request->dia_id2);                    
            //dd('año 1:',$request->periodo_id1, ' año 2:',$request->periodo_id2,' mes1:',$mes1[0]->mes_mes,' dia1:',$dia1[0]->dia_desc,' mes2:',$mes2[0]->mes_mes, ' dia2:',$dia2[0]->dia_desc);
            $name1 =null;
            //   if(!empty($_PUT['iap_foto1'])){
            if(isset($request->osc_foto1)){
                if(!empty($request->osc_foto1)){
                    //Comprobar  si el campo foto1 tiene un archivo asignado:
                    if($request->hasFile('osc_foto1')){
                        $name1 = $id.'_'.$request->file('osc_foto1')->getClientOriginalName(); 
                        //sube el archivo a la carpeta del servidor public/images/
                        $request->file('osc_foto1')->move(public_path().'/images/', $name1);

                        $regosc = regOscModel::where('OSC_ID',$id)        
                                  ->update([                
                                            'OSC_FOTO1' => $name1,
                                            'IP_M'      => $ip,
                                            'LOGIN_M'   => $nombre,
                                            'FECHA_M'   => date('Y/m/d')    //date('d/m/Y')                                
                                           ]);
                        toastr()->success('Foto 1 actualizada.','¡Ok!',['positionClass' => 'toast-bottom-right']);                      
                    }
                }
            }
            $name2 =null;
            if (isset($request->osc_foto2) and !empty($request->osc_foto2) ) {
               //Comprobar  si el campo foto2 tiene un archivo asignado:        
               if($request->hasFile('osc_foto2')){
                    $name2 = $id.'_'.$request->file('osc_foto2')->getClientOriginalName(); 
                    //sube el archivo a la carpeta del servidor public/images/
                    $request->file('osc_foto2')->move(public_path().'/images/', $name2);

                    $regosc = regOscModel::where('OSC_ID',$id)        
                              ->update([                
                                        'OSC_FOTO2' => $name2,
                                        'IP_M'      => $ip,
                                        'LOGIN_M'   => $nombre,
                                        'FECHA_M'   => date('Y/m/d')    //date('d/m/Y')                                
                                       ]);
                    toastr()->success('Foto 2 actualizada.','¡Ok!',['positionClass' => 'toast-bottom-right']);                         
               }
            }
            $name3 =null;
            if (isset($request->osc_foto3) and !empty($request->osc_foto3) ) {
               //Comprobar  si el campo foto3 tiene un archivo asignado:        
               if($request->hasFile('osc_foto3')){
                    $name3 = $id.'_'.$request->file('osc_foto3')->getClientOriginalName(); 
                    //sube el archivo a la carpeta del servidor public/images/
                    $request->file('osc_foto3')->move(public_path().'/images/', $name3);

                    $regosc = regOscModel::where('OSC_ID',$id)        
                              ->update([                
                                        'OSC_FOTO3' => $name3,
                                        'IP_M'      => $ip,
                                        'LOGIN_M'   => $nombre,
                                        'FECHA_M'   => date('Y/m/d')    //date('d/m/Y')                                
                                       ]);
                    toastr()->success('Foto 3 actualizada.','¡Ok!',['positionClass' => 'toast-bottom-right']);                          
               }
            }            
            //*********** Actualizarn items **********************
            $regosc = regOscModel::where('OSC_ID',$id)        
                      ->update([                
                'OSC_DESC'        => substr(trim(strtoupper($request->osc_desc)),0, 99),
                'OSC_DOM1'        => substr(trim(strtoupper($request->osc_dom1)),0,149),
                'OSC_DOM2'        => substr(trim(strtoupper($request->osc_dom2)),0,149),
                'OSC_DOM3'        => substr(trim(strtoupper($request->osc_dom3)),0,149),
                //'OSC_OTRAREF'   => substr(trim(strtoupper($request->osc_otraref)),0,149),
                'ENTIDADFEDERATIVA_ID' => $request->entidadfederativa_id,                
                'MUNICIPIO_ID'    => $request->municipio_id,
                'RUBRO_ID'        => $request->rubro_id,
                'OSC_REGCONS'     => substr(trim(strtoupper($request->osc_regcons)),0,49),
                'OSC_RFC'         => substr(trim(strtoupper($request->osc_rfc))    ,0,17),
                'OSC_CP'          => $request->osc_cp,
                //'IAP_FECCONS'   => date('Y/m/d', strtotime($request->osc_feccons)), //$request->osc_feccons
                //'IAP_FECCONS'   => date('Y/m/d', strtotime(trim($dia1[0]->dia_desc.'/'.$mes1[0]->mes_mes.'/'.$request->periodo_id1) )),
                //'IAP_FECCONS2'  => trim($dia1[0]->dia_desc.'/'.$mes1[0]->mes_mes.'/'.$request->periodo_id1), 
                'OSC_FECCONS2'    => substr(trim($request->osc_feccons2),0,10),
                'OSC_FRPP'        => substr(trim($request->osc_frpp)    ,0,30),                   

                'ANIO_ID'         => $request->anio_id,                
                //'IAP_FVP'       => date('Y/m/d', strtotime($request->osc_fvp)),
                //'IAP_FVP'       => date('Y/m/d', strtotime(trim($dia2[0]->dia_desc.'/'.$mes2[0]->mes_mes.'/'.$request->periodo_id2) )),
                //'IAP_FVP2'      => trim($dia2[0]->dia_desc.'/'.$mes2[0]->mes_mes.'/'.$request->periodo_id2),
                'OSC_FVP2'        => substr(trim($request->osc_fvp2),0,10),

                'INM_ID'          => $request->inm_id,
                'OSC_TELEFONO'    => substr(trim(strtoupper($request->osc_telefono)),0,  59),
                'OSC_EMAIL'       => substr(trim(strtolower($request->osc_email))   ,0, 149),
                'OSC_SWEB'        => substr(trim(strtolower($request->osc_sweb))    ,0,  99),
                'OSC_PRES'        => substr(trim(strtoupper($request->osc_pres))    ,0,  79),
                'OSC_REPLEGAL'    => substr(trim(strtoupper($request->osc_replegal)),0, 149),
                'OSC_SRIO'        => substr(trim(strtoupper($request->osc_srio))    ,0,  79),
                'OSC_TESORERO'    => substr(trim(strtoupper($request->osc_tesorero)),0,  79),
                'OSC_OBJSOC_1'    => substr(trim(strtoupper($request->osc_objsoc_1)),0,3999),
                'OSC_OBJSOC_2'    => substr(trim(strtoupper($request->osc_objsoc_2)),0,3999),                
                'OSC_OBS1'        => substr(trim(strtoupper($request->osc_obs1))    ,0,1999),        
                'FACEBOOK'        => $request->facebook,
                'WHATSAPP'        => $request->whatsapp,
                'GOOGLE'          => $request->google,
                'YAHOO'           => $request->yahoo,
                'TWITTER'         => $request->twitter,
                'INSTAGRAM'       => $request->instagram,
                'LINKEDLN'        => $request->linkedln,
                'YOUTOBE'         => $request->youtobe,
                'PINTEREST'       => $request->pinterest,
                'OTRA_REDSOCIAL'  => $request->otra,                                                
                'RED_SOCIAL'      => substr(trim(strtoupper($request->red_social))  ,0, 149),                        
                'OSC_GEOREF_LATITUD' => $request->osc_georef_latitud,
                'OSC_GEOREF_LONGITUD'=> $request->osc_georef_longitud,
                'OSC_STATUS'      => $request->osc_status,    

                'IP_M'            => $ip,
                'LOGIN_M'         => $nombre,
                'FECHA_M'         => date('Y/m/d')    //date('d/m/Y')                                
                              ]);
            toastr()->success('OSC actualizada.','¡Ok!',['positionClass' => 'toast-bottom-right']);

            /************ Bitacora inicia *************************************/ 
            setlocale(LC_TIME, "spanish");        
            $xip          = session()->get('ip');
            $xperiodo_id  = (int)date('Y');
            $xprograma_id = 1;
            $xmes_id      = (int)date('m');
            $xproceso_id  =         3;
            $xfuncion_id  =      3001;
            $xtrx_id      =       146;    //Actualizar OSC        
            $regbitacora = regBitacoraModel::select('PERIODO_ID','MES_ID', 'PROCESO_ID', 'FUNCION_ID', 
                                                    'TRX_ID',    'FOLIO',  'NO_VECES',   'FECHA_REG', 'IP', 
                                                    'LOGIN', 'FECHA_M', 'IP_M', 'LOGIN_M')
                           ->where(['PERIODO_ID' => $xperiodo_id, 'PROGRAMA_ID' => $xprograma_id, 'MES_ID' => $xmes_id, 
                                    'PROCESO_ID' => $xproceso_id, 'FUNCION_ID' => $xfuncion_id, 'TRX_ID' => $xtrx_id, 
                                    'FOLIO' => $id])
                           ->get();
            if($regbitacora->count() <= 0){              // Alta
                $nuevoregBitacora = new regBitacoraModel();              
                $nuevoregBitacora->PERIODO_ID = $xperiodo_id;    // Año de transaccion 
                $nuevoregBitacora->MES_ID     = $xmes_id;        // Mes de transaccion
                $nuevoregBitacora->PROCESO_ID = $xproceso_id;    // Proceso de apoyo
                $nuevoregBitacora->FUNCION_ID = $xfuncion_id;    // Funcion del modelado de procesos 
                $nuevoregBitacora->TRX_ID     = $xtrx_id;        // Actividad del modelado de procesos
                $nuevoregBitacora->FOLIO      = $id;             // Folio    
                $nuevoregBitacora->NO_VECES   = 1;               // Numero de veces            
                $nuevoregBitacora->IP         = $ip;             // IP
                $nuevoregBitacora->LOGIN      = $nombre;         // Usuario 

                $nuevoregBitacora->save();
                if($nuevoregBitacora->save() == true)
                    toastr()->success('Trx de actualización de OSC registrada en Bitacora.','¡Ok!',['positionClass' => 'toast-bottom-right']);
                else
                    toastr()->error('Error Trx de actualización de OSC al registrar en bitacora. Por favor volver a interlo.','Ups!',['positionClass' => 'toast-bottom-right']);
            }else{                   
                //*********** Obtine el no. de veces *****************************
                $xno_veces = regBitacoraModel::where(['PERIODO_ID' => $xperiodo_id,'MES_ID'     => $xmes_id, 
                                                      'PROCESO_ID' => $xproceso_id,'FUNCION_ID' => $xfuncion_id, 
                                                      'TRX_ID'     => $xtrx_id,    'FOLIO'      => $id])
                             ->max('NO_VECES');
                $xno_veces = $xno_veces+1;                        
                //*********** Termina de obtener el no de veces *****************************         
                $regbitacora = regBitacoraModel::select('NO_VECES','IP_M','LOGIN_M','FECHA_M')
                               ->where(['PERIODO_ID' => $xperiodo_id,'MES_ID' => $xmes_id, 'PROCESO_ID' => $xproceso_id, 
                                        'FUNCION_ID' => $xfuncion_id,'TRX_ID' => $xtrx_id, 'FOLIO'      => $id])
                               ->update([
                                         'NO_VECES' => $regbitacora->NO_VECES = $xno_veces,
                                         'IP_M'     => $regbitacora->IP       = $ip,
                                         'LOGIN_M'  => $regbitacora->LOGIN_M  = $nombre,
                                         'FECHA_M'  => $regbitacora->FECHA_M  = date('Y/m/d')  //date('d/m/Y')
                                        ]);
                toastr()->success('Trx de actualización de OSC registrada en Bitacora.','¡Ok!',['positionClass' => 'toast-bottom-right']);
            }   /************ Bitacora termina *************************************/                     
        }       /************ Actualizar *******************************************/
        return redirect()->route('verOsc');
    }

    public function actionEditarOsc1($id){
        $nombre        = session()->get('userlog');
        $pass          = session()->get('passlog');
        if($nombre == NULL AND $pass == NULL){
            return view('sicinar.login.expirada');
        }
        $usuario       = session()->get('usuario');
        $rango         = session()->get('rango');

        $regosc = regOscModel::select('OSC_ID','OSC_DESC','OSC_STATUS','OSC_FOTO1','OSC_FOTO2')
                  ->where(  'OSC_ID',$id)
                  ->orderBy('OSC_ID','ASC')
                  ->first();
        if($regosc->count() <= 0){
            toastr()->error('No existe OSC.','Lo siento!',['positionClass' => 'toast-bottom-right']);
        }
        return view('sicinar.osc.editarOsc1',compact('nombre','usuario','regosc'));
    }

    public function actionActualizarOsc1(osc1Request $request, $id){
        $nombre        = session()->get('userlog');
        $pass          = session()->get('passlog');
        if($nombre == NULL AND $pass == NULL){
            return view('sicinar.login.expirada');
        }
        $usuario       = session()->get('usuario');
        $rango         = session()->get('rango');
        $ip            = session()->get('ip');

        // **************** actualizar ******************************
        $regosc = regOscModel::where('OSC_ID',$id);
        if($regosc->count() <= 0)
            toastr()->error('No existe OSC.','¡Por favor volver a intentar!',['positionClass' => 'toast-bottom-right']);
        else{        
            $name01 =null;
            //echo "Escribió en el campo de texto 1: " .'-'. $request->iap_d01 .'-'. "<br><br>"; 
            //echo "Escribió en el campo de texto 1: " . $request->iap_d01 . "<br><br>"; 
            //Comprobar  si el campo foto1 tiene un archivo asignado:
            if($request->hasFile('osc_foto1')){
                echo "Escribió en el campo de texto 3: " .'-'. $request->osc_foto1 .'-'. "<br><br>"; 
                $name01 = $id.'_'.$request->file('osc_foto1')->getClientOriginalName(); 
                //sube el archivo a la carpeta del servidor public/images/
                $request->file('osc_foto1')->move(public_path().'/images/', $name01);

                $regosc = regOscModel::where('OSC_ID',$id)        
                          ->update([                
                                    'OSC_FOTO1' => $name01,
                                    'IP_M'      => $ip,
                                    'LOGIN_M'   => $nombre,
                                    'FECHA_M'   => date('Y/m/d')    //date('d/m/Y')                                
                                    ]);
                toastr()->success('Fotografía 1 actualizada.','¡Ok!',['positionClass' => 'toast-bottom-right']);

                /************ Bitacora inicia *************************************/ 
                setlocale(LC_TIME, "spanish");        
                $xip          = session()->get('ip');
                $xperiodo_id  = (int)date('Y');
                $xprograma_id = 1;
                $xmes_id      = (int)date('m');
                $xproceso_id  =         3;
                $xfuncion_id  =      3001;
                $xtrx_id      =       146;    //Actualizar 
                $regbitacora = regBitacoraModel::select('PERIODO_ID','MES_ID', 'PROCESO_ID', 'FUNCION_ID', 'TRX_ID', 'FOLIO', 'NO_VECES', 'FECHA_REG', 'IP', 'LOGIN', 'FECHA_M', 'IP_M', 'LOGIN_M')
                               ->where(['PERIODO_ID' => $xperiodo_id,'MES_ID' => $xmes_id, 'PROCESO_ID' => $xproceso_id, 'FUNCION_ID' => $xfuncion_id, 'TRX_ID' => $xtrx_id, 'FOLIO' => $id])
                               ->get();
                if($regbitacora->count() <= 0){              // Alta
                    $nuevoregBitacora = new regBitacoraModel();              
                    $nuevoregBitacora->PERIODO_ID = $xperiodo_id;    // Año de transaccion 
                    $nuevoregBitacora->MES_ID     = $xmes_id;        // Mes de transaccion
                    $nuevoregBitacora->PROCESO_ID = $xproceso_id;    // Proceso de apoyo
                    $nuevoregBitacora->FUNCION_ID = $xfuncion_id;    // Funcion del modelado de procesos 
                    $nuevoregBitacora->TRX_ID     = $xtrx_id;        // Actividad del modelado de procesos
                    $nuevoregBitacora->FOLIO      = $id;             // Folio    
                    $nuevoregBitacora->NO_VECES   = 1;               // Numero de veces            
                    $nuevoregBitacora->IP         = $ip;             // IP
                    $nuevoregBitacora->LOGIN      = $nombre;         // Usuario 
                    $nuevoregBitacora->save();
                    if($nuevoregBitacora->save() == true)
                        toastr()->success('Trx de foto 1 actualizada en Bitacora.','¡Ok!',['positionClass' => 'toast-bottom-right']);
                    else
                        toastr()->error('Error de Trx de foto 1 en bitacora. Por favor volver a interlo.','Ups!',['positionClass' => 'toast-bottom-right']);
                }else{                   
                    //*********** Obtine el no. de veces *****************************
                    $xno_veces = regBitacoraModel::where(['PERIODO_ID' => $xperiodo_id,'MES_ID' => $xmes_id, 'PROCESO_ID' => $xproceso_id, 'FUNCION_ID' => $xfuncion_id, 'TRX_ID' => $xtrx_id, 'FOLIO' => $id])
                                ->max('NO_VECES');
                    $xno_veces = $xno_veces+1;                        
                    //*********** Termina de obtener el no de veces *****************************         
                    $regbitacora = regBitacoraModel::select('NO_VECES','IP_M','LOGIN_M','FECHA_M')
                                   ->where(['PERIODO_ID' => $xperiodo_id,'MES_ID' => $xmes_id, 'PROCESO_ID' => $xproceso_id, 'FUNCION_ID' => $xfuncion_id, 'TRX_ID' => $xtrx_id, 'FOLIO' => $id])
                                   ->update([
                                         'NO_VECES' => $regbitacora->NO_VECES = $xno_veces,
                                         'IP_M'     => $regbitacora->IP           = $ip,
                                         'LOGIN_M'  => $regbitacora->LOGIN_M   = $nombre,
                                         'FECHA_M'  => $regbitacora->FECHA_M   = date('Y/m/d')  //date('d/m/Y')
                                        ]);
                    toastr()->success('Trx de foto 1 actualizada en Bitacora.','¡Ok!',['positionClass' => 'toast-bottom-right']);
                }   /************ Bitacora termina *************************************/         
            }            
        }
        return redirect()->route('verOsc');
    }

    public function actionEditarOsc2($id){
        $nombre        = session()->get('userlog');
        $pass          = session()->get('passlog');
        if($nombre == NULL AND $pass == NULL){
            return view('sicinar.login.expirada');
        }
        $usuario       = session()->get('usuario');
        $rango         = session()->get('rango');

        $regosc = regOscModel::select('OSC_ID','OSC_DESC','OSC_STATUS','OSC_FOTO1','OSC_FOTO2')
                  ->where(  'OSC_ID',$id)
                  ->orderBy('OSC_ID','ASC')
                  ->first();
        if($regosc->count() <= 0){
            toastr()->error('No existe OSC.','Lo siento!',['positionClass' => 'toast-bottom-right']);
        }
        return view('sicinar.osc.editarOsc2',compact('nombre','usuario','regosc'));
    }

    public function actionActualizarOsc2(osc2Request $request, $id){
        $nombre        = session()->get('userlog');
        $pass          = session()->get('passlog');
        if($nombre == NULL AND $pass == NULL){
            return view('sicinar.login.expirada');
        }
        $usuario       = session()->get('usuario');
        $rango         = session()->get('rango');
        $ip            = session()->get('ip');

        // **************** actualizar ******************************
        $regosc = regOscModel::where('OSC_ID',$id);
        if($regosc->count() <= 0)
            toastr()->error('No existe OSC.','¡Por favor volver a intentar!',['positionClass' => 'toast-bottom-right']);
        else{        
            $name02 =null;
            //echo "Escribió en el campo de texto 1: " .'-'. $request->iap_d01 .'-'. "<br><br>"; 
            //echo "Escribió en el campo de texto 1: " . $request->iap_d01 . "<br><br>"; 
            //Comprobar  si el campo foto1 tiene un archivo asignado:
            if($request->hasFile('osc_foto2')){
                echo "Escribió en el campo de texto 3: " .'-'. $request->osc_foto2 .'-'. "<br><br>"; 
                $name02 = $id.'_'.$request->file('osc_foto2')->getClientOriginalName(); 
                //sube el archivo a la carpeta del servidor public/images/
                $request->file('osc_foto2')->move(public_path().'/images/', $name02);

                $regosc = regOscModel::where('OSC_ID',$id)        
                          ->update([                
                                    'OSC_FOTO2' => $name02,
                                    'IP_M'      => $ip,
                                    'LOGIN_M'   => $nombre,
                                    'FECHA_M'   => date('Y/m/d')    //date('d/m/Y')                                
                                    ]);
                toastr()->success('Fotografía 2 actualizada.','¡Ok!',['positionClass' => 'toast-bottom-right']);

                /************ Bitacora inicia *************************************/ 
                setlocale(LC_TIME, "spanish");        
                $xip          = session()->get('ip');
                $xperiodo_id  = (int)date('Y');
                $xprograma_id = 1;
                $xmes_id      = (int)date('m');
                $xproceso_id  =         3;
                $xfuncion_id  =      3001;
                $xtrx_id      =       146;    //Actualizar 
                $regbitacora = regBitacoraModel::select('PERIODO_ID','MES_ID', 'PROCESO_ID', 'FUNCION_ID', 'TRX_ID', 'FOLIO', 'NO_VECES', 'FECHA_REG', 'IP', 'LOGIN', 'FECHA_M', 'IP_M', 'LOGIN_M')
                               ->where(['PERIODO_ID' => $xperiodo_id,'MES_ID' => $xmes_id, 'PROCESO_ID' => $xproceso_id, 'FUNCION_ID' => $xfuncion_id, 'TRX_ID' => $xtrx_id, 'FOLIO' => $id])
                               ->get();
                if($regbitacora->count() <= 0){              // Alta
                    $nuevoregBitacora = new regBitacoraModel();              
                    $nuevoregBitacora->PERIODO_ID = $xperiodo_id;    // Año de transaccion 
                    $nuevoregBitacora->MES_ID     = $xmes_id;        // Mes de transaccion
                    $nuevoregBitacora->PROCESO_ID = $xproceso_id;    // Proceso de apoyo
                    $nuevoregBitacora->FUNCION_ID = $xfuncion_id;    // Funcion del modelado de procesos 
                    $nuevoregBitacora->TRX_ID     = $xtrx_id;        // Actividad del modelado de procesos
                    $nuevoregBitacora->FOLIO      = $id;             // Folio    
                    $nuevoregBitacora->NO_VECES   = 1;               // Numero de veces            
                    $nuevoregBitacora->IP         = $ip;             // IP
                    $nuevoregBitacora->LOGIN      = $nombre;         // Usuario 
                    $nuevoregBitacora->save();
                    if($nuevoregBitacora->save() == true)
                        toastr()->success('Trx de foto 2 actualizada en Bitacora.','¡Ok!',['positionClass' => 'toast-bottom-right']);
                    else
                        toastr()->error('Error de Trx de foto 2 en bitacora. Por favor volver a interlo.','Ups!',['positionClass' => 'toast-bottom-right']);
                }else{                   
                    //*********** Obtine el no. de veces *****************************
                    $xno_veces = regBitacoraModel::where(['PERIODO_ID' => $xperiodo_id,'MES_ID' => $xmes_id, 'PROCESO_ID' => $xproceso_id, 'FUNCION_ID' => $xfuncion_id, 'TRX_ID' => $xtrx_id, 'FOLIO' => $id])
                                ->max('NO_VECES');
                    $xno_veces = $xno_veces+1;                        
                    //*********** Termina de obtener el no de veces *****************************         
                    $regbitacora = regBitacoraModel::select('NO_VECES','IP_M','LOGIN_M','FECHA_M')
                                   ->where(['PERIODO_ID' => $xperiodo_id,'MES_ID' => $xmes_id, 'PROCESO_ID' => $xproceso_id, 'FUNCION_ID' => $xfuncion_id, 'TRX_ID' => $xtrx_id, 'FOLIO' => $id])
                                   ->update([
                                         'NO_VECES' => $regbitacora->NO_VECES = $xno_veces,
                                         'IP_M'     => $regbitacora->IP           = $ip,
                                         'LOGIN_M'  => $regbitacora->LOGIN_M   = $nombre,
                                         'FECHA_M'  => $regbitacora->FECHA_M   = date('Y/m/d')  //date('d/m/Y')
                                        ]);
                    toastr()->success('Trx de foto 2 actualizada en Bitacora.','¡Ok!',['positionClass' => 'toast-bottom-right']);
                }   /************ Bitacora termina *************************************/         
            }            
        }
        return redirect()->route('verOsc');
    }

    public function actionEditarOsc5($id){
        $nombre        = session()->get('userlog');
        $pass          = session()->get('passlog');
        if($nombre == NULL AND $pass == NULL){
            return view('sicinar.login.expirada');
        }
        $usuario       = session()->get('usuario');
        $rango         = session()->get('rango');
        $arbol_id      = session()->get('arbol_id');        

        $regentidades = regEntidadesModel::select('ENTIDADFEDERATIVA_ID','ENTIDADFEDERATIVA_DESC')                
                           ->orderBy('ENTIDADFEDERATIVA_ID','asc')
                           ->get();
        $regmunicipio = regMunicipioModel::join('PE_CAT_ENTIDADES_FED',
                  'PE_CAT_ENTIDADES_FED.ENTIDADFEDERATIVA_ID', '=', 'PE_CAT_MUNICIPIOS_SEDESEM.ENTIDADFEDERATIVAID')
                        ->select('PE_CAT_MUNICIPIOS_SEDESEM.ENTIDADFEDERATIVAID',
                            'PE_CAT_ENTIDADES_FED.ENTIDADFEDERATIVA_DESC','PE_CAT_MUNICIPIOS_SEDESEM.MUNICIPIOID',
                            'PE_CAT_MUNICIPIOS_SEDESEM.MUNICIPIONOMBRE')
                        ->wherein('PE_CAT_MUNICIPIOS_SEDESEM.ENTIDADFEDERATIVAID',[9, 11, 15, 22])
                        ->orderBy('PE_CAT_MUNICIPIOS_SEDESEM.ENTIDADFEDERATIVAID','DESC')
                        ->orderBy('PE_CAT_MUNICIPIOS_SEDESEM.MUNICIPIONOMBRE','DESC')
                        ->get();
        $regrubro     = regRubroModel::select('RUBRO_ID','RUBRO_DESC')
                        ->orderBy('RUBRO_ID','asc')
                        ->get();     
        $regvigencia  = regVigenciaModel::select('ANIO_ID', 'ANIO_DESC')
                        ->wherein('ANIO_ID',[0, 99]) 
                        ->get();        
        $reginmuebles = regInmuebleedoModel::select('INM_ID','INM_DESC')->get();
        $regperiodos  = regPeriodosaniosModel::select('PERIODO_ID', 'PERIODO_DESC')->get();         
        $regmeses     = regMesesModel::select('MES_ID','MES_DESC')->get();      
        $regdias      = regDiasModel::select('DIA_ID','DIA_DESC')->get();         
        $regosc       = regOscModel::select('OSC_ID','OSC_DESC','OSC_CALLE','OSC_NUM','OSC_DOM1','OSC_DOM2','OSC_DOM3',
                                     'OSC_COLONIA','MUNICIPIO_ID','ENTIDADFEDERATIVA_ID','RUBRO_ID','OSC_REGCONS',
                                     'OSC_RFC','OSC_CP','OSC_FECCONS','OSC_FECCONS2','OSC_FECCONS3','OSC_TELEFONO',
                                     'OSC_EMAIL','OSC_SWEB','OSC_PRES','OSC_REPLEGAL','OSC_SRIO','OSC_TESORERO',
                                     'OSC_OBJSOC_1','OSC_OBJSOC_2','GRUPO_ID','OSC_FECCERTIFIC','OSC_FECCERTIFIC2',
                                     'OSC_OTRAREF','OSC_OBS1','OSC_OBS2','ANIO_ID','OSC_FVP','OSC_FVP2','OSC_FVP3',
                                     'OSC_FRPP',
                                     'INM_ID','PERIODO_ID1','MES_ID1','DIA_ID1','PERIODO_ID2','MES_ID2','DIA_ID2',
                                     'FACEBOOK','WHATSAPP','GOOGLE','YAHOO','TWITTER','INSTAGRAM',
                                     'LINKEDLN','YOUTOBE','PINTEREST','OTRA_REDSOCIAL','RED_SOCIAL',
                                     'OSC_FOTO1','OSC_FOTO2','OSC_FOTO3','OSC_FOTO4','OSC_FOTO5',
                                     'OSC_GEOREF_LATITUD','OSC_GEOREF_LONGITUD','OSC_STATUS',
                                     'OSC_FECREG','OSC_FECREG3','IP','LOGIN','FECHA_M','FECHA_M3','IP_M','LOGIN_M')
                        ->where('OSC_ID',$id)
                        ->first();
        if($regosc->count() <= 0){
            toastr()->error('No existe OSC.','Lo siento!',['positionClass' => 'toast-bottom-right']);
            return redirect()->route('nuevaIap');
        }
        return view('sicinar.osc.editarOsc5',compact('nombre','usuario','regosc','regentidades','regmunicipio','regrubro','regvigencia','reginmuebles','regperiodos','regmeses','regdias'));

    }

    public function actionActualizarOsc5(osc5Request $request, $id){
        $nombre        = session()->get('userlog');
        $pass          = session()->get('passlog');
        if($nombre == NULL AND $pass == NULL){
            return view('sicinar.login.expirada');
        }
        $usuario       = session()->get('usuario');
        $rango         = session()->get('rango');
        $ip            = session()->get('ip');
        $arbol_id      = session()->get('arbol_id');        

        // **************** actualizar ******************************
        $regosc = regOscModel::where('OSC_ID',$id);
        if($regosc->count() <= 0)
            toastr()->error('No existe OSC.','¡Por favor volver a intentar!',['positionClass' => 'toast-bottom-right']);
        else{        
            $name1 =null;
            //   if(!empty($_PUT['iap_foto1'])){
            if(isset($request->osc_foto1)){
                if(!empty($request->osc_foto1)){
                    //Comprobar  si el campo foto1 tiene un archivo asignado:
                    if($request->hasFile('osc_foto1')){
                        $name1 = $id.'_'.$request->file('osc_foto1')->getClientOriginalName(); 
                        //sube el archivo a la carpeta del servidor public/images/
                        $request->file('osc_foto1')->move(public_path().'/images/', $name1);

                        $regosc = regOscModel::where('OSC_ID',$id)        
                                  ->update([                
                                            'OSC_FOTO1' => $name1,
                                            'IP_M'      => $ip,
                                            'LOGIN_M'   => $nombre,
                                            'FECHA_M'   => date('Y/m/d')    //date('d/m/Y')                                
                                           ]);
                        toastr()->success('Foto 1 actualizada.','¡Ok!',['positionClass' => 'toast-bottom-right']);                      
                    }
                }
            }
            $name2 =null;
            if (isset($request->osc_foto2) and !empty($request->osc_foto2) ) {
               //Comprobar  si el campo foto2 tiene un archivo asignado:        
               if($request->hasFile('osc_foto2')){
                    $name2 = $id.'_'.$request->file('osc_foto2')->getClientOriginalName(); 
                    //sube el archivo a la carpeta del servidor public/images/
                    $request->file('osc_foto2')->move(public_path().'/images/', $name2);

                    $regosc = regOscModel::where('OSC_ID',$id)        
                              ->update([                
                                        'OSC_FOTO2' => $name2,
                                        'IP_M'      => $ip,
                                        'LOGIN_M'   => $nombre,
                                        'FECHA_M'   => date('Y/m/d')    //date('d/m/Y')                                
                                       ]);
                    toastr()->success('Foto 2 actualizada.','¡Ok!',['positionClass' => 'toast-bottom-right']);                         
               }
            }
            $name3 =null;
            if (isset($request->osc_foto3) and !empty($request->osc_foto3) ) {
               //Comprobar  si el campo foto3 tiene un archivo asignado:        
               if($request->hasFile('osc_foto3')){
                    $name3 = $id.'_'.$request->file('osc_foto3')->getClientOriginalName(); 
                    //sube el archivo a la carpeta del servidor public/images/
                    $request->file('osc_foto3')->move(public_path().'/images/', $name3);

                    $regosc = regOscModel::where('OSC_ID',$id)        
                              ->update([                
                                        'OSC_FOTO3' => $name3,
                                        'IP_M'      => $ip,
                                        'LOGIN_M'   => $nombre,
                                        'FECHA_M'   => date('Y/m/d')    //date('d/m/Y')                                
                                       ]);
                    toastr()->success('Foto 3 actualizada.','¡Ok!',['positionClass' => 'toast-bottom-right']);                          
               }
            }            
            //***************** Actualizar items ***************************/
            /****************** Actualizar *********************************/
            $regosc = regOscModel::where('OSC_ID',$id)        
                      ->update([                
                                'OSC_DESC'      => substr(trim(strtoupper($request->osc_desc)),    0, 99),
                                'OSC_DOM1'      => substr(trim(strtoupper($request->osc_dom1)),    0,149),
                                'OSC_DOM2'      => substr(trim(strtoupper($request->osc_dom2)),    0,149),
                                'OSC_DOM3'      => substr(trim(strtoupper($request->osc_dom3)),    0,149),
                                'OSC_REGCONS'   => substr(trim(strtoupper($request->osc_regcons)), 0, 49),                                
                                'OSC_FECCONS2'  => substr(trim($request->osc_feccons2),            0, 10),                                
                                'OSC_RFC'       => substr(trim(strtoupper($request->osc_rfc)),     0, 17),
                                'OSC_CP'        => $request->osc_cp,
                                'ANIO_ID'       => $request->anio_id,                
                
                                'OSC_FVP2'      => substr(trim($request->osc_fvp2),                0, 10),
                                'OSC_FRPP'      => substr(trim($request->osc_frpp),                0, 30),   

                                'ENTIDADFEDERATIVA_ID' => $request->entidadfederativa_id,                
                                'MUNICIPIO_ID'  => $request->municipio_id,
                                'RUBRO_ID'      => $request->rubro_id,
                                'INM_ID'        => $request->inm_id,

                                'OSC_TELEFONO'  => substr(trim(strtoupper($request->osc_telefono)),0, 59),
                                'OSC_EMAIL'     => substr(trim(strtolower($request->osc_email)),   0,149),
                                'OSC_SWEB'      => substr(trim(strtolower($request->osc_sweb)),    0, 99),
                                'OSC_PRES'      => substr(trim(strtoupper($request->osc_pres)),    0, 79),
                                'OSC_REPLEGAL'  => substr(trim(strtoupper($request->osc_replegal)),0,149),
                                'OSC_SRIO'      => substr(trim(strtoupper($request->osc_srio)),    0, 79),
                                'OSC_TESORERO'  => substr(trim(strtoupper($request->osc_tesorero)),0, 79),

                                'OSC_OBJSOC_1'  => substr(trim(strtoupper($request->osc_objsoc_1)),0,3999),
                                'OSC_OBJSOC_2'  => substr(trim(strtoupper($request->osc_objsoc_2)),0,3999),                
                                'OSC_OBS1'      => substr(trim(strtoupper($request->osc_obs1))    ,0,1999),  
                                'FACEBOOK'        => $request->facebook,
                                'WHATSAPP'        => $request->whatsapp,
                                'GOOGLE'          => $request->google,
                                'YAHOO'           => $request->yahoo,
                                'TWITTER'         => $request->twitter,
                                'INSTAGRAM'       => $request->instagram,
                                'LINKEDLN'        => $request->linkedln,
                                'YOUTOBE'         => $request->youtobe,
                                'PINTEREST'       => $request->pinterest,
                                'OTRA_REDSOCIAL'  => $request->otra,                                                
                                'RED_SOCIAL'      => substr(trim(strtoupper($request->red_social))  ,0, 149),                                                                         
                                'OSC_GEOREF_LATITUD' => $request->osc_georef_latitud,
                                'OSC_GEOREF_LONGITUD'=> $request->osc_georef_longitud,

                                'IP_M'          => $ip,
                                'LOGIN_M'       => $nombre,
                                'FECHA_M'       => date('Y/m/d')    //date('d/m/Y')                                
                                ]);
            toastr()->success('OSC actualizada.','¡Ok!',['positionClass' => 'toast-bottom-right']);

            /************ Bitacora inicia *************************************/ 
            setlocale(LC_TIME, "spanish");        
            $xip          = session()->get('ip');
            $xperiodo_id  = (int)date('Y');
            $xprograma_id = 1;
            $xmes_id      = (int)date('m');
            $xproceso_id  =         3;
            $xfuncion_id  =      3001;
            $xtrx_id      =       146;    //Actualizar
            $regbitacora = regBitacoraModel::select('PERIODO_ID','MES_ID', 'PROCESO_ID', 'FUNCION_ID', 
                           'TRX_ID', 'FOLIO', 'NO_VECES', 'FECHA_REG', 'IP', 'LOGIN', 'FECHA_M', 'IP_M', 'LOGIN_M')
                           ->where(['PERIODO_ID' => $xperiodo_id,'MES_ID' => $xmes_id,'PROCESO_ID' => $xproceso_id, 
                                    'FUNCION_ID' => $xfuncion_id,'TRX_ID' => $xtrx_id,'FOLIO'      => $id])
                           ->get();
            if($regbitacora->count() <= 0){              // Alta
                $nuevoregBitacora = new regBitacoraModel();              
                $nuevoregBitacora->PERIODO_ID = $xperiodo_id;    // Año de transaccion 
                $nuevoregBitacora->MES_ID     = $xmes_id;        // Mes de transaccion
                $nuevoregBitacora->PROCESO_ID = $xproceso_id;    // Proceso de apoyo
                $nuevoregBitacora->FUNCION_ID = $xfuncion_id;    // Funcion del modelado de procesos 
                $nuevoregBitacora->TRX_ID     = $xtrx_id;        // Actividad del modelado de procesos
                $nuevoregBitacora->FOLIO      = $id;             // Folio    
                $nuevoregBitacora->NO_VECES   = 1;               // Numero de veces            
                $nuevoregBitacora->IP         = $ip;             // IP
                $nuevoregBitacora->LOGIN      = $nombre;         // Usuario 

                $nuevoregBitacora->save();
                if($nuevoregBitacora->save() == true)
                    toastr()->success('Trx de OSC actualizada en Bitacora.','¡Ok!',['positionClass' => 'toast-bottom-right']);
                else
                    toastr()->error('Error de actualización de Trx de OSC en bitacora. Por favor volver a interlo.','Ups!',['positionClass' => 'toast-bottom-right']);
            }else{                   
                //*********** Obtine el no. de veces *****************************
                $xno_veces = regBitacoraModel::where(['PERIODO_ID' => $xperiodo_id,'MES_ID'     => $xmes_id, 
                                                      'PROCESO_ID' => $xproceso_id,'FUNCION_ID' => $xfuncion_id,
                                                      'TRX_ID'     => $xtrx_id,    'FOLIO'      => $id])
                        ->max('NO_VECES');
                $xno_veces = $xno_veces+1;                        
                //*********** Termina de obtener el no de veces *****************************         
                $regbitacora= regBitacoraModel::select('NO_VECES','IP_M','LOGIN_M','FECHA_M')
                              ->where(['PERIODO_ID' => $xperiodo_id,'MES_ID' => $xmes_id,'PROCESO_ID' => $xproceso_id,
                                       'FUNCION_ID' => $xfuncion_id,'TRX_ID' => $xtrx_id,'FOLIO'      => $id])
                              ->update([
                                         'NO_VECES'=> $regbitacora->NO_VECES = $xno_veces,
                                         'IP_M'    => $regbitacora->IP       = $ip,
                                         'LOGIN_M' => $regbitacora->LOGIN_M  = $nombre,
                                         'FECHA_M' => $regbitacora->FECHA_M  = date('Y/m/d')  //date('d/m/Y')
                                        ]);
                toastr()->success('Trx de OSC actualizada en Bitacora.','¡Ok!',['positionClass' => 'toast-bottom-right']);
            }   /************ Bitacora termina *************************************/                     
        }   /***************** Actualizar IAP **************************************/
        return redirect()->route('verOsc5');
    }

    public function actionBorrarOsc($id){
        //dd($request->all());
        $nombre       = session()->get('userlog');
        $pass         = session()->get('passlog');
        if($nombre == NULL AND $pass == NULL){
            return view('sicinar.login.expirada');
        }
        $usuario      = session()->get('usuario');
        $rango        = session()->get('rango');
        $ip           = session()->get('ip');
        $arbol_id     = session()->get('arbol_id');        

        /************ Elimina la OSC **************************************/
        $regosc       = regOscModel::select('OSC_ID','OSC_DESC','OSC_CALLE','OSC_NUM','OSC_DOM1','OSC_DOM2','OSC_DOM3',
                                     'OSC_COLONIA','MUNICIPIO_ID','ENTIDADFEDERATIVA_ID','RUBRO_ID','OSC_REGCONS',
                                     'OSC_RFC','OSC_CP','OSC_FECCONS','OSC_FECCONS2','OSC_FECCONS3','OSC_TELEFONO',
                                     'OSC_EMAIL','OSC_SWEB','OSC_PRES','OSC_REPLEGAL','OSC_SRIO','OSC_TESORERO',
                                     'OSC_OBJSOC_1','OSC_OBJSOC_2','GRUPO_ID','OSC_FECCERTIFIC','OSC_FECCERTIFIC2',
                                     'OSC_OTRAREF','OSC_OBS1','OSC_OBS2','ANIO_ID','OSC_FVP','OSC_FVP2','OSC_FVP3',
                                     'OSC_FRPP',
                                     'INM_ID','PERIODO_ID1','MES_ID1','DIA_ID1','PERIODO_ID2','MES_ID2','DIA_ID2',
                                     'FACEBOOK','WHATSAPP','GOOGLE','YAHOO','TWITTER','INSTAGRAM',
                                     'LINKEDLN','YOUTOBE','PINTEREST','OTRA_REDSOCIAL','RED_SOCIAL',
                                     'OSC_FOTO1','OSC_FOTO2','OSC_FOTO3','OSC_FOTO4','OSC_FOTO5',
                                     'OSC_GEOREF_LATITUD','OSC_GEOREF_LONGITUD','OSC_STATUS',
                                     'OSC_FECREG','OSC_FECREG3','IP','LOGIN','FECHA_M','FECHA_M3','IP_M','LOGIN_M')
                        ->where('OSC_ID',$id);
        //              ->find('RUBRO_ID',$id);
        if($regosc->count() <= 0)
            toastr()->error('No existe OSC.','¡Por favor volver a intentar!',['positionClass' => 'toast-bottom-right']);
        else{        
            $regosc->delete();
            toastr()->success('OSC eliminada.','¡Ok!',['positionClass' => 'toast-bottom-right']);

            //echo 'Ya entre a borrar registro..........';
            /************ Bitacora inicia *************************************/ 
            setlocale(LC_TIME, "spanish");        
            $xip          = session()->get('ip');
            $xperiodo_id  = (int)date('Y');
            $xprograma_id = 1;
            $xmes_id      = (int)date('m');
            $xproceso_id  =         3;
            $xfuncion_id  =      3001;
            $xtrx_id      =       147;     // Baja de IAP

            $regbitacora = regBitacoraModel::select('PERIODO_ID','MES_ID', 'PROCESO_ID', 'FUNCION_ID', 'TRX_ID', 'FOLIO', 'NO_VECES', 'FECHA_REG', 'IP', 'LOGIN', 'FECHA_M', 'IP_M', 'LOGIN_M')
                           ->where(['PERIODO_ID' => $xperiodo_id,'MES_ID' => $xmes_id, 'PROCESO_ID' => $xproceso_id, 'FUNCION_ID' => $xfuncion_id, 'TRX_ID' => $xtrx_id, 'FOLIO' => $id])
                           ->get();
            if($regbitacora->count() <= 0){              // Alta
                $nuevoregBitacora = new regBitacoraModel();              
                $nuevoregBitacora->PERIODO_ID = $xperiodo_id;    // Año de transaccion 
                $nuevoregBitacora->MES_ID     = $xmes_id;        // Mes de transaccion
                $nuevoregBitacora->PROCESO_ID = $xproceso_id;    // Proceso de apoyo
                $nuevoregBitacora->FUNCION_ID = $xfuncion_id;    // Funcion del modelado de procesos 
                $nuevoregBitacora->TRX_ID     = $xtrx_id;        // Actividad del modelado de procesos
                $nuevoregBitacora->FOLIO      = $id;             // Folio    
                $nuevoregBitacora->NO_VECES   = 1;               // Numero de veces            
                $nuevoregBitacora->IP         = $ip;             // IP
                $nuevoregBitacora->LOGIN      = $nombre;         // Usuario 

                $nuevoregBitacora->save();
                if($nuevoregBitacora->save() == true)
                    toastr()->success('Trx de baja de OSC registrada en Bitacora.','¡Ok!',['positionClass' => 'toast-bottom-right']);
                else
                    toastr()->error('Error en Trx de baja de OSC en bitacora. Por favor volver a interlo.','Ups!',['positionClass' => 'toast-bottom-right']);
            }else{                   
                //*********** Obtine el no. de veces *****************************
                $xno_veces = regBitacoraModel::where(['PERIODO_ID' => $xperiodo_id,'MES_ID' => $xmes_id, 'PROCESO_ID' => $xproceso_id, 'FUNCION_ID' => $xfuncion_id, 'TRX_ID' => $xtrx_id, 'FOLIO' => $id])
                        ->max('NO_VECES');
                $xno_veces = $xno_veces+1;                        
                //*********** Termina de obtener el no de veces *****************************         
                $regbitacora = regBitacoraModel::select('NO_VECES','IP_M','LOGIN_M','FECHA_M')
                               ->where(['PERIODO_ID' => $xperiodo_id,'MES_ID' => $xmes_id, 'PROCESO_ID' => $xproceso_id, 
                                        'FUNCION_ID' => $xfuncion_id,'TRX_ID' => $xtrx_id, 'FOLIO'      => $id])
                               ->update([
                                         'NO_VECES' => $regbitacora->NO_VECES = $xno_veces,
                                         'IP_M'     => $regbitacora->IP       = $ip,
                                         'LOGIN_M'  => $regbitacora->LOGIN_M  = $nombre,
                                         'FECHA_M'  => $regbitacora->FECHA_M  = date('Y/m/d')  //date('d/m/Y')
                                        ]);
                toastr()->success('Trx de actualizar OSC registrada en Bitacora.','¡Ok!',['positionClass' => 'toast-bottom-right']);
            }   /************ Bitacora termina *************************************/                 
        }       /************* Termina de eliminar  la IAP **********************************/
        return redirect()->route('verOsc');
    }    

    // exportar a formato catalogo de OSC a formato excel
    public function exportOscExcel(){
        $nombre       = session()->get('userlog');
        $pass         = session()->get('passlog');
        if($nombre == NULL AND $pass == NULL){
            return view('sicinar.login.expirada');
        }
        $usuario      = session()->get('usuario');
        $rango        = session()->get('rango');
        $ip           = session()->get('ip');
        $arbol_id     = session()->get('arbol_id');        
        
        /************ Bitacora inicia *************************************/ 
        setlocale(LC_TIME, "spanish");        
        $xip          = session()->get('ip');
        $xperiodo_id  = (int)date('Y');
        $xprograma_id = 1;
        $xmes_id      = (int)date('m');
        $xproceso_id  =         3;
        $xfuncion_id  =      3001;
        $xtrx_id      =       148;            // Exportar a formato Excel
        $id           =         0;

        $regbitacora = regBitacoraModel::select('PERIODO_ID','MES_ID', 'PROCESO_ID', 'FUNCION_ID', 'TRX_ID', 'FOLIO', 'NO_VECES', 'FECHA_REG', 'IP', 'LOGIN', 'FECHA_M', 'IP_M', 'LOGIN_M')
                        ->where(['PERIODO_ID' => $xperiodo_id,'MES_ID' => $xmes_id, 'PROCESO_ID' => $xproceso_id, 'FUNCION_ID' => $xfuncion_id, 'TRX_ID' => $xtrx_id, 'FOLIO' => $id])
                        ->get();
        if($regbitacora->count() <= 0){              // Alta
            $nuevoregBitacora = new regBitacoraModel();              
            $nuevoregBitacora->PERIODO_ID = $xperiodo_id;    // Año de transaccion 
            $nuevoregBitacora->MES_ID     = $xmes_id;        // Mes de transaccion
            $nuevoregBitacora->PROCESO_ID = $xproceso_id;    // Proceso de apoyo
            $nuevoregBitacora->FUNCION_ID = $xfuncion_id;    // Funcion del modelado de procesos 
            $nuevoregBitacora->TRX_ID     = $xtrx_id;        // Actividad del modelado de procesos
            $nuevoregBitacora->FOLIO      = $id;             // Folio    
            $nuevoregBitacora->NO_VECES   = 1;               // Numero de veces            
            $nuevoregBitacora->IP         = $ip;             // IP
            $nuevoregBitacora->LOGIN      = $nombre;         // Usuario 

            $nuevoregBitacora->save();
            if($nuevoregBitacora->save() == true)
               toastr()->success('Trx de exportar OSC registrada en Bitacora.','¡Ok!',['positionClass' => 'toast-bottom-right']);
            else
               toastr()->error('Error de Trx de exportar OSC en bitacora. Por favor volver a interlo.','Ups!',['positionClass' => 'toast-bottom-right']);
        }else{                   
            //*********** Obtine el no. de veces *****************************
            $xno_veces = regBitacoraModel::where(['PERIODO_ID' => $xperiodo_id,'MES_ID' => $xmes_id, 'PROCESO_ID' => $xproceso_id, 'FUNCION_ID' => $xfuncion_id, 'TRX_ID' => $xtrx_id, 'FOLIO' => $id])
                        ->max('NO_VECES');
            $xno_veces  = $xno_veces+1;                        
            //*********** Termina de obtener el no de veces *****************************                
            $regbitacora = regBitacoraModel::select('NO_VECES','IP_M','LOGIN_M','FECHA_M')
                           ->where(['PERIODO_ID' => $xperiodo_id,'MES_ID' => $xmes_id, 'PROCESO_ID' => $xproceso_id, 
                                    'FUNCION_ID' => $xfuncion_id,'TRX_ID' => $xtrx_id, 'FOLIO'      => $id])
                           ->update([
                                     'NO_VECES' => $regbitacora->NO_VECES = $xno_veces,
                                     'IP_M'     => $regbitacora->IP       = $ip,
                                     'LOGIN_M'  => $regbitacora->LOGIN_M  = $nombre,
                                     'FECHA_M'  => $regbitacora->FECHA_M  = date('Y/m/d')  //date('d/m/Y')
                                    ]);
            toastr()->success('Trx de exportar OSC registrada en Bitacora.','¡Ok!',['positionClass' => 'toast-bottom-right']);
        }
        /************ Bitacora termina *************************************/  
        return Excel::download(new ExcelExportOsc, 'Cat_OSCS_'.date('d-m-Y').'.xlsx');
    } 

    // exportar a formato PDF
    public function exportOscPdf(){
        set_time_limit(0);
        ini_set("memory_limit",-1);
        ini_set('max_execution_time', 0);

        $nombre       = session()->get('userlog');
        $pass         = session()->get('passlog');
        if($nombre == NULL AND $pass == NULL){
            return view('sicinar.login.expirada');
        }
        $usuario       = session()->get('usuario');
        $rango         = session()->get('rango');
        $ip           = session()->get('ip');
        $arbol_id     = session()->get('arbol_id');        

        /************ Bitacora inicia *************************************/ 
        setlocale(LC_TIME, "spanish");        
        $xip          = session()->get('ip');
        $xperiodo_id  = (int)date('Y');
        $xprograma_id = 1;
        $xmes_id      = (int)date('m');
        $xproceso_id  =         3;
        $xfuncion_id  =      3001;
        $xtrx_id      =       143;       //Exportar a formato PDF
        $id           =         0;

        $regbitacora = regBitacoraModel::select('PERIODO_ID', 'PROGRAMA_ID', 'MES_ID', 'PROCESO_ID', 'FUNCION_ID', 
                       'TRX_ID', 'FOLIO', 'NO_VECES', 'FECHA_REG', 'IP', 'LOGIN', 'FECHA_M', 'IP_M', 'LOGIN_M')
                       ->where(['PERIODO_ID' => $xperiodo_id, 'PROGRAMA_ID' => $xprograma_id, 'MES_ID' => $xmes_id, 
                                'PROCESO_ID' => $xproceso_id, 'FUNCION_ID' => $xfuncion_id, 'TRX_ID' => $xtrx_id, 
                                'FOLIO' => $id])
                       ->get();
        if($regbitacora->count() <= 0){              // Alta
            $nuevoregBitacora = new regBitacoraModel();              
            $nuevoregBitacora->PERIODO_ID = $xperiodo_id;    // Año de transaccion 
            $nuevoregBitacora->PROGRAMA_ID= $xprograma_id;   // Proyecto JAPEM 
            $nuevoregBitacora->MES_ID     = $xmes_id;        // Mes de transaccion
            $nuevoregBitacora->PROCESO_ID = $xproceso_id;    // Proceso de apoyo
            $nuevoregBitacora->FUNCION_ID = $xfuncion_id;    // Funcion del modelado de procesos 
            $nuevoregBitacora->TRX_ID     = $xtrx_id;        // Actividad del modelado de procesos
            $nuevoregBitacora->FOLIO      = $id;             // Folio    
            $nuevoregBitacora->NO_VECES   = 1;               // Numero de veces            
            $nuevoregBitacora->IP         = $ip;             // IP
            $nuevoregBitacora->LOGIN      = $nombre;         // Usuario 

            $nuevoregBitacora->save();
            if($nuevoregBitacora->save() == true)
               toastr()->success('Trx de exportar a PDF registrada en Bitacora.','¡Ok!',['positionClass' => 'toast-bottom-right']);
            else
               toastr()->error('Error de Trx de exportar a PDF en bitacora. Por favor volver a interlo.','Ups!',['positionClass' => 'toast-bottom-right']);
        }else{                   
            //*********** Obtine el no. de veces *****************************
            $xno_veces = regBitacoraModel::where(['PERIODO_ID' => $xperiodo_id,'MES_ID'     => $xmes_id, 
                                                  'PROCESO_ID' => $xproceso_id,'FUNCION_ID' => $xfuncion_id, 
                                                  'TRX_ID'     => $xtrx_id,    'FOLIO'      => $id])
                        ->max('NO_VECES');
            $xno_veces = $xno_veces+1;                        
            //*********** Termina de obtener el no de veces *****************************         
            $regbitacora = regBitacoraModel::select('NO_VECES','IP_M','LOGIN_M','FECHA_M')
                           ->where(['PERIODO_ID' => $xperiodo_id,'MES_ID' => $xmes_id,'PROCESO_ID' => $xproceso_id, 
                                    'FUNCION_ID' => $xfuncion_id,'TRX_ID' => $xtrx_id,'FOLIO'      => $id])
                           ->update([
                                     'NO_VECES' => $regbitacora->NO_VECES = $xno_veces,
                                     'IP_M'     => $regbitacora->IP       = $ip,
                                     'LOGIN_M'  => $regbitacora->LOGIN_M  = $nombre,
                                     'FECHA_M'  => $regbitacora->FECHA_M  = date('Y/m/d')  //date('d/m/Y')
                                    ]);
            toastr()->success('Trx de exportación a PDF registrada en Bitacora.','¡Ok!',['positionClass' => 'toast-bottom-right']);
        }   /************ Bitacora termina *************************************/ 

        $regentidades = regEntidadesModel::select('ENTIDADFEDERATIVA_ID','ENTIDADFEDERATIVA_DESC')     
                                           ->get();
        $regmunicipio = regMunicipioModel::select('ENTIDADFEDERATIVAID', 'MUNICIPIOID', 'MUNICIPIONOMBRE')
                                           ->wherein('ENTIDADFEDERATIVAID',[9, 11, 15, 22])
                                           ->get();                           
        $regrubro     = regRubroModel::select('RUBRO_ID','RUBRO_DESC')
                                       ->get();                         
        $regosc       = regOscModel::select('OSC_ID','OSC_DESC','OSC_DOM1','OSC_DOM2','OSC_DOM3', 'OSC_TELEFONO',
                                      'OSC_STATUS', 'OSC_FECREG')
                                     ->orderBy('OSC_ID','ASC')
                                     ->get();                               
        if($regosc->count() <= 0){
            toastr()->error('No existen registros en el catalogo de OSC.','Uppss!',['positionClass' => 'toast-bottom-right']);
        }
        $pdf = PDF::loadView('sicinar.pdf.oscPDF', compact('nombre','usuario','regentidades','regmunicipio','regrubro','regosc'));
        //$options = new Options();
        //$options->set('defaultFont', 'Courier');
        //$pdf->set_option('defaultFont', 'Courier');
        $pdf->setPaper('A4', 'landscape');      
        //$pdf->set('defaultFont', 'Courier');          
        //$pdf->setPaper('A4','portrait');

        // Output the generated PDF to Browser
        return $pdf->stream('CatalogoDeOSC');
    }

    //*********************************************************************************//
    //************************* Estadísticas ******************************************//
    //*********************************************************************************//
    // Gráfica por estado
    public function OscxEdo(){
        $nombre       = session()->get('userlog');
        $pass         = session()->get('passlog');
        if($nombre == NULL AND $pass == NULL){
            return view('sicinar.login.expirada');
        }
        $usuario      = session()->get('usuario');
        $rango        = session()->get('rango');
        $ip           = session()->get('ip'); 
        $arbol_id     = session()->get('arbol_id');               

        $regtotxedo=regOscModel::join('PE_CAT_ENTIDADES_FED',[['PE_CAT_ENTIDADES_FED.ENTIDADFEDERATIVA_ID','=','PE_OSC.ENTIDADFEDERATIVA_ID'],['PE_OSC.OSC_ID','<>',0]])
                         ->selectRaw('COUNT(*) AS TOTALXEDO')
                               ->get();

        $regosc=regOscModel::join('PE_CAT_ENTIDADES_FED',[['PE_CAT_ENTIDADES_FED.ENTIDADFEDERATIVA_ID','=','PE_OSC.ENTIDADFEDERATIVA_ID'],['PE_OSC.OSC_ID','<>',0]])
                      ->selectRaw('PE_OSC.ENTIDADFEDERATIVA_ID, PE_CAT_ENTIDADES_FED.ENTIDADFEDERATIVA_DESC AS ESTADO, COUNT(*) AS TOTAL')
                        ->groupBy('PE_OSC.ENTIDADFEDERATIVA_ID', 'PE_CAT_ENTIDADES_FED.ENTIDADFEDERATIVA_DESC')
                        ->orderBy('PE_OSC.ENTIDADFEDERATIVA_ID','asc')
                        ->get();
        //$procesos = procesosModel::join('SCI_TIPO_PROCESO','SCI_PROCESOS.CVE_TIPO_PROC','=','SCI_TIPO_PROCESO.CVE_TIPO_PROC')
        //    ->selectRaw('SCI_TIPO_PROCESO.DESC_TIPO_PROC AS TIPO, COUNT(SCI_PROCESOS.CVE_TIPO_PROC) AS TOTAL')
        //    ->groupBy('SCI_TIPO_PROCESO.DESC_TIPO_PROC')
        //    ->get();
        //dd($procesos);
        return view('sicinar.numeralia.iapxedo',compact('regosc','regtotxedo','nombre','usuario','rango'));
    }

    // Filtro de estadistica de la bitacora
    public function actionVerBitacora(){
        $nombre       = session()->get('userlog');
        $pass         = session()->get('passlog');
        if($nombre == NULL AND $pass == NULL){
            return view('sicinar.login.expirada');
        }
        $usuario      = session()->get('usuario');
        $rango        = session()->get('rango');
        $ip           = session()->get('ip');

        $regmeses     = regMesesModel::select('MES_ID','MES_DESC')->get();   
        $regperiodos  = regPfiscalesModel::select('PERIODO_ID', 'PERIODO_DESC')->get();        
        if($regperiodos->count() <= 0){
            toastr()->error('No existen periodos fiscales.','Lo siento!',['positionClass' => 'toast-bottom-right']);
            //return redirect()->route('nuevaIap');
        }
        return view('sicinar.osc.verBitacora',compact('nombre','usuario','regmeses','regperiodos'));
    }

    // Gráfica de transacciones (Bitacora)
    public function Bitacora(Request $request){
        $nombre       = session()->get('userlog');
        $pass         = session()->get('passlog');
        if($nombre == NULL AND $pass == NULL){
            return view('sicinar.login.expirada');
        }
        $usuario      = session()->get('usuario');
        $rango        = session()->get('rango');
        $ip           = session()->get('ip'); 
        $arbol_id     = session()->get('arbol_id');

        // http://www.chartjs.org/docs/#bar-chart
        $regperiodos  = regPfiscalesModel::select('PERIODO_ID', 'PERIODO_DESC')->get();                
        $regbitatxmes=regBitacoraModel::join('PE_CAT_PROCESOS' ,'PE_CAT_PROCESOS.PROCESO_ID' ,'=','PE_BITACORA.PROCESO_ID')
                                      ->join('PE_CAT_FUNCIONES','PE_CAT_FUNCIONES.FUNCION_ID','=','PE_BITACORA.FUNCION_ID')
                                      ->join('PE_CAT_TRX'      ,'PE_CAT_TRX.TRX_ID'          ,'=','PE_BITACORA.TRX_ID')
                                      ->join('PE_CAT_MESES'    ,'PE_CAT_MESES.MES_ID'        ,'=','PE_BITACORA.MES_ID')
                                 ->select(   'PE_BITACORA.MES_ID','PE_CAT_MESES.MES_DESC')
                                 ->selectRaw('COUNT(*) AS TOTALGENERAL')
                                 ->where(    'PE_BITACORA.PERIODO_ID',$request->periodo_id)
                                 ->groupBy(  'PE_BITACORA.MES_ID','PE_CAT_MESES.MES_DESC')
                                 ->orderBy(  'PE_BITACORA.MES_ID','asc')
                                 ->get();        
        $regbitatot=regBitacoraModel::join('PE_CAT_PROCESOS','PE_CAT_PROCESOS.PROCESO_ID' ,'=','PE_BITACORA.PROCESO_ID')
                                   ->join('PE_CAT_FUNCIONES','PE_CAT_FUNCIONES.FUNCION_ID','=','PE_BITACORA.FUNCION_ID')
                                   ->join('PE_CAT_TRX'      ,'PE_CAT_TRX.TRX_ID'          ,'=','PE_BITACORA.TRX_ID')
                         ->selectRaw('SUM(CASE WHEN PE_BITACORA.MES_ID = 1 THEN 1 END) AS M01')  
                         ->selectRaw('SUM(CASE WHEN PE_BITACORA.MES_ID = 2 THEN 1 END) AS M02')
                         ->selectRaw('SUM(CASE WHEN PE_BITACORA.MES_ID = 3 THEN 1 END) AS M03')
                         ->selectRaw('SUM(CASE WHEN PE_BITACORA.MES_ID = 4 THEN 1 END) AS M04')
                         ->selectRaw('SUM(CASE WHEN PE_BITACORA.MES_ID = 5 THEN 1 END) AS M05')
                         ->selectRaw('SUM(CASE WHEN PE_BITACORA.MES_ID = 6 THEN 1 END) AS M06')
                         ->selectRaw('SUM(CASE WHEN PE_BITACORA.MES_ID = 7 THEN 1 END) AS M07')
                         ->selectRaw('SUM(CASE WHEN PE_BITACORA.MES_ID = 8 THEN 1 END) AS M08')
                         ->selectRaw('SUM(CASE WHEN PE_BITACORA.MES_ID = 9 THEN 1 END) AS M09')
                         ->selectRaw('SUM(CASE WHEN PE_BITACORA.MES_ID =10 THEN 1 END) AS M10')
                         ->selectRaw('SUM(CASE WHEN PE_BITACORA.MES_ID =11 THEN 1 END) AS M11')
                         ->selectRaw('SUM(CASE WHEN PE_BITACORA.MES_ID =12 THEN 1 END) AS M12')
                         ->selectRaw('COUNT(*) AS TOTALGENERAL')
                         ->where(    'PE_BITACORA.PERIODO_ID',$request->periodo_id)
                         ->get();

        $regbitacora=regBitacoraModel::join('PE_CAT_PROCESOS' ,'PE_CAT_PROCESOS.PROCESO_ID' ,'=','PE_BITACORA.PROCESO_ID')
                                     ->join('PE_CAT_FUNCIONES','PE_CAT_FUNCIONES.FUNCION_ID','=','PE_BITACORA.FUNCION_ID')
                                     ->join('PE_CAT_TRX'      ,'PE_CAT_TRX.TRX_ID'          ,'=','PE_BITACORA.TRX_ID')
                    ->select(   'PE_BITACORA.PERIODO_ID', 'PE_BITACORA.PROCESO_ID','PE_CAT_PROCESOS.PROCESO_DESC', 
                                'PE_BITACORA.FUNCION_ID', 'PE_CAT_FUNCIONES.FUNCION_DESC', 
                                'PE_BITACORA.TRX_ID',     'PE_CAT_TRX.TRX_DESC')
                    ->selectRaw('SUM(CASE WHEN PE_BITACORA.MES_ID = 1 THEN 1 END) AS ENE')  
                    ->selectRaw('SUM(CASE WHEN PE_BITACORA.MES_ID = 2 THEN 1 END) AS FEB')
                    ->selectRaw('SUM(CASE WHEN PE_BITACORA.MES_ID = 3 THEN 1 END) AS MAR')
                    ->selectRaw('SUM(CASE WHEN PE_BITACORA.MES_ID = 4 THEN 1 END) AS ABR')
                    ->selectRaw('SUM(CASE WHEN PE_BITACORA.MES_ID = 5 THEN 1 END) AS MAY')
                    ->selectRaw('SUM(CASE WHEN PE_BITACORA.MES_ID = 6 THEN 1 END) AS JUN')
                    ->selectRaw('SUM(CASE WHEN PE_BITACORA.MES_ID = 7 THEN 1 END) AS JUL')
                    ->selectRaw('SUM(CASE WHEN PE_BITACORA.MES_ID = 8 THEN 1 END) AS AGO')
                    ->selectRaw('SUM(CASE WHEN PE_BITACORA.MES_ID = 9 THEN 1 END) AS SEP')
                    ->selectRaw('SUM(CASE WHEN PE_BITACORA.MES_ID =10 THEN 1 END) AS OCT')
                    ->selectRaw('SUM(CASE WHEN PE_BITACORA.MES_ID =11 THEN 1 END) AS NOV')
                    ->selectRaw('SUM(CASE WHEN PE_BITACORA.MES_ID =12 THEN 1 END) AS DIC')                   
                    ->selectRaw('COUNT(*) AS SUMATOTAL')
                    ->where(    'PE_BITACORA.PERIODO_ID',$request->periodo_id)
                    ->groupBy(  'PE_BITACORA.PERIODO_ID','PE_BITACORA.PROCESO_ID','PE_CAT_PROCESOS.PROCESO_DESC',
                                'PE_BITACORA.FUNCION_ID','PE_CAT_FUNCIONES.FUNCION_DESC', 
                                'PE_BITACORA.TRX_ID'    ,'PE_CAT_TRX.TRX_DESC')
                    ->orderBy(  'PE_BITACORA.PERIODO_ID','asc')
                    ->orderBy(  'PE_BITACORA.PROCESO_ID','asc')
                    ->orderBy(  'PE_BITACORA.FUNCION_ID','asc')
                    ->orderBy(  'PE_BITACORA.TRX_ID'    ,'asc')
                    ->get();
        //dd($procesos);
        return view('sicinar.numeralia.bitacora',compact('regbitatxmes','regbitacora','regbitatot','regperiodos','nombre','usuario','rango'));
    }

    // Georefrenciación por municipio
    public function actiongeorefxmpio(){
        $nombre       = session()->get('userlog');
        $pass         = session()->get('passlog');
        if($nombre == NULL AND $pass == NULL){
            return view('sicinar.login.expirada');
        }
        $usuario      = session()->get('usuario');
        $rango        = session()->get('rango');
        $ip           = session()->get('ip');  
        $arbol_id     = session()->get('arbol_id');              

        $regosc=regOscModel::join(  'PE_CAT_ENTIDADES_FED',     'PE_CAT_ENTIDADES_FED.ENTIDADFEDERATIVA_ID','=',
                                                                'PE_OSC.ENTIDADFEDERATIVA_ID')
                           ->join(  'PE_CAT_MUNICIPIOS_SEDESEM',[['PE_CAT_MUNICIPIOS_SEDESEM.ENTIDADFEDERATIVAID','=',
                                                                'PE_OSC.ENTIDADFEDERATIVA_ID'],
                                                               ['PE_CAT_MUNICIPIOS_SEDESEM.MUNICIPIOID'        ,'=',
                                                                'PE_OSC.MUNICIPIO_ID'],
                                                               ['PE_OSC.OSC_ID','<>',0]
                                                              ])
                        ->select(   'PE_OSC.ENTIDADFEDERATIVA_ID','PE_CAT_ENTIDADES_FED.ENTIDADFEDERATIVA_DESC AS ENTIDAD',
                                    'PE_OSC.MUNICIPIO_ID',        'PE_CAT_MUNICIPIOS_SEDESEM.MUNICIPIONOMBRE AS MUNICIPIO', 
                                    'PE_CAT_MUNICIPIOS_SEDESEM.GEOREF_CABMPIO_LATDECIMAL', 
                                    'PE_CAT_MUNICIPIOS_SEDESEM.GEOREF_CABMPIO_LONGDECIMAL')
                        ->selectRaw('COUNT(*) AS TOTAL')
                        ->groupBy(  'PE_OSC.ENTIDADFEDERATIVA_ID','PE_CAT_ENTIDADES_FED.ENTIDADFEDERATIVA_DESC',
                                    'PE_OSC.MUNICIPIO_ID',        'PE_CAT_MUNICIPIOS_SEDESEM.MUNICIPIONOMBRE', 
                                    'PE_CAT_MUNICIPIOS_SEDESEM.GEOREF_CABMPIO_LATDECIMAL', 
                                    'PE_CAT_MUNICIPIOS_SEDESEM.GEOREF_CABMPIO_LONGDECIMAL')
                        ->orderBy('PE_OSC.ENTIDADFEDERATIVA_ID','asc')
                        ->orderBy('PE_OSC.MUNICIPIO_ID'        ,'asc')
                        ->get();
        //dd($procesos);
        return view('sicinar.numeralia.mapaxmpio',compact('regosc','nombre','usuario','rango'));
    }


    // Estadistica por municipio    
    public function OscxMpio(){
        $nombre       = session()->get('userlog');
        $pass         = session()->get('passlog');
        if($nombre == NULL AND $pass == NULL){
            return view('sicinar.login.expirada');
        }
        $usuario      = session()->get('usuario');
        $rango        = session()->get('rango');
        $ip           = session()->get('ip');  
        $arbol_id     = session()->get('arbol_id');              

        $regtotxmpio=regOscModel::join('PE_CAT_MUNICIPIOS_SEDESEM',[['PE_CAT_MUNICIPIOS_SEDESEM.ENTIDADFEDERATIVAID','=',15],
                                                            ['PE_CAT_MUNICIPIOS_SEDESEM.MUNICIPIOID','=','PE_OSC.MUNICIPIO_ID'],['PE_OSC.OSC_ID','<>',0]])
                         ->selectRaw('COUNT(*) AS TOTALXMPIO')
                               ->get();
        $regosc=regOscModel::join('PE_CAT_MUNICIPIOS_SEDESEM',[['PE_CAT_MUNICIPIOS_SEDESEM.ENTIDADFEDERATIVAID','=',15],
                                                            ['PE_CAT_MUNICIPIOS_SEDESEM.MUNICIPIOID','=','PE_OSC.MUNICIPIO_ID'],['PE_OSC.OSC_ID','<>',0]])
                      ->selectRaw('PE_OSC.MUNICIPIO_ID, PE_CAT_MUNICIPIOS_SEDESEM.MUNICIPIONOMBRE AS MUNICIPIO,COUNT(*) AS TOTAL')
                        ->groupBy('PE_OSC.MUNICIPIO_ID', 'PE_CAT_MUNICIPIOS_SEDESEM.MUNICIPIONOMBRE')
                        ->orderBy('PE_OSC.MUNICIPIO_ID','asc')
                        ->get();
        //dd($procesos);
        return view('sicinar.numeralia.oscxmpio',compact('regosc','regtotxmpio','nombre','usuario','rango'));
    }    

    // Gráfica por Rubro social
    public function OscxRubro(){
        $nombre       = session()->get('userlog');
        $pass         = session()->get('passlog');
        if($nombre == NULL AND $pass == NULL){
            return view('sicinar.login.expirada');
        }
        $usuario      = session()->get('usuario');
        $rango        = session()->get('rango');
        $ip           = session()->get('ip'); 
        $arbol_id     = session()->get('arbol_id');               

        $regtotxrubro=regOscModel::join('PE_CAT_RUBROS','PE_CAT_RUBROS.RUBRO_ID','=','PE_OSC.RUBRO_ID')
                      ->selectRaw('COUNT(*) AS TOTALXRUBRO')
                            ->get();
        $regosc=regOscModel::join('PE_CAT_RUBROS','PE_CAT_RUBROS.RUBRO_ID','=','PE_OSC.RUBRO_ID')
                      ->selectRaw('PE_OSC.RUBRO_ID,  PE_CAT_RUBROS.RUBRO_DESC AS RUBRO, COUNT(*) AS TOTAL')
                        ->groupBy('PE_OSC.RUBRO_ID','PE_CAT_RUBROS.RUBRO_DESC')
                        ->orderBy('PE_OSC.RUBRO_ID','asc')
                        ->get();
        //$procesos = procesosModel::join('SCI_TIPO_PROCESO','SCI_PROCESOS.CVE_TIPO_PROC','=','SCI_TIPO_PROCESO.CVE_TIPO_PROC')
        //    ->selectRaw('SCI_TIPO_PROCESO.DESC_TIPO_PROC AS TIPO, COUNT(SCI_PROCESOS.CVE_TIPO_PROC) AS TOTAL')
        //    ->groupBy('SCI_TIPO_PROCESO.DESC_TIPO_PROC')
        //    ->get();
        //dd($procesos);
        return view('sicinar.numeralia.oscxrubro',compact('regosc','regtotxrubro','nombre','usuario','rango'));
    }

    // Gráfica por Rubro social
    public function OscxRubro2(){
        $nombre       = session()->get('userlog');
        $pass         = session()->get('passlog');
        if($nombre == NULL AND $pass == NULL){
            return view('sicinar.login.expirada');
        }
        $usuario      = session()->get('usuario');
        $rango        = session()->get('rango');
        $ip           = session()->get('ip'); 
        $arbol_id     = session()->get('arbol_id');               

        $regtotxrubro=regOscModel::join('PE_CAT_RUBROS','PE_CAT_RUBROS.RUBRO_ID','=','PE_OSC.RUBRO_ID')
                      ->selectRaw('COUNT(*) AS TOTALXRUBRO')
                            ->get();
        $regosc=regOscModel::join('PE_CAT_RUBROS','PE_CAT_RUBROS.RUBRO_ID','=','PE_OSC.RUBRO_ID')
                      ->selectRaw('PE_OSC.RUBRO_ID,  PE_CAT_RUBROS.RUBRO_DESC AS RUBRO, COUNT(*) AS TOTAL')
                        ->groupBy('PE_OSC.RUBRO_ID','PE_CAT_RUBROS.RUBRO_DESC')
                        ->orderBy('PE_OSC.RUBRO_ID','asc')
                        ->get();
        //$procesos = procesosModel::join('SCI_TIPO_PROCESO','SCI_PROCESOS.CVE_TIPO_PROC','=','SCI_TIPO_PROCESO.CVE_TIPO_PROC')
        //    ->selectRaw('SCI_TIPO_PROCESO.DESC_TIPO_PROC AS TIPO, COUNT(SCI_PROCESOS.CVE_TIPO_PROC) AS TOTAL')
        //    ->groupBy('SCI_TIPO_PROCESO.DESC_TIPO_PROC')
        //    ->get();
        //dd($procesos);
        return view('sicinar.numeralia.graficadeprueba',compact('regosc','regtotxrubro','nombre','usuario','rango'));
    }

    // Mapas
    public function Mapas(){
        $nombre       = session()->get('userlog');
        $pass         = session()->get('passlog');
        if($nombre == NULL AND $pass == NULL){
            return view('sicinar.login.expirada');
        }
        $usuario      = session()->get('usuario');
        $rango        = session()->get('rango');
        $ip           = session()->get('ip'); 
        $arbol_id     = session()->get('arbol_id');               

        $regtotxrubro=regOscModel::join('PE_CAT_RUBROS','PE_CAT_RUBROS.RUBRO_ID','=','PE_OSC.RUBRO_ID')
                      ->selectRaw('COUNT(*) AS TOTALXRUBRO')
                            ->get();

        $regosc=regOscModel::join('PE_CAT_RUBROS','PE_CAT_RUBROS.RUBRO_ID','=','PE_OSC.RUBRO_ID')
                      ->selectRaw('PE_OSC.RUBRO_ID,  PE_CAT_RUBROS.RUBRO_DESC AS RUBRO, COUNT(*) AS TOTAL')
                        ->groupBy('PE_OSC.RUBRO_ID','PE_CAT_RUBROS.RUBRO_DESC')
                        ->orderBy('PE_OSC.RUBRO_ID','asc')
                        ->get();
        //$procesos = procesosModel::join('SCI_TIPO_PROCESO','SCI_PROCESOS.CVE_TIPO_PROC','=','SCI_TIPO_PROCESO.CVE_TIPO_PROC')
        //    ->selectRaw('SCI_TIPO_PROCESO.DESC_TIPO_PROC AS TIPO, COUNT(SCI_PROCESOS.CVE_TIPO_PROC) AS TOTAL')
        //    ->groupBy('SCI_TIPO_PROCESO.DESC_TIPO_PROC')
        //    ->get();
        //dd($procesos);
        return view('sicinar.numeralia.mapasdeprueba',compact('regosc','regtotxrubro','nombre','usuario','rango'));
    }

    // Mapas
    public function Mapas2(){
        $nombre       = session()->get('userlog');
        $pass         = session()->get('passlog');
        if($nombre == NULL AND $pass == NULL){
            return view('sicinar.login.expirada');
        }
        $usuario      = session()->get('usuario');
        $rango        = session()->get('rango');
        $ip           = session()->get('ip');
        $arbol_id     = session()->get('arbol_id');                

        $regtotxrubro=regOscModel::join('PE_CAT_RUBROS','PE_CAT_RUBROS.RUBRO_ID','=','PE_OSC.RUBRO_ID')
                      ->selectRaw('COUNT(*) AS TOTALXRUBRO')
                            ->get();

        $regosc=regOscModel::join('PE_CAT_RUBROS','PE_CAT_RUBROS.RUBRO_ID','=','PE_OSC.RUBRO_ID')
                      ->selectRaw('PE_OSC.RUBRO_ID,  PE_CAT_RUBROS.RUBRO_DESC AS RUBRO, COUNT(*) AS TOTAL')
                        ->groupBy('PE_OSC.RUBRO_ID','PE_CAT_RUBROS.RUBRO_DESC')
                        ->orderBy('PE_OSC.RUBRO_ID','asc')
                        ->get();
        //$procesos = procesosModel::join('SCI_TIPO_PROCESO','SCI_PROCESOS.CVE_TIPO_PROC','=','SCI_TIPO_PROCESO.CVE_TIPO_PROC')
        //    ->selectRaw('SCI_TIPO_PROCESO.DESC_TIPO_PROC AS TIPO, COUNT(SCI_PROCESOS.CVE_TIPO_PROC) AS TOTAL')
        //    ->groupBy('SCI_TIPO_PROCESO.DESC_TIPO_PROC')
        //    ->get();
        //dd($procesos);
        return view('sicinar.numeralia.mapasdeprueba2',compact('regosc','regtotxrubro','nombre','usuario','rango'));
    }

    // Mapas
    public function Mapas3(){
        $nombre       = session()->get('userlog');
        $pass         = session()->get('passlog');
        if($nombre == NULL AND $pass == NULL){
            return view('sicinar.login.expirada');
        }
        $usuario      = session()->get('usuario');
        $rango        = session()->get('rango');
        $ip           = session()->get('ip');
        $arbol_id     = session()->get('arbol_id');                

        $regtotxrubro=regOscModel::join('PE_CAT_RUBROS','PE_CAT_RUBROS.RUBRO_ID','=','PE_OSC.RUBRO_ID')
                      ->selectRaw('COUNT(*) AS TOTALXRUBRO')
                            ->get();
        $regosc=regOscModel::join('PE_CAT_RUBROS','PE_CAT_RUBROS.RUBRO_ID','=','PE_OSC.RUBRO_ID')
                      ->selectRaw('PE_OSC.RUBRO_ID,  PE_CAT_RUBROS.RUBRO_DESC AS RUBRO, COUNT(*) AS TOTAL')
                        ->groupBy('PE_OSC.RUBRO_ID','PE_CAT_RUBROS.RUBRO_DESC')
                        ->orderBy('PE_OSC.RUBRO_ID','asc')
                        ->get();
        //dd($procesos);
        return view('sicinar.numeralia.mapasdeprueba3',compact('regosc','regtotxrubro','nombre','usuario','rango'));
    }

}
