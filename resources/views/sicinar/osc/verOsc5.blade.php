@extends('sicinar.principal')

@section('title','Ver OSC')

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
            <h1><i class="fa fa-dashboard"></i>&nbsp;&nbsp;Menú.
                <small>1. Registro OSC</small>
                <small> - Seleccionar para editar o registrar nueva</small>
            </h1>
            <ol class="breadcrumb">
                <li><img src="{{ asset('images/b1.jpg') }}" border="0" width="30" height="30">Registro OSC</li> 
            </ol>
        </section>
        <section class="content">
            <div class="row">
                <div class="col-md-12">
                    <div class="box">
                        <p align="justify"><b style="color:red;">
                            Instrucciones:</b> <b style="color:green;">
                            Requisita los datos de la Organización
                            </b>
                        </p>                                            
                        <div class="box-body">
                            <table id="tabla1" class="table table-hover table-striped">
                                <thead style="color: brown;" class="justify">
                                    <tr>
                                        <th style="text-align:left;   vertical-align: middle;">Id.              </th>
                                        <th style="text-align:left;   vertical-align: middle;">Nombre de la OSC </th>
                                        <th style="text-align:left;   vertical-align: middle;">Domicilio Legal  </th>     
                                        <th style="text-align:center; vertical-align: middle;">Activa <br>Inact.</th> 
                                        <th style="text-align:center; vertical-align: middle; width:100px;">Acciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($regosc as $osc)
                                    <tr>
                                        <td style="text-align:left; vertical-align: middle;">{{$osc->osc_id}}        </td>
                                        <td style="text-align:left; vertical-align: middle;">{{Trim($osc->osc_desc)}}</td>
                                        <td style="text-align:left; vertical-align: middle;">{{$osc->osc_dom1}}     </td>
                                                                                

                                        @if($osc->osc_status == 'S')
                                            <td style="color:darkgreen;text-align:center; vertical-align: middle;" title="Activo"><i class="fa fa-check"></i>
                                            </td>                                            
                                        @else
                                            <td style="color:darkred; text-align:center; vertical-align: middle;" title="Inactivo"><i class="fa fa-times"></i>
                                            </td>                                            
                                        @endif
                                        
                                        <td style="text-align:center;">
                                            <a href="{{route('editarOsc5',$osc->osc_id)}}" class="btn badge-warning" title="Editar OSC"><i class="fa fa-edit"></i>
                                            </a>
                                        </td>                                                                                
                                    </tr>
                                        @if(session()->get('rango') == '0')
                                        <tr>
                                            <td style="text-align:left;"></td>
                                            <td style="text-align:left;"></td>
                                            <td style="text-align:center;"></td>
                                            <td style="text-align:right;"></td>                                            
                                            <td style="text-align:right;">
                                                <a href="{{route('verJur')}}">                                        
                                                <img src="{{ asset('images/fsig.jpg') }}" border="0" width="30" height="30" title="Siguiente requisito">
                                                <img src="{{ asset('images/b2.jpg') }}" border="0" width="30" height="30" title="Requisitos jurídicos">
                                                </a>
                                            </td>
                                        </tr>
                                        @endif
                                    @endforeach
                                </tbody>
                            </table>
                            {!! $regosc->appends(request()->input())->links() !!}
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