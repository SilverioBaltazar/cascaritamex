@extends('sicinar.pdf.layoutQuestion')

@section('content')
    <!--<h1 class="page-header">Listado de productos</h1>-->
    <table class="table table-hover table-striped" align="center">
        <thead>
        <tr>
            <th>
            <img src="{{ asset('images/escudo-edomexhor.jpg') }}" alt="EDOMEX" width="190px" height="50px" border="0" align="left"/>
            <img src="{{ asset('images/escudos-japem-edomexmazo.jpg') }}" alt="JAPEM" width="175px" height="50px" border="0" align="right"/>
            </th>
        </tr>
        </thead>
    </table>
    <br>
    <table class="table table-sm" align="center" style="border:0;">
        <thead>
        <tr>
            <th style="width:800px;text-align:center;">
            <p style="border:0; font-family:'HelveticaNeueLT Std'; font-size:12px; text-align:center;">
            CRITERIOS DE VERIFICACIÓN
            </p>
            </th>
        </tr>
        </thead>
    </table>
    <!-- :::::::::::::::::::::::APARTADO 1::::::::::::::::::::::::: -->
    <table class="table table-sm" align="center">
        <thead>
        <tr>
            <th colspan="8" style="width:800px;text-align:center;">
                <b style="color:white;">Apartado 1. Aspectos obligatorios</b>
            </th>
        </tr>
        <tr>
            <th style="background-color:darkred;text-align:center;">
                <b style="color:white;font-size: x-small;">No.</b>
            </th>
            <th style="background-color:darkred;text-align:center; width:300px;">
                <b style="color:white;font-size: x-small;">Pregunta</b>
            </th>
            <th style="background-color:darkred;text-align:center;">
                <b style="color:white;font-size: x-small;">Cumple</b>
            </th>
            <th style="background-color:darkred;text-align:center;">
                <b style="color:white;font-size: x-small;">Rubro</b>
            </th>
            <th style="background-color:darkred;text-align:center; ">
                <b style="color:white;font-size: x-small;">Observaciones</b>
            </th>
        </tr>
        </thead>

        <tbody>
        @foreach($regquestion as $pregunta)
            @if($pregunta->preg_id >= 1 AND $pregunta->preg_id <= 9)
                <tr>
                    <td style="text-align:center;vertical-align: middle;">
                        <b style="font-size: x-small;">{{$pregunta->preg_id}}</b>
                    </td>
                    <td style="text-align:justify;vertical-align: middle;width: 100px;">
                        <b style="color:black;font-size: xx-small;">{{$pregunta->preg_desc}}</b>
                    </td>
                    @foreach($regquestdili as $quest)
                        @if($quest->preg_id1 == $pregunta->preg_id)
                            @if($quest->p_resp1 == 'S')
                                <td style="color:darkgreen;text-align:center; vertical-align: middle;">Si</td>
                            @else
                                <td style="color:darkred; text-align:center; vertical-align: middle;" >No</td> 
                            @endif
                            <td style="text-align:center;vertical-align: middle;">
                                <b style="color:black;font-size: x-small;">
                                @foreach($regrubro as $rubro)
                                    @if($rubro->rubro_id == $pregunta->rubro_id)
                                        {{$rubro->rubro_desc}}
                                        @break
                                    @endif
                            @endforeach</b>
                            </td>
                            <td style="text-align:center;vertical-align: middle;">
                                <b style="color:black;font-size: x-small;">{{$quest->p_obs1}}</b>
                            </td>
                        @endif
                        @if($quest->preg_id2 == $pregunta->preg_id)
                            @if($quest->p_resp2 == 'S')
                                <td style="color:darkgreen;text-align:center; vertical-align: middle;">Si</td>
                            @else
                                <td style="color:darkred; text-align:center; vertical-align: middle;" >No</td> 
                            @endif
                            <td style="text-align:center;vertical-align: middle;">
                                <b style="color:black;font-size: x-small;">
                                @foreach($regrubro as $rubro)
                                    @if($rubro->rubro_id == $pregunta->rubro_id)
                                        {{$rubro->rubro_desc}}
                                        @break
                                    @endif
                            @endforeach</b>
                            </td>
                            <td style="text-align:center;vertical-align: middle;">
                                <b style="color:black;font-size: x-small;">{{$quest->p_obs2}}</b>
                            </td>
                        @endif
                        @if($quest->preg_id3 == $pregunta->preg_id)
                            @if($quest->p_resp3 == 'S')
                                <td style="color:darkgreen;text-align:center; vertical-align: middle;">Si</td>
                            @else
                                <td style="color:darkred; text-align:center; vertical-align: middle;" >No</td> 
                            @endif
                            <td style="text-align:center;vertical-align: middle;">
                                <b style="color:black;font-size: x-small;">
                                @foreach($regrubro as $rubro)
                                    @if($rubro->rubro_id == $pregunta->rubro_id)
                                        {{$rubro->rubro_desc}}
                                        @break
                                    @endif
                            @endforeach</b>
                            </td>
                            <td style="text-align:center;vertical-align: middle;">
                                <b style="color:black;font-size: x-small;">{{$quest->p_obs3}}</b>
                            </td>
                        @endif
                        @if($quest->preg_id4 == $pregunta->preg_id)
                            @if($quest->p_resp4 == 'S')
                                <td style="color:darkgreen;text-align:center; vertical-align: middle;">Si</td>
                            @else
                                <td style="color:darkred; text-align:center; vertical-align: middle;" >No</td> 
                            @endif
                            <td style="text-align:center;vertical-align: middle;">
                                <b style="color:black;font-size: x-small;">
                                @foreach($regrubro as $rubro)
                                    @if($rubro->rubro_id == $pregunta->rubro_id)
                                        {{$rubro->rubro_desc}}
                                        @break
                                    @endif
                            @endforeach</b>
                            </td>
                            <td style="text-align:center;vertical-align: middle;">
                                <b style="color:black;font-size: x-small;">{{$quest->p_obs4}}</b>
                            </td>
                        @endif
                        @if($quest->preg_id5 == $pregunta->preg_id)
                            @if($quest->p_resp5 == 'S')
                                <td style="color:darkgreen;text-align:center; vertical-align: middle;">Si</td>
                            @else
                                <td style="color:darkred; text-align:center; vertical-align: middle;" >No</td> 
                            @endif
                            <td style="text-align:center;vertical-align: middle;">
                                <b style="color:black;font-size: x-small;">
                                @foreach($regrubro as $rubro)
                                    @if($rubro->rubro_id == $pregunta->rubro_id)
                                        {{$rubro->rubro_desc}}
                                        @break
                                    @endif
                            @endforeach</b>
                            </td>
                            <td style="text-align:center;vertical-align: middle;">
                                <b style="color:black;font-size: x-small;">{{$quest->p_obs5}}</b>
                            </td>
                        @endif                                                                                             
                        @if($quest->preg_id6 == $pregunta->preg_id)
                            @if($quest->p_resp6 == 'S')
                                <td style="color:darkgreen;text-align:center; vertical-align: middle;">Si</td>
                            @else
                                <td style="color:darkred; text-align:center; vertical-align: middle;" >No</td> 
                            @endif
                            <td style="text-align:center;vertical-align: middle;">
                                <b style="color:black;font-size: x-small;">
                                @foreach($regrubro as $rubro)
                                    @if($rubro->rubro_id == $pregunta->rubro_id)
                                        {{$rubro->rubro_desc}}
                                        @break
                                    @endif
                            @endforeach</b>
                            </td>
                            <td style="text-align:center;vertical-align: middle;">
                                <b style="color:black;font-size: x-small;">{{$quest->p_obs6}}</b>
                            </td>
                        @endif
                        @if($quest->preg_id7 == $pregunta->preg_id)
                            @if($quest->p_resp7 == 'S')
                                <td style="color:darkgreen;text-align:center; vertical-align: middle;">Si</td>
                            @else
                                <td style="color:darkred; text-align:center; vertical-align: middle;" >No</td> 
                            @endif
                            <td style="text-align:center;vertical-align: middle;">
                                <b style="color:black;font-size: x-small;">
                                @foreach($regrubro as $rubro)
                                    @if($rubro->rubro_id == $pregunta->rubro_id)
                                        {{$rubro->rubro_desc}}
                                        @break
                                    @endif
                            @endforeach</b>
                            </td>
                            <td style="text-align:center;vertical-align: middle;">
                                <b style="color:black;font-size: x-small;">{{$quest->p_obs7}}</b>
                            </td>
                        @endif
                        @if($quest->preg_id8 == $pregunta->preg_id)
                            @if($quest->p_resp8 == 'S')
                                <td style="color:darkgreen;text-align:center; vertical-align: middle;">Si</td>
                            @else
                                <td style="color:darkred; text-align:center; vertical-align: middle;" >No</td> 
                            @endif
                            <td style="text-align:center;vertical-align: middle;">
                                <b style="color:black;font-size: x-small;">
                                @foreach($regrubro as $rubro)
                                    @if($rubro->rubro_id == $pregunta->rubro_id)
                                        {{$rubro->rubro_desc}}
                                        @break
                                    @endif
                            @endforeach</b>
                            </td>
                            <td style="text-align:center;vertical-align: middle;">
                                <b style="color:black;font-size: x-small;">{{$quest->p_obs8}}</b>
                            </td>
                        @endif
                        @if($quest->preg_id9 == $pregunta->preg_id)
                            @if($quest->p_resp9 == 'S')
                                <td style="color:darkgreen;text-align:center; vertical-align: middle;">Si</td>
                            @else
                                <td style="color:darkred; text-align:center; vertical-align: middle;" >No</td> 
                            @endif
                            <td style="text-align:center;vertical-align: middle;">
                                <b style="color:black;font-size: x-small;">
                                @foreach($regrubro as $rubro)
                                    @if($rubro->rubro_id == $pregunta->rubro_id)
                                        {{$rubro->rubro_desc}}
                                        @break
                                    @endif
                            @endforeach</b>
                            </td>
                            <td style="text-align:center;vertical-align: middle;">
                                <b style="color:black;font-size: x-small;">{{$quest->p_obs9}}</b>
                            </td>
                        @endif                                                                                                   
                    @endforeach
                </tr>
            @endif
            @if($pregunta->preg_id >= 10 AND $pregunta->preg_id <= 17)
                <tr>
                    <td style="text-align:center;vertical-align: middle;">
                        <b style="font-size: x-small;">{{$pregunta->preg_id}}</b>
                    </td>
                    <td style="text-align:justify;vertical-align: middle;width: 100px;">
                        <b style="color:black;font-size: xx-small;">{{$pregunta->preg_desc}}</b>
                    </td>
                    @foreach($regquestdili as $quest)
                        @if($quest->preg_id10 == $pregunta->preg_id)
                            @if($quest->p_resp10 == 'S')
                                <td style="color:darkgreen;text-align:center; vertical-align: middle;">Si</td>
                            @else
                                <td style="color:darkred; text-align:center; vertical-align: middle;" >No</td> 
                            @endif
                            <td style="text-align:center;vertical-align: middle;">
                                <b style="color:black;font-size: x-small;">
                                @foreach($regrubro as $rubro)
                                    @if($rubro->rubro_id == $pregunta->rubro_id)
                                        {{$rubro->rubro_desc}}
                                        @break
                                    @endif
                            @endforeach</b>
                            </td>
                            <td style="text-align:center;vertical-align: middle;">
                                <b style="color:black;font-size: x-small;">{{$quest->p_obs10}}</b>
                            </td>
                        @endif
                        @if($quest->preg_id11 == $pregunta->preg_id)
                            @if($quest->p_resp11 == 'S')
                                <td style="color:darkgreen;text-align:center; vertical-align: middle;">Si</td>
                            @else
                                <td style="color:darkred; text-align:center; vertical-align: middle;" >No</td> 
                            @endif
                            <td style="text-align:center;vertical-align: middle;">
                                <b style="color:black;font-size: x-small;">
                                @foreach($regrubro as $rubro)
                                    @if($rubro->rubro_id == $pregunta->rubro_id)
                                        {{$rubro->rubro_desc}}
                                        @break
                                    @endif
                            @endforeach</b>
                            </td>
                            <td style="text-align:center;vertical-align: middle;">
                                <b style="color:black;font-size: x-small;">{{$quest->p_obs11}}</b>
                            </td>
                        @endif
                        @if($quest->preg_id12 == $pregunta->preg_id)
                            @if($quest->p_resp12 == 'S')
                                <td style="color:darkgreen;text-align:center; vertical-align: middle;">Si</td>
                            @else
                                <td style="color:darkred; text-align:center; vertical-align: middle;" >No</td> 
                            @endif
                            <td style="text-align:center;vertical-align: middle;">
                                <b style="color:black;font-size: x-small;">
                                @foreach($regrubro as $rubro)
                                    @if($rubro->rubro_id == $pregunta->rubro_id)
                                        {{$rubro->rubro_desc}}
                                        @break
                                    @endif
                            @endforeach</b>
                            </td>
                            <td style="text-align:center;vertical-align: middle;">
                                <b style="color:black;font-size: x-small;">{{$quest->p_obs12}}</b>
                            </td>
                        @endif
                        @if($quest->preg_id13 == $pregunta->preg_id)
                            @if($quest->p_resp13 == 'S')
                                <td style="color:darkgreen;text-align:center; vertical-align: middle;">Si</td>
                            @else
                                <td style="color:darkred; text-align:center; vertical-align: middle;" >No</td> 
                            @endif
                            <td style="text-align:center;vertical-align: middle;">
                                <b style="color:black;font-size: x-small;">
                                @foreach($regrubro as $rubro)
                                    @if($rubro->rubro_id == $pregunta->rubro_id)
                                        {{$rubro->rubro_desc}}
                                        @break
                                    @endif
                            @endforeach</b>
                            </td>
                            <td style="text-align:center;vertical-align: middle;">
                                <b style="color:black;font-size: x-small;">{{$quest->p_obs13}}</b>
                            </td>
                        @endif
                        @if($quest->preg_id14 == $pregunta->preg_id)
                            @if($quest->p_resp14 == 'S')
                                <td style="color:darkgreen;text-align:center; vertical-align: middle;">Si</td>
                            @else
                                <td style="color:darkred; text-align:center; vertical-align: middle;" >No</td> 
                            @endif
                            <td style="text-align:center;vertical-align: middle;">
                                <b style="color:black;font-size: x-small;">
                                @foreach($regrubro as $rubro)
                                    @if($rubro->rubro_id == $pregunta->rubro_id)
                                        {{$rubro->rubro_desc}}
                                        @break
                                    @endif
                            @endforeach</b>
                            </td>
                            <td style="text-align:center;vertical-align: middle;">
                                <b style="color:black;font-size: x-small;">{{$quest->p_obs14}}</b>
                            </td>
                        @endif                                                                                             
                        @if($quest->preg_id15 == $pregunta->preg_id)
                            @if($quest->p_resp15 == 'S')
                                <td style="color:darkgreen;text-align:center; vertical-align: middle;">Si</td>
                            @else
                                <td style="color:darkred; text-align:center; vertical-align: middle;" >No</td> 
                            @endif
                            <td style="text-align:center;vertical-align: middle;">
                                <b style="color:black;font-size: x-small;">
                                @foreach($regrubro as $rubro)
                                    @if($rubro->rubro_id == $pregunta->rubro_id)
                                        {{$rubro->rubro_desc}}
                                        @break
                                    @endif
                            @endforeach</b>
                            </td>
                            <td style="text-align:center;vertical-align: middle;">
                                <b style="color:black;font-size: x-small;">{{$quest->p_obs15}}</b>
                            </td>
                        @endif
                        @if($quest->preg_id16 == $pregunta->preg_id)
                            @if($quest->p_resp16 == 'S')
                                <td style="color:darkgreen;text-align:center; vertical-align: middle;">Si</td>
                            @else
                                <td style="color:darkred; text-align:center; vertical-align: middle;" >No</td> 
                            @endif
                            <td style="text-align:center;vertical-align: middle;">
                                <b style="color:black;font-size: x-small;">
                                @foreach($regrubro as $rubro)
                                    @if($rubro->rubro_id == $pregunta->rubro_id)
                                        {{$rubro->rubro_desc}}
                                        @break
                                    @endif
                            @endforeach</b>
                            </td>
                            <td style="text-align:center;vertical-align: middle;">
                                <b style="color:black;font-size: x-small;">{{$quest->p_obs16}}</b>
                            </td>
                        @endif
                        @if($quest->preg_id17 == $pregunta->preg_id)
                            @if($quest->p_resp17 == 'S')
                                <td style="color:darkgreen;text-align:center; vertical-align: middle;">Si</td>
                            @else
                                <td style="color:darkred; text-align:center; vertical-align: middle;" >No</td> 
                            @endif
                            <td style="text-align:center;vertical-align: middle;">
                                <b style="color:black;font-size: x-small;">
                                @foreach($regrubro as $rubro)
                                    @if($rubro->rubro_id == $pregunta->rubro_id)
                                        {{$rubro->rubro_desc}}
                                        @break
                                    @endif
                            @endforeach</b>
                            </td>
                            <td style="text-align:center;vertical-align: middle;">
                                <b style="color:black;font-size: x-small;">{{$quest->p_obs17}}</b>
                            </td>
                        @endif      
                    @endforeach
                </tr>
            @endif

            @if($pregunta->preg_id >= 18 AND $pregunta->preg_id <= 31)
                <tr>
                    <td style="text-align:center;vertical-align: middle;">
                        <b style="font-size: x-small;">{{$pregunta->preg_id}}</b>
                    </td>
                    <td style="text-align:justify;vertical-align: middle;width: 100px;">
                        <b style="color:black;font-size: xx-small;">{{$pregunta->preg_desc}}</b>
                    </td>
                    @foreach($regquestdili as $quest)
                        @if($quest->preg_id18 == $pregunta->preg_id)
                            @if($quest->p_resp18 == 'S')
                                <td style="color:darkgreen;text-align:center; vertical-align: middle;">Si</td>
                            @else
                                <td style="color:darkred; text-align:center; vertical-align: middle;" >No</td> 
                            @endif
                            <td style="text-align:center;vertical-align: middle;">
                                <b style="color:black;font-size: x-small;">
                                @foreach($regrubro as $rubro)
                                    @if($rubro->rubro_id == $pregunta->rubro_id)
                                        {{$rubro->rubro_desc}}
                                        @break
                                    @endif
                            @endforeach</b>
                            </td>
                            <td style="text-align:center;vertical-align: middle;">
                                <b style="color:black;font-size: x-small;">{{$quest->p_obs18}}</b>
                            </td>
                        @endif
                        @if($quest->preg_id19 == $pregunta->preg_id)
                            @if($quest->p_resp19 == 'S')
                                <td style="color:darkgreen;text-align:center; vertical-align: middle;">Si</td>
                            @else
                                <td style="color:darkred; text-align:center; vertical-align: middle;" >No</td> 
                            @endif
                            <td style="text-align:center;vertical-align: middle;">
                                <b style="color:black;font-size: x-small;">
                                @foreach($regrubro as $rubro)
                                    @if($rubro->rubro_id == $pregunta->rubro_id)
                                        {{$rubro->rubro_desc}}
                                        @break
                                    @endif
                            @endforeach</b>
                            </td>
                            <td style="text-align:center;vertical-align: middle;">
                                <b style="color:black;font-size: x-small;">{{$quest->p_obs19}}</b>
                            </td>
                        @endif
                        @if($quest->preg_id20 == $pregunta->preg_id)
                            @if($quest->p_resp20 == 'S')
                                <td style="color:darkgreen;text-align:center; vertical-align: middle;">Si</td>
                            @else
                                <td style="color:darkred; text-align:center; vertical-align: middle;" >No</td> 
                            @endif
                            <td style="text-align:center;vertical-align: middle;">
                                <b style="color:black;font-size: x-small;">
                                @foreach($regrubro as $rubro)
                                    @if($rubro->rubro_id == $pregunta->rubro_id)
                                        {{$rubro->rubro_desc}}
                                        @break
                                    @endif
                            @endforeach</b>
                            </td>
                            <td style="text-align:center;vertical-align: middle;">
                                <b style="color:black;font-size: x-small;">{{$quest->p_obs20}}</b>
                            </td>
                        @endif
                        @if($quest->preg_id21 == $pregunta->preg_id)
                            @if($quest->p_resp21 == 'S')
                                <td style="color:darkgreen;text-align:center; vertical-align: middle;">Si</td>
                            @else
                                <td style="color:darkred; text-align:center; vertical-align: middle;" >No</td> 
                            @endif
                            <td style="text-align:center;vertical-align: middle;">
                                <b style="color:black;font-size: x-small;">
                                @foreach($regrubro as $rubro)
                                    @if($rubro->rubro_id == $pregunta->rubro_id)
                                        {{$rubro->rubro_desc}}
                                        @break
                                    @endif
                            @endforeach</b>
                            </td>
                            <td style="text-align:center;vertical-align: middle;">
                                <b style="color:black;font-size: x-small;">{{$quest->p_obs21}}</b>
                            </td>
                        @endif
                        @if($quest->preg_i22 == $pregunta->preg_id)
                            @if($quest->p_resp22 == 'S')
                                <td style="color:darkgreen;text-align:center; vertical-align: middle;">Si</td>
                            @else
                                <td style="color:darkred; text-align:center; vertical-align: middle;" >No</td> 
                            @endif
                            <td style="text-align:center;vertical-align: middle;">
                                <b style="color:black;font-size: x-small;">
                                @foreach($regrubro as $rubro)
                                    @if($rubro->rubro_id == $pregunta->rubro_id)
                                        {{$rubro->rubro_desc}}
                                        @break
                                    @endif
                            @endforeach</b>
                            </td>
                            <td style="text-align:center;vertical-align: middle;">
                                <b style="color:black;font-size: x-small;">{{$quest->p_obs22}}</b>
                            </td>
                        @endif                                                                                             
                        @if($quest->preg_id23 == $pregunta->preg_id)
                            @if($quest->p_resp23 == 'S')
                                <td style="color:darkgreen;text-align:center; vertical-align: middle;">Si</td>
                            @else
                                <td style="color:darkred; text-align:center; vertical-align: middle;" >No</td> 
                            @endif
                            <td style="text-align:center;vertical-align: middle;">
                                <b style="color:black;font-size: x-small;">
                                @foreach($regrubro as $rubro)
                                    @if($rubro->rubro_id == $pregunta->rubro_id)
                                        {{$rubro->rubro_desc}}
                                        @break
                                    @endif
                            @endforeach</b>
                            </td>
                            <td style="text-align:center;vertical-align: middle;">
                                <b style="color:black;font-size: x-small;">{{$quest->p_obs23}}</b>
                            </td>
                        @endif
                        @if($quest->preg_id24 == $pregunta->preg_id)
                            @if($quest->p_resp24 == 'S')
                                <td style="color:darkgreen;text-align:center; vertical-align: middle;">Si</td>
                            @else
                                <td style="color:darkred; text-align:center; vertical-align: middle;" >No</td> 
                            @endif
                            <td style="text-align:center;vertical-align: middle;">
                                <b style="color:black;font-size: x-small;">
                                @foreach($regrubro as $rubro)
                                    @if($rubro->rubro_id == $pregunta->rubro_id)
                                        {{$rubro->rubro_desc}}
                                        @break
                                    @endif
                            @endforeach</b>
                            </td>
                            <td style="text-align:center;vertical-align: middle;">
                                <b style="color:black;font-size: x-small;">{{$quest->p_obs24}}</b>
                            </td>
                        @endif
                        @if($quest->preg_id25 == $pregunta->preg_id)
                            @if($quest->p_resp25 == 'S')
                                <td style="color:darkgreen;text-align:center; vertical-align: middle;">Si</td>
                            @else
                                <td style="color:darkred; text-align:center; vertical-align: middle;" >No</td> 
                            @endif
                            <td style="text-align:center;vertical-align: middle;">
                                <b style="color:black;font-size: x-small;">
                                @foreach($regrubro as $rubro)
                                    @if($rubro->rubro_id == $pregunta->rubro_id)
                                        {{$rubro->rubro_desc}}
                                        @break
                                    @endif
                            @endforeach</b>
                            </td>
                            <td style="text-align:center;vertical-align: middle;">
                                <b style="color:black;font-size: x-small;">{{$quest->p_obs25}}</b>
                            </td>
                        @endif
                        @if($quest->preg_id26 == $pregunta->preg_id)
                            @if($quest->p_resp26 == 'S')
                                <td style="color:darkgreen;text-align:center; vertical-align: middle;">Si</td>
                            @else
                                <td style="color:darkred; text-align:center; vertical-align: middle;" >No</td> 
                            @endif
                            <td style="text-align:center;vertical-align: middle;">
                                <b style="color:black;font-size: x-small;">
                                @foreach($regrubro as $rubro)
                                    @if($rubro->rubro_id == $pregunta->rubro_id)
                                        {{$rubro->rubro_desc}}
                                        @break
                                    @endif
                            @endforeach</b>
                            </td>
                            <td style="text-align:center;vertical-align: middle;">
                                <b style="color:black;font-size: x-small;">{{$quest->p_obs26}}</b>
                            </td>
                        @endif
                        @if($quest->preg_id27 == $pregunta->preg_id)
                            @if($quest->p_resp27 == 'S')
                                <td style="color:darkgreen;text-align:center; vertical-align: middle;">Si</td>
                            @else
                                <td style="color:darkred; text-align:center; vertical-align: middle;" >No</td> 
                            @endif
                            <td style="text-align:center;vertical-align: middle;">
                                <b style="color:black;font-size: x-small;">
                                @foreach($regrubro as $rubro)
                                    @if($rubro->rubro_id == $pregunta->rubro_id)
                                        {{$rubro->rubro_desc}}
                                        @break
                                    @endif
                            @endforeach</b>
                            </td>
                            <td style="text-align:center;vertical-align: middle;">
                                <b style="color:black;font-size: x-small;">{{$quest->p_obs27}}</b>
                            </td>
                        @endif
                        @if($quest->preg_id28 == $pregunta->preg_id)
                            @if($quest->p_resp28 == 'S')
                                <td style="color:darkgreen;text-align:center; vertical-align: middle;">Si</td>
                            @else
                                <td style="color:darkred; text-align:center; vertical-align: middle;" >No</td> 
                            @endif
                            <td style="text-align:center;vertical-align: middle;">
                                <b style="color:black;font-size: x-small;">
                                @foreach($regrubro as $rubro)
                                    @if($rubro->rubro_id == $pregunta->rubro_id)
                                        {{$rubro->rubro_desc}}
                                        @break
                                    @endif
                            @endforeach</b>
                            </td>
                            <td style="text-align:center;vertical-align: middle;">
                                <b style="color:black;font-size: x-small;">{{$quest->p_obs28}}</b>
                            </td>
                        @endif                         
                        @if($quest->preg_id29 == $pregunta->preg_id)
                            @if($quest->p_resp29 == 'S')
                                <td style="color:darkgreen;text-align:center; vertical-align: middle;">Si</td>
                            @else
                                <td style="color:darkred; text-align:center; vertical-align: middle;" >No</td> 
                            @endif
                            <td style="text-align:center;vertical-align: middle;">
                                <b style="color:black;font-size: x-small;">
                                @foreach($regrubro as $rubro)
                                    @if($rubro->rubro_id == $pregunta->rubro_id)
                                        {{$rubro->rubro_desc}}
                                        @break
                                    @endif
                            @endforeach</b>
                            </td>
                            <td style="text-align:center;vertical-align: middle;">
                                <b style="color:black;font-size: x-small;">{{$quest->p_obs29}}</b>
                            </td>
                        @endif
                        @if($quest->preg_id30 == $pregunta->preg_id)
                            @if($quest->p_resp30 == 'S')
                                <td style="color:darkgreen;text-align:center; vertical-align: middle;">Si</td>
                            @else
                                <td style="color:darkred; text-align:center; vertical-align: middle;" >No</td> 
                            @endif
                            <td style="text-align:center;vertical-align: middle;">
                                <b style="color:black;font-size: x-small;">
                                @foreach($regrubro as $rubro)
                                    @if($rubro->rubro_id == $pregunta->rubro_id)
                                        {{$rubro->rubro_desc}}
                                        @break
                                    @endif
                            @endforeach</b>
                            </td>
                            <td style="text-align:center;vertical-align: middle;">
                                <b style="color:black;font-size: x-small;">{{$quest->p_obs30}}</b>
                            </td>
                        @endif                        
                        @if($quest->preg_id31 == $pregunta->preg_id)
                            @if($quest->p_resp31 == 'S')
                                <td style="color:darkgreen;text-align:center; vertical-align: middle;">Si</td>
                            @else
                                <td style="color:darkred; text-align:center; vertical-align: middle;" >No</td> 
                            @endif
                            <td style="text-align:center;vertical-align: middle;">
                                <b style="color:black;font-size: x-small;">
                                @foreach($regrubro as $rubro)
                                    @if($rubro->rubro_id == $pregunta->rubro_id)
                                        {{$rubro->rubro_desc}}
                                        @break
                                    @endif
                            @endforeach</b>
                            </td>
                            <td style="text-align:center;vertical-align: middle;">
                                <b style="color:black;font-size: x-small;">{{$quest->p_obs31}}</b>
                            </td>
                        @endif                        
                    @endforeach
                </tr>
            @endif
            @if($pregunta->preg_id >= 32 AND $pregunta->preg_id <= 47)
                <tr>
                    <td style="text-align:center;vertical-align: middle;">
                        <b style="font-size: x-small;">{{$pregunta->preg_id}}</b>
                    </td>
                    <td style="text-align:justify;vertical-align: middle;width: 100px;">
                        <b style="color:black;font-size: xx-small;">{{$pregunta->preg_desc}}</b>
                    </td>
                    @foreach($regquestdili as $quest)
                        @if($quest->preg_id32 == $pregunta->preg_id)
                            @if($quest->p_resp32 == 'S')
                                <td style="color:darkgreen;text-align:center; vertical-align: middle;">Si</td>
                            @else
                                <td style="color:darkred; text-align:center; vertical-align: middle;" >No</td> 
                            @endif
                            <td style="text-align:center;vertical-align: middle;">
                                <b style="color:black;font-size: x-small;">
                                @foreach($regrubro as $rubro)
                                    @if($rubro->rubro_id == $pregunta->rubro_id)
                                        {{$rubro->rubro_desc}}
                                        @break
                                    @endif
                            @endforeach</b>
                            </td>
                            <td style="text-align:center;vertical-align: middle;">
                                <b style="color:black;font-size: x-small;">{{$quest->p_obs32}}</b>
                            </td>
                        @endif
                        @if($quest->preg_id33 == $pregunta->preg_id)
                            @if($quest->p_resp33 == 'S')
                                <td style="color:darkgreen;text-align:center; vertical-align: middle;">Si</td>
                            @else
                                <td style="color:darkred; text-align:center; vertical-align: middle;" >No</td> 
                            @endif
                            <td style="text-align:center;vertical-align: middle;">
                                <b style="color:black;font-size: x-small;">
                                @foreach($regrubro as $rubro)
                                    @if($rubro->rubro_id == $pregunta->rubro_id)
                                        {{$rubro->rubro_desc}}
                                        @break
                                    @endif
                            @endforeach</b>
                            </td>
                            <td style="text-align:center;vertical-align: middle;">
                                <b style="color:black;font-size: x-small;">{{$quest->p_obs33}}</b>
                            </td>
                        @endif
                        @if($quest->preg_id34 == $pregunta->preg_id)
                            @if($quest->p_resp34 == 'S')
                                <td style="color:darkgreen;text-align:center; vertical-align: middle;">Si</td>
                            @else
                                <td style="color:darkred; text-align:center; vertical-align: middle;" >No</td> 
                            @endif
                            <td style="text-align:center;vertical-align: middle;">
                                <b style="color:black;font-size: x-small;">
                                @foreach($regrubro as $rubro)
                                    @if($rubro->rubro_id == $pregunta->rubro_id)
                                        {{$rubro->rubro_desc}}
                                        @break
                                    @endif
                            @endforeach</b>
                            </td>
                            <td style="text-align:center;vertical-align: middle;">
                                <b style="color:black;font-size: x-small;">{{$quest->p_obs34}}</b>
                            </td>
                        @endif
                        @if($quest->preg_id35 == $pregunta->preg_id)
                            @if($quest->p_resp35 == 'S')
                                <td style="color:darkgreen;text-align:center; vertical-align: middle;">Si</td>
                            @else
                                <td style="color:darkred; text-align:center; vertical-align: middle;" >No</td> 
                            @endif
                            <td style="text-align:center;vertical-align: middle;">
                                <b style="color:black;font-size: x-small;">
                                @foreach($regrubro as $rubro)
                                    @if($rubro->rubro_id == $pregunta->rubro_id)
                                        {{$rubro->rubro_desc}}
                                        @break
                                    @endif
                            @endforeach</b>
                            </td>
                            <td style="text-align:center;vertical-align: middle;">
                                <b style="color:black;font-size: x-small;">{{$quest->p_obs35}}</b>
                            </td>
                        @endif
                        @if($quest->preg_id36 == $pregunta->preg_id)
                            @if($quest->p_resp36 == 'S')
                                <td style="color:darkgreen;text-align:center; vertical-align: middle;">Si</td>
                            @else
                                <td style="color:darkred; text-align:center; vertical-align: middle;" >No</td> 
                            @endif
                            <td style="text-align:center;vertical-align: middle;">
                                <b style="color:black;font-size: x-small;">
                                @foreach($regrubro as $rubro)
                                    @if($rubro->rubro_id == $pregunta->rubro_id)
                                        {{$rubro->rubro_desc}}
                                        @break
                                    @endif
                            @endforeach</b>
                            </td>
                            <td style="text-align:center;vertical-align: middle;">
                                <b style="color:black;font-size: x-small;">{{$quest->p_obs36}}</b>
                            </td>
                        @endif                                                                                             
                        @if($quest->preg_id37 == $pregunta->preg_id)
                            @if($quest->p_resp37 == 'S')
                                <td style="color:darkgreen;text-align:center; vertical-align: middle;">Si</td>
                            @else
                                <td style="color:darkred; text-align:center; vertical-align: middle;" >No</td> 
                            @endif
                            <td style="text-align:center;vertical-align: middle;">
                                <b style="color:black;font-size: x-small;">
                                @foreach($regrubro as $rubro)
                                    @if($rubro->rubro_id == $pregunta->rubro_id)
                                        {{$rubro->rubro_desc}}
                                        @break
                                    @endif
                            @endforeach</b>
                            </td>
                            <td style="text-align:center;vertical-align: middle;">
                                <b style="color:black;font-size: x-small;">{{$quest->p_obs37}}</b>
                            </td>
                        @endif
                        @if($quest->preg_id38 == $pregunta->preg_id)
                            @if($quest->p_resp38 == 'S')
                                <td style="color:darkgreen;text-align:center; vertical-align: middle;">Si</td>
                            @else
                                <td style="color:darkred; text-align:center; vertical-align: middle;" >No</td> 
                            @endif
                            <td style="text-align:center;vertical-align: middle;">
                                <b style="color:black;font-size: x-small;">
                                @foreach($regrubro as $rubro)
                                    @if($rubro->rubro_id == $pregunta->rubro_id)
                                        {{$rubro->rubro_desc}}
                                        @break
                                    @endif
                            @endforeach</b>
                            </td>
                            <td style="text-align:center;vertical-align: middle;">
                                <b style="color:black;font-size: x-small;">{{$quest->p_obs38}}</b>
                            </td>
                        @endif
                        @if($quest->preg_id39 == $pregunta->preg_id)
                            @if($quest->p_resp39 == 'S')
                                <td style="color:darkgreen;text-align:center; vertical-align: middle;">Si</td>
                            @else
                                <td style="color:darkred; text-align:center; vertical-align: middle;" >No</td> 
                            @endif
                            <td style="text-align:center;vertical-align: middle;">
                                <b style="color:black;font-size: x-small;">
                                @foreach($regrubro as $rubro)
                                    @if($rubro->rubro_id == $pregunta->rubro_id)
                                        {{$rubro->rubro_desc}}
                                        @break
                                    @endif
                            @endforeach</b>
                            </td>
                            <td style="text-align:center;vertical-align: middle;">
                                <b style="color:black;font-size: x-small;">{{$quest->p_obs39}}</b>
                            </td>
                        @endif
                        @if($quest->preg_id40 == $pregunta->preg_id)
                            @if($quest->p_resp40 == 'S')
                                <td style="color:darkgreen;text-align:center; vertical-align: middle;">Si</td>
                            @else
                                <td style="color:darkred; text-align:center; vertical-align: middle;" >No</td> 
                            @endif
                            <td style="text-align:center;vertical-align: middle;">
                                <b style="color:black;font-size: x-small;">
                                @foreach($regrubro as $rubro)
                                    @if($rubro->rubro_id == $pregunta->rubro_id)
                                        {{$rubro->rubro_desc}}
                                        @break
                                    @endif
                            @endforeach</b>
                            </td>
                            <td style="text-align:center;vertical-align: middle;">
                                <b style="color:black;font-size: x-small;">{{$quest->p_obs40}}</b>
                            </td>
                        @endif 
                        @if($quest->preg_id41 == $pregunta->preg_id)
                            @if($quest->p_resp41 == 'S')
                                <td style="color:darkgreen;text-align:center; vertical-align: middle;">Si</td>
                            @else
                                <td style="color:darkred; text-align:center; vertical-align: middle;" >No</td> 
                            @endif
                            <td style="text-align:center;vertical-align: middle;">
                                <b style="color:black;font-size: x-small;">
                                @foreach($regrubro as $rubro)
                                    @if($rubro->rubro_id == $pregunta->rubro_id)
                                        {{$rubro->rubro_desc}}
                                        @break
                                    @endif
                            @endforeach</b>
                            </td>
                            <td style="text-align:center;vertical-align: middle;">
                                <b style="color:black;font-size: x-small;">{{$quest->p_obs41}}</b>
                            </td>
                        @endif
                        @if($quest->preg_id42 == $pregunta->preg_id)
                            @if($quest->p_resp42 == 'S')
                                <td style="color:darkgreen;text-align:center; vertical-align: middle;">Si</td>
                            @else
                                <td style="color:darkred; text-align:center; vertical-align: middle;" >No</td> 
                            @endif
                            <td style="text-align:center;vertical-align: middle;">
                                <b style="color:black;font-size: x-small;">
                                @foreach($regrubro as $rubro)
                                    @if($rubro->rubro_id == $pregunta->rubro_id)
                                        {{$rubro->rubro_desc}}
                                        @break
                                    @endif
                            @endforeach</b>
                            </td>
                            <td style="text-align:center;vertical-align: middle;">
                                <b style="color:black;font-size: x-small;">{{$quest->p_obs42}}</b>
                            </td>
                        @endif  
                        @if($quest->preg_id43 == $pregunta->preg_id)
                            @if($quest->p_resp43 == 'S')
                                <td style="color:darkgreen;text-align:center; vertical-align: middle;">Si</td>
                            @else
                                <td style="color:darkred; text-align:center; vertical-align: middle;" >No</td> 
                            @endif
                            <td style="text-align:center;vertical-align: middle;">
                                <b style="color:black;font-size: x-small;">
                                @foreach($regrubro as $rubro)
                                    @if($rubro->rubro_id == $pregunta->rubro_id)
                                        {{$rubro->rubro_desc}}
                                        @break
                                    @endif
                            @endforeach</b>
                            </td>
                            <td style="text-align:center;vertical-align: middle;">
                                <b style="color:black;font-size: x-small;">{{$quest->p_obs43}}</b>
                            </td>
                        @endif
                        @if($quest->preg_id44 == $pregunta->preg_id)
                            @if($quest->p_resp44 == 'S')
                                <td style="color:darkgreen;text-align:center; vertical-align: middle;">Si</td>
                            @else
                                <td style="color:darkred; text-align:center; vertical-align: middle;" >No</td> 
                            @endif
                            <td style="text-align:center;vertical-align: middle;">
                                <b style="color:black;font-size: x-small;">
                                @foreach($regrubro as $rubro)
                                    @if($rubro->rubro_id == $pregunta->rubro_id)
                                        {{$rubro->rubro_desc}}
                                        @break
                                    @endif
                            @endforeach</b>
                            </td>
                            <td style="text-align:center;vertical-align: middle;">
                                <b style="color:black;font-size: x-small;">{{$quest->p_obs44}}</b>
                            </td>
                        @endif                                                              
                        @if($quest->preg_id45 == $pregunta->preg_id)
                            @if($quest->p_resp45 == 'S')
                                <td style="color:darkgreen;text-align:center; vertical-align: middle;">Si</td>
                            @else
                                <td style="color:darkred; text-align:center; vertical-align: middle;" >No</td> 
                            @endif
                            <td style="text-align:center;vertical-align: middle;">
                                <b style="color:black;font-size: x-small;">
                                @foreach($regrubro as $rubro)
                                    @if($rubro->rubro_id == $pregunta->rubro_id)
                                        {{$rubro->rubro_desc}}
                                        @break
                                    @endif
                            @endforeach</b>
                            </td>
                            <td style="text-align:center;vertical-align: middle;">
                                <b style="color:black;font-size: x-small;">{{$quest->p_obs45}}</b>
                            </td>
                        @endif
                        @if($quest->preg_id46 == $pregunta->preg_id)
                            @if($quest->p_resp46 == 'S')
                                <td style="color:darkgreen;text-align:center; vertical-align: middle;">Si</td>
                            @else
                                <td style="color:darkred; text-align:center; vertical-align: middle;" >No</td> 
                            @endif
                            <td style="text-align:center;vertical-align: middle;">
                                <b style="color:black;font-size: x-small;">
                                @foreach($regrubro as $rubro)
                                    @if($rubro->rubro_id == $pregunta->rubro_id)
                                        {{$rubro->rubro_desc}}
                                        @break
                                    @endif
                            @endforeach</b>
                            </td>
                            <td style="text-align:center;vertical-align: middle;">
                                <b style="color:black;font-size: x-small;">{{$quest->p_obs46}}</b>
                            </td>
                        @endif                         
                        @if($quest->preg_id47 == $pregunta->preg_id)
                            @if($quest->p_resp47 == 'S')
                                <td style="color:darkgreen;text-align:center; vertical-align: middle;">Si</td>
                            @else
                                <td style="color:darkred; text-align:center; vertical-align: middle;" >No</td> 
                            @endif
                            <td style="text-align:center;vertical-align: middle;">
                                <b style="color:black;font-size: x-small;">
                                @foreach($regrubro as $rubro)
                                    @if($rubro->rubro_id == $pregunta->rubro_id)
                                        {{$rubro->rubro_desc}}
                                        @break
                                    @endif
                            @endforeach</b>
                            </td>
                            <td style="text-align:center;vertical-align: middle;">
                                <b style="color:black;font-size: x-small;">{{$quest->p_obs47}}</b>
                            </td>
                        @endif
                    @endforeach
                </tr>
            @endif
            @if($pregunta->preg_id >= 48 AND $pregunta->preg_id <= 58)
                <tr>
                    <td style="text-align:center;vertical-align: middle;">
                        <b style="font-size: x-small;">{{$pregunta->preg_id}}</b>
                    </td>
                    <td style="text-align:justify;vertical-align: middle;width: 100px;">
                        <b style="color:black;font-size: xx-small;">{{$pregunta->preg_desc}}</b>
                    </td>
                    @foreach($regquestdili as $quest)
                        @if($quest->preg_id48 == $pregunta->preg_id)
                            @if($quest->p_resp48 == 'S')
                                <td style="color:darkgreen;text-align:center; vertical-align: middle;">Si</td>
                            @else
                                <td style="color:darkred; text-align:center; vertical-align: middle;" >No</td> 
                            @endif
                            <td style="text-align:center;vertical-align: middle;">
                                <b style="color:black;font-size: x-small;">
                                @foreach($regrubro as $rubro)
                                    @if($rubro->rubro_id == $pregunta->rubro_id)
                                        {{$rubro->rubro_desc}}
                                        @break
                                    @endif
                            @endforeach</b>
                            </td>
                            <td style="text-align:center;vertical-align: middle;">
                                <b style="color:black;font-size: x-small;">{{$quest->p_obs48}}</b>
                            </td>
                        @endif
                        @if($quest->preg_id49 == $pregunta->preg_id)
                            @if($quest->p_resp49 == 'S')
                                <td style="color:darkgreen;text-align:center; vertical-align: middle;">Si</td>
                            @else
                                <td style="color:darkred; text-align:center; vertical-align: middle;" >No</td> 
                            @endif
                            <td style="text-align:center;vertical-align: middle;">
                                <b style="color:black;font-size: x-small;">
                                @foreach($regrubro as $rubro)
                                    @if($rubro->rubro_id == $pregunta->rubro_id)
                                        {{$rubro->rubro_desc}}
                                        @break
                                    @endif
                            @endforeach</b>
                            </td>
                            <td style="text-align:center;vertical-align: middle;">
                                <b style="color:black;font-size: x-small;">{{$quest->p_obs49}}</b>
                            </td>
                        @endif
                        @if($quest->preg_id50 == $pregunta->preg_id)
                            @if($quest->p_resp50 == 'S')
                                <td style="color:darkgreen;text-align:center; vertical-align: middle;">Si</td>
                            @else
                                <td style="color:darkred; text-align:center; vertical-align: middle;" >No</td> 
                            @endif
                            <td style="text-align:center;vertical-align: middle;">
                                <b style="color:black;font-size: x-small;">
                                @foreach($regrubro as $rubro)
                                    @if($rubro->rubro_id == $pregunta->rubro_id)
                                        {{$rubro->rubro_desc}}
                                        @break
                                    @endif
                            @endforeach</b>
                            </td>
                            <td style="text-align:center;vertical-align: middle;">
                                <b style="color:black;font-size: x-small;">{{$quest->p_obs50}}</b>
                            </td>
                        @endif
                        @if($quest->preg_id51 == $pregunta->preg_id)
                            @if($quest->p_resp51 == 'S')
                                <td style="color:darkgreen;text-align:center; vertical-align: middle;">Si</td>
                            @else
                                <td style="color:darkred; text-align:center; vertical-align: middle;" >No</td> 
                            @endif
                            <td style="text-align:center;vertical-align: middle;">
                                <b style="color:black;font-size: x-small;">
                                @foreach($regrubro as $rubro)
                                    @if($rubro->rubro_id == $pregunta->rubro_id)
                                        {{$rubro->rubro_desc}}
                                        @break
                                    @endif
                            @endforeach</b>
                            </td>
                            <td style="text-align:center;vertical-align: middle;">
                                <b style="color:black;font-size: x-small;">{{$quest->p_obs51}}</b>
                            </td>
                        @endif
                        @if($quest->preg_id52 == $pregunta->preg_id)
                            @if($quest->p_resp52 == 'S')
                                <td style="color:darkgreen;text-align:center; vertical-align: middle;">Si</td>
                            @else
                                <td style="color:darkred; text-align:center; vertical-align: middle;" >No</td> 
                            @endif
                            <td style="text-align:center;vertical-align: middle;">
                                <b style="color:black;font-size: x-small;">
                                @foreach($regrubro as $rubro)
                                    @if($rubro->rubro_id == $pregunta->rubro_id)
                                        {{$rubro->rubro_desc}}
                                        @break
                                    @endif
                            @endforeach</b>
                            </td>
                            <td style="text-align:center;vertical-align: middle;">
                                <b style="color:black;font-size: x-small;">{{$quest->p_obs52}}</b>
                            </td>
                        @endif                                                                                             
                        @if($quest->preg_id53 == $pregunta->preg_id)
                            @if($quest->p_resp53 == 'S')
                                <td style="color:darkgreen;text-align:center; vertical-align: middle;">Si</td>
                            @else
                                <td style="color:darkred; text-align:center; vertical-align: middle;" >No</td> 
                            @endif
                            <td style="text-align:center;vertical-align: middle;">
                                <b style="color:black;font-size: x-small;">
                                @foreach($regrubro as $rubro)
                                    @if($rubro->rubro_id == $pregunta->rubro_id)
                                        {{$rubro->rubro_desc}}
                                        @break
                                    @endif
                            @endforeach</b>
                            </td>
                            <td style="text-align:center;vertical-align: middle;">
                                <b style="color:black;font-size: x-small;">{{$quest->p_obs53}}</b>
                            </td>
                        @endif
                        @if($quest->preg_id54 == $pregunta->preg_id)
                            @if($quest->p_resp54 == 'S')
                                <td style="color:darkgreen;text-align:center; vertical-align: middle;">Si</td>
                            @else
                                <td style="color:darkred; text-align:center; vertical-align: middle;" >No</td> 
                            @endif
                            <td style="text-align:center;vertical-align: middle;">
                                <b style="color:black;font-size: x-small;">
                                @foreach($regrubro as $rubro)
                                    @if($rubro->rubro_id == $pregunta->rubro_id)
                                        {{$rubro->rubro_desc}}
                                        @break
                                    @endif
                            @endforeach</b>
                            </td>
                            <td style="text-align:center;vertical-align: middle;">
                                <b style="color:black;font-size: x-small;">{{$quest->p_obs54}}</b>
                            </td>
                        @endif
                        @if($quest->preg_id55 == $pregunta->preg_id)
                            @if($quest->p_resp55 == 'S')
                                <td style="color:darkgreen;text-align:center; vertical-align: middle;">Si</td>
                            @else
                                <td style="color:darkred; text-align:center; vertical-align: middle;" >No</td> 
                            @endif
                            <td style="text-align:center;vertical-align: middle;">
                                <b style="color:black;font-size: x-small;">
                                @foreach($regrubro as $rubro)
                                    @if($rubro->rubro_id == $pregunta->rubro_id)
                                        {{$rubro->rubro_desc}}
                                        @break
                                    @endif
                            @endforeach</b>
                            </td>
                            <td style="text-align:center;vertical-align: middle;">
                                <b style="color:black;font-size: x-small;">{{$quest->p_obs55}}</b>
                            </td>
                        @endif
                        @if($quest->preg_id56 == $pregunta->preg_id)
                            @if($quest->p_resp56 == 'S')
                                <td style="color:darkgreen;text-align:center; vertical-align: middle;">Si</td>
                            @else
                                <td style="color:darkred; text-align:center; vertical-align: middle;" >No</td> 
                            @endif
                            <td style="text-align:center;vertical-align: middle;">
                                <b style="color:black;font-size: x-small;">
                                @foreach($regrubro as $rubro)
                                    @if($rubro->rubro_id == $pregunta->rubro_id)
                                        {{$rubro->rubro_desc}}
                                        @break
                                    @endif
                            @endforeach</b>
                            </td>
                            <td style="text-align:center;vertical-align: middle;">
                                <b style="color:black;font-size: x-small;">{{$quest->p_obs56}}</b>
                            </td>
                        @endif 
                        @if($quest->preg_id57 == $pregunta->preg_id)
                            @if($quest->p_resp57 == 'S')
                                <td style="color:darkgreen;text-align:center; vertical-align: middle;">Si</td>
                            @else
                                <td style="color:darkred; text-align:center; vertical-align: middle;" >No</td> 
                            @endif
                            <td style="text-align:center;vertical-align: middle;">
                                <b style="color:black;font-size: x-small;">
                                @foreach($regrubro as $rubro)
                                    @if($rubro->rubro_id == $pregunta->rubro_id)
                                        {{$rubro->rubro_desc}}
                                        @break
                                    @endif
                            @endforeach</b>
                            </td>
                            <td style="text-align:center;vertical-align: middle;">
                                <b style="color:black;font-size: x-small;">{{$quest->p_obs57}}</b>
                            </td>
                        @endif
                        @if($quest->preg_id58 == $pregunta->preg_id)
                            @if($quest->p_resp58 == 'S')
                                <td style="color:darkgreen;text-align:center; vertical-align: middle;">Si</td>
                            @else
                                <td style="color:darkred; text-align:center; vertical-align: middle;" >No</td> 
                            @endif
                            <td style="text-align:center;vertical-align: middle;">
                                <b style="color:black;font-size: x-small;">
                                @foreach($regrubro as $rubro)
                                    @if($rubro->rubro_id == $pregunta->rubro_id)
                                        {{$rubro->rubro_desc}}
                                        @break
                                    @endif
                            @endforeach</b>
                            </td>
                            <td style="text-align:center;vertical-align: middle;">
                                <b style="color:black;font-size: x-small;">{{$quest->p_obs58}}</b>
                            </td>
                        @endif                              
                    @endforeach
                </tr>
            @endif


        @endforeach
        </tbody>
    </table>
    <!-- :::::::::::::::::::::::APARTADO 2::::::::::::::::::::::::: -->
    <!-- :::::::::::::::::::::::APARTADO 3::::::::::::::::::::::::: -->
    <!-- :::::::::::::::::::::::APARTADO 4::::::::::::::::::::::::: -->
    <!-- :::::::::::::::::::::::APARTADO 5::::::::::::::::::::::::: -->
    <table style="page-break-inside: avoid;" class="table table-sm" align="center">
        <tfoot>
        <tr>
            <td style="text-align:center;">
                <b style="font-size: x-small;">FIRMA DE QUIEN ATENDIO LA DILIGENCIA</b>
            </td>
        </tr>
        <tr>
            <td style="text-align:center;">
                <b style="text-align:center;font-size: x-small;">____________________________________</b>
            </td>
        </tr>
        </tfoot>
    </table>

@endsection