<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class dependenciasModel extends Model
{
    protected $table = "JP_CAT_DEPENDENCIAS";
    protected  $primaryKey = 'DEPEN_ID';
    public $timestamps = false;
    public $incrementing = false;
    protected $fillable = [
	    'DEPEN_ID', 
	    'DEPEN_DESC',
	    'ESTRUCGOB_ID',
        'CLASIFICGOB_ID'
    ];

    public static function Unidades($id){
        return dependenciasModel::select('DEPEN_ID','DEPEN_DESC')
                                  ->where('DEPEN_ID','like','%211C04%')
        						  ->where('ESTRUCGOB_ID','like','%'.$id.'%')
                                  ->orderBy('DEPEN_ID','asc')
                                  ->get();
    }
}
