<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class regInventarioModel extends Model
{
    protected $table      = "JP_INVENTARIO";
    protected $primaryKey = ['PERIODO_ID','IAP_ID','ACTIVO_ID']; 
    public $timestamps    = false;
    public $incrementing  = false;
    protected $fillable   = [
        'PERIODO_ID',
        'ID',
        'IAP_ID',
        'ACTIVO_ID',
        'ACTIVO_DESC',
        'INVENTARIO_FECADQ',
        'INVENTARIO_FECADQ2',
        'PERIODO_ID1',
        'MES_ID1',
        'DIA_ID1',
        'INVENTARIO_DOC',
        'ACTIVO_VALOR',
        'CONDICION_ID',
        'INVENTARIO_OBS',
        'INVENTARIO_STATUS',
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
    public function scopefPer($query, $fper)
    {
        if($fper)
            return $query->orwhere('PERIODO_ID', '=', "$fper");
    }

    public function scopeIdd($query, $idd)
    {
        if($idd)
            return $query->orwhere('IAP_ID', '=', "$idd");
    }    
    public function scopeNameiap($query, $nameiap)
    {
        if($nameiap) 
            return $query->orwhere('IAP_DESC', 'LIKE', "%$nameiap%");
    } 
 
}
