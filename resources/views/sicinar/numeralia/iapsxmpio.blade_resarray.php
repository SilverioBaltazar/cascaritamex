@extends('sicinar.principal')

@section('title','Gráfica de IAPS por municipio')

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
                Unidad Administrativa: Secretaría de Desarrollo Social
            </h1>
            <ol class="breadcrumb">
                <li><a href="#"><i class="fa fa-dashboard"></i> Menú</a></li>
                <li><a href="#">Numeralia</a></li>
                <li class="active">Ver Gráfica de IAPS por municipio</li>
            </ol>
        </section>
        <section class="content">
            <div class="row">
                <div class="col-md-8">
                    <div class="box box-danger">
                        <div class="box-header with-border">
                            <h3 class="box-title">Gráfica por Tipo de Proceso</h3>
                                <div class="box-body">
                                    <div class="row">
                                        <div class="col-xs-12">
                                            <b style="color:red;">{{$regiap[0]->municipio_id}}-{{$regiap[0]->municipio}}:: {{$regiap[0]->total}}</b><br>
                                            <b style="color:green;">{{$regiap[1]->municipio_id}}-{{$regiap[1]->municipio}}:: {{$regiap[1]->total}}</b><br>
                                            <b style="color:deepskyblue;">{{$regiap[2]->municipio_id}}- {{$regiap[2]->municipio}}:: {{$regiap[2]->total}}</b><br>
                                            <b style="color:orange;">{{$regiap[3]->municipio_id}}-{{$regiap[3]->municipio}}:: {{$regiap[3]->total}}</b><br>
                                            <b style="color:blue;">{{$regiap[4]->municipio_id}}-{{$regiap[4]->municipio}}:: {{$regiap[4]->total}}</b><br>
                                            <b style="color:grey;">{{$regiap[5]->municipio_id}}-{{$regiap[5]->municipio}}:: {{$regiap[5]->total}}</b><br>
                                            <b style="color:yellow;">{{$regiap[6]->municipio_id}}-{{$regiap[6]->municipio}}:: {{$regiap[6]->total}}</b><br>
                                            <b style="color:purple;">{{$regiap[7]->municipio_id}}-{{$regiap[7]->municipio}}:: {{$regiap[7]->total}}</b><br>
                                            <b style="color:red;">{{$regiap[8]->municipio_id}}-{{$regiap[8]->municipio}}:: {{$regiap[8]->total}}</b><br>
                                        </div>
                                    </div>
                                </div>

                            <div class="box-tools pull-right">
                                <!--<button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>-->
                                <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
                            </div>
                        </div>
                        <div class="box-body">
                          <div class="chart">
                            <canvas id="barChart" style="height:230px"></canvas>
                          </div>
                        </div>
                        <!-- /.box-body -->
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
            //var barChartCanvas = $('#barChart').get(0).getContext('2d');
            //var barChart       = new Chart(barChartCanvas);

            // Any of the following formats may be used
            //var ctx = document.getElementById('myChart');
            //var ctx = document.getElementById('myChart').getContext('2d');
            //var ctx = $('#myChart');
            //var ctx = 'myChart';

      var barChartData = {
      labels  : ['Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'],
      datasets: [
        {
          label               : 'Demanda',
          fillColor           : 'rgba(210, 214, 222, 1)',
          strokeColor         : 'rgba(210, 214, 222, 1)',
          pointColor          : 'rgba(210, 214, 222, 1)',
          pointStrokeColor    : '#c1c7d1',
          pointHighlightFill  : '#fff',
          pointHighlightStroke: 'rgba(220,220,220,1)',
          data                : [ "<?php echo $regiap[0]->total; ?>",
                                  "<?php echo $regiap[1]->total; ?>",
                                  "<?php echo $regiap[2]->total; ?>",
                                  "<?php echo $regiap[3]->total; ?>",
                                  "<?php echo $regiap[4]->total; ?>",
                                  "<?php echo $regiap[5]->total; ?>",
                                  "<?php echo $regiap[6]->total; ?>",
                                  "<?php echo $regiap[7]->total; ?>",
                                  "<?php echo $regiap[8]->total; ?>",
                                  "<?php echo $regiap[9]->total; ?>",
                                  "<?php echo $regiap[10]->total; ?>",
                                  "<?php echo $regiap[11]->total; ?>" 
                                ]
        }
      ]
    }
    var barChartCanvas                   = $('#barChart').get(0).getContext('2d')
    var barChart                         = new Chart(barChartCanvas)
    var barChartData                     = barChartData
    barChartData.datasets[1].fillColor   = '#00a65a'
    barChartData.datasets[1].strokeColor = '#00a65a'
    barChartData.datasets[1].pointColor  = '#00a65a'
    var barChartOptions                  = {
      //Boolean - Whether the scale should start at zero, or an order of magnitude down from the lowest value
      scaleBeginAtZero        : true,
      //Boolean - Whether grid lines are shown across the chart
      scaleShowGridLines      : true,
      //String - Colour of the grid lines
      scaleGridLineColor      : 'rgba(0,0,0,.05)',
      //Number - Width of the grid lines
      scaleGridLineWidth      : 1,
      //Boolean - Whether to show horizontal lines (except X axis)
      scaleShowHorizontalLines: true,
      //Boolean - Whether to show vertical lines (except Y axis)
      scaleShowVerticalLines  : true,
      //Boolean - If there is a stroke on each bar
      barShowStroke           : true,
      //Number - Pixel width of the bar stroke
      barStrokeWidth          : 2,
      //Number - Spacing between each of the X value sets
      barValueSpacing         : 5,
      //Number - Spacing between data sets within X values
      barDatasetSpacing       : 5,
      //String - A legend template
      legendTemplate          : '<ul class="<%=name.toLowerCase()%>-legend"><% for (var i=0; i<datasets.length; i++){%><li><span style="background-color:<%=datasets[i].fillColor%>"></span><%if(datasets[i].label){%><%=datasets[i].label%><%}%></li><%}%></ul>',
      //Boolean - whether to make the chart responsive
      responsive              : true,
      maintainAspectRatio     : true
    }
 
    barChartOptions.datasetFill = false
    barChart.Bar(barChartData, barChartOptions)
 
  })
</script>
@endsection