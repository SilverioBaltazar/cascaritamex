@extends('sicinar.principal')

@section('title','Nueva Funcion de proceso')

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
                <small> Catálogos - Funciones - Nueva</small>                
            </h1>
        </section>
        <section class="content">
            <div class="row">
                <div class="col-md-12">
                    <div class="box box-success">
                        <div class="box-header"><h3 class="box-title">Registrar nueva Función</h3></div>
                        {!! Form::open(['route' => 'AltaNuevaFuncion', 'method' => 'POST','proceso_id','funcion_id' => 'nuevaFuncion']) !!}
                        <div class="box-body">
                            <div class="row">
                                <div class="col-xs-3 form-group">
                                    <label>Proceso</label>
                                    <select class="form-control m-bot15" name="proceso_id" id="proceso_id" required>
                                        <option selected="true" disabled="disabled">Seleccionar proceso</option>
                                        @foreach($regproceso as $proceso)
                                            <option value="{{$proceso->proceso_id}}">{{$proceso->proceso_id}} - {{$proceso->proceso_desc}}
                                            </option>
                                        @endforeach
                                    </select><br>
                                </div>
                                <div class="col-xs-3 form-group">
                                    <label>Clave de la función</label>
                                    <input type="text" class="form-control" name="funcion_id" id="funcion_id" placeholder="Digitar clave de la función de proceso" required>
                                </div>
                                <div class="col-xs-4 form-group">
                                    <label >Nombre de la función de proceso </label>
                                    <input type="text" class="form-control" name="funcion_desc" placeholder="Digitar nombre de la función del proceso" onkeypress="return soloAlfa(event)" required>
                                </div>
                                <div class="col-md-12 offset-md-5">
                                    {!! Form::submit('Dar de alta',['class' => 'btn btn-success btn-flat pull-right']) !!}
                                    <a href="{{route('verFuncion')}}" role="button" id="cancelar" class="btn btn-danger">Cancelar</a>
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
    {!! JsValidator::formRequest('App\Http\Requests\catalogosfuncionRequest','#nuevaFuncion') !!}
@endsection

@section('javascrpt')
@endsection