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
            <h1>
                Menú
                <small>Validar y autorizar requisitos - Editar</small>
            </h1>
        </section> 
        <section class="content">
            <div class="row">
                <div class="col-md-12">
                    <div class="box box-success">

                        {!! Form::open(['route' => ['actualizarValrse',$regautorizados->osc_folio], 'method' => 'PUT', 'id' => 'actualizarValrse', 'enctype' => 'multipart/form-data']) !!}
                        <div class="box-body">
                            <div class="row">
                                <div class="col-xs-4 form-group">
                                    <input type="hidden" name="periodo_id" id="periodo_id" value="{{$regautorizados->periodo_id}}">                                
                                    <label>Periodo fiscal </label><br>
                                    <label style="color:green;">{{$regautorizados->periodo_id}}</label>
                                </div>                                   
                                <div class="col-xs-4 form-group">
                                    <input type="hidden" name="osc_id" id="osc_id" value="{{$regautorizados->osc_id}}">
                                    <label>OSC &nbsp;&nbsp;&nbsp; </label><br>
                                    @foreach($regosc as $osc)
                                        @if($osc->osc_id == $regautorizados->osc_id)
                                            <label style="color:green;">{{$regautorizados->osc_id.' '.$osc->osc_desc}}</label>
                                            @break
                                        @endif
                                    @endforeach
                                </div>  
                                <div class="col-xs-4 form-group">
                                    <label>Folio </label><br><label style="color:green;">{{$regautorizados->osc_folio}}</label>
                                </div>                                                                                                                            
                            </div>

                            <div class="row">
                                <div class="col-xs-4 form-group">
                                    <label >Quien valido </label>
                                    <input type="text" class="form-control" name="osc_valida" id="osc_valida" placeholder="Quien valida" value="{{Trim($regautorizados->osc_valida)}}" required>
                                </div>
                                <div class="col-xs-4 form-group">
                                    <label >Quien autorizo </label>
                                    <input type="text" class="form-control" name="osc_autoriza" id="osc_autoriza" placeholder="Quien autoriza" value="{{Trim($regautorizados->osc_autoriza)}}" required>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-xs-4 form-group">                        
                                    <label>Estado del trámite de inscripción al Registro del Padrón Estatal (PSE) </label>
                                    <select class="form-control m-bot15" name="osc_edo1" id="osc_edo1" required>
                                        @if($regautorizados->osc_edo1 == 'P') <!-- amarillo -->
                                            <option value="P" selected>Pendiente    </option>
                                            <option value="A">         Autorizado   </option>
                                            <option value="N">         Sin autorizar</option>
                                        @else  
                                            @if($regautorizados->osc_edo1 == 'A') <!-- verde -->
                                                <option value="P">         Pendiente    </option>
                                                <option value="A" selected>Autorizado   </option>
                                                <option value="N">         Sin autorizar</option>
                                            @else
                                                @if($regautorizados->osc_edo1 == 'N') <!-- rojo -->
                                                    <option value="P">         Pendiente    </option>
                                                    <option value="A">         Autorizado   </option>
                                                    <option value="N" selected>Sin autorizar</option>
                                                @endif
                                            @endif
                                        @endif
                                    </select>
                                </div>                                                                  
                            </div>

                            <div class="row">                                
                                <div class="col-xs-12 form-group">
                                    <label >Observaciones (4,000 carácteres)</label>
                                    <textarea class="form-control" name="osc_obs1" id="osc_obs1" rows="3" cols="120" placeholder="Observaciones" required>{{Trim($regautorizados->osc_obs1)}}
                                    </textarea>
                                </div>                                
                            </div>

                            <div class="row">
                                <div class="col-xs-12 form-group">
                                    <label style="background-color:yellow;color:red"><b>Nota importante:</b>&nbsp;&nbsp;El archivo digital, NO deberá ser mayor a 1,500 kBytes en tamaño.  </label>
                                </div>   
                            </div>  
                            <div class="row">
                                @if (!empty($regautorizados->osc_d1)||!is_null($regautorizados->osc_d1))   
                                    <div class="col-xs-4 form-group">
                                        <label >Archivo de Inscrip. autorizado al RSE en formato PDF</label><br>
                                        <label ><a href="/images/{{$regautorizados->osc_d1}}" class="btn btn-danger" title="Archivo de Inscrip. autorizado al RSE en formato PDF"><i class="fa fa-file-pdf-o"></i>PDF
                                        </a>&nbsp;&nbsp;&nbsp;{{$regautorizados->osc_d1}}
                                        </label>
                                        <input type="hidden" name="doc_id1" id="doc_id1" value="13">
                                    </div>   
                                    <div class="col-xs-4 form-group">
                                        <label >Archivo de Inscrip. autorizado al RSE en formato PDF</label>
                                        <input type="file" class="text-md-center" style="color:red" name="osc_d1" id="osc_d1" placeholder="Subir archivo de Declaración anual en formato PDF" >
                                    </div>      
                                @else     <!-- se captura archivo 1 -->
                                    <div class="col-xs-4 form-group">
                                        <label >Archivo de Inscrip. autorizado al RSE en formato PDF</label>
                                        <input type="file" class="text-md-center" style="color:red" name="osc_d1" id="osc_d1" placeholder="Subir archivo de Declaración anual en formato PDF" >
                                        <input type="hidden" name="doc_id1" id="doc_id1" value="1">
                                    </div>                                                
                                @endif 
                            </div> 

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
    {!! JsValidator::formRequest('App\Http\Requests\valrseRequest','#actualizarValrse') !!}
@endsection

@section('javascrpt')
@endsection
