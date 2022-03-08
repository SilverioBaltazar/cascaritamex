<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class regTipoempModel extends Model
{
    protected $table      = "JP_CAT_TIPOS_EMPLEADO";
    protected $primaryKey = 'TIPOEMP_ID';
    public $timestamps    = false;
    public $incrementing  = false;
    protected $fillable   = [
        'TIPOEMP_ID',
		'TIPOEMP_DESC',
		'TIPOEMP_STATUS',
		'TIPOEMP_FECREG'
    ];
}
