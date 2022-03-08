<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8"> 
    <title>Mensage</title>
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,600" rel="stylesheet">
    <!-- Styles -->
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

</head>
<body>
     <header id="header1">
        <p style="border:0; font-family:'Arial, Helvetica, sans-serif'; font-size:13px; text-align:center;">
            <img src="{{ asset('images/Gobierno.png') }}" alt="EDOMEX" width="90px" height="55px" style="margin-right: 15px;" align="left"/>            
            &nbsp;&nbsp;<b>Auto registro al Sistema de Registro al Padrón Estatal (SRPE v.1)</b>
            <img src="{{ asset('images/Edomex.png') }}" alt="EDOMEX" width="80px" height="55px" style="margin-left: 15px;" align="right"/>
        </p>
    </header>

    <br />
    <div id="content1">
        <div class="container box" style="width: 970px;">
        <h1 align="left;"> {{ $osc }} </h1>
        <h2 align="left;">Representante {{ $replegal }}</h2>
        <h3 align="left;">Teléfono de contacto {{ $tel }}</h3>        

        <br><br> 
        <div>
            <p align="justify" style="border:0; font-size:13px;">
            La Secretaría De Desarrollo Social del Estado de México a través del Instituto Mexiquense de la Juventud 
            (IMEJ), le da la más cordial bienvenida al Torneo Estatal de Futbol "Cascarita Mexiquense 2022.
            </p>
        </div>
        <div class="card-body">
            <p style="border:0; text-align:left; font-size:16px;"><b>Parámetros de acceso al sistema      </b></p>
            <p style="border:0; text-align:left; font-size:12px;"><b>Folio asigando por sistema:  <i>{{$folio}} </i></b></p>
            <p style="border:0; text-align:left; font-size:12px;"><b>Login de usuario .........:  <i>{{$login}} </i></b></p>
            <p style="border:0; text-align:left; font-size:12px;"><b>Password .................:  <i>{{$psw}}   </i></b></p>
        </div>
        <br><br>

    </div>

    <div id="footer1">
        <table class="table table-hover table-striped" align="center" width="100%">
            <tr>
                <td style="border:0; text-align:right; font-size:13px;">
                    <b>SECRETARÍA DE DESARROLLO SOCIAL</b><br>
                    INSTITUTO MEXIQUENSE DE LA JUVENTUD (IMEJ)<br>
                    <b style="border:0; text-align:left;font-size:10px;">
                    Calle Primavera S/N, entre Fco. I Madero e Invierno, Col. Los Álamos, Ecatepec de Morelos.<br>
                    Teléfono: (55) 57704126, Correo electrónico: direcciongeneralimej@edomex.gob.mx
                    </b>
                </td>
            </tr>
        </table>
        
    </div>

</body>
</html>