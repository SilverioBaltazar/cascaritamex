@extends('sicinar.principal')

@section('title','Ver Formatos')

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
            <h1>Catálogo de formatos de archivos
                <small> Seleccionar alguno para editar o registrar nuevo formato de archivo </small>
            </h1>
            <ol class="breadcrumb">
                <li><a href="#"><i class="fa fa-dashboard"></i> Menú</a></li>
                <li><a href="#">Catálogos </a></li>
                <li><a href="#">Formatos de archivos  </a></li>         
            </ol>
        </section>
        <section class="content">
            <div class="row">
                <div class="col-md-12">
                    <div class="box">
                        <div class="box-header" style="text-align:right;">
                            <a href="{{route('nuevoFormato')}}"   class="btn btn-primary" title="Alta de nuevo formato"><i class="fa fa-file-new-o"></i><span class="glyphicon glyphicon-plus"></span>Nuevo formato de archivo</a>
                        </div>
                        <div class="box-body">
                            <table id="tabla1" class="table table-striped table-bordered table-sm">
                                <thead style="color: brown;" class="justify">
                                    <tr>
                                        <th style="text-align:left;   vertical-align: middle;">Id.</th>
                                        <th style="text-align:left;   vertical-align: middle;">Tipo de archivo</th>
                                        <th style="text-align:left;   vertical-align: middle;">Etiqueta</th>
                                        <th style="text-align:center; vertical-align: middle;">Activo / Inactivo</th>
                                        <th style="text-align:center; vertical-align: middle;">Comando 1</th>
                                        <th style="text-align:center; vertical-align: middle;">Comando 2</th>
                                        <th style="text-align:center; vertical-align: middle;">Acciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($regformatos as $formato)
                                    <tr>
                                        <td style="text-align:left; vertical-align: middle;">{{$formato->formato_id}}</td>
                                        <td style="text-align:left; vertical-align: middle;">{{$formato->formato_desc}}</td>
                                        <td style="text-align:left; vertical-align: middle;">{{$formato->formato_etiq}}</td>
                                        @if($formato->formato_status == 'S')
                                            <td style="color:darkgreen;text-align:center; vertical-align: middle;" title="Activo"><i class="fa fa-check"></i>
                                            </td>                                            
                                        @else
                                            <td style="color:darkred; text-align:center; vertical-align: middle;" title="Inactivo"><i class="fa fa-times"></i>
                                            </td>                                            
                                        @endif
                                        <td style="text-align:center; vertical-align: middle;">{{$formato->formato_comando1}}</td>
                                        <td style="text-align:center; vertical-align: middle;">{{$formato->formato_comando2}}</td>
                                        <td style="text-align:center;">
                                            <a href="{{route('editarFormato',$formato->formato_id)}}" class="btn badge-warning" title="Editar formato"><i class="fa fa-edit"></i></a>
                                            <a href="{{route('borrarFormato',$formato->formato_id)}}" class="btn badge-danger" title="Borrar formato" onclick="return confirm('¿Seguro que desea borrar el formato?')"><i class="fa fa-times"></i></a>
                                        </td>                                                                                
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                            {!! $regformatos->appends(request()->input())->links() !!}
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