<?php
//**************************************************************/
//* File:       rJuridicosController.php
//* Proyecto:   Sistema SIINAP.V2 
//* Función:    Clases para el modulo de requisitos jurídicos
//* Autor:      Ing. Silverio Baltazar Barrientos Zarate
//* Modifico:   Ing. Silverio Baltazar Barrientos Zarate
//* Fecha act.: sep 2021
//* @Derechos reservados. Gobierno del Estado de México
//*************************************************************/
namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests\reqjuridicoRequest;
use App\Http\Requests\reqjuridico12Request;
use App\Http\Requests\reqjuridico13Request;
use App\Http\Requests\reqjuridico14Request;
use App\Http\Requests\reqjuridico15Request;
use App\regOscModel;
use App\regBitacoraModel;
use App\regPfiscalesModel;
use App\regReqJuridicoModel;
use App\regPerModel;
use App\regNumerosModel;
use App\regFormatosModel;

// Exportar a excel 
use Maatwebsite\Excel\Facades\Excel;

// Exportar a pdf
use PDF;
//use Options;

class rJuridicosController extends Controller
{

    //******** Buscar requisitos juridicos *****//
    public function actionBuscarJur(Request $request){
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
        $fper  = $request->get('fper');   
        $idd   = $request->get('idd');                
        $name  = $request->get('name');         
        //********* Validar rol de usuario **********************/
        if(session()->get('rango') !== '0'){
            $regosc       = regOscModel::select('OSC_ID', 'OSC_DESC','OSC_STATUS')
                            ->get();            
            $regjuridico  = regReqJuridicoModel::join('PE_OSC','PE_OSC.OSC_ID','=','PE_REQUISITOS_JURIDICOS.OSC_ID')
                            ->select( 'PE_OSC.OSC_DESC','PE_REQUISITOS_JURIDICOS.*')
                            ->orderBy('PE_REQUISITOS_JURIDICOS.OSC_ID','ASC')
                            ->orderBy('PE_REQUISITOS_JURIDICOS.PERIODO_ID','ASC')
                            ->orderBy('PE_REQUISITOS_JURIDICOS.OSC_FOLIO' ,'ASC')
                            ->fper($fper)           //Metodos personalizados es equvalente a ->where('OSC_DESC', 'LIKE', "%$name%");
                            ->idd($idd)             //Metodos personalizados 
                            ->name($name)  
                            ->paginate(30);  
        }else{
            $regosc       = regOscModel::select('OSC_ID', 'OSC_DESC','OSC_STATUS')
                            ->where('OSC_ID',$arbol_id)
                            ->get();            
            $regjuridico  = regReqJuridicoModel::join('PE_OSC','PE_OSC.OSC_ID','=','PE_REQUISITOS_JURIDICOS.OSC_ID')
                            ->select( 'PE_OSC.OSC_DESC','PE_REQUISITOS_JURIDICOS.*')
                            ->where(  'PE_REQUISITOS_JURIDICOS.OSC_ID',$arbol_id)
                            ->orderBy('PE_REQUISITOS_JURIDICOS.OSC_ID'    ,'ASC')
                            ->orderBy('PE_REQUISITOS_JURIDICOS.PERIODO_ID','ASC')
                            ->orderBy('PE_REQUISITOS_JURIDICOS.OSC_FOLIO' ,'ASC') 
                            ->fper($fper)           //Metodos personalizados es equvalente a ->where('OSC_DESC', 'LIKE', "%$name%");
                            ->idd($idd)             //Metodos personalizados  
                            ->name($name)  
                            ->paginate(30);            
        }
        if($regjuridico->count() <= 0){
            toastr()->error('No existen requisitos jurídicos.','Lo siento!',['positionClass' => 'toast-bottom-right']);
            //return redirect()->route('nuevajuridica');
        }
        return view('sicinar.requisitos.verReqJuridico',compact('nombre','usuario','regosc','regperiodicidad','regnumeros', 'regperiodos','regjuridico','regformatos'));
    }

    //******** Mostrar requisitos juridicos *****//
    public function actionVerJur(){
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
            $regjuridico  = regReqJuridicoModel::select('OSC_ID','PERIODO_ID','OSC_FOLIO',
                            'DOC_ID12','FORMATO_ID12','OSC_D12','PER_ID12','NUM_ID12','OSC_EDO12',
                            'DOC_ID13','FORMATO_ID13','OSC_D13','PER_ID13','NUM_ID13','OSC_EDO13',
                            'DOC_ID14','FORMATO_ID14','OSC_D14','PER_ID14','NUM_ID14','OSC_EDO14',
                            'DOC_ID15','FORMATO_ID15','OSC_D15','PER_ID15','NUM_ID15','OSC_EDO15',
                            'OSC_STATUS','FECREG','IP','LOGIN','FECHA_M','IP_M','LOGIN_M')
                            ->orderBy('OSC_ID'    ,'ASC')
                            ->orderBy('PERIODO_ID','ASC')
                            ->orderBy('OSC_FOLIO' ,'ASC')
                            ->paginate(30);
        }else{
            $regosc       = regOscModel::select('OSC_ID', 'OSC_DESC','OSC_STATUS')
                            ->where('OSC_ID',$arbol_id)
                            ->get();            
            $regjuridico  = regReqJuridicoModel::select('OSC_ID','PERIODO_ID','OSC_FOLIO',
                            'DOC_ID12','FORMATO_ID12','OSC_D12','PER_ID12','NUM_ID12','OSC_EDO12',
                            'DOC_ID13','FORMATO_ID13','OSC_D13','PER_ID13','NUM_ID13','OSC_EDO13',
                            'DOC_ID14','FORMATO_ID14','OSC_D14','PER_ID14','NUM_ID14','OSC_EDO14',
                            'DOC_ID15','FORMATO_ID15','OSC_D15','PER_ID15','NUM_ID15','OSC_EDO15',
                            'OSC_STATUS','FECREG','IP','LOGIN','FECHA_M','IP_M','LOGIN_M')
                            ->where(  'OSC_ID'    ,$arbol_id)
                            ->orderBy('OSC_ID'    ,'ASC')
                            ->orderBy('PERIODO_ID','ASC')
                            ->orderBy('OSC_FOLIO' ,'ASC')                            
                            ->paginate(30);            
        }
        if($regjuridico->count() <= 0){
            toastr()->error('No existen requisitos juridicos.','Lo siento!',['positionClass' => 'toast-bottom-right']);
            //return redirect()->route('nuevajuridica');
        }
        return view('sicinar.requisitos.verReqJuridico',compact('nombre','usuario','regosc','regperiodicidad','regnumeros', 'regperiodos','regjuridico','regformatos'));
    }

    public function actionNuevaJur(){
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
            $regosc   = regOscModel::select('OSC_ID', 'OSC_DESC','OSC_STATUS')
                        ->orderBy('OSC_DESC','asc')
                        ->get();
        }else{
            $regosc   = regOscModel::select('OSC_ID', 'OSC_DESC','OSC_STATUS')
                        ->where('OSC_ID',$arbol_id)
                        ->get();            
        }
        $regjuridico  = regReqJuridicoModel::select('OSC_ID','PERIODO_ID','OSC_FOLIO',
                        'DOC_ID12','FORMATO_ID12','OSC_D12','PER_ID12','NUM_ID12','OSC_EDO12',
                        'DOC_ID13','FORMATO_ID13','OSC_D13','PER_ID13','NUM_ID13','OSC_EDO13',
                        'DOC_ID14','FORMATO_ID14','OSC_D14','PER_ID14','NUM_ID14','OSC_EDO14',
                        'DOC_ID15','FORMATO_ID15','OSC_D15','PER_ID15','NUM_ID15','OSC_EDO15',                        
                        'OSC_STATUS','FECREG','IP','LOGIN','FECHA_M','IP_M','LOGIN_M')
                        ->orderBy('OSC_ID','ASC')
                        ->get();        
        //dd($unidades); 
        return view('sicinar.requisitos.nuevoReqj',compact('regper','regnumeros','regosc','regperiodos','regperiodicidad','nombre','usuario','regjuridico','regformatos'));
    }

    public function actionAltaNuevaJur(Request $request){
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

        // *************** Validar triada ***********************************/
        $regjuridico  = regReqJuridicoModel::select('OSC_ID','PERIODO_ID','OSC_FOLIO',
                        'DOC_ID12','FORMATO_ID12','OSC_D12','PER_ID12','NUM_ID12','OSC_EDO12',
                        'DOC_ID13','FORMATO_ID13','OSC_D13','PER_ID13','NUM_ID13','OSC_EDO13',
                        'DOC_ID14','FORMATO_ID14','OSC_D14','PER_ID14','NUM_ID14','OSC_EDO14',
                        'DOC_ID15','FORMATO_ID15','OSC_D15','PER_ID15','NUM_ID15','OSC_EDO15',                        
                        'OSC_STATUS','FECREG','IP','LOGIN','FECHA_M','IP_M','LOGIN_M')
                        ->where('OSC_ID',$request->osc_id)
                        //->where(['PERIODO_ID' => $request->periodo_id,'OSC_ID' => $request->osc_id])
                        ->get();
        if($regjuridico->count() <= 0 && !empty($request->osc_id)){
            //********** Registrar la alta *****************************/
            $osc_folio     = regReqJuridicoModel::max('OSC_FOLIO');
            $osc_folio     = $osc_folio + 1;
            $nuevajuridica = new regReqJuridicoModel();
 
            $file12 =null;
            if(isset($request->osc_d12)){
                if(!empty($request->osc_d12)){
                    //Comprobar  si el campo act_const tiene un archivo asignado:
                    if($request->hasFile('osc_d12')){
                        $file12=$request->osc_id.'_'.$request->file('osc_d12')->getClientOriginalName();
                        //sube el archivo a la carpeta del servidor public/images/
                        $request->file('osc_d12')->move(public_path().'/images/', $file12);
                    }
                }
            }
            $file13 =null;
            if(isset($request->osc_d13)){
                if(!empty($request->osc_d13)){
                    //Comprobar  si el campo act_const tiene un archivo asignado:
                    if($request->hasFile('osc_d13')){
                        $file13=$request->osc_id.'_'.$request->file('osc_d13')->getClientOriginalName();
                        //sube el archivo a la carpeta del servidor public/images/
                        $request->file('osc_d13')->move(public_path().'/images/', $file13);
                    }
                }
            }
            $file14 =null;
            if(isset($request->osc_d14)){
                if(!empty($request->osc_d14)){
                    //Comprobar  si el campo act_const tiene un archivo asignado:
                    if($request->hasFile('osc_d14')){
                        $file14=$request->osc_id.'_'.$request->file('osc_d14')->getClientOriginalName();
                        //sube el archivo a la carpeta del servidor public/images/
                        $request->file('osc_d14')->move(public_path().'/images/', $file14);
                    }
                }
            }
            $file15 =null;
            if(isset($request->osc_d15)){
                if(!empty($request->osc_d15)){
                    //Comprobar  si el campo act_const tiene un archivo asignado:
                    if($request->hasFile('osc_d15')){
                        $file15=$request->osc_id.'_'.$request->file('osc_d15')->getClientOriginalName();
                        //sube el archivo a la carpeta del servidor public/images/
                        $request->file('osc_d15')->move(public_path().'/images/', $file15);
                    }
                }
            }            

            $xperiodo_id  = (int)date('Y');
            $nuevajuridica->OSC_FOLIO    = $osc_folio;    
            $nuevajuridica->PERIODO_ID   = $xperiodo_id;
            $nuevajuridica->OSC_ID       = $request->osc_id;    

            $nuevajuridica->DOC_ID12     = $request->doc_id12;
            //$nuevajuridica->FORMATO_ID12 = $request->formato_id12;
            $nuevajuridica->OSC_D12      = $file12;        
            //$nuevajuridica->NUM_ID12   = $request->num_id12;
            //$nuevajuridica->PER_ID12   = $request->per_id12;        
            //$nuevajuridica->OSC_EDO12  = $request->osc_edo12;        

            $nuevajuridica->DOC_ID13     = $request->doc_id13;
            //$nuevajuridica->FORMATO_ID13 = $request->formato_id13;
            $nuevajuridica->OSC_D13      = $file13;        
            //$nuevajuridica->NUM_ID13   = $request->num_id13;
            //$nuevajuridica->PER_ID13   = $request->per_id13;        
            //$nuevajuridica->OSC_EDO13  = $request->osc_edo13; 

            $nuevajuridica->DOC_ID14     = $request->doc_id14;
            //$nuevajuridica->FORMATO_ID14 = $request->formato_id14;
            $nuevajuridica->OSC_D14      = $file14;        
            //$nuevajuridica->NUM_ID14   = $request->num_id14;
            //$nuevajuridica->PER_ID14   = $request->per_id14;        
            //$nuevajuridica->OSC_EDO14  = $request->osc_edo14;

            $nuevajuridica->DOC_ID15     = $request->doc_id15;
            //$nuevajuridica->FORMATO_ID15 = $request->formato_id15;
            $nuevajuridica->OSC_D15      = $file15;        
            //$nuevajuridica->NUM_ID15   = $request->num_id15;
            //$nuevajuridica->PER_ID15   = $request->per_id15;        
            //$nuevajuridica->OSC_EDO15  = $request->osc_edo15;                     

            $nuevajuridica->IP           = $ip;
            $nuevajuridica->LOGIN        = $nombre;         // Usuario ;
            $nuevajuridica->save();

            if($nuevajuridica->save() == true){
                toastr()->success('Requisitos jurídicos registrados.','ok!',['positionClass' => 'toast-bottom-right']);

                /************ Bitacora inicia *************************************/ 
                setlocale(LC_TIME, "spanish");        
                $xip          = session()->get('ip');
                $xperiodo_id  = (int)date('Y');
                $xprograma_id = 1;
                $xmes_id      = (int)date('m');
                $xproceso_id  =         3;
                $xfuncion_id  =      3002;
                $xtrx_id      =       150;    //alta
                $regbitacora = regBitacoraModel::select('PERIODO_ID','MES_ID', 'PROCESO_ID','FUNCION_ID',
                                                        'TRX_ID','FOLIO','NO_VECES','FECHA_REG','IP','LOGIN',
                                                        'FECHA_M', 'IP_M', 'LOGIN_M')
                               ->where(['PERIODO_ID' => $xperiodo_id,'MES_ID' => $xmes_id,'PROCESO_ID' => $xproceso_id, 
                                        'FUNCION_ID' => $xfuncion_id,'TRX_ID' => $xtrx_id,'FOLIO'      => $request->osc_id])
                               ->get();
                if($regbitacora->count() <= 0){              // Alta
                    $nuevoregBitacora = new regBitacoraModel();              
                    $nuevoregBitacora->PERIODO_ID = $xperiodo_id;    // Año de transaccion 
                    $nuevoregBitacora->MES_ID     = $xmes_id;        // Mes de transaccion
                    $nuevoregBitacora->PROCESO_ID = $xproceso_id;    // Proceso de apoyo
                    $nuevoregBitacora->FUNCION_ID = $xfuncion_id;    // Funcion del modelado de procesos 
                    $nuevoregBitacora->TRX_ID     = $xtrx_id;        // Actividad del modelado de procesos
                    $nuevoregBitacora->FOLIO      = $request->osc_id;      // Folio    
                    $nuevoregBitacora->NO_VECES   = 1;               // Numero de veces            
                    $nuevoregBitacora->IP         = $ip;             // IP
                    $nuevoregBitacora->LOGIN      = $nombre;         // Usuario 
                    $nuevoregBitacora->save();
                    if($nuevoregBitacora->save() == true)
                       toastr()->success('Trx de requisitos juridicos dada de alta en Bitacora.','¡Ok!',['positionClass' => 'toast-bottom-right']);
                    else
                       toastr()->error('Error trx de requisitos jurídicos al dar de alta la bitacora. Por favor volver a interlo.','Ups!',['positionClass' => 'toast-bottom-right']);
                }else{                   
                    //*********** Obtine el no. de veces *****************************
                    $xno_veces = regBitacoraModel::where(['PERIODO_ID' => $xperiodo_id,'MES_ID'     => $xmes_id, 
                                                          'PROCESO_ID' => $xproceso_id,'FUNCION_ID' => $xfuncion_id, 
                                                          'TRX_ID'     => $xtrx_id,    'FOLIO'      => $request->osc_id])
                                 ->max('NO_VECES');
                    $xno_veces = $xno_veces+1;                        
                    //*********** Termina de obtener el no de veces *****************************               
                    $regbitacora = regBitacoraModel::select('NO_VECES','IP_M','LOGIN_M','FECHA_M')
                                   ->where(['PERIODO_ID' => $xperiodo_id,'MES_ID' => $xmes_id,'PROCESO_ID' => $xproceso_id, 
                                            'FUNCION_ID' => $xfuncion_id,'TRX_ID' => $xtrx_id,'FOLIO'      => $request->osc_id])
                                   ->update([
                                             'NO_VECES' => $regbitacora->NO_VECES = $xno_veces,
                                             'IP_M'     => $regbitacora->IP       = $ip,
                                             'LOGIN_M'  => $regbitacora->LOGIN_M  = $nombre,
                                             'FECHA_M'  => $regbitacora->FECHA_M  = date('Y/m/d')  //date('d/m/Y')
                                            ]);
                    toastr()->success('Trx de requisitos jurídicos actualizada en Bitacora.','¡Ok!',['positionClass' => 'toast-bottom-right']);
                }   /************ Bitacora termina *************************************/                 
            }else{
                toastr()->error('Error trx de requisitos jurídicos en Bitacora. Por favor volver a interlo.','Ups!',['positionClass' => 'toast-bottom-right']);
            }   //******************** Termina la alta ************************/ 

        }else{
            toastr()->error('Ya existe OSC con requisitos jurídicos.','Por favor editar, lo siento!',['positionClass' => 'toast-bottom-right']);
        }   // Termian If de busqueda ****************
        return redirect()->route('verJur');
    }

    /****************** Editar registro  **********/
    public function actionEditarJur($id){
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
        $regosc       = regOscModel::select('OSC_ID', 'OSC_DESC','OSC_STATUS')
                        ->orderBy('OSC_DESC','asc')
                        ->get();
        $regjuridico  = regReqJuridicoModel::select('OSC_ID','PERIODO_ID','OSC_FOLIO',
                        'DOC_ID12','FORMATO_ID12','OSC_D12','PER_ID12','NUM_ID12','OSC_EDO12',
                        'DOC_ID13','FORMATO_ID13','OSC_D13','PER_ID13','NUM_ID13','OSC_EDO13',
                        'DOC_ID14','FORMATO_ID14','OSC_D14','PER_ID14','NUM_ID14','OSC_EDO14',
                        'DOC_ID15','FORMATO_ID15','OSC_D15','PER_ID15','NUM_ID15','OSC_EDO15',                        
                        'OSC_STATUS','FECREG','IP','LOGIN','FECHA_M','IP_M','LOGIN_M')
                        ->where('OSC_ID', $id)
                        ->first();
        if($regjuridico->count() <= 0){
            toastr()->error('No existe requisito jurídico.','Lo siento!',['positionClass' => 'toast-bottom-right']);
        }
        return view('sicinar.requisitos.editarReqj',compact('nombre','usuario','regosc','regjuridico','regnumeros', 'regperiodos','regperiodicidad','regformatos'));

    }
    
    public function actionActualizarJur(reqjuridicoRequest $request, $id){
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
        $regjuridico = regReqJuridicoModel::where('OSC_ID',$id);
        if($regjuridico->count() <= 0)
            toastr()->error('No existe requisito jurídico.','¡Por favor volver a intentar!',['positionClass' => 'toast-bottom-right']);
        else{        
            //****************** Actualizar **************************/
            $name12 =null;        
            if($request->hasFile('osc_d12')){
                $name12 = $id.'_'.$request->file('osc_d12')->getClientOriginalName(); 
                $request->file('osc_d12')->move(public_path().'/images/', $name12);
            }
            $name13 =null;         
            if($request->hasFile('osc_d13')){
                $name13 = $id.'_'.$request->file('osc_d13')->getClientOriginalName(); 
                $request->file('osc_d13')->move(public_path().'/images/', $name13);
            }            
            $name14 =null;        
            if($request->hasFile('osc_d14')){
                $name14 = $id.'_'.$request->file('osc_d14')->getClientOriginalName(); 
                $request->file('osc_d14')->move(public_path().'/images/', $name14);
            }  
            $name15 =null;        
            if($request->hasFile('osc_d15')){
                $name15 = $id.'_'.$request->file('osc_d15')->getClientOriginalName(); 
                $request->file('osc_d15')->move(public_path().'/images/', $name15);
            }                        
            // ************* Actualizamos registro **********************************
            $regjuridico = regReqJuridicoModel::where('OSC_ID',$id)        
                           ->update([                
                                     //'PERIODO_ID'   => $request->periodo_id,
                                    
                                     'DOC_ID12'     => $request->doc_id12,
                                     //'FORMATO_ID12' => $request->formato_id12,                            
                                     //'OSC_D12'      => $name12,                                                       
                                     //'PER_ID12'     => $request->per_id12,
                                     //'NUM_ID12'     => $request->num_id12,                
                                     //'OSC_EDO12'    => $request->osc_edo12,
                                    
                                     'DOC_ID13'     => $request->doc_id13,
                                     //'FORMATO_ID13' => $request->formato_id13,                                          
                                     //'OSC_D13'      => $name13,              
                                     //'PER_ID13'     => $request->per_id13,
                                     //'NUM_ID13'     => $request->num_id13,                
                                     //'OSC_EDO13'    => $request->osc_edo13,
                                     
                                     'DOC_ID14'     => $request->doc_id14,
                                     //'FORMATO_ID14' => $request->formato_id14, 
                                     //'OSC_D14'      => $name14,        
                                     //'PER_ID14'     => $request->per_id14,
                                     //'NUM_ID14'     => $request->num_id14,                
                                     //'OSC_EDO14'    => $request->osc_edo14,

                                     'DOC_ID15'     => $request->doc_id15,
                                     //'FORMATO_ID15' => $request->formato_id15, 
                                     //'OSC_D15'      => $name15,        
                                     //'PER_ID15'     => $request->per_id15,
                                     //'NUM_ID15'     => $request->num_id15,                
                                     //'OSC_EDO15'    => $request->osc_edo15,                                     

                                     'OSC_STATUS'   => $request->osc_status,
                                     'IP_M'         => $ip,
                                     'LOGIN_M'      => $nombre,
                                     'FECHA_M'      => date('Y/m/d')    //date('d/m/
                                    ]);
            toastr()->success('Requisitos jurídicos actualizados.','¡Ok!',['positionClass' => 'toast-bottom-right']);

            /************ Bitacora inicia *************************************/ 
            setlocale(LC_TIME, "spanish");        
            $xip          = session()->get('ip');
            $xperiodo_id  = (int)date('Y');
            $xprograma_id = 1;
            $xmes_id      = (int)date('m');
            $xproceso_id  =         3;
            $xfuncion_id  =      3002;
            $xtrx_id      =       151;    //Actualizar         
            $regbitacora = regBitacoraModel::select('PERIODO_ID','MES_ID','PROCESO_ID','FUNCION_ID', 
                                                    'TRX_ID','FOLIO','NO_VECES','FECHA_REG','IP','LOGIN','FECHA_M',
                                                    'IP_M','LOGIN_M')
                           ->where(['PERIODO_ID' => $xperiodo_id, 'MES_ID' => $xmes_id,'PROCESO_ID' => $xproceso_id, 
                                    'FUNCION_ID' => $xfuncion_id, 'TRX_ID' => $xtrx_id,'FOLIO'      => $id])
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
                    toastr()->success('Trx de requisitos jurídicos dada de alta en Bitacora.','¡Ok!',['positionClass' => 'toast-bottom-right']);
                else
                    toastr()->error('Error trx en requisitos jurídicos al dar de alta la bitacora. Por favor volver a interlo.','Ups!',['positionClass' => 'toast-bottom-right']);
            }else{                   
                //*********** Obtine el no. de veces *****************************
                $xno_veces = regBitacoraModel::where(['PERIODO_ID' => $xperiodo_id,'MES_ID' => $xmes_id,'PROCESO_ID' => $xproceso_id, 
                                                      'FUNCION_ID' => $xfuncion_id,'TRX_ID' => $xtrx_id,'FOLIO'      => $id])
                        ->max('NO_VECES');
                $xno_veces = $xno_veces+1;                        
                //*********** Termina de obtener el no de veces *****************************                    
                $regbitacora = regBitacoraModel::select('NO_VECES','IP_M','LOGIN_M','FECHA_M')
                               ->where(['PERIODO_ID' => $xperiodo_id, 'MES_ID' => $xmes_id,'PROCESO_ID' => $xproceso_id, 
                                        'FUNCION_ID' => $xfuncion_id, 'TRX_ID' => $xtrx_id,'FOLIO'      => $id])
                               ->update([
                                          'NO_VECES'=> $regbitacora->NO_VECES = $xno_veces,
                                          'IP_M'    => $regbitacora->IP       = $ip,
                                          'LOGIN_M' => $regbitacora->LOGIN_M  = $nombre,
                                          'FECHA_M' => $regbitacora->FECHA_M  = date('Y/m/d')  //date('d/m/Y')
                                        ]);
                toastr()->success('Trx de requisitos jurídicos actualizada en Bitacora.','¡Ok!',['positionClass' => 'toast-bottom-right']);
            }   /************ Bitacora termina *************************************/         
        }       /************ Termina actualizar ***********************************/
        return redirect()->route('verJur');
    }

    /****************** Editar inf. juridica **********/
    public function actionEditarJur12($id){
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
        $regjuridico  = regReqJuridicoModel::select('OSC_ID','PERIODO_ID','OSC_FOLIO',
                        'DOC_ID12','FORMATO_ID12','OSC_D12','PER_ID12','NUM_ID12','OSC_EDO12',
                        'DOC_ID13','FORMATO_ID13','OSC_D13','PER_ID13','NUM_ID13','OSC_EDO13',
                        'DOC_ID14','FORMATO_ID14','OSC_D14','PER_ID14','NUM_ID14','OSC_EDO14',
                        'DOC_ID15','FORMATO_ID15','OSC_D15','PER_ID15','NUM_ID15','OSC_EDO15',
                        'OSC_STATUS','FECREG','IP','LOGIN','FECHA_M','IP_M','LOGIN_M')
                        ->where('OSC_ID', $id)
                        ->first();
        if($regjuridico->count() <= 0){
            toastr()->error('No existe requisito jurídico de acta constitutiva 12.','Lo siento!',['positionClass' => 'toast-bottom-right']);
        }
        return view('sicinar.requisitos.editarReqj12',compact('nombre','usuario','regosc','regjuridico','regnumeros', 'regperiodos','regperiodicidad','regformatos'));
    }

    public function actionActualizarJur12(reqjuridico12Request $request, $id){
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
        $regjuridico = regReqJuridicoModel::where('OSC_ID',$id);
        if($regjuridico->count() <= 0)
            toastr()->error('No existe Requisito jurídico de acta constitutiva 12.','¡Por favor volver a intentar!',['positionClass' => 'toast-bottom-right']);
        else{        
            $name12 =null;
            if($request->hasFile('osc_d12')){
                $name12 = $id.'_'.$request->file('osc_d12')->getClientOriginalName(); 
                $request->file('osc_d12')->move(public_path().'/images/', $name12);
                // ************* Actualizamos registro **********************************
                $regjuridico = regReqJuridicoModel::where('OSC_ID',$id)        
                           ->update([   
                                     'DOC_ID12'      => $request->doc_id12,
                                     //'FORMATO_ID12'=> $request->formato_id12,             
                                     'OSC_D12'       => $name12,                  
                                     //'PER_ID12'    => $request->per_id12,
                                     //'NUM_ID12'    => $request->num_id12,                
                                     //'OSC_EDO12'   => $request->osc_edo12,

                                     'IP_M'          => $ip,
                                     'LOGIN_M'       => $nombre,
                                     'FECHA_M'       => date('Y/m/d')    //date('d/m/Y')                                
                                     ]);
                toastr()->success('Requisito de acta constitutiva 12 actualizada.','¡Ok!',['positionClass' => 'toast-bottom-right']);
            }else{
                // ************* Actualizamos registro **********************************
                $regjuridico = regReqJuridicoModel::where('OSC_ID',$id)        
                           ->update([   
                                     'DOC_ID12'    => $request->doc_id12,
                                     //'FORMATO_ID12'=> $request->formato_id12,             
                                     //'OSC_D12'    => $name12,                  
                                     //'PER_ID12'    => $request->per_id12,
                                     //'NUM_ID12'    => $request->num_id12,                
                                     //'OSC_EDO12'   => $request->osc_edo12,

                                     'IP_M'        => $ip,
                                     'LOGIN_M'     => $nombre,
                                     'FECHA_M'     => date('Y/m/d')    //date('d/m/Y')                                
                                     ]);                
                toastr()->success('Requisito de acta constitutiva 12 actualizada.','¡Ok!',['positionClass' => 'toast-bottom-right']);
            }

            /************ Bitacora inicia *************************************/ 
            setlocale(LC_TIME, "spanish");        
            $xip          = session()->get('ip');
            $xperiodo_id  = (int)date('Y');
            $xprograma_id = 1;
            $xmes_id      = (int)date('m');
            $xproceso_id  =         3;
            $xfuncion_id  =      3002;
            $xtrx_id      =       151;    //Actualizar         
            $regbitacora = regBitacoraModel::select('PERIODO_ID','MES_ID','PROCESO_ID','FUNCION_ID', 
                               'TRX_ID','FOLIO','NO_VECES','FECHA_REG','IP','LOGIN','FECHA_M','IP_M','LOGIN_M')
                           ->where(['PERIODO_ID' => $xperiodo_id, 'MES_ID' => $xmes_id,'PROCESO_ID' => $xproceso_id, 
                                    'FUNCION_ID' => $xfuncion_id, 'TRX_ID' => $xtrx_id,'FOLIO'      => $id])
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
                        toastr()->success('Trx de requisito de acta constitutiva actualizada dada de alta en bitacora.','¡Ok!',['positionClass' => 'toast-bottom-right']);
                    else
                        toastr()->error('Error de Trx de requisito de acta constitutiva al dar de alta en bitacora. Por favor volver a interlo.','Ups!',['positionClass' => 'toast-bottom-right']);
            }else{                   
                    //*********** Obtine el no. de veces *****************************
                    $xno_veces = regBitacoraModel::where(['PERIODO_ID' => $xperiodo_id,'MES_ID'     => $xmes_id, 
                                                          'PROCESO_ID' => $xproceso_id,'FUNCION_ID' => $xfuncion_id, 
                                                          'TRX_ID'     => $xtrx_id,    'FOLIO'      => $id])
                                 ->max('NO_VECES');
                    $xno_veces = $xno_veces+1;                        
                    //*********** Termina de obtener el no de veces *****************************                    
                    $regbitacora= regBitacoraModel::select('NO_VECES','IP_M','LOGIN_M','FECHA_M')
                                  ->where(['PERIODO_ID' => $xperiodo_id,'PROGRAMA_ID' => $xprograma_id,'MES_ID' => $xmes_id,
                                           'PROCESO_ID' => $xproceso_id, 'FUNCION_ID' => $xfuncion_id,'TRX_ID' => $xtrx_id, 
                                           'FOLIO' => $id])
                                  ->update([
                                            'NO_VECES' => $regbitacora->NO_VECES = $xno_veces,
                                            'IP_M'     => $regbitacora->IP       = $ip,
                                            'LOGIN_M'  => $regbitacora->LOGIN_M  = $nombre,
                                            'FECHA_M'  => $regbitacora->FECHA_M  = date('Y/m/d')  //date('d/m/Y')
                                          ]);
                    toastr()->success('Trx de requisito de acta constitutiva actualizada en Bitacora.','¡Ok!',['positionClass' => 'toast-bottom-right']);
            }   /************ Bitacora termina *************************************/         
        }       /************ Termina de actualizar ********************************/
        return redirect()->route('verJur');
    }    

    /****************** Editar inf. juridica **********/
    public function actionEditarJur13($id){
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
        $regjuridico = regReqJuridicoModel::select('OSC_ID','PERIODO_ID','OSC_FOLIO',
                        'DOC_ID12','FORMATO_ID12','OSC_D12','PER_ID12','NUM_ID12','OSC_EDO12',
                        'DOC_ID13','FORMATO_ID13','OSC_D13','PER_ID13','NUM_ID13','OSC_EDO13',
                        'DOC_ID14','FORMATO_ID14','OSC_D14','PER_ID14','NUM_ID14','OSC_EDO14',
                        'DOC_ID15','FORMATO_ID15','OSC_D15','PER_ID15','NUM_ID15','OSC_EDO15',
                        'OSC_STATUS','FECREG','IP','LOGIN','FECHA_M','IP_M','LOGIN_M')
                       ->where('OSC_ID', $id)
                       ->first();
        if($regjuridico->count() <= 0){
            toastr()->error('No existe requisito jurídico de registro en el IFREM 13.','Lo siento!',['positionClass' => 'toast-bottom-right']);
        }
        return view('sicinar.requisitos.editarReqj13',compact('nombre','usuario','regosc','regjuridico','regnumeros', 'regperiodos','regperiodicidad','regformatos'));
    }

    public function actionActualizarJur13(reqjuridico13Request $request, $id){
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
        $regjuridico = regReqJuridicoModel::where('OSC_ID',$id);
        if($regjuridico->count() <= 0)
            toastr()->error('No existe requisito jurídico de registro en el IFREM 13.','¡Por favor volver a intentar!',['positionClass' => 'toast-bottom-right']);
        else{        
            //***************** Actualizar *****************************
            $name13 =null;
            if($request->hasFile('osc_d13')){
                $name13 = $id.'_'.$request->file('osc_d13')->getClientOriginalName(); 
                $request->file('osc_d13')->move(public_path().'/images/', $name13);
                // ************* Actualizamos registro **********************************
                $regjuridico = regReqJuridicoModel::where('OSC_ID',$id)        
                               ->update([  
                                      'DOC_ID13'    => $request->doc_id13,
                                      //'FORMATO_ID13'=> $request->formato_id13,             
                                      'OSC_D13'     => $name13,           
                                      //'PER_ID13'  => $request->per_id13,
                                      //'NUM_ID13'  => $request->num_id13,                
                                      //'OSC_EDO13' => $request->osc_edo13,

                                      'IP_M'        => $ip,
                                      'LOGIN_M'     => $nombre,
                                      'FECHA_M'     => date('Y/m/d')    //date('d/m/Y')                                
                                    ]);
                toastr()->success('Requisitos jurídicos Trx de registro en el IFREM 13 actualizada en Bitacora.','¡Ok!',['positionClass' => 'toast-bottom-right']);
            }else{
                // ************* Actualizamos registro **********************************
                $regjuridico = regReqJuridicoModel::where('OSC_ID',$id)        
                               ->update([  
                                      'DOC_ID13'    => $request->doc_id13,
                                      //'FORMATO_ID13'=> $request->formato_id13,             
                                      //'OSC_D13'   => $name13,           
                                      //'PER_ID13'  => $request->per_id13,
                                      //'NUM_ID13'  => $request->num_id13,                
                                      //'OSC_EDO13' => $request->osc_edo13,

                                      'IP_M'        => $ip,
                                      'LOGIN_M'     => $nombre,
                                      'FECHA_M'     => date('Y/m/d')    //date('d/m/Y')                                
                                    ]);                
                toastr()->success('Requisitos jurídicos Trx de registro en el IFREM 13 actualizada en Bitacora.','¡Ok!',['positionClass' => 'toast-bottom-right']);
            }

            /************ Bitacora inicia *************************************/ 
            setlocale(LC_TIME, "spanish");        
            $xip          = session()->get('ip');
            $xperiodo_id  = (int)date('Y');
            $xprograma_id = 1;
            $xmes_id      = (int)date('m');
            $xproceso_id  =         3;
            $xfuncion_id  =      3002;
            $xtrx_id      =       151;    //Actualizar         
            $regbitacora = regBitacoraModel::select('PERIODO_ID','MES_ID','PROCESO_ID','FUNCION_ID', 
                               'TRX_ID','FOLIO','NO_VECES','FECHA_REG','IP','LOGIN','FECHA_M','IP_M','LOGIN_M')
                           ->where(['PERIODO_ID' => $xperiodo_id, 'MES_ID' => $xmes_id, 
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
                        toastr()->success('Trx de registro en el IFREM 13 dada de alta.','¡Ok!',['positionClass' => 'toast-bottom-right']);
                    else
                        toastr()->error('Error de Trx de registro en el IFREM 13 al dar de alta en bitacora. Por favor volver a interlo.','Ups!',['positionClass' => 'toast-bottom-right']);
            }else{                   
                    //*********** Obtine el no. de veces *****************************
                    $xno_veces = regBitacoraModel::where(['PERIODO_ID' => $xperiodo_id, 
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
                toastr()->success('Trx de registro en el IFREM 13 actualizada en Bitacora.','¡Ok!',['positionClass' => 'toast-bottom-right']);
            }   /************ Bitacora termina *************************************/         
        }       /************ Termina de actualizar ********************************/
        return redirect()->route('verJur');    
    }    

    /****************** Editar inf. jurídica *********************************/
    public function actionEditarJur14($id){
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
        $regjuridico  = regReqJuridicoModel::select('OSC_ID','PERIODO_ID','OSC_FOLIO',
                        'DOC_ID12','FORMATO_ID12','OSC_D12','PER_ID12','NUM_ID12','OSC_EDO12',
                        'DOC_ID13','FORMATO_ID13','OSC_D13','PER_ID13','NUM_ID13','OSC_EDO13',
                        'DOC_ID14','FORMATO_ID14','OSC_D14','PER_ID14','NUM_ID14','OSC_EDO14',
                        'DOC_ID15','FORMATO_ID15','OSC_D15','PER_ID15','NUM_ID15','OSC_EDO15',
                        'OSC_STATUS','FECREG','IP','LOGIN','FECHA_M','IP_M','LOGIN_M')
                        ->where('OSC_ID',$id)
                        ->first();
        if($regjuridico->count() <= 0){
            toastr()->error('No existe requisito jurídico de curriculum 14.','Lo siento!',['positionClass' => 'toast-bottom-right']);
        }   /********************* Termina de actualizar ***********************/
        return view('sicinar.requisitos.editarReqj14',compact('nombre','usuario','regosc','regjuridico','regnumeros', 'regperiodos','regperiodicidad','regformatos'));
    }

    public function actionActualizarJur14(reqjuridico14Request $request, $id){
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
        $regjuridico = regReqJuridicoModel::where('OSC_ID',$id);
        if($regjuridico->count() <= 0)
            toastr()->error('No existe requisito jurídico, curriculum 14.','¡Por favor volver a intentar!',['positionClass' => 'toast-bottom-right']);
        else{        

            //***************** Actualizar *****************************
            $name14 =null;
            if($request->hasFile('osc_d14')){
                echo "Escribió en el campo de texto 14: " .'-'. $request->osc_d14 .'-'. "<br><br>"; 
                $name14 = $id.'_'.$request->file('osc_d14')->getClientOriginalName(); 
                //sube el archivo a la carpeta del servidor public/images/
                $request->file('osc_d14')->move(public_path().'/images/', $name14);

                // ************* Actualizamos registro **********************************
                $regjuridico = regReqJuridicoModel::where('OSC_ID',$id)        
                               ->update([  
                                      'DOC_ID14'    => $request->doc_id14,
                                      //'FORMATO_ID14'=> $request->formato_id14,             
                                      'OSC_D14'     => $name14,           
                                      //'PER_ID14'    => $request->per_id14,
                                      //'NUM_ID14'    => $request->num_id14,                
                                      //'OSC_EDO14'   => $request->osc_edo14,

                                      'IP_M'        => $ip,
                                      'LOGIN_M'     => $nombre,
                                      'FECHA_M'     => date('Y/m/d')    //date('d/m/Y')                                
                                    ]);
                toastr()->success('Requisito jurídico de curriculum 14 actualizado.','¡Ok!',['positionClass' => 'toast-bottom-right']);
            }else{
                // ************* Actualizamos registro **********************************
                $regjuridico = regReqJuridicoModel::where('OSC_ID',$id)        
                               ->update([  
                                      'DOC_ID14'    => $request->doc_id14,
                                      //'FORMATO_ID14'=> $request->formato_id14,             
                                      //'OSC_D14'   => $name14,           
                                      //'PER_ID14'    => $request->per_id14,
                                      //'NUM_ID14'    => $request->num_id14,                
                                      //'OSC_EDO14'   => $request->osc_edo14,

                                      'IP_M'        => $ip,
                                      'LOGIN_M'     => $nombre,
                                      'FECHA_M'     => date('Y/m/d')    //date('d/m/Y')                                
                                    ]);                
                toastr()->error('Requisito jurídico de curriculum 14 actualizado.','¡Ok!',['positionClass' => 'toast-bottom-right']);
            }

            /************ Bitacora inicia *************************************/ 
            setlocale(LC_TIME, "spanish");        
            $xip          = session()->get('ip');
            $xperiodo_id  = (int)date('Y');
            $xprograma_id = 1;
            $xmes_id      = (int)date('m');
            $xproceso_id  =         3;
            $xfuncion_id  =      3002;
            $xtrx_id      =       151;    //Actualizar         
            $regbitacora = regBitacoraModel::select('PERIODO_ID','MES_ID','PROCESO_ID','FUNCION_ID', 
                               'TRX_ID','FOLIO','NO_VECES','FECHA_REG','IP','LOGIN','FECHA_M','IP_M','LOGIN_M')
                           ->where(['PERIODO_ID' => $xperiodo_id, 'MES_ID' => $xmes_id,'PROCESO_ID' => $xproceso_id, 
                                    'FUNCION_ID' => $xfuncion_id, 'TRX_ID' => $xtrx_id,'FOLIO'      => $id])
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
                        toastr()->success('Trx de curriculum dada de alta en Bitacora.','¡Ok!',['positionClass' => 'toast-bottom-right']);
                    else
                        toastr()->error('Error de trx de curriculum al dar de alta en bitacora. Por favor volver a interlo.','Ups!',['positionClass' => 'toast-bottom-right']);
            }else{                   
                    //*********** Obtine el no. de veces *****************************
                    $xno_veces = regBitacoraModel::where(['PERIODO_ID' => $xperiodo_id,'MES_ID'     => $xmes_id, 
                                                          'PROCESO_ID' => $xproceso_id,'FUNCION_ID' => $xfuncion_id, 
                                                          'TRX_ID'     => $xtrx_id,    'FOLIO'      => $id])
                                 ->max('NO_VECES');
                    $xno_veces = $xno_veces+1;                        
                    //*********** Termina de obtener el no de veces *****************************                    
                    $regbitacora= regBitacoraModel::select('NO_VECES','IP_M','LOGIN_M','FECHA_M')
                                  ->where(['PERIODO_ID' => $xperiodo_id,'PROGRAMA_ID' => $xprograma_id,'MES_ID' => $xmes_id,
                                           'PROCESO_ID' => $xproceso_id, 'FUNCION_ID' => $xfuncion_id,'TRX_ID' => $xtrx_id, 
                                           'FOLIO' => $id])
                                  ->update([
                                            'NO_VECES' => $regbitacora->NO_VECES = $xno_veces,
                                            'IP_M'     => $regbitacora->IP       = $ip,
                                            'LOGIN_M'  => $regbitacora->LOGIN_M  = $nombre,
                                            'FECHA_M'  => $regbitacora->FECHA_M  = date('Y/m/d')  //date('d/m/Y')
                                          ]);
                toastr()->success('Trx de curriculum actualizado en Bitacora .','¡Ok!',['positionClass' => 'toast-bottom-right']);
            }   /************ Bitacora termina *************************************/         
        }       /************ Termina de actualizar ********************************/
        return redirect()->route('verJur');    
    }    

    /****************** Editar inf. juridica **********/
    public function actionEditarJur15($id){
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
        $regjuridico  = regReqJuridicoModel::select('OSC_ID','PERIODO_ID','OSC_FOLIO',
                        'DOC_ID12','FORMATO_ID12','OSC_D12','PER_ID12','NUM_ID12','OSC_EDO12',
                        'DOC_ID13','FORMATO_ID13','OSC_D13','PER_ID13','NUM_ID13','OSC_EDO13',
                        'DOC_ID14','FORMATO_ID14','OSC_D14','PER_ID14','NUM_ID14','OSC_EDO14',
                        'DOC_ID15','FORMATO_ID15','OSC_D15','PER_ID15','NUM_ID15','OSC_EDO15',
                        'OSC_STATUS','FECREG','IP','LOGIN','FECHA_M','IP_M','LOGIN_M')
                        ->where('OSC_ID', $id)
                        ->first();
        if($regjuridico->count() <= 0){
            toastr()->error('No existe requisito jurídico de protocolización.','Lo siento!',['positionClass' => 'toast-bottom-right']);
        }
        return view('sicinar.requisitos.editarReqj15',compact('nombre','usuario','regosc','regjuridico','regnumeros', 'regperiodos','regperiodicidad','regformatos'));
    }

    public function actionActualizarJur15(reqjuridico15Request $request, $id){
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
        $regjuridico = regReqJuridicoModel::where('OSC_ID',$id);
        if($regjuridico->count() <= 0)
            toastr()->error('No existe archivo de protocolización 15.','¡Por favor volver a intentar!',['positionClass' => 'toast-bottom-right']);
        else{        
            $name15 =null;
            if($request->hasFile('osc_d15')){
                $name15 = $id.'_'.$request->file('osc_d15')->getClientOriginalName(); 
                $request->file('osc_d15')->move(public_path().'/images/', $name15);
                // ************* Actualizamos registro **********************************
                $regjuridico = regReqJuridicoModel::where('OSC_ID',$id)        
                           ->update([   
                                     'DOC_ID15'    => $request->doc_id15,
                                     //'FORMATO_ID15'=> $request->formato_id15,
                                     'OSC_D15'     => $name15,                  
                                     //'PER_ID15'  => $request->per_id15,
                                     //'NUM_ID15'  => $request->num_id15,                
                                     //'OSC_EDO15' => $request->osc_edo15,

                                     'IP_M'        => $ip,
                                     'LOGIN_M'     => $nombre,
                                     'FECHA_M'     => date('Y/m/d')    //date('d/m/Y')                                
                                     ]);
                echo "Escribió en el campo de texto 15-1: " .'-'. $name15 .'-'. "<br><br>";
                toastr()->success('archivo de requisito jurídico de protocolización 15 actualizado.','¡Ok!',['positionClass' => 'toast-bottom-right']);
            }else{
                // ************* Actualizamos registro **********************************
                echo "Escribió en el campo de texto 15-2: " .'-'. $request->osc_d15 .'-'. "<br><br>"; 
                $regjuridico = regReqJuridicoModel::where('OSC_ID',$id)        
                           ->update([   
                                     'DOC_ID15'    => $request->doc_id15,
                                     //'FORMATO_ID15'=> $request->formato_id15,             
                                     //'OSC_D15'   => $name15,       
                                     //'PER_ID15'  => $request->per_id15,
                                     //'NUM_ID15'  => $request->num_id15,                
                                     //'OSC_EDO15' => $request->osc_edo15,

                                     'IP_M'        => $ip,
                                     'LOGIN_M'     => $nombre,
                                     'FECHA_M'     => date('Y/m/d')    //date('d/m/Y')                                
                                     ]);                
                toastr()->success('archivo de requisito jurídico de protocolización 15 actualizado.','¡Ok!',['positionClass' => 'toast-bottom-right']);
            }

            /************ Bitacora inicia *************************************/ 
            setlocale(LC_TIME, "spanish");        
            $xip          = session()->get('ip');
            $xperiodo_id  = (int)date('Y');
            $xprograma_id = 1;
            $xmes_id      = (int)date('m');
            $xproceso_id  =         3;
            $xfuncion_id  =      3002;
            $xtrx_id      =       151;    //Actualizar         
            $regbitacora = regBitacoraModel::select('PERIODO_ID','MES_ID','PROCESO_ID','FUNCION_ID', 
                               'TRX_ID','FOLIO','NO_VECES','FECHA_REG','IP','LOGIN','FECHA_M','IP_M','LOGIN_M')
                           ->where(['PERIODO_ID' => $xperiodo_id, 'MES_ID' => $xmes_id, 
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
                        toastr()->success('Trx de protocolización 15 dado de alta en Bitacora.','¡Ok!',['positionClass' => 'toast-bottom-right']);
                    else
                        toastr()->error('Error de trx de protocolización 15 al dar de alta la bitacora. Por favor volver a interlo.','Ups!',['positionClass' => 'toast-bottom-right']);
            }else{                   
                    //*********** Obtine el no. de veces *****************************
                    $xno_veces = regBitacoraModel::where(['PERIODO_ID' => $xperiodo_id, 
                                                      'MES_ID' => $xmes_id, 'PROCESO_ID' => $xproceso_id, 
                                                      'FUNCION_ID' => $xfuncion_id, 'TRX_ID' => $xtrx_id, 'FOLIO' => $id])
                                 ->max('NO_VECES');
                    $xno_veces = $xno_veces+1;                        
                    //*********** Termina de obtener el no de veces *****************************                    
                    $regbitacora= regBitacoraModel::select('NO_VECES','IP_M','LOGIN_M','FECHA_M')
                                  ->where(['PERIODO_ID' => $xperiodo_id,'PROGRAMA_ID' => $xprograma_id,
                                           'MES_ID' => $xmes_id,'PROCESO_ID' => $xproceso_id, 
                                           'FUNCION_ID' => $xfuncion_id,'TRX_ID' => $xtrx_id,'FOLIO' => $id])
                                  ->update([
                                            'NO_VECES'=> $regbitacora->NO_VECES = $xno_veces,
                                            'IP_M'    => $regbitacora->IP       = $ip,
                                            'LOGIN_M' => $regbitacora->LOGIN_M  = $nombre,
                                            'FECHA_M' => $regbitacora->FECHA_M  = date('Y/m/d')  //date('d/m/Y')
                                          ]);
                    toastr()->success('Trx de protocolización 15 actualizada en Bitacora.','¡Ok!',['positionClass' => 'toast-bottom-right']);
            }   /************ Bitacora termina *************************************/         
        }       /************ Termina de actualizar ********************************/
        return redirect()->route('verJur');
    }    
 
    //***** Borrar registro completo ***********************
    public function actionBorrarJur($id){
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
        $regjuridico = regReqJuridicoModel::where('OSC_FOLIO',$id);
        if($regjuridico->count() <= 0)
            toastr()->error('No existe requisito jurídico.','¡Por favor volver a intentar!',['positionClass' => 'toast-bottom-right']);
        else{        
            $regjuridico->delete();
            toastr()->success('Requisito jurídico eliminado.','¡Ok!',['positionClass' => 'toast-bottom-right']);

            //echo 'Ya entre aboorar registro..........';
            /************ Bitacora inicia *************************************/ 
            setlocale(LC_TIME, "spanish");        
            $xip          = session()->get('ip');
            $xperiodo_id  = (int)date('Y');
            $xprograma_id = 1;
            $xmes_id      = (int)date('m');
            $xproceso_id  =         3;
            $xfuncion_id  =      3002;
            $xtrx_id      =       152;     // Cancelación transaccion 
            $regbitacora = regBitacoraModel::select('PERIODO_ID', 'MES_ID','PROCESO_ID','FUNCION_ID', 
                           'TRX_ID','FOLIO','NO_VECES','FECHA_REG','IP','LOGIN','FECHA_M','IP_M','LOGIN_M')
                           ->where(['PERIODO_ID' => $xperiodo_id, 'MES_ID' => $xmes_id, 
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
                    toastr()->success('Trx de requisitos juridicos eliminados registrados en Bitacora.','¡Ok!',['positionClass' => 'toast-bottom-right']);
                else
                    toastr()->error('Error inesperado en Trx de requisitos juridicos en bitacora. Por favor volver a interlo.','Ups!',['positionClass' => 'toast-bottom-right']);
            }else{                   
                //*********** Obtine el no. de veces *****************************
                $xno_veces = regBitacoraModel::where(['PERIODO_ID' => $xperiodo_id,'MES_ID'     => $xmes_id,
                                                      'PROCESO_ID' => $xproceso_id,'FUNCION_ID' => $xfuncion_id, 
                                                      'TRX_ID'     => $xtrx_id,    'FOLIO'      => $id])
                             ->max('NO_VECES');
                $xno_veces = $xno_veces+1;                        
                //*********** Termina de obtener el no de veces *****************************         
                $regbitacora = regBitacoraModel::select('NO_VECES','IP_M','LOGIN_M','FECHA_M')
                               ->where(['PERIODO_ID' => $xperiodo_id,'PROGRAMA_ID' => $xprograma_id,'MES_ID' => $xmes_id, 
                                        'PROCESO_ID' => $xproceso_id, 'FUNCION_ID' => $xfuncion_id, 'TRX_ID' => $xtrx_id, 
                                        'FOLIO' => $id])
                               ->update([
                                         'NO_VECES' => $regbitacora->NO_VECES = $xno_veces,
                                         'IP_M'     => $regbitacora->IP       = $ip,
                                         'LOGIN_M'  => $regbitacora->LOGIN_M  = $nombre,
                                         'FECHA_M'  => $regbitacora->FECHA_M  = date('Y/m/d')  //date('d/m/Y')
                                        ]);
                toastr()->success('Trx de eliminar requisitos juridicos actualizda en Bitacora.','¡Ok!',['positionClass' => 'toast-bottom-right']);
            }   /************ Bitacora termina *************************************/    
        }       /************ Termina de eliminar **********************************/
        return redirect()->route('verJur');
    }    

}
