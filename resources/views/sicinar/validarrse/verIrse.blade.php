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
            <h1><i class="fa fa-dashboard"></i>&nbsp;&nbsp;Menú.
                <small>Inscrip. al RSE  </small>
                <small> - Validar y autorizar </small>
            </h1>
            <ol class="breadcrumb">
                <li>&nbsp;&nbsp;Validar y autorizar </li> 
            </ol>            
        </section>
        <section class="content">
            <div class="row">
                <div class="col-md-12">
                    <div class="box">

                        <div class="page-header" style="text-align:right;">
                            {{ Form::open(['route' => 'buscarirse', 'method' => 'GET', 'class' => 'form-inline pull-right']) }}
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
                                        <th colspan="4" style="background-color:pink;text-align:center;vertical-align: middle;">Validar y autorizar Inscrip. RSE</th>
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

                                        <th style="background-color:pink;text-align:center; vertical-align: middle;">Semáforo   <br>Edo.        </th>
                                        <th style="background-color:pink;text-align:center; vertical-align: middle;">Generar Of.<br>Incrip. RSE </th>                                        
                                        <th style="background-color:pink;text-align:center; vertical-align: middle;">Oficio     <br>Incrip. RSE </th>
                                        <th style="background-color:pink;text-align:center; vertical-align: middle;">Acciones                   </th>
                                    </tr>
                                </thead> 
                                <tbody>
                                    <?php $i   = 0; ?>
                                    <?php $r01 = 0; ?>
                                    <?php $r02 = 0; ?>
                                    <?php $r03 = 0; ?>
                                    <?php $r04 = 0; ?>
                                    <?php $r05 = 0; ?>
                                    <?php $r06 = 0; ?>
                                    <?php $r07 = 0; ?>
                                    <?php $r08 = 0; ?>
                                    <?php $r09 = 0; ?>
                                    <?php $r10 = 0; ?>
                                    <?php $r11 = 0; ?>
                                    <?php $r12 = 0; ?>
                                    <?php $tot = 0; ?>
                                    @foreach($regosc as $osc)
                                    <?php $i  = $i + 1; ?>  
                                    <tr>
                                                                        
                                        <td style="tfont-family:'Arial, Helvetica, sans-serif'; font-size:11px; text-align:left; vertical-align: middle;">{{$osc->osc_id.' '.Trim($osc->osc_desc)}}
                                        </td>

                                        <td style="text-align:center; vertical-align: middle;"><small>
                                            @foreach($regjuridico as $rjuridico)
                                                @if($rjuridico->osc_id == $osc->osc_id)
                                                    @if(!empty($rjuridico->osc_d12)&&(!is_null($rjuridico->osc_d12)))
                                                        <?php $r01 = $r01 + 1; ?>  
                                                        <a href="/images/{{$rjuridico->osc_d12}}" class="btn btn-danger" title="Acta Constitutiva"><i class="fa fa-file-pdf-o"></i>
                                                        </a>
                                                        @break
                                                    @else                                                        
                                                        <img src="{{ asset('images/signoadm2.jpg') }}" width="20px" height="20px" title="Acta Constitutiva" style="text-align:center;margin-right: 15px;vertical-align: middle;"/> 
                                                        @break
                                                    @endif
                                                @endif
                                            @endforeach 
                                            @if( $r01 == 0)
                                                <img src="{{ asset('images/signoadm2.jpg') }}" width="20px" height="20px" title="Acta Constitutiva" style="text-align:center;margin-right: 15px;vertical-align: middle;"/> 
                                            @endif                                            
                                            </small>
                                        </td>
                                        <td style="text-align:center; vertical-align: middle;"><small>
                                            @foreach($regjuridico as $rjuridico)
                                                @if($rjuridico->osc_id == $osc->osc_id)
                                                    @if(!empty($rjuridico->osc_d13)&&(!is_null($rjuridico->osc_d13)))
                                                        <?php $r02 = $r02 + 1; ?>  
                                                        <a href="/images/{{$rjuridico->osc_d13}}" class="btn btn-danger" title="Documento de registro en el IFREM"><i class="fa fa-file-pdf-o"></i>
                                                        </a>
                                                        @break
                                                    @else                                                        
                                                        <img src="{{ asset('images/signoadm2.jpg') }}" width="20px" height="20px" title="Documento de registro en el IFREM" style="text-align:center;margin-right: 15px;vertical-align: middle;"/> 
                                                        @break
                                                    @endif
                                                @endif
                                            @endforeach 
                                            @if( $r02 == 0)
                                                <img src="{{ asset('images/signoadm2.jpg') }}" width="20px" height="20px" title="Acta Constitutiva" style="text-align:center;margin-right: 15px;vertical-align: middle;"/> 
                                            @endif                                            
                                            </small>
                                        </td>            

                                        <td style="text-align:center; vertical-align: middle;"><small>
                                            @foreach($regjuridico as $rjuridico)
                                                @if($rjuridico->osc_id == $osc->osc_id)
                                                    @if(!empty($rjuridico->osc_d14)&&(!is_null($rjuridico->osc_d14)))
                                                        <?php $r03 = $r03 + 1; ?>  
                                                        <a href="/images/{{$rjuridico->osc_d14}}" class="btn btn-danger" title="Curriculum"><i class="fa fa-file-pdf-o"></i>
                                                        </a>
                                                        @break
                                                    @else                                                        
                                                        <img src="{{ asset('images/signoadm2.jpg') }}" width="20px" height="20px" title="Curriculum" style="text-align:center;margin-right: 15px;vertical-align: middle;"/> 
                                                        @break 
                                                    @endif
                                                @endif
                                            @endforeach 
                                            @if( $r03 == 0)
                                                <img src="{{ asset('images/signoadm2.jpg') }}" width="20px" height="20px" title="Acta Constitutiva" style="text-align:center;margin-right: 15px;vertical-align: middle;"/> 
                                            @endif                                            
                                            </small>
                                        </td>                                                    
                                        <td style="text-align:center; vertical-align: middle;"><small>
                                            @foreach($regjuridico as $rjuridico)
                                                @if($rjuridico->osc_id == $osc->osc_id)
                                                    @if(!empty($rjuridico->osc_d15)&&(!is_null($rjuridico->osc_d15)))
                                                        <?php $r04 = $r04 + 1; ?>  
                                                        <a href="/images/{{$rjuridico->osc_d15}}" class="btn btn-danger" title="Documento de ultima protocolización"><i class="fa fa-file-pdf-o"></i>
                                                        </a>
                                                        @break
                                                    @else                                                        
                                                        <img src="{{ asset('images/signoadm2.jpg') }}" width="20px" height="20px" title="Curriculum" style="text-align:center;margin-right: 15px;vertical-align: middle;"/> 
                                                        @break                                                         
                                                    @endif
                                                @endif
                                            @endforeach
                                            @if( $r04 == 0)
                                                <img src="{{ asset('images/signoadm2.jpg') }}" width="20px" height="20px" title="Acta Constitutiva" style="text-align:center;margin-right: 15px;vertical-align: middle;"/> 
                                            @endif                                            
                                            </small>
                                        </td>                                                                                                                        

                                        <td style="text-align:center; vertical-align: middle;"><small>
                                            @foreach($regoperativo as $operativo)
                                                @if($operativo->osc_id == $osc->osc_id)
                                                    @if(!empty($operativo->osc_d1)&&(!is_null($operativo->osc_d1)))
                                                        <?php $r05 = $r05 + 1; ?>  
                                                        <a href="/images/{{$operativo->osc_d1}}" class="btn btn-danger" title="Padrón de beneficiarios"><i class="fa fa-file-pdf-o"></i>
                                                        </a>
                                                        @break
                                                    @else                                                        
                                                        <img src="{{ asset('images/signoadm2.jpg') }}" width="20px" height="20px" title="Curriculum" style="text-align:center;margin-right: 15px;vertical-align: middle;"/> 
                                                        @break                                                         
                                                    @endif
                                                @endif
                                            @endforeach
                                            @if( $r05 == 0)
                                                <img src="{{ asset('images/signoadm2.jpg') }}" width="20px" height="20px" title="Acta Constitutiva" style="text-align:center;margin-right: 15px;vertical-align: middle;"/> 
                                            @endif                                            
                                            </small>
                                        </td>
                                        <td style="text-align:center; vertical-align: middle;"><small>
                                            @foreach($regoperativo as $operativo)
                                                @if($operativo->osc_id == $osc->osc_id)
                                                    @if(!empty($operativo->osc_d2)&&(!is_null($operativo->osc_d2)))
                                                        <?php $r06 = $r06 + 1; ?>  
                                                        <a href="/images/{{$operativo->osc_d2}}" class="btn btn-danger" title="Programa de trabajo"><i class="fa fa-file-pdf-o"></i>
                                                        </a>
                                                        @break
                                                    @else                                                        
                                                        <img src="{{ asset('images/signoadm2.jpg') }}" width="20px" height="20px" title="Programa de trabajo" style="text-align:center;margin-right: 15px;vertical-align: middle;"/> 
                                                        @break
                                                    @endif
                                                @endif
                                            @endforeach
                                            @if( $r06 == 0)
                                                <img src="{{ asset('images/signoadm2.jpg') }}" width="20px" height="20px" title="Acta Constitutiva" style="text-align:center;margin-right: 15px;vertical-align: middle;"/> 
                                            @endif                                            
                                            </small>
                                        </td>

                                        <td style="text-align:center; vertical-align: middle;"><small>
                                            @foreach($regoperativo as $operativo)
                                                @if($operativo->osc_id == $osc->osc_id)
                                                    @if(!empty($operativo->osc_d3)&&(!is_null($operativo->osc_d3)))
                                                        <?php $r07 = $r07 + 1; ?>  
                                                        <a href="/images/{{$operativo->osc_d3}}" class="btn btn-danger" title="Informe anual"><i class="fa fa-file-pdf-o"></i>
                                                        </a>
                                                        @break
                                                    @else                                                        
                                                        <img src="{{ asset('images/signoadm2.jpg') }}" width="20px" height="20px" title="Informe anual" style="text-align:center;margin-right: 15px;vertical-align: middle;"/> 
                                                        @break
                                                    @endif
                                                @endif
                                            @endforeach 
                                            @if( $r07 == 0)
                                                <img src="{{ asset('images/signoadm2.jpg') }}" width="20px" height="20px" title="Acta Constitutiva" style="text-align:center;margin-right: 15px;vertical-align: middle;"/> 
                                            @endif                                            
                                            </small>
                                        </td>                                        

                                        <td style="text-align:center; vertical-align: middle;"><small>
                                            @foreach($regcontable as $contable)
                                                @if($contable->osc_id == $osc->osc_id)
                                                    @if(!empty($contable->osc_d7)&&(!is_null($contable->osc_d7)))
                                                        <?php $r08 = $r08 + 1; ?>  
                                                        <a href="/images/{{$contable->osc_d7}}" class="btn btn-danger" title="Presupuesto anual"><i class="fa fa-file-pdf-o"></i>
                                                        </a>
                                                        @break
                                                    @else                                                        
                                                        <img src="{{ asset('images/signoadm2.jpg') }}" width="20px" height="20px" title="Presupuesto anual" style="text-align:center;margin-right: 15px;vertical-align: middle;"/> 
                                                        @break
                                                    @endif
                                                @endif
                                            @endforeach
                                            @if( $r08 == 0)
                                                <img src="{{ asset('images/signoadm2.jpg') }}" width="20px" height="20px" title="Acta Constitutiva" style="text-align:center;margin-right: 15px;vertical-align: middle;"/> 
                                            @endif                                            
                                            </small>
                                        </td>
                                        <td style="text-align:center; vertical-align: middle;"><small>
                                            @foreach($regcontable as $contable)
                                                @if($contable->osc_id == $osc->osc_id)
                                                    @if(!empty($contable->osc_d8)&&(!is_null($contable->osc_d8)))
                                                        <?php $r09 = $r09 + 1; ?>  
                                                        <a href="/images/{{$contable->osc_d8}}" class="btn btn-danger" title="Constancia de autorización para recibir donativos"><i class="fa fa-file-pdf-o"></i>
                                                        </a>
                                                        @break
                                                    @else                                                        
                                                        <img src="{{ asset('images/signoadm2.jpg') }}" width="20px" height="20px" title="Constancia de autorización para recibir donativos" style="text-align:center;margin-right: 15px;vertical-align: middle;"/> 
                                                        @break 
                                                    @endif
                                                @endif
                                            @endforeach
                                            @if( $r09 == 0)
                                                <img src="{{ asset('images/signoadm2.jpg') }}" width="20px" height="20px" title="Acta Constitutiva" style="text-align:center;margin-right: 15px;vertical-align: middle;"/> 
                                            @endif                                            
                                            </small>
                                        </td>           
                                        <td style="text-align:center; vertical-align: middle;"><small>
                                            @foreach($regcontable as $contable)
                                                @if($contable->osc_id == $osc->osc_id)
                                                    @if(!empty($contable->osc_d9)&&(!is_null($contable->osc_d9)))
                                                        <?php $r10 = $r10 + 1; ?>  
                                                        <a href="/images/{{$contable->osc_d9}}" class="btn btn-danger" title="Declaración anual"><i class="fa fa-file-pdf-o"></i>
                                                        </a>
                                                        @break
                                                    @else                                                        
                                                        <img src="{{ asset('images/signoadm2.jpg') }}" width="20px" height="20px" title="Declaración anual" style="text-align:center;margin-right: 15px;vertical-align: middle;"/> 
                                                        @break 
                                                    @endif
                                                @endif
                                            @endforeach 
                                            @if( $r10 == 0)
                                                <img src="{{ asset('images/signoadm2.jpg') }}" width="20px" height="20px" title="Acta Constitutiva" style="text-align:center;margin-right: 15px;vertical-align: middle;"/> 
                                            @endif                                            
                                            </small>
                                        </td>                                                                                                           
                                        <td style="text-align:center; vertical-align: middle;"><small>
                                            @foreach($regcontable as $contable)
                                                @if($contable->osc_id == $osc->osc_id)
                                                    @if(!empty($contable->osc_d10)&&(!is_null($contable->osc_d10)))
                                                        <?php $r11 = $r11 + 1; ?>  
                                                        <a href="/images/{{$contable->osc_d10}}" class="btn btn-danger" title="Comprobante deducible de impuestos"><i class="fa fa-file-pdf-o"></i>
                                                        </a>
                                                        @break
                                                    @else                                                        
                                                        <img src="{{ asset('images/signoadm2.jpg') }}" width="20px" height="20px" title="Comprobante deducible de impuestos" style="text-align:center;margin-right: 15px;vertical-align: middle;"/> 
                                                        @break
                                                    @endif
                                                @endif
                                            @endforeach
                                            @if( $r11 == 0)
                                                <img src="{{ asset('images/signoadm2.jpg') }}" width="20px" height="20px" title="Acta Constitutiva" style="text-align:center;margin-right: 15px;vertical-align: middle;"/> 
                                            @endif                                            
                                            </small>
                                        </td>                                                                                                           
                                        <td style="text-align:center; vertical-align: middle;"><small>
                                            @foreach($regcontable as $contable)
                                                @if($contable->osc_id == $osc->osc_id)
                                                    @if(!empty($contable->osc_d11)&&(!is_null($contable->osc_d11)))
                                                        <?php $r12 = $r12 + 1; ?>  
                                                        <a href="/images/{{$contable->osc_d11}}" class="btn btn-danger" title="Apertura y/o Edo. cta."><i class="fa fa-file-pdf-o"></i>
                                                        </a>
                                                        @break
                                                    @else
                                                        <img src="{{ asset('images/signoadm2.jpg') }}" width="20px" height="20px" title="Apertura y/o Edo. cta." style="text-align:center;margin-right: 15px;vertical-align: middle;"/> 
                                                        @break
                                                    @endif
                                                @endif
                                            @endforeach 
                                            @if( $r12 == 0)
                                                <img src="{{ asset('images/signoadm2.jpg') }}" width="20px" height="20px" title="Acta Constitutiva" style="text-align:center;margin-right: 15px;vertical-align: middle;"/> 
                                            @endif                                            
                                            </small>
                                        </td>     
                                        <?php $tot = $r01+$r02+$r03+$r04+$r05+$r06+$r07+$r08+$r09+$r10+$r11+$r12 ; ?>  

                                        @if( ($r01==1)&&($r02==1)&&($r03==1)&&($r04==1)&&($r06==1)&&($r07==1)&&($r12==1) )
                                            @foreach($regtotalauto as $auto)  
                                                @if($auto->total  == 0)
                                                    <td style="text-align:center;"><small>
                                                        <img src="{{ asset('images/semaforo_rojo.jpg') }}" width="15px" height="15px" title="Sin cumplir requisitos" style="text-align:center;margin-right: 15px;vertical-align: middle;"/></a>
                                                    </td></small>                                           
                                                    <td style="text-align:center;"><small>
                                                    @foreach($regcontable as $contable)
                                                        @if($contable->osc_id == $osc->osc_id)
                                                            <a href="{{route('irsePDF',array($contable->periodo_id, $contable->osc_id) )}}" class="btn btn-danger" title="Oficio de incripción al RSE en formato PDF"><i class="fa fa-file-pdf-o"></i>
                                                                <small> PDF</small>
                                                            </a>
                                                            @break
                                                        @endif
                                                    @endforeach
                                                    </td></small>                                                                                          
                                                    <td style="text-align:center;"><small>
                                                        <a href="{{route('nuevoValrse')}}" class="btn btn-primary btn_xs" title="Validar requisitos 1ra vez"><i class="fa fa-file-new-o"></i><span class="glyphicon glyphicon-plus"></span><small>Validar</small></a>
                                                    </td></small>                                                            
                                                    @break                                                                                           
                                                @else                                       
                                                    @foreach($regautorizados as $autorizado)  
                                                        @if($autorizado->osc_id == $osc->osc_id)
                                                            <td style="text-align:center;">
                                                            @switch($autorizado->osc_edo1) 
                                                            @case('P')  <!-- amarillo -->
                                                                    <img src="{{ asset('images/semaforo_amarillo.jpg') }}" width="15px" height="15px" title="Pendiente" style="text-align:center;margin-right: 15px;vertical-align: middle;"/> 
                                                                @break
                                                            @case('A')  <!-- verde -->
                                                                    <img src="{{ asset('images/semaforo_verde.jpg') }}" width="15px" height="15px" title="Autorizado" style="text-align:center;margin-right: 15px;vertical-align: middle;"/>    
                                                                @break
                                                            @case('N')   <!-- Rojo -->
                                                                    <img src="{{ asset('images/semaforo_rojo.jpg') }}" width="15px" height="15px" title="Sin autorizar" style="text-align:center;margin-right: 15px;vertical-align: middle;"/>
                                                                @break 
                                                            @default 
                                                                    <img src="{{ asset('images/semaforo_rojo.jpg') }}" width="15px" height="15px" title="Sin autorizar" style="text-align:center;margin-right: 15px;vertical-align: middle;"/>                                           
                                                            @endswitch
                                                            </td>                                                            
                                                            <td style="text-align:center;"><small>
                                                                @foreach($regcontable as $contable)
                                                                    @if($contable->osc_id == $osc->osc_id)
                                                                        <a href="{{route('irsePDF',array($contable->periodo_id, $contable->osc_id) )}}" class="btn btn-danger" title="Oficio de incripción al RSE en formato PDF"><i class="fa fa-file-pdf-o"></i>
                                                                        <small>Oficio</small>
                                                                        </a>
                                                                        @break
                                                                    @endif
                                                                @endforeach
                                                            </td></small>      
                                                            @if(!empty($autorizado->osc_d1)&&(!is_null($autorizado->osc_d1)))
                                                                <td style="font-family:'Arial, Helvetica, sans-serif'; font-size:10px; text-align:center; vertical-align: middle;" title="Validar Inscrip. al RSE">
                                                                    <a href="/images/{{$autorizado->osc_d1}}" class="btn btn-success" title="Validar Inscrip. al RSE"><i class="fa fa-file-pdf-o"></i></a>
                                                                    <a href="{{route('editarValrse',$autorizado->osc_folio)}}" class="btn badge-warning" title="Editar Inscrip. al RSE"><i class="fa fa-edit"></i></a>
                                                                </td>
                                                            @else
                                                                <td style="font-family:'Arial, Helvetica, sans-serif'; font-size:10px; text-align:center; vertical-align: middle;" title="Sin archivo digital de validación de Inscrip. al RSE"><i class="fa fa-times"> </i>
                                                                <a href="{{route('editarValrse',$autorizado->osc_folio)}}" class="btn badge-warning" title="Editar Inscrip. al RSE"><i class="fa fa-edit"></i></a>                                                
                                                                </td>   
                                                            @endif
                                                            <td style="text-align:center;">
                                                                <a href="{{route('borrarValrse',$autorizado->osc_folio)}}" class="btn badge-danger" title="Borrar Validación" onclick="return confirm('¿Seguro que desea borrar la Validación de inscripción al RSE?')"><i class="fa fa-times"></i></a>
                                                            </td>                                                                                
                                                        @endif
                                                    @endforeach                                                                              
                                                @endif
                                            @endforeach    
                                        @else
                                            <td style="text-align:center;"><small>
                                                <img src="{{ asset('images/semaforo_rojo.jpg') }}" width="15px" height="15px" title="Sin cumplir requisitos" style="text-align:center;margin-right: 15px;vertical-align: middle;"/></a>
                                            </td></small>                                           
                                            <td style="text-align:center;"><small>  </td></small>    
                                            <td style="text-align:center;"><small>  </td></small>    
                                            <td style="text-align:center;"><small>  </td></small>                                                 
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
