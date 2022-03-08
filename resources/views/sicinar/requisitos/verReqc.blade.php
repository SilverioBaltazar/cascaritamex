@extends('sicinar.principal')

@section('title','Ver requisitos admon.')

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
                <small>4. Requisitos admon.  </small>
                <small> - Seleccionar para editar </small>
            </h1>
            <ol class="breadcrumb">
                <li><img src="{{ asset('images/b4.jpg') }}" border="0" width="30" height="30">Requisitos Admon.</li> 
            </ol>            
        </section>
        <section class="content">
            <div class="row">
                <div class="col-md-12">
                    <div class="box">
                        <p align="justify"><b style="color:red;">
                            Instrucciones:</b> <b style="color:green;">
                            Los requisitos administrativos obligatorios: Apertura y/o Estado de Cuenta Bancario, opcionales: Presupuesto anual, Constancia de recibir donativos, Declaración Anual y 
                            Comprobante Deducible de Impuestos; se deberán subir al sistema de forma anual y actualizar cuando haya algún cambio de estos.
                            Se requiere escanear a una resolución de 300 ppp en blanco y negro.
                            </b>
                        </p>

                        <div class="box-header" style="text-align:right;">
                            {{ Form::open(['route' => 'buscarReqc', 'method' => 'GET', 'class' => 'form-inline pull-right']) }}
                                <div class="form-group">
                                    {{ Form::text('fper', null, ['class' => 'form-control', 'placeholder' => 'Periodo fiscal']) }}
                                </div>
                                <div class="form-group">
                                    {{ Form::text('nameiap', null, ['class' => 'form-control', 'placeholder' => 'Nombre OSC']) }}
                                </div>                                           
                                <div class="form-group"> 
                                    <button type="submit" class="btn btn-default">
                                        <span class="glyphicon glyphicon-search"></span>
                                    </button>
                                </div>
                                <div class="form-group">
                                    <a href="{{route('nuevoReqc')}}" class="btn btn-primary btn_xs" title="Registrar Requisitos admon."><i class="fa fa-file-new-o"></i><span class="glyphicon glyphicon-plus"></span>Registrar requisitos admon.</a>
                                </div>                                
                            {{ Form::close() }}                            
                        </div>  

                        <div class="box-body">
                            <table id="tabla1" class="table table-striped table-bordered table-sm">
                                <thead style="color: brown;" class="justify">
                                    <tr>
                                        <th style="text-align:left;   vertical-align: middle;font-size:11px;">Periodo          </th>
                                        <th style="text-align:left;   vertical-align: middle;font-size:11px;">Folio            </th>  
                                        <th style="text-align:left;   vertical-align: middle;font-size:11px;">OSC              </th>
                                        <th style="text-align:center; vertical-align: middle;font-size:11px;">Presup.<br>Anual </th>
                                        <th style="text-align:center; vertical-align: middle;font-size:11px;">Const.<br>Recibir<br>Donativos</th>
                                        <th style="text-align:center; vertical-align: middle;font-size:11px;">Declaración<br>Anual </th>
                                        <th style="text-align:center; vertical-align: middle;font-size:11px;">Comprobación<br>Deducibles <br>de impuestos</th>
                                        <th style="text-align:center; vertical-align: middle;font-size:11px;">Apertura y/o<br>Edo. cta.</th>
                                        <th style="text-align:center; vertical-align: middle;font-size:11px; width:100px;">Acciones</th>
                                    </tr>
                                </thead>

                                <tbody>
                                    @foreach($regcontable as $contable)
                                    <tr>
                                        <td style="text-align:left; vertical-align: middle;font-size:11px;">{{$contable->periodo_id}}</td>
                                        <td style="text-align:left; vertical-align: middle;font-size:11px;">{{$contable->osc_folio}} </td>
                                        <td style="text-align:left; vertical-align: middle;font-size:11px;">
                                            @foreach($regosc as $osc)
                                                @if($osc->osc_id == $contable->osc_id)
                                                    {{$osc->osc_id.' '.Trim($osc->osc_desc)}} 
                                                    @break
                                                @endif
                                            @endforeach    
                                        </td>  
                                        @if(!empty($contable->osc_d7)&&(!is_null($contable->osc_d7)))
                                            <td style="color:darkgreen;text-align:center; vertical-align:middle;font-size:11px;" title="Presupuesto anual">
                                                <a href="/images/{{$contable->osc_d7}}" class="btn btn-danger" title="Presupuesto anual"><i class="fa fa-file-pdf-o"></i>
                                                </a>
                                                <a href="{{route('editarReqc7',$contable->osc_folio)}}" class="btn badge-warning" title="Editar Presupuesto anual"><i class="fa fa-edit"></i>
                                                </a>
                                            </td>
                                        @else
                                            <td style="color:darkred; text-align:center; vertical-align: middle;font-size:11px;" title="Presupuesto anual"><i class="fa fa-times"></i>
                                                <a href="{{route('editarReqc7',$contable->osc_folio)}}" class="btn badge-warning" title="Editar Presupuesto anual"><i class="fa fa-edit"></i>
                                                </a>
                                            </td>   
                                        @endif
                                        @if(!empty($contable->osc_d8)&&(!is_null($contable->osc_d8)))
                                            <td style="color:darkgreen;text-align:center; vertical-align: middle;font-size:11px;" title="Contancia para recibir donativos">
                                                <a href="/images/{{$contable->osc_d8}}" class="btn btn-danger" title="Contancia para recibir donativos"><i class="fa fa-file-pdf-o"></i>
                                                </a>
                                                <a href="{{route('editarReqc8',$contable->osc_folio)}}" class="btn badge-warning" title="Editar Contancia para recibir donativos"><i class="fa fa-edit"></i>
                                                </a>
                                            </td>
                                        @else
                                            <td style="color:darkred; text-align:center; vertical-align: middle;font-size:11px;" title="Contancia para recibir donativos"><i class="fa fa-times"></i>
                                                <a href="{{route('editarReqc8',$contable->osc_folio)}}" class="btn badge-warning" title="Editar Contancia para recibir donativos"><i class="fa fa-edit"></i>
                                                </a>
                                            </td>   
                                        @endif
                                        @if(!empty($contable->osc_d9)&&(!is_null($contable->osc_d9)))
                                            <td style="color:darkgreen;text-align:center; vertical-align: middle;font-size:11px;" title="Archivo digital de Declaración Anual en formato PDF">
                                                <a href="/images/{{$contable->osc_d9}}" class="btn btn-danger" title="Declaración anual"><i class="fa fa-file-pdf-o"></i>
                                                </a>
                                                <a href="{{route('editarReqc9',$contable->osc_folio)}}" class="btn badge-warning" title="Editar Archivo digital de Declaración Anual en formato PDF"><i class="fa fa-edit"></i>
                                                </a>
                                            </td>
                                        @else
                                            <td style="color:darkred; text-align:center; vertical-align: middle;font-size:11px;" title="Archivo digital de Declaración Anual en formato PDF"><i class="fa fa-times"></i>
                                                <a href="{{route('editarReqc9',$contable->osc_folio)}}" class="btn badge-warning" title="Editar archivo digital de Declaración anual en formato PDF"><i class="fa fa-edit"></i>
                                                </a>
                                            </td>   
                                        @endif
                                        @if(!empty($contable->osc_d10)&&(!is_null($contable->osc_d10)))
                                            <td style="color:darkgreen;text-align:center; vertical-align: middle;font-size:11px;" title="Archivo digital de Comprobante deducible de impuestos en formato PDF">
                                                <a href="/images/{{$contable->osc_d10}}" class="btn btn-danger" title="Archivo digital de Comprobante deducible de impuestos en formato PDF"><i class="fa fa-file-pdf-o"></i>
                                                </a>
                                                <a href="{{route('editarReqc10',$contable->osc_folio)}}" class="btn badge-warning" title="Editar Archivo digital de Comprobante deducible de impuestos en formato PDF"><i class="fa fa-edit"></i>
                                                </a>
                                            </td>
                                        @else
                                            <td style="color:darkred; text-align:center; vertical-align: middle;font-size:11px;" title="Archivo digital de Comprobante deducible de impuestos en formato PDF"><i class="fa fa-times"></i>
                                                <a href="{{route('editarReqc10',$contable->osc_folio)}}" class="btn badge-warning" title="Editar Archivo digital de Comprobante deducible de impuestos en formato PDF"><i class="fa fa-edit"></i>
                                                </a>
                                            </td>   
                                        @endif
                                        @if(!empty($contable->osc_d11)&&(!is_null($contable->osc_d11)))
                                            <td style="color:darkgreen;text-align:center; vertical-align: middle;font-size:11px;" title="Archivo digital de Apertura y/o Estado de Cuenta Bancario en formato PDF">
                                                <a href="/images/{{$contable->osc_d11}}" class="btn btn-danger" title="Archivo digital de Apertura y/o Estado de Cuenta Bancario en formato PDF"><i class="fa fa-file-pdf-o"></i>
                                                </a>
                                                <a href="{{route('editarReqc11',$contable->osc_folio)}}" class="btn badge-warning" title="Editar Archivo digital de Apertura y/o Estado de Cuenta Bancario en formato PDF"><i class="fa fa-edit"></i>
                                                </a>
                                            </td>
                                        @else
                                            <td style="color:darkred; text-align:center; vertical-align: middle;font-size:11px;" title="Archivo digital de Apertura y/o Estado de Cuenta Bancario en formato PDF"><i class="fa fa-times"></i>
                                                <a href="{{route('editarReqc11',$contable->osc_folio)}}" class="btn badge-warning" title="Editar Archivo digital de Apertura y/o Estado de Cuenta Bancario en formato PDF"><i class="fa fa-edit"></i>
                                                </a>
                                            </td>   
                                        @endif

                                        <td style="text-align:center; vertical-align: middle;font-size:11px;">
                                            <a href="{{route('editarReqc',$contable->osc_folio)}}" class="btn badge-warning" title="Editar requisitos admon."><i class="fa fa-edit"></i>
                                            </a>
                                            @if(session()->get('rango') == '4')
                                                <a href="{{route('borrarReqc',$contable->osc_folio)}}" class="btn badge-danger" title="Borrar registro" onclick="return confirm('¿Seguro que desea borrar requisitos admon.?')"><i class="fa fa-times"></i>
                                                </a>                                             
                                            @endif 
                                        </td>
                                    </tr>   
                                        @if(session()->get('rango') == '0')
                                        <tr>
                                            <td style="text-align:left; rowspan=2;">
                                                <a href="{{route('verReqop')}}">                                        
                                                <img src="{{ asset('images/b3.jpg') }}"   border="0" width="25" height="30" title="Requisitos operativos">
                                                <img src="{{ asset('images/fant.jpg') }}" border="0" width="30" height="30" title="Anterior requisito">
                                                </a>                                            
                                            </td>
                                            <td style="text-align:left;"></td>
                                            <td style="text-align:center;"></td>
                                            <td style="text-align:center;"></td>
                                            <td style="text-align:center;"></td>
                                            <td style="text-align:center;"></td>
                                            <td style="text-align:center;"></td>                                            
                                            <td style="text-align:right;"></td>
                                            <td style="text-align:right; ">
                                                @if(session()->get('trami') == '2')
                                                   <a href="{{route('verConst')}}">                                        
                                                   <img src="{{ asset('images/fsig.jpg') }}" border="0" width="30" height="30" title="Siguiente requisito">
                                                   <img src="{{ asset('images/b5.jpg') }}"   border="0" width="25" height="30" title="Solicitud de Constancia">
                                                   </a>
                                                @else
                                                   <a href="{{route('verOsc5')}}">                                        
                                                   <img src="{{ asset('images/fsig.jpg') }}" border="0" width="30" height="30" title="Inicio">
                                                   <img src="{{ asset('images/b1.jpg') }}"   border="0" width="25" height="30" title="Registro OSC">
                                                   </a>                                                
                                                @endif
                                            </td>
                                        </tr>                                    
                                        @endif
                                        @if(session()->get('rango') == '0')
                                            @if(session()->get('trami') == '1')
                                            <tr>
                                                <td colspan="9">
                                                <p align="justify"><b style="color:red;">
                                                Aviso importante:</b> <b style="color:green;">
                                                Los datos registrados y los archivos digitales subidos al sistema; pasarán a un proceso de validación al área correspondiente 
                                                para continuar el trámite y en un máximo de 10 días hábiles personal de la dependencia se comunicarán para informar el estado del 
                                                trámite. 
                                                
                                                Para dudas enviar su solicitud al correo electrónico: registro.social.edomex@gmail.com
                                                </b></p>
                                                </td>
                                            </tr>
                                            @EndIf
                                        @endif
                                    <tr>
                                    @endforeach
                                </tbody>
                            </table>
                            {!! $regcontable->appends(request()->input())->links() !!}
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
