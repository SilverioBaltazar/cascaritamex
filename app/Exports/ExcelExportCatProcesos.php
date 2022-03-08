<?php
namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use App\regProcesoModel;

use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;

class ExcelExportCatProcesos implements FromCollection, /*FromQuery,*/ WithHeadings
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function headings(): array
    {
        return [
            'ID',
            'NOMBRE_PROCESO',
            'ESTADO',
            'FECHA_REG'
        ];
    }

    public function collection()
    {
        return $regproceso = regProcesoModel::select('PROCESO_ID','PROCESO_DESC', 'PROCESO_STATUS','PROCESO_FECREG')
            ->orderBy('PROCESO_ID','asc')->get();                                
    }
}
