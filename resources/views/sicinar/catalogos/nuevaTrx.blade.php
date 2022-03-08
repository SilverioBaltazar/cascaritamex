@extends('sicinar.principal')

@section('title','Nueva Actividad')

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
                <small> Catálogos - Actividades - Nueva</small>                
            </h1>
        </section>
        <section class="content">
            <div class="row">
                <div class="col-md-12">
                    <div class="box box-success">

                        {!! Form::open(['route' => 'AltaNuevaTrx', 'method' => 'POST','id' => 'nuevaTrx']) !!}
                        <div class="box-body">
                            <div class="row">
                                <div class="col-xs-10 form-group">
                                    <div class="col-xs-12">
                                        <label >Actividad </label>
                                        <input type="text" class="form-control" name="trx_desc" id="trx_desc" placeholder="Digitar nombre de la actividad" required>
                                    </div>
                                </div>
                            </div>
                            <div class="row">                                
                                <div class="col-md-12 offset-md-5">
                                    {!! Form::submit('Registrar',['class' => 'btn btn-success btn-flat pull-right']) !!}
                                    <a href="{{route('verTrx')}}" role="button" id="cancelar" class="btn btn-danger">Cancelar</a>
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
    {!! JsValidator::formRequest('App\Http\Requests\catalogostrxRequest','#nuevaTrx') !!}
@endsection

@section('javascrpt')
@endsection