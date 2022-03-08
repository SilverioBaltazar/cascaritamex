@extends('sicinar.principal')

@section('title','Editar ficha del equipo de futbol')

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
            <h1><i class="fa fa-dashboard"></i>&nbsp;&nbsp;Menú.
                <small>1. Ficha del equipo</small>
                <small> - Editar</small>
            </h1>
            <ol class="breadcrumb">
                <li><img src="{{ asset('images/b1.jpg') }}" border="0" width="30" height="30">Ficha del equipo - Editar</li> 
            </ol>
        </section>
        <section class="content">
            <div class="row">
                <div class="col-md-12">
                    <div class="box box-success">
                        <p align="justify">
                        <b style="color:red;">Instrucciones:</b> 
                        <b style="color:green;">Inicia el registro capturando los siguientes datos:<br>
                        Del equipo: • Nombre • Rama: Varonil o femenil • Categoría: Mayor (de 18 a 29 años) o menor (de 14 a 17 años).<br>
                        Del representante de equipo: • Nombre completo • Teléfono (Celular y/o local) • Correo electrónico.</b>
                        </p>                
                        {!! Form::open(['route' => ['actualizarficha1',$regficha->eq_id], 'method' => 'PUT', 'id' => 'actualizarficha1', 'enctype' => 'multipart/form-data']) !!}
                        <div class="box-body">
                            <div class="row">
                                <div class="col-xs-10 form-group">
                                    <input type="hidden" id="eq_desc" name="eq_desc" value="{{$regficha->eq_desc}}">  
                                    <label style="color:green;">Equipo de Futbol: &nbsp;&nbsp;{{Trim($regficha->eq_desc)}}</label>
                                </div>                                      
                                <div class="col-xs-2 form-group">
                                    <input type="hidden" id="eq_id" name="eq_id" value="{{$regficha->eq_id}}">  
                                    <label style="color:green;">Folio: &nbsp;&nbsp; {{$regficha->eq_id}} </label>                         
                                </div> 
                            </div>

                            @if (!empty($regficha->eq_arc1)||!is_null($regficha->eq_arc1))   
                                <div class="row">
                                    <div class="col-xs-6 form-group">
                                        <label >Archivo digital de Ficha del equipo de futbol en formato PDF</label><br>
                                        <label ><a href="/storage/{{$regficha->eq_arc1}}" class="btn btn-danger" title="Archivo digital de Ficha del equipo de futbol en formato PDF"><i class="fa fa-file-pdf-o"></i>PDF
                                        </a>&nbsp;&nbsp;&nbsp;{{$regficha->eq_arc1}}
                                        </label>
                                        <input type="hidden" name="doc_id15" id="doc_id15" value="19">
                                    </div>   
                                    <div class="col-xs-6 form-group">                                        
                                        <label>
                                        <iframe width="400" height="300" src="{{ asset('storage/'.$regficha->eq_arc1)}}" frameborder="0"></iframe> 
                                        </label>                                       
                                    </div>                                        
                                </div>
                                <div class="row">
                                    <div class="col-xs-6 form-group">
                                        <label >Actualizar archivo digital Ficha del equipo de futbol en formato PDF</label>
                                        <input type="file" class="text-md-center" style="color:red" name="eq_arc1" id="eq_arc1" placeholder="Subir archivo de digital de Ficha del equipo de futbol en formato PDF" >
                                    </div>      
                                </div>
                            @else     <!-- se captura archivo 1 -->
                                <div class="row">                            
                                    <div class="col-xs-6 form-group">
                                        <label >Archivo digital de Ficha del equipo de futbol en formato PDF</label>
                                        <input type="file" class="text-md-center" style="color:red" name="eq_arc1" id="eq_arc1" placeholder="Subir archivo digital de Ficha del equipo de futbol en formato PDF" >
                                        <input type="hidden" name="doc_id15" id="doc_id15" value="19">
                                    </div>                                                
                                </div>
                            @endif  


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
                                    {!! Form::submit('Registrar',['class' => 'btn btn-success btn-flat pull-right']) !!}
                                    <a href="{{route('verficha')}}" role="button" id="cancelar" class="btn btn-danger">Cancelar</a>
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
    {!! JsValidator::formRequest('App\Http\Requests\ficha1Request','#actualizarficha1') !!}
@endsection

@section('javascrpt')
@endsection
