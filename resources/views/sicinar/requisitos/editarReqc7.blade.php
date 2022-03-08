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
                        {!! Form::open(['route' => ['actualizarReqc7',$regcontable->osc_folio], 'method' => 'PUT', 'id' => 'actualizarReqc7', 'enctype' => 'multipart/form-data']) !!}
                        <div class="box-body">
                            <div class="row">
                                <div class="col-xs-2 form-group">
                                    <input type="hidden" name="periodo_id" id="periodo_id" value="{{$regcontable->periodo_id}}">                                
                                    <label>Periodo fiscal </label><br>
                                    <label style="color:green;">{{$regcontable->periodo_id}}</label>
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
                                    <label>Folio </label><br><label style="color:green;">{{$regcontable->osc_folio}}</label>
                                </div>                                                                                                                            
                            </div>

                            <div class="row">
                                <div class="col-xs-12 form-group">
                                    <label style="background-color:yellow;color:red"><b>Nota importante:</b>&nbsp;&nbsp;El archivo digital, NO deberá ser mayor a 1,500 kBytes en tamaño.  </label>
                                </div>   
                            </div>  
                            @if (!empty($regcontable->osc_d7)||!is_null($regcontable->osc_d7))   
                                <div class="row">
                                    <div class="col-xs-6 form-group">
                                        <label >Archivo digital de Presupuesto Anual en formato PDF </label><br>
                                        <label ><a href="/images/{{$regcontable->osc_d7}}" class="btn btn-danger" title="Subir archivo digital de Presupuesto Anual en formato PDF "><i class="fa fa-file-pdf-o"></i>PDF
                                        </a>&nbsp;&nbsp;&nbsp;{{$regcontable->osc_d7}}
                                        </label>
                                        <input type="hidden" name="doc_id7" id="doc_id7" value="16">
                                    </div>   
                                    <div class="col-xs-6 form-group">                                        
                                        <label>
                                        <iframe width="400" height="400" src="{{ asset('images/'.$regcontable->osc_d7) }}" frameborder="0"></iframe> 
                                        </label>                                       
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-xs-6 form-group">
                                        <label >Actualizar archivo digital de Presupuesto Anual en formato PDF </label>
                                        <input type="file" class="text-md-center" style="color:red" name="osc_d7" id="osc_d7" placeholder="Subir archivo digital de Presupuesto Anual en formato PDF " >
                                    </div>      
                                </div>
                            @else     <!-- se captura archivo 1 -->
                                <div class="row">
                                    <div class="col-xs-6 form-group">
                                        <label >Archivo digital de Presupuesto Anual en formato PDF</label>
                                        <input type="file" class="text-md-center" style="color:red" name="osc_d7" id="osc_d7" placeholder="Subir archivo digital de Presupuesto Anual en formato PDF " >
                                        <input type="hidden" name="doc_id7" id="doc_id7" value="16">
                                    </div>                                                
                                <div class="row">                                    
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
                                    <a href="{{route('verReqc')}}" role="button" id="cancelar" class="btn btn-danger">Cancelar</a>
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
    {!! JsValidator::formRequest('App\Http\Requests\reqcontables7Request','#actualizarRec7') !!}
@endsection

@section('javascrpt')
@endsection
