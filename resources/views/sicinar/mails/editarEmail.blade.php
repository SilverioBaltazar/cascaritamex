@extends('sicinar.principal')

@section('title','Editar Edo. financiero')

@section('links')
    <link rel="stylesheet" href="{{ asset('bower_components/datatables.net-bs/css/dataTables.bootstrap.min.css') }}">
@endsection

@section('nombre')
    {{$nombre}}
@endsection

@section('usuario')
    {{$usuario}}
@endsection

@section('content')
    <!DOCTYPE html>
    <html lang="es">
    <div class="content-wrapper">
        <section class="content-header">
            <!--<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />-->
            <meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1">
            <h1>
                Menú
                <small>email - Editar</small>
            </h1>
        </section>
        <section class="content">
            <div class="row">
                <div class="col-md-12">
                    <div class="box box-success">
 
                        {!! Form::open(['route' => ['Email',$regosc->osc_id], 'method' => 'PUT', 'id' => 'Email', 'enctype' => 'multipart/form-data']) !!}
                        <div class="box-body">

                            <div class="row">    
                                <div class="col-xs-12 form-group">
                                    <label style="color:green;">Id.: &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;{{$regosc->osc_id}}</label>                         
                                </div> 
                            </div>

                            <div class="row">
                                <div class="col-xs-12 form-group">
                                    <input type="hidden" name="osc_desc" id="osc_desc" value="{{$regosc->osc_desc}}">                                
                                    <label >OSC: </label> &nbsp;&nbsp; 
                                    {{Trim($regosc->osc_desc)}}
                                </div>         
                            </div>
                            <div class="row">                       
                                <div class="col-xs-4 form-group">
                                    <input type="hidden" name="osc_telefono" id="osc_telefono" value="{{$regosc->osc_telefono}}">
                                    <label >Teléfono: </label> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp; 
                                    {{Trim($regosc->osc_telefono)}}
                                </div>  
                            </div>
                            <div class="row">                                  
                                <div class="col-xs-4 form-group">
                                    <input type="hidden" name="osc_replegal" id="osc_replegal" value="{{$regosc->osc_replegal}}">
                                    <input type="hidden" name="osc_email"    id="osc_email"    value="{{$regosc->osc_email}}">
                                    <label >e-mail: </label> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; 
                                    {{Trim($regosc->osc_email)}}
                                </div>                                  
                            </div>

                            <div class="row">               
                                  <div class="col-xs-12 form-group">
                                    <label style="background-color:blue;color:white;text-align:center;vertical-align: middle;">&nbsp;&nbsp;&nbsp;Correo electrónico&nbsp;&nbsp;&nbsp;</label>
                                </div>                                                                      
                            </div>


                            <div class="row">  
                                <div class="col-xs-4 form-group">
                                    <label >Asunto </label>
                                    <input type="text" class="form-control" name="email_subject" id="email_subject" placeholder="Digitar el asunto del correo eletrónico" required>
                                </div>
                                <div class="col-xs-12 form-group">
                                    <label >Mensaje (2,000 carácteres)</label>
                                    <textarea class="form-control" name="email_msj" id="email_msj" rows="2" cols="120" placeholder="Mensaje (2,000 caracteres)" required></textarea>
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
                                    {!! Form::submit('Enviar email',['class' => 'btn btn-success btn-flat pull-right']) !!}
                                    <a href="{{route('vercontactos')}}" role="button" id="cancelar" class="btn btn-danger">Cancelar</a>
                                </div>
                            </div>
                        </div>
                        {!! Form::close() !!}
                    </div>
                </div>
            </div>
        </section>
    </div>
@endsection

@section('request')
    <script src="//cdnjs.cloudflare.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>
    <script src="//cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/3.3.1/js/bootstrap.min.js"></script>
    <script type="text/javascript" src="{{ asset('vendor/jsvalidation/js/jsvalidation.js')}}"></script>
    {!! JsValidator::formRequest('App\Http\Requests\emailRequest','#Email') !!}
@endsection

@section('javascrpt')
@endsection
