@extends('sicinar.pdf.layoutActavisita')

@section('content')
    <page pageset='new' backtop='10mm' backbottom='10mm' backleft='20mm' backright='20mm' footer='page'>
    <page_header id="header">
        <table class="table table-hover table-striped" align="center" width="100%">
            <tr>
                <td style="border:0; text-align:left;">
                    <img src="{{ asset('images/Gobierno.png') }}" alt="EDOMEX" width="75px" height="55px"/>
                </td>
                <td style="border:0; text-align:right;">
                    <img src="{{ asset('images/japem.jpg') }}"  alt="JAPEM"  width="70px" height="55px"/>
                    <img src="{{ asset('images/Edomex.png') }}" alt="EDOMEX" width="60px" height="55px"/>
                </td>
            </tr>
        </table>                
    </page_header>
    
    <!--<h1>Documento de pruebas para PDF</h1>  -->
        <p style="border:0; text-align:right;">NÚMERO DE VISITA DE VERIFICACIÓN: W/________/JAPEM/_____</p>
        <p style="border:0; text-align:center;">ACTA DE VISITA DE VERIFICACIÓN</p>
        <br>
        <ul>
            @foreach($regvisita as $visita)
            <p style="border:0; font-size: x-small; text-align:left;vertical-align: middle;">
                    En el municipio de <b>
                    @foreach($regmunicipio as $mun)
                        @if($mun->municipioid == $visita->municipio2_id)
                            {{$mun->municipionombre.', '.$mun->entidadfederativa_desc}}
                        @endif
                    @endforeach
                    </b>; siendo las <b> 
                    @foreach($reghoras as $hora)
                        @if($hora->hora_id == $visita->hora2_id)
                            {{$hora->hora_desc}}
                        @endif
                    @endforeach
                    </b> horas del día <b>{{date('d')}}</b> de <b> {{strftime("%B")}}</b> de <b>{{date('Y')}}</b>; me constituí en las instalaciones de la asociación y/o fundación denominada: <b> 
                    @foreach($regiap as $iap)
                        @if($iap->iap_id == $visita->iap_id)
                            {{Trim($iap->iap_desc)}}
                        @endif
                    @endforeach
                    </b>, I.A.P., con número de registro <b> 
                    @foreach($regiap as $iap)
                        @if($iap->iap_id == $visita->iap_id)
                            {{$iap->iap_regcons}}
                        @endif
                    @endforeach
                    </b>, ubicada en: <b>{{$visita->visita_dom}}</b>, en cumplimiento del mandamiento escrito número JAP/SE/______/__________, emitido por el Secretario Ejecutivo de la Junta de Asistencia Privada del Estado de México, en el ejercicio de las funciones y atribuciones que confiere el artículo 24 fracciones II y III de la Ley de Instituciones de Asistencia Privada del Estado de México y con fundamento en el artículo 16 de la Constitución Política de los Estados Unidos Mexicanos y los artículos 64 inciso a) fracciones II, III y XII, inciso b) fracción IV, 72,73,74,92,93,94 y 95 de la Ley de Instituciones de Asistencia Privada del Estado de México, y 128 del Código de Procedimientos Administrativos del Estado de México,  con  el objeto de practicar la visita de verificación, así como el funcionamiento, condiciones y cumplimiento del objeto de la institución de Asistencia Privada que se visita; mandamiento escrito que exhibo y del cual dejo copia simple con la persona que atiende la presente diligencia, procedo a practicar la visita de verificación correspondiente, cuyo objeto lo es constatar el cumplimiento de las disposiciones de la ley de Instituciones de Asistencia Privada; por lo que una vez cerciorado plenamente de ser el domicilio correcto de la institución denominada <b> 
                    @foreach($regiap as $iap)
                        @if($iap->iap_id == $visita->iap_id)
                            {{Trim($iap->iap_desc)}}
                        @endif
                    @endforeach
                    </b>, I.A.P., por así indicarlo la calle, nomenclatura, colonia, municipio, así como por el dicho del C. <b>{{$visita->visita_auditado1}}</b>, quien dijo ser <b>{{$visita->visita_puesto1}}</b> y quien se identifica con <b>{{$visita->visita_ident1}}</b>, expedida por <b>{{$visita->visita_exped1}}</b>, procedo en estos momentos a identificar a <b>{{$visita->visita_auditor11}}</b>, personal  adscrito a la Junta de Asistencia Privada, quien se identifica con gafete, que cuenta con fotografía al frente expedido y con firma autógrafa del Secretario Ejecutivo de la Junta de Asistencia Privada del Estado de México, autoridad competente  para emitirlo; y así mismo procedo a requerir la presencia del representante legal o cualquier integrante del patronato de la institución visitada quien dijo llamarse <b>{{$visita->visita_auditado2}}</b>, desempeñando el cargo de <b>{{$visita->visita_puesto2}}</b> y quien se identifica con <b>{{$visita->visita_ident2}}</b>, expedida por <b>{{$visita->visita_exped2}}</b>; acto seguido, procedo a hacer del conocimiento de la persona que atiende la diligencia, el C. <b>{{$visita->visita_auditado3}}</b>, que se requiere la presentación de la documentación que compruebe el cumplimiento de los CRITERIOS DE VERIFICACIÓN, el CUMPLIMIENTO DEL OBJETO ASISTENCIAL  el cual se describe a continuación: <b>{{$visita->visita_criterios}}</b>; así como TODA AQUELLA DOCUMENTACIÓN QUE SUSTENTE LAS ACTIVIDADES ASISTENCIALES REALIZADAS POR LA INSTITUCIÓN con el objeto de verificar que la documentación requerida cumple con los lineamientos  establecidos por la Ley de Instituciones y así mismo permita al personal de esta Junta efectuar el  recorrido de las instalaciones de la institución <b> 
                    @foreach($regiap as $iap)
                        @if($iap->iap_id == $visita->iap_id)
                            {{Trim($iap->iap_desc)}}
                        @endif
                    @endforeach
                    </b>, I.A.P.; así mismo se le previene que para el caso de no contar con los mismos, tendrá como  consecuencia legal la contravención de la ley de la materia y se hará acreedor a la sanción correspondiente, por lo que se hace del conocimiento de la persona que atiende la diligencia de  verificación, que puede formular observaciones en el acto de la presente diligencia, así como ofrecer pruebas en relación a los hechos u omisiones contenidos en el acta de la misma o bien hacer uso de  ese derecho, por escrito, dentro del término de tres días siguientes a la fecha en que se hubiere   levantado la presente acta, por lo que una vez entendido de este derecho y del motivo de la presente se procede, en cumplimiento del artículo 128 fracción VII del Código de Procedimientos Administrativos en vigor, a solicitar a la persona que atiende la diligencia nombre a dos testigos de su parte, y se  le previene para que, en caso de que sí estos no son nombrados o los señalados no acepten servir como tales, los visitadores los designaran; acto  seguido,  el o la C. <b>{{$visita->visita_auditado1}}</b> nombra de su parte a <b>{{$visita->visita_testigo1}}</b> y <b>{{$visita->visita_testigo2}}</b>, mismos que aceptan desempeñarse como testigos en el presente acto. Acto seguido se procede a hacer constar en la presente acta, todas y cada una de las circunstancias, hechos u omisiones que se observen durante el desarrollo de la presente visita de verificación:________________________________________________.
                    <br><br><br><br>

                    Con Fundamento en el artículo 64, inciso a) fracción X, de la Ley de Instituciones de Asistencia Privada del Estado de México, visto que: <b>{{$visita->visita_visto}}</b>, se emiten las siguientes recomendaciones obligatorias: <b>{{$visita->visita_recomen}}</b>. Así como las siguientes sugerencias de mejora: <b>{{$visita->visita_sugeren}}</b>
                    <br><br><br>
                    Contando con un plazo no mayor de <b>{{$visita->num2_id}}</b>, contados a partir de la fecha de entrega del duplicado de esta acta de visita de verificación para el cumplimiento de las recomendaciones antes enlistadas,  mismas que se anexan y forma parte integral del acta de visita de verificación en que se actúa.  
                    <br>
                    “Sin más que agregar se da por terminada la presente diligencia siendo las <b> 
                    @foreach($reghoras as $hora)
                        @if($hora->hora_id == $visita->hora2_id)
                            {{$hora->hora_desc}}
                        @endif
                    @endforeach
                    </b> horas con <b> 
                    @foreach($regminutos as $min)
                        @if($min->min_id == $visita->num2_id)
                            {{$min->min_desc}}
                        @endif
                    @endforeach
                    </b> minutos, firmando al margen y al calce los que en ella intervinieron para los efectos legales que haya a lugar”.
                </p>           
            @endforeach 
        </ul>
        <table class="table table-hover table-striped" align="center" width="100%">
            <tr><td style="border:0;"></td></tr>
            <tr><td style="border:0;"></td></tr>
            <tr>
                <td style="border:0;text-align:center;">POR LA JUNTA  </td>
                <td style="border:0;text-align:center;">POR LA INSTITUCIÓN</td>
            </tr>
            <tr><td style="border:0;"></td></tr>
            <tr><td style="border:0;"></td></tr>
            <tr>
                <td style="border:0; text-align:center;">Nombre y firma de quien realizo la diligencia.</td>
                <td style="border:0;text-align:center;">Nombre y firma de quien atendio la diligencia.</td>
            </tr>
            <tr><td style="border:0;"></td></tr> 
            <tr><td style="border:0;"></td></tr>
            <tr>
                <td style="border:0;text-align:center;">TESTIGOS  </td>
                <td style="border:0;text-align:center;">TESTIGOS  </td>
            </tr>            
            <tr><td style="border:0;"></td></tr>   
            <tr><td style="border:0;"></td></tr>
            <tr>
                <td style="border:0; text-align:center;">Nombre y firma de testigo</td>
                <td style="border:0; text-align:center;">Nombre y firma de testigo</td>
            </tr>         
        </table>

    <page_footer id="footer">
        <table class="table table-hover table-striped" align="center" width="100%">
            <tr>
                <td style="border:0; text-align:right;">
                    <b>SECRETARIA DE DESARROLLO SOCIAL</b><br>JUNTA DE ASISTENCIA PRIVADA DEL ESTADO DE MÉXICO
                </td>
            </tr>
        </table>
    </page_footer>  
     
    </page>
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