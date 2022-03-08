<?php
//**************************************************************/
//* File:       rContablesController.php
//* Proyecto:   Sistema SRPE.V1 DGPS
//¨Función:     Clases para el modulo de requisitos administrativos
//* Autor:      Ing. Silverio Baltazar Barrientos Zarate
//* Modifico:   Ing. Silverio Baltazar Barrientos Zarate
//* Fecha act.: octubre 2021
//* @Derechos reservados. Gobierno del Estado de México
//*************************************************************/
namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests\reqcontablesRequest;
use App\Http\Requests\reqcontables7Request;
use App\Http\Requests\reqcontables8Request;
use App\Http\Requests\reqcontables9Request;
use App\Http\Requests\reqcontables10Request;
use App\Http\Requests\reqcontables11Request;
use App\regOscModel;
use App\regBitacoraModel;
use App\regPfiscalesModel;
use App\regReqContablesModel;
use App\regPerModel;
use App\regNumerosModel;
use App\regFormatosModel;
 
// Exportar a excel 
use Maatwebsite\Excel\Facades\Excel;

// Exportar a pdf
use PDF;
//use Options;

class rContablesController extends Controller
{

    //******** Buscar requisitos administrativos *****//
    public function actionBuscarReqc(Request $request){
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
        //**************************************************************//
        // ***** busqueda https://github.com/rimorsoft/Search-simple ***//
        // ***** video https://www.youtube.com/watch?v=bmtD9GUaszw   ***//                            
        //**************************************************************//
        $fper    = $request->get('fper');   
        $idd     = $request->get('idd');  
        $nameiap = $request->get('nameiap');                  
        //********* Validar rol de usuario **********************/
        if(session()->get('rango') !== '0'){   
            $regosc       = regOscModel::select('OSC_ID', 'OSC_DESC','OSC_STATUS')
                            ->get();                                      
            $regcontable  = regReqContablesModel::join('PE_OSC','PE_OSC.OSC_ID','=','PE_REQUISITOS_ADMON.OSC_ID')
                            ->select( 'PE_OSC.OSC_DESC','PE_REQUISITOS_ADMON.*')
                            ->orderBy('PE_REQUISITOS_ADMON.PERIODO_ID','ASC')
                            ->orderBy('PE_REQUISITOS_ADMON.OSC_ID'    ,'ASC')                            
                            ->orderBy('PE_REQUISITOS_ADMON.OSC_FOLIO' ,'ASC')
                            ->fper($fper)           //Metodos personalizados es equvalente a ->where('OSC_DESC', 'LIKE', "%$name%");
                            ->idd($idd)             //Metodos personalizados  
                            ->nameiap($nameiap)     //Metodos personalizados                                                                                                            
                            ->paginate(30);
        }else{  
            $regosc       = regOscModel::select('OSC_ID', 'OSC_DESC','OSC_STATUS')
                            ->where('OSC_ID',$arbol_id)
                            ->get();                                  
            $regcontable  = regReqContablesModel::join('PE_OSC','PE_OSC.OSC_ID','=','PE_REQUISITOS_ADMON.OSC_ID')
                            ->select( 'PE_OSC.OSC_DESC','PE_REQUISITOS_ADMON.*')
                            ->where(  'PE_REQUISITOS_ADMON.OSC_ID'    ,$arbol_id)
                            ->orderBy('PE_REQUISITOS_ADMON.PERIODO_ID','ASC')
                            ->orderBy('PE_REQUISITOS_ADMON.OSC_ID'    ,'ASC')
                            ->orderBy('PE_REQUISITOS_ADMON.OSC_FOLIO' ,'ASC') 
                            ->fper($fper)           //Metodos personalizados es equvalente a ->where('OSC_DESC', 'LIKE', "%$name%");
                            ->idd($idd)             //Metodos personalizados
                            ->nameiap($nameiap)     //Metodos personalizados                                                                               
                            ->paginate(30);            
        }
        if($regcontable->count() <= 0){
            toastr()->error('No existen requisitos administrativos.','Lo siento!',['positionClass' => 'toast-bottom-right']);
        }
        return view('sicinar.requisitos.verReqc',compact('nombre','usuario','regosc','regperiodicidad','regnumeros', 'regperiodos','regcontable','regformatos'));
    }

    //******** Mostrar requisitos administrativos *****//
    public function actionVerReqc(){
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
        //********* Validar rol de usuario **********************/
        if(session()->get('rango') !== '0'){  
            $regosc       = regOscModel::select('OSC_ID', 'OSC_DESC','OSC_STATUS')
                            ->get();                                                    
            $regcontable  = regReqContablesModel::select('OSC_FOLIO','OSC_ID','PERIODO_ID',
                            'DOC_ID6' ,'FORMATO_ID6' ,'OSC_D6' ,'PER_ID6' ,'NUM_ID6' ,'OSC_EDO6',
                            'DOC_ID7' ,'FORMATO_ID7' ,'OSC_D7' ,'PER_ID7' ,'NUM_ID7' ,'OSC_EDO7',
                            'DOC_ID8' ,'FORMATO_ID8' ,'OSC_D8' ,'PER_ID8' ,'NUM_ID8' ,'OSC_EDO8',
                            'DOC_ID9' ,'FORMATO_ID9' ,'OSC_D9' ,'PER_ID9' ,'NUM_ID9' ,'OSC_EDO9',                        
                            'DOC_ID10','FORMATO_ID10','OSC_D10','PER_ID10','NUM_ID10','OSC_EDO10',                        
                            'DOC_ID11','FORMATO_ID11','OSC_D11','PER_ID11','NUM_ID11','OSC_EDO11',
                            'DOC_ID1002','OSC_D1002','PER_ID1002','NUM_ID1002',
                            'DOC_ID1003','OSC_D1003','PER_ID1003','NUM_ID1003',
                            'DOC_ID1004','OSC_D1004','PER_ID1004','NUM_ID1004',
                            'DOC_ID1005','OSC_D1005','PER_ID1005','NUM_ID1005',
                            'DOC_ID1006','OSC_D1006','PER_ID1006','NUM_ID1006',
                            'DOC_ID1007','OSC_D1007','PER_ID1007','NUM_ID1007',
                            'DOC_ID1008','OSC_D1008','PER_ID1008','NUM_ID1008',
                            'DOC_ID1009','OSC_D1009','PER_ID1009','NUM_ID1009',
                            'DOC_ID1010','OSC_D1010','PER_ID1010','NUM_ID1010',
                            'DOC_ID1011','OSC_D1011','PER_ID1011','NUM_ID1011',
                            'DOC_ID1012','OSC_D1012','PER_ID1012','NUM_ID1012',
                            'PREG_1002','PREG_1003','PREG_1004','PREG_1005','PREG_1006',
                            'PREG_1007','PREG_1008','PREG_1009','PREG_1010','PREG_1011','PREG_1012',
                            'PREG_001','PREG_002','PREG_003','PREG_004','PREG_005','PREG_006',
                            'OSC_STATUS','FECREG','IP','LOGIN','FECHA_M','IP_M','LOGIN_M')
                            ->orderBy('PERIODO_ID','ASC')
                            ->orderBy('OSC_ID'    ,'ASC')                            
                            ->orderBy('OSC_FOLIO' ,'ASC') 
                            ->paginate(30);
        }else{  
            $regosc       = regOscModel::select('OSC_ID', 'OSC_DESC','OSC_STATUS')
                            ->where('OSC_ID',$arbol_id)
                            ->get();                    
            $regcontable  = regReqContablesModel::select('OSC_FOLIO','OSC_ID','PERIODO_ID',
                            'DOC_ID6' ,'FORMATO_ID6' ,'OSC_D6' ,'PER_ID6' ,'NUM_ID6' ,'OSC_EDO6',
                            'DOC_ID7' ,'FORMATO_ID7' ,'OSC_D7' ,'PER_ID7' ,'NUM_ID7' ,'OSC_EDO7',
                            'DOC_ID8' ,'FORMATO_ID8' ,'OSC_D8' ,'PER_ID8' ,'NUM_ID8' ,'OSC_EDO8',
                            'DOC_ID9' ,'FORMATO_ID9' ,'OSC_D9' ,'PER_ID9' ,'NUM_ID9' ,'OSC_EDO9',                        
                            'DOC_ID10','FORMATO_ID10','OSC_D10','PER_ID10','NUM_ID10','OSC_EDO10',                        
                            'DOC_ID11','FORMATO_ID11','OSC_D11','PER_ID11','NUM_ID11','OSC_EDO11',
                            'DOC_ID1002','OSC_D1002','PER_ID1002','NUM_ID1002',
                            'DOC_ID1003','OSC_D1003','PER_ID1003','NUM_ID1003',
                            'DOC_ID1004','OSC_D1004','PER_ID1004','NUM_ID1004',
                            'DOC_ID1005','OSC_D1005','PER_ID1005','NUM_ID1005',
                            'DOC_ID1006','OSC_D1006','PER_ID1006','NUM_ID1006',
                            'DOC_ID1007','OSC_D1007','PER_ID1007','NUM_ID1007',
                            'DOC_ID1008','OSC_D1008','PER_ID1008','NUM_ID1008',
                            'DOC_ID1009','OSC_D1009','PER_ID1009','NUM_ID1009',
                            'DOC_ID1010','OSC_D1010','PER_ID1010','NUM_ID1010',
                            'DOC_ID1011','OSC_D1011','PER_ID1011','NUM_ID1011',
                            'DOC_ID1012','OSC_D1012','PER_ID1012','NUM_ID1012',
                            'PREG_1002','PREG_1003','PREG_1004','PREG_1005','PREG_1006',
                            'PREG_1007','PREG_1008','PREG_1009','PREG_1010','PREG_1011','PREG_1012',                            
                            'PREG_001','PREG_002','PREG_003','PREG_004','PREG_005','PREG_006',
                            'OSC_STATUS','FECREG','IP','LOGIN','FECHA_M','IP_M','LOGIN_M')
                            ->where(  'OSC_ID'    ,$arbol_id)
                            ->orderBy('PERIODO_ID','ASC')
                            ->orderBy('OSC_ID'    ,'ASC')                            
                            ->orderBy('OSC_FOLIO' ,'ASC')                             
                            ->paginate(30);            
        }
        if($regcontable->count() <= 0){
            toastr()->error('No existen requisitos administrativos.','Lo siento!',['positionClass' => 'toast-bottom-right']);
            //return redirect()->route('nuevocontable');
        }
        return view('sicinar.requisitos.verReqc',compact('nombre','usuario','regosc','regperiodicidad','regnumeros', 'regperiodos','regcontable','regformatos'));
    }


    public function actionNuevoReqc(){
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
        $regformatos  = regFormatosModel::select('FORMATO_ID','FORMATO_DESC')->get();
        if(session()->get('rango') !== '0'){        
            $regosc   = regOscModel::select('OSC_ID', 'OSC_DESC','OSC_STATUS')->orderBy('OSC_DESC','asc')
                        ->get();
        }else{
            $regosc   = regOscModel::select('OSC_ID', 'OSC_DESC','OSC_STATUS')
                        ->where('OSC_ID',$arbol_id)
                        ->get();            
        }                        
        $regcontable  = regReqContablesModel::select('OSC_FOLIO','OSC_ID','PERIODO_ID',
                        'DOC_ID6' ,'FORMATO_ID6' ,'OSC_D6' ,'PER_ID6' ,'NUM_ID6' ,'OSC_EDO6',
                        'DOC_ID7' ,'FORMATO_ID7' ,'OSC_D7' ,'PER_ID7' ,'NUM_ID7' ,'OSC_EDO7',
                        'DOC_ID8' ,'FORMATO_ID8' ,'OSC_D8' ,'PER_ID8' ,'NUM_ID8' ,'OSC_EDO8',
                        'DOC_ID9' ,'FORMATO_ID9' ,'OSC_D9' ,'PER_ID9' ,'NUM_ID9' ,'OSC_EDO9',                        
                        'DOC_ID10','FORMATO_ID10','OSC_D10','PER_ID10','NUM_ID10','OSC_EDO10',                        
                        'DOC_ID11','FORMATO_ID11','OSC_D11','PER_ID11','NUM_ID11','OSC_EDO11',
                        'DOC_ID1002','OSC_D1002','PER_ID1002','NUM_ID1002',
                        'DOC_ID1003','OSC_D1003','PER_ID1003','NUM_ID1003',
                        'DOC_ID1004','OSC_D1004','PER_ID1004','NUM_ID1004',
                        'DOC_ID1005','OSC_D1005','PER_ID1005','NUM_ID1005',
                        'DOC_ID1006','OSC_D1006','PER_ID1006','NUM_ID1006',
                        'DOC_ID1007','OSC_D1007','PER_ID1007','NUM_ID1007',
                        'DOC_ID1008','OSC_D1008','PER_ID1008','NUM_ID1008',
                        'DOC_ID1009','OSC_D1009','PER_ID1009','NUM_ID1009',
                        'DOC_ID1010','OSC_D1010','PER_ID1010','NUM_ID1010',
                        'DOC_ID1011','OSC_D1011','PER_ID1011','NUM_ID1011',
                        'DOC_ID1012','OSC_D1012','PER_ID1012','NUM_ID1012',
                        'PREG_1002','PREG_1003','PREG_1004','PREG_1005','PREG_1006',
                        'PREG_1007','PREG_1008','PREG_1009','PREG_1010','PREG_1011','PREG_1012',                        
                        'PREG_001','PREG_002','PREG_003','PREG_004','PREG_005','PREG_006',
                        'OSC_STATUS','FECREG','IP','LOGIN','FECHA_M','IP_M','LOGIN_M')
                        ->orderBy('OSC_ID','ASC')
                        ->get();        
        //dd($unidades);
        return view('sicinar.requisitos.nuevoReqc',compact('regper','regnumeros','regosc','regperiodos','regperiodicidad','nombre','usuario','regcontable','regformatos'));
    }

    public function actionAltaNuevoReqc(Request $request){
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
        $regcontable  = regReqContablesModel::select('OSC_FOLIO','OSC_ID','PERIODO_ID',
                        'DOC_ID6' ,'FORMATO_ID6' ,'OSC_D6' ,'PER_ID6' ,'NUM_ID6' ,'OSC_EDO6',
                        'DOC_ID7' ,'FORMATO_ID7' ,'OSC_D7' ,'PER_ID7' ,'NUM_ID7' ,'OSC_EDO7',
                        'DOC_ID8' ,'FORMATO_ID8' ,'OSC_D8' ,'PER_ID8' ,'NUM_ID8' ,'OSC_EDO8',
                        'DOC_ID9' ,'FORMATO_ID9' ,'OSC_D9' ,'PER_ID9' ,'NUM_ID9' ,'OSC_EDO9',                        
                        'DOC_ID10','FORMATO_ID10','OSC_D10','PER_ID10','NUM_ID10','OSC_EDO10',                        
                        'DOC_ID11','FORMATO_ID11','OSC_D11','PER_ID11','NUM_ID11','OSC_EDO11',
                            'DOC_ID1002','OSC_D1002','PER_ID1002','NUM_ID1002',
                            'DOC_ID1003','OSC_D1003','PER_ID1003','NUM_ID1003',
                            'DOC_ID1004','OSC_D1004','PER_ID1004','NUM_ID1004',
                            'DOC_ID1005','OSC_D1005','PER_ID1005','NUM_ID1005',
                            'DOC_ID1006','OSC_D1006','PER_ID1006','NUM_ID1006',
                            'DOC_ID1007','OSC_D1007','PER_ID1007','NUM_ID1007',
                            'DOC_ID1008','OSC_D1008','PER_ID1008','NUM_ID1008',
                            'DOC_ID1009','OSC_D1009','PER_ID1009','NUM_ID1009',
                            'DOC_ID1010','OSC_D1010','PER_ID1010','NUM_ID1010',
                            'DOC_ID1011','OSC_D1011','PER_ID1011','NUM_ID1011',
                            'DOC_ID1012','OSC_D1012','PER_ID1012','NUM_ID1012',
                            'PREG_1002','PREG_1003','PREG_1004','PREG_1005','PREG_1006',
                            'PREG_1007','PREG_1008','PREG_1009','PREG_1010','PREG_1011','PREG_1012',                        
                        'PREG_001','PREG_002','PREG_003','PREG_004','PREG_005','PREG_006',
                        'OSC_STATUS','FECREG','IP','LOGIN','FECHA_M','IP_M','LOGIN_M')
                        ->where(['PERIODO_ID' => $request->periodo_id,'OSC_ID' => $request->osc_id])
                        ->get();
        if($regcontable->count() <= 0 && !empty($request->osc_id)){
            //********** Registrar la alta *****************************/
            $osc_folio = regReqContablesModel::max('OSC_FOLIO');
            $osc_folio = $osc_folio+1;
            $nuevocontable = new regReqContablesModel();

            $file6 =null;
            if(isset($request->osc_d6)){
                if(!empty($request->osc_d6)){
                    //Comprobar  si el campo act_const tiene un archivo asignado:
                    if($request->hasFile('osc_d6')){
                        $file6=$request->osc_id.'_'.$request->file('osc_d6')->getClientOriginalName();
                        //sube el archivo a la carpeta del servidor public/images/
                        $request->file('osc_d6')->move(public_path().'/images/', $file6);
                    }
                }
            }            
            $file7 =null;
            if(isset($request->osc_d7)){
                if(!empty($request->osc_d7)){
                    //Comprobar  si el campo act_const tiene un archivo asignado:
                    if($request->hasFile('osc_d7')){
                        $file7=$request->osc_id.'_'.$request->file('osc_d7')->getClientOriginalName();
                        //sube el archivo a la carpeta del servidor public/images/
                        $request->file('osc_d7')->move(public_path().'/images/', $file7);
                    }
                }
            }
            $file8 =null;
            if(isset($request->osc_d8)){
                if(!empty($request->osc_d8)){
                    //Comprobar  si el campo act_const tiene un archivo asignado:
                    if($request->hasFile('osc_d8')){
                        $file8=$request->osc_id.'_'.$request->file('osc_d8')->getClientOriginalName();
                        //sube el archivo a la carpeta del servidor public/images/
                        $request->file('osc_d8')->move(public_path().'/images/', $file8);
                    }
                }
            }
            $file9 =null;
            if(isset($request->osc_d9)){
                if(!empty($request->osc_d9)){
                    //Comprobar  si el campo act_const tiene un archivo asignado:
                    if($request->hasFile('osc_d9')){
                        $file9=$request->osc_id.'_'.$request->file('osc_d9')->getClientOriginalName();
                        //sube el archivo a la carpeta del servidor public/images/
                        $request->file('osc_d9')->move(public_path().'/images/', $file9);
                    }
                }
            }            
            $file10 =null;
            if(isset($request->osc_d10)){
                if(!empty($request->osc_d10)){
                    //Comprobar  si el campo act_const tiene un archivo asignado:
                    if($request->hasFile('osc_d10')){
                        $file10=$request->osc_id.'_'.$request->file('osc_d10')->getClientOriginalName();
                        //sube el archivo a la carpeta del servidor public/images/
                        $request->file('osc_d10')->move(public_path().'/images/', $file10);
                    }
                }
            }
            $file11 =null;
            if(isset($request->osc_d11)){
                if(!empty($request->osc_d11)){
                    //Comprobar  si el campo act_const tiene un archivo asignado:
                    if($request->hasFile('osc_d11')){
                        $file11=$request->osc_id.'_'.$request->file('osc_d11')->getClientOriginalName();
                        //sube el archivo a la carpeta del servidor public/images/
                        $request->file('osc_d11')->move(public_path().'/images/', $file11);
                    }
                }
            }

            $file1002 =null;
            if(isset($request->osc_d1002)){
                if(!empty($request->osc_d1002)){
                    if($request->hasFile('osc_d1002')){
                        $file1002=$request->osc_id.'_'.$request->file('osc_d1002')->getClientOriginalName();
                        $request->file('osc_d1002')->move(public_path().'/images/', $file1002);
                    }
                }
            }
            $file1003 =null;
            if(isset($request->osc_d1003)){
                if(!empty($request->osc_d1003)){
                    if($request->hasFile('osc_d1003')){
                        $file1003=$request->osc_id.'_'.$request->file('osc_d1003')->getClientOriginalName();
                        $request->file('osc_d1003')->move(public_path().'/images/', $file1003);
                    }
                }
            }            
            $file1004 =null;
            if(isset($request->osc_d1004)){
                if(!empty($request->osc_d1004)){
                    if($request->hasFile('osc_d1004')){
                        $file1004=$request->osc_id.'_'.$request->file('osc_d1004')->getClientOriginalName();
                        $request->file('osc_d1004')->move(public_path().'/images/', $file1004);
                    }
                }
            }            
            $file1005 =null;
            if(isset($request->osc_d1005)){
                if(!empty($request->osc_d1005)){
                    if($request->hasFile('osc_d1005')){
                        $file1005=$request->osc_id.'_'.$request->file('osc_d1005')->getClientOriginalName();
                        $request->file('osc_d1005')->move(public_path().'/images/', $file1005);
                    }
                }
            }          
            $file1006 =null;
            if(isset($request->osc_d1006)){
                if(!empty($request->osc_d1006)){
                    if($request->hasFile('osc_d1006')){
                        $file1006=$request->osc_id.'_'.$request->file('osc_d1006')->getClientOriginalName();
                        $request->file('osc_d1006')->move(public_path().'/images/', $file1006);
                    }
                }
            } 
            $file1007 =null;
            if(isset($request->osc_d1007)){
                if(!empty($request->osc_d1007)){
                    if($request->hasFile('osc_d1007')){
                        $file1007=$request->osc_id.'_'.$request->file('osc_d1007')->getClientOriginalName();
                        $request->file('osc_d1007')->move(public_path().'/images/', $file1007);
                    }
                }
            }            
            $file1008 =null;
            if(isset($request->osc_d1008)){
                if(!empty($request->osc_d1008)){
                    if($request->hasFile('osc_d1008')){
                        $file1008=$request->osc_id.'_'.$request->file('osc_d1008')->getClientOriginalName();
                        $request->file('osc_d1008')->move(public_path().'/images/', $file1008);
                    }
                }
            } 
            $file1009 =null;
            if(isset($request->osc_d1009)){
                if(!empty($request->osc_d1009)){
                    if($request->hasFile('osc_d1009')){
                        $file1009=$request->osc_id.'_'.$request->file('osc_d1009')->getClientOriginalName();
                        $request->file('osc_d1009')->move(public_path().'/images/', $file1009);
                    }
                }
            }            
            $file1010 =null;
            if(isset($request->osc_d1010)){
                if(!empty($request->osc_d1010)){
                    if($request->hasFile('osc_d1010')){
                        $file1010=$request->osc_id.'_'.$request->file('osc_d1010')->getClientOriginalName();
                        $request->file('osc_d1010')->move(public_path().'/images/', $file1010);
                    }
                }
            } 
            $file1011 =null;
            if(isset($request->osc_d1011)){
                if(!empty($request->osc_d1011)){
                    if($request->hasFile('osc_d1011')){
                        $file1011=$request->osc_id.'_'.$request->file('osc_d1011')->getClientOriginalName();
                        $request->file('osc_d1011')->move(public_path().'/images/', $file1011);
                    }
                }
            }            
            $file1012 =null;
            if(isset($request->osc_d1012)){
                if(!empty($request->osc_d1012)){
                    if($request->hasFile('osc_d1012')){
                        $file1012=$request->osc_id.'_'.$request->file('osc_d1012')->getClientOriginalName();
                        $request->file('osc_d1012')->move(public_path().'/images/', $file1012);
                    }
                }
            }     
            $preg_003  =str_replace(",", "", $request->preg_003);
            $preg_004  =str_replace(",", "", $request->preg_004);
            $preg_005  =str_replace(",", "", $request->preg_005);
            $preg_006  =str_replace(",", "", $request->preg_006);
            $preg_1002 =str_replace(",", "", $request->preg_1002);
            $preg_1003 =str_replace(",", "", $request->preg_1003);
            $preg_1004 =str_replace(",", "", $request->preg_1004);
            $preg_1005 =str_replace(",", "", $request->preg_1005);            
            $preg_1006 =str_replace(",", "", $request->preg_1006);
            $preg_1007 =str_replace(",", "", $request->preg_1007);
            $preg_1008 =str_replace(",", "", $request->preg_1008);
            $preg_1009 =str_replace(",", "", $request->preg_1009);             
            $preg_1010 =str_replace(",", "", $request->preg_1010); 
            $preg_1011 =str_replace(",", "", $request->preg_1011); 
            $preg_1012 =str_replace(",", "", $request->preg_1012); 

            $nuevocontable->OSC_FOLIO    = $osc_folio;
            $nuevocontable->PERIODO_ID   = $request->periodo_id;
            $nuevocontable->OSC_ID       = $request->osc_id;        

            $nuevocontable->DOC_ID6      = $request->doc_id6;
            $nuevocontable->FORMATO_ID6  = $request->formato_id6;
            $nuevocontable->OSC_D6       = $file6;        
            $nuevocontable->NUM_ID6      = $request->num_id6;
            $nuevocontable->PER_ID6      = $request->per_id6;        
            $nuevocontable->OSC_EDO6     = $request->osc_edo6;

            $nuevocontable->DOC_ID7      = $request->doc_id7;
            $nuevocontable->FORMATO_ID7  = $request->formato_id7;
            $nuevocontable->OSC_D7       = $file7;        
            $nuevocontable->NUM_ID7      = 1;   // 1     $request->num_id7;
            $nuevocontable->PER_ID7      = 5;   // Anual $request->per_id7;        
            //$nuevocontable->OSC_EDO7   = $request->osc_edo7;

            $nuevocontable->DOC_ID8      = $request->doc_id8;
            $nuevocontable->FORMATO_ID8  = $request->formato_id8;
            $nuevocontable->OSC_D8       = $file8;        
            $nuevocontable->NUM_ID8      = 1;   // 1     $request->num_id8;
            $nuevocontable->PER_ID8      = 5;   // Anual $request->per_id8;        
            //$nuevocontable->OSC_EDO8   = $request->osc_edo8;

            $nuevocontable->DOC_ID9      = $request->doc_id9;
            $nuevocontable->FORMATO_ID9  = $request->formato_id9;
            $nuevocontable->OSC_D9       = $file9;        
            $nuevocontable->NUM_ID9      = 1;   // 1     $request->num_id9;
            $nuevocontable->PER_ID9      = 5;   // Anual $request->per_id9;        
            //$nuevocontable->OSC_EDO9   = $request->osc_edo9;        

            $nuevocontable->DOC_ID10     = $request->doc_id10;
            //$nuevocontable->FORMATO_ID10= $request->formato_id10;
            $nuevocontable->OSC_D10      = $file10;        
            $nuevocontable->NUM_ID10     = 1;  // 1       $request->num_id10;
            $nuevocontable->PER_ID10     = 1;  // Mensual $request->per_id10;        
            //$nuevocontable->OSC_EDO10  = $request->osc_edo10; 

            $nuevocontable->DOC_ID11     = $request->doc_id11;
            $nuevocontable->FORMATO_ID11 = $request->formato_id11;
            $nuevocontable->OSC_D11      = $file11;        
            $nuevocontable->NUM_ID11     = $request->num_id11;
            $nuevocontable->PER_ID11     = $request->per_id11;        
            $nuevocontable->OSC_EDO11    = $request->osc_edo11;         

            $nuevocontable->DOC_ID1002   = $request->doc_id1002;
            $nuevocontable->OSC_D1002    = $file1002;        
            $nuevocontable->NUM_ID1002   = 1;  // 1       $request->num_id1002;
            $nuevocontable->PER_ID1002   = 1;  // Mensual $request->per_id1002; 

            $nuevocontable->DOC_ID1003   = $request->doc_id1003;
            $nuevocontable->OSC_D1003    = $file1003;        
            $nuevocontable->NUM_ID1003   = 1;  // 1       $request->num_id1002;
            $nuevocontable->PER_ID1003   = 1;  // Mensual $request->per_id1002;

            $nuevocontable->DOC_ID1004   = $request->doc_id1004;
            $nuevocontable->OSC_D1004    = $file1004;        
            $nuevocontable->NUM_ID1004   = 1;  // 1       $request->num_id1002;
            $nuevocontable->PER_ID1004   = 1;  // Mensual $request->per_id1002;

            $nuevocontable->DOC_ID1005   = $request->doc_id1005;
            $nuevocontable->OSC_D1005    = $file1005;        
            $nuevocontable->NUM_ID1005   = 1;  // 1       $request->num_id1002;
            $nuevocontable->PER_ID1005   = 1;  // Mensual $request->per_id1002;

            $nuevocontable->DOC_ID1006   = $request->doc_id1006;
            $nuevocontable->OSC_D1006    = $file1006;        
            $nuevocontable->NUM_ID1006   = 1;  // 1       $request->num_id1002;
            $nuevocontable->PER_ID1006   = 1;  // Mensual $request->per_id1002;

            $nuevocontable->DOC_ID1007   = $request->doc_id1007;
            $nuevocontable->OSC_D1007    = $file1007;        
            $nuevocontable->NUM_ID1007   = 1;  // 1       $request->num_id1002;
            $nuevocontable->PER_ID1007   = 1;  // Mensual $request->per_id1002;

            $nuevocontable->DOC_ID1008   = $request->doc_id1008;
            $nuevocontable->OSC_D1008    = $file1008;        
            $nuevocontable->NUM_ID1008   = 1;  // 1       $request->num_id1002;
            $nuevocontable->PER_ID1008   = 1;  // Mensual $request->per_id1002;

            $nuevocontable->DOC_ID1009   = $request->doc_id1009;
            $nuevocontable->OSC_D1009    = $file1009;        
            $nuevocontable->NUM_ID1009   = 1;  // 1       $request->num_id1002;
            $nuevocontable->PER_ID1009   = 1;  // Mensual $request->per_id1002;

            $nuevocontable->DOC_ID1010   = $request->doc_id1010;
            $nuevocontable->OSC_D1010    = $file1010;        
            $nuevocontable->NUM_ID1010   = 1;  // 1       $request->num_id1002;
            $nuevocontable->PER_ID1010   = 1;  // Mensual $request->per_id1002;

            $nuevocontable->DOC_ID1011   = $request->doc_id1011;
            $nuevocontable->OSC_D1011    = $file1011;        
            $nuevocontable->NUM_ID1011   = 1;  // 1       $request->num_id1002;
            $nuevocontable->PER_ID1011   = 1;  // Mensual $request->per_id1002;

            $nuevocontable->DOC_ID1012   = $request->doc_id1012;
            $nuevocontable->OSC_D1012    = $file1012;        
            $nuevocontable->NUM_ID1012   = 1;  // 1       $request->num_id1002;
            $nuevocontable->PER_ID1012   = 1;  // Mensual $request->per_id1002;                                                                        

            $nuevocontable->PREG_001     = $request->preg_001; 
            $nuevocontable->PREG_002     = $request->preg_002;
            $nuevocontable->PREG_003     = $preg_003; 
            $nuevocontable->PREG_004     = $preg_004;
            $nuevocontable->PREG_005     = $preg_005; 
            $nuevocontable->PREG_006     = $preg_006;           

            $nuevocontable->PREG_1002    = $preg_1002;                                    
            $nuevocontable->PREG_1003    = $preg_1003;
            $nuevocontable->PREG_1004    = $preg_1004;
            $nuevocontable->PREG_1005    = $preg_1005;
            $nuevocontable->PREG_1006    = $preg_1006;
            $nuevocontable->PREG_1007    = $preg_1007;
            $nuevocontable->PREG_1008    = $preg_1008;
            $nuevocontable->PREG_1009    = $preg_1009;
            $nuevocontable->PREG_1010    = $preg_1010;
            $nuevocontable->PREG_1011    = $preg_1011;
            $nuevocontable->PREG_1012    = $preg_1012;

            $nuevocontable->IP           = $ip;
            $nuevocontable->LOGIN        = $nombre;         // Usuario ;
            $nuevocontable->save();

            if($nuevocontable->save() == true){
                toastr()->success('requisitos administrativos, otros requisitos registrados.','ok!',['positionClass' => 'toast-bottom-right']);

                /************ Bitacora inicia *************************************/ 
                setlocale(LC_TIME, "spanish");        
                $xip          = session()->get('ip');
                $xperiodo_id  = (int)date('Y');
                $xprograma_id = 1;
                $xmes_id      = (int)date('m');
                $xproceso_id  =         3;
                $xfuncion_id  =      3004;
                $xtrx_id      =        94;    //alta
                $regbitacora = regBitacoraModel::select('PERIODO_ID', 'PROGRAMA_ID', 'MES_ID', 'PROCESO_ID', 
                                           'FUNCION_ID','TRX_ID','FOLIO','NO_VECES','FECHA_REG','IP','LOGIN',
                                           'FECHA_M', 'IP_M', 'LOGIN_M')
                               ->where(['PERIODO_ID' => $xperiodo_id, 'PROGRAMA_ID' => $xprograma_id, 
                                        'MES_ID' => $xmes_id,'PROCESO_ID' => $xproceso_id, 'FUNCION_ID' => $xfuncion_id, 
                                        'TRX_ID' => $xtrx_id, 'FOLIO' => $osc_folio])
                               ->get();
                if($regbitacora->count() <= 0){              // Alta
                    $nuevoregBitacora = new regBitacoraModel();              
                    $nuevoregBitacora->PERIODO_ID = $xperiodo_id;    // Año de transaccion 
                    $nuevoregBitacora->PROGRAMA_ID= $xprograma_id;   // Proyecto JAPEM 
                    $nuevoregBitacora->MES_ID     = $xmes_id;        // Mes de transaccion
                    $nuevoregBitacora->PROCESO_ID = $xproceso_id;    // Proceso de apoyo
                    $nuevoregBitacora->FUNCION_ID = $xfuncion_id;    // Funcion del modelado de procesos 
                    $nuevoregBitacora->TRX_ID     = $xtrx_id;        // Actividad del modelado de procesos
                    $nuevoregBitacora->FOLIO      = $osc_folio;      // Folio    
                    $nuevoregBitacora->NO_VECES   = 1;               // Numero de veces            
                    $nuevoregBitacora->IP         = $ip;             // IP
                    $nuevoregBitacora->LOGIN      = $nombre;         // Usuario 
                    $nuevoregBitacora->save();
                    if($nuevoregBitacora->save() == true)
                       toastr()->success('Trx otros requisitos administrativos dada de alta en bitacora.','¡Ok!',['positionClass' => 'toast-bottom-right']);
                    else
                       toastr()->error('Error trx otros requisitos administrativos al dar de alta en bitacora. Por favor volver a interlo.','Ups!',['positionClass' => 'toast-bottom-right']);
                }else{                   
                    //*********** Obtine el no. de veces *****************************
                    $xno_veces = regBitacoraModel::where(['PERIODO_ID' => $xperiodo_id, 'PROGRAMA_ID' => $xprograma_id, 
                                                          'MES_ID' => $xmes_id, 'PROCESO_ID' => $xproceso_id, 
                                                          'FUNCION_ID' => $xfuncion_id, 'TRX_ID' => $xtrx_id, 
                                                          'FOLIO' => $osc_folio])
                                 ->max('NO_VECES');
                    $xno_veces = $xno_veces+1;                        
                    //*********** Termina de obtener el no de veces *****************************               
                    $regbitacora = regBitacoraModel::select('NO_VECES','IP_M','LOGIN_M','FECHA_M')
                                   ->where(['PERIODO_ID' => $xperiodo_id, 'PROGRAMA_ID' => $xprograma_id, 
                                            'MES_ID' => $xmes_id, 'PROCESO_ID' => $xproceso_id, 
                                            'FUNCION_ID' => $xfuncion_id,'TRX_ID' => $xtrx_id,
                                            'FOLIO' => $osc_folio])
                                   ->update([
                                             'NO_VECES' => $regbitacora->NO_VECES = $xno_veces,
                                             'IP_M'     => $regbitacora->IP       = $ip,
                                             'LOGIN_M'  => $regbitacora->LOGIN_M  = $nombre,
                                             'FECHA_M'  => $regbitacora->FECHA_M  = date('Y/m/d')  //date('d/m/Y')
                                            ]);
                    toastr()->success('Trx de otros requisitos administrativos actualizada en Bitacora.','¡Ok!',['positionClass' => 'toast-bottom-right']);
                }   /************ Bitacora termina *************************************/ 
                
                //return redirect()->route('nuevocontable');
                //return view('sicinar.plandetrabajo.nuevoPlan',compact('unidades','nombre','usuario','rango','preguntas','apartados'));
            }else{
                toastr()->error('Error en trx otros requisitos administrativos en bitacora. Por favor volver a interlo.','Ups!',['positionClass' => 'toast-bottom-right']);
                //return back();
                //return redirect()->route('nuevocontable');
            }   //******************** Termina la alta ************************/ 

        }else{
            toastr()->error('Ya existe otro requisito contable.','Por favor editar, lo siento!',['positionClass' => 'toast-bottom-right']);
            //return redirect()->route('nuevocontable');
        }   // Termian If de busqueda ****************

        return redirect()->route('verReqc');
    }


    /****************** Editar registro  **********/
    public function actionEditarReqc($id){
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
        $regformatos  = regFormatosModel::select('FORMATO_ID','FORMATO_DESC')->get();
        $regosc       = regOscModel::select('OSC_ID', 'OSC_DESC','OSC_STATUS')->orderBy('OSC_DESC','asc')
                        ->get();
        $regcontable  = regReqContablesModel::select('OSC_FOLIO','OSC_ID','PERIODO_ID',
                        'DOC_ID6' ,'FORMATO_ID6' ,'OSC_D6' ,'PER_ID6' ,'NUM_ID6' ,'OSC_EDO6',
                        'DOC_ID7' ,'FORMATO_ID7' ,'OSC_D7' ,'PER_ID7' ,'NUM_ID7' ,'OSC_EDO7',
                        'DOC_ID8' ,'FORMATO_ID8' ,'OSC_D8' ,'PER_ID8' ,'NUM_ID8' ,'OSC_EDO8',
                        'DOC_ID9' ,'FORMATO_ID9' ,'OSC_D9' ,'PER_ID9' ,'NUM_ID9' ,'OSC_EDO9',                        
                        'DOC_ID10','FORMATO_ID10','OSC_D10','PER_ID10','NUM_ID10','OSC_EDO10',                        
                        'DOC_ID11','FORMATO_ID11','OSC_D11','PER_ID11','NUM_ID11','OSC_EDO11',
                            'DOC_ID1002','OSC_D1002','PER_ID1002','NUM_ID1002',
                            'DOC_ID1003','OSC_D1003','PER_ID1003','NUM_ID1003',
                            'DOC_ID1004','OSC_D1004','PER_ID1004','NUM_ID1004',
                            'DOC_ID1005','OSC_D1005','PER_ID1005','NUM_ID1005',
                            'DOC_ID1006','OSC_D1006','PER_ID1006','NUM_ID1006',
                            'DOC_ID1007','OSC_D1007','PER_ID1007','NUM_ID1007',
                            'DOC_ID1008','OSC_D1008','PER_ID1008','NUM_ID1008',
                            'DOC_ID1009','OSC_D1009','PER_ID1009','NUM_ID1009',
                            'DOC_ID1010','OSC_D1010','PER_ID1010','NUM_ID1010',
                            'DOC_ID1011','OSC_D1011','PER_ID1011','NUM_ID1011',
                            'DOC_ID1012','OSC_D1012','PER_ID1012','NUM_ID1012',
                            'PREG_1002','PREG_1003','PREG_1004','PREG_1005','PREG_1006',
                            'PREG_1007','PREG_1008','PREG_1009','PREG_1010','PREG_1011','PREG_1012',                        
                        'PREG_001','PREG_002','PREG_003','PREG_004','PREG_005','PREG_006',
                        'OSC_STATUS','FECREG','IP','LOGIN','FECHA_M','IP_M','LOGIN_M')
                        ->where('OSC_FOLIO', $id)
                        ->first();
        if($regcontable->count() <= 0){
            toastr()->error('No existe requisitos administrativos.','Lo siento!',['positionClass' => 'toast-bottom-right']);
            //return redirect()->route('nuevocontable');
        }
        return view('sicinar.requisitos.editarReqc',compact('nombre','usuario','regosc','regcontable','regnumeros', 'regperiodos','regperiodicidad','regformatos'));

    }
    
    public function actionActualizarReqc(reqcontablesRequest $request, $id){
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
        $regcontable = regReqContablesModel::where('OSC_FOLIO',$id);
        if($regcontable->count() <= 0)
            toastr()->error('No existen requisitos administrativos.','¡Por favor volver a intentar!',['positionClass' => 'toast-bottom-right']);
        else{        
            //****************** Actualizar **************************/
            //echo "Escribió en el campo de texto 1: " .'-'. $request->osc_d9 .'-'. "<br><br>"; 
            //echo "Escribió en el campo de texto 1: " . $request->osc_d9 . "<br><br>"; 
            //Comprobar  si el campo foto1 tiene un archivo asignado:
            $name6 =null;        
            if($request->hasFile('osc_d6')){
                $name6 = $id.'_'.$request->file('osc_d6')->getClientOriginalName(); 
                //sube el archivo a la carpeta del servidor public/images/
                $request->file('osc_d6')->move(public_path().'/images/', $name6);
            }            
            $name7 =null;        
            if($request->hasFile('osc_d7')){
                $name7 = $id.'_'.$request->file('osc_d7')->getClientOriginalName(); 
                //sube el archivo a la carpeta del servidor public/images/
                $request->file('osc_d7')->move(public_path().'/images/', $name7);
            }            
            $name8 =null;        
            if($request->hasFile('osc_d8')){
                $name8 = $id.'_'.$request->file('osc_d8')->getClientOriginalName(); 
                //sube el archivo a la carpeta del servidor public/images/
                $request->file('osc_d8')->move(public_path().'/images/', $name8);
            }            
            $name9 =null;        
            if($request->hasFile('osc_d9')){
                echo "Escribió en el campo de texto 9: " .'-'. $request->osc_d9 .'-'. "<br><br>"; 
                $name9 = $id.'_'.$request->file('osc_d9')->getClientOriginalName(); 
                //sube el archivo a la carpeta del servidor public/images/
                $request->file('osc_d9')->move(public_path().'/images/', $name9);
            }
            $name10 =null;        
            if($request->hasFile('osc_d10')){
                echo "Escribió en el campo de texto 10: " .'-'. $request->osc_d10 .'-'. "<br><br>"; 
                $name10 = $id.'_'.$request->file('osc_d10')->getClientOriginalName(); 
                //sube el archivo a la carpeta del servidor public/images/
                $request->file('osc_d10')->move(public_path().'/images/', $name10);
            }            
            $name11 =null;        
            if($request->hasFile('osc_d11')){
                echo "Escribió en el campo de texto 11: " .'-'. $request->osc_d11 .'-'. "<br><br>"; 
                $name11 = $id.'_'.$request->file('osc_d11')->getClientOriginalName(); 
                //sube el archivo a la carpeta del servidor public/images/
                $request->file('osc_d11')->move(public_path().'/images/', $name11);
            }            

            // ************* Actualizamos registro **********************************/
            $preg_003  =str_replace(",", "", $request->preg_003);
            $preg_004  =str_replace(",", "", $request->preg_004);
            $preg_005  =str_replace(",", "", $request->preg_005); 
            $preg_006  =str_replace(",", "", $request->preg_006);            
            $preg_1002 =str_replace(",", "", $request->preg_1002);
            $preg_1003 =str_replace(",", "", $request->preg_1003);
            $preg_1004 =str_replace(",", "", $request->preg_1004);
            $preg_1005 =str_replace(",", "", $request->preg_1005);            
            $preg_1006 =str_replace(",", "", $request->preg_1006);
            $preg_1007 =str_replace(",", "", $request->preg_1007);
            $preg_1008 =str_replace(",", "", $request->preg_1008);
            $preg_1009 =str_replace(",", "", $request->preg_1009);             
            $preg_1010 =str_replace(",", "", $request->preg_1010); 
            $preg_1011 =str_replace(",", "", $request->preg_1011); 
            $preg_1012 =str_replace(",", "", $request->preg_1012);            

            $regcontable = regReqContablesModel::where('OSC_FOLIO',$id)        
                           ->update([                
                                     //'PERIODO_ID'   => $request->periodo_id,

                                     'DOC_ID6'      => $request->doc_id6,
                                     'FORMATO_ID6'  => $request->formato_id6,                            
                                     //'OSC_D6'     => $name6,                                                       
                                     'PER_ID6'      => $request->per_id6,
                                     'NUM_ID6'      => $request->num_id6,                
                                     'OSC_EDO6'     => $request->osc_edo6,

                                     'DOC_ID7'      => $request->doc_id7,
                                     'FORMATO_ID7'  => $request->formato_id7,                            
                                     //'OSC_D7'     => $name7,                                                       
                                     //'PER_ID7'    => $request->per_id7,
                                     //'NUM_ID7'    => $request->num_id7,                
                                     //'OSC_EDO7'   => $request->osc_edo7,

                                     'DOC_ID8'      => $request->doc_id8,
                                     'FORMATO_ID8'  => $request->formato_id8,                            
                                     //'OSC_D8'     => $name8,                                                       
                                     //'PER_ID8'    => $request->per_id8,
                                     //'NUM_ID8'    => $request->num_id8,                
                                     //'OSC_EDO8'   => $request->osc_edo8,

                                     'DOC_ID9'      => $request->doc_id9,
                                     'FORMATO_ID9'  => $request->formato_id9,                            
                                     //'OSC_D9'     => $name9,                                                       
                                     //'PER_ID9'    => $request->per_id9,
                                     //'NUM_ID9'    => $request->num_id9,                
                                     //'OSC_EDO9'   => $request->osc_edo9,
                                    
                                     'DOC_ID10'     => $request->doc_id10,
                                     'FORMATO_ID10' => $request->formato_id10,                                          
                                     //'OSC_D10'    => $name10,              
                                     //'PER_ID10'   => $request->per_id10,
                                     //'NUM_ID10'   => $request->num_id10,                
                                     //'OSC_EDO10'  => $request->osc_edo10,
                                     
                                     'DOC_ID11'     => $request->doc_id11,
                                     'FORMATO_ID11' => $request->formato_id11, 
                                     //'OSC_D11'    => $name11,        
                                     'PER_ID11'     => $request->per_id11,
                                     'NUM_ID11'     => $request->num_id11,                
                                     'OSC_EDO11'    => $request->osc_edo11,

                                     'DOC_ID1002'   => $request->doc_id1002, 
                                     'DOC_ID1003'   => $request->doc_id1003, 
                                     'DOC_ID1004'   => $request->doc_id1004,
                                     'DOC_ID1005'   => $request->doc_id1005, 
                                     'DOC_ID1006'   => $request->doc_id1006, 
                                     'DOC_ID1007'   => $request->doc_id1007,
                                     'DOC_ID1008'   => $request->doc_id1008, 
                                     'DOC_ID1009'   => $request->doc_id1009, 
                                     'DOC_ID1010'   => $request->doc_id1010,
                                     'DOC_ID1011'   => $request->doc_id1011, 
                                     'DOC_ID1012'   => $request->doc_id1012, 

                                     'PREG_001'     => $request->preg_001,
                                     'PREG_002'     => $request->preg_002,
                                     'PREG_003'     => $preg_003,
                                     'PREG_004'     => $preg_004,
                                     'PREG_005'     => $preg_005,
                                     'PREG_006'     => $preg_006,   
                                     'PREG_1002'    => $preg_1002,
                                     'PREG_1003'    => $preg_1003,
                                     'PREG_1004'    => $preg_1004,
                                     'PREG_1005'    => $preg_1005,
                                     'PREG_1006'    => $preg_1006,
                                     'PREG_1007'    => $preg_1007,
                                     'PREG_1008'    => $preg_1008,
                                     'PREG_1009'    => $preg_1009,
                                     'PREG_1010'    => $preg_1010,
                                     'PREG_1011'    => $preg_1011,
                                     'PREG_1012'    => $preg_1012,                                                                                                              

                                     //'OSC_STATUS'   => $request->osc_status,
                                     'IP_M'         => $ip,
                                     'LOGIN_M'      => $nombre,
                                     'FECHA_M'      => date('Y/m/d')    //date('d/m/
                                    ]);
            toastr()->success('Otros requisitos administrativos actualizado.','¡Ok!',['positionClass' => 'toast-bottom-right']);

            /************ Bitacora inicia *************************************/ 
            setlocale(LC_TIME, "spanish");        
            $xip          = session()->get('ip');
            $xperiodo_id  = (int)date('Y');
            $xprograma_id = 1;
            $xmes_id      = (int)date('m');
            $xproceso_id  =         3;
            $xfuncion_id  =      3004;
            $xtrx_id      =        95;    //Actualizar         
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
                    toastr()->success('Trx de otros requisitos dada de alta en Bitacora.','¡Ok!',['positionClass' => 'toast-bottom-right']);
                else
                    toastr()->error('Error de trxt de otros requisitos al dar de alta en bitacora. Por favor volver a interlo.','Ups!',['positionClass' => 'toast-bottom-right']);
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
                toastr()->success('Trx de otros requsitos actualizada en Bitacora.','¡Ok!',['positionClass' => 'toast-bottom-right']);
            }   /************ Bitacora termina *************************************/         
        }       /************ Termina actualizar ***********************************/
        return redirect()->route('verReqc');
        
    }

    /****************** Editar Enero quotas de 5 al millar **********/
    public function actionEditarReqc6($id){
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
        $regformatos  = regFormatosModel::select('FORMATO_ID','FORMATO_DESC')->get();                               
        $regosc       = regOscModel::select('OSC_ID', 'OSC_DESC','OSC_STATUS')->orderBy('OSC_DESC','asc')
                        ->get();
        $regcontable  = regReqContablesModel::select('OSC_FOLIO','OSC_ID','PERIODO_ID',
                        'DOC_ID6' ,'FORMATO_ID6' ,'OSC_D6' ,'PER_ID6' ,'NUM_ID6' ,'OSC_EDO6',
                        'DOC_ID7' ,'FORMATO_ID7' ,'OSC_D7' ,'PER_ID7' ,'NUM_ID7' ,'OSC_EDO7',
                        'DOC_ID8' ,'FORMATO_ID8' ,'OSC_D8' ,'PER_ID8' ,'NUM_ID8' ,'OSC_EDO8',
                        'DOC_ID9' ,'FORMATO_ID9' ,'OSC_D9' ,'PER_ID9' ,'NUM_ID9' ,'OSC_EDO9',                        
                        'DOC_ID10','FORMATO_ID10','OSC_D10','PER_ID10','NUM_ID10','OSC_EDO10',                        
                        'DOC_ID11','FORMATO_ID11','OSC_D11','PER_ID11','NUM_ID11','OSC_EDO11',
                        'DOC_ID1002','OSC_D1002','PER_ID1002','NUM_ID1002',
                        'DOC_ID1003','OSC_D1003','PER_ID1003','NUM_ID1003',
                        'DOC_ID1004','OSC_D1004','PER_ID1004','NUM_ID1004',
                        'DOC_ID1005','OSC_D1005','PER_ID1005','NUM_ID1005',
                        'DOC_ID1006','OSC_D1006','PER_ID1006','NUM_ID1006',
                        'DOC_ID1007','OSC_D1007','PER_ID1007','NUM_ID1007',
                        'DOC_ID1008','OSC_D1008','PER_ID1008','NUM_ID1008',
                        'DOC_ID1009','OSC_D1009','PER_ID1009','NUM_ID1009',
                        'DOC_ID1010','OSC_D1010','PER_ID1010','NUM_ID1010',
                        'DOC_ID1011','OSC_D1011','PER_ID1011','NUM_ID1011',
                        'DOC_ID1012','OSC_D1012','PER_ID1012','NUM_ID1012',
                        'PREG_1002','PREG_1003','PREG_1004','PREG_1005','PREG_1006',
                        'PREG_1007','PREG_1008','PREG_1009','PREG_1010','PREG_1011','PREG_1012',                        
                        'PREG_001','PREG_002','PREG_003','PREG_004','PREG_005','PREG_006',                        
                        'OSC_STATUS','FECREG','IP','LOGIN','FECHA_M','IP_M','LOGIN_M')
                        ->where('OSC_FOLIO', $id)
                        ->first();
        if($regcontable->count() <= 0){
            toastr()->error('No existe requisitos administrativos.','Lo siento!',['positionClass' => 'toast-bottom-right']);
            //return redirect()->route('nuevocontable');
        }
        return view('sicinar.requisitos.editarReqc6',compact('nombre','usuario','regosc','regcontable','regnumeros', 'regperiodos','regperiodicidad','regformatos'));
    }

    public function actionActualizarReqc6(reqcontablesRequest $request, $id){
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
        $regcontable = regReqContablesModel::where('OSC_FOLIO', $id);
        if($regcontable->count() <= 0)
            toastr()->error('No existe archivo PDF6.','¡Por favor volver a intentar!',['positionClass' => 'toast-bottom-right']);
        else{        

            //echo "Escribió en el campo de texto 1: " .'-'. $request->osc_d9 .'-'. "<br><br>"; 
            //echo "Escribió en el campo de texto 1: " . $request->osc_d9 . "<br><br>"; 
            //Comprobar  si el campo foto1 tiene un archivo asignado:
            $name6 =null;
            if($request->hasFile('osc_d6')){
                echo "Escribió en el campo de texto 6: " .'-'. $request->osc_d6 .'-'. "<br><br>"; 
                $name6 = $id.'_'.$request->file('osc_d6')->getClientOriginalName(); 
                //sube el archivo a la carpeta del servidor public/images/
                $request->file('osc_d6')->move(public_path().'/images/', $name6);

                // ************* Actualizamos registro **********************************
                $regcontable = regReqContablesModel::where('OSC_FOLIO',$id)        
                           ->update([   
                                     'DOC_ID6'    => $request->doc_id6,
                                     'FORMATO_ID6'=> $request->formato_id6,             
                                     'osc_D6'     => $name6,                  
                                     'PER_ID6'    => $request->per_id6,
                                     'NUM_ID6'    => $request->num_id6,                
                                     'OSC_EDO6'   => $request->osc_edo6,

                                     'PREG_001'   => $request->preg_001,
                                     'PREG_002'   => $request->preg_002,                     

                                     'IP_M'        => $ip,
                                     'LOGIN_M'     => $nombre,
                                     'FECHA_M'     => date('Y/m/d')    //date('d/m/Y')                                
                                     ]);
                toastr()->success('archivo contable 6 actualizado.','¡Ok!',['positionClass' => 'toast-bottom-right']);
            }else{
                // ************* Actualizamos registro **********************************
                $regcontable = regReqContablesModel::where('OSC_FOLIO',$id)        
                           ->update([   
                                     'DOC_ID6'    => $request->doc_id6,
                                     'FORMATO_ID6'=> $request->formato_id6,             
                                     //'OSC_D6'   => $name6,                  
                                     'PER_ID6'    => $request->per_id6,
                                     'NUM_ID6'    => $request->num_id6,                
                                     'OSC_EDO6'   => $request->osc_edo6,

                                     'PREG_001'   => $request->preg_001,
                                     'PREG_002'   => $request->preg_002,

                                     'IP_M'        => $ip,
                                     'LOGIN_M'     => $nombre,
                                     'FECHA_M'     => date('Y/m/d')    //date('d/m/Y')                                
                                     ]);                
                toastr()->success('archivo contable 6 actualizado.','¡Ok!',['positionClass' => 'toast-bottom-right']);
            }

            /************ Bitacora inicia *************************************/ 
            setlocale(LC_TIME, "spanish");        
            $xip          = session()->get('ip');
            $xperiodo_id  = (int)date('Y');
            $xprograma_id = 1;
            $xmes_id      = (int)date('m');
            $xproceso_id  =         3;
            $xfuncion_id  =      3004;
            $xtrx_id      =        95;    //Actualizar         
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

        return redirect()->route('verReqc');
        
    }    

        /****************** Editar requisitos administrativos **********/
    public function actionEditarReqc7($id){
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
        $regformatos  = regFormatosModel::select('FORMATO_ID','FORMATO_DESC')->get();                               
        $regosc       = regOscModel::select('OSC_ID', 'OSC_DESC','OSC_STATUS')->orderBy('OSC_DESC','asc')
                        ->get();
        $regcontable  = regReqContablesModel::select('OSC_FOLIO','OSC_ID','PERIODO_ID',
                        'DOC_ID6' ,'FORMATO_ID6' ,'OSC_D6' ,'PER_ID6' ,'NUM_ID6' ,'OSC_EDO6',
                        'DOC_ID7' ,'FORMATO_ID7' ,'OSC_D7' ,'PER_ID7' ,'NUM_ID7' ,'OSC_EDO7',
                        'DOC_ID8' ,'FORMATO_ID8' ,'OSC_D8' ,'PER_ID8' ,'NUM_ID8' ,'OSC_EDO8',
                        'DOC_ID9' ,'FORMATO_ID9' ,'OSC_D9' ,'PER_ID9' ,'NUM_ID9' ,'OSC_EDO9',                        
                        'DOC_ID10','FORMATO_ID10','OSC_D10','PER_ID10','NUM_ID10','OSC_EDO10',                        
                        'DOC_ID11','FORMATO_ID11','OSC_D11','PER_ID11','NUM_ID11','OSC_EDO11',
                        'DOC_ID1002','OSC_D1002','PER_ID1002','NUM_ID1002',
                        'DOC_ID1003','OSC_D1003','PER_ID1003','NUM_ID1003',
                        'DOC_ID1004','OSC_D1004','PER_ID1004','NUM_ID1004',
                        'DOC_ID1005','OSC_D1005','PER_ID1005','NUM_ID1005',
                        'DOC_ID1006','OSC_D1006','PER_ID1006','NUM_ID1006',
                        'DOC_ID1007','OSC_D1007','PER_ID1007','NUM_ID1007',
                        'DOC_ID1008','OSC_D1008','PER_ID1008','NUM_ID1008',
                        'DOC_ID1009','OSC_D1009','PER_ID1009','NUM_ID1009',
                        'DOC_ID1010','OSC_D1010','PER_ID1010','NUM_ID1010',
                        'DOC_ID1011','OSC_D1011','PER_ID1011','NUM_ID1011',
                        'DOC_ID1012','OSC_D1012','PER_ID1012','NUM_ID1012',
                        'PREG_1002','PREG_1003','PREG_1004','PREG_1005','PREG_1006',
                        'PREG_1007','PREG_1008','PREG_1009','PREG_1010','PREG_1011','PREG_1012',                          
                        'PREG_001','PREG_002','PREG_003','PREG_004','PREG_005','PREG_006',                        
                        'OSC_STATUS','FECREG','IP','LOGIN','FECHA_M','IP_M','LOGIN_M')
                        ->where('OSC_FOLIO', $id)
                        ->first();
        if($regcontable->count() <= 0){
            toastr()->error('No existe otros requisitos administrativos.','Lo siento!',['positionClass' => 'toast-bottom-right']);
            //return redirect()->route('nuevocontable');
        }
        return view('sicinar.requisitos.editarReqc7',compact('nombre','usuario','regosc','regcontable','regnumeros', 'regperiodos','regperiodicidad','regformatos'));
    }

    public function actionActualizarReqc7(reqcontables7Request $request, $id){
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
        $regcontable = regReqContablesModel::where('OSC_FOLIO',$id);
        if($regcontable->count() <= 0)
            toastr()->error('No existe archivo PDF de enero.','¡Por favor volver a intentar!',['positionClass' => 'toast-bottom-right']);
        else{        

            //echo "Escribió en el campo de texto 1: " .'-'. $request->osc_d9 .'-'. "<br><br>"; 
            //echo "Escribió en el campo de texto 1: " . $request->osc_d9 . "<br><br>"; 
            //Comprobar  si el campo foto1 tiene un archivo asignado:
            $preg_003  =str_replace(",", "", $request->preg_003);
            $preg_004  =str_replace(",", "", $request->preg_004);
            $preg_005  =str_replace(",", "", $request->preg_005); 

            $name7 =null;
            if($request->hasFile('osc_d7')){
                echo "Escribió en el campo de texto 7: " .'-'. $request->osc_d7 .'-'. "<br><br>"; 
                $name7 = $id.'_'.$request->file('osc_d7')->getClientOriginalName(); 
                //sube el archivo a la carpeta del servidor public/images/
                $request->file('osc_d7')->move(public_path().'/images/', $name7);

                // ************* Actualizamos registro **********************************
                $regcontable = regReqContablesModel::where('OSC_FOLIO',$id)        
                           ->update([   
                                     'DOC_ID7'    => $request->doc_id7,
                                     'FORMATO_ID7'=> $request->formato_id7,             
                                     'OSC_D7'     => $name7,                  
                                     //'PER_ID7'  => $request->per_id7,
                                     //'NUM_ID7'  => $request->num_id7,                
                                     //'OSC_EDO7' => $request->osc_edo7,

                                     'PREG_003'   => $preg_003,
                                     'PREG_004'   => $preg_004,                                     
                                     'PREG_005'   => $preg_005,

                                     'IP_M'       => $ip,
                                     'LOGIN_M'    => $nombre,
                                     'FECHA_M'    => date('Y/m/d')    //date('d/m/Y')                                
                                     ]);
                toastr()->success('archivo 7 PDF actualizado.','¡Ok!',['positionClass' => 'toast-bottom-right']);
            }else{
                // ************* Actualizamos registro **********************************
                $regcontable = regReqContablesModel::where('OSC_FOLIO',$id)        
                           ->update([   
                                     'DOC_ID7'    => $request->doc_id7,
                                     'FORMATO_ID7'=> $request->formato_id7,             
                                     //'OSC_D7'   => $name7,                  
                                     //'PER_ID7'  => $request->per_id7,
                                     //'NUM_ID7'  => $request->num_id7,                
                                     //'OSC_EDO7' => $request->osc_edo7,

                                     'PREG_003'   => $preg_003,
                                     'PREG_004'   => $preg_004, 
                                     'PREG_005'   => $preg_005,                                

                                     'IP_M'        => $ip,
                                     'LOGIN_M'     => $nombre,
                                     'FECHA_M'     => date('Y/m/d')    //date('d/m/Y')                                
                                     ]);                
                toastr()->success('archivo 7 PDF actualizado.','¡Ok!',['positionClass' => 'toast-bottom-right']);
            }

            /************ Bitacora inicia *************************************/ 
            setlocale(LC_TIME, "spanish");        
            $xip          = session()->get('ip');
            $xperiodo_id  = (int)date('Y');
            $xprograma_id = 1;
            $xmes_id      = (int)date('m');
            $xproceso_id  =         3;
            $xfuncion_id  =      3004;
            $xtrx_id      =        95;    //Actualizar         
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

        return redirect()->route('verReqc');
        
    }    

    /****************** Editar requisitos administrativos **********/
    public function actionEditarReqc8($id){
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
        $regosc       = regOscModel::select('OSC_ID', 'OSC_DESC','OSC_STATUS')->orderBy('OSC_DESC','asc')
                        ->get();
        $regcontable  = regReqContablesModel::select('OSC_FOLIO','OSC_ID','PERIODO_ID',
                        'DOC_ID6' ,'FORMATO_ID6' ,'OSC_D6' ,'PER_ID6' ,'NUM_ID6' ,'OSC_EDO6',
                        'DOC_ID7' ,'FORMATO_ID7' ,'OSC_D7' ,'PER_ID7' ,'NUM_ID7' ,'OSC_EDO7',
                        'DOC_ID8' ,'FORMATO_ID8' ,'OSC_D8' ,'PER_ID8' ,'NUM_ID8' ,'OSC_EDO8',
                        'DOC_ID9' ,'FORMATO_ID9' ,'OSC_D9' ,'PER_ID9' ,'NUM_ID9' ,'OSC_EDO9',                        
                        'DOC_ID10','FORMATO_ID10','OSC_D10','PER_ID10','NUM_ID10','OSC_EDO10',                        
                        'DOC_ID11','FORMATO_ID11','OSC_D11','PER_ID11','NUM_ID11','OSC_EDO11',
                        'DOC_ID1002','OSC_D1002','PER_ID1002','NUM_ID1002',
                        'DOC_ID1003','OSC_D1003','PER_ID1003','NUM_ID1003',
                        'DOC_ID1004','OSC_D1004','PER_ID1004','NUM_ID1004',
                        'DOC_ID1005','OSC_D1005','PER_ID1005','NUM_ID1005',
                        'DOC_ID1006','OSC_D1006','PER_ID1006','NUM_ID1006',
                        'DOC_ID1007','OSC_D1007','PER_ID1007','NUM_ID1007',
                        'DOC_ID1008','OSC_D1008','PER_ID1008','NUM_ID1008',
                        'DOC_ID1009','OSC_D1009','PER_ID1009','NUM_ID1009',
                        'DOC_ID1010','OSC_D1010','PER_ID1010','NUM_ID1010',
                        'DOC_ID1011','OSC_D1011','PER_ID1011','NUM_ID1011',
                        'DOC_ID1012','OSC_D1012','PER_ID1012','NUM_ID1012',
                        'PREG_1002','PREG_1003','PREG_1004','PREG_1005','PREG_1006',
                        'PREG_1007','PREG_1008','PREG_1009','PREG_1010','PREG_1011','PREG_1012',                        
                        'PREG_001','PREG_002','PREG_003','PREG_004','PREG_005','PREG_006',                        
                        'OSC_STATUS','FECREG','IP','LOGIN','FECHA_M','IP_M','LOGIN_M')
                        ->where('OSC_FOLIO', $id)
                        ->first();
        if($regcontable->count() <= 0){
            toastr()->error('No existe requisito contable.','Lo siento!',['positionClass' => 'toast-bottom-right']);
            //return redirect()->route('nuevocontable');
        }
        return view('sicinar.requisitos.editarReqc8',compact('nombre','usuario','regosc','regcontable','regnumeros', 'regperiodos','regperiodicidad','regformatos'));
    }

    public function actionActualizarReqc8(reqcontables8Request $request, $id){
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
        $regcontable = regReqContablesModel::where('OSC_FOLIO', $id);
        if($regcontable->count() <= 0)
            toastr()->error('No existe archivo 8 PDF.','¡Por favor volver a intentar!',['positionClass' => 'toast-bottom-right']);
        else{        

            //echo "Escribió en el campo de texto 1: " .'-'. $request->osc_d9 .'-'. "<br><br>"; 
            //echo "Escribió en el campo de texto 1: " . $request->osc_d9 . "<br><br>"; 
            //Comprobar  si el campo foto1 tiene un archivo asignado:
            $name8 =null;
            if($request->hasFile('osc_d8')){
                echo "Escribió en el campo de texto 8: " .'-'. $request->osc_d8 .'-'. "<br><br>"; 
                $name8 = $id.'_'.$request->file('osc_d8')->getClientOriginalName(); 
                //sube el archivo a la carpeta del servidor public/images/
                $request->file('osc_d8')->move(public_path().'/images/', $name8);

                // ************* Actualizamos registro **********************************
                $regcontable = regReqContablesModel::where('OSC_FOLIO', $id)        
                           ->update([   
                                     'DOC_ID8'    => $request->doc_id8,
                                     'FORMATO_ID8'=> $request->formato_id8,             
                                     'OSC_D8'     => $name8,                  
                                     //'PER_ID8'  => $request->per_id8,
                                     //'NUM_ID8'  => $request->num_id8,                
                                     //'OSC_EDO8' => $request->osc_edo8,

                                     'IP_M'       => $ip,
                                     'LOGIN_M'    => $nombre,
                                     'FECHA_M'    => date('Y/m/d')    //date('d/m/Y')                                
                                     ]);
                toastr()->success('archivo 8 PDF actualizado.','¡Ok!',['positionClass' => 'toast-bottom-right']);
            }else{
                // ************* Actualizamos registro **********************************
                $regcontable = regReqContablesModel::where('OSC_FOLIO',$id)        
                           ->update([   
                                     'DOC_ID8'    => $request->doc_id8,
                                     'FORMATO_ID8'=> $request->formato_id8,             
                                     //'OSC_D8'   => $name8,                   
                                     //'PER_ID8'  => $request->per_id8,
                                     //'NUM_ID8'  => $request->num_id8,                
                                     //'OSC_EDO8' => $request->osc_edo8,

                                     'IP_M'       => $ip,
                                     'LOGIN_M'    => $nombre,
                                     'FECHA_M'    => date('Y/m/d')    //date('d/m/Y')                                
                                     ]);                
                toastr()->success('archivo 8 PDF actualizado.','¡Ok!',['positionClass' => 'toast-bottom-right']);
            }

            /************ Bitacora inicia *************************************/ 
            setlocale(LC_TIME, "spanish");        
            $xip          = session()->get('ip');
            $xperiodo_id  = (int)date('Y');
            $xprograma_id = 1;
            $xmes_id      = (int)date('m');
            $xproceso_id  =         3;
            $xfuncion_id  =      3004;
            $xtrx_id      =        95;    //Actualizar         
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
        return redirect()->route('verReqc');
    }    

    /****************** Editar requisitos administrativos **********/
    public function actionEditarReqc9($id){
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
        $regformatos  = regFormatosModel::select('FORMATO_ID','FORMATO_DESC')->get();                               
        $regosc       = regOscModel::select('OSC_ID', 'OSC_DESC','OSC_STATUS')->orderBy('OSC_DESC','asc')
                        ->get();
        $regcontable  = regReqContablesModel::select('OSC_FOLIO','OSC_ID','PERIODO_ID',
                        'DOC_ID6' ,'FORMATO_ID6' ,'OSC_D6' ,'PER_ID6' ,'NUM_ID6' ,'OSC_EDO6',
                        'DOC_ID7' ,'FORMATO_ID7' ,'OSC_D7' ,'PER_ID7' ,'NUM_ID7' ,'OSC_EDO7',
                        'DOC_ID8' ,'FORMATO_ID8' ,'OSC_D8' ,'PER_ID8' ,'NUM_ID8' ,'OSC_EDO8',
                        'DOC_ID9' ,'FORMATO_ID9' ,'OSC_D9' ,'PER_ID9' ,'NUM_ID9' ,'OSC_EDO9',                        
                        'DOC_ID10','FORMATO_ID10','OSC_D10','PER_ID10','NUM_ID10','OSC_EDO10',                        
                        'DOC_ID11','FORMATO_ID11','OSC_D11','PER_ID11','NUM_ID11','OSC_EDO11',
                        'DOC_ID1002','OSC_D1002','PER_ID1002','NUM_ID1002',
                        'DOC_ID1003','OSC_D1003','PER_ID1003','NUM_ID1003',
                        'DOC_ID1004','OSC_D1004','PER_ID1004','NUM_ID1004',
                        'DOC_ID1005','OSC_D1005','PER_ID1005','NUM_ID1005',
                        'DOC_ID1006','OSC_D1006','PER_ID1006','NUM_ID1006',
                        'DOC_ID1007','OSC_D1007','PER_ID1007','NUM_ID1007',
                        'DOC_ID1008','OSC_D1008','PER_ID1008','NUM_ID1008',
                        'DOC_ID1009','OSC_D1009','PER_ID1009','NUM_ID1009',
                        'DOC_ID1010','OSC_D1010','PER_ID1010','NUM_ID1010',
                        'DOC_ID1011','OSC_D1011','PER_ID1011','NUM_ID1011',
                        'DOC_ID1012','OSC_D1012','PER_ID1012','NUM_ID1012',
                        'PREG_1002','PREG_1003','PREG_1004','PREG_1005','PREG_1006',
                        'PREG_1007','PREG_1008','PREG_1009','PREG_1010','PREG_1011','PREG_1012',                        
                        'PREG_001','PREG_002','PREG_003','PREG_004','PREG_005','PREG_006',                        
                        'OSC_STATUS','FECREG','IP','LOGIN','FECHA_M','IP_M','LOGIN_M')
                        ->where('OSC_FOLIO', $id)
                        ->first();
        if($regcontable->count() <= 0){
            toastr()->error('No existe otros requisitos administrativos.','Lo siento!',['positionClass' => 'toast-bottom-right']);
            //return redirect()->route('nuevocontable');
        }
        return view('sicinar.requisitos.editarReqc9',compact('nombre','usuario','regosc','regcontable','regnumeros', 'regperiodos','regperiodicidad','regformatos'));
    }

    public function actionActualizarReqc9(reqcontables9Request $request, $id){
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
        $regcontable = regReqContablesModel::where('OSC_FOLIO', $id);
        if($regcontable->count() <= 0)
            toastr()->error('No existe archivo 9 PDF.','¡Por favor volver a intentar!',['positionClass' => 'toast-bottom-right']);
        else{        

            //echo "Escribió en el campo de texto 1: " .'-'. $request->osc_d9 .'-'. "<br><br>"; 
            //echo "Escribió en el campo de texto 1: " . $request->osc_d9 . "<br><br>"; 
            //Comprobar  si el campo foto1 tiene un archivo asignado:
            $name9 =null;
            if($request->hasFile('osc_d9')){
                echo "Escribió en el campo de texto 9: " .'-'. $request->osc_d9 .'-'. "<br><br>"; 
                $name9 = $id.'_'.$request->file('osc_d9')->getClientOriginalName(); 
                //sube el archivo a la carpeta del servidor public/images/
                $request->file('osc_d9')->move(public_path().'/images/', $name9);

                // ************* Actualizamos registro **********************************
                $regcontable = regReqContablesModel::where('OSC_FOLIO',$id)        
                           ->update([   
                                     'DOC_ID9'    => $request->doc_id9,
                                     'FORMATO_ID9'=> $request->formato_id9,             
                                     'OSC_D9'     => $name9,                  
                                     //'PER_ID9'    => $request->per_id9,
                                     //'NUM_ID9'    => $request->num_id9,                
                                     //'OSC_EDO9'   => $request->osc_edo9,

                                     'IP_M'        => $ip,
                                     'LOGIN_M'     => $nombre,
                                     'FECHA_M'     => date('Y/m/d')    //date('d/m/Y')                                
                                     ]);
                toastr()->success('archivo contable 9 actualizado.','¡Ok!',['positionClass' => 'toast-bottom-right']);
            }else{
                // ************* Actualizamos registro **********************************
                $regcontable = regReqContablesModel::where('OSC_FOLIO',$id)        
                           ->update([   
                                     'DOC_ID9'    => $request->doc_id9,
                                     'FORMATO_ID9'=> $request->formato_id9,             
                                     //'OSC_D9'   => $name9,                  
                                     //'PER_ID9'    => $request->per_id9,
                                     //'NUM_ID9'    => $request->num_id9,                
                                     //'OSC_EDO9'   => $request->osc_edo9,

                                     'IP_M'        => $ip,
                                     'LOGIN_M'     => $nombre,
                                     'FECHA_M'     => date('Y/m/d')    //date('d/m/Y')                                
                                     ]);                
                toastr()->success('archivo 9 PDF actualizado.','¡Ok!',['positionClass' => 'toast-bottom-right']);
            }

            /************ Bitacora inicia *************************************/ 
            setlocale(LC_TIME, "spanish");        
            $xip          = session()->get('ip');
            $xperiodo_id  = (int)date('Y');
            $xprograma_id = 1;
            $xmes_id      = (int)date('m');
            $xproceso_id  =         3;
            $xfuncion_id  =      3004;
            $xtrx_id      =        95;    //Actualizar         
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
                        toastr()->success('Trx archivo 9 pdf dado de alta en Bitacora.','¡Ok!',['positionClass' => 'toast-bottom-right']);
                    else
                        toastr()->error('Error de trx 9 PDF al dar de alta la bitacora. Por favor volver a interlo.','Ups!',['positionClass' => 'toast-bottom-right']);
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
                    toastr()->success('Trx archivo 9 PDF en Bitacora.','¡Ok!',['positionClass' => 'toast-bottom-right']);
            }   /************ Bitacora termina *************************************/         
        }       /************ Termina de actualizar ********************************/
        return redirect()->route('verReqc');    
    }    

    /****************** Editar enero otros requisitos administrativos quotas de 5 al millar **********/
    public function actionEditarReqc10($id){
        $nombre       = session()->get('userlog');
        $pass         = session()->get('passlog');
        if($nombre == NULL AND $pass == NULL){
            return view('sicinar.login.expirada');
        }
        $usuario      = session()->get('usuario');
        $rango        = session()->get('rango');
        $arbol_id     = session()->get('arbol_id');        

        $regnumeros   = regNumerosModel::select('NUM_ID', 'NUM_DESC')->orderBy('NUM_ID','asc')
                        ->get();
        $regperiodos  = regPfiscalesModel::select('PERIODO_ID', 'PERIODO_DESC')->orderBy('PERIODO_ID','asc')
                        ->get();        
        $regperiodicidad= regPerModel::select('PER_ID', 'PER_DESC')->orderBy('PER_ID','asc')
                        ->get();     
        $regformatos  = regFormatosModel::select('FORMATO_ID','FORMATO_DESC')->get();                           
        $regosc       = regOscModel::select('OSC_ID', 'OSC_DESC','OSC_STATUS')->orderBy('OSC_DESC','asc')
                        ->get();
        $regcontable  = regReqContablesModel::select('OSC_FOLIO','OSC_ID','PERIODO_ID',
                        'DOC_ID6' ,'FORMATO_ID6' ,'OSC_D6' ,'PER_ID6' ,'NUM_ID6' ,'OSC_EDO6',
                        'DOC_ID7' ,'FORMATO_ID7' ,'OSC_D7' ,'PER_ID7' ,'NUM_ID7' ,'OSC_EDO7',
                        'DOC_ID8' ,'FORMATO_ID8' ,'OSC_D8' ,'PER_ID8' ,'NUM_ID8' ,'OSC_EDO8',
                        'DOC_ID9' ,'FORMATO_ID9' ,'OSC_D9' ,'PER_ID9' ,'NUM_ID9' ,'OSC_EDO9',                        
                        'DOC_ID10','FORMATO_ID10','OSC_D10','PER_ID10','NUM_ID10','OSC_EDO10',                        
                        'DOC_ID11','FORMATO_ID11','OSC_D11','PER_ID11','NUM_ID11','OSC_EDO11',
                        'DOC_ID1002','OSC_D1002','PER_ID1002','NUM_ID1002',
                        'DOC_ID1003','OSC_D1003','PER_ID1003','NUM_ID1003',
                        'DOC_ID1004','OSC_D1004','PER_ID1004','NUM_ID1004',
                        'DOC_ID1005','OSC_D1005','PER_ID1005','NUM_ID1005',
                        'DOC_ID1006','OSC_D1006','PER_ID1006','NUM_ID1006',
                        'DOC_ID1007','OSC_D1007','PER_ID1007','NUM_ID1007',
                        'DOC_ID1008','OSC_D1008','PER_ID1008','NUM_ID1008',
                        'DOC_ID1009','OSC_D1009','PER_ID1009','NUM_ID1009',
                        'DOC_ID1010','OSC_D1010','PER_ID1010','NUM_ID1010',
                        'DOC_ID1011','OSC_D1011','PER_ID1011','NUM_ID1011',
                        'DOC_ID1012','OSC_D1012','PER_ID1012','NUM_ID1012',
                        'PREG_1002','PREG_1003','PREG_1004','PREG_1005','PREG_1006',
                        'PREG_1007','PREG_1008','PREG_1009','PREG_1010','PREG_1011','PREG_1012',                        
                        'PREG_001','PREG_002','PREG_003','PREG_004','PREG_005','PREG_006',                        
                        'OSC_STATUS','FECREG','IP','LOGIN','FECHA_M','IP_M','LOGIN_M')
                       ->where('OSC_FOLIO', $id)
                       ->first();
        if($regcontable->count() <= 0){
            toastr()->error('No existe otros requisitos enero quotas de 5 al millar.','Lo siento!',['positionClass' => 'toast-bottom-right']);
            //return redirect()->route('nuevocontable');
        }
        return view('sicinar.requisitos.editarReqc10',compact('nombre','usuario','regosc','regcontable','regnumeros', 'regperiodos','regperiodicidad','regformatos'));
    }


    public function actionActualizarReqc10(reqcontables10Request $request, $id){
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
        $regcontable = regReqContablesModel::where('OSC_FOLIO',$id);
        if($regcontable->count() <= 0)
            toastr()->error('No existe Comprobación Deducibles de impuestos.','¡Por favor volver a intentar!',['positionClass' => 'toast-bottom-right']);
        else{        

            //***************** Actualizar *****************************
            //echo "Escribió en el campo de texto 1: " .'-'. $request->osc_d9 .'-'. "<br><br>"; 
            //echo "Escribió en el campo de texto 1: " . $request->osc_d9 . "<br><br>"; 
            //Comprobar  si el campo foto1 tiene un archivo asignado:
            $preg_006  =str_replace(",", "", $request->preg_006);            
            $preg_1002 =str_replace(",", "", $request->preg_1002);
            $preg_1003 =str_replace(",", "", $request->preg_1003);
            $preg_1004 =str_replace(",", "", $request->preg_1004);
            $preg_1005 =str_replace(",", "", $request->preg_1005);            
            $preg_1006 =str_replace(",", "", $request->preg_1006);
            $preg_1007 =str_replace(",", "", $request->preg_1007);
            $preg_1008 =str_replace(",", "", $request->preg_1008);
            $preg_1009 =str_replace(",", "", $request->preg_1009);             
            $preg_1010 =str_replace(",", "", $request->preg_1010); 
            $preg_1011 =str_replace(",", "", $request->preg_1011); 
            $preg_1012 =str_replace(",", "", $request->preg_1012);            

            $name10 =null;
            if($request->hasFile('osc_d10')){
                echo "Escribió en el campo de texto 10: " .'-'. $request->osc_d10 .'-'. "<br><br>"; 
                $name10 = $id.'_'.$request->file('osc_d10')->getClientOriginalName(); 
                //sube el archivo a la carpeta del servidor public/images/
                $request->file('osc_d10')->move(public_path().'/images/', $name10);

                // ************* Actualizamos registro **********************************
                $regcontable = regReqContablesModel::where('OSC_FOLIO',$id)        
                               ->update([  
                                      'DOC_ID10'    => $request->doc_id10,
                                      'FORMATO_ID10'=> $request->formato_id10,             
                                      'OSC_D10'     => $name10,           
                                      //'PER_ID10'    => $request->per_id10,
                                      //'NUM_ID10'    => $request->num_id10,                
                                      //'OSC_EDO10'   => $request->osc_edo10,

                                      //'PREG_006'    => $preg_006,

                                      'IP_M'        => $ip,
                                      'LOGIN_M'     => $nombre,
                                      'FECHA_M'     => date('Y/m/d')    //date('d/m/Y')                                
                                    ]);
                toastr()->success('requisitos administrativos 10 actualizados.','¡Ok!',['positionClass' => 'toast-bottom-right']);
            }else{
                // ************* Actualizamos registro **********************************
                $regcontable = regReqContablesModel::where('OSC_FOLIO',$id)        
                               ->update([  
                                      'DOC_ID10'    => $request->doc_id10,
                                      'FORMATO_ID10'=> $request->formato_id10,             
                                      //'OSC_D10'   => $name10,           
                                      //'PER_ID10'    => $request->per_id10,
                                      //'NUM_ID10'    => $request->num_id10,                
                                      //'OSC_EDO10'   => $request->osc_edo10,

                                      'PREG_006'    => $preg_006,

                                      'IP_M'        => $ip,
                                      'LOGIN_M'     => $nombre,
                                      'FECHA_M'     => date('Y/m/d')    //date('d/m/Y')                                
                                    ]);                
                toastr()->success('Comprobación Deducibles de impuestos actualizado.','¡Ok!',['positionClass' => 'toast-bottom-right']);
            }

            /************ Bitacora inicia *************************************/ 
            setlocale(LC_TIME, "spanish");        
            $xip          = session()->get('ip');
            $xperiodo_id  = (int)date('Y');
            $xprograma_id = 1;
            $xmes_id      = (int)date('m');
            $xproceso_id  =         3;
            $xfuncion_id  =      3004;
            $xtrx_id      =        95;    //Actualizar         
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
                        toastr()->success('Trx de Comprobación Deducibles de impuestos dada de alta.','¡Ok!',['positionClass' => 'toast-bottom-right']);
                    else
                        toastr()->error('Error trx de Comprobación Deducibles de impuestos inesperado al dar de alta en bitacora. Por favor volver a interlo.','Ups!',['positionClass' => 'toast-bottom-right']);
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
                toastr()->success('Trx de Comprobación Deducibles de impuestos actualizada en Bitacora.','¡Ok!',['positionClass' => 'toast-bottom-right']);
            }   /************ Bitacora termina *************************************/         
        }       /************ Termina de actualizar ********************************/
        return redirect()->route('verReqc');    
    }    

    /****************** Editar enero otros requisitos administrativos quotas de 5 al millar **********/
    public function actionEditarReqc11($id){
        $nombre       = session()->get('userlog');
        $pass         = session()->get('passlog');
        if($nombre == NULL AND $pass == NULL){
            return view('sicinar.login.expirada');
        }
        $usuario      = session()->get('usuario');
        $rango        = session()->get('rango');
        $arbol_id     = session()->get('arbol_id');        

        $regnumeros   = regNumerosModel::select('NUM_ID', 'NUM_DESC')->orderBy('NUM_ID','asc')
                        ->get();
        $regperiodos  = regPfiscalesModel::select('PERIODO_ID', 'PERIODO_DESC')->orderBy('PERIODO_ID','asc')
                        ->get();        
        $regperiodicidad= regPerModel::select('PER_ID', 'PER_DESC')->orderBy('PER_ID','asc')
                        ->get();     
        $regformatos  = regFormatosModel::select('FORMATO_ID','FORMATO_DESC')->get();                           
        $regosc       = regOscModel::select('OSC_ID', 'OSC_DESC','OSC_STATUS')->orderBy('OSC_DESC','asc')
                        ->get();
        $regcontable  = regReqContablesModel::select('OSC_FOLIO','OSC_ID','PERIODO_ID',
                        'DOC_ID6' ,'FORMATO_ID6' ,'OSC_D6' ,'PER_ID6' ,'NUM_ID6' ,'OSC_EDO6',
                        'DOC_ID7' ,'FORMATO_ID7' ,'OSC_D7' ,'PER_ID7' ,'NUM_ID7' ,'OSC_EDO7',
                        'DOC_ID8' ,'FORMATO_ID8' ,'OSC_D8' ,'PER_ID8' ,'NUM_ID8' ,'OSC_EDO8',
                        'DOC_ID9' ,'FORMATO_ID9' ,'OSC_D9' ,'PER_ID9' ,'NUM_ID9' ,'OSC_EDO9',                        
                        'DOC_ID10','FORMATO_ID10','OSC_D10','PER_ID10','NUM_ID10','OSC_EDO10',                        
                        'DOC_ID11','FORMATO_ID11','OSC_D11','PER_ID11','NUM_ID11','OSC_EDO11',
                        'DOC_ID1002','OSC_D1002','PER_ID1002','NUM_ID1002',
                        'DOC_ID1003','OSC_D1003','PER_ID1003','NUM_ID1003',
                        'DOC_ID1004','OSC_D1004','PER_ID1004','NUM_ID1004',
                        'DOC_ID1005','OSC_D1005','PER_ID1005','NUM_ID1005',
                        'DOC_ID1006','OSC_D1006','PER_ID1006','NUM_ID1006',
                        'DOC_ID1007','OSC_D1007','PER_ID1007','NUM_ID1007',
                        'DOC_ID1008','OSC_D1008','PER_ID1008','NUM_ID1008',
                        'DOC_ID1009','OSC_D1009','PER_ID1009','NUM_ID1009',
                        'DOC_ID1010','OSC_D1010','PER_ID1010','NUM_ID1010',
                        'DOC_ID1011','OSC_D1011','PER_ID1011','NUM_ID1011',
                        'DOC_ID1012','OSC_D1012','PER_ID1012','NUM_ID1012',
                        'PREG_1002','PREG_1003','PREG_1004','PREG_1005','PREG_1006',
                        'PREG_1007','PREG_1008','PREG_1009','PREG_1010','PREG_1011','PREG_1012',                        
                        'PREG_001','PREG_002','PREG_003','PREG_004','PREG_005','PREG_006',                        
                        'OSC_STATUS','FECREG','IP','LOGIN','FECHA_M','IP_M','LOGIN_M')
                       ->where('OSC_FOLIO', $id)
                       ->first();
        if($regcontable->count() <= 0){
            toastr()->error('No existe Apertura y/o Edo. cta.','Lo siento!',['positionClass' => 'toast-bottom-right']);
            //return redirect()->route('nuevocontable');
        }
        return view('sicinar.requisitos.editarReqc11',compact('nombre','usuario','regosc','regcontable','regnumeros', 'regperiodos','regperiodicidad','regformatos'));
    }


    public function actionActualizarReqc11(reqcontables11Request $request, $id){
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
        $regcontable = regReqContablesModel::where('OSC_FOLIO',$id);
        if($regcontable->count() <= 0)
            toastr()->error('No existe Apertura y/o Edo. cta..','¡Por favor volver a intentar!',['positionClass' => 'toast-bottom-right']);
        else{        

            //***************** Actualizar *****************************
            //echo "Escribió en el campo de texto 1: " .'-'. $request->osc_d9 .'-'. "<br><br>"; 
            //echo "Escribió en el campo de texto 1: " . $request->osc_d9 . "<br><br>"; 
            //Comprobar  si el campo foto1 tiene un archivo asignado:
            $preg_006  =str_replace(",", "", $request->preg_006);            
            $preg_1002 =str_replace(",", "", $request->preg_1002);
            $preg_1003 =str_replace(",", "", $request->preg_1003);
            $preg_1004 =str_replace(",", "", $request->preg_1004);
            $preg_1005 =str_replace(",", "", $request->preg_1005);            
            $preg_1006 =str_replace(",", "", $request->preg_1006);
            $preg_1007 =str_replace(",", "", $request->preg_1007);
            $preg_1008 =str_replace(",", "", $request->preg_1008);
            $preg_1009 =str_replace(",", "", $request->preg_1009);             
            $preg_1010 =str_replace(",", "", $request->preg_1010); 
            $preg_1011 =str_replace(",", "", $request->preg_1011); 
            $preg_1012 =str_replace(",", "", $request->preg_1012);            

            $name11 =null;
            if($request->hasFile('osc_d11')){
                echo "Escribió en el campo de texto 11: " .'-'. $request->osc_d11 .'-'. "<br><br>"; 
                $name11 = $id.'_'.$request->file('osc_d11')->getClientOriginalName(); 
                //sube el archivo a la carpeta del servidor public/images/
                $request->file('osc_d11')->move(public_path().'/images/', $name11);

                // ************* Actualizamos registro **********************************
                $regcontable = regReqContablesModel::where('OSC_FOLIO',$id)        
                               ->update([  
                                      'DOC_ID11'    => $request->doc_id11,
                                      'FORMATO_ID11'=> $request->formato_id11,             
                                      'OSC_D11'     => $name11,           
                                      //'PER_ID10'    => $request->per_id10,
                                      //'NUM_ID10'    => $request->num_id10,                
                                      //'OSC_EDO10'   => $request->osc_edo10,

                                      //'PREG_006'    => $preg_006,

                                      'IP_M'        => $ip,
                                      'LOGIN_M'     => $nombre,
                                      'FECHA_M'     => date('Y/m/d')    //date('d/m/Y')                                
                                    ]);
                toastr()->success('requisitos administrativos 11 actualizados.','¡Ok!',['positionClass' => 'toast-bottom-right']);
            }else{
                // ************* Actualizamos registro **********************************
                $regcontable = regReqContablesModel::where('OSC_FOLIO',$id)        
                               ->update([  
                                      'DOC_ID11'    => $request->doc_id11,
                                      'FORMATO_ID11'=> $request->formato_id11,             
                                      //'OSC_D10'   => $name10,           
                                      //'PER_ID10'    => $request->per_id10,
                                      //'NUM_ID10'    => $request->num_id10,                
                                      //'OSC_EDO10'   => $request->osc_edo10,

                                      //'PREG_006'    => $preg_006,

                                      'IP_M'        => $ip,
                                      'LOGIN_M'     => $nombre,
                                      'FECHA_M'     => date('Y/m/d')    //date('d/m/Y')                                
                                    ]);                
                toastr()->success('Apertura y/o Edo. cta. actualizado.','¡Ok!',['positionClass' => 'toast-bottom-right']);
            }

            /************ Bitacora inicia *************************************/ 
            setlocale(LC_TIME, "spanish");        
            $xip          = session()->get('ip');
            $xperiodo_id  = (int)date('Y');
            $xprograma_id = 1;
            $xmes_id      = (int)date('m');
            $xproceso_id  =         3;
            $xfuncion_id  =      3004;
            $xtrx_id      =        95;    //Actualizar         
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
                        toastr()->success('Trx de Apertura y/o Edo. cta. dada de alta.','¡Ok!',['positionClass' => 'toast-bottom-right']);
                    else
                        toastr()->error('Error trx de Apertura y/o Edo. cta. inesperado al dar de alta en bitacora. Por favor volver a interlo.','Ups!',['positionClass' => 'toast-bottom-right']);
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
                toastr()->success('Trx de Apertura y/o Edo. cta. actualizada en Bitacora.','¡Ok!',['positionClass' => 'toast-bottom-right']);
            }   /************ Bitacora termina *************************************/         
        }       /************ Termina de actualizar ********************************/
        return redirect()->route('verReqc');    
    }    


    /****************** Editar febrero otros requisitos administrativos quotas de 5 al millar **********/
    public function actionEditarReqc1002($id){
        $nombre       = session()->get('userlog');
        $pass         = session()->get('passlog');
        if($nombre == NULL AND $pass == NULL){
            return view('sicinar.login.expirada');
        }
        $usuario      = session()->get('usuario');
        $rango        = session()->get('rango');
        $arbol_id     = session()->get('arbol_id');        

        $regnumeros   = regNumerosModel::select('NUM_ID', 'NUM_DESC')->orderBy('NUM_ID','asc')
                        ->get();
        $regperiodos  = regPfiscalesModel::select('PERIODO_ID', 'PERIODO_DESC')->orderBy('PERIODO_ID','asc')
                        ->get();        
        $regperiodicidad= regPerModel::select('PER_ID', 'PER_DESC')->orderBy('PER_ID','asc')
                        ->get();     
        $regformatos  = regFormatosModel::select('FORMATO_ID','FORMATO_DESC')->get();                           
        $regosc       = regOscModel::select('OSC_ID', 'OSC_DESC','OSC_STATUS')->orderBy('OSC_DESC','asc')
                        ->get();
        $regcontable  = regReqContablesModel::select('OSC_FOLIO','OSC_ID','PERIODO_ID',
                        'DOC_ID6' ,'FORMATO_ID6' ,'OSC_D6' ,'PER_ID6' ,'NUM_ID6' ,'OSC_EDO6',
                        'DOC_ID7' ,'FORMATO_ID7' ,'OSC_D7' ,'PER_ID7' ,'NUM_ID7' ,'OSC_EDO7',
                        'DOC_ID8' ,'FORMATO_ID8' ,'OSC_D8' ,'PER_ID8' ,'NUM_ID8' ,'OSC_EDO8',
                        'DOC_ID9' ,'FORMATO_ID9' ,'OSC_D9' ,'PER_ID9' ,'NUM_ID9' ,'OSC_EDO9',                        
                        'DOC_ID10','FORMATO_ID10','OSC_D10','PER_ID10','NUM_ID10','OSC_EDO10',                        
                        'DOC_ID11','FORMATO_ID11','OSC_D11','PER_ID11','NUM_ID11','OSC_EDO11',
                        'DOC_ID1002','OSC_D1002','PER_ID1002','NUM_ID1002',
                        'DOC_ID1003','OSC_D1003','PER_ID1003','NUM_ID1003',
                        'DOC_ID1004','OSC_D1004','PER_ID1004','NUM_ID1004',
                        'DOC_ID1005','OSC_D1005','PER_ID1005','NUM_ID1005',
                        'DOC_ID1006','OSC_D1006','PER_ID1006','NUM_ID1006',
                        'DOC_ID1007','OSC_D1007','PER_ID1007','NUM_ID1007',
                        'DOC_ID1008','OSC_D1008','PER_ID1008','NUM_ID1008',
                        'DOC_ID1009','OSC_D1009','PER_ID1009','NUM_ID1009',
                        'DOC_ID1010','OSC_D1010','PER_ID1010','NUM_ID1010',
                        'DOC_ID1011','OSC_D1011','PER_ID1011','NUM_ID1011',
                        'DOC_ID1012','OSC_D1012','PER_ID1012','NUM_ID1012',
                        'PREG_1002','PREG_1003','PREG_1004','PREG_1005','PREG_1006',
                        'PREG_1007','PREG_1008','PREG_1009','PREG_1010','PREG_1011','PREG_1012',                        
                        'PREG_001','PREG_002','PREG_003','PREG_004','PREG_005','PREG_006',                        
                        'OSC_STATUS','FECREG','IP','LOGIN','FECHA_M','IP_M','LOGIN_M')
                       ->where('OSC_FOLIO', $id)
                       ->first();
        if($regcontable->count() <= 0){
            toastr()->error('No existe otros requisitos febrero quotas de 5 al millar.','Lo siento!',['positionClass' => 'toast-bottom-right']);
            //return redirect()->route('nuevocontable');
        }
        return view('sicinar.requisitos.editarReqc1002',compact('nombre','usuario','regosc','regcontable','regnumeros', 'regperiodos','regperiodicidad','regformatos'));
    }


    public function actionActualizarReqc1002(reqcontablesRequest $request, $id){
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
        $regcontable = regReqContablesModel::where('OSC_FOLIO',$id);
        if($regcontable->count() <= 0)
            toastr()->error('No existe otros requisitos febrero quotas de 5 al millar.','¡Por favor volver a intentar!',['positionClass' => 'toast-bottom-right']);
        else{        

            //***************** Actualizar *****************************           
            $preg_1002 = str_replace(",", "", $request->preg_1002);
            $name1002  = null;
            if($request->hasFile('osc_d1002')){
                $name1002 = $id.'_'.$request->file('osc_d1002')->getClientOriginalName(); 
                $request->file('osc_d1002')->move(public_path().'/images/', $name1002);
                // ************* Actualizamos registro **********************************
                $regcontable = regReqContablesModel::where('OSC_FOLIO',$id)        
                               ->update([  
                                      'DOC_ID1002'  => $request->doc_id1002,
                                      'OSC_D1002'   => $name1002,           
                                      //'PER_ID10'  => $request->per_id10,
                                      //'NUM_ID10'  => $request->num_id10,                
                                      //'OSC_EDO10' => $request->osc_edo10,
                                      'PREG_1002'   => $preg_1002,

                                      'IP_M'        => $ip,
                                      'LOGIN_M'     => $nombre,
                                      'FECHA_M'     => date('Y/m/d')    //date('d/m/Y')                                
                                    ]);
                toastr()->success('Febrero Otros requisitos quotas 5 al millar actualizados.','¡Ok!',['positionClass' => 'toast-bottom-right']);
            }else{
                // ************* Actualizamos registro **********************************
                $regcontable = regReqContablesModel::where('OSC_FOLIO',$id)        
                               ->update([  
                                      'DOC_ID1002'  => $request->doc_id1002,
                                      //'OSC_D10'   => $name10,           
                                      //'PER_ID10'  => $request->per_id10,
                                      //'NUM_ID10'  => $request->num_id10,                
                                      //'OSC_EDO10' => $request->osc_edo10,
                                      'PREG_1002'   => $preg_1002,

                                      'IP_M'        => $ip,
                                      'LOGIN_M'     => $nombre,
                                      'FECHA_M'     => date('Y/m/d')    //date('d/m/Y')                                
                                    ]);                
                toastr()->success('Febrero Otros requisitos quotas 5 al millar actualizado.','¡Ok!',['positionClass' => 'toast-bottom-right']);
            }

            /************ Bitacora inicia *************************************/ 
            setlocale(LC_TIME, "spanish");        
            $xip          = session()->get('ip');
            $xperiodo_id  = (int)date('Y');
            $xprograma_id = 1;
            $xmes_id      = (int)date('m');
            $xproceso_id  =         3;
            $xfuncion_id  =      3004;
            $xtrx_id      =        95;    //Actualizar         
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
                        toastr()->success('Trx de otros requisitos febrero quotas de 5 al millar dada de alta.','¡Ok!',['positionClass' => 'toast-bottom-right']);
                    else
                        toastr()->error('Error trx de otros requisitos febrero inesperado al dar de alta en bitacora. Por favor volver a interlo.','Ups!',['positionClass' => 'toast-bottom-right']);
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
                toastr()->success('Trx de otros requisitos febrero quotas de 5 al millar actualizada en Bitacora.','¡Ok!',['positionClass' => 'toast-bottom-right']);
            }   /************ Bitacora termina *************************************/         
        }       /************ Termina de actualizar ********************************/
        return redirect()->route('verReqc');    
    } 

    /****************** Editar marzo otros requisitos administrativos quotas de 5 al millar **********/
    public function actionEditarReqc1003($id){
        $nombre       = session()->get('userlog');
        $pass         = session()->get('passlog');
        if($nombre == NULL AND $pass == NULL){
            return view('sicinar.login.expirada');
        }
        $usuario      = session()->get('usuario');
        $rango        = session()->get('rango');
        $arbol_id     = session()->get('arbol_id');        

        $regnumeros   = regNumerosModel::select('NUM_ID', 'NUM_DESC')->orderBy('NUM_ID','asc')
                        ->get();
        $regperiodos  = regPfiscalesModel::select('PERIODO_ID', 'PERIODO_DESC')->orderBy('PERIODO_ID','asc')
                        ->get();        
        $regperiodicidad= regPerModel::select('PER_ID', 'PER_DESC')->orderBy('PER_ID','asc')
                        ->get();     
        $regformatos  = regFormatosModel::select('FORMATO_ID','FORMATO_DESC')->get();                           
        $regosc       = regOscModel::select('OSC_ID', 'OSC_DESC','OSC_STATUS')->orderBy('OSC_DESC','asc')
                        ->get();
        $regcontable  = regReqContablesModel::select('OSC_FOLIO','OSC_ID','PERIODO_ID',
                        'DOC_ID6' ,'FORMATO_ID6' ,'OSC_D6' ,'PER_ID6' ,'NUM_ID6' ,'OSC_EDO6',
                        'DOC_ID7' ,'FORMATO_ID7' ,'OSC_D7' ,'PER_ID7' ,'NUM_ID7' ,'OSC_EDO7',
                        'DOC_ID8' ,'FORMATO_ID8' ,'OSC_D8' ,'PER_ID8' ,'NUM_ID8' ,'OSC_EDO8',
                        'DOC_ID9' ,'FORMATO_ID9' ,'OSC_D9' ,'PER_ID9' ,'NUM_ID9' ,'OSC_EDO9',                        
                        'DOC_ID10','FORMATO_ID10','OSC_D10','PER_ID10','NUM_ID10','OSC_EDO10',                        
                        'DOC_ID11','FORMATO_ID11','OSC_D11','PER_ID11','NUM_ID11','OSC_EDO11',
                        'DOC_ID1002','OSC_D1002','PER_ID1002','NUM_ID1002',
                        'DOC_ID1003','OSC_D1003','PER_ID1003','NUM_ID1003',
                        'DOC_ID1004','OSC_D1004','PER_ID1004','NUM_ID1004',
                        'DOC_ID1005','OSC_D1005','PER_ID1005','NUM_ID1005',
                        'DOC_ID1006','OSC_D1006','PER_ID1006','NUM_ID1006',
                        'DOC_ID1007','OSC_D1007','PER_ID1007','NUM_ID1007',
                        'DOC_ID1008','OSC_D1008','PER_ID1008','NUM_ID1008',
                        'DOC_ID1009','OSC_D1009','PER_ID1009','NUM_ID1009',
                        'DOC_ID1010','OSC_D1010','PER_ID1010','NUM_ID1010',
                        'DOC_ID1011','OSC_D1011','PER_ID1011','NUM_ID1011',
                        'DOC_ID1012','OSC_D1012','PER_ID1012','NUM_ID1012',
                        'PREG_1002','PREG_1003','PREG_1004','PREG_1005','PREG_1006',
                        'PREG_1007','PREG_1008','PREG_1009','PREG_1010','PREG_1011','PREG_1012',                        
                        'PREG_001','PREG_002','PREG_003','PREG_004','PREG_005','PREG_006',                        
                        'OSC_STATUS','FECREG','IP','LOGIN','FECHA_M','IP_M','LOGIN_M')
                       ->where('OSC_FOLIO', $id)
                       ->first();
        if($regcontable->count() <= 0){
            toastr()->error('No existe otros requisitos marzo quotas de 5 al millar.','Lo siento!',['positionClass' => 'toast-bottom-right']);
        }
        return view('sicinar.requisitos.editarReqc1003',compact('nombre','usuario','regosc','regcontable','regnumeros', 'regperiodos','regperiodicidad','regformatos'));
    }


    public function actionActualizarReqc1003(reqcontablesRequest $request, $id){
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
        $regcontable = regReqContablesModel::where('OSC_FOLIO',$id);
        if($regcontable->count() <= 0)
            toastr()->error('No existe otros requisitos marzo quotas de 5 al millar.','¡Por favor volver a intentar!',['positionClass' => 'toast-bottom-right']);
        else{        

            //***************** Actualizar *****************************
            $preg_1003 = str_replace(",", "", $request->preg_1003);
            $name1003  = null;
            if($request->hasFile('osc_d1003')){
                $name1003 = $id.'_'.$request->file('osc_d1003')->getClientOriginalName(); 
                $request->file('osc_d1003')->move(public_path().'/images/', $name1003);
                // ************* Actualizamos registro **********************************
                $regcontable = regReqContablesModel::where('OSC_FOLIO',$id)        
                               ->update([  
                                      'DOC_ID1003'  => $request->doc_id1003,
                                      'OSC_D1003'   => $name1003,           
                                      //'PER_ID10'  => $request->per_id10,
                                      //'NUM_ID10'  => $request->num_id10,                
                                      //'OSC_EDO10' => $request->osc_edo10,
                                      'PREG_1003'   => $preg_1003,

                                      'IP_M'        => $ip,
                                      'LOGIN_M'     => $nombre,
                                      'FECHA_M'     => date('Y/m/d')    //date('d/m/Y')                                
                                    ]);
                toastr()->success('Marzo Otros requisitos quotas 5 al millar actualizados.','¡Ok!',['positionClass' => 'toast-bottom-right']);
            }else{
                // ************* Actualizamos registro **********************************
                $regcontable = regReqContablesModel::where('OSC_FOLIO',$id)        
                               ->update([  
                                      'DOC_ID1003'  => $request->doc_id1003,
                                      //'OSC_D10'   => $name10,           
                                      //'PER_ID10'  => $request->per_id10,
                                      //'NUM_ID10'  => $request->num_id10,                
                                      //'OSC_EDO10' => $request->osc_edo10,
                                      'PREG_1003'   => $preg_1003,

                                      'IP_M'        => $ip,
                                      'LOGIN_M'     => $nombre,
                                      'FECHA_M'     => date('Y/m/d')    //date('d/m/Y')                                
                                    ]);                
                toastr()->success('Marzo Otros requisitos quotas 5 al millar actualizado.','¡Ok!',['positionClass' => 'toast-bottom-right']);
            }

            /************ Bitacora inicia *************************************/ 
            setlocale(LC_TIME, "spanish");        
            $xip          = session()->get('ip');
            $xperiodo_id  = (int)date('Y');
            $xprograma_id = 1;
            $xmes_id      = (int)date('m');
            $xproceso_id  =         3;
            $xfuncion_id  =      3004;
            $xtrx_id      =        95;    //Actualizar         
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
                        toastr()->success('Trx de otros requisitos marzo quotas de 5 al millar dada de alta.','¡Ok!',['positionClass' => 'toast-bottom-right']);
                    else
                        toastr()->error('Error trx de otros requisitos marzo inesperado al dar de alta en bitacora. Por favor volver a interlo.','Ups!',['positionClass' => 'toast-bottom-right']);
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
                toastr()->success('Trx de otros requisitos marzo quotas de 5 al millar actualizada en Bitacora.','¡Ok!',['positionClass' => 'toast-bottom-right']);
            }   /************ Bitacora termina *************************************/         
        }       /************ Termina de actualizar ********************************/
        return redirect()->route('verReqc');    
    } 

    /****************** Editar abril otros requisitos administrativos quotas de 5 al millar **********/
    public function actionEditarReqc1004($id){
        $nombre       = session()->get('userlog');
        $pass         = session()->get('passlog');
        if($nombre == NULL AND $pass == NULL){
            return view('sicinar.login.expirada');
        }
        $usuario      = session()->get('usuario');
        $rango        = session()->get('rango');
        $arbol_id     = session()->get('arbol_id');        

        $regnumeros   = regNumerosModel::select('NUM_ID', 'NUM_DESC')->orderBy('NUM_ID','asc')
                        ->get();
        $regperiodos  = regPfiscalesModel::select('PERIODO_ID', 'PERIODO_DESC')->orderBy('PERIODO_ID','asc')
                        ->get();        
        $regperiodicidad= regPerModel::select('PER_ID', 'PER_DESC')->orderBy('PER_ID','asc')
                        ->get();     
        $regformatos  = regFormatosModel::select('FORMATO_ID','FORMATO_DESC')->get();                           
        $regosc       = regOscModel::select('OSC_ID', 'OSC_DESC','OSC_STATUS')->orderBy('OSC_DESC','asc')
                        ->get();
        $regcontable  = regReqContablesModel::select('OSC_FOLIO','OSC_ID','PERIODO_ID',
                        'DOC_ID6' ,'FORMATO_ID6' ,'OSC_D6' ,'PER_ID6' ,'NUM_ID6' ,'OSC_EDO6',
                        'DOC_ID7' ,'FORMATO_ID7' ,'OSC_D7' ,'PER_ID7' ,'NUM_ID7' ,'OSC_EDO7',
                        'DOC_ID8' ,'FORMATO_ID8' ,'OSC_D8' ,'PER_ID8' ,'NUM_ID8' ,'OSC_EDO8',
                        'DOC_ID9' ,'FORMATO_ID9' ,'OSC_D9' ,'PER_ID9' ,'NUM_ID9' ,'OSC_EDO9',                        
                        'DOC_ID10','FORMATO_ID10','OSC_D10','PER_ID10','NUM_ID10','OSC_EDO10',                        
                        'DOC_ID11','FORMATO_ID11','OSC_D11','PER_ID11','NUM_ID11','OSC_EDO11',
                        'DOC_ID1002','OSC_D1002','PER_ID1002','NUM_ID1002',
                        'DOC_ID1003','OSC_D1003','PER_ID1003','NUM_ID1003',
                        'DOC_ID1004','OSC_D1004','PER_ID1004','NUM_ID1004',
                        'DOC_ID1005','OSC_D1005','PER_ID1005','NUM_ID1005',
                        'DOC_ID1006','OSC_D1006','PER_ID1006','NUM_ID1006',
                        'DOC_ID1007','OSC_D1007','PER_ID1007','NUM_ID1007',
                        'DOC_ID1008','OSC_D1008','PER_ID1008','NUM_ID1008',
                        'DOC_ID1009','OSC_D1009','PER_ID1009','NUM_ID1009',
                        'DOC_ID1010','OSC_D1010','PER_ID1010','NUM_ID1010',
                        'DOC_ID1011','OSC_D1011','PER_ID1011','NUM_ID1011',
                        'DOC_ID1012','OSC_D1012','PER_ID1012','NUM_ID1012',
                        'PREG_1002','PREG_1003','PREG_1004','PREG_1005','PREG_1006',
                        'PREG_1007','PREG_1008','PREG_1009','PREG_1010','PREG_1011','PREG_1012',                        
                        'PREG_001','PREG_002','PREG_003','PREG_004','PREG_005','PREG_006',                        
                        'OSC_STATUS','FECREG','IP','LOGIN','FECHA_M','IP_M','LOGIN_M')
                       ->where('OSC_FOLIO', $id)
                       ->first();
        if($regcontable->count() <= 0){
            toastr()->error('No existe otros requisitos abril quotas de 5 al millar.','Lo siento!',['positionClass' => 'toast-bottom-right']);
        }
        return view('sicinar.requisitos.editarReqc1004',compact('nombre','usuario','regosc','regcontable','regnumeros', 'regperiodos','regperiodicidad','regformatos'));
    }


    public function actionActualizarReqc1004(reqcontablesRequest $request, $id){
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
        $regcontable = regReqContablesModel::where('OSC_FOLIO',$id);
        if($regcontable->count() <= 0)
            toastr()->error('No existe otros requisitos abril quotas de 5 al millar.','¡Por favor volver a intentar!',['positionClass' => 'toast-bottom-right']);
        else{        
            //***************** Actualizar *****************************
            $preg_1004 =str_replace(",", "", $request->preg_1004);
            $name1004  = null;
            if($request->hasFile('osc_d1004')){
                $name1004 = $id.'_'.$request->file('osc_d1004')->getClientOriginalName(); 
                $request->file('osc_d1004')->move(public_path().'/images/', $name1004);
                // ************* Actualizamos registro **********************************
                $regcontable = regReqContablesModel::where('OSC_FOLIO',$id)        
                               ->update([  
                                      'DOC_ID1004'  => $request->doc_id1004,
                                      'OSC_D1004'   => $name1004,           
                                      //'PER_ID10'  => $request->per_id10,
                                      //'NUM_ID10'  => $request->num_id10,                
                                      //'OSC_EDO10' => $request->osc_edo10,
                                      'PREG_1004'   => $preg_1004,

                                      'IP_M'        => $ip,
                                      'LOGIN_M'     => $nombre,
                                      'FECHA_M'     => date('Y/m/d')    //date('d/m/Y')                                
                                    ]);
                toastr()->success('Abril Otros requisitos quotas 5 al millar actualizados.','¡Ok!',['positionClass' => 'toast-bottom-right']);
            }else{
                // ************* Actualizamos registro **********************************
                $regcontable = regReqContablesModel::where('OSC_FOLIO',$id)        
                               ->update([  
                                      'DOC_ID1004'  => $request->doc_id1004,
                                      //'OSC_D10'   => $name10,           
                                      //'PER_ID10'  => $request->per_id10,
                                      //'NUM_ID10'  => $request->num_id10,                
                                      //'OSC_EDO10' => $request->osc_edo10,
                                      'PREG_1004'   => $preg_1004,

                                      'IP_M'        => $ip,
                                      'LOGIN_M'     => $nombre,
                                      'FECHA_M'     => date('Y/m/d')    //date('d/m/Y')                                
                                    ]);                
                toastr()->success('Abril Otros requisitos quotas 5 al millar actualizado.','¡Ok!',['positionClass' => 'toast-bottom-right']);
            }

            /************ Bitacora inicia *************************************/ 
            setlocale(LC_TIME, "spanish");        
            $xip          = session()->get('ip');
            $xperiodo_id  = (int)date('Y');
            $xprograma_id = 1;
            $xmes_id      = (int)date('m');
            $xproceso_id  =         3;
            $xfuncion_id  =      3004;
            $xtrx_id      =        95;    //Actualizar         
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
                        toastr()->success('Trx de otros requisitos abril quotas de 5 al millar dada de alta.','¡Ok!',['positionClass' => 'toast-bottom-right']);
                    else
                        toastr()->error('Error trx de otros requisitos abril inesperado al dar de alta en bitacora. Por favor volver a interlo.','Ups!',['positionClass' => 'toast-bottom-right']);
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
                toastr()->success('Trx de otros requisitos abril quotas de 5 al millar actualizada en Bitacora.','¡Ok!',['positionClass' => 'toast-bottom-right']);
            }   /************ Bitacora termina *************************************/         
        }       /************ Termina de actualizar ********************************/
        return redirect()->route('verReqc');    
    } 

    /****************** Editar mayo otros requisitos administrativos quotas de 5 al millar **********/
    public function actionEditarReqc1005($id){
        $nombre       = session()->get('userlog');
        $pass         = session()->get('passlog');
        if($nombre == NULL AND $pass == NULL){
            return view('sicinar.login.expirada');
        }
        $usuario      = session()->get('usuario');
        $rango        = session()->get('rango');
        $arbol_id     = session()->get('arbol_id');        

        $regnumeros   = regNumerosModel::select('NUM_ID', 'NUM_DESC')->orderBy('NUM_ID','asc')
                        ->get();
        $regperiodos  = regPfiscalesModel::select('PERIODO_ID', 'PERIODO_DESC')->orderBy('PERIODO_ID','asc')
                        ->get();        
        $regperiodicidad= regPerModel::select('PER_ID', 'PER_DESC')->orderBy('PER_ID','asc')
                        ->get();     
        $regformatos  = regFormatosModel::select('FORMATO_ID','FORMATO_DESC')->get();                           
        $regosc       = regOscModel::select('OSC_ID', 'OSC_DESC','OSC_STATUS')->orderBy('OSC_DESC','asc')
                        ->get();
        $regcontable  = regReqContablesModel::select('OSC_FOLIO','OSC_ID','PERIODO_ID',
                        'DOC_ID6' ,'FORMATO_ID6' ,'OSC_D6' ,'PER_ID6' ,'NUM_ID6' ,'OSC_EDO6',
                        'DOC_ID7' ,'FORMATO_ID7' ,'OSC_D7' ,'PER_ID7' ,'NUM_ID7' ,'OSC_EDO7',
                        'DOC_ID8' ,'FORMATO_ID8' ,'OSC_D8' ,'PER_ID8' ,'NUM_ID8' ,'OSC_EDO8',
                        'DOC_ID9' ,'FORMATO_ID9' ,'OSC_D9' ,'PER_ID9' ,'NUM_ID9' ,'OSC_EDO9',                        
                        'DOC_ID10','FORMATO_ID10','OSC_D10','PER_ID10','NUM_ID10','OSC_EDO10',                        
                        'DOC_ID11','FORMATO_ID11','OSC_D11','PER_ID11','NUM_ID11','OSC_EDO11',
                        'DOC_ID1002','OSC_D1002','PER_ID1002','NUM_ID1002',
                        'DOC_ID1003','OSC_D1003','PER_ID1003','NUM_ID1003',
                        'DOC_ID1004','OSC_D1004','PER_ID1004','NUM_ID1004',
                        'DOC_ID1005','OSC_D1005','PER_ID1005','NUM_ID1005',
                        'DOC_ID1006','OSC_D1006','PER_ID1006','NUM_ID1006',
                        'DOC_ID1007','OSC_D1007','PER_ID1007','NUM_ID1007',
                        'DOC_ID1008','OSC_D1008','PER_ID1008','NUM_ID1008',
                        'DOC_ID1009','OSC_D1009','PER_ID1009','NUM_ID1009',
                        'DOC_ID1010','OSC_D1010','PER_ID1010','NUM_ID1010',
                        'DOC_ID1011','OSC_D1011','PER_ID1011','NUM_ID1011',
                        'DOC_ID1012','OSC_D1012','PER_ID1012','NUM_ID1012',
                        'PREG_1002','PREG_1003','PREG_1004','PREG_1005','PREG_1006',
                        'PREG_1007','PREG_1008','PREG_1009','PREG_1010','PREG_1011','PREG_1012',                        
                        'PREG_001','PREG_002','PREG_003','PREG_004','PREG_005','PREG_006',                        
                        'OSC_STATUS','FECREG','IP','LOGIN','FECHA_M','IP_M','LOGIN_M')
                       ->where('OSC_FOLIO', $id)
                       ->first();
        if($regcontable->count() <= 0){
            toastr()->error('No existe otros requisitos mayo quotas de 5 al millar.','Lo siento!',['positionClass' => 'toast-bottom-right']);
        }
        return view('sicinar.requisitos.editarReqc1005',compact('nombre','usuario','regosc','regcontable','regnumeros', 'regperiodos','regperiodicidad','regformatos'));
    }


    public function actionActualizarReqc1005(reqcontablesRequest $request, $id){
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
        $regcontable = regReqContablesModel::where('OSC_FOLIO',$id);
        if($regcontable->count() <= 0)
            toastr()->error('No existe otros requisitos mayo quotas de 5 al millar.','¡Por favor volver a intentar!',['positionClass' => 'toast-bottom-right']);
        else{        
            //***************** Actualizar *****************************
            $preg_1005 = str_replace(",", "", $request->preg_1005);    
            $name1005  = null;
            if($request->hasFile('osc_d1005')){
                $name1005 = $id.'_'.$request->file('osc_d1005')->getClientOriginalName(); 
                $request->file('osc_d1005')->move(public_path().'/images/', $name1005);
                // ************* Actualizamos registro **********************************
                $regcontable = regReqContablesModel::where('OSC_FOLIO',$id)        
                               ->update([  
                                      'DOC_ID1005'  => $request->doc_id1005,
                                      'OSC_D1005'   => $name1005,           
                                      //'PER_ID10'  => $request->per_id10,
                                      //'NUM_ID10'  => $request->num_id10,                
                                      //'OSC_EDO10' => $request->osc_edo10,
                                      'PREG_1005'   => $preg_1005,

                                      'IP_M'        => $ip,
                                      'LOGIN_M'     => $nombre,
                                      'FECHA_M'     => date('Y/m/d')    //date('d/m/Y')                                
                                    ]);
                toastr()->success('Mayo Otros requisitos quotas 5 al millar actualizados.','¡Ok!',['positionClass' => 'toast-bottom-right']);
            }else{
                // ************* Actualizamos registro **********************************
                $regcontable = regReqContablesModel::where('OSC_FOLIO',$id)        
                               ->update([  
                                      'DOC_ID1005'  => $request->doc_id1005,
                                      //'OSC_D10'   => $name10,           
                                      //'PER_ID10'  => $request->per_id10,
                                      //'NUM_ID10'  => $request->num_id10,                
                                      //'OSC_EDO10' => $request->osc_edo10,
                                      'PREG_1005'   => $preg_1005,

                                      'IP_M'        => $ip,
                                      'LOGIN_M'     => $nombre,
                                      'FECHA_M'     => date('Y/m/d')    //date('d/m/Y')                                
                                    ]);                
                toastr()->success('Mayo Otros requisitos quotas 5 al millar actualizado.','¡Ok!',['positionClass' => 'toast-bottom-right']);
            }

            /************ Bitacora inicia *************************************/ 
            setlocale(LC_TIME, "spanish");        
            $xip          = session()->get('ip');
            $xperiodo_id  = (int)date('Y');
            $xprograma_id = 1;
            $xmes_id      = (int)date('m');
            $xproceso_id  =         3;
            $xfuncion_id  =      3004;
            $xtrx_id      =        95;    //Actualizar         
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
                        toastr()->success('Trx de otros requisitos mayo quotas de 5 al millar dada de alta.','¡Ok!',['positionClass' => 'toast-bottom-right']);
                    else
                        toastr()->error('Error trx de otros requisitos mayo inesperado al dar de alta en bitacora. Por favor volver a interlo.','Ups!',['positionClass' => 'toast-bottom-right']);
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
                toastr()->success('Trx de otros requisitos mayo quotas de 5 al millar actualizada en Bitacora.','¡Ok!',['positionClass' => 'toast-bottom-right']);
            }   /************ Bitacora termina *************************************/         
        }       /************ Termina de actualizar ********************************/
        return redirect()->route('verReqc');    
    } 

    /****************** Editar junio otros requisitos administrativos quotas de 5 al millar **********/
    public function actionEditarReqc1006($id){
        $nombre       = session()->get('userlog');
        $pass         = session()->get('passlog');
        if($nombre == NULL AND $pass == NULL){
            return view('sicinar.login.expirada');
        }
        $usuario      = session()->get('usuario');
        $rango        = session()->get('rango');
        $arbol_id     = session()->get('arbol_id');        

        $regnumeros   = regNumerosModel::select('NUM_ID', 'NUM_DESC')->orderBy('NUM_ID','asc')
                        ->get();
        $regperiodos  = regPfiscalesModel::select('PERIODO_ID', 'PERIODO_DESC')->orderBy('PERIODO_ID','asc')
                        ->get();        
        $regperiodicidad= regPerModel::select('PER_ID', 'PER_DESC')->orderBy('PER_ID','asc')
                        ->get();     
        $regformatos  = regFormatosModel::select('FORMATO_ID','FORMATO_DESC')->get();                           
        $regosc       = regOscModel::select('OSC_ID', 'OSC_DESC','OSC_STATUS')->orderBy('OSC_DESC','asc')
                        ->get();
        $regcontable  = regReqContablesModel::select('OSC_FOLIO','OSC_ID','PERIODO_ID',
                        'DOC_ID6' ,'FORMATO_ID6' ,'OSC_D6' ,'PER_ID6' ,'NUM_ID6' ,'OSC_EDO6',
                        'DOC_ID7' ,'FORMATO_ID7' ,'OSC_D7' ,'PER_ID7' ,'NUM_ID7' ,'OSC_EDO7',
                        'DOC_ID8' ,'FORMATO_ID8' ,'OSC_D8' ,'PER_ID8' ,'NUM_ID8' ,'OSC_EDO8',
                        'DOC_ID9' ,'FORMATO_ID9' ,'OSC_D9' ,'PER_ID9' ,'NUM_ID9' ,'OSC_EDO9',                        
                        'DOC_ID10','FORMATO_ID10','OSC_D10','PER_ID10','NUM_ID10','OSC_EDO10',                        
                        'DOC_ID11','FORMATO_ID11','OSC_D11','PER_ID11','NUM_ID11','OSC_EDO11',
                        'DOC_ID1002','OSC_D1002','PER_ID1002','NUM_ID1002',
                        'DOC_ID1003','OSC_D1003','PER_ID1003','NUM_ID1003',
                        'DOC_ID1004','OSC_D1004','PER_ID1004','NUM_ID1004',
                        'DOC_ID1005','OSC_D1005','PER_ID1005','NUM_ID1005',
                        'DOC_ID1006','OSC_D1006','PER_ID1006','NUM_ID1006',
                        'DOC_ID1007','OSC_D1007','PER_ID1007','NUM_ID1007',
                        'DOC_ID1008','OSC_D1008','PER_ID1008','NUM_ID1008',
                        'DOC_ID1009','OSC_D1009','PER_ID1009','NUM_ID1009',
                        'DOC_ID1010','OSC_D1010','PER_ID1010','NUM_ID1010',
                        'DOC_ID1011','OSC_D1011','PER_ID1011','NUM_ID1011',
                        'DOC_ID1012','OSC_D1012','PER_ID1012','NUM_ID1012',
                        'PREG_1002','PREG_1003','PREG_1004','PREG_1005','PREG_1006',
                        'PREG_1007','PREG_1008','PREG_1009','PREG_1010','PREG_1011','PREG_1012',                        
                        'PREG_001','PREG_002','PREG_003','PREG_004','PREG_005','PREG_006',                        
                        'OSC_STATUS','FECREG','IP','LOGIN','FECHA_M','IP_M','LOGIN_M')
                       ->where('OSC_FOLIO', $id)
                       ->first();
        if($regcontable->count() <= 0){
            toastr()->error('No existe otros requisitos junio quotas de 5 al millar.','Lo siento!',['positionClass' => 'toast-bottom-right']);
        }
        return view('sicinar.requisitos.editarReqc1006',compact('nombre','usuario','regosc','regcontable','regnumeros', 'regperiodos','regperiodicidad','regformatos'));
    }

    public function actionActualizarReqc1006(reqcontablesRequest $request, $id){
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
        $regcontable = regReqContablesModel::where('OSC_FOLIO',$id);
        if($regcontable->count() <= 0)
            toastr()->error('No existe otros requisitos junio quotas de 5 al millar.','¡Por favor volver a intentar!',['positionClass' => 'toast-bottom-right']);
        else{        

            //***************** Actualizar *****************************
            $preg_1006 =str_replace(",", "", $request->preg_1006);
            $name1006  = null;
            if($request->hasFile('osc_d1006')){
                $name1006 = $id.'_'.$request->file('osc_d1006')->getClientOriginalName(); 
                $request->file('osc_d1006')->move(public_path().'/images/', $name1006);
                // ************* Actualizamos registro **********************************
                $regcontable = regReqContablesModel::where('OSC_FOLIO',$id)        
                               ->update([  
                                      'DOC_ID1006'  => $request->doc_id1006,
                                      'OSC_D1006'   => $name1006,           
                                      //'PER_ID10'  => $request->per_id10,
                                      //'NUM_ID10'  => $request->num_id10,                
                                      //'OSC_EDO10' => $request->osc_edo10,

                                      'PREG_1006'   => $preg_1006,

                                      'IP_M'        => $ip,
                                      'LOGIN_M'     => $nombre,
                                      'FECHA_M'     => date('Y/m/d')    //date('d/m/Y')                                
                                    ]);
                toastr()->success('Junio Otros requisitos quotas 5 al millar actualizados.','¡Ok!',['positionClass' => 'toast-bottom-right']);
            }else{
                // ************* Actualizamos registro **********************************
                $regcontable = regReqContablesModel::where('OSC_FOLIO',$id)        
                               ->update([  
                                      'DOC_ID1006'  => $request->doc_id1006,
                                      //'OSC_D10'   => $name10,           
                                      //'PER_ID10'  => $request->per_id10,
                                      //'NUM_ID10'  => $request->num_id10,                
                                      //'OSC_EDO10' => $request->osc_edo10,
                                      'PREG_1006'   => $preg_1006,

                                      'IP_M'        => $ip,
                                      'LOGIN_M'     => $nombre,
                                      'FECHA_M'     => date('Y/m/d')    //date('d/m/Y')                                
                                    ]);                
                toastr()->success('Junio Otros requisitos quotas 5 al millar actualizado.','¡Ok!',['positionClass' => 'toast-bottom-right']);
            }

            /************ Bitacora inicia *************************************/ 
            setlocale(LC_TIME, "spanish");        
            $xip          = session()->get('ip');
            $xperiodo_id  = (int)date('Y');
            $xprograma_id = 1;
            $xmes_id      = (int)date('m');
            $xproceso_id  =         3;
            $xfuncion_id  =      3004;
            $xtrx_id      =        95;    //Actualizar         
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
                        toastr()->success('Trx de otros requisitos junio quotas de 5 al millar dada de alta.','¡Ok!',['positionClass' => 'toast-bottom-right']);
                    else
                        toastr()->error('Error trx de otros requisitos junio inesperado al dar de alta en bitacora. Por favor volver a interlo.','Ups!',['positionClass' => 'toast-bottom-right']);
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
                toastr()->success('Trx de otros requisitos junio quotas de 5 al millar actualizada en Bitacora.','¡Ok!',['positionClass' => 'toast-bottom-right']);
            }   /************ Bitacora termina *************************************/         
        }       /************ Termina de actualizar ********************************/
        return redirect()->route('verReqc');    
    } 

    /****************** Editar julio otros requisitos administrativos quotas de 5 al millar **********/
    public function actionEditarReqc1007($id){
        $nombre       = session()->get('userlog');
        $pass         = session()->get('passlog');
        if($nombre == NULL AND $pass == NULL){
            return view('sicinar.login.expirada');
        }
        $usuario      = session()->get('usuario');
        $rango        = session()->get('rango');
        $arbol_id     = session()->get('arbol_id');        

        $regnumeros   = regNumerosModel::select('NUM_ID', 'NUM_DESC')->orderBy('NUM_ID','asc')
                        ->get();
        $regperiodos  = regPfiscalesModel::select('PERIODO_ID', 'PERIODO_DESC')->orderBy('PERIODO_ID','asc')
                        ->get();        
        $regperiodicidad= regPerModel::select('PER_ID', 'PER_DESC')->orderBy('PER_ID','asc')
                        ->get();     
        $regformatos  = regFormatosModel::select('FORMATO_ID','FORMATO_DESC')->get();                           
        $regosc       = regOscModel::select('OSC_ID', 'OSC_DESC','OSC_STATUS')->orderBy('OSC_DESC','asc')
                        ->get();
        $regcontable  = regReqContablesModel::select('OSC_FOLIO','OSC_ID','PERIODO_ID',
                        'DOC_ID6' ,'FORMATO_ID6' ,'OSC_D6' ,'PER_ID6' ,'NUM_ID6' ,'OSC_EDO6',
                        'DOC_ID7' ,'FORMATO_ID7' ,'OSC_D7' ,'PER_ID7' ,'NUM_ID7' ,'OSC_EDO7',
                        'DOC_ID8' ,'FORMATO_ID8' ,'OSC_D8' ,'PER_ID8' ,'NUM_ID8' ,'OSC_EDO8',
                        'DOC_ID9' ,'FORMATO_ID9' ,'OSC_D9' ,'PER_ID9' ,'NUM_ID9' ,'OSC_EDO9',                        
                        'DOC_ID10','FORMATO_ID10','OSC_D10','PER_ID10','NUM_ID10','OSC_EDO10',                        
                        'DOC_ID11','FORMATO_ID11','OSC_D11','PER_ID11','NUM_ID11','OSC_EDO11',
                        'DOC_ID1002','OSC_D1002','PER_ID1002','NUM_ID1002',
                        'DOC_ID1003','OSC_D1003','PER_ID1003','NUM_ID1003',
                        'DOC_ID1004','OSC_D1004','PER_ID1004','NUM_ID1004',
                        'DOC_ID1005','OSC_D1005','PER_ID1005','NUM_ID1005',
                        'DOC_ID1006','OSC_D1006','PER_ID1006','NUM_ID1006',
                        'DOC_ID1007','OSC_D1007','PER_ID1007','NUM_ID1007',
                        'DOC_ID1008','OSC_D1008','PER_ID1008','NUM_ID1008',
                        'DOC_ID1009','OSC_D1009','PER_ID1009','NUM_ID1009',
                        'DOC_ID1010','OSC_D1010','PER_ID1010','NUM_ID1010',
                        'DOC_ID1011','OSC_D1011','PER_ID1011','NUM_ID1011',
                        'DOC_ID1012','OSC_D1012','PER_ID1012','NUM_ID1012',
                        'PREG_1002','PREG_1003','PREG_1004','PREG_1005','PREG_1006',
                        'PREG_1007','PREG_1008','PREG_1009','PREG_1010','PREG_1011','PREG_1012',                        
                        'PREG_001','PREG_002','PREG_003','PREG_004','PREG_005','PREG_006',                        
                        'OSC_STATUS','FECREG','IP','LOGIN','FECHA_M','IP_M','LOGIN_M')
                       ->where('OSC_FOLIO', $id)
                       ->first();
        if($regcontable->count() <= 0){
            toastr()->error('No existe otros requisitos julio quotas de 5 al millar.','Lo siento!',['positionClass' => 'toast-bottom-right']);
        }
        return view('sicinar.requisitos.editarReqc1007',compact('nombre','usuario','regosc','regcontable','regnumeros', 'regperiodos','regperiodicidad','regformatos'));
    }


    public function actionActualizarReqc1007(reqcontablesRequest $request, $id){
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
        $regcontable = regReqContablesModel::where('OSC_FOLIO',$id);
        if($regcontable->count() <= 0)
            toastr()->error('No existe otros requisitos julio quotas de 5 al millar.','¡Por favor volver a intentar!',['positionClass' => 'toast-bottom-right']);
        else{        

            //***************** Actualizar *****************************
            $preg_1007 =str_replace(",", "", $request->preg_1007);
            $name1007  = null;
            if($request->hasFile('osc_d1007')){
                $name1007 = $id.'_'.$request->file('osc_d1007')->getClientOriginalName(); 
                $request->file('osc_d1007')->move(public_path().'/images/', $name1007);
                // ************* Actualizamos registro **********************************
                $regcontable = regReqContablesModel::where('OSC_FOLIO',$id)        
                               ->update([  
                                      'DOC_ID1007'  => $request->doc_id1007,
                                      'OSC_D1007'   => $name1007,           
                                      //'PER_ID10'  => $request->per_id10,
                                      //'NUM_ID10'  => $request->num_id10,                
                                      //'OSC_EDO10' => $request->osc_edo10,
                                      'PREG_1007'   => $preg_1007,

                                      'IP_M'        => $ip,
                                      'LOGIN_M'     => $nombre,
                                      'FECHA_M'     => date('Y/m/d')    //date('d/m/Y')                                
                                    ]);
                toastr()->success('Julio Otros requisitos quotas 5 al millar actualizados.','¡Ok!',['positionClass' => 'toast-bottom-right']);
            }else{
                // ************* Actualizamos registro **********************************
                $regcontable = regReqContablesModel::where('OSC_FOLIO',$id)        
                               ->update([  
                                      'DOC_ID1007'  => $request->doc_id1007,
                                      //'OSC_D10'   => $name10,           
                                      //'PER_ID10'  => $request->per_id10,
                                      //'NUM_ID10'  => $request->num_id10,                
                                      //'OSC_EDO10' => $request->osc_edo10,
                                      'PREG_1007'   => $preg_1007,

                                      'IP_M'        => $ip,
                                      'LOGIN_M'     => $nombre,
                                      'FECHA_M'     => date('Y/m/d')    //date('d/m/Y')                                
                                    ]);                
                toastr()->success('Julio Otros requisitos quotas 5 al millar actualizado.','¡Ok!',['positionClass' => 'toast-bottom-right']);
            }

            /************ Bitacora inicia *************************************/ 
            setlocale(LC_TIME, "spanish");        
            $xip          = session()->get('ip');
            $xperiodo_id  = (int)date('Y');
            $xprograma_id = 1;
            $xmes_id      = (int)date('m');
            $xproceso_id  =         3;
            $xfuncion_id  =      3004;
            $xtrx_id      =        95;    //Actualizar         
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
                        toastr()->success('Trx de otros requisitos julio quotas de 5 al millar dada de alta.','¡Ok!',['positionClass' => 'toast-bottom-right']);
                    else
                        toastr()->error('Error trx de otros requisitos julio inesperado al dar de alta en bitacora. Por favor volver a interlo.','Ups!',['positionClass' => 'toast-bottom-right']);
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
                toastr()->success('Trx de otros requisitos julio quotas de 5 al millar actualizada en Bitacora.','¡Ok!',['positionClass' => 'toast-bottom-right']);
            }   /************ Bitacora termina *************************************/         
        }       /************ Termina de actualizar ********************************/
        return redirect()->route('verReqc');    
    } 

    /****************** Editar agosto otros requisitos administrativos quotas de 5 al millar **********/
    public function actionEditarReqc1008($id){
        $nombre       = session()->get('userlog');
        $pass         = session()->get('passlog');
        if($nombre == NULL AND $pass == NULL){
            return view('sicinar.login.expirada');
        }
        $usuario      = session()->get('usuario');
        $rango        = session()->get('rango');
        $arbol_id     = session()->get('arbol_id');        

        $regnumeros   = regNumerosModel::select('NUM_ID', 'NUM_DESC')->orderBy('NUM_ID','asc')
                        ->get();
        $regperiodos  = regPfiscalesModel::select('PERIODO_ID', 'PERIODO_DESC')->orderBy('PERIODO_ID','asc')
                        ->get();        
        $regperiodicidad= regPerModel::select('PER_ID', 'PER_DESC')->orderBy('PER_ID','asc')
                        ->get();     
        $regformatos  = regFormatosModel::select('FORMATO_ID','FORMATO_DESC')->get();                           
        $regosc       = regOscModel::select('OSC_ID', 'OSC_DESC','OSC_STATUS')->orderBy('OSC_DESC','asc')
                        ->get();
        $regcontable  = regReqContablesModel::select('OSC_FOLIO','OSC_ID','PERIODO_ID',
                        'DOC_ID6' ,'FORMATO_ID6' ,'OSC_D6' ,'PER_ID6' ,'NUM_ID6' ,'OSC_EDO6',
                        'DOC_ID7' ,'FORMATO_ID7' ,'OSC_D7' ,'PER_ID7' ,'NUM_ID7' ,'OSC_EDO7',
                        'DOC_ID8' ,'FORMATO_ID8' ,'OSC_D8' ,'PER_ID8' ,'NUM_ID8' ,'OSC_EDO8',
                        'DOC_ID9' ,'FORMATO_ID9' ,'OSC_D9' ,'PER_ID9' ,'NUM_ID9' ,'OSC_EDO9',                        
                        'DOC_ID10','FORMATO_ID10','OSC_D10','PER_ID10','NUM_ID10','OSC_EDO10',                        
                        'DOC_ID11','FORMATO_ID11','OSC_D11','PER_ID11','NUM_ID11','OSC_EDO11',
                        'DOC_ID1002','OSC_D1002','PER_ID1002','NUM_ID1002',
                        'DOC_ID1003','OSC_D1003','PER_ID1003','NUM_ID1003',
                        'DOC_ID1004','OSC_D1004','PER_ID1004','NUM_ID1004',
                        'DOC_ID1005','OSC_D1005','PER_ID1005','NUM_ID1005',
                        'DOC_ID1006','OSC_D1006','PER_ID1006','NUM_ID1006',
                        'DOC_ID1007','OSC_D1007','PER_ID1007','NUM_ID1007',
                        'DOC_ID1008','OSC_D1008','PER_ID1008','NUM_ID1008',
                        'DOC_ID1009','OSC_D1009','PER_ID1009','NUM_ID1009',
                        'DOC_ID1010','OSC_D1010','PER_ID1010','NUM_ID1010',
                        'DOC_ID1011','OSC_D1011','PER_ID1011','NUM_ID1011',
                        'DOC_ID1012','OSC_D1012','PER_ID1012','NUM_ID1012',
                        'PREG_1002','PREG_1003','PREG_1004','PREG_1005','PREG_1006',
                        'PREG_1007','PREG_1008','PREG_1009','PREG_1010','PREG_1011','PREG_1012',                        
                        'PREG_001','PREG_002','PREG_003','PREG_004','PREG_005','PREG_006',                        
                        'OSC_STATUS','FECREG','IP','LOGIN','FECHA_M','IP_M','LOGIN_M')
                       ->where('OSC_FOLIO', $id)
                       ->first();
        if($regcontable->count() <= 0){
            toastr()->error('No existe otros requisitos agosto quotas de 5 al millar.','Lo siento!',['positionClass' => 'toast-bottom-right']);
            //return redirect()->route('nuevocontable');
        }
        return view('sicinar.requisitos.editarReqc1008',compact('nombre','usuario','regosc','regcontable','regnumeros', 'regperiodos','regperiodicidad','regformatos'));
    }


    public function actionActualizarReqc1008(reqcontablesRequest $request, $id){
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
        $regcontable = regReqContablesModel::where('OSC_FOLIO',$id);
        if($regcontable->count() <= 0)
            toastr()->error('No existe otros requisitos agosto quotas de 5 al millar.','¡Por favor volver a intentar!',['positionClass' => 'toast-bottom-right']);
        else{        
            //***************** Actualizar *****************************
            $preg_1008 =str_replace(",", "", $request->preg_1008);
            $name1008  = null;
            if($request->hasFile('osc_d1008')){
                $name1008 = $id.'_'.$request->file('osc_d1008')->getClientOriginalName(); 
                $request->file('osc_d1008')->move(public_path().'/images/', $name1008);
                // ************* Actualizamos registro **********************************
                $regcontable = regReqContablesModel::where('OSC_FOLIO',$id)        
                               ->update([  
                                      'DOC_ID1008'  => $request->doc_id1008,
                                      'OSC_D1008'   => $name1008,           
                                      //'PER_ID10'  => $request->per_id10,
                                      //'NUM_ID10'  => $request->num_id10,                
                                      //'OSC_EDO10' => $request->osc_edo10,
                                      'PREG_1008'   => $preg_1008,

                                      'IP_M'        => $ip,
                                      'LOGIN_M'     => $nombre,
                                      'FECHA_M'     => date('Y/m/d')    //date('d/m/Y')                                
                                    ]);
                toastr()->success('Agosto Otros requisitos quotas 5 al millar actualizados.','¡Ok!',['positionClass' => 'toast-bottom-right']);
            }else{
                // ************* Actualizamos registro **********************************
                $regcontable = regReqContablesModel::where('OSC_FOLIO',$id)        
                               ->update([  
                                      'DOC_ID1008'  => $request->doc_id1008,
                                      //'OSC_D10'   => $name10,           
                                      //'PER_ID10'  => $request->per_id10,
                                      //'NUM_ID10'  => $request->num_id10,                
                                      //'OSC_EDO10' => $request->osc_edo10,
                                      'PREG_1008'   => $preg_1008,

                                      'IP_M'        => $ip,
                                      'LOGIN_M'     => $nombre,
                                      'FECHA_M'     => date('Y/m/d')    //date('d/m/Y')                                
                                    ]);                
                toastr()->success('Agosto Otros requisitos quotas 5 al millar actualizado.','¡Ok!',['positionClass' => 'toast-bottom-right']);
            }

            /************ Bitacora inicia *************************************/ 
            setlocale(LC_TIME, "spanish");        
            $xip          = session()->get('ip');
            $xperiodo_id  = (int)date('Y');
            $xprograma_id = 1;
            $xmes_id      = (int)date('m');
            $xproceso_id  =         3;
            $xfuncion_id  =      3004;
            $xtrx_id      =        95;    //Actualizar         
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
                        toastr()->success('Trx de otros requisitos agosto quotas de 5 al millar dada de alta.','¡Ok!',['positionClass' => 'toast-bottom-right']);
                    else
                        toastr()->error('Error trx de otros requisitos agosto inesperado al dar de alta en bitacora. Por favor volver a interlo.','Ups!',['positionClass' => 'toast-bottom-right']);
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
                toastr()->success('Trx de otros requisitos agosto quotas de 5 al millar actualizada en Bitacora.','¡Ok!',['positionClass' => 'toast-bottom-right']);
            }   /************ Bitacora termina *************************************/         
        }       /************ Termina de actualizar ********************************/
        return redirect()->route('verReqc');    
    } 

    /****************** Editar septiembre otros requisitos administrativos quotas de 5 al millar **********/
    public function actionEditarReqc1009($id){
        $nombre       = session()->get('userlog');
        $pass         = session()->get('passlog');
        if($nombre == NULL AND $pass == NULL){
            return view('sicinar.login.expirada');
        }
        $usuario      = session()->get('usuario');
        $rango        = session()->get('rango');
        $arbol_id     = session()->get('arbol_id');        

        $regnumeros   = regNumerosModel::select('NUM_ID', 'NUM_DESC')->orderBy('NUM_ID','asc')
                        ->get();
        $regperiodos  = regPfiscalesModel::select('PERIODO_ID', 'PERIODO_DESC')->orderBy('PERIODO_ID','asc')
                        ->get();        
        $regperiodicidad= regPerModel::select('PER_ID', 'PER_DESC')->orderBy('PER_ID','asc')
                        ->get();     
        $regformatos  = regFormatosModel::select('FORMATO_ID','FORMATO_DESC')->get();                           
        $regosc       = regOscModel::select('OSC_ID', 'OSC_DESC','OSC_STATUS')->orderBy('OSC_DESC','asc')
                        ->get();
        $regcontable  = regReqContablesModel::select('OSC_FOLIO','OSC_ID','PERIODO_ID',
                        'DOC_ID6' ,'FORMATO_ID6' ,'OSC_D6' ,'PER_ID6' ,'NUM_ID6' ,'OSC_EDO6',
                        'DOC_ID7' ,'FORMATO_ID7' ,'OSC_D7' ,'PER_ID7' ,'NUM_ID7' ,'OSC_EDO7',
                        'DOC_ID8' ,'FORMATO_ID8' ,'OSC_D8' ,'PER_ID8' ,'NUM_ID8' ,'OSC_EDO8',
                        'DOC_ID9' ,'FORMATO_ID9' ,'OSC_D9' ,'PER_ID9' ,'NUM_ID9' ,'OSC_EDO9',                        
                        'DOC_ID10','FORMATO_ID10','OSC_D10','PER_ID10','NUM_ID10','OSC_EDO10',                        
                        'DOC_ID11','FORMATO_ID11','OSC_D11','PER_ID11','NUM_ID11','OSC_EDO11',
                        'DOC_ID1002','OSC_D1002','PER_ID1002','NUM_ID1002',
                        'DOC_ID1003','OSC_D1003','PER_ID1003','NUM_ID1003',
                        'DOC_ID1004','OSC_D1004','PER_ID1004','NUM_ID1004',
                        'DOC_ID1005','OSC_D1005','PER_ID1005','NUM_ID1005',
                        'DOC_ID1006','OSC_D1006','PER_ID1006','NUM_ID1006',
                        'DOC_ID1007','OSC_D1007','PER_ID1007','NUM_ID1007',
                        'DOC_ID1008','OSC_D1008','PER_ID1008','NUM_ID1008',
                        'DOC_ID1009','OSC_D1009','PER_ID1009','NUM_ID1009',
                        'DOC_ID1010','OSC_D1010','PER_ID1010','NUM_ID1010',
                        'DOC_ID1011','OSC_D1011','PER_ID1011','NUM_ID1011',
                        'DOC_ID1012','OSC_D1012','PER_ID1012','NUM_ID1012',
                        'PREG_1002','PREG_1003','PREG_1004','PREG_1005','PREG_1006',
                        'PREG_1007','PREG_1008','PREG_1009','PREG_1010','PREG_1011','PREG_1012',                        
                        'PREG_001','PREG_002','PREG_003','PREG_004','PREG_005','PREG_006',                        
                        'OSC_STATUS','FECREG','IP','LOGIN','FECHA_M','IP_M','LOGIN_M')
                       ->where('OSC_FOLIO', $id)
                       ->first();
        if($regcontable->count() <= 0){
            toastr()->error('No existe otros requisitos septiembre quotas de 5 al millar.','Lo siento!',['positionClass' => 'toast-bottom-right']);
        }
        return view('sicinar.requisitos.editarReqc1009',compact('nombre','usuario','regosc','regcontable','regnumeros', 'regperiodos','regperiodicidad','regformatos'));
    }


    public function actionActualizarReqc1009(reqcontablesRequest $request, $id){
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
        $regcontable = regReqContablesModel::where('OSC_FOLIO',$id);
        if($regcontable->count() <= 0)
            toastr()->error('No existe otros requisitos septiembre quotas de 5 al millar.','¡Por favor volver a intentar!',['positionClass' => 'toast-bottom-right']);
        else{        

            //***************** Actualizar *****************************
            $preg_1009 =str_replace(",", "", $request->preg_1009);  
            $name1009  = null;
            if($request->hasFile('osc_d1009')){
                $name1009 = $id.'_'.$request->file('osc_d1009')->getClientOriginalName(); 
                $request->file('osc_d1009')->move(public_path().'/images/', $name1009);
                // ************* Actualizamos registro **********************************
                $regcontable = regReqContablesModel::where('OSC_FOLIO',$id)        
                               ->update([  
                                      'DOC_ID1009'  => $request->doc_id1009,
                                      'OSC_D1009'   => $name1009,           
                                      //'PER_ID10'  => $request->per_id10,
                                      //'NUM_ID10'  => $request->num_id10,                
                                      //'OSC_EDO10' => $request->osc_edo10,
                                      'PREG_1009'   => $preg_1009,

                                      'IP_M'        => $ip,
                                      'LOGIN_M'     => $nombre,
                                      'FECHA_M'     => date('Y/m/d')    //date('d/m/Y')                                
                                    ]);
                toastr()->success('Septiembre Otros requisitos quotas 5 al millar actualizados.','¡Ok!',['positionClass' => 'toast-bottom-right']);
            }else{
                // ************* Actualizamos registro **********************************
                $regcontable = regReqContablesModel::where('OSC_FOLIO',$id)        
                               ->update([  
                                      'DOC_ID1009'  => $request->doc_id1009,
                                      //'OSC_D10'   => $name10,           
                                      //'PER_ID10'  => $request->per_id10,
                                      //'NUM_ID10'  => $request->num_id10,                
                                      //'OSC_EDO10' => $request->osc_edo10,
                                      'PREG_1009'   => $preg_1009,

                                      'IP_M'        => $ip,
                                      'LOGIN_M'     => $nombre,
                                      'FECHA_M'     => date('Y/m/d')    //date('d/m/Y')                                
                                    ]);                
                toastr()->success('Septiembre Otros requisitos quotas 5 al millar actualizado.','¡Ok!',['positionClass' => 'toast-bottom-right']);
            }

            /************ Bitacora inicia *************************************/ 
            setlocale(LC_TIME, "spanish");        
            $xip          = session()->get('ip');
            $xperiodo_id  = (int)date('Y');
            $xprograma_id = 1;
            $xmes_id      = (int)date('m');
            $xproceso_id  =         3;
            $xfuncion_id  =      3004;
            $xtrx_id      =        95;    //Actualizar         
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
                        toastr()->success('Trx de otros requisitos septiembre quotas de 5 al millar dada de alta.','¡Ok!',['positionClass' => 'toast-bottom-right']);
                    else
                        toastr()->error('Error trx de otros requisitos septiembre inesperado al dar de alta en bitacora. Por favor volver a interlo.','Ups!',['positionClass' => 'toast-bottom-right']);
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
                toastr()->success('Trx de otros requisitos septiembre quotas de 5 al millar actualizada en Bitacora.','¡Ok!',['positionClass' => 'toast-bottom-right']);
            }   /************ Bitacora termina *************************************/         
        }       /************ Termina de actualizar ********************************/
        return redirect()->route('verReqc');    
    } 

    /****************** Editar octubre otros requisitos administrativos quotas de 5 al millar **********/
    public function actionEditarReqc1010($id){
        $nombre       = session()->get('userlog');
        $pass         = session()->get('passlog');
        if($nombre == NULL AND $pass == NULL){
            return view('sicinar.login.expirada');
        }
        $usuario      = session()->get('usuario');
        $rango        = session()->get('rango');
        $arbol_id     = session()->get('arbol_id');        

        $regnumeros   = regNumerosModel::select('NUM_ID', 'NUM_DESC')->orderBy('NUM_ID','asc')
                        ->get();
        $regperiodos  = regPfiscalesModel::select('PERIODO_ID', 'PERIODO_DESC')->orderBy('PERIODO_ID','asc')
                        ->get();        
        $regperiodicidad= regPerModel::select('PER_ID', 'PER_DESC')->orderBy('PER_ID','asc')
                        ->get();     
        $regformatos  = regFormatosModel::select('FORMATO_ID','FORMATO_DESC')->get();                           
        $regosc       = regOscModel::select('OSC_ID', 'OSC_DESC','OSC_STATUS')->orderBy('OSC_DESC','asc')
                        ->get();
        $regcontable  = regReqContablesModel::select('OSC_FOLIO','OSC_ID','PERIODO_ID',
                        'DOC_ID6' ,'FORMATO_ID6' ,'OSC_D6' ,'PER_ID6' ,'NUM_ID6' ,'OSC_EDO6',
                        'DOC_ID7' ,'FORMATO_ID7' ,'OSC_D7' ,'PER_ID7' ,'NUM_ID7' ,'OSC_EDO7',
                        'DOC_ID8' ,'FORMATO_ID8' ,'OSC_D8' ,'PER_ID8' ,'NUM_ID8' ,'OSC_EDO8',
                        'DOC_ID9' ,'FORMATO_ID9' ,'OSC_D9' ,'PER_ID9' ,'NUM_ID9' ,'OSC_EDO9',                        
                        'DOC_ID10','FORMATO_ID10','OSC_D10','PER_ID10','NUM_ID10','OSC_EDO10',                        
                        'DOC_ID11','FORMATO_ID11','OSC_D11','PER_ID11','NUM_ID11','OSC_EDO11',
                        'DOC_ID1002','OSC_D1002','PER_ID1002','NUM_ID1002',
                        'DOC_ID1003','OSC_D1003','PER_ID1003','NUM_ID1003',
                        'DOC_ID1004','OSC_D1004','PER_ID1004','NUM_ID1004',
                        'DOC_ID1005','OSC_D1005','PER_ID1005','NUM_ID1005',
                        'DOC_ID1006','OSC_D1006','PER_ID1006','NUM_ID1006',
                        'DOC_ID1007','OSC_D1007','PER_ID1007','NUM_ID1007',
                        'DOC_ID1008','OSC_D1008','PER_ID1008','NUM_ID1008',
                        'DOC_ID1009','OSC_D1009','PER_ID1009','NUM_ID1009',
                        'DOC_ID1010','OSC_D1010','PER_ID1010','NUM_ID1010',
                        'DOC_ID1011','OSC_D1011','PER_ID1011','NUM_ID1011',
                        'DOC_ID1012','OSC_D1012','PER_ID1012','NUM_ID1012',
                        'PREG_1002','PREG_1003','PREG_1004','PREG_1005','PREG_1006',
                        'PREG_1007','PREG_1008','PREG_1009','PREG_1010','PREG_1011','PREG_1012',                        
                        'PREG_001','PREG_002','PREG_003','PREG_004','PREG_005','PREG_006',                        
                        'OSC_STATUS','FECREG','IP','LOGIN','FECHA_M','IP_M','LOGIN_M')
                       ->where('OSC_FOLIO', $id)
                       ->first();
        if($regcontable->count() <= 0){
            toastr()->error('No existe otros requisitos octubre quotas de 5 al millar.','Lo siento!',['positionClass' => 'toast-bottom-right']);
        }
        return view('sicinar.requisitos.editarReqc1010',compact('nombre','usuario','regosc','regcontable','regnumeros', 'regperiodos','regperiodicidad','regformatos'));
    }


    public function actionActualizarReqc1010(reqcontablesRequest $request, $id){
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
        $regcontable = regReqContablesModel::where('OSC_FOLIO',$id);
        if($regcontable->count() <= 0)
            toastr()->error('No existe otros requisitos octubre quotas de 5 al millar.','¡Por favor volver a intentar!',['positionClass' => 'toast-bottom-right']);
        else{        
            //***************** Actualizar *****************************            
            $preg_1010 =str_replace(",", "", $request->preg_1010); 
            $name1010  = null;
            if($request->hasFile('osc_d1010')){
                $name1010 = $id.'_'.$request->file('osc_d1010')->getClientOriginalName(); 
                $request->file('osc_d1010')->move(public_path().'/images/', $name1010);

                // ************* Actualizamos registro **********************************
                $regcontable = regReqContablesModel::where('OSC_FOLIO',$id)        
                               ->update([  
                                      'DOC_ID1010'  => $request->doc_id1010,             
                                      'OSC_D1010'   => $name1010,           
                                      //'PER_ID10'  => $request->per_id10,
                                      //'NUM_ID10'  => $request->num_id10,                
                                      //'OSC_EDO10' => $request->osc_edo10,
                                      'PREG_1010'   => $preg_1010,

                                      'IP_M'        => $ip,
                                      'LOGIN_M'     => $nombre,
                                      'FECHA_M'     => date('Y/m/d')    //date('d/m/Y')                                
                                    ]);
                toastr()->success('Octubre Otros requisitos quotas 5 al millar actualizados.','¡Ok!',['positionClass' => 'toast-bottom-right']);
            }else{
                // ************* Actualizamos registro **********************************
                $regcontable = regReqContablesModel::where('OSC_FOLIO',$id)        
                               ->update([  
                                      'DOC_ID1010'  => $request->doc_id1010,
                                      //'OSC_D10'   => $name10,           
                                      //'PER_ID10'  => $request->per_id10,
                                      //'NUM_ID10'  => $request->num_id10,                
                                      //'OSC_EDO10' => $request->osc_edo10,
                                      'PREG_1010'   => $preg_1010,

                                      'IP_M'        => $ip,
                                      'LOGIN_M'     => $nombre,
                                      'FECHA_M'     => date('Y/m/d')    //date('d/m/Y')                                
                                    ]);                
                toastr()->success('Octubre Otros requisitos quotas 5 al millar actualizado.','¡Ok!',['positionClass' => 'toast-bottom-right']);
            }

            /************ Bitacora inicia *************************************/ 
            setlocale(LC_TIME, "spanish");        
            $xip          = session()->get('ip');
            $xperiodo_id  = (int)date('Y');
            $xprograma_id = 1;
            $xmes_id      = (int)date('m');
            $xproceso_id  =         3;
            $xfuncion_id  =      3004;
            $xtrx_id      =        95;    //Actualizar         
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
                        toastr()->success('Trx de otros requisitos octubre quotas de 5 al millar dada de alta.','¡Ok!',['positionClass' => 'toast-bottom-right']);
                    else
                        toastr()->error('Error trx de otros requisitos octubre inesperado al dar de alta en bitacora. Por favor volver a interlo.','Ups!',['positionClass' => 'toast-bottom-right']);
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
                toastr()->success('Trx de otros requisitos octubre quotas de 5 al millar actualizada en Bitacora.','¡Ok!',['positionClass' => 'toast-bottom-right']);
            }   /************ Bitacora termina *************************************/         
        }       /************ Termina de actualizar ********************************/
        return redirect()->route('verReqc');    
    } 

    /****************** Editar noviembre otros requisitos administrativos quotas de 5 al millar **********/
    public function actionEditarReqc1011($id){
        $nombre       = session()->get('userlog');
        $pass         = session()->get('passlog');
        if($nombre == NULL AND $pass == NULL){
            return view('sicinar.login.expirada');
        }
        $usuario      = session()->get('usuario');
        $rango        = session()->get('rango');
        $arbol_id     = session()->get('arbol_id');        

        $regnumeros   = regNumerosModel::select('NUM_ID', 'NUM_DESC')->orderBy('NUM_ID','asc')
                        ->get();
        $regperiodos  = regPfiscalesModel::select('PERIODO_ID', 'PERIODO_DESC')->orderBy('PERIODO_ID','asc')
                        ->get();        
        $regperiodicidad= regPerModel::select('PER_ID', 'PER_DESC')->orderBy('PER_ID','asc')
                        ->get();     
        $regformatos  = regFormatosModel::select('FORMATO_ID','FORMATO_DESC')->get();                           
        $regosc       = regOscModel::select('OSC_ID', 'OSC_DESC','OSC_STATUS')->orderBy('OSC_DESC','asc')
                        ->get();
        $regcontable  = regReqContablesModel::select('OSC_FOLIO','OSC_ID','PERIODO_ID',
                        'DOC_ID6' ,'FORMATO_ID6' ,'OSC_D6' ,'PER_ID6' ,'NUM_ID6' ,'OSC_EDO6',
                        'DOC_ID7' ,'FORMATO_ID7' ,'OSC_D7' ,'PER_ID7' ,'NUM_ID7' ,'OSC_EDO7',
                        'DOC_ID8' ,'FORMATO_ID8' ,'OSC_D8' ,'PER_ID8' ,'NUM_ID8' ,'OSC_EDO8',
                        'DOC_ID9' ,'FORMATO_ID9' ,'OSC_D9' ,'PER_ID9' ,'NUM_ID9' ,'OSC_EDO9',                        
                        'DOC_ID10','FORMATO_ID10','OSC_D10','PER_ID10','NUM_ID10','OSC_EDO10',                        
                        'DOC_ID11','FORMATO_ID11','OSC_D11','PER_ID11','NUM_ID11','OSC_EDO11',
                        'DOC_ID1002','OSC_D1002','PER_ID1002','NUM_ID1002',
                        'DOC_ID1003','OSC_D1003','PER_ID1003','NUM_ID1003',
                        'DOC_ID1004','OSC_D1004','PER_ID1004','NUM_ID1004',
                        'DOC_ID1005','OSC_D1005','PER_ID1005','NUM_ID1005',
                        'DOC_ID1006','OSC_D1006','PER_ID1006','NUM_ID1006',
                        'DOC_ID1007','OSC_D1007','PER_ID1007','NUM_ID1007',
                        'DOC_ID1008','OSC_D1008','PER_ID1008','NUM_ID1008',
                        'DOC_ID1009','OSC_D1009','PER_ID1009','NUM_ID1009',
                        'DOC_ID1010','OSC_D1010','PER_ID1010','NUM_ID1010',
                        'DOC_ID1011','OSC_D1011','PER_ID1011','NUM_ID1011',
                        'DOC_ID1012','OSC_D1012','PER_ID1012','NUM_ID1012',
                        'PREG_1002','PREG_1003','PREG_1004','PREG_1005','PREG_1006',
                        'PREG_1007','PREG_1008','PREG_1009','PREG_1010','PREG_1011','PREG_1012',                        
                        'PREG_001','PREG_002','PREG_003','PREG_004','PREG_005','PREG_006',                        
                        'OSC_STATUS','FECREG','IP','LOGIN','FECHA_M','IP_M','LOGIN_M')
                       ->where('OSC_FOLIO', $id)
                       ->first();
        if($regcontable->count() <= 0){
            toastr()->error('No existe otros requisitos noviembre quotas de 5 al millar.','Lo siento!',['positionClass' => 'toast-bottom-right']);
        }
        return view('sicinar.requisitos.editarReqc1011',compact('nombre','usuario','regosc','regcontable','regnumeros', 'regperiodos','regperiodicidad','regformatos'));
    }


    public function actionActualizarReqc1011(reqcontablesRequest $request, $id){
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
        $regcontable = regReqContablesModel::where('OSC_FOLIO',$id);
        if($regcontable->count() <= 0)
            toastr()->error('No existe otros requisitos noviembre quotas de 5 al millar.','¡Por favor volver a intentar!',['positionClass' => 'toast-bottom-right']);
        else{        

            //***************** Actualizar *****************************
            $preg_1011 = str_replace(",", "", $request->preg_1011); 
            $name1011  = null;
            if($request->hasFile('osc_d1011')){
                $name1011 = $id.'_'.$request->file('osc_d1011')->getClientOriginalName(); 
                $request->file('osc_d1011')->move(public_path().'/images/', $name1011);
                // ************* Actualizamos registro **********************************
                $regcontable = regReqContablesModel::where('OSC_FOLIO',$id)        
                               ->update([  
                                      'DOC_ID1011'  => $request->doc_id1011,
                                      'OSC_D1011'   => $name1011,           
                                      //'PER_ID10'  => $request->per_id10,
                                      //'NUM_ID10'  => $request->num_id10,                
                                      //'OSC_EDO10' => $request->osc_edo10,
                                      'PREG_1011'   => $preg_1011,

                                      'IP_M'        => $ip,
                                      'LOGIN_M'     => $nombre,
                                      'FECHA_M'     => date('Y/m/d')    //date('d/m/Y')                                
                                    ]);
                toastr()->success('Noviembre Otros requisitos quotas 5 al millar actualizados.','¡Ok!',['positionClass' => 'toast-bottom-right']);
            }else{
                // ************* Actualizamos registro **********************************
                $regcontable = regReqContablesModel::where('OSC_FOLIO',$id)        
                               ->update([  
                                      'DOC_ID1011'  => $request->doc_id1011,
                                      //'OSC_D10'   => $name10,           
                                      //'PER_ID10'  => $request->per_id10,
                                      //'NUM_ID10'  => $request->num_id10,                
                                      //'OSC_EDO10' => $request->osc_edo10,
                                      'PREG_1011'   => $preg_1011,

                                      'IP_M'        => $ip,
                                      'LOGIN_M'     => $nombre,
                                      'FECHA_M'     => date('Y/m/d')    //date('d/m/Y')                                
                                    ]);                
                toastr()->success('Noviembre Otros requisitos quotas 5 al millar actualizado.','¡Ok!',['positionClass' => 'toast-bottom-right']);
            }

            /************ Bitacora inicia *************************************/ 
            setlocale(LC_TIME, "spanish");        
            $xip          = session()->get('ip');
            $xperiodo_id  = (int)date('Y');
            $xprograma_id = 1;
            $xmes_id      = (int)date('m');
            $xproceso_id  =         3;
            $xfuncion_id  =      3004;
            $xtrx_id      =        95;    //Actualizar         
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
                        toastr()->success('Trx de otros requisitos noviembre quotas de 5 al millar dada de alta.','¡Ok!',['positionClass' => 'toast-bottom-right']);
                    else
                        toastr()->error('Error trx de otros requisitos noviembre inesperado al dar de alta en bitacora. Por favor volver a interlo.','Ups!',['positionClass' => 'toast-bottom-right']);
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
                toastr()->success('Trx de otros requisitos noviembre quotas de 5 al millar actualizada en Bitacora.','¡Ok!',['positionClass' => 'toast-bottom-right']);
            }   /************ Bitacora termina *************************************/         
        }       /************ Termina de actualizar ********************************/
        return redirect()->route('verReqc');    
    } 

    /****************** Editar diciembre otros requisitos administrativos quotas de 5 al millar **********/
    public function actionEditarReqc1012($id){
        $nombre       = session()->get('userlog');
        $pass         = session()->get('passlog');
        if($nombre == NULL AND $pass == NULL){
            return view('sicinar.login.expirada');
        }
        $usuario      = session()->get('usuario');
        $rango        = session()->get('rango');
        $arbol_id     = session()->get('arbol_id');        

        $regnumeros   = regNumerosModel::select('NUM_ID', 'NUM_DESC')->orderBy('NUM_ID','asc')
                        ->get();
        $regperiodos  = regPfiscalesModel::select('PERIODO_ID', 'PERIODO_DESC')->orderBy('PERIODO_ID','asc')
                        ->get();        
        $regperiodicidad= regPerModel::select('PER_ID', 'PER_DESC')->orderBy('PER_ID','asc')
                        ->get();     
        $regformatos  = regFormatosModel::select('FORMATO_ID','FORMATO_DESC')->get();                           
        $regosc       = regOscModel::select('OSC_ID', 'OSC_DESC','OSC_STATUS')->orderBy('OSC_DESC','asc')
                        ->get();
        $regcontable  = regReqContablesModel::select('OSC_FOLIO','OSC_ID','PERIODO_ID',
                        'DOC_ID6' ,'FORMATO_ID6' ,'OSC_D6' ,'PER_ID6' ,'NUM_ID6' ,'OSC_EDO6',
                        'DOC_ID7' ,'FORMATO_ID7' ,'OSC_D7' ,'PER_ID7' ,'NUM_ID7' ,'OSC_EDO7',
                        'DOC_ID8' ,'FORMATO_ID8' ,'OSC_D8' ,'PER_ID8' ,'NUM_ID8' ,'OSC_EDO8',
                        'DOC_ID9' ,'FORMATO_ID9' ,'OSC_D9' ,'PER_ID9' ,'NUM_ID9' ,'OSC_EDO9',                        
                        'DOC_ID10','FORMATO_ID10','OSC_D10','PER_ID10','NUM_ID10','OSC_EDO10',                        
                        'DOC_ID11','FORMATO_ID11','OSC_D11','PER_ID11','NUM_ID11','OSC_EDO11',
                        'DOC_ID1002','OSC_D1002','PER_ID1002','NUM_ID1002',
                        'DOC_ID1003','OSC_D1003','PER_ID1003','NUM_ID1003',
                        'DOC_ID1004','OSC_D1004','PER_ID1004','NUM_ID1004',
                        'DOC_ID1005','OSC_D1005','PER_ID1005','NUM_ID1005',
                        'DOC_ID1006','OSC_D1006','PER_ID1006','NUM_ID1006',
                        'DOC_ID1007','OSC_D1007','PER_ID1007','NUM_ID1007',
                        'DOC_ID1008','OSC_D1008','PER_ID1008','NUM_ID1008',
                        'DOC_ID1009','OSC_D1009','PER_ID1009','NUM_ID1009',
                        'DOC_ID1010','OSC_D1010','PER_ID1010','NUM_ID1010',
                        'DOC_ID1011','OSC_D1011','PER_ID1011','NUM_ID1011',
                        'DOC_ID1012','OSC_D1012','PER_ID1012','NUM_ID1012',
                        'PREG_1002','PREG_1003','PREG_1004','PREG_1005','PREG_1006',
                        'PREG_1007','PREG_1008','PREG_1009','PREG_1010','PREG_1011','PREG_1012',                        
                        'PREG_001','PREG_002','PREG_003','PREG_004','PREG_005','PREG_006',                        
                        'OSC_STATUS','FECREG','IP','LOGIN','FECHA_M','IP_M','LOGIN_M')
                       ->where('OSC_FOLIO', $id)
                       ->first();
        if($regcontable->count() <= 0){
            toastr()->error('No existe otros requisitos diciembre quotas de 5 al millar.','Lo siento!',['positionClass' => 'toast-bottom-right']);
        }
        return view('sicinar.requisitos.editarReqc1012',compact('nombre','usuario','regosc','regcontable','regnumeros', 'regperiodos','regperiodicidad','regformatos'));
    }


    public function actionActualizarReqc1012(reqcontablesRequest $request, $id){
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
        $regcontable = regReqContablesModel::where('OSC_FOLIO',$id);
        if($regcontable->count() <= 0)
            toastr()->error('No existe otros requisitos diciembre quotas de 5 al millar.','¡Por favor volver a intentar!',['positionClass' => 'toast-bottom-right']);
        else{        

            //***************** Actualizar *****************************
            $preg_1012 =str_replace(",", "", $request->preg_1012);            
            $name1012 = null;
            if($request->hasFile('osc_d1012')){
                $name1012 = $id.'_'.$request->file('osc_d1012')->getClientOriginalName(); 
                $request->file('osc_d1012')->move(public_path().'/images/', $name1012);

                // ************* Actualizamos registro **********************************
                $regcontable = regReqContablesModel::where('OSC_FOLIO',$id)        
                               ->update([  
                                      'DOC_ID1012'    => $request->doc_id1012,
                                      'OSC_D1012'     => $name1012,           
                                      //'PER_ID10'    => $request->per_id10,
                                      //'NUM_ID10'    => $request->num_id10,                
                                      //'OSC_EDO10'   => $request->osc_edo10,
                                      'PREG_1012'     => $preg_1012,

                                      'IP_M'          => $ip,
                                      'LOGIN_M'       => $nombre,
                                      'FECHA_M'       => date('Y/m/d')    //date('d/m/Y')                                
                                    ]);
                toastr()->success('Diciembre otros requisitos quotas 5 al millar actualizado.','¡Ok!',['positionClass' => 'toast-bottom-right']);
            }else{
                // ************* Actualizamos registro **********************************
                $regcontable = regReqContablesModel::where('OSC_FOLIO',$id)        
                               ->update([  
                                      'DOC_ID1012'    => $request->doc_id1012,
                                      //'OSC_D10'     => $name10,           
                                      //'PER_ID10'    => $request->per_id10,
                                      //'NUM_ID10'    => $request->num_id10,                
                                      //'OSC_EDO10'   => $request->osc_edo10,
                                      'PREG_1012'     => $preg_1012,

                                      'IP_M'          => $ip,
                                      'LOGIN_M'       => $nombre,
                                      'FECHA_M'       => date('Y/m/d')    //date('d/m/Y')                                
                                    ]);                
                toastr()->success('Diciembre otros requisitos quotas 5 al millar actualizado.','¡Ok!',['positionClass' => 'toast-bottom-right']);
            }

            /************ Bitacora inicia *************************************/ 
            setlocale(LC_TIME, "spanish");        
            $xip          = session()->get('ip');
            $xperiodo_id  = (int)date('Y');
            $xprograma_id = 1;
            $xmes_id      = (int)date('m');
            $xproceso_id  =         3;
            $xfuncion_id  =      3004;
            $xtrx_id      =        95;    //Actualizar         
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
                        toastr()->success('Trx de otros requisitos diciembre quotas de 5 al millar dada de alta.','¡Ok!',['positionClass' => 'toast-bottom-right']);
                    else
                        toastr()->error('Error trx de otros requisitos diciembre inesperado al dar de alta en bitacora. Por favor volver a interlo.','Ups!',['positionClass' => 'toast-bottom-right']);
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
                toastr()->success('Trx de otros requisitos diciembre quotas de 5 al millar actualizada en Bitacora.','¡Ok!',['positionClass' => 'toast-bottom-right']);
            }   /************ Bitacora termina *************************************/         
        }       /************ Termina de actualizar ********************************/
        return redirect()->route('verReqc');    
    } 
    //***** Borrar registro completo ***********************
    public function actionBorrarReqc($id){
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

        /************ Elimina transacción de asistencia social y contable ***************/
        $regcontable = regReqContablesModel::where('OSC_FOLIO',$id);
        if($regcontable->count() <= 0)
            toastr()->error('No existe requisito contable.','¡Por favor volver a intentar!',['positionClass' => 'toast-bottom-right']);
        else{        
            $regcontable->delete();
            toastr()->success('Requisito contable eliminado.','¡Ok!',['positionClass' => 'toast-bottom-right']);

            //echo 'Ya entre aboorar registro..........';
            /************ Bitacora inicia *************************************/ 
            setlocale(LC_TIME, "spanish");        
            $xip          = session()->get('ip');
            $xperiodo_id  = (int)date('Y');
            $xprograma_id = 1;
            $xmes_id      = (int)date('m');
            $xproceso_id  =         3;
            $xfuncion_id  =      3004;
            $xtrx_id      =        96;     // borrar 
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
        }       /************* Termina de eliminar  *********************************/
        return redirect()->route('verReqc');
    }    

}
