@extends('sicinar.principal')

@section('title','Editar documento')

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
            <h1>
                Menú
                <small> Catálogos - Documentos - Editar</small>
            </h1>
        </section>
        <section class="content">
            <div class="row">
                <div class="col-md-12">
                    <div class="box box-success">


                        {!! Form::open(['route' => ['actualizarDocto',$regdocto->doc_id], 'method' => 'PUT', 'id' => 'actualizarDocto', 'enctype' => 'multipart/form-data']) !!}
                        <div class="box-body">
                            <div class="row">          
                                <div class="col-xs-4 form-group">
                                    <label >Nombre del documento </label>
                                    <input type="text" class="form-control" name="doc_desc" placeholder="Documento" value="{{$regdocto->doc_desc}}" required>
                                </div>
                                <div class="col-xs-4 form-group">
                                    <label>Id.: {{$regdocto->doc_id}}</label>
                                </div>                                   
                            </div>


                            <div class="row">
                                <div class="col-xs-4 form-group">
                                    <label >Formato del documento </label>
                                    <select class="form-control m-bot15" name="formato_id" id="formato_id" required>
                                        <option selected="true" disabled="disabled">Seleccionar formato del documento</option>
                                        @foreach($regformato as $formato)
                                            @if($formato->formato_id == $regdocto->formato_id)
                                                <option value="{{$formato->formato_id}}" selected>{{$formato->formato_desc}}</option>
                                            @else                                        
                                               <option value="{{$formato->formato_id}}">{{$formato->formato_desc}} 
                                               </option>
                                            @endif
                                        @endforeach
                                    </select>                                    
                                </div>                                     
                                <div class="col-xs-4 form-group">
                                    <label >Periodo o frecuencia de entrega del formato </label>
                                    <select class="form-control m-bot15" name="per_id" id="per_id" required>
                                        <option selected="true" disabled="disabled">Seleccionar Periodo o frecuencia de entrega del formato</option>
                                        @foreach($regper as $per)
                                            @if($per->per_id == $regdocto->per_id)
                                                <option value="{{$per->per_id}}" selected>{{$per->per_desc}}</option>
                                            @else                                        
                                               <option value="{{$per->per_id}}">{{$per->per_desc}}</option>
                                            @endif
                                        @endforeach
                                    </select>                                    
                                </div>                                     
                            </div>

                            <div class="row">
                                <div class="col-xs-4 form-group">                        
                                    <label>Estado del documento </label>
                                    <select class="form-control m-bot15" name="doc_status" id="doc_status" required>
                                        @if($regdocto->doc_status == 'S')
                                            <option value="S" selected>Activo  </option>
                                            <option value="N">         Inactivo</option>
                                        @else
                                            <option value="S">         Activo  </option>
                                            <option value="N" selected>Inactivo</option>
                                        @endif
                                    </select>
                                </div>                                                                  
                            </div>

                            <div class="row">
                                <div class="col-xs-4 form-group">                        
                                    <label>Control del documento </label>
                                    <select class="form-control m-bot15" name="doc_status2" id="doc_status2" required>
                                        @if($regdocto->doc_status2 == 'S')
                                            <option value="S" selected>Documento de control interno    </option>
                                            <option value="N">         Documento externo solicitado a las OSC</option>
                                        @else
                                            <option value="S">         Documento de control interno  </option>
                                            <option value="N" selected>Documento externo solicitado a las OSC</option>
                                        @endif
                                    </select>
                                </div>                                                                  
                                <div class="col-xs-4 form-group">                        
                                    <label>Tipo de documento  </label>
                                    <select class="form-control m-bot15" name="doc_status3" id="doc_status3" required>
                                        @if($regdocto->doc_status3 == 'S')
                                            <option value="S" selected>Obligatorio </option>
                                            <option value="N">         Opcional    </option>
                                        @else
                                            <option value="S">         Obligatorio </option>
                                            <option value="N" selected>Opcional    </option>
                                        @endif
                                    </select>
                                </div>                                                                  
                            </div>                                                        

                            <div class="row">                                
                                <div class="col-xs-12 form-group">
                                    <label >Observaciones (1,000 caracteres)</label>
                                    <textarea class="form-control" name="doc_obs" id="doc_obs" rows="3" cols="100" placeholder="Observaciones" required>{{Trim($regdocto->doc_obs)}}
                                    </textarea>
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
    {!! JsValidator::formRequest('App\Http\Requests\doctoRequest','#actualizarDocto') !!}
@endsection

@section('javascrpt')

@endsection
