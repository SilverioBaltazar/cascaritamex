<?php
//**************************************************************/
//* File:       cedulaController.php
//* Función:    Cedula detección de necesidades
//* Autor:      Ing. Silverio Baltazar Barrientos Zarate
//* Modifico:   Ing. Silverio Baltazar Barrientos Zarate
//* Fecha act.: febrero 2021
//* @Derechos reservados. Gobierno del Estado de México
//*************************************************************/
namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests\cedulaRequest;
use App\Http\Requests\cedulaarticuloRequest;

use App\regBitacoraModel;
use App\regIapModel;
use App\regArticulosModel;
use App\regTiposModel;
use App\regPeriodosaniosModel;
use App\regPfiscalesModel;
use App\regMesesModel;
use App\regDiasModel;
use App\regCedulaModel;
use App\regCedulaarticulosModel;

// Exportar a excel 
use App\Exports\ExportCedulaExcel;
use Maatwebsite\Excel\Facades\Excel;
// Exportar a pdf
use PDF;
//use Options;

class cedulaController extends Controller
{

    public function actionBuscarCedula(Request $request)
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

        $regperiodos  = regPfiscalesModel::select('PERIODO_ID', 'PERIODO_DESC')->get();  
        $reganios     = regPeriodosaniosModel::select('PERIODO_ID','PERIODO_DESC')
                        ->wherein('PERIODO_ID',[2020,2021,2022,2023])
                        ->get();
        $regmeses     = regMesesModel::select('MES_ID','MES_DESC')->get();      
        $regdias      = regDiasModel::select('DIA_ID','DIA_DESC')->get(); 
        $totarticulos = regCedulaarticulosModel::join('JP_CEDULA','JP_CEDULA.CEDULA_FOLIO', '=', 
                                                                  'JP_CEDULA_ARTICULOS.CEDULA_FOLIO')
                        ->select('JP_CEDULA.PERIODO_ID','JP_CEDULA.CEDULA_FOLIO')
                        ->selectRaw('COUNT(*) AS TOTARTICULOS')
                        ->groupBy('JP_CEDULA.PERIODO_ID','JP_CEDULA.CEDULA_FOLIO')
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
            $regiap     = regIapModel::select('IAP_ID', 'IAP_DESC','IAP_STATUS')
                          ->get(); 
            $regcedula  = regCedulaModel::join('JP_IAPS','JP_IAPS.IAP_ID','=','JP_CEDULA.IAP_ID')
                          ->select( 'JP_IAPS.IAP_DESC','JP_CEDULA.*')                   
                          ->orderBy('JP_CEDULA.PERIODO_ID'  ,'ASC')
                          ->orderBy('JP_CEDULA.IAP_ID'      ,'ASC')
                          ->orderBy('JP_CEDULA.CEDULA_FOLIO','asc')
                       //->name($name)    //Metodos personalizados es equvalente a ->where('IAP_DESC', 'LIKE', "%$name%");
                       //->acti($acti)    //Metodos personalizados
                       //->bio($bio)      //Metodos personalizados
                       ->folio($folio)    //Metodos personalizados  
                       ->nameiap($nameiap) //Metodos personalizados                         
                       ->paginate(30);
        }else{
            $regiap     = regIapModel::select('IAP_ID', 'IAP_DESC','IAP_STATUS')
                          ->where('IAP_ID',$arbol_id)
                          ->get();                                 
            $regcedula  = regCedulaModel::join('JP_IAPS','JP_IAPS.IAP_ID','=','JP_CEDULA.IAP_ID')
                          ->select( 'JP_IAPS.IAP_DESC','JP_CEDULA.*')    
                          ->where(  'JP_CEDULA.IAP_ID'      ,$arbol_id)                          
                          ->orderBy('JP_CEDULA.PERIODO_ID'  ,'ASC')
                          ->orderBy('JP_CEDULA.IAP_ID'      ,'ASC')
                          ->orderBy('JP_CEDULA.CEDULA_FOLIO','asc') 
                          ->folio($folio)    //Metodos personalizados                                                            
                          ->name($name)      //Metodos personalizados es equvalente a ->where('IAP_DESC', 'LIKE', "%$name%");
                          ->nameiap($nameiap) //Metodos personalizados                                  
                          //->email($email)   //Metodos personalizados
                          //->bio($bio)       //Metodos personalizados
                          ->paginate(30);              
        }                               
        if($regcedula->count() <= 0){
            toastr()->error('No existen registros de cedula de detección de necesidades.','Lo siento!',['positionClass' => 'toast-bottom-right']);
        }            
        return view('sicinar.cedulas.verCedula', compact('nombre','usuario','regiap','reganios','regperiodos','regmeses','regdias','regcedula','totarticulos'));
    }

public function actionVerCedula(){
        $nombre       = session()->get('userlog');
        $pass         = session()->get('passlog');
        if($nombre == NULL AND $pass == NULL){
            return view('sicinar.login.expirada');
        }
        $usuario      = session()->get('usuario');
        $rango        = session()->get('rango');
        $ip           = session()->get('ip');
        $arbol_id     = session()->get('arbol_id');        

        $reganios     = regPeriodosaniosModel::select('PERIODO_ID','PERIODO_DESC')
                        ->wherein('PERIODO_ID',[2020,2021,2022,2023])        
                        ->get();
        $regperiodos  = regPfiscalesModel::select('PERIODO_ID', 'PERIODO_DESC')->get();         
        $regmeses     = regMesesModel::select('MES_ID','MES_DESC')->get();      
        $regdias      = regDiasModel::select('DIA_ID','DIA_DESC')->get();                                                  
        $regcedulaarti= regCedulaarticulosModel::select('PERIODO_ID','CEDULA_FOLIO','CEDULA_PARTIDA','IAP_ID',
                        'CEDULA_FECHA','CEDULA_FECHA2','PERIODO_ID1','MES_ID1','DIA_ID1',
                        'ARTICULO_ID','ARTICULO_CANTIDAD','CEDART_OBS','CEDART_STATUS',
                        'FECREG','IP','LOGIN','FECHA_M','IP_M','LOGIN_M')        
                        ->orderBy('CEDULA_FOLIO'  ,'asc')
                        ->orderBy('CEDULA_PARTIDA','asc')
                        ->get();        
        //********* Validar rol de usuario **********************/
        if(session()->get('rango') !== '0'){    
            $regiap     = regIapModel::select('IAP_ID', 'IAP_DESC','IAP_STATUS')
                          ->get();                                                
            $totarticulos=regCedulaarticulosModel::join('JP_CEDULA','JP_CEDULA.CEDULA_FOLIO', '=', 
                                                                    'JP_CEDULA_ARTICULOS.CEDULA_FOLIO')
                        ->select('JP_CEDULA.PERIODO_ID','JP_CEDULA.CEDULA_FOLIO')
                        ->selectRaw('COUNT(*) AS TOTARTICULOS')
                        ->groupBy('JP_CEDULA.PERIODO_ID','JP_CEDULA.CEDULA_FOLIO')
                        ->get();   
            $regcedula =regCedulaModel::select('PERIODO_ID','CEDULA_FOLIO','IAP_ID','SP_ID','SP_NOMB',
                        'CEDULA_FECHA','CEDULA_FECHA2','PERIODO_ID1','MES_ID1','DIA_ID1','CEDULA_OBS','CEDULA_STATUS',
                        'FECREG','IP','LOGIN','FECHA_M','IP_M','LOGIN_M')
                        ->orderBy('PERIODO_ID','ASC')
                        ->orderBy('IAP_ID'    ,'ASC')
                        ->paginate(30);
        }else{                       
            $regiap     = regIapModel::select('IAP_ID', 'IAP_DESC','IAP_STATUS')
                          ->where('IAP_ID',$arbol_id)
                          ->get();                                             
            $totarticulos=regCedulaarticulosModel::join('JP_CEDULA','JP_CEDULA.CEDULA_FOLIO', '=', 
                                                                    'JP_CEDULA_ARTICULOS.CEDULA_FOLIO')
                        ->select('JP_CEDULA.PERIODO_ID','JP_CEDULA.CEDULA_FOLIO')
                        ->selectRaw('COUNT(*) AS TOTARTICULOS')
                        ->where('JP_CEDULA.LOGIN', $arbol_id)
                        ->groupBy('JP_CEDULA.PERIODO_ID','JP_CEDULA.CEDULA_FOLIO')
                        ->get();            
            $regcedula =regCedulaModel::select('PERIODO_ID','CEDULA_FOLIO','IAP_ID','SP_ID','SP_NOMB',
                        'CEDULA_FECHA','CEDULA_FECHA2','PERIODO_ID1','MES_ID1','DIA_ID1','CEDULA_OBS','CEDULA_STATUS',
                        'FECREG','IP','LOGIN','FECHA_M','IP_M','LOGIN_M')
                        ->where(  'IAP_ID'    ,$arbol_id)            
                        ->orderBy('PERIODO_ID','ASC')
                        ->orderBy('IAP_ID'    ,'ASC')
                        ->paginate(30);         
        }                        
        if($regcedula->count() <= 0){
            toastr()->error('No existe cedula de detección de necesidades.','Lo siento!',['positionClass' => 'toast-bottom-right']);
        }
        return view('sicinar.cedulas.verCedula',compact('nombre','usuario','regiap','reganios','regperiodos','regmeses','regdias','totarticulos','regcedula','regcedulaarti'));
    }

    public function actionNuevaCedula(){
        $nombre       = session()->get('userlog');
        $pass         = session()->get('passlog');
        if($nombre == NULL AND $pass == NULL){
            return view('sicinar.login.expirada');
        }
        $usuario      = session()->get('usuario');
        $rango        = session()->get('rango');
        $ip           = session()->get('ip');
        $arbol_id     = session()->get('arbol_id');

        //$regumedida   = regArticulosModel::select('UMEDIDA_ID','UMEDIDA_DESC')->orderBy('UMEDIDA_ID','asc')
        //                ->get();  
        $reganios     = regPeriodosaniosModel::select('PERIODO_ID','PERIODO_DESC')
                        ->wherein('PERIODO_ID',[2020,2021,2022,2023])  
                        ->orderBy('PERIODO_ID','ASC')      
                        ->get();
        $regperiodos  = regPfiscalesModel::select('PERIODO_ID', 'PERIODO_DESC')->get();         
        $regmeses     = regMesesModel::select('MES_ID','MES_DESC')->get();      
        $regdias      = regDiasModel::select('DIA_ID','DIA_DESC')->get();     
        if(session()->get('rango') !== '0'){                           
            $regiap   = regIapModel::select('IAP_ID', 'IAP_DESC','IAP_STATUS')->orderBy('IAP_DESC','ASC')
                        ->get();                                                        
        }else{
            $regiap   = regIapModel::select('IAP_ID', 'IAP_DESC','IAP_STATUS')->orderBy('IAP_DESC','ASC')
                        ->where('IAP_ID',$arbol_id)
                        ->get();            
        }    
        $regcedula    = regCedulaModel::select('PERIODO_ID','CEDULA_FOLIO','IAP_ID','SP_ID','SP_NOMB',
                        'CEDULA_FECHA','CEDULA_FECHA2','PERIODO_ID1','MES_ID1','DIA_ID1','CEDULA_OBS','CEDULA_STATUS',
                        'FECREG','IP','LOGIN','FECHA_M','IP_M','LOGIN_M')
                        ->get();
        //dd($unidades);
        //return view('sicinar.cedulas.nuevoProgtrab',compact('regumedida','regiap','nombre','usuario','reganios','regperiodos','regmeses','regdias','regcedula'));
        return view('sicinar.cedulas.nuevaCedula',compact('regiap','nombre','usuario','reganios','regperiodos','regmeses','regdias','regcedula'));                         
    }

    public function actionAltaNuevaCedula(Request $request){
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
        $duplicado = regCedulaModel::where(['PERIODO_ID' => $request->periodo_id,'IAP_ID' => $request->iap_id])
                     ->get();
        if($duplicado->count() >= 1)
            return back()->withInput()->withErrors(['IAP_ID' => 'IAP '.$request->iap_id.' Ya existe cedula de detección de necesidades en el mismo periodo y con la IAP referida. Por favor verificar.']);
        else{  
            /************ ALTA  *****************************/ 
            if(!empty($request->periodo_d1) and !empty($request->mes_d1) and !empty($request->dia_d1) ){
                //toastr()->error('muy bien 1.....','¡ok!',['positionClass' => 'toast-bottom-right']);
                $mes1 = regMesesModel::ObtMes($request->mes_id1);
                $dia1 = regDiasModel::ObtDia($request->dia_id1);                
            }   //endif

            $mes1 = regMesesModel::ObtMes($request->mes_id1);
            $dia1 = regDiasModel::ObtDia($request->dia_id1);                

            $folio = regCedulaModel::max('CEDULA_FOLIO');
            $folio = $folio + 1;

            $nuevacedula = new regCedulaModel();
            $nuevacedula->CEDULA_FOLIO  = $folio;
            $nuevacedula->PERIODO_ID    = $request->periodo_id;                            
            $nuevacedula->IAP_ID        = $request->iap_id;
            $nuevacedula->CEDULA_FECHA  = date('Y/m/d', strtotime(trim($dia1[0]->dia_desc.'/'.$mes1[0]->mes_mes.'/'.$request->periodo_id1) ));
            $nuevacedula->CEDULA_FECHA2 = trim($dia1[0]->dia_desc.'/'.$mes1[0]->mes_mes.'/'.$request->periodo_id1);
            $nuevacedula->PERIODO_ID1   = $request->periodo_id1;                
            $nuevacedula->MES_ID1       = $request->mes_id1;                
            $nuevacedula->DIA_ID1       = $request->dia_id1;       

            $nuevacedula->SP_NOMB       = substr(trim(strtoupper($request->sp_nomb)),0,79);
            $nuevacedula->CEDULA_OBS    = substr(trim(strtoupper($request->cedula_obs)),0,499);
        
            $nuevacedula->IP            = $ip;
            $nuevacedula->LOGIN         = $nombre;         // Usuario ;
            $nuevacedula->save();
            if($nuevacedula->save() == true){
                toastr()->success('Cedula dada de alta.','ok!',['positionClass' => 'toast-bottom-right']);
                /************ Bitacora inicia *************************************/ 
                setlocale(LC_TIME, "spanish");        
                $xip          = session()->get('ip');
                $xperiodo_id  = (int)date('Y');
                $xprograma_id = 1;
                $xmes_id      = (int)date('m');
                $xproceso_id  =         3;
                $xfuncion_id  =      3012;
                $xtrx_id      =        27;    //Alta
                $regbitacora = regBitacoraModel::select('PERIODO_ID','PROGRAMA_ID','MES_ID','PROCESO_ID','FUNCION_ID', 
                                                    'TRX_ID','FOLIO','NO_VECES','FECHA_REG','IP','LOGIN', 
                                                    'FECHA_M','IP_M','LOGIN_M')
                               ->where(['PERIODO_ID' => $xperiodo_id,'PROGRAMA_ID' => $xprograma_id,'MES_ID' => $xmes_id,
                                    'PROCESO_ID' => $xproceso_id,'FUNCION_ID' => $xfuncion_id,'TRX_ID' => $xtrx_id, 
                                    'FOLIO' => $folio])
                               ->get();
                if($regbitacora->count() <= 0){              // Alta
                    $nuevoregBitacora = new regBitacoraModel();              
                    $nuevoregBitacora->PERIODO_ID = $xperiodo_id;    // Año de transaccion 
                    $nuevoregBitacora->PROGRAMA_ID= $xprograma_id;   // Proyecto JAPEM 
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
                        toastr()->success('Bitacora dada de alta correctamente.','¡Ok!',['positionClass' => 'toast-bottom-right']);
                    else
                        toastr()->error('Error inesperado al dar de alta la bitacora. Por favor volver a interlo.','Ups!',['positionClass' => 'toast-bottom-right']);
                }else{                   
                    //*********** Obtine el no. de veces *****************************
                    $xno_veces = regBitacoraModel::where(['PERIODO_ID' => $xperiodo_id, 'PROGRAMA_ID' => $xprograma_id, 
                                                      'MES_ID' => $xmes_id, 'PROCESO_ID' => $xproceso_id, 
                                                      'FUNCION_ID' => $xfuncion_id, 'TRX_ID' => $xtrx_id, 
                                                      'FOLIO' => $folio])
                                 ->max('NO_VECES');
                    $xno_veces = $xno_veces+1;                        
                    //*********** Termina de obtener el no de veces *****************************         
                    $regbitacora = regBitacoraModel::select('NO_VECES','IP_M','LOGIN_M','FECHA_M')
                                   ->where(['PERIODO_ID' => $xperiodo_id, 'PROGRAMA_ID' => $xprograma_id, 
                                        'MES_ID' => $xmes_id, 'PROCESO_ID' => $xproceso_id, 
                                        'FUNCION_ID' => $xfuncion_id,'TRX_ID' => $xtrx_id,'FOLIO' => $folio])
                                   ->update([
                                         'NO_VECES'=> $regbitacora->NO_VECES = $xno_veces,
                                         'IP_M'    => $regbitacora->IP       = $ip,
                                         'LOGIN_M' => $regbitacora->LOGIN_M  = $nombre,
                                         'FECHA_M' => $regbitacora->FECHA_M  = date('Y/m/d')  //date('d/m/Y')
                                       ]);
                    toastr()->success('Bitacora actualizada.','¡Ok!',['positionClass' => 'toast-bottom-right']);
                }   /************ Bitacora termina *************************************/ 

            }else{
                toastr()->error('Error al dar de alta el programa de trabajo. Por favor volver a interlo.','Ups!',['positionClass' => 'toast-bottom-right']);
                //return back();
                //return redirect()->route('nuevoProceso');
            }   //**************** Termina la alta ***************/
        }   // ******************* Termina el duplicado **********/

        return redirect()->route('verCedula');
    }

    
        
    public function actionEditarCedula($id){
        $nombre        = session()->get('userlog');
        $pass          = session()->get('passlog');
        if($nombre == NULL AND $pass == NULL){
            return view('sicinar.login.expirada');
        }
        $usuario       = session()->get('usuario');
        $rango         = session()->get('rango');
        $arbol_id      = session()->get('arbol_id');        

        //$regumedida   = regArticulosModel::select('UMEDIDA_ID','UMEDIDA_DESC')->orderBy('UMEDIDA_ID','asc')
        //                ->get();   
        $reganios     = regPeriodosaniosModel::select('PERIODO_ID','PERIODO_DESC')
                        ->wherein('PERIODO_ID',[2020,2021,2022,2023])  
                        ->orderBy('PERIODO_ID','ASC')                                    
                        ->get();
        $regperiodos  = regPfiscalesModel::select('PERIODO_ID', 'PERIODO_DESC')->get();         
        $regmeses     = regMesesModel::select('MES_ID','MES_DESC')->get();      
        $regdias      = regDiasModel::select('DIA_ID','DIA_DESC')->get();         
        //********* Validar rol de usuario **********************/
        if(session()->get('rango') !== '0'){                          
            $regiap   = regIapModel::select('IAP_ID', 'IAP_DESC','IAP_STATUS')
                        ->get();                                                        
        }else{
            $regiap   = regIapModel::select('IAP_ID', 'IAP_DESC','IAP_STATUS')
                        ->where('IAP_ID',$arbol_id)
                        ->get();            
        }                    
        $regcedula   = regCedulaModel::select('PERIODO_ID','CEDULA_FOLIO','IAP_ID','SP_ID','SP_NOMB',
                       'CEDULA_FECHA','CEDULA_FECHA2','PERIODO_ID1','MES_ID1','DIA_ID1','CEDULA_OBS','CEDULA_STATUS',
                       'FECREG','IP','LOGIN','FECHA_M','IP_M','LOGIN_M')
                       ->where('CEDULA_FOLIO',$id)
                       ->orderBy('PERIODO_ID','ASC')
                       ->orderBy('IAP_ID'    ,'ASC')
                       ->first();
        if($regcedula->count() <= 0){
            toastr()->error('No existen registro de Cedula de detección de necesidades.','Lo siento!',['positionClass' => 'toast-bottom-right']);
            //return redirect()->route('nuevoprogtrab');
        }
        //return view('sicinar.cedulas.editarProgtrab',compact('nombre','usuario','regiap','regumedida','reganios','regperiodos','regmeses','regdias','regcedula'));
        return view('sicinar.cedulas.editarCedula',compact('nombre','usuario','regiap','reganios','regperiodos','regmeses','regdias','regcedula'));

    }

    public function actionActualizarCedula(cedulaRequest $request, $id){
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
        $regcedula = regCedulaModel::where('CEDULA_FOLIO',$id);
        if($regcedula->count() <= 0)
            toastr()->error('No existe Cédula de detección de necesidades.','¡Por favor volver a intentar!',['positionClass' => 'toast-bottom-right']);
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
            $regcedula = regCedulaModel::where('CEDULA_FOLIO',$id)        
                         ->update([                
                    'CEDULA_FECHA'  => date('Y/m/d', strtotime(trim($dia1[0]->dia_desc.'/'.$mes1[0]->mes_mes.'/'.$request->periodo_id1) )),
                    'CEDULA_FECHA2' => trim($dia1[0]->dia_desc.'/'.$mes1[0]->mes_mes.'/'.$request->periodo_id1), 
                    'PERIODO_ID1'   => $request->periodo_id1,
                    'MES_ID1'       => $request->mes_id1,
                    'DIA_ID1'       => $request->dia_id1,                

                    'SP_NOMB'       => substr(trim(strtoupper($request->sp_nomb)),0,79),
                    'CEDULA_OBS'    => substr(trim(strtoupper($request->cedula_obs)),0,499),        
                    'CEDULA_STATUS' => $request->cedula_status,

                    'IP_M'          => $ip,
                    'LOGIN_M'       => $nombre,
                    'FECHA_M'       => date('Y/m/d')    //date('d/m/Y')                                
                                   ]);
            toastr()->success('Cédula de detección de necesidades actualizada.','¡Ok!',['positionClass' => 'toast-bottom-right']);

            /************ Bitacora inicia *************************************/ 
            setlocale(LC_TIME, "spanish");        
            $xip          = session()->get('ip');
            $xperiodo_id  = (int)date('Y');
            $xprograma_id = 1;
            $xmes_id      = (int)date('m');
            $xproceso_id  =         3;
            $xfuncion_id  =      3012;
            $xtrx_id      =        28;    //Actualizar 
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
                    toastr()->success('Bitacora dada de alta correctamente.','¡Ok!',['positionClass' => 'toast-bottom-right']);
                else
                    toastr()->error('Error inesperado al dar de alta la bitacora. Por favor volver a interlo.','Ups!',['positionClass' => 'toast-bottom-right']);
            }else{                   
                //*********** Obtine el no. de veces *****************************
                $xno_veces = regBitacoraModel::where(['PERIODO_ID' => $xperiodo_id, 'PROGRAMA_ID' => $xprograma_id, 
                             'MES_ID' => $xmes_id, 'PROCESO_ID' => $xproceso_id, 'FUNCION_ID' => $xfuncion_id, 
                             'TRX_ID' => $xtrx_id, 'FOLIO' => $id])
                             ->max('NO_VECES');
                $xno_veces = $xno_veces+1;                        
                //*********** Termina de obtener el no de veces *****************************         
                $regbitacora = regBitacoraModel::select('NO_VECES','IP_M','LOGIN_M','FECHA_M')
                               ->where(['PERIODO_ID' => $xperiodo_id, 'PROGRAMA_ID' => $xprograma_id, 
                                        'MES_ID' => $xmes_id, 'PROCESO_ID' => $xproceso_id, 'FUNCION_ID' => $xfuncion_id, 
                                        'TRX_ID' => $xtrx_id, 'FOLIO' => $id])
                               ->update([
                                         'NO_VECES'=> $regbitacora->NO_VECES = $xno_veces,
                                         'IP_M'    => $regbitacora->IP       = $ip,
                                         'LOGIN_M' => $regbitacora->LOGIN_M  = $nombre,
                                         'FECHA_M' => $regbitacora->FECHA_M  = date('Y/m/d')  //date('d/m/Y')
                                         ]);
                toastr()->success('Bitacora actualizada.','¡Ok!',['positionClass' => 'toast-bottom-right']);
            }   /************ Bitacora termina *************************************/                     
        }       /************ Actualizar *******************************************/

        return redirect()->route('verCedula');

    }


    public function actionBorrarCedula($id){
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
        $regcedula  = regCedulaModel::where('CEDULA_FOLIO',$id);
        //              ->find('UMEDIDA_ID',$id);
        if($regcedula->count() <= 0)
            toastr()->error('No existe cedula.','¡Por favor volver a intentar!',['positionClass' => 'toast-bottom-right']);
        else{        
            $regcedula->delete();
            toastr()->success('Cédula eliminada.','¡Ok!',['positionClass' => 'toast-bottom-right']);

            //echo 'Ya entre a borrar registro..........';
            /************ Bitacora inicia *************************************/ 
            setlocale(LC_TIME, "spanish");        
            $xip          = session()->get('ip');
            $xperiodo_id  = (int)date('Y');
            $xprograma_id = 1;
            $xmes_id      = (int)date('m');
            $xproceso_id  =         3;
            $xfuncion_id  =      3012;
            $xtrx_id      =        29;     // Baja 
            $regbitacora = regBitacoraModel::select('PERIODO_ID','PROGRAMA_ID','MES_ID', 'PROCESO_ID','FUNCION_ID', 
                           'TRX_ID', 'FOLIO', 'NO_VECES', 'FECHA_REG', 'IP', 'LOGIN', 'FECHA_M', 'IP_M', 'LOGIN_M')
                           ->where(['PERIODO_ID' => $xperiodo_id, 'PROGRAMA_ID' => $xprograma_id, 'MES_ID' => $xmes_id, 
                                    'PROCESO_ID' => $xproceso_id, 'FUNCION_ID' => $xfuncion_id, 
                                    'TRX_ID' => $xtrx_id, 'FOLIO' => $id])
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
                                                      'FUNCION_ID' => $xfuncion_id,'TRX_ID' => $xtrx_id,'FOLIO' => $id])
                             ->max('NO_VECES');
                $xno_veces = $xno_veces+1;                        
                //*********** Termina de obtener el no de veces *****************************         
                $regbitacora = regBitacoraModel::select('NO_VECES','IP_M','LOGIN_M','FECHA_M')
                               ->where(['PERIODO_ID' => $xperiodo_id, 'PROGRAMA_ID' => $xprograma_id, 
                                        'MES_ID' => $xmes_id, 'PROCESO_ID' => $xproceso_id, 'FUNCION_ID' => $xfuncion_id, 
                                        'TRX_ID' => $xtrx_id, 'FOLIO' => $id])
                               ->update([
                                         'NO_VECES' => $regbitacora->NO_VECES = $xno_veces,
                                         'IP_M'     => $regbitacora->IP       = $ip,
                                         'LOGIN_M'  => $regbitacora->LOGIN_M  = $nombre,
                                         'FECHA_M'  => $regbitacora->FECHA_M  = date('Y/m/d')  //date('d/m/Y')
                                        ]);
                toastr()->success('Bitacora actualizada.','¡Ok!',['positionClass' => 'toast-bottom-right']);
            }   /************ Bitacora termina *************************************/                 
        }       /************* Termina de eliminar *********************************/
        
        return redirect()->route('verCedula');
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
        $xfuncion_id  =      3012;
        $xtrx_id      =        30;            // Exportar a formato Excel
        $id           =         0;
        $regbitacora  = regBitacoraModel::select('PERIODO_ID', 'PROGRAMA_ID', 'MES_ID', 'PROCESO_ID', 'FUNCION_ID', 
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
               toastr()->success('Bitacora dada de alta correctamente.','¡Ok!',['positionClass' => 'toast-bottom-right']);
            else
               toastr()->error('Error inesperado al dar de alta la bitacora. Por favor volver a interlo.','Ups!',['positionClass' => 'toast-bottom-right']);
        }else{                   
            //*********** Obtine el no. de veces *****************************
            $xno_veces = regBitacoraModel::where(['PERIODO_ID' => $xperiodo_id, 'PROGRAMA_ID' => $xprograma_id, 
                         'MES_ID' => $xmes_id, 'PROCESO_ID' => $xproceso_id, 'FUNCION_ID' => $xfuncion_id, 
                         'TRX_ID' => $xtrx_id, 'FOLIO' => $id])
                         ->max('NO_VECES');
            $xno_veces = $xno_veces+1;                        
            //*********** Termina de obtener el no de veces *****************************                
            $regbitacora = regBitacoraModel::select('NO_VECES','IP_M','LOGIN_M','FECHA_M')
                           ->where(['PERIODO_ID' => $xperiodo_id, 'PROGRAMA_ID' => $xprograma_id, 'MES_ID' => $xmes_id, 
                                    'PROCESO_ID' => $xproceso_id, 'FUNCION_ID' => $xfuncion_id, 'TRX_ID' => $xtrx_id, 
                                    'FOLIO' => $id])
                           ->update([
                                     'NO_VECES' => $regbitacora->NO_VECES = $xno_veces,
                                     'IP_M'     => $regbitacora->IP       = $ip,
                                     'LOGIN_M'  => $regbitacora->LOGIN_M  = $nombre,
                                     'FECHA_M'  => $regbitacora->FECHA_M  = date('Y/m/d')  //date('d/m/Y')
                                    ]);
            toastr()->success('Bitacora actualizada.','¡Ok!',['positionClass' => 'toast-bottom-right']);
        }   /********************** Bitacora termina *************************************/  

        return Excel::download(new ExportCedulaExcel, 'Cedula_'.date('d-m-Y').'.xlsx');
    }

    // exportar a formato PDF
    public function actionExportCedulaPdf($id,$id2){
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
        $xfuncion_id  =      3012;
        $xtrx_id      =        31;       //Exportar a formato PDF
        $regbitacora = regBitacoraModel::select('PERIODO_ID', 'PROGRAMA_ID', 'MES_ID', 'PROCESO_ID', 'FUNCION_ID', 
                       'TRX_ID', 'FOLIO', 'NO_VECES', 'FECHA_REG', 'IP', 'LOGIN', 'FECHA_M', 'IP_M', 'LOGIN_M')
                       ->where(['PERIODO_ID' => $xperiodo_id, 'PROGRAMA_ID' => $xprograma_id, 'MES_ID' => $xmes_id, 
                                'PROCESO_ID' => $xproceso_id, 'FUNCION_ID' => $xfuncion_id, 'TRX_ID' => $xtrx_id, 
                                'FOLIO' => $id2])
                       ->get();
        if($regbitacora->count() <= 0){              // Alta
            $nuevoregBitacora = new regBitacoraModel();              
            $nuevoregBitacora->PERIODO_ID = $xperiodo_id;    // Año de transaccion 
            $nuevoregBitacora->PROGRAMA_ID= $xprograma_id;   // Proyecto JAPEM 
            $nuevoregBitacora->MES_ID     = $xmes_id;        // Mes de transaccion
            $nuevoregBitacora->PROCESO_ID = $xproceso_id;    // Proceso de apoyo
            $nuevoregBitacora->FUNCION_ID = $xfuncion_id;    // Funcion del modelado de procesos 
            $nuevoregBitacora->TRX_ID     = $xtrx_id;        // Actividad del modelado de procesos
            $nuevoregBitacora->FOLIO      = $id2;             // Folio    
            $nuevoregBitacora->NO_VECES   = 1;               // Numero de veces            
            $nuevoregBitacora->IP         = $ip;             // IP
            $nuevoregBitacora->LOGIN      = $nombre;         // Usuario 

            $nuevoregBitacora->save();
            if($nuevoregBitacora->save() == true)
               toastr()->success('Bitacora dada de alta correctamente.','¡Ok!',['positionClass' => 'toast-bottom-right']);
            else
               toastr()->error('Erroral dar de alta la bitacora. Por favor volver a interlo.','Ups!',['positionClass' => 'toast-bottom-right']);
        }else{                   
            //*********** Obtine el no. de veces *****************************
            $xno_veces = regBitacoraModel::where(['PERIODO_ID' => $xperiodo_id, 'PROGRAMA_ID' => $xprograma_id, 
                         'MES_ID' => $xmes_id, 'PROCESO_ID' => $xproceso_id, 'FUNCION_ID' => $xfuncion_id, 
                         'TRX_ID' => $xtrx_id, 'FOLIO' => $id2])
                        ->max('NO_VECES');
            $xno_veces = $xno_veces+1;                        
            //*********** Termina de obtener el no de veces *****************************         
            $regbitacora = regBitacoraModel::select('NO_VECES','IP_M','LOGIN_M','FECHA_M')
                           ->where(['PERIODO_ID' => $xperiodo_id, 'PROGRAMA_ID' => $xprograma_id, 'MES_ID' => $xmes_id, 
                                    'PROCESO_ID' => $xproceso_id, 'FUNCION_ID' => $xfuncion_id, 'TRX_ID' => $xtrx_id, 
                                    'FOLIO' => $id2])
                           ->update([
                                     'NO_VECES' => $regbitacora->NO_VECES = $xno_veces,
                                     'IP_M' => $regbitacora->IP           = $ip,
                                     'LOGIN_M' => $regbitacora->LOGIN_M   = $nombre,
                                     'FECHA_M' => $regbitacora->FECHA_M   = date('Y/m/d')  //date('d/m/Y')
                                    ]);
            toastr()->success('Bitacora actualizada.','¡Ok!',['positionClass' => 'toast-bottom-right']);
        }   /************ Bitacora termina *************************************/ 

        $regarticulos = regArticulosModel::join('JP_CAT_TIPO_ARTICULO','JP_CAT_TIPO_ARTICULO.TIPO_ID','=','JP_CAT_ARTICULOS.TIPO_ID')
                                       ->select('JP_CAT_ARTICULOS.ARTICULO_ID', 'JP_CAT_ARTICULOS.ARTICULO_DESC',
                                                'JP_CAT_ARTICULOS.TIPO_ID','JP_CAT_TIPO_ARTICULO.TIPO_DESC')
                                       ->get(); 
        $regiap       = regIapModel::select('IAP_ID', 'IAP_DESC')->get();   
        //********* Validar rol de usuario **********************/
        if(session()->get('rango') !== '0'){                 
            $regcedula= regCedulaModel::select('PERIODO_ID','CEDULA_FOLIO','IAP_ID','SP_ID','SP_NOMB',
                        'CEDULA_FECHA','CEDULA_FECHA2','PERIODO_ID1','MES_ID1','DIA_ID1','CEDULA_OBS','CEDULA_STATUS',
                        'FECREG','IP','LOGIN','FECHA_M','IP_M','LOGIN_M')
                        ->where(['PERIODO_ID' => $id,'CEDULA_FOLIO' => $id2])                                 
                        ->get();
        }else{
            $regcedula= regCedulaModel::select('PERIODO_ID','CEDULA_FOLIO','IAP_ID','SP_ID','SP_NOMB',
                        'CEDULA_FECHA','CEDULA_FECHA2','PERIODO_ID1','MES_ID1','DIA_ID1','CEDULA_OBS','CEDULA_STATUS',
                        'FECREG','IP','LOGIN','FECHA_M','IP_M','LOGIN_M')
                        ->where(['PERIODO_ID' => $id,'CEDULA_FOLIO' => $id2, 'IAP_ID' => $arbol_id])
                        ->orderBy('PERIODO_ID'  ,'ASC')
                        ->orderBy('CEDULA_FOLIO','ASC')
                        ->get();            
        }                           
        $regcedulaarti= regCedulaarticulosModel::join('JP_CEDULA','JP_CEDULA.CEDULA_FOLIO','=',
                                                        'JP_CEDULA_ARTICULOS.CEDULA_FOLIO')
                        ->select( 'JP_CEDULA.PERIODO_ID',
                                  'JP_CEDULA.CEDULA_FOLIO',
                                  'JP_CEDULA.IAP_ID',
                                  'JP_CEDULA.SP_NOMB',
                                  'JP_CEDULA.CEDULA_FECHA2',
                                  'JP_CEDULA.CEDULA_OBS',
                                  'JP_CEDULA.CEDULA_STATUS',
                                  'JP_CEDULA_ARTICULOS.CEDULA_PARTIDA','JP_CEDULA_ARTICULOS.IAP_ID',
                                  'JP_CEDULA_ARTICULOS.CEDULA_FECHA'  ,'JP_CEDULA_ARTICULOS.CEDULA_FECHA2',
                                  'JP_CEDULA_ARTICULOS.PERIODO_ID1'   ,'JP_CEDULA_ARTICULOS.MES_ID1',
                                  'JP_CEDULA_ARTICULOS.DIA_ID1'       ,
                                  'JP_CEDULA_ARTICULOS.ARTICULO_ID'   ,
                                  'JP_CEDULA_ARTICULOS.ARTICULO_CANTIDAD',
                                  'JP_CEDULA_ARTICULOS.CEDART_OBS'    ,
                                  'JP_CEDULA_ARTICULOS.CEDART_STATUS',
                                  'JP_CEDULA_ARTICULOS.FECREG','JP_CEDULA_ARTICULOS.IP',
                                  'JP_CEDULA_ARTICULOS.LOGIN','JP_CEDULA_ARTICULOS.FECHA_M',
                                  'JP_CEDULA_ARTICULOS.IP_M','JP_CEDULA_ARTICULOS.LOGIN_M')         
                        ->where( ['JP_CEDULA.PERIODO_ID' => $id, 'JP_CEDULA.CEDULA_FOLIO' => $id2])
                        ->orderBy('JP_CEDULA.PERIODO_ID'    ,'asc')          
                        ->orderBy('JP_CEDULA.CEDULA_FOLIO'  ,'asc')
                        ->orderBy('JP_CEDULA_ARTICULOS.CEDULA_PARTIDA','asc')            
                        ->get();    
        //dd('Llave:',$id,' llave2:',$id2);       
        if($regcedulaarti->count() <= 0){
            toastr()->error('No existen articulos en la cedula de detección de necesidades.','Uppss!',['positionClass' => 'toast-bottom-right']);
            //return redirect()->route('verProgtrab');
        }else{
            $pdf = PDF::loadView('sicinar.pdf.CedulaPdf',compact('nombre','usuario','regarticulos','regiap','regcedula','regcedulaarti'));
            //******** Horizontal ***************
            //$pdf->setPaper('A4', 'landscape');      
            //$pdf->set('defaultFont', 'Courier');          
            //$pdf->setPaper('A4','portrait');
            // Output the generated PDF to Browser
            //******** vertical *************** 
            //El tamaño de hoja se especifica en page_size puede ser letter, legal, A4, etc.         
            $pdf->setPaper('letter','portrait');   
            // Output the generated PDF to Browser
            return $pdf->stream('CeduladeDetecciondeNecesidades-'.$id2);
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

        $regtotxedo=regIapModel::join('JP_CAT_ENTIDADES_FED',[['JP_CAT_ENTIDADES_FED.ENTIDADFEDERATIVA_ID','=','JP_IAPS.ENTIDADFEDERATIVA_ID'],['JP_IAPS.IAP_ID','<>',0]])
                         ->selectRaw('COUNT(*) AS TOTALXEDO')
                               ->get();

        $regiap=regIapModel::join('JP_CAT_ENTIDADES_FED',[['JP_CAT_ENTIDADES_FED.ENTIDADFEDERATIVA_ID','=','JP_IAPS.ENTIDADFEDERATIVA_ID'],['JP_IAPS.IAP_ID','<>',0]])
                      ->selectRaw('JP_IAPS.ENTIDADFEDERATIVA_ID, JP_CAT_ENTIDADES_FED.ENTIDADFEDERATIVA_DESC AS ESTADO, COUNT(*) AS TOTAL')
                        ->groupBy('JP_IAPS.ENTIDADFEDERATIVA_ID', 'JP_CAT_ENTIDADES_FED.ENTIDADFEDERATIVA_DESC')
                        ->orderBy('JP_IAPS.ENTIDADFEDERATIVA_ID','asc')
                        ->get();
        //$procesos = procesosModel::join('SCI_TIPO_PROCESO','SCI_PROCESOS.CVE_TIPO_PROC','=','SCI_TIPO_PROCESO.CVE_TIPO_PROC')
        //    ->selectRaw('SCI_TIPO_PROCESO.DESC_TIPO_PROC AS TIPO, COUNT(SCI_PROCESOS.CVE_TIPO_PROC) AS TOTAL')
        //    ->groupBy('SCI_TIPO_PROCESO.DESC_TIPO_PROC')
        //    ->get();
        //dd($procesos);
        return view('sicinar.numeralia.iapxedo',compact('regiap','regtotxedo','nombre','usuario','rango'));
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

        $regtotxmpio=regIapModel::join('JP_CAT_MUNICIPIOS_SEDESEM',[['JP_CAT_MUNICIPIOS_SEDESEM.ENTIDADFEDERATIVAID','=',15],
                                                            ['JP_CAT_MUNICIPIOS_SEDESEM.MUNICIPIOID','=','JP_IAPS.MUNICIPIO_ID'],['JP_IAPS.IAP_ID','<>',0]])
                         ->selectRaw('COUNT(*) AS TOTALXMPIO')
                               ->get();
        $regiap=regIapModel::join('JP_CAT_MUNICIPIOS_SEDESEM',[['JP_CAT_MUNICIPIOS_SEDESEM.ENTIDADFEDERATIVAID','=',15],
                                                            ['JP_CAT_MUNICIPIOS_SEDESEM.MUNICIPIOID','=','JP_IAPS.MUNICIPIO_ID'],['JP_IAPS.IAP_ID','<>',0]])
                      ->selectRaw('JP_IAPS.MUNICIPIO_ID, JP_CAT_MUNICIPIOS_SEDESEM.MUNICIPIONOMBRE AS MUNICIPIO,COUNT(*) AS TOTAL')
                        ->groupBy('JP_IAPS.MUNICIPIO_ID', 'JP_CAT_MUNICIPIOS_SEDESEM.MUNICIPIONOMBRE')
                        ->orderBy('JP_IAPS.MUNICIPIO_ID','asc')
                        ->get();
        //dd($procesos);
        return view('sicinar.numeralia.iapxmpio',compact('regiap','regtotxmpio','nombre','usuario','rango'));
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

        $regtotxrubro=regIapModel::join('JP_CAT_RUBROS','JP_CAT_RUBROS.UMEDIDA_ID','=','JP_IAPS.UMEDIDA_ID')
                      ->selectRaw('COUNT(*) AS TOTALXRUBRO')
                            ->get();
        $regiap=regIapModel::join('JP_CAT_RUBROS','JP_CAT_RUBROS.UMEDIDA_ID','=','JP_IAPS.UMEDIDA_ID')
                      ->selectRaw('JP_IAPS.UMEDIDA_ID,  JP_CAT_RUBROS.RUBRO_DESC AS RUBRO, COUNT(*) AS TOTAL')
                        ->groupBy('JP_IAPS.UMEDIDA_ID','JP_CAT_RUBROS.RUBRO_DESC')
                        ->orderBy('JP_IAPS.UMEDIDA_ID','asc')
                        ->get();
        //$procesos = procesosModel::join('SCI_TIPO_PROCESO','SCI_PROCESOS.CVE_TIPO_PROC','=','SCI_TIPO_PROCESO.CVE_TIPO_PROC')
        //    ->selectRaw('SCI_TIPO_PROCESO.DESC_TIPO_PROC AS TIPO, COUNT(SCI_PROCESOS.CVE_TIPO_PROC) AS TOTAL')
        //    ->groupBy('SCI_TIPO_PROCESO.DESC_TIPO_PROC')
        //    ->get();
        //dd($procesos);
        return view('sicinar.numeralia.iapxrubro',compact('regiap','regtotxrubro','nombre','usuario','rango'));
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

        $regtotxrubro=regIapModel::join('JP_CAT_RUBROS','JP_CAT_RUBROS.UMEDIDA_ID','=','JP_IAPS.UMEDIDA_ID')
                      ->selectRaw('COUNT(*) AS TOTALXRUBRO')
                            ->get();
        $regiap=regIapModel::join('JP_CAT_RUBROS','JP_CAT_RUBROS.UMEDIDA_ID','=','JP_IAPS.UMEDIDA_ID')
                      ->selectRaw('JP_IAPS.UMEDIDA_ID,  JP_CAT_RUBROS.RUBRO_DESC AS RUBRO, COUNT(*) AS TOTAL')
                        ->groupBy('JP_IAPS.UMEDIDA_ID','JP_CAT_RUBROS.RUBRO_DESC')
                        ->orderBy('JP_IAPS.UMEDIDA_ID','asc')
                        ->get();
        //$procesos = procesosModel::join('SCI_TIPO_PROCESO','SCI_PROCESOS.CVE_TIPO_PROC','=','SCI_TIPO_PROCESO.CVE_TIPO_PROC')
        //    ->selectRaw('SCI_TIPO_PROCESO.DESC_TIPO_PROC AS TIPO, COUNT(SCI_PROCESOS.CVE_TIPO_PROC) AS TOTAL')
        //    ->groupBy('SCI_TIPO_PROCESO.DESC_TIPO_PROC')
        //    ->get();
        //dd($procesos);
        return view('sicinar.numeralia.graficadeprueba',compact('regiap','regtotxrubro','nombre','usuario','rango'));
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

        $regtotxrubro=regIapModel::join('JP_CAT_RUBROS','JP_CAT_RUBROS.UMEDIDA_ID','=','JP_IAPS.UMEDIDA_ID')
                      ->selectRaw('COUNT(*) AS TOTALXRUBRO')
                            ->get();

        $regiap=regIapModel::join('JP_CAT_RUBROS','JP_CAT_RUBROS.UMEDIDA_ID','=','JP_IAPS.UMEDIDA_ID')
                      ->selectRaw('JP_IAPS.UMEDIDA_ID,  JP_CAT_RUBROS.RUBRO_DESC AS RUBRO, COUNT(*) AS TOTAL')
                        ->groupBy('JP_IAPS.UMEDIDA_ID','JP_CAT_RUBROS.RUBRO_DESC')
                        ->orderBy('JP_IAPS.UMEDIDA_ID','asc')
                        ->get();
        //$procesos = procesosModel::join('SCI_TIPO_PROCESO','SCI_PROCESOS.CVE_TIPO_PROC','=','SCI_TIPO_PROCESO.CVE_TIPO_PROC')
        //    ->selectRaw('SCI_TIPO_PROCESO.DESC_TIPO_PROC AS TIPO, COUNT(SCI_PROCESOS.CVE_TIPO_PROC) AS TOTAL')
        //    ->groupBy('SCI_TIPO_PROCESO.DESC_TIPO_PROC')
        //    ->get();
        //dd($procesos);
        return view('sicinar.numeralia.mapasdeprueba',compact('regiap','regtotxrubro','nombre','usuario','rango'));
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

        $regtotxrubro=regIapModel::join('JP_CAT_RUBROS','JP_CAT_RUBROS.UMEDIDA_ID','=','JP_IAPS.UMEDIDA_ID')
                      ->selectRaw('COUNT(*) AS TOTALXRUBRO')
                            ->get();

        $regiap=regIapModel::join('JP_CAT_RUBROS','JP_CAT_RUBROS.UMEDIDA_ID','=','JP_IAPS.UMEDIDA_ID')
                      ->selectRaw('JP_IAPS.UMEDIDA_ID,  JP_CAT_RUBROS.RUBRO_DESC AS RUBRO, COUNT(*) AS TOTAL')
                        ->groupBy('JP_IAPS.UMEDIDA_ID','JP_CAT_RUBROS.RUBRO_DESC')
                        ->orderBy('JP_IAPS.UMEDIDA_ID','asc')
                        ->get();
        //$procesos = procesosModel::join('SCI_TIPO_PROCESO','SCI_PROCESOS.CVE_TIPO_PROC','=','SCI_TIPO_PROCESO.CVE_TIPO_PROC')
        //    ->selectRaw('SCI_TIPO_PROCESO.DESC_TIPO_PROC AS TIPO, COUNT(SCI_PROCESOS.CVE_TIPO_PROC) AS TOTAL')
        //    ->groupBy('SCI_TIPO_PROCESO.DESC_TIPO_PROC')
        //    ->get();
        //dd($procesos);
        return view('sicinar.numeralia.mapasdeprueba2',compact('regiap','regtotxrubro','nombre','usuario','rango'));
    }


    //*****************************************************************************//
    //********************* Detalle de la cedula - articulos **********************//
    //*****************************************************************************//
    public function actionVerCedulaarti($id,$id2){
        $nombre       = session()->get('userlog');
        $pass         = session()->get('passlog');
        if($nombre == NULL AND $pass == NULL){
            return view('sicinar.login.expirada');
        }
        $usuario      = session()->get('usuario');
        $rango        = session()->get('rango');
        $ip           = session()->get('ip');
        $arbol_id     = session()->get('arbol_id');        

        $regarticulos = regArticulosModel::join('JP_CAT_TIPO_ARTICULO','JP_CAT_TIPO_ARTICULO.TIPO_ID','=','JP_CAT_ARTICULOS.TIPO_ID')
                                       ->select('JP_CAT_ARTICULOS.ARTICULO_ID', 'JP_CAT_ARTICULOS.ARTICULO_DESC',
                                                'JP_CAT_ARTICULOS.TIPO_ID','JP_CAT_TIPO_ARTICULO.TIPO_DESC')
                                       ->get(); 
        $regperiodos  = regPfiscalesModel::select('PERIODO_ID', 'PERIODO_DESC')->get();         
        $reganios     = regPeriodosaniosModel::select('PERIODO_ID','PERIODO_DESC')
                        ->wherein('PERIODO_ID',[2020,2021,2022,2023])        
                        ->get();        
        $regmeses     = regMesesModel::select('MES_ID','MES_DESC')->get();      
        $regdias      = regDiasModel::select('DIA_ID','DIA_DESC')->get();                                
        $regiap       = regIapModel::select('IAP_ID', 'IAP_DESC')->orderBy('IAP_DESC','asc')->get();                     
        //************** Validar rol de usuario **********************/
        if(session()->get('rango') !== '0'){           
            $regcedula= regCedulaModel::select('PERIODO_ID','CEDULA_FOLIO','IAP_ID','SP_ID','SP_NOMB',
                        'CEDULA_FECHA','CEDULA_FECHA2','PERIODO_ID1','MES_ID1','DIA_ID1','CEDULA_OBS','CEDULA_STATUS',
                        'FECREG','IP','LOGIN','FECHA_M','IP_M','LOGIN_M')
                        ->where(['PERIODO_ID' => $id, 'CEDULA_FOLIO' => $id2])
                        ->orderBy('PERIODO_ID','ASC')
                        ->orderBy('IAP_ID'    ,'ASC')
                        ->get();
        }else{                         
            $regcedula= regCedulaModel::select('PERIODO_ID','CEDULA_FOLIO','IAP_ID','SP_ID','SP_NOMB',
                        'CEDULA_FECHA','CEDULA_FECHA2','PERIODO_ID1','MES_ID1','DIA_ID1','CEDULA_OBS','CEDULA_STATUS',
                        'FECREG','IP','LOGIN','FECHA_M','IP_M','LOGIN_M')
                        ->where(['PERIODO_ID' => $id, 'CEDULA_FOLIO' => $id2, 'IAP_ID' => $arbol_id])
                        //->where('FOLIO',$id)            
                        //->where('IAP_ID',$arbol_id)            
                        ->orderBy('PERIODO_ID','ASC')
                        ->orderBy('IAP_ID'    ,'ASC')
                        ->get();
        }                        
        if($regcedula->count() <= 0){
            toastr()->error('No existe cedula de detección de necesidades.','Lo siento!',['positionClass' => 'toast-bottom-right']);
            //return redirect()->route('nuevoprogtrab');
        }
        $regcedulaarti= regCedulaarticulosModel::select('PERIODO_ID','CEDULA_FOLIO','CEDULA_PARTIDA','IAP_ID',
                        'CEDULA_FECHA','CEDULA_FECHA2','PERIODO_ID1','MES_ID1','DIA_ID1',
                        'ARTICULO_ID','ARTICULO_CANTIDAD','CEDART_OBS','CEDART_STATUS',
                        'FECREG','IP','LOGIN','FECHA_M','IP_M','LOGIN_M')         
                        ->where( ['PERIODO_ID' => $id, 'CEDULA_FOLIO' => $id2])
                        ->orderBy('PERIODO_ID'    ,'asc')          
                        ->orderBy('CEDULA_FOLIO'  ,'asc')
                        ->orderBy('CEDULA_PARTIDA','asc')
                        ->paginate(100);           
        if($regcedulaarti->count() <= 0){
            toastr()->error('No existen artículos en la cédula de detección de necesidades.','Lo siento!',['positionClass' => 'toast-bottom-right']);
            //return redirect()->route('nuevoprogtrab');
        }                        

        return view('sicinar.cedulas.verCedulaarti',compact('nombre','usuario','regiap','regarticulos','reganios','regperiodos','regmeses','regdias','regcedula','regcedulaarti'));
    }


    public function actionNuevaCedulaarti($id,$id2){
        $nombre       = session()->get('userlog');
        $pass         = session()->get('passlog');
        if($nombre == NULL AND $pass == NULL){
            return view('sicinar.login.expirada');
        }
        $usuario      = session()->get('usuario');
        $rango        = session()->get('rango');
        $ip           = session()->get('ip');
        $arbol_id     = session()->get('arbol_id');

        $regtipos     = regTiposModel::select('TIPO_ID', 'TIPO_DESC')->get();  
        $regarticulos = regArticulosModel::select('ARTICULO_ID', 'ARTICULO_DESC','TIPO_ID')->orderBy('ARTICULO_ID','asc')
                        ->get(); 
        //$regperiodos  = regPfiscalesModel::select('PERIODO_ID', 'PERIODO_DESC')->get();         
        //$reganios     = regPeriodosaniosModel::select('PERIODO_ID','PERIODO_DESC')
        //                ->wherein('PERIODO_ID',[2020,2021,2022,2023])        
        //                ->get();        
        //$regmeses     = regMesesModel::select('MES_ID','MES_DESC')->get();      
        //$regdias      = regDiasModel::select('DIA_ID','DIA_DESC')->get();     
        if(session()->get('rango') !== '0'){                           
            $regiap   = regIapModel::select('IAP_ID', 'IAP_DESC','IAP_STATUS')->orderBy('IAP_DESC','ASC')
                        ->get();                                                        
        }else{
            $regiap   = regIapModel::select('IAP_ID', 'IAP_DESC','IAP_STATUS')->orderBy('IAP_DESC','ASC')
                        ->where('IAP_ID',$arbol_id)
                        ->get();            
        }    
        $regcedula    = regCedulaModel::select('PERIODO_ID','CEDULA_FOLIO','IAP_ID','SP_ID','SP_NOMB',
                        'CEDULA_FECHA','CEDULA_FECHA2','PERIODO_ID1','MES_ID1','DIA_ID1','CEDULA_OBS','CEDULA_STATUS',
                        'FECREG','IP','LOGIN','FECHA_M','IP_M','LOGIN_M')
                        ->where(['PERIODO_ID' => $id,'CEDULA_FOLIO' => $id2])            
                        ->get();
        $regcedulaarti= regCedulaarticulosModel::select('PERIODO_ID','CEDULA_FOLIO','CEDULA_PARTIDA','IAP_ID',
                        'CEDULA_FECHA','CEDULA_FECHA2','PERIODO_ID1','MES_ID1','DIA_ID1',
                        'ARTICULO_ID','ARTICULO_CANTIDAD','CEDART_OBS','CEDART_STATUS',
                        'FECREG','IP','LOGIN','FECHA_M','IP_M','LOGIN_M')       
                        ->where( ['PERIODO_ID' => $id,'CEDULA_FOLIO' => $id2])                      
                        ->orderBy('CEDULA_FOLIO'  ,'asc')
                        ->orderBy('CEDULA_PARTIDA','asc')
                        ->get();                                
        //dd($unidades);
        //return view('sicinar.cedulas.nuevoProgtrab',compact('regumedida','regiap','nombre','usuario','reganios','regperiodos','regmeses','regdias','regcedula'));
        return view('sicinar.cedulas.nuevaCedulaarti',compact('nombre','usuario','regiap','regtipos','regarticulos','regcedula','regcedulaarti'));   
    }

    public function actionAltaNuevaCedulaarti(Request $request){
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
        //$duplicado = regCedulaarticulosModel::where(['PERIODO_ID' => $request->periodo_id,'IAP_ID' => $request->iap_id])
        //             ->get();
        //if($duplicado->count() >= 1)
        //    return back()->withInput()->withErrors(['IAP_ID' => 'IAP '.$request->iap_id.' Ya existe programa de trabajo en el mismo periodo y con la IAP referida. Por favor verificar.']);
        //else{  
        $max = regArticulosModel::max('ARTICULO_ID');
        //$max = $max+1;
        $per_aux = date('Y');
        $mes_aux = date('m');
        $dia_aux = date('d');
        $hoy     = date('Y/m/d');
        //$arr_articulos_id   = Post::get('articulo_id');
        //$arr_articulos_cant = Post::get('articulo_cantidad');
        //dd($arr_articulos_id,' arreglo:',$arr_articulos_cant);
        //dd(' Articulos:',$request->input('articulo_id'),' Cantidad:',$request->input('articulo_cantidad'));
        $articulo_id   = $request->input('articulo_id');
        $articulo_cant = $request->input('articulo_cantidad');
        $i       = 0;
        //for($i=1;$i<=$max;$i++){
        //foreach($arr_articulos as $articulo) {    
        foreach($articulo_id as $key => $n ) {
            $i++;
            //********************* ALTA  *****************************/ 
            //**************** Validar duplicidad ***********************************/
            $duplicado = regCedulaarticulosModel::where(['PERIODO_ID'   => $request->periodo_id, 
                                                         'IAP_ID'       => $request->iap_id, 
                                                         'CEDULA_FOLIO' => $request->cedula_folio,
                                                         'ARTICULO_ID'  => $articulo_id[$key] 
                                                        ])
                                                         //'ARTICULO_ID'  => $request->input('articulo_id')[$i]])
                         ->get();
            if($duplicado->count() >= 1)
                return back()->withInput()->withErrors(['ARTICULO_ID' => 'Artículo '.$articulo_id[$key].' Ya existe en la lista de la cedula.']);
            else{  

                // ******** Obtiene partida ************************/
                //$partida = regCedulaarticulosModel::where(['PERIODO_ID'   => $request->periodo_id, 
                //                                           'IAP_ID'       => $request->iap_id, 
                //                                           'CEDULA_FOLIO' => $request->cedula_folio])
                //           ->max('CEDULA_PARTIDA');
                //$partida = $partida + 1;
                
                $nuevoarti = new regCedulaarticulosModel();
                $nuevoarti->PERIODO_ID       = $request->periodo_id;                            
                $nuevoarti->IAP_ID           = $request->iap_id;            
                $nuevoarti->CEDULA_FOLIO     = $request->cedula_folio;
                //$nuevoarti->CEDULA_PARTIDA = $partida;
                $nuevoarti->CEDULA_PARTIDA   = $i;
                //$nuevoarti->ARTICULO_ID      = $request->input('articulo_id')[$i];
                $nuevoarti->ARTICULO_ID      = $articulo_id[$key];
            
                $nuevoarti->CEDULA_FECHA     = $hoy;
                $nuevoarti->CEDULA_FECHA2    = trim($dia_aux.'/'.$mes_aux.'/'.$per_aux);

                $nuevoarti->PERIODO_ID1      = $per_aux;
                $nuevoarti->MES_ID1          = $mes_aux;
                $nuevoarti->DIA_ID1          = $dia_aux;

                $nuevoarti->ARTICULO_CANTIDAD= $articulo_cant[$key];
                $nuevoarti->CEDART_OBS       = substr(trim(strtoupper($request->cedart_obs)),0,499);
      
                $nuevoarti->IP               = $ip;
                $nuevoarti->LOGIN            = $nombre;         // Usuario ;
                //dd($nuevoarti);
                $nuevoarti->save();
                //if($nuevoarti->save() == true)
                    //toastr()->success('Articulo en cedula dado de alta.','ok!',['positionClass' => 'toast-bottom-right']);
                //else
                    //toastr()->error('Error al dar de alta lista de articulos en la cedula. Por favor volver a interlo.','Ups!',['positionClass' => 'toast-bottom-right']);
                //**************** Termina la alta ***************/
            }       //**************** Duplicado *********************/

        }   // ******************* for Termina listado de articulos **********/

        if($i = 0)
            toastr()->error('Error al dar de alta artículos en la cédula. Por favor volver a interlo.','Ups!',['positionClass' => 'toast-bottom-right']);
        else{  
                /************ Bitacora inicia *************************************/ 
                setlocale(LC_TIME, "spanish");        
                $xip          = session()->get('ip');
                $xperiodo_id  = (int)date('Y');
                $xprograma_id = 1;
                $xmes_id      = (int)date('m');
                $xproceso_id  =         3;
                $xfuncion_id  =      3012;
                $xtrx_id      =        32;    //Alta
                $regbitacora = regBitacoraModel::select('PERIODO_ID','PROGRAMA_ID','MES_ID','PROCESO_ID','FUNCION_ID', 
                                                    'TRX_ID','FOLIO','NO_VECES','FECHA_REG','IP','LOGIN', 
                                                    'FECHA_M','IP_M','LOGIN_M')
                               ->where(['PERIODO_ID' => $xperiodo_id,'PROGRAMA_ID' => $xprograma_id,'MES_ID' => $xmes_id,
                                    'PROCESO_ID' => $xproceso_id,'FUNCION_ID' => $xfuncion_id,'TRX_ID' => $xtrx_id, 
                                    'FOLIO' => $request->cedula_folio])
                               ->get();
                if($regbitacora->count() <= 0){              // Alta
                    $nuevoregBitacora = new regBitacoraModel();              
                    $nuevoregBitacora->PERIODO_ID = $xperiodo_id;    // Año de transaccion 
                    $nuevoregBitacora->PROGRAMA_ID= $xprograma_id;   // Proyecto JAPEM 
                    $nuevoregBitacora->MES_ID     = $xmes_id;        // Mes de transaccion
                    $nuevoregBitacora->PROCESO_ID = $xproceso_id;    // Proceso de apoyo
                    $nuevoregBitacora->FUNCION_ID = $xfuncion_id;    // Funcion del modelado de procesos 
                    $nuevoregBitacora->TRX_ID     = $xtrx_id;        // Actividad del modelado de procesos
                    $nuevoregBitacora->FOLIO      = $request->cedula_folio;          // Folio    
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
                                                      'FOLIO' => $request->cedula_folio])
                                 ->max('NO_VECES');
                    $xno_veces = $xno_veces+1;                        
                    //*********** Termina de obtener el no de veces *****************************         
                    $regbitacora = regBitacoraModel::select('NO_VECES','IP_M','LOGIN_M','FECHA_M')
                                   ->where(['PERIODO_ID' => $xperiodo_id, 'PROGRAMA_ID' => $xprograma_id, 
                                        'MES_ID' => $xmes_id, 'PROCESO_ID' => $xproceso_id, 
                                        'FUNCION_ID' => $xfuncion_id,'TRX_ID' => $xtrx_id,'FOLIO' => $request->cedula_folio])
                                   ->update([
                                         'NO_VECES'=> $regbitacora->NO_VECES = $xno_veces,
                                         'IP_M'    => $regbitacora->IP       = $ip,
                                         'LOGIN_M' => $regbitacora->LOGIN_M  = $nombre,
                                         'FECHA_M' => $regbitacora->FECHA_M  = date('Y/m/d')  //date('d/m/Y')
                                       ]);
                    toastr()->success('Bitacora actualizada.','¡Ok!',['positionClass' => 'toast-bottom-right']);
                }   /************ Bitacora termina *************************************/ 
        }

        return redirect()->route('verCedulaarti',array($request->periodo_id,$request->cedula_folio));
    }

    public function actionEditarCedulaarti($id,$id2,$id3){
        $nombre        = session()->get('userlog');
        $pass          = session()->get('passlog');
        if($nombre == NULL AND $pass == NULL){
            return view('sicinar.login.expirada');
        }
        $usuario       = session()->get('usuario');
        $rango         = session()->get('rango');
        $arbol_id      = session()->get('arbol_id');        

        $regarticulos = regArticulosModel::join('JP_CAT_TIPO_ARTICULO','JP_CAT_TIPO_ARTICULO.TIPO_ID','=','JP_CAT_ARTICULOS.TIPO_ID')
                                       ->select('JP_CAT_ARTICULOS.ARTICULO_ID', 'JP_CAT_ARTICULOS.ARTICULO_DESC',
                                                'JP_CAT_ARTICULOS.TIPO_ID','JP_CAT_TIPO_ARTICULO.TIPO_DESC')
                                       ->get(); 
        $reganios     = regPeriodosaniosModel::select('PERIODO_ID','PERIODO_DESC')
                        ->wherein('PERIODO_ID',[2020,2021,2022,2023])        
                        ->get();
        $regperiodos  = regPfiscalesModel::select('PERIODO_ID', 'PERIODO_DESC')->get();         
        $regmeses     = regMesesModel::select('MES_ID','MES_DESC')->get();      
        $regdias      = regDiasModel::select('DIA_ID','DIA_DESC')->get();         
        //********* Validar rol de usuario **********************/
        if(session()->get('rango') !== '0'){                          
            $regiap   = regIapModel::select('IAP_ID', 'IAP_DESC','IAP_STATUS')
                        ->get();                                                        
        }else{
            $regiap   = regIapModel::select('IAP_ID', 'IAP_DESC','IAP_STATUS')
                        ->where('IAP_ID',$arbol_id)
                        ->get();            
        }                    
        $regcedula    = regCedulaModel::select('PERIODO_ID','CEDULA_FOLIO','IAP_ID','SP_ID','SP_NOMB',
                        'CEDULA_FECHA','CEDULA_FECHA2','PERIODO_ID1','MES_ID1','DIA_ID1','CEDULA_OBS','CEDULA_STATUS',
                        'FECREG','IP','LOGIN','FECHA_M','IP_M','LOGIN_M')
                        ->where(['PERIODO_ID' => $id, 'CEDULA_FOLIO' => $id2])
                        ->get();
        $regcedulaarti= regCedulaarticulosModel::select('PERIODO_ID','CEDULA_FOLIO','CEDULA_PARTIDA','IAP_ID',
                        'CEDULA_FECHA','CEDULA_FECHA2','PERIODO_ID1','MES_ID1','DIA_ID1',
                        'ARTICULO_ID','ARTICULO_CANTIDAD','CEDART_OBS','CEDART_STATUS',
                        'FECREG','IP','LOGIN','FECHA_M','IP_M','LOGIN_M')       
                        ->where(['PERIODO_ID' => $id,'CEDULA_FOLIO' => $id2, 'ARTICULO_ID' => $id3])
                        //->where('FOLIO',$id)
                        //->where('PARTIDA',$id2)
                        ->first();
        if($regcedulaarti->count() <= 0){
            toastr()->error('No existe artículo en cedula.','Lo siento!',['positionClass' => 'toast-bottom-right']);
            //return redirect()->route('nuevoprogtrab');
        }
        //return view('sicinar.cedulas.editarProgtrab',compact('nombre','usuario','regiap','regumedida','reganios','regperiodos','regmeses','regdias','regcedula'));
        return view('sicinar.cedulas.editarCedulaarti',compact('nombre','usuario','regarticulos','regiap','reganios','regperiodos','regmeses','regdias','regcedula','regcedulaarti'));

    }

    public function actionActualizarCedulaarti(cedulaarticuloRequest $request, $id, $id2,$id3){
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
        $regcedulaarti = regCedulaarticulosModel::where(['PERIODO_ID' => $id,'CEDULA_FOLIO' => $id2, 'ARTICULO_ID' => $id3]);
        if($regcedulaarti->count() <= 0)
            toastr()->error('No existe articulo en la cedula.','¡Por favor volver a intentar!',['positionClass' => 'toast-bottom-right']);
        else{        
            //********************** Actualizar ********************************/
            $regcedulaarti = regCedulaarticulosModel::where(['PERIODO_ID' => $id,'CEDULA_FOLIO' => $id2, 'ARTICULO_ID' => $id3])        
                             ->update([                
                                        'ARTICULO_CANTIDAD' => $request->articulo_cantidad,

                                        'IP_M'              => $ip,
                                        'LOGIN_M'           => $nombre,
                                        'FECHA_M'           => date('Y/m/d')    //date('d/m/Y')                                
                                       ]);
            toastr()->success('Articulo en cedula actualizado.','¡Ok!',['positionClass' => 'toast-bottom-right']);

            /************ Bitacora inicia *************************************/ 
            setlocale(LC_TIME, "spanish");        
            $xip          = session()->get('ip');
            $xperiodo_id  = (int)date('Y');
            $xprograma_id = 1;
            $xmes_id      = (int)date('m');
            $xproceso_id  =         3;
            $xfuncion_id  =      3012;
            $xtrx_id      =        33;    //Actualizar 
            $regbitacora = regBitacoraModel::select('PERIODO_ID', 'PROGRAMA_ID', 'MES_ID', 'PROCESO_ID', 'FUNCION_ID', 
                           'TRX_ID', 'FOLIO', 'NO_VECES', 'FECHA_REG', 'IP', 'LOGIN', 'FECHA_M', 'IP_M', 'LOGIN_M')
                           ->where(['PERIODO_ID' => $xperiodo_id, 'PROGRAMA_ID' => $xprograma_id, 'MES_ID' => $xmes_id, 
                                    'PROCESO_ID' => $xproceso_id, 'FUNCION_ID' => $xfuncion_id, 'TRX_ID' => $xtrx_id, 
                                    'FOLIO' => $id2])
                           ->get();
            if($regbitacora->count() <= 0){              // Alta
                $nuevoregBitacora = new regBitacoraModel();              
                $nuevoregBitacora->PERIODO_ID = $xperiodo_id;    // Año de transaccion 
                $nuevoregBitacora->PROGRAMA_ID= $xprograma_id;   // Proyecto JAPEM 
                $nuevoregBitacora->MES_ID     = $xmes_id;        // Mes de transaccion
                $nuevoregBitacora->PROCESO_ID = $xproceso_id;    // Proceso de apoyo
                $nuevoregBitacora->FUNCION_ID = $xfuncion_id;    // Funcion del modelado de procesos 
                $nuevoregBitacora->TRX_ID     = $xtrx_id;        // Actividad del modelado de procesos
                $nuevoregBitacora->FOLIO      = $id2;             // Folio    
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
                             'MES_ID' => $xmes_id, 'PROCESO_ID' => $xproceso_id, 'FUNCION_ID' => $xfuncion_id, 
                             'TRX_ID' => $xtrx_id, 'FOLIO' => $id2])
                             ->max('NO_VECES');
                $xno_veces = $xno_veces+1;                        
                //*********** Termina de obtener el no de veces *****************************         
                $regbitacora = regBitacoraModel::select('NO_VECES','IP_M','LOGIN_M','FECHA_M')
                               ->where(['PERIODO_ID' => $xperiodo_id, 'PROGRAMA_ID' => $xprograma_id, 
                                        'MES_ID' => $xmes_id, 'PROCESO_ID' => $xproceso_id, 'FUNCION_ID' => $xfuncion_id, 
                                        'TRX_ID' => $xtrx_id, 'FOLIO' => $id2])
                               ->update([
                                         'NO_VECES'=> $regbitacora->NO_VECES = $xno_veces,
                                         'IP_M'    => $regbitacora->IP       = $ip,
                                         'LOGIN_M' => $regbitacora->LOGIN_M  = $nombre,
                                         'FECHA_M' => $regbitacora->FECHA_M  = date('Y/m/d')  //date('d/m/Y')
                                         ]);
                toastr()->success('Bitacora actualizada.','¡Ok!',['positionClass' => 'toast-bottom-right']);
            }   /************ Bitacora termina *************************************/                     
        }       /************ Actualizar *******************************************/

        return redirect()->route('verCedulaarti',array($id,$id2));

    }


    public function actionBorrarCedulaArti($id,$id2,$id3){
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
        $regcedulaarti  = regCedulaarticulosModel::where(['PERIODO_ID' => $id, 'CEDULA_FOLIO' => $id2]);
        //              ->find('UMEDIDA_ID',$id);
        if($regcedulaarti->count() <= 0)
            toastr()->error('No existe cedula.','¡Por favor volver a intentar!',['positionClass' => 'toast-bottom-right']);
        else{        
            $regcedulaarti->delete();
            toastr()->success('Articulo de Cedula eliminado.','¡Ok!',['positionClass' => 'toast-bottom-right']);

            //echo 'Ya entre a borrar registro..........';
            /************ Bitacora inicia *************************************/ 
            setlocale(LC_TIME, "spanish");        
            $xip          = session()->get('ip');
            $xperiodo_id  = (int)date('Y');
            $xprograma_id = 1;
            $xmes_id      = (int)date('m');
            $xproceso_id  =         3;
            $xfuncion_id  =      3012;
            $xtrx_id      =        34;     // Baja 
            $regbitacora = regBitacoraModel::select('PERIODO_ID','PROGRAMA_ID','MES_ID', 'PROCESO_ID','FUNCION_ID', 
                           'TRX_ID', 'FOLIO', 'NO_VECES', 'FECHA_REG', 'IP', 'LOGIN', 'FECHA_M', 'IP_M', 'LOGIN_M')
                           ->where(['PERIODO_ID' => $xperiodo_id, 'PROGRAMA_ID' => $xprograma_id, 'MES_ID' => $xmes_id, 
                                    'PROCESO_ID' => $xproceso_id, 'FUNCION_ID' => $xfuncion_id, 
                                    'TRX_ID' => $xtrx_id, 'FOLIO' => $id])
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
                                                      'FUNCION_ID' => $xfuncion_id,'TRX_ID' => $xtrx_id,'FOLIO' => $id])
                             ->max('NO_VECES');
                $xno_veces = $xno_veces+1;                        
                //*********** Termina de obtener el no de veces *****************************         
                $regbitacora = regBitacoraModel::select('NO_VECES','IP_M','LOGIN_M','FECHA_M')
                               ->where(['PERIODO_ID' => $xperiodo_id, 'PROGRAMA_ID' => $xprograma_id, 
                                        'MES_ID' => $xmes_id, 'PROCESO_ID' => $xproceso_id, 'FUNCION_ID' => $xfuncion_id, 
                                        'TRX_ID' => $xtrx_id, 'FOLIO' => $id])
                               ->update([
                                         'NO_VECES' => $regbitacora->NO_VECES = $xno_veces,
                                         'IP_M'     => $regbitacora->IP       = $ip,
                                         'LOGIN_M'  => $regbitacora->LOGIN_M  = $nombre,
                                         'FECHA_M'  => $regbitacora->FECHA_M  = date('Y/m/d')  //date('d/m/Y')
                                        ]);
                toastr()->success('Bitacora actualizada.','¡Ok!',['positionClass' => 'toast-bottom-right']);
            }   /************ Bitacora termina *************************************/                 
        }       /************* Termina de eliminar *********************************/
        return redirect()->route('verCedula');
    }    

}
