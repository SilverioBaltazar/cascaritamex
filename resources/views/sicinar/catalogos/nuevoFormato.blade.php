@extends('sicinar.principal')

@section('title','Nuevo formato')

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
            <h1>Menú
                <small> Catálogos - Formato de archivo - Nuevo</small>                
            </h1>
        </section>
        <section class="content">
            <div class="row">
                <div class="col-md-12">
                    <div class="box box-success">
                        <div class="box-header"><h3 class="box-title">Registrar nuevo formato de archivo </h3></div>
                        {!! Form::open(['route' => 'AltaNuevoFormato', 'method' => 'POST','id' => 'nuevoFormato']) !!}
                        <div class="box-body">
                            <div class="row">
                                <div class="col-xs-5 form-group">
                                    <div class="col-xs-12">
                                        <label >Formato de archivo </label>
                                        <input type="text" class="form-control" id="formato_desc" name="formato_desc" placeholder="Digitar nombre del formato" onkeypress="return soloAlfa(event)" required>
                                    </div>
                                </div>
                                <div class="col-xs-5 form-group">
                                    <div class="col-xs-12">
                                        <label >Etiqueta </label>
                                        <input type="text" class="form-control" id="formato_etiq" name="formato_etiq" placeholder="Digitar etiqueta" onkeypress="return soloAlfa(event)" required>
                                    </div>
                                </div>
                            </div>    
                            <div class="row">
                                <div class="col-xs-5 form-group">
                                    <div class="col-xs-12">
                                        <label >Comando 1 </label>
                                        <input type="text" class="form-control" id="formato_comando1" name="formato_comando1" placeholder="Digitar comando 1" onkeypress="return soloAlfa(event)" required>
                                    </div>
                                </div>
                            </div>    
                            <div class="row">
                                <div class="col-xs-5 form-group">
                                    <div class="col-xs-12">
                                        <label >Comando 2 </label>
                                        <input type="text" class="form-control" id="formato_comando2" name="formato_comando2" placeholder="Digitar comando 2" onkeypress="return soloAlfa(event)" required>
                                    </div>
                                </div>
                            </div>           
                            <div class="row">   
                                <div class="col-xs-5 form-group">
                                    <div class="col-xs-12">
                                        <label >Comando 3 </label>
                                        <input type="text" class="form-control" id="formato_comando3" name="formato_comando3" placeholder="Digitar comando 3" onkeypress="return soloAlfa(event)" required>
                                    </div>
                                </div>
                            </div>           
                            <div class="row">
                                <div class="col-md-12 offset-md-5">
                                    {!! Form::submit('Dar de alta',['class' => 'btn btn-success btn-flat pull-right']) !!}
                                    <a href="{{route('verFormatos')}}" role="button" id="cancelar" class="btn btn-danger">Cancelar</a>
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
    {!! JsValidator::formRequest('App\Http\Requests\catformatoRequest','#nuevoFormato') !!}
@endsection

@section('javascrpt')
@endsection