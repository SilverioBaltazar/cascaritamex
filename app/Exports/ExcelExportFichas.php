<?php
namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use App\regMunicipioModel;
use App\regCategoriasModel;
use App\regRegionesModel;
use App\regfichaModel;

use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;

class ExcelExportFichas implements FromCollection, /*FromQuery,*/ WithHeadings
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function headings(): array
    {
        return [
            'EQ_ID',
            'EQUIPO_FUTBOL',
            'REPRESENTANTE',
            'TELEFONO',
            'EMAIL',            
            'RAMA',
            'CATEGORIA',
            'MUNICIPIO_ID',
            'MUNICIPIO',
            'ID_REGION',
            'REGION',
            'ARCHIVO_DIGITAL_FICHA',        
            'FECHA_REGISTRO',
            //'VIGENCIA',
            'STATUS'            
        ];
    }

    public function collection()
    {
        return regfichaModel::join('CASMEX_CAT_MUNICIPIOS_SEDESEM',
                                      [['CASMEX_CAT_MUNICIPIOS_SEDESEM.ENTIDADFEDERATIVAID','=',15],
                                       ['CASMEX_CAT_MUNICIPIOS_SEDESEM.MUNICIPIOID','=','CASMEX_FICHA_EQUIPO.MUNICIPIO_ID']]) 
                            ->join('CASMEX_CAT_REGIONES',
                                           [['CASMEX_CAT_REGIONES.REGION_ID','=','CASMEX_CAT_MUNICIPIOS_SEDESEM.REGIONID'],
                                            ['CASMEX_CAT_MUNICIPIOS_SEDESEM.ENTIDADFEDERATIVAID','=',15]
                                           ])
                            ->join('CASMEX_CAT_CATEGORIAS'        ,'CASMEX_CAT_CATEGORIAS.CATE_ID','=',
                                                                   'CASMEX_FICHA_EQUIPO.CATE_ID')
                          ->select('CASMEX_FICHA_EQUIPO.EQ_ID',
                                   'CASMEX_FICHA_EQUIPO.EQ_DESC', 
                                   'CASMEX_FICHA_EQUIPO.EQ_NOMBRECOMP_REP',
                                   'CASMEX_FICHA_EQUIPO.EQ_TEL_REP',
                                   'CASMEX_FICHA_EQUIPO.EQ_EMAIL_REP',
                                   'CASMEX_FICHA_EQUIPO.EQ_RAMA',
                                   'CASMEX_CAT_CATEGORIAS.CATE_DESC',
                                   'CASMEX_FICHA_EQUIPO.MUNICIPIO_ID',
                                   'CASMEX_CAT_MUNICIPIOS_SEDESEM.MUNICIPIONOMBRE AS MUNICIPIO',         
                                   'CASMEX_CAT_REGIONES.DESC_REGION',
                                   'CASMEX_CAT_REGIONES.ROMA_REGION',                                   
                                   'CASMEX_FICHA_EQUIPO.EQ_ARC1',   
                                   'CASMEX_FICHA_EQUIPO.FEC_REG',
                                   //'CASMEX_CAT_INMUEBLES_EDO.INM_DESC','CASMEX_CAT_PERIODOS_ANIOS.PERIODO_DESC',
                                   //'CASMEX_CAT_PERIODOS_ANIOS.PERIODO_DESC',
                                   'CASMEX_FICHA_EQUIPO.EQ_STATUS1'
                                  )
                          ->orderBy('CASMEX_FICHA_EQUIPO.EQ_ID','ASC')
                          ->get();    
    //dd($regOscModel);                           
    }
}
