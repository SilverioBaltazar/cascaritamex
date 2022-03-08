<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class regSeccionesModel extends Model
{
    protected $table = "JP_CAT_SECCIONES";
    protected  $primaryKey = 'SEC_ID';
    public $timestamps = false;
    public $incrementing = false;
    protected $fillable = [
	    'SEC_ID',
        'SEC_DESC',
        'SEC_STATUS',
        'FECREG'
    ];
}
