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
            &nbsp;&nbsp;PROGRAMA MENSUAL DE VISITAS DE VERIFICACIÓN 
            <img src="{{ asset('images/Edomex.png') }}" alt="EDOMEX" width="80px" height="55px" style="margin-left: 15px;" align="right"/>
        </p>
    </div>

    
    <div id="content">               
        <p>..</p>
        <table class="table table-hover table-striped" align="center" width="100%">  
            <tr><td></td><td></td><td></td><td></td></tr>       
            <tr><td></td><td></td><td></td><td></td></tr>
            
            @foreach($regprogdil as $program)
            <tr>
                <td style="border:0; text-align:left;font-size:10px;">Periodo:<b>{{$program->periodo_id}}</b></td>
                <td style="border:0; text-align:center;font-size:10px;" >Mes:<b>
                     @foreach($regmeses as $mes)
                        @if($mes->mes_id == $program->mes_id)
                           {{Trim($mes->mes_desc)}} 
                           @break
                        @endif
                    @endforeach
                    </b>
                </td>
                <td style="border:0; text-align:right;font-size:10px;"  >Tipo de visita:<b>
                    @if($program->visita_tipo1 == '1')
                        Juridica
                    @else
                        @if($program->visita_tipo1 == '2')
                            Operativa
                         @else
                            @if($program->visita_tipo1 == '3')
                                Administrativa
                            @else      
                                General  
                            @endif
                         @endif
                    @endif
                    </b>
                </td>   
                <td style="text-align:right;"><b style="font-size:08px;"><b>Fecha de emisión: {!! date('d/m/Y') !!}</b></td>             
            </tr>
            @break
            @endforeach
        </table>

        <!-- ::::::::::::::::::::::: titulos ::::::::::::::::::::::::: -->
        <table class="table table-sm" align="center" >     
            <tr>
                <th style="background-color:darkgreen;text-align:center;vertical-align: middle;"><b style="color:white;font-size:09px;;">Fol.</b></th>
                <th style="background-color:darkgreen;text-align:left;"  ><b style="color:white;font-size:09px;">OSC</b></th>
                <th style="background-color:darkgreen;text-align:center;"><b style="color:white;font-size:09px;">No.<br>Registro</b></th>
                <th style="background-color:darkgreen;text-align:center;"><b style="color:white;font-size:09px;">RFC </b></th>
                <th style="background-color:darkgreen;text-align:center;"><b style="color:white;font-size:09px;">Domicilio   </b></th>
                <th style="background-color:darkgreen;text-align:center;"><b style="color:white;font-size:09px;">Entidad <br>Municipio </b></th>
                <th style="background-color:darkgreen;text-align:center;"><b style="color:white;font-size:09px;">Fecha<br>visita</b></th>
                <th style="background-color:darkgreen;text-align:center;"><b style="color:white;font-size:09px;">Hora<br>Prog. </b></th>
                <th style="background-color:darkgreen;text-align:center;"><b style="color:white;font-size:09px;">Contacto </b></th>
                <th style="background-color:darkgreen;text-align:center;"><b style="color:white;font-size:09px;">Objetivo </b></th>
            </tr>
            @foreach($regprogdil as $program) 
                <tr>
                    <td style="text-align:justify;vertical-align: middle;"><b style="font-size:07px;">
                        {{Trim($program->visita_folio)}}</b>
                    </td>
                    <td style="text-align:justify;vertical-align: middle;"><b style="font-size:07px;">
                        @foreach($regosc as $osc)
                            @if($osc->osc_id == $program->osc_id)
                                {{Trim($osc->osc_desc)}}
                                @break
                            @endif
                        @endforeach </b>
                    </td>                    
               
                    <td style="text-align:justify;vertical-align: middle;"><b style="font-size:07px;">
                        @foreach($regosc as $osc)
                            @if($osc->osc_id == $program->osc_id)
                                {{Trim($osc->osc_regcons)}}
                                @break
                            @endif
                        @endforeach </b>
                    </td>
                    <td style="text-align:justify;vertical-align: middle;"><b style="font-size:07px;width:07px;">                        
                        @foreach($regosc as $osc)
                            @if($osc->osc_id == $program->osc_id)
                                {{substr(Trim($osc->osc_rfc),0,20)}}
                                @break
                            @endif
                        @endforeach </b>
                    </td>                         
                    <td style="text-align:justify;vertical-align: middle;"><b style="font-size:07px;">
                        {{Trim($program->visita_dom)}}</b>
                    </td>
                    <td style="text-align:justify;vertical-align: middle;"><b style="font-size:07px;">                        
                        @foreach($regmunicipio as $municipio)
                            @if($municipio->municipioid == $program->municipio_id)
                                {{$municipio->entidadfederativa_desc.' '.$municipio->municipionombre}}
                                @break
                            @endif
                        @endforeach </b>
                    </td>                         
                    <td style="text-align:justify;vertical-align: middle;"><b style="font-size:07px;"> 
                        {{Trim($program->visita_fecregp)}}</b>
                    </td>  
                    <td style="text-align:justify;vertical-align: middle;"><b style="font-size:07px;">
                        @foreach($reghoras as $hora)
                            @if($hora->hora_id == $program->hora_id)
                                {{Trim($hora->hora_desc)}}
                                @break
                            @endif
                        @endforeach </b>
                    </td>  
                    <td style="text-align:justify;vertical-align: middle;"><b style="font-size:07px;">
                        {{Trim($program->visita_contacto)}}</b>
                    </td>         
                    <td style="text-align:justify;vertical-align: middle;"><b style="font-size:07px;width:300px;">
                        {{Trim($program->visita_obj).' '.Trim($program->visita_obs3)}}</b>
                    </td>                                                                        
                </tr>
            @endforeach
            
            <!--<p style="page-break-before: always;">--</p> -->
            <p style="page-break-inside: avoid;">++
                <div id="footer">
                    <p class="page">Page </p>
                    <p style="border:0; text-align:right;font-size:08px;">
                    <b>SECRETARIA DE DESARROLLO SOCIAL</b><br>DIRECCIÓN DE PROGRAMAS SOCIALES
                    </p>
                </div>  
            </p>
        </table> 
    </div>
    

    </body>  
</html>    
@endsection
