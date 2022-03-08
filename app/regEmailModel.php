<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Mail;     //Importante incluir la clase Mail, que será la encargada del envío

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

//class regDemoemailModel extends Model
class regEmailModel extends Mailable
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
     * The demo object instance.
     * @var Demo
     */
    //public $demo;
    public $name;
    //public $email;
    public $phone;
    //public $menssage;
 
    /**
     * Create a new message instance.
     * @return void
     */
    //public function __construct($demo)
    //public function __construct($demo, $name,$email,$phone,$message)
    public function __construct($name,$phone)
    {
        //$this->demo = $demo;
        $this->$name = $name;
        //$this->$email = $email;
        $this->$phone = $phone;
        //$this->$message = $message;
    }

    // ver url https://visioncodigo.com/blog/como-crear-eventos-en-laravel
    public function handle(UserHasContacted $event)
    {
       Mail::to('nuestroEmail@gmail.com')->queue(
           new Contact($event->name, $event->email, $event->phone, $event->message)
       );
    }
 
    /**
     * Build the message.
     * @return $this
     */
    public function build2()
    {
        //return $this->from('desarrollosedesem.sbbz.1965@gmail.com')
        //            //->view('sicinar.mails.demo')
        //            //->text('sicinar.mails.demo_plain')
        //            ->with(
        //              [
        //                    'testVarOne' => '1',
        //                    'testVarTwo' => '2',
        //              ])
        //              ->attach(public_path('/images').'/excel.jpg', [
        //                      'as'   => 'excel.jpg',
        //                      'mime' => 'image/jpeg',
        //              ]);

        //return view('sicinar.requisitos.verReqc',compact('nombre','usuario','regosc','regperiodicidad','regnumeros', 'regperiodos','regcontable','regformatos'));

        //Mail::send('emails.template', $data, function ($message) use ($user){
        //$message->subject('Asunto del correo');
        //$message->to('destino@correo.com');
        //});         

        dd('en la clase .....');
           return $this->view("sicinar.mails.demo2")
                       ->from("desarrollosedesem.sbbz.1965@gmail.com")
                       ->subject("Bienvenido a mi sitio");             
    }    


    /**
     * Send HTML email
     */
    public function build()
    {
        $data = array('name'=>"Our Code World...........");
        // Ruta o nombre de la plantilla de hoja que se va a representar
        $template_path = 'sicinar.mails.email_template';

        Mail::send($template_path, $data, function($message) {
            // Configure el destinatario y el asunto del correo.
            //$message->to('anyemail@emails.com', 'Receiver Name')->subject('Laravel HTML Mail');
            $message->to('sbbz650620@hotmail.com', 'Receptor SILVERIO BALTAZAR')->subject('Laravel HTML Mail.......');
            // Establecer el remitente
            $message->from('desarrollosedesem.sbbz.1965@gmail.com','Our Code World');
        });

        return "Correo electrónico básico enviado, revise su bandeja de entrada.";
    }

}
