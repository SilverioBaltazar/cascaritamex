<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class regBitacoraModel extends Model
{
    protected $table      = "CASMEX_BITACORA";
    protected $primaryKey = ['PERIODO_ID','MES_ID','PROCESO_ID','FUNCION_ID','TRX_ID','FOLIO'];
    public $timestamps    = false;
    public $incrementing  = false;
    protected $fillable   = [
        'PERIODO_ID',
        'PROGRAMA_ID',
        'MES_ID',
        'PROCESO_ID',
        'FUNCION_ID',
        'TRX_ID',
        'FOLIO',        
        'NO_VECES',
        'FECHA_REG',
        'IP',
        'LOGIN',
        'FECHA_M',
        'IP_M'
    ];
}