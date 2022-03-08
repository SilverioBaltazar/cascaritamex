@extends('sicinar.principal')

@section('title','Estadística por categoria')

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
        Estadísticas de equipos  
        <small>Por Categoria</small>
      </h1>
      <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> Menú</a></li>
        <li><a href="#">Estadísticas de Equipos    </a></li>
        <li class="active">Por Categoria </li>
      </ol>
    </section>
    <section class="content">
      <div class="row">
        <div class="col-md-6">
          <div class="box box-success">
            <div class="box-header">

                <div class="box-tools pull-right">
                  <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
                </div>
            </div>
            <div class="box-body">
              <table id="tabla1" class="table table-striped table-bordered table-sm">
                <thead style="color: brown;" class="justify">
                  <tr>
                    <th rowspan="2" style="text-align:left;"  >ID.       </th>
                    <th rowspan="2" style="text-align:left;"  >CATEGORIA </th>
                    <th rowspan="2" style="text-align:center;">TOTAL     </th>
                  </tr>
                  <tr>
                  </tr>
                </thead>

                <tbody>
                  @foreach($regficha as $ficha)
                    <tr>
                      <td style="color:darkgreen;">{{$ficha->cate_id}}</td>
                      <td style="color:darkgreen;">{{Trim($ficha->cate_desc)}}</td>
                      <td style="color:darkgreen; text-align:center;">{{$ficha->total}}</td>
                    </tr>
                  @endforeach
                  @foreach($regtotxcate as $totales)
                     <tr>
                        <td>                                  </td>
                        <td style="color:green;"><b>TOTAL</b> </td>                         
                        <td style="color:green; text-align:center;"><b>{{$totales->totalxcate}} </b></td>
                     </tr>
                  @endforeach
                </tbody>
              </table>
            </div>
          </div>
        </div>

        <!-- Grafica de barras 2-->
        <div class="row">
          <div class="col-md-6">
            <div class="box">
              <div class="box box-success">
                <div class="box-header with-border">
                  <!--<h3 class="box-title" style="text-align:center;">Gráfica por estado 2D </h3>  -->
                  <!-- BOTON para cerrar ventana x -->
                  <div class="box-tools pull-right">
                    <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
                  </div>
                  <!-- Pinta la grafica de barras 2-->
                  <div class="box-body">
                    <camvas id="top_x_div" style="width: 900px; height: 500px;"></camvas>
                  </div>
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
  <!-- Grafica google de pay, barras en 3D
    https://google-developers.appspot.com/chart/interactive/docs/gallery/piechart
  -->
  <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>  
@endsection

@section('javascrpt')

  <!-- Grafica de barras 2D Google/chart -->
  <script type="text/javascript">
      google.charts.load('current', {'packages':['bar']});
      google.charts.setOnLoadCallback(drawStuff);

      function drawStuff() {
        var data = new google.visualization.arrayToDataTable([
          ['Categoria', 'Total'],
          @foreach($regficha as $ficha)
             ['{{Trim($ficha->cate_desc)}}', {{$ficha->total}} ],
          @endforeach          
        ]);

        var options = {
          //Boolean - Whether we should show a stroke on each segment
          //segmentShowStroke    : true,
          //String - The colour of each segment stroke
          //segmentStrokeColor   : '#fff',
          //Number - The width of each segment stroke
          //segmentStrokeWidth   : 2,
          //Number - The percentage of the chart that we cut out of the middle
          //percentageInnerCutout: 50, // This is 0 for Pie charts
          //Number - Amount of animation steps
          //animationSteps       : 100,  
                 
          title: 'Por Categoria',
          width: 500,                   // Ancho de la pantalla horizontal
          height: 300,                  // Alto de la pantall '75%',
          colors: ['green'],          // Naranja
          //backgroundColor:'#fdc', 
          stroke:'red',
          highlight: 'blue',
          legend: { position: 'none' },
          chart: { title: 'Numeralia básica',
                   subtitle: 'Gráfica por Categoria' },
          bars: 'horizontal', // Required for Material Bar Charts.
          //bars: 'vertical', // Required for Material Bar Charts.
          //chartArea:{left:20, top:0, width:'50%', height:'75%', backgroundColor:'#fdc', stroke:'green'},
          axes: {
            x: {
              0: { side: 'top', label: 'Total'} // Top x-axis.
              //1: { side: 'top', label: 'Total de oscS'} // Top x-axis.
              //distance: {label: 'Total'}, // Bottom x-axis.
              //brightness: {side: 'top', label: 'Total de oscS'} // Top x-axis.
            }
          },
          annotations: {
            textStyle: {
            fontName: 'Times-Roman',
            fontSize: 18,
            bold: true,
            italic: true,
            // The color of the text.
            color: '#871b47',
            // The color of the text outline.
            auraColor: '#d799ae',
            // The transparency of the text.
            opacity: 0.8
            }
          },
          //backgroundColor: { fill:  '#666' },
          //bar: { groupWidth: "90%" }
          bar: { groupWidth: "50%" }
        };

        var chart = new google.charts.Bar(document.getElementById('top_x_div'));
        chart.draw(data, options);
      };
  </script>  
@endsection