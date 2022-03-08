@extends('sicinar.principal')

@section('title','Editar OSC')

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
                <small> OSC - Editar</small>
            </h1>
        </section>
        <section class="content">
            <div class="row">
                <div class="col-md-12">
                    <div class="box box-success">

                        {!! Form::open(['route' => ['actualizarOsc1',$regosc->osc_id], 'method' => 'PUT', 'id' => 'actualizarOsc1', 'enctype' => 'multipart/form-data']) !!}
                        <div class="box-body">
                            <div class="row">
                                <div class="col-md-8 offset-md-12">
                                    <input type="text" class="form-control" name="osc_desc" placeholder="Nombre de la OSC" value="{{$regosc->osc_id.' - '.$regosc->osc_desc}}" required>
                                </div>                               
                            </div>

                            <div class="row">
                                <div class="col-xs-12 form-group">
                                    <label style="background-color:yellow;color:red"><b>Nota importante:</b> El archivo digital fotográfico (foto 1), NO deberán ser mayores a 1,500 kBytes en tamaño.  </label>
                                </div>   
                            </div>
                            <div class="row">
                                @if (!empty($regosc->osc_foto1)||!is_null($regosc->osc_foto1))  
                                    <div class="col-xs-4 form-group">
                                        <label >Fotografía 1 (jpg, jpeg, png)</label>
                                        <label ><a href="/images/{{$regosc->osc_foto1}}" class="btn btn-danger" title="Fotografía 1  jpg, jpeg, png"><i class="fa-file-image-o"></i> jpg, jpeg, png</a>
                                        </label>
                                    </div>   
                                    <div class="col-xs-4 form-group">
                                        <label >Actualizar archivo de Fotografía 1  jpg, jpeg, png</label>
                                        <input type="file" class="text-md-center" style="color:red" name="osc_foto1" id="osc_foto1" placeholder="Subir archivo de Fotografía 1  jpg, jpeg, png" >
                                    </div>      
                                @else     <!-- se captura archivo 1 -->
                                    <div class="col-xs-4 form-group">
                                        <label >Archivo de Fotografía 1 (jpg, jpeg, png)</label>
                                        <input type="file" class="text-md-center" style="color:red" name="osc_foto1" id="osc_foto1" placeholder="Subir archivo de Fotografía 1  jpg, jpeg, png" >
                                    </div>                                                
                                @endif 
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
                                    <a href="{{route('verOsc')}}" role="button" id="cancelar" class="btn btn-danger">Cancelar</a>
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
    {!! JsValidator::formRequest('App\Http\Requests\osc1Request','#actualizarOsc1') !!}
@endsection

@section('javascrpt')
@endsection