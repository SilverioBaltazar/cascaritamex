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
            <h1><i class="fa fa-users"></i>
                Gestión de usuarios
            </h1>
            <ol class="breadcrumb">
                <li><a href="#"><i class="fa fa-dashboard"></i> Menú</a></li>
                <li><a href="{{route('verUsuarios')}}">Usuarios</a></li>
                <li class="active">Editar</li>
            </ol>
        </section>
        <section class="content">
            <div class="row">
                <div class="col-md-12">
                    <div class="box box-danger">
                        <div class="box-header with-border">
                            <h3 class="box-title"><b>Editar Usuario</b></h3>
                            <a href="{{route('verUsuarios')}}" class="btn btn-primary pull-right" title="Ver todos los usuarios"><i class="fa fa-users"> Ver Usuarios</i></a>
                        </div>
                        {!! Form::open(['route' => ['actualizarUsuario',$user->folio], 'method' => 'PUT', 'id' => 'altaUsuario']) !!}
                        <div class="box-body">
                            <div class="row">
                                <div class="col-xs-4 form-group">
                                    <label>Nombre(s)</label>
                                    <input type="text" class="form-control" name="nombre" id="nombre" placeholder="Nombre(s)" value="{{$user->nombres}}" required>
                                </div>
                                <div class="col-xs-4 form-group">
                                    <label>Apellido Paterno</label>
                                    <input type="text" class="form-control" name="paterno" id="paterno" placeholder="Apellido Paterno" value="{{$user->ap_paterno}}" required>
                                </div>
                                <div class="col-xs-4 form-group">
                                    <label>Apellido Materno</label>
                                    <input type="text" class="form-control" name="materno" id="materno" placeholder="Apellido Materno" value="{{$user->ap_materno}}" required>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-xs-4 form-group">
                                    <label>Correo Electrónico</label>
                                    <input type="text" class="form-control" name="usuario" id="usuario" placeholder="Usuario Correo Electrónico" value="{{$user->login}}" required>
                                </div>
                                <div class="col-xs-4 form-group">
                                    <label>Contraseña</label>
                                    <input type="text" class="form-control" name="password" id="password" placeholder="Contraseña" value="{{$user->password}}" required>
                                </div>
                                <div class="col-xs-4 form-group">                        
                                    <label>Activo o inactivo </label>
                                    <select class="form-control m-bot15" name="status_2" id="status_2" required>
                                        @if($user->status_2 == '1')
                                            <option value="1" selected>Si</option>
                                            <option value="0">         No</option>
                                        @else
                                            <option value="1">         Si</option>
                                            <option value="0" selected>No</option>
                                        @endif
                                    </select>
                                </div>                                                                  
                               
                            </div>

                            <div class="row">                            
                                <div class="col-xs-4 form-group">                        
                                    <label>Rol </label>
                                    <select class="form-control m-bot15" name="perfil" id="perfil" required>
                                        @if($user->status_1 == '4')
                                            <option value="0"         >OSC         </option>
                                            <option value="1"         >Operativo    </option>
                                            <option value="2"         >Particular   </option>
                                            <option value="3"         >Administrador</option>
                                            <option value="4" selected>Super Admon. </option>
                                        @else
                                            @if($user->status_1 == '3')
                                                <option value="0"         >OSC          </option>
                                                <option value="1"         >Operativo    </option>
                                                <option value="2"         >Particular   </option>
                                                <option value="3" selected>Administrador</option>
                                                <option value="4"         >Super Admon. </option>
                                            @else
                                                @if($user->status_1 == '2')
                                                    <option value="0"         >OSC          </option>
                                                    <option value="1"         >Operativo    </option>
                                                    <option value="2" selected>Particular   </option>
                                                    <option value="3"         >Administrador</option>
                                                    <option value="4"         >Super Admon. </option>
                                                @else
                                                    @if($user->status_1 == '1')
                                                        <option value="0"         >OSC          </option>
                                                        <option value="1" selected>Operativo    </option>
                                                        <option value="2"         >Particular   </option>
                                                        <option value="3"         >Administrador</option>
                                                        <option value="4"         >Super Admon. </option>
                                                    @else
                                                        <option value="0" selected>OSC          </option>
                                                        <option value="1"         >Operativo    </option>
                                                        <option value="2"         >Particular   </option>
                                                        <option value="3"         >Administrador</option>
                                                        <option value="4"         >Super Admon. </option>
                                                    @endif
                                                @endif
                                            @endif
                                        @endif
                                    </select>
                                </div>                                  
                                <div class="col-xs-4 form-group">
                                    <label >OSC </label>
                                    <select class="form-control m-bot15" name="osc_id" id="osc_id" required>
                                    <option selected="true" disabled="disabled">Seleccionar OSC </option>
                                    @foreach($regosc as $osc)
                                        @if($osc->osc_id == $user->cve_arbol)
                                            <option value="{{$osc->osc_id}}" selected>{{$osc->osc_desc}}</option>
                                        @else 
                                            <option value="{{$osc->osc_id}}">{{$osc->osc_desc}}</option>
                                        @endif                                    
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
                                {!! Form::submit('Actualizar',['class' => 'btn btn-danger btn-block']) !!}
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
    {!! JsValidator::formRequest('App\Http\Requests\altaUsuarioRequest','#actualizarUsuario') !!}
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
