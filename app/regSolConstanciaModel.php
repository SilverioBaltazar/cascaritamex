<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class regSolConstanciaModel extends Model
{
protected $table      = "PE_SOLICITUD_CONSTANCIA";
    protected $primaryKey = 'OSC_FOLIO';
    public $timestamps    = false;
    public $incrementing  = false;
    protected $fillable   = [
    'OSC_FOLIO',
    'OSC_ID',
    'PERIODO_ID',
    'DOC_ID1',
    'FORMATO_ID1',
    'OSC_D1',
    'PER_ID1',
    'NUM_ID1',
    'OSC_EDO1',
    'DOC_ID2',
    'FORMATO_ID2',
    'OSC_D2',
    'PER_ID2',
    'NUM_ID2',
    'OSC_EDO2',
    'DOC_ID3',
    'FORMATO_ID3',
    'OSC_D3',
    'PER_ID3',
    'NUM_ID3',
    'OSC_EDO3',
    'DOC_ID4',
    'FORMATO_ID4',
    'OSC_D4',
    'PER_ID4',
    'NUM_ID4',
    'OSC_EDO4',
    'DOC_ID5',
    'FORMATO_ID5',
    'OSC_D5',
    'PER_ID5',
    'NUM_ID5',
    'OSC_EDO5',
    'OSC_STATUS',
    'FECREG2',
    'FECREG',
    'IP',
    'LOGIN',
    'FECHA_M2',
    'FECHA_M',
    'IP_M',
    'LOGIN_M'     
    ];

    //***************************************//
    // *** Como se usa el query scope  ******//
    //***************************************// 
    public function scopefPer($query, $fper)
    {
        if($fper)
            return $query->orwhere('PERIODO_ID', '=', "$fper");
    }

    public function scopeIdd($query, $idd)
    {
        if($idd)
            return $query->orwhere('OSC_ID', '=', "$idd");
    }    
    public function scopeNameiap($query, $nameiap)
    {
        if($nameiap) 
            return $query->orwhere('OSC_DESC', 'LIKE', "%$nameiap%");
    } 
    
}
