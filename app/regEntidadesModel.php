<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class regEntidadesModel extends Model
{
    protected $table      = "CASMEX_CAT_ENTIDADES_FED";
    protected $primaryKey = 'ENTIDADFED_ID';
    public $timestamps    = false;
    public $incrementing  = false;
    protected $fillable   = [
        'ENTIDADFED_ID',
        'ENTIDADFED_DESC',
        'ENTIDADFED_SIGLAS'
    ];
}