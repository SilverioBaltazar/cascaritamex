<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class regFichaModel extends Model
{
    protected $table      = "CASMEX_FICHA_EQUIPO";
    protected $primaryKey = 'EQ_ID';
    public $timestamps    = false;
    public $incrementing  = false;
    protected $fillable   = [
        'EQ_ID', 
        'EQ_DESC', 
        'EQ_NOMBRES_REP', 
        'EQ_AP_REP', 
        'EQ_AM_REP', 
        'EQ_NOMBRECOMP_REP', 
        'EQ_CURP_REP',  
        'EQ_TEL_REP',  
        'EQ_EMAIL_REP', 
        'EQ_RAMA',  
        'EQ_ARC1',
        'EQ_ARC2',
        'EQ_STATUS1',         
        'EQ_STATUS2',                 
        'CATE_ID',  
        'MUNICIPIO_ID', 
        'REGION_ID',  
        'FEC_REG', 
        'FEC_REG2', 
        'IP', 
        'LOGIN', 
        'FECHA_M',
        'FECHA_M2',
        'IP_M', 
        'LOGIN_M'
    ];

    public static function ObtEq($id){
        return (regFichaModel::select('EQ_ID')->where('EQ_ID','=',$id)
                             ->get());
    }

    public static function obtCatMunicipios(){
        return regFichaModel::select('ENTIDADFEDERATIVAID','MUNICIPIOID','MUNICIPIONOMBRE')
                           ->where('ENTIDADFEDERATIVAID','=', 15)
                           ->orderBy('MUNICIPIOID','asc')
                           ->get();
    }

    public static function obtMunicipio($id){
        return regFichaModel::select('MUNICIPIOID','MUNICIPIONOMBRE')
                            ->where([ 
                                     ['ENTIDADFEDERATIVAID','=', 15], 
                                     ['MUNICIPIOID',        '=',$id]
                                    ])
                            ->get();
                            //->where('ENTIDADFEDERATIVAID','=', 15)
                            //->where('MUNICIPIOID',        '=',$id)
    }

    public static function obtCate(){
        return regFichaModel::select('CATE_ID','CATE_DESC')
                            ->orderBy('CATE_ID','asc')
                            ->get();
    }    

    
    //***************************************//
    // *** Como se usa el query scope  ******//
    //***************************************//
    public function scopeName($query, $name)
    {
        if($name) 
            return $query->where('EQ_DESC', 'LIKE', "%$name%");
    }
    public function scopeIdd($query, $idd)
    {
        if($idd)
            return $query->where('EQ_ID', '=', "$idd");
    }    

    public function scopeEmail($query, $email)
    {
        if($email)
            return $query->where('EQ_EMAIL_EQ', 'LIKE', "%$email%");
    }

    public function scopeBio($query, $bio)
    {
        if($bio)
            return $query->where('EQ_NOMBRECOMP_REP', 'LIKE', "%$bio%");
    } 


}