<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class regProgTrabModel extends Model
{
    protected $table      = "PE_PROGRAMA_ETRABAJO";
    protected $primaryKey = '[PERIODO_ID, OSC_ID]';
    public $timestamps    = false;
    public $incrementing  = false;
    protected $fillable   = [
        'FOLIO',
        'PERIODO_ID',
        'OSC_ID',
        'FECHA_ELAB',
        'FECHA_ELAB2',
        'PERIODO_ID1',
        'MES_ID1',
        'DIA_ID1',
        'PROGRAMA_ID',
        'PROGRAMA_DESC',
        'ACTIVIDAD_ID',
        'ACTIVIDAD_DESC',
        'OBJETIVO_ID',
        'OBJETIVO_DESC',
        'UMEDIDA_ID',
        'MESP_01',
        'MESP_02',
        'MESP_03',
        'MESP_04',
        'MESP_05',
        'MESP_06',
        'MESP_07',
        'MESP_08',
        'MESP_09',
        'MESP_10',
        'MESP_11',
        'MESP_12',
        'MESC_01',
        'MESC_02',
        'MESC_03',
        'MESC_04',
        'MESC_05',
        'MESC_06',
        'MESC_07',
        'MESC_08',
        'MESC_09',
        'MESC_10',
        'MESC_11',
        'MESC_12',
        'RESPONSABLE',
        'ELABORO',
        'AUTORIZO',
        'OBS_1',
        'OBS_2',
        'STATUS_1',
        'STATUS_2',
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
    public function scopeFolio($query, $folio)
    {
        if($folio)
            return $query->where('FOLIO', '=', $folio);
    }

    public function scopeName($query, $name)
    {
        if($name)
            return $query->where('PROGRAMA_DESC', 'LIKE', "%$name%");
    }

    public function scopeActi($query, $acti)
    {
        if($acti)
            return $query->where('ACTIVIDAD_DESC', 'LIKE', "%$acti%");
    }

    public function scopeBio($query, $bio)
    {
        if($bio)
            return $query->where('OBJETIVO_DESC', 'LIKE', "%$bio%");
    }  
    public function scopeNameiap($query, $nameiap)
    {
        if($nameiap) 
            return $query->where('OSC_DESC', 'LIKE', "%$nameiap%");
    }      
       
}
