<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

//use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

//class regAgendaModel extends Model  
class claseMailModel extends Model
//class claseMailModel extends Mailable
//https://www.tutofox.com/laravel/tutorial-laravel-mail-enviar-un-mensaje-al-correo/
{
    //use Queueable, SerializesModels;
    use SerializesModels;

    public $data;

    /**
     * Create a new message instance.
     *
     * @return void
     */

    public function __construct($data)
    {
        $this->data = $data;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        //return $this->view('mensaje');
        return $this->view('sicinar.mails.send_email');
    }

}