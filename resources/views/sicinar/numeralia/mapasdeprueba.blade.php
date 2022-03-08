
@extends('sicinar.principal')

@section('title','Mapas')

@section('links')
  <link rel="stylesheet" href="{{ asset('bower_components/datatables.net-bs/css/dataTables.bootstrap.min.css') }}">
@endsection

@section('nombre')
{{$nombre}}
@endsection

@section('usuario')
{{$usuario}}
@endsection

@section('content')
 
<div class="content-wrapper">
    <section class="content-header">
      <h1>
        Numeralia Básica
        <small> Mapas</small>
      </h1>
      <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> Menú</a></li>
        <li><a href="#">Numeralia</a></li>
        <li><a href="#">Estadisticas</a></li>
        <li class="active">Mapas</li>
      </ol>
    </section>
    <section class="content">        
         

      <!-- GMaps.js es una herramienta muy sencilla y fácil de utilizar. Nos permite acceder a muchos de los recursos de Google Maps como son sus bases de datos de direcciones, rutas y elevaciones. Creo en consecuencia que puede ser muy útil para incorporar mapas a aquellas páginas web que necesiten incorporar un mapa con geolocalización o cálculo de rutas.-->
      <div class="row">
        <div class="col-md-12">
          <div class="box">
            <div class="box box-danger">
              <div class="box-header with-border"> 
                <meta charset="utf-8">
                <h3 class="box-title" style="text-align:center;">GMaps.js &mdash; Geolocation </h3>    
                <h2 class="box-title" style="text-align:center;">GMaps.js &mdash; Muestra direcciones de la rutas </h2>
                <!-- BOTON para cerrar ventana x -->
                <div class="box-tools pull-right">
                  <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
                </div>
                <!-- Pinta la grafica de barras 2-->
                <div class="box-body">
                  <!-- <camvas id="chart_div" style="width: 900px; height: 500px;"></camvas>  
                  <div id="map"></div>
                  <div id="directions"></div>  -->
                  <camvas id="map"></camvas>
                  <camvas id="directions"></camvas>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>    

    </section>
  </div>      
@endsection

@section('request')
  <script src="{{ asset('bower_components/chart.js/Chart.js') }}"></script>
  
  <!-- Grafica google de pay, barras en 3D, donas
    https://google-developers.appspot.com/chart/interactive/docs/gallery/piechart
  -->
  <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>

  <!-- GMaps.js es una herramienta muy sencilla y fácil de utilizar. Nos permite acceder a muchos de los recursos de Google Maps como son sus bases de datos de direcciones, rutas y elevaciones. Creo en consecuencia que puede ser muy útil para incorporar mapas a aquellas páginas web que necesiten incorporar un mapa con geolocalización o cálculo de rutas.

  <script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.6.4/jquery.min.js"></script>
  <script type="text/javascript" src="http://maps.google.com/maps/api/js?sensor=true"></script>
  <script type="text/javascript" src="gmaps.js"></script> -->
  <!-- <link rel="stylesheet" href="http://twitter.github.com/bootstrap/1.3.0/bootstrap.min.css" /> -->
  <!-- <link rel="stylesheet" type="text/css" href="examples.css" /> -->  
  <!-- llamada a la API de Google era:  -->
  <script type='text/javascript' src='https://maps.googleapis.com/maps/api/js'></script>
  <!--Ahora pasará a ser así:  -->
  <!--<script type='text/javascript' src='https://maps.googleapis.com/maps/api/js?key=AIzaSyB6vWlanNH6vM588sqCSWFiwN5QiDTaJvw'></script>  -->
 
  <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
  <script src="http://maps.google.com/maps/api/js?sensor=true"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/gmaps.js/0.4.24/gmaps.js"></script> 
  <style type="text/css">
    #map{
    display: block;
    width: 100%;
    height: 600px;
    margin: 0 auto;
    -moz-box-shadow: 0px 5px 20px #ccc;
    -webkit-box-shadow: 0px 5px 20px #ccc;
    box-shadow: 0px 5px 20px #ccc;
    } 
  </style> 
  <!--<style type="text/css">
      #mymap {
          border:1px solid red;
          width: 800px;
          height: 500px;
      }
  </style>  -->
@endsection

@section('javascrpt')
  <!-- GMaps.js es una herramienta muy sencilla y fácil de utilizar. Nos permite acceder a muchos de los recursos de Google Maps como son sus bases de datos de direcciones, rutas y elevaciones. Creo en consecuencia que puede ser muy útil para incorporar mapas a aquellas páginas web que necesiten incorporar un mapa con geolocalización o cálculo de rutas.-->
  <script type="text/javascript">
    var map, lat, lng;
    $(document).ready(function(){
      var map = new GMaps({
        el: '#map',
        lat: 19.4968732,  //21.170240,
        lng: -99.7232673,  //72.831061,
        zoom:6
      });

      GMaps.geolocate({
        success: function(position){
          lat = position.coords.latitude;  
          lng = position.coords.longitude;
          map.setCenter(lat, lng);
          map.addMarker({ lat: lat, lng: lng});  
        },
        error: function(error){
          alert('Geolocation failed: '+error.message);
        },
        not_supported: function(){
          alert("Your browser does not support geolocation");
        }
      });

      map.addListener('click', function(e) {
          map.renderRoute({
          origin: [lat, lng],  
          destination: [e.latLng.lat(), e.latLng.lng()],
          travelMode: 'driving',
          strokeColor: '#000000',
          strokeOpacity: 0.6,
          strokeWeight: 5
          }, {
          panel: '#directions',
          draggable: true
          });
          lat = e.latLng.lat();   
          lng = e.latLng.lng();
          //Crea un marcador en el punto final automaticamente
          });
      })
  </script>
@endsection