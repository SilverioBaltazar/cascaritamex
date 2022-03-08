@extends('sicinar.principal')

@section('title','Ver Actividades')

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
            <h1>Catálogo de actividades 
                <small> Seleccionar alguna para editar o registrar nueva actividad del modelado de procesos</small>
            </h1>
            <ol class="breadcrumb">
                <li><a href="#"><i class="fa fa-dashboard"></i> Menú</a></li>
                <li><a href="#">Catálogos </a></li>
                <li><a href="#">Actividades  </a></li>         
            </ol>
        </section>
        <section class="content">
            <div class="row">
                <div class="col-md-12">
                    <div class="box">
                        <div class="box-header" style="text-align:right;">
                            <a href="{{route('downloadtrx')}}" class="btn btn-success" title="Exportar catálogo de actividades del modelado de procesos (formato Excel)"><i class="fa fa-file-excel-o"></i> Excel</a>                            
                            <a href="{{route('cattrxPDF')}}" class="btn btn-danger" title="Exportar catálogo de actividades del modelado de procesos (formato PDF)"><i class="fa fa-file-pdf-o"></i> PDF</a>
                            <a href="{{route('nuevaTrx')}}"   class="btn btn-primary" title="Alta de nueva actividad"><i class="fa fa-file-new-o"></i><span class="glyphicon glyphicon-plus"></span>Nueva actividad</a>
                        </div>
                        <div class="box-body">
                            <table id="tabla1" class="table table-striped table-bordered table-sm">
                                <thead style="color: brown;" class="justify">
                                    <tr>
                                        <th style="text-align:left;   vertical-align: middle;">Id.</th>
                                        <th style="text-align:left;   vertical-align: middle;">Actividad</th>
                                        <th style="text-align:center; vertical-align: middle;">Activa / Inactiva</th>
                                        <th style="text-align:center; vertical-align: middle;">Fecha registro</th>
                                        <th style="text-align:center; vertical-align: middle;">Acciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($regtrx as $trx)
                                    <tr>
                                        <td style="text-align:left; vertical-align: middle;">{{$trx->trx_id}}</td>
                                        <td style="text-align:left; vertical-align: middle;">{{$trx->trx_desc}}</td>
                                        @if($trx->trx_status == 'S')
                                            <td style="color:darkgreen;text-align:center; vertical-align: middle;" title="Activo"><i class="fa fa-check"></i>
                                            </td>                                            
                                        @else
                                            <td style="color:darkred; text-align:center; vertical-align: middle;" title="Inactivo"><i class="fa fa-times"></i>
                                            </td>                                            
                                        @endif
                                        <td style="text-align:center; vertical-align: middle;">{{date("d/m/Y", strtotime($trx->trx_fecreg))}}</td>
                                        <td style="text-align:center;">
                                            <a href="{{route('editarTrx',$trx->trx_id)}}" class="btn badge-warning" title="Editar actividad"><i class="fa fa-edit"></i></a>
                                            <a href="{{route('borrarTrx',$trx->trx_id)}}" class="btn badge-danger" title="Borrar actividad" onclick="return confirm('¿Seguro que desea borrar la actividad?')"><i class="fa fa-times"></i></a>
                                        </td>                                                                                
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                            {!! $regtrx->appends(request()->input())->links() !!}
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