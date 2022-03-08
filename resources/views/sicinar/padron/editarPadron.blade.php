@extends('sicinar.principal')

@section('title','Editar beneficiario')

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
                <small>2. Juagadores del equipo   </small>
                <small> - Editar </small>
            </h1> 
            <ol class="breadcrumb">
                <li><img src="{{ asset('images/b2.jpg') }}" border="0" width="30" height="30">Jugadores del equipo - Editar jugador</li> 
            </ol>            
        </section>
        <section class="content">
            <div class="row">
                <div class="col-md-12">
                    <div class="box box-success">
                        <div class="box-header">
                        </div>
                        <p align="justify">
                        <b style="color:red;">DATOS PERSONALES:</b> 
                        <b style="color:green;">De cada persona joven participante.<br>
                        Serán máximo 11 registros: 10 jugadores y 1 representante, siendo obligatorios los datos personales 
                        (un representante y mínimo 7 jugadores). El representante puede ser un mismo jugador y en este caso 
                        se debe contar dentro del número mínimo y máximo del total de jugadores del equipo).</b>
                        </p>              
                        {!! Form::open(['route' => ['actualizarpadron',$regpadron->folio], 'method' => 'PUT', 'id' => 'actualizarpadron', 'enctype' => 'multipart/form-data']) !!}
                        <div class="box-body">
                            <div class="row">    
                                <div class="col-xs-10 form-group">
                                    <input type="hidden" id="eq_id" name="eq_id" value="{{$regpadron->eq_id}}">  
                                    <label style="color:green;">Equipo de Futbol: </label>
                                    &nbsp;&nbsp;{{$regpadron->eq_id}}
                                    <td style="text-align:left; vertical-align: middle;">   
                                        @foreach($regficha as $ficha)
                                            @if($ficha->eq_id == $regpadron->eq_id)
                                                {{Trim($ficha->eq_desc)}}
                                                @break
                                            @endif
                                        @endforeach
                                    </td>                                     
                                </div> 
                                <div class="col-xs-2 form-group">
                                    <input type="hidden" id="folio" name="folio" value="{{$regpadron->folio}}">  
                                    <label style="color:green;">Folio asignado: &nbsp;&nbsp;{{$regpadron->folio}} </label>
                                </div>    
                            </div>

                            <div class="row">
                                <div class="col-xs-4 form-group">
                                    <label >Apellido paterno </label>
                                    <input type="text" class="form-control" name="primer_apellido" id="primer_apellido" placeholder="Apellido paterno" value="{{$regpadron->primer_apellido}}" required>
                                </div>  
                                <div class="col-xs-4 form-group">
                                    <label >Apellido materno </label>
                                    <input type="text" class="form-control" name="segundo_apellido" id="segundo_apellido" placeholder="Apellido materno" value="{{$regpadron->segundo_apellido}}" required>
                                </div>
                                <div class="col-xs-4 form-group">
                                    <label >Nombre(s) </label>
                                    <input type="text" class="form-control" name="nombres" id="nombres" placeholder="Nombre(s)" value="{{$regpadron->nombres}}" required>
                                </div>
                            </div>

                            <div class="row">    
                                <div class="col-xs-4 form-group">
                                    <label >Fecha de nacimiento - Año </label>
                                    <select class="form-control m-bot15" name="periodo_id2" id="periodo_id2" required>
                                        <option selected="true" disabled="disabled">Seleccionar año </option>
                                        @foreach($reganios as $anios)
                                            @if($anios->anio_id == $regpadron->periodo_id2)
                                                <option value="{{$anios->anio_id}}" selected>{{$anios->anio_desc}}</option>
                                            @else                                        
                                               <option value="{{$anios->anio_id}}">{{$anios->anio_desc}}</option>
                                            @endif
                                        @endforeach
                                    </select>                                    
                                </div>   
                                <div class="col-xs-4 form-group">
                                    <label >Mes </label>
                                    <select class="form-control m-bot15" name="mes_id2" id="mes_id2" required>
                                        <option selected="true" disabled="disabled">Seleccionar mes de nacimiento </option>
                                        @foreach($regmeses as $mes)
                                            @if($mes->mes_id == $regpadron->mes_id2)
                                                <option value="{{$mes->mes_id}}" selected>{{$mes->mes_desc}}</option>
                                            @else                                        
                                               <option value="{{$mes->mes_id}}">{{$mes->mes_desc}} </option>
                                            @endif
                                        @endforeach
                                    </select>                                    
                                </div>    
                                <div class="col-xs-4 form-group">
                                    <label >Día </label>
                                    <select class="form-control m-bot15" name="dia_id2" id="dia_id2" required>
                                        <option selected="true" disabled="disabled">Seleccionar día de nacimiento </option>
                                        @foreach($regdias as $dia)
                                            @if($dia->dia_id == $regpadron->dia_id2)
                                                <option value="{{$dia->dia_id}}" selected>{{$dia->dia_desc}}</option>
                                            @else                                        
                                               <option value="{{$dia->dia_id}}">{{$dia->dia_desc}} </option>
                                            @endif
                                        @endforeach
                                    </select>                                    
                                </div>                                    
                            </div>

                            <div class="row">
                                <div class="col-xs-4 form-group">
                                    <label >CURP </label>
                                    <input type="text" class="form-control" name="curp" id="curp" placeholder="CURP" value="{{$regpadron->curp}}" oninput="validarInput(this)" required>
                                </div>        
                                <div class="col-xs-4 form-group">
                                    <label >Folio Credencial INE y/o cartilla militar </label>
                                    <input type="text" class="form-control" name="id_oficial" id="id_oficial" placeholder="Solo aplica para mayores de 18 años" value="{{$regpadron->id_oficial}}" required>
                                </div>                                   
                                <div class="col-xs-4 form-group">                        
                                    <label>Sexo </label>
                                    <select class="form-control m-bot15" name="sexo" id="sexo" required>
                                        @if($regpadron->sexo == 'H')
                                            <option value="H" selected>Hombre </option>
                                            <option value="M">         Mujer  </option>
                                        @else
                                            <option value="H">         Hombre </option>
                                            <option value="M" selected>Mujer  </option>
                                        @endif
                                    </select>
                                </div>                                   
                            </div>                                

                            <div class="row">
                                <div class="col-xs-4 form-group">
                                    <label >Domicilio (Calle, no.ext/int.) </label>
                                    <input type="text" class="form-control" name="domicilio" id="domicilio" value="{{trim($regpadron->domicilio)}}" placeholder="Domicilio donde vive" required>
                                </div>
                                <div class="col-xs-4 form-group">
                                    <label >Colonia)</label>
                                    <input type="text" class="form-control" name="colonia" id="colonia" value="{{trim($regpadron->colonia)}}" placeholder="Colonia" required>
                                </div>
                                <div class="col-xs-4 form-group">
                                    <label >Código postal </label>
                                    <input type="number" max="99999" class="form-control" name="cp" id="cp" placeholder="Código postal" value="{{$regpadron->cp}}" required>
                                </div>                                                                                  
                            </div>

                            <div class="row">
                                <div class="col-xs-6 form-group">
                                    <label >Entre calle </label>
                                    <input type="text" class="form-control" name="entre_calle" id="entre_calle" placeholder="entre calle" value="{{$regpadron->entre_calle}}" required>
                                </div>       
                                <div class="col-xs-6 form-group">
                                    <label >Y calle </label>
                                    <input type="text" class="form-control" name="y_calle" id="y_calle" placeholder="y calle" value="{{$regpadron->y_calle}}" required>
                                </div>       
                                <div class="col-xs-6 form-group">
                                    <label >Otra referencia </label>
                                    <input type="text" class="form-control" name="otra_ref" id="otra_ref" placeholder="Otra referencia" value="{{$regpadron->otra_referencia}}" required>
                                </div>                                                                            
                            </div>                            

                            <div class="row">
                                <div class="col-xs-4 form-group">
                                    <label >Entidad de nacimiento </label>
                                    <select class="form-control m-bot15" name="entidad_nac_id" id="entidad_nac_id" required>
                                        <option selected="true" disabled="disabled">Seleccionar entidad</option>
                                        @foreach($regentidades as $enti)
                                            @if($enti->entidadfed_id == $regpadron->entidad_nac_id)
                                                <option value="{{$enti->entidadfed_id}}" selected>{{Trim($enti->entidadfed_desc)}}</option>
                                            @else                                        
                                               <option value="{{$enti->entidadfed_id}}">{{trim($enti->entidadfed_desc)}}</option>
                                            @endif
                                        @endforeach
                                    </select>                                  
                                </div>
                                <div class="col-xs-4 form-group">
                                    <label >Municipio </label>
                                    <select class="form-control m-bot15" name="municipio_id" id="municipio_id" required>
                                        <option selected="true" disabled="disabled">Seleccionar municipio</option>
                                        @foreach($regmunicipio as $muni)
                                            @if($muni->municipioid == $regpadron->municipio_id)
                                                <option value="{{$muni->municipioid}}" selected>{{Trim($muni->municipio)}}</option>
                                            @else                                        
                                               <option value="{{$muni->municipioid}}">{{trim($muni->municipio)}}</option>
                                            @endif
                                        @endforeach
                                    </select>                                  
                                </div>                                
                            </div>

                            <div class="row">
                                <div class="col-xs-6 form-group">
                                    <label >Localidad </label>
                                    <input type="text" class="form-control" name="localidad" id="localidad" placeholder="Localidad" value="{{Trim($regpadron->localidad)}}" required>
                                </div>            
                            </div>

                            <div class="row">                                
                                <div class="col-xs-4 form-group">
                                    <label >Teléfono </label>
                                    <input type="text" class="form-control" name="telefono" id="telefono" placeholder="Teléfono" value="{{$regpadron->telefono}}" required>
                                </div>                                                                 
                                <div class="col-xs-6 form-group">
                                    <label >Correo electrónico </label>
                                    <input type="text" class="form-control" name="email" id="email" placeholder="Correo electrónico" value="{{$regpadron->e_mail}}" required>
                                </div>                                                    
                            </div>

                            <div class="row">                                                                
                                <div class="col-xs-4 form-group">                        
                                    <label>Jugador Activo o Baja </label>
                                    <select class="form-control m-bot15" name="status_1" required>
                                        @if($regpadron->status_1 == 'S')
                                            <option value="S" selected>Activo </option>
                                            <option value="N">         Baja   </option>
                                        @else
                                            <option value="S">         Activo </option>
                                            <option value="N" selected>Baja   </option>
                                        @endif
                                    </select>
                                </div>                                                                  
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
                                    {!! Form::submit('Registrar',['class' => 'btn btn-success btn-flat pull-right']) !!}
                                    <a href="{{route('verpadron')}}" role="button" id="cancelar" class="btn btn-danger">Cancelar</a>
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
    {!! JsValidator::formRequest('App\Http\Requests\padronRequest','#actualizarpadron') !!}
@endsection

@section('javascrpt')
<script>
// https://es.stackoverflow.com/questions/31039/c%C3%B3mo-validar-una-curp-de-m%C3%A9xico
//Función para validar una CURP
function curpValida(curp) {
    var re = /^([A-Z][AEIOUX][A-Z]{2}\d{2}(?:0[1-9]|1[0-2])(?:0[1-9]|[12]\d|3[01])[HM](?:AS|B[CS]|C[CLMSH]|D[FG]|G[TR]|HG|JC|M[CNS]|N[ETL]|OC|PL|Q[TR]|S[PLR]|T[CSL]|VZ|YN|ZS)[B-DF-HJ-NP-TV-Z]{3}[A-Z\d])(\d)$/,
        validado = curp.match(re);
    
    if (!validado)  //Coincide con el formato general?
        return false;
    
    //Validar que coincida el dígito verificador
    function digitoVerificador(curp17) {
        //Fuente https://consultas.curp.gob.mx/CurpSP/
        var diccionario  = "0123456789ABCDEFGHIJKLMNÑOPQRSTUVWXYZ",
            lngSuma      = 0.0,
            lngDigito    = 0.0;
        for(var i=0; i<17; i++)
            lngSuma = lngSuma + diccionario.indexOf(curp17.charAt(i)) * (18 - i);
        lngDigito = 10 - lngSuma % 10;
        if (lngDigito == 10) return 0;
        return lngDigito;
    }
    if (validado[2] != digitoVerificador(validado[1])) 
        return false;       
    return true; //Validado
}

//Handler para el evento cuando cambia el input
//Lleva la CURP a mayúsculas para validarlo
function validarInput(input) {
    var curp = input.value.toUpperCase(),
        resultado = document.getElementById("resultado"),
        valido = "No válido";
        
    if (curpValida(curp)) { // ⬅️ Acá se comprueba
        valido = "Válido";
        resultado.classList.add("ok");
    } else {
        resultado.classList.remove("ok");
    }
    resultado.innerText = "CURP: " + curp + "\nFormato: " + valido;
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

