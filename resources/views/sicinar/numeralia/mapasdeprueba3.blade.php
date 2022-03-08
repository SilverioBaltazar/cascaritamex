<html>
  <head>
  <meta charset="utf-8">
  <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>

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
  <!-- https://developers.google.com/chart/interactive/docs/gallery/map#loading -->
  <script>
    google.charts.load('current', { 'packages': ['map'] });
    google.charts.setOnLoadCallback(drawMap);

    function drawMap() {
      //var data = google.visualization.arrayToDataTable([
      //  ['Country', 'Population'],
      //  ['China', 'China: 1,363,800,000'],
      //  ['India', 'India: 1,242,620,000'],
      //  ['US', 'US: 317,842,000'],
      //  ['Indonesia', 'Indonesia: 247,424,598'],
      //  ['Brazil', 'Brazil: 201,032,714'],
      //  ['Pakistan', 'Pakistan: 186,134,000'],
      //  ['Nigeria', 'Nigeria: 173,615,000'],
      //  ['Bangladesh', 'Bangladesh: 152,518,015'],
      //  ['Russia', 'Russia: 146,019,512'],
      //  ['Japan', 'Japan: 127,120,000']
      //]);
      var data = google.visualization.arrayToDataTable([
       ['Latitud', 'Longitud', 'Municipio'],
       [37.4232, -122.0853, 'Work'],
       [37.4289, -122.1697, 'University'],
       [37.6153, -122.3900, 'Airport'],
       [37.4422, -122.1731, 'Shopping']
      ]);

    var options = {
      zoomLevel: 9,
      showTooltip: true,
      showInfoWindow: true,
      useMapTypeControl: true,
      //mapTypeIds: ['styledMap', 'redEverything', 'imBlue']
        //icons: {
        //  default: {
        //    normal:   '/path/to/marker/image',
        //    selected: '/path/to/marker/image'
        //  },
        //  customMarker: {
        //    normal:   '/path/to/other/marker/image',
        //    selected: '/path/to/other/marker/image'
        //  }
        //}
      maps: {
          // Your custom mapTypeId holding custom map styles.
          styledMap: {
            name: 'Styled Map', // This name will be displayed in the map type control.
            styles: [
              {featureType: 'poi.attraction',
               stylers: [{color: '#fce8b2'}]
              },
              {featureType: 'road.highway',
               stylers: [{hue: '#0277bd'}, {saturation: -50}]
              },
              {featureType: 'road.highway',
               elementType: 'labels.icon',
               stylers: [{hue: '#000'}, {saturation: 100}, {lightness: 50}]
              },
              {featureType: 'landscape',
               stylers: [{hue: '#259b24'}, {saturation: 10}, {lightness: -22}]
              }
            ]
          }
      }

    };

    var map = new google.visualization.Map(document.getElementById('chart_div'));
    map.draw(data, options);
  };
  </script>
  </head>
  <body>
    <div id="chart_div"></div>
  </body>
</html>
