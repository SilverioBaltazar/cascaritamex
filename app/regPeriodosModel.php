<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class regPeriodosModel extends Model
{
    protected $table      = "CASMEX_CAT_PERIODOS";
    protected $primaryKey = 'PERIODO_ID';
    public $timestamps    = false;
    public $incrementing  = false;
    protected $fillable   = [
        'PERIODO_ID',
        'PERIODO_DESC',
        'PERIODO_FECREG'
    ];
}
