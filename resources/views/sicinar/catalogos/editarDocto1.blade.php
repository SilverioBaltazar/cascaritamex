@extends('sicinar.principal')

@section('title','Editar formato')

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
                <small> Catálogos - Documentos - Editar</small>
            </h1>
        </section>
        <section class="content">
            <div class="row">
                <div class="col-md-12">
                    <div class="box box-success">

                        {!! Form::open(['route' => ['actualizarDocto1',$regdocto->doc_id], 'method' => 'PUT', 'id' => 'actualizarDocto1', 'enctype' => 'multipart/form-data']) !!}
                        <div class="box-body">
                            <div class="row">
                                <div class="col-xs-4 offset-md-12">
                                    <label>Id.: {{$regdocto->doc_id}}</label>
                                </div>             
                                <div class="col-xs-12 form-group">
                                    <label >Documento: {{$regdocto->doc_desc}} </label>
                                </div>   
                            </div>

                            <div class="row">
                                @if (!empty($regdocto->doc_file)||!is_null($regdocto->doc_file)) 
                                    @switch($regdocto->formato_id)
                                    @case(1) 
                                        <div class="col-xs-4 form-group">
                                            <label >Archivo de documento en formato Excel</label>
                                            <label ><a href="/images/{{$regdocto->doc_file}}" class="btn btn-danger" title="Archivo de documento en formato Excel"><i class="fa-file-excel-o"></i>Excel</a>
                                            </label>
                                        </div>   
                                        <div class="col-xs-4 form-group">
                                            <label >Actualizar archivo de documento en formato Excel</label>
                                            <input type="file" class="text-md-center" style="color:red" name="doc_file" id="doc_file" placeholder="Subir archivo de documento en formato Excel" >
                                        </div>                                            
                                        @break
                                    @case(2) 
                                        <div class="col-xs-4 form-group">
                                            <label >Archivo de documento en formato PDF</label>
                                            <label ><a href="/images/{{$regdocto->doc_file}}" class="btn btn-danger" title="Archivo de documento en formato PDF"><i class="fa-file-pdf-o"></i>PDF</a>
                                            </label>
                                        </div>   
                                        <div class="col-xs-4 form-group">
                                            <label >Actualizar archivo de documento en formato PDF</label>
                                            <input type="file" class="text-md-center" style="color:red" name="doc_file" id="doc_file" placeholder="Subir archivo de documento en formato PDF" >
                                        </div>                                        
                                        @break
                                    @case(3) 
                                        <div class="col-xs-4 form-group">
                                            <label >Archivo de documento en formato (jpg, jpeg, png)</label>
                                            <label ><a href="/images/{{$regdocto->doc_file}}" class="btn btn-danger" title="Archivo de fotografía jpg, jpeg, png"><i class="fa-file-image-o"></i> jpg, jpeg, png</a>
                                            </label>
                                        </div>   
                                        <div class="col-xs-4 form-group">
                                            <label >Actualizar archivo de documento en formato (jpg, jpeg, png)</label>
                                            <input type="file" class="text-md-center" style="color:red" name="doc_file" id="doc_file" placeholder="Subir archivo de fotografía en formato jpg, jpeg, png" >
                                        </div>    
                                        @break                                        
                                    @case(5)  
                                        <div class="col-xs-4 form-group">
                                            <label >Archivo de documento en formato powerpoint (ppt)</label>
                                            <label ><a href="/images/{{$regdocto->doc_file}}" class="btn btn-danger" title="Archivo de documento en formto powerpoint"><i class="fa fa-file-powerpoint-o"></i>Ppt</a>
                                            </label>
                                        </div>   
                                        <div class="col-xs-4 form-group">
                                            <label >Actualizar archivo de documento en formato powerpoint (ppt)</label>
                                            <input type="file" class="text-md-center" style="color:red" name="doc_file" id="doc_file" placeholder="Subir archivo de documento en formato powerpoint" >
                                        </div>
                                        @break               
                                    @endswitch  
                                @else     <!-- se captura archivo 1 -->
                                    <div class="col-xs-4 form-group">
                                        <label >Archivo de documento</label>
                                        <input type="file" class="text-md-center" style="color:red" name="doc_file" id="doc_file" placeholder="Subir archivo del documento" >
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
    {!! JsValidator::formRequest('App\Http\Requests\docto1Request','#actualizarDocto1') !!}
@endsection

@section('javascrpt')
@endsection