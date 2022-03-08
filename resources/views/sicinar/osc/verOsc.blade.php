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

                        <div class="page-header" style="text-align:right;">
                            <label style="color:green;"><small><i class="fa fa-check"></i>Activas :</small></label>
                            @foreach($regtotactivas as $total_a)
                               <label style="color:green;"><small>{{$total_a->total_activas}}</small></label>
                            @endforeach
                            <label style="color:red;"><small><i class="fa fa-times"></i>  Inactivas :</small></label>
                            @foreach($regtotinactivas as $total_i)
                               <label style="color:red;"><small>{{$total_i->total_inactivas}} </small></label>
                            @endforeach
                            
                            {{ Form::open(['route' => 'buscarOsc', 'method' => 'GET', 'class' => 'form-inline pull-right']) }}
                                <div class="form-group">
                                    {{ Form::text('name', null, ['class' => 'form-control', 'placeholder' => 'Nombre OSC']) }}
                                </div>
                                <div class="form-group">
                                    {{ Form::text('bio', null, ['class' => 'form-control', 'placeholder' => 'Objeto social']) }}
                                </div>
                                <div class="form-group">
                                    <button type="submit" class="btn btn-default">
                                        <span class="glyphicon glyphicon-search"></span>
                                    </button>
                                </div>
                                <div class="form-group">
                                    <a href="{{route('oscexcel')}}" class="btn btn-success" title="Exportar catálogo de OSC (formato Excel)"><i class="fa fa-file-excel-o"></i> Excel
                                    </a>                            
                                    <a href="{{route('nuevaOsc')}}" class="btn btn-primary btn_xs" title="Alta nueva OSC"><i class="fa fa-file-new-o"></i><span class="glyphicon glyphicon-plus"></span>Nueva OSC
                                    </a>
                                </div>                                
                            {{ Form::close() }}
                        </div>

                        <div class="box-body">
                            <table id="tabla1" class="table table-hover table-striped">
                                <thead style="color: brown;" class="justify">
                                    <tr>
                                        <th style="text-align:left;   vertical-align: middle;">Id.              </th>
                                        <th style="text-align:left;   vertical-align: middle;">Nombre de la OSC </th>
                                        <th style="text-align:left;   vertical-align: middle;">Domicilio Legal  </th>     
                                        <th style="text-align:left;   vertical-align: middle;">Fol.Reg.<br>Púb.Prop.</th>     
                                        <th style="text-align:center; vertical-align: middle;">Activa <br>Inact.</th>
                                        
                                        <th style="text-align:center; vertical-align: middle; width:100px;">Acciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($regosc as $osc)
                                    <tr>
                                        <td style="tfont-family:'Arial, Helvetica, sans-serif'; font-size:11px; text-align:left; vertical-align: middle;">{{$osc->osc_id}}        
                                        </td>
                                        <td style="tfont-family:'Arial, Helvetica, sans-serif'; font-size:11px; text-align:left; vertical-align: middle;">{{Trim($osc->osc_desc)}}
                                        </td>
                                        <td style="tfont-family:'Arial, Helvetica, sans-serif'; font-size:11px; text-align:left; vertical-align: middle;">{{$osc->osc_dom1}}     
                                        </td>
                                        <td style="font-family:'Arial, Helvetica, sans-serif'; font-size:11px; text-align:left; vertical-align: middle;">{{$osc->osc_regcons}}     
                                        </td>                                                                                

                                        @if($osc->osc_status == 'S')
                                            <td style="font-family:'Arial, Helvetica, sans-serif'; font-size:10px; text-align:center; vertical-align: middle;" title="Activo"><i class="fa fa-check"></i>
                                            </td>                                            
                                        @else
                                            <td style="tfont-family:'Arial, Helvetica, sans-serif'; font-size:10px; text-align:center; vertical-align: middle;" title="Inactivo"><i class="fa fa-times"></i>
                                            </td>                                            
                                        @endif
                                        
                                        <td style="text-align:center;">
                                            <a href="{{route('editarOsc',$osc->osc_id)}}" class="btn badge-warning" title="Editar OSC"><i class="fa fa-edit"></i></a>
                                            <a href="{{route('borrarOsc',$osc->osc_id)}}" class="btn badge-danger" title="Borrar OSC" onclick="return confirm('¿Seguro que desea borrar la OSC?')"><i class="fa fa-times"></i></a>
                                        </td>                                                                                
                                    </tr>
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
