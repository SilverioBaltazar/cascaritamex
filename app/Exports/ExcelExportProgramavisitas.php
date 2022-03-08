<?php
namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithTitle;

use App\regHorasModel;
use App\regAgendaModel;


// class ExcelExportProgramavisitas implements FromQuery,  WithHeadings   ojo jala con el query************
class ExcelExportProgramavisitas implements FromCollection, /*FromQuery,*/ WithHeadings, WithTitle
{

    //********** Parámetros de filtro del query *******************//
    private $periodo;
    private $mes;
    private $tipo;
 
    public function __construct(int $periodo, int $mes, string $tipo)
    {
        $this->periodo = $periodo;
        $this->mes     = $mes;
        $this->tipo    = $tipo;
    }

    /**
    * @return \Illuminate\Support\Collection
    */
    public function headings(): array
    {
        return [
            'PERIODO_FISCAL',
            'MES',
            'DIA',            
            'FOLIO',  
            'ID_OSC',
            'OSC',
            'REG_CONSTITUCION',
            'RFC',
            'DOMICILIO',
            'ENTIDAD',
            'MUNICIPIO',
            'FECHA_VISITA',
            'HORA',
            'CONTACTO',
            'OBJETO_VISITA_PARTE_1',
            'OBJETO_VISITA_PARTE_2',
            'TIPO_VISITA'
        ];
    }


    public function collection()
    {
        //dd($id);
        //$arbol_id     = session()->get('arbol_id');  
        //$id           = session()->get('sfolio');    
        return  regAgendaModel::join('PE_CAT_HORAS','PE_CAT_HORAS.HORA_ID','=','PE_AGENDA.HORA_ID')
                              ->join('PE_OSC'      ,'PE_OSC.OSC_ID'       ,'=','PE_AGENDA.OSC_ID')
                            ->select('PE_AGENDA.PERIODO_ID',
                                     'PE_AGENDA.MES_ID','PE_AGENDA.DIA_ID',
                                     'PE_AGENDA.VISITA_FOLIO',
                                     'PE_AGENDA.OSC_ID', 
                                     'PE_OSC.OSC_DESC',
                                     'PE_OSC.OSC_REGCONS',
                                     'PE_OSC.OSC_RFC',
                                     'PE_AGENDA.VISITA_DOM',
                                     'PE_AGENDA.ENTIDAD_ID',
                                     'PE_AGENDA.MUNICIPIO_ID',
                                     'PE_AGENDA.VISITA_FECREGP',
                                     'PE_CAT_HORAS.HORA_DESC',
                                     'PE_AGENDA.VISITA_CONTACTO',                         
                                     'PE_AGENDA.VISITA_OBJ',
                                     'PE_AGENDA.VISITA_OBS3',
                                     'PE_AGENDA.VISITA_TIPO1'
                                     )
                            ->where( ['PERIODO_ID'   => $this->periodo, 
                                      'MES_ID'       => $this->mes //,
                                      //'VISITA_TIPO1' => $this->tipo
                                     ])
                            ->orderBy('PE_AGENDA.PERIODO_ID'  ,'ASC')
                            ->orderBy('PE_AGENDA.VISITA_FOLIO','ASC')    
                            ->get();                       

    }

    /**
     * @return string
     */
    public function title(): string
    {
        return 'Mes ' . $this->mes;
    }

    //public function query()
    //{
    //    return  regAgendaModel::query()
    //            ->where( ['PERIODO_ID'   => $this->periodo, 
    //                      'MES_ID'       => $this->mes,
    //                      'VISITA_TIPO1' => $this->tipo]);   
    //                        //->get();                                                           
    //}

}
