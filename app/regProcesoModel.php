<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class regProcesoModel extends Model
{
    protected $table      = "CASMEX_CAT_PROCESOS";
    protected $primaryKey = 'PROCESO_ID';
    public $timestamps    = false;
    public $incrementing  = false;
    protected $fillable   = [
        'PROCESO_ID',
        'PROCESO_DESC',
        'PROCESO_STATUS', //S ACTIVO      N INACTIVO
        'PROCESO_FECREG'
    ];
}