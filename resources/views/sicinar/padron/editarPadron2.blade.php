@extends('sicinar.principal')

@section('title','Editar archivo digital 2')

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
                        <p align="justify"><b style="color:red;">
                            Instrucciones:</b> <b style="color:green;">
                            El registro de jugadores del equipo es obligatorio. 
                            Para la digitalización de archivos se requieren en formato PDF y NO deberán ser mayores a 1,500 kBytes en tamaño.
                            </b>
                        </p>

                        {!! Form::open(['route' => ['actualizarpadron2',$regpadron->folio], 'method' => 'PUT', 'id' => 'actualizarpadron2', 'enctype' => 'multipart/form-data']) !!}
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
                            </div>
                            <div class="row">                                    
                                <div class="col-xs-10 form-group">
                                    <label style="color:green;">Jugador: </label>
                                    &nbsp;&nbsp;{{trim($regpadron->nombre_completo)}}
                                </div> 
                                <div class="col-xs-2 form-group">
                                    <input type="hidden" id="folio" name="folio" value="{{$regpadron->folio}}">  
                                    <label style="color:green;">Folio asignado: &nbsp;&nbsp;{{$regpadron->folio}} </label>
                                </div>    
                            </div>

                            @if (!empty($regpadron->arc_2)||!is_null($regpadron->arc_2))   
                                <div class="row">
                                    <div class="col-xs-6 form-group">
                                        <label >Archivo digital de identificación (Credencial INE) en formato PDF</label><br>
                                        <label ><a href="/storage/{{$regpadron->arc_2}}" class="btn btn-danger" title="Archivo digital de identificación (Credencial INE) en formato PDF"><i class="fa fa-file-pdf-o"></i>PDF
                                        </a>&nbsp;&nbsp;&nbsp;{{$regpadron->arc_2}}
                                        </label>
                                        <input type="hidden" name="doc_id15" id="doc_id15" value="19">
                                    </div>   
                                    <div class="col-xs-6 form-group">                                        
                                        <label>
                                        <iframe width="400" height="300" src="{{ asset('storage/'.$regpadron->arc_2)}}" frameborder="0"></iframe> 
                                        </label>                                       
                                    </div>                                        
                                </div>
                                <div class="row">
                                    <div class="col-xs-6 form-group">
                                        <label >Actualizar archivo digital identificación (Credencial INE) en formato PDF</label>
                                        <input type="file" class="text-md-center" style="color:red" name="arc_2" id="arc_2" placeholder="Subir archivo de digital de identificación (Credencial INE) en formato PDF" >
                                    </div>      
                                </div>
                            @else     <!-- se captura archivo 1 -->
                                <div class="row">                            
                                    <div class="col-xs-6 form-group">
                                        <label >Archivo digital de identificación (Credencial INE) en formato PDF</label>
                                        <input type="file" class="text-md-center" style="color:red" name="arc_2" id="arc_2" placeholder="Subir archivo digital de identificación (Credencial INE) en formato PDF" >
                                        <input type="hidden" name="doc_id15" id="doc_id15" value="19">
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
    {!! JsValidator::formRequest('App\Http\Requests\padron2Request','#actualizarpadron2') !!}
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

