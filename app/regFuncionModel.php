<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class regFuncionModel extends Model
{
    protected $table      = "CASMEX_CAT_FUNCIONES";
    protected $primaryKey = 'FUNCION_ID';
    public $timestamps    = false;
    public $incrementing  = false;
    protected $fillable   = [
        'PROCESO_ID',
        'FUNCION_ID',        
        'FUNCION_DESC',
        'FUNCION_STATUS', //S ACTIVO      N INACTIVO
        'FUNCION_FECREG'
    ];
}