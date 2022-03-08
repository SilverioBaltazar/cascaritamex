@extends('sicinar.principal')

@section('title','Editar curso')

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
    <!DOCTYPE html>
    <html lang="es">
    <div class="content-wrapper">
        <section class="content-header">
            <!--<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />-->
            <meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1">
            <h1>
                Menú
                <small>4. Requisitos contables              </small>
                <small>4.1 Correo electrónico - Enviar</small>
            </h1>
        </section>
        <section class="content">
            <div class="row">
                <div class="col-md-12">
                    <div class="box box-success">

                        {!! Form::open(['route' => 'send', 'method' => 'POST','id' => 'send', 'enctype' => 'multipart/form-data']) !!}
                        <div class="box-body">                       

                            <div class="row">
                                <div class="col-xs-12 form-group" style="text-align:left; vertical-align: middle;color:green;">
                                    <label>Hello <i>{{ $nombre }}</i>,
                                        <p>This is a demo email for testing purposes! Also, it's the HTML version.</p>
                                    </label>
                                </div>                                             
                                <div class="col-xs-8 form-group" style="text-align:left; vertical-align: middle;color:green;">
                                    <label><p><u>Demo object values:</u></p></label>
                                </div>                            
                            </div>


                            <div class="row">
                                <div class="col-xs-8 form-group" style="text-align:left; vertical-align: middle;color:green;">
                                    <label><p><u>Values passed by With method:</u></p> </label>
                                </div>
                            </div>   

                            <div class="row">
                                <div class="col-xs-6 form-group">
                                    <label >Thank You, <br/>
                                    </label>
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
                                    {!! Form::submit('Enviar email',['class' => 'btn btn-success btn-flat pull-right']) !!}
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
@endsection

@section('javascrpt')
@endsection

