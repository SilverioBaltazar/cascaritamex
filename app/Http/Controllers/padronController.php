<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests\padronRequest;
use App\Http\Requests\padron1Request;
use App\Http\Requests\padron2Request;
use App\regFichaModel;
use App\regPadronModel;
use App\regBitacoraModel;
use App\regMunicipioModel;
use App\regEntidadesModel; 
use App\regServicioModel;
use App\regRubrosModel;
use App\regAniosModel;
use App\regMesesModel;
use App\regDiasModel;

// Exportar a excel 
use App\Exports\ExportPadronExcel;
use Maatwebsite\Excel\Facades\Excel;
// Exportar a pdf
use PDF;
//use Options;

class padronController extends Controller
{

    public function actionBuscarPadron(Request $request)
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

        $regentidades = regEntidadesModel::select('ENTIDADFED_ID','ENTIDADFED_DESC')
                        ->orderBy('ENTIDADFED_ID','asc')
                        ->get();
        $regmunicipio = regMunicipioModel::select('CASMEX_CAT_MUNICIPIOS_SEDESEM.MUNICIPIOID',
                                  'CASMEX_CAT_MUNICIPIOS_SEDESEM.MUNICIPIONOMBRE AS MUNICIPIO')
                        ->wherein('CASMEX_CAT_MUNICIPIOS_SEDESEM.ENTIDADFEDERATIVAID',[15])
                        ->orderBy('CASMEX_CAT_MUNICIPIOS_SEDESEM.MUNICIPIONOMBRE','ASC')
                        ->get();        
        $reganios     = regAniosModel::select('ANIO_ID','ANIO_DESC')
                        ->orderBy('ANIO_ID','ASC')
                        ->get(); 
        $regmeses     = regMesesModel::select('MES_ID','MES_DESC')
                        ->get();      
        $regdias      = regDiasModel::select('DIA_ID','DIA_DESC')
                        ->get();                           
        //$regservicios = regServicioModel::join('PE_CAT_RUBROS' ,'PE_CAT_RUBROS.RUBRO_ID', '=', 
        //                                                        'PE_CAT_SERVICIOS.RUBRO_ID')
        //                ->select('PE_CAT_SERVICIOS.SERVICIO_ID','PE_CAT_SERVICIOS.SERVICIO_DESC',
        //                         'PE_CAT_RUBROS.RUBRO_DESC')
        //                ->get();                          
        //**************************************************************//
        // ***** busqueda https://github.com/rimorsoft/Search-simple ***//
        // ***** video https://www.youtube.com/watch?v=bmtD9GUaszw   ***//                            
        //**************************************************************//
        //********* Validar rol de usuario **********************/
        $name    = $request->get('name');   
        $nameiap = $request->get('nameiap');           
        //$email = $request->get('email');  
        //$bio   = $request->get('bio');             
        if(session()->get('rango') !== '0'){    
            $regficha = regFichaModel::select('EQ_ID','EQ_DESC','EQ_STATUS1')
                        ->get();                        
            $regpadron= regPadronModel::join('CASMEX_FICHA_EQUIPO',
                                             'CASMEX_FICHA_EQUIPO.EQ_ID','=','CASMEX_METADATO.EQ_ID')
                        ->select( 'CASMEX_FICHA_EQUIPO.EQ_DESC','CASMEX_METADATO.*')
                        ->orderBy('CASMEX_METADATO.EQ_ID', 'ASC')
                        ->name($name)           //Metodos personalizados es equvalente a ->where('EQ_DESC', 'LIKE', "%$name%");
                        ->nameiap($nameiap)     //Metodos personalizados
                        //->bio($bio)           //Metodos personalizados
                        ->paginate(30);                 
        }else{                
            $regficha   = regFichaModel::select('EQ_ID','EQ_DESC','EQ_STATUS1')
                        ->where('EQ_ID',$arbol_id)
                        ->get();                              
            $regpadron= regPadronModel::join('CASMEX_FICHA_EQUIPO',
                                             'CASMEX_FICHA_EQUIPO.EQ_ID','=','CASMEX_METADATO.EQ_ID')
                        ->select( 'CASMEX_FICHA_EQUIPO.EQ_DESC','CASMEX_METADATO.*')
                        ->where(  'CASMEX_METADATO.EQ_ID',$arbol_id)
                        ->orderBy('CASMEX_METADATO.EQ_ID','ASC')
                        ->name($name)           //Metodos personalizados es equvalente a ->where('EQ_DESC', 'LIKE', "%$name%");
                        ->nameiap($nameiap)       //Metodos personalizados
                        //->bio($bio)             //Metodos personalizados
                        ->paginate(30);                         
        }
        if($regpadron->count() <= 0){
            toastr()->error('No existen jugadores.','Lo siento!',['positionClass' => 'toast-bottom-right']);
        }            
        return view('sicinar.padron.verPadron', compact('nombre','usuario','regpadron','regentidades','regmunicipio','reganios','regficha','regservicios','regmeses','regdias'));
    }

    public function actionverPadron(){
        $nombre       = session()->get('userlog');
        $pass         = session()->get('passlog');
        if($nombre == NULL AND $pass == NULL){
            return view('sicinar.login.expirada');
        }
        $usuario      = session()->get('usuario');
        $rango        = session()->get('rango');
        $ip           = session()->get('ip');
        $arbol_id     = session()->get('arbol_id');        

        $regentidades = regEntidadesModel::select('ENTIDADFED_ID','ENTIDADFED_DESC')
                        ->orderBy('ENTIDADFED_ID','asc')
                        ->get();
        $regmunicipio = regMunicipioModel::select('CASMEX_CAT_MUNICIPIOS_SEDESEM.MUNICIPIOID',
                                  'CASMEX_CAT_MUNICIPIOS_SEDESEM.MUNICIPIONOMBRE AS MUNICIPIO')
                        ->wherein('CASMEX_CAT_MUNICIPIOS_SEDESEM.ENTIDADFEDERATIVAID',[15])
                        ->orderBy('CASMEX_CAT_MUNICIPIOS_SEDESEM.MUNICIPIONOMBRE','ASC')
                        ->get();        

        $reganios     = regAniosModel::select('ANIO_ID','ANIO_DESC')
                        ->orderBy('ANIO_ID','ASC')
                        ->get(); 
        $regmeses     = regMesesModel::select('MES_ID','MES_DESC')
                        ->get();      
        $regdias      = regDiasModel::select('DIA_ID','DIA_DESC')
                        ->get();                           
        //********* Validar rol de usuario **********************/
        if(session()->get('rango') !== '0'){    
            $regficha   = regFichaModel::select('EQ_ID','EQ_DESC','EQ_STATUS1')
                        ->get();                                    
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
                        ->orderBy('EQ_ID'    ,'asc')
                        ->paginate(30);
        }else{            
            $regficha   = regFichaModel::select('EQ_ID','EQ_DESC','EQ_STATUS1')
                        ->where('EQ_ID',$arbol_id)
                        ->get();                                  
            $regpadron= regPadronModel::select('PERIODO_ID','FOLIO','EQ_ID','PRIMER_APELLIDO',
                        'SEGUNDO_APELLIDO','NOMBRES','NOMBRE_COMPLETO','CURP','FECHA_NACIMIENTO','EDAD',
                        'FECHA_NACIMIENTO2','FECHA_NACIMIENTO3','PERIODO_ID2','MES_ID2','DIA_ID2','SEXO',
                        'RFC','ID_OFICIAL','DOMICILIO','COLONIA','CP','ENTRE_CALLE','Y_CALLE','OTRA_REFERENCIA',
                        'TP_ID_OFICIAL','NUM_EXT','NUM_INT',
                        'TELEFONO','CELULAR','E_MAIL','ENTIDAD_NAC_ID','ENTIDAD_FED_ID','MUNICIPIO_ID',
                        'LOCALIDAD_ID','LOCALIDAD','EDO_CIVIL_ID','GRADO_ESTUDIOS_ID','FECHA_INGRESO','FECHA_INGRESO2',
                        'ARC_1','ARC_2','ARC_3','ARC_4','ARC_5','ARC_6','ARC_7','ARC_8',
                        'STATUS_1','STATUS_2','FECHA_REG','FECHA_REG2','IP','LOGIN','FECHA_M','FECHA_M2','IP_M','LOGIN_M')
                        ->where('EQ_ID',$arbol_id)
                        ->paginate(30);            
        }
        if($regpadron->count() <= 0){
            toastr()->error('No existen jugadores.','Lo siento!',['positionClass' => 'toast-bottom-right']);
        }
        return view('sicinar.padron.verPadron',compact('nombre','usuario','regficha','regentidades','regmunicipio','reganios','regpadron','regservicios','regmeses','regdias'));
    }

    public function actionnuevoPadron(){
        $nombre       = session()->get('userlog');
        $pass         = session()->get('passlog');
        if($nombre == NULL AND $pass == NULL){
            return view('sicinar.login.expirada');
        }
        $usuario      = session()->get('usuario');
        $rango        = session()->get('rango');
        $ip           = session()->get('ip');
        $arbol_id     = session()->get('arbol_id');

        $regentidades = regEntidadesModel::select('ENTIDADFED_ID','ENTIDADFED_DESC')
                        ->orderBy('ENTIDADFED_DESC','asc')
                        ->get();
        $regmunicipio = regMunicipioModel::select('CASMEX_CAT_MUNICIPIOS_SEDESEM.MUNICIPIOID',
                                  'CASMEX_CAT_MUNICIPIOS_SEDESEM.MUNICIPIONOMBRE AS MUNICIPIO')
                        ->wherein('CASMEX_CAT_MUNICIPIOS_SEDESEM.ENTIDADFEDERATIVAID',[15])
                        ->orderBy('CASMEX_CAT_MUNICIPIOS_SEDESEM.MUNICIPIONOMBRE','ASC')
                        ->get();        
        $reganios     = regAniosModel::select('ANIO_ID','ANIO_DESC')
                        ->orderBy('ANIO_ID','ASC')
                        ->get(); 
        $regmeses     = regMesesModel::select('MES_ID','MES_DESC')
                        ->get();      
        $regdias      = regDiasModel::select('DIA_ID','DIA_DESC')
                        ->get();                                
        if(session()->get('rango') !== '0'){                           
            $regficha = regFichaModel::select('EQ_ID', 'EQ_DESC','EQ_STATUS1')
                        ->orderBy('EQ_DESC','ASC')
                        ->get();       
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
                        ->orderBy('EQ_ID'    ,'asc')
                        ->get();                        
        }else{
            $regficha = regFichaModel::select('EQ_ID', 'EQ_DESC','EQ_STATUS1')->orderBy('EQ_DESC','ASC')
                        ->where('EQ_ID',$arbol_id)
                        ->get();            
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
        }                                                
        //dd($unidades);
        return view('sicinar.padron.nuevoPadron',compact('regmunicipio','regficha','reganios','regpadron','regmeses','regdias','nombre','usuario','regentidades'));
    }

    public function actionAltanuevoPadron(Request $request){
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

        // **************** calcular variables aux ******************************/
        //https://www.baulphp.com/calcular-edad-en-php-ejemplos-completos
        //4.- Cuarta Forma: Usando date_diff
        $fecha_nac = date('Y/m/d',strtotime(trim($request->periodo_id2.'/'.$mes2[0]->mes_mes.'/'.$dia2[0]->dia_desc)));
        //dd('fecha..:'.$fecha_nac);
        $fecha_hoy = date('Y/m/d');
        //dd('fecha....:'.$fecha_nac,' Fecha de hoy:'.$fecha_hoy);
        //$edad      = $fecha_hoy->diff($fecha_nac);
        $edad = date_diff(date_create($fecha_nac), date_create($fecha_hoy));
        //dd('fecha....:'.$fecha_nac,' Fecha de hoy:'.$fecha_hoy,' Edad:'.$edad->format('%y'));

        setlocale(LC_TIME, "spanish");        
        $xperiodo_id  = (int)date('Y');        
        if(!empty($request->periodo_d1) and !empty($request->mes_d1) and !empty($request->dia_d1) ){
            //toastr()->error('muy bien 1....................','¡ok...........!',['positionClass' => 'toast-bottom-right']);
            $mes1 = regMesesModel::ObtMes($request->mes_id1);
            $dia1 = regDiasModel::ObtDia($request->dia_id1);                
            //xiap_feccons = $dia1[0]->dia_desc.'/'.$mes1[0]->mes_mes.'/'.$request->periodo_id1;
        }   //endif
        if(!empty($request->periodo_d2) and !empty($request->mes_d2) and !empty($request->dia_d2) ){
            $mes2 = regMesesModel::ObtMes($request->mes_id2);
            $dia2 = regDiasModel::ObtDia($request->dia_id2);        
        }
        //$mes1 = regMesesModel::ObtMes($request->mes_id1);
        //$dia1 = regDiasModel::ObtDia($request->dia_id1);                
        $mes2   = regMesesModel::ObtMes($request->mes_id2);
        $dia2   = regDiasModel::ObtDia($request->dia_id2);                        

        $xnombre_completo = substr(strtoupper(trim($request->primer_apellido).' '.trim($request->segundo_apellido).' '.trim($request->nombres)),0,79);
        //$mes1 = regMesesModel::ObtMes($request->mes_id1);
        //$dia1 = regDiasModel::ObtDia($request->dia_id1);
        //$mes2 = regMesesModel::ObtMes($request->mes_id2);
        //$dia2 = regDiasModel::ObtDia($request->dia_id2);        
        // *************** Validar triada ***********************************/
        $triada = regPadronModel::where(['NOMBRE_COMPLETO'=> $xnombre_completo, 
                                         'CURP'           => substr(strtoupper(trim($request->curp)),0,18),
                                         'MUNICIPIO_ID'   => $request->municipio_id])
                  ->get();
        if($triada->count() >= 1)
            //toastr()->error('Ya existe un beneficiario:'.$nombre_completo.' CURP:'.$request->curp.' Municipio:'.$request->municipio_id.'--','¡Por favor volver a intentar!',['positionClass' => 'toast-bottom-right']);
            //return back()->withInput()->withErrors(['PRIMER_APELLIDO' => 'El PRIMER APELLIDO '.$request->PRIMER_APELLIDO.' contiene caracteres inválidos. Favor de verificar.']);
            //return back()->withInput()->withErrors(['NOMBRE_COMPLETO' => 'Beneficiario '.$xnombre_completo.'CURP' => 'Con CURP '.$request->curp.'MUNICIPIO_ID' => 'Clave de municipio '.$request->municipio_id.' Ya existe. Por favor verificar.']);
            return back()->withInput()->withErrors(['NOMBRE_COMPLETO' => 'El jugador '.$xnombre_completo.' con curp y municipio ya existe. Por favor verificar.']);
        else{        
            // *************** Validar curp ***********************************/
            $dupcurp = regPadronModel::where('CURP',substr(strtoupper(trim($request->curp)),0,18))
                       ->get();
            if($dupcurp->count() >= 1)
                return back()->withInput()->withErrors(['CURP' => ' el CURP '.$request->curp.' del jugador ya existe. Por favor verificar.']);
            else{                    

                //**************************** Alta ********************************/
                $folio = regPadronModel::max('FOLIO');
                $folio = $folio + 1;

                $name1 =null;
                //Comprobar  si el campo foto1 tiene un archivo asignado:
                if($request->hasFile('arc_1')){
                   $name1 = $folio.'_'.$request->file('arc_1')->getClientOriginalName(); 
                   $request->file('arc_1')->move(public_path().'/storage/', $name1);
                }
                $name2 =null;
                //Comprobar  si el campo foto2 tiene un archivo asignado:        
                if($request->hasFile('arc_2')){
                   $name2 = $folio.'_'.$request->file('arc_2')->getClientOriginalName(); 
                   $request->file('arc_2')->move(public_path().'/storage/', $name2);
                }

                $nuevoPadron = new regPadronModel();
                $nuevoPadron->PERIODO_ID       = $xperiodo_id;
                $nuevoPadron->FOLIO            = $folio;
                $nuevoPadron->EQ_ID            = $request->eq_id;
                $nuevoPadron->PRIMER_APELLIDO  = substr(strtoupper(trim($request->primer_apellido)) ,0,79);
                $nuevoPadron->SEGUNDO_APELLIDO = substr(strtoupper(trim($request->segundo_apellido)),0,79);
                $nuevoPadron->NOMBRES          = substr(strtoupper(trim($request->nombres))         ,0,79);
                $nuevoPadron->NOMBRE_COMPLETO  = $xnombre_completo;
                $nuevoPadron->CURP             = substr(strtoupper(trim($request->curp))      ,0,18);
                $nuevoPadron->ID_OFICIAL       = substr(strtoupper(trim($request->id_oficial)),0,39);
                //$nuevoPadron->FECHA_NACIMIENTO = date('Y/m/d', strtotime($request->fecha_nacimiento));
                $nuevoPadron->FECHA_NACIMIENTO = date('Y/m/d',strtotime($request->periodo_id2.'/'.$mes2[0]->mes_mes.'/'.trim($dia2[0]->dia_desc) ));
                $nuevoPadron->FECHA_NACIMIENTO2= trim($dia2[0]->dia_desc.'/'.$mes2[0]->mes_mes.'/'.$request->periodo_id2);
                //TO_DATE(‘20100901’, ‘YYYYMMDD’)
                //dd(date('YYYY/m/d H:i:s',trim($request->periodo_id2.'/'.$mes2[0]->mes_mes.'/'.$dia2[0]->dia_desc)));
                $nuevoPadron->FECHA_NACIMIENTO3= date('Y/m/d',strtotime(trim($request->periodo_id2.'/'.$mes2[0]->mes_mes.'/'.$dia2[0]->dia_desc)));
                $nuevoPadron->EDAD             = $edad->format('%y');
                $nuevoPadron->PERIODO_ID2      = $request->periodo_id2;                
                $nuevoPadron->MES_ID2          = $request->mes_id2;                
                $nuevoPadron->DIA_ID2          = $request->dia_id2;                        
                $nuevoPadron->SEXO             = $request->sexo;

                $nuevoPadron->DOMICILIO        = substr(strtoupper(trim($request->domicilio))  ,0,149);        
                $nuevoPadron->COLONIA          = substr(strtoupper(trim($request->colonia))    ,0, 79);        
                $nuevoPadron->LOCALIDAD        = substr(strtoupper(trim($request->localidad))  ,0,149);                
                $nuevoPadron->ENTRE_CALLE      = substr(strtoupper(trim($request->entre_calle)),0, 99); 
                $nuevoPadron->Y_CALLE          = substr(strtoupper(trim($request->y_calle))    ,0, 99); 
                $nuevoPadron->OTRA_REFERENCIA  = substr(strtoupper(trim($request->otra_ref))   ,0,149); 
                $nuevoPadron->CP               = $request->cp; 
                $nuevoPadron->MUNICIPIO_ID     = $request->municipio_id;
                $nuevoPadron->ENTIDAD_FED_ID   = 15;  //$request->entidad_fed_id;
                $nuevoPadron->ENTIDAD_NAC_ID   = $request->entidad_nac_id;
                //$nuevoPadron->FECHA_INGRESO  = date('Y/m/d', strtotime($request->fecha_ingreso));
                //$nuevoPadron->FECHA_INGRESO  = date('Y/m/d', strtotime(trim($dia1[0]->dia_desc.'/'.$mes1[0]->mes_mes.'/'.$request->periodo_id1) ));
                //$nuevoPadron->FECHA_INGRESO2 = trim($dia1[0]->dia_desc.'/'.$mes1[0]->mes_mes.'/'.$request->periodo_id1);
                //$nuevoPadron->FECHA_INGRESO3 = trim($dia1[0]->dia_desc.'/'.$mes1[0]->mes_mes.'/'.$request->periodo_id1);
                //$nuevoPadron->PERIODO_ID1    = $request->periodo_id1;                
                //$nuevoPadron->MES_ID1        = $request->mes_id1;                
                //$nuevoPadron->DIA_ID1        = $request->dia_id1;                
                $nuevoPadron->TELEFONO         = substr(strtoupper(trim($request->telefono)),0,49);        
                $nuevoPadron->CELULAR          = substr(strtoupper(trim($request->telefono)),0,49);        
                $nuevoPadron->E_MAIL           = substr(strtolower(trim($request->email))   ,0,79);  
                $nuevaPadron->ARC_1            = $name1;
                $nuevaPadron->ARC_2            = $name2;              

                $nuevoPadron->IP               = $ip;
                $nuevoPadron->LOGIN            = $nombre;         // Usuario ;
                $nuevoPadron->save();
                if($nuevoPadron->save() == true){
                    toastr()->success('Juagdor registrado.','ok!',['positionClass' => 'toast-bottom-right']);

                    /************ Bitacora inicia *************************************/ 
                    setlocale(LC_TIME, "spanish");        
                    $xip          = session()->get('ip');
                    $xperiodo_id  = (int)date('Y');
                    $xprograma_id = 1;
                    $xmes_id      = (int)date('m');
                    $xproceso_id  =         3;
                    $xfuncion_id  =      3007;
                    $xtrx_id      =         2;    //Alta
                    $regbitacora  =regBitacoraModel::select('PERIODO_ID','MES_ID','PROCESO_ID','FUNCION_ID', 
                                   'TRX_ID', 'FOLIO', 'NO_VECES', 'FECHA_REG','IP', 'LOGIN', 
                                   'FECHA_M','IP_M', 'LOGIN_M')
                                   ->where(['PERIODO_ID'=> $xperiodo_id,'PROGRAMA_ID'=> $xprograma_id,'MES_ID'=> $xmes_id,
                                            'PROCESO_ID'=> $xproceso_id,'FUNCION_ID' => $xfuncion_id, 'TRX_ID'=> $xtrx_id, 
                                            'FOLIO'     => $folio])
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
                        $nuevoregBitacora->IP         = $folio;          // Folio
                        $nuevoregBitacora->LOGIN      = $nombre;         // Usuario 
                        $nuevoregBitacora->save();
                        if($nuevoregBitacora->save() == true)
                            toastr()->success('Trx de jugador registrada en Bitacora.','¡Ok!',['positionClass' => 'toast-bottom-right']);
                        else
                            toastr()->error('Error de trx de jugador. Por favor volver a interlo.','Ups!',['positionClass' => 'toast-bottom-right']);
                    }else{                   
                        //*********** Obtine el no. de veces *****************************
                        $xno_veces = regBitacoraModel::where(['PERIODO_ID' => $xperiodo_id,'PROGRAMA_ID' => $xprograma_id, 
                                                      'MES_ID'     => $xmes_id, 'PROCESO_ID' => $xproceso_id, 
                                                      'FUNCION_ID' => $xfuncion_id, 'TRX_ID' => $xtrx_id, 
                                                      'FOLIO'      => $folio])
                                     ->max('NO_VECES');
                        $xno_veces = $xno_veces+1;                        
                        //*********** Termina de obtener el no de veces *****************************         
                        $regbitacora = regBitacoraModel::select('NO_VECES','IP_M','LOGIN_M','FECHA_M')
                                       ->where(['PERIODO_ID'=> $xperiodo_id,'PROGRAMA_ID'=> $xprograma_id,'MES_ID'=> $xmes_id, 
                                                'PROCESO_ID'=> $xproceso_id,'FUNCION_ID' => $xfuncion_id, 'TRX_ID'=> $xtrx_id,
                                                'FOLIO'     => $folio])
                                       ->update([
                                         'NO_VECES'=> $regbitacora->NO_VECES = $xno_veces,
                                         'IP_M'    => $regbitacora->IP       = $ip,
                                         'LOGIN_M' => $regbitacora->LOGIN_M  = $nombre,
                                         'FECHA_M' => $regbitacora->FECHA_M  = date('Y/m/d')  //date('d/m/Y')
                                                ]);
                        toastr()->success('Trx de jugador registrada en Bitacora.','¡Ok!',['positionClass' => 'toast-bottom-right']);
                    }   /************ Bitacora termina *************************************/ 
                }else{
                    toastr()->error('Error en trx de regitro de jugador. Por favor volver a interlo.','Ups!',['positionClass' => 'toast-bottom-right']);
                }   /**************** Termina de registrar jugador **************************/
            }       /**************** Termina de validar duplicidad del CURP ****************/
        }           /**************** Termina de validar duplicidad triada ******************/
        return redirect()->route('verpadron');
    }
    
    public function actionEditarPadron($id){
        $nombre        = session()->get('userlog');
        $pass          = session()->get('passlog');
        if($nombre == NULL AND $pass == NULL){
            return view('sicinar.login.expirada');
        }
        $usuario       = session()->get('usuario');
        $rango         = session()->get('rango');
        $arbol_id      = session()->get('arbol_id');        

        $regentidades = regEntidadesModel::select('ENTIDADFED_ID','ENTIDADFED_DESC')
                        ->orderBy('ENTIDADFED_DESC','asc')
                        ->get();
        $regmunicipio = regMunicipioModel::select('ENTIDADFEDERATIVAID','MUNICIPIOID',
                                                  'MUNICIPIONOMBRE AS MUNICIPIO')
                        ->where(  'ENTIDADFEDERATIVAID','=',15)
                        ->orderBy('MUNICIPIONOMBRE'    ,'ASC')
                        ->get();        
        $reganios     = regAniosModel::select('ANIO_ID','ANIO_DESC')
                        ->orderBy('ANIO_ID','ASC')
                        ->get(); 
        $regmeses     = regMesesModel::select('MES_ID','MES_DESC')
                        ->get();      
        $regdias      = regDiasModel::select('DIA_ID','DIA_DESC')
                        ->get();                                                        
        //********* Validar rol de usuario **********************/
        if(session()->get('rango') !== '0'){                          
            $regficha = regFichaModel::select('EQ_ID', 'EQ_DESC','EQ_STATUS1')
                        ->orderBy('EQ_DESC','ASC')
                        ->get();       
            $regpadron= regPadronModel::select('PERIODO_ID','FOLIO','EQ_ID','PRIMER_APELLIDO',
                        'SEGUNDO_APELLIDO','NOMBRES','NOMBRE_COMPLETO','CURP','FECHA_NACIMIENTO','EDAD',
                        'FECHA_NACIMIENTO2','FECHA_NACIMIENTO3','PERIODO_ID2','MES_ID2','DIA_ID2','SEXO',
                        'RFC','ID_OFICIAL','DOMICILIO','COLONIA','CP','ENTRE_CALLE','Y_CALLE','OTRA_REFERENCIA',
                        'TP_ID_OFICIAL','NUM_EXT','NUM_INT',
                        'TELEFONO','CELULAR','E_MAIL','ENTIDAD_NAC_ID','ENTIDAD_FED_ID','MUNICIPIO_ID',
                        'LOCALIDAD_ID','LOCALIDAD','EDO_CIVIL_ID','GRADO_ESTUDIOS_ID','FECHA_INGRESO','FECHA_INGRESO2',
                        'ARC_1','ARC_2','ARC_3','ARC_4','ARC_5','ARC_6','ARC_7','ARC_8',
                        'STATUS_1','STATUS_2','FECHA_REG','FECHA_REG2','IP','LOGIN','FECHA_M','FECHA_M2','IP_M','LOGIN_M')
                        ->where(  'FOLIO',$id)
                        ->orderBy('PERIODO_ID','asc')
                        ->orderBy('FOLIO'     ,'asc')
                        ->first();                      
        }else{
            $regficha = regFichaModel::select('EQ_ID', 'EQ_DESC','EQ_STATUS1')
                        ->orderBy('EQ_DESC','ASC')
                        ->where('EQ_ID',$arbol_id)
                        ->get();            
            $regpadron= regPadronModel::select('PERIODO_ID','FOLIO','EQ_ID','PRIMER_APELLIDO',
                        'SEGUNDO_APELLIDO','NOMBRES','NOMBRE_COMPLETO','CURP','FECHA_NACIMIENTO','EDAD',
                        'FECHA_NACIMIENTO2','FECHA_NACIMIENTO3','PERIODO_ID2','MES_ID2','DIA_ID2','SEXO',
                        'RFC','ID_OFICIAL','DOMICILIO','COLONIA','CP','ENTRE_CALLE','Y_CALLE','OTRA_REFERENCIA',
                        'TP_ID_OFICIAL','NUM_EXT','NUM_INT',
                        'TELEFONO','CELULAR','E_MAIL','ENTIDAD_NAC_ID','ENTIDAD_FED_ID','MUNICIPIO_ID',
                        'LOCALIDAD_ID','LOCALIDAD','EDO_CIVIL_ID','GRADO_ESTUDIOS_ID','FECHA_INGRESO','FECHA_INGRESO2',
                        'ARC_1','ARC_2','ARC_3','ARC_4','ARC_5','ARC_6','ARC_7','ARC_8',
                        'STATUS_1','STATUS_2','FECHA_REG','FECHA_REG2','IP','LOGIN','FECHA_M','FECHA_M2','IP_M','LOGIN_M')
                        ->where(  'EQ_ID',$arbol_id)            
                        ->where(  'FOLIO',$id)
                        ->orderBy('PERIODO_ID','asc')
                        ->orderBy('FOLIO'     ,'asc')
                        ->first();
        }                        
        if($regpadron->count() <= 0){
            toastr()->error('No existen jugadores del equipo.','Lo siento!',['positionClass' => 'toast-bottom-right']);
        }
        return view('sicinar.padron.editarPadron',compact('nombre','usuario','regficha','regentidades','regmunicipio','reganios','regpadron','regmeses','regdias'));
    }

    public function actionActualizarPadron(padronRequest $request, $id){
        $nombre        = session()->get('userlog');
        $pass          = session()->get('passlog');
        if($nombre == NULL AND $pass == NULL){
            return view('sicinar.login.expirada');
        }
        $usuario       = session()->get('usuario');
        $rango         = session()->get('rango');
        $ip            = session()->get('ip');
        $arbol_id      = session()->get('arbol_id');        

        $xnombre_completo = substr(strtoupper(trim($request->primer_apellido).' '.trim($request->segundo_apellido).' '.trim($request->nombres)),0,79);
        // *************** Validar triada ***********************************/
        $triada = regPadronModel::where(['NOMBRE_COMPLETO'=> $xnombre_completo, 
                                         'CURP'           => substr(strtoupper(trim($request->curp)),0,18),
                                         'MUNICIPIO_ID'   => $request->municipio_id])
                  ->get();
        if($triada->count() >= 2)
          return back()->withInput()->withErrors(['NOMBRE_COMPLETO' => 'El jugador '.$xnombre_completo.' con curp y municipio ya existe. Por favor verificar.']);
        else{        
          // *************** Validar curp ***********************************/
          $dupcurp = regPadronModel::where('CURP',substr(strtoupper(trim($request->curp)),0,18))
                     ->get();
          if($dupcurp->count() >= 2)
            return back()->withInput()->withErrors(['CURP' => ' el CURP '.$request->curp.' del jugador ya existe. Por favor verificar.']);
          else{                    
            //**************** actualizar ******************************
            $regpadron = regPadronModel::where('FOLIO',$id);
            if($regpadron->count() <= 0)
                toastr()->error('No existe jugador.','¡Por favor volver a intentar!',['positionClass' => 'toast-bottom-right']);
            else{        
                //*************** Actualizar ********************************/
                //if(!empty($request->periodo_d1) and !empty($request->mes_d1) and !empty($request->dia_d1) ){
                    ////toastr()->error('muy bien 1....................','¡ok...........!',['positionClass' => 'toast-bottom-right']);
                    //$mes1 = regMesesModel::ObtMes($request->mes_id1);
                    //$dia1 = regDiasModel::ObtDia($request->dia_id1);                
                    ////xiap_feccons = $dia1[0]->dia_desc.'/'.$mes1[0]->mes_mes.'/'.$request->periodo_id1;
                //} 
                if(!empty($request->periodo_d2) and !empty($request->mes_d2) and !empty($request->dia_d2) ){
                    $mes2 = regMesesModel::ObtMes($request->mes_id2);
                    $dia2 = regDiasModel::ObtDia($request->dia_id2);        
                }
                //$mes1= regMesesModel::ObtMes($request->mes_id1);
                //$dia1= regDiasModel::ObtDia($request->dia_id1);                
                $mes2  = regMesesModel::ObtMes($request->mes_id2);
                $dia2  = regDiasModel::ObtDia($request->dia_id2);                

                $yperiodo_id= (int)date('Y');
                $ymes_id    = (int)date('m');
                $ydia_id    = (int)date('d');
                $mes  = regMesesModel::ObtMes($ymes_id);
                $dia  = regDiasModel::ObtDia($ydia_id);     

                //https://www.baulphp.com/calcular-edad-en-php-ejemplos-completos
                //4.- Cuarta Forma: Usando date_diff
                $fecha_nac = date('Y/m/d',strtotime(trim($request->periodo_id2.'/'.$mes2[0]->mes_mes.'/'.$dia2[0]->dia_desc)));
                //$fecha_nac = date('Y/m/d',strtotime(trim($dia2[0]->dia_desc.'/'.$mes2[0]->mes_mes.'/'.$request->periodo_id2)));
                //dd('fecha..:'.$fecha_nac);
                $fecha_hoy = date('Y/m/d');
                //dd('fecha....:'.$fecha_nac,' Fecha de hoy:'.$fecha_hoy);
                //$edad      = $fecha_hoy->diff($fecha_nac);
                $edad = date_diff(date_create($fecha_nac), date_create($fecha_hoy));
                //dd('fecha....:'.$fecha_nac,' Fecha de hoy:'.$fecha_hoy,' Edad:'.$edad->format('%y'));

                $regpadron=regPadronModel::where('FOLIO',$id)        
                           ->update([                
                    //'PERIODO_ID'     => $request->periodo_id,
                    //'FECHA_INGRESO'  => date('Y/m/d', strtotime(trim($dia1[0]->dia_desc.'/'.$mes1[0]->mes_mes.'/'.$request->periodo_id1) )),
                    //'FECHA_INGRESO2' => trim($dia1[0]->dia_desc.'/'.$mes1[0]->mes_mes.'/'.$request->periodo_id1),
                    'PRIMER_APELLIDO'  => substr(strtoupper(trim($request->primer_apellido)) ,0,79),
                    'SEGUNDO_APELLIDO' => substr(strtoupper(trim($request->segundo_apellido)),0,79),
                    'NOMBRES'          => substr(strtoupper(trim($request->nombres))         ,0,79),
                    'NOMBRE_COMPLETO'  => $xnombre_completo,
                    'CURP'             => substr(strtoupper(trim($request->curp)),0,18),
                    'SEXO'             => $request->sexo,
                    //'FECHA_NACIMIENT'=> date('Y/m/d', strtotime($request->fecha_nacimiento)), //$request->iap_feccons
                    'FECHA_NACIMIENTO' => date('Y/m/d',strtotime($request->periodo_id2.'/'.$mes2[0]->mes_mes.'/'.trim($dia2[0]->dia_desc) )),
                    'FECHA_NACIMIENTO2'=> trim($dia2[0]->dia_desc.'/'.$mes2[0]->mes_mes.'/'.$request->periodo_id2),
                    'FECHA_NACIMIENTO3'=> date('Y/m/d',strtotime(trim($request->periodo_id2.'/'.$mes2[0]->mes_mes.'/'.$dia2[0]->dia_desc))),
                    'EDAD'             => $edad->format('%y'),
                    'PERIODO_ID2'      => $request->periodo_id2,
                    'MES_ID2'          => $request->mes_id2,
                    'DIA_ID2'          => $request->dia_id2,

                    'ENTIDAD_NAC_ID'   => $request->entidad_nac_id,                
                    'MUNICIPIO_ID'     => $request->municipio_id,
        
                    'DOMICILIO'       => substr(strtoupper(trim($request->domicilio))  ,0,149),
                    'COLONIA'         => substr(strtoupper(trim($request->colonia))    ,0, 79),
                    'LOCALIDAD'       => substr(strtoupper(trim($request->localidad))  ,0,149),
                    'CP'              => substr(trim($request->cp)                     ,0,  5),
                    'ENTRE_CALLE'     => substr(strtoupper(trim($request->entre_calle)),0, 99),
                    'Y_CALLE'         => substr(strtoupper(trim($request->y_calle))    ,0, 99),
                    'OTRA_REFERENCIA' => substr(strtoupper(trim($request->otra_ref))   ,0,149),                                                
                
                    'TELEFONO'        => substr(strtoupper(trim($request->telefono)),0, 49),
                    'CELULAR'         => substr(strtoupper(trim($request->telefono)),0, 49),
                    'E_MAIL'          => substr(strtolower(trim($request->email))   ,0, 79),
                    'STATUS_1'        => $request->status_1,                

                    'IP_M'            => $ip,
                    'LOGIN_M'         => $nombre,
                    'FECHA_M2'        => trim($dia[0]->dia_desc.'/'.$mes[0]->mes_mes.'/'.$yperiodo_id), 
                    'FECHA_M'         => date('Y/m/d')    //date('d/m/Y')                                
                                  ]);
                toastr()->success('Datos del jugador actualizados.','¡Ok!',['positionClass' => 'toast-bottom-right']);

                /************ Bitacora inicia *************************************/ 
                setlocale(LC_TIME, "spanish");        
                $xip          = session()->get('ip');
                $xperiodo_id  = (int)date('Y');
                $xprograma_id = 1;
                $xmes_id      = (int)date('m');
                $xproceso_id  =         3;
                $xfuncion_id  =      3007;
                $xtrx_id      =         3;    //Actualizar         
                $regbitacora = regBitacoraModel::select('PERIODO_ID','MES_ID','PROCESO_ID','FUNCION_ID','TRX_ID','FOLIO',
                                                        'NO_VECES','FECHA_REG','IP','LOGIN','FECHA_M','IP_M','LOGIN_M')
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
                        toastr()->success('Trx de jugador actualizada en Bitacora.','¡Ok!',['positionClass' => 'toast-bottom-right']);
                    else
                        toastr()->error('Error de trx de actualizar jugador. Por favor volver a interlo.','Ups!',['positionClass' => 'toast-bottom-right']);
                }else{                   
                    //*********** Obtine el no. de veces *****************************
                    $xno_veces=regBitacoraModel::where(['PERIODO_ID' => $xperiodo_id, 'PROGRAMA_ID' => $xprograma_id, 
                               'MES_ID' => $xmes_id, 'PROCESO_ID' => $xproceso_id, 'FUNCION_ID' => $xfuncion_id, 
                               'TRX_ID' => $xtrx_id, 'FOLIO' => $id])
                               ->max('NO_VECES');
                    $xno_veces = $xno_veces+1;                        
                    //*********** Termina de obtener el no de veces *****************************         
                    $regbitacora=regBitacoraModel::select('NO_VECES','IP_M','LOGIN_M','FECHA_M')
                                 ->where(['PERIODO_ID' => $xperiodo_id,'PROGRAMA_ID'=> $xprograma_id,'MES_ID' => $xmes_id, 
                                          'PROCESO_ID' => $xproceso_id,'FUNCION_ID' => $xfuncion_id, 'TRX_ID' => $xtrx_id, 
                                          'FOLIO'      => $id])
                                 ->update([
                                           'NO_VECES'=> $regbitacora->NO_VECES = $xno_veces,
                                           'IP_M'    => $regbitacora->IP       = $ip,
                                           'LOGIN_M' => $regbitacora->LOGIN_M  = $nombre,
                                           'FECHA_M' => $regbitacora->FECHA_M  = date('Y/m/d')  //date('d/m/Y')
                                          ]);
                    toastr()->success('Trx de actulizar jugador actualizada en Bitacora.','¡Ok!',['positionClass' => 'toast-bottom-right']);
                }   /**************** Bitacora termina **************************************/                     
            }       /**************** Actualizar datos del jugador **************************/
          }         /**************** Termina de validar duplicidad del CURP ****************/
        }           /**************** Termina de validar duplicidad triada ******************/            
        return redirect()->route('verpadron');
    }

    public function actionEditarPadron1($id){
        $nombre        = session()->get('userlog');
        $pass          = session()->get('passlog');
        if($nombre == NULL AND $pass == NULL){
            return view('sicinar.login.expirada');
        }
        $usuario       = session()->get('usuario');
        $rango         = session()->get('rango');
        $arbol_id      = session()->get('arbol_id');        

        $regentidades = regEntidadesModel::select('ENTIDADFED_ID','ENTIDADFED_DESC')
                        ->orderBy('ENTIDADFED_DESC','asc')
                        ->get();
        $regmunicipio = regMunicipioModel::select('ENTIDADFEDERATIVAID','MUNICIPIOID',
                                                  'MUNICIPIONOMBRE AS MUNICIPIO')
                        ->where(  'ENTIDADFEDERATIVAID','=',15)
                        ->orderBy('MUNICIPIONOMBRE'    ,'ASC')
                        ->get();        
        $reganios     = regAniosModel::select('ANIO_ID','ANIO_DESC')
                        ->orderBy('ANIO_ID','ASC')
                        ->get(); 
        $regmeses     = regMesesModel::select('MES_ID','MES_DESC')
                        ->get();      
        $regdias      = regDiasModel::select('DIA_ID','DIA_DESC')
                        ->get();                                                        
        //********* Validar rol de usuario **********************/
        if(session()->get('rango') !== '0'){                          
            $regficha = regFichaModel::select('EQ_ID', 'EQ_DESC','EQ_STATUS1')
                        ->orderBy('EQ_DESC','ASC')
                        ->get();       
            $regpadron= regPadronModel::select('PERIODO_ID','FOLIO','EQ_ID','PRIMER_APELLIDO',
                        'SEGUNDO_APELLIDO','NOMBRES','NOMBRE_COMPLETO','CURP','FECHA_NACIMIENTO','EDAD',
                        'FECHA_NACIMIENTO2','FECHA_NACIMIENTO3','PERIODO_ID2','MES_ID2','DIA_ID2','SEXO',
                        'RFC','ID_OFICIAL','DOMICILIO','COLONIA','CP','ENTRE_CALLE','Y_CALLE','OTRA_REFERENCIA',
                        'TP_ID_OFICIAL','NUM_EXT','NUM_INT',
                        'TELEFONO','CELULAR','E_MAIL','ENTIDAD_NAC_ID','ENTIDAD_FED_ID','MUNICIPIO_ID',
                        'LOCALIDAD_ID','LOCALIDAD','EDO_CIVIL_ID','GRADO_ESTUDIOS_ID','FECHA_INGRESO','FECHA_INGRESO2',
                        'ARC_1','ARC_2','ARC_3','ARC_4','ARC_5','ARC_6','ARC_7','ARC_8',
                        'STATUS_1','STATUS_2','FECHA_REG','FECHA_REG2','IP','LOGIN','FECHA_M','FECHA_M2','IP_M','LOGIN_M')
                        ->where(  'FOLIO',$id)
                        ->orderBy('PERIODO_ID','asc')
                        ->orderBy('FOLIO'     ,'asc')
                        ->first();                      
        }else{
            $regficha = regFichaModel::select('EQ_ID', 'EQ_DESC','EQ_STATUS1')
                        ->orderBy('EQ_DESC','ASC')
                        ->where('EQ_ID',$arbol_id)
                        ->get();            
            $regpadron= regPadronModel::select('PERIODO_ID','FOLIO','EQ_ID','PRIMER_APELLIDO',
                        'SEGUNDO_APELLIDO','NOMBRES','NOMBRE_COMPLETO','CURP','FECHA_NACIMIENTO','EDAD',
                        'FECHA_NACIMIENTO2','FECHA_NACIMIENTO3','PERIODO_ID2','MES_ID2','DIA_ID2','SEXO',
                        'RFC','ID_OFICIAL','DOMICILIO','COLONIA','CP','ENTRE_CALLE','Y_CALLE','OTRA_REFERENCIA',
                        'TP_ID_OFICIAL','NUM_EXT','NUM_INT',
                        'TELEFONO','CELULAR','E_MAIL','ENTIDAD_NAC_ID','ENTIDAD_FED_ID','MUNICIPIO_ID',
                        'LOCALIDAD_ID','LOCALIDAD','EDO_CIVIL_ID','GRADO_ESTUDIOS_ID','FECHA_INGRESO','FECHA_INGRESO2',
                        'ARC_1','ARC_2','ARC_3','ARC_4','ARC_5','ARC_6','ARC_7','ARC_8',
                        'STATUS_1','STATUS_2','FECHA_REG','FECHA_REG2','IP','LOGIN','FECHA_M','FECHA_M2','IP_M','LOGIN_M')
                        ->where(  'EQ_ID',$arbol_id)            
                        ->where(  'FOLIO',$id)
                        ->orderBy('PERIODO_ID','asc')
                        ->orderBy('FOLIO'     ,'asc')
                        ->first();
        }                        
        if($regpadron->count() <= 0){
            toastr()->error('No existe jugador.','Lo siento!',['positionClass' => 'toast-bottom-right']);
        }
        return view('sicinar.padron.editarPadron1',compact('nombre','usuario','regficha','regentidades','regmunicipio','reganios','regpadron','regmeses','regdias'));
    }

    public function actionActualizarPadron1(padron1Request $request, $id){
        $nombre        = session()->get('userlog');
        $pass          = session()->get('passlog');
        if($nombre == NULL AND $pass == NULL){
            return view('sicinar.login.expirada');
        }
        $usuario       = session()->get('usuario');
        $rango         = session()->get('rango');
        $ip            = session()->get('ip');
        $arbol_id      = session()->get('arbol_id');        

        $xnombre_completo = substr(strtoupper(trim($request->primer_apellido).' '.trim($request->segundo_apellido).' '.trim($request->nombres)),0,79);
        //**************** actualizar ******************************
        $regpadron = regPadronModel::where('FOLIO',$id);
        if($regpadron->count() <= 0)
            toastr()->error('No existe jugador.','¡Por favor volver a intentar!',['positionClass' => 'toast-bottom-right']);
        else{        
            //*************** Actualizar ********************************/
            $yperiodo_id= (int)date('Y');
            $ymes_id    = (int)date('m');
            $ydia_id    = (int)date('d');
            $mes = regMesesModel::ObtMes($ymes_id);
            $dia = regDiasModel::ObtDia($ydia_id);     

            $name01 =null;
            //echo "Escribió en el campo de texto 1: " .'-'. $request->iap_d01 .'-'. "<br><br>"; 
            //echo "Escribió en el campo de texto 1: " . $request->iap_d01 . "<br><br>"; 
            //Comprobar  si el campo foto1 tiene un archivo asignado:
            if($request->hasFile('arc_1')){
                $name01 = $id.'_'.$request->file('arc_1')->getClientOriginalName(); 
                //sube el archivo a la carpeta del servidor public/storage/
                $request->file('arc_1')->move(public_path().'/storage/', $name01);

                $regpadron=regPadronModel::where('FOLIO',$id)        
                           ->update([                
                                     'ARC_1'    => $name01,

                                     'IP_M'     => $ip,
                                     'LOGIN_M'  => $nombre,
                                     'FECHA_M2' => trim($dia[0]->dia_desc.'/'.$mes[0]->mes_mes.'/'.$yperiodo_id), 
                                     'FECHA_M'  => date('Y/m/d')    //date('d/m/Y')                                
                                  ]);
                toastr()->success('Archivo digital 1 actualizado.','¡Ok!',['positionClass' => 'toast-bottom-right']);

                /************ Bitacora inicia *************************************/ 
                setlocale(LC_TIME, "spanish");        
                $xip          = session()->get('ip');
                $xperiodo_id  = (int)date('Y');
                $xprograma_id = 1;
                $xmes_id      = (int)date('m');
                $xproceso_id  =         3;
                $xfuncion_id  =      3007;
                $xtrx_id      =         3;    //Actualizar         
                $regbitacora = regBitacoraModel::select('PERIODO_ID','MES_ID','PROCESO_ID','FUNCION_ID','TRX_ID','FOLIO',
                                                        'NO_VECES','FECHA_REG','IP','LOGIN','FECHA_M','IP_M','LOGIN_M')
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
                        toastr()->success('Trx de actualizar archivo digital 1 registrada en Bitacora.','¡Ok!',['positionClass' => 'toast-bottom-right']);
                    else
                        toastr()->error('Error de trx de actualizar archivo digital 1. Por favor volver a interlo.','Ups!',['positionClass' => 'toast-bottom-right']);
                }else{                   
                    //*********** Obtine el no. de veces *****************************
                    $xno_veces=regBitacoraModel::where(['PERIODO_ID' => $xperiodo_id, 'PROGRAMA_ID' => $xprograma_id, 
                               'MES_ID' => $xmes_id, 'PROCESO_ID' => $xproceso_id, 'FUNCION_ID' => $xfuncion_id, 
                               'TRX_ID' => $xtrx_id, 'FOLIO' => $id])
                               ->max('NO_VECES');
                    $xno_veces = $xno_veces+1;                        
                    //*********** Termina de obtener el no de veces *****************************         
                    $regbitacora=regBitacoraModel::select('NO_VECES','IP_M','LOGIN_M','FECHA_M')
                                 ->where(['PERIODO_ID' => $xperiodo_id,'PROGRAMA_ID'=> $xprograma_id,'MES_ID' => $xmes_id, 
                                          'PROCESO_ID' => $xproceso_id,'FUNCION_ID' => $xfuncion_id, 'TRX_ID' => $xtrx_id, 
                                          'FOLIO'      => $id])
                                 ->update([
                                           'NO_VECES'=> $regbitacora->NO_VECES = $xno_veces,
                                           'IP_M'    => $regbitacora->IP       = $ip,
                                           'LOGIN_M' => $regbitacora->LOGIN_M  = $nombre,
                                           'FECHA_M' => $regbitacora->FECHA_M  = date('Y/m/d')  //date('d/m/Y')
                                          ]);
                    toastr()->success('Trx de actualizar archivo digital 1 registrado en Bitacora.','¡Ok!',['positionClass' => 'toast-bottom-right']);
                }   /**************** Bitacora termina **************************************/                     
            }       /**************** Validar archivo digital *******************************/
        }           /**************** Actualizar datos del jugador **************************/
        return redirect()->route('verpadron');
    }

    public function actionEditarPadron2($id){
        $nombre        = session()->get('userlog');
        $pass          = session()->get('passlog');
        if($nombre == NULL AND $pass == NULL){
            return view('sicinar.login.expirada');
        }
        $usuario       = session()->get('usuario');
        $rango         = session()->get('rango');
        $arbol_id      = session()->get('arbol_id');        

        $regentidades = regEntidadesModel::select('ENTIDADFED_ID','ENTIDADFED_DESC')
                        ->orderBy('ENTIDADFED_DESC','asc')
                        ->get();
        $regmunicipio = regMunicipioModel::select('ENTIDADFEDERATIVAID','MUNICIPIOID',
                                                  'MUNICIPIONOMBRE AS MUNICIPIO')
                        ->where(  'ENTIDADFEDERATIVAID','=',15)
                        ->orderBy('MUNICIPIONOMBRE'    ,'ASC')
                        ->get();        
        $reganios     = regAniosModel::select('ANIO_ID','ANIO_DESC')
                        ->orderBy('ANIO_ID','ASC')
                        ->get(); 
        $regmeses     = regMesesModel::select('MES_ID','MES_DESC')
                        ->get();      
        $regdias      = regDiasModel::select('DIA_ID','DIA_DESC')
                        ->get();                                                        
        //********* Validar rol de usuario **********************/
        if(session()->get('rango') !== '0'){                          
            $regficha = regFichaModel::select('EQ_ID', 'EQ_DESC','EQ_STATUS1')
                        ->orderBy('EQ_DESC','ASC')
                        ->get();       
            $regpadron= regPadronModel::select('PERIODO_ID','FOLIO','EQ_ID','PRIMER_APELLIDO',
                        'SEGUNDO_APELLIDO','NOMBRES','NOMBRE_COMPLETO','CURP','FECHA_NACIMIENTO','EDAD',
                        'FECHA_NACIMIENTO2','FECHA_NACIMIENTO3','PERIODO_ID2','MES_ID2','DIA_ID2','SEXO',
                        'RFC','ID_OFICIAL','DOMICILIO','COLONIA','CP','ENTRE_CALLE','Y_CALLE','OTRA_REFERENCIA',
                        'TP_ID_OFICIAL','NUM_EXT','NUM_INT',
                        'TELEFONO','CELULAR','E_MAIL','ENTIDAD_NAC_ID','ENTIDAD_FED_ID','MUNICIPIO_ID',
                        'LOCALIDAD_ID','LOCALIDAD','EDO_CIVIL_ID','GRADO_ESTUDIOS_ID','FECHA_INGRESO','FECHA_INGRESO2',
                        'ARC_1','ARC_2','ARC_3','ARC_4','ARC_5','ARC_6','ARC_7','ARC_8',
                        'STATUS_1','STATUS_2','FECHA_REG','FECHA_REG2','IP','LOGIN','FECHA_M','FECHA_M2','IP_M','LOGIN_M')
                        ->where(  'FOLIO',$id)
                        ->orderBy('PERIODO_ID','asc')
                        ->orderBy('FOLIO'     ,'asc')
                        ->first();                      
        }else{
            $regficha = regFichaModel::select('EQ_ID', 'EQ_DESC','EQ_STATUS1')
                        ->orderBy('EQ_DESC','ASC')
                        ->where('EQ_ID',$arbol_id)
                        ->get();            
            $regpadron= regPadronModel::select('PERIODO_ID','FOLIO','EQ_ID','PRIMER_APELLIDO',
                        'SEGUNDO_APELLIDO','NOMBRES','NOMBRE_COMPLETO','CURP','FECHA_NACIMIENTO','EDAD',
                        'FECHA_NACIMIENTO2','FECHA_NACIMIENTO3','PERIODO_ID2','MES_ID2','DIA_ID2','SEXO',
                        'RFC','ID_OFICIAL','DOMICILIO','COLONIA','CP','ENTRE_CALLE','Y_CALLE','OTRA_REFERENCIA',
                        'TP_ID_OFICIAL','NUM_EXT','NUM_INT',
                        'TELEFONO','CELULAR','E_MAIL','ENTIDAD_NAC_ID','ENTIDAD_FED_ID','MUNICIPIO_ID',
                        'LOCALIDAD_ID','LOCALIDAD','EDO_CIVIL_ID','GRADO_ESTUDIOS_ID','FECHA_INGRESO','FECHA_INGRESO2',
                        'ARC_1','ARC_2','ARC_3','ARC_4','ARC_5','ARC_6','ARC_7','ARC_8',
                        'STATUS_1','STATUS_2','FECHA_REG','FECHA_REG2','IP','LOGIN','FECHA_M','FECHA_M2','IP_M','LOGIN_M')
                        ->where(  'EQ_ID',$arbol_id)            
                        ->where(  'FOLIO',$id)
                        ->orderBy('PERIODO_ID','asc')
                        ->orderBy('FOLIO'     ,'asc')
                        ->first();
        }                        
        if($regpadron->count() <= 0){
            toastr()->error('No existe jugador.','Lo siento!',['positionClass' => 'toast-bottom-right']);
        }
        return view('sicinar.padron.editarPadron2',compact('nombre','usuario','regficha','regentidades','regmunicipio','reganios','regpadron','regmeses','regdias'));
    }

    public function actionActualizarPadron2(padron2Request $request, $id){
        $nombre        = session()->get('userlog');
        $pass          = session()->get('passlog');
        if($nombre == NULL AND $pass == NULL){
            return view('sicinar.login.expirada');
        }
        $usuario       = session()->get('usuario');
        $rango         = session()->get('rango');
        $ip            = session()->get('ip');
        $arbol_id      = session()->get('arbol_id');        

        $xnombre_completo = substr(strtoupper(trim($request->primer_apellido).' '.trim($request->segundo_apellido).' '.trim($request->nombres)),0,79);
        //**************** actualizar ******************************
        $regpadron = regPadronModel::where('FOLIO',$id);
        if($regpadron->count() <= 0)
            toastr()->error('No existe jugador.','¡Por favor volver a intentar!',['positionClass' => 'toast-bottom-right']);
        else{        
            //*************** Actualizar ********************************/
            $yperiodo_id= (int)date('Y');
            $ymes_id    = (int)date('m');
            $ydia_id    = (int)date('d');
            $mes = regMesesModel::ObtMes($ymes_id);
            $dia = regDiasModel::ObtDia($ydia_id);     

            $name02 =null;
            //Comprobar  si el campo foto2 tiene un archivo asignado:
            if($request->hasFile('arc_2')){
                $name02 = $id.'_'.$request->file('arc_2')->getClientOriginalName(); 
                //sube el archivo a la carpeta del servidor public/storage/
                $request->file('arc_2')->move(public_path().'/storage/', $name02);

                $regpadron=regPadronModel::where('FOLIO',$id)        
                           ->update([                
                                     'ARC_2'    => $name02,

                                     'IP_M'     => $ip,
                                     'LOGIN_M'  => $nombre,
                                     'FECHA_M2' => trim($dia[0]->dia_desc.'/'.$mes[0]->mes_mes.'/'.$yperiodo_id), 
                                     'FECHA_M'  => date('Y/m/d')    //date('d/m/Y')                                
                                  ]);
                toastr()->success('Archivo digital 2 actualizado.','¡Ok!',['positionClass' => 'toast-bottom-right']);

                /************ Bitacora inicia *************************************/ 
                setlocale(LC_TIME, "spanish");        
                $xip          = session()->get('ip');
                $xperiodo_id  = (int)date('Y');
                $xprograma_id = 1;
                $xmes_id      = (int)date('m');
                $xproceso_id  =         3;
                $xfuncion_id  =      3007;
                $xtrx_id      =         3;    //Actualizar         
                $regbitacora = regBitacoraModel::select('PERIODO_ID','MES_ID','PROCESO_ID','FUNCION_ID','TRX_ID','FOLIO',
                                                        'NO_VECES','FECHA_REG','IP','LOGIN','FECHA_M','IP_M','LOGIN_M')
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
                        toastr()->success('Trx de actualizar archivo digital 2 registrada en Bitacora.','¡Ok!',['positionClass' => 'toast-bottom-right']);
                    else
                        toastr()->error('Error de trx de actualizar archivo digital 2. Por favor volver a interlo.','Ups!',['positionClass' => 'toast-bottom-right']);
                }else{                   
                    //*********** Obtine el no. de veces *****************************
                    $xno_veces=regBitacoraModel::where(['PERIODO_ID' => $xperiodo_id, 'PROGRAMA_ID' => $xprograma_id, 
                               'MES_ID' => $xmes_id, 'PROCESO_ID' => $xproceso_id, 'FUNCION_ID' => $xfuncion_id, 
                               'TRX_ID' => $xtrx_id, 'FOLIO' => $id])
                               ->max('NO_VECES');
                    $xno_veces = $xno_veces+1;                        
                    //*********** Termina de obtener el no de veces *****************************         
                    $regbitacora=regBitacoraModel::select('NO_VECES','IP_M','LOGIN_M','FECHA_M')
                                 ->where(['PERIODO_ID' => $xperiodo_id,'PROGRAMA_ID'=> $xprograma_id,'MES_ID' => $xmes_id, 
                                          'PROCESO_ID' => $xproceso_id,'FUNCION_ID' => $xfuncion_id, 'TRX_ID' => $xtrx_id, 
                                          'FOLIO'      => $id])
                                 ->update([
                                           'NO_VECES'=> $regbitacora->NO_VECES = $xno_veces,
                                           'IP_M'    => $regbitacora->IP       = $ip,
                                           'LOGIN_M' => $regbitacora->LOGIN_M  = $nombre,
                                           'FECHA_M' => $regbitacora->FECHA_M  = date('Y/m/d')  //date('d/m/Y')
                                          ]);
                    toastr()->success('Trx de actualizar archivo digital 2 registrada en Bitacora.','¡Ok!',['positionClass' => 'toast-bottom-right']);
                }   /**************** Bitacora termina **************************************/                     
            }       /**************** Validar archivo digital *******************************/
        }           /**************** Actualizar datos del jugador **************************/
        return redirect()->route('verpadron');
    }

    public function actionBorrarPadron($id){
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
        $regpadron = regPadronModel::where('FOLIO',$id);
        if($regpadron->count() <= 0)
            toastr()->error('No existe jugador.','¡Por favor volver a intentar!',['positionClass' => 'toast-bottom-right']);
        else{        
            $regpadron->delete();
            toastr()->success('Jugador dado de baja del equipo.','¡Ok!',['positionClass' => 'toast-bottom-right']);

            //echo 'Ya entre a borrar registro..........';
            /************ Bitacora inicia *************************************/ 
            setlocale(LC_TIME, "spanish");        
            $xip          = session()->get('ip');
            $xperiodo_id  = (int)date('Y');
            $xprograma_id = 1;
            $xmes_id      = (int)date('m');
            $xproceso_id  =         3;
            $xfuncion_id  =      3007;
            $xtrx_id      =         4;     // Baja 
            $regbitacora = regBitacoraModel::select('PERIODO_ID','MES_ID','PROCESO_ID','FUNCION_ID','TRX_ID','FOLIO', 
                                                    'NO_VECES','FECHA_REG','IP','LOGIN','FECHA_M','IP_M','LOGIN_M')
                           ->where(['PERIODO_ID' => $xperiodo_id,'PROGRAMA_ID'=> $xprograma_id,'MES_ID' => $xmes_id, 
                                    'PROCESO_ID' => $xproceso_id,'FUNCION_ID' => $xfuncion_id, 'TRX_ID' => $xtrx_id, 
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
                    toastr()->success('Trx de eliminar jugador del equipo registrada en Bitacora.','¡Ok!',['positionClass' => 'toast-bottom-right']);
                else
                    toastr()->error('Error de trx de eliminar jugador del equipo. Por favor volver a interlo.','Ups!',['positionClass' => 'toast-bottom-right']);
            }else{                   
                //*********** Obtine el no. de veces *****************************
                $xno_veces = regBitacoraModel::where(['PERIODO_ID' => $xperiodo_id, 'PROGRAMA_ID' => $xprograma_id, 
                             'MES_ID' => $xmes_id,'PROCESO_ID' => $xproceso_id, 'FUNCION_ID' => $xfuncion_id, 
                             'TRX_ID' => $xtrx_id,'FOLIO' => $id])
                        ->max('NO_VECES');
                $xno_veces = $xno_veces+1;                        
                //*********** Termina de obtener el no de veces *****************************         
                $regbitacora = regBitacoraModel::select('NO_VECES','IP_M','LOGIN_M','FECHA_M')
                               ->where(['PERIODO_ID' => $xperiodo_id,'PROGRAMA_ID'=> $xprograma_id,'MES_ID' => $xmes_id,
                                        'PROCESO_ID' => $xproceso_id,'FUNCION_ID' => $xfuncion_id, 'TRX_ID' => $xtrx_id,
                                        'FOLIO'      => $id])
                               ->update([
                                         'NO_VECES' => $regbitacora->NO_VECES = $xno_veces,
                                         'IP_M'     => $regbitacora->IP       = $ip,
                                         'LOGIN_M'  => $regbitacora->LOGIN_M  = $nombre,
                                         'FECHA_M'  => $regbitacora->FECHA_M  = date('Y/m/d')  //date('d/m/Y')
                                        ]);
                toastr()->success('Trx de eliminar jugador del equipo actualizada en Bitacora.','¡Ok!',['positionClass' => 'toast-bottom-right']);
            }   /************ Bitacora termina *************************************/                 
        }       /************* Termina de eliminar *********************************/
        return redirect()->route('verpadron');
    }    

    // exportar a formato excel
    public function actionExportPadronExcel(){
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
        $xfuncion_id  =      3007;
        $xtrx_id      =         5;            // Exportar a formato Excel
        $id           =         0;
        $regbitacora = regBitacoraModel::select('PERIODO_ID',  'MES_ID','PROCESO_ID','FUNCION_ID','TRX_ID','FOLIO', 
                                                'NO_VECES', 'FECHA_REG','IP','LOGIN','FECHA_M','IP_M', 'LOGIN_M')
                        ->where(['PERIODO_ID' => $xperiodo_id,'PROGRAMA_ID' => $xprograma_id,'MES_ID' => $xmes_id, 
                                 'PROCESO_ID' => $xproceso_id,'FUNCION_ID'  => $xfuncion_id, 'TRX_ID' => $xtrx_id, 
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
            $regbitacora = regBitacoraModel::select('NO_VECES','IP_M','LOGIN_M','FECHA_M')
                           ->where(['PERIODO_ID' => $xperiodo_id, 'PROGRAMA_ID' => $xprograma_id, 'MES_ID' => $xmes_id, 
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

        return Excel::download(new ExportPadronExcel, 'equipo_'.date('d-m-Y').'.xlsx');
    }

    // exportar a formato PDF
    public function actionExportPadronPdf(){
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
        $xfuncion_id  =      3007;
        $xtrx_id      =         6;       //Exportar a formato PDF
        $id           =         0;
        $regbitacora = regBitacoraModel::select('PERIODO_ID',  'MES_ID', 'PROCESO_ID', 'FUNCION_ID', 
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
                                     'IP_M' => $regbitacora->IP           = $ip,
                                     'LOGIN_M' => $regbitacora->LOGIN_M   = $nombre,
                                     'FECHA_M' => $regbitacora->FECHA_M   = date('Y/m/d')  //date('d/m/Y')
                                    ]);
            toastr()->success('Bitacora actualizada.','¡Ok!',['positionClass' => 'toast-bottom-right']);
        }   /************ Bitacora termina *************************************/ 

        $regentidades = regEntidadesModel::select('ENTIDADFED_ID','ENTIDADFED_DESC')     
                                           ->get();
        $regmunicipio = regMunicipioModel::select('ENTIDADFEDERATIVAID', 'MUNICIPIOID', 'MUNICIPIONOMBRE')
                                           ->wherein('ENTIDADFEDERATIVAID',[9, 11, 15, 22])
                                           ->get(); 
        $reganios  = regAniosModel::select('ANIO_ID','PERIODO_DESC')
                        ->get();  
        $regmeses     = regMesesModel::select('MES_ID','MES_DESC')->get();      
        $regdias      = regDiasModel::select('DIA_ID','DIA_DESC')->get();                          
        $regservicios = regServicioModel::join('PE_CAT_RUBROS','PE_CAT_RUBROS.RUBRO_ID', '=', 
                                                               'PE_CAT_SERVICIOS.RUBRO_ID')
                        ->select('PE_CAT_SERVICIOS.SERVICIO_ID','PE_CAT_SERVICIOS.SERVICIO_DESC',
                                 'PE_CAT_RUBROS.RUBRO_DESC')
                        ->orderBy('PE_CAT_SERVICIOS.SERVICIO_DESC','ASC')
                        ->orderBy('PE_CAT_RUBROS.RUBRO_DESC','ASC')
                        ->get();                                                                                    
        $regficha       = regFichaModel::select('EQ_ID', 'EQ_DESC','EQ_STATUS1')
                        ->get();                                                        
        $regpadron    = regPadronModel::select('ANIO_ID','FOLIO','EQ_ID','PRIMER_APELLIDO',
                        'SEGUNDO_APELLIDO','NOMBRES','NOMBRE_COMPLETO','CURP','FECHA_NACIMIENTO','EDAD',
                        'FECHA_NACIMIENTO2','FECHA_NACIMIENTO3','PERIODO_ID2','MES_ID2','DIA_ID2','SEXO',
                        'RFC','ID_OFICIAL','DOMICILIO','COLONIA','CP','ENTRE_CALLE','Y_CALLE','OTRA_REFERENCIA',
                        'TP_ID_OFICIAL','NUM_EXT','NUM_INT',
                        'TELEFONO','CELULAR','E_MAIL','ENTIDAD_NAC_ID','ENTIDAD_FED_ID','MUNICIPIO_ID',
                        'LOCALIDAD_ID','LOCALIDAD','EDO_CIVIL_ID','GRADO_ESTUDIOS_ID','FECHA_INGRESO','FECHA_INGRESO2',
                        'ARC_1','ARC_2','ARC_3','ARC_4','ARC_5','ARC_6','ARC_7','ARC_8',
                        'STATUS_1','STATUS_2','FECHA_REG','FECHA_REG2','IP','LOGIN','FECHA_M','FECHA_M2','IP_M','LOGIN_M')
                        ->orderBy('PERIODO_ID','asc')
                        ->orderBy('EQ_ID','asc')
                        ->get();                               
        if($regpadron->count() <= 0){
            toastr()->error('No existen beneficiarios.','Uppss!',['positionClass' => 'toast-bottom-right']);
            return redirect()->route('verPadron');
        }
        $pdf = PDF::loadView('sicinar.pdf.padronPdf', compact('nombre','usuario','regentidades','regmunicipio','regficha','reganios','regpadron','regservicios','regmeses','regdias'));
        //$options = new Options();
        //$options->set('defaultFont', 'Courier');
        //$pdf->set_option('defaultFont', 'Courier');
        $pdf->setPaper('A4', 'landscape');      
        //$pdf->set('defaultFont', 'Courier');          
        //$pdf->setPaper('A4','portrait');

        // Output the generated PDF to Browser
        return $pdf->stream('PadronBeneficiarios');
    }

    // Gráfica por estado
    public function actionPadronxEdo(){
        $nombre       = session()->get('userlog');
        $pass         = session()->get('passlog');
        if($nombre == NULL AND $pass == NULL){
            return view('sicinar.login.expirada');
        }
        $usuario      = session()->get('usuario');
        $rango        = session()->get('rango');
        $ip           = session()->get('ip'); 
        $arbol_id     = session()->get('arbol_id');               

        $regtotxedo=regPadronModel::join('PE_CAT_ENTIDADES_FED','PE_CAT_ENTIDADES_FED.ENTIDADFED_ID','=',
                                                                'CASMEX_METADATO.ENTIDAD_FED_ID')
                    ->selectRaw('COUNT(*) AS TOTALXEDO')
                    ->get();
        $regpadron =regPadronModel::join('PE_CAT_ENTIDADES_FED','PE_CAT_ENTIDADES_FED.ENTIDADFED_ID','=',
                                                                'CASMEX_METADATO.ENTIDAD_FED_ID')
                      ->selectRaw('CASMEX_METADATO.ENTIDAD_FED_ID, 
                                   PE_CAT_ENTIDADES_FED.ENTIDADFED_DESC AS ESTADO, COUNT(*) AS TOTAL')
                      ->groupBy('CASMEX_METADATO.ENTIDAD_FED_ID','PE_CAT_ENTIDADES_FED.ENTIDADFED_DESC')
                      ->orderBy('CASMEX_METADATO.ENTIDAD_FED_ID','asc')
                      ->get();
        //dd($procesos);
        return view('sicinar.numeralia.padronxedo',compact('regpadron','regtotxedo','nombre','usuario','rango'));
    }

    
    // Gráfica por municipio
    public function actionPadronxMpio(){
        $nombre       = session()->get('userlog');
        $pass         = session()->get('passlog');
        if($nombre == NULL AND $pass == NULL){
            return view('sicinar.login.expirada');
        }
        $usuario      = session()->get('usuario');
        $rango        = session()->get('rango');
        $ip           = session()->get('ip');  
        $arbol_id     = session()->get('arbol_id');              

        $regtotxmpio=regPadronModel::join('PE_CAT_MUNICIPIOS_SEDESEM',
                                     [['PE_CAT_MUNICIPIOS_SEDESEM.ENTIDADFEDERATIVAID','=',15],
                                      ['PE_CAT_MUNICIPIOS_SEDESEM.MUNICIPIOID','=','CASMEX_METADATO.MUNICIPIO_ID']
                                      ])
                         ->selectRaw('COUNT(*) AS TOTALXMPIO')
                               ->get();
        $regpadron=regPadronModel::join('PE_CAT_MUNICIPIOS_SEDESEM',
                                      [['PE_CAT_MUNICIPIOS_SEDESEM.ENTIDADFEDERATIVAID','=',15],
                                       ['PE_CAT_MUNICIPIOS_SEDESEM.MUNICIPIOID','=','CASMEX_METADATO.MUNICIPIO_ID']
                                      ])
                      ->selectRaw('CASMEX_METADATO.MUNICIPIO_ID, PE_CAT_MUNICIPIOS_SEDESEM.MUNICIPIONOMBRE AS MUNICIPIO,COUNT(*) AS TOTAL')
                        ->groupBy('CASMEX_METADATO.MUNICIPIO_ID', 'PE_CAT_MUNICIPIOS_SEDESEM.MUNICIPIONOMBRE')
                        ->orderBy('CASMEX_METADATO.MUNICIPIO_ID','asc')
                        ->get();
        //dd($procesos);
        return view('sicinar.numeralia.padronxmpio',compact('regpadron','regtotxmpio','nombre','usuario','rango'));
    }

    // Gráfica por Servicio
    public function actionPadronxServicio(){
        $nombre       = session()->get('userlog');
        $pass         = session()->get('passlog');
        if($nombre == NULL AND $pass == NULL){
            return view('sicinar.login.expirada');
        }
        $usuario      = session()->get('usuario');
        $rango        = session()->get('rango');
        $ip           = session()->get('ip'); 
        $arbol_id     = session()->get('arbol_id');               

        $regtotales=regPadronModel::join('PE_CAT_SERVICIOS','PE_CAT_SERVICIOS.SERVICIO_ID',  '=',
                                                            'CASMEX_METADATO.SERVICIO_ID')
                    ->selectRaw('COUNT(*) AS TOTAL')
                    ->get();
        $regpadron =regPadronModel::join('PE_CAT_SERVICIOS','PE_CAT_SERVICIOS.SERVICIO_ID','=',
                                                            'CASMEX_METADATO.SERVICIO_ID')
                    ->selectRaw('CASMEX_METADATO.SERVICIO_ID, 
                                 PE_CAT_SERVICIOS.SERVICIO_DESC AS SERVICIO, COUNT(*) AS TOTAL')
                    ->groupBy('CASMEX_METADATO.SERVICIO_ID','PE_CAT_SERVICIOS.SERVICIO_DESC')
                    ->orderBy('CASMEX_METADATO.SERVICIO_ID','asc')
                    ->get();
        //dd($regpadron);
        return view('sicinar.numeralia.padronxservicio',compact('nombre','usuario','rango','regpadron','regtotales'));
    }

    // Gráfica x sexo
    public function actionPadronxSexo(){
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
        $regtotal =regPadronModel::selectRaw('COUNT(*) AS TOTAL')
                   ->get();
        $regpadron=regPadronModel::selectRaw('SEXO, COUNT(*) AS TOTAL')
                   ->groupBy('SEXO')
                   ->orderBy('SEXO','asc')
                   ->get();
        //dd($procesos);
        return view('sicinar.numeralia.padronxsexo',compact('regtotal','regpadron','nombre','usuario','rango'));
    }   

    // Gráfica x edad
    public function actionPadronxedad(){
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
        $regtotal =regPadronModel::selectRaw('COUNT(*) AS TOTAL')
                   ->get();
        //$regpadron=regPadronModel::selectRaw('EXTRACT(YEAR FROM SYSDATE) - TO_NUMBER(SUBSTR(FECHA_NACIMIENTO2,7,4)) EDAD,
        //                                      COUNT(1) AS TOTAL')
        //           ->groupBy('EXTRACT(YEAR FROM SYSDATE) - TO_NUMBER(SUBSTR(FECHA_NACIMIENTO2,7,4))')
        $regpadron=regPadronModel::select('PERIODO_ID2')
                   ->selectRaw('EXTRACT(YEAR FROM SYSDATE) - PERIODO_ID2 EDAD,
                                COUNT(1) AS TOTAL')
                   ->groupBy('PERIODO_ID2')                   
                   ->orderBy('TOTAL','asc')
                   ->get();
        //dd($procesos);
        return view('sicinar.numeralia.padronxedad',compact('regtotal','regpadron','nombre','usuario','rango'));
    }   

    // Gráfica x rango de edad
    public function actionPadronxRangoedad(){
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
        $regtotal =regPadronModel::selectRaw('COUNT(*) AS TOTAL')
                   ->get();
        $regpadron=regPadronModel::select('PERIODO_ID')
                   ->selectRaw('SUM(CASE WHEN (EXTRACT(YEAR FROM SYSDATE) - PERIODO_ID2) <= 5                               THEN 1 ELSE 0 END) EMENOSDE5')  
                   ->selectRaw('SUM(CASE WHEN (EXTRACT(YEAR FROM SYSDATE) - PERIODO_ID2) >= 6 AND (EXTRACT(YEAR FROM SYSDATE) - PERIODO_ID2) <=10 THEN 1 ELSE 0 END) E06A10')
                   ->selectRaw('SUM(CASE WHEN (EXTRACT(YEAR FROM SYSDATE) - PERIODO_ID2) >=11 AND (EXTRACT(YEAR FROM SYSDATE) - PERIODO_ID2) <=17 THEN 1 ELSE 0 END) E11A17')
                   ->selectRaw('SUM(CASE WHEN (EXTRACT(YEAR FROM SYSDATE) - PERIODO_ID2) >=18 AND (EXTRACT(YEAR FROM SYSDATE) - PERIODO_ID2) <=30 THEN 1 ELSE 0 END) E18A30')
                   ->selectRaw('SUM(CASE WHEN (EXTRACT(YEAR FROM SYSDATE) - PERIODO_ID2) >=31 AND (EXTRACT(YEAR FROM SYSDATE) - PERIODO_ID2) <=60 THEN 1 ELSE 0 END) E31A60')
                   ->selectRaw('SUM(CASE WHEN (EXTRACT(YEAR FROM SYSDATE) - PERIODO_ID2) >=61                                                    THEN 1 ELSE 0 END) E61YMAS')
                    ->selectRaw('COUNT(*) AS TOTAL')
                    ->groupBy('PERIODO_ID')
                    ->orderBy('PERIODO_ID','asc')
                    ->get();
        //dd($procesos);
        return view('sicinar.numeralia.padronxrangoedad',compact('regtotal','regpadron','nombre','usuario','rango'));
    }        

}
