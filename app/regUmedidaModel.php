<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class regUmedidaModel extends Model
{
    protected $table      = "PE_CAT_UNID_MEDIDA";
    protected $primaryKey = 'UMEDIDA_ID';
    public $timestamps    = false;
    public $incrementing  = false;
    protected $fillable   = [
        'UMEDIDA_ID',
        'UMEDIDA_DESC',
        'UMEDIDA_STATUS',
        'FECREG'
    ];
}