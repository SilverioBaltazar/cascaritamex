<?php
//**************************************************************/
//* File:       rAsistenciaController.php
//* Proyecto:   Sistema SIINAP.V2 JAPEM
//¨Función:     Clases para el modulo de requísitos asistenciales
//* Autor:      Ing. Silverio Baltazar Barrientos Zarate
//* Modifico:   Ing. Silverio Baltazar Barrientos Zarate
//* Fecha act.: diciembre 2019
//* @Derechos reservados. Gobierno del Estado de México
//*************************************************************/
namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests\reqasistenciaRequest;
use App\regIapModel;
use App\regBitacoraModel;
use App\regPfiscalesModel;
use App\regReqAsistenciaModel;
use App\regPerModel;
use App\regNumerosModel;
use App\regFormatosModel;

// Exportar a excel 
use Maatwebsite\Excel\Facades\Excel;

// Exportar a pdf
use PDF;
//use Options;

class rAsistenciaController extends Controller
{

    //******** Mostrar requísitos asistenciales *****//
    public function actionverReqa(){
        $nombre       = session()->get('userlog');
        $pass         = session()->get('passlog');
        if($nombre == NULL AND $pass == NULL){
            return view('sicinar.login.expirada');
        }
        $usuario      = session()->get('usuario');
        $rango        = session()->get('rango');
        $ip           = session()->get('ip');
        $arbol_id     = session()->get('arbol_id');        

        $regperiodicidad= regPerModel::select('PER_ID','PER_DESC')->orderBy('PER_ID','asc')
                        ->get();                          
        $regnumeros   = regNumerosModel::select('NUM_ID','NUM_DESC')->get();
        $regformatos  = regFormatosModel::select('FORMATO_ID','FORMATO_DESC')->get();
        $regperiodos  = regPfiscalesModel::select('PERIODO_ID','PERIODO_DESC')->get();        
        $regiap       = regIapModel::select('IAP_ID', 'IAP_DESC','IAP_STATUS')->get();
        //********* Validar rol de usuario **********************/
        if(session()->get('rango') !== '0'){          
            $regasistencia= regReqAsistenciaModel::select('IAP_ID','PERIODO_ID',
                            'DOC_ID1' ,'FORMATO_ID1' ,'IAP_D1' ,'PER_ID1' ,'NUM_ID1' ,'IAP_EDO1',
                            'DOC_ID2' ,'FORMATO_ID2' ,'IAP_D2' ,'PER_ID2' ,'NUM_ID2' ,'IAP_EDO2',
                            'DOC_ID3' ,'FORMATO_ID3' ,'IAP_D3' ,'PER_ID3' ,'NUM_ID3' ,'IAP_EDO3',
                            'DOC_ID4' ,'FORMATO_ID4' ,'IAP_D4' ,'PER_ID4' ,'NUM_ID4' ,'IAP_EDO4',                        
                            'DOC_ID5' ,'FORMATO_ID5' ,'IAP_D5' ,'PER_ID5' ,'NUM_ID5' ,'IAP_EDO5',  
                            'PREG_007','PREG_008','PREG_009','PREG_010','PREG_011','PREG_012',
                            'PREG_013','PREG_014','PREG_015','PREG_016','PREG_017','PREG_018',
                            'PREG_019','PREG_020','PREG_021','PREG_022','PREG_023','PREG_024',
                            'IAP_STATUS','FECREG','IP','LOGIN','FECHA_M','IP_M','LOGIN_M')
                            ->orderBy('IAP_ID','ASC')
                            ->paginate(30);
        }else{
            $regasistencia= regReqAsistenciaModel::select('IAP_ID','PERIODO_ID',
                            'DOC_ID1' ,'FORMATO_ID1' ,'IAP_D1' ,'PER_ID1' ,'NUM_ID1' ,'IAP_EDO1',
                            'DOC_ID2' ,'FORMATO_ID2' ,'IAP_D2' ,'PER_ID2' ,'NUM_ID2' ,'IAP_EDO2',
                            'DOC_ID3' ,'FORMATO_ID3' ,'IAP_D3' ,'PER_ID3' ,'NUM_ID3' ,'IAP_EDO3',
                            'DOC_ID4' ,'FORMATO_ID4' ,'IAP_D4' ,'PER_ID4' ,'NUM_ID4' ,'IAP_EDO4',                        
                            'DOC_ID5' ,'FORMATO_ID5' ,'IAP_D5' ,'PER_ID5' ,'NUM_ID5' ,'IAP_EDO5',  
                            'PREG_007','PREG_008','PREG_009','PREG_010','PREG_011','PREG_012',
                            'PREG_013','PREG_014','PREG_015','PREG_016','PREG_017','PREG_018',
                            'PREG_019','PREG_020','PREG_021','PREG_022','PREG_023','PREG_024',
                            'IAP_STATUS','FECREG','IP','LOGIN','FECHA_M','IP_M','LOGIN_M')
                            ->where('IAP_ID',$arbol_id)
                            ->paginate(30);            
        }
        if($regasistencia->count() <= 0){
            toastr()->error('No existen requísitos asistenciales.','Lo siento!',['positionClass' => 'toast-bottom-right']);
            //return redirect()->route('nuevoasistencial');
        }
        return view('sicinar.requisitos.verReqa',compact('nombre','usuario','regiap','regperiodicidad','regnumeros', 'regperiodos','regasistencia','regformatos'));
    }


    public function actionNuevoReqa(){
        $nombre       = session()->get('userlog');
        $pass         = session()->get('passlog');
        if($nombre == NULL AND $pass == NULL){
            return view('sicinar.login.expirada');
        }
        $usuario      = session()->get('usuario');
        $rango        = session()->get('rango');
        $ip           = session()->get('ip');
        $arbol_id     = session()->get('arbol_id');        

        $regnumeros   = regNumerosModel::select('NUM_ID', 'NUM_DESC')->orderBy('NUM_ID','asc')
                        ->get();
        $regperiodos  = regPfiscalesModel::select('PERIODO_ID', 'PERIODO_DESC')->orderBy('PERIODO_ID','asc')
                        ->get();        
        $regperiodicidad= regPerModel::select('PER_ID', 'PER_DESC')->orderBy('PER_ID','asc')
                        ->get();   
        $regformatos  = regFormatosModel::select('FORMATO_ID','FORMATO_DESC')
                        ->get();
        if(session()->get('rango') !== '0'){                                
            $regiap   = regIapModel::select('IAP_ID', 'IAP_DESC','IAP_STATUS')->orderBy('IAP_DESC','asc')
                        ->get();
        }else{
            $regiap   = regIapModel::select('IAP_ID', 'IAP_DESC','IAP_STATUS')
                        ->where('IAP_ID',$arbol_id)
                        ->get();            
        }                                                
        $regasistencia  = regReqAsistenciaModel::select('IAP_ID','PERIODO_ID',
                        'DOC_ID1' ,'FORMATO_ID1' ,'IAP_D1' ,'PER_ID1' ,'NUM_ID1' ,'IAP_EDO1',
                        'DOC_ID2' ,'FORMATO_ID2' ,'IAP_D2' ,'PER_ID2' ,'NUM_ID2' ,'IAP_EDO2',
                        'DOC_ID3' ,'FORMATO_ID3' ,'IAP_D3' ,'PER_ID3' ,'NUM_ID3' ,'IAP_EDO3',
                        'DOC_ID4' ,'FORMATO_ID4' ,'IAP_D4' ,'PER_ID4' ,'NUM_ID4' ,'IAP_EDO4',                        
                        'DOC_ID5' ,'FORMATO_ID5' ,'IAP_D5' ,'PER_ID5' ,'NUM_ID5' ,'IAP_EDO5',                        
                        'DOC_ID11','FORMATO_ID11','IAP_D11','PER_ID11','NUM_ID11','IAP_EDO11',
                        'PREG_013','PREG_014','PREG_015','PREG_016','PREG_017','PREG_018',
                        'PREG_019','PREG_020','PREG_021','PREG_022','PREG_023','PREG_024',
                        'PREG_007','PREG_008','PREG_009','PREG_010','PREG_011','PREG_012',
                        'IAP_STATUS','FECREG','IP','LOGIN','FECHA_M','IP_M','LOGIN_M')
                        ->orderBy('IAP_ID','ASC')
                        ->get();        
        //dd($unidades);
        return view('sicinar.requisitos.nuevoReqa',compact('regper','regnumeros','regiap','regperiodos','regperiodicidad','nombre','usuario','regasistencia','regformatos'));
    }

    public function actionAltaNuevoReqa(Request $request){
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

        /************ Registro *****************************/ 
        $regasistencia  = regReqAsistenciaModel::select('IAP_ID','PERIODO_ID',
                        'DOC_ID1' ,'FORMATO_ID1' ,'IAP_D1' ,'PER_ID1' ,'NUM_ID1' ,'IAP_EDO1',
                        'DOC_ID2' ,'FORMATO_ID2' ,'IAP_D2' ,'PER_ID2' ,'NUM_ID2' ,'IAP_EDO2',
                        'DOC_ID3' ,'FORMATO_ID3' ,'IAP_D3' ,'PER_ID3' ,'NUM_ID3' ,'IAP_EDO3',
                        'DOC_ID4' ,'FORMATO_ID4' ,'IAP_D4' ,'PER_ID4' ,'NUM_ID4' ,'IAP_EDO4',                        
                        'DOC_ID5' ,'FORMATO_ID5' ,'IAP_D5' ,'PER_ID5' ,'NUM_ID5' ,'IAP_EDO5', 
                        'PREG_007','PREG_008','PREG_009','PREG_010','PREG_011','PREG_012',
                        'PREG_013','PREG_014','PREG_015','PREG_016','PREG_017','PREG_018',
                        'PREG_019','PREG_020','PREG_021','PREG_022','PREG_023','PREG_024',                          
                        'IAP_STATUS','FECREG','IP','LOGIN','FECHA_M','IP_M','LOGIN_M')
                        ->where(['PERIODO_ID' => $request->periodo_id,'IAP_ID' => $request->iap_id])
                        ->get();
        if($regasistencia->count() <= 0 && !empty($request->iap_id)){
            //********** Registrar la alta *****************************/
            //$iap_folio = regReqAsistenciaModel::max('IAP_FOLIO');
            //$iap_folio = $iap_folio+1;
            $nuevoasistencial = new regReqAsistenciaModel();

            $file1 =null;
            if(isset($request->iap_d1)){
                if(!empty($request->iap_d1)){
                    //Comprobar  si el campo act_const tiene un archivo asignado:
                    if($request->hasFile('iap_d1')){
                        $file1=$request->iap_id.'_'.$request->file('iap_d1')->getClientOriginalName();
                        //sube el archivo a la carpeta del servidor public/images/
                        $request->file('iap_d1')->move(public_path().'/images/', $file1);
                    }
                }
            }            
            $file2 =null;
            if(isset($request->iap_d2)){
                if(!empty($request->iap_d2)){
                    //Comprobar  si el campo act_const tiene un archivo asignado:
                    if($request->hasFile('iap_d2')){
                        $file2=$request->iap_id.'_'.$request->file('iap_D2')->getClientOriginalName();
                        //sube el archivo a la carpeta del servidor public/images/
                        $request->file('iap_d2')->move(public_path().'/images/', $file2);
                    }
                }
            }
            $file3 =null;
            if(isset($request->iap_d3)){
                if(!empty($request->iap_d3)){
                    //Comprobar  si el campo act_const tiene un archivo asignado:
                    if($request->hasFile('iap_d3')){
                        $file3=$request->iap_id.'_'.$request->file('iap_d3')->getClientOriginalName();
                        //sube el archivo a la carpeta del servidor public/images/
                        $request->file('iap_d3')->move(public_path().'/images/', $file3);
                    }
                }
            }
            $file4 =null;
            if(isset($request->iap_d4)){
                if(!empty($request->iap_d4)){
                    //Comprobar  si el campo act_const tiene un archivo asignado:
                    if($request->hasFile('iap_d4')){
                        $file4=$request->iap_id.'_'.$request->file('iap_D4')->getClientOriginalName();
                        //sube el archivo a la carpeta del servidor public/images/
                        $request->file('iap_d4')->move(public_path().'/images/', $file4);
                    }
                }
            }            
            $file5 =null;
            if(isset($request->iap_d5)){
                if(!empty($request->iap_d5)){
                    //Comprobar  si el campo act_const tiene un archivo asignado:
                    if($request->hasFile('iap_d5')){
                        $file5=$request->iap_id.'_'.$request->file('iap_D5')->getClientOriginalName();
                        //sube el archivo a la carpeta del servidor public/images/
                        $request->file('iap_d5')->move(public_path().'/images/', $file5);
                    }
                }
            }

            $preg_013  =str_replace(",", "", $request->preg_013);
            $preg_013  =str_replace("$", "", $preg_013);            
            $preg_014  =str_replace(",", "", $request->preg_014);
            $preg_014  =str_replace("$", "", $preg_014);

            $nuevoasistencial->PERIODO_ID   = $request->periodo_id;
            $nuevoasistencial->IAP_ID       = $request->iap_id;        

            $nuevoasistencial->DOC_ID1     = $request->doc_id1;
            $nuevoasistencial->FORMATO_ID1 = $request->formato_id1;
            $nuevoasistencial->IAP_D1      = $file1;        
            $nuevoasistencial->NUM_ID1     = $request->num_id1;
            $nuevoasistencial->PER_ID1     = $request->per_id1;        
            $nuevoasistencial->IAP_EDO1    = $request->iap_edo1;

            $nuevoasistencial->DOC_ID2     = $request->doc_id2;
            $nuevoasistencial->FORMATO_ID2 = $request->formato_id2;
            $nuevoasistencial->IAP_D2      = $file2;        
            $nuevoasistencial->NUM_ID2     = $request->num_id2;
            $nuevoasistencial->PER_ID2     = $request->per_id2;        
            $nuevoasistencial->IAP_EDO2    = $request->iap_edo2;

            $nuevoasistencial->DOC_ID3     = $request->doc_id3;
            $nuevoasistencial->FORMATO_ID3 = $request->formato_id3;
            $nuevoasistencial->IAP_D3      = $file3;        
            $nuevoasistencial->NUM_ID3     = $request->num_id3;
            $nuevoasistencial->PER_ID3     = $request->per_id3;        
            $nuevoasistencial->IAP_EDO3    = $request->iap_edo3;

            $nuevoasistencial->DOC_ID4     = $request->doc_id4;
            $nuevoasistencial->FORMATO_ID4 = $request->formato_id4;
            $nuevoasistencial->IAP_D4      = $file4;        
            $nuevoasistencial->NUM_ID4     = $request->num_id4;
            $nuevoasistencial->PER_ID4     = $request->per_id4;        
            $nuevoasistencial->IAP_EDO4    = $request->iap_edo4;        

            $nuevoasistencial->DOC_ID5     = $request->doc_id5;
            $nuevoasistencial->FORMATO_ID5 = $request->formato_id5;
            $nuevoasistencial->IAP_D5      = $file5;        
            $nuevoasistencial->NUM_ID5     = $request->num_id5;
            $nuevoasistencial->PER_ID5     = $request->per_id5;        
            $nuevoasistencial->IAP_EDO5    = $request->iap_edo5; 

            $nuevoasistencial->PREG_007    = $request->preg_007; 
            $nuevoasistencial->PREG_008    = $request->preg_008;
            $nuevoasistencial->PREG_009    = $request->preg_009; 
            $nuevoasistencial->PREG_010    = $request->preg_010;
            $nuevoasistencial->PREG_011    = $request->preg_011; 
            $nuevoasistencial->PREG_012    = $request->preg_012;

            $nuevoasistencial->PREG_013    = $preg_013; 
            $nuevoasistencial->PREG_014    = $preg_014;
            $nuevoasistencial->PREG_015    = $request->preg_015; 
            $nuevoasistencial->PREG_016    = $request->preg_016;
            $nuevoasistencial->PREG_017    = $request->preg_017; 
            $nuevoasistencial->PREG_018    = $request->preg_018;  

            $nuevoasistencial->PREG_019    = $request->preg_019;  
            $nuevoasistencial->PREG_020    = $request->preg_020;
            $nuevoasistencial->PREG_021    = $request->preg_021; 
            $nuevoasistencial->PREG_022    = $request->preg_022;
            $nuevoasistencial->PREG_023    = $request->preg_023; 
            $nuevoasistencial->PREG_024    = $request->preg_024;                        

            $nuevoasistencial->IP          = $ip;
            $nuevoasistencial->LOGIN       = $nombre;         // Usuario ;
            $nuevoasistencial->save();

            if($nuevoasistencial->save() == true){
                toastr()->success('Información asistencial registrada.','ok!',['positionClass' => 'toast-bottom-right']);

                /************ Bitacora inicia *************************************/ 
                setlocale(LC_TIME, "spanish");        
                $xip          = session()->get('ip');
                $xperiodo_id  = (int)date('Y');
                $xprograma_id = 1;
                $xmes_id      = (int)date('m');
                $xproceso_id  =         3;
                $xfuncion_id  =      3003;
                $xtrx_id      =       160;    //alta
                $regbitacora = regBitacoraModel::select('PERIODO_ID', 'PROGRAMA_ID', 'MES_ID', 'PROCESO_ID', 
                                           'FUNCION_ID','TRX_ID','FOLIO','NO_VECES','FECHA_REG','IP','LOGIN',
                                           'FECHA_M', 'IP_M', 'LOGIN_M')
                               ->where(['PERIODO_ID' => $xperiodo_id, 'PROGRAMA_ID' => $xprograma_id, 
                                        'MES_ID' => $xmes_id,'PROCESO_ID' => $xproceso_id, 'FUNCION_ID' => $xfuncion_id, 
                                        'TRX_ID' => $xtrx_id, 'FOLIO' => $request->iap_id])
                               ->get();
                if($regbitacora->count() <= 0){              // Alta
                    $nuevoregBitacora = new regBitacoraModel();              
                    $nuevoregBitacora->PERIODO_ID = $xperiodo_id;    // Año de transaccion 
                    $nuevoregBitacora->PROGRAMA_ID= $xprograma_id;   // Proyecto JAPEM 
                    $nuevoregBitacora->MES_ID     = $xmes_id;        // Mes de transaccion
                    $nuevoregBitacora->PROCESO_ID = $xproceso_id;    // Proceso de apoyo
                    $nuevoregBitacora->FUNCION_ID = $xfuncion_id;    // Funcion del modelado de procesos 
                    $nuevoregBitacora->TRX_ID     = $xtrx_id;        // Actividad del modelado de procesos
                    $nuevoregBitacora->FOLIO      = $request->iap_id;      // Folio    
                    $nuevoregBitacora->NO_VECES   = 1;               // Numero de veces            
                    $nuevoregBitacora->IP         = $ip;             // IP
                    $nuevoregBitacora->LOGIN      = $nombre;         // Usuario 
                    $nuevoregBitacora->save();
                    if($nuevoregBitacora->save() == true)
                       toastr()->success('Bitacora dada de alta correctamente.','¡Ok!',['positionClass' => 'toast-bottom-right']);
                    else
                       toastr()->error('Error inesperado al dar de alta la bitacora. Por favor volver a interlo.','Ups!',['positionClass' => 'toast-bottom-right']);
                }else{                   
                    //*********** Obtine el no. de veces *****************************
                    $xno_veces = regBitacoraModel::where(['PERIODO_ID' => $xperiodo_id, 'PROGRAMA_ID' => $xprograma_id, 
                                                          'MES_ID' => $xmes_id, 'PROCESO_ID' => $xproceso_id, 
                                                          'FUNCION_ID' => $xfuncion_id, 'TRX_ID' => $xtrx_id, 
                                                          'FOLIO' => $request->iap_id])
                                 ->max('NO_VECES');
                    $xno_veces = $xno_veces+1;                        
                    //*********** Termina de obtener el no de veces *****************************               
                    $regbitacora = regBitacoraModel::select('NO_VECES','IP_M','LOGIN_M','FECHA_M')
                                   ->where(['PERIODO_ID' => $xperiodo_id, 'PROGRAMA_ID' => $xprograma_id, 
                                            'MES_ID' => $xmes_id, 'PROCESO_ID' => $xproceso_id, 
                                            'FUNCION_ID' => $xfuncion_id,'TRX_ID' => $xtrx_id,
                                            'FOLIO' => $request->iap_id])
                                   ->update([
                                             'NO_VECES' => $regbitacora->NO_VECES = $xno_veces,
                                             'IP_M'     => $regbitacora->IP       = $ip,
                                             'LOGIN_M'  => $regbitacora->LOGIN_M  = $nombre,
                                             'FECHA_M'  => $regbitacora->FECHA_M  = date('Y/m/d')  //date('d/m/Y')
                                            ]);
                    toastr()->success('Bitacora actualizada.','¡Ok!',['positionClass' => 'toast-bottom-right']);
                }   /************ Bitacora termina *************************************/ 
                
                //return redirect()->route('nuevoasistencial');
                //return view('sicinar.plandetrabajo.nuevoPlan',compact('unidades','nombre','usuario','rango','preguntas','apartados'));
            }else{
                toastr()->error('Error inesperado al registrar requísitos asistenciales. Por favor volver a interlo.','Ups!',['positionClass' => 'toast-bottom-right']);
                //return back();
                //return redirect()->route('nuevoasistencial');
            }   //******************** Termina la alta ************************/ 

        }else{
            toastr()->error('Ya existen requísitos asistenciales.','Por favor editar, lo siento!',['positionClass' => 'toast-bottom-right']);
            //return redirect()->route('nuevoasistencial');
        }   // Termian If de busqueda ****************

        return redirect()->route('verReqa');
    }


    /****************** Editar registro  **********/
    public function actionEditarReqa($id){
        $nombre        = session()->get('userlog');
        $pass          = session()->get('passlog');
        if($nombre == NULL AND $pass == NULL){
            return view('sicinar.login.expirada');
        }
        $usuario       = session()->get('usuario');
        $rango         = session()->get('rango');
        $arbol_id     = session()->get('arbol_id');        

        $regnumeros   = regNumerosModel::select('NUM_ID', 'NUM_DESC')->orderBy('NUM_ID','asc')
                        ->get();
        $regperiodos  = regPfiscalesModel::select('PERIODO_ID', 'PERIODO_DESC')->orderBy('PERIODO_ID','asc')
                        ->get();        
        $regperiodicidad= regPerModel::select('PER_ID', 'PER_DESC')->orderBy('PER_ID','asc')
                        ->get();                                
        $regformatos  = regFormatosModel::select('FORMATO_ID','FORMATO_DESC')->get();
        $regiap       = regIapModel::select('IAP_ID', 'IAP_DESC','IAP_STATUS')->orderBy('IAP_DESC','asc')
                        ->get();
        $regasistencia= regReqAsistenciaModel::select('IAP_ID','PERIODO_ID',
                        'DOC_ID1' ,'FORMATO_ID1' ,'IAP_D1' ,'PER_ID1' ,'NUM_ID1' ,'IAP_EDO1',
                        'DOC_ID2' ,'FORMATO_ID2' ,'IAP_D2' ,'PER_ID2' ,'NUM_ID2' ,'IAP_EDO2',
                        'DOC_ID3' ,'FORMATO_ID3' ,'IAP_D3' ,'PER_ID3' ,'NUM_ID3' ,'IAP_EDO3',
                        'DOC_ID4' ,'FORMATO_ID4' ,'IAP_D4' ,'PER_ID4' ,'NUM_ID4' ,'IAP_EDO4',                        
                        'DOC_ID5' ,'FORMATO_ID5' ,'IAP_D5' ,'PER_ID5' ,'NUM_ID5' ,'IAP_EDO5',                        
                        'PREG_007','PREG_008','PREG_009','PREG_010','PREG_011','PREG_012',
                        'PREG_013','PREG_014','PREG_015','PREG_016','PREG_017','PREG_018',
                        'PREG_019','PREG_020','PREG_021','PREG_022','PREG_023','PREG_024',                          
                        'IAP_STATUS','FECREG','IP','LOGIN','FECHA_M','IP_M','LOGIN_M')
                        ->where('IAP_ID', $id)
                        ->first();
        if($regasistencia->count() <= 0){
            toastr()->error('No existe requísitos asistenciales.','Lo siento!',['positionClass' => 'toast-bottom-right']);
            //return redirect()->route('nuevoasistencial');
        }
        return view('sicinar.requisitos.editarReqa',compact('nombre','usuario','regiap','regasistencia','regnumeros', 'regperiodos','regperiodicidad','regformatos'));

    }
    
    public function actionActualizarReqa(reqasistenciaRequest $request, $id){
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
        $regasistencia = regReqAsistenciaModel::where('IAP_ID',$id);
        if($regasistencia->count() <= 0)
            toastr()->error('No existen requísitos asistenciales.','¡Por favor volver a intentar!',['positionClass' => 'toast-bottom-right']);
        else{        
            //****************** Actualizar **************************/
            //echo "Escribió en el campo de texto 1: " .'-'. $request->iap_D4 .'-'. "<br><br>"; 
            //echo "Escribió en el campo de texto 1: " . $request->iap_D4 . "<br><br>"; 
            //Comprobar  si el campo foto1 tiene un archivo asignado:
            $name1 =null;        
            if($request->hasFile('iap_d1')){
                $name1 = $id.'_'.$request->file('iap_d1')->getClientOriginalName(); 
                //sube el archivo a la carpeta del servidor public/images/
                $request->file('iap_d1')->move(public_path().'/images/', $name1);
            }            
            $name2 =null;        
            if($request->hasFile('iap_d2')){
                $name2 = $id.'_'.$request->file('iap_d2')->getClientOriginalName(); 
                //sube el archivo a la carpeta del servidor public/images/
                $request->file('iap_d2')->move(public_path().'/images/', $name2);
            }            
            $name3 =null;        
            if($request->hasFile('iap_d3')){
                $name3 = $id.'_'.$request->file('iap_d3')->getClientOriginalName(); 
                //sube el archivo a la carpeta del servidor public/images/
                $request->file('iap_d3')->move(public_path().'/images/', $name3);
            }            
            $name4 =null;        
            if($request->hasFile('iap_d4')){
                echo "Escribió en el campo de texto 4: " .'-'. $request->iap_D4 .'-'. "<br><br>"; 
                $name4 = $id.'_'.$request->file('iap_d4')->getClientOriginalName(); 
                //sube el archivo a la carpeta del servidor public/images/
                $request->file('iap_d4')->move(public_path().'/images/', $name4);
            }
            $name5 =null;        
            if($request->hasFile('iap_d5')){
                echo "Escribió en el campo de texto 5: " .'-'. $request->iap_D5 .'-'. "<br><br>"; 
                $name5 = $id.'_'.$request->file('iap_d5')->getClientOriginalName(); 
                //sube el archivo a la carpeta del servidor public/images/
                $request->file('iap_d5')->move(public_path().'/images/', $name5);
            }            
            // ************* Actualizamos registro **********************************/
            $preg_013  =str_replace(",", "", $request->preg_013);
            $preg_013  =str_replace("$", "", $preg_013);            
            $preg_014  =str_replace(",", "", $request->preg_014);
            $preg_014  =str_replace("$", "", $preg_014);

            $regasistencia= regReqAsistenciaModel::where('IAP_ID',$id)        
                            ->update([                
                                     //'PERIODO_ID'   => $request->periodo_id,

                                     'DOC_ID1'     => $request->doc_id1,
                                     'FORMATO_ID1' => $request->formato_id1,                            
                                     //'IAP_D1'      => $name1,                                                       
                                     'PER_ID1'     => $request->per_id1,
                                     'NUM_ID1'     => $request->num_id1,                
                                     'IAP_EDO1'    => $request->iap_edo1,

                                     'DOC_ID2'     => $request->doc_id2,
                                     'FORMATO_ID2' => $request->formato_id2,                            
                                     //'IAP_D2'      => $name2,                                                       
                                     'PER_ID2'     => $request->per_id2,
                                     'NUM_ID2'     => $request->num_id2,                
                                     'IAP_EDO2'    => $request->iap_edo2,

                                     'DOC_ID3'     => $request->doc_id3,
                                     'FORMATO_ID3' => $request->formato_id3,                            
                                     //'IAP_D3'      => $name3,                                                       
                                     'PER_ID3'     => $request->per_id3,
                                     'NUM_ID3'     => $request->num_id3,                
                                     'IAP_EDO3'    => $request->iap_edo3,

                                     'DOC_ID4'     => $request->doc_id4,
                                     'FORMATO_ID4' => $request->formato_id4,                            
                                     //'IAP_D4'      => $name4,                                                       
                                     'PER_ID4'     => $request->per_id4,
                                     'NUM_ID4'     => $request->num_id4,                
                                     'IAP_EDO4'    => $request->iap_edo4,
                                    
                                     'DOC_ID5'     => $request->doc_id5,
                                     'FORMATO_ID5' => $request->formato_id5,                                          
                                     //'IAP_D5'      => $name5,              
                                     'PER_ID5'     => $request->per_id5,
                                     'NUM_ID5'     => $request->num_id5,                
                                     'IAP_EDO5'    => $request->iap_edo5,
                                     
                                     'PREG_007'    => $request->preg_007,
                                     'PREG_008'    => $request->preg_008,
                                     'PREG_009'    => $request->preg_009,
                                     'PREG_010'    => $request->preg_010,
                                     'PREG_011'    => $request->preg_011,
                                     'PREG_012'    => $request->preg_012,                                     

                                     'PREG_013'    => $preg_013,
                                     'PREG_014'    => $preg_014,
                                     'PREG_015'    => $request->preg_015,
                                     'PREG_016'    => $request->preg_016,
                                     'PREG_017'    => $request->preg_017,
                                     'PREG_018'    => $request->preg_018,

                                     'PREG_019'    => $request->preg_019,
                                     'PREG_020'    => $request->preg_020,
                                     'PREG_021'    => $request->preg_021,
                                     'PREG_022'    => $request->preg_022,
                                     'PREG_023'    => $request->preg_023,
                                     'PREG_024'    => $request->preg_024,

                                     //'IAP_STATUS'   => $request->iap_status,
                                     'IP_M'         => $ip,
                                     'LOGIN_M'      => $nombre,
                                     'FECHA_M'      => date('Y/m/d')    //date('d/m/
                                    ]);
            toastr()->success('requísitos asistenciales actualizados.','¡Ok!',['positionClass' => 'toast-bottom-right']);

            /************ Bitacora inicia *************************************/ 
            setlocale(LC_TIME, "spanish");        
            $xip          = session()->get('ip');
            $xperiodo_id  = (int)date('Y');
            $xprograma_id = 1;
            $xmes_id      = (int)date('m');
            $xproceso_id  =         3;
            $xfuncion_id  =      3003;
            $xtrx_id      =       161;    //Actualizar         
            $regbitacora = regBitacoraModel::select('PERIODO_ID','PROGRAMA_ID','MES_ID','PROCESO_ID','FUNCION_ID', 
                                                    'TRX_ID','FOLIO','NO_VECES','FECHA_REG','IP','LOGIN','FECHA_M',
                                                    'IP_M','LOGIN_M')
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
                    toastr()->success('Bitacora dada de alta correctamente.','¡Ok!',['positionClass' => 'toast-bottom-right']);
                else
                    toastr()->error('Error inesperado al dar de alta la bitacora. Por favor volver a interlo.','Ups!',['positionClass' => 'toast-bottom-right']);
            }else{                   
                //*********** Obtine el no. de veces *****************************
                $xno_veces = regBitacoraModel::where(['PERIODO_ID' => $xperiodo_id, 'PROGRAMA_ID' => $xprograma_id, 'MES_ID' => $xmes_id, 'PROCESO_ID' => $xproceso_id, 'FUNCION_ID' => $xfuncion_id, 'TRX_ID' => $xtrx_id, 'FOLIO' => $id])
                        ->max('NO_VECES');
                $xno_veces = $xno_veces+1;                        
                //*********** Termina de obtener el no de veces *****************************                    
                $regbitacora = regBitacoraModel::select('NO_VECES','IP_M','LOGIN_M','FECHA_M')
                               ->where(['PERIODO_ID' => $xperiodo_id, 'PROGRAMA_ID' => $xprograma_id, 'MES_ID' => $xmes_id,
                                        'PROCESO_ID' => $xproceso_id, 'FUNCION_ID' => $xfuncion_id, 'TRX_ID' => $xtrx_id, 
                                        'FOLIO' => $id])
                               ->update([
                                          'NO_VECES'=> $regbitacora->NO_VECES = $xno_veces,
                                          'IP_M'    => $regbitacora->IP       = $ip,
                                          'LOGIN_M' => $regbitacora->LOGIN_M  = $nombre,
                                          'FECHA_M' => $regbitacora->FECHA_M  = date('Y/m/d')  //date('d/m/Y')
                                        ]);
                toastr()->success('Bitacora actualizada.','¡Ok!',['positionClass' => 'toast-bottom-right']);
            }   /************ Bitacora termina *************************************/         
        }       /************ Termina actualizar ***********************************/

        return redirect()->route('verReqa');
        
    }

    /****************** Editar requísitos asistenciales **********/
    public function actionEditarReqa1($id){
        $nombre        = session()->get('userlog');
        $pass          = session()->get('passlog');
        if($nombre == NULL AND $pass == NULL){
            return view('sicinar.login.expirada');
        }
        $usuario       = session()->get('usuario');
        $rango         = session()->get('rango');
        $arbol_id     = session()->get('arbol_id');        

        $regnumeros   = regNumerosModel::select('NUM_ID', 'NUM_DESC')->orderBy('NUM_ID','asc')
                        ->get();
        $regperiodos  = regPfiscalesModel::select('PERIODO_ID', 'PERIODO_DESC')->orderBy('PERIODO_ID','asc')
                        ->get();        
        $regperiodicidad= regPerModel::select('PER_ID', 'PER_DESC')->orderBy('PER_ID','asc')
                        ->get(); 
        $regformatos  = regFormatosModel::select('FORMATO_ID','FORMATO_DESC')->get();                               
        $regiap       = regIapModel::select('IAP_ID', 'IAP_DESC','IAP_STATUS')->orderBy('IAP_DESC','asc')
                        ->get();
        $regasistencia= regReqAsistenciaModel::select('IAP_ID','PERIODO_ID',
                        'DOC_ID1' ,'FORMATO_ID1' ,'IAP_D1' ,'PER_ID1' ,'NUM_ID1' ,'IAP_EDO1',
                        'DOC_ID2' ,'FORMATO_ID2' ,'IAP_D2' ,'PER_ID2' ,'NUM_ID2' ,'IAP_EDO2',
                        'DOC_ID3' ,'FORMATO_ID3' ,'IAP_D3' ,'PER_ID3' ,'NUM_ID3' ,'IAP_EDO3',
                        'DOC_ID4' ,'FORMATO_ID4' ,'IAP_D4' ,'PER_ID4' ,'NUM_ID4' ,'IAP_EDO4',                        
                        'DOC_ID5','FORMATO_ID5','IAP_D5','PER_ID5','NUM_ID5','IAP_EDO5',                        
                        'DOC_ID11','FORMATO_ID11','IAP_D11','PER_ID11','NUM_ID11','IAP_EDO11',
                        'PREG_007','PREG_008','PREG_009','PREG_010','PREG_011','PREG_012',                        
                        'IAP_STATUS','FECREG','IP','LOGIN','FECHA_M','IP_M','LOGIN_M')
                        ->where('IAP_ID', $id)
                        ->first();
        if($regasistencia->count() <= 0){
            toastr()->error('No existe requísitos asistenciales.','Lo siento!',['positionClass' => 'toast-bottom-right']);
            //return redirect()->route('nuevoasistencial');
        }
        return view('sicinar.requisitos.editarReqa1',compact('nombre','usuario','regiap','regasistencia','regnumeros', 'regperiodos','regperiodicidad','regformatos'));
    }

    public function actionActualizarReqa1(reqasistenciaRequest $request, $id){
        $nombre        = session()->get('userlog');
        $pass          = session()->get('passlog');
        if($nombre == NULL AND $pass == NULL){
            return view('sicinar.login.expirada');
        }
        $usuario       = session()->get('usuario');
        $rango         = session()->get('rango');
        $ip            = session()->get('ip');
        $arbol_id     = session()->get('arbol_id');        

        // **************** actualizar ******************************
        $regasistencia = regReqAsistenciaModel::where('IAP_ID',$id);
        if($regasistencia->count() <= 0)
            toastr()->error('No existe archivo 1.','¡Por favor volver a intentar!',['positionClass' => 'toast-bottom-right']);
        else{        

            //echo "Escribió en el campo de texto 1: " .'-'. $request->iap_D4 .'-'. "<br><br>"; 
            //echo "Escribió en el campo de texto 1: " . $request->iap_D4 . "<br><br>"; 
            //Comprobar  si el campo foto1 tiene un archivo asignado:
            $name1 =null;
            if($request->hasFile('iap_d1')){
                echo "Escribió en el campo de texto d: " .'-'. $request->iap_d1 .'-'. "<br><br>"; 
                $name1 = $id.'_'.$request->file('iap_d1')->getClientOriginalName(); 
                //sube el archivo a la carpeta del servidor public/images/
                $request->file('iap_d1')->move(public_path().'/images/', $name1);

                // ************* Actualizamos registro **********************************
                $regasistencia = regReqAsistenciaModel::where('IAP_ID',$id)        
                           ->update([   
                                     'DOC_ID1'    => $request->doc_id1,
                                     'FORMATO_ID1'=> $request->formato_id1,             
                                     'IAP_D1'     => $name1,                  
                                     'PER_ID1'    => $request->per_id1,
                                     'NUM_ID1'    => $request->num_id1,                
                                     'IAP_EDO1'   => $request->iap_edo1,

                                     'PREG_007'   => $request->preg_007,
                                     'PREG_008'   => $request->preg_008,                     

                                     'IP_M'       => $ip,
                                     'LOGIN_M'    => $nombre,
                                     'FECHA_M'    => date('Y/m/d')    //date('d/m/Y')                                
                                     ]);
                toastr()->success('Requisito asistencial 1 actualizado.','¡Ok!',['positionClass' => 'toast-bottom-right']);
            }else{
                // ************* Actualizamos registro **********************************
                $regasistencia = regReqAsistenciaModel::where('IAP_ID',$id)        
                           ->update([   
                                     'DOC_ID1'    => $request->doc_id1,
                                     'FORMATO_ID1'=> $request->formato_id1,             
                                     //'IAP_D1'   => $name1,                  
                                     'PER_ID1'    => $request->per_id1,
                                     'NUM_ID1'    => $request->num_id1,                
                                     'IAP_EDO1'   => $request->iap_edo1,

                                     'PREG_007'   => $request->PREG_007,
                                     'PREG_008'   => $request->PREG_008,

                                     'IP_M'       => $ip,
                                     'LOGIN_M'    => $nombre,
                                     'FECHA_M'    => date('Y/m/d')    //date('d/m/Y')                                
                                     ]);                
                toastr()->success('Requisito asistencia 1 actualizado.','¡Ok!',['positionClass' => 'toast-bottom-right']);
            }

            /************ Bitacora inicia *************************************/ 
            setlocale(LC_TIME, "spanish");        
            $xip          = session()->get('ip');
            $xperiodo_id  = (int)date('Y');
            $xprograma_id = 1;
            $xmes_id      = (int)date('m');
            $xproceso_id  =         3;
            $xfuncion_id  =      3003;
            $xtrx_id      =       161;    //Actualizar         
            $regbitacora = regBitacoraModel::select('PERIODO_ID','PROGRAMA_ID','MES_ID','PROCESO_ID','FUNCION_ID', 
                               'TRX_ID','FOLIO','NO_VECES','FECHA_REG','IP','LOGIN','FECHA_M','IP_M','LOGIN_M')
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
                        toastr()->success('Bitacora dada de alta correctamente.','¡Ok!',['positionClass' => 'toast-bottom-right']);
                    else
                        toastr()->error('Error inesperado al dar de alta la bitacora. Por favor volver a interlo.','Ups!',['positionClass' => 'toast-bottom-right']);
            }else{                   
                    //*********** Obtine el no. de veces *****************************
                    $xno_veces = regBitacoraModel::where(['PERIODO_ID' => $xperiodo_id, 'PROGRAMA_ID' => $xprograma_id, 
                                                      'MES_ID' => $xmes_id, 'PROCESO_ID' => $xproceso_id, 
                                                      'FUNCION_ID' => $xfuncion_id, 'TRX_ID' => $xtrx_id, 'FOLIO' => $id])
                                 ->max('NO_VECES');
                    $xno_veces = $xno_veces+1;                        
                    //*********** Termina de obtener el no de veces *****************************                    
                    $regbitacora= regBitacoraModel::select('NO_VECES','IP_M','LOGIN_M','FECHA_M')
                                  ->where(['PERIODO_ID' => $xperiodo_id,'PROGRAMA_ID' => $xprograma_id,'MES_ID' => $xmes_id,
                                           'PROCESO_ID' => $xproceso_id, 'FUNCION_ID' => $xfuncion_id,'TRX_ID' => $xtrx_id, 
                                           'FOLIO' => $id])
                                  ->update([
                                            'NO_VECES' => $regbitacora->NO_VECES = $xno_veces,
                                            'IP_M' => $regbitacora->IP           = $ip,
                                            'LOGIN_M' => $regbitacora->LOGIN_M   = $nombre,
                                            'FECHA_M' => $regbitacora->FECHA_M   = date('Y/m/d')  //date('d/m/Y')
                                          ]);
                    toastr()->success('Bitacora actualizada.','¡Ok!',['positionClass' => 'toast-bottom-right']);
            }   /************ Bitacora termina *************************************/         
            
        }       /************ Termina de actualizar ********************************/

        return redirect()->route('verReqa');
        
    }    

        /****************** Editar requísitos asistenciales **********/
    public function actionEditarReqa2($id){
        $nombre        = session()->get('userlog');
        $pass          = session()->get('passlog');
        if($nombre == NULL AND $pass == NULL){
            return view('sicinar.login.expirada');
        }
        $usuario       = session()->get('usuario');
        $rango         = session()->get('rango');
        $arbol_id     = session()->get('arbol_id');        

        $regnumeros   = regNumerosModel::select('NUM_ID', 'NUM_DESC')->orderBy('NUM_ID','asc')
                        ->get();
        $regperiodos  = regPfiscalesModel::select('PERIODO_ID', 'PERIODO_DESC')->orderBy('PERIODO_ID','asc')
                        ->get();        
        $regperiodicidad= regPerModel::select('PER_ID', 'PER_DESC')->orderBy('PER_ID','asc')
                        ->get(); 
        $regformatos  = regFormatosModel::select('FORMATO_ID','FORMATO_DESC')
                        ->get();                               
        $regiap       = regIapModel::select('IAP_ID', 'IAP_DESC','IAP_STATUS')->orderBy('IAP_DESC','asc')
                        ->get();
        $regasistencia= regReqAsistenciaModel::select('IAP_ID','PERIODO_ID',
                        'DOC_ID1' ,'FORMATO_ID1' ,'IAP_D1' ,'PER_ID1' ,'NUM_ID1' ,'IAP_EDO1',
                        'DOC_ID2' ,'FORMATO_ID2' ,'IAP_D2' ,'PER_ID2' ,'NUM_ID2' ,'IAP_EDO2',
                        'DOC_ID3' ,'FORMATO_ID3' ,'IAP_D3' ,'PER_ID3' ,'NUM_ID3' ,'IAP_EDO3',
                        'DOC_ID4' ,'FORMATO_ID4' ,'IAP_D4' ,'PER_ID4' ,'NUM_ID4' ,'IAP_EDO4',                        
                        'DOC_ID5' ,'FORMATO_ID5' ,'IAP_D5' ,'PER_ID5' ,'NUM_ID5' ,'IAP_EDO5',                        
                        'PREG_007','PREG_008','PREG_009','PREG_010','PREG_011','PREG_012',  
                        'PREG_013','PREG_014','PREG_015','PREG_016','PREG_017','PREG_018',
                        'PREG_019','PREG_020','PREG_021','PREG_022','PREG_023','PREG_024',                           
                        'IAP_STATUS','FECREG','IP','LOGIN','FECHA_M','IP_M','LOGIN_M')
                        ->where('IAP_ID', $id)
                        ->first();
        if($regasistencia->count() <= 0){
            toastr()->error('No existe requísito asistencial.','Lo siento!',['positionClass' => 'toast-bottom-right']);
            //return redirect()->route('nuevoasistencial');
        }
        return view('sicinar.requisitos.editarReqa2',compact('nombre','usuario','regiap','regasistencia','regnumeros', 'regperiodos','regperiodicidad','regformatos'));
    }

    public function actionActualizarReqa2(reqasistenciaRequest $request, $id){
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
        $regasistencia = regReqAsistenciaModel::where('IAP_ID',$id);
        if($regasistencia->count() <= 0)
            toastr()->error('No existe archivo PDF2.','¡Por favor volver a intentar!',['positionClass' => 'toast-bottom-right']);
        else{        

            //echo "Escribió en el campo de texto 1: " .'-'. $request->iap_D4 .'-'. "<br><br>"; 
            //echo "Escribió en el campo de texto 1: " . $request->iap_D4 . "<br><br>"; 
            //Comprobar  si el campo foto1 tiene un archivo asignado:
            $name2 =null;
            if($request->hasFile('iap_d2')){
                echo "Escribió en el campo de texto 2: " .'-'. $request->iap_d2 .'-'. "<br><br>"; 
                $name2 = $id.'_'.$request->file('iap_d2')->getClientOriginalName(); 
                //sube el archivo a la carpeta del servidor public/images/
                $request->file('iap_d2')->move(public_path().'/images/', $name2);

                // ************* Actualizamos registro **********************************
                $regasistencia = regReqAsistenciaModel::where('IAP_ID',$id)        
                                 ->update([   
                                     'DOC_ID2'    => $request->doc_id2,
                                     'FORMATO_ID2'=> $request->formato_id2,             
                                     'IAP_D2'     => $name2,                  
                                     'PER_ID2'    => $request->per_id2,
                                     'NUM_ID2'    => $request->num_id2,                
                                     'IAP_EDO2'   => $request->iap_edo2,

                                     'PREG_009'   => $request->PREG_009,
                                     'PREG_010'   => $request->PREG_010,                                     
                                     'PREG_011'   => $request->PREG_011,
                                     'PREG_012'   => $request->PREG_012,
                                     'PREG_013'   => $request->PREG_013,                                     
                                     'PREG_014'   => $request->PREG_014,                                     

                                     'IP_M'       => $ip,
                                     'LOGIN_M'    => $nombre,
                                     'FECHA_M'    => date('Y/m/d')    //date('d/m/Y')                                
                                     ]);
                toastr()->success('Requisito asistencial 2 actualizado.','¡Ok!',['positionClass' => 'toast-bottom-right']);
            }else{
                // ************* Actualizamos registro **********************************
                $regasistencia = regReqAsistenciaModel::where('IAP_ID',$id)        
                           ->update([   
                                     'DOC_ID2'    => $request->doc_id2,
                                     'FORMATO_ID2'=> $request->formato_id2,             
                                     //'IAP_D2'   => $name2,                  
                                     'PER_ID2'    => $request->per_id2,
                                     'NUM_ID2'    => $request->num_id2,                
                                     'IAP_EDO2'   => $request->iap_edo2,

                                     'PREG_009'   => $request->PREG_009,
                                     'PREG_010'   => $request->PREG_010, 
                                     'PREG_011'   => $request->PREG_011,
                                     'PREG_012'   => $request->PREG_012,
                                     'PREG_013'   => $request->PREG_013,                                     
                                     'PREG_014'   => $request->PREG_014,

                                     'IP_M'       => $ip,
                                     'LOGIN_M'    => $nombre,
                                     'FECHA_M'    => date('Y/m/d')    //date('d/m/Y')                                
                                     ]);                
                toastr()->success('Requisito asistencial 2 actualizado.','¡Ok!',['positionClass' => 'toast-bottom-right']);
            }

            /************ Bitacora inicia *************************************/ 
            setlocale(LC_TIME, "spanish");        
            $xip          = session()->get('ip');
            $xperiodo_id  = (int)date('Y');
            $xprograma_id = 1;
            $xmes_id      = (int)date('m');
            $xproceso_id  =         3;
            $xfuncion_id  =      3003;
            $xtrx_id      =       161;    //Actualizar         
            $regbitacora = regBitacoraModel::select('PERIODO_ID','PROGRAMA_ID','MES_ID','PROCESO_ID','FUNCION_ID', 
                               'TRX_ID','FOLIO','NO_VECES','FECHA_REG','IP','LOGIN','FECHA_M','IP_M','LOGIN_M')
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
                        toastr()->success('Bitacora dada de alta correctamente.','¡Ok!',['positionClass' => 'toast-bottom-right']);
                    else
                        toastr()->error('Error inesperado al dar de alta la bitacora. Por favor volver a interlo.','Ups!',['positionClass' => 'toast-bottom-right']);
            }else{                   
                    //*********** Obtine el no. de veces *****************************
                    $xno_veces = regBitacoraModel::where(['PERIODO_ID' => $xperiodo_id, 'PROGRAMA_ID' => $xprograma_id, 
                                                      'MES_ID' => $xmes_id, 'PROCESO_ID' => $xproceso_id, 
                                                      'FUNCION_ID' => $xfuncion_id, 'TRX_ID' => $xtrx_id, 'FOLIO' => $id])
                                 ->max('NO_VECES');
                    $xno_veces = $xno_veces+1;                        
                    //*********** Termina de obtener el no de veces *****************************                    
                    $regbitacora= regBitacoraModel::select('NO_VECES','IP_M','LOGIN_M','FECHA_M')
                                  ->where(['PERIODO_ID' => $xperiodo_id,'PROGRAMA_ID' => $xprograma_id,
                                           'MES_ID' => $xmes_id,'PROCESO_ID' => $xproceso_id, 
                                           'FUNCION_ID' => $xfuncion_id,'TRX_ID' => $xtrx_id, 'FOLIO' => $id])
                                  ->update([
                                            'NO_VECES' => $regbitacora->NO_VECES = $xno_veces,
                                            'IP_M' => $regbitacora->IP           = $ip,
                                            'LOGIN_M' => $regbitacora->LOGIN_M   = $nombre,
                                            'FECHA_M' => $regbitacora->FECHA_M   = date('Y/m/d')  //date('d/m/Y')
                                          ]);
                    toastr()->success('Bitacora actualizada.','¡Ok!',['positionClass' => 'toast-bottom-right']);
            }   /************ Bitacora termina *************************************/         
            
        }       /************ Termina de actualizar ********************************/

        return redirect()->route('verReqa');
        
    }    

    /****************** Editar requísitos asistenciales **********/
    public function actionEditarReqa3($id){
        $nombre        = session()->get('userlog');
        $pass          = session()->get('passlog');
        if($nombre == NULL AND $pass == NULL){
            return view('sicinar.login.expirada');
        }
        $usuario       = session()->get('usuario');
        $rango         = session()->get('rango');
        $arbol_id      = session()->get('arbol_id');        

        $regnumeros   = regNumerosModel::select('NUM_ID', 'NUM_DESC')->orderBy('NUM_ID','asc')
                        ->get();
        $regperiodos  = regPfiscalesModel::select('PERIODO_ID', 'PERIODO_DESC')->orderBy('PERIODO_ID','asc')
                        ->get();        
        $regperiodicidad= regPerModel::select('PER_ID', 'PER_DESC')->orderBy('PER_ID','asc')
                        ->get(); 
        $regformatos  = regFormatosModel::select('FORMATO_ID','FORMATO_DESC')
                        ->get();                               
        $regiap       = regIapModel::select('IAP_ID', 'IAP_DESC','IAP_STATUS')->orderBy('IAP_DESC','asc')
                        ->get();
        $regasistencia= regReqAsistenciaModel::select('IAP_ID','PERIODO_ID',
                        'DOC_ID1' ,'FORMATO_ID1' ,'IAP_D1' ,'PER_ID1' ,'NUM_ID1' ,'IAP_EDO1',
                        'DOC_ID2' ,'FORMATO_ID2' ,'IAP_D2' ,'PER_ID2' ,'NUM_ID2' ,'IAP_EDO2',
                        'DOC_ID3' ,'FORMATO_ID3' ,'IAP_D3' ,'PER_ID3' ,'NUM_ID3' ,'IAP_EDO3',
                        'DOC_ID4' ,'FORMATO_ID4' ,'IAP_D4' ,'PER_ID4' ,'NUM_ID4' ,'IAP_EDO4',                        
                        'DOC_ID5' ,'FORMATO_ID5' ,'IAP_D5' ,'PER_ID5' ,'NUM_ID5' ,'IAP_EDO5', 
                        'PREG_007','PREG_008','PREG_009','PREG_010','PREG_011','PREG_012',                        
                        'PREG_013','PREG_014','PREG_015','PREG_016','PREG_017','PREG_018',
                        'PREG_019','PREG_020','PREG_021','PREG_022','PREG_023','PREG_024',                        
                        'IAP_STATUS','FECREG','IP','LOGIN','FECHA_M','IP_M','LOGIN_M')
                        ->where('IAP_ID', $id)
                        ->first();
        if($regasistencia->count() <= 0){
            toastr()->error('No existe requisito asistencial.','Lo siento!',['positionClass' => 'toast-bottom-right']);
            //return redirect()->route('nuevoasistencial');
        }
        return view('sicinar.requisitos.editarReqa3',compact('nombre','usuario','regiap','regasistencia','regnumeros', 'regperiodos','regperiodicidad','regformatos'));
    }

    public function actionActualizarReqa3(reqasistenciaRequest $request, $id){
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
        $regasistencia = regReqAsistenciaModel::where('IAP_ID',$id);
        if($regasistencia->count() <= 0)
            toastr()->error('No existe archivo 3.','¡Por favor volver a intentar!',['positionClass' => 'toast-bottom-right']);
        else{        

            //echo "Escribió en el campo de texto 1: " .'-'. $request->iap_D4 .'-'. "<br><br>"; 
            //echo "Escribió en el campo de texto 1: " . $request->iap_D4 . "<br><br>"; 
            //Comprobar  si el campo foto1 tiene un archivo asignado:
            $name3 =null;
            if($request->hasFile('iap_d3')){
                echo "Escribió en el campo de texto 3: " .'-'. $request->iap_d3 .'-'. "<br><br>"; 
                $name3 = $id.'_'.$request->file('iap_d3')->getClientOriginalName(); 
                //sube el archivo a la carpeta del servidor public/images/
                $request->file('iap_d3')->move(public_path().'/images/', $name3);

                // ************* Actualizamos registro **********************************
                $regasistencia = regReqAsistenciaModel::where('IAP_ID',$id)        
                           ->update([   
                                     'DOC_ID3'    => $request->doc_id3,
                                     'FORMATO_ID3'=> $request->formato_id3,             
                                     'IAP_D3'     => $name3,                  
                                     'PER_ID3'    => $request->per_id3,
                                     'NUM_ID3'    => $request->num_id3,                
                                     'IAP_EDO3'   => $request->iap_edo3,

                                     'IP_M'       => $ip,
                                     'LOGIN_M'    => $nombre,
                                     'FECHA_M'    => date('Y/m/d')    //date('d/m/Y')                                
                                     ]);
                toastr()->success('Requisito asistencial 3 actualizado.','¡Ok!',['positionClass' => 'toast-bottom-right']);
            }else{
                // ************* Actualizamos registro **********************************
                $regasistencia = regReqAsistenciaModel::where('IAP_ID',$id)        
                           ->update([   
                                     'DOC_ID3'    => $request->doc_id3,
                                     'FORMATO_ID3'=> $request->formato_id3,             
                                     //'IAP_ID3'  => $name3,                  
                                     'PER_ID3'    => $request->per_id3,
                                     'NUM_ID3'    => $request->num_id3,                
                                     'IAP_EDO3'   => $request->iap_edo3,

                                     'IP_M'       => $ip,
                                     'LOGIN_M'    => $nombre,
                                     'FECHA_M'    => date('Y/m/d')    //date('d/m/Y')                                
                                     ]);                
                toastr()->success('Requisito asistencial 3 actualizado.','¡Ok!',['positionClass' => 'toast-bottom-right']);
            }

            /************ Bitacora inicia *************************************/ 
            setlocale(LC_TIME, "spanish");        
            $xip          = session()->get('ip');
            $xperiodo_id  = (int)date('Y');
            $xprograma_id = 1;
            $xmes_id      = (int)date('m');
            $xproceso_id  =         3;
            $xfuncion_id  =      3003;
            $xtrx_id      =       161;    //Actualizar         
            $regbitacora = regBitacoraModel::select('PERIODO_ID','PROGRAMA_ID','MES_ID','PROCESO_ID','FUNCION_ID', 
                               'TRX_ID','FOLIO','NO_VECES','FECHA_REG','IP','LOGIN','FECHA_M','IP_M','LOGIN_M')
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
                        toastr()->success('Bitacora dada de alta correctamente.','¡Ok!',['positionClass' => 'toast-bottom-right']);
                    else
                        toastr()->error('Error inesperado al dar de alta la bitacora. Por favor volver a interlo.','Ups!',['positionClass' => 'toast-bottom-right']);
            }else{                   
                    //*********** Obtine el no. de veces *****************************
                    $xno_veces = regBitacoraModel::where(['PERIODO_ID' => $xperiodo_id, 'PROGRAMA_ID' => $xprograma_id, 
                                                      'MES_ID' => $xmes_id, 'PROCESO_ID' => $xproceso_id, 
                                                      'FUNCION_ID' => $xfuncion_id, 'TRX_ID' => $xtrx_id, 'FOLIO' => $id])
                                 ->max('NO_VECES');
                    $xno_veces = $xno_veces+1;                        
                    //*********** Termina de obtener el no de veces *****************************                    
                    $regbitacora= regBitacoraModel::select('NO_VECES','IP_M','LOGIN_M','FECHA_M')
                                  ->where(['PERIODO_ID' => $xperiodo_id,'PROGRAMA_ID' => $xprograma_id,
                                           'MES_ID' => $xmes_id,'PROCESO_ID' => $xproceso_id,'FUNCION_ID' => $xfuncion_id,
                                           'TRX_ID' => $xtrx_id,'FOLIO' => $id])
                                  ->update([
                                            'NO_VECES'=> $regbitacora->NO_VECES = $xno_veces,
                                            'IP_M'    => $regbitacora->IP       = $ip,
                                            'LOGIN_M' => $regbitacora->LOGIN_M  = $nombre,
                                            'FECHA_M' => $regbitacora->FECHA_M  = date('Y/m/d')  //date('d/m/Y')
                                          ]);
                    toastr()->success('Bitacora actualizada.','¡Ok!',['positionClass' => 'toast-bottom-right']);
            }   /************ Bitacora termina *************************************/         
            
        }       /************ Termina de actualizar ********************************/

        return redirect()->route('verReqa');
        
    }    

    /****************** Editar requísitos asistenciales **********/
    public function actionEditarReqa4($id){
        $nombre        = session()->get('userlog');
        $pass          = session()->get('passlog');
        if($nombre == NULL AND $pass == NULL){
            return view('sicinar.login.expirada');
        }
        $usuario       = session()->get('usuario');
        $rango         = session()->get('rango');
        $arbol_id      = session()->get('arbol_id');        

        $regnumeros   = regNumerosModel::select('NUM_ID', 'NUM_DESC')->orderBy('NUM_ID','asc')
                        ->get();
        $regperiodos  = regPfiscalesModel::select('PERIODO_ID', 'PERIODO_DESC')->orderBy('PERIODO_ID','asc')
                        ->get();        
        $regperiodicidad= regPerModel::select('PER_ID', 'PER_DESC')->orderBy('PER_ID','asc')
                        ->get(); 
        $regformatos  = regFormatosModel::select('FORMATO_ID','FORMATO_DESC')
                        ->get();                               
        $regiap       = regIapModel::select('IAP_ID', 'IAP_DESC','IAP_STATUS')->orderBy('IAP_DESC','asc')
                        ->get();
        $regasistencia= regReqAsistenciaModel::select('IAP_ID','PERIODO_ID',
                        'DOC_ID1' ,'FORMATO_ID1' ,'IAP_D1' ,'PER_ID1' ,'NUM_ID1' ,'IAP_EDO1',
                        'DOC_ID2' ,'FORMATO_ID2' ,'IAP_D2' ,'PER_ID2' ,'NUM_ID2' ,'IAP_EDO2',
                        'DOC_ID3' ,'FORMATO_ID3' ,'IAP_D3' ,'PER_ID3' ,'NUM_ID3' ,'IAP_EDO3',
                        'DOC_ID4' ,'FORMATO_ID4' ,'IAP_D4' ,'PER_ID4' ,'NUM_ID4' ,'IAP_EDO4',                        
                        'DOC_ID5' ,'FORMATO_ID5' ,'IAP_D5' ,'PER_ID5' ,'NUM_ID5' ,'IAP_EDO5',
                        'PREG_007','PREG_008','PREG_009','PREG_010','PREG_011','PREG_012', 
                        'PREG_013','PREG_014','PREG_015','PREG_016','PREG_017','PREG_018',
                        'PREG_019','PREG_020','PREG_021','PREG_022','PREG_023','PREG_024',                          
                        'IAP_STATUS','FECREG','IP','LOGIN','FECHA_M','IP_M','LOGIN_M')
                        ->where('IAP_ID', $id)
                        ->first();
        if($regasistencia->count() <= 0){
            toastr()->error('No existe requísito asistencial.','Lo siento!',['positionClass' => 'toast-bottom-right']);
            //return redirect()->route('nuevoasistencial');
        }
        return view('sicinar.requisitos.editarReqa4',compact('nombre','usuario','regiap','regasistencia','regnumeros', 'regperiodos','regperiodicidad','regformatos'));
    }

    public function actionActualizarReqa4(reqasistenciaRequest $request, $id){
        $nombre        = session()->get('userlog');
        $pass          = session()->get('passlog');
        if($nombre == NULL AND $pass == NULL){
            return view('sicinar.login.expirada');
        }
        $usuario       = session()->get('usuario');
        $rango         = session()->get('rango');
        $ip            = session()->get('ip');
        $arbol_id     = session()->get('arbol_id');        

        // **************** actualizar ******************************
        $regasistencia = regReqAsistenciaModel::where('IAP_ID',$id);
        if($regasistencia->count() <= 0)
            toastr()->error('No existe requisito 4.','¡Por favor volver a intentar!',['positionClass' => 'toast-bottom-right']);
        else{        

            //echo "Escribió en el campo de texto 1: " .'-'. $request->iap_D4 .'-'. "<br><br>"; 
            //echo "Escribió en el campo de texto 1: " . $request->iap_D4 . "<br><br>"; 
            //Comprobar  si el campo foto1 tiene un archivo asignado:
            $name4 =null;
            if($request->hasFile('iap_d4')){
                echo "Escribió en el campo de texto 4: " .'-'. $request->iap_d4 .'-'. "<br><br>"; 
                $name4 = $id.'_'.$request->file('iap_d4')->getClientOriginalName(); 
                //sube el archivo a la carpeta del servidor public/images/
                $request->file('iap_d4')->move(public_path().'/images/', $name4);

                // ************* Actualizamos registro **********************************
                $regasistencia = regReqAsistenciaModel::where('IAP_ID',$id)        
                           ->update([   
                                     'DOC_ID4'    => $request->doc_id4,
                                     'FORMATO_ID4'=> $request->formato_id4,             
                                     'IAP_D4'     => $name4,                  
                                     'PER_ID4'    => $request->per_id4,
                                     'NUM_ID4'    => $request->num_id4,                
                                     'IAP_EDO4'   => $request->iap_edo4,

                                     'PREG_015'   => $request->PREG_015,                                     

                                     'IP_M'       => $ip,
                                     'LOGIN_M'    => $nombre,
                                     'FECHA_M'    => date('Y/m/d')    //date('d/m/Y')                                
                                     ]);
                toastr()->success('Requisito asistencial 4 actualizado.','¡Ok!',['positionClass' => 'toast-bottom-right']);
            }else{
                // ************* Actualizamos registro **********************************
                $regasistencia = regReqAsistenciaModel::where('IAP_ID',$id)        
                           ->update([   
                                     'DOC_ID4'    => $request->doc_id4,
                                     'FORMATO_ID4'=> $request->formato_id4,             
                                     //'IAP_D4'   => $name4,                  
                                     'PER_ID4'    => $request->per_id4,
                                     'NUM_ID4'    => $request->num_id4,                
                                     'IAP_EDO4'   => $request->iap_edo4,

                                     'PREG_015'   => $request->PREG_015,                                             

                                     'IP_M'       => $ip,
                                     'LOGIN_M'    => $nombre,
                                     'FECHA_M'    => date('Y/m/d')    //date('d/m/Y')                                
                                     ]);                
                toastr()->success('Requisito asistencial 4 actualizado.','¡Ok!',['positionClass' => 'toast-bottom-right']);
            }

            /************ Bitacora inicia *************************************/ 
            setlocale(LC_TIME, "spanish");        
            $xip          = session()->get('ip');
            $xperiodo_id  = (int)date('Y');
            $xprograma_id = 1;
            $xmes_id      = (int)date('m');
            $xproceso_id  =         3;
            $xfuncion_id  =      3003;
            $xtrx_id      =       161;    //Actualizar         
            $regbitacora = regBitacoraModel::select('PERIODO_ID','PROGRAMA_ID','MES_ID','PROCESO_ID','FUNCION_ID', 
                               'TRX_ID','FOLIO','NO_VECES','FECHA_REG','IP','LOGIN','FECHA_M','IP_M','LOGIN_M')
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
                        toastr()->success('Bitacora dada de alta correctamente.','¡Ok!',['positionClass' => 'toast-bottom-right']);
                    else
                        toastr()->error('Error inesperado al dar de alta la bitacora. Por favor volver a interlo.','Ups!',['positionClass' => 'toast-bottom-right']);
            }else{                   
                    //*********** Obtine el no. de veces *****************************
                    $xno_veces = regBitacoraModel::where(['PERIODO_ID' => $xperiodo_id, 'PROGRAMA_ID' => $xprograma_id, 
                                                      'MES_ID' => $xmes_id, 'PROCESO_ID' => $xproceso_id, 
                                                      'FUNCION_ID' => $xfuncion_id, 'TRX_ID' => $xtrx_id, 'FOLIO' => $id])
                                 ->max('NO_VECES');
                    $xno_veces = $xno_veces+1;                        
                    //*********** Termina de obtener el no de veces *****************************                    
                    $regbitacora= regBitacoraModel::select('NO_VECES','IP_M','LOGIN_M','FECHA_M')
                                  ->where(['PERIODO_ID' => $xperiodo_id,'PROGRAMA_ID' => $xprograma_id,'MES_ID' => $xmes_id,
                                           'PROCESO_ID' => $xproceso_id, 'FUNCION_ID' => $xfuncion_id,'TRX_ID' => $xtrx_id, 
                                           'FOLIO' => $id])
                                  ->update([
                                            'NO_VECES' => $regbitacora->NO_VECES = $xno_veces,
                                            'IP_M' => $regbitacora->IP           = $ip,
                                            'LOGIN_M' => $regbitacora->LOGIN_M   = $nombre,
                                            'FECHA_M' => $regbitacora->FECHA_M   = date('Y/m/d')  //date('d/m/Y')
                                          ]);
                    toastr()->success('Bitacora actualizada.','¡Ok!',['positionClass' => 'toast-bottom-right']);
            }   /************ Bitacora termina *************************************/         
            
        }       /************ Termina de actualizar ********************************/

        return redirect()->route('verReqa');
        
    }    
    /****************** Editar requísitos asistenciales **********/
    public function actionEditarReqa5($id){
        $nombre        = session()->get('userlog');
        $pass          = session()->get('passlog');
        if($nombre == NULL AND $pass == NULL){
            return view('sicinar.login.expirada');
        }
        $usuario       = session()->get('usuario');
        $rango         = session()->get('rango');
        $arbol_id      = session()->get('arbol_id');        

        $regnumeros   = regNumerosModel::select('NUM_ID', 'NUM_DESC')->orderBy('NUM_ID','asc')
                        ->get();
        $regperiodos  = regPfiscalesModel::select('PERIODO_ID', 'PERIODO_DESC')->orderBy('PERIODO_ID','asc')
                        ->get();        
        $regperiodicidad= regPerModel::select('PER_ID', 'PER_DESC')->orderBy('PER_ID','asc')
                        ->get();     
        $regformatos  = regFormatosModel::select('FORMATO_ID','FORMATO_DESC')
                        ->get();       
        $regiap       = regIapModel::select('IAP_ID', 'IAP_DESC','IAP_STATUS')->orderBy('IAP_DESC','asc')
                        ->get();
        $regasistencia= regReqAsistenciaModel::select('IAP_ID','PERIODO_ID',
                        'DOC_ID1' ,'FORMATO_ID1' ,'IAP_D1' ,'PER_ID1' ,'NUM_ID1' ,'IAP_EDO1',
                        'DOC_ID2' ,'FORMATO_ID2' ,'IAP_D2' ,'PER_ID2' ,'NUM_ID2' ,'IAP_EDO2',
                        'DOC_ID3' ,'FORMATO_ID3' ,'IAP_D3' ,'PER_ID3' ,'NUM_ID3' ,'IAP_EDO3',
                        'DOC_ID4' ,'FORMATO_ID4' ,'IAP_D4' ,'PER_ID4' ,'NUM_ID4' ,'IAP_EDO4',                        
                        'DOC_ID5' ,'FORMATO_ID5' ,'IAP_D5' ,'PER_ID5' ,'NUM_ID5' ,'IAP_EDO5',        
                        'PREG_007','PREG_008','PREG_009','PREG_010','PREG_011','PREG_012',                        
                        'PREG_013','PREG_014','PREG_015','PREG_016','PREG_017','PREG_018',
                        'PREG_019','PREG_020','PREG_021','PREG_022','PREG_023','PREG_024',                          
                        'IAP_STATUS','FECREG','IP','LOGIN','FECHA_M','IP_M','LOGIN_M')
                       ->where('IAP_ID', $id)
                       ->first();
        if($regasistencia->count() <= 0){
            toastr()->error('No existe requísito asistencial 5.','Lo siento!',['positionClass' => 'toast-bottom-right']);
            //return redirect()->route('nuevoasistencial');
        }
        return view('sicinar.requisitos.editarReqa5',compact('nombre','usuario','regiap','regasistencia','regnumeros', 'regperiodos','regperiodicidad','regformatos'));
    }


    public function actionActualizarReqa5(reqasistenciaRequest $request, $id){
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
        $regasistencia = regReqAsistenciaModel::where('IAP_ID',$id);
        if($regasistencia->count() <= 0)
            toastr()->error('No existe requísito asistencial.','¡Por favor volver a intentar!',['positionClass' => 'toast-bottom-right']);
        else{        

            //***************** Actualizar *****************************
            //echo "Escribió en el campo de texto 1: " .'-'. $request->iap_D4 .'-'. "<br><br>"; 
            //echo "Escribió en el campo de texto 1: " . $request->iap_D4 . "<br><br>"; 
            //Comprobar  si el campo foto1 tiene un archivo asignado:
            $name5 =null;
            if($request->hasFile('iap_d5')){
                echo "Escribió en el campo de texto 5: " .'-'. $request->iap_d5 .'-'. "<br><br>"; 
                $name5 = $id.'_'.$request->file('iap_d5')->getClientOriginalName(); 
                //sube el archivo a la carpeta del servidor public/images/
                $request->file('iap_d5')->move(public_path().'/images/', $name5);

                // ************* Actualizamos registro **********************************
                $regasistencia = regReqAsistenciaModel::where('IAP_ID',$id)        
                               ->update([  
                                      'DOC_ID5'    => $request->doc_id5,
                                      'FORMATO_ID5'=> $request->formato_id5,             
                                      'IAP_D5'     => $name5,           
                                      'PER_ID5'    => $request->per_id5,
                                      'NUM_ID5'    => $request->num_id5,                
                                      'IAP_EDO5'   => $request->iap_edo5,

                                      'PREG_016'   => $request->PREG_016,
                                      'PREG_017'   => $request->PREG_017,

                                      'IP_M'       => $ip,
                                      'LOGIN_M'    => $nombre,
                                      'FECHA_M'    => date('Y/m/d')    //date('d/m/Y')                                
                                    ]);
                toastr()->success('Requísito asistencial 5 actualizado.','¡Ok!',['positionClass' => 'toast-bottom-right']);
            }else{
                // ************* Actualizamos registro **********************************
                $regasistencia = regReqAsistenciaModel::where('IAP_ID',$id)        
                               ->update([  
                                      'DOC_ID5'    => $request->doc_id5,
                                      'FORMATO_ID5'=> $request->formato_id5,             
                                      //'IAP_D5'   => $name5,           
                                      'PER_ID5'    => $request->per_id5,
                                      'NUM_ID5'    => $request->num_id5,                
                                      'IAP_EDO5'   => $request->iap_edo5,

                                      'PREG_016'   => $request->PREG_016,
                                      'PREG_017'   => $request->PREG_017,

                                      'IP_M'       => $ip,
                                      'LOGIN_M'    => $nombre,
                                      'FECHA_M'    => date('Y/m/d')    //date('d/m/Y')                                
                                    ]);                
                toastr()->success('Requisito asistencial 5 actualizado.','¡Ok!',['positionClass' => 'toast-bottom-right']);
            }

            /************ Bitacora inicia *************************************/ 
            setlocale(LC_TIME, "spanish");        
            $xip          = session()->get('ip');
            $xperiodo_id  = (int)date('Y');
            $xprograma_id = 1;
            $xmes_id      = (int)date('m');
            $xproceso_id  =         3;
            $xfuncion_id  =      3003;
            $xtrx_id      =       161;    //Actualizar         
            $regbitacora = regBitacoraModel::select('PERIODO_ID','PROGRAMA_ID','MES_ID','PROCESO_ID','FUNCION_ID', 
                               'TRX_ID','FOLIO','NO_VECES','FECHA_REG','IP','LOGIN','FECHA_M','IP_M','LOGIN_M')
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
                        toastr()->success('Bitacora dada de alta correctamente.','¡Ok!',['positionClass' => 'toast-bottom-right']);
                    else
                        toastr()->error('Error inesperado al dar de alta la bitacora. Por favor volver a interlo.','Ups!',['positionClass' => 'toast-bottom-right']);
            }else{                   
                    //*********** Obtine el no. de veces *****************************
                    $xno_veces = regBitacoraModel::where(['PERIODO_ID' => $xperiodo_id, 'PROGRAMA_ID' => $xprograma_id, 
                                                      'MES_ID' => $xmes_id, 'PROCESO_ID' => $xproceso_id, 
                                                      'FUNCION_ID' => $xfuncion_id, 'TRX_ID' => $xtrx_id, 'FOLIO' => $id])
                                 ->max('NO_VECES');
                    $xno_veces = $xno_veces+1;                        
                    //*********** Termina de obtener el no de veces *****************************                    
                    $regbitacora= regBitacoraModel::select('NO_VECES','IP_M','LOGIN_M','FECHA_M')
                                  ->where(['PERIODO_ID' => $xperiodo_id,'PROGRAMA_ID' => $xprograma_id,
                                           'MES_ID' => $xmes_id, 'PROCESO_ID' => $xproceso_id, 
                                           'FUNCION_ID' => $xfuncion_id,'TRX_ID' => $xtrx_id, 'FOLIO' => $id])
                                  ->update([
                                            'NO_VECES' => $regbitacora->NO_VECES = $xno_veces,
                                            'IP_M' => $regbitacora->IP           = $ip,
                                            'LOGIN_M' => $regbitacora->LOGIN_M   = $nombre,
                                            'FECHA_M' => $regbitacora->FECHA_M   = date('Y/m/d')  //date('d/m/Y')
                                          ]);
                toastr()->success('Bitacora actualizada.','¡Ok!',['positionClass' => 'toast-bottom-right']);
            }   /************ Bitacora termina *************************************/         

        }       /************ Termina de actualizar ********************************/

        return redirect()->route('verReqa');    
    }    


    //***** Borrar registro completo ***********************
    public function actionBorrarReqa($id){
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

        /************ Elimina transacción de asistencia social y asistencial ***************/
        $regasistencia = regReqAsistenciaModel::where('IAP_ID',$id);
        if($regasistencia->count() <= 0)
            toastr()->error('No existe requisito asistencial.','¡Por favor volver a intentar!',['positionClass' => 'toast-bottom-right']);
        else{        
            $regasistencia->delete();
            toastr()->success('Requisito asistencial eliminado.','¡Ok!',['positionClass' => 'toast-bottom-right']);

            //echo 'Ya entre aboorar registro..........';
            /************ Bitacora inicia *************************************/ 
            setlocale(LC_TIME, "spanish");        
            $xip          = session()->get('ip');
            $xperiodo_id  = (int)date('Y');
            $xprograma_id = 1;
            $xmes_id      = (int)date('m');
            $xproceso_id  =         3;
            $xfuncion_id  =      3003;
            $xtrx_id      =       162;     // borrar 
            $regbitacora = regBitacoraModel::select('PERIODO_ID', 'PROGRAMA_ID','MES_ID','PROCESO_ID','FUNCION_ID', 
                           'TRX_ID','FOLIO','NO_VECES','FECHA_REG','IP','LOGIN','FECHA_M','IP_M','LOGIN_M')
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
                    toastr()->success('Bitacora dada de alta correctamente.','¡Ok!',['positionClass' => 'toast-bottom-right']);
                else
                    toastr()->error('Error inesperado al dar de alta la bitacora. Por favor volver a interlo.','Ups!',['positionClass' => 'toast-bottom-right']);
            }else{                   
                //*********** Obtine el no. de veces *****************************
                $xno_veces = regBitacoraModel::where(['PERIODO_ID' => $xperiodo_id, 'PROGRAMA_ID' => $xprograma_id, 
                             'MES_ID'  => $xmes_id, 'PROCESO_ID' => $xproceso_id, 'FUNCION_ID' => $xfuncion_id, 
                             'TRX_ID' => $xtrx_id, 'FOLIO' => $id])
                             ->max('NO_VECES');
                $xno_veces = $xno_veces+1;                        
                //*********** Termina de obtener el no de veces *****************************         
                $regbitacora = regBitacoraModel::select('NO_VECES','IP_M','LOGIN_M','FECHA_M')
                               ->where(['PERIODO_ID' => $xperiodo_id,'PROGRAMA_ID' => $xprograma_id,'MES_ID' => $xmes_id, 
                                        'PROCESO_ID' => $xproceso_id, 'FUNCION_ID' => $xfuncion_id, 'TRX_ID' => $xtrx_id, 
                                        'FOLIO' => $id])
                               ->update([
                                         'NO_VECES' => $regbitacora->NO_VECES = $xno_veces,
                                         'IP_M' => $regbitacora->IP           = $ip,
                                         'LOGIN_M' => $regbitacora->LOGIN_M   = $nombre,
                                         'FECHA_M' => $regbitacora->FECHA_M   = date('Y/m/d')  //date('d/m/Y')
                                        ]);
                toastr()->success('Bitacora actualizada.','¡Ok!',['positionClass' => 'toast-bottom-right']);
            }   /************ Bitacora termina *************************************/    

        }   /************* Termina de eliminar  ************************************/
        
        return redirect()->route('verReqa');
    }    

}