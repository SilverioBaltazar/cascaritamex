@extends('sicinar.principal')

@section('title','Estadistica por rubro social v2')

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
        <small> Gráfica</small>
      </h1>
      <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> Menú</a></li>
        <li><a href="#">Numeralia</a></li>
        <li><a href="#">Estadisticas</a></li>
        <li class="active">IAPS por Rubro social</li>
      </ol>
    </section>
    <section class="content">
      <div class="row">
        <div class="col-md-6">
          <div class="box box-success">
            <div class="box-header">
              <h3 class="box-title"><b>Estadistica de IAPS por Rubro Social</b></h3>
                <div class="box-tools pull-right">
                  <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
                </div>
            </div>
            <div class="box-body">
              <table id="tabla1" class="table table-striped table-bordered table-sm">
                <thead style="color: brown;" class="justify">
                  <tr>
                    <th rowspan="2" style="text-align:left;"  >ID.         </th>
                    <th rowspan="2" style="text-align:left;"  >RUBRO SOCIAL</th>
                    <th rowspan="2" style="text-align:center;">TOTAL       </th>
                  </tr>
                  <tr>
                  </tr>
                </thead>

                <tbody>
                  @foreach($regiap as $iap)
                    <tr>
                      @if($iap->rubro_id == 0)
                         <td style="color:darkgreen;">{{$iap->rubro_id}}</td>
                         <td style="color:darkgreen;">{{$iap->rubro}}   </td>
                         <td style="color:darkgreen; text-align:center;">{{$iap->total}}   </td>
                      @endif
                      @if($iap->rubro_id == 1)
                         <td style="color:red;">{{$iap->rubro_id}}</td>
                         <td style="color:red;">{{$iap->rubro}}   </td>
                         <td style="color:red; text-align:center;">{{$iap->total}}   </td>
                      @endif
                      @if($iap->rubro_id == 2)
                         <td style="color:orange;">{{$iap->rubro_id}}</td>
                         <td style="color:orange;">{{$iap->rubro}}   </td>
                         <td style="color:orange; text-align:center;">{{$iap->total}}   </td>
                      @endif
                      @if($iap->rubro_id == 3)
                         <td style="color:blue;">{{$iap->rubro_id}}</td>
                         <td style="color:blue;">{{$iap->rubro}}   </td>
                         <td style="color:blue; text-align:center;">{{$iap->total}}   </td>
                      @endif
                      @if($iap->rubro_id == 4)
                         <td style="color:grey;">{{$iap->rubro_id}}</td>
                         <td style="color:grey;">{{$iap->rubro}}   </td>
                         <td style="color:grey; text-align:center;">{{$iap->total}}   </td>
                      @endif
                      @if($iap->rubro_id == 5)
                         <td style="color:purple;">{{$iap->rubro_id}}</td>
                         <td style="color:purple;">{{$iap->rubro}}   </td>
                         <td style="color:purple; text-align:center;">{{$iap->total}}   </td>
                      @endif
                      @if($iap->rubro_id == 6)
                         <td style="color:dodgerblue;">{{$iap->rubro_id}}</td>
                         <td style="color:dodgerblue;">{{$iap->rubro}}   </td>
                         <td style="color:dodgerblue; text-align:center;">{{$iap->total}}   </td>
                      @endif
                    </tr>
                  @endforeach
                  @foreach($regtotxrubro as $totales)
                     <tr>
                         <td></td>
                         <td style="color:green;"><b>TOTAL</b></td>                         
                         <td style="color:green; text-align:center;"><b>{{$totales->totalxrubro}} </b></td>                      
                     </tr>
                  @endforeach
                </tbody>
              </table>
            </div>
          </div>

        </div>
        <div class="col-md-6">
          <div class="box">
            <div class="box box-danger">
              <div class="box-header with-border">
                <h3 class="box-title" style="text-align:center;">Gráfica </h3>
                <div class="box-tools pull-right">
                  <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
                </div>
                <div class="box-body">
                  <canvas id="pieChart" style="height:250px"></canvas>
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
@endsection

@section('javascrpt')
  <script>
      $(function(){
          //-------------
          //- PIE CHART -
          //-------------
          // Get context with jQuery - using jQuery's .get() method.
          var pieChartCanvas = $('#pieChart').get(0).getContext('2d');
          var pieChart       = new Chart(pieChartCanvas);
          var PieData        = [
              {
                  value    : <?php echo $regiap[0]->total;?>,
                  color    : 'green',
                  highlight: 'green',
                  label    : '<?php echo $regiap[0]->rubro;?>'
              },
              {
                  value    : <?php echo $regiap[1]->total;?>,
                  color    : 'red',
                  highlight: 'red',
                  label    : '<?php echo $regiap[1]->rubro;?>'
              },
              {
                  value    : <?php echo $regiap[2]->total;?>,
                  color    : 'orange',
                  highlight: 'orange',
                  label    : '<?php echo $regiap[2]->rubro;?>'
              },
              {
                  value    : <?php echo $regiap[3]->total;?>,
                  color    : 'blue',
                  highlight: 'blue',
                  label    : '<?php echo $regiap[3]->rubro;?>'
              },
              {
                  value    : <?php echo $regiap[4]->total;?>,
                  color    : 'grey',
                  highlight: 'grey',
                  label    : '<?php echo $regiap[4]->rubro;?>'
              },
              {
                  value    : <?php echo $regiap[5]->total;?>,
                  color    : 'purple',
                  highlight: 'purple',
                  label    : '<?php echo $regiap[5]->rubro;?>'
              },
              {
                  value    : <?php echo $regiap[6]->total;?>,
                  color    : 'dodgerblue',
                  highlight: 'dodgerblue',
                  label    : '<?php echo $regiap[6]->rubro;?>'
              }
          ];
          var pieOptions     = {
              //Boolean - Whether we should show a stroke on each segment
              segmentShowStroke    : true,
              //String - The colour of each segment stroke
              segmentStrokeColor   : '#fff',
              //Number - The width of each segment stroke
              segmentStrokeWidth   : 2,
              //Number - The percentage of the chart that we cut out of the middle
              percentageInnerCutout: 50, // This is 0 for Pie charts
              //Number - Amount of animation steps
              animationSteps       : 100,
              //String - Animation easing effect
              animationEasing      : 'easeOutBounce',
              //Boolean - Whether we animate the rotation of the Doughnut
              animateRotate        : true,
              //Boolean - Whether we animate scaling the Doughnut from the centre
              animateScale         : false,
              //Boolean - whether to make the chart responsive to window resizing
              responsive           : true,
              // Boolean - whether to maintain the starting aspect ratio or not when responsive, if set to false, will take up entire container
              maintainAspectRatio  : true
              //String - A legend template
          };
          //Create pie or douhnut chart
          // You can switch between pie and douhnut using the method below.
          pieChart.Doughnut(PieData, pieOptions)
      })
  </script>
@endsection