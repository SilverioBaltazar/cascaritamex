<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

//use App\regPadronModel;
use App\regOscModel;
use App\regPerModel;
use App\regHorasModel;

use App\regReqJuridicoModel;
use App\regBitacoraModel;
use App\regMunicipioModel;
use App\regRubroModel;
use App\regEntidadesModel; 
use App\regVigenciaModel;
use App\regInmuebleedoModel;
use App\regPeriodosaniosModel;
use App\regMesesModel; 
use App\regDiasModel;
//use App\regProgtrabModel;
//use App\regProgdtrabModel;
//use App\regBalanzaModel;
use App\regReqContablesModel;
use App\regReqOperativosModel;
use App\regAgendaModel;

// Exportar a excel 
use App\Exports\ExcelExportCatOSCS;
use Maatwebsite\Excel\Facades\Excel;
// Exportar a pdf
use PDF;
//use Options;

class indicadoresController extends Controller
{

    public function actionBuscarIrse(Request $request)
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
        $regjuridico  = regReqJuridicoModel::select('OSC_ID','PERIODO_ID', 
                        'DOC_ID12','FORMATO_ID12','OSC_D12','PER_ID12','NUM_ID12','OSC_EDO12',
                        'DOC_ID13','FORMATO_ID13','OSC_D13','PER_ID13','NUM_ID13','OSC_EDO13',
                        'DOC_ID14','FORMATO_ID14','OSC_D14','PER_ID14','NUM_ID14','OSC_EDO14',
                        'DOC_ID15','FORMATO_ID15','OSC_D15','PER_ID15','NUM_ID15','OSC_EDO15',
                        'OSC_STATUS')
                        ->orderBy('PERIODO_ID','ASC')                                 
                        ->orderBy('OSC_ID'    ,'ASC')
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
                        ->get();            
        $regcontable  = regReqContablesModel::select('OSC_ID','PERIODO_ID',
                        'DOC_ID6' ,'FORMATO_ID6' ,'OSC_D6' ,'PER_ID6' ,'NUM_ID6' ,'OSC_EDO6',
                        'DOC_ID7' ,'FORMATO_ID7' ,'OSC_D7' ,'PER_ID7' ,'NUM_ID7' ,'OSC_EDO7',
                        'DOC_ID8' ,'FORMATO_ID8' ,'OSC_D8' ,'PER_ID8' ,'NUM_ID8' ,'OSC_EDO8',
                        'DOC_ID9' ,'FORMATO_ID9' ,'OSC_D9' ,'PER_ID9' ,'NUM_ID9' ,'OSC_EDO9',                        
                        'DOC_ID10','FORMATO_ID10','OSC_D10','PER_ID10','NUM_ID10','OSC_EDO10',                        
                        'DOC_ID11','FORMATO_ID11','OSC_D11','PER_ID11','NUM_ID11','OSC_EDO11',
                        'PREG_001','PREG_002','PREG_003','PREG_004','PREG_005','PREG_006',
                        'OSC_STATUS')                                                            
                        ->orderBy('PERIODO_ID','ASC')                                 
                        ->orderBy('OSC_ID'    ,'ASC')
                        ->get();            
        //**************************************************************//
        // ***** busqueda https://github.com/rimorsoft/Search-simple ***//
        // ***** video https://www.youtube.com/watch?v=bmtD9GUaszw   ***//                            
        //**************************************************************//
        $name  = $request->get('name');   
        $idd   = $request->get('idd');  
        $bio   = $request->get('bio');    
        $regosc = regOscModel::where('OSC_STATUS','S')
                  ->orderBy('OSC_ID', 'ASC')
                  ->name($name)           //Metodos personalizados es equvalente a ->where('OSC_DESC', 'LIKE', "%$name%");
                  ->idd($idd)         //Metodos personalizados
                  ->bio($bio)             //Metodos personalizados
                  ->paginate(30);
        if($regosc->count() <= 0){
            toastr()->error('No existen registros de OSCS.','Lo siento!',['positionClass' => 'toast-bottom-right']);
        }            
        return view('sicinar.indicadores.verIrse', compact('nombre','usuario','regosc','regentidades','regmunicipio','regrubro','regvigencia','reginmuebles','regtotactivas','regtotinactivas','regperiodos','regmeses','regdias','regjuridico','regcontable','regoperativo'));
    }


    public function actionVerIrse(){
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
        $regjuridico  = regReqJuridicoModel::select('OSC_ID','PERIODO_ID', 
                        'DOC_ID12','FORMATO_ID12','OSC_D12','PER_ID12','NUM_ID12','OSC_EDO12',
                        'DOC_ID13','FORMATO_ID13','OSC_D13','PER_ID13','NUM_ID13','OSC_EDO13',
                        'DOC_ID14','FORMATO_ID14','OSC_D14','PER_ID14','NUM_ID14','OSC_EDO14',
                        'DOC_ID15','FORMATO_ID15','OSC_D15','PER_ID15','NUM_ID15','OSC_EDO15',
                        'OSC_STATUS')
                        ->orderBy('PERIODO_ID','ASC')                                 
                        ->orderBy('OSC_ID'    ,'ASC')
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
                        ->get();                                    
        $regcontable  = regReqContablesModel::select('OSC_ID','PERIODO_ID',
                        'DOC_ID6' ,'FORMATO_ID6' ,'OSC_D6' ,'PER_ID6' ,'NUM_ID6' ,'OSC_EDO6',
                        'DOC_ID7' ,'FORMATO_ID7' ,'OSC_D7' ,'PER_ID7' ,'NUM_ID7' ,'OSC_EDO7',
                        'DOC_ID8' ,'FORMATO_ID8' ,'OSC_D8' ,'PER_ID8' ,'NUM_ID8' ,'OSC_EDO8',
                        'DOC_ID9' ,'FORMATO_ID9' ,'OSC_D9' ,'PER_ID9' ,'NUM_ID9' ,'OSC_EDO9',                        
                        'DOC_ID10','FORMATO_ID10','OSC_D10','PER_ID10','NUM_ID10','OSC_EDO10',                        
                        'DOC_ID11','FORMATO_ID11','OSC_D11','PER_ID11','NUM_ID11','OSC_EDO11',
                        'PREG_001','PREG_002','PREG_003','PREG_004','PREG_005','PREG_006',
                        'OSC_STATUS')                                                            
                        ->orderBy('PERIODO_ID','ASC')                                 
                        ->orderBy('OSC_ID'    ,'ASC')
                        ->get();                                        
        $regosc       = regOscModel::select('OSC_ID', 'OSC_DESC','OSC_DOM1','OSC_DOM2','OSC_DOM3','MUNICIPIO_ID',
                        'ENTIDADFEDERATIVA_ID','RUBRO_ID','OSC_REGCONS','OSC_RFC','OSC_CP','OSC_FECCONS',
                        'OSC_FECCONS2','OSC_TELEFONO',
                        'OSC_EMAIL','OSC_SWEB','OSC_PRES','OSC_REPLEGAL','OSC_SRIO','OSC_TESORERO','OSC_OBJSOC_1','OSC_OBJSOC_2',
                        'ANIO_ID','OSC_FVP','OSC_FVP2','INM_ID','OSC_FOTO1','OSC_FOTO2',
                        'OSC_GEOREF_LATITUD','OSC_GEOREF_LONGITUD',
                        'PERIODO_ID1','MES_ID1','DIA_ID1','PERIODO_ID2','MES_ID2','DIA_ID2',
                        'OSC_STATUS','OSC_OBS1','OSC_OBS2','OSC_FECCERTIFIC','IP','LOGIN','FECHA_M','IP_M','LOGIN_M')
                        ->orderBy('OSC_ID','ASC')
                        ->paginate(30);
        if($regosc->count() <= 0){
            toastr()->error('No existen OSCS.','Lo siento!',['positionClass' => 'toast-bottom-right']);
            //return redirect()->route('nuevaIap');
        }
        return view('sicinar.indicadores.verIrse',compact('nombre','usuario','regosc','regentidades', 'regmunicipio', 'regrubro','regvigencia','reginmuebles','regtotactivas','regtotinactivas','regperiodos','regmeses','regdias','regjuridico','regcontable','regoperativo'));
    }
 
    // exportar a formato PDF
    public function actionIrsePDF($id, $id2){
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
        $regjuridico  = regReqJuridicoModel::select('OSC_ID','PERIODO_ID', 
                        'DOC_ID12','FORMATO_ID12','OSC_D12','PER_ID12','NUM_ID12','OSC_EDO12',
                        'DOC_ID13','FORMATO_ID13','OSC_D13','PER_ID13','NUM_ID13','OSC_EDO13',
                        'DOC_ID14','FORMATO_ID14','OSC_D14','PER_ID14','NUM_ID14','OSC_EDO14',
                        'DOC_ID15','FORMATO_ID15','OSC_D15','PER_ID15','NUM_ID15','OSC_EDO15',
                        'OSC_STATUS')
                        ->where(  'OSC_ID',$id2)
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
                        ->where(  'PERIODO_ID',$id)
                        ->where(  'OSC_ID'    ,$id2)                        
                        ->get();            
        $regcontable  = regReqContablesModel::select('OSC_ID','PERIODO_ID',
                        'DOC_ID6' ,'FORMATO_ID6' ,'OSC_D6' ,'PER_ID6' ,'NUM_ID6' ,'OSC_EDO6',
                        'DOC_ID7' ,'FORMATO_ID7' ,'OSC_D7' ,'PER_ID7' ,'NUM_ID7' ,'OSC_EDO7',
                        'DOC_ID8' ,'FORMATO_ID8' ,'OSC_D8' ,'PER_ID8' ,'NUM_ID8' ,'OSC_EDO8',
                        'DOC_ID9' ,'FORMATO_ID9' ,'OSC_D9' ,'PER_ID9' ,'NUM_ID9' ,'OSC_EDO9',                        
                        'DOC_ID10','FORMATO_ID10','OSC_D10','PER_ID10','NUM_ID10','OSC_EDO10',                        
                        'DOC_ID11','FORMATO_ID11','OSC_D11','PER_ID11','NUM_ID11','OSC_EDO11',
                        'PREG_001','PREG_002','PREG_003','PREG_004','PREG_005','PREG_006',
                        'OSC_STATUS')                                                            
                        ->where(  'PERIODO_ID',$id)
                        ->where(  'OSC_ID'    ,$id2)                        
                        ->get();
        $regosc       = regOscModel::select('OSC_ID','OSC_DESC','OSC_CALLE','OSC_NUM','OSC_DOM1','OSC_DOM2','OSC_DOM3',
                        'OSC_COLONIA','MUNICIPIO_ID','ENTIDADFEDERATIVA_ID','RUBRO_ID','OSC_REGCONS',
                        'OSC_RFC','OSC_CP','OSC_FECCONS','OSC_FECCONS2','OSC_FECCONS3','OSC_TELEFONO',
                        'OSC_EMAIL','OSC_SWEB','OSC_PRES','OSC_REPLEGAL','OSC_SRIO','OSC_TESORERO',
                        'OSC_OBJSOC_1','OSC_OBJSOC_2','GRUPO_ID','OSC_FECCERTIFIC','OSC_FECCERTIFIC2',
                        'OSC_OTRAREF','OSC_OBS1','OSC_OBS2','ANIO_ID','OSC_FVP','OSC_FVP2','OSC_FVP3',
                        'OSC_FRPP',
                        'INM_ID','PERIODO_ID1','MES_ID1','DIA_ID1','PERIODO_ID2','MES_ID2','DIA_ID2',
                        'OSC_GEOREF_LATITUD','OSC_GEOREF_LONGITUD','OSC_FOTO1','OSC_FOTO2','OSC_STATUS',
                        'OSC_FECREG','OSC_FECREG3','IP','LOGIN','FECHA_M','FECHA_M3','IP_M','LOGIN_M')
                        ->where(  'OSC_ID'    ,$id2)                                
                        ->get();                               
        if($regosc->count() <= 0){
            toastr()->error('No existe OSC.','Uppss!',['positionClass' => 'toast-bottom-right']);
        }else{
            /************ Bitacora inicia *************************************/ 
            setlocale(LC_TIME, "spanish");        
            $xip          = session()->get('ip');
            $xperiodo_id  = (int)date('Y');
            $xprograma_id = 1;
            $xmes_id      = (int)date('m');
            $xproceso_id  =        3;
            $xfuncion_id  =     3005;
            $xtrx_id      =       47;     // pdf
            $id           =      $id;
            $regbitacora = regBitacoraModel::select('PERIODO_ID', 'PROGRAMA_ID', 'MES_ID', 'PROCESO_ID', 
                           'FUNCION_ID', 'TRX_ID', 'FOLIO', 'NO_VECES', 'FECHA_REG', 'IP', 'LOGIN', 
                           'FECHA_M', 'IP_M', 'LOGIN_M')
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
                    toastr()->success('Trx de emisión de IRSE dada de alta EN Bitacora.','¡Ok!',['positionClass' => 'toast-bottom-right']);
                else
                    toastr()->error('Error inesperado EN Trx de IRSE en bitacora. Por favor volver a interlo.','Ups!',['positionClass' => 'toast-bottom-right']);
            }else{                   
                //*********** Obtine el no. de veces *****************************
                $xno_veces = regBitacoraModel::where(['PERIODO_ID' => $xperiodo_id, 'PROGRAMA_ID' => $xprograma_id, 
                                                      'MES_ID'     => $xmes_id,     'PROCESO_ID'  => $xproceso_id, 
                                                      'FUNCION_ID' => $xfuncion_id, 'TRX_ID'      => $xtrx_id, 'FOLIO' => $id2])
                            ->max('NO_VECES');
                $xno_veces = $xno_veces+1;                        
                //*********** Termina de obtener el no de veces *****************************         
                $regbitacora = regBitacoraModel::select('NO_VECES','IP_M','LOGIN_M','FECHA_M')
                               ->where(['PERIODO_ID' => $xperiodo_id, 'PROGRAMA_ID' => $xprograma_id, 
                                        'MES_ID'     => $xmes_id,     'PROCESO_ID'  => $xproceso_id, 
                                        'FUNCION_ID' => $xfuncion_id, 'TRX_ID'      => $xtrx_id, 'FOLIO' => $id2])
                               ->update([
                                        'NO_VECES'=> $regbitacora->NO_VECES = $xno_veces,
                                        'IP_M'    => $regbitacora->IP       = $ip,
                                        'LOGIN_M' => $regbitacora->LOGIN_M  = $nombre,
                                        'FECHA_M' => $regbitacora->FECHA_M  = date('Y/m/d')  //date('d/m/Y')
                                        ]);
                toastr()->success('Trx de emisión IRSE actualizada en Bitacora.','¡Ok!',['positionClass' => 'toast-bottom-right']);
            }   /************ Bitacora termina *************************************/             
            /***************** Genera pdf ******************************/
            $pdf = PDF::loadView('sicinar.pdf.irsePDF', compact('nombre','usuario','regmeses','regdias','regperiodos','regentidades','regmunicipio','regosc','regjuridico','regoprativo','regcontable'));
            //$options = new Options();
            //$options->set('defaultFont', 'Courier');
            //$pdf->set_option('defaultFont', 'Courier');
            //******** Horizontal ***************
            //$pdf->setPaper('A4', 'landscape');      
            //$pdf->set('defaultFont', 'Courier');
            //$pdf->set_options('isPhpEnabled', true);
            //$pdf->setOptions(['isPhpEnabled' => true]);
            //******** vertical *************** 
            //El tamaño de hoja se especifica en page_size puede ser letter, legal, A4, etc.         
            $pdf->setPaper('letter','portrait');
            // Output the generated PDF to Browser
            return $pdf->stream('IncripcionRSE');
        }   // ***************** Termina visita ****************************//
    }


    /********************************************************/
    public function actionBuscarCumplimiento(Request $request)
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
        $regjuridico  = regReqJuridicoModel::select('OSC_ID','PERIODO_ID', 
                        'DOC_ID12','FORMATO_ID12','OSC_D12','PER_ID12','NUM_ID12','OSC_EDO12',
                        'DOC_ID13','FORMATO_ID13','OSC_D13','PER_ID13','NUM_ID13','OSC_EDO13',
                        'DOC_ID14','FORMATO_ID14','OSC_D14','PER_ID14','NUM_ID14','OSC_EDO14',
                        'DOC_ID15','FORMATO_ID15','OSC_D15','PER_ID15','NUM_ID15','OSC_EDO15',
                        'OSC_STATUS')
                        ->orderBy('PERIODO_ID','ASC')                                 
                        ->orderBy('OSC_ID'    ,'ASC')
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
                        ->get();            
        $regcontable  = regReqContablesModel::select('OSC_ID','PERIODO_ID',
                        'DOC_ID6' ,'FORMATO_ID6' ,'OSC_D6' ,'PER_ID6' ,'NUM_ID6' ,'OSC_EDO6',
                        'DOC_ID7' ,'FORMATO_ID7' ,'OSC_D7' ,'PER_ID7' ,'NUM_ID7' ,'OSC_EDO7',
                        'DOC_ID8' ,'FORMATO_ID8' ,'OSC_D8' ,'PER_ID8' ,'NUM_ID8' ,'OSC_EDO8',
                        'DOC_ID9' ,'FORMATO_ID9' ,'OSC_D9' ,'PER_ID9' ,'NUM_ID9' ,'OSC_EDO9',                        
                        'DOC_ID10','FORMATO_ID10','OSC_D10','PER_ID10','NUM_ID10','OSC_EDO10',                        
                        'DOC_ID11','FORMATO_ID11','OSC_D11','PER_ID11','NUM_ID11','OSC_EDO11',
                        'PREG_001','PREG_002','PREG_003','PREG_004','PREG_005','PREG_006',
                        'OSC_STATUS')                                                            
                        ->orderBy('PERIODO_ID','ASC')                                 
                        ->orderBy('OSC_ID'    ,'ASC')
                        ->get();            
        //**************************************************************//
        // ***** busqueda https://github.com/rimorsoft/Search-simple ***//
        // ***** video https://www.youtube.com/watch?v=bmtD9GUaszw   ***//                            
        //**************************************************************//
        $name  = $request->get('name');   
        $idd   = $request->get('idd');  
        $bio   = $request->get('bio');    
        $regosc = regOscModel::where('OSC_STATUS','S')
                  ->orderBy('OSC_ID', 'ASC')
                  ->name($name)           //Metodos personalizados es equvalente a ->where('OSC_DESC', 'LIKE', "%$name%");
                  ->idd($idd)         //Metodos personalizados
                  ->bio($bio)             //Metodos personalizados
                  ->paginate(30);
        if($regosc->count() <= 0){
            toastr()->error('No existen registros de OSCS.','Lo siento!',['positionClass' => 'toast-bottom-right']);
        }            
        return view('sicinar.indicadores.verCumplimiento', compact('nombre','usuario','regosc','regentidades','regmunicipio','regrubro','regvigencia','reginmuebles','regtotactivas','regtotinactivas','regperiodos','regmeses','regdias','regjuridico','regcontable','regoperativo'));
    }


    public function actionVerCumplimiento(){
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
        $regjuridico  = regReqJuridicoModel::select('OSC_ID','PERIODO_ID', 
                        'DOC_ID12','FORMATO_ID12','OSC_D12','PER_ID12','NUM_ID12','OSC_EDO12',
                        'DOC_ID13','FORMATO_ID13','OSC_D13','PER_ID13','NUM_ID13','OSC_EDO13',
                        'DOC_ID14','FORMATO_ID14','OSC_D14','PER_ID14','NUM_ID14','OSC_EDO14',
                        'DOC_ID15','FORMATO_ID15','OSC_D15','PER_ID15','NUM_ID15','OSC_EDO15',
                        'OSC_STATUS')
                        ->orderBy('PERIODO_ID','ASC')                                 
                        ->orderBy('OSC_ID'    ,'ASC')
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
                        ->get();                                    
        $regcontable  = regReqContablesModel::select('OSC_ID','PERIODO_ID',
                        'DOC_ID6' ,'FORMATO_ID6' ,'OSC_D6' ,'PER_ID6' ,'NUM_ID6' ,'OSC_EDO6',
                        'DOC_ID7' ,'FORMATO_ID7' ,'OSC_D7' ,'PER_ID7' ,'NUM_ID7' ,'OSC_EDO7',
                        'DOC_ID8' ,'FORMATO_ID8' ,'OSC_D8' ,'PER_ID8' ,'NUM_ID8' ,'OSC_EDO8',
                        'DOC_ID9' ,'FORMATO_ID9' ,'OSC_D9' ,'PER_ID9' ,'NUM_ID9' ,'OSC_EDO9',                        
                        'DOC_ID10','FORMATO_ID10','OSC_D10','PER_ID10','NUM_ID10','OSC_EDO10',                        
                        'DOC_ID11','FORMATO_ID11','OSC_D11','PER_ID11','NUM_ID11','OSC_EDO11',
                        'PREG_001','PREG_002','PREG_003','PREG_004','PREG_005','PREG_006',
                        'OSC_STATUS')                                                            
                        ->orderBy('PERIODO_ID','ASC')                                 
                        ->orderBy('OSC_ID'    ,'ASC')
                        ->get();                                        
        $regosc       = regOscModel::select('OSC_ID', 'OSC_DESC','OSC_DOM1','OSC_DOM2','OSC_DOM3','MUNICIPIO_ID',
                        'ENTIDADFEDERATIVA_ID','RUBRO_ID','OSC_REGCONS','OSC_RFC','OSC_CP','OSC_FECCONS',
                        'OSC_FECCONS2','OSC_TELEFONO',
                        'OSC_EMAIL','OSC_SWEB','OSC_PRES','OSC_REPLEGAL','OSC_SRIO','OSC_TESORERO','OSC_OBJSOC_1','OSC_OBJSOC_2',
                        'ANIO_ID','OSC_FVP','OSC_FVP2','INM_ID','OSC_FOTO1','OSC_FOTO2',
                        'OSC_GEOREF_LATITUD','OSC_GEOREF_LONGITUD',
                        'PERIODO_ID1','MES_ID1','DIA_ID1','PERIODO_ID2','MES_ID2','DIA_ID2',
                        'OSC_STATUS','OSC_OBS1','OSC_OBS2','OSC_FECCERTIFIC','IP','LOGIN','FECHA_M','IP_M','LOGIN_M')
                        ->orderBy('OSC_ID','ASC')
                        ->paginate(30);
        if($regosc->count() <= 0){
            toastr()->error('No existen OSCS.','Lo siento!',['positionClass' => 'toast-bottom-right']);
            //return redirect()->route('nuevaIap');
        }
        return view('sicinar.indicadores.verCumplimiento',compact('nombre','usuario','regosc','regentidades', 'regmunicipio', 'regrubro','regvigencia','reginmuebles','regtotactivas','regtotinactivas','regperiodos','regmeses','regdias','regjuridico','regcontable','regoperativo'));
    }

    public function actionBuscarmatrizCump(Request $request)
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
        $regjuridico  = regReqJuridicoModel::select('OSC_ID','PERIODO_ID', 
                        'DOC_ID12','FORMATO_ID12','OSC_D12','PER_ID12','NUM_ID12','OSC_EDO12',
                        'DOC_ID13','FORMATO_ID13','OSC_D13','PER_ID13','NUM_ID13','OSC_EDO13',
                        'DOC_ID14','FORMATO_ID14','OSC_D14','PER_ID14','NUM_ID14','OSC_EDO14',
                        'DOC_ID15','FORMATO_ID15','OSC_D15','PER_ID15','NUM_ID15','OSC_EDO15',
                        'OSC_STATUS')
                        ->orderBy('PERIODO_ID','ASC')                                 
                        ->orderBy('OSC_ID'    ,'ASC')
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
                        ->get();                                    
        $regcontable  = regReqContablesModel::select('OSC_ID','PERIODO_ID',
                        'DOC_ID6' ,'FORMATO_ID6' ,'OSC_D6' ,'PER_ID6' ,'NUM_ID6' ,'OSC_EDO6',
                        'DOC_ID7' ,'FORMATO_ID7' ,'OSC_D7' ,'PER_ID7' ,'NUM_ID7' ,'OSC_EDO7',
                        'DOC_ID8' ,'FORMATO_ID8' ,'OSC_D8' ,'PER_ID8' ,'NUM_ID8' ,'OSC_EDO8',
                        'DOC_ID9' ,'FORMATO_ID9' ,'OSC_D9' ,'PER_ID9' ,'NUM_ID9' ,'OSC_EDO9',                        
                        'DOC_ID10','FORMATO_ID10','OSC_D10','PER_ID10','NUM_ID10','OSC_EDO10',                        
                        'DOC_ID11','FORMATO_ID11','OSC_D11','PER_ID11','NUM_ID11','OSC_EDO11',
                        'PREG_001','PREG_002','PREG_003','PREG_004','PREG_005','PREG_006',
                        'OSC_STATUS')                                                            
                        ->orderBy('PERIODO_ID','ASC')                                 
                        ->orderBy('OSC_ID'    ,'ASC')
                        ->get();            
        //**************************************************************//
        // ***** busqueda https://github.com/rimorsoft/Search-simple ***//
        // ***** video https://www.youtube.com/watch?v=bmtD9GUaszw   ***//                            
        //**************************************************************//
        $name  = $request->get('name');   
        $idd   = $request->get('idd');  
        $bio   = $request->get('bio');    
        $regosc = regOscModel::where('OSC_STATUS','S')
                  ->orderBy('OSC_ID', 'ASC')
                  ->name($name)           //Metodos personalizados es equvalente a ->where('OSC_DESC', 'LIKE', "%$name%");
                  ->idd($idd)         //Metodos personalizados
                  ->bio($bio)             //Metodos personalizados
                  ->paginate(300);
        if($regosc->count() <= 0){
            toastr()->error('No existen registros de OSC.','Lo siento!',['positionClass' => 'toast-bottom-right']);
        }            
        return view('sicinar.indicadores.vermatrizCump', compact('nombre','usuario','regosc','regentidades','regmunicipio','regrubro','regvigencia','reginmuebles','regtotactivas','regtotinactivas','regperiodos','regmeses','regdias','regjuridico','regcontable','regoperativo'));
    }

    public function actionVermatrizCump(){
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
        $regjuridico  = regReqJuridicoModel::select('OSC_ID','PERIODO_ID', 
                        'DOC_ID12','FORMATO_ID12','OSC_D12','PER_ID12','NUM_ID12','OSC_EDO12',
                        'DOC_ID13','FORMATO_ID13','OSC_D13','PER_ID13','NUM_ID13','OSC_EDO13',
                        'DOC_ID14','FORMATO_ID14','OSC_D14','PER_ID14','NUM_ID14','OSC_EDO14',
                        'DOC_ID15','FORMATO_ID15','OSC_D15','PER_ID15','NUM_ID15','OSC_EDO15',
                        'OSC_STATUS')
                        ->orderBy('PERIODO_ID','ASC')                                 
                        ->orderBy('OSC_ID'    ,'ASC')
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
                        ->get();                                    
        $regcontable  = regReqContablesModel::select('OSC_ID','PERIODO_ID',
                        'DOC_ID6' ,'FORMATO_ID6' ,'OSC_D6' ,'PER_ID6' ,'NUM_ID6' ,'OSC_EDO6',
                        'DOC_ID7' ,'FORMATO_ID7' ,'OSC_D7' ,'PER_ID7' ,'NUM_ID7' ,'OSC_EDO7',
                        'DOC_ID8' ,'FORMATO_ID8' ,'OSC_D8' ,'PER_ID8' ,'NUM_ID8' ,'OSC_EDO8',
                        'DOC_ID9' ,'FORMATO_ID9' ,'OSC_D9' ,'PER_ID9' ,'NUM_ID9' ,'OSC_EDO9',                        
                        'DOC_ID10','FORMATO_ID10','OSC_D10','PER_ID10','NUM_ID10','OSC_EDO10',                        
                        'DOC_ID11','FORMATO_ID11','OSC_D11','PER_ID11','NUM_ID11','OSC_EDO11',
                        'PREG_001','PREG_002','PREG_003','PREG_004','PREG_005','PREG_006',
                        'OSC_STATUS')                                                            
                        ->orderBy('PERIODO_ID','ASC')                                 
                        ->orderBy('OSC_ID'    ,'ASC')
                        ->get();                                        
        $regosc       = regOscModel::select('OSC_ID', 'OSC_DESC','OSC_DOM1','OSC_DOM2','OSC_DOM3','MUNICIPIO_ID',
                        'ENTIDADFEDERATIVA_ID','RUBRO_ID','OSC_REGCONS','OSC_RFC','OSC_CP','OSC_FECCONS',
                        'OSC_FECCONS2','OSC_TELEFONO',
                        'OSC_EMAIL','OSC_SWEB','OSC_PRES','OSC_REPLEGAL','OSC_SRIO','OSC_TESORERO','OSC_OBJSOC_1','OSC_OBJSOC_2',
                        'ANIO_ID','OSC_FVP','OSC_FVP2','INM_ID','OSC_FOTO1','OSC_FOTO2',
                        'OSC_GEOREF_LATITUD','OSC_GEOREF_LONGITUD',
                        'PERIODO_ID1','MES_ID1','DIA_ID1','PERIODO_ID2','MES_ID2','DIA_ID2',
                        'OSC_STATUS','OSC_OBS1','OSC_OBS2','OSC_FECCERTIFIC','IP','LOGIN','FECHA_M','IP_M','LOGIN_M')
                        ->where(  'OSC_STATUS','S')
                        ->orderBy('OSC_ID'    ,'ASC')
                        ->paginate(300);
        if($regosc->count() <= 0){
            toastr()->error('No existen OSCS.','Lo siento!',['positionClass' => 'toast-bottom-right']);
            //return redirect()->route('nuevaIap');
        }
        return view('sicinar.indicadores.vermatrizCump',compact('nombre','usuario','regosc','regentidades', 'regmunicipio', 'regrubro','regvigencia','reginmuebles','regtotactivas','regtotinactivas','regperiodos','regmeses','regdias','regjuridico','regcontable','regoperativo'));
    }

    public function actionBuscarCumplimientovisitas(Request $request)
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
        $reghoras     = regHorasModel::select('HORA_ID','HORA_DESC')->get();                     
        $regtotactivas= regOscModel::selectRaw('COUNT(*) AS TOTAL_ACTIVAS')
                        ->where('OSC_STATUS','S')
                        ->get();
        $regtotinactivas=regOscModel::selectRaw('COUNT(*) AS TOTAL_INACTIVAS')
                        ->where('OSC_STATUS','N')
                        ->get();    
        $regosc       = regOscModel::select('OSC_ID', 'OSC_DESC','OSC_STATUS')
                        ->where(  'OSC_STATUS','S')
                        ->orderBy('OSC_DESC'  ,'ASC')                                 
                        ->get();       
        //**************************************************************//
        // ***** busqueda https://github.com/rimorsoft/Search-simple ***//
        // ***** video https://www.youtube.com/watch?v=bmtD9GUaszw   ***//
        //**************************************************************//
        $fper  = $request->get('fper');   
        $fmes  = $request->get('fmes');  
        $fdia  = $request->get('fdia');    
        $fiap  = $request->get('fiap');            
        $regprogdil = regAgendaModel::orderBy('PERIODO_ID', 'DESC')
                      ->orderBy('OSC_ID'      , 'ASC')
                      ->orderBy('VISITA_FOLIO', 'ASC')
                      ->fper($fper)    //Metodos personalizados es equivalente a ->where('OSC_DESC', 'LIKE', "%$name%");
                      ->fmes($fmes)    //Metodos personalizados
                      //->fdia($fdia)    //Metodos personalizados
                      ->fiap($fiap)    //Metodos personalizados
                      ->paginate(50);
        if($regprogdil->count() <= 0){
            toastr()->error('No existen registros programados en la agenda.','Lo siento!',['positionClass' => 'toast-bottom-right']);
            //return redirect()->route('nuevaIap');
        }            
        return view('sicinar.indicadores.verCumplimientovisitas', compact('nombre','usuario','regosc','regentidades','regmunicipio','regrubro','regvigencia','reginmuebles','regtotactivas','regtotinactivas','regperiodos','regmeses','regdias','reghoras','regosc','regprogdil'));
    }


    public function actionVerCumplimientovisitas(){
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
        $reghoras     = regHorasModel::select('HORA_ID','HORA_DESC')->get();                                                            
        $regtotactivas= regOscModel::selectRaw('COUNT(*) AS TOTAL_ACTIVAS')
                        ->where('OSC_STATUS','S')
                        ->get();
        $regtotinactivas=regOscModel::selectRaw('COUNT(*) AS TOTAL_INACTIVAS')
                        ->where('OSC_STATUS','N')
                        ->get();       
        $regosc       = regOscModel::select('OSC_ID', 'OSC_DESC','OSC_STATUS')
                        ->where(  'OSC_STATUS','S')    
                        ->orderBy('OSC_DESC'  ,'ASC')                              
                        ->get();     
        $regprogdil   = regAgendaModel::select('VISITA_FOLIO','PERIODO_ID','MES_ID','DIA_ID','HORA_ID','OSC_ID',
                                             'MUNICIPIO_ID','ENTIDAD_ID',
                                             'VISITA_CONTACTO','VISITA_TEL','VISITA_EMAIL','VISITA_DOM','VISITA_OBJ',
                                             'VISITA_SPUB','VISITA_SPUB2','VISITA_AUDITOR2','VISITA_AUDITOR4',
                                             'VISITA_TIPO1','VISITA_FECREGP','VISITA_EDO','VISITA_OBS2','VISITA_OBS3',
                                             'FECREG','IP','LOGIN','FECHA_M','IP_M','LOGIN_M')
                        ->orderBy('PERIODO_ID'  ,'DESC')   
                        ->orderBy('OSC_ID'      ,'ASC')                                                         
                        ->orderBy('VISITA_FOLIO','ASC')

                        ->paginate(30);
        if($regprogdil->count() <= 0){
            toastr()->error('No existen visitas de verificación en la agenda.','Lo siento!',['positionClass' => 'toast-bottom-right']);
        }
        return view('sicinar.indicadores.verCumplimientovisitas',compact('nombre','usuario','regosc','regentidades', 'regmunicipio', 'regrubro','regvigencia','reginmuebles','regtotactivas','regtotinactivas','regperiodos','regmeses','regdias','reghoras','regosc','regprogdil'));
    }




    // Gráfica de IAP por estado
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

        $regtotxedo=regOscModel::join('PE_CAT_ENTIDADES_FED',[['PE_CAT_ENTIDADES_FED.ENTIDADFEDERATIVA_ID','=','JP_OSCS.ENTIDADFEDERATIVA_ID'],['JP_OSCS.OSC_ID','<>',0]])
                         ->selectRaw('COUNT(*) AS TOTALXEDO')
                               ->get();

        $regosc=regOscModel::join('PE_CAT_ENTIDADES_FED',[['PE_CAT_ENTIDADES_FED.ENTIDADFEDERATIVA_ID','=','JP_OSCS.ENTIDADFEDERATIVA_ID'],['JP_OSCS.OSC_ID','<>',0]])
                      ->selectRaw('JP_OSCS.ENTIDADFEDERATIVA_ID, PE_CAT_ENTIDADES_FED.ENTIDADFEDERATIVA_DESC AS ESTADO, COUNT(*) AS TOTAL')
                        ->groupBy('JP_OSCS.ENTIDADFEDERATIVA_ID', 'PE_CAT_ENTIDADES_FED.ENTIDADFEDERATIVA_DESC')
                        ->orderBy('JP_OSCS.ENTIDADFEDERATIVA_ID','asc')
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

        $regtotxmpio=regOscModel::join('PE_CAT_MUNICIPIOS_SEDESEM',[['PE_CAT_MUNICIPIOS_SEDESEM.ENTIDADFEDERATIVAID','=',15],
                                                            ['PE_CAT_MUNICIPIOS_SEDESEM.MUNICIPIOID','=','JP_OSCS.MUNICIPIO_ID'],['JP_OSCS.OSC_ID','<>',0]])
                         ->selectRaw('COUNT(*) AS TOTALXMPIO')
                               ->get();
        $regosc=regOscModel::join('PE_CAT_MUNICIPIOS_SEDESEM',[['PE_CAT_MUNICIPIOS_SEDESEM.ENTIDADFEDERATIVAID','=',15],
                                                            ['PE_CAT_MUNICIPIOS_SEDESEM.MUNICIPIOID','=','JP_OSCS.MUNICIPIO_ID'],['JP_OSCS.OSC_ID','<>',0]])
                      ->selectRaw('JP_OSCS.MUNICIPIO_ID, PE_CAT_MUNICIPIOS_SEDESEM.MUNICIPIONOMBRE AS MUNICIPIO,COUNT(*) AS TOTAL')
                        ->groupBy('JP_OSCS.MUNICIPIO_ID', 'PE_CAT_MUNICIPIOS_SEDESEM.MUNICIPIONOMBRE')
                        ->orderBy('JP_OSCS.MUNICIPIO_ID','asc')
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

        $regtotxrubro=regOscModel::join('JP_CAT_RUBROS','JP_CAT_RUBROS.RUBRO_ID','=','JP_OSCS.RUBRO_ID')
                      ->selectRaw('COUNT(*) AS TOTALXRUBRO')
                            ->get();
        $regosc=regOscModel::join('JP_CAT_RUBROS','JP_CAT_RUBROS.RUBRO_ID','=','JP_OSCS.RUBRO_ID')
                      ->selectRaw('JP_OSCS.RUBRO_ID,  JP_CAT_RUBROS.RUBRO_DESC AS RUBRO, COUNT(*) AS TOTAL')
                        ->groupBy('JP_OSCS.RUBRO_ID','JP_CAT_RUBROS.RUBRO_DESC')
                        ->orderBy('JP_OSCS.RUBRO_ID','asc')
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

        $regtotxrubro=regOscModel::join('JP_CAT_RUBROS','JP_CAT_RUBROS.RUBRO_ID','=','JP_OSCS.RUBRO_ID')
                      ->selectRaw('COUNT(*) AS TOTALXRUBRO')
                            ->get();
        $regosc=regOscModel::join('JP_CAT_RUBROS','JP_CAT_RUBROS.RUBRO_ID','=','JP_OSCS.RUBRO_ID')
                      ->selectRaw('JP_OSCS.RUBRO_ID,  JP_CAT_RUBROS.RUBRO_DESC AS RUBRO, COUNT(*) AS TOTAL')
                        ->groupBy('JP_OSCS.RUBRO_ID','JP_CAT_RUBROS.RUBRO_DESC')
                        ->orderBy('JP_OSCS.RUBRO_ID','asc')
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

        $regtotxrubro=regOscModel::join('JP_CAT_RUBROS','JP_CAT_RUBROS.RUBRO_ID','=','JP_OSCS.RUBRO_ID')
                      ->selectRaw('COUNT(*) AS TOTALXRUBRO')
                            ->get();

        $regosc=regOscModel::join('JP_CAT_RUBROS','JP_CAT_RUBROS.RUBRO_ID','=','JP_OSCS.RUBRO_ID')
                      ->selectRaw('JP_OSCS.RUBRO_ID,  JP_CAT_RUBROS.RUBRO_DESC AS RUBRO, COUNT(*) AS TOTAL')
                        ->groupBy('JP_OSCS.RUBRO_ID','JP_CAT_RUBROS.RUBRO_DESC')
                        ->orderBy('JP_OSCS.RUBRO_ID','asc')
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

        $regtotxrubro=regOscModel::join('JP_CAT_RUBROS','JP_CAT_RUBROS.RUBRO_ID','=','JP_OSCS.RUBRO_ID')
                      ->selectRaw('COUNT(*) AS TOTALXRUBRO')
                            ->get();

        $regosc=regOscModel::join('JP_CAT_RUBROS','JP_CAT_RUBROS.RUBRO_ID','=','JP_OSCS.RUBRO_ID')
                      ->selectRaw('JP_OSCS.RUBRO_ID,  JP_CAT_RUBROS.RUBRO_DESC AS RUBRO, COUNT(*) AS TOTAL')
                        ->groupBy('JP_OSCS.RUBRO_ID','JP_CAT_RUBROS.RUBRO_DESC')
                        ->orderBy('JP_OSCS.RUBRO_ID','asc')
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

        $regtotxrubro=regOscModel::join('JP_CAT_RUBROS','JP_CAT_RUBROS.RUBRO_ID','=','JP_OSCS.RUBRO_ID')
                      ->selectRaw('COUNT(*) AS TOTALXRUBRO')
                            ->get();
        $regosc=regOscModel::join('JP_CAT_RUBROS','JP_CAT_RUBROS.RUBRO_ID','=','JP_OSCS.RUBRO_ID')
                      ->selectRaw('JP_OSCS.RUBRO_ID,  JP_CAT_RUBROS.RUBRO_DESC AS RUBRO, COUNT(*) AS TOTAL')
                        ->groupBy('JP_OSCS.RUBRO_ID','JP_CAT_RUBROS.RUBRO_DESC')
                        ->orderBy('JP_OSCS.RUBRO_ID','asc')
                        ->get();
        //dd($procesos);
        return view('sicinar.numeralia.mapasdeprueba3',compact('regosc','regtotxrubro','nombre','usuario','rango'));
    }

    // exportar a formato PDF
    public function actionOficioIncripPdf($id){
        set_time_limit(0);
        ini_set("memory_limit",-1);
        ini_set('max_execution_time', 0);

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
        $xfuncion_id  =      3005;
        $xtrx_id      =        47;       // formato PDF
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
               toastr()->success('Trx de oficio de inscripción al RSE registrada en Bitacora.','¡Ok!',['positionClass' => 'toast-bottom-right']);
            else
               toastr()->error('Error de Trx de oficio de inscripción al RSE al dar de alta la bitacora. Por favor volver a interlo.','Ups!',['positionClass' => 'toast-bottom-right']);
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
            toastr()->success('Trx de exportar a PDF oficio de inscripción al RSE actualizada en Bitacora.','¡Ok!',['positionClass' => 'toast-bottom-right']);
        }   /************ Bitacora termina *************************************/ 

        $regumedida   = regUmedidaModel::select('UMEDIDA_ID','UMEDIDA_DESC')->get(); 
        $regjuridico  = regReqJuridicoModel::select('OSC_ID','PERIODO_ID', 
                        'DOC_ID12','FORMATO_ID12','OSC_D12','PER_ID12','NUM_ID12','OSC_EDO12',
                        'DOC_ID13','FORMATO_ID13','OSC_D13','PER_ID13','NUM_ID13','OSC_EDO13',
                        'DOC_ID14','FORMATO_ID14','OSC_D14','PER_ID14','NUM_ID14','OSC_EDO14',
                        'DOC_ID15','FORMATO_ID15','OSC_D15','PER_ID15','NUM_ID15','OSC_EDO15',
                        'OSC_STATUS')
                        ->where(  'OSC_ID', $id1)
                        ->orderBy('PERIODO_ID','ASC')                                 
                        ->orderBy('OSC_ID'    ,'ASC')
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
                        ->where(  'OSC_ID', $id1)
                        ->orderBy('PERIODO_ID','ASC')                                 
                        ->orderBy('OSC_ID'    ,'ASC')
                        ->get();                                    
        $regcontable  = regReqContablesModel::select('OSC_ID','PERIODO_ID',
                        'DOC_ID6' ,'FORMATO_ID6' ,'OSC_D6' ,'PER_ID6' ,'NUM_ID6' ,'OSC_EDO6',
                        'DOC_ID7' ,'FORMATO_ID7' ,'OSC_D7' ,'PER_ID7' ,'NUM_ID7' ,'OSC_EDO7',
                        'DOC_ID8' ,'FORMATO_ID8' ,'OSC_D8' ,'PER_ID8' ,'NUM_ID8' ,'OSC_EDO8',
                        'DOC_ID9' ,'FORMATO_ID9' ,'OSC_D9' ,'PER_ID9' ,'NUM_ID9' ,'OSC_EDO9',                        
                        'DOC_ID10','FORMATO_ID10','OSC_D10','PER_ID10','NUM_ID10','OSC_EDO10',                        
                        'DOC_ID11','FORMATO_ID11','OSC_D11','PER_ID11','NUM_ID11','OSC_EDO11',
                        'PREG_001','PREG_002','PREG_003','PREG_004','PREG_005','PREG_006',
                        'OSC_STATUS')                                                            
                        ->where(  'OSC_ID', $id1)
                        ->orderBy('PERIODO_ID','ASC')                                 
                        ->orderBy('OSC_ID'    ,'ASC')
                        ->get();                                        
        //********* Validar rol de usuario **********************/
        if(session()->get('rango') !== '0'){            
            $regosc   = regOscModel::select('OSC_ID', 'OSC_DESC','OSC_DOM1','OSC_DOM2','OSC_DOM3','MUNICIPIO_ID',
                        'ENTIDADFEDERATIVA_ID','RUBRO_ID','OSC_REGCONS','OSC_RFC','OSC_CP','OSC_FECCONS',
                        'OSC_FECCONS2','OSC_TELEFONO',
                        'OSC_EMAIL','OSC_SWEB','OSC_PRES','OSC_REPLEGAL','OSC_SRIO','OSC_TESORERO','OSC_OBJSOC_1','OSC_OBJSOC_2',
                        'ANIO_ID','OSC_FVP','OSC_FVP2','INM_ID','OSC_FOTO1','OSC_FOTO2',
                        'OSC_GEOREF_LATITUD','OSC_GEOREF_LONGITUD',
                        'PERIODO_ID1','MES_ID1','DIA_ID1','PERIODO_ID2','MES_ID2','DIA_ID2',
                        'OSC_STATUS','OSC_OBS1','OSC_OBS2','OSC_FECCERTIFIC','IP','LOGIN','FECHA_M','IP_M','LOGIN_M')
                        ->where(  'OSC_ID', $id1)
                        ->get();
        }else{
            $regosc   = regOscModel::select('OSC_ID', 'OSC_DESC','OSC_DOM1','OSC_DOM2','OSC_DOM3','MUNICIPIO_ID',
                        'ENTIDADFEDERATIVA_ID','RUBRO_ID','OSC_REGCONS','OSC_RFC','OSC_CP','OSC_FECCONS',
                        'OSC_FECCONS2','OSC_TELEFONO',
                        'OSC_EMAIL','OSC_SWEB','OSC_PRES','OSC_REPLEGAL','OSC_SRIO','OSC_TESORERO','OSC_OBJSOC_1','OSC_OBJSOC_2',
                        'ANIO_ID','OSC_FVP','OSC_FVP2','INM_ID','OSC_FOTO1','OSC_FOTO2',
                        'OSC_GEOREF_LATITUD','OSC_GEOREF_LONGITUD',
                        'PERIODO_ID1','MES_ID1','DIA_ID1','PERIODO_ID2','MES_ID2','DIA_ID2',
                        'OSC_STATUS','OSC_OBS1','OSC_OBS2','OSC_FECCERTIFIC','IP','LOGIN','FECHA_M','IP_M','LOGIN_M')
                        ->where(['OSC_ID' => $id1,'OSC_ID' => $arbol_id])
                        //->orderBy('PERIODO_ID','ASC')
                        //->orderBy('FOLIO'     ,'ASC')
                        ->get();            
        }                                       
        //dd('Llave:',$id,' llave2:',$id2);       
        if($regosc->count() <= 0){
            toastr()->error('No existen OSCS.','Lo siento!',['positionClass' => 'toast-bottom-right']);
            return redirect()->route('verCumplimiento');
        }else{
            $pdf = PDF::loadView('sicinar.pdf.OficioPdf',compact('nombre','usuario','regumedida','regosc','regjuridico','regoperativo','regcontable'));
            //$options = new Options();
            //$options->set('defaultFont', 'Courier');
            //$pdf->set_option('defaultFont', 'Courier');
            $pdf->setPaper('A4', 'landscape');      
            //$pdf->set('defaultFont', 'Courier');          
            //$pdf->setPaper('A4','portrait');

            // Output the generated PDF to Browser
            return $pdf->stream('Oficio_inscripcion-'.$id1);
        }
    }


}
