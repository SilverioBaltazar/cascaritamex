@extends('sicinar.principal')

@section('title','Editar requisitos admon.')

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
                <small> - Editar </small>
            </h1>
            <ol class="breadcrumb">
                <li><img src="{{ asset('images/b4.jpg') }}" border="0" width="30" height="30">Requisitos Admon. - Editar</li> 
            </ol>       
        </section>
        <section class="content">
            <div class="row">
                <div class="col-md-12">
                    <div class="box box-success">
                        <p align="justify"><b style="color:red;">
                            Instrucciones:</b> <b style="color:green;">
                            Los requisitos administrativos obligatorios: Apertura y/o Estado de Cuenta Bancario, opcionales: Presupuesto anual, Constancia de recibir donativos, Declaración Anual y 
                            Comprobante Deducible de Impuestos; se deberán subir al sistema de forma anual y actualizar cuando haya algún cambio de estos.
                            Se requiere escanear a una resolución de 300 ppp en blanco y negro.
                            </b>
                        </p>
                        {!! Form::open(['route' => ['actualizarReqc',$regcontable->osc_folio], 'method' => 'PUT', 'id' => 'actualizarReqc', 'enctype' => 'multipart/form-data']) !!}
                        <div class="box-body">
                            <div class="row">                            
                                <div class="col-xs-2 form-group" style="color:green;">
                                    <input type="hidden" name="periodo_id" id="periodo_id" value="{{$regcontable->periodo_id}}">                                
                                    <label>Periodo fiscal <br>
                                        {{$regcontable->periodo_id}}
                                    </label>
                                </div>                                   
                                <div class="col-xs-8 form-group">
                                    <input type="hidden" name="osc_id" id="osc_id" value="{{$regcontable->osc_id}}">
                                    <label>OSC &nbsp;&nbsp;&nbsp; </label><br>
                                    @foreach($regosc as $osc)
                                        @if($osc->osc_id == $regcontable->osc_id)
                                            <label style="color:green;">{{$regcontable->osc_id.' '.$osc->osc_desc}}</label>
                                            @break
                                        @endif
                                    @endforeach
                                </div>  
                                <div class="col-xs-1 form-group">
                                    <label>Folio <br> {{$regcontable->osc_folio}}</label>
                                </div>                                                                                     
                            </div>
           
                            @if(!empty($regcontable->osc_d7)||(!is_null($regcontable->osc_d7)))
                                <div class="row">               
                                    <div class="col-xs-6 form-group">                            
                                        <label >Archivo digital de Presupuesto Anual en formato PDF </label><br>
                                        <a href="/images/{{$regcontable->osc_d7}}" class="btn btn-danger" title="Archivo digital de Presupuesto Anual en formato PDF"><i class="fa fa-file-pdf-o"></i><small>PDF</small>
                                        </a>{{$regcontable->osc_d7}}
                                        <input type="hidden" name="doc_id7" id="doc_id7" value="16">
                                    </div>
                                    <div class="col-xs-6 form-group">                                        
                                        <label>
                                        <iframe width="400" height="400" src="{{ asset('images/'.$regcontable->osc_d7) }}" frameborder="0"></iframe> 
                                        </label>                                       
                                    </div>
                                </div>                                        
                            @else
                                <div class="row">               
                                    <div class="col-xs-6 form-group">
                                        <label >Archivo digital de Presupuesto Anual en formato PDF </label><br>
                                        <b style="color:darkred;">** Pendiente **</b>
                                        <input type="hidden" name="doc_id7" id="doc_id7" value="16">
                                    </div>
                                </div>
                            @endif

                            @if(!empty($regcontable->osc_d8)||(!is_null($regcontable->osc_d8)))
                                <div class="row">               
                                    <div class="col-xs-6 form-group">                                                        
                                        <label >Archivo digital de Constancia de Autorización para Recibir Donativos en formato PDF </label><br>
                                        <a href="/images/{{$regcontable->osc_d8}}" class="btn btn-danger" title="Archivo digital de Constancia de Autorización para Recibir Donativos en formato PDF"><i class="fa fa-file-pdf-o"></i><small>PDF</small>
                                        </a>{{$regcontable->osc_d8}}
                                        <input type="hidden" name="doc_id8" id="doc_id8" value="14">
                                    </div>
                                    <div class="col-xs-6 form-group">                                        
                                        <label>
                                        <iframe width="400" height="400" src="{{ asset('images/'.$regcontable->osc_d8) }}" frameborder="0"></iframe> 
                                        </label>                                       
                                    </div>                                    
                                </div>
                            @else
                                <div class="row">               
                                    <div class="col-xs-6 form-group">                            
                                        <label >Archivo digital de Constancia de Autorización para Recibir Donativos en formato PDF </label><br>
                                        <b style="color:darkred;">** Pendiente **</b>
                                        <input type="hidden" name="doc_id8" id="doc_id8" value="14">
                                    </div>
                                </div>
                            @endif
                                </div>  
                            </div>

                            @if(!empty($regcontable->osc_d9)||(!is_null($regcontable->osc_d9)))
                                <div class="row">               
                                    <div class="col-xs-6 form-group">                                                        
                                        <label >Archivo digital de Declaración Anual en formato PDF </label><br>
                                        <a href="/images/{{$regcontable->osc_d9}}" class="btn btn-danger" title="Archivo digital de Declaración Anual en formato PDF"><i class="fa fa-file-pdf-o"></i><small>PDF</small>
                                        </a>{{$regcontable->osc_d9}}
                                        <input type="hidden" name="doc_id9" id="doc_id9" value="13">
                                    </div>
                                    <div class="col-xs-6 form-group">                                        
                                        <label>
                                        <iframe width="400" height="400" src="{{ asset('images/'.$regcontable->osc_d9) }}" frameborder="0"></iframe> 
                                        </label>                                       
                                    </div>
                                </div>
                            @else
                                <div class="row">               
                                    <div class="col-xs-6 form-group">                                                        
                                        <label >Archivo digital de Declaración Anual en formato PDF </label><br>
                                        <b style="color:darkred;">** Pendiente **</b>
                                        <input type="hidden" name="doc_id9" id="doc_id9" value="13">
                                    </div>
                                </div>
                            @endif

                            @if(!empty($regcontable->osc_d10) || !is_null($regcontable->osc_d10))
                                <div class="row">               
                                    <div class="col-xs-6 form-group">                                                                                    
                                        <label >Archivo digital de Comprobación Deducibles de Impuestos en formato PDF </label><br>
                                        <a href="/images/{{$regcontable->osc_d10}}" class="btn btn-danger" title="Archivo de Comprobación deducibles de impuestos "><i class="fa fa-file-pdf-o"></i><small>PDF</small>
                                        </a>{{$regcontable->osc_d10}}
                                        <input type="hidden" name="doc_id10" id="doc_id10" value="15">
                                    </div>
                                    <div class="col-xs-6 form-group">                                        
                                        <label>
                                        <iframe width="400" height="400" src="{{ asset('images/'.$regcontable->osc_d10) }}" frameborder="0"></iframe> 
                                        </label>                                       
                                    </div>                                    
                                </div>
                            @else
                                <div class="row">               
                                    <div class="col-xs-6 form-group">                                                                                    
                                        <label >Archivo digital de Comprobación Deducibles de Impuestos en formato PDF </label><br>
                                        <b style="color:darkred;">** Pendiente **</b>
                                        <input type="hidden" name="doc_id10" id="doc_id10" value="15">
                                    </div>
                                </div>
                            @endif

                            @if(!empty($regcontable->osc_d11) || !is_null($regcontable->osc_d11))
                                <div class="row">               
                                    <div class="col-xs-6 form-group">                                                                                    
                                        <label >Archivo digital de Apertura y/o Edo. de Cuenta en formato PDF </label><br>
                                        <a href="/images/{{$regcontable->osc_d11}}" class="btn btn-danger" title="Apertura de cuenta y/o estado de cuenta"><i class="fa fa-file-pdf-o"></i><small>PDF</small>
                                        </a>{{$regcontable->osc_d11}}
                                        <input type="hidden" name="doc_id11" id="doc_id11" value="15">
                                    </div>
                                    <div class="col-xs-6 form-group">                                        
                                        <label>
                                        <iframe width="400" height="400" src="{{ asset('images/'.$regcontable->osc_d11) }}" frameborder="0"></iframe> 
                                        </label>                                       
                                    </div>                                    
                                </div>
                            @else
                                <div class="row">               
                                    <div class="col-xs-6 form-group">                                                                                    
                                        <label >Archivo digital de Apertura y/o Edo. de Cuenta en formato PDF </label><br>
                                        <b style="color:darkred;">** Pendiente **</b>
                                        <input type="hidden" name="doc_id11" id="doc_id11" value="15">
                                    </div>
                                </div>
                            @endif

                            <div class="row">
                                @if(count($errors) > 0)
                                    <div class="alert alert-danger" role="alert">
                                        <ul>
                                            @foreach($errors->all() as $error)
                                                <li>{{ $error }}</li>
                                            @endforeach
                                        </ul>
                                    </div>
                                @endif 
                                <div class="col-md-12 offset-md-5">
                                    {!! Form::submit('Guardar',['class' => 'btn btn-success btn-flat pull-right']) !!}
                                    <a href="{{route('verReqc')}}" role="button" id="cancelar" class="btn btn-danger">Cancelar
                                    </a>
                                </div>
                            </div>
                        </div>
                        {!! Form::close() !!}
                    </div>
                </div>
            </div>
        </section>
    </div>
@endsection

@section('request')
    <script src="//cdnjs.cloudflare.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>
    <script src="//cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/3.3.1/js/bootstrap.min.js"></script>
    <script type="text/javascript" src="{{ asset('vendor/jsvalidation/js/jsvalidation.js')}}"></script>
    {!! JsValidator::formRequest('App\Http\Requests\reqcontablesRequest','#actualizarReqc') !!}
@endsection

@section('javascrpt')
@endsection
