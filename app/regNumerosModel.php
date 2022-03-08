<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class regNumerosModel extends Model
{
    protected $table      = "PE_CAT_NUMEROS";
    protected $primaryKey = 'NUM_ID';
    public $timestamps    = false;
    public $incrementing  = false;
    protected $fillable   = [
        'NUM_ID',
        'NUM_DESC',
        'NUM_STATUS', //S ACTIVO      N INACTIVO
        'FECREG'
    ];
}