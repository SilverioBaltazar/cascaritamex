<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests\visitaRequest;

use App\regBitacoraModel;
use App\regOscModel;
use App\regPerModel;
use App\regMesesModel;
use App\regDiasModel;
use App\regHorasModel;
use App\regPfiscalesModel;
use App\regMunicipioModel;
use App\regEntidadesModel;
use App\regNumerosModel;
use App\regDoctosModel;
use App\regAgendaModel;
use App\regFormatosModel;

// Exportar a excel 
use Maatwebsite\Excel\Facades\Excel;
// Exportar a pdf
use PDF;
//use Options;

class visitasController extends Controller
{

    public function actionBuscarVisita(Request $request)
    {
        $nombre       = session()->get('userlog');
        $pass         = session()->get('passlog');
        if($nombre == NULL AND $pass == NULL){
            return view('sicinar.login.expirada');
        }
        $usuario      = session()->get('usuario');
        $rango        = session()->get('rango');
        $ip           = session()->get('ip');

        $regentidades = regEntidadesModel::select('ENTIDADFEDERATIVA_ID','ENTIDADFEDERATIVA_DESC')
                           ->orderBy('ENTIDADFEDERATIVA_ID','asc')
                           ->get();
        $regmunicipio = regMunicipioModel::join('PE_CAT_ENTIDADES_FED','PE_CAT_ENTIDADES_FED.ENTIDADFEDERATIVA_ID', '=', 'PE_CAT_MUNICIPIOS_SEDESEM.ENTIDADFEDERATIVAID')
                        ->select('PE_CAT_MUNICIPIOS_SEDESEM.ENTIDADFEDERATIVAID','PE_CAT_ENTIDADES_FED.ENTIDADFEDERATIVA_DESC','PE_CAT_MUNICIPIOS_SEDESEM.MUNICIPIOID','PE_CAT_MUNICIPIOS_SEDESEM.MUNICIPIONOMBRE')
                        ->wherein('PE_CAT_MUNICIPIOS_SEDESEM.ENTIDADFEDERATIVAID',[9, 11, 15, 22])
                        ->orderBy('PE_CAT_MUNICIPIOS_SEDESEM.ENTIDADFEDERATIVAID','DESC')
                        ->orderBy('PE_CAT_MUNICIPIOS_SEDESEM.MUNICIPIONOMBRE','DESC')
                        ->get();
        $regmeses     = regMesesModel::select('MES_ID','MES_DESC')->get();   
        $reghoras     = regHorasModel::select('HORA_ID','HORA_DESC')->get();   
        $regdias      = regDiasModel::select('DIA_ID','DIA_DESC')->get();   
        $regminutos   = regNumerosModel::select('NUM_ID','NUM_DESC')->get();  
        $regperiodos  = regPfiscalesModel::select('PERIODO_ID', 'PERIODO_DESC')->get();        
        $regosc       = regOscModel::select('OSC_ID', 'OSC_DESC','OSC_STATUS')->get();       
        //**************************************************************//
        // ***** busqueda https://github.com/rimorsoft/Search-simple ***//
        // ***** video https://www.youtube.com/watch?v=bmtD9GUaszw   ***//
        //**************************************************************//
        $fper  = $request->get('fper');   
        $fmes  = $request->get('fmes');  
        $fdia  = $request->get('fdia');    
        $fiap  = $request->get('fiap');            
        $regvisita = regAgendaModel::orderBy('VISITA_FOLIO', 'ASC')
                      ->fper($fper)    //Metodos personalizados es equivalente a ->where('OSC_DESC', 'LIKE', "%$name%");
                      ->fmes($fmes)    //Metodos personalizados
                      //->fdia($fdia)    //Metodos personalizados
                      ->fiap($fiap)    //Metodos personalizados
                      ->paginate(50);
        if($regvisita->count() <= 0){
            toastr()->error('No existen registros de visitas de diligencias en la agenda.','Lo siento!',['positionClass' => 'toast-bottom-right']);
            //return redirect()->route('nuevaIap');
        }            
        return view('sicinar.agenda.verVisitas', compact('nombre','usuario','regosc','regperiodos', 'regdias', 'reghoras','regmeses','regminutos','regvisita','regentidades','regmunicipio'));
    }

    public function actionVerVisitas(){
        $nombre       = session()->get('userlog');
        $pass         = session()->get('passlog');
        if($nombre == NULL AND $pass == NULL){
            return view('sicinar.login.expirada');
        }
        $usuario      = session()->get('usuario');
        $rango        = session()->get('rango');
        $ip           = session()->get('ip');

        setlocale(LC_TIME, "spanish");        
        $xperiodo_id  = (int)date('Y');
        $xmes_id      = (int)date('m');
        $xdia_id      = (int)date('d');
 
        $regentidades = regEntidadesModel::select('ENTIDADFEDERATIVA_ID','ENTIDADFEDERATIVA_DESC')
                           ->orderBy('ENTIDADFEDERATIVA_ID','asc')
                           ->get();
        $regmunicipio = regMunicipioModel::join('PE_CAT_ENTIDADES_FED','PE_CAT_ENTIDADES_FED.ENTIDADFEDERATIVA_ID', '=', 'PE_CAT_MUNICIPIOS_SEDESEM.ENTIDADFEDERATIVAID')
                        ->select('PE_CAT_MUNICIPIOS_SEDESEM.ENTIDADFEDERATIVAID','PE_CAT_ENTIDADES_FED.ENTIDADFEDERATIVA_DESC','PE_CAT_MUNICIPIOS_SEDESEM.MUNICIPIOID','PE_CAT_MUNICIPIOS_SEDESEM.MUNICIPIONOMBRE')
                        ->wherein('PE_CAT_MUNICIPIOS_SEDESEM.ENTIDADFEDERATIVAID',[9, 11, 15, 22])
                        ->orderBy('PE_CAT_MUNICIPIOS_SEDESEM.ENTIDADFEDERATIVAID','DESC')
                        ->orderBy('PE_CAT_MUNICIPIOS_SEDESEM.MUNICIPIONOMBRE','DESC')
                        ->get();
        $regmeses     = regMesesModel::select('MES_ID','MES_DESC')->get();   
        $reghoras     = regHorasModel::select('HORA_ID','HORA_DESC')->get();   
        $regdias      = regDiasModel::select('DIA_ID','DIA_DESC')->get();  
        $regminutos   = regNumerosModel::select('NUM_ID','NUM_DESC')->get();   
        $regperiodos  = regPfiscalesModel::select('PERIODO_ID', 'PERIODO_DESC')->get();        
        $regosc       = regOscModel::select('OSC_ID', 'OSC_DESC','OSC_STATUS')->get();       
        $regvisita    = regAgendaModel::select('VISITA_FOLIO','PERIODO_ID','MES_ID','DIA_ID','HORA_ID','NUM_ID',
                                             'OSC_ID','MUNICIPIO_ID',
                                             'VISITA_CONTACTO','VISITA_TEL','VISITA_EMAIL','VISITA_DOM','VISITA_OBJ',
                                             'VISITA_SPUB','VISITA_SPUB2','VISITA_FECREGP','VISITA_FECREGP2',
                                             'VISITA_TIPO1','VISITA_EDO','VISITA_STATUS2',
                                             'VISITA_AUDITADO1','VISITA_PUESTO1' ,'VISITA_IDENT1'  ,'VISITA_EXPED1',
                                             'VISITA_AUDITADO2','VISITA_PUESTO2' ,'VISITA_IDENT2'  ,'VISITA_EXPED2',
                                             'VISITA_AUDITADO3','VISITA_PUESTO3' ,'VISITA_IDENT3'  ,'VISITA_EXPED3',
                                             'VISITA_AUDITOR1' ,'VISITA_AUDITOR2','VISITA_AUDITOR3',
                                             'VISITA_TESTIGO1' ,'VISITA_TESTIGO2','VISITA_TESTIGO3',
                                             'VISITA_CRITERIOS','VISITA_VISTO'   ,'VISITA_RECOMEN' ,'VISITA_SUGEREN',
                                             'VISITA_OBS2','VISITA_OBS3','VISITA_OFICIO',
                                             'VISITA_FECREGD','VISITA_FECREGD2','VISITA_FECREGV','VISITA_FECREGV2',
                                             'FECHA_M','IP_M' ,'LOGIN_M')
                      ->where(  'PERIODO_ID','=',$xperiodo_id)
                      ->where(  'MES_ID',    '=',$xmes_id)
                      ->orderBy('VISITA_FOLIO','ASC')                    
                      ->paginate(30);
                      // ->where('VISITA_STATUS2','=','N')
        if($regvisita->count() <= 0){
            toastr()->error('En la agenda no hay programadas visitas de diligencias.','Lo siento!',['positionClass' => 'toast-bottom-right']);
        }
        return view('sicinar.agenda.verVisitas',compact('nombre','usuario','regmeses', 'reghoras','regminutos','regdias','regperiodos','regosc','regvisita','regentidades','regmunicipio'));
    }


    public function actionEditarVisita($id){
        $nombre        = session()->get('userlog');
        $pass          = session()->get('passlog');
        if($nombre == NULL AND $pass == NULL){
            return view('sicinar.login.expirada');
        }
        $usuario       = session()->get('usuario');
        $rango         = session()->get('rango');

        $regentidades = regEntidadesModel::select('ENTIDADFEDERATIVA_ID','ENTIDADFEDERATIVA_DESC')
                           ->orderBy('ENTIDADFEDERATIVA_ID','asc')
                           ->get();
        $regmunicipio = regMunicipioModel::join('PE_CAT_ENTIDADES_FED','PE_CAT_ENTIDADES_FED.ENTIDADFEDERATIVA_ID', '=', 'PE_CAT_MUNICIPIOS_SEDESEM.ENTIDADFEDERATIVAID')
                        ->select('PE_CAT_MUNICIPIOS_SEDESEM.ENTIDADFEDERATIVAID','PE_CAT_ENTIDADES_FED.ENTIDADFEDERATIVA_DESC','PE_CAT_MUNICIPIOS_SEDESEM.MUNICIPIOID','PE_CAT_MUNICIPIOS_SEDESEM.MUNICIPIONOMBRE')
                        ->wherein('PE_CAT_MUNICIPIOS_SEDESEM.ENTIDADFEDERATIVAID',[9, 11, 15, 22])
                        ->orderBy('PE_CAT_MUNICIPIOS_SEDESEM.ENTIDADFEDERATIVAID','DESC')
                        ->orderBy('PE_CAT_MUNICIPIOS_SEDESEM.MUNICIPIONOMBRE','DESC')
                        ->get();
        $regformatos  = regFormatosModel::select('FORMATO_ID','FORMATO_DESC')->get();                        
        $regmeses     = regMesesModel::select('MES_ID','MES_DESC')->get();   
        $reghoras     = regHorasModel::select('HORA_ID','HORA_DESC')->get();   
        $regdias      = regDiasModel::select('DIA_ID','DIA_DESC')->get();   
        $regminutos   = regNumerosModel::select('NUM_ID','NUM_DESC')->orderBy('NUM_ID','ASC')
                        ->get();   
        $regperiodos  = regPfiscalesModel::select('PERIODO_ID', 'PERIODO_DESC')->get();        
        $regosc       = regOscModel::select('OSC_ID', 'OSC_DESC','OSC_STATUS')->get();       
        $regvisita    = regAgendaModel::select('VISITA_FOLIO','PERIODO_ID','MES_ID','DIA_ID','HORA_ID','NUM_ID',
                        'OSC_ID','MUNICIPIO_ID',
                        'PERIODO2_ID','MES2_ID','DIA2_ID','HORA2_ID','NUM2_ID','ENTIDAD2_ID','MUNICIPIO2_ID',
                        'NUM3_ID','VISITA_CONTACTO',
                        'VISITA_TEL','VISITA_EMAIL','VISITA_DOM','VISITA_OBJ', 'VISITA_SPUB',
                        'VISITA_SPUB2','VISITA_FECREGP','VISITA_FECREGP2','VISITA_EDO','VISITA_TIPO1','VISITA_STATUS2',
                        'VISITA_AUDITADO1','VISITA_PUESTO1' ,'VISITA_IDENT1'  ,'VISITA_EXPED1',
                        'VISITA_AUDITADO2','VISITA_PUESTO2' ,'VISITA_IDENT2'  ,'VISITA_EXPED2',
                        'VISITA_AUDITADO3','VISITA_PUESTO3' ,'VISITA_IDENT3'  ,'VISITA_EXPED3',
                        'VISITA_AUDITOR1' ,'VISITA_AUDITOR2','VISITA_AUDITOR3',
                        'VISITA_TESTIGO1' ,'VISITA_TESTIGO2','VISITA_TESTIGO3',
                        'VISITA_CRITERIOS','VISITA_VISTO'   ,'VISITA_RECOMEN' ,'VISITA_SUGEREN',
                        'VISITA_OBS2','VISITA_OBS3',
                        'P_OBS01','PREG01_ID','VISITA_OFICIO',
                        'VISITA_FECREGD','VISITA_FECREGD2','VISITA_FECREGV','VISITA_FECREGV2',
                        'FECHA_M','IP_M' ,'LOGIN_M')
                        ->where('VISITA_FOLIO',$id)
                        ->first();

        if($regvisita->count() <= 0){
        //if($regvisitt->count() <= 0){
            toastr()->error('No existe visita de diligencia en la agenda.','Lo siento!',['positionClass' => 'toast-bottom-right']);
            ////return redirect()->route('nuevoProgdil');
        }
        return view('sicinar.agenda.editarVisita',compact('nombre','usuario','regmeses','reghoras','regdias','regminutos','regosc','regperiodos','regentidades','regmunicipio','regvisita','regformatos'));

    }

    public function actionActualizarVisita(visitaRequest $request, $id){
        $nombre        = session()->get('userlog');
        $pass          = session()->get('passlog');
        if($nombre == NULL AND $pass == NULL){
            return view('sicinar.login.expirada');
        }
        $usuario       = session()->get('usuario');
        $rango         = session()->get('rango');
        $ip            = session()->get('ip');

        // **************** actualizar ******************************
        $regvisita   = regAgendaModel::where('VISITA_FOLIO',$id);
        if($regvisita->count() <= 0)
            toastr()->error('No existe visita de diligencia en la agenda.','¡Por favor volver a intentar!',['positionClass' => 'toast-bottom-right']);
        else{        
            //                'OSC_GEOREF_LATITUD' => $request->osc_georef_latitud, 
            $mes = regMesesModel::ObtMes($request->mes2_id);
            $dia = regDiasModel::ObtDia($request->dia2_id);

            $name1 =null;
            if($request->hasFile('p_obs01')){
                //echo "Escribió en el campo de texto 1: " .'-'. $request->p_obs01 .'-'. "<br><br>"; 
                $name1 = $id.'_'.$request->file('p_obs01')->getClientOriginalName(); 
                //sube el archivo a la carpeta del servidor public/storage/
                $request->file('p_obs01')->move(public_path().'/images/', $name1);

                // ************* Actualizamos registro **********************************
                //dd('entidad:'.$request->entidad_id, 'municipio:'.$request->municipio_id);
                $regvisita  = regAgendaModel::where('VISITA_FOLIO',$id)        
                ->update([                
                    'PERIODO2_ID'     => $request->periodo2_id,
                    'MES2_ID'         => $request->mes2_id,
                    'DIA2_ID'         => $request->dia2_id,
                    'HORA2_ID'        => $request->hora2_id,
                    'NUM2_ID'         => $request->num2_id, 
                    'ENTIDAD2_ID'     => $request->entidad2_id,                                                       
                    'MUNICIPIO2_ID'   => $request->municipio2_id,               
                    'NUM3_ID'         => $request->num3_id,  
                    'ENTIDAD_ID'      => $request->entidad_id,                                                                           
                    'MUNICIPIO_ID'    => $request->municipio_id,               

                    'VISITA_DOM'      => substr(strtoupper(trim($request->visita_dom))      ,0, 199),                    
                    'VISITA_AUDITADO1'=> substr(strtoupper(trim($request->visita_auditado1)),0,  79),
                    'VISITA_PUESTO1'  => substr(strtoupper(trim($request->visita_puesto1))  ,0,  79),
                    'VISITA_IDENT1'   => substr(strtoupper(trim($request->visita_ident1))   ,0,  79),
                    'VISITA_EXPED1'   => substr(strtoupper(trim($request->visita_exped1))   ,0,  79),
                    'VISITA_AUDITADO2'=> substr(strtoupper(trim($request->visita_auditado2)),0,  79),
                    'VISITA_PUESTO2'  => substr(strtoupper(trim($request->visita_puesto2))  ,0,  79),
                    'VISITA_IDENT2'   => substr(strtoupper(trim($request->visita_ident2))   ,0,  79),
                    'VISITA_EXPED2'   => substr(strtoupper(trim($request->visita_exped2))   ,0,  79),
                    'VISITA_AUDITADO3'=> substr(strtoupper(trim($request->visita_auditado3)),0,  79),
                    'VISITA_IDENT3'   => substr(strtoupper(trim($request->visita_ident3))   ,0,  79),                                    
                    'VISITA_AUDITOR1' => substr(strtoupper(trim($request->visita_auditor1)) ,0,  79),
                    'VISITA_AUDITOR2' => substr(strtoupper(trim($request->visita_auditor2)) ,0,  79),
 
                    'VISITA_TESTIGO1' => substr(strtoupper(trim($request->visita_testigo1)) ,0,  79),
                    'VISITA_TESTIGO2' => substr(strtoupper(trim($request->visita_testigo2)) ,0,  79),

                    'VISITA_CRITERIOS'=> substr(strtoupper(trim($request->visita_criterios)),0,3999),
                    'VISITA_VISTO'    => substr(strtoupper(trim($request->visita_visto))    ,0,3999),
                    'VISITA_RECOMEN'  => substr(strtoupper(trim($request->visita_recomen))  ,0,3999),
                    'VISITA_SUGEREN'  => substr(strtoupper(trim($request->visita_sugeren))  ,0,3999),

                    'VISITA_FECREGD'  => trim($dia[0]->dia_desc.'/'.$mes[0]->mes_mes.'/'.$request->periodo2_id),
                    'VISITA_FECREGD2' => trim($dia[0]->dia_desc.'/'.$mes[0]->mes_mes.'/'.$request->periodo2_id),                
                    'VISITA_FECREGV'  => date('Y/m/d'),    //date('d/m/Y')                                
                    'VISITA_FECREGV2' => date('Y/m/d'),    //date('d/m/Y')                                
                    'VISITA_EDO'      => $request->visita_edo,                
                    'VISITA_STATUS2'  => $request->visita_status2, 

                    'PREG01_ID'       => $request->preg01_id,             
                    'P_OBS01'         => $name1,                  
 
                    'IP_M'            => $ip,
                    'LOGIN_M'         => $nombre,
                    'FECHA_M'         => date('Y/m/d')    //date('d/m/Y')                                
                ]);
                toastr()->success('Visita de diligencia actualizada en la agenda.','¡Ok!',['positionClass' => 'toast-bottom-right']);
            }else{
                $regvisita  = regAgendaModel::where('VISITA_FOLIO',$id)        
                ->update([                
                    'PERIODO2_ID'     => $request->periodo2_id,
                    'MES2_ID'         => $request->mes2_id,
                    'DIA2_ID'         => $request->dia2_id,
                    'HORA2_ID'        => $request->hora2_id,
                    'NUM2_ID'         => $request->num2_id, 
                    'ENTIDAD2_ID'     => $request->entidad2_id,                                                       
                    'MUNICIPIO2_ID'   => $request->municipio2_id, 
                    'NUM3_ID'         => $request->num3_id,  
                    'ENTIDAD_ID'      => $request->entidad_id,                                 
                    'MUNICIPIO_ID'    => $request->municipio_id,               
 
                    'VISITA_DOM'      => substr(strtoupper(trim($request->visita_dom))      ,0, 199),                    
                    'VISITA_AUDITADO1'=> substr(strtoupper(trim($request->visita_auditado1)),0,  79),
                    'VISITA_PUESTO1'  => substr(strtoupper(trim($request->visita_puesto1))  ,0,  79),
                    'VISITA_IDENT1'   => substr(strtoupper(trim($request->visita_ident1))   ,0,  79),
                    'VISITA_EXPED1'   => substr(strtoupper(trim($request->visita_exped1))   ,0,  79),
                    'VISITA_AUDITADO2'=> substr(strtoupper(trim($request->visita_auditado2)),0,  79),
                    'VISITA_PUESTO2'  => substr(strtoupper(trim($request->visita_puesto2))  ,0,  79),
                    'VISITA_IDENT2'   => substr(strtoupper(trim($request->visita_ident2))   ,0,  79),
                    'VISITA_EXPED2'   => substr(strtoupper(trim($request->visita_exped2))   ,0,  79),
                    'VISITA_AUDITADO3'=> substr(strtoupper(trim($request->visita_auditado3)),0,  79),  
                    'VISITA_IDENT3'   => substr(strtoupper(trim($request->visita_ident3))   ,0,  79),                                   
                    'VISITA_AUDITOR1' => substr(strtoupper(trim($request->visita_auditor1)) ,0,  79),
                    'VISITA_AUDITOR2' => substr(strtoupper(trim($request->visita_auditor2)) ,0,  79),

                    'VISITA_TESTIGO1' => substr(strtoupper(trim($request->visita_testigo1)) ,0,  79),
                    'VISITA_TESTIGO2' => substr(strtoupper(trim($request->visita_testigo2)) ,0,  79),

                    'VISITA_CRITERIOS'=> substr(strtoupper(trim($request->visita_criterios)),0,3999),
                    'VISITA_VISTO'    => substr(strtoupper(trim($request->visita_visto))    ,0,3999),
                    'VISITA_RECOMEN'  => substr(strtoupper(trim($request->visita_recomen))  ,0,3999),
                    'VISITA_SUGEREN'  => substr(strtoupper(trim($request->visita_sugeren))  ,0,3999),

                    'VISITA_FECREGD'  => trim($dia[0]->dia_desc.'/'.$mes[0]->mes_mes.'/'.$request->periodo2_id),
                    'VISITA_FECREGD2' => trim($dia[0]->dia_desc.'/'.$mes[0]->mes_mes.'/'.$request->periodo2_id),                
                    'VISITA_FECREGV'  => date('Y/m/d'),    //date('d/m/Y')                                
                    'VISITA_FECREGV2' => date('Y/m/d'),    //date('d/m/Y')                                
                    'VISITA_EDO'      => $request->visita_edo,                
                    'VISITA_STATUS2'  => $request->visita_status2,     

                    'IP_M'            => $ip,
                    'LOGIN_M'         => $nombre,
                    'FECHA_M'         => date('Y/m/d')    //date('d/m/Y')                                
                ]);
                toastr()->success('Visita de diligencia actualizada en la agenda.','¡Ok!',['positionClass' => 'toast-bottom-right']);
            }
            /************ Bitacora inicia *************************************/ 
            setlocale(LC_TIME, "spanish");        
            $xip          = session()->get('ip');
            $xperiodo_id  = (int)date('Y');
            $xprograma_id = 1;
            $xmes_id      = (int)date('m');
            $xproceso_id  =        3;
            $xfuncion_id  =     3006;
            $xtrx_id      =      186;    //Actualizar         
            $regbitacora = regBitacoraModel::select('PERIODO_ID', 'PROGRAMA_ID', 'MES_ID', 'PROCESO_ID', 'FUNCION_ID', 'TRX_ID', 'FOLIO', 'NO_VECES', 'FECHA_REG', 'IP', 'LOGIN', 'FECHA_M', 'IP_M', 'LOGIN_M')
                           ->where(['PERIODO_ID' => $xperiodo_id, 'PROGRAMA_ID' => $xprograma_id, 'MES_ID' => $xmes_id, 'PROCESO_ID' => $xproceso_id, 'FUNCION_ID' => $xfuncion_id, 'TRX_ID' => $xtrx_id, 'FOLIO' => $id])
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
                $xno_veces = regBitacoraModel::where(['PERIODO_ID' => $xperiodo_id, 'PROGRAMA_ID' => $xprograma_id, 'MES_ID' => $xmes_id, 'PROCESO_ID' => $xproceso_id, 'FUNCION_ID' => $xfuncion_id, 'TRX_ID' => $xtrx_id, 'FOLIO' => $id])
                            ->max('NO_VECES');
                $xno_veces = $xno_veces+1;                        
                //*********** Termina de obtener el no de veces *****************************         
                $regbitacora = regBitacoraModel::select('NO_VECES','IP_M','LOGIN_M','FECHA_M')
                               ->where(['PERIODO_ID' => $xperiodo_id, 'PROGRAMA_ID' => $xprograma_id, 'MES_ID' => $xmes_id, 'PROCESO_ID' => $xproceso_id, 'FUNCION_ID' => $xfuncion_id, 'TRX_ID' => $xtrx_id, 'FOLIO' => $id])
                               ->update([
                                          'NO_VECES'=> $regbitacora->NO_VECES = $xno_veces,
                                          'IP_M'    => $regbitacora->IP       = $ip,
                                          'LOGIN_M' => $regbitacora->LOGIN_M  = $nombre,
                                          'FECHA_M' => $regbitacora->FECHA_M  = date('Y/m/d')  //date('d/m/Y')
                                        ]);
                toastr()->success('Bitacora actualizada.','¡Ok!',['positionClass' => 'toast-bottom-right']);
            }   /************ Bitacora termina *************************************/                     
        }       /************ Termina actualización ********************************/

        return redirect()->route('verVisitas');
    }

    // exportar a formato PDF
    public function actionActaVisitaPDF($id){
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
        $regmunicipio = regMunicipioModel::join('PE_CAT_ENTIDADES_FED','PE_CAT_ENTIDADES_FED.ENTIDADFEDERATIVA_ID', '=', 'PE_CAT_MUNICIPIOS_SEDESEM.ENTIDADFEDERATIVAID')
                        ->select('PE_CAT_MUNICIPIOS_SEDESEM.ENTIDADFEDERATIVAID','PE_CAT_ENTIDADES_FED.ENTIDADFEDERATIVA_DESC','PE_CAT_MUNICIPIOS_SEDESEM.MUNICIPIOID','PE_CAT_MUNICIPIOS_SEDESEM.MUNICIPIONOMBRE')
                        ->wherein('PE_CAT_MUNICIPIOS_SEDESEM.ENTIDADFEDERATIVAID',[9, 11, 15, 22])
                        ->orderBy('PE_CAT_MUNICIPIOS_SEDESEM.ENTIDADFEDERATIVAID','DESC')
                        ->orderBy('PE_CAT_MUNICIPIOS_SEDESEM.MUNICIPIONOMBRE','DESC')
                        ->get();        
        $regmeses     = regMesesModel::select('MES_ID','MES_DESC')->get();   
        $reghoras     = regHorasModel::select('HORA_ID','HORA_DESC')->get();   
        $regdias      = regDiasModel::select('DIA_ID','DIA_DESC')->get();   
        $regminutos   = regNumerosModel::select('NUM_ID','NUM_DESC')->get();   
        $regperiodos  = regPfiscalesModel::select('PERIODO_ID', 'PERIODO_DESC')->get();        
        $regosc       = regOscModel::select('OSC_ID', 'OSC_DESC','OSC_STATUS')->get();       
        $regvisita    = regAgendaModel::select('VISITA_FOLIO','PERIODO_ID','MES_ID','DIA_ID','HORA_ID','NUM_ID',
                        'OSC_ID','MUNICIPIO_ID',
                        'PERIODO2_ID','MES2_ID','DIA2_ID','HORA2_ID','NUM2_ID','ENTIDAD2_ID','MUNICIPIO2_ID',
                        'NUM3_ID','VISITA_CONTACTO',
                        'VISITA_TEL','VISITA_EMAIL','VISITA_DOM','VISITA_OBJ', 'VISITA_SPUB',
                        'VISITA_SPUB2','VISITA_FECREGP','VISITA_FECREGP2','VISITA_EDO','VISITA_TIPO1','VISITA_STATUS2',
                        'VISITA_AUDITADO1','VISITA_PUESTO1' ,'VISITA_IDENT1'  ,'VISITA_EXPED1',
                        'VISITA_AUDITADO2','VISITA_PUESTO2' ,'VISITA_IDENT2'  ,'VISITA_EXPED2',
                        'VISITA_AUDITADO3','VISITA_PUESTO3' ,'VISITA_IDENT3'  ,'VISITA_EXPED3',
                        'VISITA_AUDITOR1' ,'VISITA_AUDITOR2','VISITA_AUDITOR3','VISITA_AUDITOR4',
                        'VISITA_TESTIGO1' ,'VISITA_TESTIGO2','VISITA_TESTIGO3',
                        'VISITA_CRITERIOS','VISITA_VISTO'   ,'VISITA_RECOMEN' ,'VISITA_SUGEREN',
                        'VISITA_OBS2','VISITA_OBS3',
                        'P_OBS01','PREG01_ID','VISITA_OFICIO',
                        'VISITA_FECREGD','VISITA_FECREGD2','VISITA_FECREGV','VISITA_FECREGV2',
                        'FECHA_M','IP_M' ,'LOGIN_M')
                        ->where('VISITA_FOLIO',$id)
                        ->get();
        if($regvisita->count() <= 0){
            toastr()->error('No existe Visita de diligencia en la agenda.','Uppss!',['positionClass' => 'toast-bottom-right']);
        }else{
            /************ Bitacora inicia *************************************/ 
            setlocale(LC_TIME, "spanish");        
            $xip          = session()->get('ip');
            $xperiodo_id  = (int)date('Y');
            $xprograma_id = 1;
            $xmes_id      = (int)date('m');
            $xproceso_id  =        3;
            $xfuncion_id  =     3006;
            $xtrx_id      =      189;     // pdf
            $id           =      $id;
            $regbitacora = regBitacoraModel::select('PERIODO_ID', 'PROGRAMA_ID', 'MES_ID', 'PROCESO_ID', 
                           'FUNCION_ID', 'TRX_ID', 'FOLIO', 'NO_VECES', 'FECHA_REG', 'IP', 'LOGIN', 
                           'FECHA_M', 'IP_M', 'LOGIN_M')
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
                $xno_veces = regBitacoraModel::where(['PERIODO_ID' => $xperiodo_id, 'PROGRAMA_ID' => $xprograma_id, 'MES_ID' => $xmes_id, 'PROCESO_ID' => $xproceso_id, 'FUNCION_ID' => $xfuncion_id, 'TRX_ID' => $xtrx_id, 'FOLIO' => $id])
                            ->max('NO_VECES');
                $xno_veces = $xno_veces+1;                        
                //*********** Termina de obtener el no de veces *****************************         
                $regbitacora = regBitacoraModel::select('NO_VECES','IP_M','LOGIN_M','FECHA_M')
                               ->where(['PERIODO_ID' => $xperiodo_id, 'PROGRAMA_ID' => $xprograma_id, 'MES_ID' => $xmes_id, 'PROCESO_ID' => $xproceso_id, 'FUNCION_ID' => $xfuncion_id, 'TRX_ID' => $xtrx_id, 'FOLIO' => $id])
                               ->update([
                                        'NO_VECES' => $regbitacora->NO_VECES = $xno_veces,
                                        'IP_M' => $regbitacora->IP           = $ip,
                                        'LOGIN_M' => $regbitacora->LOGIN_M   = $nombre,
                                        'FECHA_M' => $regbitacora->FECHA_M   = date('Y/m/d')  //date('d/m/Y')
                                        ]);
                toastr()->success('Bitacora actualizada.','¡Ok!',['positionClass' => 'toast-bottom-right']);
            }   /************ Bitacora termina *************************************/             
            /***************** Genera pdf ******************************/
            $pdf = PDF::loadView('sicinar.pdf.actavisitaPDF', compact('nombre','usuario','regmeses','reghoras','regdias','regminutos','regperiodos','regosc','regvisita','regentidades','regmunicipio'));
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
            return $pdf->stream('ActaDeVisitaDeVerificacion');
        }   // ***************** Termina visita ****************************//
    }

}
