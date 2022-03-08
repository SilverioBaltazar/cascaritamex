<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\valrseRequest;

use App\regOscModel;
use App\regPerModel;
use App\regReqJuridicoModel;
use App\regBitacoraModel;
use App\regMunicipioModel;
use App\regEntidadesModel; 
use App\regVigenciaModel;
use App\regPeriodosaniosModel;
use App\regMesesModel; 
use App\regDiasModel;
use App\regperiodos;         
use App\regFormatosModel;
use App\regReqContablesModel;
use App\regReqOperativosModel;
use App\regReqAutorizadosModel;

// Exportar a excel 
use Maatwebsite\Excel\Facades\Excel;
// Exportar a pdf
use PDF;
//use Options;

class validarrseController extends Controller
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
        $regvigencia  = regVigenciaModel::select('ANIO_ID', 'ANIO_DESC')->get();
        $regperiodos  = regPeriodosaniosModel::select('PERIODO_ID', 'PERIODO_DESC')->get();         
        $regmeses     = regMesesModel::select('MES_ID','MES_DESC')->get();      
        $regdias      = regDiasModel::select('DIA_ID','DIA_DESC')->get();     
        $regformatos  = regFormatosModel::select('FORMATO_ID','FORMATO_DESC')->get();                   
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
                        'DOC_ID1' ,'FORMATO_ID1' ,'OSC_D1' ,'PER_ID1' ,'NUM_ID1' ,'OSC_EDO1',
                        'DOC_ID2' ,'FORMATO_ID2' ,'OSC_D2' ,'PER_ID2' ,'NUM_ID2' ,'OSC_EDO2',            
                        'DOC_ID6' ,'FORMATO_ID6' ,'OSC_D6' ,'PER_ID6' ,'NUM_ID6' ,'OSC_EDO6',
                        'DOC_ID7' ,'FORMATO_ID7' ,'OSC_D7' ,'PER_ID7' ,'NUM_ID7' ,'OSC_EDO7',
                        'DOC_ID8' ,'FORMATO_ID8' ,'OSC_D8' ,'PER_ID8' ,'NUM_ID8' ,'OSC_EDO8',
                        'DOC_ID9' ,'FORMATO_ID9' ,'OSC_D9' ,'PER_ID9' ,'NUM_ID9' ,'OSC_EDO9',                        
                        'DOC_ID10','FORMATO_ID10','OSC_D10','PER_ID10','NUM_ID10','OSC_EDO10',                        
                        'DOC_ID11','FORMATO_ID11','OSC_D11','PER_ID11','NUM_ID11','OSC_EDO11',
                        'OSC_STATUS')                                                            
                        ->orderBy('PERIODO_ID','ASC')                                 
                        ->orderBy('OSC_ID'    ,'ASC')
                        ->get(); 
        $regtotalauto = regReqAutorizadosModel::selectRaw('COUNT(*) AS TOTAL')
                        ->get();                        
        $regautorizados=regReqAutorizadosModel::select('OSC_FOLIO','OSC_ID','PERIODO_ID',
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
                        'OSC_VALIDA','OSC_AUTORIZA','OSC_OBS1','OSC_OBS2','OSC_STATUS',
                        'FECREG','IP','LOGIN','FECHA_M','IP_M','LOGIN_M')
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
        return view('sicinar.validarrse.verIrse', compact('nombre','usuario','regformatos','regosc','regentidades','regmunicipio','regvigencia','regperiodos','regmeses','regdias','regjuridico','regcontable','regoperativo','regautorizados','regtotalauto'));
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
        $regvigencia  = regVigenciaModel::select('ANIO_ID', 'ANIO_DESC')->get();
        $regperiodos  = regPeriodosaniosModel::select('PERIODO_ID', 'PERIODO_DESC')->get();         
        $regmeses     = regMesesModel::select('MES_ID','MES_DESC')->get();      
        $regdias      = regDiasModel::select('DIA_ID','DIA_DESC')->get();  
        $regformatos  = regFormatosModel::select('FORMATO_ID','FORMATO_DESC')->get();
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
                        'DOC_ID1' ,'FORMATO_ID1' ,'OSC_D1' ,'PER_ID1' ,'NUM_ID1' ,'OSC_EDO1',
                        'DOC_ID2' ,'FORMATO_ID2' ,'OSC_D2' ,'PER_ID2' ,'NUM_ID2' ,'OSC_EDO2',            
                        'DOC_ID6' ,'FORMATO_ID6' ,'OSC_D6' ,'PER_ID6' ,'NUM_ID6' ,'OSC_EDO6',
                        'DOC_ID7' ,'FORMATO_ID7' ,'OSC_D7' ,'PER_ID7' ,'NUM_ID7' ,'OSC_EDO7',
                        'DOC_ID8' ,'FORMATO_ID8' ,'OSC_D8' ,'PER_ID8' ,'NUM_ID8' ,'OSC_EDO8',
                        'DOC_ID9' ,'FORMATO_ID9' ,'OSC_D9' ,'PER_ID9' ,'NUM_ID9' ,'OSC_EDO9',                        
                        'DOC_ID10','FORMATO_ID10','OSC_D10','PER_ID10','NUM_ID10','OSC_EDO10',                        
                        'DOC_ID11','FORMATO_ID11','OSC_D11','PER_ID11','NUM_ID11','OSC_EDO11',
                        'OSC_STATUS')                                                            
                        ->orderBy('PERIODO_ID','ASC')                                 
                        ->orderBy('OSC_ID'    ,'ASC')
                        ->get();     
        $regtotalauto = regReqAutorizadosModel::selectRaw('COUNT(*) AS TOTAL')
                        ->get();                                                
        $regautorizados=regReqAutorizadosModel::select('OSC_FOLIO','OSC_ID','PERIODO_ID',
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
                        'OSC_VALIDA','OSC_AUTORIZA','OSC_OBS1','OSC_OBS2','OSC_STATUS',
                        'FECREG','IP','LOGIN','FECHA_M','IP_M','LOGIN_M')
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
        return view('sicinar.validarrse.verIrse',compact('nombre','usuario','regformatos','regosc','regentidades', 'regmunicipio','regvigencia','regperiodos','regmeses','regdias','regjuridico','regcontable','regoperativo','regautorizados','regtotalauto'));
    }

    public function actionNuevoValrse(){
        $nombre       = session()->get('userlog');
        $pass         = session()->get('passlog');
        if($nombre == NULL AND $pass == NULL){
            return view('sicinar.login.expirada');
        }
        $usuario      = session()->get('usuario');
        $rango        = session()->get('rango');
        $ip           = session()->get('ip');
        $arbol_id     = session()->get('arbol_id');        

        $regperiodicidad= regPerModel::select('PER_ID', 'PER_DESC')->orderBy('PER_ID','asc')
                        ->get();   
        $regformatos  = regFormatosModel::select('FORMATO_ID','FORMATO_DESC')->get();
        $regperiodos  = regPeriodosaniosModel::select('PERIODO_ID', 'PERIODO_DESC')
                        ->where('PERIODO_ID','>',2020)
                        ->get();         
        if(session()->get('rango') !== '0'){        
            $regosc   = regOscModel::select('OSC_ID', 'OSC_DESC','OSC_STATUS')->orderBy('OSC_DESC','asc')
                        ->get();
        }else{
            $regosc   = regOscModel::select('OSC_ID', 'OSC_DESC','OSC_STATUS')
                        ->where('OSC_ID',$arbol_id)
                        ->get();            
        }                        
        $regtotalauto = regReqAutorizadosModel::selectRaw('COUNT(*) AS TOTAL')
                        ->get();                                
        $regautorizados=regReqAutorizadosModel::select('OSC_FOLIO','OSC_ID','PERIODO_ID',
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
                        'OSC_VALIDA','OSC_AUTORIZA','OSC_OBS1','OSC_OBS2','OSC_STATUS',
                        'FECREG','IP','LOGIN','FECHA_M','IP_M','LOGIN_M')
                        ->orderBy('OSC_ID','ASC')
                        ->get();        
        //dd($unidades);
        return view('sicinar.validarrse.nuevoValrse',compact('regper','regosc','regperiodos','regperiodicidad','nombre','usuario','regformatos','regautorizados','regtotalauto'));
    }

    public function actionAltaNuevoValrse(Request $request){
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
        $regautorizados=regReqAutorizadosModel::select('OSC_FOLIO','OSC_ID','PERIODO_ID',
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
                        'OSC_VALIDA','OSC_AUTORIZA','OSC_OBS1','OSC_OBS2','OSC_STATUS',
                        'FECREG','IP','LOGIN','FECHA_M','IP_M','LOGIN_M')
                        ->where(['PERIODO_ID' => $request->periodo_id,'OSC_ID' => $request->osc_id])
                        ->get();
        if($regautorizados->count() <= 0 && !empty($request->osc_id)){
            //********** Registrar la alta *****************************/
            $osc_folio = regReqAutorizadosModel::max('OSC_FOLIO');
            $osc_folio = $osc_folio+1;
            $nuevocontable = new regReqAutorizadosModel();

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
            //$file7 =null;
            //if(isset($request->osc_d7)){
            //    if(!empty($request->osc_d7)){
            //        //Comprobar  si el campo act_const tiene un archivo asignado:
            //        if($request->hasFile('osc_d7')){
            //            $file7=$request->osc_id.'_'.$request->file('osc_d7')->getClientOriginalName();
            //            //sube el archivo a la carpeta del servidor public/images/
            //            $request->file('osc_d7')->move(public_path().'/images/', $file7);
            //        }
            //    }
            //}

            $nuevocontable->OSC_FOLIO    = $osc_folio;
            $nuevocontable->PERIODO_ID   = $request->periodo_id;
            $nuevocontable->OSC_ID       = $request->osc_id;        

            $nuevocontable->DOC_ID1      = $request->doc_id1;
            $nuevocontable->FORMATO_ID1  = $request->formato_id1;
            $nuevocontable->OSC_D1       = $file1;        
            $nuevocontable->NUM_ID1      = $request->num_id1;
            $nuevocontable->PER_ID1      = $request->per_id1;        
            $nuevocontable->OSC_EDO1     = $request->osc_edo1;

            $nuevocontable->OSC_VALIDA   = substr(trim(strtoupper($request->osc_valida))  ,0,  99);
            $nuevocontable->OSC_AUTORIZA = substr(trim(strtoupper($request->osc_autoriza)),0,  99);
            $nuevocontable->OSC_OBS1     = substr(trim(strtoupper($request->osc_obs1))    ,0,3999);

            //$nuevocontable->DOC_ID7    = $request->doc_id7;
            //$nuevocontable->FORMATO_ID7= $request->formato_id7;
            //$nuevocontable->OSC_D7     = $file7;        
            //$nuevocontable->NUM_ID7    = 1;   // 1     $request->num_id7;
            //$nuevocontable->PER_ID7    = 5;   // Anual $request->per_id7;        
            ////$nuevocontable->OSC_EDO7 = $request->osc_edo7;

            $nuevocontable->IP           = $ip;
            $nuevocontable->LOGIN        = $nombre;         // Usuario ;
            $nuevocontable->save();

            if($nuevocontable->save() == true){
                toastr()->success('Trx de validar requisitos registrada.','ok!',['positionClass' => 'toast-bottom-right']);

                /************ Bitacora inicia *************************************/ 
                setlocale(LC_TIME, "spanish");        
                $xip          = session()->get('ip');
                $xperiodo_id  = (int)date('Y');
                $xprograma_id = 1;
                $xmes_id      = (int)date('m');
                $xproceso_id  =         3;
                $xfuncion_id  =      3005;
                $xtrx_id      =        47;    //alta
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
                       toastr()->success('Trx de validar requisitos dada de alta en bitacora.','¡Ok!',['positionClass' => 'toast-bottom-right']);
                    else
                       toastr()->error('Error trx de validar requisitos al dar de alta en bitacora. Por favor volver a interlo.','Ups!',['positionClass' => 'toast-bottom-right']);
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
                    toastr()->success('Trx de validar requisitos actualizada en Bitacora.','¡Ok!',['positionClass' => 'toast-bottom-right']);
                }   /************ Bitacora termina *************************************/                 
            }else{
                toastr()->error('Error en trx de validar requisitos en bitacora. Por favor volver a interlo.','Ups!',['positionClass' => 'toast-bottom-right']);
            }   //******************** Termina la alta ************************/ 
        }else{
            toastr()->error('Ya existe trx de validar de requisitos.','Por favor editar, lo siento!',['positionClass' => 'toast-bottom-right']);
        }   // Termian If de busqueda ****************
        return redirect()->route('verirse');
    }

    /****************** Editar registro  **********/
    public function actionEditarValrse($id){
        $nombre        = session()->get('userlog');
        $pass          = session()->get('passlog');
        if($nombre == NULL AND $pass == NULL){
            return view('sicinar.login.expirada');
        }
        $usuario       = session()->get('usuario');
        $rango         = session()->get('rango');
        $arbol_id      = session()->get('arbol_id');        

        $regperiodicidad= regPerModel::select('PER_ID', 'PER_DESC')->orderBy('PER_ID','asc')
                        ->get();   
        $regformatos  = regFormatosModel::select('FORMATO_ID','FORMATO_DESC')->get();
        $regperiodos  = regPeriodosaniosModel::select('PERIODO_ID', 'PERIODO_DESC')
                        ->where('PERIODO_ID','>',2020)
                        ->get();    
        $regosc       = regOscModel::select('OSC_ID', 'OSC_DESC','OSC_STATUS')->orderBy('OSC_DESC','asc')
                        ->get();
        $regautorizados=regReqAutorizadosModel::select('OSC_FOLIO','OSC_ID','PERIODO_ID',
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
                        'OSC_VALIDA','OSC_AUTORIZA','OSC_OBS1','OSC_OBS2','OSC_STATUS',
                        'FECREG','IP','LOGIN','FECHA_M','IP_M','LOGIN_M')
                        ->where('OSC_FOLIO', $id)
                        ->first();
        if($regautorizados->count() <= 0){
            toastr()->error('No existe TRX de validar requisitos.','Lo siento!',['positionClass' => 'toast-bottom-right']);
            //return redirect()->route('nuevocontable');
        }
        return view('sicinar.validarrse.editarValrse',compact('nombre','usuario','regosc','regautorizados','regperiodos','regperiodicidad','regformatos','regautorizados'));

    }
    
    public function actionActualizarValrse(valrseRequest $request, $id){
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
        $regautorizados = regReqAutorizadosModel::where('OSC_FOLIO',$id);
        if($regautorizados->count() <= 0)
            toastr()->error('No existen TRX de validar requisitos.','¡Por favor volver a intentar!',['positionClass' => 'toast-bottom-right']);
        else{        
            //****************** Actualizar **************************/
            //echo "Escribió en el campo de texto 1: " .'-'. $request->osc_d9 .'-'. "<br><br>"; 
            //echo "Escribió en el campo de texto 1: " . $request->osc_d9 . "<br><br>"; 
            //Comprobar  si el campo foto1 tiene un archivo asignado:
            $name1 =null;
            if($request->hasFile('osc_d1')){
                echo "Escribió en el campo de texto 1: " .'-'. $request->osc_d1 .'-'. "<br><br>"; 
                $name1 = $id.'_'.$request->file('osc_d1')->getClientOriginalName(); 
                //sube el archivo a la carpeta del servidor public/images/
                $request->file('osc_d1')->move(public_path().'/images/', $name1);

                // ************* Actualizamos registro **********************************/
                $regautorizados= regReqAutorizadosModel::where('OSC_FOLIO',$id)        
                           ->update([                
                                     //'PERIODO_ID' => $request->periodo_id,
                                     'DOC_ID1'      => $request->doc_id1,
                                     'FORMATO_ID1'  => $request->formato_id1,                            
                                     'OSC_D1'       => $name1,                                                       
                                     'PER_ID1'      => $request->per_id1,
                                     'NUM_ID1'      => $request->num_id1,                
                                     'OSC_EDO1'     => $request->osc_edo1,

                                     'OSC_VALIDA'   => substr(trim(strtoupper($request->osc_valida))  ,0,  99),        
                                     'OSC_AUTORIZA' => substr(trim(strtoupper($request->osc_autoriza)),0,  99),        
                                     'OSC_OBS1'     => substr(trim(strtoupper($request->osc_obs1))    ,0,3999),        

                                     //'OSC_STATUS' => $request->osc_status,
                                     'IP_M'         => $ip,
                                     'LOGIN_M'      => $nombre,
                                     'FECHA_M2'     => date('Y/m/d'),
                                     'FECHA_M'      => date('Y/m/d')    //date('d/m/
                                    ]);
                toastr()->success('Validación actualizada.','¡Ok!',['positionClass' => 'toast-bottom-right']);
            }else{                
                // ************* Actualizamos registro **********************************/
                $regautorizados= regReqAutorizadosModel::where('OSC_FOLIO',$id)        
                           ->update([                
                                     //'PERIODO_ID' => $request->periodo_id,
                                     'DOC_ID1'      => $request->doc_id1,
                                     'FORMATO_ID1'  => $request->formato_id1,                            
                                     //'OSC_D1'     => $name1,                
                                     'PER_ID1'      => $request->per_id1,
                                     'NUM_ID1'      => $request->num_id1,                
                                     'OSC_EDO1'     => $request->osc_edo1,

                                     'OSC_VALIDA'   => substr(trim(strtoupper($request->osc_valida))  ,0,  99),        
                                     'OSC_AUTORIZA' => substr(trim(strtoupper($request->osc_autoriza)),0,  99),        
                                     'OSC_OBS1'     => substr(trim(strtoupper($request->osc_obs1))    ,0,3999),        

                                     //'OSC_STATUS' => $request->osc_status,
                                     'IP_M'         => $ip,
                                     'LOGIN_M'      => $nombre,
                                     'FECHA_M2'     => date('Y/m/d'),
                                     'FECHA_M'      => date('Y/m/d')    //date('d/m/
                                    ]);
                toastr()->success('Validación actualizada.','¡Ok!',['positionClass' => 'toast-bottom-right']);                
            }                            

            /************ Bitacora inicia *************************************/ 
            setlocale(LC_TIME, "spanish");        
            $xip          = session()->get('ip');
            $xperiodo_id  = (int)date('Y');
            $xprograma_id = 1;
            $xmes_id      = (int)date('m');
            $xproceso_id  =         3;
            $xfuncion_id  =      3005;
            $xtrx_id      =        48;    //Actualizar         
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
                    toastr()->success('Trx de validación de requisitos dada de alta en Bitacora.','¡Ok!',['positionClass' => 'toast-bottom-right']);
                else
                    toastr()->error('Error de trxt de validación de requisitos al dar de alta en bitacora. Por favor volver a interlo.','Ups!',['positionClass' => 'toast-bottom-right']);
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
                toastr()->success('Trx de validación de requsitos actualizada en Bitacora.','¡Ok!',['positionClass' => 'toast-bottom-right']);
            }   /************ Bitacora termina *************************************/         
        }       /************ Termina actualizar ***********************************/
        return redirect()->route('verirse');
    }

     //***** Borrar registro completo ***********************
    public function actionBorrarValrse($id){
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
        $regautorizados= regReqAutorizadosModel::where('OSC_FOLIO',$id);
        if($regautorizados->count() <= 0)
            toastr()->error('No existe folio de validación de requisitos.','¡Por favor volver a intentar!',['positionClass' => 'toast-bottom-right']);
        else{        
            $regautorizados->delete();
            toastr()->success('Trx de validar requisitos eliminado.','¡Ok!',['positionClass' => 'toast-bottom-right']);

            //echo 'Ya entre aboorar registro..........';
            /************ Bitacora inicia *************************************/ 
            setlocale(LC_TIME, "spanish");        
            $xip          = session()->get('ip');
            $xperiodo_id  = (int)date('Y');
            $xprograma_id = 1;
            $xmes_id      = (int)date('m');
            $xproceso_id  =         3;
            $xfuncion_id  =      3005;
            $xtrx_id      =        49;     // borrar 
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
                    toastr()->success('Trx de eliminar validación de requisitos dado de alta en Bitacora.','¡Ok!',['positionClass' => 'toast-bottom-right']);
                else
                    toastr()->error('Error inesperado ne trx de eliminar validación de requisitos al dar de alta la bitacora. Por favor volver a interlo.','Ups!',['positionClass' => 'toast-bottom-right']);
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
                                         'NO_VECES'=> $regbitacora->NO_VECES = $xno_veces,
                                         'IP_M'    => $regbitacora->IP       = $ip,
                                         'LOGIN_M' => $regbitacora->LOGIN_M  = $nombre,
                                         'FECHA_M' => $regbitacora->FECHA_M  = date('Y/m/d')  //date('d/m/Y')
                                        ]);
                toastr()->success('Trx de validación de requisitos actualizada en Bitacora.','¡Ok!',['positionClass' => 'toast-bottom-right']);
            }   /************ Bitacora termina *************************************/    
        }       /************* Termina de eliminar  *********************************/
        return redirect()->route('verirse');
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
        $regvigencia  = regVigenciaModel::select('ANIO_ID', 'ANIO_DESC')->get();
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
                        'DOC_ID1' ,'FORMATO_ID1' ,'OSC_D1' ,'PER_ID1' ,'NUM_ID1' ,'OSC_EDO1',
                        'DOC_ID2' ,'FORMATO_ID2' ,'OSC_D2' ,'PER_ID2' ,'NUM_ID2' ,'OSC_EDO2',            
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
            $xtrx_id      =       50;     // pdf
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
                    toastr()->error('Error inesperado en Trx de IRSE en bitacora. Por favor volver a interlo.','Ups!',['positionClass' => 'toast-bottom-right']);
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

}
