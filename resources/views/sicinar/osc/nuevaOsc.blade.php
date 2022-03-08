@extends('sicinar.principal')

@section('title','Nueva OSC')

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
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <div class="content-wrapper">
        <section class="content-header">
            <h1><i class="fa fa-dashboard"></i>&nbsp;&nbsp;Menú.
                <small>1. Registro OSC</small>
                <small> - Nueva</small>
            </h1>
            <ol class="breadcrumb">
                <li><img src="{{ asset('images/b1.jpg') }}" border="0" width="30" height="30">Registro OSC - Nueva</li> 
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
                        {!! Form::open(['route' => 'AltaNuevaOsc', 'method' => 'POST','id' => 'nuevaOsc', 'enctype' => 'multipart/form-data']) !!}
                        <div class="box-body">
                            <div class="row">
                                <div class="col-xs-4 form-group">
                                    <label >Nombre de la OSC </label>
                                    <input type="text" class="form-control" name="osc_desc" id="osc_desc" placeholder="Digitar nombre de la OSC" required>
                                </div>
                                <div class="col-xs-4 form-group">
                                    <label >RFC </label>
                                    <input type="text" class="form-control" name="osc_rfc" id="osc_rfc" placeholder="RFC" required>
                                </div>  
                                <div class="col-xs-4 form-group">
                                    <label >Rubro  </label>
                                    <select class="form-control m-bot15" name="rubro_id" id="rubro_id" required>
                                        <option selected="true" disabled="disabled">Seleccionar rubro </option>
                                        @foreach($regrubro as $rubro)
                                            <option value="{{$rubro->rubro_id}}">{{$rubro->rubro_id}} - {{$rubro->rubro_desc}}
                                            </option>
                                        @endforeach
                                    </select>                                    
                                </div>                                                               
                            </div>

                            <div class="row">
                                <div class="col-xs-4 form-group">
                                    <label >Domicilio legal (Calle, no.ext/int., colonia) </label>
                                    <input type="text" class="form-control" name="osc_dom1" id="osc_dom2" placeholder="Domicilio legal" required>
                                </div>
                                <div class="col-xs-4 form-group">
                                    <label >Domicilio 2 (Calle, no.ext/int., colonia)</label>
                                    <input type="text" class="form-control" name="osc_dom2" id="osc_dom2" placeholder="Domicilio fiscal" required>
                                </div>
                                <div class="col-xs-4 form-group">
                                    <label >Domicilio 3 (Calle, no.ext/int., colonia) </label>
                                    <input type="text" class="form-control" name="osc_dom3" id="osc_dom3" placeholder="Domicilio asistencial" required>
                                </div>                                
                            </div>

                            <div class="row">
                                <div class="col-xs-4 form-group">
                                    <label >Código postal </label>
                                    <input type="number" min="0" max="99999" class="form-control" name="osc_cp" id="osc_cp" placeholder="Código postal" required>
                                </div>                                                                      
                                <div class="col-xs-4 form-group">
                                    <label >Entidad federativa</label>
                                    <select class="form-control m-bot15" name="entidadfederativa_id" id="entidadfederativa_id" required>
                                        <option selected="true" disabled="disabled">Seleccionar entidad federativa</option>
                                        @foreach($regentidades as $estado)
                                            <option value="{{$estado->entidadfederativa_id}}">{{$estado->entidadfederativa_desc}}
                                            </option>
                                        @endforeach
                                    </select>                                  
                                </div>
                                <div class="col-xs-4 form-group">
                                    <label >Municipio</label>
                                    <select class="form-control m-bot15" name="municipio_id" id="municipio_id" required>
                                        <option selected="true" disabled="disabled">Seleccionar municipio</option>
                                        @foreach($regmunicipio as $municipio)
                                            <option value="{{$municipio->municipioid}}">{{$municipio->entidadfederativa_desc.'-'.$municipio->municipionombre}}
                                            </option>
                                        @endforeach
                                    </select>                                  
                                </div>                                                                    
                            </div>

                            <div class="row">                                
                                <div class="col-xs-4 form-group">
                                    <label >Teléfono </label>
                                    <input type="text" class="form-control" name="osc_telefono" id="osc_telefono" placeholder="Teléfono" required>
                                </div>                                                                 
                                <div class="col-xs-4 form-group">
                                    <label >Correo electrónico </label>
                                    <input type="text" class="form-control" name="osc_email" id="osc_email" placeholder=" Código postal" required>
                                </div>               
                            </div>

                            <div class="row">                                
                                <div class="col-xs-2 form-group">
                                    <label >Redes Sociales: </label>
                                </div>                                                                 
                                <div class="col-xs-1 form-group" style="text-align:left;">
                                    <input type="checkbox" name="facebook" id="facebook" value="S" placeholder="FaceBook" required>
                                    <img src="{{ asset('images/facebook.jpg') }}"   title="FaceBook" border="0" width="30" height="30" style="text-align:center;margin-right: 15px;vertical-align: middle;"/>   
                                    &nbsp;
                                </div>
                                <div class="col-xs-1 form-group" style="text-align:left;">
                                    <input type="checkbox" name="whatsapp" id="whatsapp" value="S" placeholder="WhatsApp" required>
                                    <img src="{{ asset('images/whatsapp.jpg') }}"  title="WhatsApp" border="0" width="30" height="30" style="text-align:center;margin-right: 15px;vertical-align: middle;"/>   
                                    &nbsp;
                                </div>
                                <div class="col-xs-1 form-group" style="text-align:left;">
                                    <input type="checkbox" name="google" id="google" value="S" placeholder="Google" required>
                                    <img src="{{ asset('images/google.jpg') }}"  title="Gloogle" border="0" width="30" height="30" style="text-align:center;margin-right: 15px;vertical-align: middle;"/>                                    
                                    &nbsp;
                                </div>    
                                <div class="col-xs-1 form-group" style="text-align:left;">
                                    <input type="checkbox" name="yahoo" id="yahoo" value="S" placeholder="Yahoo" required>
                                    <img src="{{ asset('images/yahoo.jpg') }}"  title="Yahoo" border="0" width="30" height="30" style="text-align:center;margin-right: 15px;vertical-align: middle;"/>                                    
                                    &nbsp;
                                </div>
                                <div class="col-xs-1 form-group" style="text-align:left;">
                                    <input type="checkbox" name="twitter" id="twitter" value="S" placeholder="Twitter" required>
                                    <img src="{{ asset('images/twitter.jpg') }}"  title="Twitter" border="0" width="30" height="30" style="text-align:center;margin-right: 15px;vertical-align: middle;"/>
                                    &nbsp;
                                </div>                                
                                <div class="col-xs-1 form-group" style="text-align:left;">
                                    <input type="checkbox" name="instagram" id="instagram" value="S" placeholder="Instagram" required>
                                    <img src="{{ asset('images/instagram.jpg') }}"  title="Instagram" border="0" width="30" height="30" style="text-align:center;margin-right: 15px;vertical-align: middle;"/>                                         
                                    &nbsp;
                                </div>
                                <div class="col-xs-1 form-group" style="text-align:left;">
                                    <input type="checkbox" name="linkedln" id="linkedln" value="S" placeholder="Linkedln" required>
                                    <img src="{{ asset('images/linkedln.jpg') }}"   title="Linkedln" border="0" width="30" height="30" style="text-align:center;margin-right: 15px;vertical-align: middle;"/>   
                                    &nbsp;
                                </div>
                                <div class="col-xs-1 form-group" style="text-align:left;">
                                    <input type="checkbox" name="pinterest" id="pinterest" value="S" placeholder="Pinterest" required>
                                    <img src="{{ asset('images/pinterest.jpg') }}"  title="Pinterest" border="0" width="30" height="30" style="text-align:center;margin-right: 15px;vertical-align: middle;"/>                                    
                                    &nbsp;
                                </div>
                                <div class="col-xs-1 form-group" style="text-align:left;">
                                    <input type="checkbox" name="otra" id="otra" value="S" placeholder="Otra red social" required>
                                    &nbsp;Otra
                                </div>    
                            </div>
                            <div class="row">    
                                <div class="col-xs-6 form-group">
                                    <label >Cuenta de Red social </label>
                                    <input type="text" class="form-control" name="red_social" id="red_social" placeholder="Red social" required>
                                </div>           
                                <div class="col-xs-6 form-group">
                                    <label >Sitio web </label>
                                    <input type="text" class="form-control" name="osc_sweb" id="osc_sweb" placeholder=" Sitio web o pagina electrónica" required>
                                </div>                                             
                            </div>


                            <div class="row">                                
                                <div class="col-xs-4 form-group">
                                    <label >Fecha de constitución (dd/mm/aaaa) </label>
                                    <input type="text" class="form-control" name="osc_feccons2" id="osc_feccons2" placeholder="dd/mm/aaaa" required>
                                </div>                                                                 
                            </div>

                            <div class="row">      
                                <div class="col-xs-4 form-group">
                                    <label >Folio reg. público de la propiedad </label>
                                    <input type="text" class="form-control" name="osc_regcons" id="osc_regcons" placeholder="Folio de registro público de la propiedad" required>
                                </div>                                                                                    
                                <div class="col-xs-4 form-group">
                                    <label >Vigencia de la asociación (años) </label>
                                    <select class="form-control m-bot15" name="anio_id" id="anio_id" required>
                                        <option selected="true" disabled="disabled">Seleccionar vigencia del patronato</option>
                                        @foreach($regvigencia as $vigencia)
                                            <option value="{{$vigencia->anio_id}}">{{$vigencia->anio_desc}}</option>
                                        @endforeach 
                                    </select>                                    
                                </div>
                            </div>     

                            <div class="row">
                                <div class="col-xs-4 form-group">
                                    <label >Presidente </label>
                                    <input type="text" class="form-control" name="osc_pres" id="osc_pres" placeholder=" Presidente" required>
                                </div>
                                <div class="col-xs-4 form-group">
                                    <label >Representante legal </label>
                                    <input type="text" class="form-control" name="osc_replegal" id="osc_replegal" placeholder="Representante legal" required>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-xs-4 form-group">
                                    <label >Secretario </label>
                                    <input type="text" class="form-control" name="osc_srio" id="osc_srio" placeholder="Secretario" required>
                                </div>                                                          
                                <div class="col-xs-4 form-group">
                                    <label >Tesorero </label>
                                    <input type="text" class="form-control" name="osc_tesorero" id="osc_tesorero" placeholder="Tesorero" required>
                                </div>   
                            </div>
                            
                            <div class="row">
                                <div class="col-xs-12 form-group">
                                    <label >Objeto social parte 1 (4,000 caracteres)</label>
                                    <textarea class="form-control" name="osc_objsoc_1" id="osc_objsoc_1" rows="6" cols="120" placeholder="Objeto social parte 1" required>
                                    </textarea>
                                </div>                                
                            </div>
                            <div class="row">
                                <div class="col-xs-12 form-group">
                                    <label >Objeto social parte 2 (4,000 caracteres)</label>
                                    <textarea class="form-control" name="osc_objsoc_2" id="osc_objsoc_2" rows="6" cols="120" placeholder="Objeto social parte 2" required>
                                    </textarea>
                                </div>                                
                            </div>                            
                            <div class="row">
                                <div class="col-xs-12 form-group">
                                    <label >Observaciones (2,000 caracteres)</label>
                                    <textarea class="form-control" name="osc_obs1" id="osc_obs1" rows="3" cols="120" placeholder="Observaciones" required>
                                    </textarea>
                                </div>                                
                            </div>                            

                            <div class="row">
                                <div class="col-xs-4 form-group">
                                    <label >Georeferenciación latitud (google maps) </label>
                                    <input type="text" class="form-control" name="osc_georef_latitud" id="osc_georef_latitud" placeholder="Georeferenciación latitud (google maps)" required>
                                </div>                                                          
                                <div class="col-xs-4 form-group">
                                    <label >Georeferenciación longitud (google maps) </label>
                                    <input type="text" class="form-control" name="osc_georef_longitud" id="osc_georef_longitud" placeholder="Georeferenciación longitud (google maps)" required>
                                </div>   
                            </div>                            

                            <div class="row">
                                <div class="col-xs-12 form-group">
                                    <label style="background-color:yellow;color:red"><b>Nota importante:</b>&nbsp;&nbsp;Los archivos digitales fotográficos (foto 1 y foto 2), NO deberán ser mayores a 1,500 kBytes en tamaño.  </label>
                                </div>   
                            </div> 
                            <div class="row">
                                <div class="col-xs-4 form-group">
                                    <label >Archivo de foto 1 </label>
                                    <input type="file" class="text-md-center" style="color:red" name="osc_foto1" id="osc_foto1" placeholder="Subir archivo de fotografía 1" >
                                </div>                                                          
                                <div class="col-xs-4 form-group">
                                    <label >Archivo de foto 2 </label>
                                    <input type="file" class="text-md-center" style="color:red" name="osc_foto2" id="osc_foto2" placeholder="Subir archivo de fotografía 2" >
                                </div>   
                                <div class="col-xs-4 form-group">
                                    <label >Archivo de foto 3 </label>
                                    <input type="file" class="text-md-center" style="color:red" name="osc_foto3" id="osc_foto3" placeholder="Subir archivo de fotografía 3" >
                                </div>                                   
                            </div>

                            <div class="row">
                                <div class="col-md-12 offset-md-5">
                                    {!! Form::submit('Dar de alta',['class' => 'btn btn-success btn-flat pull-right']) !!}
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
    {!! JsValidator::formRequest('App\Http\Requests\oscRequest','#nuevaOsc') !!}
@endsection

@section('javascrpt')
<script>
  function soloAlfa(e){
       key = e.keyCode || e.which;
       tecla = String.fromCharCode(key);
       letras = "abcdefghijklmnñopqrstuvwxyz ABCDEFGHIJKLMNÑOPQRSTUVWXYZ.";
       especiales = "8-37-39-46";

       tecla_especial = false
       for(var i in especiales){
            if(key == especiales[i]){
                tecla_especial = true;
                break;
            }
        }
        if(letras.indexOf(tecla)==-1 && !tecla_especial){
            return false;
        }
    }

    function general(e){
       key = e.keyCode || e.which;
       tecla = String.fromCharCode(key);
       letras = "abcdefghijklmnñopqrstuvwxyz ABCDEFGHIJKLMNÑOPQRSTUVWXYZ1234567890,.;:-_<>!%()=?¡¿/*+";
       especiales = "8-37-39-46";

       tecla_especial = false
       for(var i in especiales){
            if(key == especiales[i]){
                tecla_especial = true;
                break;
            }
        }
        if(letras.indexOf(tecla)==-1 && !tecla_especial){
            return false;
        }
    }
</script>

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