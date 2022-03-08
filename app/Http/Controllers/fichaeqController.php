<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests\fichaRequest;
use App\Http\Requests\ficha1Request;
use App\Http\Requests\ficha2Request;

use App\regBitacoraModel;
use App\regMunicipioModel;
use App\regCategoriasModel;
use App\regRegionesModel;
use App\regPeriodosModel;
use App\regMesesModel;
use App\regDiasModel;
use App\regfichaModel;

// Exportar a excel 
use App\Exports\ExcelExportFichas;
use Maatwebsite\Excel\Facades\Excel;
// Exportar a pdf
use PDF;
//use Options;

class fichaeqController extends Controller
{

    public function actionBuscarFicha(Request $request)
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

        $regmunicipio = regMunicipioModel::join('CASMEX_CAT_REGIONES',
                                           [['CASMEX_CAT_REGIONES.REGION_ID','=','CASMEX_CAT_MUNICIPIOS_SEDESEM.REGIONID'],
                                            ['CASMEX_CAT_MUNICIPIOS_SEDESEM.ENTIDADFEDERATIVAID','=',15]
                                           ])
                        ->select( 'CASMEX_CAT_REGIONES.REGION_ID',
                                  'CASMEX_CAT_REGIONES.DESC_REGION',
                                  'CASMEX_CAT_REGIONES.ROMA_REGION',
                                  'CASMEX_CAT_MUNICIPIOS_SEDESEM.MUNICIPIOID',
                                  'CASMEX_CAT_MUNICIPIOS_SEDESEM.MUNICIPIONOMBRE AS MUNICIPIO')
                        ->wherein('CASMEX_CAT_MUNICIPIOS_SEDESEM.ENTIDADFEDERATIVAID',[15])
                        ->orderBy('CASMEX_CAT_MUNICIPIOS_SEDESEM.REGIONID'       ,'ASC')
                        ->orderBy('CASMEX_CAT_MUNICIPIOS_SEDESEM.MUNICIPIONOMBRE','ASC')
                        ->get();        
        $regcategoria = regCategoriasModel::select('CATE_ID','CATE_DESC')
                        ->orderBy('CATE_ID','asc')
                        ->get(); 
        $regregiones  = regRegionesModel::select('REGION_ID','DESC_REGION','ROMA_REGION')
                        ->orderBy('REGION_ID','asc')
                        ->get();   
        $regperiodos  = RegPeriodosModel::select('PERIODO_ID', 'PERIODO_DESC')
                        ->get();         
        $regmeses     = regMesesModel::select('MES_ID','MES_DESC')
                        ->get();      
        $regdias      = regDiasModel::select('DIA_ID','DIA_DESC')
                        ->get();                
        //**************************************************************//
        // ***** busqueda https://github.com/rimorsoft/Search-simple ***//
        // ***** video https://www.youtube.com/watch?v=bmtD9GUaszw   ***//                            
        //**************************************************************//
        $name  = $request->get('name');    
        $email = $request->get('email');  
        $bio   = $request->get('bio');    
        if(session()->get('rango') !== '0'){            
            $regficha = regfichaModel::orderBy('EQ_ID', 'ASC')
                        ->name($name)     //Metodos personalizados es equvalente a ->where('IAP_DESC', 'LIKE', "%$name%");
                        ->email($email)         //Metodos personalizados
                        ->bio($bio)             //Metodos personalizados
                        ->paginate(30);
        }else{
            $regficha = regfichaModel::where('EQ_ID',$arbol_id)
                        ->orderBy('EQ_ID', 'ASC')
                        ->name($name)     //Metodos personalizados es equvalente a ->where('IAP_DESC', 'LIKE', "%$name%");
                        ->email($email)         //Metodos personalizados
                        ->bio($bio)             //Metodos personalizados
                        ->paginate(30);            
        }                        
        if($regficha->count() <= 0){
            toastr()->error('No existen equipos.','Lo siento!',['positionClass' => 'toast-bottom-right']);
        }            
        return view('sicinar.fichas_equipos.verFichas', compact('nombre','usuario','regficha','regmunicipio','regcategoria','regperiodos','regmeses','regdias','regregiones'));
    }

    public function actionVerFicha(){
        $nombre       = session()->get('userlog');
        $pass         = session()->get('passlog');
        if($nombre == NULL AND $pass == NULL){
            return view('sicinar.login.expirada');
        }
        $usuario      = session()->get('usuario');
        $rango        = session()->get('rango');
        $ip           = session()->get('ip');
        $arbol_id     = session()->get('arbol_id');        

        $regmunicipio = regMunicipioModel::join('CASMEX_CAT_REGIONES',
                                           [['CASMEX_CAT_REGIONES.REGION_ID','=','CASMEX_CAT_MUNICIPIOS_SEDESEM.REGIONID'],
                                            ['CASMEX_CAT_MUNICIPIOS_SEDESEM.ENTIDADFEDERATIVAID','=',15]
                                           ])
                        ->select( 'CASMEX_CAT_REGIONES.REGION_ID',
                                  'CASMEX_CAT_REGIONES.DESC_REGION',
                                  'CASMEX_CAT_REGIONES.ROMA_REGION',
                                  'CASMEX_CAT_MUNICIPIOS_SEDESEM.MUNICIPIOID',
                                  'CASMEX_CAT_MUNICIPIOS_SEDESEM.MUNICIPIONOMBRE AS MUNICIPIO')
                        ->wherein('CASMEX_CAT_MUNICIPIOS_SEDESEM.ENTIDADFEDERATIVAID',[15])
                        ->orderBy('CASMEX_CAT_MUNICIPIOS_SEDESEM.REGIONID'       ,'ASC')
                        ->orderBy('CASMEX_CAT_MUNICIPIOS_SEDESEM.MUNICIPIONOMBRE','ASC')
                        ->get();      
        $regregiones  = regRegionesModel::select('REGION_ID','DESC_REGION','ROMA_REGION')
                        ->orderBy('REGION_ID','asc')
                        ->get();                            
        $regcategoria = regCategoriasModel::select('CATE_ID','CATE_DESC')
                        ->orderBy('CATE_ID','asc')
                        ->get(); 
        $regperiodos  = RegPeriodosModel::select('PERIODO_ID', 'PERIODO_DESC')
                        ->get();         
        $regmeses     = regMesesModel::select('MES_ID','MES_DESC')
                        ->get();      
        $regdias      = regDiasModel::select('DIA_ID','DIA_DESC')
                        ->get();   
        if(session()->get('rango') !== '0'){                                                       
            $regficha = regfichaModel::select('EQ_ID','EQ_DESC','EQ_NOMBRES_REP','EQ_AP_REP','EQ_AM_REP', 
                        'EQ_NOMBRECOMP_REP','EQ_CURP_REP','EQ_TEL_REP','EQ_EMAIL_REP','EQ_RAMA',  
                        'EQ_ARC1','EQ_ARC2','EQ_STATUS1','EQ_STATUS2','CATE_ID','MUNICIPIO_ID','REGION_ID',  
                        'FEC_REG','FEC_REG2','IP','LOGIN','FECHA_M','FECHA_M2','IP_M','LOGIN_M')
                        ->orderBy('EQ_ID','ASC')
                        ->paginate(30);
        }else{
            $regficha = regfichaModel::select('EQ_ID','EQ_DESC','EQ_NOMBRES_REP','EQ_AP_REP','EQ_AM_REP', 
                        'EQ_NOMBRECOMP_REP','EQ_CURP_REP','EQ_TEL_REP','EQ_EMAIL_REP','EQ_RAMA',  
                        'EQ_ARC1','EQ_ARC2','EQ_STATUS1','EQ_STATUS2','CATE_ID','MUNICIPIO_ID','REGION_ID',  
                        'FEC_REG','FEC_REG2','IP','LOGIN','FECHA_M','FECHA_M2','IP_M','LOGIN_M')
                        ->where(  'EQ_ID',$arbol_id)
                        ->orderBy('EQ_ID','ASC')
                        ->paginate(30);            
        }                                
        if($regficha->count() <= 0){
            toastr()->error('No existen equipos de futbol','Lo siento!',['positionClass' => 'toast-bottom-right']);
        }
        return view('sicinar.fichas_equipos.verFichas',compact('nombre','usuario','arbol_id','regficha','regmunicipio','regcategoria','regperiodos','regmeses','regdias','regregiones'));
    }

    public function actionNuevaFicha(){
        $nombre       = session()->get('userlog');
        $pass         = session()->get('passlog');
        if($nombre == NULL AND $pass == NULL){
            return view('sicinar.login.expirada');
        }
        $usuario      = session()->get('usuario');
        $rango        = session()->get('rango');
        $ip           = session()->get('ip');
        $arbol_id     = session()->get('arbol_id');

        $regmunicipio = regMunicipioModel::join('CASMEX_CAT_REGIONES',
                                           [['CASMEX_CAT_REGIONES.REGION_ID','=','CASMEX_CAT_MUNICIPIOS_SEDESEM.REGIONID'],
                                            ['CASMEX_CAT_MUNICIPIOS_SEDESEM.ENTIDADFEDERATIVAID','=',15]
                                           ])
                        ->select( 'CASMEX_CAT_REGIONES.REGION_ID',
                                  'CASMEX_CAT_REGIONES.DESC_REGION',
                                  'CASMEX_CAT_REGIONES.ROMA_REGION',
                                  'CASMEX_CAT_MUNICIPIOS_SEDESEM.MUNICIPIOID',
                                  'CASMEX_CAT_MUNICIPIOS_SEDESEM.MUNICIPIONOMBRE AS MUNICIPIO')
                        ->wherein('CASMEX_CAT_MUNICIPIOS_SEDESEM.ENTIDADFEDERATIVAID',[15])
                        ->orderBy('CASMEX_CAT_MUNICIPIOS_SEDESEM.REGIONID'       ,'ASC')
                        ->orderBy('CASMEX_CAT_MUNICIPIOS_SEDESEM.MUNICIPIONOMBRE','ASC')
                        ->get();        
        $regcategoria = regCategoriasModel::select('CATE_ID','CATE_DESC')
                        ->orderBy('CATE_ID','asc')
                        ->get(); 
        $regperiodos  = RegPeriodosModel::select('PERIODO_ID', 'PERIODO_DESC')
                        ->get();         
        $regmeses     = regMesesModel::select('MES_ID','MES_DESC')
                        ->get();      
        $regdias      = regDiasModel::select('DIA_ID','DIA_DESC')
                        ->get();                      
        $regficha     = regfichaModel::select('EQ_ID','EQ_DESC','EQ_NOMBRES_REP','EQ_AP_REP','EQ_AM_REP', 
                        'EQ_NOMBRECOMP_REP','EQ_CURP_REP','EQ_TEL_REP','EQ_EMAIL_REP','EQ_RAMA',  
                        'EQ_ARC1','EQ_ARC2','EQ_STATUS1','EQ_STATUS2','CATE_ID','MUNICIPIO_ID','REGION_ID',  
                        'FEC_REG','FEC_REG2','IP','LOGIN','FECHA_M','FECHA_M2','IP_M','LOGIN_M')
                         ->orderBy('EQ_ID','asc')
                         ->get();
        //dd($unidades);
        return view('sicinar.fichas_equipos.nuevaFicha',compact('regcategoria','regmunicipio','regficha','nombre','usuario','regperiodos','regmeses','regdias'));
    }

    public function actionAltaNuevaFicha(Request $request){
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
        $duplicado = regfichaModel::where(['EQ_DESC' => $request->eq_desc,'MUNICIPIO_ID' => $request->municipio_id])
                     ->get();
        if($duplicado->count() >= 1)
            return back()->withInput()->withErrors(['EQ_DESC' => 'EQUIPO '.$request->eq_desc.' Ya existe equipo de futbol con ese nombre y municipio. Por favor verificar.']);
        else{  
            /************ ALTA  *****************************/ 
            $yperiodo_id= (int)date('Y');
            $ymes_id    = (int)date('m');
            $ydia_id    = (int)date('d');
            $mes        = regMesesModel::ObtMes($ymes_id);
            $dia        = regDiasModel::ObtDia($ydia_id);  
            $region     = regMunicipioModel::ObtRegion($request->municipio_id);  

            $nombre_completo=substr(strtoupper(trim($request->eq_ap_rep).' '.trim($request->eq_am_rep).' '.trim($request->eq_nombres_rep)),0,79);                          

            $eq_id = regfichaModel::max('EQ_ID');
            $eq_id = $eq_id+1;

            $name1 =null;
            //Comprobar  si el campo foto1 tiene un archivo asignado:
            if($request->hasFile('eq_arc1')){
               $name1 = $eq_id.'_'.$request->file('eq_arc1')->getClientOriginalName(); 
               $request->file('eq_arc1')->move(public_path().'/storage/', $name1);
            }
            $name2 =null;
            //Comprobar  si el campo foto2 tiene un archivo asignado:        
            if($request->hasFile('eq_arc2')){
               $name2 = $eq_id.'_'.$request->file('eq_arc2')->getClientOriginalName(); 
               $request->file('eq_arc2')->move(public_path().'/storage/', $name2);
            }

            $nuevaiap = new regfichaModel();
            $nuevaiap->EQ_ID         = $eq_id;
            $nuevaiap->EQ_DESC       = substr(trim(strtoupper($request->eq_desc))       ,0,99);
            $nuevaiap->EQ_NOMBRES_REP= substr(trim(strtoupper($request->eq_nombres_rep)),0,79);
            $nuevaiap->EQ_AP_REP     = substr(trim(strtoupper($request->eq_ap_rep))     ,0,79);
            $nuevaiap->EQ_AM_REP     = substr(trim(strtoupper($request->eq_am_rep))     ,0,79);
            $nuevaiap->EQ_NOMBRECOMP_REP= $nombre_completo;
            $nuevaiap->EQ_TEL_REP    = substr(trim(strtoupper($request->eq_tel_rep))    ,0,59);
            $nuevaiap->EQ_EMAIL_REP  = substr(trim(strtolower($request->eq_email_rep))  ,0,79);
            $nuevaiap->EQ_RAMA       = $request->eq_rama;
            $nuevaiap->MUNICIPIO_ID  = $request->municipio_id;
            $nuevaiap->REGION_ID     = $region[0]->regionid;
            $nuevaiap->CATE_ID       = $request->cate_id;
            $nuevaiap->EQ_ARC1       = $name1;
        
            $nuevaiap->IP          = $ip;
            $nuevaiap->LOGIN       = $nombre;         // Usuario ;
            //dd($nuevaiap);
            $nuevaiap->save();
            if($nuevaiap->save() == true){
                toastr()->success('Ficha del equipo dada de alta.','ok!',['positionClass' => 'toast-bottom-right']);

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
                                        'FOLIO'      => $eq_id])
                               ->get();
                if($regbitacora->count() <= 0){              // Alta
                    $nuevoregBitacora = new regBitacoraModel();              
                    $nuevoregBitacora->PERIODO_ID = $xperiodo_id;    // Año de transaccion 
                    $nuevoregBitacora->MES_ID     = $xmes_id;        // Mes de transaccion
                    $nuevoregBitacora->PROCESO_ID = $xproceso_id;    // Proceso de apoyo
                    $nuevoregBitacora->FUNCION_ID = $xfuncion_id;    // Funcion del modelado de procesos 
                    $nuevoregBitacora->TRX_ID     = $xtrx_id;        // Actividad del modelado de procesos
                    $nuevoregBitacora->FOLIO      = $eq_id;         // Folio    
                    $nuevoregBitacora->NO_VECES   = 1;               // Numero de veces            
                    $nuevoregBitacora->IP         = $ip;             // IP
                    $nuevoregBitacora->LOGIN      = $nombre;         // Usuario 

                    $nuevoregBitacora->save();
                    if($nuevoregBitacora->save() == true)
                        toastr()->success('Trx de Ficha de equipo registrada en Bitacora.','¡Ok!',['positionClass' => 'toast-bottom-right']);
                    else
                        toastr()->error('Error de trx ficha de equipo. Por favor volver a interlo.','Ups!',['positionClass' => 'toast-bottom-right']);
                }else{                   
                    //*********** Obtine el no. de veces *****************************
                    $xno_veces=regBitacoraModel::where(['PERIODO_ID' => $xperiodo_id,'MES_ID'     => $xmes_id, 
                                                        'PROCESO_ID' => $xproceso_id,'FUNCION_ID' => $xfuncion_id, 
                                                        'TRX_ID'     => $xtrx_id,    'FOLIO'      => $eq_id])
                               ->max('NO_VECES');
                    $xno_veces = $xno_veces+1;                        
                    //*********** Termina de obtener el no de veces *****************************         
                    $regbitacora=regBitacoraModel::select('NO_VECES','IP_M','LOGIN_M','FECHA_M')
                                 ->where(['PERIODO_ID' => $xperiodo_id,'MES_ID' => $xmes_id,'PROCESO_ID' => $xproceso_id, 
                                          'FUNCION_ID' => $xfuncion_id,'TRX_ID' => $xtrx_id,'FOLIO'      => $eq_id])
                                 ->update([
                                           'NO_VECES' => $regbitacora->NO_VECES = $xno_veces,
                                           'IP_M'     => $regbitacora->IP       = $ip,
                                           'LOGIN_M'  => $regbitacora->LOGIN_M  = $nombre,
                                           'FECHA_M'  => $regbitacora->FECHA_M  = date('Y/m/d')  //date('d/m/Y')
                                         ]);
                    toastr()->success('Trx de ficha del equipo actualizada en Bitacora.','¡Ok!',['positionClass' => 'toast-bottom-right']);
                }   /************ Bitacora termina *************************************/ 
            }else{
                toastr()->error('No se registro ficha de equipo. Por favor volver a interlo.','Ups!',['positionClass' => 'toast-bottom-right']);
            } //**************** Termina alta **********************/
        }       // ******************* Termina el duplicado **********/
        return redirect()->route('verficha');
    }

    public function actionEditarFicha($id){
        $nombre        = session()->get('userlog');
        $pass          = session()->get('passlog');
        if($nombre == NULL AND $pass == NULL){
            return view('sicinar.login.expirada');
        }
        $usuario       = session()->get('usuario');
        $rango         = session()->get('rango');
        $arbol_id      = session()->get('arbol_id');        

        $regmunicipio = regMunicipioModel::join('CASMEX_CAT_REGIONES',
                                           [['CASMEX_CAT_REGIONES.REGION_ID','=','CASMEX_CAT_MUNICIPIOS_SEDESEM.REGIONID'],
                                            ['CASMEX_CAT_MUNICIPIOS_SEDESEM.ENTIDADFEDERATIVAID','=',15]
                                           ])
                        ->select( 'CASMEX_CAT_REGIONES.REGION_ID',
                                  'CASMEX_CAT_REGIONES.DESC_REGION',
                                  'CASMEX_CAT_REGIONES.ROMA_REGION',
                                  'CASMEX_CAT_MUNICIPIOS_SEDESEM.MUNICIPIOID',
                                  'CASMEX_CAT_MUNICIPIOS_SEDESEM.MUNICIPIONOMBRE AS MUNICIPIO')
                        ->wherein('CASMEX_CAT_MUNICIPIOS_SEDESEM.ENTIDADFEDERATIVAID',[15])
                        ->orderBy('CASMEX_CAT_MUNICIPIOS_SEDESEM.REGIONID'       ,'ASC')
                        ->orderBy('CASMEX_CAT_MUNICIPIOS_SEDESEM.MUNICIPIONOMBRE','ASC')
                        ->get();        
        $regcategoria = regCategoriasModel::select('CATE_ID','CATE_DESC')
                        ->orderBy('CATE_ID','asc')
                        ->get(); 
        $regperiodos  = RegPeriodosModel::select('PERIODO_ID', 'PERIODO_DESC')
                        ->get();         
        $regmeses     = regMesesModel::select('MES_ID','MES_DESC')
                        ->get();      
        $regdias      = regDiasModel::select('DIA_ID','DIA_DESC')
                        ->get();                      
        $regficha     = regfichaModel::select('EQ_ID','EQ_DESC','EQ_NOMBRES_REP','EQ_AP_REP','EQ_AM_REP', 
                        'EQ_NOMBRECOMP_REP','EQ_CURP_REP','EQ_TEL_REP','EQ_EMAIL_REP','EQ_RAMA',  
                        'EQ_ARC1','EQ_ARC2','EQ_STATUS1','EQ_STATUS2','CATE_ID','MUNICIPIO_ID','REGION_ID',  
                        'FEC_REG','FEC_REG2','IP','LOGIN','FECHA_M','FECHA_M2','IP_M','LOGIN_M')
                        ->where(  'EQ_ID',$id)
                        ->first();
        if($regficha->count() <= 0){
            toastr()->error('No existe ficha del equipo.','Lo siento!',['positionClass' => 'toast-bottom-right']);
        }
        return view('sicinar.fichas_equipos.editarFicha',compact('nombre','usuario','regficha','regmunicipio','regcategoria','regregiones','regperiodos','regmeses','regdias'));

    }

    public function actionActualizarFicha(fichaRequest $request, $id){
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
        $regficha = regfichaModel::where('EQ_ID',$id);
        if($regficha->count() <= 0)
            toastr()->error('No existe ficha del equipo.','¡Por favor volver a intentar!',['positionClass' => 'toast-bottom-right']);
        else{        
            //*************** Actualizar ********************************/
            $yperiodo_id= (int)date('Y');
            $ymes_id    = (int)date('m');
            $ydia_id    = (int)date('d');
            $mes        = regMesesModel::ObtMes($ymes_id);
            $dia        = regDiasModel::ObtDia($ydia_id);  
            $region     = regMunicipioModel::ObtRegion($request->municipio_id);  

            $nombre_completo=substr(strtoupper(trim($request->eq_ap_rep).' '.trim($request->eq_am_rep).' '.trim($request->eq_nombres_rep)),0,79);                          

            $name1      = null;
            //dd('0....',' Rama:'.$request->eq_rama,' MUN:'.$request->municipio_id,' cate:'.$request->cate_id,' reg:'.$region_id);
            $regficha = regfichaModel::where('EQ_ID',$id)        
                        ->update([                           
                                  'EQ_AP_REP'     => substr(trim(strtoupper($request->eq_ap_rep))     ,0,79),
                                  'EQ_AM_REP'     => substr(trim(strtoupper($request->eq_am_rep))     ,0,79),
                                  'EQ_NOMBRES_REP'=> substr(trim(strtoupper($request->eq_nombres_rep)),0,79),
                                  'EQ_NOMBRECOMP_REP'=> substr(trim(strtoupper($nombre_completo))     ,0,79),
                                  'EQ_TEL_REP'    => substr(trim(strtoupper($request->eq_tel_rep))    ,0,59),
                                  'EQ_EMAIL_REP'  => substr(trim(strtolower($request->eq_email_rep))  ,0,79),
                                  'EQ_RAMA'       => $request->eq_rama,                                      

                                  'MUNICIPIO_ID'  => $request->municipio_id,
                                  'CATE_ID'       => $request->cate_id,
                                  'REGION_ID'     => $region[0]->regionid,
                                  //'EQ_ARC1'     => $name1,

                                  'IP_M'          => $ip,
                                  'LOGIN_M'       => $nombre,
                                  'FECHA_M2'      => trim($dia[0]->dia_desc.'/'.$mes[0]->mes_mes.'/'.$yperiodo_id),
                                  'FECHA_M'       => date('Y/m/d')    //date('d/m/Y')  
                                  ]);
            toastr()->success('Ficha del equipo de futbol actualizada.','¡Ok!',['positionClass' => 'toast-bottom-right']);

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
                    toastr()->success('Trx de actualización de ficha registrada en Bitacora.','¡Ok!',['positionClass' => 'toast-bottom-right']);
                else
                    toastr()->error('Error Trx de actualización de ficha en bitacora. Por favor volver a interlo.','Ups!',['positionClass' => 'toast-bottom-right']);
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
                toastr()->success('Trx de actualización de ficha registrada en Bitacora.','¡Ok!',['positionClass' => 'toast-bottom-right']);
            }   /************ Bitacora termina *************************************/                     
        }       /************ Actualizar *******************************************/
        return redirect()->route('verficha');
    }

    public function actionEditarFicha1($id){
        $nombre        = session()->get('userlog');
        $pass          = session()->get('passlog');
        if($nombre == NULL AND $pass == NULL){
            return view('sicinar.login.expirada');
        }
        $usuario       = session()->get('usuario');
        $rango         = session()->get('rango');
        $arbol_id      = session()->get('arbol_id');        

        $regmunicipio = regMunicipioModel::join('CASMEX_CAT_REGIONES',
                                           [['CASMEX_CAT_REGIONES.REGION_ID','=','CASMEX_CAT_MUNICIPIOS_SEDESEM.REGIONID'],
                                            ['CASMEX_CAT_MUNICIPIOS_SEDESEM.ENTIDADFEDERATIVAID','=',15]
                                           ])
                        ->select( 'CASMEX_CAT_REGIONES.REGION_ID',
                                  'CASMEX_CAT_REGIONES.DESC_REGION',
                                  'CASMEX_CAT_REGIONES.ROMA_REGION',
                                  'CASMEX_CAT_MUNICIPIOS_SEDESEM.MUNICIPIOID',
                                  'CASMEX_CAT_MUNICIPIOS_SEDESEM.MUNICIPIONOMBRE AS MUNICIPIO')
                        ->wherein('CASMEX_CAT_MUNICIPIOS_SEDESEM.ENTIDADFEDERATIVAID',[15])
                        ->orderBy('CASMEX_CAT_MUNICIPIOS_SEDESEM.REGIONID'       ,'ASC')
                        ->orderBy('CASMEX_CAT_MUNICIPIOS_SEDESEM.MUNICIPIONOMBRE','ASC')
                        ->get();        
        $regcategoria = regCategoriasModel::select('CATE_ID','CATE_DESC')
                        ->orderBy('CATE_ID','asc')
                        ->get(); 
        $regperiodos  = RegPeriodosModel::select('PERIODO_ID', 'PERIODO_DESC')
                        ->get();         
        $regmeses     = regMesesModel::select('MES_ID','MES_DESC')
                        ->get();      
        $regdias      = regDiasModel::select('DIA_ID','DIA_DESC')
                        ->get();                      
        $regficha     = regfichaModel::select('EQ_ID','EQ_DESC','EQ_NOMBRES_REP','EQ_AP_REP','EQ_AM_REP', 
                        'EQ_NOMBRECOMP_REP','EQ_CURP_REP','EQ_TEL_REP','EQ_EMAIL_REP','EQ_RAMA',  
                        'EQ_ARC1','EQ_ARC2','EQ_STATUS1','EQ_STATUS2','CATE_ID','MUNICIPIO_ID','REGION_ID',  
                        'FEC_REG','FEC_REG2','IP','LOGIN','FECHA_M','FECHA_M2','IP_M','LOGIN_M')
                        ->where(  'EQ_ID',$id)
                        ->first();
        if($regficha->count() <= 0){
            toastr()->error('No existe ficha del equipo.','Lo siento!',['positionClass' => 'toast-bottom-right']);
        }
        return view('sicinar.fichas_equipos.editarFicha1',compact('nombre','usuario','regficha','regmunicipio','regcategoria','regregiones','regperiodos','regmeses','regdias'));
    }

    public function actionActualizarFicha1(ficha1Request $request, $id){
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
        $regficha = regfichaModel::where('EQ_ID',$id);
        if($regficha->count() <= 0)
            toastr()->error('No existe ficha del equipo.','¡Por favor volver a intentar!',['positionClass' => 'toast-bottom-right']);
        else{        

            $yperiodo_id= (int)date('Y');
            $ymes_id    = (int)date('m');
            $ydia_id    = (int)date('d');
            $mes        = regMesesModel::ObtMes($ymes_id);
            $dia        = regDiasModel::ObtDia($ydia_id);  

            $name01 =null;
            //echo "Escribió en el campo de texto 1: " .'-'. $request->iap_d01 .'-'. "<br><br>"; 
            //echo "Escribió en el campo de texto 1: " . $request->iap_d01 . "<br><br>"; 
            //Comprobar  si el campo foto1 tiene un archivo asignado:
            if($request->hasFile('eq_arc1')){
                echo "Escribió en el campo de texto 1: " .'-'. $request->eq_arc1 .'-'. "<br><br>"; 
                $name01 = $id.'_'.$request->file('eq_arc1')->getClientOriginalName(); 
                //sube el archivo a la carpeta del servidor public/storage/
                $request->file('eq_arc1')->move(public_path().'/storage/', $name01);

                $regficha = regfichaModel::where('EQ_ID',$id)        
                          ->update([                
                                    'EQ_ARC1'  => $name01,

                                    'IP_M'     => $ip,
                                    'LOGIN_M'  => $nombre,
                                    'FECHA_M2' => trim($dia[0]->dia_desc.'/'.$mes[0]->mes_mes.'/'.$yperiodo_id),
                                    'FECHA_M'  => date('Y/m/d')    //date('d/m/Y')                                
                                    ]);
                toastr()->success('Archivo digital de la ficha del equipo actualizada.','¡Ok!',['positionClass' => 'toast-bottom-right']);

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
                        toastr()->success('Trx de archivo digital actualizada en Bitacora.','¡Ok!',['positionClass' => 'toast-bottom-right']);
                    else
                        toastr()->error('Error de Trx de archivo digital. Por favor volver a interlo.','Ups!',['positionClass' => 'toast-bottom-right']);
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
                    toastr()->success('Trx de archivo digital actualizada en Bitacora.','¡Ok!',['positionClass' => 'toast-bottom-right']);
                }   /************ Bitacora termina *************************************/         
            }       /************ Valida archivo digital *******************************/     
        }
        return redirect()->route('verficha');
    }

    public function actionBorrarFicha($id){
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
        $regficha       = regfichaModel::where('EQ_ID',$id);
        //              ->find('RUBRO_ID',$id);
        if($regficha->count() <= 0)
            toastr()->error('No existe ficha del equipo de futbol.','¡Por favor volver a intentar!',['positionClass' => 'toast-bottom-right']);
        else{        
            $regficha->delete();
            toastr()->success('Ficha del equipo de futbol eliminada.','¡Ok!',['positionClass' => 'toast-bottom-right']);

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
                    toastr()->success('Trx de baja de ficha registrada en Bitacora.','¡Ok!',['positionClass' => 'toast-bottom-right']);
                else
                    toastr()->error('Error en Trx de baja de ficha. Por favor volver a interlo.','Ups!',['positionClass' => 'toast-bottom-right']);
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
                toastr()->success('Trx de eliminar ficha registrada en Bitacora.','¡Ok!',['positionClass' => 'toast-bottom-right']);
            }   /************ Bitacora termina *************************************/                 
        }       /************* Termina de eliminar  la IAP **********************************/
        return redirect()->route('verficha');
    }    

    // exportar a formato catalogo de fichas a formato excel
    public function exportFichaExcel(){
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

        $regbitacora = regBitacoraModel::select('PERIODO_ID','MES_ID', 'PROCESO_ID', 'FUNCION_ID', 'TRX_ID', 'FOLIO', 
                       'NO_VECES', 'FECHA_REG', 'IP', 'LOGIN', 'FECHA_M', 'IP_M', 'LOGIN_M')
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
               toastr()->success('Trx de exportar fichas de equipos registrada en Bitacora.','¡Ok!',['positionClass' => 'toast-bottom-right']);
            else
               toastr()->error('Error de Trx de exportar fichas de equipos. Por favor volver a interlo.','Ups!',['positionClass' => 'toast-bottom-right']);
        }else{                   
            //*********** Obtine el no. de veces *****************************
            $xno_veces = regBitacoraModel::where(['PERIODO_ID' => $xperiodo_id,'MES_ID' => $xmes_id, 
                         'PROCESO_ID' => $xproceso_id, 'FUNCION_ID' => $xfuncion_id, 'TRX_ID' => $xtrx_id,'FOLIO' => $id])
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
            toastr()->success('Trx de exportar fichas de equipos registrada en Bitacora.','¡Ok!',['positionClass' => 'toast-bottom-right']);
        }   /************ Bitacora termina *************************************/  
        return Excel::download(new ExcelExportFichas, 'Fichas_equipos_'.date('d-m-Y').'.xlsx');
    } 

    // exportar a formato PDF
    public function exportFichasPdf(){
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

        $regmunicipio = regMunicipioModel::join('CASMEX_CAT_REGIONES','CASMEX_CAT_REGIONES.REGION_ID','=', 
                                                                      'CASMEX_CAT_MUNICIPIOS_SEDESEM.REGIONID')
                        ->select( 'CASMEX_CAT_REGIONES.REGION_ID',
                                  'CASMEX_CAT_REGIONES.DESC_REGION',
                                  'CASMEX_CAT_REGIONES.ROMA_REGION',
                                  'CASMEX_CAT_MUNICIPIOS_SEDESEM.MUNICIPIOID',
                                  'CASMEX_CAT_MUNICIPIOS_SEDESEM.MUNICIPIONOMBRE AS MUNICIPIO')
                        ->wherein('CASMEX_CAT_MUNICIPIOS_SEDESEM.ENTIDADFEDERATIVAID',[15])
                        ->orderBy('CASMEX_CAT_MUNICIPIOS_SEDESEM.ENTIDADFEDERATIVAID','DESC')
                        ->orderBy('CASMEX_CAT_MUNICIPIOS_SEDESEM.MUNICIPIONOMBRE','DESC')
                        ->get();
        $regcategoria = regCategoriasModel::select('CATE_ID','CATE_DESC')
                        ->orderBy('CATE_ID','asc')
                        ->get(); 
        //$regregiones= regRegionesModel::select('REGION_ID','DESC_REGION','ROMA_REGION')
        //              ->orderBy('REGION_ID','asc')
        //              ->get();   
        $regperiodos  = RegPeriodosModel::select('PERIODO_ID', 'PERIODO_DESC')
                        ->get();         
        $regmeses     = regMesesModel::select('MES_ID','MES_DESC')
                        ->get();      
        $regdias      = regDiasModel::select('DIA_ID','DIA_DESC')
                        ->get();                      
        $regficha     = regfichaModel::select('EQ_ID','EQ_DESC','EQ_NOMBRES_REP','EQ_AP_REP','EQ_AM_REP', 
                        'EQ_NOMBRECOMP_REP','EQ_CURP_REP','EQ_TEL_REP','EQ_EMAIL_REP','EQ_RAMA',  
                        'EQ_ARC1','EQ_ARC2','EQ_STATUS1','EQ_STATUS2','CATE_ID','MUNICIPIO_ID','REGION_ID',  
                        'FEC_REG','FEC_REG2','IP','LOGIN','FECHA_M','FECHA_M2','IP_M','LOGIN_M')
                        ->orderBy('EQ_ID','ASC')
                        ->get();                               
        if($regficha->count() <= 0){
            toastr()->error('No existen registros de fichas de equipos.','Uppss!',['positionClass' => 'toast-bottom-right']);
        }
        $pdf = PDF::loadView('sicinar.pdf.fichasPDF',compact('nombre','usuario','regficha','regmunicipio','regcategoria','regregiones','regperiodos','regmeses','regdias'));
        //$options = new Options();
        //$options->set('defaultFont', 'Courier');
        //$pdf->set_option('defaultFont', 'Courier');
        $pdf->setPaper('A4', 'landscape');      
        //$pdf->set('defaultFont', 'Courier');          
        //$pdf->setPaper('A4','portrait');

        // Output the generated PDF to Browser
        return $pdf->stream('FichasDeEquipos');
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

        $regtotxedo=regfichaModel::join('CASMEX_CAT_ENTIDADES_FED',[['CASMEX_CAT_ENTIDADES_FED.ENTIDADFEDERATIVA_ID','=','CASMEX_OSC.ENTIDADFEDERATIVA_ID'],['CASMEX_OSC.OSC_ID','<>',0]])
                         ->selectRaw('COUNT(*) AS TOTALXEDO')
                               ->get();

        $regficha=regfichaModel::join('CASMEX_CAT_ENTIDADES_FED',[['CASMEX_CAT_ENTIDADES_FED.ENTIDADFEDERATIVA_ID','=','CASMEX_OSC.ENTIDADFEDERATIVA_ID'],['CASMEX_OSC.OSC_ID','<>',0]])
                      ->selectRaw('CASMEX_OSC.ENTIDADFEDERATIVA_ID, CASMEX_CAT_ENTIDADES_FED.ENTIDADFEDERATIVA_DESC AS ESTADO, COUNT(*) AS TOTAL')
                        ->groupBy('CASMEX_OSC.ENTIDADFEDERATIVA_ID', 'CASMEX_CAT_ENTIDADES_FED.ENTIDADFEDERATIVA_DESC')
                        ->orderBy('CASMEX_OSC.ENTIDADFEDERATIVA_ID','asc')
                        ->get();
        //$procesos = procesosModel::join('SCI_TIPO_PROCESO','SCI_PROCESOS.CVE_TIPO_PROC','=','SCI_TIPO_PROCESO.CVE_TIPO_PROC')
        //    ->selectRaw('SCI_TIPO_PROCESO.DESC_TIPO_PROC AS TIPO, COUNT(SCI_PROCESOS.CVE_TIPO_PROC) AS TOTAL')
        //    ->groupBy('SCI_TIPO_PROCESO.DESC_TIPO_PROC')
        //    ->get();
        //dd($procesos);
        return view('sicinar.numeralia.iapxedo',compact('regficha','regtotxedo','nombre','usuario','rango'));
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
        $regbitatxmes=regBitacoraModel::join('CASMEX_CAT_PROCESOS' ,'CASMEX_CAT_PROCESOS.PROCESO_ID' ,'=','CASMEX_BITACORA.PROCESO_ID')
                                      ->join('CASMEX_CAT_FUNCIONES','CASMEX_CAT_FUNCIONES.FUNCION_ID','=','CASMEX_BITACORA.FUNCION_ID')
                                      ->join('CASMEX_CAT_TRX'      ,'CASMEX_CAT_TRX.TRX_ID'          ,'=','CASMEX_BITACORA.TRX_ID')
                                      ->join('CASMEX_CAT_MESES'    ,'CASMEX_CAT_MESES.MES_ID'        ,'=','CASMEX_BITACORA.MES_ID')
                                 ->select(   'CASMEX_BITACORA.MES_ID','CASMEX_CAT_MESES.MES_DESC')
                                 ->selectRaw('COUNT(*) AS TOTALGENERAL')
                                 ->where(    'CASMEX_BITACORA.PERIODO_ID',$request->periodo_id)
                                 ->groupBy(  'CASMEX_BITACORA.MES_ID','CASMEX_CAT_MESES.MES_DESC')
                                 ->orderBy(  'CASMEX_BITACORA.MES_ID','asc')
                                 ->get();        
        $regbitatot=regBitacoraModel::join('CASMEX_CAT_PROCESOS','CASMEX_CAT_PROCESOS.PROCESO_ID' ,'=','CASMEX_BITACORA.PROCESO_ID')
                                   ->join('CASMEX_CAT_FUNCIONES','CASMEX_CAT_FUNCIONES.FUNCION_ID','=','CASMEX_BITACORA.FUNCION_ID')
                                   ->join('CASMEX_CAT_TRX'      ,'CASMEX_CAT_TRX.TRX_ID'          ,'=','CASMEX_BITACORA.TRX_ID')
                         ->selectRaw('SUM(CASE WHEN CASMEX_BITACORA.MES_ID = 1 THEN 1 END) AS M01')  
                         ->selectRaw('SUM(CASE WHEN CASMEX_BITACORA.MES_ID = 2 THEN 1 END) AS M02')
                         ->selectRaw('SUM(CASE WHEN CASMEX_BITACORA.MES_ID = 3 THEN 1 END) AS M03')
                         ->selectRaw('SUM(CASE WHEN CASMEX_BITACORA.MES_ID = 4 THEN 1 END) AS M04')
                         ->selectRaw('SUM(CASE WHEN CASMEX_BITACORA.MES_ID = 5 THEN 1 END) AS M05')
                         ->selectRaw('SUM(CASE WHEN CASMEX_BITACORA.MES_ID = 6 THEN 1 END) AS M06')
                         ->selectRaw('SUM(CASE WHEN CASMEX_BITACORA.MES_ID = 7 THEN 1 END) AS M07')
                         ->selectRaw('SUM(CASE WHEN CASMEX_BITACORA.MES_ID = 8 THEN 1 END) AS M08')
                         ->selectRaw('SUM(CASE WHEN CASMEX_BITACORA.MES_ID = 9 THEN 1 END) AS M09')
                         ->selectRaw('SUM(CASE WHEN CASMEX_BITACORA.MES_ID =10 THEN 1 END) AS M10')
                         ->selectRaw('SUM(CASE WHEN CASMEX_BITACORA.MES_ID =11 THEN 1 END) AS M11')
                         ->selectRaw('SUM(CASE WHEN CASMEX_BITACORA.MES_ID =12 THEN 1 END) AS M12')
                         ->selectRaw('COUNT(*) AS TOTALGENERAL')
                         ->where(    'CASMEX_BITACORA.PERIODO_ID',$request->periodo_id)
                         ->get();

        $regbitacora=regBitacoraModel::join('CASMEX_CAT_PROCESOS' ,'CASMEX_CAT_PROCESOS.PROCESO_ID' ,'=','CASMEX_BITACORA.PROCESO_ID')
                                     ->join('CASMEX_CAT_FUNCIONES','CASMEX_CAT_FUNCIONES.FUNCION_ID','=','CASMEX_BITACORA.FUNCION_ID')
                                     ->join('CASMEX_CAT_TRX'      ,'CASMEX_CAT_TRX.TRX_ID'          ,'=','CASMEX_BITACORA.TRX_ID')
                    ->select(   'CASMEX_BITACORA.PERIODO_ID', 'CASMEX_BITACORA.PROCESO_ID','CASMEX_CAT_PROCESOS.PROCESO_DESC', 
                                'CASMEX_BITACORA.FUNCION_ID', 'CASMEX_CAT_FUNCIONES.FUNCION_DESC', 
                                'CASMEX_BITACORA.TRX_ID',     'CASMEX_CAT_TRX.TRX_DESC')
                    ->selectRaw('SUM(CASE WHEN CASMEX_BITACORA.MES_ID = 1 THEN 1 END) AS ENE')  
                    ->selectRaw('SUM(CASE WHEN CASMEX_BITACORA.MES_ID = 2 THEN 1 END) AS FEB')
                    ->selectRaw('SUM(CASE WHEN CASMEX_BITACORA.MES_ID = 3 THEN 1 END) AS MAR')
                    ->selectRaw('SUM(CASE WHEN CASMEX_BITACORA.MES_ID = 4 THEN 1 END) AS ABR')
                    ->selectRaw('SUM(CASE WHEN CASMEX_BITACORA.MES_ID = 5 THEN 1 END) AS MAY')
                    ->selectRaw('SUM(CASE WHEN CASMEX_BITACORA.MES_ID = 6 THEN 1 END) AS JUN')
                    ->selectRaw('SUM(CASE WHEN CASMEX_BITACORA.MES_ID = 7 THEN 1 END) AS JUL')
                    ->selectRaw('SUM(CASE WHEN CASMEX_BITACORA.MES_ID = 8 THEN 1 END) AS AGO')
                    ->selectRaw('SUM(CASE WHEN CASMEX_BITACORA.MES_ID = 9 THEN 1 END) AS SEP')
                    ->selectRaw('SUM(CASE WHEN CASMEX_BITACORA.MES_ID =10 THEN 1 END) AS OCT')
                    ->selectRaw('SUM(CASE WHEN CASMEX_BITACORA.MES_ID =11 THEN 1 END) AS NOV')
                    ->selectRaw('SUM(CASE WHEN CASMEX_BITACORA.MES_ID =12 THEN 1 END) AS DIC')                   
                    ->selectRaw('COUNT(*) AS SUMATOTAL')
                    ->where(    'CASMEX_BITACORA.PERIODO_ID',$request->periodo_id)
                    ->groupBy(  'CASMEX_BITACORA.PERIODO_ID','CASMEX_BITACORA.PROCESO_ID','CASMEX_CAT_PROCESOS.PROCESO_DESC',
                                'CASMEX_BITACORA.FUNCION_ID','CASMEX_CAT_FUNCIONES.FUNCION_DESC', 
                                'CASMEX_BITACORA.TRX_ID'    ,'CASMEX_CAT_TRX.TRX_DESC')
                    ->orderBy(  'CASMEX_BITACORA.PERIODO_ID','asc')
                    ->orderBy(  'CASMEX_BITACORA.PROCESO_ID','asc')
                    ->orderBy(  'CASMEX_BITACORA.FUNCION_ID','asc')
                    ->orderBy(  'CASMEX_BITACORA.TRX_ID'    ,'asc')
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

        $regficha=regfichaModel::join(  'CASMEX_CAT_ENTIDADES_FED',     'CASMEX_CAT_ENTIDADES_FED.ENTIDADFEDERATIVA_ID','=',
                                                                'CASMEX_OSC.ENTIDADFEDERATIVA_ID')
                           ->join(  'CASMEX_CAT_MUNICIPIOS_SEDESEM',[['CASMEX_CAT_MUNICIPIOS_SEDESEM.ENTIDADFEDERATIVAID','=',
                                                                'CASMEX_OSC.ENTIDADFEDERATIVA_ID'],
                                                               ['CASMEX_CAT_MUNICIPIOS_SEDESEM.MUNICIPIOID'        ,'=',
                                                                'CASMEX_OSC.MUNICIPIO_ID'],
                                                               ['CASMEX_OSC.OSC_ID','<>',0]
                                                              ])
                        ->select(   'CASMEX_OSC.ENTIDADFEDERATIVA_ID','CASMEX_CAT_ENTIDADES_FED.ENTIDADFEDERATIVA_DESC AS ENTIDAD',
                                    'CASMEX_OSC.MUNICIPIO_ID',        'CASMEX_CAT_MUNICIPIOS_SEDESEM.MUNICIPIONOMBRE AS MUNICIPIO', 
                                    'CASMEX_CAT_MUNICIPIOS_SEDESEM.GEOREF_CABMPIO_LATDECIMAL', 
                                    'CASMEX_CAT_MUNICIPIOS_SEDESEM.GEOREF_CABMPIO_LONGDECIMAL')
                        ->selectRaw('COUNT(*) AS TOTAL')
                        ->groupBy(  'CASMEX_OSC.ENTIDADFEDERATIVA_ID','CASMEX_CAT_ENTIDADES_FED.ENTIDADFEDERATIVA_DESC',
                                    'CASMEX_OSC.MUNICIPIO_ID',        'CASMEX_CAT_MUNICIPIOS_SEDESEM.MUNICIPIONOMBRE', 
                                    'CASMEX_CAT_MUNICIPIOS_SEDESEM.GEOREF_CABMPIO_LATDECIMAL', 
                                    'CASMEX_CAT_MUNICIPIOS_SEDESEM.GEOREF_CABMPIO_LONGDECIMAL')
                        ->orderBy('CASMEX_OSC.ENTIDADFEDERATIVA_ID','asc')
                        ->orderBy('CASMEX_OSC.MUNICIPIO_ID'        ,'asc')
                        ->get();
        //dd($procesos);
        return view('sicinar.numeralia.mapaxmpio',compact('regficha','nombre','usuario','rango'));
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

        $regtotxmpio=regfichaModel::join('CASMEX_CAT_MUNICIPIOS_SEDESEM',[['CASMEX_CAT_MUNICIPIOS_SEDESEM.ENTIDADFEDERATIVAID','=',15],
                                                            ['CASMEX_CAT_MUNICIPIOS_SEDESEM.MUNICIPIOID','=','CASMEX_OSC.MUNICIPIO_ID'],['CASMEX_OSC.OSC_ID','<>',0]])
                         ->selectRaw('COUNT(*) AS TOTALXMPIO')
                               ->get();
        $regficha=regfichaModel::join('CASMEX_CAT_MUNICIPIOS_SEDESEM',[['CASMEX_CAT_MUNICIPIOS_SEDESEM.ENTIDADFEDERATIVAID','=',15],
                                                            ['CASMEX_CAT_MUNICIPIOS_SEDESEM.MUNICIPIOID','=','CASMEX_OSC.MUNICIPIO_ID'],['CASMEX_OSC.OSC_ID','<>',0]])
                      ->selectRaw('CASMEX_OSC.MUNICIPIO_ID, CASMEX_CAT_MUNICIPIOS_SEDESEM.MUNICIPIONOMBRE AS MUNICIPIO,COUNT(*) AS TOTAL')
                        ->groupBy('CASMEX_OSC.MUNICIPIO_ID', 'CASMEX_CAT_MUNICIPIOS_SEDESEM.MUNICIPIONOMBRE')
                        ->orderBy('CASMEX_OSC.MUNICIPIO_ID','asc')
                        ->get();
        //dd($procesos);
        return view('sicinar.numeralia.oscxmpio',compact('regficha','regtotxmpio','nombre','usuario','rango'));
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

        $regtotxrubro=regfichaModel::join('CASMEX_CAT_RUBROS','CASMEX_CAT_RUBROS.CATE_ID','=','CASMEX_OSC.CATE_ID')
                      ->selectRaw('COUNT(*) AS TOTALXRUBRO')
                            ->get();
        $regficha=regfichaModel::join('CASMEX_CAT_RUBROS','CASMEX_CAT_RUBROS.CATE_ID','=','CASMEX_OSC.CATE_ID')
                      ->selectRaw('CASMEX_OSC.CATE_ID,  CASMEX_CAT_RUBROS.CATE_DESC AS RUBRO, COUNT(*) AS TOTAL')
                        ->groupBy('CASMEX_OSC.CATE_ID','CASMEX_CAT_RUBROS.CATE_DESC')
                        ->orderBy('CASMEX_OSC.CATE_ID','asc')
                        ->get();
        //$procesos = procesosModel::join('SCI_TIPO_PROCESO','SCI_PROCESOS.CVE_TIPO_PROC','=','SCI_TIPO_PROCESO.CVE_TIPO_PROC')
        //    ->selectRaw('SCI_TIPO_PROCESO.DESC_TIPO_PROC AS TIPO, COUNT(SCI_PROCESOS.CVE_TIPO_PROC) AS TOTAL')
        //    ->groupBy('SCI_TIPO_PROCESO.DESC_TIPO_PROC')
        //    ->get();
        //dd($procesos);
        return view('sicinar.numeralia.oscxrubro',compact('regficha','regtotxrubro','nombre','usuario','rango'));
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

        $regtotxrubro=regfichaModel::join('CASMEX_CAT_RUBROS','CASMEX_CAT_RUBROS.CATE_ID','=','CASMEX_OSC.CATE_ID')
                      ->selectRaw('COUNT(*) AS TOTALXRUBRO')
                            ->get();
        $regficha=regfichaModel::join('CASMEX_CAT_RUBROS','CASMEX_CAT_RUBROS.CATE_ID','=','CASMEX_OSC.CATE_ID')
                      ->selectRaw('CASMEX_OSC.CATE_ID,  CASMEX_CAT_RUBROS.CATE_DESC AS RUBRO, COUNT(*) AS TOTAL')
                        ->groupBy('CASMEX_OSC.CATE_ID','CASMEX_CAT_RUBROS.CATE_DESC')
                        ->orderBy('CASMEX_OSC.CATE_ID','asc')
                        ->get();
        //$procesos = procesosModel::join('SCI_TIPO_PROCESO','SCI_PROCESOS.CVE_TIPO_PROC','=','SCI_TIPO_PROCESO.CVE_TIPO_PROC')
        //    ->selectRaw('SCI_TIPO_PROCESO.DESC_TIPO_PROC AS TIPO, COUNT(SCI_PROCESOS.CVE_TIPO_PROC) AS TOTAL')
        //    ->groupBy('SCI_TIPO_PROCESO.DESC_TIPO_PROC')
        //    ->get();
        //dd($procesos);
        return view('sicinar.numeralia.graficadeprueba',compact('regficha','regtotxrubro','nombre','usuario','rango'));
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

        $regtotxrubro=regfichaModel::join('CASMEX_CAT_RUBROS','CASMEX_CAT_RUBROS.CATE_ID','=','CASMEX_OSC.CATE_ID')
                      ->selectRaw('COUNT(*) AS TOTALXRUBRO')
                            ->get();

        $regficha=regfichaModel::join('CASMEX_CAT_RUBROS','CASMEX_CAT_RUBROS.CATE_ID','=','CASMEX_OSC.CATE_ID')
                      ->selectRaw('CASMEX_OSC.CATE_ID,  CASMEX_CAT_RUBROS.CATE_DESC AS RUBRO, COUNT(*) AS TOTAL')
                        ->groupBy('CASMEX_OSC.CATE_ID','CASMEX_CAT_RUBROS.CATE_DESC')
                        ->orderBy('CASMEX_OSC.CATE_ID','asc')
                        ->get();
        //$procesos = procesosModel::join('SCI_TIPO_PROCESO','SCI_PROCESOS.CVE_TIPO_PROC','=','SCI_TIPO_PROCESO.CVE_TIPO_PROC')
        //    ->selectRaw('SCI_TIPO_PROCESO.DESC_TIPO_PROC AS TIPO, COUNT(SCI_PROCESOS.CVE_TIPO_PROC) AS TOTAL')
        //    ->groupBy('SCI_TIPO_PROCESO.DESC_TIPO_PROC')
        //    ->get();
        //dd($procesos);
        return view('sicinar.numeralia.mapasdeprueba',compact('regficha','regtotxrubro','nombre','usuario','rango'));
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

        $regtotxrubro=regfichaModel::join('CASMEX_CAT_RUBROS','CASMEX_CAT_RUBROS.CATE_ID','=','CASMEX_OSC.CATE_ID')
                      ->selectRaw('COUNT(*) AS TOTALXRUBRO')
                            ->get();

        $regficha=regfichaModel::join('CASMEX_CAT_RUBROS','CASMEX_CAT_RUBROS.CATE_ID','=','CASMEX_OSC.CATE_ID')
                      ->selectRaw('CASMEX_OSC.CATE_ID,  CASMEX_CAT_RUBROS.CATE_DESC AS RUBRO, COUNT(*) AS TOTAL')
                        ->groupBy('CASMEX_OSC.CATE_ID','CASMEX_CAT_RUBROS.CATE_DESC')
                        ->orderBy('CASMEX_OSC.CATE_ID','asc')
                        ->get();
        //$procesos = procesosModel::join('SCI_TIPO_PROCESO','SCI_PROCESOS.CVE_TIPO_PROC','=','SCI_TIPO_PROCESO.CVE_TIPO_PROC')
        //    ->selectRaw('SCI_TIPO_PROCESO.DESC_TIPO_PROC AS TIPO, COUNT(SCI_PROCESOS.CVE_TIPO_PROC) AS TOTAL')
        //    ->groupBy('SCI_TIPO_PROCESO.DESC_TIPO_PROC')
        //    ->get();
        //dd($procesos);
        return view('sicinar.numeralia.mapasdeprueba2',compact('regficha','regtotxrubro','nombre','usuario','rango'));
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

        $regtotxrubro=regfichaModel::join('CASMEX_CAT_RUBROS','CASMEX_CAT_RUBROS.CATE_ID','=','CASMEX_OSC.CATE_ID')
                      ->selectRaw('COUNT(*) AS TOTALXRUBRO')
                            ->get();
        $regficha=regfichaModel::join('CASMEX_CAT_RUBROS','CASMEX_CAT_RUBROS.CATE_ID','=','CASMEX_OSC.CATE_ID')
                      ->selectRaw('CASMEX_OSC.CATE_ID,  CASMEX_CAT_RUBROS.CATE_DESC AS RUBRO, COUNT(*) AS TOTAL')
                        ->groupBy('CASMEX_OSC.CATE_ID','CASMEX_CAT_RUBROS.CATE_DESC')
                        ->orderBy('CASMEX_OSC.CATE_ID','asc')
                        ->get();
        //dd($procesos);
        return view('sicinar.numeralia.mapasdeprueba3',compact('regficha','regtotxrubro','nombre','usuario','rango'));
    }

}
