<?php
namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use App\regMunicipioModel;

use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;

class ExcelExportCatMunicipios implements FromCollection, /*FromQuery,*/ WithHeadings
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function headings(): array
    {
        return [
            'ENTIDAD_ID',            
            'MPIO_ID',
            'MUNICIPIO',
            'REGION_ID',
            'COORD.'
        ];
    }

    public function collection()
    {
        return $regmunicipio = regMunicipioModel::select('ENTIDADFEDERATIVAID','MUNICIPIOID','MUNICIPIONOMBRE','REGIONID','CVE_COORDINACION')
            ->where('ENTIDADFEDERATIVAID',15)
            ->orderBy('MUNICIPIOID','asc')->get();                                
    }
}
