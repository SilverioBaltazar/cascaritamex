<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

//use App\Http\Requests\oscRequest;
//use App\Http\Requests\osc1Request;
//use App\Http\Requests\osc2Request;
//use App\Http\Requests\osc5Request;

use App\regBitacoraModel;
use App\regMunicipioModel;
use App\regCategoriasModel;
use App\regRegionesModel;
use App\regPeriodosModel;
use App\regMesesModel;
use App\regDiasModel;
use App\regfichaModel;

// Exportar a excel 
//use App\Exports\ExcelExportOsc;
use Maatwebsite\Excel\Facades\Excel;
// Exportar a pdf
use PDF;
//use Options;

class estadisticaEqController extends Controller
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

    //*********************************************************************************//
    //************************* Estadísticas ******************************************//
    //*********************************************************************************//
    // Estadística por Region
    public function actionEqxRegion(){
        $nombre       = session()->get('userlog');
        $pass         = session()->get('passlog');
        if($nombre == NULL AND $pass == NULL){
            return view('sicinar.login.expirada');
        }
        $usuario      = session()->get('usuario');
        $rango        = session()->get('rango');
        $ip           = session()->get('ip'); 
        $arbol_id     = session()->get('arbol_id');               

        $regfichaxreg = regfichaModel::join('CASMEX_CAT_REGIONES',
                                            'CASMEX_CAT_REGIONES.REGION_ID','=','CASMEX_FICHA_EQUIPO.REGION_ID')
                        ->selectRaw('COUNT(*) AS TOTALXREGION')
                        ->orderBy('CASMEX_FICHA_EQUIPO.REGION_ID','ASC')
                        ->get();        
        $regficha     = regfichaModel::join('CASMEX_CAT_REGIONES',
                                            'CASMEX_CAT_REGIONES.REGION_ID','=','CASMEX_FICHA_EQUIPO.REGION_ID')
                        ->selectRaw('CASMEX_FICHA_EQUIPO.REGION_ID, 
                                     CASMEX_CAT_REGIONES.DESC_REGION,
                                     CASMEX_CAT_REGIONES.ROMA_REGION,
                                     COUNT(*) AS TOTAL')
                        ->groupBy(  'CASMEX_FICHA_EQUIPO.REGION_ID',
                                    'CASMEX_CAT_REGIONES.DESC_REGION',
                                    'CASMEX_CAT_REGIONES.ROMA_REGION'                            
                                 )                        
                        ->orderBy(  'CASMEX_FICHA_EQUIPO.REGION_ID','ASC')
                        ->get();                                
        //dd($procesos);
        return view('sicinar.numeralia.eqxregion',compact('nombre','usuario','rango','regficha','regfichaxreg'));
    }

    // Estadistica por municipio    
    public function actionEqxMpio(){
        $nombre       = session()->get('userlog');
        $pass         = session()->get('passlog');
        if($nombre == NULL AND $pass == NULL){
            return view('sicinar.login.expirada');
        }
        $usuario      = session()->get('usuario');
        $rango        = session()->get('rango');
        $ip           = session()->get('ip');  
        $arbol_id     = session()->get('arbol_id');  

        $regtotxmpio  = regfichaModel::join('CASMEX_CAT_MUNICIPIOS_SEDESEM',
                                           [['CASMEX_CAT_MUNICIPIOS_SEDESEM.MUNICIPIOID','=','CASMEX_FICHA_EQUIPO.MUNICIPIO_ID'],
                                            ['CASMEX_CAT_MUNICIPIOS_SEDESEM.ENTIDADFEDERATIVAID','=',15]
                                           ])
                        ->selectRaw('COUNT(*) AS TOTALXMPIO')
                        ->orderBy('CASMEX_CAT_MUNICIPIOS_SEDESEM.MUNICIPIOID','ASC')
                        ->get();      
        $regficha     = regfichaModel::join('CASMEX_CAT_MUNICIPIOS_SEDESEM',
                                          [['CASMEX_CAT_MUNICIPIOS_SEDESEM.MUNICIPIOID','=','CASMEX_FICHA_EQUIPO.MUNICIPIO_ID'],
                                           ['CASMEX_CAT_MUNICIPIOS_SEDESEM.ENTIDADFEDERATIVAID','=',15]
                                        ])
                        ->selectRaw(        'CASMEX_FICHA_EQUIPO.MUNICIPIO_ID,
                                             CASMEX_CAT_MUNICIPIOS_SEDESEM.MUNICIPIONOMBRE AS MUNICIPIO,
                                             COUNT(*) AS TOTAL'
                                    )
                         ->groupBy(         'CASMEX_FICHA_EQUIPO.MUNICIPIO_ID',
                                            'CASMEX_CAT_MUNICIPIOS_SEDESEM.MUNICIPIONOMBRE'
                                  )
                        ->orderBy(          'CASMEX_FICHA_EQUIPO.MUNICIPIO_ID','ASC')
                        ->get();      
        //dd($procesos);
        return view('sicinar.numeralia.eqxmpio',compact('nombre','usuario','rango','regtotxmpio','regficha'));
    }    

    // Estadisticas por Categoria
    public function actionEqxCate(){
        $nombre       = session()->get('userlog');
        $pass         = session()->get('passlog');
        if($nombre == NULL AND $pass == NULL){
            return view('sicinar.login.expirada');
        }
        $usuario      = session()->get('usuario');
        $rango        = session()->get('rango');
        $ip           = session()->get('ip'); 
        $arbol_id     = session()->get('arbol_id');               

        $regtotxcate  = regfichaModel::join('CASMEX_CAT_CATEGORIAS ','CASMEX_CAT_CATEGORIAS.CATE_ID','=','CASMEX_FICHA_EQUIPO.CATE_ID')
                        ->selectRaw('COUNT(*) AS TOTALXCATE')
                        ->orderBy('CASMEX_CAT_CATEGORIAS.CATE_ID','ASC')
                        ->get();      
        $regficha     = regfichaModel::join('CASMEX_CAT_CATEGORIAS ','CASMEX_CAT_CATEGORIAS.CATE_ID','=','CASMEX_FICHA_EQUIPO.CATE_ID')
                        ->selectRaw(        'CASMEX_FICHA_EQUIPO.CATE_ID,CASMEX_CAT_CATEGORIAS.CATE_DESC,COUNT(*) AS TOTAL')
                        ->groupBy(          'CASMEX_FICHA_EQUIPO.CATE_ID','CASMEX_CAT_CATEGORIAS.CATE_DESC')
                        ->orderBy(          'CASMEX_FICHA_EQUIPO.CATE_ID','ASC')
                        ->get();
        //dd($procesos);
        return view('sicinar.numeralia.eqxcategoria',compact('nombre','usuario','rango','regficha','regtotxcate'));
    }

    // Estadistica por Rama
    public function actionEqxRama(){
        $nombre       = session()->get('userlog');
        $pass         = session()->get('passlog');
        if($nombre == NULL AND $pass == NULL){
            return view('sicinar.login.expirada');
        }
        $usuario      = session()->get('usuario');
        $rango        = session()->get('rango');
        $ip           = session()->get('ip'); 
        $arbol_id     = session()->get('arbol_id');               

        $regtotxrama  = regFichaModel::selectRaw('COUNT(*) AS TOTALXRAMA')
                        ->orderBy('EQ_RAMA','ASC')
                        ->get();   
        $regficha     = regFichaModel::selectRaw("EQ_RAMA, COUNT(*) AS TOTAL")
                        ->groupBy(    'EQ_RAMA')
                        ->orderBy(    'EQ_RAMA','ASC')
                        ->get();
        //dd($regficha);
        return view('sicinar.numeralia.eqxrama',compact('nombre','usuario','rango','regtotxrama','regficha'));
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

        $regmeses     = regMesesModel::select('MES_ID','MES_DESC')
                        ->get();   
        $regperiodos  = regPeriodosModel::select('PERIODO_ID', 'PERIODO_DESC')
                        ->get();        
        if($regperiodos->count() <= 0){
            toastr()->error('No existen periodos fiscales.','Lo siento!',['positionClass' => 'toast-bottom-right']);
        }
        return view('sicinar.osc.verBitacora',compact('nombre','usuario','regmeses','regperiodos'));
    }

    // Gráfica de transacciones (Bitacora)
    public function actionBitacora(Request $request){
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
        $regperiodos  = regPeriodosModel::select('PERIODO_ID', 'PERIODO_DESC')
                        ->get();                
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
        $regbitatot=regBitacoraModel::join('CASMEX_CAT_PROCESOS', 'CASMEX_CAT_PROCESOS.PROCESO_ID' ,'=','CASMEX_BITACORA.PROCESO_ID')
                                   ->join( 'CASMEX_CAT_FUNCIONES','CASMEX_CAT_FUNCIONES.FUNCION_ID','=','CASMEX_BITACORA.FUNCION_ID')
                                   ->join( 'CASMEX_CAT_TRX'      ,'CASMEX_CAT_TRX.TRX_ID'          ,'=','CASMEX_BITACORA.TRX_ID')
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

    // Georeferenciación por municipio
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

        $regficha     = regfichaModel::join('CASMEX_CAT_MUNICIPIOS_SEDESEM',
                                          [['CASMEX_CAT_MUNICIPIOS_SEDESEM.MUNICIPIOID','=','CASMEX_FICHA_EQUIPO.MUNICIPIO_ID'],
                                           ['CASMEX_CAT_MUNICIPIOS_SEDESEM.ENTIDADFEDERATIVAID','=',15]
                                        ])
                        ->select(   'CASMEX_FICHA_EQUIPO.MUNICIPIO_ID',
                                    'CASMEX_CAT_MUNICIPIOS_SEDESEM.MUNICIPIONOMBRE AS MUNICIPIO', 
                                    'CASMEX_CAT_MUNICIPIOS_SEDESEM.GEOREF_LATDECIMAL', 
                                    'CASMEX_CAT_MUNICIPIOS_SEDESEM.GEOREF_LONGDECIMAL')
                        ->selectRaw('COUNT(*) AS TOTAL')
                        ->groupBy(  'CASMEX_FICHA_EQUIPO.MUNICIPIO_ID',        
                                    'CASMEX_CAT_MUNICIPIOS_SEDESEM.MUNICIPIONOMBRE', 
                                    'CASMEX_CAT_MUNICIPIOS_SEDESEM.GEOREF_LATDECIMAL', 
                                    'CASMEX_CAT_MUNICIPIOS_SEDESEM.GEOREF_LONGDECIMAL')
                        ->orderBy('CASMEX_FICHA_EQUIPO.MUNICIPIO_ID'        ,'asc')
                        ->get();
        //dd($procesos);
        return view('sicinar.numeralia.mapaxmpio',compact('nombre','usuario','rango','regficha'));
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

        $regtotxrubro=regOscModel::join('CASMEX_CAT_RUBROS','CASMEX_CAT_RUBROS.RUBRO_ID','=','CASMEX_OSC.RUBRO_ID')
                      ->selectRaw('COUNT(*) AS TOTALXRUBRO')
                            ->get();

        $regosc=regOscModel::join('CASMEX_CAT_RUBROS','CASMEX_CAT_RUBROS.RUBRO_ID','=','CASMEX_OSC.RUBRO_ID')
                      ->selectRaw('CASMEX_OSC.RUBRO_ID,  CASMEX_CAT_RUBROS.RUBRO_DESC AS RUBRO, COUNT(*) AS TOTAL')
                        ->groupBy('CASMEX_OSC.RUBRO_ID','CASMEX_CAT_RUBROS.RUBRO_DESC')
                        ->orderBy('CASMEX_OSC.RUBRO_ID','asc')
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

        $regtotxrubro=regOscModel::join('CASMEX_CAT_RUBROS','CASMEX_CAT_RUBROS.RUBRO_ID','=','CASMEX_OSC.RUBRO_ID')
                      ->selectRaw('COUNT(*) AS TOTALXRUBRO')
                            ->get();

        $regosc=regOscModel::join('CASMEX_CAT_RUBROS','CASMEX_CAT_RUBROS.RUBRO_ID','=','CASMEX_OSC.RUBRO_ID')
                      ->selectRaw('CASMEX_OSC.RUBRO_ID,  CASMEX_CAT_RUBROS.RUBRO_DESC AS RUBRO, COUNT(*) AS TOTAL')
                        ->groupBy('CASMEX_OSC.RUBRO_ID','CASMEX_CAT_RUBROS.RUBRO_DESC')
                        ->orderBy('CASMEX_OSC.RUBRO_ID','asc')
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

        $regtotxrubro=regOscModel::join('CASMEX_CAT_RUBROS','CASMEX_CAT_RUBROS.RUBRO_ID','=','CASMEX_OSC.RUBRO_ID')
                      ->selectRaw('COUNT(*) AS TOTALXRUBRO')
                            ->get();
        $regosc=regOscModel::join('CASMEX_CAT_RUBROS','CASMEX_CAT_RUBROS.RUBRO_ID','=','CASMEX_OSC.RUBRO_ID')
                      ->selectRaw('CASMEX_OSC.RUBRO_ID,  CASMEX_CAT_RUBROS.RUBRO_DESC AS RUBRO, COUNT(*) AS TOTAL')
                        ->groupBy('CASMEX_OSC.RUBRO_ID','CASMEX_CAT_RUBROS.RUBRO_DESC')
                        ->orderBy('CASMEX_OSC.RUBRO_ID','asc')
                        ->get();
        //dd($procesos);
        return view('sicinar.numeralia.mapasdeprueba3',compact('regosc','regtotxrubro','nombre','usuario','rango'));
    }

}
