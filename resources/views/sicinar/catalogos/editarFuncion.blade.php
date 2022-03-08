@extends('sicinar.principal')

@section('title','Editar Funcion')

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
            <h1>
                Menú
                <small> Catálogos - Funciones - Editar</small>
            </h1>
        </section>
        <section class="content">
            <div class="row">
                <div class="col-md-12">
                    <div class="box box-primary">
                        <div class="box-header">
                            <h3 class="box-title">Editar función </h3>
                        </div>
                        {!! Form::open(['route' => ['actualizarFuncion', $regfuncion->funcion_id], 'method' => 'PUT', 'id' => 'actualizarFuncion']) !!}
                        <div class="box-body">
                            <div class="row">
                                <div class="col-md-12 offset-md-12">
                                    <label>id. proceso: {{$regfuncion->proceso_id}}</label>
                                </div>
                                <div class="col-xs-3 form-group">
                                    <label><i class="fa fa-circle-o-notch"></i> Proceso </label>
                                    <select class="form-control m-bot15" name="proceso" id="proceso" required>
                                        @foreach($regproceso as $proceso)
                                            @if($proceso->proceso_id == $regfuncion->proceso_id)
                                                <option value="{{$proceso->proceso_id}}" selected>{{$proceso->proceso_desc}}</option>
                                            @else
                                                <option value="{{$proceso->proceso_id}}">{{$proceso->proceso_desc}}</option>
                                            @endif
                                        @endforeach
                                    </select><br>
                                </div>                                
                                <div class="col-md-12 offset-md-12">
                                    <label>id. función: {{$regfuncion->funcion_id}}</label>
                                </div>                                
                                <div class="col-xs-3 form-group">
                                    <label>* Nombre de la función del proceso </label>
                                    <input type="text" class="form-control" name="funcion_desc" placeholder="Nombre de la función del proceso" value="{{$regfuncion->funcion_desc}}" required>
                                </div>
                                <div class="col-xs-3 form-group">
                                    <label>* Activa o Inactiva </label>
                                    <select class="form-control m-bot15" name="funcion_status" required>
                                        @if($regfuncion->funcion_status == 'S')
                                            <option value="S" selected>SI</option>
                                            <option value="N">NO</option>
                                        @else
                                            <option value="S">SI</option>
                                            <option value="N" selected>NO</option>
                                        @endif
                                    </select>
                                </div>
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
                                    {!! Form::submit('Guardar',['class' => 'btn btn-success btn-flat pull-right']) !!}
                                    <a href="{{route('verFuncion')}}" role="button" id="cancelar" class="btn btn-danger">Cancelar</a>
                                </div>
                            </div><br>
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
    {!! JsValidator::formRequest('App\Http\Requests\catalogosfuncionRequest','#actualizarFuncion') !!}
@endsection

@section('javascrpt')
@endsection