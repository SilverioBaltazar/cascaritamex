@extends('sicinar.principal')

@section('title','Nueva Documento')

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
    <meta charset="utf-8">
    <div class="content-wrapper">
        <section class="content-header">
            <h1>Menú
                <small> Documentos - Nuevo </small>                
            </h1>
        </section>
        <section class="content">
            <div class="row">
                <div class="col-md-12">
                    <div class="box box-success">
                        
                        {!! Form::open(['route' => 'AltaNuevoDocto', 'method' => 'POST','id' => 'nuevoDocto', 'enctype' => 'multipart/form-data']) !!}
                        <div class="box-body">
                            <div class="row">
                                <div class="col-xs-4 form-group">
                                    <label >Nombre del documento </label>
                                    <input type="text" class="form-control" name="doc_desc" id="doc_desc" placeholder="Digitar nombre del documento" required>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-xs-4 form-group">
                                    <label >Formato del documento </label>
                                    <select class="form-control m-bot15" name="formato_id" id="formato_id" required>
                                        <option selected="true" disabled="disabled">Seleccionar formato del documento</option>
                                        @foreach($regformato as $formato)
                                            <option value="{{$formato->formato_id}}">{{$formato->formato_desc}}
                                            </option>
                                        @endforeach
                                    </select>                                    
                                </div>                                                                                     

                                <div class="col-xs-4 form-group">
                                    <label >Periodo o frecuencia de entrega del formato</label>
                                    <select class="form-control m-bot15" name="per_id" id="per_id" required>
                                        <option selected="true" disabled="disabled">Seleccionar periodo o frecuencia de entrega del formato</option>
                                        @foreach($regper as $per)
                                            <option value="{{$per->per_id}}">{{$per->per_desc}}
                                            </option>
                                        @endforeach
                                    </select>                                  
                                </div>                                                                    
                            </div>

                            <div class="row">
                                <div class="col-xs-4 form-group">
                                    <label >Control del documento</label>
                                    <select class="form-control m-bot15" name="doc_status2" id="doc_status2" required>
                                        <option selected="true" disabled="disabled">Seleccionar control</option>
                                        <option value="S">Documento de control interno      </option>
                                        <option value="N">Documento externo solicitado a las OSC </option>
                                    </select>
                                </div>
                                <div class="col-xs-4 form-group">
                                    <label >Tipo de documento</label>
                                    <select class="form-control m-bot15" name="doc_status3" id="doc_status3" required>
                                        <option selected="true" disabled="disabled">Seleccionar tipo de documento</option>
                                        <option value="S">Obligatorio </option>
                                        <option value="N">Opcional    </option>
                                    </select>
                                </div>                                
                            </div>
                            <div class="row">
                                <div class="col-xs-12 form-group">
                                    <label >Observaciones (1,000 caracteres)</label>
                                    <textarea class="form-control" name="doc_obs" id="doc_obs" value="" rows="3" cols="100" placeholder="Observaciones" required>
                                    </textarea>
                                </div>                                
                            </div>

                            <div class="row">
                                <div class="col-xs-4 form-group">
                                    <label >Archivo del documento </label>
                                    <input type="file" class="text-md-center" style="color:red" name="doc_file" id="doc_file" placeholder="Subir archivo del documento " >
                                </div>                                                          


                            <div class="row">
                                <div class="col-md-12 offset-md-5">
                                    {!! Form::submit('Dar de alta',['class' => 'btn btn-success btn-flat pull-right']) !!}
                                    <a href="{{route('verDoctos')}}" role="button" id="cancelar" class="btn btn-danger">Cancelar</a>
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
    {!! JsValidator::formRequest('App\Http\Requests\doctoRequest','#nuevoDocto') !!}
@endsection

@section('javascrpt')

@endsection
