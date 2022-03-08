<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class regPerModel extends Model
{
    protected $table      = "PE_CAT_PERIODICIDAD";
    protected $primaryKey = 'PER_ID';
    public $timestamps    = false;
    public $incrementing  = false;
    protected $fillable   = [
        'PER_ID',
        'PER_DESC',
        'PER_FREC',
        'PER_STATUS', //S ACTIVO      N INACTIVO
        'FECREG'
    ];

    public static function ObtFrec($id){
        return (regPerModel::select('PER_FREC')->where('PER_ID','=',$id)
                             ->get());
    }
}