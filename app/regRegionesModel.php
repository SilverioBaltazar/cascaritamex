<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class regRegionesModel extends Model
{
    protected $table      = "CASMEX_CAT_REGIONES";
    protected $primaryKey = 'REGION_ID';
    public $timestamps    = false;
    public $incrementing  = false;
    protected $fillable   = [
        'REGION_ID',
        'DESC_REGION',
        'ROMA_REGION',
        'REG_ORDEN'
    ];
}