<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class regArticulosModel extends Model
{
    protected $table      = "JP_CAT_ARTICULOS";
    protected $primaryKey = 'ARTICULO_ID';
    public $timestamps    = false;
    public $incrementing  = false;
    protected $fillable   = [
        'ARTICULO_ID',
        'ARTICULO_DESC',
        'ARTICULO_STATUS',
        'TIPO_ID',
        'FECREG',
        'IP',
        'LOGIN',
        'FECHA_M',
        'IP_M',
        'LOGIN_M'
    ];

    //***************************************//
    // *** Como se usa el query scope  ******//
    //***************************************//
    public function scopeName($query, $name)
    {
        if($name)
            return $query->where('ARTICULO_DESC', 'LIKE', "%$name%");
    }    
    
}
