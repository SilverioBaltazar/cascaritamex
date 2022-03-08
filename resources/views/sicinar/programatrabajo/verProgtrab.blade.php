@extends('sicinar.principal')

@section('title','Ver programa de trabajo')

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
            <h1>Requisitos de operación
                <small> Seleccionar alguna para editar o registrar programa de trabajo </small>
            </h1>
            <ol class="breadcrumb">
                <li><a href="#"><i class="fa fa-dashboard"></i> Menú</a></li>
                <li><a href="#">Requisitos de operación </a></li>   
                <li><a href="#">Programa de trabajo     </a></li>               
            </ol>
        </section>
        <section class="content">
            <div class="row">
                <div class="col-md-12">
                    <div class="box">

                        <div class="page-header" style="text-align:right;">
                            
                            {{ Form::open(['route' => 'buscarProgtrab', 'method' => 'GET', 'class' => 'form-inline pull-right']) }}
                                <div class="form-group">
                                    {{ Form::text('nameiap', null, ['class' => 'form-control', 'placeholder' => 'Nombre OSC']) }}
                                </div>                                             
                                <div class="form-group">
                                    {{ Form::text('folio', null, ['class' => 'form-control', 'placeholder' => 'Folio']) }}
                                </div>
                                <div class="form-group">
                                    <button type="submit" class="btn btn-default">
                                        <span class="glyphicon glyphicon-search"></span>
                                    </button>
                                </div>
                                <div class="form-group">   
                                    <a href="{{route('nuevoProgtrab')}}" class="btn btn-primary btn_xs" title="Nuevo programa de trabajo"><i class="fa fa-file-new-o"></i><span class="glyphicon glyphicon-plus"></span><small>Nuevo programa de trabajo</small>
                                    </a>
                                </div>                                
                            {{ Form::close() }}
                        </div>

                        <div class="box-body">
                            <table id="tabla1" class="table table-hover table-striped">
                                <thead style="color: brown;" class="justify">
                                    <tr>
                                        <th style="text-align:left;   vertical-align: middle;">Periodo        </th>
                                        <th style="text-align:left;   vertical-align: middle;">OSC            </th>
                                        <th style="text-align:left;   vertical-align: middle;">Folio          </th>                                        
                                        <th style="text-align:left;   vertical-align: middle;">Responsable    </th>
                                        <th style="text-align:left;   vertical-align: middle;">Elaboro        </th>
                                        <th style="text-align:left;   vertical-align: middle;">Autorizo       </th>
                                        <th style="text-align:left;   vertical-align: middle;">Actividades    </th>
                                        <th style="text-align:left;   vertical-align: middle;">Fec.Reg.       </th>
                                        <th style="text-align:center; vertical-align: middle;">Activa <br>Inact.</th>
                                        
                                        <th style="text-align:center; vertical-align: middle; width:100px;">Acciones</th>
                                        <th style="text-align:center; vertical-align: middle; width:100px;">Funciones</th>
                                    </tr>
                                </thead> 
                                <tbody>
                                    @foreach($regprogtrab as $progtrab)

                                    <tr>
                                        <td style="text-align:left; vertical-align: middle;">{{$progtrab->periodo_id}}</td>
                                        <td style="text-align:left; vertical-align: middle;">{{$progtrab->osc_id}}   
                                            @foreach($regosc as $osc)
                                                @if($osc->osc_id == $progtrab->osc_id)
                                                    {{$osc->osc_desc}}
                                                    @break 
                                                @endif
                                            @endforeach
                                        </td>                                          
                                        <td style="text-align:left; vertical-align: middle;">{{$progtrab->folio}}     </td>                                        
                                        <td style="text-align:left; vertical-align: middle;">{{Trim($progtrab->responsable)}}
                                        </td>
                                        <td style="text-align:left; vertical-align: middle;">{{Trim($progtrab->elaboro)}}    
                                        </td>
                                        <td style="text-align:left; vertical-align: middle;">
                                            {{Trim($progtrab->autorizo)}}    
                                        </td>
                                        <td style="text-align:center; vertical-align: middle;"><small>
                                            @foreach($totactivs as $partida)
                                                @if($partida->periodo_id == $progtrab->periodo_id && $partida->folio == $progtrab->folio)
                                                    @if(!empty($partida->totactividades)&&(!is_null($partida->totactividades)))
                                                       <b style="color:darkgreen;">{{$partida->totactividades}}</b>
                                                       
                                                    @else
                                                       <b style="color:darkred;">0 Faltan las actividades</b>
                                                       
                                                    @endif
                                                    @break
                                                @endif
                                            @endforeach </small>
                                        </td>                                                                                 
                                        <td style="text-align:left; vertical-align: middle;">{{date("d/m/Y",strtotime($progtrab->fecreg))}}</td>
                                        @if($progtrab->status_1 == 'S')
                                            <td style="color:darkgreen;text-align:center; vertical-align: middle;" title="Activo"><i class="fa fa-check"></i>
                                            </td>                                            
                                        @else
                                            <td style="color:darkred; text-align:center; vertical-align: middle;" title="Inactivo"><i class="fa fa-times"></i>
                                            </td>                                            
                                        @endif
                                        
                                        <td style="text-align:center;">
                                            <a href="{{route('editarProgtrab',$progtrab->folio)}}" class="btn badge-warning" title="Editar programa de trabajo"><i class="fa fa-edit"></i>
                                            </a>
                                            <a href="{{route('borrarProgtrab',$progtrab->folio)}}" class="btn badge-danger" title="Borrar programa de trabajo" onclick="return confirm('¿Seguro que desea borrar programa de trabajo?')"><i class="fa fa-times"></i>
                                            </a>
                                        </td>
                                        <td style="text-align:center;">
                                            <a href="{{route('ExportProgtrabPdf',array($progtrab->periodo_id,$progtrab->folio))}}" class="btn btn-danger" title="Programa de trabajo (formato PDF)"><i class="fa fa-file-pdf-o"></i>
                                            <small> PDF</small>
                                            </a>
                                            <a href="{{route('verProgdtrab',$progtrab->folio)}}" class="btn btn-primary btn_xs" title="Ver actividades del programa de trabajo"><i class="fa fa-file-new-o"></i><span class="glyphicon glyphicon-plus"></span><small>Actividades</small>
                                            </a>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                            {!! $regprogtrab->appends(request()->input())->links() !!}
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
