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
            &nbsp;&nbsp;PROGRAMA DE TRABAJO ANUAL 
            <img src="{{ asset('images/Edomex.png') }}" alt="EDOMEX" width="80px" height="55px" style="margin-left: 15px;" align="right"/>
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
            
            @foreach($regprogtrab as $progtrab)
            <tr>
                <td style="border:0; text-align:left;font-size:10px;"  >
                    Folio:<b>{{$progtrab->folio}}  </b><br>
                    OSC: <b>
                    @foreach($regosc as $osc)
                        @if($osc->osc_id == $progtrab->osc_id)
                           {{Trim($osc->osc_desc)}} 
                           @break
                        @endif
                    @endforeach
                    </b>
                </td>
                <td style="border:0; text-align:center;font-size:10px;">
                    Periodo fiscal:<b>{{$progtrab->periodo_id}}</b><br>
                    Responsable:<b>{{trim($progtrab->responsable)}}</b>
                </td>
                <td style="border:0; text-align:right;font-size:10px;">
                    Fecha de elaboración:<b>{{$progtrab->fecha_elab2}}</b><br>
                    Fecha de emisión:<b> Toluca de Lerdo, México a {{date('d')}} de {{strftime("%B")}} de {{date('Y')}} </b>
                </td>
            </tr>
            @endforeach             
        </table>

        <!-- ::::::::::::::::::::::: titulos ::::::::::::::::::::::::: -->
        <table class="table table-sm" align="center">
        <thead>        
        <tr>
            <th style="background-color:darkgreen;text-align:center;vertical-align: middle;"><b style="color:white;font-size: x-small;">#</b>
            </th>
            <th style="background-color:darkgreen;text-align:center;vertical-align: middle;"><b style="color:white;font-size: x-small;">Programa</b>
            </th>
            <th style="background-color:darkgreen;text-align:left;"><b style="color:white;font-size: x-small;">Actividad</b>
            </th>
            <th style="background-color:darkgreen;text-align:left;"><b style="color:white;font-size: x-small;">Objetivo</b>
            </th>
            <th style="background-color:darkgreen;text-align:left;"><b style="color:white;font-size: x-small;">U. medida </b>
            </th>  
            <th style="background-color:darkgreen;text-align:left;"><b style="color:white;font-size: x-small;">Ene </b>
            </th>  
            <th style="background-color:darkgreen;text-align:left;"><b style="color:white;font-size: x-small;">Feb </b>
            </th>
            <th style="background-color:darkgreen;text-align:left;"><b style="color:white;font-size: x-small;">Mar</b>
            </th>
            <th style="background-color:darkgreen;text-align:left;"><b style="color:white;font-size: x-small;">Abr</b>
            </th>  
            <th style="background-color:darkgreen;text-align:left;"><b style="color:white;font-size: x-small;">May</b>
            </th>
            <th style="background-color:darkgreen;text-align:left;"><b style="color:white;font-size: x-small;">Jun</b>
            </th>
            <th style="background-color:darkgreen;text-align:left;"><b style="color:white;font-size: x-small;">Jul </b>
            </th>  
            <th style="background-color:darkgreen;text-align:left;"><b style="color:white;font-size: x-small;">Ago </b>
            </th>
            <th style="background-color:darkgreen;text-align:left;"><b style="color:white;font-size: x-small;">Sep</b>
            </th> 
            <th style="background-color:darkgreen;text-align:left;"><b style="color:white;font-size: x-small;">Oct</b>
            </th>  
            <th style="background-color:darkgreen;text-align:left;"><b style="color:white;font-size: x-small;">Nov</b>
            </th>
            <th style="background-color:darkgreen;text-align:left;"><b style="color:white;font-size: x-small;">Dic</b>
            </th>            
            <th style="background-color:darkgreen;text-align:left;"><b style="color:white;font-size: x-small;">Meta</b>
            </th>             
        </tr>
        </thead>

        <tbody>
            @foreach($regprogdtrab as $progdtrab)
                <tr>
                    <td style="text-align:center;vertical-align: middle;font-size:9px;"><p align="justify">{{$progdtrab->partida}}</p>
                    </td>
                    <td style="text-align:center;vertical-align: middle;font-size:9px;"><p align="justify">{{$progdtrab->programa_desc}}</p>
                    </td>
                    <td style="text-align:center;vertical-align: middle;font-size:9px;"><p align="justify">{{$progdtrab->actividad_desc}}</p>
                    </td>
                    <td style="text-align:left;vertical-align: middle;font-size:9px;"><p align="justify">{{$progdtrab->objetivo_desc}}</p>
                    </td>      
                    <td style="text-align:justify;vertical-align: middle;font-size:9px;">             
                    @foreach($regumedida as $umedida)
                        @if($umedida->umedida_id == $progdtrab->umedida_id)
                            {{trim($umedida->umedida_desc)}}        
                            @break
                        @endif
                    @endforeach       
                    </td>
                    <td style="text-align:center;vertical-align: middle;font-size:9px;">{{$progdtrab->mesp_01}}</b>
                    </td>
                    <td style="text-align:center;vertical-align: middle;font-size:9px;">{{$progdtrab->mesp_02}}</b>
                    </td>
                    <td style="text-align:center;vertical-align: middle;font-size:9px;">{{$progdtrab->mesp_03}}</b>
                    </td>
                    <td style="text-align:center;vertical-align: middle;font-size:9px;">{{$progdtrab->mesp_04}}</b>
                    </td>
                    <td style="text-align:center;vertical-align: middle;font-size:9px;">{{$progdtrab->mesp_05}}</b>
                    </td>
                    <td style="text-align:center;vertical-align: middle;font-size:9px;">{{$progdtrab->mesp_06}}</b>
                    </td>
                    <td style="text-align:center;vertical-align: middle;font-size:9px;">{{$progdtrab->mesp_07}}</b>
                    </td>
                    <td style="text-align:center;vertical-align: middle;font-size:9px;">{{$progdtrab->mesp_08}}</b>
                    </td>
                    <td style="text-align:center;vertical-align: middle;font-size:9px;">{{$progdtrab->mesp_09}}</b>
                    </td>
                    <td style="text-align:center;vertical-align: middle;font-size:9px;">{{$progdtrab->mesp_10}}</b>
                    </td>
                    <td style="text-align:center;vertical-align: middle;font-size:9px;">{{$progdtrab->mesp_11}}</b>
                    </td>
                    <td style="text-align:center;vertical-align: middle;font-size:9px;">{{$progdtrab->mesp_12}}</b>
                    </td>                   
                    <td style="text-align:center;vertical-align: middle;font-size:9px;">{{$progdtrab->meta_programada}}</b>
                    </td>                                                                                
                </tr>
            @endforeach
        </table>
       
        <!-- ::::::::::::::::::::::: titulos ::::::::::::::::::::::::: -->
        <table class="table table-sm" align="center">
        
            @foreach($regprogtrab as $progtrab)
            <tr>
                <td style="border:0;"></td>
                <td style="border:0;text-align:center;font-size:10px;">Elaboro<br><b>{{$progtrab->elaboro}}</b>
                </td>
                <td style="border:0;text-align:center;font-size:10px;">Autorizo<br><b>{{$progtrab->autorizo}}</b>
                </td>
                <td style="border:0;"></td>
            </tr>
            @endforeach         
        </table>
        </tbody>
    </section>

    <footer id="footer">
        <table class="table table-hover table-striped" align="center" width="100%">
            <tr>
                <td style="border:0; text-align:right;">
                    <b>SECRETARIA DE DESARROLLO SOCIAL</b><br>DIRECCIÓN GENERAL DE PROGRAMAS SOCIALES
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