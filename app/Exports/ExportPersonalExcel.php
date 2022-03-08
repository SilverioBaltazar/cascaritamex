<?php
namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use App\regPersonalModel;

use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;

class ExportPersonalExcel implements FromCollection, /*FromQuery,*/ WithHeadings
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function headings(): array
    {
        return [
            'FOLIO',
            'IAP',
            'FECHA_INGRESO',
            'APELLIDO_PATERNO',
            'APELLIDO_MATERNO',
            'NOMBRES',
            'FECHA_NACIMIENTO',
            'CURP',
            'SEXO',
            'DOMICILIO',
            'CP',            
            'COLONIA',
            'ENTIDAD_FEDERATIVA',
            'MUNICIPIO',
            'TELEFONO',            
            'PUESTO',
            'GRADO_ESTUDIOS',
            'TIPO_EMP',
            'CLASE_EMP',
            'ACTIVIDADES',
            'SUELDO_MENSUAL',
            'STATUS',
            'FECHA_REGISTRO'
        ];
    }

    public function collection()
    {
        $arbol_id     = session()->get('arbol_id');        
        //********* Validar rol de usuario **********************/
        if(session()->get('rango') !== '0'){                          
            //return regPadronModel::join('JP_CAT_MUNICIPIOS_SEDESEM','JP_CAT_MUNICIPIOS_SEDESEM.MUNICIPIOID','=',
            //                                                        'JP_METADATO_PERSONAL.MUNICIPIO_ID') 
            //                ->wherein('JP_CAT_MUNICIPIOS_SEDESEM.ENTIDADFEDERATIVAID',[9,15,22])
            return regPersonalModel::join('JP_CAT_ENTIDADES_FED','JP_CAT_ENTIDADES_FED.ENTIDADFEDERATIVA_ID', '=', 
                                                               'JP_METADATO_PERSONAL.ENTIDAD_FED_ID')
                            ->join('JP_CAT_GRADO_ESTUDIOS'    ,'JP_CAT_GRADO_ESTUDIOS.GRADO_ESTUDIOS_ID','=',
                                                               'JP_METADATO_PERSONAL.GRADO_ESTUDIOS_ID')
                            ->join('JP_CAT_TIPOS_EMPLEADO'    ,'JP_CAT_TIPOS_EMPLEADO.TIPOEMP_ID','=',
                                                               'JP_METADATO_PERSONAL.TIPOEMP_ID')
                            ->join('JP_CAT_CLASES_EMPLEADO'   ,'JP_CAT_CLASES_EMPLEADO.CLASEEMP_ID','=',
                                                               'JP_METADATO_PERSONAL.CLASEEMP_ID')
                            ->join('JP_IAPS'                  ,'JP_IAPS.IAP_ID' ,'=','JP_METADATO_PERSONAL.IAP_ID')
                            ->select('JP_METADATO_PERSONAL.FOLIO',
                                     'JP_IAPS.IAP_DESC'        ,  
                                     'JP_METADATO_PERSONAL.FECHA_INGRESO2', 
                                     'JP_METADATO_PERSONAL.PRIMER_APELLIDO',
                                     'JP_METADATO_PERSONAL.SEGUNDO_APELLIDO',
                                     'JP_METADATO_PERSONAL.NOMBRES',
                                     'JP_METADATO_PERSONAL.FECHA_NACIMIENTO2',     
                                     'JP_METADATO_PERSONAL.CURP',     
                                     'JP_METADATO_PERSONAL.SEXO',     
                                     'JP_METADATO_PERSONAL.DOMICILIO',     
                                     'JP_METADATO_PERSONAL.CP', 
                                     'JP_METADATO_PERSONAL.COLONIA',
                                     'JP_CAT_ENTIDADES_FED.ENTIDADFEDERATIVA_DESC',
                                     'JP_METADATO_PERSONAL.LOCALIDAD',         
                                     'JP_METADATO_PERSONAL.TELEFONO',                                              
                                     'JP_METADATO_PERSONAL.PUESTO',
                                     'JP_CAT_GRADO_ESTUDIOS.GRADO_ESTUDIOS_DESC', 
                                     'JP_CAT_TIPOS_EMPLEADO.TIPOEMP_DESC', 
                                     'JP_CAT_CLASES_EMPLEADO.CLASEEMP_DESC', 
                                     'JP_METADATO_PERSONAL.OBS_1', 
                                     'JP_METADATO_PERSONAL.SUELDO_MENSUAL',
                                     'JP_METADATO_PERSONAL.STATUS_1',  
                                     'JP_METADATO_PERSONAL.FECHA_REG'
                                    )
                            ->orderBy('JP_METADATO_PERSONAL.NOMBRE_COMPLETO','ASC')
                            ->get();                               
        }else{
            return regPersonalModel::join('JP_CAT_ENTIDADES_FED','JP_CAT_ENTIDADES_FED.ENTIDADFEDERATIVA_ID', '=', 
                                                               'JP_METADATO_PERSONAL.ENTIDAD_FED_ID')
                            ->join('JP_CAT_GRADO_ESTUDIOS'    ,'JP_CAT_GRADO_ESTUDIOS.GRADO_ESTUDIOS_ID','=',
                                                               'JP_METADATO_PERSONAL.GRADO_ESTUDIOS_ID')
                            ->join('JP_CAT_TIPOS_EMPLEADO'    ,'JP_CAT_TIPOS_EMPLEADO.TIPOEMP_ID','=',
                                                               'JP_METADATO_PERSONAL.TIPOEMP_ID')
                            ->join('JP_CAT_CLASES_EMPLEADO'   ,'JP_CAT_CLASES_EMPLEADO.CLASEEMP_ID','=',
                                                               'JP_METADATO_PERSONAL.CLASEEMP_ID')
                            ->join('JP_IAPS'                  ,'JP_IAPS.IAP_ID' ,'=','JP_METADATO_PERSONAL.IAP_ID')
                            ->select('JP_METADATO_PERSONAL.FOLIO',
                                     'JP_IAPS.IAP_DESC'        ,  
                                     'JP_METADATO_PERSONAL.FECHA_INGRESO2', 
                                     'JP_METADATO_PERSONAL.PRIMER_APELLIDO',
                                     'JP_METADATO_PERSONAL.SEGUNDO_APELLIDO',
                                     'JP_METADATO_PERSONAL.NOMBRES',
                                     'JP_METADATO_PERSONAL.FECHA_NACIMIENTO2',     
                                     'JP_METADATO_PERSONAL.CURP',     
                                     'JP_METADATO_PERSONAL.SEXO',     
                                     'JP_METADATO_PERSONAL.DOMICILIO',     
                                     'JP_METADATO_PERSONAL.CP', 
                                     'JP_METADATO_PERSONAL.COLONIA',
                                     'JP_CAT_ENTIDADES_FED.ENTIDADFEDERATIVA_DESC',
                                     'JP_METADATO_PERSONAL.LOCALIDAD',   
                                     'JP_METADATO_PERSONAL.TELEFONO',                                                                                          
                                     'JP_METADATO_PERSONAL.PUESTO',
                                     'JP_CAT_GRADO_ESTUDIOS.GRADO_ESTUDIOS_DESC', 
                                     'JP_CAT_TIPOS_EMPLEADO.TIPOEMP_DESC', 
                                     'JP_CAT_CLASES_EMPLEADO.CLASEEMP_DESC', 
                                     'JP_METADATO_PERSONAL.OBS_1', 
                                     'JP_METADATO_PERSONAL.SUELDO_MENSUAL', 
                                     'JP_METADATO_PERSONAL.STATUS_1',
                                     'JP_METADATO_PERSONAL.FECHA_REG'
                                    )
                            ->where('JP_METADATO_PERSONAL.IAP_ID',$arbol_id)
                            ->orderBy('JP_METADATO_PERSONAL.NOMBRE_COMPLETO','ASC')
                            ->get();               
        }                            
    }
}
