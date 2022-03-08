<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class regHorasModel extends Model
{
    protected $table      = "PE_CAT_HORAS";
    protected $primaryKey = 'HORA_ID';
    public $timestamps    = false;
    public $incrementing  = false;
    protected $fillable   = [
        'HORA_ID',
        'HORA_DESC',
        'HORA_STATUS',
        'FECREG'
    ];
}