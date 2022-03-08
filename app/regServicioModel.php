<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class regServicioModel extends Model
{
    protected $table      = "PE_CAT_SERVICIOS";
    protected $primaryKey = 'SERVICIO_ID';
    public $timestamps    = false;
    public $incrementing  = false;
    protected $fillable   = [
        'SERVICIO_ID',
        'SERVICIO_DESC',
        'SERVICIO_STATUS',
        'RUBRO_ID',
        'FECREG'
    ];
}