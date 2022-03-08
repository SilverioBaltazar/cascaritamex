<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class regDoctosModel extends Model
{
    protected $table      = "PE_CAT_DOCUMENTOS";
    protected $primaryKey = 'DOC_ID';
    public $timestamps    = false;
    public $incrementing  = false;
    protected $fillable   = [
        'DOC_ID', 
        'DOC_DESC', 
        'DOC_FILE', 
        'DOC_OBS', 
        'DEPENDENCIA_ID', 
        'FORMATO_ID', 
        'PER_ID', 
        'PER_FREC', 
        'RUBRO_ID',
        'DOC_STATUS', 
        'DOC_STATUS2', 
        'DOC_STATUS3', 
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
            return $query->where('DOC_DESC', 'LIKE', "%$name%");
    }    
    
}
