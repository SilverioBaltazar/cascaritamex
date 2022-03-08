<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests\visitaquestionRequest;

use App\regBitacoraModel;
use App\regIapModel;
//use App\regPerModel;
//use App\regMesesModel;
//use App\regDiasModel;
//use App\regHorasModel;
use App\regPfiscalesModel;
use App\regAgendaModel;
use App\regRubroModel;
use App\regSeccionesModel;
use App\regTipoPregModel;
use App\regPreguntasModel;
use App\regQuestionsModel;
use App\regVisitaQuestionsModel;

// Exportar a excel 
//use App\Exports\ExcelExportCatIAPS;
use Maatwebsite\Excel\Facades\Excel;
// Exportar a pdf
use PDF;
//use Options;

class questionController extends Controller
{

    public function actionBuscarQuestion(Request $request)
    {
        $nombre       = session()->get('userlog');
        $pass         = session()->get('passlog');
        if($nombre == NULL AND $pass == NULL){
            return view('sicinar.login.expirada');
        }
        $usuario      = session()->get('usuario');
        //$estructura   = session()->get('estructura');
        //$id_estruc    = session()->get('id_estructura');
        //$id_estructura= rtrim($id_estruc," ");
        $rango        = session()->get('rango');
        $ip           = session()->get('ip');

        $regiap       = regIapModel::select('IAP_ID','IAP_DESC')->orderBy('IAP_ID','asc')
                        ->get();         
        $regrubro     = regRubroModel::select('RUBRO_ID','RUBRO_DESC')->orderBy('RUBRO_ID','asc')
                        ->get();   
        $regagenda    = regAgendaModel::select('VISITA_FOLIO','PERIODO_ID','MES_ID','DIA_ID','HORA_ID','NUM_ID','IAP_ID')
                        ->orderBy('VISITA_FOLIO','asc')
                        ->get();
        $regseccion   = regSeccionesModel::select('SEC_ID','SEC_DESC')->orderBy('SEC_ID','asc')
                        ->get();       
        $regtipopreg  = regTipoPregModel::select('TIPOP_ID','TIPOP_DESC')->orderBy('TIPOP_ID','asc')
                        ->get();     
        $regpreguntas = regPreguntasModel::select('PREG_ID','PREG_DESC')->orderBy('PREG_ID','asc')
                        ->get();                                                                        
        $regquestion  = regQuestionsModel::select('QUESTION_NO','PREG_ID','TIPOP_ID','SECCION_ID','RUBRO_ID',
                                                  'QUESTION_OBS')
                        ->orderBy('QUESTION_NO','DESC')
                        ->get();  
        $regvisita    = regAgendaModel::select('VISITA_FOLIO','PERIODO_ID','MES_ID','DIA_ID','HORA_ID','NUM_ID',
                                             'IAP_ID','MUNICIPIO_ID',
                                             'VISITA_CONTACTO','VISITA_TEL','VISITA_EMAIL','VISITA_DOM','VISITA_OBJ',
                                             'VISITA_SPUB','VISITA_SPUB2','VISITA_FECREGP','VISITA_EDO','VISITA_STATUS2',
                                             'VISITA_AUDITADO1','VISITA_PUESTO1' ,'VISITA_IDENT1'  ,'VISITA_EXPED1',
                                             'VISITA_AUDITADO2','VISITA_PUESTO2' ,'VISITA_IDENT2'  ,'VISITA_EXPED2',
                                             'VISITA_AUDITADO3','VISITA_PUESTO3' ,'VISITA_IDENT3'  ,'VISITA_EXPED3',
                                             'VISITA_AUDITOR1' ,'VISITA_AUDITOR2','VISITA_AUDITOR3',
                                             'VISITA_TESTIGO1' ,'VISITA_TESTIGO2','VISITA_TESTIGO3',
                                             'VISITA_CRITERIOS','VISITA_VISTO'   ,'VISITA_RECOMEN' ,'VISITA_SUGEREN',
                                             'VISITA_FECREGD'  ,'VISITA_FECREGV')
                        ->orderBy('VISITA_FOLIO','ASC')
                        ->get();                                               
        //**************************************************************//
        // ***** busqueda https://github.com/rimorsoft/Search-simple ***//
        // ***** video https://www.youtube.com/watch?v=bmtD9GUaszw   ***//                            
        //**************************************************************//
        $name  = $request->get('name');   
        $email = $request->get('email');  
        $folioo = $request->get('folioo');    
        $regquestdili = regVisitaQuestionsModel::orderBy('VISITA_FOLIO', 'ASC')
                        //->name($name)   //Metodos personalizados es equvalente a ->where('IAP_DESC', 'LIKE', "%$name%");
                        //->email($email)   //Metodos personalizados
                        ->folioo($folioo) //Metodos personalizados
                        ->paginate(30);
        if($regquestdili->count() <= 0){
            toastr()->error('No existen registros de cuestionarios.','Lo siento!',['positionClass' => 'toast-bottom-right']);
            //return redirect()->route('nuevaIap');
        }            
        return view('sicinar.cuestionario.verQuestions', compact('regiap','regrubro','regagenda','regseccion','regtipopreg','regpreguntas','regquestion','regquestdili','regvisita','nombre','usuario'));
    }


    public function actionVerQuestions(){
        $nombre       = session()->get('userlog');
        $pass         = session()->get('passlog');
        if($nombre == NULL AND $pass == NULL){
            return view('sicinar.login.expirada');
        }
        $usuario      = session()->get('usuario');
        //$estructura   = session()->get('estructura');
        //$id_estruc    = session()->get('id_estructura');
        //$id_estructura= rtrim($id_estruc," ");
        $rango        = session()->get('rango');
        $ip           = session()->get('ip');

        //$regmeses     = regMesesModel::select('MES_ID','MES_DESC')->get();   
        //$reghoras     = regHorasModel::select('HORA_ID','HORA_DESC')->get();   
        //$regdias      = regDiasModel::select('DIA_ID','DIA_DESC')->get();   
        //$regminutos   = regNumerosModel::select('NUM_ID','NUM_DESC')->get();  
        //$regperiodos  = regPfiscalesModel::select('PERIODO_ID', 'PERIODO_DESC')->get();   

        $regiap       = regIapModel::select('IAP_ID','IAP_DESC')->orderBy('IAP_ID','asc')
                        ->get();         
        $regrubro     = regRubroModel::select('RUBRO_ID','RUBRO_DESC')->orderBy('RUBRO_ID','asc')
                        ->get();   
        $regagenda    = regAgendaModel::select('VISITA_FOLIO','PERIODO_ID','MES_ID','DIA_ID','HORA_ID','NUM_ID','IAP_ID')
                        ->orderBy('VISITA_FOLIO','asc')
                        ->get();
        $regseccion   = regSeccionesModel::select('SEC_ID','SEC_DESC')->orderBy('SEC_ID','asc')
                        ->get();       
        $regtipopreg  = regTipoPregModel::select('TIPOP_ID','TIPOP_DESC')->orderBy('TIPOP_ID','asc')
                        ->get();     
        $regpreguntas = regPreguntasModel::select('PREG_ID','PREG_DESC')->orderBy('PREG_ID','asc')
                        ->get();                                                                        
        $regquestion  = regQuestionsModel::select('QUESTION_NO','PREG_ID','TIPOP_ID','SECCION_ID','RUBRO_ID',
                                                  'QUESTION_OBS')
                        ->orderBy('QUESTION_NO','DESC')
                        ->get();  
        $regvisita    = regAgendaModel::select('VISITA_FOLIO','PERIODO_ID','MES_ID','DIA_ID','HORA_ID','NUM_ID',
                                             'IAP_ID','MUNICIPIO_ID',
                                             'VISITA_CONTACTO','VISITA_TEL','VISITA_EMAIL','VISITA_DOM','VISITA_OBJ',
                                             'VISITA_SPUB','VISITA_SPUB2','VISITA_FECREGP','VISITA_EDO','VISITA_STATUS2',
                                             'VISITA_AUDITADO1','VISITA_PUESTO1' ,'VISITA_IDENT1'  ,'VISITA_EXPED1',
                                             'VISITA_AUDITADO2','VISITA_PUESTO2' ,'VISITA_IDENT2'  ,'VISITA_EXPED2',
                                             'VISITA_AUDITADO3','VISITA_PUESTO3' ,'VISITA_IDENT3'  ,'VISITA_EXPED3',
                                             'VISITA_AUDITOR1' ,'VISITA_AUDITOR2','VISITA_AUDITOR3',
                                             'VISITA_TESTIGO1' ,'VISITA_TESTIGO2','VISITA_TESTIGO3',
                                             'VISITA_CRITERIOS','VISITA_VISTO'   ,'VISITA_RECOMEN' ,'VISITA_SUGEREN',
                                             'VISITA_FECREGD'  ,'VISITA_FECREGV')
                        ->orderBy('VISITA_FOLIO','ASC')
                        ->get();                             
        $regquestdili = regVisitaQuestionsModel::select('VISITA_FOLIO','QUESTION_NO','IAP_ID', 'VISITAQ_SPUB1',
            'VISITAQ_SPUB2','VISITAQ_CARGO1','VISITAQ_CARGO2',
  'PREG_ID1' ,'PREG_ID2' ,'PREG_ID3' ,'PREG_ID4' ,'PREG_ID5' ,'PREG_ID6' ,'PREG_ID7' ,'PREG_ID8' ,'PREG_ID9' ,'PREG_ID10',
  'PREG_ID11','PREG_ID12','PREG_ID13','PREG_ID14','PREG_ID15','PREG_ID16','PREG_ID17','PREG_ID18','PREG_ID19','PREG_ID20',
  'PREG_ID21','PREG_ID22','PREG_ID23','PREG_ID24','PREG_ID25','PREG_ID26','PREG_ID27','PREG_ID28','PREG_ID29','PREG_ID30',
  'PREG_ID31','PREG_ID32','PREG_ID33','PREG_ID34','PREG_ID35','PREG_ID36','PREG_ID37','PREG_ID38','PREG_ID39','PREG_ID40',
  'PREG_ID41','PREG_ID42','PREG_ID43','PREG_ID44','PREG_ID45','PREG_ID46','PREG_ID47','PREG_ID48','PREG_ID49','PREG_ID50',
  'PREG_ID51','PREG_ID52','PREG_ID53','PREG_ID54','PREG_ID55','PREG_ID56','PREG_ID57','PREG_ID58','PREG_ID59','PREG_ID60',
  'PREG_ID61','PREG_ID62','PREG_ID63','PREG_ID64','PREG_ID65','PREG_ID66','PREG_ID67','PREG_ID68','PREG_ID69','PREG_ID70',
  'PREG_ID71','PREG_ID72','PREG_ID73','PREG_ID74','PREG_ID75','PREG_ID76','PREG_ID77','PREG_ID78','PREG_ID79','PREG_ID80',
  'P_RESP1' ,'P_RESP2' ,'P_RESP3' ,'P_RESP4' ,'P_RESP5' ,'P_RESP6' ,'P_RESP7' ,'P_RESP8' ,'P_RESP9' ,'P_RESP10',
  'P_RESP11','P_RESP12','P_RESP13','P_RESP14','P_RESP15','P_RESP16','P_RESP17','P_RESP18','P_RESP19','P_RESP20',
  'P_RESP21','P_RESP22','P_RESP23','P_RESP24','P_RESP25','P_RESP26','P_RESP27','P_RESP28','P_RESP29','P_RESP30',
  'P_RESP31','P_RESP32','P_RESP33','P_RESP34','P_RESP35','P_RESP36','P_RESP37','P_RESP38','P_RESP39','P_RESP40',
  'P_RESP41','P_RESP42','P_RESP43','P_RESP44','P_RESP45','P_RESP46','P_RESP47','P_RESP48','P_RESP49','P_RESP50',
  'P_RESP51','P_RESP52','P_RESP53','P_RESP54','P_RESP55','P_RESP56','P_RESP57','P_RESP58','P_RESP59','P_RESP60',
  'P_RESP61','P_RESP62','P_RESP63','P_RESP64','P_RESP65','P_RESP66','P_RESP67','P_RESP68','P_RESP69','P_RESP70',
  'P_RESP71','P_RESP72','P_RESP73','P_RESP74','P_RESP75','P_RESP76','P_RESP77','P_RESP78','P_RESP79','P_RESP80',
  'P_OBS1' ,'P_OBS2' ,'P_OBS3' ,'P_OBS4' ,'P_OBS5' ,'P_OBS6' ,'P_OBS7' ,'P_OBS8' ,'P_OBS9' ,'P_OBS10',
  'P_OBS11','P_OBS12','P_OBS13','P_OBS14','P_OBS15','P_OBS16','P_OBS17','P_OBS18','P_OBS19','P_OBS20',
  'P_OBS21','P_OBS22','P_OBS23','P_OBS24','P_OBS25','P_OBS26','P_OBS27','P_OBS28','P_OBS29','P_OBS30',
  'P_OBS31','P_OBS32','P_OBS33','P_OBS34','P_OBS35','P_OBS36','P_OBS37','P_OBS38','P_OBS39','P_OBS40',
  'P_OBS41','P_OBS42','P_OBS43','P_OBS44','P_OBS45','P_OBS46','P_OBS47','P_OBS48','P_OBS49','P_OBS50',
  'P_OBS51','P_OBS52','P_OBS53','P_OBS54','P_OBS55','P_OBS56','P_OBS57','P_OBS58','P_OBS59','P_OBS60',
  'P_OBS61','P_OBS62','P_OBS63','P_OBS64','P_OBS65','P_OBS66','P_OBS67','P_OBS68','P_OBS69','P_OBS70',
  'P_OBS71','P_OBS72','P_OBS73','P_OBS74','P_OBS75','P_OBS76','P_OBS77','P_OBS78','P_OBS79','P_OBS80',
  'VISITAQ_STATUS','FECREG','IP','LOGIN','FECHA_M','IP_M','LOGIN_M')
                        ->orderBy('VISITA_FOLIO','ASC')
                        ->orderBy('QUESTION_NO' ,'ASC')
                        ->paginate(30);
        if($regiap->count() <= 0){
            toastr()->error('No existen cuestionarios dados de alta.','Lo siento!',['positionClass' => 'toast-bottom-right']);
            //return redirect()->route('nuevaIap');
        }
        return view('sicinar.cuestionario.verQuestions',compact('regiap','regrubro','regagenda','regseccion','regtipopreg','regpreguntas','regquestion','regquestdili','regvisita','nombre','usuario'));
        //return view('sicinar.cuestionario.verQuestions',compact('regiap','regrubro','regagenda','regseccion','regtipopreg','regpreguntas','regquestion','regquestdili','regmeses','regperiodos','reghoras','regminutos','regdias','regvisita','nombre','usuario'));
    }


    public function actionNuevoQuestion(){
        $nombre       = session()->get('userlog');
        $pass         = session()->get('passlog');
        if($nombre == NULL AND $pass == NULL){
            return view('sicinar.login.expirada');
        }
        $usuario      = session()->get('usuario');
        $estructura   = session()->get('estructura');
        //$id_estruc    = session()->get('id_estructura');
        //$id_estructura= rtrim($id_estruc," ");
        $rango        = session()->get('rango');
        $ip           = session()->get('ip');

        $regiap       = regIapModel::select('IAP_ID','IAP_DESC')->orderBy('IAP_ID','asc')
                        ->get();         
        $regrubro     = regRubroModel::select('RUBRO_ID','RUBRO_DESC')->orderBy('RUBRO_ID','asc')
                        ->get();   
        $regagenda    = regAgendaModel::select('VISITA_FOLIO','PERIODO_ID','MES_ID','DIA_ID','HORA_ID','NUM_ID','IAP_ID')
                        ->orderBy('VISITA_FOLIO','asc')
                        ->get();
        $regseccion   = regSeccionesModel::select('SEC_ID','SEC_DESC')->orderBy('SEC_ID','asc')
                        ->get();       
        $regtipopreg  = regTipoPregModel::select('TIPOP_ID','TIPOP_DESC')->orderBy('TIPOP_ID','asc')
                        ->get();     
        $regpreguntas = regPreguntasModel::select('PREG_ID','PREG_DESC')->orderBy('PREG_ID','asc')
                        ->get();                                                                        
        //$regquestion  = regQuestionsModel::select('QUESTION_NO','PREG_ID','TIPOP_ID','SECCION_ID','RUBRO_ID',
        //                                          'QUESTION_OBS')
        //                ->orderBy('PREG_ID','ASC')
        //                ->get();
        $regquestion  = regQuestionsModel::join('JP_CAT_PREGUNTAS','JP_CAT_PREGUNTAS.PREG_ID', '=','JP_QUESTIONS.PREG_ID')
                        ->select('JP_QUESTIONS.QUESTION_NO','JP_QUESTIONS.PREG_ID','JP_CAT_PREGUNTAS.PREG_DESC',
                                 'JP_QUESTIONS.TIPOP_ID'   ,'JP_QUESTIONS.SECCION_ID' ,'JP_QUESTIONS.RUBRO_ID')
                        ->orderBy('JP_QUESTIONS.PREG_ID','ASC')
                        ->get();
        $regvisita    = regAgendaModel::select('VISITA_FOLIO','PERIODO_ID','MES_ID','DIA_ID','HORA_ID','NUM_ID',
                                             'IAP_ID','MUNICIPIO_ID',
                                             'VISITA_CONTACTO','VISITA_TEL','VISITA_EMAIL','VISITA_DOM','VISITA_OBJ',
                                             'VISITA_SPUB','VISITA_SPUB2','VISITA_FECREGP','VISITA_EDO','VISITA_STATUS2',
                                             'VISITA_AUDITADO1','VISITA_PUESTO1' ,'VISITA_IDENT1'  ,'VISITA_EXPED1',
                                             'VISITA_AUDITADO2','VISITA_PUESTO2' ,'VISITA_IDENT2'  ,'VISITA_EXPED2',
                                             'VISITA_AUDITADO3','VISITA_PUESTO3' ,'VISITA_IDENT3'  ,'VISITA_EXPED3',
                                             'VISITA_AUDITOR1' ,'VISITA_AUDITOR2','VISITA_AUDITOR3',
                                             'VISITA_TESTIGO1' ,'VISITA_TESTIGO2','VISITA_TESTIGO3',
                                             'VISITA_CRITERIOS','VISITA_VISTO'   ,'VISITA_RECOMEN' ,'VISITA_SUGEREN',
                                             'VISITA_FECREGD'  ,'VISITA_FECREGV')
                        ->orderBy('VISITA_FOLIO','ASC')
                        ->get();                                                     
        $regquestdili = regVisitaQuestionsModel::select('VISITA_FOLIO','QUESTION_NO','IAP_ID', 'VISITAQ_SPUB1',
            'VISITAQ_SPUB2','VISITAQ_CARGO1','VISITAQ_CARGO2',
  'PREG_ID1' ,'PREG_ID2' ,'PREG_ID3' ,'PREG_ID4' ,'PREG_ID5' ,'PREG_ID6' ,'PREG_ID7' ,'PREG_ID8' ,'PREG_ID9' ,'PREG_ID10',
  'PREG_ID11','PREG_ID12','PREG_ID13','PREG_ID14','PREG_ID15','PREG_ID16','PREG_ID17','PREG_ID18','PREG_ID19','PREG_ID20',
  'PREG_ID21','PREG_ID22','PREG_ID23','PREG_ID24','PREG_ID25','PREG_ID26','PREG_ID27','PREG_ID28','PREG_ID29','PREG_ID30',
  'PREG_ID31','PREG_ID32','PREG_ID33','PREG_ID34','PREG_ID35','PREG_ID36','PREG_ID37','PREG_ID38','PREG_ID39','PREG_ID40',
  'PREG_ID41','PREG_ID42','PREG_ID43','PREG_ID44','PREG_ID45','PREG_ID46','PREG_ID47','PREG_ID48','PREG_ID49','PREG_ID50',
  'PREG_ID51','PREG_ID52','PREG_ID53','PREG_ID54','PREG_ID55','PREG_ID56','PREG_ID57','PREG_ID58','PREG_ID59','PREG_ID60',
  'PREG_ID61','PREG_ID62','PREG_ID63','PREG_ID64','PREG_ID65','PREG_ID66','PREG_ID67','PREG_ID68','PREG_ID69','PREG_ID70',
  'PREG_ID71','PREG_ID72','PREG_ID73','PREG_ID74','PREG_ID75','PREG_ID76','PREG_ID77','PREG_ID78','PREG_ID79','PREG_ID80',
  'P_RESP1' ,'P_RESP2' ,'P_RESP3' ,'P_RESP4' ,'P_RESP5' ,'P_RESP6' ,'P_RESP7' ,'P_RESP8' ,'P_RESP9' ,'P_RESP10',
  'P_RESP11','P_RESP12','P_RESP13','P_RESP14','P_RESP15','P_RESP16','P_RESP17','P_RESP18','P_RESP19','P_RESP20',
  'P_RESP21','P_RESP22','P_RESP23','P_RESP24','P_RESP25','P_RESP26','P_RESP27','P_RESP28','P_RESP29','P_RESP30',
  'P_RESP31','P_RESP32','P_RESP33','P_RESP34','P_RESP35','P_RESP36','P_RESP37','P_RESP38','P_RESP39','P_RESP40',
  'P_RESP41','P_RESP42','P_RESP43','P_RESP44','P_RESP45','P_RESP46','P_RESP47','P_RESP48','P_RESP49','P_RESP50',
  'P_RESP51','P_RESP52','P_RESP53','P_RESP54','P_RESP55','P_RESP56','P_RESP57','P_RESP58','P_RESP59','P_RESP60',
  'P_RESP61','P_RESP62','P_RESP63','P_RESP64','P_RESP65','P_RESP66','P_RESP67','P_RESP68','P_RESP69','P_RESP70',
  'P_RESP71','P_RESP72','P_RESP73','P_RESP74','P_RESP75','P_RESP76','P_RESP77','P_RESP78','P_RESP79','P_RESP80',
  'P_OBS1' ,'P_OBS2' ,'P_OBS3' ,'P_OBS4' ,'P_OBS5' ,'P_OBS6' ,'P_OBS7' ,'P_OBS8' ,'P_OBS9' ,'P_OBS10',
  'P_OBS11','P_OBS12','P_OBS13','P_OBS14','P_OBS15','P_OBS16','P_OBS17','P_OBS18','P_OBS19','P_OBS20',
  'P_OBS21','P_OBS22','P_OBS23','P_OBS24','P_OBS25','P_OBS26','P_OBS27','P_OBS28','P_OBS29','P_OBS30',
  'P_OBS31','P_OBS32','P_OBS33','P_OBS34','P_OBS35','P_OBS36','P_OBS37','P_OBS38','P_OBS39','P_OBS40',
  'P_OBS41','P_OBS42','P_OBS43','P_OBS44','P_OBS45','P_OBS46','P_OBS47','P_OBS48','P_OBS49','P_OBS50',
  'P_OBS51','P_OBS52','P_OBS53','P_OBS54','P_OBS55','P_OBS56','P_OBS57','P_OBS58','P_OBS59','P_OBS60',
  'P_OBS61','P_OBS62','P_OBS63','P_OBS64','P_OBS65','P_OBS66','P_OBS67','P_OBS68','P_OBS69','P_OBS70',
  'P_OBS71','P_OBS72','P_OBS73','P_OBS74','P_OBS75','P_OBS76','P_OBS77','P_OBS78','P_OBS79','P_OBS80',
  'VISITAQ_STATUS','FECREG','IP','LOGIN','FECHA_M','IP_M','LOGIN_M')
                    ->orderBy('VISITA_FOLIO','asc')
                    ->orderBy('QUESTION_NO' ,'asc')
                    ->get();
        return view('sicinar.cuestionario.nuevoQuestion',compact('regiap','regrubro','regagenda','regseccion','regtipopreg','regpreguntas','regquestion','regvisita','regquestdili','nombre','usuario'));
    }


    public function actionAltaNuevoQuestion(Request $request){
        //dd($request->all());
        $nombre       = session()->get('userlog');
        $pass         = session()->get('passlog');
        if($nombre == NULL AND $pass == NULL){
            return view('sicinar.login.expirada');
        }
        $usuario      = session()->get('usuario');
        //$estructura   = session()->get('estructura');
        //$id_estruc    = session()->get('id_estructura');
        //$id_estructura= rtrim($id_estruc," ");
        $rango        = session()->get('rango');
        $ip           = session()->get('ip');

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

        /************ ALTA  *****************************/ 
        //$iap_id = regquestdili::max('IAP_ID');
        //$iap_id = $iap_id+1;
        $iap_id = regAgendaModel::ObtIap($request->visita_folio);

        $nuevoquestion = new regVisitaQuestionsModel();

        $nuevoquestion->VISITA_FOLIO  = $request->visita_folio;
        $nuevoquestion->QUESTION_NO   = $request->question_no;
        $nuevoquestion->IAP_ID        = $iap_id;
        $nuevoquestion->VISITAQ_SPUB1 = strtoupper($request->visitaq_spub1);
        $nuevoquestion->VISITAQ_CARGO1= strtoupper($request->visitaq_cargo1);

        $nuevoquestion->PREG_ID1      = $request->preg_id1;
        $nuevoquestion->PREG_ID2      = $request->preg_id2;
        $nuevoquestion->PREG_ID3      = $request->preg_id3;
        $nuevoquestion->PREG_ID4      = $request->preg_id4;
        $nuevoquestion->PREG_ID5      = $request->preg_id5;
        $nuevoquestion->PREG_ID6      = $request->preg_id6;
        $nuevoquestion->PREG_ID7      = $request->preg_id7;
        $nuevoquestion->PREG_ID8      = $request->preg_id8;
        $nuevoquestion->PREG_ID9      = $request->preg_id9;
        $nuevoquestion->PREG_ID10     = $request->preg_id3;
        $nuevoquestion->PREG_ID11     = $request->preg_id11;
        $nuevoquestion->PREG_ID12     = $request->preg_id12;
        $nuevoquestion->PREG_ID13     = $request->preg_id13;
        $nuevoquestion->PREG_ID14     = $request->preg_id14;
        $nuevoquestion->PREG_ID15     = $request->preg_id15;        
        $nuevoquestion->PREG_ID16     = $request->preg_id16;
        $nuevoquestion->PREG_ID17     = $request->preg_id17;
        $nuevoquestion->PREG_ID18     = $request->preg_id18;
        $nuevoquestion->PREG_ID19     = $request->preg_id19;
        $nuevoquestion->PREG_ID20     = $request->preg_id20;
        $nuevoquestion->PREG_ID21     = $request->preg_id21;
        $nuevoquestion->PREG_ID22     = $request->preg_id22;
        $nuevoquestion->PREG_ID23     = $request->preg_id23;
        $nuevoquestion->PREG_ID24     = $request->preg_id24;
        $nuevoquestion->PREG_ID25     = $request->preg_id25;        
        $nuevoquestion->PREG_ID26     = $request->preg_id26;
        $nuevoquestion->PREG_ID27     = $request->preg_id27;
        $nuevoquestion->PREG_ID28     = $request->preg_id28;
        $nuevoquestion->PREG_ID29     = $request->preg_id29;
        $nuevoquestion->PREG_ID30     = $request->preg_id30;
        $nuevoquestion->PREG_ID31     = $request->preg_id31;        
        $nuevoquestion->PREG_ID32     = $request->preg_id32;
        $nuevoquestion->PREG_ID33     = $request->preg_id33;
        $nuevoquestion->PREG_ID34     = $request->preg_id34;
        $nuevoquestion->PREG_ID35     = $request->preg_id35;        
        $nuevoquestion->PREG_ID36     = $request->preg_id36;
        $nuevoquestion->PREG_ID37     = $request->preg_id37;
        $nuevoquestion->PREG_ID38     = $request->preg_id38;
        $nuevoquestion->PREG_ID39     = $request->preg_id39;
        $nuevoquestion->PREG_ID40     = $request->preg_id40;
        $nuevoquestion->PREG_ID41     = $request->preg_id41;        
        $nuevoquestion->PREG_ID42     = $request->preg_id42;
        $nuevoquestion->PREG_ID43     = $request->preg_id43;
        $nuevoquestion->PREG_ID44     = $request->preg_id44;
        $nuevoquestion->PREG_ID45     = $request->preg_id45;        
        $nuevoquestion->PREG_ID46     = $request->preg_id46;
        $nuevoquestion->PREG_ID47     = $request->preg_id47;
        $nuevoquestion->PREG_ID48     = $request->preg_id48;
        $nuevoquestion->PREG_ID49     = $request->preg_id49;
        $nuevoquestion->PREG_ID50     = $request->preg_id50;
        $nuevoquestion->PREG_ID51     = $request->preg_id51;        
        $nuevoquestion->PREG_ID52     = $request->preg_id52;
        $nuevoquestion->PREG_ID53     = $request->preg_id53;
        $nuevoquestion->PREG_ID54     = $request->preg_id54;
        $nuevoquestion->PREG_ID55     = $request->preg_id55;        
        $nuevoquestion->PREG_ID56     = $request->preg_id56;
        $nuevoquestion->PREG_ID57     = $request->preg_id57;
        $nuevoquestion->PREG_ID58     = $request->preg_id58;
        $nuevoquestion->PREG_ID59     = $request->preg_id59;
        $nuevoquestion->PREG_ID60     = $request->preg_id60;

        $nuevoquestion->P_RESP1       = $request->p_resp1;
        $nuevoquestion->P_RESP2       = $request->p_resp2;
        $nuevoquestion->P_RESP3       = $request->p_resp3;
        $nuevoquestion->P_RESP4       = $request->p_resp4;
        $nuevoquestion->P_RESP5       = $request->p_resp5;
        $nuevoquestion->P_RESP6       = $request->p_resp6;
        $nuevoquestion->P_RESP7       = $request->p_resp7;
        $nuevoquestion->P_RESP8       = $request->p_resp8;
        $nuevoquestion->P_RESP9       = $request->p_resp9;
        $nuevoquestion->P_RESP10      = $request->p_resp3;
        $nuevoquestion->P_RESP11      = $request->p_resp11;
        $nuevoquestion->P_RESP12      = $request->p_resp12;
        $nuevoquestion->P_RESP13      = $request->p_resp13;
        $nuevoquestion->P_RESP14      = $request->p_resp14;
        $nuevoquestion->P_RESP15      = $request->p_resp15;
        $nuevoquestion->P_RESP16      = $request->p_resp16;
        $nuevoquestion->P_RESP17      = $request->p_resp17;
        $nuevoquestion->P_RESP18      = $request->p_resp18;
        $nuevoquestion->P_RESP19      = $request->p_resp19;
        $nuevoquestion->P_RESP20      = $request->p_resp20;
        $nuevoquestion->P_RESP21      = $request->p_resp21;
        $nuevoquestion->P_RESP22      = $request->p_resp22;
        $nuevoquestion->P_RESP23      = $request->p_resp23;
        $nuevoquestion->P_RESP24      = $request->p_resp24;
        $nuevoquestion->P_RESP25      = $request->p_resp25;
        $nuevoquestion->P_RESP26      = $request->p_resp26;
        $nuevoquestion->P_RESP27      = $request->p_resp27;
        $nuevoquestion->P_RESP28      = $request->p_resp28;
        $nuevoquestion->P_RESP29      = $request->p_resp29;
        $nuevoquestion->P_RESP30      = $request->p_resp30;
        $nuevoquestion->P_RESP31      = $request->p_resp31;
        $nuevoquestion->P_RESP32      = $request->p_resp32;
        $nuevoquestion->P_RESP33      = $request->p_resp33;
        $nuevoquestion->P_RESP34      = $request->p_resp34;
        $nuevoquestion->P_RESP35      = $request->p_resp35;
        $nuevoquestion->P_RESP36      = $request->p_resp36;
        $nuevoquestion->P_RESP37      = $request->p_resp37;
        $nuevoquestion->P_RESP38      = $request->p_resp38;
        $nuevoquestion->P_RESP39      = $request->p_resp39;
        $nuevoquestion->P_RESP40      = $request->p_resp40;
        $nuevoquestion->P_RESP41      = $request->p_resp41;
        $nuevoquestion->P_RESP42      = $request->p_resp42;
        $nuevoquestion->P_RESP43      = $request->p_resp43;
        $nuevoquestion->P_RESP44      = $request->p_resp44;
        $nuevoquestion->P_RESP45      = $request->p_resp45;
        $nuevoquestion->P_RESP46      = $request->p_resp46;
        $nuevoquestion->P_RESP47      = $request->p_resp47;
        $nuevoquestion->P_RESP48      = $request->p_resp48;
        $nuevoquestion->P_RESP49      = $request->p_resp49;
        $nuevoquestion->P_RESP50      = $request->p_resp50;                                        
        $nuevoquestion->P_RESP51      = $request->p_resp51;
        $nuevoquestion->P_RESP52      = $request->p_resp52;
        $nuevoquestion->P_RESP53      = $request->p_resp53;
        $nuevoquestion->P_RESP54      = $request->p_resp54;
        $nuevoquestion->P_RESP55      = $request->p_resp55;
        $nuevoquestion->P_RESP56      = $request->p_resp56;
        $nuevoquestion->P_RESP57      = $request->p_resp57;
        $nuevoquestion->P_RESP58      = $request->p_resp58;
        $nuevoquestion->P_RESP59      = $request->p_resp59;
        $nuevoquestion->P_RESP60      = $request->p_resp60;

        $nuevoquestion->P_OBS1        = strtoupper($request->p_obs1);
        $nuevoquestion->P_OBS2        = strtoupper($request->p_obs2);        
        $nuevoquestion->P_OBS3        = strtoupper($request->p_obs3);
        $nuevoquestion->P_OBS4        = strtoupper($request->p_obs4);
        $nuevoquestion->P_OBS5        = strtoupper($request->p_obs5);
        $nuevoquestion->P_OBS6        = strtoupper($request->p_obs6);
        $nuevoquestion->P_OBS7        = strtoupper($request->p_obs7);
        $nuevoquestion->P_OBS8        = strtoupper($request->p_obs8);
        $nuevoquestion->P_OBS9        = strtoupper($request->p_obs9);
        $nuevoquestion->P_OBS10       = strtoupper($request->p_obs10);
        $nuevoquestion->P_OBS11       = strtoupper($request->p_obs11);
        $nuevoquestion->P_OBS12       = strtoupper($request->p_obs12);        
        $nuevoquestion->P_OBS13       = strtoupper($request->p_obs13);
        $nuevoquestion->P_OBS14       = strtoupper($request->p_obs14);
        $nuevoquestion->P_OBS15       = strtoupper($request->p_obs15);
        $nuevoquestion->P_OBS16       = strtoupper($request->p_obs16);
        $nuevoquestion->P_OBS17       = strtoupper($request->p_obs17);
        $nuevoquestion->P_OBS18       = strtoupper($request->p_obs18);
        $nuevoquestion->P_OBS19       = strtoupper($request->p_obs19);
        $nuevoquestion->P_OBS20       = strtoupper($request->p_obs20);
        $nuevoquestion->P_OBS21       = strtoupper($request->p_obs21);
        $nuevoquestion->P_OBS22       = strtoupper($request->p_obs22);        
        $nuevoquestion->P_OBS23       = strtoupper($request->p_obs23);
        $nuevoquestion->P_OBS24       = strtoupper($request->p_obs24);
        $nuevoquestion->P_OBS25       = strtoupper($request->p_obs25);
        $nuevoquestion->P_OBS26       = strtoupper($request->p_obs26);
        $nuevoquestion->P_OBS27       = strtoupper($request->p_obs27);
        $nuevoquestion->P_OBS28       = strtoupper($request->p_obs28);
        $nuevoquestion->P_OBS29       = strtoupper($request->p_obs29);
        $nuevoquestion->P_OBS30       = strtoupper($request->p_obs30);
        $nuevoquestion->P_OBS31       = strtoupper($request->p_obs31);
        $nuevoquestion->P_OBS32       = strtoupper($request->p_obs32);        
        $nuevoquestion->P_OBS33       = strtoupper($request->p_obs33);
        $nuevoquestion->P_OBS34       = strtoupper($request->p_obs34);
        $nuevoquestion->P_OBS35       = strtoupper($request->p_obs35);
        $nuevoquestion->P_OBS36       = strtoupper($request->p_obs36);
        $nuevoquestion->P_OBS37       = strtoupper($request->p_obs37);
        $nuevoquestion->P_OBS38       = strtoupper($request->p_obs38);
        $nuevoquestion->P_OBS39       = strtoupper($request->p_obs39);
        $nuevoquestion->P_OBS40       = strtoupper($request->p_obs40);
        $nuevoquestion->P_OBS41       = strtoupper($request->p_obs41);
        $nuevoquestion->P_OBS42       = strtoupper($request->p_obs42);        
        $nuevoquestion->P_OBS43       = strtoupper($request->p_obs43);
        $nuevoquestion->P_OBS44       = strtoupper($request->p_obs44);
        $nuevoquestion->P_OBS45       = strtoupper($request->p_obs45);
        $nuevoquestion->P_OBS46       = strtoupper($request->p_obs46);
        $nuevoquestion->P_OBS47       = strtoupper($request->p_obs47);
        $nuevoquestion->P_OBS48       = strtoupper($request->p_obs48);
        $nuevoquestion->P_OBS49       = strtoupper($request->p_obs49);
        $nuevoquestion->P_OBS40       = strtoupper($request->p_obs50);
        $nuevoquestion->P_OBS51       = strtoupper($request->p_obs51);
        $nuevoquestion->P_OBS52       = strtoupper($request->p_obs52);        
        $nuevoquestion->P_OBS53       = strtoupper($request->p_obs53);
        $nuevoquestion->P_OBS54       = strtoupper($request->p_obs54);
        $nuevoquestion->P_OBS55       = strtoupper($request->p_obs55);
        $nuevoquestion->P_OBS56       = strtoupper($request->p_obs56);
        $nuevoquestion->P_OBS57       = strtoupper($request->p_obs57);
        $nuevoquestion->P_OBS58       = strtoupper($request->p_obs58);
        $nuevoquestion->P_OBS59       = strtoupper($request->p_obs59);
        $nuevoquestion->P_OBS60       = strtoupper($request->p_obs60);        

        $nuevoquestion->IP            = $ip;
        $nuevoquestion->LOGIN         = $nombre;         // Usuario ;
        $nuevoquestion->save();

        if($nuevoquestion->save() == true){
            toastr()->success('Cuestionario ok.','Cuestionario dado de alta!',['positionClass' => 'toast-bottom-right']);
            //return redirect()->route('nuevaIap');
            //return view('sicinar.plandetrabajo.nuevoPlan',compact('unidades','nombre','usuario','estructura','id_estructura','rango','preguntas','apartados'));
        }else{
            toastr()->error('Error inesperado al dar de alta el cuestionario. Por favor volver a interlo.','Ups!',['positionClass' => 'toast-bottom-right']);
            //return back();
            //return redirect()->route('nuevoProceso');
        }

        /************ Bitacora inicia *************************************/ 
        setlocale(LC_TIME, "spanish");        
        $xip          = session()->get('ip');
        $xperiodo_id  = (int)date('Y');
        $xprograma_id = 1;
        $xmes_id      = (int)date('m');
        $xproceso_id  =         3;
        $xfuncion_id  =      3006;
        $xtrx_id      =       190;    //Alta

        $regbitacora = regBitacoraModel::select('PERIODO_ID', 'PROGRAMA_ID', 'MES_ID', 'PROCESO_ID', 'FUNCION_ID', 'TRX_ID', 'FOLIO', 'NO_VECES', 'FECHA_REG', 'IP', 'LOGIN', 'FECHA_M', 'IP_M', 'LOGIN_M')
                        ->where(['PERIODO_ID' => $xperiodo_id, 'PROGRAMA_ID' => $xprograma_id, 'MES_ID' => $xmes_id,'PROCESO_ID' => $xproceso_id, 'FUNCION_ID' => $xfuncion_id, 'TRX_ID' => $xtrx_id, 'FOLIO' => $request->visita_folio])
                        ->get();
        if($regbitacora->count() <= 0){              // Alta
            $nuevoregBitacora = new regBitacoraModel();              
            $nuevoregBitacora->PERIODO_ID = $xperiodo_id;    // Año de transaccion 
            $nuevoregBitacora->PROGRAMA_ID= $xprograma_id;   // Proyecto JAPEM 
            $nuevoregBitacora->MES_ID     = $xmes_id;        // Mes de transaccion
            $nuevoregBitacora->PROCESO_ID = $xproceso_id;    // Proceso de apoyo
            $nuevoregBitacora->FUNCION_ID = $xfuncion_id;    // Funcion del modelado de procesos 
            $nuevoregBitacora->TRX_ID     = $xtrx_id;        // Actividad del modelado de procesos
            $nuevoregBitacora->FOLIO      = $request->visita_folio;             // Folio    
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
            $xno_veces = regBitacoraModel::where(['PERIODO_ID' => $xperiodo_id, 'PROGRAMA_ID' => $xprograma_id, 'MES_ID' => $xmes_id, 'PROCESO_ID' => $xproceso_id, 'FUNCION_ID' => $xfuncion_id, 'TRX_ID' => $xtrx_id, 'FOLIO' => $request->visita_folio])
                        ->max('NO_VECES');
            $xno_veces = $xno_veces+1;                        
            //*********** Termina de obtener el no de veces *****************************         

            $regbitacora = regBitacoraModel::select('NO_VECES','IP_M','LOGIN_M','FECHA_M')
                        ->where(['PERIODO_ID' => $xperiodo_id, 'PROGRAMA_ID' => $xprograma_id, 'MES_ID' => $xmes_id, 'PROCESO_ID' => $xproceso_id, 'FUNCION_ID' => $xfuncion_id,'TRX_ID' => $xtrx_id,'FOLIO' => $id])
            ->update([
                'NO_VECES' => $regbitacora->NO_VECES = $xno_veces,
                'IP_M' => $regbitacora->IP           = $ip,
                'LOGIN_M' => $regbitacora->LOGIN_M   = $nombre,
                'FECHA_M' => $regbitacora->FECHA_M   = date('Y/m/d')  //date('d/m/Y')
            ]);
            toastr()->success('Bitacora actualizada.','¡Ok!',['positionClass' => 'toast-bottom-right']);
        }
        /************ Bitacora termina *************************************/ 

        return redirect()->route('verQuestions');
    }

    public function actionEditarQuestion($id){
        $nombre        = session()->get('userlog');
        $pass          = session()->get('passlog');
        if($nombre == NULL AND $pass == NULL){
            return view('sicinar.login.expirada');
        }
        $usuario       = session()->get('usuario');
        //$estructura    = session()->get('estructura');
        //$id_estruc     = session()->get('id_estructura');
        //$id_estructura = rtrim($id_estruc," ");
        $rango         = session()->get('rango');

        $regiap       = regIapModel::select('IAP_ID','IAP_DESC')->orderBy('IAP_ID','asc')
                        ->get();         
        $regrubro     = regRubroModel::select('RUBRO_ID','RUBRO_DESC')->orderBy('RUBRO_ID','asc')
                        ->get();   
        $regagenda    = regAgendaModel::select('VISITA_FOLIO','PERIODO_ID','MES_ID','DIA_ID','HORA_ID','NUM_ID','IAP_ID')
                        ->orderBy('VISITA_FOLIO','asc')
                        ->get();
        $regseccion   = regSeccionesModel::select('SEC_ID','SEC_DESC')->orderBy('SEC_ID','asc')
                        ->get();       
        $regtipopreg  = regTipoPregModel::select('TIPOP_ID','TIPOP_DESC')->orderBy('TIPOP_ID','asc')
                        ->get();     
        $regpreguntas = regPreguntasModel::select('PREG_ID','PREG_DESC')->orderBy('PREG_ID','asc')
                        ->get();                                                                        
        $regquestion  = regQuestionsModel::select('QUESTION_NO','PREG_ID','TIPOP_ID','SECCION_ID','RUBRO_ID',
                                                  'QUESTION_OBS')
                        ->orderBy('QUESTION_NO','DESC')
                        ->get();
        $regquestdili = regVisitaQuestionsModel::select('VISITA_FOLIO','QUESTION_NO','IAP_ID', 'VISITAQ_SPUB1',
            'VISITAQ_SPUB2','VISITAQ_CARGO1','VISITAQ_CARGO2',
  'PREG_ID1' ,'PREG_ID2' ,'PREG_ID3' ,'PREG_ID4' ,'PREG_ID5' ,'PREG_ID6' ,'PREG_ID7' ,'PREG_ID8' ,'PREG_ID9' ,'PREG_ID10',
  'PREG_ID11','PREG_ID12','PREG_ID13','PREG_ID14','PREG_ID15','PREG_ID16','PREG_ID17','PREG_ID18','PREG_ID19','PREG_ID20',
  'PREG_ID21','PREG_ID22','PREG_ID23','PREG_ID24','PREG_ID25','PREG_ID26','PREG_ID27','PREG_ID28','PREG_ID29','PREG_ID30',
  'PREG_ID31','PREG_ID32','PREG_ID33','PREG_ID34','PREG_ID35','PREG_ID36','PREG_ID37','PREG_ID38','PREG_ID39','PREG_ID40',
  'PREG_ID41','PREG_ID42','PREG_ID43','PREG_ID44','PREG_ID45','PREG_ID46','PREG_ID47','PREG_ID48','PREG_ID49','PREG_ID50',
  'PREG_ID51','PREG_ID52','PREG_ID53','PREG_ID54','PREG_ID55','PREG_ID56','PREG_ID57','PREG_ID58','PREG_ID59','PREG_ID60',
  'PREG_ID61','PREG_ID62','PREG_ID63','PREG_ID64','PREG_ID65','PREG_ID66','PREG_ID67','PREG_ID68','PREG_ID69','PREG_ID70',
  'PREG_ID71','PREG_ID72','PREG_ID73','PREG_ID74','PREG_ID75','PREG_ID76','PREG_ID77','PREG_ID78','PREG_ID79','PREG_ID80',
  'P_RESP1' ,'P_RESP2' ,'P_RESP3' ,'P_RESP4' ,'P_RESP5' ,'P_RESP6' ,'P_RESP7' ,'P_RESP8' ,'P_RESP9' ,'P_RESP10',
  'P_RESP11','P_RESP12','P_RESP13','P_RESP14','P_RESP15','P_RESP16','P_RESP17','P_RESP18','P_RESP19','P_RESP20',
  'P_RESP21','P_RESP22','P_RESP23','P_RESP24','P_RESP25','P_RESP26','P_RESP27','P_RESP28','P_RESP29','P_RESP30',
  'P_RESP31','P_RESP32','P_RESP33','P_RESP34','P_RESP35','P_RESP36','P_RESP37','P_RESP38','P_RESP39','P_RESP40',
  'P_RESP41','P_RESP42','P_RESP43','P_RESP44','P_RESP45','P_RESP46','P_RESP47','P_RESP48','P_RESP49','P_RESP50',
  'P_RESP51','P_RESP52','P_RESP53','P_RESP54','P_RESP55','P_RESP56','P_RESP57','P_RESP58','P_RESP59','P_RESP60',
  'P_RESP61','P_RESP62','P_RESP63','P_RESP64','P_RESP65','P_RESP66','P_RESP67','P_RESP68','P_RESP69','P_RESP70',
  'P_RESP71','P_RESP72','P_RESP73','P_RESP74','P_RESP75','P_RESP76','P_RESP77','P_RESP78','P_RESP79','P_RESP80',
  'P_OBS1' ,'P_OBS2' ,'P_OBS3' ,'P_OBS4' ,'P_OBS5' ,'P_OBS6' ,'P_OBS7' ,'P_OBS8' ,'P_OBS9' ,'P_OBS10',
  'P_OBS11','P_OBS12','P_OBS13','P_OBS14','P_OBS15','P_OBS16','P_OBS17','P_OBS18','P_OBS19','P_OBS20',
  'P_OBS21','P_OBS22','P_OBS23','P_OBS24','P_OBS25','P_OBS26','P_OBS27','P_OBS28','P_OBS29','P_OBS30',
  'P_OBS31','P_OBS32','P_OBS33','P_OBS34','P_OBS35','P_OBS36','P_OBS37','P_OBS38','P_OBS39','P_OBS40',
  'P_OBS41','P_OBS42','P_OBS43','P_OBS44','P_OBS45','P_OBS46','P_OBS47','P_OBS48','P_OBS49','P_OBS50',
  'P_OBS51','P_OBS52','P_OBS53','P_OBS54','P_OBS55','P_OBS56','P_OBS57','P_OBS58','P_OBS59','P_OBS60',
  'P_OBS61','P_OBS62','P_OBS63','P_OBS64','P_OBS65','P_OBS66','P_OBS67','P_OBS68','P_OBS69','P_OBS70',
  'P_OBS71','P_OBS72','P_OBS73','P_OBS74','P_OBS75','P_OBS76','P_OBS77','P_OBS78','P_OBS79','P_OBS80',
  'VISITAQ_STATUS','FECREG','IP','LOGIN','FECHA_M','IP_M','LOGIN_M')
                        ->where('VISITA_FOLIO',$id)
                        ->first();
        if($regquestdili->count() <= 0){
        //if($regvisitt->count() <= 0){
            toastr()->error('No existe cuestionario de diligencia.','Lo siento!',['positionClass' => 'toast-bottom-right']);
            ////return redirect()->route('nuevoProgdil');
        }
        return view('sicinar.cuestionario.editarQuestion',compact('regiap','regrubro','regagenda','regseccion','regtipopreg','regpreguntas','regquestion','regquestdili','nombre','usuario'));

    }

    public function actionActualizarQuestion(visitaquestionRequest $request, $id){
        $nombre        = session()->get('userlog');
        $pass          = session()->get('passlog');
        if($nombre == NULL AND $pass == NULL){
            return view('sicinar.login.expirada');
        }
        $usuario       = session()->get('usuario');
        //$estructura    = session()->get('estructura');
        //$id_estruc     = session()->get('id_estructura');
        //$id_estructura = rtrim($id_estruc," ");
        $rango         = session()->get('rango');
        $ip            = session()->get('ip');

        /************ Bitacora inicia *************************************/ 
        setlocale(LC_TIME, "spanish");        
        $xip          = session()->get('ip');
        $xperiodo_id  = (int)date('Y');
        $xprograma_id = 1;
        $xmes_id      = (int)date('m');
        $xproceso_id  =        3;
        $xfuncion_id  =     3006;
        $xtrx_id      =      191;    //Actualizar         

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
                'NO_VECES' => $regbitacora->NO_VECES = $xno_veces,
                'IP_M' => $regbitacora->IP           = $ip,
                'LOGIN_M' => $regbitacora->LOGIN_M   = $nombre,
                'FECHA_M' => $regbitacora->FECHA_M   = date('Y/m/d')  //date('d/m/Y')
            ]);
            toastr()->success('Bitacora actualizada.','¡Ok!',['positionClass' => 'toast-bottom-right']);
        }
        /************ Bitacora termina *************************************/         

        // **************** actualizar ******************************
        $regquestdili = regVisitaQuestionsModel::where('VISITA_FOLIO',$id);
        if($regquestdili->count() <= 0)
            toastr()->error('No existe cuestionario de diligencia.','¡Por favor volver a intentar!',['positionClass' => 'toast-bottom-right']);
        else{        
            //************************** Realiza actulización ********************
            //$mes = regMesesModel::ObtMes($request->mes2_id);
            //$dia = regDiasModel::ObtDia($request->dia2_id);
            $regquestdili = regVisitaQuestionsModel::where('VISITA_FOLIO',$id)        
            ->update([                
                //'QUESTION_NO'     => $request->question_no,
                //'IAP_ID'          => $request->iap_id,
                'VISITAQ_SPUB1'   => strtoupper($request->visitaq_spub1),
                'VISITAQ_CARGO1'  => strtoupper($request->visitaq_cargo1),

                'PREG_ID1'        => $request->preg_id1,
                'PREG_ID2'        => $request->preg_id2,
                'PREG_ID3'        => $request->preg_id3,
                'PREG_ID4'        => $request->preg_id4,
                'PREG_ID5'        => $request->preg_id5,
                'PREG_ID6'        => $request->preg_id6,
                'PREG_ID7'        => $request->preg_id7,
                'PREG_ID8'        => $request->preg_id8,
                'PREG_ID9'        => $request->preg_id9,
                'PREG_ID10'       => $request->preg_id10,
                'PREG_ID11'       => $request->preg_id11,
                'PREG_ID12'       => $request->preg_id12,
                'PREG_ID13'       => $request->preg_id13,
                'PREG_ID14'       => $request->preg_id14,
                'PREG_ID15'       => $request->preg_id15,
                'PREG_ID16'       => $request->preg_id16,
                'PREG_ID17'       => $request->preg_id17,
                'PREG_ID18'       => $request->preg_id18,
                'PREG_ID19'       => $request->preg_id19,
                'PREG_ID20'       => $request->preg_id20,
                'PREG_ID21'       => $request->preg_id21,
                'PREG_ID22'       => $request->preg_id22,
                'PREG_ID23'       => $request->preg_id23,
                'PREG_ID24'       => $request->preg_id24,
                'PREG_ID25'       => $request->preg_id25,
                'PREG_ID26'       => $request->preg_id26,
                'PREG_ID27'       => $request->preg_id27,
                'PREG_ID28'       => $request->preg_id28,
                'PREG_ID29'       => $request->preg_id29,
                'PREG_ID30'       => $request->preg_id30,
                'PREG_ID31'       => $request->preg_id31,
                'PREG_ID32'       => $request->preg_id32,
                'PREG_ID33'       => $request->preg_id33,
                'PREG_ID34'       => $request->preg_id34,
                'PREG_ID35'       => $request->preg_id35,
                'PREG_ID36'       => $request->preg_id36,
                'PREG_ID37'       => $request->preg_id37,
                'PREG_ID38'       => $request->preg_id38,
                'PREG_ID39'       => $request->preg_id39,
                'PREG_ID40'       => $request->preg_id40,
                'PREG_ID41'       => $request->preg_id41,
                'PREG_ID42'       => $request->preg_id43,
                'PREG_ID43'       => $request->preg_id43,
                'PREG_ID44'       => $request->preg_id44,
                'PREG_ID45'       => $request->preg_id45,
                'PREG_ID46'       => $request->preg_id46,
                'PREG_ID47'       => $request->preg_id47,
                'PREG_ID48'       => $request->preg_id48,
                'PREG_ID49'       => $request->preg_id49,
                'PREG_ID50'       => $request->preg_id50,
                'PREG_ID51'       => $request->preg_id51,
                'PREG_ID52'       => $request->preg_id52,
                'PREG_ID53'       => $request->preg_id53,
                'PREG_ID54'       => $request->preg_id54,
                'PREG_ID55'       => $request->preg_id55,
                'PREG_ID56'       => $request->preg_id56,
                'PREG_ID57'       => $request->preg_id57,
                'PREG_ID58'       => $request->preg_id58,
                'PREG_ID59'       => $request->preg_id59,
                'PREG_ID60'       => $request->preg_id60,

                'P_RESP1'         => $request->p_resp1,
                'P_RESP2'         => $request->p_resp2,
                'P_RESP3'         => $request->p_resp3,
                'P_RESP4'         => $request->p_resp4,
                'P_RESP5'         => $request->p_resp5,
                'P_RESP6'         => $request->p_resp6,
                'P_RESP7'         => $request->p_resp7,
                'P_RESP8'         => $request->p_resp8,
                'P_RESP9'         => $request->p_resp9,
                'P_RESP10'        => $request->p_resp10,
                'P_RESP11'        => $request->p_resp11,
                'P_RESP12'        => $request->p_resp12,
                'P_RESP13'        => $request->p_resp13,
                'P_RESP14'        => $request->p_resp14,
                'P_RESP15'        => $request->p_resp15,
                'P_RESP16'        => $request->p_resp16,
                'P_RESP17'        => $request->p_resp17,
                'P_RESP18'        => $request->p_resp18,
                'P_RESP19'        => $request->p_resp19,
                'P_RESP20'        => $request->p_resp20,
                'P_RESP21'        => $request->p_resp21,
                'P_RESP22'        => $request->p_resp22,
                'P_RESP23'        => $request->p_resp23,
                'P_RESP24'        => $request->p_resp24,
                'P_RESP25'        => $request->p_resp25,
                'P_RESP26'        => $request->p_resp26,
                'P_RESP27'        => $request->p_resp27,
                'P_RESP28'        => $request->p_resp28,
                'P_RESP29'        => $request->p_resp29,
                'P_RESP30'        => $request->p_resp30,
                'P_RESP31'        => $request->p_resp31,
                'P_RESP32'        => $request->p_resp32,
                'P_RESP33'        => $request->p_resp33,
                'P_RESP34'        => $request->p_resp34,
                'P_RESP35'        => $request->p_resp35,
                'P_RESP36'        => $request->p_resp36,
                'P_RESP37'        => $request->p_resp37,
                'P_RESP38'        => $request->p_resp38,
                'P_RESP39'        => $request->p_resp39,
                'P_RESP40'        => $request->p_resp40,
                'P_RESP41'        => $request->p_resp41,
                'P_RESP42'        => $request->p_resp42,
                'P_RESP43'        => $request->p_resp43,
                'P_RESP44'        => $request->p_resp44,
                'P_RESP45'        => $request->p_resp45,
                'P_RESP46'        => $request->p_resp46,
                'P_RESP47'        => $request->p_resp47,
                'P_RESP48'        => $request->p_resp48,
                'P_RESP49'        => $request->p_resp49,
                'P_RESP50'        => $request->p_resp50,
                'P_RESP51'        => $request->p_resp51,
                'P_RESP52'        => $request->p_resp52,
                'P_RESP53'        => $request->p_resp53,
                'P_RESP54'        => $request->p_resp54,
                'P_RESP55'        => $request->p_resp55,
                'P_RESP56'        => $request->p_resp56,
                'P_RESP57'        => $request->p_resp57,
                'P_RESP58'        => $request->p_resp58,
                'P_RESP59'        => $request->p_resp59,
                'P_RESP60'        => $request->p_resp60,                                

                'P_OBS1'          => strtoupper($request->p_obs1),
                'P_OBS2'          => strtoupper($request->p_obs2),
                'P_OBS3'          => strtoupper($request->p_obs3),
                'P_OBS4'          => strtoupper($request->p_obs4),
                'P_OBS5'          => strtoupper($request->p_obs5),
                'P_OBS6'          => strtoupper($request->p_obs6),
                'P_OBS7'          => strtoupper($request->p_obs7),
                'P_OBS8'          => strtoupper($request->p_obs8),                
                'P_OBS9'          => strtoupper($request->p_obs9),
                'P_OBS10'         => strtoupper($request->p_obs10),
                'P_OBS11'         => strtoupper($request->p_obs11),
                'P_OBS12'         => strtoupper($request->p_obs12),
                'P_OBS13'         => strtoupper($request->p_obs13),
                'P_OBS14'         => strtoupper($request->p_obs14),
                'P_OBS15'         => strtoupper($request->p_obs15),
                'P_OBS16'         => strtoupper($request->p_obs16),
                'P_OBS17'         => strtoupper($request->p_obs17),
                'P_OBS18'         => strtoupper($request->p_obs18),                
                'P_OBS19'         => strtoupper($request->p_obs19),
                'P_OBS20'         => strtoupper($request->p_obs20),
                'P_OBS21'         => strtoupper($request->p_obs21),
                'P_OBS22'         => strtoupper($request->p_obs22),
                'P_OBS23'         => strtoupper($request->p_obs23),
                'P_OBS24'         => strtoupper($request->p_obs24),
                'P_OBS25'         => strtoupper($request->p_obs25),
                'P_OBS26'         => strtoupper($request->p_obs26),
                'P_OBS27'         => strtoupper($request->p_obs27),
                'P_OBS28'         => strtoupper($request->p_obs28),                
                'P_OBS29'         => strtoupper($request->p_obs29),
                'P_OBS30'         => strtoupper($request->p_obs30),
                'P_OBS31'         => strtoupper($request->p_obs31),
                'P_OBS32'         => strtoupper($request->p_obs32),
                'P_OBS33'         => strtoupper($request->p_obs33),
                'P_OBS34'         => strtoupper($request->p_obs34),
                'P_OBS35'         => strtoupper($request->p_obs35),
                'P_OBS36'         => strtoupper($request->p_obs36),
                'P_OBS37'         => strtoupper($request->p_obs37),
                'P_OBS38'         => strtoupper($request->p_obs38),                
                'P_OBS39'         => strtoupper($request->p_obs39),
                'P_OBS40'         => strtoupper($request->p_obs40),
                'P_OBS41'         => strtoupper($request->p_obs41),
                'P_OBS42'         => strtoupper($request->p_obs42),
                'P_OBS43'         => strtoupper($request->p_obs43),
                'P_OBS44'         => strtoupper($request->p_obs44),
                'P_OBS45'         => strtoupper($request->p_obs45),
                'P_OBS46'         => strtoupper($request->p_obs46),
                'P_OBS47'         => strtoupper($request->p_obs47),
                'P_OBS48'         => strtoupper($request->p_obs48),                
                'P_OBS49'         => strtoupper($request->p_obs49),
                'P_OBS50'         => strtoupper($request->p_obs50),                                                
                'P_OBS51'         => strtoupper($request->p_obs51),
                'P_OBS52'         => strtoupper($request->p_obs52),
                'P_OBS53'         => strtoupper($request->p_obs53),
                'P_OBS54'         => strtoupper($request->p_obs54),
                'P_OBS55'         => strtoupper($request->p_obs55),
                'P_OBS56'         => strtoupper($request->p_obs56),
                'P_OBS57'         => strtoupper($request->p_obs57),
                'P_OBS58'         => strtoupper($request->p_obs58),                
                'P_OBS59'         => strtoupper($request->p_obs59),
                'P_OBS60'         => strtoupper($request->p_obs60),                
                'VISITAQ_STATUS'  => $request->visitaq_status,                
                'IP_M'            => $ip,
                'LOGIN_M'         => $nombre,
                'FECHA_M'         => date('Y/m/d')    //date('d/m/Y')                                
            ]);
            toastr()->success('Cuestionario de diligencia actualizado correctamente.','¡Ok!',['positionClass' => 'toast-bottom-right']);
        }
        return redirect()->route('verQuestions');
    }

    public function actionBorrarQuestion($id){
        //dd($request->all());
        $nombre       = session()->get('userlog');
        $pass         = session()->get('passlog');
        if($nombre == NULL AND $pass == NULL){
            return view('sicinar.login.expirada');
        }
        $usuario      = session()->get('usuario');
        //$estructura   = session()->get('estructura');
        //$id_estruc    = session()->get('id_estructura');
        //$id_estructura= rtrim($id_estruc," ");
        $rango        = session()->get('rango');
        $ip           = session()->get('ip');
        //echo 'Ya entre aboorar registro..........';
        /************ Bitacora inicia *************************************/ 
        setlocale(LC_TIME, "spanish");        
        $xip          = session()->get('ip');
        $xperiodo_id  = (int)date('Y');
        $xprograma_id = 1;
        $xmes_id      = (int)date('m');
        $xproceso_id  =        3;
        $xfuncion_id  =     3006;
        $xtrx_id      =      192;     // Baja 

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
                'NO_VECES' => $regbitacora->NO_VECES = $xno_veces,
                'IP_M' => $regbitacora->IP           = $ip,
                'LOGIN_M' => $regbitacora->LOGIN_M   = $nombre,
                'FECHA_M' => $regbitacora->FECHA_M   = date('Y/m/d')  //date('d/m/Y')
            ]);
            toastr()->success('Bitacora actualizada.','¡Ok!',['positionClass' => 'toast-bottom-right']);
        }
        /************ Bitacora termina *************************************/     
        /************ Elimina la IAP **************************************/
        $regquestdili = regVisitaQuestionsModel::where('VISITA_FOLIO',$id);
        //                    ->find('RUBRO_ID',$id);
        if($regquestdili->count() <= 0)
            toastr()->error('No existe cuestionario.','¡Por favor volver a intentar!',['positionClass' => 'toast-bottom-right']);
        else{        
            $regquestdili->delete();
            toastr()->success('Cuestionario ha sido eliminado.','¡Ok!',['positionClass' => 'toast-bottom-right']);
        }
        /************* Termina de eliminar  la IAP **********************************/
        return redirect()->route('verQuestions');
    }    

    // exportar a formato PDF
    public function actionQuestionPDF($id){
        set_time_limit(0);
        ini_set("memory_limit",-1);
        ini_set('max_execution_time', 0);

        $nombre       = session()->get('userlog');
        $pass         = session()->get('passlog');
        if($nombre == NULL AND $pass == NULL){
            return view('sicinar.login.expirada');
        }
        $usuario       = session()->get('usuario');
        //$estructura    = session()->get('estructura');
        //$id_estruc     = session()->get('id_estructura');
        //$id_estructura = rtrim($id_estruc," ");
        $rango         = session()->get('rango');
        $ip           = session()->get('ip');

        /************ Bitacora inicia *************************************/ 
        setlocale(LC_TIME, "spanish");        
        $xip          = session()->get('ip');
        $xperiodo_id  = (int)date('Y');
        $xprograma_id = 1;
        $xmes_id      = (int)date('m');
        $xproceso_id  =        3;
        $xfuncion_id  =     3006;
        $xtrx_id      =      194;     // pdf
        $id           =      $id;

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
                'NO_VECES' => $regbitacora->NO_VECES = $xno_veces,
                'IP_M' => $regbitacora->IP           = $ip,
                'LOGIN_M' => $regbitacora->LOGIN_M   = $nombre,
                'FECHA_M' => $regbitacora->FECHA_M   = date('Y/m/d')  //date('d/m/Y')
            ]);
            toastr()->success('Bitacora actualizada.','¡Ok!',['positionClass' => 'toast-bottom-right']);
        }
        /************ Bitacora termina *************************************/ 

        $regiap       = regIapModel::select('IAP_ID','IAP_DESC')->orderBy('IAP_ID','asc')
                        ->get();         
        $regrubro     = regRubroModel::select('RUBRO_ID','RUBRO_DESC')->orderBy('RUBRO_ID','asc')
                        ->get();   
        $regagenda    = regAgendaModel::select('VISITA_FOLIO','PERIODO_ID','MES_ID','DIA_ID','HORA_ID','NUM_ID','IAP_ID')
                        ->orderBy('VISITA_FOLIO','asc')
                        ->get();
        $regseccion   = regSeccionesModel::select('SEC_ID','SEC_DESC')->orderBy('SEC_ID','asc')
                        ->get();       
        $regtipopreg  = regTipoPregModel::select('TIPOP_ID','TIPOP_DESC')->orderBy('TIPOP_ID','asc')
                        ->get();     
        $regpreguntas = regPreguntasModel::select('PREG_ID','PREG_DESC')->orderBy('PREG_ID','asc')
                        ->get();                                                                        
        $regquestion  = regQuestionsModel::join('JP_CAT_PREGUNTAS','JP_CAT_PREGUNTAS.PREG_ID', '=','JP_QUESTIONS.PREG_ID')
                        ->select('JP_QUESTIONS.QUESTION_NO','JP_QUESTIONS.PREG_ID','JP_CAT_PREGUNTAS.PREG_DESC',
                                 'JP_QUESTIONS.TIPOP_ID'   ,'JP_QUESTIONS.SECCION_ID' ,'JP_QUESTIONS.RUBRO_ID')
                        ->orderBy('JP_QUESTIONS.PREG_ID','ASC')
                        ->get();
        $regquestdili = regVisitaQuestionsModel::select('VISITA_FOLIO','QUESTION_NO','IAP_ID', 'VISITAQ_SPUB1',
            'VISITAQ_SPUB2','VISITAQ_CARGO1','VISITAQ_CARGO2',
  'PREG_ID1' ,'PREG_ID2' ,'PREG_ID3' ,'PREG_ID4' ,'PREG_ID5' ,'PREG_ID6' ,'PREG_ID7' ,'PREG_ID8' ,'PREG_ID9' ,'PREG_ID10',
  'PREG_ID11','PREG_ID12','PREG_ID13','PREG_ID14','PREG_ID15','PREG_ID16','PREG_ID17','PREG_ID18','PREG_ID19','PREG_ID20',
  'PREG_ID21','PREG_ID22','PREG_ID23','PREG_ID24','PREG_ID25','PREG_ID26','PREG_ID27','PREG_ID28','PREG_ID29','PREG_ID30',
  'PREG_ID31','PREG_ID32','PREG_ID33','PREG_ID34','PREG_ID35','PREG_ID36','PREG_ID37','PREG_ID38','PREG_ID39','PREG_ID40',
  'PREG_ID41','PREG_ID42','PREG_ID43','PREG_ID44','PREG_ID45','PREG_ID46','PREG_ID47','PREG_ID48','PREG_ID49','PREG_ID50',
  'PREG_ID51','PREG_ID52','PREG_ID53','PREG_ID54','PREG_ID55','PREG_ID56','PREG_ID57','PREG_ID58','PREG_ID59','PREG_ID60',
  'PREG_ID61','PREG_ID62','PREG_ID63','PREG_ID64','PREG_ID65','PREG_ID66','PREG_ID67','PREG_ID68','PREG_ID69','PREG_ID70',
  'PREG_ID71','PREG_ID72','PREG_ID73','PREG_ID74','PREG_ID75','PREG_ID76','PREG_ID77','PREG_ID78','PREG_ID79','PREG_ID80',
  'P_RESP1' ,'P_RESP2' ,'P_RESP3' ,'P_RESP4' ,'P_RESP5' ,'P_RESP6' ,'P_RESP7' ,'P_RESP8' ,'P_RESP9' ,'P_RESP10',
  'P_RESP11','P_RESP12','P_RESP13','P_RESP14','P_RESP15','P_RESP16','P_RESP17','P_RESP18','P_RESP19','P_RESP20',
  'P_RESP21','P_RESP22','P_RESP23','P_RESP24','P_RESP25','P_RESP26','P_RESP27','P_RESP28','P_RESP29','P_RESP30',
  'P_RESP31','P_RESP32','P_RESP33','P_RESP34','P_RESP35','P_RESP36','P_RESP37','P_RESP38','P_RESP39','P_RESP40',
  'P_RESP41','P_RESP42','P_RESP43','P_RESP44','P_RESP45','P_RESP46','P_RESP47','P_RESP48','P_RESP49','P_RESP50',
  'P_RESP51','P_RESP52','P_RESP53','P_RESP54','P_RESP55','P_RESP56','P_RESP57','P_RESP58','P_RESP59','P_RESP60',
  'P_RESP61','P_RESP62','P_RESP63','P_RESP64','P_RESP65','P_RESP66','P_RESP67','P_RESP68','P_RESP69','P_RESP70',
  'P_RESP71','P_RESP72','P_RESP73','P_RESP74','P_RESP75','P_RESP76','P_RESP77','P_RESP78','P_RESP79','P_RESP80',
  'P_OBS1' ,'P_OBS2' ,'P_OBS3' ,'P_OBS4' ,'P_OBS5' ,'P_OBS6' ,'P_OBS7' ,'P_OBS8' ,'P_OBS9' ,'P_OBS10',
  'P_OBS11','P_OBS12','P_OBS13','P_OBS14','P_OBS15','P_OBS16','P_OBS17','P_OBS18','P_OBS19','P_OBS20',
  'P_OBS21','P_OBS22','P_OBS23','P_OBS24','P_OBS25','P_OBS26','P_OBS27','P_OBS28','P_OBS29','P_OBS30',
  'P_OBS31','P_OBS32','P_OBS33','P_OBS34','P_OBS35','P_OBS36','P_OBS37','P_OBS38','P_OBS39','P_OBS40',
  'P_OBS41','P_OBS42','P_OBS43','P_OBS44','P_OBS45','P_OBS46','P_OBS47','P_OBS48','P_OBS49','P_OBS50',
  'P_OBS51','P_OBS52','P_OBS53','P_OBS54','P_OBS55','P_OBS56','P_OBS57','P_OBS58','P_OBS59','P_OBS60',
  'P_OBS61','P_OBS62','P_OBS63','P_OBS64','P_OBS65','P_OBS66','P_OBS67','P_OBS68','P_OBS69','P_OBS70',
  'P_OBS71','P_OBS72','P_OBS73','P_OBS74','P_OBS75','P_OBS76','P_OBS77','P_OBS78','P_OBS79','P_OBS80',
  'VISITAQ_STATUS','FECREG','IP','LOGIN','FECHA_M','IP_M','LOGIN_M')
                        ->where('VISITA_FOLIO',$id)
                        ->get();
        if($regquestdili->count() <= 0){
            toastr()->error('No existe cuestionario de diligencia.','Uppss!',['positionClass' => 'toast-bottom-right']);
        }
        $pdf = PDF::loadView('sicinar.pdf.questionPDF', compact('regiap','regrubro','regagenda','regseccion','regtipopreg','regpreguntas','regquestion','regquestdili','nombre','usuario'));
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
        return $pdf->stream('CriteriosDeVerificacion');
    }

}
