<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class regCursosModel extends Model
{
    protected $table      = "JP_IAPS_CURSOS";
    protected $primaryKey = ['PERIODO_ID','CURSO_ID'];
    public $timestamps    = false;
    public $incrementing  = false;
    protected $fillable   = [
        'CURSO_ID',
        'IAP_ID',
        'PERIODO_ID',
        'MES_ID',
        'CURSO_FINICIO',
        'CURSO_FINICIO2',
        'PERIODO_ID1',
        'MES_ID1',
        'DIA_ID1',                
        'CURSO_FFIN',
        'CURSO_FFIN2',
        'PERIODO_ID2',
        'MES_ID2',
        'DIA_ID2', 
        'CURSO_DESC',
        'CURSO_OBJ',
        'CURSO_PONENTES',        
        'CURSO_COSTO',
        'CURSO_THORAS',
        'CURSO_TDIAS',        
        'CURSO_OBS',
        'CURSO_STATUS',
        'FECREG',
        'IP',
        'LOGIN',
        'FECHA_M',
        'IP_M',
        'LOGIN_M'
    ];
}