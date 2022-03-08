@extends('main')

@section('title','Iniciar Sesión')

@section('content')
<style type="text/css">
/*------------------------------ logininicio.blade.php ---------------------------------------------------
 Propósito: Mostrar la pantalla de inicio del sistema con los campos para accesar al mismo.
 Autor:   Gobierno del Estado de Mexico
 Modifico:  Ing. Silverio Baltazar Barrientos Zarate
 Sistema:   SRP v.1
 Fecha última modificación:  septiembre 2021
 ---------------------------------------------------------------------------------------------------*/
#wrap{
  text-align:left;
  height: 600px;
  width: 834px;
  padding:0;
  background-color: #FFFFFF;
  background-image: url("{{asset('sr_splash.jpg')}}"); /*url(imagen/sr_splash.jpg);*/
  background-repeat: no-repeat;
  background-position: center top;
  margin-top: 0;
  margin-right: auto;
  margin-bottom: 0;
  margin-left: auto;
}
  
#accesosistema {
  background-image: url("{{asset('sr_caja.jpg')}}"); /*url(imagen/sr_caja.jpg);*/
  background-repeat: repeat-y;
  background-position: left top;
  width:293px;
  float:right;
  margin-top: 040px;
  margin-right: 0px;
  margin-bottom: 0;
  margin-left: 0;
  background-color: transparent;
  padding-right: 10px;
  }
  
  #accesosistema h1 {
  height: 100px;
  color: #FFF;
  background-image: url("{{asset('sr_AccesoTop.jpg')}}"); /*url(imagen/sr_AccesoTop.jpg);*/
  background-repeat: no-repeat;
  background-position: left top;
  width: 293px;
  margin: 0;
  background-color: transparent;
  }
  #forma
  {padding: 0 10px 10px 15px;
   margin:0;
  }
  #pie{
  background-image: url("{{asset('sr_fondo_pie.jpg')}}"); /*url(Imagen/sr_fondo_pie.jpg); */
  height: 25px;
  width: 293px;
  background-repeat: no-repeat;
  }
  
#accesosistema label {
  display:block;
  float: left;
  width:130px;
  font-weight:bold;
  cursor: default;
  margin-right: 0;
  margin-bottom: 5px;
  margin-left: 0;
  }
  
  #accesosistema p 
  {
  font-weight:bold;
  margin: 0 0 5px 20px;
  }

  #accesosistema input
  {
  display:block;
  margin-bottom:15px;
  margin-left:20px;
  }

  #accesosistema .error
  { color: #CC0000;
    margin-bottom: 10px;
    margin-right:10px;
  }
</style>
<!--<body class="hold-transition login-page">-->
<body class="hold-transition">
  <img src="{{ asset('images/Logo-Gobierno.png') }}" border="0" width="200" height="60" style="margin-left: 200px;margin-right: 50%">
  <!--<img src="{{ asset('images/japem.jpg') }}"  border="0" width="110" height="70">-->
  <img src="{{ asset('images/imej.jpeg') }}"  border="0" width="100" height="65">
  <img src="{{ asset('images/Edomex.png') }}" border="0" width="80" height="60">
  <!--
  <style type="text/css">
    body{
    background-image: url("{{asset('images/japem.jpg')}}"); 
    height: 100%;
    background-position: center;
    background-repeat: no-repeat;
    background-size: cover;
    }
    
  </style> -->
  <!--<img src="{{ asset('images/Logo-Gobierno.png') }}" border="1" width="200" height="60" style="margin-right: 1000px;">-->
  <!--<img src="{{ asset('images/Edomex.png') }}" border="1" width="80" height="60" style="position: relative;">-->
  <div class="login-logo">
      <h4 style="color:green;" width="80">
          <b>SECRETARIA DE DESARROLLO SOCIAL <br> INSTITUTO MEXIQUENSE DE LA JUVENTUD (IMEJ)<br>
          <b style="color:orange;">SISTEMA DE REGISTRO AL TORNEO ESTATAL DE FUTBOL "CASCARITA MEXIQUENSE 2022" </b>
      </h4>
  </div>

  <div class="login-box">
    <!--<img src="{{ asset('images/Logo-Gobierno.png') }}" border="1" width="200" height="60" style="margin-right: 20px;">
    <img src="{{ asset('images/Edomex.png') }}" border="1" width="80" height="60">-->
    <!-- /.login-logo -->
    <div class="login-box-body">
      <p class="login-box-msg; font-size:08px;">Ingresa tus datos para iniciar sesión</p>

      {!! Form::open(['route' => 'login', 'method' => 'POST', 'id' => 'logeo']) !!}
        <div class="form-group has-feedback">
            <input type="text" class="form-control" name="folio" placeholder="folio asignado por sistema">    
        </div>      
        <div class="form-group has-feedback">
            <input type="text" class="form-control" name="usuario" placeholder="Usuario">
            <span class="glyphicon glyphicon-user form-control-feedback"></span>
        </div>
        <div class="form-group has-feedback">
            <input type="password" class="form-control" name="password" placeholder="Contraseña">
            <span class="glyphicon glyphicon-lock form-control-feedback"></span>
        </div>

        @if(count($errors) > 0)
          <div class="alert alert-danger" role="alert">
            <ul>
              @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
              @endforeach
            </ul>
          </div>
        @endif
        <div class="row">
            <div class="col-md-12 offset-md-5">
                <button type="submit" class="btn btn-primary btn-block btn-success">Iniciar</button>
            </div>
        </div>
        <br>

        <div class="col-md-12 offset-md-5">
            <p style="font-family:'Arial, Helvetica, sans-serif'; font-size:12px; text-align:center;">          
            <a href="{{route('autoregusu')}}" class="btn btn-primary btn_xs" title="Autoregistrse para generar un folio">
                <i class="fa fa-file-new-o"></i>   
                <span class="glyphicon glyphicon-plus"></span>Auto registrarse
            </a>
            &nbsp;&nbsp;&nbsp;&nbsp;
            <a href="/images/AVISO_PRIVACIDAD_RPE.pdf"> Aviso de privacidad</a>
            </p>
        </div>   
      {!! Form::close() !!}
    </div>
    <!-- /.login-box-body -->

  </div>
  <div class="lockscreen-footer text-center">
      <b>Copyright &copy;. Derechos reservados. Secretaría de Desarrollo Social - Instituto Mexiquense de la Juventud (IMEJ).</b>
  </div>

  <!--<div class="login-box">
    <div class="login-logo">
      <a href="#"><b>SEDESEM</b></a>
      <h4 style="color:gray;">SECRETARIA DE DESARROLLO SOCIAL</h4>
    </div>
  </div>-->

  <!-- /.login-box -->

  <!-- Javascript Requirements -->
  <!--<script src="//cdnjs.cloudflare.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>
  <script src="//cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/3.3.1/js/bootstrap.min.js"></script>-->

  <!-- Laravel Javascript Validation -->
  <script type="text/javascript" src="{{ asset('vendor/jsvalidation/js/jsvalidation.js')}}"></script>

  {!! JsValidator::formRequest('App\Http\Requests\usuarioRequest','#logeo') !!}

</body>
@endsection
