@extends('sicinar.principal')

@section('title','Registro de requisitos admon.')

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
                <small> - Nuevo </small>
            </h1>
            <ol class="breadcrumb">
                <li><img src="{{ asset('images/b4.jpg') }}" border="0" width="30" height="30">Requisitos Admon. - Nuevo</li> 
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
                        {!! Form::open(['route' => 'AltaNuevoReqc', 'method' => 'POST','id' => 'nuevoReqc', 'enctype' => 'multipart/form-data']) !!}

                        <div class="box-body">
                            <div class="row">                                
                                <div class="col-xs-8 form-group">
                                    <label >OSC</label>
                                    <select class="form-control m-bot15" name="osc_id" id="osc_id" required>
                                        <option selected="true" disabled="disabled">Seleccionar OSC</option>
                                        @foreach($regosc as $osc)
                                            <option value="{{$osc->osc_id}}">{{$osc->osc_id.' '.$osc->osc_desc}}</option>
                                        @endforeach 
                                    </select>                                    
                                </div>   
                                <div class="col-xs-4 form-group">
                                    <label >Periodo fiscal</label>
                                    <select class="form-control m-bot15" name="periodo_id" id="periodo_id" required>
                                        <option selected="true" disabled="disabled">Seleccionar periodo fiscal</option>
                                        @foreach($regperiodos as $periodo)
                                            <option value="{{$periodo->periodo_id}}">{{$periodo->periodo_desc}}</option>
                                        @endforeach
                                    </select>                                    
                                </div>                                                               
                            </div>     

                            <div class="row">
                                <div class="col-xs-12 form-group">
                                    <label style="background-color:yellow;color:red"><b>Nota importante:</b>&nbsp;&nbsp;Los archivos digitales, NO deberán ser mayores a 1,500 kBytes en tamaño.  </label>
                                </div>   
                            </div>  
                            <div class="row">               
                                <div class="col-xs-6 form-group">
                                    <label >Archivo digital de Presupuesto Anual en formato PDF </label>
                                    <input type="hidden" name="doc_id7" id="doc_id7" value="16">
                                    <input type="file" class="text-md-center" style="color:red" name="osc_d7" id="osc_d7" placeholder="Subir archivo de Presupuesto anual en formato Excel">
                                </div>   
                            </div>    

                            <div class="row">               
                                <div class="col-xs-6 form-group">
                                    <label >Archivo digital de Constancia de Cumplimiento para recibir donativos en formato PDF </label>
                                    <input type="hidden" name="doc_id8" id="doc_id8" value="14">
                                    <input type="file" class="text-md-center" style="color:red" name="osc_d8" id="osc_d8" placeholder="Subir archivo digital de Constancia de Cumplimiento para Recibir Donativos en formato PDF">
                                </div>   
                            </div> 

                            <div class="row">               
                                <div class="col-xs-6 form-group">
                                    <label >Archivo digital de Declaración Anual en formato PDF </label>
                                    <input type="hidden" name="doc_id9" id="doc_id9" value="13">
                                    <input type="file" class="text-md-center" style="color:red" name="osc_d9" id="osc_d9" placeholder="Subir archivo digital de Declaración Anual en formato PDF">
                                </div>   
                            </div> 
                         

                            <div class="row">               
                                <div class="col-xs-6 form-group">
                                    <label >Archivo digital de Comprobación Deducibles de Impuestos en formato PDF </label>
                                    <input type="hidden" name="doc_id10" id="doc_id10" value="15">
                                    <input type="file" class="text-md-center" style="color:red" name="osc_d10" id="osc_d10" placeholder="Subir archivo digital de Comprobación Deducibles de Impuestos en formato PDF">
                                </div>   
                            </div>        

                            <div class="row">                
                                <div class="col-xs-6 form-group">
                                    <label >Archivo digital de Apertura y/o Edo. de Cuenta en formato PDF </label>
                                    <input type="hidden" name="doc_id11" id="doc_id11" value="15">
                                    <input type="file" class="text-md-center" style="color:red" name="osc_d11" id="osc_d11" placeholder="Subir archivo digital de Apertura de Cuenta y/o Estado de Cuenta en formato PDF">
                                </div>   
                            </div> 

                            <div class="row">
                                <div class="col-md-12 offset-md-5">
                                    {!! Form::submit('Registrar',['class' => 'btn btn-success btn-flat pull-right']) !!}
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
    {!! JsValidator::formRequest('App\Http\Requests\reqcontablesRequest','#nuevoReqc') !!}
@endsection

@section('javascrpt')
@endsection
