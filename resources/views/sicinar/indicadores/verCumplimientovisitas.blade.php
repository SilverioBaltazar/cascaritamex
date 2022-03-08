@extends('sicinar.principal')

@section('title','Ver cumplimiento de visitas de verificación')

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
            <h1>Indicadores
                <small> Seleccionar alguna para editar o visita</small>
            </h1>
            <ol class="breadcrumb">
                <li><a href="#"><i class="fa fa-dashboard"></i> Menú</a></li>
                <li><a href="#">Indicadores        </a></li>   
                <li><a href="#">Cumplimiento       </a></li>               
            </ol>
        </section>
        <section class="content">
            <div class="row">
                <div class="col-md-12">
                    <div class="box">

                        <div class="box-header" style="text-align:right;">
                            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; 
                            {{ Form::open(['route' => 'buscarcumplimientovisitas', 'method' => 'GET', 'class' => 'form-inline pull-right']) }}
                                <div class="form-group"> Periodo
                                    <!--{{ Form::text('fper', null, ['class' => 'form-control', 'placeholder' => 'Periodo','maxlength' => '10']) }}
                                    {!! Form::label('fper','osc') !!} -->
                                    <!--<option value=""> --Seleccionar periodo-- </option> -->
                                    <select class="form-control m-bot15" name="fper" id="fper" class="form-control">
                                        <option value=""> </option> 
                                        @foreach($regperiodos as $periodo)
                                            <option value="{{$periodo->periodo_id}}">{{trim($periodo->periodo_desc)}}</option>
                                        @endforeach   
                                    </select>
                                </div>
                                <div class="form-group">Mes
                                    <!--{{ Form::text('fmes', null, ['class' => 'form-control', 'placeholder' => 'Mes','maxlength' => '10']) }}  -->
                                    <!--<option value=""> --Seleccionar periodo-- </option> -->
                                    <select class="form-control m-bot15" name="fmes" id="fmes" class="form-control">
                                        <option value=""> </option> 
                                        @foreach($regmeses as $mes)
                                            <option value="{{$mes->mes_id}}">{{trim($mes->mes_desc)}}</option>
                                        @endforeach   
                                    </select>
                                </div>                                
                                <div class="form-group">osc
                                    <!--{{ Form::text('fosc', null, ['class' => 'form-control', 'placeholder' => 'osc','maxlength' => '10']) }}-->
                                    <select class="form-control m-bot15" name="fosc" id="fosc" class="form-control">
                                        <option value=""> </option>
                                        @foreach($regosc as $osc)
                                            <option value="{{$osc->osc_id}}">{{substr($osc->osc_desc,1,50)}}</option>
                                        @endforeach   
                                    </select>
                                </div>
                                <!--
                                <div class="form-group">
                                    {{ Form::text('bio', null, ['class' => 'form-control', 'placeholder' => 'Concepto']) }}
                                </div>
                                -->
                                <div class="form-group">
                                    <button type="submit" class="btn btn-default">
                                    <span class="glyphicon glyphicon-search"></span>
                                    </button>
                                </div>
                            {{ Form::close() }}
                        </div>

                        <div class="box-body">
                            <table id="tabla1" class="table table-hover table-striped">
                                <thead style="color: brown;" class="justify">
                                    <tr>
                                        <th style="text-align:center; vertical-align: middle;">Per.       </th>
                                        <th style="text-align:center; vertical-align: middle;">Mes        </th>
                                        <th style="text-align:center; vertical-align: middle;">Día        </th>
                                        <th style="text-align:center; vertical-align: middle;">Hora       </th>
                                        <th style="text-align:center; vertical-align: middle;">Fol.       </th>
                                        <th style="text-align:left;   vertical-align: middle;">osc        </th>
                                        <th style="text-align:left;   vertical-align: middle;">Domicilio  </th>
                                        <th style="text-align:center; vertical-align: middle;">Tipo       </th>
                                        <th style="text-align:left;   vertical-align: middle;">Objetivo   </th>                                        
                                        <th style="text-align:center; vertical-align: middle;">Edo.       </th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($regprogdil as $program)
                                    <tr>
                                        <td style="font-size:10px; text-align:left; vertical-align: middle;">{{$program->periodo_id}}
                                        </td> 
                                        <td style="font-size:10px; text-align:center; vertical-align: middle;">  
                                            @foreach($regmeses as $mes)
                                                @if($mes->mes_id == $program->mes_id)
                                                    {{$mes->mes_desc}}
                                                    @break
                                                @endif
                                            @endforeach 
                                        </td>                    
                                        <td style="font-size:10px; text-align:center; vertical-align: middle;">
                                            @foreach($regdias as $dia)
                                                @if($dia->dia_id == $program->dia_id)
                                                    {{$dia->dia_desc}}
                                                    @break
                                                @endif
                                            @endforeach 
                                        </td>
                                        <td style="font-size:10px; text-align:center; vertical-align: middle;">
                                            @foreach($reghoras as $hora)
                                                @if($hora->hora_id == $program->hora_id)
                                                    {{$hora->hora_desc}}
                                                    @break
                                                @endif
                                            @endforeach 
                                        </td>
                                        <td style="font-size:10px; text-align:left; vertical-align: middle;">
                                            {{$program->visita_folio}}    
                                        </td>
                                        <td style="font-size:10px; text-align:left; vertical-align: middle;">   
                                            @foreach($regosc as $osc)
                                                @if($osc->osc_id == $program->osc_id)
                                                    {{$osc->osc_desc}}
                                                    @break
                                                @endif
                                            @endforeach
                                        </td>                                        
                                        <td style="font-size:10px; text-align:left; vertical-align: middle;">{{Trim($program->visita_dom)}}</td>

                                        @switch($program->visita__tipo1)
                                        @case(1)
                                            <td style="font-size:10px; text-align:center; vertical-align: middle;">JURÍDICO </td>
                                        @case(2)
                                            <td style="font-size:10px; text-align:center; vertical-align: middle;">OPERACIÓN</td>
                                        @case(3)
                                            <td style="font-size:10px; text-align:center; vertical-align: middle;">ADMON.   </td>
                                        @case(4)
                                            <td style="font-size:10px; text-align:center; vertical-align: middle;">GENERAL  </td>
                                        @default 
                                            <td style="font-size:10px; text-align:center; vertical-align: middle;">SIN ESPECIFICAR</td>
                                        @endswitch    

                                        <td style="font-size:08px; text-align:left; vertical-align: middle;">
                                            {{Trim($program->visita_obj).' '.Trim($program->visita_obs3)}}
                                        </td>
                                        @switch($program->visita_edo)
                                        @case(0)  <!-- amarillo -->
                                            <td style="text-align:center;">
                                                 <img src="{{ asset('images/semaforo_amarillo.jpg') }}" width="15px" height="15px" title="En proceso" style="text-align:center;margin-right: 15px;vertical-align: middle;"/> 
                                            </td>
                                            @break
                                        @case(1)  <!-- cerrada -->
                                            <td style="text-align:center;">
                                                <img src="{{ asset('images/semaforo_verde.jpg') }}" width="15px" height="15px" title="Cerrada" style="text-align:center;margin-right: 15px;vertical-align: middle;"/>    
                                            </td>
                                            @break
                                        @case(2)
                                            <td style="text-align:center;">
                                                <img src="{{ asset('images/semaforo_rojo.jpg') }}" width="15px" height="15px" title="Cancelada" style="text-align:center;margin-right: 15px;vertical-align: middle;"/>
                                            </td>
                                            @break 
                                        @default 
                                            <td style="text-align:center;"> 
                                                <img src="{{ asset('images/semaforo_rojo.jpg') }}" width="15px" height="15px" title="Cancelada" style="text-align:center;margin-right: 15px;vertical-align: middle;"/> 
                                            </td>                                                                                  
                                        @endswitch
                                        
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                            {!! $regprogdil->appends(request()->input())->links() !!}
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
