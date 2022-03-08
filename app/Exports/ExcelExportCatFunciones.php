<?php
namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use App\regFuncionModel;

use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;

class ExcelExportCatFunciones implements FromCollection, /*FromQuery,*/ WithHeadings
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function headings(): array
    {
        return [
            'ID_PROCESO',
            'PROCESO',            
            'ID_FUNCION',
            'FUNCION',
            'ESTADO',
            'FECHA_REG'
        ];
    }

    public function collection()
    {
         return regfuncionModel::join('JP_CAT_PROCESOS','JP_CAT_PROCESOS.PROCESO_ID','=','JP_CAT_FUNCIONES.PROCESO_ID')
                            ->select('JP_CAT_FUNCIONES.PROCESO_ID','JP_CAT_PROCESOS.PROCESO_DESC','JP_CAT_FUNCIONES.FUNCION_ID','JP_CAT_FUNCIONES.FUNCION_DESC','JP_CAT_FUNCIONES.FUNCION_STATUS','JP_CAT_FUNCIONES.FUNCION_FECREG')
                            ->orderBy('JP_CAT_FUNCIONES.PROCESO_ID','ASC')
                            ->orderBy('JP_CAT_FUNCIONES.FUNCION_ID','ASC')
                            ->get();                               
    }
}
