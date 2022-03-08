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
            &nbsp;&nbsp;<b>ACTA DE VISITA DE VERIFICACIÓN</b>
            <img src="{{ asset('images/Edomex.png') }}" alt="EDOMEX" width="80px" height="55px" style="margin-left: 15px;" align="right"/>
        </p>
    </header>

    <div id="content1">
        <!--<p>the first page</p> -->
        <table class="table table-hover table-striped" align="center" width="100%"> 
            @foreach($regvisita as $visita)
            <tr><td style="border:0;"></td></tr>
            <tr><td style="border:0;"></td></tr>
            <tr><td style="border:0;"></td></tr>
            <tr>
                <td style="border:0; text-align:right; font-size:13px;">
                    Toluca de Lerdo, México a {{$visita->dia2_id}} de 
                    @foreach($regmeses as $mes)
                        @if($mes->mes_id == $visita->mes2_id)
                            {{strtolower(Trim($mes->mes_desc))}}
                            @break
                        @endif
                    @endforeach
                    de {{$visita->periodo2_id}} <br>
                    No.: <b>{{$visita->visita_oficio}}</b>
                    <br><br>
                    <img src='https://barcode.tec-it.com/barcode.ashx?data={{$visita->visita_oficio}}&code=Code39&multiplebarcodes=false&translate-esc=false&unit=Fit&dpi=76&imagetype=Gif&rotation=0&color=%23000000&bgcolor=%23ffffff&codepage=&qunit=Mm&quiet=0' style="border:0;text-align:rigth; height:50px; width:180px;"/> 
                </td>
            </tr>                        
            <tr>
                <td style="border:0; text-align:justify;vertical-align: middle;font-size:13px;">                    
                    En el municipio de <b>
                    @foreach($regmunicipio as $mun)
                        @if($mun->municipioid == $visita->municipio2_id)
                            {{$mun->municipionombre.', '.$mun->entidadfederativa_desc}}
                            @break
                        @endif
                    @endforeach
                    </b>; siendo las 
                    <b> 
                    @foreach($reghoras as $hora)
                        @if($hora->hora_id == $visita->hora2_id)
                            {{$hora->hora_desc}}
                            @break
                        @endif
                    @endforeach
                    </b> 
                    horas con
                    <b>
                    @foreach($regminutos as $min)
                        @if($min->num_id == $visita->num2_id)
                             {{$min->num_desc}}
                            @break                                    
                        @endif
                    @endforeach                    
                    </b>
                    minutos del día 
                    <b>
                    {{$visita->dia_id}}
                    </b> 
                    de 
                    <b> 
                    @foreach($regmeses as $mes)
                        @if($mes->mes_id == $visita->mes_id)
                            {{strtolower(Trim($mes->mes_desc))}}
                            @break
                        @endif
                    @endforeach
                    </b> 
                    de 
                    <b>
                    {{$visita->periodo_id.','}}
                    </b>
                    la (el) que suscribe C. <b>{{Trim($visita->visita_auditor2)}}</b>, servidora(or) pública(o) adscrito a la 
                    Dirección de Programas Sociales Estatales de la Secretaría de Desarrollo Social del Gobierno del Estado de México, 
                    y en cumplimiento al oficio de notificación No. <b>{{Trim($visita->visita_oficio)}}</b> de fecha 
                    {{$visita->dia_id}} 
                    de 
                    @foreach($regmeses as $mes)
                        @if($mes->mes_id == $visita->mes_id)
                            {{strtolower(Trim($mes->mes_desc))}}
                            @break
                        @endif
                    @endforeach
                    de {{$visita->periodo_id.';'}}

                    emitido por la (el) C. <b>{{Trim($visita->visita_auditor4)}} </b> Titular de la Dirección de Programas Sociales Estatales, 
                    hago constar que en la fecha y hora señalados me constituyo plena y legalmente en el domicilio donde ejecuta sus 
                    actividades la Organización de la Sociedad Civil (OSC) denominada:
                    <b> 
                    @foreach($regosc as $osc)
                        @if($osc->osc_id == $visita->osc_id)
                            {{Trim($osc->osc_desc.',')}}
                            @break
                        @endif
                    @endforeach
                    </b>
                    ubicada en: <b>{{Trim($visita->visita_dom)}}</b>, cerciorandome de ser el domicilio correcto.
                    <br>
                    <b style="border:0; text-align:center;vertical-align: middle;font-size:14px;">
                    ------------------------------------------------------------------ CONSTE --------------------------------------------------------
                    </b>
                    <br>
                    Acto continuo, con fundamento en los artículos 16 de la Constitución Política de los Estados Unidos Mexicanos y 128 
                    del Código de Procedimientos Administrativos del Estado de México, procedo a IDENTIFICARME con credencial oficial en 
                    favor de <b>{{$visita->visita_auditor2}}</b> ante quien dijo llamarse <b>{{$visita->visita_auditado2}}</b>
                    que se identifica como <b>{{$visita->visita_puesto1}}</b> de la OSC antes referida; quien en este acto se presenta
                    ante el suscrito y se identifica con <b>{{$visita->visita_ident1}}</b>. De la misma forma, se hace constar que 
                    intervienen como Testigos de Asistencia la (el) C. <b>{{$visita->visita_testigo1}}</b>, quien se identifica con
                    <b>{{$visita->visita_ident2}}</b>, y la (el) 
                    <b>{{$visita->visita_testigo2}}</b>, quien se identifica con <b>{{$visita->visita_ident3}}</b>; personas que fueron 
                    nombradas por <b>{{$visita->visita_auditado2}}</b> para fungir con tal calidad.
                    <br>
                    <b style="border:0; text-align:center;vertical-align: middle;font-size:14px;">
                    ------------------------------------------------------------------ CONSTE --------------------------------------------------------
                    </b>
                    <br>
                    A continuación, se procede a constatar el Objeto Social de la Organización de la Sociedad Civil de referencia, por lo 
                    que se solicita a quien se encuentra atendiendo la presente diligencia, que se otorguen las facilidades para la 
                    supervisión correspondiente, para lo cual se deberá permitir el acceso al lugar y/o la revisión de todos y cada uno de 
                    los elementos de información social que acrediten que se garantiza la interacción corresponsable de datos con la debida 
                    transparencia para la aplicación de recursos públicos ejercidos o por ejercer por la sociedad.
                    <br>
                    Es decir, mantener, a disposición de esta autoridad la información relativa a las actividades que realizan; lo anterior, 
                    con el fin de verificar que:
                    <br>
                    -   Se destina la totalidad de los recursos programados al cumplimiento de las acciones concertadas o respecto de las cuales se expida la constancia de objeto social.
                    <br>
                    -   Se promueve la capacitación y profesionalización de sus integrantes; y 
                    <br>
                    -   Se observan las obligaciones inherentes al cumplimiento de su objeto social en los términos de las disposiciones jurídicas relativas y aplicables. 
                </td>
            </tr>           
            @endforeach 
        </table>
    </div>
    <div id="footer1">
        <table class="table table-hover table-striped" align="center" width="100%">
            <tr>
                <td style="border:0; text-align:right; font-size:12px;">
                    <b>SECRETARÍA DE DESARROLLO SOCIAL</b><br>
                    DIRECCIÓN GENERAL DE BIENESTAR SOCIAL Y FORTALECIMIENTO FAMILIAR<br>
                    <b style="border:0; text-align:left;font-size: 7px;">
                    Paseo Tollocan No. 1003, Km. 52.5, Zona Industrial Toluca, Estado de México. C.P. 50160<br>
                    Tel.: 722 226 01 82, 84 y 85 ext. 5916
                    </b>
                </td>
            </tr>
        </table>
        <p class="page">Page </p>
    </div>

    <p style="page-break-before: always;"></p> 
     <header id="header2">
        <p style="border:0; font-family:'Arial, Helvetica, sans-serif'; font-size:11px; text-align:center;">
            <img src="{{ asset('images/Gobierno.png') }}" alt="EDOMEX" width="90px" height="55px" style="margin-right: 15px;" align="left"/>            
            &nbsp;&nbsp;<b>ACTA DE VISITA DE VERIFICACIÓN</b>
            <img src="{{ asset('images/Edomex.png') }}" alt="EDOMEX" width="80px" height="55px" style="margin-left: 15px;" align="right"/>
        </p>
    </header>

    <div id="content2">
        <table class="table table-hover table-striped" align="center" width="100%"> 
            <tr><td style="border:0;"></td></tr>
            <tr><td style="border:0;"></td></tr>
            <tr><td style="border:0;"></td></tr>

            @foreach($regvisita as $visita)
            <tr>
                <td style="border:0; text-align:justify;vertical-align: middle;font-size:12px;">
                    De acuerdo a los artículos 33, fracción IV y 37, fracciones II, III, VI y VIII de la Ley de Desarrollo Social 
                    del Estado de México; así como a los artículos 34 y 37 del Reglamento de la Ley de Desarrollo Social del Estado 
                    de México; 128 fracciones VI y VII del Código de Procedimientos Administrativos del Estado de México y Funciones 
                    IX y XII del Numeral 21100010000300L del Manual General de Organización de la Secretaría de Desarrollo Social.
                    Derivado del recorrido de instalaciones y de la revisión de documentos adicionales y actividades probatorias se 
                    desprende lo siguiente:
                    <br>
                    <b>{{$visita->visita_criterios}}</b>
                    <br>
                    En términos de lo dispuesto por el artículo 37 fracción VI de la Ley de Desarrollo Social del Estado de México y 
                    128 fracción VII del Código de Procedimientos Administrativos del Estado de México, la (el) suscrito hace constar que 
                    la Organización de la Sociedad Civil denominada: 
                    <b> 
                    @foreach($regosc as $osc)
                        @if($osc->osc_id == $visita->osc_id)
                            {{Trim($osc->osc_desc.';')}}
                        @endif
                    @endforeach
                    </b>
                    permitió la realización de la visita de verificación; de lo anterior al realizarse la supervisión se observa que:
                    <b>{{$visita->visita_visto}}</b>
                    <br>
                    <b style="border:0; text-align:center;vertical-align: middle;font-size:12px;">
                    ------------------------------------------------------------------ CONSTE --------------------------------------------------------
                    </b>
                    <br>
                    Hecho lo anterior, se hace del conocimiento a quien atiende la diligencia, que en este acto puede formular observaciones 
                    y ofrecer pruebas en relación a los hechos u omisiones que pudiera contener la presente acta o bien hacer uso de ese 
                    derecho. (En caso de no ser utilizado este espacio, cancelar) 
                    <br>
                    <b>{{$visita->visita_recomen}}</b>
                    <br>
                    Las actuaciones que preceden se realizan con fundamento en los artículos 33, 34 y 35 del Reglamento de la Ley antes citada,
                    así como en el artículo 15, fracción XI del Reglamento Interior de la Secretaría de Desarrollo Social, considerando que es 
                    de interés del Gobierno del Estado de México, promover la participación social organizada en los programas, proyectos y 
                    acciones de desarrollo social en beneficio de la población mexiquense. 
                    <br>
                    Se anexa a la presente acta, la reseña fotográfica del inmueble visitado, como constancia de la diligencia realizada, lo 
                    anterior para todos los efectos legales a que haya lugar.
                    <br>
                    Se da por concluida la presente diligencia, siendo las
                    <b>
                    @foreach($reghoras as $hora)
                        @if($hora->hora_id == $visita->hora2_id)
                            {{$hora->hora_desc}}
                        @endif
                    @endforeach
                    </b> 
                    horas con 
                    <b>
                    @foreach($regminutos as $min)
                        @if($min->num_id == $visita->num2_id)
                             {{$min->num_desc}}
                            @break                                    
                        @endif
                    @endforeach                    
                    </b>
                    minutos del día que se actúa, firmando al margen y al calce los que en ella intervinieron y así 
                    quisieron hacerlo, previa lectura y ratificación que de la misma hicieron.
                    <br>

                </td>
            </tr>           
            @endforeach 
        </table>
        <table class="table table-hover table-striped" align="center" width="100%">
            @foreach($regvisita as $visita)
            <tr>
                <td style="border:0;font-size:11px; text-align:center;">
                    POR LA SEDESEM  <br>
                    <b>{{$visita->visita_auditor2}}</b><br>
                    NOMBRE, ADSCRIPCIÓN Y FIRMA
                </td>
                <td style="border:0;font-size:11px; text-align:center;">
                    REPRESENTANTE DE LA A.C.<br>
                    <b>{{$visita->visita_auditado2}}</b><br>
                    NOMBRE Y FIRMA
                </td>
            </tr>

            <tr>
                <td style="border:0;font-size:11px; text-align:center;">
                    TESTIGO DE ASISTENCIA  <br>
                    <b>{{$visita->visita_testigo1}}</b><br>
                    NOMBRE Y FIRMA
                </td>
                <td style="border:0;font-size:11px; text-align:center;">
                    TESTIGO DE ASISTENCIA <br> 
                    <b>{{$visita->visita_testigo2}}</b><br>
                    NOMBRE Y FIRMA
                </td>
            </tr>
            @endforeach        
        </table>
        <br><br>
        <table class="table table-sm" align="center">       
            <tr>
                <td style="border:0;text-align:left;  font-size:10px;">
                @foreach($regvisita as $visita)
                    <img src='https://barcode.tec-it.com/barcode.ashx?data={{$visita->visita_folio}}&code=Code39&multiplebarcodes=false&translate-esc=false&unit=Fit&dpi=76&imagetype=Gif&rotation=0&color=%23000000&bgcolor=%23ffffff&codepage=&qunit=Mm&quiet=0' style="border:0;text-align:rigth; height:50px; width:90px;"/>                                       
                @endforeach                     
                </td>
                <td style="border:0;text-align:center;font-size:09px;"></td>                
                <td align="right">
                    <img src = "https://api.qrserver.com/v1/create-qr-code/?data=http://187.216.191.87/&size=100x100" alt="" title="" align="right"/>
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
        <table class="table table-hover table-striped" width="100%">
            <tr>
                <td style="border:0; text-align:right; font-size:12px;">
                    <b>SECRETARÍA DE DESARROLLO SOCIAL</b><br>
                    DIRECCIÓN GENERAL DE BIENESTAR SOCIAL Y FORTALECIMIENTO FAMILIAR<br>
                    <b style="border:0; text-align:left;font-size: 7px;">
                    Paseo Tollocan No. 1003, Km. 52.5, Zona Industrial Toluca, Estado de México. C.P. 50160<br>
                    Tel.: 722 226 01 82, 84 y 85 ext. 5916
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
