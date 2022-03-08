<?php
namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use App\regProgtrabModel;
use App\regProgdtrabModel;

use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;

class ExportProgtrabExcel implements FromCollection, /*FromQuery,*/ WithHeadings
{
    /**
    * @return \Illuminate\Support\Collection
    */ 
    public function headings(): array
    {
        return [
            'FOLIO',
            'PERIODO',
            'FECHA_ELAB',
            'OSC_ID',            
            'OSC',            
            'RESPONSABLE',
            'PROGRAMA',
            'ACTIVIDAD',
            'OBJETIVO',
            'UNID_MEDIDA',
            'ELABORO',
            'AUTORIZO',
            'OBSERVACIONES',
            'ESTADO',
            'FECHA_REGISTRO',            
            'ENE',
            'FEB',
            'MAR',
            'ABR',
            'MAY',            
            'JUN',
            'JUL',
            'AGO',
            'SEP',
            'OCT',
            'NOV',            
            'DIC',
            'TOTAL_META_PROGRAMADA'
        ];
    }

    public function collection()
    {
        $arbol_id     = session()->get('arbol_id');  
        //$id           = session()->get('sfolio');        
        //********* Validar rol de usuario **********************/
        if(session()->get('rango') !== '0'){                          
            //PE_PROGRAMA_ETRABAJO on PE_PROGRAMA_ETRABAJO.FOLIO   = PE_PROGRAMA_DTRABAJO.FOLIO 
            //            inner join PE_OSC              on PE_OSC.OSC_ID               = PE_PROGRAMA_ETRABAJO.OSC_ID 
            //            inner join PE_CAT_UNID_MEDIDA   on PE_CAT_UNID_MEDIDA.UMEDIDA_ID= PE_PROGRAMA_DTRABAJO.UMEDIDA_ID
            return regProgdtrabModel::join('PE_PROGRAMA_ETRABAJO','PE_PROGRAMA_ETRABAJO.FOLIO','=',
                                                                  'PE_PROGRAMA_DTRABAJO.FOLIO')
                   ->join(  'PE_OSC'             ,'PE_OSC.OSC_ID',                '=','PE_PROGRAMA_ETRABAJO.OSC_ID')
                   ->join(  'PE_CAT_UNID_MEDIDA' ,'PE_CAT_UNID_MEDIDA.UMEDIDA_ID','=','PE_PROGRAMA_DTRABAJO.UMEDIDA_ID')
                   ->select('PE_PROGRAMA_ETRABAJO.FOLIO', 
                            'PE_PROGRAMA_ETRABAJO.PERIODO_ID', 
                            'PE_PROGRAMA_ETRABAJO.FECHA_ELAB2', 
                            'PE_OSC.OSC_ID',               
                            'PE_OSC.OSC_DESC',        
                            'PE_PROGRAMA_ETRABAJO.RESPONSABLE', 
                            'PE_PROGRAMA_DTRABAJO.PROGRAMA_DESC', 
                            'PE_PROGRAMA_DTRABAJO.ACTIVIDAD_DESC', 
                            'PE_PROGRAMA_DTRABAJO.OBJETIVO_DESC', 
                            'PE_CAT_UNID_MEDIDA.UMEDIDA_DESC', 
                            'PE_PROGRAMA_ETRABAJO.ELABORO', 
                            'PE_PROGRAMA_ETRABAJO.AUTORIZO', 
                            'PE_PROGRAMA_ETRABAJO.OBS_1', 
                            'PE_PROGRAMA_ETRABAJO.STATUS_1', 
                            'PE_PROGRAMA_ETRABAJO.FECREG',                                   
                            'PE_PROGRAMA_DTRABAJO.MESP_01','PE_PROGRAMA_DTRABAJO.MESP_02','PE_PROGRAMA_DTRABAJO.MESP_03', 
                            'PE_PROGRAMA_DTRABAJO.MESP_04','PE_PROGRAMA_DTRABAJO.MESP_05','PE_PROGRAMA_DTRABAJO.MESP_06', 
                            'PE_PROGRAMA_DTRABAJO.MESP_07','PE_PROGRAMA_DTRABAJO.MESP_08','PE_PROGRAMA_DTRABAJO.MESP_09', 
                            'PE_PROGRAMA_DTRABAJO.MESP_10','PE_PROGRAMA_DTRABAJO.MESP_11','PE_PROGRAMA_DTRABAJO.MESP_12' 
                            )
                   ->selectRaw('(PE_PROGRAMA_DTRABAJO.MESP_01+PE_PROGRAMA_DTRABAJO.MESP_02+PE_PROGRAMA_DTRABAJO.MESP_03+
                                 PE_PROGRAMA_DTRABAJO.MESP_04+PE_PROGRAMA_DTRABAJO.MESP_05+PE_PROGRAMA_DTRABAJO.MESP_06+
                                 PE_PROGRAMA_DTRABAJO.MESP_07+PE_PROGRAMA_DTRABAJO.MESP_08+PE_PROGRAMA_DTRABAJO.MESP_09+
                                 PE_PROGRAMA_DTRABAJO.MESP_10+PE_PROGRAMA_DTRABAJO.MESP_11+PE_PROGRAMA_DTRABAJO.MESP_12)
                                 META_PROGRAMADA')
                   ->where(  'PE_PROGRAMA_ETRABAJO.FOLIO'     ,$id)
                   ->orderBy('PE_PROGRAMA_ETRABAJO.PERIODO_ID','ASC')                   
                   ->orderBy('PE_PROGRAMA_ETRABAJO.FOLIO'     ,'ASC')
                   ->get();                               
        }else{
            return regProgdtrabModel::join('PE_PROGRAMA_ETRABAJO','PE_PROGRAMA_ETRABAJO.FOLIO','=',
                                                                  'PE_PROGRAMA_DTRABAJO.FOLIO')
                   ->join(  'PE_OSC'             ,'PE_OSC.OSC_ID',                '=','PE_PROGRAMA_ETRABAJO.OSC_ID')
                   ->join(  'PE_CAT_UNID_MEDIDA' ,'PE_CAT_UNID_MEDIDA.UMEDIDA_ID','=','PE_PROGRAMA_DTRABAJO.UMEDIDA_ID')
                   ->select('PE_PROGRAMA_ETRABAJO.FOLIO', 
                            'PE_PROGRAMA_ETRABAJO.PERIODO_ID', 
                            'PE_PROGRAMA_ETRABAJO.FECHA_ELAB2', 
                            'PE_OSC.OSC_ID',               
                            'PE_OSC.OSC_DESC',        
                            'PE_PROGRAMA_ETRABAJO.RESPONSABLE', 
                            'PE_PROGRAMA_DTRABAJO.PROGRAMA_DESC', 
                            'PE_PROGRAMA_DTRABAJO.ACTIVIDAD_DESC', 
                            'PE_PROGRAMA_DTRABAJO.OBJETIVO_DESC',
                            'PE_CAT_UNID_MEDIDA.UMEDIDA_DESC', 
                            'PE_PROGRAMA_ETRABAJO.ELABORO', 
                            'PE_PROGRAMA_ETRABAJO.AUTORIZO', 
                            'PE_PROGRAMA_ETRABAJO.OBS_1', 
                            'PE_PROGRAMA_ETRABAJO.STATUS_1', 
                            'PE_PROGRAMA_ETRABAJO.FECREG',                         
                            'PE_PROGRAMA_DTRABAJO.MESP_01','PE_PROGRAMA_DTRABAJO.MESP_02','PE_PROGRAMA_DTRABAJO.MESP_03', 
                            'PE_PROGRAMA_DTRABAJO.MESP_04','PE_PROGRAMA_DTRABAJO.MESP_05','PE_PROGRAMA_DTRABAJO.MESP_06', 
                            'PE_PROGRAMA_DTRABAJO.MESP_07','PE_PROGRAMA_DTRABAJO.MESP_08','PE_PROGRAMA_DTRABAJO.MESP_09', 
                            'PE_PROGRAMA_DTRABAJO.MESP_10','PE_PROGRAMA_DTRABAJO.MESP_11','PE_PROGRAMA_DTRABAJO.MESP_12' 
                            )
                   ->selectRaw('(PE_PROGRAMA_DTRABAJO.MESP_01+PE_PROGRAMA_DTRABAJO.MESP_02+PE_PROGRAMA_DTRABAJO.MESP_03+
                                 PE_PROGRAMA_DTRABAJO.MESP_04+PE_PROGRAMA_DTRABAJO.MESP_05+PE_PROGRAMA_DTRABAJO.MESP_06+
                                 PE_PROGRAMA_DTRABAJO.MESP_07+PE_PROGRAMA_DTRABAJO.MESP_08+PE_PROGRAMA_DTRABAJO.MESP_09+
                                 PE_PROGRAMA_DTRABAJO.MESP_10+PE_PROGRAMA_DTRABAJO.MESP_11+PE_PROGRAMA_DTRABAJO.MESP_12)
                                 META_PROGRAMADA')
                   ->where(['PE_PROGRAMA_ETRABAJO.FOLIO' => $id, 'PE_PROGRAMA_ETRABAJO.OSC_ID' => $arbol_id])
                   //->where('PE_PROGRAMA_ETRABAJO.OSC_ID'    ,$arbol_id)
                   ->orderBy('PE_PROGRAMA_ETRABAJO.PERIODO_ID','ASC')
                   ->orderBy('PE_PROGRAMA_ETRABAJO.FOLIO'     ,'ASC')
                   ->get();               
        }                            
    }
}
