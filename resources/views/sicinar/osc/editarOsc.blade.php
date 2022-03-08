@extends('sicinar.principal')

@section('title','Editar OSC')

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
    <meta http-equiv="Content-type" content="text/html; charset=utf-8" />
    <div class="content-wrapper">
        <section class="content-header">
            <h1><i class="fa fa-dashboard"></i>&nbsp;&nbsp;Menú.
                <small>1. Registro OSC</small>
                <small> - Editar</small>
            </h1>
            <ol class="breadcrumb">
                <li><img src="{{ asset('images/b1.jpg') }}" border="0" width="30" height="30">Registro OSC - Editar</li> 
            </ol>
        </section>
        <section class="content">
            <div class="row">
                <div class="col-md-12">
                    <div class="box box-success">
                        <p align="justify"><b style="color:red;">
                            Instrucciones:</b> <b style="color:green;">
                            Requisita los datos de la Organización
                            </b>
                        </p>                                            
                        {!! Form::open(['route' => ['actualizarOsc',$regosc->osc_id], 'method' => 'PUT', 'id' => 'actualizarOsc', 'enctype' => 'multipart/form-data']) !!}
                        <div class="box-body">
                            <div class="row">
                                <div class="col-xs-10 form-group">
                                    <label >OSC </label>
                                    <input type="text" class="form-control" name="osc_desc" id="osc_desc" placeholder="Nombre" value="{{Trim($regosc->osc_desc)}}" required>
                                </div>                                                              
                                <div class="col-xs-2 form-group">
                                    <label style="color:green;">Id.: <br>{{$regosc->osc_id}} </label>                         
                                </div> 
                            </div>

                            <div class="row">
                                <div class="col-xs-4 form-group">
                                    <label >RFC </label>
                                    <input type="text" class="form-control" name="osc_rfc" id="osc_rfc" placeholder="RFC" value="{{Trim($regosc->osc_rfc)}}" required>
                                </div>  
                                <div class="col-xs-4 form-group">
                                    <label >Rubro  </label>
                                    <select class="form-control m-bot15" name="rubro_id" id="rubro_id" required>
                                        <option selected="true" disabled="disabled">Seleccionar rubro </option>
                                        @foreach($regrubro as $rubro)
                                            @if($rubro->rubro_id == $regosc->rubro_id)
                                                <option value="{{$rubro->rubro_id}}" selected>{{$rubro->rubro_desc}}</option>
                                            @else                                        
                                               <option value="{{$rubro->rubro_id}}">{{$rubro->rubro_id}} - {{$rubro->rubro_desc}} 
                                               </option>
                                            @endif
                                        @endforeach
                                    </select>                                    
                                </div>                                     
                            </div>

                            <div class="row">
                                <div class="col-xs-4 form-group">
                                    <label >Domicilio Legal (Calle, no.ext/int., colonia) </label>
                                    <input type="text" class="form-control" name="osc_dom1" id="osc_dom1" placeholder="Domicilio Legal" value="{{Trim($regosc->osc_dom1)}}" required>
                                </div>
                                <div class="col-xs-4 form-group">
                                    <label >Domicilio 2 (Calle, no.ext/int., colonia)</label>
                                    <input type="text" class="form-control" name="osc_dom2" id="osc_dom2" placeholder="Domicilio Fiscal" value="{{Trim($regosc->osc_dom2)}}" required>
                                </div>
                                <div class="col-xs-4 form-group">
                                    <label >Domicilio 3 (Calle, no.ext/int., colonia) </label>
                                    <input type="text" class="form-control" name="osc_dom3" id="osc_dom3" placeholder="Domicilio Asistencial" value="{{Trim($regosc->osc_dom3)}}" required>
                                </div>                                
                            </div>

                            <div class="row">        
                                <div class="col-xs-4 form-group">
                                    <label >Código postal </label>
                                    <input type="number" min="0" max="99999" class="form-control" name="osc_cp" id="osc_cp" placeholder="Código postal" value="{{Trim($regosc->osc_cp)}}" required>
                                </div>                                                                        
                                <div class="col-xs-4 form-group">
                                    <label >Entidad federativa</label>
                                    <select class="form-control m-bot15" name="entidadfederativa_id" id="entidadfederativa_id" required>
                                        <option selected="true" disabled="disabled">Seleccionar entidad federativa</option>
                                        @foreach($regentidades as $estado)
                                            @if($estado->entidadfederativa_id == $regosc->entidadfederativa_id)
                                                <option value="{{$estado->entidadfederativa_id}}" selected>{{$estado->entidadfederativa_desc}}</option>
                                            @else                                        
                                               <option value="{{$estado->entidadfederativa_id}}">{{$estado->entidadfederativa_desc}}</option>
                                            @endif
                                        @endforeach
                                    </select>                                  
                                </div>                                                                                      
                                <div class="col-xs-4 form-group">
                                    <label >Municipio</label>
                                    <select class="form-control m-bot15" name="municipio_id" id="municipio_id" required>
                                        <option selected="true" disabled="disabled">Seleccionar municipio</option>
                                        @foreach($regmunicipio as $municipio)
                                            @if($municipio->municipioid == $regosc->municipio_id)
                                                <option value="{{$municipio->municipioid}}" selected>{{$municipio->entidadfederativa_desc.'-'.$municipio->municipionombre}}</option>
                                            @else 
                                               <option value="{{$municipio->municipioid}}">{{$municipio->entidadfederativa_desc.'-'.$municipio->municipionombre}}
                                               </option>
                                            @endif
                                        @endforeach
                                    </select>                                  
                                </div>                                    
                            </div>

                            <div class="row">                                
                                <div class="col-xs-4 form-group">
                                    <label >Teléfono </label>
                                    <input type="text" class="form-control" name="osc_telefono" id="osc_telefono" placeholder="Teléfono" value="{{Trim($regosc->osc_telefono)}}" required>
                                </div>                                                                
                                <div class="col-xs-4 form-group">
                                    <label >Correo electrónico </label>
                                    <input type="text" class="form-control" name="osc_email" id="osc_email" placeholder=" Correo electrónico" value="{{Trim($regosc->osc_email)}}" required>
                                </div>
                            </div>

                            <div class="row">                                
                                <div class="col-xs-2 form-group">
                                    <label >Redes Sociales:</label>
                                </div>                                                                 
                                <div class="col-xs-1 form-group" style="text-align:left;">
                                    <input type="checkbox" name="facebook" id="facebook" value="S" placeholder="FaceBook" 
                                    @if(old('facebook',$regosc->facebook)=="S") checked @endif required>
                                    <img src="{{ asset('images/facebook.jpg') }}"   title="FaceBook" border="0" width="30" height="30" style="text-align:center;margin-right: 15px;vertical-align: middle;"/>   
                                    &nbsp;&nbsp;
                                </div>
                                <div class="col-xs-1 form-group" style="text-align:left;">
                                    <input type="checkbox" name="whatsapp" id="whatsapp" value="S" placeholder="WhatsApp" 
                                    @if(old('whatsapp',$regosc->whatsapp)=="S") checked @endif required>                                    
                                    <img src="{{ asset('images/whatsapp.jpg') }}"  title="WhatsApp" border="0" width="30" height="30" style="text-align:center;margin-right: 15px;vertical-align: middle;"/>   
                                    &nbsp;&nbsp;
                                </div>
                                <div class="col-xs-1 form-group" style="text-align:left;">
                                    <input type="checkbox" name="google" id="google" value="S" placeholder="Google" 
                                    @if(old('google',$regosc->google)=="S") checked @endif required>                                                
                                    <img src="{{ asset('images/google.jpg') }}"  title="Gloogle" border="0" width="30" height="30" style="text-align:center;margin-right: 15px;vertical-align: middle;"/>    
                                    &nbsp;&nbsp;                                    
                                </div>    
                                <div class="col-xs-1 form-group" style="text-align:left;">
                                    <input type="checkbox" name="yahoo" id="yahoo" value="S" placeholder="Yahoo" 
                                    @if(old('yahoo',$regosc->yahoo)=="S") checked @endif required>            
                                    <img src="{{ asset('images/yahoo.jpg') }}"  title="Yahoo" border="0" width="30" height="30" style="text-align:center;margin-right: 15px;vertical-align: middle;"/>                                    
                                    &nbsp;&nbsp;
                                </div>
                                <div class="col-xs-1 form-group" style="text-align:left;">
                                    <input type="checkbox" name="twitter" id="twitter" value="S" placeholder="Twitter" 
                                    @if(old('twitter',$regosc->twitter)=="S") checked @endif required>                                                
                                    <img src="{{ asset('images/twitter.jpg') }}"  title="Twitter" border="0" width="30" height="30" style="text-align:center;margin-right: 15px;vertical-align: middle;"/>
                                    &nbsp;&nbsp;
                                </div>                                
                                <div class="col-xs-1 form-group" style="text-align:left;">
                                    <input type="checkbox" name="instagram" id="instagram" value="S" placeholder="Instagram"
                                    @if(old('instagram',$regosc->instagram)=="S") checked @endif required>                                                                                    
                                    <img src="{{ asset('images/instagram.jpg') }}"  title="Instagram" border="0" width="30" height="30" style="text-align:center;margin-right: 15px;vertical-align: middle;"/>                                         
                                    &nbsp;&nbsp;                                    
                                </div>
                                <div class="col-xs-1 form-group" style="text-align:left;">
                                    <input type="checkbox" name="linkedln" id="linkedln" value="S" placeholder="Linkedln" 
                                    @if(old('linkedln',$regosc->linkedln)=="S") checked @endif required>                                                                                    
                                    <img src="{{ asset('images/linkedln.jpg') }}"   title="Linkedln" border="0" width="30" height="30" style="text-align:center;margin-right: 15px;vertical-align: middle;"/>   
                                    &nbsp;&nbsp;
                                </div>
                                <div class="col-xs-1 form-group" style="text-align:left;">
                                    <input type="checkbox" name="pinterest" id="pinterest" value="S" placeholder="Pinterest"
                                    @if(old('pinterest',$regosc->pinterest)=="S") checked @endif required>                                     
                                    <img src="{{ asset('images/pinterest.jpg') }}"  title="Pinterest" border="0" width="30" height="30" style="text-align:center;margin-right: 15px;vertical-align: middle;"/>                                    
                                    &nbsp;&nbsp;
                                </div>
                                <div class="col-xs-1 form-group" style="text-align:left;">
                                    <input type="checkbox" name="otra" id="otra" value="S" placeholder="Otra red social" 
                                    @if(old('otra_redsocial',$regosc->otra_redsocial)=="S") checked @endif required>                                          
                                    &nbsp;&nbsp;Otra
                                </div>    
                            </div>                            

                            <div class="row">    
                                <div class="col-xs-6 form-group">
                                    <label >Cuenta de Red social </label>
                                    <input type="text" class="form-control" name="red_social" id="red_social" placeholder="Red social" value="{{Trim($regosc->red_social)}}" required>
                                </div>           
                                <div class="col-xs-6 form-group">
                                    <label >Sitio web </label>
                                    <input type="text" class="form-control" name="osc_sweb" id="osc_sweb" placeholder=" Sitio web o pagina electrónica" value="{{Trim($regosc->osc_sweb)}}" required>
                                </div>                                             
                            </div>                            

                            <div class="row">                                
                                <div class="col-xs-4 form-group">
                                    <label >Fecha de constitución (dd/mm/aaaa) </label>
                                    <input type="text" class="form-control" name="osc_feccons2" id="osc_feccons2" placeholder="dd/mm/aaaa" value="{{Trim($regosc->osc_feccons2)}}" required>
                                </div>                                                                 
                            </div>

                            <div class="row">
                                <div class="col-xs-4 form-group">
                                    <label >Folio reg. público de la propiedad </label>
                                    <input type="text" class="form-control" name="osc_regcons" id="osc_regcons" placeholder="Folio de registro público de la propiedad" value="{{Trim($regosc->osc_regcons)}}" required>  
                                </div>                                                                                    
                                <div class="col-xs-4 form-group">
                                    <label >Vigencia de la asociación (años)</label>
                                    <select class="form-control m-bot15" name="anio_id" id="anio_id" required>
                                        <option selected="true" disabled="disabled">Seleccionar la vigencia </option>
                                        @foreach($regvigencia as $vigencia)
                                            @if($vigencia->anio_id == $regosc->anio_id)
                                                <option value="{{$vigencia->anio_id}}" selected>{{$vigencia->anio_desc}}</option>
                                            @else                                        
                                               <option value="{{$vigencia->anio_id}}">{{$vigencia->anio_desc}}</option>
                                            @endif
                                        @endforeach
                                    </select>                                  
                                </div> 
                            </div>
                            <div class="row">
                                <div class="col-xs-4 form-group">
                                    <label >Presidente </label>
                                    <input type="text" class="form-control" name="osc_pres" id="osc_pres" placeholder=" Presidente" value="{{Trim($regosc->osc_pres)}}" required>
                                </div>
                                <div class="col-xs-4 form-group">
                                    <label >Representante legal </label>
                                    <input type="text" class="form-control" name="osc_replegal" id="osc_replegal" placeholder="Representante legal" value="{{Trim($regosc->osc_replegal)}}" required>
                                </div>
                                <div class="col-xs-4 form-group">
                                    <label >Secretario </label>
                                    <input type="text" class="form-control" name="osc_srio" id="osc_srio" placeholder="Secretario" value="{{Trim($regosc->osc_srio)}}" required>
                                </div>                          
                            </div>

                            <div class="row">
                                <div class="col-xs-4 form-group">
                                    <label >Tesorero </label>
                                    <input type="text" class="form-control" name="osc_tesorero" id="osc_tesorero" placeholder="Tesorero" value="{{Trim($regosc->osc_tesorero)}}" required>
                                </div>                                                                
                                <div class="col-xs-4 form-group">                        
                                    <label>Activa o Inactiva </label>
                                    <select class="form-control m-bot15" name="osc_status" required>
                                        @if($regosc->osc_status == 'S')
                                            <option value="S" selected>Vigente</option>
                                            <option value="N">         Extinta</option>
                                        @else
                                            <option value="S">         Vigente</option>
                                            <option value="N" selected>Extinta</option>
                                        @endif
                                    </select>
                                </div>                                                                  
                            </div>

                            <div class="row">                                
                                <div class="col-xs-12 form-group">
                                    <label >Objeto social 1 (4,000 caracteres)</label>
                                    <textarea class="form-control" name="osc_objsoc_1" id="osc_objsoc_1" rows="6" cols="120" placeholder="Objeto social parte 1" required>{{Trim($regosc->osc_objsoc_1)}}
                                    </textarea>
                                </div>                                
                            </div>
                            <div class="row">                                
                                <div class="col-xs-12 form-group">
                                    <label >Objeto social 2 (4,000 caracteres)</label>
                                    <textarea class="form-control" name="osc_objsoc_2" id="osc_objsoc_2" rows="6" cols="120" placeholder="Objeto social parte 2" required>{{Trim($regosc->osc_objsoc_2)}}
                                    </textarea>
                                </div>                                
                            </div>                            
                            <div class="row">                                
                                <div class="col-xs-12 form-group">
                                    <label >Observaciones (2,000 caracteres)</label>
                                    <textarea class="form-control" name="osc_obs1" id="osc_obs1" rows="3" cols="120" placeholder="Observaciones" required>{{Trim($regosc->osc_obs1)}}
                                    </textarea>
                                </div>                                
                            </div>         
                            <div class="row">
                                <div class="col-xs-4 form-group">
                                    <label >Georeferenciación latitud (google maps) </label>
                                    <input type="text" class="form-control" name="osc_georef_latitud" id="osc_georef_latitud" placeholder="Georeferenciación latitud (google maps)" value="{{$regosc->osc_georef_latitud}}" required>
                                </div>                                                                
                                <div class="col-xs-4 form-group">
                                    <label >Georeferenciación longitud (google maps) </label>
                                    <input type="text" class="form-control" name="osc_georef_longitud" id="osc_georef_longitud" placeholder="Georeferenciación longitud (google maps)" value="{{$regosc->osc_georef_longitud}}" required>
                                </div>                                           
                            </div>                            

                            <div class="row">
                                <div class="col-xs-12 form-group">
                                    <label style="background-color:yellow;color:red"><b>Nota importante:</b>&nbsp;&nbsp;Los archivos digitales foto 1, foto 2 y foto 3, NO deberán ser mayores a 1,500 kBytes en tamaño.  </label>
                                </div>   
                            </div> 
                            <div class="row">
                                @if (!empty($regosc->osc_foto1)||!is_null($regosc->osc_foto1))  
                                    <div class="col-sm">
                                        <div class="card" style="width: 18rem;">
                                           <label >Fotografía 1 </label>
                                           <img class="card-img-top" src="/images/{{$regosc->osc_foto1}}" alt="foto 1">
                                           <!--<input type="hidden" name="osc_foto1" id="osc_foto1" value="{{$regosc->osc_foto1}}">-->
                                        </div>
                                    </div>      
                                @else     <!-- se captura foto 1 -->
                                    <div class="col-xs-4 form-group">
                                        <label >Archivo de foto 1 </label>
                                        <input type="file" class="text-md-center" style="color:red" name="osc_foto1" id="osc_foto1" placeholder="Subir archivo de fotografía 1" >
                                    </div>                                                
                                @endif                                    
                                @if (isset($regosc->osc_foto2)||!empty($regosc->osc_foto2)||!is_null($regosc->osc_foto2)) 
                                    <div class="col-sm">
                                        <div class="card" style="width: 18rem;">
                                           <label >Fotografía 2 </label>
                                           <img class="card-img-top" src="/images/{{$regosc->osc_foto2}}" alt="foto 2">
                                           <!--<input type="hidden" name="osc_foto2" id="osc_foto2" value="{{$regosc->osc_foto2}}"> -->
                                        </div>
                                    </div>   
                                @else      
                                    <div class="col-xs-4 form-group">
                                        <label >Archivo de foto 2 </label>
                                        <input type="file" class="text-md-center" style="color:red" name="osc_foto2" id="osc_foto2" placeholder="Subir archivo de fotografía 2" >
                                    </div>                                                                                
                                @endif
                                @if (isset($regosc->osc_foto3)||!empty($regosc->osc_foto3)||!is_null($regosc->osc_foto3)) 
                                    <div class="col-sm">
                                        <div class="card" style="width: 18rem;">
                                           <label >Fotografía 3 </label>
                                           <img class="card-img-top" src="/images/{{$regosc->osc_foto3}}" alt="foto 3">
                                           <!--<input type="hidden" name="osc_foto2" id="osc_foto2" value="{{$regosc->osc_foto2}}"> -->
                                        </div>
                                    </div>   
                                @else      
                                    <div class="col-xs-4 form-group">
                                        <label >Archivo de foto 3 </label>
                                        <input type="file" class="text-md-center" style="color:red" name="osc_foto3" id="osc_foto3" placeholder="Subir archivo de fotografía 3" >
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
                                    <a href="{{route('verOsc')}}" role="button" id="cancelar" class="btn btn-danger">Cancelar</a>
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
    {!! JsValidator::formRequest('App\Http\Requests\oscRequest','#actualizarOsc') !!}
@endsection

@section('javascrpt')
<script>
    $('.datepicker').datepicker({
        format: "dd/mm/yyyy",
        startDate: '-29y',
        endDate: '-18y',
        startView: 2,
        maxViewMode: 2,
        clearBtn: true,        
        language: "es",
        autoclose: true
    });
</script>

@endsection
