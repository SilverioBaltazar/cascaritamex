@extends('sicinar.principal')

@section('title','Registro de requisitos juridicos')

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
    <div class="content-wrapper">
        <section class="content-header">
            <h1><i class="fa fa-dashboard"></i>&nbsp;&nbsp;Menú.
                <small>2. Requisitos jurídicos    </small>
                <small> - Nuevo </small>
            </h1>
            <ol class="breadcrumb">
                <li><img src="{{ asset('images/b2.jpg') }}" border="0" width="30" height="30">Requisitos Jurídicos - nuevo</li> 
            </ol>        
        </section>
        <section class="content">
            <div class="row">
                <div class="col-md-12">
                    <div class="box box-success">
                        <div class="box-header">
                            <h3 class="box-title"><p align="justify"><b style="color:red;">
                            Instrucciones:</b> <b style="color:green;">
                            Los requisitos jurídicos-normativos son obligatorios y se deberán subir al sistema por única vez y actualizar cuando haya algún cambio de estos.
                            Se requiere escanear a una resolución de 300 ppp en blanco y negro.
                            </b></p>
                            </h3>
                        </div>                    
                        {!! Form::open(['route' => 'AltaNuevaJur', 'method' => 'POST','id' => 'nuevaJur', 'enctype' => 'multipart/form-data']) !!}

                        <div class="box-body">
                            <div class="row">                                
                                <div class="col-xs-8 form-group">
                                    <label >OSC </label>
                                    <select class="form-control m-bot15" name="osc_id" id="osc_id" required>
                                        <option selected="true" disabled="disabled">Seleccionar OSC </option>
                                        @foreach($regosc as $osc)
                                            <option value="{{$osc->osc_id}}">{{$osc->osc_id.' '.$osc->osc_desc}}</option>
                                        @endforeach
                                    </select>                                    
                                </div>   
                            </div>     

                            <div class="row">
                                <div class="col-xs-12 form-group">
                                    <label style="background-color:yellow;color:red"><b>Nota importante:</b> Los archivos digitales, NO deberán ser mayores a 1,500 kBytes en tamaño.  </label>
                                </div>   
                            </div>
                            <div class="row">               
                                <div class="col-xs-6 form-group">
                                    <label >Archivo digital de Acta Constitutiva en formato PDF </label>
                                    <input type="hidden" name="doc_id12" id="doc_id12" value="17">
                                    <input type="file" class="text-md-center" style="color:red" name="osc_d12" id="osc_d12" placeholder="Subir archivo de Acta constitutiva en formato PDF">
                                </div>  
                            </div>

                            <div class="row">               
                                <div class="col-xs-6 form-group">
                                    <label >Archivo digital de Registro en el IFREM en formato PDF </label>
                                    <input type="hidden" name="doc_id13" id="doc_id13" value="18">
                                    <input type="file" class="text-md-center" style="color:red" name="osc_d13" id="osc_d13" placeholder="Subir archivo de Documento de registro en el IFREM en formato PDF">
                                </div>   
                            </div>             

                            <div class="row">               
                                <div class="col-xs-6 form-group">
                                    <label >Archivo digital de Curriculum en formato PDF </label>
                                    <input type="hidden" name="doc_id14" id="doc_id14" value="9">
                                    <input type="file" class="text-md-center" style="color:red" name="osc_d14" id="osc_d14" placeholder="Subir archivo de Documento de Curriculum en formato PDF">
                                </div>   
                            </div> 

                            <div class="row">               
                                <div class="col-xs-6 form-group">
                                    <label >Archivo digital de Última protocolización en formato PDF </label>
                                    <input type="hidden" name="doc_id15" id="doc_id15" value="19">
                                    <input type="file" class="text-md-center" style="color:red" name="osc_d15" id="osc_d15" placeholder="Subir archivo de documento de ultima protocolización en formato PDF">
                                </div>   
                            </div> 
 
                            <div class="row">
                                <div class="col-md-12 offset-md-5">
                                    {!! Form::submit('Registrar requisitos jurídicos ',['class' => 'btn btn-success btn-flat pull-right']) !!}
                                    <a href="{{route('verJur')}}" role="button" id="cancelar" class="btn btn-danger">Cancelar</a>
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
    {!! JsValidator::formRequest('App\Http\Requests\reqjuridicoRequest','#nuevaJur') !!} 
@endsection

@section('javascrpt')
<script>
  function soloAlfa(e){
       key = e.keyCode || e.which;
       tecla = String.fromCharCode(key);
       letras = "abcdefghijklmnñopqrstuvwxyz ABCDEFGHIJKLMNÑOPQRSTUVWXYZ.";
       especiales = "8-37-39-46";

       tecla_especial = false
       for(var i in especiales){
            if(key == especiales[i]){
                tecla_especial = true;
                break;
            }
        }
        if(letras.indexOf(tecla)==-1 && !tecla_especial){
            return false;
        }
    }

    function general(e){
       key = e.keyCode || e.which;
       tecla = String.fromCharCode(key);
       letras = "abcdefghijklmnñopqrstuvwxyz ABCDEFGHIJKLMNÑOPQRSTUVWXYZ1234567890,.;:-_<>!%()=?¡¿/*+";
       especiales = "8-37-39-46";

       tecla_especial = false
       for(var i in especiales){
            if(key == especiales[i]){
                tecla_especial = true;
                break;
            }
        }
        if(letras.indexOf(tecla)==-1 && !tecla_especial){
            return false;
        }
    }
</script>
@endsection