<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class regEstudiosModel extends Model
{
    protected $table      = "JP_CAT_GRADO_ESTUDIOS";
    protected $primaryKey = 'GRADO_ESTUDIOS_ID';
    public $timestamps    = false;
    public $incrementing  = false;
    protected $fillable   = [
        'GRADO_ESTUDIOS_ID',
        'GRADO_ESTUDIOS_DESC'
    ];
}