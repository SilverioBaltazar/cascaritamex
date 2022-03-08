<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
//use Illuminate\Support\Str;
//use Illuminate\Support\Collection;
use App\Http\Requests\emailbienveRequest;

use Illuminate\Support\Facades\Mail;
//use Mail;     //Importante incluir la clase Mail, que será la encargada del envío

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

use App\regEmailModel;
use App\regEmailBienveModel;

class mailController extends Controller
{

    public function actionEmail(){
        $nombre       = session()->get('userlog');
        $pass         = session()->get('passlog');
        //if($nombre == NULL AND $pass == NULL){
        //    return view('sicinar.login.expirada');
        //}
        $usuario      = session()->get('usuario');
        $rango        = session()->get('rango');
        $ip           = session()->get('ip');
        $arbol_id     = session()->get('arbol_id');        


        $destinatario = "sbbz650620@hotmail.com";
        $nombre       = "Luis Cabrera Benito";
        $telefono     = "+52 7226101217";
        //dd('antes.....');
        $correo = new regEmailModel($nombre, $telefono);
        //dd($correo);
        //return view('sicinar.mails.email_template',compact('nombre','destinatario','correo'));
        // Armar correo y pasarle datos para el constructor
        # ¡Enviarlo!
        //dd('email_template ok');

        $data = array('name'=>"Our Code World", 'phone'=>"Celular +52 7226101217" );
        // Ruta o nombre de la plantilla de hoja que se va a representar
        $template_email = 'sicinar.mails.email_template';
        //dd('despues .....'.$template_email);
        Mail::send($template_email, $data, function($message) {
            // Configure el destinatario y el asunto del correo.
            //$message->to('anyemail@emails.com', 'Receiver Name')->subject('Laravel HTML Mail');
            $message->to('sbbz650620@hotmail.com', 'Representante y/o gestor de la OSC');
            $message->subject('!Notificación¡');
            // Establecer el remitente
            $message->from('desarrollo.6570@gmail.com','SECRETARÍA DE DESARROLLO SOCIAL, DIRECCIÓN GENERAL DE BIENESTAR SOCIAL Y FORTALECIMIENTO FAMILIAR');
            $message->attach(public_path('/images').'/Gobierno.png', 
            	            [
                              'as'   => 'Gobierno.png',
                              'mime' => 'image/png',
                            ]);
        });
        //dd('Termine.....');
     
        //$datos =array(
        //         "osc_replegal"=>$request->osc_replegal,
        //         "osc_desc"    =>$request->osc_desc,
        //         "osc_email"   =>$request->osc_email,
        //         //"osc_telefono"=>$request->osc_telefono,
        //         "subject"     =>$request->email_subject,
        //         "msj"         =>$request->email_msj,
        //        );        

        //$subject       = $request->email_subject;
        //$msj           = $request->email_msj;
        //$osc_email     = "sbbz650620@hotmail.com";  //$request->osc_email;
        //$email_template= 'sicinar.mails.email_template';
        ////dd('ok:'.$datos);
        //Mail::send($email_template,$datos, function($msj) use($subject,$osc_email){
        //      $msj->from("desarrollo.6570@gmail.com","Coordinación de Vinculación, Secretaría de Desarrollo Social");
        //      $msj->subject($subject);
        //      $msj->to($osc_email);
        //      //$msj->attach(public_path('/images').'/excel.jpg', [
        //      //                'as'   => 'excel.jpg',
        //      //                'mime' => 'image/jpeg',
        //      //        ]);
        //});        
        //dd('termine de mandar el email....:');
        // Mail::send('folder.view', $data, function($message) {
   		//$message->to('registered-user@gmail. com', 'Jon Doe')
   		//        ->subject('Welcome to the Laravel 4 Auth App!');
        //});

        //toastr()->success('Correo electrónico enviado.',' Ok',['positionClass' => 'toast-bottom-right']);
        //return "Correo electrónico básico enviado, revise su bandeja de entrada.";
		//return redirect()->route('verContactos');
		return back()->with('success', 'Enviado exitosamente!');
    }
 
    //public function actionEmail(emailRequest $request, $id){
    public function actionEmailBienve(emailbienveRequest $request){
        $nombre       = session()->get('userlog');
        $pass         = session()->get('passlog');
        //if($nombre == NULL AND $pass == NULL){
        //    return view('sicinar.login.expirada');
        //}
        $usuario      = session()->get('usuario');
        $rango        = session()->get('rango');
        $ip           = session()->get('ip');
        $arbol_id     = session()->get('arbol_id');        


        $folio    = (string)$request->folio;
        $replegal = $request->replegal;
        $eq_desc  = $request->eq_desc;
        $login    = $request->login;
        $psw      = $request->psw;
        $tel      = (string)$request->tel;
        //$destinatario = (string)$login;
        //$destinatario = "sbbz650620@hotmail.com";        

        //dd('Folio..'.$folio,'rep...'.$replegal,'Login...'.$login,'osc...'.$osc,'pasw...'.$psw,'tel...'.$tel);
        $correo   = new regEmailBienveModel($folio,$replegal,$login,$eq_desc,$psw, $tel);
        //$data = array('name'=>"Our Code World", 'phone'=>"Celular +52 7226101217" );
        $data     = [];
        $data     = array('folio'=>$folio, 'replegal'=>$replegal,'login'=>$login,'eq_desc'=>$eq_desc, 'psw'=>$psw,'tel'=>$tel); 
        //dd('data .....',$data);        

        // Ruta o nombre de la plantilla de hoja que se va a representar
        $template_email = 'sicinar.mails.email_bienve'; 
        Mail::send($template_email, $data, function($message) use ($data) {
            // Configure el destinatario y el asunto del correo.
            $message->to( $data['login'] , 'Representante del equipo de futbol');
            //$message->to( 'sbbz650620@hotmail.com', 'Representante y/o gestor de la OSC');
            $message->subject('!Notificación de autoregistro al Sistema de Registro al Torneo Estatal de Futbol (CASCARITA MEXIQUENSE 2022)¡');

            // Establecer el remitente
            $message->from('desarrollo.6570@gmail.com','SECRETARÍA DE DESARROLLO SOCIAL, INSTITUTO MEXIQUENSE DE LA JUVENTUD (IMEJ)');
            $message->attach(public_path('/images').'/Instructivo_de_Registro_al_torneo_en_Linea.pdf', 
                            [
                              'as'   => 'Instructivo_de_Registro_al_torneo_en_Linea.pdf',
                              'mime' => 'application/pd',
                            ]);
        });
        //return back()->with('success', 'Enviado exitosamente!');
        return view('sicinar.login.loginInicio');
    }

}
