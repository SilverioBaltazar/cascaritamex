@extends('sicinar.principal')

@section('title','Ver Municipios SEDESEM')

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
            <h1>Catálogo de Municipios
                <small> Seleccionar </small>
            </h1>
            <ol class="breadcrumb">
                <li><a href="#"><i class="fa fa-dashboard"></i> Menú</a></li>
                <li><a href="#">Catálogos          </a></li>
                <li><a href="#">Municipios SEDESEM </a></li>         
            </ol>
        </section>
        <section class="content">
            <div class="row">
                <div class="col-md-12">
                    <div class="box">
                        <div class="box-header" style="text-align:right;">
                            <a href="{{route('downloadmunicipios')}}" class="btn btn-success" title="Exportar catálogo de municipios SEDESEM (formato Excel)"><i class="fa fa-file-excel-o"></i> Excel</a>                
                            <a href="{{route('catmunicipiosPDF')}}" class="btn btn-danger" title="Exportar catálogo de municipios SEDESEM (formato PDF)"><i class="fa fa-file-pdf-o"></i> PDF</a>
                        </div>
                        <div class="box-body">
                            <table id="tabla1" class="table table-striped table-bordered table-sm">
                                <thead style="color: brown;" class="justify">
                                    <tr>
                                        <th style="text-align:left; vertical-align: middle;">Id.</th>
                                        <th style="text-align:left; vertical-align: middle;">Municipio</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($regmunicipio as $municipio)
                                    <tr>
                                        <td style="text-align:left; vertical-align: middle;">{{$municipio->municipioid}}</td>
                                        <td style="text-align:left; vertical-align: middle;">{{$municipio->municipionombre}}</td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                            {!! $regmunicipio->appends(request()->input())->links() !!}
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