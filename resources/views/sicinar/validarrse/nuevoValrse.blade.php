@extends('sicinar.principal')

@section('title','Validar requisitos')

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
            <h1>Menú
                <small>Validar y autorizar requisitos - Validar - Nuevo </small>
            </h1>
        </section>
        <section class="content">
            <div class="row">
                <div class="col-md-12"> 
                    <div class="box box-success">

                        {!! Form::open(['route' => 'AltaNuevoValrse', 'method' => 'POST','id' => 'nuevoValrse', 'enctype' => 'multipart/form-data']) !!}
                        <div class="box-body">
                            <div class="row">                                
                                <div class="col-xs-6 form-group">
                                    <label >OSC</label>
                                    <select class="form-control m-bot15" name="osc_id" id="osc_id" required>
                                        <option selected="true" disabled="disabled">Seleccionar OSC</option>
                                        @foreach($regosc as $osc)
                                            <option value="{{$osc->osc_id}}">{{$osc->osc_id.' '.$osc->osc_desc}}</option>
                                        @endforeach 
                                    </select>                                    
                                </div>   
                                <div class="col-xs-4 form-group">
                                    <label >Periodo fiscal </label>
                                    <select class="form-control m-bot15" name="periodo_id" id="periodo_id" required>
                                        <option selected="true" disabled="disabled">Seleccionar periodo fiscal</option>
                                        @foreach($regperiodos as $periodo)
                                            <option value="{{$periodo->periodo_id}}">{{$periodo->periodo_desc}}</option>
                                        @endforeach
                                    </select>                                    
                                </div>                                                               
                            </div>     

                            <div class="row">
                                <div class="col-xs-4 form-group">
                                    <label >Quien valido </label>
                                    <input type="text" class="form-control" name="osc_valida" id="osc_valida" placeholder="Quien valida" required>
                                </div>
                                <div class="col-xs-4 form-group">
                                    <label >Quien autorizo </label>
                                    <input type="text" class="form-control" name="osc_autoriza" id="osc_autoriza" placeholder="Quien autoriza" required>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-xs-4 form-group">
                                    <label>Estado del trámite de inscripción al Registro del Padrón Estatal (PSE) </label>
                                    <select class="form-control m-bot15" name="osc_edo1" id="osc_edo1" required>
                                        <option selected="true" disabled="disabled">Selecciona estado del trámite </option>
                                        <option value="A">Autorizado   </option>
                                        <option value="P">Pendiente    </option>
                                        <option value="N">Sin autorizar</option>
                                    </select>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-xs-12 form-group">
                                    <label >Observaciones (4,000 caracteres)</label>
                                    <textarea class="form-control" name="osc_obs1" id="osc_obs1" rows="6" cols="120" placeholder="Observaciones" required>
                                    </textarea>
                                </div>                                
                            </div>

                            <div class="row">
                                <div class="col-xs-12 form-group">
                                    <label style="background-color:yellow;color:red"><b>Nota importante:</b>&nbsp;&nbsp;Los archivos digitales, NO deberán ser mayores a 1,500 kBytes en tamaño.  </label>
                                </div>   
                            </div>  
                            <div class="row">               
                                <div class="col-xs-6 form-group">
                                    <label >Archivo de Inscrip. autorizado al RSE en formato PDF </label>
                                    <input type="hidden" name="doc_id1" id="doc_id1" value="1">
                                    <input type="file" class="text-md-center" style="color:red" name="osc_d1" id="osc_d1" placeholder="Subir archivo digital Inscrip. al RSE en formato PDF">
                                </div>   
                            </div>    
                          
                            <div class="row">
                                <div class="col-md-6 offset-md-5">
                                    {!! Form::submit('Registrar',['class' => 'btn btn-success btn-flat pull-right']) !!}
                                    <a href="{{route('verirse')}}" role="button" id="cancelar" class="btn btn-danger">Cancelar</a>
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
    {!! JsValidator::formRequest('App\Http\Requests\valrseRequest','#nuevoValrse') !!}
@endsection
