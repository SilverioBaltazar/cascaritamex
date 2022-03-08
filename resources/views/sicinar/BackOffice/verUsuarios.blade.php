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
            <h1><i class="fa fa-users"></i>&nbsp;&nbsp;&nbsp;BackOffice - Usuarios
            <small> Seleccionar alguno para editar o nuevo usuario</small>
            </h1>
            <ol class="breadcrumb">
                <li><a href="#"><i class="fa fa-dashboard"></i> Menú</a></li>
                <li><a href="#">BackOffice </a></li>
                <li class="active">Usuarios</li>
            </ol>
        </section>
        <section class="content">
            <div class="row">
                <div class="col-md-12">
                    <div class="box box-danger">

                        <div class="page-header" style="text-align:right;">                            
                            {{ Form::open(['route' => 'buscarUsuario', 'method' => 'GET', 'class' => 'form-inline pull-right']) }}
                                <div class="form-group">
                                    {{ Form::text('idd', null, ['class' => 'form-control', 'placeholder' => 'Id. Equipo']) }}
                                </div>                                                             
                                <div class="form-group">
                                    {{ Form::text('name', null, ['class' => 'form-control', 'placeholder' => 'Nombre usuario']) }}
                                </div>
                                <div class="form-group">
                                    {{ Form::text('login', null, ['class' => 'form-control', 'placeholder' => 'Login']) }}
                                </div>
                                <div class="form-group">
                                    <button type="submit" class="btn btn-default">
                                        <span class="glyphicon glyphicon-search"></span>
                                    </button>
                                </div>
                                <div class="form-group">
                                    <a href="{{route('nuevoUsuario')}}" class="btn btn-info pull-right" title="Nuevo usuario"><i class="fa fa-user-plus"></i> Nuevo</a>
                                </div>                                
                            {{ Form::close() }}
                        </div>

                        <div class="box-body">
                            <table id="tabla1" border="1" style="border: 2px solid slategray;" class="table table-bordered table-sm">
                                <thead style="border-color:brown;color: brown;" class="justify">
                                    <tr>
                                        <th colspan="1" style="text-align:center; vertical-align: middle;border: 2px solid slategray;"><b style="color: green">Folio</b>
                                        </th>
                                        <th colspan="1" style="text-align:center; vertical-align: middle;border: 2px solid slategray;"><b style="color: green">Usuario</b>
                                        </th>
                                        <th colspan="1" style="text-align:center; vertical-align: middle;border: 2px solid slategray;"><b style="color: green">Equipo de futbol</b>
                                        </th>
                                        <th colspan="1" style="text-align:left; vertical-align: middle;border: 2px solid slategray;"><b style="color: dodgerblue">Login </b>
                                        </th>
                                        <th colspan="1" style="text-align:left; vertical-align: middle;border: 2px solid slategray;"><b style="color: dodgerblue">Contraseña</b>
                                        </th>
                                        <th colspan="1" style="text-align:center; vertical-align: middle;border: 2px solid slategray;">Rol
                                        </th>
                                        <th colspan="1" style="text-align:center; vertical-align: middle;border: 2px solid slategray;">Status
                                        </th>
                                        <th colspan="1" style="text-align:center; vertical-align: middle;border: 2px solid slategray;">Acciones
                                        </th>
                                    </tr>
                                </thead>
                                <tbody>
                                @foreach($usuarios as $usuario)
                                    <tr>
                                        <td style="text-align:center; vertical-align:middle;">{{$usuario->folio}} </td>
                                        <td style="text-align:left; vertical-align:middle;">{{$usuario->nombre_completo}}
                                        </td>
                                        <td style="text-align:left; vertical-align:middle;">
                                            @foreach($regficha as $equipo)
                                                @if($equipo->eq_id == $usuario->cve_arbol)
                                                    {{Trim($equipo->eq_desc)}}
                                                    @break
                                                @endif
                                            @endforeach 
                                        </td>
                                        <td style="text-align:left; vertical-align: middle;">{{$usuario->login}}</td>
                                        <td style="text-align:left; vertical-align: middle;">{{$usuario->password}}</td>
                                        @if($usuario->status_1 == 4)
                                            <td style="text-align:center; vertical-align: middle;"><a class="btn btn-danger" title="Super Administrador"><i class="fa  fa-user-secret"></i></a></td>
                                        @else
                                            @if($usuario->status_1 == 3)
                                                <td style="text-align:center; vertical-align: middle;"><a class="btn btn-warning" title="Administrador"><i class="fa fa-users"></i></a></td>
                                            @else
                                                @if($usuario->status_1 == 2)
                                                    <td style="text-align:center; vertical-align: middle;"><a class="btn btn-primary" title="Particular"><i class="fa fa-user"></i></a></td>
                                                @else
                                                    @if($usuario->status_1 == 1)
                                                        <td style="text-align:center; vertical-align: middle;"><a class="btn btn-primary" title="Operativo"><i class="fa fa-user"></i></a></td>
                                                    @else
                                                        <td style="text-align:center; vertical-align: middle;"><a class="btn btn-info" title="osc_"><i class="fa fa-male"></i></a></td>
                                                    @endif
                                                @endif
                                            @endif
                                        @endif
                                        @if($usuario->status_2 == 1)
                                            <td style="color:darkred; text-align:center; vertical-align: middle;">
                                                <a href="{{route('desactivarUsuario',$usuario->folio)}}" title="Activo"><i class="fa fa-check"></i>
                                                </a>
                                            </td>
                                        @else
                                            <td style="color:darkgreen;text-align:center; vertical-align: middle;">
                                                <a href="{{route('activarUsuario',$usuario->folio)}}" title="Inactivo"><i class="fa  fa-times"></i>
                                                </a>
                                            </td>
                                        @endif
                                        <td style="text-align:center; vertical-align: middle;">
                                            <a href="{{route('editarUsuario',$usuario->folio)}}" class="btn badge-warning" title="Editar"><i class="fa fa-edit"></i>
                                            </a>
                                            <a href="{{route('borrarUsuario',$usuario->folio)}}" class="btn badge-danger" title="Borrar usuario" onclick="return confirm('¿Seguro que desea borrar el usuario?')"><i class="fa fa-times"></i>
                                            </a>
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                            {!! $usuarios->appends(request()->input())->links() !!}
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
