<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class regDiasModel extends Model
{
    protected $table      = "CASMEX_CAT_DIAS";
    protected $primaryKey = 'DIA_ID';
    public $timestamps    = false;
    public $incrementing  = false;
    protected $fillable   = [
        'DIA_ID',
        'DIA_DESC',
        'DIA_STATUS',
        'FECREG'
    ];

    public static function obtDia($id){
        return regDiasModel::select('DIA_ID','DIA_DESC')
                           ->where('DIA_ID','=',$id )
                           ->get();
    }   

}