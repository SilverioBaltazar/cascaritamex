@extends('sicinar.principal')

@section('title','Editar Formato')

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
                <small> Catálogos - formato de archivo - Editar</small>
            </h1>
        </section>
        <section class="content">
            <div class="row">
                <div class="col-md-12">
                    <div class="box box-primary">
                        <div class="box-header">
                            <h3 class="box-title">Editar formato de archivo</h3>
                        </div>
                        {!! Form::open(['route' => ['actualizarFormato',$regformatos->formato_id], 'method' => 'PUT', 'id' => 'actualizarFormato']) !!}
                        <div class="box-body">
                            <div class="row">
                                <div class="col-md-12 offset-md-12">
                                    <label>id. : {{$regformatos->formato_id}}</label>
                                </div>
                            </div>    
                            <div class="row">                                
                                <div class="col-xs-3 form-group">
                                    <label>Formato de archivo </label>
                                    <input type="text" class="form-control" id="formato_desc" name="formato_desc" placeholder="Nombre del formato" value="{{$regformatos->formato_desc}}" required>
                                </div>
                                <div class="col-xs-3 form-group">
                                    <label>Etiqueta </label>
                                    <input type="text" class="form-control" id="formato_etiq" name="formato_etiq" placeholder="Etiqueta del formato" value="{{$regformatos->formato_etiq}}" required>
                                </div>
                            </div>    
                            <div class="row">
                                <div class="col-xs-3 form-group">
                                    <label>Comando 1 </label>
                                    <input type="text" class="form-control" id="formato_comando1" name="formato_comando1" placeholder="Comando 1" value="{{$regformatos->formato_comando1}}" required>
                                </div>
                                <div class="col-xs-3 form-group">
                                    <label>Comando 2 </label>
                                    <input type="text" class="form-control" id="formato_comando2" name="formato_comando2" placeholder="Comando 2" value="{{$regformatos->formato_comando2}}" required>
                                </div>
                            </div>    
                            <div class="row">    
                                <div class="col-xs-3 form-group">
                                    <label>Comando 3 </label>
                                    <input type="text" class="form-control" id="formato_comando3" name="formato_comando3" placeholder="Comando 3" value="{{$regformatos->formato_comando3}}" required>
                                </div>
                                                             
                                <div class="col-xs-3 form-group">
                                    <label>Activo o Inactivo </label>
                                    <select class="form-control m-bot15" name="formato_status" required>
                                        @if($regformatos->formato_status == 'S')
                                            <option value="S" selected>SI</option>
                                            <option value="N">NO</option>
                                        @else
                                            <option value="S">SI</option>
                                            <option value="N" selected>NO</option>
                                        @endif
                                    </select>
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
                                    {!! Form::submit('Guardar',['class' => 'btn btn-success btn-flat pull-right']) !!}
                                    <a href="{{route('verFormatos')}}" role="button" id="cancelar" class="btn btn-danger">Cancelar</a>
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
    {!! JsValidator::formRequest('App\Http\Requests\catformatoRequest','#actualizarFormato') !!}
@endsection

@section('javascrpt')
@endsection