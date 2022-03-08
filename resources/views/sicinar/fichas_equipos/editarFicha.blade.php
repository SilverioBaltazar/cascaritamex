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
                        {!! Form::open(['route' => ['actualizarficha',$regficha->eq_id], 'method' => 'PUT', 'id' => 'actualizarficha', 'enctype' => 'multipart/form-data']) !!}
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

                            <div class="row">    
                                <div class="col-xs-3 form-group">
                                    <label >Apellido paterno del representante </label>
                                    <input type="text" class="form-control" name="eq_ap_rep" id="eq_ap_rep" placeholder="Apellido paterno del representante" value="{{Trim($regficha->eq_ap_rep)}}" required>
                                </div>  
                                <div class="col-xs-3 form-group">
                                    <label >Apellido materno </label>
                                    <input type="text" class="form-control" name="eq_am_rep" id="eq_am_rep" placeholder="Apellido materno del representante" value="{{Trim($regficha->eq_am_rep)}}" required>
                                </div>  
                                <div class="col-xs-3 form-group">
                                    <label >Nombre(s) </label>
                                    <input type="text" class="form-control" name="eq_nombres_rep" id="eq_nombres_rep" placeholder="Nombre(s) del representante" value="{{Trim($regficha->eq_nombres_rep)}}" required>
                                </div>  
                            </div>                               
                            <div class="row">                        
                                <div class="col-xs-4 form-group">
                                    <label >Teléfono </label>
                                    <input type="text" class="form-control" name="eq_tel_rep" id="eq_tel_rep" placeholder="Teléfono del representante" value="{{Trim($regficha->eq_tel_rep)}}" required>
                                </div>  
                                <div class="col-xs-4 form-group">
                                    <label >Correo electrónico </label>
                                    <input type="text" class="form-control" name="eq_email_rep" id="eq_email_rep" placeholder="Correo electrónico del representante" value="{{Trim($regficha->eq_email_rep)}}" required>
                                </div>  
                            </div>                 

                            <div class="row">
                                <div class="col-xs-4 form-group">                        
                                    <label>Rama </label>
                                    <select class="form-control m-bot15" name="eq_rama" id="eq_rama" required>
                                        @if($regficha->eq_rama == 'F')
                                            <option value="F" selected>Femenil</option>
                                            <option value="V">         Varonil</option>
                                        @else
                                            <option value="F">         Femenil</option>
                                            <option value="V" selected>Varonil</option>
                                        @endif
                                    </select>
                                </div>                                                                                              
                                <div class="col-xs-4 form-group">
                                    <label >Categoria </label>
                                    <select class="form-control m-bot15" name="cate_id" id="cate_id" required>
                                        <option selected="true" disabled="disabled">Seleccionar categoria </option>
                                        @foreach($regcategoria as $cate)
                                            @if($cate->cate_id == $regficha->cate_id)
                                                <option value="{{$cate->cate_id}}" selected>{{$cate->cate_id.' '.Trim($cate->cate_desc)}}</option>
                                            @else                                        
                                               <option value="{{$cate->cate_id}}">{{$cate->cate_id.' '.Trim($cate->cate_desc)}} 
                                               </option>
                                            @endif
                                        @endforeach
                                    </select>                                    
                                </div>                                                                
                            </div>
                            <div class="row">
                                <div class="col-xs-4 form-group">
                                    <label >Municipio</label>
                                    <select class="form-control m-bot15" name="municipio_id" id="municipio_id" required>
                                        <option selected="true" disabled="disabled">Seleccionar municipio</option>
                                        @foreach($regmunicipio as $municipio)
                                            @if($municipio->municipioid == $regficha->municipio_id)
                                                <option value="{{$municipio->municipioid}}" selected>{{$municipio->municipio}}</option>
                                            @else 
                                               <option value="{{$municipio->municipioid}}">{{$municipio->municipio}}
                                               </option>
                                            @endif
                                        @endforeach
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
    {!! JsValidator::formRequest('App\Http\Requests\fichaRequest','#actualizarficha') !!}
@endsection

@section('javascrpt')
@endsection
