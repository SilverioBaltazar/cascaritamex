<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class regTipoPregModel extends Model
{
    protected $table = "JP_CAT_TIPOPREG";
    protected  $primaryKey = 'SEC_ID';
    public $timestamps = false;
    public $incrementing = false;
    protected $fillable = [
	    'TIPOP_ID',
        'TIPOP_DESC',
        'TIPOP_STATUS',
        'FECREG'
    ];
}
