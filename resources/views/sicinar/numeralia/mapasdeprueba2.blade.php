<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <title>GMaps.js &mdash; Geolocation</title>
  <!--<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.6.4/jquery.min.js"></script>
  <script type="text/javascript" src="http://maps.google.com/maps/api/js?sensor=true"></script>
  <script type="text/javascript" src="gmaps.js"></script>  -->
  <!-- <link rel="stylesheet" href="http://twitter.github.com/bootstrap/1.3.0/bootstrap.min.css" /> -->
  <!-- <link rel="stylesheet" type="text/css" href="examples.css" /> -->

  <script type='text/javascript' src='https://maps.googleapis.com/maps/api/js'></script>
  <!--Ahora pasará a ser así: 
  https://console.developers.google.com/apis/credentials?project=static-pottery-245815&supportedpurview=project 
  <script type='text/javascript' src='https://maps.googleapis.com/maps/api/js?key=AIzaSyB6vWlanNH6vM588sqCSWFiwN5QiDTaJvw'></script>  -->
 
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

    });


  </script>
</head>
<body>
  <h1>GMaps.js &mdash; Muestra direcciones de la rutas</h1>
  <div id="map"></div>
  <div id="directions"></div>
  
</body>
</html>