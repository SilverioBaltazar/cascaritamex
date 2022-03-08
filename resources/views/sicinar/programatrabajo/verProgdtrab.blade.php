@extends('sicinar.principal')

@section('title','Ver actividades del programa de trabajo')

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
                <small> Seleccionar para editar o registrar actividad del prog. de trab. </small>
            </h1>
            <ol class="breadcrumb">
                <li><a href="#"><i class="fa fa-dashboard"></i> Menú</a></li>
                <li><a href="#">Requisitos de operación </a></li>   
                <li><a href="#">Programa de trabajo (actividades)  </a></li>               
            </ol>
        </section>
        <section class="content">
            <div class="row">
                <div class="col-md-12">
                    <div class="box">

                        <table id="tabla1" class="table table-hover table-striped">
                            @foreach($regprogtrab as $progtrab)
                            <tr>
                                <td style="text-align:left; vertical-align: middle;">   
                                </td>                                                                 
                                <td style="text-align:left; vertical-align: middle;">   
                                </td>
                                <td style="text-align:center; vertical-align: middle;"> 
                                </td>
                                <td style="text-align:right; vertical-align: middle;">   
                                    <a href="{{route('verProgtrab')}}" role="button" id="cancelar" class="btn btn-success"><small>Regresar a programa trabajo</small>
                                    </a>
                                    <a href="{{route('nuevoProgdtrab',$progtrab->folio)}}" class="btn btn-primary btn_xs" title="Nueva actividad del programa de trabajo"><i class="fa fa-file-new-o"></i><span class="glyphicon glyphicon-plus"></span><small>Nueva actividad</small>
                                    </a>
                                </td>                                     
                            </tr>                                                   
                            <tr>                            
                                <td style="text-align:left; vertical-align: middle; color:green;">   
                                    <input type="hidden" id="folio" name="folio" value="{{$progtrab->folio}}">  
                                    <label>Folio: </label>{{$progtrab->folio}}
                                </td>                                                                 
                                <td style="text-align:left; vertical-align: middle; color:green;">   
                                    <input type="hidden" id="periodo_id" name="periodo_id" value="{{$progtrab->periodo_id}}">  
                                    <label>Periodo fiscal: </label>{{$progtrab->periodo_id}}                                        
                                </td>
                                <td style="text-align:center; vertical-align: middle; color:green;"> 
                                    <input type="hidden" id="osc_id" name="osc_id" value="{{$progtrab->osc_id}}">  
                                    <label>OSC: </label>
                                    @foreach($regosc as $osc)
                                        @if($osc->osc_id == $progtrab->osc_id)
                                            {{$osc->osc_desc}}
                                            @break
                                        @endif
                                    @endforeach
                                </td>
                                <td style="text-align:right; vertical-align: middle; color:green;">   
                                    <input type="hidden" id="folio" name="folio" value="{{$progtrab->folio}}">  
                                    <label>Responsable: </label>{{$progtrab->responsable}}
                                </td>                                     
                            </tr>      
  
                            @endforeach     
                        </table>

                        <div class="box-body">
                            <table id="tabla1" class="table table-hover table-striped">
                                <thead style="color: brown;" class="justify">
                                    <tr>
                                        <th style="text-align:left;   vertical-align: middle;">#               </th>
                                        <th style="text-align:left;   vertical-align: middle;">Programa        </th>
                                        <th style="text-align:left;   vertical-align: middle;">Actividad       </th>
                                        <th style="text-align:left;   vertical-align: middle;">Objetivo        </th>
                                        <th style="text-align:left;   vertical-align: middle;">Unidad<br>Medida</th>
                                        <th style="text-align:left;   vertical-align: middle;">Ene             </th>
                                        <th style="text-align:left;   vertical-align: middle;">Feb             </th>
                                        <th style="text-align:left;   vertical-align: middle;">Mar             </th> 
                                        <th style="text-align:left;   vertical-align: middle;">Abr             </th>
                                        <th style="text-align:left;   vertical-align: middle;">May             </th>
                                        <th style="text-align:left;   vertical-align: middle;">Jun             </th>
                                        <th style="text-align:left;   vertical-align: middle;">Jul             </th>
                                        <th style="text-align:left;   vertical-align: middle;">Ago             </th>
                                        <th style="text-align:left;   vertical-align: middle;">Sep             </th>
                                        <th style="text-align:left;   vertical-align: middle;">Oct             </th>
                                        <th style="text-align:left;   vertical-align: middle;">Nov             </th>
                                        <th style="text-align:left;   vertical-align: middle;">Dic             </th>
                                        <th style="text-align:left;   vertical-align: middle;">Edo.            </th>
                                        <th style="text-align:center; vertical-align: middle; width:100px;">Acciones</th>

                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($regprogdtrab as $progdtrab)
                                    <tr>
                                        <td style="text-align:left; vertical-align: middle;">{{$progdtrab->partida}}</td>
                                        <td style="text-align:left; vertical-align: middle;">{{trim($progdtrab->programa_desc)}}
                                        </td>
                                        <td style="text-align:left; vertical-align: middle;">{{Trim($progdtrab->actividad_desc)}}
                                        </td>
                                        <td style="text-align:left; vertical-align: middle;">{{Trim($progdtrab->objetivo_desc)}}    
                                        </td>
                                        <td style="text-align:left; vertical-align: middle;">   
                                        @foreach($regumedida as $umedida)
                                            @if($umedida->umedida_id == $progdtrab->umedida_id)
                                                {{$umedida->umedida_desc}}
                                                @break                                        
                                            @endif
                                        @endforeach
                                        </td>                                                                            
                                        <td style="text-align:left; vertical-align: middle;">{{$progdtrab->mesp_01}}</td>
                                        <td style="text-align:left; vertical-align: middle;">{{$progdtrab->mesp_02}}</td>
                                        <td style="text-align:left; vertical-align: middle;">{{$progdtrab->mesp_03}}</td>
                                        <td style="text-align:left; vertical-align: middle;">{{$progdtrab->mesp_04}}</td>
                                        <td style="text-align:left; vertical-align: middle;">{{$progdtrab->mesp_05}}</td>
                                        <td style="text-align:left; vertical-align: middle;">{{$progdtrab->mesp_06}}</td>
                                        <td style="text-align:left; vertical-align: middle;">{{$progdtrab->mesp_07}}</td>
                                        <td style="text-align:left; vertical-align: middle;">{{$progdtrab->mesp_08}}</td>
                                        <td style="text-align:left; vertical-align: middle;">{{$progdtrab->mesp_09}}</td>
                                        <td style="text-align:left; vertical-align: middle;">{{$progdtrab->mesp_10}}</td>
                                        <td style="text-align:left; vertical-align: middle;">{{$progdtrab->mesp_11}}</td>
                                        <td style="text-align:left; vertical-align: middle;">{{$progdtrab->mesp_12}}</td>

                                        @if($progdtrab->dstatus_1 == 'S')
                                            <td style="color:darkgreen;text-align:center; vertical-align: middle;" title="Activo"><i class="fa fa-check"></i>
                                            </td>                                            
                                        @else
                                            <td style="color:darkred; text-align:center; vertical-align: middle;" title="Inactivo"><i class="fa fa-times"></i>
                                            </td>                                            
                                        @endif
                                        
                                        <td style="text-align:center;">
                                            <a href="{{route('editarProgdtrab',array($progdtrab->folio,$progdtrab->partida) )}}" class="btn badge-warning" title="Editar actividad del programa de trabajo"><i class="fa fa-edit"></i>
                                            </a>
                                            <a href="{{route('borrarProgdtrab',array($progdtrab->folio,$progdtrab->partida) )}}" class="btn badge-danger" title="Borrar actividad del programa de trabajo" onclick="return confirm('¿Seguro que desea borrar actividad del programa de trabajo?')"><i class="fa fa-times"></i>
                                            </a>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>

                            <table id="tabla1" class="table table-hover table-striped">
                                <tr>
                                @foreach($regprogtrab as $progtrab)
                                    <td style="text-align:left; vertical-align: middle;">
                                        <label>Elaboró: </label>{{$progtrab->elaboro}}   
                                    </td>
                                    <td style="text-align:right; vertical-align: middle;">   
                                        <label>Autorizo: </label>{{$progtrab->autorizo}}
                                    </td>                                     
                                </tr>                                 
                                @endforeach
                            </table>

                            {!! $regprogdtrab->appends(request()->input())->links() !!}
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

