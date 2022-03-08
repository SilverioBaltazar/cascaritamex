<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class regProgdtrabModel extends Model
{
    protected $table      = "PE_PROGRAMA_DTRABAJO";
    protected $primaryKey = '[FOLIO, PARTIDA]';
    public $timestamps    = false;
    public $incrementing  = false;
    protected $fillable   = [
        'FOLIO',
        'PARTIDA',
        'PERIODO_ID',
        'OSC_ID',
        'DFECHA_ELAB',
        'DFECHA_ELAB2',
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
        'DOBS_1',
        'DOBS_2',
        'DSTATUS_1',
        'DSTATUS_2',
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

}
