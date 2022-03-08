<?php
//**************************************************************/
//* File:       rOperativosController.php
//* Proyecto:   Sistema SRPE.V1 DGPS
//¨Función:     Clases para el modulo de requisitos operativos
//* Autor:      Ing. Silverio Baltazar Barrientos Zarate
//* Modifico:   Ing. Silverio Baltazar Barrientos Zarate
//* Fecha act.: oct 2021
//* @Derechos reservados. Gobierno del Estado de México
//*************************************************************/
namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests\reqoperativosRequest;
use App\Http\Requests\reqoperativos1Request;
use App\Http\Requests\reqoperativos2Request;
use App\Http\Requests\reqoperativos3Request;
use App\regOscModel;
use App\regBitacoraModel;
use App\regPfiscalesModel;
use App\regReqOperativosModel;
use App\regPerModel;
use App\regNumerosModel;
use App\regFormatosModel;
 
// Exportar a excel 
use Maatwebsite\Excel\Facades\Excel;

// Exportar a pdf
use PDF;
//use Options;

class rOperativosController extends Controller
{

    //******** Buscar requisitos operativos *****//
    public function actionBuscarReqop(Request $request){
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
            $regoperativo = regReqOperativosModel::join('PE_OSC','PE_OSC.OSC_ID','=','PE_REQUISITOS_OPERATIVOS.OSC_ID')
                            ->select( 'PE_OSC.OSC_DESC','PE_REQUISITOS_OPERATIVOS.*')
                            ->orderBy('PE_REQUISITOS_OPERATIVOS.PERIODO_ID','ASC')
                            ->orderBy('PE_REQUISITOS_OPERATIVOS.OSC_ID'    ,'ASC')                            
                            ->orderBy('PE_REQUISITOS_OPERATIVOS.OSC_FOLIO' ,'ASC')
                            ->fper($fper)           //Metodos personalizados es equvalente a ->where('OSC_DESC', 'LIKE', "%$name%");
                            ->idd($idd)             //Metodos personalizados  
                            ->nameiap($nameiap)     //Metodos personalizados                                                                                                            
                            ->paginate(30);
        }else{  
            $regosc       = regOscModel::select('OSC_ID', 'OSC_DESC','OSC_STATUS')
                            ->where('OSC_ID',$arbol_id)
                            ->get();                                  
            $regoperativo = regReqOperativosModel::join('PE_OSC','PE_OSC.OSC_ID','=','PE_REQUISITOS_OPERATIVOS.OSC_ID')
                            ->select( 'PE_OSC.OSC_DESC','PE_REQUISITOS_OPERATIVOS.*')
                            ->where(  'PE_REQUISITOS_OPERATIVOS.OSC_ID'    ,$arbol_id)
                            ->orderBy('PE_REQUISITOS_OPERATIVOS.PERIODO_ID','ASC')
                            ->orderBy('PE_REQUISITOS_OPERATIVOS.OSC_ID'    ,'ASC')
                            ->orderBy('PE_REQUISITOS_OPERATIVOS.OSC_FOLIO' ,'ASC') 
                            ->fper($fper)           //Metodos personalizados es equvalente a ->where('OSC_DESC', 'LIKE', "%$name%");
                            ->idd($idd)             //Metodos personalizados
                            ->nameiap($nameiap)     //Metodos personalizados                                                                               
                            ->paginate(30);            
        }
        if($regoperativo->count() <= 0){
            toastr()->error('No existen requisitos operativos.','Lo siento!',['positionClass' => 'toast-bottom-right']);
        }
        return view('sicinar.requisitos.verReqop',compact('nombre','usuario','regosc','regperiodicidad','regnumeros', 'regperiodos','regoperativo','regformatos'));
    }

    //******** Mostrar requisitos operativos *****//
    public function actionVerReqop(){
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
            $regoperativo  = regReqOperativosModel::select('OSC_FOLIO','OSC_ID','PERIODO_ID',
                            'DOC_ID1' ,'FORMATO_ID1' ,'OSC_D1' ,'PER_ID1' ,'NUM_ID1' ,'OSC_EDO1',
                            'DOC_ID2' ,'FORMATO_ID2' ,'OSC_D2' ,'PER_ID2' ,'NUM_ID2' ,'OSC_EDO2',
                            'DOC_ID3' ,'FORMATO_ID3' ,'OSC_D3' ,'PER_ID3' ,'NUM_ID3' ,'OSC_EDO3',
                            'DOC_ID4' ,'FORMATO_ID4' ,'OSC_D4' ,'PER_ID4' ,'NUM_ID4' ,'OSC_EDO4',                        
                            'DOC_ID5' ,'FORMATO_ID5' ,'OSC_D5' ,'PER_ID5' ,'NUM_ID5' ,'OSC_EDO5',                    
                            'DOC_ID6' ,'FORMATO_ID6' ,'OSC_D6' ,'PER_ID6' ,'NUM_ID6' ,'OSC_EDO6',
                            'DOC_ID7' ,'FORMATO_ID7' ,'OSC_D7' ,'PER_ID7' ,'NUM_ID7' ,'OSC_EDO7',
                            'DOC_ID8' ,'FORMATO_ID8' ,'OSC_D8' ,'PER_ID8' ,'NUM_ID8' ,'OSC_EDO8',
                            'DOC_ID9' ,'FORMATO_ID9' ,'OSC_D9' ,'PER_ID9' ,'NUM_ID9' ,'OSC_EDO9',                        
                            'DOC_ID10','FORMATO_ID10','OSC_D10','PER_ID10','NUM_ID10','OSC_EDO10',                        
                            'DOC_ID11','FORMATO_ID11','OSC_D11','PER_ID11','NUM_ID11','OSC_EDO11',
                            'OSC_STATUS','FECREG','IP','LOGIN','FECHA_M','IP_M','LOGIN_M')
                            ->orderBy('PERIODO_ID','ASC')
                            ->orderBy('OSC_ID'    ,'ASC')                            
                            ->orderBy('OSC_FOLIO' ,'ASC') 
                            ->paginate(30);
        }else{  
            $regosc       = regOscModel::select('OSC_ID', 'OSC_DESC','OSC_STATUS')
                            ->where('OSC_ID',$arbol_id)
                            ->get();                    
            $regoperativo  = regReqOperativosModel::select('OSC_FOLIO','OSC_ID','PERIODO_ID',
                            'DOC_ID1' ,'FORMATO_ID1' ,'OSC_D1' ,'PER_ID1' ,'NUM_ID1' ,'OSC_EDO1',
                            'DOC_ID2' ,'FORMATO_ID2' ,'OSC_D2' ,'PER_ID2' ,'NUM_ID2' ,'OSC_EDO2',
                            'DOC_ID3' ,'FORMATO_ID3' ,'OSC_D3' ,'PER_ID3' ,'NUM_ID3' ,'OSC_EDO3',
                            'DOC_ID4' ,'FORMATO_ID4' ,'OSC_D4' ,'PER_ID4' ,'NUM_ID4' ,'OSC_EDO4',                        
                            'DOC_ID5' ,'FORMATO_ID5' ,'OSC_D5' ,'PER_ID5' ,'NUM_ID5' ,'OSC_EDO5',                
                            'DOC_ID6' ,'FORMATO_ID6' ,'OSC_D6' ,'PER_ID6' ,'NUM_ID6' ,'OSC_EDO6',
                            'DOC_ID7' ,'FORMATO_ID7' ,'OSC_D7' ,'PER_ID7' ,'NUM_ID7' ,'OSC_EDO7',
                            'DOC_ID8' ,'FORMATO_ID8' ,'OSC_D8' ,'PER_ID8' ,'NUM_ID8' ,'OSC_EDO8',
                            'DOC_ID9' ,'FORMATO_ID9' ,'OSC_D9' ,'PER_ID9' ,'NUM_ID9' ,'OSC_EDO9',                        
                            'DOC_ID10','FORMATO_ID10','OSC_D10','PER_ID10','NUM_ID10','OSC_EDO10',                        
                            'DOC_ID11','FORMATO_ID11','OSC_D11','PER_ID11','NUM_ID11','OSC_EDO11',
                            'OSC_STATUS','FECREG','IP','LOGIN','FECHA_M','IP_M','LOGIN_M')
                            ->where(  'OSC_ID'    ,$arbol_id)
                            ->orderBy('PERIODO_ID','ASC')
                            ->orderBy('OSC_ID'    ,'ASC')                            
                            ->orderBy('OSC_FOLIO' ,'ASC')                             
                            ->paginate(30);            
        }
        if($regoperativo->count() <= 0){
            toastr()->error('No existen requisitos operativos.','Lo siento!',['positionClass' => 'toast-bottom-right']);
            //return redirect()->route('nuevooperativo');
        }
        return view('sicinar.requisitos.verReqop',compact('nombre','usuario','regosc','regperiodicidad','regnumeros', 'regperiodos','regoperativo','regformatos'));
    }


    public function actionNuevoReqop(){
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
        $regoperativo  = regReqOperativosModel::select('OSC_FOLIO','OSC_ID','PERIODO_ID',
                        'DOC_ID1' ,'FORMATO_ID1' ,'OSC_D1' ,'PER_ID1' ,'NUM_ID1' ,'OSC_EDO1',
                        'DOC_ID2' ,'FORMATO_ID2' ,'OSC_D2' ,'PER_ID2' ,'NUM_ID2' ,'OSC_EDO2',
                        'DOC_ID3' ,'FORMATO_ID3' ,'OSC_D3' ,'PER_ID3' ,'NUM_ID3' ,'OSC_EDO3',
                        'DOC_ID4' ,'FORMATO_ID4' ,'OSC_D4' ,'PER_ID4' ,'NUM_ID4' ,'OSC_EDO4',                        
                        'DOC_ID5' ,'FORMATO_ID5' ,'OSC_D5' ,'PER_ID5' ,'NUM_ID5' ,'OSC_EDO5',            
                        'DOC_ID6' ,'FORMATO_ID6' ,'OSC_D6' ,'PER_ID6' ,'NUM_ID6' ,'OSC_EDO6',
                        'DOC_ID7' ,'FORMATO_ID7' ,'OSC_D7' ,'PER_ID7' ,'NUM_ID7' ,'OSC_EDO7',
                        'DOC_ID8' ,'FORMATO_ID8' ,'OSC_D8' ,'PER_ID8' ,'NUM_ID8' ,'OSC_EDO8',
                        'DOC_ID9' ,'FORMATO_ID9' ,'OSC_D9' ,'PER_ID9' ,'NUM_ID9' ,'OSC_EDO9',                        
                        'DOC_ID10','FORMATO_ID10','OSC_D10','PER_ID10','NUM_ID10','OSC_EDO10',                        
                        'DOC_ID11','FORMATO_ID11','OSC_D11','PER_ID11','NUM_ID11','OSC_EDO11',
                        'OSC_STATUS','FECREG','IP','LOGIN','FECHA_M','IP_M','LOGIN_M')
                        ->orderBy('OSC_ID','ASC')
                        ->get();        
        //dd($unidades);
        return view('sicinar.requisitos.nuevoReqop',compact('regper','regnumeros','regosc','regperiodos','regperiodicidad','nombre','usuario','regoperativo','regformatos'));
    }

    public function actionAltaNuevoReqop(Request $request){
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
        $regoperativo  = regReqOperativosModel::select('OSC_FOLIO','OSC_ID','PERIODO_ID',
                        'DOC_ID1' ,'FORMATO_ID1' ,'OSC_D1' ,'PER_ID1' ,'NUM_ID1' ,'OSC_EDO1',
                        'DOC_ID2' ,'FORMATO_ID2' ,'OSC_D2' ,'PER_ID2' ,'NUM_ID2' ,'OSC_EDO2',
                        'DOC_ID3' ,'FORMATO_ID3' ,'OSC_D3' ,'PER_ID3' ,'NUM_ID3' ,'OSC_EDO3',
                        'DOC_ID4' ,'FORMATO_ID4' ,'OSC_D4' ,'PER_ID4' ,'NUM_ID4' ,'OSC_EDO4',                        
                        'DOC_ID5' ,'FORMATO_ID5' ,'OSC_D5' ,'PER_ID5' ,'NUM_ID5' ,'OSC_EDO5',             
                        'DOC_ID6' ,'FORMATO_ID6' ,'OSC_D6' ,'PER_ID6' ,'NUM_ID6' ,'OSC_EDO6',
                        'DOC_ID7' ,'FORMATO_ID7' ,'OSC_D7' ,'PER_ID7' ,'NUM_ID7' ,'OSC_EDO7',
                        'DOC_ID8' ,'FORMATO_ID8' ,'OSC_D8' ,'PER_ID8' ,'NUM_ID8' ,'OSC_EDO8',
                        'DOC_ID9' ,'FORMATO_ID9' ,'OSC_D9' ,'PER_ID9' ,'NUM_ID9' ,'OSC_EDO9',                        
                        'DOC_ID10','FORMATO_ID10','OSC_D10','PER_ID10','NUM_ID10','OSC_EDO10',                        
                        'DOC_ID11','FORMATO_ID11','OSC_D11','PER_ID11','NUM_ID11','OSC_EDO11',
                        'OSC_STATUS','FECREG','IP','LOGIN','FECHA_M','IP_M','LOGIN_M')
                        ->where(['PERIODO_ID' => $request->periodo_id,'OSC_ID' => $request->osc_id])
                        ->get();
        if($regoperativo->count() <= 0 && !empty($request->osc_id)){
            //********** Registrar la alta *****************************/
            $osc_folio = regReqOperativosModel::max('OSC_FOLIO');
            $osc_folio = $osc_folio+1;
            $nuevooperativo = new regReqOperativosModel();

            $file1 =null;
            if(isset($request->osc_d1)){
                if(!empty($request->osc_d1)){
                    //Comprobar  si el campo act_const tiene un archivo asignado:
                    if($request->hasFile('osc_d1')){
                        $file1=$request->osc_id.'_'.$request->file('osc_d1')->getClientOriginalName();
                        //sube el archivo a la carpeta del servidor public/images/
                        $request->file('osc_d1')->move(public_path().'/images/', $file1);
                    }
                }
            }            
            $file2 =null;
            if(isset($request->osc_d2)){
                if(!empty($request->osc_d2)){
                    //Comprobar  si el campo act_const tiene un archivo asignado:
                    if($request->hasFile('osc_d2')){
                        $file2=$request->osc_id.'_'.$request->file('osc_d2')->getClientOriginalName();
                        //sube el archivo a la carpeta del servidor public/images/
                        $request->file('osc_d2')->move(public_path().'/images/', $file2);
                    }
                }
            }
            $file3 =null;
            if(isset($request->osc_d3)){
                if(!empty($request->osc_d3)){
                    //Comprobar  si el campo act_const tiene un archivo asignado:
                    if($request->hasFile('osc_d3')){
                        $file3=$request->osc_id.'_'.$request->file('osc_d3')->getClientOriginalName();
                        //sube el archivo a la carpeta del servidor public/images/
                        $request->file('osc_d3')->move(public_path().'/images/', $file3);
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

            $nuevooperativo->OSC_FOLIO    = $osc_folio;
            $nuevooperativo->PERIODO_ID   = $request->periodo_id;
            $nuevooperativo->OSC_ID       = $request->osc_id;        

            $nuevooperativo->DOC_ID1      = $request->doc_id1;
            //$nuevooperativo->FORMATO_ID1= $request->formato_id1;
            $nuevooperativo->OSC_D1       = $file1;        
            $nuevooperativo->NUM_ID1      = $request->num_id1;
            $nuevooperativo->PER_ID1      = $request->per_id1;        
            $nuevooperativo->OSC_EDO1     = $request->osc_edo1;

            $nuevooperativo->DOC_ID2      = $request->doc_id2;
            //$nuevooperativo->FORMATO_ID2= $request->formato_id2;
            $nuevooperativo->OSC_D2       = $file2;        
            $nuevooperativo->NUM_ID2      = 1;   // 1     $request->num_id2;
            $nuevooperativo->PER_ID2      = 5;   // Anual $request->per_id2;        
            //$nuevooperativo->OSC_EDO2   = $request->osc_edo2;

            $nuevooperativo->DOC_ID3      = $request->doc_id3;
            //$nuevooperativo->FORMATO_ID3= $request->formato_id3;
            $nuevooperativo->OSC_D3       = $file3;        
            $nuevooperativo->NUM_ID3      = 1;   // 1     $request->num_id3;
            $nuevooperativo->PER_ID3      = 5;   // Anual $request->per_id3;        
            //$nuevooperativo->OSC_EDO3   = $request->osc_edo3;

            $nuevooperativo->DOC_ID9      = $request->doc_id9;
            //$nuevooperativo->FORMATO_ID9= $request->formato_id9;
            $nuevooperativo->OSC_D9       = $file9;        
            $nuevooperativo->NUM_ID9      = 1;   // 1     $request->num_id9;
            $nuevooperativo->PER_ID9      = 5;   // Anual $request->per_id9;        
            //$nuevooperativo->OSC_EDO9   = $request->osc_edo9;        

            $nuevooperativo->DOC_ID10     = $request->doc_id10;
            //$nuevooperativo->FORMATO_ID10= $request->formato_id10;
            $nuevooperativo->OSC_D10      = $file10;        
            $nuevooperativo->NUM_ID10     = 1;  // 1       $request->num_id10;
            $nuevooperativo->PER_ID10     = 1;  // Mensual $request->per_id10;        
            //$nuevooperativo->OSC_EDO10  = $request->osc_edo10; 

            $nuevooperativo->DOC_ID11     = $request->doc_id11;
            $nuevooperativo->FORMATO_ID11 = $request->formato_id11;
            $nuevooperativo->OSC_D11      = $file11;        
            $nuevooperativo->NUM_ID11     = $request->num_id11;
            $nuevooperativo->PER_ID11     = $request->per_id11;        
            $nuevooperativo->OSC_EDO11    = $request->osc_edo11;         


            $nuevooperativo->IP           = $ip;
            $nuevooperativo->LOGIN        = $nombre;         // Usuario ;
            $nuevooperativo->save();

            if($nuevooperativo->save() == true){
                toastr()->success('requisitos operativos registrados.','ok!',['positionClass' => 'toast-bottom-right']);

                /************ Bitacora inicia *************************************/ 
                setlocale(LC_TIME, "spanish");        
                $xip          = session()->get('ip');
                $xperiodo_id  = (int)date('Y');
                $xprograma_id = 1;
                $xmes_id      = (int)date('m');
                $xproceso_id  =         3;
                $xfuncion_id  =      3003;
                $xtrx_id      =       113;    //alta
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
                       toastr()->success('Trx requisitos operativos dada de alta en bitacora.','¡Ok!',['positionClass' => 'toast-bottom-right']);
                    else
                       toastr()->error('Error trx requisitos operativos al dar de alta en bitacora. Por favor volver a interlo.','Ups!',['positionClass' => 'toast-bottom-right']);
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
                    toastr()->success('Trx de requisitos operativos actualizada en Bitacora.','¡Ok!',['positionClass' => 'toast-bottom-right']);
                }   /************ Bitacora termina *************************************/ 
            }else{
                toastr()->error('Error en trx de requisitos operativos en bitacora. Por favor volver a interlo.','Ups!',['positionClass' => 'toast-bottom-right']);
            }   //******************** Termina la alta ************************/ 

        }else{
            toastr()->error('Ya existen requisitos operativos.','Por favor editar, lo siento!',['positionClass' => 'toast-bottom-right']);
        }   // Termian If de busqueda ****************

        return redirect()->route('verReqop');
    }


    /****************** Editar registro  **********/
    public function actionEditarReqop($id){
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
        $regoperativo  = regReqOperativosModel::select('OSC_FOLIO','OSC_ID','PERIODO_ID',
                        'DOC_ID1' ,'FORMATO_ID1' ,'OSC_D1' ,'PER_ID1' ,'NUM_ID1' ,'OSC_EDO1',
                        'DOC_ID2' ,'FORMATO_ID2' ,'OSC_D2' ,'PER_ID2' ,'NUM_ID2' ,'OSC_EDO2',
                        'DOC_ID3' ,'FORMATO_ID3' ,'OSC_D3' ,'PER_ID3' ,'NUM_ID3' ,'OSC_EDO3',
                        'DOC_ID4' ,'FORMATO_ID4' ,'OSC_D4' ,'PER_ID4' ,'NUM_ID4' ,'OSC_EDO4',                        
                        'DOC_ID5' ,'FORMATO_ID5' ,'OSC_D5' ,'PER_ID5' ,'NUM_ID5' ,'OSC_EDO5',             
                        'DOC_ID6' ,'FORMATO_ID6' ,'OSC_D6' ,'PER_ID6' ,'NUM_ID6' ,'OSC_EDO6',
                        'DOC_ID7' ,'FORMATO_ID7' ,'OSC_D7' ,'PER_ID7' ,'NUM_ID7' ,'OSC_EDO7',
                        'DOC_ID8' ,'FORMATO_ID8' ,'OSC_D8' ,'PER_ID8' ,'NUM_ID8' ,'OSC_EDO8',
                        'DOC_ID9' ,'FORMATO_ID9' ,'OSC_D9' ,'PER_ID9' ,'NUM_ID9' ,'OSC_EDO9',                        
                        'DOC_ID10','FORMATO_ID10','OSC_D10','PER_ID10','NUM_ID10','OSC_EDO10',                        
                        'DOC_ID11','FORMATO_ID11','OSC_D11','PER_ID11','NUM_ID11','OSC_EDO11',
                        'OSC_STATUS','FECREG','IP','LOGIN','FECHA_M','IP_M','LOGIN_M')
                        ->where('OSC_FOLIO', $id)
                        ->first();
        if($regoperativo->count() <= 0){
            toastr()->error('No existen requisitos operativos.','Lo siento!',['positionClass' => 'toast-bottom-right']);
        }
        return view('sicinar.requisitos.editarReqop',compact('nombre','usuario','regosc','regoperativo','regnumeros', 'regperiodos','regperiodicidad','regformatos'));
    }
    
    public function actionActualizarReqop(reqoperativosRequest $request, $id){
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
        $regoperativo = regReqOperativosModel::where('OSC_FOLIO',$id);
        if($regoperativo->count() <= 0)
            toastr()->error('No existen requisitos operativos.','¡Por favor volver a intentar!',['positionClass' => 'toast-bottom-right']);
        else{        
            //****************** Actualizar **************************/
            //echo "Escribió en el campo de texto 1: " .'-'. $request->osc_d9 .'-'. "<br><br>"; 
            //echo "Escribió en el campo de texto 1: " . $request->osc_d9 . "<br><br>"; 
            //Comprobar  si el campo foto1 tiene un archivo asignado:
            $name1 =null;        
            if($request->hasFile('osc_d1')){
                $name1 = $id.'_'.$request->file('osc_d1')->getClientOriginalName(); 
                //sube el archivo a la carpeta del servidor public/images/
                $request->file('osc_d1')->move(public_path().'/images/', $name1);
            }            
            $name2 =null;        
            if($request->hasFile('osc_d2')){
                $name2 = $id.'_'.$request->file('osc_d2')->getClientOriginalName(); 
                //sube el archivo a la carpeta del servidor public/images/
                $request->file('osc_d2')->move(public_path().'/images/', $name2);
            }            
            $name3 =null;        
            if($request->hasFile('osc_d3')){
                $name3 = $id.'_'.$request->file('osc_d3')->getClientOriginalName(); 
                //sube el archivo a la carpeta del servidor public/images/
                $request->file('osc_d3')->move(public_path().'/images/', $name3);
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
            $regoperativo = regReqOperativosModel::where('OSC_FOLIO',$id)        
                           ->update([                
                                     //'PERIODO_ID'   => $request->periodo_id,

                                     'DOC_ID1'      => $request->doc_id1,
                                     //'FORMATO_ID1'=> $request->formato_id1,                            
                                     //'OSC_D1'     => $name1,                                                       
                                     //'PER_ID1'    => $request->per_id1,
                                     //'NUM_ID1'    => $request->num_id1,                
                                     //'OSC_EDO1'   => $request->osc_edo1,

                                     'DOC_ID2'      => $request->doc_id2,
                                     //'FORMATO_ID2'=> $request->formato_id2,                            
                                     //'OSC_D2'     => $name2,                                                       
                                     //'PER_ID2'    => $request->per_id2,
                                     //'NUM_ID2'    => $request->num_id2,                
                                     //'OSC_EDO2'   => $request->osc_edo2,

                                     'DOC_ID3'      => $request->doc_id3,
                                     //'FORMATO_ID3'=> $request->formato_id3,                            
                                     //'OSC_D3'     => $name3,                                                       
                                     //'PER_ID3'    => $request->per_id3,
                                     //'NUM_ID3'    => $request->num_id3,                
                                     //'OSC_EDO3'   => $request->osc_edo3,

                                     'DOC_ID9'      => $request->doc_id9,
                                     //'FORMATO_ID9'=> $request->formato_id9,                            
                                     //'OSC_D9'     => $name9,                                                       
                                     //'PER_ID9'    => $request->per_id9,
                                     //'NUM_ID9'    => $request->num_id9,                
                                     //'OSC_EDO9'   => $request->osc_edo9,
                                    
                                     'DOC_ID10'     => $request->doc_id10,
                                     //'FORMATO_ID10'=> $request->formato_id10,                                          
                                     //'OSC_D10'    => $name10,              
                                     //'PER_ID10'   => $request->per_id10,
                                     //'NUM_ID10'   => $request->num_id10,                
                                     //'OSC_EDO10'  => $request->osc_edo10,
                                     
                                     //'OSC_STATUS'   => $request->osc_status,
                                     'IP_M'         => $ip,
                                     'LOGIN_M'      => $nombre,
                                     'FECHA_M'      => date('Y/m/d')    //date('d/m/
                                    ]);
            toastr()->success('Requisitos operativos actualizado.','¡Ok!',['positionClass' => 'toast-bottom-right']);

            /************ Bitacora inicia *************************************/ 
            setlocale(LC_TIME, "spanish");        
            $xip          = session()->get('ip');
            $xperiodo_id  = (int)date('Y');
            $xprograma_id = 1;
            $xmes_id      = (int)date('m');
            $xproceso_id  =         3;
            $xfuncion_id  =      3003;
            $xtrx_id      =       114;    //Actualizar         
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
                    toastr()->success('Trx de requisitos operativos dada de alta en Bitacora.','¡Ok!',['positionClass' => 'toast-bottom-right']);
                else
                    toastr()->error('Error de trxt de requisitos operativos al dar de alta en bitacora. Por favor volver a interlo.','Ups!',['positionClass' => 'toast-bottom-right']);
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
                toastr()->success('Trx de requsitos operativos actualizada en Bitacora.','¡Ok!',['positionClass' => 'toast-bottom-right']);
            }   /************ Bitacora termina *************************************/         
        }       /************ Termina actualizar ***********************************/
        return redirect()->route('verReqop');
    }

    /****************** Editar Enero quotas de 5 al millar **********/
    public function actionEditarReqop1($id){
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
        $regoperativo  = regReqOperativosModel::select('OSC_FOLIO','OSC_ID','PERIODO_ID',
                        'DOC_ID1' ,'FORMATO_ID1' ,'OSC_D1' ,'PER_ID1' ,'NUM_ID1' ,'OSC_EDO1',
                        'DOC_ID2' ,'FORMATO_ID2' ,'OSC_D2' ,'PER_ID2' ,'NUM_ID2' ,'OSC_EDO2',
                        'DOC_ID3' ,'FORMATO_ID3' ,'OSC_D3' ,'PER_ID3' ,'NUM_ID3' ,'OSC_EDO3',
                        'DOC_ID4' ,'FORMATO_ID4' ,'OSC_D4' ,'PER_ID4' ,'NUM_ID4' ,'OSC_EDO4',                        
                        'DOC_ID5' ,'FORMATO_ID5' ,'OSC_D5' ,'PER_ID5' ,'NUM_ID5' ,'OSC_EDO5',             
                        'DOC_ID6' ,'FORMATO_ID6' ,'OSC_D6' ,'PER_ID6' ,'NUM_ID6' ,'OSC_EDO6',
                        'DOC_ID7' ,'FORMATO_ID7' ,'OSC_D7' ,'PER_ID7' ,'NUM_ID7' ,'OSC_EDO7',
                        'DOC_ID8' ,'FORMATO_ID8' ,'OSC_D8' ,'PER_ID8' ,'NUM_ID8' ,'OSC_EDO8',
                        'DOC_ID9' ,'FORMATO_ID9' ,'OSC_D9' ,'PER_ID9' ,'NUM_ID9' ,'OSC_EDO9',                        
                        'DOC_ID10','FORMATO_ID10','OSC_D10','PER_ID10','NUM_ID10','OSC_EDO10',                        
                        'DOC_ID11','FORMATO_ID11','OSC_D11','PER_ID11','NUM_ID11','OSC_EDO11',
                        'OSC_STATUS','FECREG','IP','LOGIN','FECHA_M','IP_M','LOGIN_M')
                        ->where('OSC_FOLIO', $id)
                        ->first();
        if($regoperativo->count() <= 0){
            toastr()->error('No existe requisitos operativos.','Lo siento!',['positionClass' => 'toast-bottom-right']);
        }
        return view('sicinar.requisitos.editarReqop1',compact('nombre','usuario','regosc','regoperativo','regnumeros', 'regperiodos','regperiodicidad','regformatos'));
    }

    public function actionActualizarReqop1(reqoperativos1Request $request, $id){
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
        $regoperativo = regReqOperativosModel::where('OSC_FOLIO', $id);
        if($regoperativo->count() <= 0)
            toastr()->error('No existe archivo PDF 1.','¡Por favor volver a intentar!',['positionClass' => 'toast-bottom-right']);
        else{        

            //echo "Escribió en el campo de texto 1: " .'-'. $request->osc_d9 .'-'. "<br><br>"; 
            //echo "Escribió en el campo de texto 1: " . $request->osc_d9 . "<br><br>"; 
            //Comprobar  si el campo foto1 tiene un archivo asignado:
            $name1 =null;
            if($request->hasFile('osc_d1')){
                echo "Escribió en el campo de texto 1: " .'-'. $request->osc_d1 .'-'. "<br><br>"; 
                $name1 = $id.'_'.$request->file('osc_d1')->getClientOriginalName(); 
                //sube el archivo a la carpeta del servidor public/images/
                $request->file('osc_d1')->move(public_path().'/images/', $name1);

                // ************* Actualizamos registro **********************************
                $regoperativo = regReqOperativosModel::where('OSC_FOLIO',$id)        
                           ->update([   
                                     'DOC_ID1'      => $request->doc_id1,
                                     //'FORMATO_ID1'=> $request->formato_id1,             
                                     'OSC_D1'       => $name1,                  
                                     //'PER_ID1'    => $request->per_id1,
                                     //'NUM_ID1'    => $request->num_id1,                
                                     //'OSC_EDO1'   => $request->osc_edo1,

                                     'IP_M'         => $ip,
                                     'LOGIN_M'      => $nombre,
                                     'FECHA_M'      => date('Y/m/d')    //date('d/m/Y')                                
                                     ]);
                toastr()->success('archivo operativo 1 actualizado.','¡Ok!',['positionClass' => 'toast-bottom-right']);
            }else{
                // ************* Actualizamos registro **********************************
                $regoperativo = regReqOperativosModel::where('OSC_FOLIO',$id)        
                           ->update([   
                                     'DOC_ID1'      => $request->doc_id1,
                                     //'FORMATO_ID1'=> $request->formato_id1,             
                                     //'OSC_D1'     => $name1,                  
                                     //'PER_ID1'    => $request->per_id1,
                                     //'NUM_ID1'    => $request->num_id1,                
                                     //'OSC_EDO1'   => $request->osc_edo1,

                                     'IP_M'         => $ip,
                                     'LOGIN_M'      => $nombre,
                                     'FECHA_M'      => date('Y/m/d')    //date('d/m/Y')                                
                                     ]);                
                toastr()->success('archivo operativo 1 actualizado.','¡Ok!',['positionClass' => 'toast-bottom-right']);
            }

            /************ Bitacora inicia *************************************/ 
            setlocale(LC_TIME, "spanish");        
            $xip          = session()->get('ip');
            $xperiodo_id  = (int)date('Y');
            $xprograma_id = 1;
            $xmes_id      = (int)date('m');
            $xproceso_id  =         3;
            $xfuncion_id  =      3003;
            $xtrx_id      =       114;    //Actualizar         
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
        return redirect()->route('verReqop');
    }    

        /****************** Editar requisitos operativos **********/
    public function actionEditarReqop2($id){
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
        $regoperativo  = regReqOperativosModel::select('OSC_FOLIO','OSC_ID','PERIODO_ID',
                        'DOC_ID1' ,'FORMATO_ID1' ,'OSC_D1' ,'PER_ID1' ,'NUM_ID1' ,'OSC_EDO1',
                        'DOC_ID2' ,'FORMATO_ID2' ,'OSC_D2' ,'PER_ID2' ,'NUM_ID2' ,'OSC_EDO2',
                        'DOC_ID3' ,'FORMATO_ID3' ,'OSC_D3' ,'PER_ID3' ,'NUM_ID3' ,'OSC_EDO3',
                        'DOC_ID4' ,'FORMATO_ID4' ,'OSC_D4' ,'PER_ID4' ,'NUM_ID4' ,'OSC_EDO4',                        
                        'DOC_ID5' ,'FORMATO_ID5' ,'OSC_D5' ,'PER_ID5' ,'NUM_ID5' ,'OSC_EDO5',             
                        'DOC_ID6' ,'FORMATO_ID6' ,'OSC_D6' ,'PER_ID6' ,'NUM_ID6' ,'OSC_EDO6',
                        'DOC_ID7' ,'FORMATO_ID7' ,'OSC_D7' ,'PER_ID7' ,'NUM_ID7' ,'OSC_EDO7',
                        'DOC_ID8' ,'FORMATO_ID8' ,'OSC_D8' ,'PER_ID8' ,'NUM_ID8' ,'OSC_EDO8',
                        'DOC_ID9' ,'FORMATO_ID9' ,'OSC_D9' ,'PER_ID9' ,'NUM_ID9' ,'OSC_EDO9',                        
                        'DOC_ID10','FORMATO_ID10','OSC_D10','PER_ID10','NUM_ID10','OSC_EDO10',                        
                        'DOC_ID11','FORMATO_ID11','OSC_D11','PER_ID11','NUM_ID11','OSC_EDO11',
                        'OSC_STATUS','FECREG','IP','LOGIN','FECHA_M','IP_M','LOGIN_M')
                        ->where('OSC_FOLIO', $id)
                        ->first();
        if($regoperativo->count() <= 0){
            toastr()->error('No existe requisito operativo 2.','Lo siento!',['positionClass' => 'toast-bottom-right']);
            //return redirect()->route('nuevooperativo');
        }
        return view('sicinar.requisitos.editarReqop2',compact('nombre','usuario','regosc','regoperativo','regnumeros', 'regperiodos','regperiodicidad','regformatos'));
    }

    public function actionActualizarReqop2(reqoperativos2Request $request, $id){
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
        $regoperativo = regReqOperativosModel::where('OSC_FOLIO',$id);
        if($regoperativo->count() <= 0)
            toastr()->error('No existe archivo PDF 2.','¡Por favor volver a intentar!',['positionClass' => 'toast-bottom-right']);
        else{        

            //echo "Escribió en el campo de texto 1: " .'-'. $request->osc_d9 .'-'. "<br><br>"; 
            //echo "Escribió en el campo de texto 1: " . $request->osc_d9 . "<br><br>"; 
            //Comprobar  si el campo foto1 tiene un archivo asignado:
            $preg_003  =str_replace(",", "", $request->preg_003);
            $preg_004  =str_replace(",", "", $request->preg_004);
            $preg_005  =str_replace(",", "", $request->preg_005); 

            $name2 =null;
            if($request->hasFile('osc_d2')){
                echo "Escribió en el campo de texto 2: " .'-'. $request->osc_d2 .'-'. "<br><br>"; 
                $name2 = $id.'_'.$request->file('osc_d2')->getClientOriginalName(); 
                //sube el archivo a la carpeta del servidor public/images/
                $request->file('osc_d2')->move(public_path().'/images/', $name2);

                // ************* Actualizamos registro **********************************
                $regoperativo = regReqOperativosModel::where('OSC_FOLIO',$id)        
                           ->update([   
                                     'DOC_ID2'      => $request->doc_id2,
                                     //'FORMATO_ID2'=> $request->formato_id2,             
                                     'OSC_D2'       => $name2,                  
                                     //'PER_ID2'    => $request->per_id2,
                                     //'NUM_ID2'    => $request->num_id2,                
                                     //'OSC_EDO2'   => $request->osc_edo2,

                                     'IP_M'         => $ip,
                                     'LOGIN_M'      => $nombre,
                                     'FECHA_M'      => date('Y/m/d')    //date('d/m/Y')                                
                                     ]);
                toastr()->success('archivo 2 PDF actualizado.','¡Ok!',['positionClass' => 'toast-bottom-right']);
            }else{
                // ************* Actualizamos registro **********************************
                $regoperativo = regReqOperativosModel::where('OSC_FOLIO',$id)        
                           ->update([   
                                     'DOC_ID2'      => $request->doc_id2,
                                     //'FORMATO_ID2'=> $request->formato_id2,             
                                     //'OSC_D2'     => $name2,                  
                                     //'PER_ID2'    => $request->per_id2,
                                     //'NUM_ID2'    => $request->num_id2,                
                                     //'OSC_EDO2'   => $request->osc_edo2,

                                     'IP_M'         => $ip,
                                     'LOGIN_M'      => $nombre,
                                     'FECHA_M'      => date('Y/m/d')    //date('d/m/Y')                                
                                     ]);                
                toastr()->success('archivo 2 PDF actualizado.','¡Ok!',['positionClass' => 'toast-bottom-right']);
            }

            /************ Bitacora inicia *************************************/ 
            setlocale(LC_TIME, "spanish");        
            $xip          = session()->get('ip');
            $xperiodo_id  = (int)date('Y');
            $xprograma_id = 1;
            $xmes_id      = (int)date('m');
            $xproceso_id  =         3;
            $xfuncion_id  =      3003;
            $xtrx_id      =       114;    //Actualizar         
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
        return redirect()->route('verReqop');
    }    

    /****************** Editar requisitos operativos **********/
    public function actionEditarReqop3($id){
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
        $regoperativo  = regReqOperativosModel::select('OSC_FOLIO','OSC_ID','PERIODO_ID',
                        'DOC_ID1' ,'FORMATO_ID1' ,'OSC_D1' ,'PER_ID1' ,'NUM_ID1' ,'OSC_EDO1',
                        'DOC_ID2' ,'FORMATO_ID2' ,'OSC_D2' ,'PER_ID2' ,'NUM_ID2' ,'OSC_EDO2',
                        'DOC_ID3' ,'FORMATO_ID3' ,'OSC_D3' ,'PER_ID3' ,'NUM_ID3' ,'OSC_EDO3',
                        'DOC_ID4' ,'FORMATO_ID4' ,'OSC_D4' ,'PER_ID4' ,'NUM_ID4' ,'OSC_EDO4',                        
                        'DOC_ID5' ,'FORMATO_ID5' ,'OSC_D5' ,'PER_ID5' ,'NUM_ID5' ,'OSC_EDO5',             
                        'DOC_ID6' ,'FORMATO_ID6' ,'OSC_D6' ,'PER_ID6' ,'NUM_ID6' ,'OSC_EDO6',
                        'DOC_ID7' ,'FORMATO_ID7' ,'OSC_D7' ,'PER_ID7' ,'NUM_ID7' ,'OSC_EDO7',
                        'DOC_ID8' ,'FORMATO_ID8' ,'OSC_D8' ,'PER_ID8' ,'NUM_ID8' ,'OSC_EDO8',
                        'DOC_ID9' ,'FORMATO_ID9' ,'OSC_D9' ,'PER_ID9' ,'NUM_ID9' ,'OSC_EDO9',                        
                        'DOC_ID10','FORMATO_ID10','OSC_D10','PER_ID10','NUM_ID10','OSC_EDO10',                        
                        'DOC_ID11','FORMATO_ID11','OSC_D11','PER_ID11','NUM_ID11','OSC_EDO11',
                        'OSC_STATUS','FECREG','IP','LOGIN','FECHA_M','IP_M','LOGIN_M')
                        ->where('OSC_FOLIO', $id)
                        ->first();
        if($regoperativo->count() <= 0){
            toastr()->error('No existe requisito operativo 3.','Lo siento!',['positionClass' => 'toast-bottom-right']);
            //return redirect()->route('nuevooperativo');
        }
        return view('sicinar.requisitos.editarReqop3',compact('nombre','usuario','regosc','regoperativo','regnumeros', 'regperiodos','regperiodicidad','regformatos'));
    }

    public function actionActualizarReqop3(reqoperativos3Request $request, $id){
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
        $regoperativo = regReqOperativosModel::where('OSC_FOLIO', $id);
        if($regoperativo->count() <= 0)
            toastr()->error('No existe archivo 3 PDF.','¡Por favor volver a intentar!',['positionClass' => 'toast-bottom-right']);
        else{        

            //echo "Escribió en el campo de texto 1: " .'-'. $request->osc_d9 .'-'. "<br><br>"; 
            //echo "Escribió en el campo de texto 1: " . $request->osc_d9 . "<br><br>"; 
            //Comprobar  si el campo foto1 tiene un archivo asignado:
            $name3 =null;
            if($request->hasFile('osc_d3')){
                echo "Escribió en el campo de texto 3: " .'-'. $request->osc_d3 .'-'. "<br><br>"; 
                $name3 = $id.'_'.$request->file('osc_d3')->getClientOriginalName(); 
                //sube el archivo a la carpeta del servidor public/images/
                $request->file('osc_d3')->move(public_path().'/images/', $name3);

                // ************* Actualizamos registro **********************************
                $regoperativo = regReqOperativosModel::where('OSC_FOLIO', $id)        
                           ->update([   
                                     'DOC_ID3'      => $request->doc_id3,
                                     //'FORMATO_ID3'=> $request->formato_id3,             
                                     'OSC_D3'       => $name3,                  
                                     //'PER_ID3'    => $request->per_id3,
                                     //'NUM_ID3'    => $request->num_id3,                
                                     //'OSC_EDO3'   => $request->osc_edo3,

                                     'IP_M'         => $ip,
                                     'LOGIN_M'      => $nombre,
                                     'FECHA_M'      => date('Y/m/d')    //date('d/m/Y')                                
                                     ]);
                toastr()->success('archivo 3 PDF actualizado.','¡Ok!',['positionClass' => 'toast-bottom-right']);
            }else{
                // ************* Actualizamos registro **********************************
                $regoperativo = regReqOperativosModel::where('OSC_FOLIO',$id)        
                           ->update([   
                                     'DOC_ID3'      => $request->doc_id3,
                                     //'FORMATO_ID3'=> $request->formato_id3,             
                                     //'OSC_D3'     => $name3,                   
                                     //'PER_ID3'    => $request->per_id3,
                                     //'NUM_ID3'    => $request->num_id3,                
                                     //'OSC_EDO3'   => $request->osc_edo3,

                                     'IP_M'         => $ip,
                                     'LOGIN_M'      => $nombre,
                                     'FECHA_M'      => date('Y/m/d')    //date('d/m/Y')                                
                                     ]);                
                toastr()->success('archivo 3 PDF actualizado.','¡Ok!',['positionClass' => 'toast-bottom-right']);
            }

            /************ Bitacora inicia *************************************/ 
            setlocale(LC_TIME, "spanish");        
            $xip          = session()->get('ip');
            $xperiodo_id  = (int)date('Y');
            $xprograma_id = 1;
            $xmes_id      = (int)date('m');
            $xproceso_id  =         3;
            $xfuncion_id  =      3003;
            $xtrx_id      =       114;    //Actualizar         
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
        return redirect()->route('verReqop');
    }    

    /****************** Editar requisitos operativos **********/
    public function actionEditarReqop9($id){
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
        $regoperativo  = regReqOperativosModel::select('OSC_FOLIO','OSC_ID','PERIODO_ID',
                        'DOC_ID1' ,'FORMATO_ID1' ,'OSC_D1' ,'PER_ID1' ,'NUM_ID1' ,'OSC_EDO1',
                        'DOC_ID2' ,'FORMATO_ID2' ,'OSC_D2' ,'PER_ID2' ,'NUM_ID2' ,'OSC_EDO2',
                        'DOC_ID3' ,'FORMATO_ID3' ,'OSC_D3' ,'PER_ID3' ,'NUM_ID3' ,'OSC_EDO3',
                        'DOC_ID4' ,'FORMATO_ID4' ,'OSC_D4' ,'PER_ID4' ,'NUM_ID4' ,'OSC_EDO4',                        
                        'DOC_ID5' ,'FORMATO_ID5' ,'OSC_D5' ,'PER_ID5' ,'NUM_ID5' ,'OSC_EDO5',             
                        'DOC_ID6' ,'FORMATO_ID6' ,'OSC_D6' ,'PER_ID6' ,'NUM_ID6' ,'OSC_EDO6',
                        'DOC_ID7' ,'FORMATO_ID7' ,'OSC_D7' ,'PER_ID7' ,'NUM_ID7' ,'OSC_EDO7',
                        'DOC_ID8' ,'FORMATO_ID8' ,'OSC_D8' ,'PER_ID8' ,'NUM_ID8' ,'OSC_EDO8',
                        'DOC_ID9' ,'FORMATO_ID9' ,'OSC_D9' ,'PER_ID9' ,'NUM_ID9' ,'OSC_EDO9',                        
                        'DOC_ID10','FORMATO_ID10','OSC_D10','PER_ID10','NUM_ID10','OSC_EDO10',                        
                        'DOC_ID11','FORMATO_ID11','OSC_D11','PER_ID11','NUM_ID11','OSC_EDO11',
                        'OSC_STATUS','FECREG','IP','LOGIN','FECHA_M','IP_M','LOGIN_M')
                        ->where('OSC_FOLIO', $id)
                        ->first();
        if($regoperativo->count() <= 0){
            toastr()->error('No existe requisito operativo 9.','Lo siento!',['positionClass' => 'toast-bottom-right']);
        }
        return view('sicinar.requisitos.editarReqop9',compact('nombre','usuario','regosc','regoperativo','regnumeros', 'regperiodos','regperiodicidad','regformatos'));
    }

    public function actionActualizarReqop9(reqoperativosRequest $request, $id){
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
        $regoperativo = regReqOperativosModel::where('OSC_FOLIO', $id);
        if($regoperativo->count() <= 0)
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
                $regoperativo = regReqOperativosModel::where('OSC_FOLIO',$id)        
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
                toastr()->success('archivo operativo 9 actualizado.','¡Ok!',['positionClass' => 'toast-bottom-right']);
            }else{
                // ************* Actualizamos registro **********************************
                $regoperativo = regReqOperativosModel::where('OSC_FOLIO',$id)        
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
            $xfuncion_id  =      3003;
            $xtrx_id      =       114;    //Actualizar         
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
        return redirect()->route('verReqop');    
    }    

    /****************** Editar enero otros requisitos operativos quotas de 5 al millar **********/
    public function actionEditarReqop10($id){
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
        $regoperativo  = regReqOperativosModel::select('OSC_FOLIO','OSC_ID','PERIODO_ID',
                        'DOC_ID1' ,'FORMATO_ID1' ,'OSC_D1' ,'PER_ID1' ,'NUM_ID1' ,'OSC_EDO1',
                        'DOC_ID2' ,'FORMATO_ID2' ,'OSC_D2' ,'PER_ID2' ,'NUM_ID2' ,'OSC_EDO2',
                        'DOC_ID3' ,'FORMATO_ID3' ,'OSC_D3' ,'PER_ID3' ,'NUM_ID3' ,'OSC_EDO3',
                        'DOC_ID4' ,'FORMATO_ID4' ,'OSC_D4' ,'PER_ID4' ,'NUM_ID4' ,'OSC_EDO4',                        
                        'DOC_ID5' ,'FORMATO_ID5' ,'OSC_D5' ,'PER_ID5' ,'NUM_ID5' ,'OSC_EDO5',             
                        'DOC_ID6' ,'FORMATO_ID6' ,'OSC_D6' ,'PER_ID6' ,'NUM_ID6' ,'OSC_EDO6',
                        'DOC_ID7' ,'FORMATO_ID7' ,'OSC_D7' ,'PER_ID7' ,'NUM_ID7' ,'OSC_EDO7',
                        'DOC_ID8' ,'FORMATO_ID8' ,'OSC_D8' ,'PER_ID8' ,'NUM_ID8' ,'OSC_EDO8',
                        'DOC_ID9' ,'FORMATO_ID9' ,'OSC_D9' ,'PER_ID9' ,'NUM_ID9' ,'OSC_EDO9',                        
                        'DOC_ID10','FORMATO_ID10','OSC_D10','PER_ID10','NUM_ID10','OSC_EDO10',                        
                        'DOC_ID11','FORMATO_ID11','OSC_D11','PER_ID11','NUM_ID11','OSC_EDO11',
                        'OSC_STATUS','FECREG','IP','LOGIN','FECHA_M','IP_M','LOGIN_M')
                       ->where('OSC_FOLIO', $id)
                       ->first();
        if($regoperativo->count() <= 0){
            toastr()->error('No existe requisito PDF 10.','Lo siento!',['positionClass' => 'toast-bottom-right']);
            //return redirect()->route('nuevooperativo');
        }
        return view('sicinar.requisitos.editarReqop10',compact('nombre','usuario','regosc','regoperativo','regnumeros', 'regperiodos','regperiodicidad','regformatos'));
    }


    public function actionActualizarReqop10(reqoperativosRequest $request, $id){
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
        $regoperativo = regReqOperativosModel::where('OSC_FOLIO',$id);
        if($regoperativo->count() <= 0)
            toastr()->error('No existe requisito PDF 10.','¡Por favor volver a intentar!',['positionClass' => 'toast-bottom-right']);
        else{        

            //***************** Actualizar *****************************
            //echo "Escribió en el campo de texto 1: " .'-'. $request->osc_d9 .'-'. "<br><br>"; 
            //echo "Escribió en el campo de texto 1: " . $request->osc_d9 . "<br><br>"; 
            //Comprobar  si el campo foto1 tiene un archivo asignado:

            $name10 =null;
            if($request->hasFile('osc_d10')){
                echo "Escribió en el campo de texto 10: " .'-'. $request->osc_d10 .'-'. "<br><br>"; 
                $name10 = $id.'_'.$request->file('osc_d10')->getClientOriginalName(); 
                //sube el archivo a la carpeta del servidor public/images/
                $request->file('osc_d10')->move(public_path().'/images/', $name10);

                // ************* Actualizamos registro **********************************
                $regoperativo = regReqOperativosModel::where('OSC_FOLIO',$id)        
                               ->update([  
                                      'DOC_ID10'    => $request->doc_id10,
                                      'FORMATO_ID10'=> $request->formato_id10,             
                                      'OSC_D10'     => $name10,           
                                      //'PER_ID10'    => $request->per_id10,
                                      //'NUM_ID10'    => $request->num_id10,                
                                      //'OSC_EDO10'   => $request->osc_edo10,

                                      'IP_M'        => $ip,
                                      'LOGIN_M'     => $nombre,
                                      'FECHA_M'     => date('Y/m/d')    //date('d/m/Y')                                
                                    ]);
                toastr()->success('requisito operativo 10 actualizado.','¡Ok!',['positionClass' => 'toast-bottom-right']);
            }else{
                // ************* Actualizamos registro **********************************
                $regoperativo = regReqOperativosModel::where('OSC_FOLIO',$id)        
                               ->update([  
                                      'DOC_ID10'    => $request->doc_id10,
                                      'FORMATO_ID10'=> $request->formato_id10,             
                                      //'OSC_D10'   => $name10,           
                                      //'PER_ID10'    => $request->per_id10,
                                      //'NUM_ID10'    => $request->num_id10,                
                                      //'OSC_EDO10'   => $request->osc_edo10,

                                      'IP_M'        => $ip,
                                      'LOGIN_M'     => $nombre,
                                      'FECHA_M'     => date('Y/m/d')    //date('d/m/Y')                                
                                    ]);                
                toastr()->success('Reuisito operativo actualizado.','¡Ok!',['positionClass' => 'toast-bottom-right']);
            }

            /************ Bitacora inicia *************************************/ 
            setlocale(LC_TIME, "spanish");        
            $xip          = session()->get('ip');
            $xperiodo_id  = (int)date('Y');
            $xprograma_id = 1;
            $xmes_id      = (int)date('m');
            $xproceso_id  =         3;
            $xfuncion_id  =      3003;
            $xtrx_id      =       114;    //Actualizar         
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
                        toastr()->success('Trx de archivo PDF 10  dada de alta.','¡Ok!',['positionClass' => 'toast-bottom-right']);
                    else
                        toastr()->error('Error trx de archivo 10 PDF inesperado al dar de alta en bitacora. Por favor volver a interlo.','Ups!',['positionClass' => 'toast-bottom-right']);
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
                toastr()->success('Trx de archivo PDF 10 actualizada en Bitacora.','¡Ok!',['positionClass' => 'toast-bottom-right']);
            }   /************ Bitacora termina *************************************/         
        }       /************ Termina de actualizar ********************************/
        return redirect()->route('verReqop');    
    }    

    /****************** Editar enero otros requisitos operativos quotas de 5 al millar **********/
    public function actionEditarReqop11($id){
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
        $regoperativo  = regReqOperativosModel::select('OSC_FOLIO','OSC_ID','PERIODO_ID',
                        'DOC_ID1' ,'FORMATO_ID1' ,'OSC_D1' ,'PER_ID1' ,'NUM_ID1' ,'OSC_EDO1',
                        'DOC_ID2' ,'FORMATO_ID2' ,'OSC_D2' ,'PER_ID2' ,'NUM_ID2' ,'OSC_EDO2',
                        'DOC_ID3' ,'FORMATO_ID3' ,'OSC_D3' ,'PER_ID3' ,'NUM_ID3' ,'OSC_EDO3',
                        'DOC_ID4' ,'FORMATO_ID4' ,'OSC_D4' ,'PER_ID4' ,'NUM_ID4' ,'OSC_EDO4',                        
                        'DOC_ID5' ,'FORMATO_ID5' ,'OSC_D5' ,'PER_ID5' ,'NUM_ID5' ,'OSC_EDO5',             
                        'DOC_ID6' ,'FORMATO_ID6' ,'OSC_D6' ,'PER_ID6' ,'NUM_ID6' ,'OSC_EDO6',
                        'DOC_ID7' ,'FORMATO_ID7' ,'OSC_D7' ,'PER_ID7' ,'NUM_ID7' ,'OSC_EDO7',
                        'DOC_ID8' ,'FORMATO_ID8' ,'OSC_D8' ,'PER_ID8' ,'NUM_ID8' ,'OSC_EDO8',
                        'DOC_ID9' ,'FORMATO_ID9' ,'OSC_D9' ,'PER_ID9' ,'NUM_ID9' ,'OSC_EDO9',                        
                        'DOC_ID10','FORMATO_ID10','OSC_D10','PER_ID10','NUM_ID10','OSC_EDO10',                        
                        'DOC_ID11','FORMATO_ID11','OSC_D11','PER_ID11','NUM_ID11','OSC_EDO11',
                        'OSC_STATUS','FECREG','IP','LOGIN','FECHA_M','IP_M','LOGIN_M')
                       ->where('OSC_FOLIO', $id)
                       ->first();
        if($regoperativo->count() <= 0){
            toastr()->error('No existe requisito pdf 11.','Lo siento!',['positionClass' => 'toast-bottom-right']);
            //return redirect()->route('nuevooperativo');
        }
        return view('sicinar.requisitos.editarReqop11',compact('nombre','usuario','regosc','regoperativo','regnumeros', 'regperiodos','regperiodicidad','regformatos'));
    }


    public function actionActualizarReqop11(reqoperativosRequest $request, $id){
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
        $regoperativo = regReqOperativosModel::where('OSC_FOLIO',$id);
        if($regoperativo->count() <= 0)
            toastr()->error('No archivo digital 11 PDF.','¡Por favor volver a intentar!',['positionClass' => 'toast-bottom-right']);
        else{        

            //***************** Actualizar *****************************
            //echo "Escribió en el campo de texto 1: " .'-'. $request->osc_d9 .'-'. "<br><br>"; 
            //echo "Escribió en el campo de texto 1: " . $request->osc_d9 . "<br><br>"; 
            //Comprobar  si el campo foto1 tiene un archivo asignado:

            $name11 =null;
            if($request->hasFile('osc_d11')){
                echo "Escribió en el campo de texto 11: " .'-'. $request->osc_d11 .'-'. "<br><br>"; 
                $name11 = $id.'_'.$request->file('osc_d11')->getClientOriginalName(); 
                //sube el archivo a la carpeta del servidor public/images/
                $request->file('osc_d11')->move(public_path().'/images/', $name11);

                // ************* Actualizamos registro **********************************
                $regoperativo = regReqOperativosModel::where('OSC_FOLIO',$id)        
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
                toastr()->success('requisito operativo 11 actualizado.','¡Ok!',['positionClass' => 'toast-bottom-right']);
            }else{
                // ************* Actualizamos registro **********************************
                $regoperativo = regReqOperativosModel::where('OSC_FOLIO',$id)        
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
                toastr()->success('Requisito operativo 11 actualizado.','¡Ok!',['positionClass' => 'toast-bottom-right']);
            }

            /************ Bitacora inicia *************************************/ 
            setlocale(LC_TIME, "spanish");        
            $xip          = session()->get('ip');
            $xperiodo_id  = (int)date('Y');
            $xprograma_id = 1;
            $xmes_id      = (int)date('m');
            $xproceso_id  =         3;
            $xfuncion_id  =      3003;
            $xtrx_id      =       114;    //Actualizar         
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
                        toastr()->success('Trx de requisito 11 dado de alta.','¡Ok!',['positionClass' => 'toast-bottom-right']);
                    else
                        toastr()->error('Error trx de requisito 11 inesperado al dar de alta en bitacora. Por favor volver a interlo.','Ups!',['positionClass' => 'toast-bottom-right']);
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
                toastr()->success('Trx de requisito 11 actualizado en Bitacora.','¡Ok!',['positionClass' => 'toast-bottom-right']);
            }   /************ Bitacora termina *************************************/         
        }       /************ Termina de actualizar ********************************/
        return redirect()->route('verReqop');    
    }    


     
    //***** Borrar registro completo ***********************
    public function actionBorrarReqop($id){
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

        /************ Elimina transacción de asistencia social y operativo ***************/
        $regoperativo = regReqOperativosModel::where('OSC_FOLIO',$id);
        if($regoperativo->count() <= 0)
            toastr()->error('No existe requisito operativo.','¡Por favor volver a intentar!',['positionClass' => 'toast-bottom-right']);
        else{        
            $regoperativo->delete();
            toastr()->success('Requisito operativo eliminado.','¡Ok!',['positionClass' => 'toast-bottom-right']);

            //echo 'Ya entre aboorar registro..........';
            /************ Bitacora inicia *************************************/ 
            setlocale(LC_TIME, "spanish");        
            $xip          = session()->get('ip');
            $xperiodo_id  = (int)date('Y');
            $xprograma_id = 1;
            $xmes_id      = (int)date('m');
            $xproceso_id  =         3;
            $xfuncion_id  =      3003;
            $xtrx_id      =       115;     // borrar 
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
        return redirect()->route('verReqop');
    }    

}
