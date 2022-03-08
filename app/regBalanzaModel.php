<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class regBalanzaModel extends Model
{
    protected $table      = "PE_EDOS_FINANCIEROS";
    protected $primaryKey = 'EDOFINAN_FOLIO';
    public $timestamps    = false;
    public $incrementing  = false; 
    protected $fillable   = [
        'EDOFINAN_FOLIO',
        'PERIODO_ID',
        'DOC_ID',
        'FORMATO_ID',
        'PER_ID',
        'NUM_ID',
        'OSC_ID',
        'IDS_DREEF',
        'IDS_DREES',
        'IDS_CRECUP',
        'IDS_AGUB',
        'IDS_LDING',
        'EDS_CA',
        'EDS_GA',
        'EDS_CF',
        'REMAN_SEM',
        'ACT_CIRC',
        'ACT_NOCIRC',
        'ACT_NOCIRCINM',
        'PAS_ACP',
        'PAS_ALP',
        'PATRIMONIO',
        'EDOFINAN_FECHA',
        'EDOFINAN_FECHA2',
        'PERIODO_ID1',
        'MES_ID1',
        'DIA_ID1',
        'EDOFINAN_FOTO1',
        'EDOFINAN_FOTO2',
        'EDOFINAN_OBS',
        'EDOFINAN_STATUS',
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
