@extends('sicinar.pdf.layout')

@section('content')
<!DOCTYPE html>
<html>
    <!--
    <style>
        @page { margin: 180px 50px; }
        #header { position: fixed; left: 0px; top: -180px; right: 0px; height: 150px; background-color: orange; text-align: center; }
        #footer { position: fixed; left: 0px; bottom: -180px; right: 0px; height: 150px; background-color: lightblue; }
        #footer .page:after { content: counter(page, upper-roman); }
    </style>
    @page:right{ 
            @bottom-left {
            margin: 10pt 0 30pt 0;
            border-top: .25pt solid #666;
            content: "My book";
            font-size: 9pt;
            color: #333;
            }
        }
        table, figure {
            page-break-inside: avoid;
        }        
    #footer .page:after { content: counter(page); }
    #footer .page:after {content: "Page " counter(page) " of " counter(pages);}
    #content{ position: fixed; left: 0px; top: 0px; right: 0px; text-align:left;vertical-align: middle; width:1050px;}   
    -->
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
                  color: black; text-align:left;vertical-align: middle; width:700px;}   
        #footer { position: fixed; left: 0px; bottom: -10px; right: 0px; height: 60px; text-align:right; font-size: 8px;}
        #footer .page:after { content: counter(page); }        
        </style>
    </head>
    <!--<h1 class="page-header">Listado de productos</h1>-->
    <body>
    <div id="header">
        <p style="border:0; font-family:'Arial, Helvetica, sans-serif'; font-size:11px; text-align:center;">
            <img src="{{ asset('images/Gobierno.png') }}" alt="EDOMEX" width="90px" height="55px" style="margin-right: 15px;" align="left"/>            
            &nbsp;&nbsp;CEDULA: DETECCIÓN DE NECESIDADES
            <img src="{{ asset('images/Edomex.png') }}" alt="EDOMEX" width="80px" height="55px" style="margin-left: 15px;" align="right"/>
        </p>
    </div>

    
    <div id="content">               
        <p>..</p>
        <table class="table table-hover table-striped" align="center" width="100%">  
            <tr><td></td><td></tr>       
            <tr><td></td><td></tr>
            
            @foreach($regcedula as $cedula)
            <tr>
                <td style="border:0; text-align:left;font-size:10px;"  >Periodo:<b>{{$cedula->periodo_id}}</b></td>
                <td style="border:0; text-align:center;font-size:10px;">IAP:<b>
                     @foreach($regiap as $iap)
                        @if($iap->iap_id == $cedula->iap_id)
                           {{Trim($iap->iap_desc)}} 
                           @break
                        @endif
                    @endforeach
                    </b>
                </td>
                <td style="border:0; text-align:right;font-size:10px;">Folio:<b>{{$cedula->cedula_folio}} </b></td>   
            </tr>
            <tr>
                <td style="border:0; text-align:left;font-size:10px;"   >Fecha de elaboración:<b>{{$cedula->cedula_fecha2}} </b> </td>
                <td style="border:0; text-align:center;font-size:10px;" >Responsable:<b>{{$cedula->sp_nomb}} </b></td>   
                <td style="text-align:right;"><b style="font-size:08px;"><b>Fecha de emisión: {!! date('d/m/Y') !!}</b></td>             
            </tr>
            @break
            @endforeach
        </table>

        <!-- ::::::::::::::::::::::: titulos ::::::::::::::::::::::::: -->
        <table class="table table-sm" align="center" >     
            <tr>
                <th style="background-color:darkgreen;text-align:left;"  ><b style="color:white;font-size:09px;;">#</b></th>
                <th style="background-color:darkgreen;text-align:left;"  ><b style="color:white;font-size:09px;">Artículo</b></th>
                <th style="background-color:darkgreen;text-align:center;"><b style="color:white;font-size:09px;">Cantidad</b></th>
                <th style="background-color:darkgreen;text-align:left;"  ><b style="color:white;font-size:09px;">Tipo<br>Artículo</b></th>
            </tr>
            @php
            $tipo_id_ant = 0;
            @endphp
            @foreach($regcedulaarti as $cedulaarti) 
                @foreach($regarticulos as $articulo)
                    @if($articulo->articulo_id == $cedulaarti->articulo_id)
                        $tipo_id = $articulo->tipo_id
                        @break
                        @if($tipo_id_ant == $tipo_id)
                        @else
                            <tr>
                                <td colspan="3" style="text-align:justify;vertical-align: middle;"><b style="color:orange;font-size:09px;width:10px;">
                                {{$articulo->tipo_id}}
                                @foreach($regarticulos as $articulo)
                                    @if($articulo->articulo_id == $cedulaarti->articulo_id)
                                        {{Trim($articulo->tipo_desc)}}
                                        @break
                                    @endif
                                @endforeach </b>
                                </td>    
                            </tr>
                            @php
                            $tipo_id_ant = $tipo_id;
                            @endphp
                        @endif

                    @endif
                @endforeach
                <tr>
                    <td style="text-align:justify;vertical-align: middle;"><b style="font-size:09px;">
                        {{$cedulaarti->cedula_partida}}</b>
                    </td>
                    <td style="text-align:justify;vertical-align: middle;"><b style="font-size:09px;">
                        @foreach($regarticulos as $articulo)
                            @if($articulo->articulo_id == $cedulaarti->articulo_id)
                                {{Trim($articulo->articulo_desc)}}
                                @break
                            @endif
                        @endforeach </b>
                    </td>                    
               
                    <td style="text-align:center;">
                        <b style="font-size:09px;">{{number_format($cedulaarti->articulo_cantidad,0)}} </b>    
                    </td>
                    <td style="text-align:left"><b style="color:orange;font-size:09px;width:10px;">                        
                        @foreach($regarticulos as $articulo)
                            @if($articulo->articulo_id == $cedulaarti->articulo_id)
                                {{Trim($articulo->tipo_desc)}}
                                @break
                            @endif
                        @endforeach </b>
                    </td>                         
                </tr>
            @endforeach
            
            <!--<p style="page-break-before: always;">--</p> -->
            <p style="page-break-inside: avoid;">++
                <div id="footer">
                    <p class="page">Page </p>
                    <p style="border:0; text-align:right;font-size:08px;">
                    <b>SECRETARIA DE DESARROLLO SOCIAL</b><br>JUNTA DE ASISTENCIA PRIVADA DEL ESTADO DE MÉXICO
                    </p>
                </div>  
            </p>
        </table> 
    </div>
    

    </body>  
</html>    
@endsection
