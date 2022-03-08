@extends('sicinar.principalauto')

@section('title','Editar Bienvenida')

@section('links')
    <link rel="stylesheet" href="{{ asset('bower_components/datatables.net-bs/css/dataTables.bootstrap.min.css') }}">
@endsection

@section('content')
    <meta http-equiv="Content-type" content="text/html; charset=utf-8" />
    <head>
        <style>
        @page { margin-top: 50px; margin-bottom: 100px; margin-left: 50px; margin-right: 50px; }
        body{color: #767676;background: #fff;font-family: 'Open Sans',sans-serif;}
        #header { position: fixed; left: 0px; top: -20px; right: 0px; height: 375px; }
        #content{ 
                  left:10px; top: 0px; margin-bottom: 0px; right:10px;
                  border: solid 0px #000;
                  font: 1em arial, helvetica, sans-serif;
                  color: black; text-align:center;vertical-align: middle; width:910px;} 
        #footer { position: fixed; left: 0px; bottom: -100px; right: 0px; height: 50px; text-align:right; font-size: 8px;}
        #footer .page:after { content: counter(page, upper-roman); }
        #content{ }   
        </style>
    </head>

    <body>
    </body>

    <div class="content-wrapper">
        <section class="content-header">
            <h1>
                AVISO DE PRIVACIDAD
            </h1>
        </section>
        <section class="content">
        <table class="table table-hover table-striped" align="center" width="100%"> 
            <tr>
                <td style="border:0; text-align:left;vertical-align: middle; align:justify;">
                    <p align="justify">
                    La Secretaría De Desarrollo Social del Estado de México, a traves del Instituto Mexiquense de la Juventud le da la más cordial bienvenida al 
                    Torneo Estatal de Fubtbol "CASCARITA MEXIQUENSE 2022".
                    <br> 
                    El Instituto Mexiquense de la Juventud, en lo sucesivo se denominará  
                    “IMEJ”, con domicilio en Calle Primavera S/N, entre Fco. I Madero e Invierno, Col. Los Álamos, Ecatepec 
                    de Morelos. Tel. (55) 57704126. Correo Electrónico: direcciongeneralimej@edomex.gob.mx; 
                    a través del Sistema de Registro al Torneo de Futbol "CASCARITA MEXIQUENSE 2022", es responsable del uso, tratamiento y 
                    protección de la información que proporcionen los Representantes de los equipos de Futbol, en lo sucesivo 
                    “EQUIPOS DE FUTBOL”, mediante su Inscripción al Torneo de Futbol "CASCARITA MEXIQUENSE 2022", con base en los requisitos 
                    y condiciones establecidos en la Convocatoria. Aplica a las organizaciones deportivas
                    de la sociedad civil que tengan el interés de estar inscritas. El “IMEJ” se compromete a mantener reserva respecto de la 
                    información que “LOS EQUIPOS DE FUTBOL” le compartan que tenga el carácter de reservada o confidencial, en términos de lo 
                    dispuesto por las leyes emitidas en materia de Transparencia y Acceso a la Información Pública y de la 
                    Ley de Protección de Datos Personales del Estado de México, salvo que el titular de los datos autorice su transmisión.
                    “LA DGBSFF””, manifiesta que no transmitirá datos personales a persona física o jurídico colectiva alguna, que sea 
                    ajena a la dependencia, sin su consentimiento expreso; notificándole previamente la identidad del destinatario, 
                    el fundamento, la finalidad y los datos personales a transmitir, así como, las implicaciones de su consentimiento a 
                    “LOS EQUIPOS DE FUTBOL”. El uso y tratamiento de la información es responsabilidad exclusiva del “IMEJ”, por lo que podrá 
                    utilizarla y tratarla de forma automatizada, para que formen parte de la base de datos para identificar, ubicar, 
                    comunicar, contactar, enviar información, actualizar nuestra base de datos y obtener estadísticas de los “EQUIPOS DE FUTBOL” participantes
                    en el torneo ya descrito.                     
                    </p>
                </td>
            </tr>
        </table>        
            <div class="row">
                <div class="col-md-12">
                    <div class="box box-success">

                        {!! Form::open(['route' => 'emailbienve', 'method' => 'PUT', 'id' => 'emailbienve', 'enctype' => 'multipart/form-data']) !!}
                        <div class="box-body">
                            <div class="row">
                                <div class="col-xs-12 form-group">
                                    <label style="color:orange;"><b>
                                    Es importante resguardar e imprimir está información de acceso al Sistema para el 
                                    proceso del trámite electrónico de Registro al Torneo Estatal de Futbol "CASCARITA MEXIQUENSE 2022".
                                    </b> 
                                    </label> 
                                </div>
                            </div>                             
                            <div class="row">
                                <div class="col-xs-12 form-group">
                                    <input type="hidden" id="eq_desc" name="eq_desc" value="{{$reguser->eq_desc}}">  
                                    <label style="color:green;">Equipo de Futbol: </label>
                                    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                    {{$reguser->eq_desc}}
                                </div> 
                                <div class="col-xs-12 form-group">
                                    <input type="hidden" id="replegal" name="replegal" value="{{$reguser->nombre_completo}}">  
                                    <label style="color:green;">Representante: </label>
                                    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                    {{$reguser->nombre_completo}} 
                                </div>                              
                                <div class="col-xs-12 form-group">
                                    <input type="hidden" id="tel" name="tel" value="{{$reguser->telefono}}">  
                                    <label style="color:green;">Teléfono de contacto :</label>
                                    &nbsp;&nbsp;{{$reguser->telefono}}
                                </div> 
                            </div>
                            <div class="row">
                                <div class="col-xs-12 form-group">
                                    <label style="color:orange;">Parámetros de acceso al sistema  </label> 
                                </div>
                            </div>
                            <div class="row">                                                                 
                                <div class="col-xs-6 form-group">
                                    <input type="hidden" id="folio" name="folio" value="{{$reguser->folio}}">                                  
                                    <label style="color:green;">Folio de sistema: </label>&nbsp;&nbsp;{{$reguser->folio}}                                                           
                                </div>                                 
                            </div>
                            <div class="row">                                                                                                 
                                <div class="col-xs-6 form-group">
                                    <input type="hidden" id="login" name="login" value="{{$reguser->login}}">  
                                    <label style="color:green;">Login: </label>
                                    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;{{$reguser->login}}
                                </div>  
                            </div>
                            <div class="row">                                                                                                 
                                <div class="col-xs-6 form-group">
                                    <input type="hidden" id="psw" name="psw" value="{{$reguser->password}}">  
                                    <label style="color:green;">Password: </label>
                                    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                    {{$reguser->password}}
                                </div>                                  
                            </div>

                            <div class="row">
                                @if(count($errors) > 0)
                                    <div class="alert alert-danger" role="alert">
                                        <ul>
                                            @foreach($errors->all() as $error)
                                                <li>{{ $error }}</li>
                                            @endforeach
                                        </ul>
                                    </div>
                                @endif
                                <div class="col-md-12 offset-md-5">
                                    {!! Form::submit('Continuar',['class' => 'btn btn-success btn-flat pull-right']) !!}
                                </div>
                            </div>
                        </div>
                        {!! Form::close() !!}
                    </div>
                </div>
            </div>
        </section>

    <footer id="footer">
        <table class="table table-hover table-striped" align="center" width="100%">
            <tr>
                <td style="border:0; text-align:right;">
                    <b>Secretaría de Desarrollo Social </b><br>Instituto Mexiquense de la Juventud (IMEJ)</td>
            </tr>
        </table>
    </footer>            
    </div>
@endsection

@section('request')
    <script src="//cdnjs.cloudflare.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>
    <script src="//cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/3.3.1/js/bootstrap.min.js"></script>
    <script type="text/javascript" src="{{ asset('vendor/jsvalidation/js/jsvalidation.js')}}"></script>
    {!! JsValidator::formRequest('App\Http\Requests\emailbienveRequest','#emailbienve') !!}
@endsection

@section('javascrpt')
@endsection
