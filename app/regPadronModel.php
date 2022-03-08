<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class regPadronModel extends Model
{
    protected $table      = "CASMEX_METADATO";
    protected $primaryKey = ['FOLIO','OSC_ID'];
    public $timestamps    = false;
    public $incrementing  = false;
    protected $fillable   = [
            'PERIODO_ID',
            'PROGRAMA_ID',
            'FOLIO',
            'EQ_ID',
            'FECHA_INGRESO',
            'FECHA_INGRESO2',
            'FECHA_INGRESO3',
            'PRIMER_APELLIDO',
            'SEGUNDO_APELLIDO',
            'NOMBRES',
            'NOMBRE_COMPLETO',
            'CURP',
            'FECHA_NACIMIENTO',
            'FECHA_NACIMIENTO2',
            'FECHA_NACIMIENTO3',
            'EDAD',
            'PERIODO_ID2', 
            'MES_ID2', 
            'DIA_ID2',
            'SEXO',
            'RFC',
            'TP_ID_OFICIAL',
            'ID_OFICIAL',
            'DOMICILIO',
            'NUM_EXT',
            'NUM_INT',
            'COLONIA',
            'CP',
            'ENTRE_CALLE',
            'Y_CALLE',
            'OTRA_REFERENCIA',
            'TELEFONO',
            'CELULAR',
            'E_MAIL',
            'ENTIDAD_NAC_ID',
            'ENTIDAD_FED_ID',
            'MUNICIPIO_ID',
            'LOCALIDAD_ID',
            'LOCALIDAD',
            'EDO_CIVIL_ID',
            'GRADO_ESTUDIOS_ID',
            'ARC_1',
            'ARC_2',
            'ARC_3',
            'ARC_4',
            'ARC_5',
            'ARC_6',
            'ARC_7',
            'ARC_8',
            'STATUS_1',
            'STATUS_2',
            'FECHA_REG',
            'FECHA_REG2',
            'IP',
            'LOGIN',
            'FECHA_M',
            'FECHA_M2',
            'IP_M',
            'LOGIN_M'
    ];

    public static function ObtpadronIap($id){
        return (regPadronModel::select('OSC_ID')->where('OSC_ID','=',$id)
                                ->get());
    }

    public static function obtMunicipios($id){
        return regPadronModel::select('ENTIDADFEDERATIVAID','MUNICIPIOID','MUNICIPIONOMBRE')
                               ->where('ENTIDADFEDERATIVAID','=', $id)
                               ->orderBy('MUNICIPIOID','asc')
                               ->get();
    }

    //***************************************//
    // *** Como se usa el query scope  ******// 
    //***************************************//
    public function scopeName($query, $name)
    {
        if($name)
            return $query->where('NOMBRE_COMPLETO', 'LIKE', "%$name%");
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
    public function scopeNameiap($query, $nameiap)
    {
        if($nameiap) 
            return $query->where('OSC_DESC', 'LIKE', "%$nameiap%");
    } 

    //***************************************//
    // *** Calcula la edad             ******// 
    //***************************************//
    public function calculaedad($ano, $mes, $dia)
    {
        //list($ano,$mes,$dia) = explode("-",$fechanacimiento);
        $ano_diferencia = date("Y") - $ano;
        $mes_diferencia = date("m") - $mes;
        $dia_diferencia = date("d") - $dia;
        if ($dia_diferencia < 0 || $mes_diferencia < 0)
            $ano_diferencia--;
        return $ano_diferencia;
    }

 
}