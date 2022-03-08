@extends('sicinar.principal')

@section('title','Ver Procesos')

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
            <h1>Catálogo de procesos
                <small> Seleccionar alguno para editar o registrar nuevo proceso</small>
            </h1>
            <ol class="breadcrumb">
                <li><a href="#"><i class="fa fa-dashboard"></i> Menú</a></li>
                <li><a href="#">Catálogos </a></li>
                <li><a href="#">Procesos  </a></li>         
            </ol>
        </section>
        <section class="content">
            <div class="row">
                <div class="col-md-12">
                    <div class="box">
                        <div class="box-header" style="text-align:right;">
                            <a href="{{route('downloadprocesos')}}" class="btn btn-success" title="Exportar catálogo de procesos (formato Excel)"><i class="fa fa-file-excel-o"></i> Excel</a>                            
                            <a href="{{route('catprocesosPDF')}}" class="btn btn-danger" title="Exportar catálogo de procesos (formato PDF)"><i class="fa fa-file-pdf-o"></i> PDF</a>
                            <a href="{{route('nuevoProceso')}}"   class="btn btn-primary" title="Alta de nuevo proceso"><i class="fa fa-file-new-o"></i><span class="glyphicon glyphicon-plus"></span>Nuevo proceso</a>
                        </div>
                        <div class="box-body">
                            <table id="tabla1" class="table table-striped table-bordered table-sm">
                                <thead style="color: brown;" class="justify">
                                    <tr>
                                        <th style="text-align:left;   vertical-align: middle;">Id.</th>
                                        <th style="text-align:left;   vertical-align: middle;">Nombre del proceso</th>
                                        <th style="text-align:center; vertical-align: middle;">Activo / Inactivo</th>
                                        <th style="text-align:center; vertical-align: middle;">Fecha registro</th>
                                        <th style="text-align:center; vertical-align: middle;">Acciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($regproceso as $proceso)
                                    <tr>
                                        <td style="text-align:left; vertical-align: middle;">{{$proceso->proceso_id}}</td>
                                        <td style="text-align:left; vertical-align: middle;">{{$proceso->proceso_desc}}</td>
                                        @if($proceso->proceso_status == 'S')
                                            <td style="color:darkgreen;text-align:center; vertical-align: middle;" title="Activo"><i class="fa fa-check"></i>
                                            </td>                                            
                                        @else
                                            <td style="color:darkred; text-align:center; vertical-align: middle;" title="Inactivo"><i class="fa fa-times"></i>
                                            </td>                                            
                                        @endif
                                        <td style="text-align:center; vertical-align: middle;">{{date("d/m/Y", strtotime($proceso->proceso_fecreg))}}</td>
                                        <td style="text-align:center;">
                                            <a href="{{route('editarProceso',$proceso->proceso_id)}}" class="btn badge-warning" title="Editar proceso"><i class="fa fa-edit"></i></a>
                                            <a href="{{route('borrarProceso',$proceso->proceso_id)}}" class="btn badge-danger" title="Borrar proceso" onclick="return confirm('¿Seguro que desea borrar?')"><i class="fa fa-times"></i></a>
                                        </td>                                                                                
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                            {!! $regproceso->appends(request()->input())->links() !!}
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