<?php
//**************************************************************/
//* File:       progtrabController.php
//* Función:    Programa de trabajo encabezados
//* Autor:      Ing. Silverio Baltazar Barrientos Zarate
//* Modifico:   Ing. Silverio Baltazar Barrientos Zarate
//* Fecha act.: mayo 2021
//* @Derechos reservados. Gobierno del Estado de México
//*************************************************************/
namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests\progtrabRequest;
use App\Http\Requests\progdtrabRequest;

use App\regBitacoraModel;
use App\regOscModel;
use App\regUmedidaModel;
use App\regPeriodosaniosModel;
use App\regPfiscalesModel;
use App\regMesesModel;
use App\regDiasModel;
use App\regProgtrabModel;
use App\regProgdtrabModel;

// Exportar a excel 
use App\Exports\ExportProgtrabExcel;
use Maatwebsite\Excel\Facades\Excel;
// Exportar a pdf
use PDF;
//use Options;

class progtrabController extends Controller
{

    public function actionBuscarProgtrab(Request $request)
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

        //$regumedida   = regUmedidaModel::select('UMEDIDA_ID','UMEDIDA_DESC')->orderBy('UMEDIDA_ID','asc')
        //                ->get(); 
        $regperiodos  = regPfiscalesModel::select('PERIODO_ID', 'PERIODO_DESC')->get();  
        $reganios     = regPeriodosaniosModel::select('PERIODO_ID','PERIODO_DESC')
                        ->wherein('PERIODO_ID',[2020,2021,2022,2023])
                        ->get();
        $regmeses     = regMesesModel::select('MES_ID','MES_DESC')->get();      
        $regdias      = regDiasModel::select('DIA_ID','DIA_DESC')->get(); 
        $totactivs    = regprogtrabModel::join('PE_PROGRAMA_DTRABAJO','PE_PROGRAMA_DTRABAJO.FOLIO', '=', 
                                                                      'PE_PROGRAMA_ETRABAJO.FOLIO')
                        ->select(   'PE_PROGRAMA_ETRABAJO.PERIODO_ID','PE_PROGRAMA_ETRABAJO.FOLIO')
                        ->selectRaw('COUNT(*) AS TOTACTIVIDADES')
                        ->groupBy(  'PE_PROGRAMA_ETRABAJO.PERIODO_ID','PE_PROGRAMA_ETRABAJO.FOLIO')
                        ->get();                              
        //**************************************************************//
        // ***** busqueda https://github.com/rimorsoft/Search-simple ***//
        // ***** video https://www.youtube.com/watch?v=bmtD9GUaszw   ***//                            
        //**************************************************************//
        $folio   = $request->get('folio');   
        $name    = $request->get('name');           
        $acti    = $request->get('acti');  
        $bio     = $request->get('bio');   
        $nameiap = $request->get('nameiap');  
        if(session()->get('rango') !== '0'){    
            $regosc     = regOscModel::select('OSC_ID', 'OSC_DESC','OSC_STATUS')
                          ->get();                                 
            $regprogtrab= regProgtrabModel::join('PE_OSC','PE_OSC.OSC_ID','=','PE_PROGRAMA_ETRABAJO.OSC_ID')
                          ->select( 'PE_OSC.OSC_DESC','PE_PROGRAMA_ETRABAJO.*')
                          ->orderBy('PE_PROGRAMA_ETRABAJO.PERIODO_ID','ASC')
                          ->orderBy('PE_PROGRAMA_ETRABAJO.OSC_ID'    ,'ASC')
                          ->orderBy('PE_PROGRAMA_ETRABAJO.FOLIO'     ,'ASC')
                          //->name($name)     //Metodos personalizados es equvalente a ->where('OSC_DESC', 'LIKE', "%$name%");
                          //->acti($acti)     //Metodos personalizados
                          //->bio($bio)       //Metodos personalizados
                          ->folio($folio)     //Metodos personalizados     
                          ->nameiap($nameiap) //Metodos personalizados                                                                      
                          ->paginate(30);
        }else{
            $regosc     = regOscModel::select('OSC_ID', 'OSC_DESC','OSC_STATUS')
                          ->where('OSC_ID',$arbol_id)
                          ->get();                                        
            $regprogtrab= regProgtrabModel::join('PE_OSC','PE_OSC.OSC_ID','=','PE_PROGRAMA_ETRABAJO.OSC_ID')
                          ->select( 'PE_OSC.OSC_DESC','PE_PROGRAMA_ETRABAJO.*')
                          ->where(  'PE_PROGRAMA_ETRABAJO.OSC_ID'    ,$arbol_id)
                          ->orderBy('PE_PROGRAMA_ETRABAJO.PERIODO_ID','ASC')                          
                          ->orderBy('PE_PROGRAMA_ETRABAJO.OSC_ID'    ,'ASC')
                          ->orderBy('PE_PROGRAMA_ETRABAJO.FOLIO'     ,'ASC')                          
                          ->name($name)      //Metodos personalizados es equvalente a ->where('OSC_DESC', 'LIKE', "%$name%");
                          ->nameiap($nameiap) //Metodos personalizados                                  
                          //->email($email)   //Metodos personalizados
                          //->bio($bio)       //Metodos personalizados
                          ->paginate(30);              
        }                                                                          
        if($regprogtrab->count() <= 0){
            toastr()->error('No existen registros de Programa de trabajo.','Lo siento!',['positionClass' => 'toast-bottom-right']);
            //return redirect()->route('nuevoprogtrab');
        }            
        //return view('sicinar.programatrabajo.verProgtrab', compact('nombre','usuario','regosc','regumedida','reganios','regperiodos','regmeses','regdias','regprogtrab'));
        return view('sicinar.programatrabajo.verProgtrab', compact('nombre','usuario','regosc','reganios','regperiodos','regmeses','regdias','regprogtrab','totactivs'));
    }

    public function actionVerProgtrab(){
        $nombre       = session()->get('userlog');
        $pass         = session()->get('passlog');
        if($nombre == NULL AND $pass == NULL){
            return view('sicinar.login.expirada');
        }
        $usuario      = session()->get('usuario');
        $rango        = session()->get('rango');
        $ip           = session()->get('ip');
        $arbol_id     = session()->get('arbol_id');        

        //$regumedida   = regUmedidaModel::select('UMEDIDA_ID','UMEDIDA_DESC')
        //                ->orderBy('UMEDIDA_ID','asc')
        //                ->get();      
        $reganios     = regPeriodosaniosModel::select('PERIODO_ID','PERIODO_DESC')
                        ->wherein('PERIODO_ID',[2020,2021,2022,2023])        
                        ->get();
        $regperiodos  = regPfiscalesModel::select('PERIODO_ID', 'PERIODO_DESC')->get();         
        $regmeses     = regMesesModel::select('MES_ID','MES_DESC')->get();      
        $regdias      = regDiasModel::select('DIA_ID','DIA_DESC')->get();  
        $regprogdtrab = regProgdtrabModel::select('FOLIO','PARTIDA','PERIODO_ID','OSC_ID','DFECHA_ELAB',
                        'DFECHA_ELAB2','PROGRAMA_ID','PROGRAMA_DESC','ACTIVIDAD_ID',
                        'ACTIVIDAD_DESC','OBJETIVO_ID','OBJETIVO_DESC','UMEDIDA_ID',
                        'MESP_01','MESP_02','MESP_03','MESP_04','MESP_05','MESP_06',
                        'MESP_07','MESP_08','MESP_09','MESP_10','MESP_11','MESP_12',
                        'MESC_01','MESC_02','MESC_03','MESC_04','MESC_05','MESC_06',
                        'MESC_07','MESC_08','MESC_09','MESC_10','MESC_11','MESC_12',
                        'DOBS_1','DOBS_2','DSTATUS_1','DSTATUS_2',
                        'FECREG','IP','LOGIN','FECHA_M','IP_M','LOGIN_M')        
                        ->orderBy('FOLIO'  ,'asc')
                        ->orderBy('PARTIDA','asc')
                        ->get();        
        //********* Validar rol de usuario **********************/
        if(session()->get('rango') !== '0'){  
            $regosc     =regOscModel::select('OSC_ID', 'OSC_DESC','OSC_STATUS')
                         ->get();                        
            $totactivs  =regprogtrabModel::join('PE_PROGRAMA_DTRABAJO','PE_PROGRAMA_DTRABAJO.FOLIO', '=', 
                                                                       'PE_PROGRAMA_ETRABAJO.FOLIO')
                         ->select(   'PE_PROGRAMA_ETRABAJO.PERIODO_ID','PE_PROGRAMA_ETRABAJO.FOLIO')
                         ->selectRaw('COUNT(*) AS TOTACTIVIDADES')
                         ->groupBy(  'PE_PROGRAMA_ETRABAJO.PERIODO_ID','PE_PROGRAMA_ETRABAJO.FOLIO')
                         ->get();                   
            $regprogtrab=regProgtrabModel::select('FOLIO','PERIODO_ID','OSC_ID','FECHA_ELAB','FECHA_ELAB2',
                         'PERIODO_ID1','MES_ID1','DIA_ID1','PROGRAMA_ID','PROGRAMA_DESC',
                         'RESPONSABLE','ELABORO','AUTORIZO','OBS_1','OBS_2','STATUS_1','STATUS_2',
                         'FECREG','IP','LOGIN','FECHA_M','IP_M','LOGIN_M')
                         ->orderBy('PERIODO_ID','ASC')
                         ->orderBy('OSC_ID'    ,'ASC')
                         ->orderBy('FOLIO'     ,'ASC')
                         ->paginate(30);
        }else{                  
            $regosc     =regOscModel::select('OSC_ID', 'OSC_DESC','OSC_STATUS')
                         ->where('OSC_ID',$arbol_id)
                         ->get();                            
            $totactivs  =regprogtrabModel::join('PE_PROGRAMA_DTRABAJO','PE_PROGRAMA_DTRABAJO.FOLIO', '=', 
                                                                       'PE_PROGRAMA_ETRABAJO.FOLIO')
                         ->select(   'PE_PROGRAMA_ETRABAJO.PERIODO_ID','PE_PROGRAMA_ETRABAJO.FOLIO')
                         ->selectRaw('COUNT(*) AS TOTACTIVIDADES')
                         ->where(    'PE_PROGRAMA_ETRABAJO.OSC_ID',$arbol_id) 
                         ->groupBy(  'PE_PROGRAMA_ETRABAJO.PERIODO_ID','PE_PROGRAMA_ETRABAJO.FOLIO')
                         ->get();                               
            $regprogtrab=regProgtrabModel::select('FOLIO','PERIODO_ID','OSC_ID','FECHA_ELAB','FECHA_ELAB2',
                         'PERIODO_ID1','MES_ID1','DIA_ID1','PROGRAMA_ID','PROGRAMA_DESC',
                         'RESPONSABLE','ELABORO','AUTORIZO','OBS_1','OBS_2','STATUS_1','STATUS_2',
                         'FECREG','IP','LOGIN','FECHA_M','IP_M','LOGIN_M')
                         ->where(  'OSC_ID'    ,$arbol_id)            
                         ->orderBy('PERIODO_ID','ASC')
                         ->orderBy('OSC_ID'    ,'ASC')
                         ->orderBy('FOLIO'     ,'ASC')  
                         ->paginate(30);         
        }                        
        if($regprogtrab->count() <= 0){
            toastr()->error('No existe programa de trabajo.','Lo siento!',['positionClass' => 'toast-bottom-right']);
            //return redirect()->route('nuevoprogtrab');
        }
        return view('sicinar.programatrabajo.verProgtrab',compact('nombre','usuario','regosc','reganios','regperiodos','regmeses','regdias','regprogtrab','regprogdtrab','totactivs')); 
    }

    public function actionNuevoProgtrab(){
        $nombre       = session()->get('userlog');
        $pass         = session()->get('passlog');
        if($nombre == NULL AND $pass == NULL){
            return view('sicinar.login.expirada');
        }
        $usuario      = session()->get('usuario');
        $rango        = session()->get('rango');
        $ip           = session()->get('ip');
        $arbol_id     = session()->get('arbol_id');

        //$regumedida   = regUmedidaModel::select('UMEDIDA_ID','UMEDIDA_DESC')->orderBy('UMEDIDA_ID','asc')
        //                ->get();  
        $reganios     = regPeriodosaniosModel::select('PERIODO_ID','PERIODO_DESC')
                        ->wherein('PERIODO_ID',[2020,2021,2022,2023])        
                        ->get();
        $regperiodos  = regPfiscalesModel::select('PERIODO_ID', 'PERIODO_DESC')->get();         
        $regmeses     = regMesesModel::select('MES_ID','MES_DESC')->get();      
        $regdias      = regDiasModel::select('DIA_ID','DIA_DESC')->get();     
        if(session()->get('rango') !== '0'){                           
            $regosc   = regOscModel::select('OSC_ID', 'OSC_DESC','OSC_STATUS')->orderBy('OSC_DESC','ASC')
                        ->get();                                                        
        }else{
            $regosc   = regOscModel::select('OSC_ID', 'OSC_DESC','OSC_STATUS')->orderBy('OSC_DESC','ASC')
                        ->where('OSC_ID',$arbol_id)
                        ->get();            
        }     
        $regprogtrab  = regProgtrabModel::select('FOLIO','PERIODO_ID','OSC_ID','FECHA_ELAB','FECHA_ELAB2',
                        'PERIODO_ID1','MES_ID1','DIA_ID1','PROGRAMA_ID','PROGRAMA_DESC',
                        'RESPONSABLE','ELABORO','AUTORIZO','OBS_1','OBS_2','STATUS_1','STATUS_2',
                        'FECREG','IP','LOGIN','FECHA_M','IP_M','LOGIN_M')
                        ->orderBy('OSC_ID','asc')
                        ->get();
        //dd($unidades);
        return view('sicinar.programatrabajo.nuevoProgtrab',compact('regosc','nombre','usuario','reganios','regperiodos','regmeses','regdias','regprogtrab'));                         
    }

    public function actionAltaNuevoProgtrab(Request $request){
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

        // *************** Validar duplicidad ***********************************/
        $duplicado = regProgtrabModel::where(['PERIODO_ID' => $request->periodo_id,'OSC_ID' => $request->osc_id])
                     ->get();
        if($duplicado->count() >= 1)
            return back()->withInput()->withErrors(['OSC_ID' => 'OSC '.$request->osc_id.' Ya existe programa de trabajo en el mismo periodo y con la IAP referida. Por favor verificar.']);
        else{  
            /************ ALTA  *****************************/ 
            if(!empty($request->periodo_d1) and !empty($request->mes_d1) and !empty($request->dia_d1) ){
                //toastr()->error('muy bien 1.....','¡ok!',['positionClass' => 'toast-bottom-right']);
                $mes1 = regMesesModel::ObtMes($request->mes_id1);
                $dia1 = regDiasModel::ObtDia($request->dia_id1);                
            }   //endif

            $mes1 = regMesesModel::ObtMes($request->mes_id1);
            $dia1 = regDiasModel::ObtDia($request->dia_id1);                

            $folio = regProgtrabModel::max('FOLIO');
            $folio = $folio + 1;

            $nuevoprogtrab = new regProgtrabModel();
            $nuevoprogtrab->FOLIO         = $folio;
            $nuevoprogtrab->PERIODO_ID    = $request->periodo_id;                            
            $nuevoprogtrab->OSC_ID        = $request->osc_id;
            $nuevoprogtrab->FECHA_ELAB    = date('Y/m/d', strtotime(trim($dia1[0]->dia_desc.'/'.$mes1[0]->mes_mes.'/'.$request->periodo_id1) ));
            $nuevoprogtrab->FECHA_ELAB2   = trim($dia1[0]->dia_desc.'/'.$mes1[0]->mes_mes.'/'.$request->periodo_id1);
            $nuevoprogtrab->PERIODO_ID1   = $request->periodo_id1;                
            $nuevoprogtrab->MES_ID1       = $request->mes_id1;                
            $nuevoprogtrab->DIA_ID1       = $request->dia_id1;       

            $nuevoprogtrab->RESPONSABLE   = substr(trim(strtoupper($request->responsable)),0, 99);
            $nuevoprogtrab->ELABORO       = substr(trim(strtoupper($request->elaboro))    ,0, 99);
            $nuevoprogtrab->AUTORIZO      = substr(trim(strtoupper($request->autorizo))   ,0, 99);
            $nuevoprogtrab->OBS_1         = substr(trim(strtoupper($request->obs_1))      ,0,299);
        
            $nuevoprogtrab->IP            = $ip;
            $nuevoprogtrab->LOGIN         = $nombre;         // Usuario ;
            $nuevoprogtrab->save();
            if($nuevoprogtrab->save() == true){
                toastr()->success('Programa de trabajo dado de alta.','ok!',['positionClass' => 'toast-bottom-right']);
                /************ Bitacora inicia *************************************/ 
                setlocale(LC_TIME, "spanish");        
                $xip          = session()->get('ip');
                $xperiodo_id  = (int)date('Y');
                $xprograma_id = 1;
                $xmes_id      = (int)date('m');
                $xproceso_id  =         3;
                $xfuncion_id  =      3011;
                $xtrx_id      =        12;    //Alta
                $regbitacora = regBitacoraModel::select('PERIODO_ID','MES_ID','PROCESO_ID','FUNCION_ID', 
                                                    'TRX_ID','FOLIO','NO_VECES','FECHA_REG','IP','LOGIN', 
                                                    'FECHA_M','IP_M','LOGIN_M')
                               ->where(['PERIODO_ID' => $xperiodo_id,'MES_ID' => $xmes_id,'PROCESO_ID' => $xproceso_id,
                                        'FUNCION_ID' => $xfuncion_id,'TRX_ID' => $xtrx_id,'FOLIO'      => $folio])
                               ->get();
                if($regbitacora->count() <= 0){              // Alta
                    $nuevoregBitacora = new regBitacoraModel();              
                    $nuevoregBitacora->PERIODO_ID = $xperiodo_id;    // Año de transaccion 
                    $nuevoregBitacora->MES_ID     = $xmes_id;        // Mes de transaccion
                    $nuevoregBitacora->PROCESO_ID = $xproceso_id;    // Proceso de apoyo
                    $nuevoregBitacora->FUNCION_ID = $xfuncion_id;    // Funcion del modelado de procesos 
                    $nuevoregBitacora->TRX_ID     = $xtrx_id;        // Actividad del modelado de procesos
                    $nuevoregBitacora->FOLIO      = $folio;          // Folio    
                    $nuevoregBitacora->NO_VECES   = 1;               // Numero de veces            
                    $nuevoregBitacora->IP         = $ip;             // IP
                    $nuevoregBitacora->LOGIN      = $nombre;         // Usuario 
                    $nuevoregBitacora->save();
                    if($nuevoregBitacora->save() == true)
                        toastr()->success('Trx de prog. trab. dada de alta en Bitacora.','¡Ok!',['positionClass' => 'toast-bottom-right']);
                    else
                        toastr()->error('Error trx. de prog. trab al dar de alta en bitacora. Por favor volver a interlo.','Ups!',['positionClass' => 'toast-bottom-right']);
                }else{                   
                    //*********** Obtine el no. de veces *****************************
                    $xno_veces = regBitacoraModel::where(['PERIODO_ID' => $xperiodo_id,'MES_ID'     => $xmes_id, 
                                                          'PROCESO_ID' => $xproceso_id,'FUNCION_ID' => $xfuncion_id, 
                                                          'TRX_ID'     => $xtrx_id,    'FOLIO'      => $folio])
                                 ->max('NO_VECES');
                    $xno_veces = $xno_veces+1;                        
                    //*********** Termina de obtener el no de veces *****************************         
                    $regbitacora = regBitacoraModel::select('NO_VECES','IP_M','LOGIN_M','FECHA_M')
                                   ->where(['PERIODO_ID' => $xperiodo_id,'MES_ID'     => $xmes_id, 
                                            'PROCESO_ID' => $xproceso_id,'FUNCION_ID' => $xfuncion_id,
                                            'TRX_ID'     => $xtrx_id,    'FOLIO'      => $folio])
                                   ->update([
                                         'NO_VECES'=> $regbitacora->NO_VECES = $xno_veces,
                                         'IP_M'    => $regbitacora->IP       = $ip,
                                         'LOGIN_M' => $regbitacora->LOGIN_M  = $nombre,
                                         'FECHA_M' => $regbitacora->FECHA_M  = date('Y/m/d')  //date('d/m/Y')
                                       ]);
                    toastr()->success('Trx de prog. trab. registrada en Bitacora.','¡Ok!',['positionClass' => 'toast-bottom-right']);
                }   /************ Bitacora termina *************************************/ 

            }else{
                toastr()->error('Error en Trx Prog. Trab. Por favor volver a interlo.','Ups!',['positionClass' => 'toast-bottom-right']);
            }   //**************** Termina la alta *******************/
        }       // ******************* Termina el duplicado **********/
        return redirect()->route('verProgtrab');
    } 

    public function actionEditarProgtrab($id){
        $nombre        = session()->get('userlog');
        $pass          = session()->get('passlog');
        if($nombre == NULL AND $pass == NULL){
            return view('sicinar.login.expirada');
        }
        $usuario       = session()->get('usuario');
        $rango         = session()->get('rango');
        $arbol_id      = session()->get('arbol_id');        

        //$regumedida   = regUmedidaModel::select('UMEDIDA_ID','UMEDIDA_DESC')->orderBy('UMEDIDA_ID','asc')
        //                ->get();   
        $reganios     = regPeriodosaniosModel::select('PERIODO_ID','PERIODO_DESC')
                        ->wherein('PERIODO_ID',[2020,2021,2022,2023])        
                        ->get();
        $regperiodos  = regPfiscalesModel::select('PERIODO_ID', 'PERIODO_DESC')->get();         
        $regmeses     = regMesesModel::select('MES_ID','MES_DESC')->get();      
        $regdias      = regDiasModel::select('DIA_ID','DIA_DESC')->get();         
        //********* Validar rol de usuario **********************/
        if(session()->get('rango') !== '0'){                          
            $regosc       = regOscModel::select('OSC_ID', 'OSC_DESC','OSC_STATUS')
                            ->get();                                                        
        }else{
            $regosc   = regOscModel::select('OSC_ID', 'OSC_DESC','OSC_STATUS')
                        ->where('OSC_ID',$arbol_id)
                        ->get();            
        }                    
        $regprogtrab  = regProgtrabModel::select('FOLIO','PERIODO_ID','OSC_ID','FECHA_ELAB','FECHA_ELAB2',
                        'PERIODO_ID1','MES_ID1','DIA_ID1','PROGRAMA_ID','PROGRAMA_DESC',
                        'RESPONSABLE','ELABORO','AUTORIZO','OBS_1','OBS_2','STATUS_1','STATUS_2',
                        'FECREG','IP','LOGIN','FECHA_M','IP_M','LOGIN_M')
                        ->where('FOLIO',$id)
                        ->orderBy('PERIODO_ID','ASC')
                        ->orderBy('OSC_ID','ASC')
                        ->first();
        if($regprogtrab->count() <= 0){
            toastr()->error('No existen registros de programas de trabajo.','Lo siento!',['positionClass' => 'toast-bottom-right']);
        }
        return view('sicinar.programatrabajo.editarProgtrab',compact('nombre','usuario','regosc','reganios','regperiodos','regmeses','regdias','regprogtrab'));
    }

    public function actionActualizarProgtrab(progtrabRequest $request, $id){
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
        $regprogtrab = regProgtrabModel::where('FOLIO',$id);
        if($regprogtrab->count() <= 0)
            toastr()->error('No existe programa de trabajo.','¡Por favor volver a intentar!',['positionClass' => 'toast-bottom-right']);
        else{        
            //********************** Actualizar ********************************/
            //xiap_feccons =null;
            if(!empty($request->periodo_d1) and !empty($request->mes_d1) and !empty($request->dia_d1) ){
                //toastr()->error('muy bien 1....................','¡ok...........!',['positionClass' => 'toast-bottom-right']);
                $mes1 = regMesesModel::ObtMes($request->mes_id1);
                $dia1 = regDiasModel::ObtDia($request->dia_id1);                
                //xiap_feccons = $dia1[0]->dia_desc.'/'.$mes1[0]->mes_mes.'/'.$request->periodo_id1;
            }   //endif
            $mes1 = regMesesModel::ObtMes($request->mes_id1);
            $dia1 = regDiasModel::ObtDia($request->dia_id1);                
            //dd('año 1:',$request->periodo_id1, ' año 2:',$request->periodo_id2,' mes1:',$mes1[0]->mes_mes,' dia1:',$dia1[0]->dia_desc,' mes2:',$mes2[0]->mes_mes, ' dia2:',$dia2[0]->dia_desc);
            $regprogtrab = regProgtrabModel::where('FOLIO',$id)        
                           ->update([                
                'PROGRAMA_DESC' => substr(trim(strtoupper($request->programa_desc)),0,499),

                'FECHA_ELAB'    => date('Y/m/d', strtotime(trim($dia1[0]->dia_desc.'/'.$mes1[0]->mes_mes.'/'.$request->periodo_id1) )),
                'FECHA_ELAB2'   => trim($dia1[0]->dia_desc.'/'.$mes1[0]->mes_mes.'/'.$request->periodo_id1), 
                'PERIODO_ID1'   => $request->periodo_id1,
                'MES_ID1'       => $request->mes_id1,
                'DIA_ID1'       => $request->dia_id1,                

                'ELABORO'       => substr(trim(strtoupper($request->elaboro))    ,0, 99),
                'RESPONSABLE'   => substr(trim(strtoupper($request->responsable)),0, 99),
                'AUTORIZO'      => substr(trim(strtoupper($request->autorizo))   ,0, 99),
                'OBS_1'         => substr(trim(strtoupper($request->obs_1))      ,0,299),        
                'STATUS_1'      => $request->status_1,

                'IP_M'          => $ip,
                'LOGIN_M'       => $nombre,
                'FECHA_M'       => date('Y/m/d')    //date('d/m/Y')                                
                                   ]);
            toastr()->success('Programa de trabajo actualizado.','¡Ok!',['positionClass' => 'toast-bottom-right']);

            /************ Bitacora inicia *************************************/ 
            setlocale(LC_TIME, "spanish");        
            $xip          = session()->get('ip');
            $xperiodo_id  = (int)date('Y');
            $xprograma_id = 1;
            $xmes_id      = (int)date('m');
            $xproceso_id  =         3;
            $xfuncion_id  =      3011;
            $xtrx_id      =        13;    //Actualizar 
            $regbitacora = regBitacoraModel::select('PERIODO_ID',  'MES_ID', 'PROCESO_ID', 'FUNCION_ID', 
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
                    toastr()->success('Trx actualización de prog. trab. registrada en Bitacora.','¡Ok!',['positionClass' => 'toast-bottom-right']);
                else
                    toastr()->error('Error Trx actualización de prog. trab. al dar de alta la bitacora. Por favor volver a interlo.','Ups!',['positionClass' => 'toast-bottom-right']);
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
                                         'NO_VECES'=> $regbitacora->NO_VECES = $xno_veces,
                                         'IP_M'    => $regbitacora->IP       = $ip,
                                         'LOGIN_M' => $regbitacora->LOGIN_M  = $nombre,
                                         'FECHA_M' => $regbitacora->FECHA_M  = date('Y/m/d')  //date('d/m/Y')
                                         ]);
                toastr()->success('Trx actualización de prog. trab. registrada en Bitacora.','¡Ok!',['positionClass' => 'toast-bottom-right']);
            }   /************ Bitacora termina *************************************/                     
        }       /************ Actualizar *******************************************/
        return redirect()->route('verProgtrab');
    }

    public function actionBorrarProgtrab($id){
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

        /************ Elimina la IAP **************************************/
        $regprogtrab  = regProgtrabModel::where('FOLIO',$id);
        //              ->find('UMEDIDA_ID',$id);
        if($regprogtrab->count() <= 0)
            toastr()->error('No existe programa de trabajo.','¡Por favor volver a intentar!',['positionClass' => 'toast-bottom-right']);
        else{        
            $regprogtrab->delete();
            toastr()->success('Programa de trabajo eliminado.','¡Ok!',['positionClass' => 'toast-bottom-right']);

            //echo 'Ya entre a borrar registro..........';
            /************ Bitacora inicia *************************************/ 
            setlocale(LC_TIME, "spanish");        
            $xip          = session()->get('ip');
            $xperiodo_id  = (int)date('Y');
            $xprograma_id = 1;
            $xmes_id      = (int)date('m');
            $xproceso_id  =         3;
            $xfuncion_id  =      3011;
            $xtrx_id      =        14;     // Baja 
            $regbitacora = regBitacoraModel::select('PERIODO_ID','MES_ID', 'PROCESO_ID','FUNCION_ID', 
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
                    toastr()->success('Trx de elimiar de prog. trab. registrada en Bitacora.','¡Ok!',['positionClass' => 'toast-bottom-right']);
                else
                    toastr()->error('Error de Trx de elimiar de prog. trab. al dar de alta en bitacora. Por favor volver a interlo.','Ups!',['positionClass' => 'toast-bottom-right']);
            }else{                   
                //*********** Obtine el no. de veces *****************************
                $xno_veces = regBitacoraModel::where(['PERIODO_ID' => $xperiodo_id,'MES_ID' => $xmes_id,'PROCESO_ID' => $xproceso_id, 
                                                      'FUNCION_ID' => $xfuncion_id,'TRX_ID' => $xtrx_id,'FOLIO'      => $id])
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
                toastr()->success('Trx de elimiar de prog. trab. registrada en Bitacora.','¡Ok!',['positionClass' => 'toast-bottom-right']);
            }   /************ Bitacora termina *************************************/                 
        }       /************* Termina de eliminar *********************************/
        return redirect()->route('verProgtrab');
    }    

    // exportar a formato excel
    public function actionExportProgtrabExcel($id){
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
        $xfuncion_id  =      3011;
        $xtrx_id      =        15;            // Exportar a formato Excel
        $id           =         0;
        $regbitacora  = regBitacoraModel::select('PERIODO_ID',  'MES_ID', 'PROCESO_ID', 'FUNCION_ID', 
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
               toastr()->success('Trx de exportar a excel Prog. Trab. registrada en Bitacora.','¡Ok!',['positionClass' => 'toast-bottom-right']);
            else
               toastr()->error('Error Trx de exportar a excel Prog. Trab. al dar de alta en bitacora. Por favor volver a interlo.','Ups!',['positionClass' => 'toast-bottom-right']);
        }else{                   
            //*********** Obtine el no. de veces *****************************
            $xno_veces = regBitacoraModel::where(['PERIODO_ID' => $xperiodo_id,'MES_ID'     => $xmes_id,
                                                  'PROCESO_ID' => $xproceso_id,'FUNCION_ID' => $xfuncion_id, 
                                                  'TRX_ID'     => $xtrx_id,    'FOLIO'      => $id])
                         ->max('NO_VECES');
            $xno_veces = $xno_veces+1;                        
            //*********** Termina de obtener el no de veces *****************************                
            $regbitacora = regBitacoraModel::select('NO_VECES','IP_M','LOGIN_M','FECHA_M')
                           ->where(['PERIODO_ID' => $xperiodo_id, 'MES_ID' => $xmes_id,'PROCESO_ID' => $xproceso_id, 
                                    'FUNCION_ID' => $xfuncion_id, 'TRX_ID' => $xtrx_id,'FOLIO'      => $id])
                           ->update([
                                     'NO_VECES' => $regbitacora->NO_VECES = $xno_veces,
                                     'IP_M'     => $regbitacora->IP       = $ip,
                                     'LOGIN_M'  => $regbitacora->LOGIN_M  = $nombre,
                                     'FECHA_M'  => $regbitacora->FECHA_M  = date('Y/m/d')  //date('d/m/Y')
                                    ]);
            toastr()->success('Trx de exportar a excel Prog. Trab. registrada en Bitacora.','¡Ok!',['positionClass' => 'toast-bottom-right']);
        }   /********************** Bitacora termina *************************************/  
        return Excel::download(new ExportProgtrabExcel, 'Programa_Trabajo_'.date('d-m-Y').'.xlsx');
    }

    // exportar a formato PDF
    public function actionExportProgtrabPdf($id,$id2){
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
        $xfuncion_id  =      3011;
        $xtrx_id      =        16;       //Exportar a formato PDF
        $id           =         0;
        $regbitacora = regBitacoraModel::select('PERIODO_ID',  'MES_ID', 'PROCESO_ID', 'FUNCION_ID', 
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
               toastr()->success('Trx de exportar a PDF Prog. Trab. registrada en Bitacora.','¡Ok!',['positionClass' => 'toast-bottom-right']);
            else
               toastr()->error('Error de Trx de exportar a excel Prog. Trab. al dar de alta la bitacora. Por favor volver a interlo.','Ups!',['positionClass' => 'toast-bottom-right']);
        }else{                   
            //*********** Obtine el no. de veces *****************************
            $xno_veces = regBitacoraModel::where(['PERIODO_ID' => $xperiodo_id,'MES_ID' => $xmes_id,'PROCESO_ID' => $xproceso_id, 
                                                  'FUNCION_ID' => $xfuncion_id,'TRX_ID' => $xtrx_id,'FOLIO'      => $id])
                        ->max('NO_VECES');
            $xno_veces = $xno_veces+1;                        
            //*********** Termina de obtener el no de veces *****************************         
            $regbitacora = regBitacoraModel::select('NO_VECES','IP_M','LOGIN_M','FECHA_M')
                           ->where(['PERIODO_ID' => $xperiodo_id,'MES_ID' => $xmes_id,'PROCESO_ID' => $xproceso_id, 
                                    'FUNCION_ID' => $xfuncion_id,'TRX_ID' => $xtrx_id,'FOLIO'      => $id])
                           ->update([
                                     'NO_VECES'=> $regbitacora->NO_VECES = $xno_veces,
                                     'IP_M'    => $regbitacora->IP       = $ip,
                                     'LOGIN_M' => $regbitacora->LOGIN_M  = $nombre,
                                     'FECHA_M' => $regbitacora->FECHA_M  = date('Y/m/d')  //date('d/m/Y')
                                    ]);
            toastr()->success('Trx de exportar a excel Prog. Trab. actualizada en Bitacora.','¡Ok!',['positionClass' => 'toast-bottom-right']);
        }   /************ Bitacora termina *************************************/ 

        $regumedida   = regUmedidaModel::select('UMEDIDA_ID','UMEDIDA_DESC')->get(); 
        $regosc       = regOscModel::select('OSC_ID', 'OSC_DESC')->get();   
        //********* Validar rol de usuario **********************/
        if(session()->get('rango') !== '0'){                 
            $regprogtrab= regProgtrabModel::select('FOLIO','PERIODO_ID','OSC_ID','FECHA_ELAB','FECHA_ELAB2',
                          'PERIODO_ID1','MES_ID1','DIA_ID1','PROGRAMA_ID','PROGRAMA_DESC',
                          'RESPONSABLE','ELABORO','AUTORIZO','OBS_1','OBS_2','STATUS_1','STATUS_2',
                          'FECREG','IP','LOGIN','FECHA_M','IP_M','LOGIN_M')
                          ->where('FOLIO',$id2)                                 
                          ->get();
        }else{
            $regprogtrab= regProgtrabModel::select('FOLIO','PERIODO_ID','OSC_ID','FECHA_ELAB','FECHA_ELAB2',
                          'PERIODO_ID1','MES_ID1','DIA_ID1','PROGRAMA_ID','PROGRAMA_DESC',
                          'RESPONSABLE','ELABORO','AUTORIZO','OBS_1','OBS_2','STATUS_1','STATUS_2',
                          'FECREG','IP','LOGIN','FECHA_M','IP_M','LOGIN_M')
                          ->where(['FOLIO' => $id2,'OSC_ID' => $arbol_id])
                          ->orderBy('PERIODO_ID','ASC')
                          ->orderBy('FOLIO'     ,'ASC')
                          ->get();            
        }                                       
        $regprogdtrab=regProgdtrabModel::join('PE_CAT_UNID_MEDIDA' ,'PE_CAT_UNID_MEDIDA.UMEDIDA_ID','=',
                                                                    'PE_PROGRAMA_DTRABAJO.UMEDIDA_ID')
                ->select('PE_PROGRAMA_DTRABAJO.PARTIDA', 
                         'PE_PROGRAMA_DTRABAJO.PROGRAMA_DESC', 
                         'PE_PROGRAMA_DTRABAJO.ACTIVIDAD_DESC', 
                         'PE_PROGRAMA_DTRABAJO.OBJETIVO_DESC',
                         'PE_PROGRAMA_DTRABAJO.UMEDIDA_ID', 
                         'PE_CAT_UNID_MEDIDA.UMEDIDA_DESC', 
                         'PE_PROGRAMA_DTRABAJO.MESP_01', 'PE_PROGRAMA_DTRABAJO.MESP_02', 'PE_PROGRAMA_DTRABAJO.MESP_03', 
                         'PE_PROGRAMA_DTRABAJO.MESP_04', 'PE_PROGRAMA_DTRABAJO.MESP_05', 'PE_PROGRAMA_DTRABAJO.MESP_06', 
                         'PE_PROGRAMA_DTRABAJO.MESP_07', 'PE_PROGRAMA_DTRABAJO.MESP_08', 'PE_PROGRAMA_DTRABAJO.MESP_09', 
                         'PE_PROGRAMA_DTRABAJO.MESP_10', 'PE_PROGRAMA_DTRABAJO.MESP_11', 'PE_PROGRAMA_DTRABAJO.MESP_12' 
                            )
                ->selectRaw('(PE_PROGRAMA_DTRABAJO.MESP_01+PE_PROGRAMA_DTRABAJO.MESP_02+PE_PROGRAMA_DTRABAJO.MESP_03+
                              PE_PROGRAMA_DTRABAJO.MESP_04+PE_PROGRAMA_DTRABAJO.MESP_05+PE_PROGRAMA_DTRABAJO.MESP_06+
                              PE_PROGRAMA_DTRABAJO.MESP_07+PE_PROGRAMA_DTRABAJO.MESP_08+PE_PROGRAMA_DTRABAJO.MESP_09+
                              PE_PROGRAMA_DTRABAJO.MESP_10+PE_PROGRAMA_DTRABAJO.MESP_11+PE_PROGRAMA_DTRABAJO.MESP_12)
                              META_PROGRAMADA')
                ->where(  'PE_PROGRAMA_DTRABAJO.FOLIO'     ,$id2)
                ->orderBy('PE_PROGRAMA_DTRABAJO.PERIODO_ID','ASC')                   
                ->orderBy('PE_PROGRAMA_DTRABAJO.FOLIO'     ,'ASC')
                ->orderBy('PE_PROGRAMA_DTRABAJO.PARTIDA'   ,'ASC')
                ->get();    
        //dd('Llave:',$id,' llave2:',$id2);       
        if($regprogdtrab->count() <= 0){
            toastr()->error('No existen registros de actividades del programa de trabajo.','Uppss!',['positionClass' => 'toast-bottom-right']);
            return redirect()->route('verProgtrab');
        }else{
            $pdf = PDF::loadView('sicinar.pdf.ProgtrabPdf',compact('nombre','usuario','regumedida','regosc','regprogtrab','regprogdtrab'));
            //$options = new Options();
            //$options->set('defaultFont', 'Courier');
            //$pdf->set_option('defaultFont', 'Courier');
            $pdf->setPaper('A4', 'landscape');      
            //$pdf->set('defaultFont', 'Courier');          
            //$pdf->setPaper('A4','portrait');

            // Output the generated PDF to Browser
            return $pdf->stream('Programa_Trabajo-'.$id2);
        }
    }


    // Gráfica por estado
    public function IapxEdo(){
        $nombre       = session()->get('userlog');
        $pass         = session()->get('passlog');
        if($nombre == NULL AND $pass == NULL){
            return view('sicinar.login.expirada');
        }
        $usuario      = session()->get('usuario');
        $rango        = session()->get('rango');
        $ip           = session()->get('ip'); 
        $arbol_id     = session()->get('arbol_id');               

        $regtotxedo=regOscModel::join('JP_CAT_ENTIDADES_FED',[['JP_CAT_ENTIDADES_FED.ENTIDADFEDERATIVA_ID','=','PE_OSC.ENTIDADFEDERATIVA_ID'],['PE_OSC.OSC_ID','<>',0]])
                         ->selectRaw('COUNT(*) AS TOTALXEDO')
                               ->get();

        $regosc=regOscModel::join('JP_CAT_ENTIDADES_FED',[['JP_CAT_ENTIDADES_FED.ENTIDADFEDERATIVA_ID','=','PE_OSC.ENTIDADFEDERATIVA_ID'],['PE_OSC.OSC_ID','<>',0]])
                      ->selectRaw('PE_OSC.ENTIDADFEDERATIVA_ID, JP_CAT_ENTIDADES_FED.ENTIDADFEDERATIVA_DESC AS ESTADO, COUNT(*) AS TOTAL')
                        ->groupBy('PE_OSC.ENTIDADFEDERATIVA_ID', 'JP_CAT_ENTIDADES_FED.ENTIDADFEDERATIVA_DESC')
                        ->orderBy('PE_OSC.ENTIDADFEDERATIVA_ID','asc')
                        ->get();
        //$procesos = procesosModel::join('SCI_TIPO_PROCESO','SCI_PROCESOS.CVE_TIPO_PROC','=','SCI_TIPO_PROCESO.CVE_TIPO_PROC')
        //    ->selectRaw('SCI_TIPO_PROCESO.DESC_TIPO_PROC AS TIPO, COUNT(SCI_PROCESOS.CVE_TIPO_PROC) AS TOTAL')
        //    ->groupBy('SCI_TIPO_PROCESO.DESC_TIPO_PROC')
        //    ->get();
        //dd($procesos);
        return view('sicinar.numeralia.iapxedo',compact('regosc','regtotxedo','nombre','usuario','rango'));
    }


    // Gráfica demanda de transacciones (Bitacora)
    public function Bitacora(){
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
        $regbitatxmes=regBitacoraModel::join('JP_CAT_PROCESOS','JP_CAT_PROCESOS.PROCESO_ID' ,'=','JP_BITACORA.PROCESO_ID')
                                   ->join('JP_CAT_FUNCIONES','JP_CAT_FUNCIONES.FUNCION_ID','=','JP_BITACORA.FUNCION_ID')
                                   ->join('JP_CAT_TRX'      ,'JP_CAT_TRX.TRX_ID'          ,'=','JP_BITACORA.TRX_ID')
                                   ->join('JP_CAT_MESES'    ,'JP_CAT_MESES.MES_ID'        ,'=','JP_BITACORA.MES_ID')
                         ->select('JP_BITACORA.MES_ID','JP_CAT_MESES.MES_DESC')
                         ->selectRaw('COUNT(*) AS TOTALGENERAL')
                         ->groupBy('JP_BITACORA.MES_ID','JP_CAT_MESES.MES_DESC')
                         ->orderBy('JP_BITACORA.MES_ID','asc')
                         ->get();        
        $regbitatot=regBitacoraModel::join('JP_CAT_PROCESOS','JP_CAT_PROCESOS.PROCESO_ID' ,'=','JP_BITACORA.PROCESO_ID')
                                   ->join('JP_CAT_FUNCIONES','JP_CAT_FUNCIONES.FUNCION_ID','=','JP_BITACORA.FUNCION_ID')
                                   ->join('JP_CAT_TRX'      ,'JP_CAT_TRX.TRX_ID'          ,'=','JP_BITACORA.TRX_ID')
                         ->selectRaw('SUM(CASE WHEN JP_BITACORA.MES_ID = 1 THEN 1 END) AS M01')  
                         ->selectRaw('SUM(CASE WHEN JP_BITACORA.MES_ID = 2 THEN 1 END) AS M02')
                         ->selectRaw('SUM(CASE WHEN JP_BITACORA.MES_ID = 3 THEN 1 END) AS M03')
                         ->selectRaw('SUM(CASE WHEN JP_BITACORA.MES_ID = 4 THEN 1 END) AS M04')
                         ->selectRaw('SUM(CASE WHEN JP_BITACORA.MES_ID = 5 THEN 1 END) AS M05')
                         ->selectRaw('SUM(CASE WHEN JP_BITACORA.MES_ID = 6 THEN 1 END) AS M06')
                         ->selectRaw('SUM(CASE WHEN JP_BITACORA.MES_ID = 7 THEN 1 END) AS M07')
                         ->selectRaw('SUM(CASE WHEN JP_BITACORA.MES_ID = 8 THEN 1 END) AS M08')
                         ->selectRaw('SUM(CASE WHEN JP_BITACORA.MES_ID = 9 THEN 1 END) AS M09')
                         ->selectRaw('SUM(CASE WHEN JP_BITACORA.MES_ID =10 THEN 1 END) AS M10')
                         ->selectRaw('SUM(CASE WHEN JP_BITACORA.MES_ID =11 THEN 1 END) AS M11')
                         ->selectRaw('SUM(CASE WHEN JP_BITACORA.MES_ID =12 THEN 1 END) AS M12')
                         ->selectRaw('COUNT(*) AS TOTALGENERAL')
                         ->get();

        $regbitacora=regBitacoraModel::join('JP_CAT_PROCESOS' ,'JP_CAT_PROCESOS.PROCESO_ID' ,'=','JP_BITACORA.PROCESO_ID')
                                     ->join('JP_CAT_FUNCIONES','JP_CAT_FUNCIONES.FUNCION_ID','=','JP_BITACORA.FUNCION_ID')
                                     ->join('JP_CAT_TRX'      ,'JP_CAT_TRX.TRX_ID'          ,'=','JP_BITACORA.TRX_ID')
                    ->select('JP_BITACORA.PERIODO_ID', 'JP_BITACORA.PROGRAMA_ID', 'JP_BITACORA.PROCESO_ID', 
                                'JP_CAT_PROCESOS.PROCESO_DESC', 'JP_BITACORA.FUNCION_ID', 'JP_CAT_FUNCIONES.FUNCION_DESC', 
                                'JP_BITACORA.TRX_ID', 'JP_CAT_TRX.TRX_DESC')
                    ->selectRaw('SUM(CASE WHEN JP_BITACORA.MES_ID = 1 THEN 1 END) AS ENE')  
                    ->selectRaw('SUM(CASE WHEN JP_BITACORA.MES_ID = 2 THEN 1 END) AS FEB')
                    ->selectRaw('SUM(CASE WHEN JP_BITACORA.MES_ID = 3 THEN 1 END) AS MAR')
                    ->selectRaw('SUM(CASE WHEN JP_BITACORA.MES_ID = 4 THEN 1 END) AS ABR')
                    ->selectRaw('SUM(CASE WHEN JP_BITACORA.MES_ID = 5 THEN 1 END) AS MAY')
                    ->selectRaw('SUM(CASE WHEN JP_BITACORA.MES_ID = 6 THEN 1 END) AS JUN')
                    ->selectRaw('SUM(CASE WHEN JP_BITACORA.MES_ID = 7 THEN 1 END) AS JUL')
                    ->selectRaw('SUM(CASE WHEN JP_BITACORA.MES_ID = 8 THEN 1 END) AS AGO')
                    ->selectRaw('SUM(CASE WHEN JP_BITACORA.MES_ID = 9 THEN 1 END) AS SEP')
                    ->selectRaw('SUM(CASE WHEN JP_BITACORA.MES_ID =10 THEN 1 END) AS OCT')
                    ->selectRaw('SUM(CASE WHEN JP_BITACORA.MES_ID =11 THEN 1 END) AS NOV')
                    ->selectRaw('SUM(CASE WHEN JP_BITACORA.MES_ID =12 THEN 1 END) AS DIC')                   
                    ->selectRaw('COUNT(*) AS SUMATOTAL')
                    ->groupBy('JP_BITACORA.PERIODO_ID', 'JP_BITACORA.PROGRAMA_ID','JP_BITACORA.PROCESO_ID', 
                              'JP_CAT_PROCESOS.PROCESO_DESC','JP_BITACORA.FUNCION_ID','JP_CAT_FUNCIONES.FUNCION_DESC', 
                              'JP_BITACORA.TRX_ID', 'JP_CAT_TRX.TRX_DESC')
                    ->orderBy('JP_BITACORA.PERIODO_ID', 'JP_BITACORA.PROGRAMA_ID','JP_BITACORA.PROCESO_ID', 
                              'JP_CAT_PROCESOS.PROCESO_DESC','JP_BITACORA.FUNCION_ID','JP_CAT_FUNCIONES.FUNCION_DESC',
                              'JP_BITACORA.TRX_ID', 'JP_CAT_TRX.TRX_DESC','asc')
                    ->get();
        //dd($procesos);
        return view('sicinar.numeralia.bitacora',compact('regbitatxmes','regbitacora','regbitatot','nombre','usuario','rango'));
    }

    // Gráfica de IAP por municipio
    public function IapxMpio(){
        $nombre       = session()->get('userlog');
        $pass         = session()->get('passlog');
        if($nombre == NULL AND $pass == NULL){
            return view('sicinar.login.expirada');
        }
        $usuario      = session()->get('usuario');
        $rango        = session()->get('rango');
        $ip           = session()->get('ip');  
        $arbol_id     = session()->get('arbol_id');              

        $regtotxmpio=regOscModel::join('JP_CAT_MUNICIPIOS_SEDESEM',[['JP_CAT_MUNICIPIOS_SEDESEM.ENTIDADFEDERATIVAID','=',15],
                                                            ['JP_CAT_MUNICIPIOS_SEDESEM.MUNICIPIOID','=','PE_OSC.MUNICIPIO_ID'],['PE_OSC.OSC_ID','<>',0]])
                         ->selectRaw('COUNT(*) AS TOTALXMPIO')
                               ->get();
        $regosc=regOscModel::join('JP_CAT_MUNICIPIOS_SEDESEM',[['JP_CAT_MUNICIPIOS_SEDESEM.ENTIDADFEDERATIVAID','=',15],
                                                            ['JP_CAT_MUNICIPIOS_SEDESEM.MUNICIPIOID','=','PE_OSC.MUNICIPIO_ID'],['PE_OSC.OSC_ID','<>',0]])
                      ->selectRaw('PE_OSC.MUNICIPIO_ID, JP_CAT_MUNICIPIOS_SEDESEM.MUNICIPIONOMBRE AS MUNICIPIO,COUNT(*) AS TOTAL')
                        ->groupBy('PE_OSC.MUNICIPIO_ID', 'JP_CAT_MUNICIPIOS_SEDESEM.MUNICIPIONOMBRE')
                        ->orderBy('PE_OSC.MUNICIPIO_ID','asc')
                        ->get();
        //dd($procesos);
        return view('sicinar.numeralia.iapxmpio',compact('regosc','regtotxmpio','nombre','usuario','rango'));
    }

    // Gráfica de IAP por Rubro social
    public function IapxRubro(){
        $nombre       = session()->get('userlog');
        $pass         = session()->get('passlog');
        if($nombre == NULL AND $pass == NULL){
            return view('sicinar.login.expirada');
        }
        $usuario      = session()->get('usuario');
        $rango        = session()->get('rango');
        $ip           = session()->get('ip'); 
        $arbol_id     = session()->get('arbol_id');               

        $regtotxrubro=regOscModel::join('JP_CAT_RUBROS','JP_CAT_RUBROS.UMEDIDA_ID','=','PE_OSC.UMEDIDA_ID')
                      ->selectRaw('COUNT(*) AS TOTALXRUBRO')
                            ->get();
        $regosc=regOscModel::join('JP_CAT_RUBROS','JP_CAT_RUBROS.UMEDIDA_ID','=','PE_OSC.UMEDIDA_ID')
                      ->selectRaw('PE_OSC.UMEDIDA_ID,  JP_CAT_RUBROS.RUBRO_DESC AS RUBRO, COUNT(*) AS TOTAL')
                        ->groupBy('PE_OSC.UMEDIDA_ID','JP_CAT_RUBROS.RUBRO_DESC')
                        ->orderBy('PE_OSC.UMEDIDA_ID','asc')
                        ->get();
        //$procesos = procesosModel::join('SCI_TIPO_PROCESO','SCI_PROCESOS.CVE_TIPO_PROC','=','SCI_TIPO_PROCESO.CVE_TIPO_PROC')
        //    ->selectRaw('SCI_TIPO_PROCESO.DESC_TIPO_PROC AS TIPO, COUNT(SCI_PROCESOS.CVE_TIPO_PROC) AS TOTAL')
        //    ->groupBy('SCI_TIPO_PROCESO.DESC_TIPO_PROC')
        //    ->get();
        //dd($procesos);
        return view('sicinar.numeralia.iapxrubro',compact('regosc','regtotxrubro','nombre','usuario','rango'));
    }

    // Gráfica de IAP por Rubro social
    public function IapxRubro2(){
        $nombre       = session()->get('userlog');
        $pass         = session()->get('passlog');
        if($nombre == NULL AND $pass == NULL){
            return view('sicinar.login.expirada');
        }
        $usuario      = session()->get('usuario');
        $rango        = session()->get('rango');
        $ip           = session()->get('ip'); 
        $arbol_id     = session()->get('arbol_id');               

        $regtotxrubro=regOscModel::join('JP_CAT_RUBROS','JP_CAT_RUBROS.UMEDIDA_ID','=','PE_OSC.UMEDIDA_ID')
                      ->selectRaw('COUNT(*) AS TOTALXRUBRO')
                            ->get();
        $regosc=regOscModel::join('JP_CAT_RUBROS','JP_CAT_RUBROS.UMEDIDA_ID','=','PE_OSC.UMEDIDA_ID')
                      ->selectRaw('PE_OSC.UMEDIDA_ID,  JP_CAT_RUBROS.RUBRO_DESC AS RUBRO, COUNT(*) AS TOTAL')
                        ->groupBy('PE_OSC.UMEDIDA_ID','JP_CAT_RUBROS.RUBRO_DESC')
                        ->orderBy('PE_OSC.UMEDIDA_ID','asc')
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

        $regtotxrubro=regOscModel::join('JP_CAT_RUBROS','JP_CAT_RUBROS.UMEDIDA_ID','=','PE_OSC.UMEDIDA_ID')
                      ->selectRaw('COUNT(*) AS TOTALXRUBRO')
                            ->get();

        $regosc=regOscModel::join('JP_CAT_RUBROS','JP_CAT_RUBROS.UMEDIDA_ID','=','PE_OSC.UMEDIDA_ID')
                      ->selectRaw('PE_OSC.UMEDIDA_ID,  JP_CAT_RUBROS.RUBRO_DESC AS RUBRO, COUNT(*) AS TOTAL')
                        ->groupBy('PE_OSC.UMEDIDA_ID','JP_CAT_RUBROS.RUBRO_DESC')
                        ->orderBy('PE_OSC.UMEDIDA_ID','asc')
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

        $regtotxrubro=regOscModel::join('JP_CAT_RUBROS','JP_CAT_RUBROS.UMEDIDA_ID','=','PE_OSC.UMEDIDA_ID')
                      ->selectRaw('COUNT(*) AS TOTALXRUBRO')
                            ->get();

        $regosc=regOscModel::join('JP_CAT_RUBROS','JP_CAT_RUBROS.UMEDIDA_ID','=','PE_OSC.UMEDIDA_ID')
                      ->selectRaw('PE_OSC.UMEDIDA_ID,  JP_CAT_RUBROS.RUBRO_DESC AS RUBRO, COUNT(*) AS TOTAL')
                        ->groupBy('PE_OSC.UMEDIDA_ID','JP_CAT_RUBROS.RUBRO_DESC')
                        ->orderBy('PE_OSC.UMEDIDA_ID','asc')
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

        $regtotxrubro=regOscModel::join('JP_CAT_RUBROS','JP_CAT_RUBROS.UMEDIDA_ID','=','PE_OSC.UMEDIDA_ID')
                      ->selectRaw('COUNT(*) AS TOTALXRUBRO')
                            ->get();
        $regosc=regOscModel::join('JP_CAT_RUBROS','JP_CAT_RUBROS.UMEDIDA_ID','=','PE_OSC.UMEDIDA_ID')
                      ->selectRaw('PE_OSC.UMEDIDA_ID,  JP_CAT_RUBROS.RUBRO_DESC AS RUBRO, COUNT(*) AS TOTAL')
                        ->groupBy('PE_OSC.UMEDIDA_ID','JP_CAT_RUBROS.RUBRO_DESC')
                        ->orderBy('PE_OSC.UMEDIDA_ID','asc')
                        ->get();
        //dd($procesos);
        return view('sicinar.numeralia.mapasdeprueba3',compact('regosc','regtotxrubro','nombre','usuario','rango'));
    }

    //*****************************************************************************//
    //*************************************** Detalle *****************************//
    //*****************************************************************************//
    public function actionVerProgdtrab($id){
        $nombre       = session()->get('userlog');
        $pass         = session()->get('passlog');
        if($nombre == NULL AND $pass == NULL){
            return view('sicinar.login.expirada');
        }
        $usuario      = session()->get('usuario');
        $rango        = session()->get('rango');
        $ip           = session()->get('ip');
        $arbol_id     = session()->get('arbol_id');        

        $regumedida   = regUmedidaModel::select('UMEDIDA_ID','UMEDIDA_DESC')
                        ->orderBy('UMEDIDA_ID','asc')
                        ->get();      
        $regperiodos  = regPfiscalesModel::select('PERIODO_ID', 'PERIODO_DESC')->get();         
        $reganios     = regPeriodosaniosModel::select('PERIODO_ID','PERIODO_DESC')
                        ->wherein('PERIODO_ID',[2020,2021,2022,2023])        
                        ->get();        
        $regmeses     = regMesesModel::select('MES_ID','MES_DESC')->get();      
        $regdias      = regDiasModel::select('DIA_ID','DIA_DESC')->get();                                
        $regosc       = regOscModel::select('OSC_ID', 'OSC_DESC')->orderBy('OSC_DESC','asc')->get();                     
        //************** Validar rol de usuario **********************/
        if(session()->get('rango') !== '0'){           
            $regprogtrab=regProgtrabModel::select('FOLIO','PERIODO_ID','OSC_ID','FECHA_ELAB','FECHA_ELAB2',
                         'PERIODO_ID1','MES_ID1','DIA_ID1','PROGRAMA_ID','PROGRAMA_DESC',
                         'RESPONSABLE','ELABORO','AUTORIZO','OBS_1','OBS_2','STATUS_1','STATUS_2',
                         'FECREG','IP','LOGIN','FECHA_M','IP_M','LOGIN_M')
                         ->where('FOLIO',$id)
                         ->orderBy('PERIODO_ID','ASC')
                         ->orderBy('OSC_ID','ASC')
                         ->get();
        }else{                         
            $regprogtrab=regProgtrabModel::select('FOLIO','PERIODO_ID','OSC_ID','FECHA_ELAB','FECHA_ELAB2',
                         'PERIODO_ID1','MES_ID1','DIA_ID1','PROGRAMA_ID','PROGRAMA_DESC',
                         'RESPONSABLE','ELABORO','AUTORIZO','OBS_1','OBS_2','STATUS_1','STATUS_2',
                         'FECREG','IP','LOGIN','FECHA_M','IP_M','LOGIN_M')
                         ->where(['FOLIO' => $id, 'OSC_ID' => $arbol_id])
                         //->where('FOLIO',$id)            
                         //->where('OSC_ID',$arbol_id)            
                         ->orderBy('PERIODO_ID','ASC')
                         ->orderBy('OSC_ID'    ,'ASC')
                         ->get();
        }                        
        if($regprogtrab->count() <= 0){
            toastr()->error('No existe programa de trabajo.','Lo siento!',['positionClass' => 'toast-bottom-right']);
            //return redirect()->route('nuevoprogtrab');
        }
        $regprogdtrab   = regProgdtrabModel::select('FOLIO','PARTIDA','PERIODO_ID','OSC_ID','DFECHA_ELAB',
                        'DFECHA_ELAB2','PROGRAMA_ID','PROGRAMA_DESC','ACTIVIDAD_ID',
                        'ACTIVIDAD_DESC','OBJETIVO_ID','OBJETIVO_DESC','UMEDIDA_ID',
                        'MESP_01','MESP_02','MESP_03','MESP_04','MESP_05','MESP_06',
                        'MESP_07','MESP_08','MESP_09','MESP_10','MESP_11','MESP_12',
                        'MESC_01','MESC_02','MESC_03','MESC_04','MESC_05','MESC_06',
                        'MESC_07','MESC_08','MESC_09','MESC_10','MESC_11','MESC_12',
                        'DOBS_1','DOBS_2','DSTATUS_1','DSTATUS_2',
                        'FECREG','IP','LOGIN','FECHA_M','IP_M','LOGIN_M')        
                        ->where(  'FOLIO'  ,$id)            
                        ->orderBy('FOLIO'  ,'asc')
                        ->orderBy('PARTIDA','asc')
                        ->paginate(30);           
        if($regprogdtrab->count() <= 0){
            toastr()->error('No existen actividades del programa de trabajo.','Lo siento!',['positionClass' => 'toast-bottom-right']);
        }                        
        return view('sicinar.programatrabajo.verProgdtrab',compact('nombre','usuario','regosc','regumedida','reganios','regperiodos','regmeses','regdias','regprogtrab','regprogdtrab'));
    }


    public function actionNuevoProgdtrab($id){
        $nombre       = session()->get('userlog');
        $pass         = session()->get('passlog');
        if($nombre == NULL AND $pass == NULL){
            return view('sicinar.login.expirada');
        }
        $usuario      = session()->get('usuario');
        $rango        = session()->get('rango');
        $ip           = session()->get('ip');
        $arbol_id     = session()->get('arbol_id');

        $regumedida   = regUmedidaModel::select('UMEDIDA_ID','UMEDIDA_DESC')->orderBy('UMEDIDA_ID','asc')
                        ->get();  
        //$regperiodos  = regPfiscalesModel::select('PERIODO_ID', 'PERIODO_DESC')->get();         
        //$reganios     = regPeriodosaniosModel::select('PERIODO_ID','PERIODO_DESC')
        //                ->wherein('PERIODO_ID',[2020,2021,2022,2023])        
        //                ->get();        
        //$regmeses     = regMesesModel::select('MES_ID','MES_DESC')->get();      
        //$regdias      = regDiasModel::select('DIA_ID','DIA_DESC')->get();     
        if(session()->get('rango') !== '0'){                           
            $regosc   = regOscModel::select('OSC_ID', 'OSC_DESC','OSC_STATUS')->orderBy('OSC_DESC','ASC')
                        ->get();                                                        
        }else{
            $regosc   = regOscModel::select('OSC_ID', 'OSC_DESC','OSC_STATUS')->orderBy('OSC_DESC','ASC')
                        ->where('OSC_ID',$arbol_id)
                        ->get();            
        }    
        $regprogtrab  = regProgtrabModel::select('FOLIO','PERIODO_ID','OSC_ID','FECHA_ELAB','FECHA_ELAB2',
                        'PERIODO_ID1','MES_ID1','DIA_ID1','PROGRAMA_ID','PROGRAMA_DESC',
                        'RESPONSABLE','ELABORO','AUTORIZO','OBS_1','OBS_2','STATUS_1','STATUS_2',
                        'FECREG','IP','LOGIN','FECHA_M','IP_M','LOGIN_M')
                        ->where(  'FOLIO'     ,$id  )            
                        ->orderBy('PERIODO_ID','asc') 
                        ->orderBy('OSC_ID'    ,'asc') 
                        ->orderBy('FOLIO'     ,'asc')                        
                        ->get();
        $regprogdtrab = regProgdtrabModel::select('FOLIO','PARTIDA','PERIODO_ID','OSC_ID','DFECHA_ELAB',
                        'DFECHA_ELAB2','PROGRAMA_ID','PROGRAMA_DESC','ACTIVIDAD_ID',
                        'ACTIVIDAD_DESC','OBJETIVO_ID','OBJETIVO_DESC','UMEDIDA_ID',
                        'MESP_01','MESP_02','MESP_03','MESP_04','MESP_05','MESP_06',
                        'MESP_07','MESP_08','MESP_09','MESP_10','MESP_11','MESP_12',
                        'MESC_01','MESC_02','MESC_03','MESC_04','MESC_05','MESC_06',
                        'MESC_07','MESC_08','MESC_09','MESC_10','MESC_11','MESC_12',
                        'DOBS_1','DOBS_2','DSTATUS_1','DSTATUS_2',
                        'FECREG','IP','LOGIN','FECHA_M','IP_M','LOGIN_M')        
                        ->where(  'FOLIO'     ,$id  )    
                        ->orderBy('PERIODO_ID','asc') 
                        ->orderBy('OSC_ID'    ,'asc') 
                        ->orderBy('FOLIO'     ,'asc')
                        ->orderBy('PARTIDA'   ,'asc')
                        ->get();                                
        //dd($unidades);
        return view('sicinar.programatrabajo.nuevoProgdtrab',compact('nombre','usuario','regosc','regumedida','regprogtrab','regprogdtrab'));   
    }

    public function actionAltaNuevoProgdtrab(Request $request){
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

        // *************** Validar duplicidad ***********************************/
        //$duplicado = regProgdtrabModel::where(['PERIODO_ID' => $request->periodo_id,
        //                                       'OSC_ID'     => $request->osc_id, 
        //                                       'FOLIO'      => $request->folio])
        //             ->get();
        //if($duplicado->count() <= 0 )
        //    return back()->withInput()->withErrors(['OSC_ID' => 'IAP '.$request->osc_id.' Ya existe programa de trabajo en el mismo periodo y con la IAP referida. Por favor verificar.']);
        //else{  

            /************ ALTA  *****************************/ 
            $mes1 = regMesesModel::ObtMes($request->mes_id1);
            $dia1 = regDiasModel::ObtDia($request->dia_id1);                
            // ******** Obtiene partida ************************/
            $partida = regProgdtrabModel::where(['PERIODO_ID'=> $request->periodo_id, 
                                                 'OSC_ID'    => $request->osc_id, 
                                                 'FOLIO'     => $request->folio])
                       ->max('PARTIDA');
                       //->orderBy('PERIODO_ID','asc') 
                       //->orderBy('OSC_ID'    ,'asc') 
                       //->orderBy('FOLIO'     ,'asc')
                       //->get(); 
            $partida = $partida + 1;
            //dd($partida,' periodo:'.$request->periodo_id,' IAP:'.$request->osc_id,' folio'.$request->folio);

            $nuevoprogdtrab = new regProgdtrabModel();
            $nuevoprogdtrab->FOLIO         = $request->folio;
            $nuevoprogdtrab->PARTIDA       = $partida;
            $nuevoprogdtrab->PERIODO_ID    = $request->periodo_id;                            
            $nuevoprogdtrab->OSC_ID        = $request->osc_id;
            $nuevoprogdtrab->DFECHA_ELAB   = date('Y/m/d', strtotime(trim($dia1[0]->dia_desc.'/'.$mes1[0]->mes_mes.'/'.$request->periodo_id1) ));
            $nuevoprogdtrab->DFECHA_ELAB2  = trim($dia1[0]->dia_desc.'/'.$mes1[0]->mes_mes.'/'.$request->periodo_id1);

            $nuevoprogdtrab->PROGRAMA_DESC = substr(trim(strtoupper($request->programa_desc)),0,499);
            $nuevoprogdtrab->ACTIVIDAD_DESC= substr(trim(strtoupper($request->actividad_desc)),0,499);
            $nuevoprogdtrab->OBJETIVO_DESC = substr(trim(strtoupper($request->objetivo_desc)),0,499);
            $nuevoprogdtrab->UMEDIDA_ID    = $request->umedida_id;
            $nuevoprogdtrab->MESP_01       = $request->mesp_01;
            $nuevoprogdtrab->MESP_02       = $request->mesp_02;
            $nuevoprogdtrab->MESP_03       = $request->mesp_03;
            $nuevoprogdtrab->MESP_04       = $request->mesp_04;
            $nuevoprogdtrab->MESP_05       = $request->mesp_05;
            $nuevoprogdtrab->MESP_06       = $request->mesp_06;
            $nuevoprogdtrab->MESP_07       = $request->mesp_07;
            $nuevoprogdtrab->MESP_08       = $request->mesp_08;
            $nuevoprogdtrab->MESP_09       = $request->mesp_09;
            $nuevoprogdtrab->MESP_10       = $request->mesp_10;
            $nuevoprogdtrab->MESP_11       = $request->mesp_11;
            $nuevoprogdtrab->MESP_12       = $request->mesp_12;
            $nuevoprogdtrab->DOBS_1        = substr(trim(strtoupper($request->dobs_1)),0,299);
      
            $nuevoprogdtrab->IP            = $ip;
            $nuevoprogdtrab->LOGIN         = $nombre;         // Usuario ;
            $nuevoprogdtrab->save();
            if($nuevoprogdtrab->save() == true){
                toastr()->success('Actividad del programa de trabajo dada de alta.','ok!',['positionClass' => 'toast-bottom-right']);
                /************ Bitacora inicia *************************************/ 
                setlocale(LC_TIME, "spanish");        
                $xip          = session()->get('ip');
                $xperiodo_id  = (int)date('Y');
                $xprograma_id = 1;
                $xmes_id      = (int)date('m');
                $xproceso_id  =         3;
                $xfuncion_id  =      3011;
                $xtrx_id      =        42;    //Alta
                $regbitacora = regBitacoraModel::select('PERIODO_ID','MES_ID','PROCESO_ID','FUNCION_ID', 
                                                    'TRX_ID','FOLIO','NO_VECES','FECHA_REG','IP','LOGIN', 
                                                    'FECHA_M','IP_M','LOGIN_M')
                               ->where(['PERIODO_ID' => $xperiodo_id,'MES_ID' => $xmes_id,
                                    'PROCESO_ID' => $xproceso_id,'FUNCION_ID' => $xfuncion_id,'TRX_ID' => $xtrx_id, 
                                    'FOLIO' => $request->folio])
                               ->get();
                if($regbitacora->count() <= 0){              // Alta
                    $nuevoregBitacora = new regBitacoraModel();              
                    $nuevoregBitacora->PERIODO_ID = $xperiodo_id;    // Año de transaccion 
                    $nuevoregBitacora->PROGRAMA_ID= $xprograma_id;   // Proyecto JAPEM 
                    $nuevoregBitacora->MES_ID     = $xmes_id;        // Mes de transaccion
                    $nuevoregBitacora->PROCESO_ID = $xproceso_id;    // Proceso de apoyo
                    $nuevoregBitacora->FUNCION_ID = $xfuncion_id;    // Funcion del modelado de procesos 
                    $nuevoregBitacora->TRX_ID     = $xtrx_id;        // Actividad del modelado de procesos
                    $nuevoregBitacora->FOLIO      = $request->folio;          // Folio    
                    $nuevoregBitacora->NO_VECES   = 1;               // Numero de veces            
                    $nuevoregBitacora->IP         = $ip;             // IP
                    $nuevoregBitacora->LOGIN      = $nombre;         // Usuario 
                    $nuevoregBitacora->save();
                    if($nuevoregBitacora->save() == true)
                        toastr()->success('Trx de act. Prog. trab. registrada en Bitacora.','¡Ok!',['positionClass' => 'toast-bottom-right']);
                    else
                        toastr()->error('Error de Trx de act. Prog. trab. al dar de alta la bitacora. Por favor volver a interlo.','Ups!',['positionClass' => 'toast-bottom-right']);
                }else{                   
                    //*********** Obtine el no. de veces *****************************
                    $xno_veces = regBitacoraModel::where(['PERIODO_ID' => $xperiodo_id,  
                                                      'MES_ID' => $xmes_id, 'PROCESO_ID' => $xproceso_id, 
                                                      'FUNCION_ID' => $xfuncion_id, 'TRX_ID' => $xtrx_id, 
                                                      'FOLIO' => $request->folio])
                                 ->max('NO_VECES');
                    $xno_veces = $xno_veces+1;                        
                    //*********** Termina de obtener el no de veces *****************************         
                    $regbitacora = regBitacoraModel::select('NO_VECES','IP_M','LOGIN_M','FECHA_M')
                                   ->where(['PERIODO_ID' => $xperiodo_id,  
                                        'MES_ID' => $xmes_id, 'PROCESO_ID' => $xproceso_id, 
                                        'FUNCION_ID' => $xfuncion_id,'TRX_ID' => $xtrx_id,'FOLIO' => $request->folio])
                                   ->update([
                                         'NO_VECES'=> $regbitacora->NO_VECES = $xno_veces,
                                         'IP_M'    => $regbitacora->IP       = $ip,
                                         'LOGIN_M' => $regbitacora->LOGIN_M  = $nombre,
                                         'FECHA_M' => $regbitacora->FECHA_M  = date('Y/m/d')  //date('d/m/Y')
                                       ]);
                    toastr()->success('Bitacora actualizada.','¡Ok!',['positionClass' => 'toast-bottom-right']);
                }   /************ Bitacora termina *************************************/ 

            }else{
                toastr()->error('Error en Trx de act. Prog. trab. Por favor volver a interlo.','Ups!',['positionClass' => 'toast-bottom-right']);
            }   //**************** Termina la alta ***************/
        //}   // ******************* Termina el duplicado **********/
        return redirect()->route('verProgdtrab',$request->folio);
    }

    public function actionEditarProgdtrab($id, $id2){
        $nombre        = session()->get('userlog');
        $pass          = session()->get('passlog');
        if($nombre == NULL AND $pass == NULL){
            return view('sicinar.login.expirada');
        }
        $usuario       = session()->get('usuario');
        $rango         = session()->get('rango');
        $arbol_id      = session()->get('arbol_id');        

        $regumedida   = regUmedidaModel::select('UMEDIDA_ID','UMEDIDA_DESC')->orderBy('UMEDIDA_ID','asc')
                        ->get();   
        $reganios     = regPeriodosaniosModel::select('PERIODO_ID','PERIODO_DESC')
                        ->wherein('PERIODO_ID',[2020,2021,2022,2023])        
                        ->get();
        $regperiodos  = regPfiscalesModel::select('PERIODO_ID', 'PERIODO_DESC')->get();         
        $regmeses     = regMesesModel::select('MES_ID','MES_DESC')->get();      
        $regdias      = regDiasModel::select('DIA_ID','DIA_DESC')->get();         
        //********* Validar rol de usuario **********************/
        if(session()->get('rango') !== '0'){                          
            $regosc       = regOscModel::select('OSC_ID', 'OSC_DESC','OSC_STATUS')
                            ->get();                                                        
        }else{
            $regosc   = regOscModel::select('OSC_ID', 'OSC_DESC','OSC_STATUS')
                        ->where('OSC_ID',$arbol_id)
                        ->get();            
        }                    
        $regprogtrab  = regProgtrabModel::select('FOLIO','PERIODO_ID','OSC_ID','FECHA_ELAB','FECHA_ELAB2',
                        'PERIODO_ID1','MES_ID1','DIA_ID1','PROGRAMA_ID','PROGRAMA_DESC',
                        'RESPONSABLE','ELABORO','AUTORIZO','OBS_1','OBS_2','STATUS_1','STATUS_2',
                        'FECREG','IP','LOGIN','FECHA_M','IP_M','LOGIN_M')
                        ->where('FOLIO',$id)
                        ->orderBy('PERIODO_ID','ASC')
                        ->orderBy('OSC_ID','ASC')
                        ->get();
        $regprogdtrab = regProgdtrabModel::select('FOLIO','PARTIDA','PERIODO_ID','OSC_ID','DFECHA_ELAB',
                        'DFECHA_ELAB2','PROGRAMA_ID','PROGRAMA_DESC','ACTIVIDAD_ID',
                        'ACTIVIDAD_DESC','OBJETIVO_ID','OBJETIVO_DESC','UMEDIDA_ID',
                        'MESP_01','MESP_02','MESP_03','MESP_04','MESP_05','MESP_06',
                        'MESP_07','MESP_08','MESP_09','MESP_10','MESP_11','MESP_12',
                        'MESC_01','MESC_02','MESC_03','MESC_04','MESC_05','MESC_06',
                        'MESC_07','MESC_08','MESC_09','MESC_10','MESC_11','MESC_12',
                        'DOBS_1','DOBS_2','DSTATUS_1','DSTATUS_2',
                        'FECREG','IP','LOGIN','FECHA_M','IP_M','LOGIN_M')        
                        ->where(['FOLIO' => $id, 'PARTIDA' => $id2])
                        //->where('FOLIO',$id)
                        //->where('PARTIDA',$id2)
                        ->first();
        if($regprogdtrab->count() <= 0){
            toastr()->error('No existen registros de actividades del programa de trabajo.','Lo siento!',['positionClass' => 'toast-bottom-right']);
        }
        return view('sicinar.programatrabajo.editarProgdtrab',compact('nombre','usuario','regosc','reganios','regperiodos','regmeses','regdias','regprogtrab','regprogdtrab','regumedida'));
    }

    public function actionActualizarProgdtrab(progdtrabRequest $request, $id, $id2){
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
        $regprogdtrab = regProgdtrabModel::where(['FOLIO' => $id, 'PARTIDA' => $id2]);
        if($regprogdtrab->count() <= 0)
            toastr()->error('No existe actividad del programa de trabajo.','¡Por favor volver a intentar!',['positionClass' => 'toast-bottom-right']);
        else{        
            //********************** Actualizar ********************************/
            //xiap_feccons =null;
            if(!empty($request->periodo_d1) and !empty($request->mes_d1) and !empty($request->dia_d1) ){
                //toastr()->error('muy bien 1....................','¡ok...........!',['positionClass' => 'toast-bottom-right']);
                $mes1 = regMesesModel::ObtMes($request->mes_id1);
                $dia1 = regDiasModel::ObtDia($request->dia_id1);                
                //xiap_feccons = $dia1[0]->dia_desc.'/'.$mes1[0]->mes_mes.'/'.$request->periodo_id1;
            }   //endif
            $mes1 = regMesesModel::ObtMes($request->mes_id1);
            $dia1 = regDiasModel::ObtDia($request->dia_id1);                
            //dd('año 1:',$request->periodo_id1, ' año 2:',$request->periodo_id2,' mes1:',$mes1[0]->mes_mes,' dia1:',$dia1[0]->dia_desc,' mes2:',$mes2[0]->mes_mes, ' dia2:',$dia2[0]->dia_desc);
            $regprogdtrab = regProgdtrabModel::where(['FOLIO' => $id, 'PARTIDA' => $id2])        
                           ->update([                
                'PROGRAMA_DESC' => substr(trim(strtoupper($request->programa_desc)) ,0,499),
                'ACTIVIDAD_DESC'=> substr(trim(strtoupper($request->actividad_desc)),0,499),
                'OBJETIVO_DESC' => substr(trim(strtoupper($request->objetivo_desc)) ,0,499),
                'UMEDIDA_ID'    => $request->umedida_id,

                'DFECHA_ELAB'    => date('Y/m/d', strtotime(trim($dia1[0]->dia_desc.'/'.$mes1[0]->mes_mes.'/'.$request->periodo_id1) )),
                'DFECHA_ELAB2'   => trim($dia1[0]->dia_desc.'/'.$mes1[0]->mes_mes.'/'.$request->periodo_id1), 
                'MESP_01'       => $request->mesp_01,
                'MESP_02'       => $request->mesp_02,
                'MESP_03'       => $request->mesp_03,
                'MESP_04'       => $request->mesp_04,
                'MESP_05'       => $request->mesp_05,
                'MESP_06'       => $request->mesp_06,
                'MESP_07'       => $request->mesp_07,
                'MESP_08'       => $request->mesp_08,
                'MESP_09'       => $request->mesp_09,
                'MESP_10'       => $request->mesp_10,
                'MESP_11'       => $request->mesp_11,
                'MESP_12'       => $request->mesp_12,

                'DOBS_1'        => substr(trim(strtoupper($request->dobs_1)),0,299),        
                'DSTATUS_1'     => $request->dstatus_1,

                'IP_M'          => $ip,
                'LOGIN_M'       => $nombre,
                'FECHA_M'       => date('Y/m/d')    //date('d/m/Y')                                
                                   ]);
            toastr()->success('Actividad del programa de trabajo actualizada.','¡Ok!',['positionClass' => 'toast-bottom-right']);

            /************ Bitacora inicia *************************************/ 
            setlocale(LC_TIME, "spanish");        
            $xip          = session()->get('ip');
            $xperiodo_id  = (int)date('Y');
            $xprograma_id = 1;
            $xmes_id      = (int)date('m');
            $xproceso_id  =         3;
            $xfuncion_id  =      3011;
            $xtrx_id      =        43;    //Actualizar 
            $regbitacora = regBitacoraModel::select('PERIODO_ID',  'MES_ID', 'PROCESO_ID', 'FUNCION_ID', 
                           'TRX_ID', 'FOLIO', 'NO_VECES', 'FECHA_REG', 'IP', 'LOGIN', 'FECHA_M', 'IP_M', 'LOGIN_M')
                           ->where(['PERIODO_ID' => $xperiodo_id,  'MES_ID' => $xmes_id, 
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
                    toastr()->success('Trx de actualización de act. Prog. trab. registrada en Bitacora.','¡Ok!',['positionClass' => 'toast-bottom-right']);
                else
                    toastr()->error('Error de actualización de Trx de act. Prog. trab. al dar de alta la bitacora. Por favor volver a interlo.','Ups!',['positionClass' => 'toast-bottom-right']);
            }else{                   
                //*********** Obtine el no. de veces *****************************
                $xno_veces = regBitacoraModel::where(['PERIODO_ID' => $xperiodo_id,  
                             'MES_ID' => $xmes_id, 'PROCESO_ID' => $xproceso_id, 'FUNCION_ID' => $xfuncion_id, 
                             'TRX_ID' => $xtrx_id, 'FOLIO' => $id])
                             ->max('NO_VECES');
                $xno_veces = $xno_veces+1;                        
                //*********** Termina de obtener el no de veces *****************************         
                $regbitacora = regBitacoraModel::select('NO_VECES','IP_M','LOGIN_M','FECHA_M')
                               ->where(['PERIODO_ID' => $xperiodo_id,  
                                        'MES_ID' => $xmes_id, 'PROCESO_ID' => $xproceso_id, 'FUNCION_ID' => $xfuncion_id, 
                                        'TRX_ID' => $xtrx_id, 'FOLIO' => $id])
                               ->update([
                                         'NO_VECES'=> $regbitacora->NO_VECES = $xno_veces,
                                         'IP_M'    => $regbitacora->IP       = $ip,
                                         'LOGIN_M' => $regbitacora->LOGIN_M  = $nombre,
                                         'FECHA_M' => $regbitacora->FECHA_M  = date('Y/m/d')  //date('d/m/Y')
                                         ]);
                toastr()->success('Trx de actualización de act. Prog. trab. en Bitacora.','¡Ok!',['positionClass' => 'toast-bottom-right']);
            }   /************ Bitacora termina *************************************/                     
        }       /************ Actualizar *******************************************/
        return redirect()->route('verProgdtrab',$id);
    }


    public function actionBorrarProgdtrab($id,$id2){
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

        /************ Eliminar **************************************/
        $regprogdtrab  = regProgdtrabModel::where(['FOLIO' => $id, 'PARTIDA' => $id2]);
        //              ->find('UMEDIDA_ID',$id);
        if($regprogdtrab->count() <= 0)
            toastr()->error('No existe actividad del programa de trabajo.','¡Por favor volver a intentar!',['positionClass' => 'toast-bottom-right']);
        else{        
            $regprogdtrab->delete();
            toastr()->success('Actividad del programa de trabajo eliminada.','¡Ok!',['positionClass' => 'toast-bottom-right']);

            //echo 'Ya entre a borrar registro..........';
            /************ Bitacora inicia *************************************/ 
            setlocale(LC_TIME, "spanish");        
            $xip          = session()->get('ip');
            $xperiodo_id  = (int)date('Y');
            $xprograma_id = 1;
            $xmes_id      = (int)date('m');
            $xproceso_id  =         3;
            $xfuncion_id  =      3011;
            $xtrx_id      =        44;     // Baja 
            $regbitacora = regBitacoraModel::select('PERIODO_ID','MES_ID', 'PROCESO_ID','FUNCION_ID', 
                           'TRX_ID', 'FOLIO', 'NO_VECES', 'FECHA_REG', 'IP', 'LOGIN', 'FECHA_M', 'IP_M', 'LOGIN_M')
                           ->where(['PERIODO_ID' => $xperiodo_id,  'MES_ID' => $xmes_id, 
                                    'PROCESO_ID' => $xproceso_id, 'FUNCION_ID' => $xfuncion_id, 
                                    'TRX_ID' => $xtrx_id, 'FOLIO' => $id])
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
                    toastr()->success('Trx de eliminar act. Prog. trab. registrada en Bitacora.','¡Ok!',['positionClass' => 'toast-bottom-right']);
                else
                    toastr()->error('Error de eliminar Trx de act. Prog. trab. al dar de alta la bitacora. Por favor volver a interlo.','Ups!',['positionClass' => 'toast-bottom-right']);
            }else{                   
                //*********** Obtine el no. de veces *****************************
                $xno_veces = regBitacoraModel::where(['PERIODO_ID' => $xperiodo_id,  
                                                      'MES_ID' => $xmes_id, 'PROCESO_ID' => $xproceso_id, 
                                                      'FUNCION_ID' => $xfuncion_id,'TRX_ID' => $xtrx_id,'FOLIO' => $id])
                             ->max('NO_VECES');
                $xno_veces = $xno_veces+1;                        
                //*********** Termina de obtener el no de veces *****************************         
                $regbitacora = regBitacoraModel::select('NO_VECES','IP_M','LOGIN_M','FECHA_M')
                               ->where(['PERIODO_ID' => $xperiodo_id,  
                                        'MES_ID' => $xmes_id, 'PROCESO_ID' => $xproceso_id, 'FUNCION_ID' => $xfuncion_id, 
                                        'TRX_ID' => $xtrx_id, 'FOLIO' => $id])
                               ->update([
                                         'NO_VECES' => $regbitacora->NO_VECES = $xno_veces,
                                         'IP_M'     => $regbitacora->IP       = $ip,
                                         'LOGIN_M'  => $regbitacora->LOGIN_M  = $nombre,
                                         'FECHA_M'  => $regbitacora->FECHA_M  = date('Y/m/d')  //date('d/m/Y')
                                        ]);
                toastr()->success('Trx de eliminar act. Prog. trab. actualizada en Bitacora.','¡Ok!',['positionClass' => 'toast-bottom-right']);
            }   /************ Bitacora termina *************************************/                 
        }       /************* Termina de eliminar *********************************/
        return redirect()->route('verProgdtrab',$id);
    }    

}
