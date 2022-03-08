<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Mail;     //Importante incluir la clase Mail, que será la encargada del envío

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

//class regDemoemailModel extends Model
class regEmailBienveModel extends Mailable
{
    //protected $table      = "JP_CAT_ARTICULOS";
    //protected $primaryKey = 'ARTICULO_ID';
    //public $timestamps    = false;
    //public $incrementing  = false;
    //protected $fillable   = [
    //    'ARTICULO_ID',
    //    'ARTICULO_DESC',
    //    'ARTICULO_STATUS',
    //    'TIPO_ID',
    //    'FECREG',
    //    'IP',
    //    'LOGIN',
    //    'FECHA_M',
    //    'IP_M',
    //    'LOGIN_M'
    //];

    //***************************************//
    // *** Como se usa el query scope  ******//
    //***************************************//
    //public function scopeName($query, $name)
    //{
    //    if($name)
    //        return $query->where('ARTICULO_DESC', 'LIKE', "%$name%");
    //}    
    
    use Queueable, SerializesModels;
     
    /**
     * The object instance.
     * @var Demo
     */
    public $folio;  
    public $replegal;
    public $login;
    public $pws;
    public $osc;
    public $telefono;
 
    /**
     * Create a new message instance.
     * @return void
     */
    public function __construct($folio,$replegal,$login,$osc,$psw, $telefono)
    {
        $this->$folio    = $folio;
        $this->$replegal = $replegal;
        $this->$login    = $login;
        $this->$psw      = $psw;
        $this->$osc      = $osc;
        $this->$telefono = $telefono;
    }

 
}
