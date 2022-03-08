@extends('sicinar.principal')

@section('title','Editar curso')

@section('links')
    <link rel="stylesheet" href="{{ asset('bower_components/datatables.net-bs/css/dataTables.bootstrap.min.css') }}">
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

    
                        <div class="box-body">                       

                            <div class="row">
                                <div class="col-xs-12 form-group" style="text-align:left; vertical-align: middle;color:green;">
                                    <label>Hello <i></i>,
                                        <p>¡Bienvenido a mi sitio, {{$nombre}} !</p>
                                    </label>
                                </div>                                             
                            </div>

                            <div class="row">
                                <div class="col-xs-8 form-group" style="text-align:left; vertical-align: middle;color:green;">
                                    <label><p><u>Espero que te diviertas en mi sitio y que invites a tus amigos a visitarlo :)</u></p> </label>
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
                            </div>
                        </div>

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

