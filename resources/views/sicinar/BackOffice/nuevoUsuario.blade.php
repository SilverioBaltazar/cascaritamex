@extends('sicinar.principal')

@section('title','Gestión de Usuarios')

@section('nombre')
    {{$nombre}}
@endsection

@section('usuario')
    {{$usuario}}
@endsection

@section('content')
    <div class="content-wrapper" id="principal">
        <section class="content-header">
            <h1><i class="fa fa-users"></i>Gestión de Usuarios </h1>
            <ol class="breadcrumb">
                <li><a href="#"><i class="fa fa-dashboard"></i>Menú</a></li>
                <li><a href="{{route('verUsuarios')}}">Usuarios</a></li>
                <li class="active">Nuevo</li>
            </ol>
        </section>
        <section class="content">
            <div class="row">
                <div class="col-md-12">
                    <div class="box box-danger">
                        <div class="box-header with-border">
                            <h3 class="box-title"><b>Nuevo Usuario</b></h3>
                                <a href="{{route('verUsuarios')}}" class="btn btn-primary pull-right" title="Ver todos los usuarios"><i class="fa fa-users"> Ver Usuarios</i></a>
                        </div>
                        {!! Form::open(['route' => 'altaUsuario', 'method' => 'POST', 'id' => 'altaUsuario']) !!}
                        <div class="box-body">
                            <div class="row">
                                <div class="col-xs-4 form-group">
                                    <input type="text" class="form-control" name="nombre" id="nombre" placeholder="Nombre(s)" required>
                                </div>
                                <div class="col-xs-4 form-group">
                                    <input type="text" class="form-control" name="paterno" id="paterno" placeholder="Apellido Paterno" required>
                                </div>
                                <div class="col-xs-4 form-group">
                                    <input type="text" class="form-control" name="materno" id="materno" placeholder="Apellido Materno" required>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-xs-4 form-group">
                                    <input type="text" class="form-control" name="usuario" id="usuario" placeholder="Usuario" required>
                                </div>
                                <div class="col-xs-4 form-group">
                                    <input type="text" class="form-control" name="password" id="password" placeholder="Contraseña" required>
                                </div>
                                <div class="col-xs-4 form-group">
                                    <select class="form-control m-bot15" name="perfil" id="perfil" required>
                                        <option selected="true" disabled="disabled">Selecciona un Rol</option>
                                        <option value="0">OSC       </option>
                                        <option value="1">Operativo  </option>
                                        <!--<option value="2">Particular</option>-->
                                        <option value="3">Administrador</option>
                                        <option value="4">Super Administrador</option>
                                    </select>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-xs-4 form-group">
                                    <select class="form-control m-bot15" name="osc_id" id="osc_id" required>
                                        <option selected="true" disabled="disabled">Seleccionar osc</option>
                                        @foreach($regosc as $osc)
                                            <option value="{{$osc->osc_id}}">{{$osc->osc_desc.' '.$osc->osc_id}}</option>
                                        @endforeach
                                    </select>
                                </div>                                
                            </div>

                            @if(count($errors) > 0)
                                <div class="alert alert-danger" role="alert">
                                    <ul>
                                        @foreach($errors->all() as $error)
                                            <li><i class="fa fa-warning"></i> {{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif
                            <br>
                            <div class="col-md-12 offset-md-5">
                                {!! Form::submit('Registrar',['class' => 'btn btn-danger btn-block']) !!}
                            </div>
                        </div>
                    </div>
                    {!! Form::close() !!}
                </div>
            </div>
        </section>
    </div>
@endsection

@section('request')
    <script src="//cdnjs.cloudflare.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>
    <script src="//cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/3.3.1/js/bootstrap.min.js"></script>
    <script type="text/javascript" src="{{ asset('vendor/jsvalidation/js/jsvalidation.js')}}"></script>
    {!! JsValidator::formRequest('App\Http\Requests\altaUsuarioRequest','#altaUsuario') !!}
@endsection

@section('javascrpt')
    <script type="text/javascript">
        var unidad = document.getElementById('unidad');
        function dis(elemento) {
            t = elemento.value;
            if(t == "1"){
                unidad.disabled = false;
            }else{
                unidad.disabled = true;
            }
        }
    </script>
@endsection
