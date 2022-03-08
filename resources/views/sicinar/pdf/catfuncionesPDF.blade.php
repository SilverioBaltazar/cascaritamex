@extends('sicinar.pdf.layout')

@section('content')
    <!--<h1 class="page-header">Listado de productos</h1>-->
    <table class="table table-hover table-striped" align="center">
        <thead>
        <tr>
            <th><img src="{{ asset('images/Gobierno.png') }}" alt="EDOMEX" width="90px" height="55px" style="margin-right: 15px;"/></th>
            <th style="width:440px; text-align:center;"><h4 style="color:black;">Catálogo de Funciones (Modelado de procesos)</h4></th>
            <th><img src="{{ asset('images/Edomex.png') }}" alt="EDOMEX" width="80px" height="55px" style="margin-left: 15px;"/>
            </th>
        </tr>
        </thead>
    </table>
    <!-- ::::::::::::::::::::::: titulos ::::::::::::::::::::::::: -->
    <table class="table table-sm" align="center">
        <thead>        
        <tr>
            <th style="background-color:darkgreen;text-align:center;vertical-align: middle;width: 5px;"><b style="color:white;font-size: x-small;">id.<br>Proc.</b></th>
            <th style="background-color:darkgreen;text-align:left;width: 200px;"><b style="color:white;font-size: x-small;">Proceso</b></th>
            <th style="background-color:darkgreen;text-align:center;vertical-align: middle;width: 5px;"><b style="color:white;font-size: x-small;">id.<br>Func.</b></th>
            <th style="background-color:darkgreen;text-align:left;width: 200px;"><b style="color:white;font-size: x-small;">Función del proceso</b></th>            
            <th style="background-color:darkgreen;text-align:center;width: 10px;"><b style="color:white;font-size: x-small;">Activo/<br>Inactivo</b></th>
            <th style="background-color:darkgreen;text-align:center;width: 25px;"><b style="color:white;font-size: x-small;">Fecha registro</b></th>
        </tr>
        </thead>
        <tbody>
            @foreach($regfuncion as $funcion)
                <tr>
                    <td style="background-color:orange;text-align:center;vertical-align: middle;width: 5px;"><b style="color:black;font-size: xx-small;">{{$funcion->proceso_id}}</b>
                    </td>
                    @foreach($regproceso as $proceso)
                        @if($proceso->proceso_id == $funcion->proceso_id)
                            <td style="background-color:orange;text-align:justify;vertical-align: middle;"><b style="color:black;font-size: xx-small;">{{trim($proceso->proceso_desc)}}</b></td>
                        @endif
                    @endforeach                              
                    <td style="text-align:center;vertical-align: middle;width: 5px;"><b style="color:black;font-size: xx-small;">{{$funcion->funcion_id}}</b>
                    </td>
                    <td style="text-align:justify;vertical-align: middle;width: 200px;"><b style="color:black;font-size: xx-small;">{{trim($funcion->funcion_desc)}}</b>
                    </td>
                    <td style="text-align:center;vertical-align: middle;width: 10px;"><b style="color:black;font-size: xx-small;">{{$funcion->funcion_status}}</b>
                    </td>
                    <td style="text-align:center; vertical-align: middle;width: 25px;"><b style="color:black;font-size: xx-small;">{{date("d/m/Y", strtotime($funcion->funcion_fecreg))}}</b>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
    <table style="page-break-inside: avoid;" class="table table-hover table-striped" align="center">
        <thead>
        <tr>
            <th style="text-align:right;"><b style="font-size: x-small;"><b>Fecha de emisión: {!! date('d/m/Y') !!}</b></th>
        </tr>
        </thead>
    </table>
@endsection