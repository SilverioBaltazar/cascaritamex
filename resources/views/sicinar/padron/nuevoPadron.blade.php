@extends('sicinar.principal')

@section('title','Nuevo Beneficiario')

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
    <meta charset="utf-8">
    <div class="content-wrapper">
        <section class="content-header">
            <h1><i class="fa fa-dashboard"></i>&nbsp;&nbsp;Menú.
                <small>2. Juagadores del equipo   </small>
                <small> - Nuevo </small>
            </h1> 
            <ol class="breadcrumb">
                <li><img src="{{ asset('images/b2.jpg') }}" border="0" width="30" height="30">Jugadores del equipo - Nuevo jugador</li> 
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
                        {!! Form::open(['route' => 'altanuevopadron', 'method' => 'POST','id' => 'nuevopadron', 'enctype' => 'multipart/form-data']) !!}
                        <div class="box-body">
                            <div class="row">
                                <div class="col-xs-6 form-group">
                                    <label >Equipo de Futbol  </label>
                                    <select class="form-control m-bot15" name="eq_id" id="eq_id" required>
                                        <option selected="true" disabled="disabled">Seleccionar equipo </option>
                                        @foreach($regficha as $ficha)
                                            <option value="{{$ficha->eq_id}}">{{$ficha->eq_id.' '.trim($ficha->eq_desc)}}</option>
                                        @endforeach
                                    </select>                                    
                                </div>                                  
                            </div>

                            <div class="row">
                                <div class="col-xs-4 form-group">
                                    <label >Apellido paterno </label>
                                    <input type="text" class="form-control" name="primer_apellido" id="primer_apellido" placeholder="Apellido paterno" onkeypress="return soloLetras(event)" required>
                                </div>  
                                <div class="col-xs-4 form-group">
                                    <label >Apellido materno </label>
                                    <input type="text" class="form-control" name="segundo_apellido" id="segundo_apellido" placeholder="Apellido materno" onkeypress="return soloAlfa(event)" required>
                                </div>
                                <div class="col-xs-4 form-group">
                                    <label >Nombre(s) </label>
                                    <input type="text" class="form-control" name="nombres" id="nombres" placeholder="Nombre(s)" onkeypress="return soloLetras(event)" required>
                                </div>
                            </div>

                            <div class="row">    
                                <div class="col-xs-4 form-group">
                                    <label >Fecha de nacimiento - Año </label>
                                    <select class="form-control m-bot15" name="periodo_id2" id="periodo_id2" required>
                                        <option selected="true" disabled="disabled">Seleccionar año de nacimiento </option>
                                        @foreach($reganios as $anio)
                                            <option value="{{$anio->anio_id}}">{{$anio->anio_desc}}</option>
                                        @endforeach
                                    </select>                                    
                                </div>   
                                <div class="col-xs-4 form-group">
                                    <label >Mes </label>
                                    <select class="form-control m-bot15" name="mes_id2" id="mes_id2" required>
                                        <option selected="true" disabled="disabled">Seleccionar mes de nacimiento </option>
                                        @foreach($regmeses as $mes)                                      
                                            <option value="{{$mes->mes_id}}">{{$mes->mes_desc}} </option>
                                        @endforeach
                                    </select>                                    
                                </div>    
                                <div class="col-xs-4 form-group">
                                    <label >Día </label>
                                    <select class="form-control m-bot15" name="dia_id2" id="dia_id2" required>
                                        <option selected="true" disabled="disabled">Seleccionar día de nacimiento </option>
                                        @foreach($regdias as $dia)
                                            <option value="{{$dia->dia_id}}">{{$dia->dia_desc}} </option>
                                        @endforeach
                                    </select>       
                                </div>                                    
                            </div>

                            <div class="row">
                                <div class="col-xs-4 form-group">
                                    <label >CURP </label>
                                    <input type="text" class="form-control" name="curp" id="curp" placeholder="CURP" oninput="validarInput(this)" required>
                                </div>   
                                <div class="col-xs-4 form-group">
                                    <label >Folio Credencial INE y/o cartilla militar </label>
                                    <input type="text" class="form-control" name="id_oficial" id="id_oficial" placeholder="Solo aplica para mayores de 18 años" required>
                                </div>   
                                <div class="col-md-4 offset-md-0"><p>
                                    <label >Sexo </label><br>
                                    <input type="radio" name="sexo" checked value="H" style="margin-right:5px;">Hombre
                                    <input type="radio" name="sexo"         value="M" style="margin-right:5px;">Mujer</p>
                                </div>                          
                            </div>                                
                            <div class="row">
                                <div class="col-xs-6 form-group">
                                    <label >Domicilio (Calle, no.ext/int.) </label>
                                    <input type="text" class="form-control" name="domicilio" id="domicilio" placeholder="Domicilio donde vive" required>
                                </div>
                                <div class="col-xs-6 form-group">
                                    <label >Colonia)</label>
                                    <input type="text" class="form-control" name="colonia" id="colonia" placeholder="Colonia" required>
                                </div>
                                <div class="col-xs-4 form-group">
                                    <label >Código postal </label>
                                    <input type="number" min="0" max="99999" class="form-control" name="cp" id="cp" placeholder="Código postal" required>
                                </div>                                    
                            </div>

                            <div class="row">
                                <div class="col-xs-6 form-group">
                                    <label >Entre calle </label>
                                    <input type="text" class="form-control" name="entre_calle" id="entre_calle" placeholder="entre calle" required>
                                </div>       
                                <div class="col-xs-6 form-group">
                                    <label >Y calle </label>
                                    <input type="text" class="form-control" name="y_calle" id="y_calle" placeholder="y calle" required>
                                </div>       
                                <div class="col-xs-6 form-group">
                                    <label >Otra referencia </label>
                                    <input type="text" class="form-control" name="otra_ref" id="otra_ref" placeholder="Otra referencia" required>
                                </div>                                                                            
                            </div>                            

                            <div class="row">
                                <div class="col-xs-4 form-group">
                                    <label >Entidad de nacimiento </label>
                                    <select class="form-control m-bot15" name="entidad_nac_id" id="entidad_nac_id" required>
                                        <option selected="true" disabled="disabled">Seleccionar entidad</option>
                                        @foreach($regentidades as $enti)
                                            <option value="{{$enti->entidadfed_id}}">{{trim($enti->entidadfed_desc)}}</option>
                                        @endforeach
                                    </select>                                  
                                </div>
                                <div class="col-xs-4 form-group">
                                    <label >Municipio </label>
                                    <select class="form-control m-bot15" name="municipio_id" id="municipio_id" required>
                                        <option selected="true" disabled="disabled">Seleccionar municipio</option>
                                        @foreach($regmunicipio as $muni)
                                            <option value="{{$muni->municipioid}}">{{trim($muni->municipio)}}</option>
                                        @endforeach
                                    </select>                                  
                                </div>                                
                            </div>

                            <div class="row">
                                <div class="col-xs-6 form-group">
                                    <label >Localidad </label>
                                    <input type="text" class="form-control" name="localidad" id="localidad" placeholder="Localidad" required>
                                </div>            
                            </div>

                            <div class="row">                                
                                <div class="col-xs-4 form-group">
                                    <label >Teléfono </label>
                                    <input type="text" class="form-control" name="telefono" id="telefono" placeholder="Teléfono" required>
                                </div>                                                            
                                <div class="col-xs-6 form-group">
                                    <label >Correo electrónico </label>
                                    <input type="text" class="form-control" name="email" id="email" placeholder="Correo electrónico" required>
                                </div>                                                            
                            </div>
                            <div class="row">
                                <div class="col-xs-12 form-group">
                                    <label style="background-color:yellow;color:red"><b>DOCUMENTOS A ADJUNTAR POR CADA JUGADOR EN UN SOLO ARCHIVO EN FORMATO PDF:</b>
                                    1. Copia de la CURP actualizada, 2. Copia de un comprobante de domicilio con una antigüedad no mayor a tres meses,
                                    3. Identificación oficial con fotografía. (Credencial para votar, en caso de ser menor de edad puede ser credencial escolar, 
                                    pasaporte, credencial de afiliación a una Institución de salud o Constancia de identidad emitida por autoridad Municipal de 
                                    residencia.), 4. Formato de registro de jugador con carta responsiva firmado por la persona 
                                    joven mayor de edad; así como de la persona responsable del equipo, 5. Formato de registro de jugador con carta responsiva 
                                    firmado por la persona joven menor de edad; por la madre, el padre o tutor (a); así como por la persona responsable del 
                                    equipo. (Descargable en la página.) y 6. Copia de la identificación oficial de la madre, padre o tutor (a) de la persona 
                                    joven menor de edad.
                                    &nbsp;&nbsp;El archivo digital, NO deberán ser mayor a 2,500 kBytes en tamaño.  </label>
                                </div>   
                            </div> 
                            <div class="row">
                                <div class="col-xs-4 form-group">
                                    <label >Archivo digital a adjuntar del jugador en formato PDF </label>
                                    <input type="file" class="text-md-center" style="color:red" name="arc_1" id="arc_1" placeholder="Subir archivo digital a adjuntar del jugador en formato PDF" >
                                </div>  
                            </div>

                            <div class="row">
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
    {!! JsValidator::formRequest('App\Http\Requests\padronRequest','#nuevoPadron') !!}
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

@endsection