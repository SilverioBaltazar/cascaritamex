<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class regCedulaModel extends Model
{
    protected $table      = "JP_CEDULA";
    protected $primaryKey = ['PERIODO','IAP_ID'];
    public $timestamps    = false;
    public $incrementing  = false;
    protected $fillable   = [
        'PERIODO_ID',
        'CEDULA_FOLIO',
        'IAP_ID',
        'SP_ID',
        'SP_NOMB',
        'CEDULA_FECHA',
        'CEDULA_FECHA2',
        'PERIODO_ID1',
        'MES_ID1',
        'DIA_ID1',
        'CEDULA_OBS',
        'CEDULA_STATUS',
        'FECREG',
        'IP',
        'LOGIN',
        'FECHA_M',
        'IP_M',
        'LOGIN_M'
    ];

    //***************************************//
    // *** Como se usa el query scope  ******//
    //***************************************//
    public function scopeFolio($query, $folio)
    {
        if($folio)
            return $query->where('CEDULA_FOLIO', '=', $folio);
    }

    public function scopeNameiap($query, $nameiap)
    {
        if($nameiap) 
            return $query->where('IAP_DESC', 'LIKE', "%$nameiap%");
    }      
    
}
