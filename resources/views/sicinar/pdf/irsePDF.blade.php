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
        <p style="border:0; font-family:'Arial, Helvetica, sans-serif'; font-size:11px; text-align:center;">
            <img src="{{ asset('images/Gobierno.png') }}" alt="EDOMEX" width="90px" height="55px" style="margin-right: 15px;" align="left"/>            
            &nbsp;&nbsp;INSCRIPCIÓN AL REGISTRO SOCIAL ESTATAL
            <img src="{{ asset('images/Edomex.png') }}" alt="EDOMEX" width="80px" height="55px" style="margin-left: 15px;" align="right"/>
        </p>
    </header>

    <div id="content1">
        <!--<p>the first page</p> -->
        <table class="table table-hover table-striped" align="center" width="100%" style="width:710px;"> 
            <tr><td style="border:0;"></td></tr>
            <tr><td style="border:0;"></td></tr>
            <tr><td style="border:0;"></td></tr>

            @foreach($regosc as $osc)
            <tr>
                <td style="border:0; text-align:right;font-size:13px;">
                    <!--
                    Toluca de Lerdo, México a {{date('d')}} de {{strftime("%B")}} de {{date('Y')}} <br>
                    -->
                    Oficio No. 21100012000000L/<b>{{$osc->osc_id}}/{{date('Y')}}/{{date('m')}}/{{date('d')}}</b>
                    <br><br>
                    <img src='https://barcode.tec-it.com/barcode.ashx?data={{$osc->osc_id}}&code=Code39&multiplebarcodes=false&translate-esc=false&unit=Fit&dpi=76&imagetype=Gif&rotation=0&color=%23000000&bgcolor=%23ffffff&codepage=&qunit=Mm&quiet=0' style="border:0;text-align:rigth; height:50px; width:80px;"/>
                </td>
            </tr>
            <tr>
                <td style="border:0; text-align:justify;vertical-align: middle;font-size:13px;">
                    <b>{{$osc->osc_replegal}} </b><br>
                    REPRESENTANTE LEGAL <br>
                    <b>P R E S E N T E.</b>
                    <br><br>
                    Por medio del presente me dirijo a usted muy respetuosamente y en atención a su solicitud electrónica y cumplimiento de los 
                    requisitos de Inscripción al Registro Social Estatal de la organización que representa y con base en lo siguiente: 
                    <br><br>
                    <b>CONSIDERANDO</b><br><br>
                    I.   Que la asociación: <br>
                         NOMBRE: &nbsp;&nbsp;<b>{{$osc->osc_desc}} </b><br>
                         DIRECCIÓN:&nbsp;   {{$osc->osc_calle.' '.$osc->osc_dom1}} <br>
                         RFC:  &nbsp;&nbsp;&nbsp;&nbsp;    {{$osc->osc_rfc}} <br>
                         COLONIA:&nbsp; {{$osc->osc_colonia}} 
                         <!--
                         C.P.: &nbsp;&nbsp;&nbsp;&nbsp;    {{$osc->osc_cp}} <br>
                         OBJETO SOCIAL <br>
                         <p align="justify">
                         {{$osc->osc_objsoc_1.' '.$osc->osc_objsoc_2}}
                         -->
                         <br><br>
                    II.  Que la asociación ha presentado la documentación establecida en el artículo 34 de la Ley de Desarrollo Social y 
                         ha dado cumplimiento a las acciones concertadas en su objeto social con base en los principios de la Política de 
                         Desarrollo Social; y <br><br>
                    III. Que con fundamento en lo dispuesto por los artículos 34 de la Ley de Desarrollo Social del Estado de México; 
                         34 fracción V, 35 y 36 de su Reglamento; y, considerando que es de interés del Gobierno del Estado de México 
                         promover la participación de la sociedad organizada en los Programas, Proyectos y Acciones de Desarrollo Social 
                         en beneficio de la población mexiquense, esta Dirección General de Bienestar Social y Fortalecimiento Familiar:
                </td>
            </tr>           
            @endforeach 
        </table>
    </div>
    <div id="footer1">
        <table class="table table-hover table-striped" align="center" width="100%">
            <tr>
                <td style="border:0; text-align:right; font-size:12px;">
                    <b>GOBIERNO DEL ESTADO DE MÉXICO, SECRETARÍA DE DESARROLLO SOCIAL</b><br>
                    DIRECCIÓN GENERAL DE PROGRAMAS SOCIALES<br>
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
            &nbsp;&nbsp;INSCRIPCIÓN AL REGISTRO SOCIAL ESTATAL
            <img src="{{ asset('images/Edomex.png') }}" alt="EDOMEX" width="80px" height="55px" style="margin-left: 15px;" align="right"/>
        </p>
    </header>

    <div id="content2">
        <table class="table table-hover table-striped" align="center" width="100%" style="width:710px;"> 
            <tr><td style="border:0;"></td></tr>
            <tr><td style="border:0;"></td></tr>
            <tr><td style="border:0;"></td></tr>

            @foreach($regosc as $osc)
            <tr>
                <td style="border:0; text-align:justify;vertical-align: middle;font-size:13px;">
                    <b>DETERMINA</b>
                    <br><br>
                    <b>PRIMERO.</b> 
                    Otorgar la Incripción al Registro Social Estatal a <b>{{$osc->osc_desc}}</b>
                    <br><br>
                    <b>SEGUNDO.</b> 
                    Que con fundamento en lo dispuesto en el artículo 37 de la Ley de Desarrollo Social, 
                    se le exhorta a la asociación civil a desempeñarse con apego a las disposiciones contenidas 
                    en dicho precepto legal y otras disposiciones jurídicas relativas y aplicables y que se deberá
                    sujetar a las visitas que se requieran para seguir verificando el cumplimiento del objeto social, 
                    en el referido periodo.
        
                    <br><br>
                    <b>TERCERO.</b> 
                    Se otroga en la ciudad de Toluca, Estado de México, a los {{date('d')}} días del mes 
                    de {{strftime("%B")}} del año {{date('Y')}}
                </td>
            </tr>           
            @endforeach 
        </table>

        <br><br>
        <table class="table table-hover table-striped" align="center" width="100%">
            <tr>
                <td style="border:0;font-size:14px; text-align:center;">
                    A T E N T A M E N T E   <br><br><br>
                    <b>DR. EDGAR TINOCO GONZÁLEZ</b><br>
                       DIRECTOR GENERAL
                </td>
            </tr>
        </table>

        <!-- ::::::::::::::::::::::: titulos del pie ::::::::::::::::::::::::: 
            Código QR  http://goqr.me/api/
                       http://goqr.me/api/doc/create-qr-code/
        -->
        <br>        
        <table class="table table-sm" align="center">       
            <tr>
                <td style="border:0;">
                    <br><br>
                </td>
                <td style="border:0;text-align:center;font-size:09px;">
                    <br><br>
                </td>
                <td style="border:0; text-align:right;font-size:08px;"><b>
                    <br>
                    Fecha de emisión: Toluca de Lerdo, México a {{date('d')}} de {{strftime("%B")}} de {{date('Y')}}                    
                   </b>
                </td>
            </tr>
        </table>

        <br><br>
        <table class="table table-sm" align="center">       
            <tr>
                <td style="border:0;text-align:left;  font-size:10px;"></td>
                <td style="border:0;text-align:center;font-size:09px;"></td>                
                <td align="right">
                    <img src = "https://api.qrserver.com/v1/create-qr-code/?data=http://187.216.191.87/&size=100x100" alt="" title="" align="right"/>
                </td>
            </tr>
        </table>                
    </div>

    <div id="footer2">
        <table class="table table-hover table-striped" width="100%">
            <tr>
                <td style="border:0; text-align:left;font-size:07px;">
                    c.c.p. Mtra. Martha Arriaga Ramírez. Directora de Programas Sociales Estatales. <br>
                    Archivo/minutario <br>
                    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; <br>
                    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                </td>            
                <td style="border:0; text-align:right; font-size:10px;">
                    <b>GOBIERNO DEL ESTADO DE MÉXICO, SECRETARÍA DE DESARROLLO SOCIAL</b><br>
                    DIRECCIÓN GENERAL DE PROGRAMAS SOCIALES<br>
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
