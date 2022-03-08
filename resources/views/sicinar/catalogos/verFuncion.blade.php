@extends('sicinar.principal')

@section('title','Ver Funciones de procesos')

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
            <h1>Catálogo de funciones de procesos
                <small> Seleccionar alguna para editar o registrar nueva función de proceso</small>
            </h1>
            <ol class="breadcrumb">
                <li><a href="#"><i class="fa fa-dashboard"></i> Menú</a></li>
                <li><a href="#">Catálogos </a></li>
                <li><a href="#">Funciones  </a></li>         
            </ol>
        </section>
        <section class="content">
            <div class="row">
                <div class="col-md-12">
                    <div class="box">
                        <div class="box-header" style="text-align:right;">
                            <a href="{{route('downloadfunciones')}}" class="btn btn-success" title="Exportar catálogo de funciones de procesos (formato Excel)"><i class="fa fa-file-excel-o"></i> Excel</a>                            
                            <a href="{{route('catfuncionesPDF')}}" class="btn btn-danger" title="Exportar catálogo de funciones de procesos (formato PDF)"><i class="fa fa-file-pdf-o"></i> PDF</a>
                            <a href="{{route('nuevaFuncion')}}"   class="btn btn-primary" title="Alta de nueva función de proceso"><i class="fa fa-file-new-o"></i><span class="glyphicon glyphicon-plus"></span>Nueva función de proceso</a>                             
                        </div>
                        <div class="box-body">
                            <table id="tabla1" class="table table-striped table-bordered table-sm">
                                <thead style="color: brown;" class="justify">
                                    <tr>
                                        <th style="text-align:left;   vertical-align: middle;">Id.     <br>Proc.</th>
                                        <th style="text-align:left;   vertical-align: middle;">Proceso</th>
                                        <th style="text-align:left;   vertical-align: middle;">Id.     <br>Func.</th>
                                        <th style="text-align:left;   vertical-align: middle;">Función del proceso</th>
                                        <th style="text-align:center; vertical-align: middle;">Activa /<br> Inactiva</th>
                                        <th style="text-align:center; vertical-align: middle;">Fecha   <br>registro</th>
                                        <th style="text-align:center; vertical-align: middle;">Acciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($regfuncion as $funcion)
                                    <tr>
                                        <td style="text-align:left; vertical-align: middle;">{{$funcion->proceso_id}}  </td>
                                        <td style="text-align:left; vertical-align: middle;">{{$funcion->proceso_desc}}</td>
                                        <td style="text-align:left; vertical-align: middle;">{{$funcion->funcion_id}}  </td>
                                        <td style="text-align:left; vertical-align: middle;">{{$funcion->funcion_desc}}</td>
                                        @if($funcion->funcion_status == 'S')
                                            <td style="color:darkgreen;text-align:center; vertical-align: middle;" title="Activa"><i class="fa fa-check"></i>
                                            </td>                                            
                                        @else
                                            <td style="color:darkred; text-align:center; vertical-align: middle;" title="Inactiva"><i class="fa fa-times"></i>
                                            </td>                                            
                                        @endif
                                        <td style="text-align:center; vertical-align: middle;">{{date("d/m/Y", strtotime($funcion->funcion_fecreg))}}</td>
                                        <td style="text-align:center;">
                                            <a href="{{route('editarFuncion',$funcion->funcion_id)}}" class="btn badge-warning" title="Editar funcion de proceso"><i class="fa fa-edit"></i></a>
                                            <a href="{{route('borrarFuncion', $funcion->funcion_id)}}" class="btn badge-danger" title="Borrar funcion de proceso" onclick="return confirm('¿Seguro que desea borrar?')"><i class="fa fa-times"></i></a>
                                        </td>                                                                                
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                            {!! $regfuncion->appends(request()->input())->links() !!}
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