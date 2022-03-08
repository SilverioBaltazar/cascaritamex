<?php
namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use App\regTrxModel;

use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;

class ExcelExportCatTrx implements FromCollection, /*FromQuery,*/ WithHeadings
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function headings(): array
    {
        return [
            'ID',
            'ACTIVIDAD',
            'ESTADO',
            'FECHA_REG'
        ];
    }

    public function collection()
    {
        return $regtrx = regTrxModel::select('TRX_ID','TRX_DESC', 'TRX_STATUS','TRX_FECREG')
            ->orderBy('TRX_ID','asc')->get();                                
    }
}
