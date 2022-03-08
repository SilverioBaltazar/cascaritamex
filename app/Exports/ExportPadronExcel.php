<?php
namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use App\regMetadatoModel;

use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;

class ExportPadronExcel implements FromCollection, /*FromQuery,*/ WithHeadings
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function headings(): array
    {
        return [
            'VERTIENTE',
            'AÑO',
            'TRIMESTRE',
            'CANT_APOYOS_RECIBIDOS',
            'FOLIO_RELACIONADO',
            'PRIMER_AP',
            'SEGUDNO_AP',
            'NOMBRES',
            'FECHA_NACIMIENTO',
            'GENERO',
            'ID_OFICIAL',        
            'CT_ENT_NAC',                
            'CT_ENTIDAD_NACIMIENTO',            
            'CURP',
            'CALLE',
            'NUM_EXT',
            'NUM_INT',
            'ENTRE_CALLE',            
            'Y_CALLE',            
            'OTRA_REFERENCIA',            
            'COLONIA',
            'CT_LOCALIDAD',
            'LOCALIDAD',
            'CT_MUNICIPIO',
            'MUNICIPIO',
            'CT_ENTIDAD_FEDERATIVA',
            'CODIGO_POSTAL',                                                                        
            'TELEFONO',
            'CELULAR',
            'E_MAIL',
            'ID_EQUIPO',                        
            'EQUIPO',            
            'STATUS',
            'FECHA_REGISTRO'
        ];
    }

    public function collection()
    {
        $arbol_id     = session()->get('arbol_id');        
        //********* Validar rol de usuario **********************/
        if(session()->get('rango') !== '0'){                          
            return regMetadatoModel::from('CASMEX_METADATO as A')
                                   ->join('CASMEX_CAT_ENTIDADES_FED','CASMEX_CAT_ENTIDADES_FED.ENTIDADFED_ID','=', 
                                                                     'A.ENTIDAD_FED_ID')
                                   ->join('CASMEX_FICHA_EQUIPO'     ,'CASMEX_FICHA_EQUIPO.EQ_ID','=',
                                                                     'A.EQ_ID')
                                   ->join('CASMEX_CAT_MUNICIPIOS_SEDESEM',
                                        [['CASMEX_CAT_MUNICIPIOS_SEDESEM.MUNICIPIOID','=','A.MUNICIPIO_ID'],
                                         ['CASMEX_CAT_MUNICIPIOS_SEDESEM.ENTIDADFEDERATIVAID','=',15]
                                        ])                                 
                               ->select(
                                        0,
                                        2022,
                                        2,
                                        1,
                                        'A.FOLIO',
                                        'A.PRIMER_APELLIDO',
                                        'A.SEGUNDO_APELLIDO',
                                        'A.NOMBRES',
                                        'A.FECHA_NACIMIENTO2',     
                                        'A.SEXO', 
                                        9,     
                                        'A.ID_OFICIAL',  
                                        'A.ENTIDAD_NAC_ID',
                                        'CASMEX_CAT_ENTIDADES_FED.ENTIDADFED_DESC', 
                                        'A.CURP',
                                        'A.DOMICILIO', 
                                        'A.ENTRE_CALLE', 
                                        'A.Y_CALLE',      
                                        'A.OTRA_REFERENCIA',
                                        'A.COLONIA',
                                        'A.LOCALIDAD_ID', 
                                        'A.LOCALIDAD', 
                                        'A.MUNICIPIO_ID',                        
                                        'CASMEX_CAT_MUNICIPIOS_SEDESEM.MUNICIPIONOMBRE', 
                                        15,
                                        'A.CP',
                                        'A.TELEFONO', 
                                        'A.CELULAR', 
                                        'A.E_MAIL',   
                                        'A.EQ_ID',   
                                        'CASMEX_FICHA_EQUIPO.EQ_DESC', 
                                        'A.STATUS_1',
                                        'A.FECHA_REG'                                         
                                       )
                              ->orderBy('A.EQ_ID'          ,'ASC')
                              ->orderBy('A.NOMBRE_COMPLETO','ASC')
                              ->get();                               
        }else{
            return regMetadatoModel::from('CASMEX_METADATO as A')
                                   ->join('CASMEX_CAT_ENTIDADES_FED','CASMEX_CAT_ENTIDADES_FED.ENTIDADFED_ID','=', 
                                                                     'A.ENTIDAD_FED_ID')
                                   ->join('CASMEX_FICHA_EQUIPO'     ,'CASMEX_FICHA_EQUIPO.EQ_ID','=',
                                                                     'A.EQ_ID')
                                   ->join('CASMEX_CAT_MUNICIPIOS_SEDESEM',
                                        [['CASMEX_CAT_MUNICIPIOS_SEDESEM.MUNICIPIOID','=','A.MUNICIPIO_ID'],
                                         ['CASMEX_CAT_MUNICIPIOS_SEDESEM.ENTIDADFEDERATIVAID','=',15]
                                        ])                                 
                               ->select(
                                        0,
                                        2022,
                                        2,
                                        1,
                                        'A.FOLIO',
                                        'A.PRIMER_APELLIDO',
                                        'A.SEGUNDO_APELLIDO',
                                        'A.NOMBRES',
                                        'A.FECHA_NACIMIENTO2',     
                                        'A.SEXO', 
                                        9,     
                                        'A.ID_OFICIAL',  
                                        'A.ENTIDAD_NAC_ID',
                                        'CASMEX_CAT_ENTIDADES_FED.ENTIDADFED_DESC', 
                                        'A.CURP',
                                        'A.DOMICILIO', 
                                        'A.ENTRE_CALLE', 
                                        'A.Y_CALLE',      
                                        'A.OTRA_REFERENCIA',
                                        'A.COLONIA',
                                        'A.LOCALIDAD_ID',
                                        'A.LOCALIDAD',                                      
                                        'A.MUNICIPIO_ID',                        
                                        'CASMEX_CAT_MUNICIPIOS_SEDESEM.MUNICIPIONOMBRE', 
                                        15,
                                        'A.CP',
                                        'A.TELEFONO', 
                                        'A.CELULAR', 
                                        'A.E_MAIL',   
                                        'A.EQ_ID',   
                                        'CASMEX_FICHA_EQUIPO.EQ_DESC', 
                                        'A.STATUS_1',
                                        'A.FECHA_REG' 
                                       )
                              ->where(  'A.EQ_ID'          ,$arbol_id)
                              ->orderBy('A.EQ_ID'          ,'ASC')
                              ->orderBy('A.NOMBRE_COMPLETO','ASC')
                              ->get();               
        }                            
    }
}
