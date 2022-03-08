@extends('sicinar.principal')

@section('title','Ver matriz de cumpliento de las OSCS')

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
                <small> - Matriz de cumplimiento </small>
            </h1>
            <ol class="breadcrumb">
                <li><a href="#"><i class="fa fa-dashboard"></i> Menú</a></li>
                <li><a href="#">Indicadores            </a></li>   
                <li><a href="#">Matriz de cumplimiento </a></li>               
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
                            {{ Form::open(['route' => 'buscarmatrizcump', 'method' => 'GET', 'class' => 'form-inline pull-right']) }}
                                <div class="form-group">
                                    {{ Form::text('idd', null, ['class' => 'form-control', 'placeholder' => 'id.']) }}
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
                                        <th colspan="3" style="background-color:pink;text-align:center;vertical-align: middle;"></th>
                                        <th colspan="4" style="background-color:darkgreen;text-align:center;"><b style="color:white;font-size: x-small;">Requisitos jurídicos</b>
                                        </th>
                                        <th colspan="3" style="background-color:brown;text-align:center;"><b style="color:white;font-size: x-small;">Requisitos operativos</b>
                                        </th> 
                                        <th colspan="5" style="background-color:darkorange;text-align:center;vertical-align: middle;">Requisitos admon.</th>
                                        <th colspan="2" style="background-color:pink;text-align:center;vertical-align: middle;">Cumplimiento</th>
                                    </tr>        

                                    <tr>
                                        <th style="background-color:pink;text-align:left;   vertical-align: middle;">#                    </th>
                                        <th style="background-color:pink;text-align:left;   vertical-align: middle;">OSC                  </th>
                                        <th style="background-color:pink;text-align:center; vertical-align: middle;">Activa   <br>Inact.  </th>

                                        <th style="background-color:green;color:white;text-align:center;vertical-align: middle;">Acta     <br>Const.   </th>
                                        <th style="background-color:green;color:white;text-align:center;vertical-align: middle;">Reg.     <br>IFREM    </th>
                                        <th style="background-color:green;color:white;text-align:center;vertical-align: middle;">Currículum            </th>   
                                        <th style="background-color:green;color:white;text-align:center; vertical-align: middle;">Ult.     <br>Protocol.</th>

                                        <th style="background-color:brown;color:white;text-align:center; vertical-align: middle;">Padrón   <br>Benef.   </th>
                                        <th style="background-color:brown;color:white;text-align:center; vertical-align: middle;">Programa <br>Trabajo  </th>
                                        <th style="background-color:brown;color:white;text-align:center; vertical-align: middle;">Informe  <br>Anual    </th>

                                        <th style="background-color:darkorange;text-align:center; vertical-align: middle;">Presup.  <br>Anual    </th> 
                                        <th style="background-color:darkorange;text-align:center; vertical-align: middle;">Const.   <br>Donativos</th> 
                                        <th style="background-color:darkorange;text-align:center; vertical-align: middle;">Dec.     <br>Anual    </th> 
                                        <th style="background-color:darkorange;text-align:center; vertical-align: middle;">Comp.Ded.<br>Impuestos</th> 
                                        <th style="background-color:darkorange;text-align:center; vertical-align: middle;">Apertura <br>y/o Edo. cta.</th>                                         

                                        <th style="background-color:pink;text-align:center; vertical-align: middle;">Total    </th>
                                        <th style="background-color:pink;text-align:center; vertical-align: middle;">%        </th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php $j1  = 0; ?>
                                    <?php $j2  = 0; ?>
                                    <?php $j3  = 0; ?>
                                    <?php $j4  = 0; ?>
                                    <?php $a1  = 0; ?>
                                    <?php $a2  = 0; ?>
                                    <?php $a3  = 0; ?>
                                    <?php $a4  = 0; ?>
                                    <?php $a5  = 0; ?>
                                    <?php $c1  = 0; ?>
                                    <?php $c2  = 0; ?>
                                    <?php $c3  = 0; ?>
                                    <?php $c4  = 0; ?>
                                    <?php $c5  = 0; ?>
                                    <?php $c6  = 0; ?>                                    
                                    <?php $pj1 = 0; ?>
                                    <?php $pj2 = 0; ?>
                                    <?php $pj3 = 0; ?>
                                    <?php $pj4 = 0; ?>
                                    <?php $pa1 = 0; ?>
                                    <?php $pa2 = 0; ?>
                                    <?php $pa3 = 0; ?>
                                    <?php $pa4 = 0; ?>
                                    <?php $pa5 = 0; ?>
                                    <?php $pc1 = 0; ?>
                                    <?php $pc2 = 0; ?>
                                    <?php $pc3 = 0; ?>
                                    <?php $pc4 = 0; ?>
                                    <?php $pc5 = 0; ?>
                                    <?php $pc6 = 0; ?>
                                    <?php $cr1 = 0; ?>
                                    <?php $cr2 = 0; ?>
                                    <?php $cr3 = 0; ?>
                                    <?php $i   = 0; ?>
                                    <?php $totren = 0; ?>
                                    <?php $totacum= 0; ?>
                                    <?php $porcen = 0; ?>
                                    <?php $sumpor = 0; ?>                                
                                    @foreach($regosc as $osc)
                                        @if($osc->osc_status == 'S')
                                        <?php $r1 = 0; ?>
                                        <?php $r2 = 0; ?>
                                        <?php $r3 = 0; ?>
                                        <?php $r4 = 0; ?>
                                        <?php $m1 = 0; ?>
                                        <?php $m2 = 0; ?>
                                        <?php $m3 = 0; ?>
                                        <?php $m4 = 0; ?>
                                        <?php $m5 = 0; ?>
                                        <?php $n1 = 0; ?>
                                        <?php $n2 = 0; ?>
                                        <?php $n3 = 0; ?>
                                        <?php $n4 = 0; ?>
                                        <?php $n5 = 0; ?>
                                        <?php $n6 = 0; ?>
                                        <?php $i  = $i + 1; ?>  

                                        <tr>
                                        <td style="tfont-family:'Arial, Helvetica, sans-serif'; font-size:11px; text-align:left; vertical-align: middle;">{{$i}}        
                                        </td>
                                        <td style="tfont-family:'Arial, Helvetica, sans-serif'; font-size:11px; text-align:left; vertical-align: middle;">{{Trim($osc->osc_id.' '.$osc->osc_desc)}}
                                        </td>
                                        @if($osc->osc_status == 'S')
                                            <td style="font-family:'Arial, Helvetica, sans-serif'; font-size:10px; text-align:center; vertical-align: middle;" title="Activo"><i class="fa fa-check"></i></td>
                                        @else
                                            <td style="tfont-family:'Arial, Helvetica, sans-serif'; font-size:10px; text-align:center; vertical-align: middle;" title="Inactivo"><i class="fa fa-times"></i></td>
                                        @endif
                                        <td style="text-align:center; vertical-align: middle;"><small>
                                            @foreach($regjuridico as $rjuridico)
                                                @if($rjuridico->osc_id == $osc->osc_id)
                                                    @if(!empty($rjuridico->osc_d12)&&(!is_null($rjuridico->osc_d12)))
                                                        SI
                                                        <?php $r1 = 1; ?>
                                                        <?php $j1 = $j1 + 1; ?>
                                                        @break
                                                    @endif
                                                @endif
                                            @endforeach </small>
                                        </td>
                                        <td style="text-align:center; vertical-align: middle;"><small>
                                            @foreach($regjuridico as $rjuridico)
                                                @if($rjuridico->osc_id == $osc->osc_id)
                                                    @if(!empty($rjuridico->osc_d13)&&(!is_null($rjuridico->osc_d13)))
                                                        SI
                                                        <?php $r2 = 1; ?>
                                                        <?php $j2 = $j2 + 1; ?>
                                                        @break
                                                    @endif
                                                @endif
                                            @endforeach </small>
                                        </td>            

                                        <td style="text-align:center; vertical-align: middle;"><small>
                                            @foreach($regjuridico as $rjuridico)
                                                @if($rjuridico->osc_id == $osc->osc_id)
                                                    @if(!empty($rjuridico->osc_d14)&&(!is_null($rjuridico->osc_d14)))
                                                        SI
                                                        <?php $r3 = 1; ?>
                                                        <?php $j3 = $j3 + 1; ?>
                                                        @break 
                                                    @endif
                                                @endif
                                            @endforeach </small>
                                        </td>                                                    
                                        <td style="text-align:center; vertical-align: middle;"><small>
                                            @foreach($regjuridico as $rjuridico)
                                                @if($rjuridico->osc_id == $osc->osc_id)
                                                    @if(!empty($rjuridico->osc_d15)&&(!is_null($rjuridico->osc_d15)))
                                                        SI
                                                        <?php $r4 = 1; ?>
                                                        <?php $j4 = $j4 + 1; ?>
                                                        @break
                                                    @endif
                                                @endif
                                            @endforeach </small>
                                        </td>                                                                                                                        

                                        <td style="text-align:center; vertical-align: middle;"><small>
                                            @foreach($regoperativo as $operativo)
                                                @if($operativo->osc_id == $osc->osc_id)
                                                    @if(!empty($operativo->osc_d1)&&(!is_null($operativo->osc_d1)))
                                                        SI
                                                        <?php $a1 = 1; ?>
                                                        <?php $m1 = $m1 + 1; ?>
                                                        @break
                                                    @endif
                                                @endif
                                            @endforeach </small>
                                        </td>
                                        <td style="text-align:center; vertical-align: middle;"><small>
                                            @foreach($regoperativo as $operativo)
                                                @if($operativo->osc_id == $osc->osc_id)
                                                    @if(!empty($operativo->osc_d2)&&(!is_null($operativo->osc_d2)))
                                                        SI
                                                        <?php $a2 = 1; ?>
                                                        <?php $m2 = $m2 + 1; ?>
                                                        @break
                                                    @endif
                                                @endif
                                            @endforeach </small>
                                        </td>
                                        <td style="text-align:center; vertical-align: middle;"><small>
                                            @foreach($regoperativo as $operativo)
                                                @if($operativo->osc_id == $osc->osc_id)
                                                    @if(!empty($operativo->osc_d3)&&(!is_null($operativo->osc_d3)))
                                                        SI
                                                        <?php $a3 = 1; ?>
                                                        <?php $m3 = $m3 + 1; ?>
                                                        @break
                                                    @endif
                                                @endif
                                            @endforeach </small>
                                        </td>

                                        <td style="text-align:center; vertical-align: middle;"><small>
                                            @foreach($regcontable as $contable)
                                                @if($contable->osc_id == $osc->osc_id)
                                                    @if(!empty($contable->osc_d7)&&(!is_null($contable->osc_d7)))
                                                        SI
                                                        <?php $n1 = 1; ?>
                                                        <?php $c1 = $c1 + 1; ?>
                                                        @break
                                                    @endif
                                                @endif
                                            @endforeach </small>
                                        </td>
                                        <td style="text-align:center; vertical-align: middle;"><small>
                                            @foreach($regcontable as $contable)
                                                @if($contable->osc_id == $osc->osc_id)
                                                    @if(!empty($contable->osc_d8)&&(!is_null($contable->osc_d8)))
                                                        SI
                                                        <?php $n2 = 1; ?>
                                                        <?php $c2 = $c2 + 1; ?>
                                                        @break 
                                                    @endif
                                                @endif
                                            @endforeach </small>
                                        </td>           
                                        <td style="text-align:center; vertical-align: middle;"><small>
                                            @foreach($regcontable as $contable)
                                                @if($contable->osc_id == $osc->osc_id)
                                                    @if(!empty($contable->osc_d9)&&(!is_null($contable->osc_d9)))
                                                        SI
                                                        <?php $n3 = 1; ?>
                                                        <?php $c3 = $c3 + 1; ?>
                                                        @break 
                                                    @endif
                                                @endif
                                            @endforeach </small>
                                        </td>                                                                                                           
                                        <td style="text-align:center; vertical-align: middle;"><small>
                                            @foreach($regcontable as $contable)
                                                @if($contable->osc_id == $osc->osc_id)
                                                    @if(!empty($contable->osc_d10)&&(!is_null($contable->osc_d10)))
                                                        SI
                                                        <?php $n4 = 1; ?>
                                                        <?php $c4 = $c4 + 1; ?>
                                                        @break
                                                    @endif
                                                @endif
                                            @endforeach </small>
                                        </td>      
                                        <td style="text-align:center; vertical-align: middle;"><small>
                                            @foreach($regcontable as $contable)
                                                @if($contable->osc_id == $osc->osc_id)
                                                    @if(!empty($contable->osc_d11)&&(!is_null($contable->osc_d11)))
                                                        SI
                                                        <?php $n5 = 1; ?>
                                                        <?php $c5 = $c5 + 1; ?>
                                                        @break
                                                    @endif
                                                @endif
                                            @endforeach </small>
                                        </td>                                                    

                                        <?php $totren =$r1+$r2+$r3+$r4 + $m1+$m2+$m3 + $n1+$n2+$n3+$n4+$n5; ?>
                                        <td style="font-size:12px; text-align:center; vertical-align: middle;"><b>{{number_format($totren,0)}}</b></td>
                                        <td style="font-size:12px; text-align:center; vertical-align: middle;"><b>{{number_format(($totren/12)*100,2)}}</b></td>
                                        <?php $porcen = (($totren/12)*100) + $porcen; ?>                                                                                                                        
                                        </tr>
                                        @endif
                                    @endforeach
                                    <?php $totacum=$j1+$j2+$j3+$j4 + $a1+$a2+$a3 + $c1+$c2+$c3+$c4+$c5; ?>
                                    @if($totacum >0 )
                                        <?php $pj1 = ($j1/$totacum)*100 ; ?>   
                                        <?php $pj2 = ($j2/$totacum)*100 ; ?>   
                                        <?php $pj3 = ($j3/$totacum)*100 ; ?>   
                                        <?php $pj4 = ($j4/$totacum)*100 ; ?>   
                                        <?php $pa1 = ($a1/$totacum)*100 ; ?>   
                                        <?php $pa2 = ($a2/$totacum)*100 ; ?>
                                        <?php $pa3 = ($a3/$totacum)*100 ; ?>
                                        <!--
                                        <?php $pa4 = ($a4/$totacum)*100 ; ?>
                                        <?php $pa5 = ($a5/$totacum)*100 ; ?>
                                        -->
                                        <?php $pc1 = ($c1/$totacum)*100 ; ?>
                                        <?php $pc2 = ($c2/$totacum)*100 ; ?>
                                        <?php $pc3 = ($c3/$totacum)*100 ; ?> 
                                        <?php $pc4 = ($c4/$totacum)*100 ; ?>
                                        <?php $pc5 = ($c5/$totacum)*100 ; ?>
                                        <!--
                                        <?php $pc6 = ($c6/$totacum)*100 ; ?> 
                                        -->
                                    @endif                                    
                                    <tr>
                                        <th colspan="3" style="background-color:pink;                  text-align:center;vertical-align: middle;"><b>Total de cumplimiento</b></th>

                                        <th style="background-color:green;color:white;text-align:center;vertical-align: middle;" ><b>{{number_format($j1,0)}}</b></th>
                                        <th style="background-color:green;color:white;text-align:center;vertical-align: middle;" ><b>{{number_format($j2,0)}}</b></th>
                                        <th style="background-color:green;color:white;text-align:center;vertical-align: middle;" ><b>{{number_format($j3,0)}}</b></th>   
                                        <th style="background-color:green;color:white;text-align:center;vertical-align: middle;" ><b>{{number_format($j4,0)}}</b></th>

                                        <th style="background-color:brown;color:white;text-align:center; vertical-align: middle;"><b>{{number_format($a1,0)}}</b></th>
                                        <th style="background-color:brown;color:white;text-align:center; vertical-align: middle;"><b>{{number_format($a2,0)}}</b></th>
                                        <th style="background-color:brown;color:white;text-align:center; vertical-align: middle;"><b>{{number_format($a3,0)}}</b></th>
                                        <!--
                                        <th style="background-color:brown;color:white;text-align:center; vertical-align: middle;"><b>{{number_format($a4,0)}}</b></th>
                                        <th style="background-color:brown;color:white;text-align:center; vertical-align: middle;"><b>{{number_format($a5,0)}}</b></th>
                                        -->

                                        <th style="background-color:darkorange;text-align:center; vertical-align: middle;"><b>{{number_format($c1,0)}}</b></th> 
                                        <th style="background-color:darkorange;text-align:center; vertical-align: middle;"><b>{{number_format($c2,0)}}</b></th> 
                                        <th style="background-color:darkorange;text-align:center; vertical-align: middle;"><b>{{number_format($c3,0)}}</b></th> 
                                        <th style="background-color:darkorange;text-align:center; vertical-align: middle;"><b>{{number_format($c4,0)}}</b></th> 
                                        <th style="background-color:darkorange;text-align:center; vertical-align: middle;"><b>{{number_format($c5,0)}}</b></th> 
                                        <!--
                                        <th style="background-color:darkorange;text-align:center; vertical-align: middle;"><b>{{number_format($c6,0)}}</b></th> 
                                        -->
                                        <th style="background-color:pink;text-align:center; vertical-align: middle;"><b>{{number_format($totacum,0)}} </b></th> 
                                        <th style="background-color:pink;text-align:center; vertical-align: middle;"><b>      </b></th>                                    
                                    </tr>
                                    <tr>
                                        <th colspan="3" style="background-color:pink;text-align:center; vertical-align: middle;" ><b>% </b></th>

                                        <th style="background-color:green;color:white;text-align:center;vertical-align: middle;" ><b>{{number_format($pj1,0)}}</b></th>
                                        <th style="background-color:green;color:white;text-align:center;vertical-align: middle;" ><b>{{number_format($pj2,0)}}</b></th>
                                        <th style="background-color:green;color:white;text-align:center;vertical-align: middle;" ><b>{{number_format($pj3,0)}}</b></th>   
                                        <th style="background-color:green;color:white;text-align:center;vertical-align: middle;" ><b>{{number_format($pj4,0)}}</b></th>

                                        <th style="background-color:brown;color:white;text-align:center; vertical-align: middle;"><b>{{number_format($pa1,0)}}</b></th>
                                        <th style="background-color:brown;color:white;text-align:center; vertical-align: middle;"><b>{{number_format($pa2,0)}}</b></th>
                                        <th style="background-color:brown;color:white;text-align:center; vertical-align: middle;"><b>{{number_format($pa3,0)}}</b></th>
                                        <!--
                                        <th style="background-color:brown;color:white;text-align:center; vertical-align: middle;"><b>{{number_format($pa4,0)}}</b></th>
                                        <th style="background-color:brown;color:white;text-align:center; vertical-align: middle;"><b>{{number_format($pa5,0)}}</b></th>
                                        -->
                                        <th style="background-color:darkorange;text-align:center; vertical-align: middle;"><b>{{number_format($pc1,0)}}</b></th> 
                                        <th style="background-color:darkorange;text-align:center; vertical-align: middle;"><b>{{number_format($pc2,0)}}</b></th> 
                                        <th style="background-color:darkorange;text-align:center; vertical-align: middle;"><b>{{number_format($pc3,0)}}</b></th> 
                                        <th style="background-color:darkorange;text-align:center; vertical-align: middle;"><b>{{number_format($pc4,0)}}</b></th> 
                                        <th style="background-color:darkorange;text-align:center; vertical-align: middle;"><b>{{number_format($pc5,0)}}</b></th> 
                                        <!--
                                        <th style="background-color:darkorange;text-align:center; vertical-align: middle;"><b>{{number_format($pc6,0)}}</b></th> 
                                        -->
                                        @if($totacum >0 )
                                           <?php $sumpor = $pj1+$pj2+$pj3+$pj4 + $pa1+$pa2+$pa3 + $pc1+$pc2+$pc3+$pc4+$pc5; ?>
                                           <?php $cr1    = $pj1+$pj2+$pj3+$pj4     ; ?>
                                           <?php $cr2    = $pa1+$pa2+$pa3          ; ?> 
                                           <?php $cr3    = $pc1+$pc2+$pc3+$pc4+$pc5; ?>
                                        @endif
                                        <th style="background-color:pink;text-align:center; vertical-align: middle;"><b>{{number_format($sumpor,0)}} </b></th> 
                                        <th style="background-color:pink;text-align:center; vertical-align: middle;"><b>   </b></th>                                    
                                    </tr>                                    

                                    <tr>
                                        <th colspan="3" style="background-color:pink;                  text-align:center;vertical-align: middle;"><b>Cumplimiento de Requisitos </b></th>
                                        <th colspan="4" style="background-color:darkgreen; color:white;text-align:center;vertical-align: middle;"><b>Jurídicos = {{number_format($cr1,0)}} %</b></th>
                                        <th colspan="3" style="background-color:brown;     color:white;text-align:center;vertical-align: middle;"><b>Operativos = {{number_format($cr2,0)}} %</b>        </th> 
                                        <th colspan="5" style="background-color:darkorange;color:white;text-align:center;vertical-align: middle;"><b>Administrativos = {{number_format($cr3,0)}}%</b></th>
                                        <th colspan="1" style="background-color:pink;                  text-align:center;vertical-align: middle;"><b>{{number_format($sumpor,0)}} </b></th>
                                        <th colspan="1" style="background-color:pink;                  text-align:center;vertical-align: middle;"></th>
                                    </tr>                                            
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
