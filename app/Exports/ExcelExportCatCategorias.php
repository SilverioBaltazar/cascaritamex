<?php
namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use App\regCategoriasModel;

use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;

class ExcelExportCatCategorias implements FromCollection, /*FromQuery,*/ WithHeadings
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function headings(): array
    {
        return [
            'ID',
            'CATEGORIA',
            'ESTADO',
            'FECHA_REG'
        ];
    }

    public function collection()
    {
        return $regcategoria = regCategoriasModel::select('CATE_ID','CATE_DESC','CATE_STATUS','CATE_FECREG')
                               ->orderBy('CATE_ID','desc')
                               ->get();                                
    }
}
