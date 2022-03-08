@extends('sicinar.principal')

@section('title','Ver padrón de beneficiarios')

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
                <small>2. Juagadores del equipo   </small>
                <small> - Seleccionar para editar </small>
            </h1>
            <ol class="breadcrumb">
                <li><img src="{{ asset('images/b2.jpg') }}" border="0" width="30" height="30">Jugadores del equipo</li> 
            </ol>            
        </section>
        <section class="content"> 
            <div class="row">
                <div class="col-md-12">
                    <div class="box">
                        <p align="justify">
                        <b style="color:red;">DATOS PERSONALES:</b> 
                        <b style="color:green;">De cada persona joven participante.<br>
                        Serán máximo 11 registros: 10 jugadores y 1 representante, siendo obligatorios los datos personales 
                        (un representante y mínimo 7 jugadores). El representante puede ser un mismo jugador y en este caso 
                        se debe contar dentro del número mínimo y máximo del total de jugadores del equipo).</b>
                        </p>              
                        <div class="page-header" style="text-align:right;">
                            @if(session()->get('rango') == '4')                          
                            {{ Form::open(['route' => 'buscarpadron', 'method' => 'GET', 'class' => 'form-inline pull-right']) }}
                                <div class="form-group">
                                    {{ Form::text('nameiap', null, ['class' => 'form-control', 'placeholder' => 'Nombre equipo']) }}
                                </div>                                                             
                                <div class="form-group">
                                    {{ Form::text('name', null, ['class' => 'form-control', 'placeholder' => 'Nombre del jugador']) }}
                                </div>
                                <div class="form-group">
                                    <button type="submit" class="btn btn-default">
                                        <span class="glyphicon glyphicon-search"></span>
                                    </button>
                                </div>
                                <div class="form-group">
                                    <a href="{{route('exportpadronexcel')}}" class="btn btn-success" title="Exportar jugadores del equipo a formato Excel)"><i class="fa fa-file-excel-o"></i> Excel</a>                            
                                    <a href="{{route('nuevopadron')}}" class="btn btn-primary btn_xs" title="Nuevo jugador"><i class="fa fa-file-new-o"></i><span class="glyphicon glyphicon-plus"></span>Nuevo jugador del equipo</a>
                                </div>                                
                            {{ Form::close() }}
                            @endif
                        </div>

                        <div class="box-body">
                            <table id="tabla1" class="table table-hover table-striped">
                                <thead style="color: brown;" class="justify">
                                    <tr>
                                        <th style="text-align:left;   vertical-align: middle;">Folio          </th>
                                        <th style="text-align:left;   vertical-align: middle;">Equipo         </th>
                                        <th style="text-align:left;   vertical-align: middle;">Jugador        </th>
                                        <th style="text-align:left;   vertical-align: middle;">Edad           </th> 
                                        <th style="text-align:left;   vertical-align: middle;">Municipio      </th>                                         
                                        <th style="text-align:left;   vertical-align: middle;">Arc.  <br>Doctos.</th>
                                        <th style="text-align:center; vertical-align: middle;">Activo<br>Baja </th>
                                        
                                        <th style="text-align:center; vertical-align: middle; width:100px;">Acciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($regpadron as $padron)
                                    <tr>
                                        <td style="text-align:left; vertical-align: middle;">{{$padron->folio}}    </td>
                                        <td style="text-align:left; vertical-align: middle;">
                                            {{$padron->eq_id}}   
                                            @foreach($regficha as $ficha)
                                                @if($ficha->eq_id == $padron->eq_id)
                                                    {{trim($ficha->eq_desc)}}
                                                    @break
                                                @endif
                                            @endforeach
                                        </td>                                          
                                        <td style="text-align:left; vertical-align: middle;">{{Trim($padron->nombre_completo)}}
                                        </td>
                                        <td style="text-align:center; vertical-align: middle;">
                                            {{$padron->edad}}    
                                        </td>                                 
                                        <td style="text-align:left; vertical-align: middle;">       
                                        {{$padron->municipio_id}}   
                                        @foreach($regmunicipio as $mun)
                                            @if($mun->municipioid == $padron->municipio_id)
                                                {{Trim($mun->municipio)}}
                                                @break 
                                            @endif
                                        @endforeach                                         
                                        </td>
                                        @if(!empty($padron->arc_1)&&(!is_null($padron->arc_1)))
                                            <td style="color:darkgreen;text-align:center; vertical-align:middle;font-size:10px;" title="Archivo digital de CURP del jugador en formato PDF">
                                                <a href="/storage/{{$padron->arc_1}}" class="btn btn-danger" title="Archivo digital de CURP del jugador en formato PDF"><i class="fa fa-file-pdf-o"></i>
                                                </a>
                                                <a href="{{route('editarpadron1',$padron->folio)}}" class="btn badge-warning" title="Editar Archivo digital de CURP del jugador en formato PDF"><i class="fa fa-edit"></i>
                                                </a>
                                            </td>
                                        @else
                                            <td style="color:darkred; text-align:center; vertical-align: middle;font-size:10px;" title="Archivo digital de CURP del jugador en formato PDF"><i class="fa fa-times"></i>
                                                <a href="{{route('editarpadron1',$padron->folio)}}" class="btn badge-warning" title="Editar Archivo digital de CURP del jugador en formato PDF"><i class="fa fa-edit"></i>
                                                </a>
                                            </td>   
                                        @endif 
                                        @if($padron->status_1 == 'S')
                                            <td style="color:darkgreen;text-align:center; vertical-align: middle;" title="Activo"><i class="fa fa-check"></i>
                                            </td>                                            
                                        @else
                                            <td style="color:darkred; text-align:center; vertical-align: middle;" title="Inactivo"><i class="fa fa-times"></i>
                                            </td>                                            
                                        @endif
                                        
                                        <td style="text-align:center;">
                                            <a href="{{route('editarpadron',$padron->folio)}}" class="btn badge-warning" title="Editar datos del jugador"><i class="fa fa-edit"></i></a>
                                            @if(session()->get('rango') == '4')
                                                <a href="{{route('borrarpadron',$padron->folio)}}" class="btn badge-danger" title="Borrar jugador del equipo" onclick="return confirm('¿Seguro que desea borrar jugador del equipo?')"><i class="fa fa-times"></i></a>
                                            @endif                                                
                                        </td>                                                                                
                                    </tr>
                                    @endforeach
                                    @if(session()->get('rango') == '0')
                                    <tr>
                                            <td style="text-align:left; rowspan=2;">
                                                <a href="{{route('verficha')}}">                                        
                                                <img src="{{ asset('images/b1.jpg') }}"   border="0" width="30" height="30" title="Registro de ficha del equipo">
                                                <img src="{{ asset('images/fant.jpg') }}" border="0" width="30" height="30" title="Anterior">
                                                </a>                                            
                                            </td>
                                            <td style="text-align:left;"  ></td>
                                            <td style="text-align:left;"  ></td>
                                            <td style="text-align:center;"></td>
                                            <td style="text-align:center;"></td>
                                            <td style="text-align:center;"></td>
                                            <td style="text-align:right;" ></td>
                                            <td style="text-align:right;" >
                                                <a href="{{route('verequipo')}}">                                        
                                                <img src="{{ asset('images/fsig.jpg') }}" border="0" width="30" height="30" title="Siguiente concluir registro">
                                                <img src="{{ asset('images/b3.jpg') }}"   border="0" width="30" height="30" title="Concluir registro del equipo en el torneo">
                                                </a>
                                            </td>
                                    </tr>
                                    @endif                                      
                                </tbody>
                            </table>
                            {!! $regpadron->appends(request()->input())->links() !!}
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