@extends('sicinar.principal')

@section('title','Ver Estados de los Inmuebles')

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
            <h1>Catálogo de estado de inmuebles
                <small> Seleccionar alguno para editar o registrar nuevo estado del inmueble</small>
            </h1>
            <ol class="breadcrumb">
                <li><a href="#"><i class="fa fa-dashboard"></i> Menú</a></li>
                <li><a href="#">Catálogos            </a></li>
                <li><a href="#">Estado del inmueble  </a></li>         
            </ol>
        </section>
        <section class="content">
            <div class="row">
                <div class="col-md-12">
                    <div class="box">
                        <div class="box-header" style="text-align:right;">
                            <a href="{{route('downloadinmueblesedo')}}" class="btn btn-success" title="Exportar catálogo de Estado de inmuebles (formato Excel)"><i class="fa fa-file-excel-o"></i> Excel</a>                            
                            <a href="{{route('catinmueblesedoPDF')}}" class="btn btn-danger" title="Exportar catálogo de Estado de inmuebless (formato PDF)"><i class="fa fa-file-pdf-o"></i> PDF</a>
                            <a href="{{route('nuevoInmuebleedo')}}"   class="btn btn-primary" title="Alta de nuevo estado del inmueble social"><i class="fa fa-file-new-o"></i><span class="glyphicon glyphicon-plus"></span>Nuevo estado del inmueble social</a>                             
                        </div>
                        <div class="box-body">
                            <table id="tabla1" class="table table-striped table-bordered table-sm">
                                <thead style="color: brown;" class="justify">
                                    <tr>
                                        <th style="text-align:left;   vertical-align: middle;">Id.     </th>
                                        <th style="text-align:left;   vertical-align: middle;">Estado del inmueble social</th>
                                        <th style="text-align:center; vertical-align: middle;">Activo /<br> Inactivo</th>
                                        <th style="text-align:center; vertical-align: middle;">Fecha   <br>registro</th>
                                        <th style="text-align:center; vertical-align: middle;">Acciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($reginmuebleedo as $inmueble)
                                    <tr>
                                        <td style="text-align:left; vertical-align: middle;">{{$inmueble->inm_id}}  </td>
                                        <td style="text-align:left; vertical-align: middle;">{{$inmueble->inm_desc}}</td>
                                        @if($inmueble->inm_status == 'S')
                                            <td style="color:darkgreen;text-align:center; vertical-align: middle;" title="Activa"><i class="fa fa-check"></i>
                                            </td>                                            
                                        @else
                                            <td style="color:darkred; text-align:center; vertical-align: middle;" title="Inactiva"><i class="fa fa-times"></i>
                                            </td>                                            
                                        @endif
                                        <td style="text-align:center; vertical-align: middle;">{{date("d/m/Y", strtotime($inmueble->inm_fecreg))}}</td>
                                        <td style="text-align:center;">
                                            <a href="{{route('editarInmuebleedo',$inmueble->inm_id)}}" class="btn badge-warning" title="Editar estado del inmueble social"><i class="fa fa-edit"></i></a>
                                            <a href="{{route('borrarInmuebleedo', $inmueble->inm_id)}}" class="btn badge-danger" title="Borrar estado del inmueble" onclick="return confirm('¿Seguro que desea borrar el estado del inmueble social?')"><i class="fa fa-times"></i></a>
                                        </td>                                                                                
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                            {!! $reginmuebleedo->appends(request()->input())->links() !!}
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