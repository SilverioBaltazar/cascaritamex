@extends('sicinar.pdf.layout')

@section('content')
    <head>
        <style>
        @page { margin-top: 50px; margin-bottom: 100px; margin-left: 50px; margin-right: 50px; }
        body{color: #767676;background: #fff;font-family: 'Open Sans',sans-serif;}
        #header { position: fixed; left: 0px; top: -20px; right: 0px; height: 375px; }
        #footer { position: fixed; left: 0px; bottom: -100px; right: 0px; height: 90px; text-align:right; font-size: 8px;}
        #footer .page:after { content: counter(page, upper-roman); }
        #content{ }   
        </style>
    </head>

    <body>
    <header id="header">
        <p style="border:0; font-family:'Arial, Helvetica, sans-serif'; font-size:13px; text-align:center;">
            <img src="{{ asset('images/Gobierno.png') }}" alt="EDOMEX" width="90px" height="55px" style="margin-right: 15px;" align="left"/>            
            &nbsp;&nbsp;<b>OFICIO DE NOTIFICACIÓN </b>
            <img src="{{ asset('images/Edomex.png') }}" alt="EDOMEX" width="80px" height="55px" style="margin-left: 15px;" align="right"/>
        </p>
    </header>
  
    <section id="content">
        <table class="table table-hover table-striped" align="center" width="100%" >        
            <tr><td style="border:0;"></td></tr>
            <tr><td style="border:0;"></td></tr>
            @foreach($regprogdil as $program)
            <tr>
                <td style="border:0; text-align:right;font-size:13px;" width="50%">
                    Toluca de Lerdo, México a 
                    {{$program->dia_id}} de 
                    @foreach($regmeses as $mes)
                        @if($mes->mes_id == $program->mes_id)
                            {{strtolower(Trim($mes->mes_desc))}}
                            @break
                        @endif
                    @endforeach
                    de {{$program->periodo_id}} <br>
                    Oficio No. <b>{{$program->visita_oficio}}</b>
                    <br><br>
                    <img src='https://barcode.tec-it.com/barcode.ashx?data={{$program->visita_oficio}}&code=Code39&multiplebarcodes=false&translate-esc=false&unit=Fit&dpi=76&imagetype=Gif&rotation=0&color=%23000000&bgcolor=%23ffffff&codepage=&qunit=Mm&quiet=0' style="border:0;text-align:rigth; height:50px; width:180px;"/>
                </td>
            </tr>                      
            <tr>
                <td style="border:0; text-align:justify;vertical-align: middle;font-size:13px;">
                    <b>
                    @foreach($regosc as $osc)
                        @if($osc->osc_id == $program->osc_id)
                            {{trim($osc->osc_replegal)}} <br>
                            REPRESENTANTE LEGAL DE   <br>
                            "{{$program->osc_id.'-'.trim($osc->osc_desc)}}" <br>     
                            PRESENTE
                        @endif
                    @endforeach
                    </b>
                    <br><br>
                    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                    Por medio del presente, me dirijo a usted muy respetuosamente, a fin de hacer de su conocimiento que el próximo 
                    <b>{{$program->dia_id}}</b> de 
                    <b>
                    @foreach($regmeses as $mes)
                        @if($mes->mes_id == $program->mes_id)
                            {{strtolower(trim($mes->mes_desc))}}
                        @endif
                    @endforeach
                    </b>
                    a las 
                    <b>
                    @foreach($reghoras as $hora)
                        @if($hora->hora_id == $program->hora_id)
                            {{$hora->hora_desc}}
                        @endif
                    @endforeach
                    
                    </b> 
                    horas, acudirá la (el) 
                    <b>{{$program->visita_auditor2}}</b>                    
                    a las instalaciones de la organización a su digno cargo, con el fin de llevar a cabo una 
                    visita de verificación de cumplimiento del objeto social. 
                    <br><br>
                    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                    Asimismo, me permito solicitarle muy atentamente, brindar todas las facilidades necesarias para dicha actividad, 
                    misma que deberá ser asentada en el Acta de Visita de Verificación correspondiente.
                    <br><br>
                    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                    Lo anterior, con fundamento en lo establecido en los artículos 37 de la Ley de Desarrollo Social del Estado de México; 
                    34 fracción VIII del Reglamento de la Ley de Desarrollo Social del Estado de México; 15 fracciones XI y XII 
                    del Reglamento Interior de la Secretaría de Desarrollo Social; y numeral 21100010000300L viñeta décima del 
                    Manual General de Organización de la Secretaría de Desarrollo Social.
                    <br><br>
                    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                    Sin otro particular, no me queda más que reiterarle el testimonio de mi más alta y distinguida consideración. 
                </td>
            </tr>
            <tr><td style="border:0;"></td></tr>
            <tr><td style="border:0;"></td></tr>
            <tr><td style="border:0;"></td></tr>
            <tr>
                <td style="border:0;text-align:center;font-size:14px;">
                    A T E N T A M E N T E <br><br><br>
                    <b>{{Trim($program->visita_auditor4)}}</b><br>
                    DIRECTORA
                </td>
            </tr>
            @endforeach 
        </table>
        <br><br>
        <table class="table table-sm" align="center">       
            <tr>
                <td style="border:0;text-align:left;  font-size:10px;"> 
                @foreach($regprogdil as $program)
                    <img src='https://barcode.tec-it.com/barcode.ashx?data={{$program->visita_folio}}&code=Code39&multiplebarcodes=false&translate-esc=false&unit=Fit&dpi=76&imagetype=Gif&rotation=0&color=%23000000&bgcolor=%23ffffff&codepage=&qunit=Mm&quiet=0' style="border:0;text-align:rigth; height:50px; width:90px;"/>                                       
                @endforeach 
                </td>
                <td style="border:0;text-align:center;font-size:09px;"> </td>                
                <td align="right">
                    <img src = "https://api.qrserver.com/v1/create-qr-code/?data=http://187.216.191.87/&size=100x100" alt="" title="" align="right" border"0"/>
                </td>
            </tr>
        </table>
    </section>

    <footer id="footer">
        <table class="table table-hover table-striped" align="center" width="100%">
            <tr>
                <td style="border:0; text-align:left;font-size: 7px;">
                    c.c.p. Archivo/Minutario<br>
                    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;MAR/jmg. <br>
                    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; <br>
                    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                </td>
                <td style="border:0; text-align:right; font-size:13px;">
                    <b>DIRECCIÓN GENERAL DE BIENESTAR SOCIAL Y FORTALECIMIENTO FAMILIAR </b><br>
                       DIRECCIÓN DE PROGRAMAS SOCIALES ESTATALES. <br>
                    <b style="border:0; text-align:left;font-size: 7px;">
                    Paseo Tollocan No. 1003, Km. 52.5, Zona Industrial Toluca, Estado de México. C.P. 50160<br>
                    Tel.: 722 226 01 82, 84 y 85 ext. 5916
                    </b>
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
