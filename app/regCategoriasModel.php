<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class regCategoriasModel extends Model
{ 
    protected $table      = "CASMEX_CAT_CATEGORIAS";
    protected $primaryKey = 'CATE_ID';
    public $timestamps    = false;
    public $incrementing  = false;
    protected $fillable   = [
        'CATE_ID',        
        'CATE_DESC',
        'CATE_STATUS', //S ACTIVO      N INACTIVO
        'CATE_FECREG'
    ];
}