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
use App\regPadronModel;

// Exportar a excel 
use App\Exports\ExcelExportEquipo;
use Maatwebsite\Excel\Facades\Excel;
// Exportar a pdf
use PDF;
//use Options;

class regequipoController extends Controller
{

    public function actionBuscarEquipo(Request $request)
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
            $regpadron= regPadronModel::select('PERIODO_ID','FOLIO','EQ_ID','PRIMER_APELLIDO',
                        'SEGUNDO_APELLIDO','NOMBRES','NOMBRE_COMPLETO','CURP','FECHA_NACIMIENTO','EDAD',
                        'FECHA_NACIMIENTO2','FECHA_NACIMIENTO3','PERIODO_ID2','MES_ID2','DIA_ID2','SEXO',
                        'RFC','ID_OFICIAL','DOMICILIO','COLONIA','CP','ENTRE_CALLE','Y_CALLE','OTRA_REFERENCIA',
                        'TP_ID_OFICIAL','NUM_EXT','NUM_INT',
                        'TELEFONO','CELULAR','E_MAIL','ENTIDAD_NAC_ID','ENTIDAD_FED_ID','MUNICIPIO_ID',
                        'LOCALIDAD_ID','LOCALIDAD','EDO_CIVIL_ID','GRADO_ESTUDIOS_ID','FECHA_INGRESO','FECHA_INGRESO2',
                        'ARC_1','ARC_2','ARC_3','ARC_4','ARC_5','ARC_6','ARC_7','ARC_8',
                        'STATUS_1','STATUS_2','FECHA_REG','FECHA_REG2','IP','LOGIN','FECHA_M','FECHA_M2','IP_M','LOGIN_M')
                        ->orderBy('PERIODO_ID','asc')
                        ->orderBy('EQ_ID'     ,'asc')              
                        ->get();    
            $regficha = regfichaModel::orderBy('EQ_ID', 'ASC')
                        ->name($name)     //Metodos personalizados es equvalente a ->where('IAP_DESC', 'LIKE', "%$name%");
                        ->email($email)         //Metodos personalizados
                        ->bio($bio)             //Metodos personalizados
                        ->paginate(30);
        }else{
            $regpadron= regPadronModel::select('PERIODO_ID','FOLIO','EQ_ID','PRIMER_APELLIDO',
                        'SEGUNDO_APELLIDO','NOMBRES','NOMBRE_COMPLETO','CURP','FECHA_NACIMIENTO','EDAD',
                        'FECHA_NACIMIENTO2','FECHA_NACIMIENTO3','PERIODO_ID2','MES_ID2','DIA_ID2','SEXO',
                        'RFC','ID_OFICIAL','DOMICILIO','COLONIA','CP','ENTRE_CALLE','Y_CALLE','OTRA_REFERENCIA',
                        'TP_ID_OFICIAL','NUM_EXT','NUM_INT',
                        'TELEFONO','CELULAR','E_MAIL','ENTIDAD_NAC_ID','ENTIDAD_FED_ID','MUNICIPIO_ID',
                        'LOCALIDAD_ID','LOCALIDAD','EDO_CIVIL_ID','GRADO_ESTUDIOS_ID','FECHA_INGRESO','FECHA_INGRESO2',
                        'ARC_1','ARC_2','ARC_3','ARC_4','ARC_5','ARC_6','ARC_7','ARC_8',
                        'STATUS_1','STATUS_2','FECHA_REG','FECHA_REG2','IP','LOGIN','FECHA_M','FECHA_M2','IP_M','LOGIN_M')
                        ->where(  'EQ_ID'     ,$arbol_id)
                        ->orderBy('PERIODO_ID','asc')
                        ->orderBy('EQ_ID'     ,'asc')              
                        ->get();                
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
        return view('sicinar.fichas_equipos.verEquipos', compact('nombre','usuario','regficha','regmunicipio','regcategoria','regperiodos','regmeses','regdias','regpadron','regregiones'));
    }

    public function actionVerEquipo(){
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
            $regpadron= regPadronModel::select('PERIODO_ID','FOLIO','EQ_ID','PRIMER_APELLIDO',
                        'SEGUNDO_APELLIDO','NOMBRES','NOMBRE_COMPLETO','CURP','FECHA_NACIMIENTO','EDAD',
                        'FECHA_NACIMIENTO2','FECHA_NACIMIENTO3','PERIODO_ID2','MES_ID2','DIA_ID2','SEXO',
                        'RFC','ID_OFICIAL','DOMICILIO','COLONIA','CP','ENTRE_CALLE','Y_CALLE','OTRA_REFERENCIA',
                        'TP_ID_OFICIAL','NUM_EXT','NUM_INT',
                        'TELEFONO','CELULAR','E_MAIL','ENTIDAD_NAC_ID','ENTIDAD_FED_ID','MUNICIPIO_ID',
                        'LOCALIDAD_ID','LOCALIDAD','EDO_CIVIL_ID','GRADO_ESTUDIOS_ID','FECHA_INGRESO','FECHA_INGRESO2',
                        'ARC_1','ARC_2','ARC_3','ARC_4','ARC_5','ARC_6','ARC_7','ARC_8',
                        'STATUS_1','STATUS_2','FECHA_REG','FECHA_REG2','IP','LOGIN','FECHA_M','FECHA_M2','IP_M','LOGIN_M')
                        ->orderBy('PERIODO_ID','asc')
                        ->orderBy('EQ_ID'     ,'asc')              
                        ->get();                                                                           
            $regficha = regfichaModel::select('EQ_ID','EQ_DESC','EQ_NOMBRES_REP','EQ_AP_REP','EQ_AM_REP', 
                        'EQ_NOMBRECOMP_REP','EQ_CURP_REP','EQ_TEL_REP','EQ_EMAIL_REP','EQ_RAMA',  
                        'EQ_ARC1','EQ_ARC2','EQ_STATUS1','EQ_STATUS2','CATE_ID','MUNICIPIO_ID','REGION_ID',  
                        'FEC_REG','FEC_REG2','IP','LOGIN','FECHA_M','FECHA_M2','IP_M','LOGIN_M')
                        ->orderBy('EQ_ID','ASC')
                        ->paginate(30);
        }else{
            $regpadron= regPadronModel::select('PERIODO_ID','FOLIO','EQ_ID','PRIMER_APELLIDO',
                        'SEGUNDO_APELLIDO','NOMBRES','NOMBRE_COMPLETO','CURP','FECHA_NACIMIENTO','EDAD',
                        'FECHA_NACIMIENTO2','FECHA_NACIMIENTO3','PERIODO_ID2','MES_ID2','DIA_ID2','SEXO',
                        'RFC','ID_OFICIAL','DOMICILIO','COLONIA','CP','ENTRE_CALLE','Y_CALLE','OTRA_REFERENCIA',
                        'TP_ID_OFICIAL','NUM_EXT','NUM_INT',
                        'TELEFONO','CELULAR','E_MAIL','ENTIDAD_NAC_ID','ENTIDAD_FED_ID','MUNICIPIO_ID',
                        'LOCALIDAD_ID','LOCALIDAD','EDO_CIVIL_ID','GRADO_ESTUDIOS_ID','FECHA_INGRESO','FECHA_INGRESO2',
                        'ARC_1','ARC_2','ARC_3','ARC_4','ARC_5','ARC_6','ARC_7','ARC_8',
                        'STATUS_1','STATUS_2','FECHA_REG','FECHA_REG2','IP','LOGIN','FECHA_M','FECHA_M2','IP_M','LOGIN_M')
                        ->where(  'EQ_ID'     ,$arbol_id)
                        ->orderBy('PERIODO_ID','asc')
                        ->orderBy('EQ_ID'     ,'asc')              
                        ->get();                         
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
        return view('sicinar.fichas_equipos.verEquipos',compact('nombre','usuario','arbol_id','regficha','regmunicipio','regcategoria','regperiodos','regmeses','regdias','regpadron','regregiones'));
    }

    public function actionNuevoEquipo(){
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
        return view('sicinar.fichas_equipos.nuevoEquipo',compact('regcategoria','regmunicipio','regficha','nombre','usuario','regperiodos','regmeses','regdias'));
    }

    public function actionAltaNuevoEquipo(Request $request){
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
        return redirect()->route('verequipo');
    }

    public function actionEditarEquipo($id){
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

    public function actionActualizarEquipo(fichaRequest $request, $id){
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
        return redirect()->route('verequipo');
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
        return redirect()->route('verequipo');
    }

    public function actionBorrarEquipo($id){
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
        return redirect()->route('verequipo');
    }    

    // exportar a formato catalogo de fichas a formato excel
    public function exportEquipoExcel($id){
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
        return Excel::download(new ExcelexportEquipo, 'equipo_'.date('d-m-Y').'.xlsx');
    } 

    // exportar a formato PDF 
    public function exportEquipoPdf($id){
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

        // **************** actualizar ******************************
        $regfichita = regfichaModel::where('EQ_ID',$id);
        if($regfichita->count() <= 0)
            toastr()->error('No existe equipo.','¡Por favor volver a intentar!',['positionClass' => 'toast-bottom-right']);
        else{        

            $cerrado    ='S';      // Registro concluido
            $yperiodo_id= (int)date('Y');
            $ymes_id    = (int)date('m');
            $ydia_id    = (int)date('d');
            $mes        = regMesesModel::ObtMes($ymes_id);
            $dia        = regDiasModel::ObtDia($ydia_id);  

            $regfichota = regfichaModel::where('EQ_ID',$id)        
                          ->update([                
                                    'EQ_STATUS2' => $cerrado,

                                    'IP_M'     => $ip,
                                    'LOGIN_M'  => $nombre,
                                    'FECHA_M2' => trim($dia[0]->dia_desc.'/'.$mes[0]->mes_mes.'/'.$yperiodo_id),
                                    'FECHA_M'  => date('Y/m/d')    //date('d/m/Y')                                
                                    ]);
                toastr()->success('Registro de equipo concluido.','¡Ok!',['positionClass' => 'toast-bottom-right']);

                /************ Bitacora inicia *************************************/ 
                setlocale(LC_TIME, "spanish");        
                $xip          = session()->get('ip');
                $xperiodo_id  = (int)date('Y');
                $xprograma_id = 1;
                $xmes_id      = (int)date('m');
                $xproceso_id  =         3;
                $xfuncion_id  =      3001;
                $xtrx_id      =       146;    //Actualizar 
                $regbitacora = regBitacoraModel::select('PERIODO_ID','MES_ID','PROCESO_ID','FUNCION_ID','TRX_ID','FOLIO', 
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
                        toastr()->success('Trx de registro de equipo concluido en Bitacora.','¡Ok!',['positionClass' => 'toast-bottom-right']);
                    else
                        toastr()->error('Error de Trx de registro de equipo concluido. Por favor volver a interlo.','Ups!',['positionClass' => 'toast-bottom-right']);
                }else{                   
                    //*********** Obtine el no. de veces *****************************
                    $xno_veces = regBitacoraModel::where(['PERIODO_ID' => $xperiodo_id,'MES_ID' => $xmes_id,
                                'PROCESO_ID' => $xproceso_id, 'FUNCION_ID' => $xfuncion_id, 'TRX_ID' => $xtrx_id, 
                                'FOLIO' => $id])
                                ->max('NO_VECES');
                    $xno_veces = $xno_veces+1;                        
                    //*********** Termina de obtener el no de veces *****************************         
                    $regbitacora =regBitacoraModel::select('NO_VECES','IP_M','LOGIN_M','FECHA_M')
                                  ->where(['PERIODO_ID' => $xperiodo_id,'MES_ID'=> $xmes_id,'PROCESO_ID'=> $xproceso_id,
                                           'FUNCION_ID' => $xfuncion_id,'TRX_ID'=> $xtrx_id,'FOLIO'     => $id])
                                   ->update([
                                         'NO_VECES' => $regbitacora->NO_VECES = $xno_veces,
                                         'IP_M'     => $regbitacora->IP       = $ip,
                                         'LOGIN_M'  => $regbitacora->LOGIN_M  = $nombre,
                                         'FECHA_M'  => $regbitacora->FECHA_M  = date('Y/m/d')  //date('d/m/Y')
                                        ]);
                    toastr()->success('Trx de registro de equipo concluido en Bitacora.','¡Ok!',['positionClass' => 'toast-bottom-right']);
                }   /************ Bitacora termina *************************************/         
        }       /************ actualizar *******************************/     

        /************ Bitacora inicia *************************************/ 
        setlocale(LC_TIME, "spanish");        
        $xip          = session()->get('ip');
        $xperiodo_id  = (int)date('Y');
        $xprograma_id = 1;
        $xmes_id      = (int)date('m');
        $xproceso_id  =         3;
        $xfuncion_id  =      3001;
        $xtrx_id      =       143;       //Exportar a formato PDF

        $regbitacora = regBitacoraModel::select('PERIODO_ID', 'PROGRAMA_ID', 'MES_ID', 'PROCESO_ID', 'FUNCION_ID', 
                       'TRX_ID', 'FOLIO', 'NO_VECES', 'FECHA_REG', 'IP', 'LOGIN', 'FECHA_M', 'IP_M', 'LOGIN_M')
                       ->where(['PERIODO_ID' => $xperiodo_id, 'PROGRAMA_ID' => $xprograma_id, 'MES_ID' => $xmes_id, 
                                'PROCESO_ID' => $xproceso_id, 'FUNCION_ID' => $xfuncion_id, 'TRX_ID' => $xtrx_id, 
                                'FOLIO'      => $id])
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
               toastr()->error('Error de Trx de exportar a PDF. Por favor volver a interlo.','Ups!',['positionClass' => 'toast-bottom-right']);
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
        $regpadron    = regPadronModel::select('PERIODO_ID','FOLIO','EQ_ID','PRIMER_APELLIDO',
                        'SEGUNDO_APELLIDO','NOMBRES','NOMBRE_COMPLETO','CURP','FECHA_NACIMIENTO','EDAD',
                        'FECHA_NACIMIENTO2','FECHA_NACIMIENTO3','PERIODO_ID2','MES_ID2','DIA_ID2','SEXO',
                        'RFC','ID_OFICIAL','DOMICILIO','COLONIA','CP','ENTRE_CALLE','Y_CALLE','OTRA_REFERENCIA',
                        'TP_ID_OFICIAL','NUM_EXT','NUM_INT',
                        'TELEFONO','CELULAR','E_MAIL','ENTIDAD_NAC_ID','ENTIDAD_FED_ID','MUNICIPIO_ID',
                        'LOCALIDAD_ID','LOCALIDAD','EDO_CIVIL_ID','GRADO_ESTUDIOS_ID','FECHA_INGRESO','FECHA_INGRESO2',
                        'ARC_1','ARC_2','ARC_3','ARC_4','ARC_5','ARC_6','ARC_7','ARC_8',
                        'STATUS_1','STATUS_2','FECHA_REG','FECHA_REG2','IP','LOGIN','FECHA_M','FECHA_M2','IP_M','LOGIN_M')
                        ->where(  'EQ_ID','=',$id)
                        ->orderBy('NOMBRE_COMPLETO','asc')
                        ->get();                                  
        $regficha     = regfichaModel::select('EQ_ID','EQ_DESC','EQ_NOMBRES_REP','EQ_AP_REP','EQ_AM_REP', 
                        'EQ_NOMBRECOMP_REP','EQ_CURP_REP','EQ_TEL_REP','EQ_EMAIL_REP','EQ_RAMA',  
                        'EQ_ARC1','EQ_ARC2','EQ_STATUS1','EQ_STATUS2','CATE_ID','MUNICIPIO_ID','REGION_ID',  
                        'FEC_REG','FEC_REG2','IP','LOGIN','FECHA_M','FECHA_M2','IP_M','LOGIN_M')
                        ->where(  'EQ_ID','=',$id)
                        ->get();                               
        if($regficha->count() <= 0){
            toastr()->error('No existe equipo de futbol.','Uppss!',['positionClass' => 'toast-bottom-right']);
        }
        $pdf = PDF::loadView('sicinar.pdf.equipoPdf',compact('nombre','usuario','regficha','regmunicipio','regcategoria','regregiones','regperiodos','regmeses','regdias','regpadron'));
        //$options = new Options();
        //$options->set('defaultFont', 'Courier');
        //$pdf->set_option('defaultFont', 'Courier');
        $pdf->setPaper('A4', 'landscape');      
        //$pdf->set('defaultFont', 'Courier');          
        //$pdf->setPaper('A4','portrait');

        // Output the generated PDF to Browser
        return $pdf->stream('Equipo_de_futbol');
    }


}
