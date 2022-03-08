@extends('sicinar.pdf.layout')

@section('content')
    <head>      
        <style>
        @page { margin-top: 30px; margin-bottom: 30px; margin-left: 50px; margin-right: 50px; } 
        body{color: #767676;background: #fff;font-family: 'Open Sans',sans-serif;font-size: 12px;}
        h1 {
        page-break-before: always;
        }

        #header { position: fixed; left: 0px; top: 0px; right: 0px; height: 375px; }
        #content{ 
                  left: 50px; top: 0px; margin-bottom: 0px; right: 50px;
                  border: solid 0px #000;
                  font: 1em arial, helvetica, sans-serif;
                  color: black; text-align:left;vertical-align: middle; width:1000px;}   
        #footer { position: fixed; left: 0px; bottom: -10px; right: 0px; height: 60px; text-align:right; font-size: 8px;}
        #footer .page:after { content: counter(page); }        
        </style>
    </head>
    <!--<h1 class="page-header">Listado de productos</h1>-->
    <body>
    <div id="header">
        <p style="border:0; font-family:'Arial, Helvetica, sans-serif'; font-size:11px; text-align:center;">
            <img src="{{ asset('images/Gobierno.png') }}" alt="EDOMEX" width="90px" height="55px" style="margin-right: 15px;" align="left"/>            
            &nbsp;&nbsp;FICHA DE REGISTRO DIGITAL AL TORNEO ESTATAL DE FUTBOL "CASCARITA MEXIQUENSE 2022" 
            <img src="{{ asset('images/Edomex.png') }}" alt="EDOMEX" width="80px" height="55px" style="margin-left: 15px;" align="right"/>
            <img src="{{ asset('images/imej.jpeg') }}" alt="IMEJ" width="100px" height="70px" style="margin-left: 15px;" align="right"/>
        </p>
    </div>

    <section id="content">
        <table class="table table-hover table-striped" align="center" width="100%"> 
            <tr>
                <td style="border:0;"></td>
                <td style="border:0;"></td>
                <td style="border:0;"></td>
            </tr>            
            <tr>
                <td style="border:0;"></td>
                <td style="border:0;"></td>
                <td style="border:0;"></td>
            </tr> 
            <tr>
                <td style="border:0;"></td>
                <td style="border:0;"></td>
                <td style="border:0;"></td>
            </tr> 
            
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
            <th style="background-color:darkgreen;text-align:center;vertical-align: middle;"><b style="color:white;font-size: x-small;">Jugador</b>
            </th>
            <th style="background-color:darkgreen;text-align:left;"><b style="color:white;font-size: x-small;">Fecha nac.</b>
            </th>
            <th style="background-color:darkgreen;text-align:left;"><b style="color:white;font-size: x-small;">CURP </b>
            </th>
            <th style="background-color:darkgreen;text-align:left;"><b style="color:white;font-size: x-small;">Celular</b>
            </th>
            <th style="background-color:darkgreen;text-align:left;"><b style="color:white;font-size: x-small;">Correo </b>
            </th>
            <th style="background-color:darkgreen;text-align:center;"><b style="color:white;font-size: x-small;">CURP </b>
            </th>
            <th style="background-color:darkgreen;text-align:center;"><b style="color:white;font-size: x-small;">INE </b>
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
                    <td style="text-align:center;vertical-align: middle;font-size:9px;"><p align="justify">
                    {{date("d/m/Y", strtotime($jugador->fecha_nacimiento))}}</p>
                    </td>
                    <td style="text-align:left;vertical-align: middle;font-size:9px;"><p align="justify">{{$jugador->curp}}</p>
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
                    <td style="text-align:center;vertical-align: middle;font-size:9px;">
                    <!-- {{Trim($jugador->arc_2)}} -->
                        @if(isset($jugador->arc_2))
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
        </tbody>
    </section>

    <footer id="footer">
        <table class="table table-hover table-striped" align="center" width="100%">
            <tr>
                <td style="border:0; text-align:right;">
                    <b>SECRETARIA DE DESARROLLO SOCIAL</b><br>INSTITUTO MEXIQUENSE DE LA JUVENTUD
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