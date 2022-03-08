<!--
@extends('sicinar.mails.layoutemail')
-->
@component('mail::message')

@section('content')
    <head>
        <style>
        @page { margin-top: 50px; margin-bottom: 100px; margin-left: 50px; margin-right: 50px; }
        body{color: #767676;background: #fff;font-family: 'Open Sans',sans-serif;}
        #header { position: fixed; left: 0px; top: -20px; right: 0px; height: 375px; }
        #footer { position: fixed; left: 0px; bottom: -100px; right: 0px; height: 70px; text-align:right; font-size: 8px;}
        #footer .page:after { content: counter(page, upper-roman); }
        #content{ }   
        </style>
    </head>

    <body>
    <header id="header">
        <p style="border:0; font-family:'Arial, Helvetica, sans-serif'; font-size:11px; text-align:center;">
            <img src="{{ asset('images/Gobierno.png') }}" alt="EDOMEX" width="90px" height="55px" style="margin-right: 15px;" align="left"/>            
            &nbsp;&nbsp;GOBIERNO DEL ESTADO DE MÉXICO. TRÁMITE DE REGISTRO AL PADRÓN ESTATAL. 
            <img src="{{ asset('images/Edomex.png') }}" alt="EDOMEX" width="80px" height="55px" style="margin-left: 15px;" align="right"/>
        </p>
    </header>

    <section id="content">

        <table class="table table-hover table-striped" align="center" width="100%" style="width:700px;"> 
            <tr><td style="border:0;"></td></tr>
            <tr><td style="border:0;"></td></tr>

            <tr>
                <td style="border:0; text-align:right;font-size:12px;">Toluca de Lerdo, México a {{date('d')}} de {{strftime("%B")}} de {{date('Y')}}
                <br>
                CONSTANCIA DE REGISTRO NO. DGPS/<b>/{{date('Y')}}</b>
                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;


                </td>
            </tr>
            
            <tr>
                <td style="border:0; text-align:left;vertical-align: middle;font-size:12px;">
                    <p align="justify">
                    <b>
                    La Dirección General de Programas Sociales, 
                    Secretaría de Desarrollo Social del Gobierno del Estado de México, 
                    le da la más cordial bienida al Trámite del Registro al Padrón Estatal. <br>
                    Sus datos de registro son: </b><br> 
                    <b>
                    Folio de sistema:  <br>
                    Login de usuario:  <br>
                    Contraseña:  <br><br>

                    Realizó la gestión: <br>
                    Teléfono de contacto: <br>


                    </b>
                </td>
            </tr>
            <tr><td style="border:0;"></td></tr>
            
            <tr>
                <td style="border:0;text-align:center;font-size:12px;">
                    A T E N T A M E N T E <br>
                    <b>---------------------</b><br>
                    SECRETARIO EJECUTIVO DE LA DGPS
                </td>
            </tr>


        </table>
    </section>

    <footer id="footer">
        <table class="table table-hover table-striped" align="center" width="100%">
            <tr>
                <td style="border:0; text-align:left;font-size: 7px;">c.c.p. <b>Archivo</b></td>
            </tr>
            <tr>
                <td style="border:0; text-align:right;">
                    <b>SECRETARÍA DE DESARROLLO SOCIAL, GOBIERNO DEL ESTADO DE MÉXICO, </b>
                       DIRECCIÓN GENERAL DE PROGRAMAS SOCIALES.
                </td>
            </tr>            
        </table>
    </footer>    
    </body>
@endsection 

@section('javascrpt')
<!-- link de referencia de este ejmplo   http://www.ertomy.es/2018/07/generando-pdfs-con-laravel-5/ -->
<!-- si el PDF tiene varias páginas entonces hay que meter numeración de las paginas. 
     Para ello tendremos que poner el siguiente código en la plantilla: -->
<script type="text/php">
    $text = 'Página {PAGE_NUM} de {PAGE_COUNT}';
    $font = Font_Metrics::get_font("sans-serif");
    $pdf->page_text(493, 800, $text, $font, 7);
</script>
@endsection
