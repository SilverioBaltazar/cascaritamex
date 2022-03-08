<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class regCondicionModel extends Model
{
    protected $table      = "JP_CAT_CONDICIONES";
    protected $primaryKey = 'CONDICION_ID';
    public $timestamps    = false;
    public $incrementing  = false;
    protected $fillable   = [
        'CONDICION_ID',
        'CONDICION_DESC',
        'CONDICION_STATUS',
        'CONDICION_FECREG'
    ];
}