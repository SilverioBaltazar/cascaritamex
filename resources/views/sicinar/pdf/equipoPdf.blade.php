@extends('sicinar.pdf.layout')

@section('content')
    <!--<page pageset='new' backtop='10mm' backbottom='10mm' backleft='20mm' backright='20mm' footer='page'> -->
    <head>
        
        <style>
        @page { margin-top: 50px; margin-bottom: 100px; margin-left: 50px; margin-right: 50px; }
        body{color: #767676;background: #fff;font-family: 'Open Sans',sans-serif;font-size: 12px;}
        #header1 { position: fixed; left: 0px; top: -20px; right: 0px; height: 375px; }
        #content1{ }   
        #footer1 { position: fixed; left: 0px; bottom: -100px; right: 0px; height: 80px; text-align:right; font-size: 8px;}
        #header2 { position: fixed; left: 0px; top: -20px; right: 0px; height: 375px; }
        #content2{ }   
        #footer2 { position: fixed; left: 0px; bottom: -100px; right: 0px; height: 80px; text-align:right; font-size: 8px;}
        #footer .page:after { content: counter(page, upper-roman); }
        </style>
        <!--
        <style>
        @page { margin: 180px 50px; }
        #header { position: fixed; left: 0px; top: -180px; right: 0px; height: 150px; background-color: orange; text-align: center; }
        #footer { position: fixed; left: 0px; bottom: -180px; right: 0px; height: 150px; background-color: lightblue; }
        #footer .page:after { content: counter(page, upper-roman); }
        </style>
        -->
    </head>
    
    <body>
     <header id="header1">
        <p style="border:0; font-family:'Arial, Helvetica, sans-serif'; font-size:13px; text-align:center;">
            <img src="{{ asset('images/Gobierno.png') }}" alt="EDOMEX" width="90px" height="55px" style="margin-right: 15px;" align="left"/>            
            &nbsp;&nbsp;TORNEO ESTATAL DE FUTBOL “CASCARITA MEXIQUENSE 2022”
            <img src="{{ asset('images/Edomex.png') }}" alt="EDOMEX" width="80px" height="55px" style="margin-left: 15px;" align="right"/>
            <img src="{{ asset('images/imej.jpeg') }}" alt="IMEJ" width="100px" height="65px" style="margin-left: 15px;" align="right"/>
        </p>
    </header>

    <div id="content1">
        <!--<p>the first page</p> -->
        <table class="table table-hover table-striped" align="center" width="100%"> 
            <tr><td style="border:0;"></td></tr>
            <tr><td style="border:0;"></td></tr>
            <tr><td style="border:0;"></td></tr>            
            <tr>
                <td width="100%">
                    <b style="border:0; text-align:left; font-size:13px;">
                    ASUNTO: CONFIRMACIÓN DE REGISTRO</b> 
                    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                    <b style="border:0; text-align:right; font-size:10px;">
                    Toluca de Lerdo, México a {{date('d')}} de {{strftime("%B")}} de {{date('Y')}} </b>
                </td>                
            </tr>     
            <tr>
                <td style="border:0; text-align:justify;vertical-align: middle;font-size:12px;">                    
                    Bienvenidos y bienvenidas al Torneo estatal de Futbol “Cascarita Mexiquense 2022”, no olvides que 
                    <b>esta ficha impresa es el pase de entrada a tu sede regional</b>, deberás presentarla acompañada 
                    de los siguientes requisitos para poder continuar con tu participación: <br><br>
                    Del equipo: <br>
                    <b>• Ficha digital de registro de equipo emitido por el sistema.</b><br>
                    De cada participante: <br>
                    <b>• 2 fotografías tamaño infantil</b>(a color o blanco y negro, de frente, ojos, nariz y boca 
                    descubiertos, recientes). <br>
                    <b>• Identificación oficial con fotografía vigente (original y una copia al 200%, legible).</b> 
                    Puede ser cualquiera de las siguientes: Credencial escolar, Credencial para votar, Pasaporte, 
                    Credencial de afiliación a una Institución de salud o Constancia de identidad emitida por autoridad 
                    Municipal de residencia.</b>
                    <b>• Formato de registro de jugador (a) con Carta responsiva</b> (mismo que descargaste y adjuntaste a 
                    tu registro en línea, en caso de ser menor de edad deberá ser acompañada de Copia de identificación 
                    oficial de Padre, madre o tutor (a). <br>
                    <b>• CURP (actualizado con código QR, copia legible).<b> <br><br>

                    <p style="border:0; text-align:justify;vertical-align: middle;font-size:8px;">
                    <b>Puntos importantes: </b><br>
                    
                    1. Una vez confirmado tu registro no habrá modificaciones de ningún tipo, no se podrán dar de baja 
                    y/o alta integrantes del equipo <b>bajo ninguna circunstancia</b>.<br>
                    2. Al llegar a tu sede regional deberás pasar inmediatamente a la <b>“Mesa de Registro”</b> para 
                    confirmar tu asistencia, entregar documentación y obtener derecho a <b>“Rol de juego”</b>. <br>
                    3. Solo las personas que cumplan con todos los requisitos y aparezcan en tu ficha de registro 
                    serán autorizadas para disputar partidos.<br>
                    4. Los equipos solo se conformarán de mínimo 7 y máximo 10 personas jugadoras de 14 a 29 años de 
                    edad (según la categoría a participar).<br>
                    5. No podrán jugar mayores de edad en equipos de categoría menor bajo ninguna circunstancia.<br>
                    6. No hay equipos mixtos.<br>
                    7. Solo se admite 1 persona como representante de equipo.<br>
                    8. El uso del cubrebocas es obligatorio para aquellas personas participantes que no se encuentren 
                    dentro de la cancha. <br>
                    Para cualquier duda o aclaración puedes contactarnos al número telefónico 5557704126
                    </p>
                    <br>
                    <b style="border:0; text-align:center;vertical-align: middle;font-size:12px;">
                    ATENTAMENTE <br>
                    INSTITUTO MEXIQUENSE DE LA JUVENTUD
                    </b>
                </td>
            </tr>
        </table>

        <!-- ::::::::::::::::::::::: titulos ::::::::::::::::::::::::: -->        
        <table class="table table-sm" align="center">        
            <tr>
                <td style="border:0;text-align:left;  font-size:10px;"> 
                @foreach($regficha as $ficha)
                    <img src='https://barcode.tec-it.com/barcode.ashx?data={{$ficha->eq_id}}&code=Code39&multiplebarcodes=false&translate-esc=false&unit=Fit&dpi=76&imagetype=Gif&rotation=0&color=%23000000&bgcolor=%23ffffff&codepage=&qunit=Mm&quiet=0' style="border:0;text-align:rigth; height:50px; width:90px;"/> 
                @endforeach 
                </td>
                <td style="border:0;text-align:center;font-size:09px;"> </td>                
                <td align="right">
                    <img src = "https://api.qrserver.com/v1/create-qr-code/?data=http://187.216.191.87/&size=90x90" alt="" title="" align="right" border"0"/>
                </td>
            </tr>
        </table> 

    </div>
    <div id="footer1">
        <table class="table table-hover table-striped" align="center" width="100%">
            <tr>
                <td style="border:0; text-align:right; font-size:10px;">
                    <b>SECRETARÍA DE DESARROLLO SOCIAL</b><br>
                    INSTITUTO MEXIQUENSE DE LA JUVENTUD<br>
                </td>
            </tr>
        </table>
        <p class="page">Page </p>
    </div>

    <p style="page-break-before: always;"></p> 
     <header id="header2">
        <p style="border:0; font-family:'Arial, Helvetica, sans-serif'; font-size:11px; text-align:center;">
            <img src="{{ asset('images/Gobierno.png') }}" alt="EDOMEX" width="90px" height="55px" style="margin-right: 15px;" align="left"/>            
            &nbsp;&nbsp;FICHA DE REGISTRO DIGITAL AL TORNEO ESTATAL DE FUTBOL "CASCARITA MEXIQUENSE 2022" 
            <img src="{{ asset('images/Edomex.png') }}" alt="EDOMEX" width="80px" height="55px" style="margin-left: 15px;" align="right"/>
            <img src="{{ asset('images/imej.jpeg') }}" alt="IMEJ" width="100px" height="70px" style="margin-left: 15px;" align="right"/>
        </p>
    </header>

    <div id="content2">
        <table class="table table-hover table-striped" align="center" width="100%"> 
            <tr><td style="border:0;"></td></tr>
            <tr><td style="border:0;"></td></tr>
            <tr><td style="border:0;"></td></tr>

            @foreach($regficha as $ficha)
            <tr>
                <td style="border:0; text-align:left;font-size:10px;" width="30%">
                    <b width="30%">Folio: &nbsp;{{$ficha->eq_id}}  </b><br>
                    <b width="30%"></b>&nbsp;<br>  
                    <b width="30%">Representante:&nbsp;{{trim($ficha->eq_nombrecomp_rep)}}</b><br> 
                    <b width="30%"></b>&nbsp;<br>  
                    <b width="30%">Fecha de registro:&nbsp;{{date("d/m/Y", strtotime($ficha->fec_reg))}}</b>
                </td>
                <td style="border:0; text-align:left;font-size:10px;" width="30%">
                    <b width="30%">Equipo:&nbsp;{{Trim($ficha->eq_desc)}} </b><br>
                    <b width="30%">Rama:&nbsp;{{trim($ficha->eq_rama)}}</b><br> 
                    <b width="30%">Teléfono:&nbsp;{{trim($ficha->eq_tel_rep)}}</b><br>
                    <b width="30%">Municipio:    &nbsp;
                    @foreach($regmunicipio as $muni)
                        @if($muni->municipioid == $ficha->municipio_id)
                           {{Trim($muni->municipio)}} 
                           @break
                        @endif
                    @endforeach
                    </b><br>                
                    <b width="30%"></b>&nbsp;<br>  
                </td>
                <td style="border:0; text-align:left;font-size:10px;" width="40%">
                    <b width="40%"></b>&nbsp;<br>  
                    <b width="40%">Categoria:    &nbsp;
                    @foreach($regcategoria as $cate)
                        @if($cate->cate_id == $ficha->cate_id)
                           {{Trim($cate->cate_desc)}} 
                           @break
                        @endif
                    @endforeach
                    </b><br>                   
                    <b width="40%">e-mail: &nbsp;<b>{{trim($ficha->eq_email_rep)}}</b><br>                 
                    <b width="40%">Sede regional:  &nbsp;
                    @foreach($regmunicipio as $muni)
                        @if($muni->municipioid == $ficha->municipio_id)
                           {{Trim($muni->desc_region).' '.Trim($muni->roma_region)}} 
                           @break
                        @endif
                    @endforeach
                    </b><br>
                    <b width="40%">Fecha de emisión: Toluca de Lerdo, México a {{date('d')}} de {{strftime("%B")}} de {{date('Y')}}</b>
                </td>
            </tr>
            @endforeach             
        </table>

        <!-- ::::::::::::::::::::::: titulos ::::::::::::::::::::::::: -->
        <table class="table table-sm" align="center">
        <thead>        
        <tr>
            <th style="background-color:darkgreen;text-align:center;vertical-align: middle;"><b style="color:white;font-size: x-small;"># </b>
            </th>
            <th style="background-color:darkgreen;text-align:left;vertical-align: middle;"><b style="color:white;font-size: x-small;">Jugador</b>
            </th>
            <th style="background-color:darkgreen;text-align:center;"><b style="color:white;font-size: x-small;">Edad </b>
            </th>            
            <th style="background-color:darkgreen;text-align:left;"><b style="color:white;font-size: x-small;">CURP </b>
            </th>
            <th style="background-color:darkgreen;text-align:left;"><b style="color:white;font-size: x-small;">Dirección (Calle, número y colonia)</b>
            </th>
            <th style="background-color:darkgreen;text-align:left;"><b style="color:white;font-size: x-small;">Municipio</b>
            </th>                        
            <th style="background-color:darkgreen;text-align:left;"><b style="color:white;font-size: x-small;">Celular</b>
            </th>
            <th style="background-color:darkgreen;text-align:left;"><b style="color:white;font-size: x-small;">Correo </b>
            </th>
            <th style="background-color:darkgreen;text-align:center;"><b style="color:white;font-size: x-small;">Doctos.<br>PDF</b>
            </th>
        </tr>
        </thead>

        <tbody>
            <?php $i  = 0; ?>
            @foreach($regpadron as $jugador)
                <?php $i  = $i + 1; ?> 
                <tr>
                    <td style="text-align:center;vertical-align: middle;font-size:9px;"><p align="justify">{{$i}}</p>
                    </td>
                    <td style="text-align:center;vertical-align: middle;font-size:9px;"><p align="justify">{{Trim($jugador->nombre_completo)}}</p>
                    </td>
                    <td style="text-align:center;vertical-align: middle;font-size:9px;"><p align="justify">{{$jugador->edad}}</p>
                    </td>                          
                    <td style="text-align:left;vertical-align: middle;font-size:9px;"><p align="justify">{{$jugador->curp}}</p>
                    </td>
                    <td style="text-align:center;vertical-align: middle;font-size:9px;"><p align="justify">
                    {{Trim($jugador->domicilio).' '.trim($jugador->colonia)}}</p>
                    </td>
                    <td style="text-align:left;vertical-align: middle;font-size:9px;"><p align="justify">
                        {{$jugador->municipio_id}}   
                        @foreach($regmunicipio as $mun)
                            @if($mun->municipioid == $jugador->municipio_id)
                                {{Trim($mun->municipio)}}
                                @break 
                            @endif
                        @endforeach 
                    </td>                     
                    <td style="text-align:center;vertical-align: middle;font-size:9px;">{{Trim($jugador->telefono)}}
                    </td>
                    <td style="text-align:center;vertical-align: middle;font-size:9px;">{{Trim($jugador->e_mail)}}
                    </td>
                    <td style="text-align:center;vertical-align: middle;font-size:9px;">
                    <!--{{Trim($jugador->arc_1)}} -->
                        @if(isset($jugador->arc_1))
                           <b style="color:green;">SI</b>
                        @else
                           <b style="color:red;">NO </b> 
                        @endif                    
                    </td>
                </tr>
            @endforeach
        </table>
       
        <!-- ::::::::::::::::::::::: titulos ::::::::::::::::::::::::: -->
        <table class="table table-hover table-striped" align="center" width="100%">
            <tr>
                <td style="text-align:left;vertical-align: middle;font-size:8px;">
                    NOTA: Presentar esta hoja impresa acompañada de la ficha de confirmación de registro que les llegará 
                    por correo electrónico a los equipos que cumplan con su proceso y cubran todos los requisitos en tiempo 
                    y forma. (Serán su pase de entrada a la sede regional).
                </td>
            </tr>            
        </table>

        <table class="table table-sm" align="center">        
            <tr>
                <td style="border:0;text-align:left;  font-size:10px;"> 
                @foreach($regficha as $ficha)
                    <img src='https://barcode.tec-it.com/barcode.ashx?data={{$ficha->eq_id}}&code=Code39&multiplebarcodes=false&translate-esc=false&unit=Fit&dpi=76&imagetype=Gif&rotation=0&color=%23000000&bgcolor=%23ffffff&codepage=&qunit=Mm&quiet=0' style="border:0;text-align:rigth; height:50px; width:90px;"/> 
                @endforeach 
                </td>
                <td style="border:0;text-align:center;font-size:09px;"> </td>                
                <td align="right">
                    <img src = "https://api.qrserver.com/v1/create-qr-code/?data=http://187.216.191.87/&size=100x100" alt="" title="" align="right" border"0"/>
                </td>
            </tr>
        </table> 

        <!--
        <p style="border:0; text-align:right;font-size:08px;">
            <b>Fecha de emisión: Toluca de Lerdo, México a {{date('d')}} de {{strftime("%B")}} de {{date('Y')}} </b>
        </p>
        -->

    </div>

    <div id="footer2">
        <table class="table table-hover table-striped" align="center" width="100%">
            <tr>
                <td style="border:0; text-align:right; font-size:10px;">
                    <b>SECRETARÍA DE DESARROLLO SOCIAL</b><br>
                    INSTITUTO MEXIQUENSE DE LA JUVENTUD<br>
                    <b style="border:0; text-align:left;font-size: 7px;">
                    ...
                    </b>
                </td>
            </tr>
        </table>
        <p class="page">Page </p>

    </div>    


    </body>
@endsection

@section('javascrpt')
<!-- link de referencia de este ejmplo   http://www.ertomy.es/2018/07/generando-pdfs-con-laravel-5/ -->
<!-- si el PDF tiene varias páginas entonces hay que meter numeración de las paginas. 
     Para ello tendremos que poner el siguiente código en la plantilla: 
<script type="text/php">
    $text = 'Página {PAGE_NUM} de {PAGE_COUNT}';
    $font = Font_Metrics::get_font("sans-serif");
    $pdf->page_text(493, 800, $text, $font, 7);
</script>
-->
<script type="text/php">
    if ( isset($pdf) ) {
        $font = Font_Metrics::get_font("helvetica", "bold");
        $pdf->page_text(72, 18, "Header: {PAGE_NUM} of {PAGE_COUNT}",
                        $font, 6, array(0,0,0));
    }
</script>  
@endsection
