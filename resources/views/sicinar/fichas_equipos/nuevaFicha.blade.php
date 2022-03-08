@extends('sicinar.principal')

@section('title','Nueva OSC')

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
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <div class="content-wrapper">
        <section class="content-header">
            <h1><i class="fa fa-dashboard"></i>&nbsp;&nbsp;Menú.
                <small>1. Ficha del equipo</small>
                <small> - Nuevo</small>
            </h1>
            <ol class="breadcrumb">
                <li><img src="{{ asset('images/b1.jpg') }}" border="0" width="30" height="30">Ficha del equipo - Nuevo</li> 
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
                        {!! Form::open(['route' => 'altanuevaficha', 'method' => 'POST','id' => 'nuevaficha', 'enctype' => 'multipart/form-data']) !!}
                        <div class="box-body">
                            <div class="row">
                                <div class="col-xs-10 form-group">
                                    <label >Equipo de futbol </label>
                                    <input type="text" class="form-control" name="eq_desc" id="eq_desc" placeholder="Digitar nombre del equipo de futbol" required>
                                </div>
                            </div>


                            <div class="row">    
                                <div class="col-xs-3 form-group">
                                    <label >Apellido paterno del representante </label>
                                    <input type="text" class="form-control" name="eq_ap_rep" id="eq_ap_rep" placeholder="Apellido paterno del representante" required>
                                </div>  
                                <div class="col-xs-3 form-group">
                                    <label >Apellido materno </label>
                                    <input type="text" class="form-control" name="eq_am_rep" id="eq_am_rep" placeholder="Apellido materno del representante" required>
                                </div>  
                                <div class="col-xs-3 form-group">
                                    <label >Nombre(s) </label>
                                    <input type="text" class="form-control" name="eq_nombres_rep" id="eq_nombres_rep" placeholder="Nombre(s) del representante" required>
                                </div>  
                            </div>                               
                            <div class="row">                        
                                <div class="col-xs-4 form-group">
                                    <label >Teléfono </label>
                                    <input type="text" class="form-control" name="eq_tel_rep" id="eq_tel_rep" placeholder="Teléfono del representante" required>
                                </div>  
                                <div class="col-xs-4 form-group">
                                    <label >Correo electrónico </label>
                                    <input type="text" class="form-control" name="eq_email_rep" id="eq_email_rep" placeholder="Correo electrónico del representante" required>
                                </div>  
                            </div>                 


                            <div class="row">
                                <div class="col-xs-4 form-group">                        
                                    <label>Rama </label>
                                    <select class="form-control m-bot15" name="eq_rama" id="eq_rama" required>
                                        <option value="F">Femenil</option>
                                        <option value="V">Varonil</option>
                                    </select>
                                </div>                                                                                              
                                <div class="col-xs-4 form-group">
                                    <label >Categoria </label>
                                    <select class="form-control m-bot15" name="cate_id" id="cate_id" required>
                                        <option selected="true" disabled="disabled">Seleccionar categoria </option>
                                        @foreach($regcategoria as $cate)
                                            <option value="{{$cate->cate_id}}">{{$cate->cate_id.' '.Trim($cate->cate_desc)}}</option>
                                        @endforeach
                                    </select>                                    
                                </div>                                                                
                            </div>

                            <div class="row">                                                                                         
                                <div class="col-xs-4 form-group">
                                    <label >Municipio </label>
                                    <select class="form-control m-bot15" name="municipio_id" id="municipio_id" required>
                                        <option selected="true" disabled="disabled">Seleccionar municipio</option>
                                        @foreach($regmunicipio as $municipio)
                                            <option value="{{$municipio->municipioid}}">{{$municipio->municipio}}</option>
                                        @endforeach 
                                    </select>                                    
                                </div>
                            </div>     

                            <div class="row">
                                <div class="col-xs-12 form-group">
                                    <label style="background-color:yellow;color:red"><b>Nota importante:</b>&nbsp;&nbsp;El archivo digital, NO deberá ser mayor a 1,500 kBytes en tamaño.  </label>
                                </div>   
                            </div> 
                            <div class="row">
                                <div class="col-xs-4 form-group">
                                    <label >Archivo digital de Ficha del equipo </label>
                                    <input type="file" class="text-md-center" style="color:red" name="eq_arc1" id="eq_arc1" placeholder="Subir archivo digital de la Ficha del equipo" >
                                </div>  
                            </div>

                            <div class="row">
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
    {!! JsValidator::formRequest('App\Http\Requests\fichaRequest','#nuevaficha') !!}
@endsection

@section('javascrpt')
@endsection
