<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\regOscModel;
use App\regPadronModel;
use App\regBitacoraModel;
use App\regMunicipioModel;
use App\regEntidadesModel; 
use App\regServicioModel;
use App\regRubrosModel;
use App\regPeriodosaniosModel;
use App\regMesesModel;

// Exportar a excel 
use Maatwebsite\Excel\Facades\Excel;
// Exportar a pdf
use PDF;
//use Options;

class estadisticaPadronController extends Controller
{

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

        $regtotxedo=regPadronModel::join('PE_CAT_ENTIDADES_FED','PE_CAT_ENTIDADES_FED.ENTIDADFEDERATIVA_ID','=',
                                                                'PE_METADATO_PADRON.ENTIDAD_FED_ID')
                    ->selectRaw('COUNT(*) AS TOTALXEDO')
                    ->get();
        $regpadron =regPadronModel::join('PE_CAT_ENTIDADES_FED','PE_CAT_ENTIDADES_FED.ENTIDADFEDERATIVA_ID','=',
                                                                'PE_METADATO_PADRON.ENTIDAD_FED_ID')
                      ->selectRaw('PE_METADATO_PADRON.ENTIDAD_FED_ID, 
                                   PE_CAT_ENTIDADES_FED.ENTIDADFEDERATIVA_DESC AS ESTADO, COUNT(*) AS TOTAL')
                      ->groupBy('PE_METADATO_PADRON.ENTIDAD_FED_ID','PE_CAT_ENTIDADES_FED.ENTIDADFEDERATIVA_DESC')
                      ->orderBy('PE_METADATO_PADRON.ENTIDAD_FED_ID','asc')
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
                                      ['PE_CAT_MUNICIPIOS_SEDESEM.MUNICIPIOID','=','PE_METADATO_PADRON.MUNICIPIO_ID']
                                      ])
                         ->selectRaw('COUNT(*) AS TOTALXMPIO')
                               ->get();
        $regpadron=regPadronModel::join('PE_CAT_MUNICIPIOS_SEDESEM',
                                      [['PE_CAT_MUNICIPIOS_SEDESEM.ENTIDADFEDERATIVAID','=',15],
                                       ['PE_CAT_MUNICIPIOS_SEDESEM.MUNICIPIOID','=','PE_METADATO_PADRON.MUNICIPIO_ID']
                                      ])
                      ->selectRaw('PE_METADATO_PADRON.MUNICIPIO_ID,  PE_CAT_MUNICIPIOS_SEDESEM.MUNICIPIONOMBRE AS MUNICIPIO,COUNT(*) AS TOTAL')
                        ->groupBy('PE_METADATO_PADRON.MUNICIPIO_ID','PE_CAT_MUNICIPIOS_SEDESEM.MUNICIPIONOMBRE')
                        ->orderBy('PE_METADATO_PADRON.MUNICIPIO_ID','asc')
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
                                                            'PE_METADATO_PADRON.SERVICIO_ID')
                    ->selectRaw('COUNT(*) AS TOTAL')
                    ->get();
        $regpadron =regPadronModel::join('PE_CAT_SERVICIOS','PE_CAT_SERVICIOS.SERVICIO_ID','=',
                                                            'PE_METADATO_PADRON.SERVICIO_ID')
                    ->selectRaw('PE_METADATO_PADRON.SERVICIO_ID, 
                                 PE_CAT_SERVICIOS.SERVICIO_DESC,  COUNT(*) AS TOTAL')
                    ->groupBy(  'PE_METADATO_PADRON.SERVICIO_ID','PE_CAT_SERVICIOS.SERVICIO_DESC')
                    ->orderBy(  'PE_METADATO_PADRON.SERVICIO_ID','asc')
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
