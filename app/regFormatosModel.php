<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class regFormatosModel extends Model
{
    protected $table      = "PE_CAT_FORMATOS";
    protected $primaryKey = 'FORMATO_ID';
    public $timestamps    = false;
    public $incrementing  = false;
    protected $fillable   = [
        'FORMATO_ID', 
        'FORMATO_DESC', 
        'FORMATO_ETIQ', 
        'FORMATO_COMANDO1', 
        'FORMATO_COMANDO2', 
        'FORMATO_COMANDO3', 
        'FORMATO_STATUS', 
        'FECREG'
    ];
}
