<?php

namespace App;
 
use Illuminate\Database\Eloquent\Model;

class regOscModel extends Model
{
    protected $table      = "PE_OSC";
    protected $primaryKey = 'OSC_ID';
    public $timestamps    = false;
    public $incrementing  = false;
    protected $fillable   = [
    'OSC_ID',
    'OSC_DESC',
    'OSC_CALLE',
    'OSC_NUM',
    'OSC_DOM1',
    'OSC_DOM2',
    'OSC_DOM3',
    'OSC_COLONIA',
    'MUNICIPIO_ID',
    'ENTIDADFEDERATIVA_ID',
    'RUBRO_ID',
    'OSC_REGCONS',
    'OSC_RFC',
    'OSC_CP',
    'OSC_FECCONS',
    'OSC_FECCONS2',
    'OSC_FECCONS3',
    'OSC_TELEFONO',
    'OSC_EMAIL',
    'OSC_SWEB',
    'OSC_PRES',
    'OSC_REPLEGAL',
    'OSC_SRIO',
    'OSC_TESORERO',
    'OSC_OBJSOC_1',
    'OSC_OBJSOC_2',
    'GRUPO_ID',
    'OSC_FECCERTIFIC',
    'OSC_FECCERTIFIC2',
    'OSC_OTRAREF',
    'OSC_OBS1',
    'OSC_OBS2',
    'ANIO_ID',
    'OSC_FVP',
    'OSC_FVP2',
    'OSC_FVP3',
    'OSC_FRPP',
    'INM_ID',
    'PERIODO_ID1',
    'MES_ID1',
    'DIA_ID1',
    'PERIODO_ID2',
    'MES_ID2',
    'DIA_ID2',
    'OSC_GEOREF_LATITUD',
    'OSC_GEOREF_LONGITUD',
    'OSC_FOTO1',
    'OSC_FOTO2',
    'OSC_FOTO3',
    'OSC_FOTO4',
    'OSC_FOTO5',
    'OSC_FOTO6',
    'FACEBOOK',
    'WHATSAPP',
    'GOOGLE',
    'YAHOO',
    'TWITTER',
    'INSTAGRAM',
    'PINTEREST',
    'LINKEDLN',
    'YOUTOBE',
    'OTRA_REDSOCIAL',
    'RED_SOCIAL',
    'OSC_STATUS',
    'OSC_FECREG',
    'OSC_FECREG3',
    'IP',
    'LOGIN',
    'FECHA_M',
    'FECHA_M3',
    'IP_M',
    'LOGIN_M'
    ];

    public static function ObtOsc($id){
        return (regOscModel::select('OSC_ID')->where('OSC_ID','=',$id)
                             ->get());
    }

    public static function obtCatMunicipios(){
        return regOscModel::select('ENTIDADFEDERATIVAID','MUNICIPIOID','MUNICIPIONOMBRE')
                           ->where('ENTIDADFEDERATIVAID','=', 15)
                           ->orderBy('MUNICIPIOID','asc')
                           ->get();
    }

    public static function obtMunicipio($id){
        return regOscModel::select('MUNICIPIOID','MUNICIPIONOMBRE')
                            ->where([ 
                                     ['ENTIDADFEDERATIVAID','=', 15], 
                                     ['MUNICIPIOID',        '=',$id]
                                    ])
                            ->get();
                            //->where('ENTIDADFEDERATIVAID','=', 15)
                            //->where('MUNICIPIOID',        '=',$id)
    }

    public static function obtCatRubros(){
        return regOscModel::select('RUBRO_ID','RUBRO_DESC')
                            ->orderBy('RUBRO_ID','asc')
                            ->get();
    }    

    public static function obtRubro($id){
        return regOscModel::select('RUBRO_ID','RUBRO_DESC')
                           ->where('RUBRO_ID','=',$id )
                           ->get();
    }    

    //***************************************//
    // *** Como se usa el query scope  ******//
    //***************************************//
    public function scopeName($query, $name)
    {
        if($name) 
            return $query->where('OSC_DESC', 'LIKE', "%$name%");
    }
    public function scopeIdd($query, $idd)
    {
        if($idd)
            return $query->where('OSC_ID', '=', "$idd");
    }    

    public function scopeEmail($query, $email)
    {
        if($email)
            return $query->where('OSC_EMAIL', 'LIKE', "%$email%");
    }

    public function scopeBio($query, $bio)
    {
        if($bio)
            return $query->where('OSC_OBJSOC', 'LIKE', "%$bio%");
    } 

}
