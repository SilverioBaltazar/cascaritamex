@extends('sicinar.principal')

@section('title','Ver indicador de cumpliento de las OSCS')

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
                <small> Seleccionar alguna para editar o registrar </small>
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

                        <div class="page-header" style="text-align:right;">
                            <label style="color:green;"><small><i class="fa fa-check"></i>Activas :</small></label>
                            @foreach($regtotactivas as $total_a)
                               <label style="color:green;"><small>{{$total_a->total_activas}}</small></label>
                            @endforeach
                            <label style="color:red;"><small><i class="fa fa-times"></i>  Inactivas :</small></label>
                            @foreach($regtotinactivas as $total_i)
                               <label style="color:red;"><small>{{$total_i->total_inactivas}} </small></label>
                            @endforeach
                            
                            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; 
                            {{ Form::open(['route' => 'buscarcumplimiento', 'method' => 'GET', 'class' => 'form-inline pull-right']) }}
                                <div class="form-group">
                                    {{ Form::text('idd', null, ['class' => 'form-control', 'placeholder' => 'id OSC']) }}
                                </div>
                                <div class="form-group">
                                    {{ Form::text('name', null, ['class' => 'form-control', 'placeholder' => 'Nombre OSC']) }}
                                </div>
                                
                                <div class="form-group">
                                    <button type="submit" class="btn btn-default">
                                        <span class="glyphicon glyphicon-search"></span>
                                    </button>
                                </div>                             
                            {{ Form::close() }}
                        </div>

                        <div class="box-body">
                            <table id="tabla1" class="table table-hover table-striped">
                                <thead style="font-size:10px;" class="justify">
                                    <tr>
                                        <th colspan="1" style="background-color:pink;text-align:center;vertical-align: middle;"></th>
                                        <th colspan="4" style="background-color:darkgreen;text-align:center;"><b style="color:white;font-size: x-small;">Requisitos jurídicos</b>
                                        </th>
                                        <th colspan="3" style="background-color:brown;text-align:center;"><b style="color:white;font-size: x-small;">Requisitos operativos</b>
                                        </th> 
                                        <th colspan="5" style="background-color:darkorange;text-align:center;vertical-align: middle;">Requisitos admon.</th>
                                        <th colspan="1" style="background-color:pink;text-align:center;vertical-align: middle;"></th>
                                    </tr>        
                                
                                    <tr>
                                        
                                        <th style="background-color:pink;text-align:left;   vertical-align: middle;">OSC                 </th>

                                        <th style="background-color:green;color:white;text-align:center;vertical-align: middle;">Acta     <br>Const.   </th>
                                        <th style="background-color:green;color:white;text-align:center;vertical-align: middle;">Reg.     <br>IFREM    </th>
                                        <th style="background-color:green;color:white;text-align:center;vertical-align: middle;">Currículum            </th>   
                                        <th style="background-color:green;color:white;text-align:center; vertical-align: middle;">Ult.     <br>Protocol.</th>

                                        <th style="background-color:brown;color:white;text-align:center; vertical-align: middle;">Padrón   <br>Benef.   </th>
                                        <th style="background-color:brown;color:white;text-align:center; vertical-align: middle;">Prog.    <br>Trabajo  </th>
                                        <th style="background-color:brown;color:white;text-align:center; vertical-align: middle;">Informe  <br>Anual    </th>

                                        <th style="background-color:darkorange;text-align:center; vertical-align: middle;">Presup.  <br>Anual    </th> 
                                        <th style="background-color:darkorange;text-align:center; vertical-align: middle;">Const.   <br>Donativos</th> 
                                        <th style="background-color:darkorange;text-align:center; vertical-align: middle;">Dec.     <br>Anual    </th> 
                                        <th style="background-color:darkorange;text-align:center; vertical-align: middle;">Comp.Ded.<br>Impuestos</th> 
                                        <th style="background-color:darkorange;text-align:center; vertical-align: middle;">Apertura <br>y/o Edo. cta.</th>   

                                        <th style="background-color:pink;text-align:center; vertical-align: middle;">Activa   <br>Inact.   </th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php $i  = 0; ?>
                                    @foreach($regosc as $osc)
                                    <?php $i  = $i + 1; ?>  
                                    <tr>
                                                                        
                                        <td style="tfont-family:'Arial, Helvetica, sans-serif'; font-size:11px; text-align:left; vertical-align: middle;">{{$osc->osc_id.' '.Trim($osc->osc_desc)}}
                                        </td>

                                        <td style="text-align:center; vertical-align: middle;"><small>
                                            @foreach($regjuridico as $rjuridico)
                                                @if($rjuridico->osc_id == $osc->osc_id)
                                                    @if(!empty($rjuridico->osc_d12)&&(!is_null($rjuridico->osc_d12)))
                                                        <a href="/images/{{$rjuridico->osc_d12}}" class="btn btn-danger" title="Acta Constitutiva"><i class="fa fa-file-pdf-o"></i>
                                                        </a>
                                                        <img src="{{ asset('images/semaforo_verde.jpg') }}" width="15px" height="15px" title="Acta Constitutiva" style="text-align:center;margin-right: 15px;vertical-align: middle;"/> 
                                                        @break
                                                    @endif
                                                @endif
                                            @endforeach </small>
                                        </td>
                                        <td style="text-align:center; vertical-align: middle;"><small>
                                            @foreach($regjuridico as $rjuridico)
                                                @if($rjuridico->osc_id == $osc->osc_id)
                                                    @if(!empty($rjuridico->osc_d13)&&(!is_null($rjuridico->osc_d13)))
                                                        <a href="/images/{{$rjuridico->osc_d13}}" class="btn btn-danger" title="Documento de registro en el IFREM"><i class="fa fa-file-pdf-o"></i>
                                                        </a>
                                                        <img src="{{ asset('images/semaforo_verde.jpg') }}" width="15px" height="15px" title="Documento de registro en el IFREM" style="text-align:center;margin-right: 15px;vertical-align: middle;"/> 
                                                        @break
                                                    @endif
                                                @endif
                                            @endforeach </small>
                                        </td>            

                                        <td style="text-align:center; vertical-align: middle;"><small>
                                            @foreach($regjuridico as $rjuridico)
                                                @if($rjuridico->osc_id == $osc->osc_id)
                                                    @if(!empty($rjuridico->osc_d14)&&(!is_null($rjuridico->osc_d14)))
                                                        <a href="/images/{{$rjuridico->osc_d14}}" class="btn btn-danger" title="Curriculum"><i class="fa fa-file-pdf-o"></i>
                                                        </a>
                                                        <img src="{{ asset('images/semaforo_verde.jpg') }}" width="15px" height="15px" title="Curriculum" style="text-align:center;margin-right: 15px;vertical-align: middle;"/> 
                                                        @break 
                                                    @endif
                                                @endif
                                            @endforeach </small>
                                        </td>                                                    
                                        <td style="text-align:center; vertical-align: middle;"><small>
                                            @foreach($regjuridico as $rjuridico)
                                                @if($rjuridico->osc_id == $osc->osc_id)
                                                    @if(!empty($rjuridico->osc_d15)&&(!is_null($rjuridico->osc_d15)))
                                                        <a href="/images/{{$rjuridico->osc_d15}}" class="btn btn-danger" title="Documento de ultima protocolización"><i class="fa fa-file-pdf-o"></i>
                                                        </a>
                                                        <img src="{{ asset('images/semaforo_verde.jpg') }}" width="15px" height="15px" title="Documento de ultima protocolización" style="text-align:center;margin-right: 15px;vertical-align: middle;"/> 
                                                        @break
                                                    @endif
                                                @endif
                                            @endforeach </small>
                                        </td>                                                                                                                        

                                        <td style="text-align:center; vertical-align: middle;"><small>
                                            @foreach($regoperativo as $operativo)
                                                @if($operativo->osc_id == $osc->osc_id)
                                                    @if(!empty($operativo->osc_d1)&&(!is_null($operativo->osc_d1)))
                                                        <a href="/images/{{$operativo->osc_d1}}" class="btn btn-danger" title="Padrón de beneficiarios"><i class="fa fa-file-excel-o"></i>
                                                        </a>
                                                        <img src="{{ asset('images/semaforo_verde.jpg') }}" width="15px" height="15px" title="Padrón de beneficiarios" style="text-align:center;margin-right: 15px;vertical-align: middle;"/> 
                                                        @break
                                                    @endif
                                                @endif
                                            @endforeach </small>
                                        </td>
                                        <td style="text-align:center; vertical-align: middle;"><small>
                                            @foreach($regoperativo as $operativo)
                                                @if($operativo->osc_id == $osc->osc_id)
                                                    @if(!empty($operativo->osc_d2)&&(!is_null($operativo->osc_d2)))
                                                        <a href="/images/{{$operativo->osc_d2}}" class="btn btn-danger" title="Programa de trabajo"><i class="fa fa-file-excel-o"></i>
                                                        </a>
                                                        <img src="{{ asset('images/semaforo_verde.jpg') }}" width="15px" height="15px" title="Programa de trabajo" style="text-align:center;margin-right: 15px;vertical-align: middle;"/> 
                                                        @break
                                                    @endif
                                                @endif
                                            @endforeach </small>
                                        </td>
                                        <td style="text-align:center; vertical-align: middle;"><small>
                                            @foreach($regoperativo as $operativo)
                                                @if($operativo->osc_id == $osc->osc_id)
                                                    @if(!empty($operativo->osc_d3)&&(!is_null($operativo->osc_d3)))
                                                        <a href="/images/{{$operativo->osc_d3}}" class="btn btn-danger" title="Informe anual"><i class="fa fa-file-excel-o"></i>
                                                        </a>
                                                        <img src="{{ asset('images/semaforo_verde.jpg') }}" width="15px" height="15px" title="Informe anual" style="text-align:center;margin-right: 15px;vertical-align: middle;"/> 
                                                        @break
                                                    @endif
                                                @endif
                                            @endforeach </small>
                                        </td>                                        

                                        <td style="text-align:center; vertical-align: middle;"><small>
                                            @foreach($regcontable as $contable)
                                                @if($contable->osc_id == $osc->osc_id)
                                                    @if(!empty($contable->osc_d7)&&(!is_null($contable->osc_d7)))
                                                        <a href="/images/{{$contable->osc_d7}}" class="btn btn-danger" title="Presupuesto anual"><i class="fa fa-file-excel-o"></i>
                                                        </a>
                                                        <img src="{{ asset('images/semaforo_verde.jpg') }}" width="15px" height="15px" title="Presupuesto anual" style="text-align:center;margin-right: 15px;vertical-align: middle;"/> 
                                                        @break
                                                    @endif
                                                @endif
                                            @endforeach </small>
                                        </td>
                                        <td style="text-align:center; vertical-align: middle;"><small>
                                            @foreach($regcontable as $contable)
                                                @if($contable->osc_id == $osc->osc_id)
                                                    @if(!empty($contable->osc_d8)&&(!is_null($contable->osc_d8)))
                                                        <a href="/images/{{$contable->osc_d8}}" class="btn btn-danger" title="Constancia de autorización para recibir donativos"><i class="fa fa-file-pdf-o"></i>
                                                        </a>
                                                        <img src="{{ asset('images/semaforo_verde.jpg') }}" width="15px" height="15px" title="Constancia de autorización para recibir donativos" style="text-align:center;margin-right: 15px;vertical-align: middle;"/> 
                                                        @break 
                                                    @endif
                                                @endif
                                            @endforeach </small>
                                        </td>           
                                        <td style="text-align:center; vertical-align: middle;"><small>
                                            @foreach($regcontable as $contable)
                                                @if($contable->osc_id == $osc->osc_id)
                                                    @if(!empty($contable->osc_d9)&&(!is_null($contable->osc_d9)))
                                                        <a href="/images/{{$contable->osc_d9}}" class="btn btn-danger" title="Declaración anual"><i class="fa fa-file-pdf-o"></i>
                                                        </a>
                                                        <img src="{{ asset('images/semaforo_verde.jpg') }}" width="15px" height="15px" title="Declaración anual" style="text-align:center;margin-right: 15px;vertical-align: middle;"/> 
                                                        @break 
                                                    @endif
                                                @endif
                                            @endforeach </small>
                                        </td>                                                                                                           
                                        <td style="text-align:center; vertical-align: middle;"><small>
                                            @foreach($regcontable as $contable)
                                                @if($contable->osc_id == $osc->osc_id)
                                                    @if(!empty($contable->osc_d10)&&(!is_null($contable->osc_d10)))
                                                        <a href="/images/{{$contable->osc_d10}}" class="btn btn-danger" title="Comprobante deducible de impuestos"><i class="fa fa-file-pdf-o"></i>
                                                        </a>
                                                        <img src="{{ asset('images/semaforo_verde.jpg') }}" width="15px" height="15px" title="Comprobante deducible de impuestos" style="text-align:center;margin-right: 15px;vertical-align: middle;"/> 
                                                        @break
                                                    @endif
                                                @endif
                                            @endforeach </small>
                                        </td>                                                                                                           
                                        <td style="text-align:center; vertical-align: middle;"><small>
                                            @foreach($regcontable as $contable)
                                                @if($contable->osc_id == $osc->osc_id)
                                                    @if(!empty($contable->osc_d11)&&(!is_null($contable->osc_d11)))
                                                        <a href="/images/{{$contable->osc_d11}}" class="btn btn-danger" title="Apertura y/o Edo. cta."><i class="fa fa-file-pdf-o"></i>
                                                        </a>
                                                        <img src="{{ asset('images/semaforo_verde.jpg') }}" width="15px" height="15px" title="Apertura y/o Edo. cta." style="text-align:center;margin-right: 15px;vertical-align: middle;"/> 
                                                        @break
                                                    @endif
                                                @endif
                                            @endforeach </small>
                                        </td>     
                                        @if($osc->osc_status == 'S')
                                            <td style="font-family:'Arial, Helvetica, sans-serif'; font-size:10px; text-align:center; vertical-align: middle;" title="Activo"><i class="fa fa-check"></i>
                                            </td>                                            
                                        @else
                                            <td style="tfont-family:'Arial, Helvetica, sans-serif'; font-size:10px; text-align:center; vertical-align: middle;" title="Inactivo"><i class="fa fa-times"></i>
                                            </td>                                            
                                        @endif
                                                                                
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
