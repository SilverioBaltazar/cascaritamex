<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class regCedulaarticulosModel extends Model
{
    protected $table      = "JP_CEDULA_ARTICULOS";
    protected $primaryKey = ['PERIODO','IAP_ID','ARTICULO_ID'];
    public $timestamps    = false;
    public $incrementing  = false;
    protected $fillable   = [
        'PERIODO_ID',
        'CEDULA_FOLIO',
        'CEDULA_PARTIDA',
        'IAP_ID',
        'CEDULA_FECHA',
        'CEDULA_FECHA2',
        'PERIODO_ID1',
        'MES_ID1',
        'DIA_ID1',
        'ARTICULO_ID',
        'ARTICULO_CANTIDAD',
        'CEDART_OBS',
        'CEDART_STATUS',
        'FECREG',
        'IP',
        'LOGIN',
        'FECHA_M',
        'IP_M',
        'LOGIN_M'
    ];

    
}