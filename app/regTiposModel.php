<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class regTiposModel extends Model
{
    protected $table      = "JP_CAT_TIPO_ARTICULO";
    protected $primaryKey = 'TIPO_ID';
    public $timestamps    = false;
    public $incrementing  = false;
    protected $fillable   = [
        'TIPO_ID',
        'TIPO_DESC',
        'TIPO_STATUS',
        'FECREG',
        'IP',
        'LOGIN',
        'FECHA_M',
        'IP_M',
        'LOGIN_M'
    ];

    
}
