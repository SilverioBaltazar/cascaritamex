@extends('sicinar.principal')

@section('title','Estadística de la bitacora')

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
        Estadísticas
        <small> Bitacora</small>
      </h1>
      <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> Menú</a></li>
        <li><a href="#">Estadísticas  </a></li>
        <li class="active">Bitacora   </li>
      </ol>
    </section>
    <section class="content">
      <div class="row">
        <div class="col-md-12">
          <div class="box box-success">
            <div class="box-header">
              <h3 class="box-title"><b>Estadística de la bitacora del sistema</b></h3>
                <div class="box-tools pull-right">
                  <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
                </div>
            </div>
            <div class="box-body">
              <b style="color:green;text-align:left;">Periodo: 
                @foreach($regperiodos as $periodos)
                    {{$periodos->periodo_id}} 
                    @break
                @endforeach                      
              </b>            
              <table id="tabla1" class="table table-striped table-bordered table-sm">
                <thead style="color: brown;" class="justify">
                  <tr>
                    <th style="text-align:left; vertical-align: middle; width=10%;"><small>PROCESO </small></th>
                    <th style="text-align:left; vertical-align: middle; width=10%;"><small>FUNCION </small></th> 
                    <th style="text-align:left; vertical-align: middle; width=10%;"><small>TRX     </small></th> 

                    <th style="text-align:center; vertical-align: middle; width=03%;"><small>ENE     </small></th>
                    <th style="text-align:center; vertical-align: middle; width=03%;"><small>FEB     </small></th>
                    <th style="text-align:center; vertical-align: middle; width=03%;"><small>MAR     </small></th>
                    <th style="text-align:center; vertical-align: middle; width=03%;"><small>ABR     </small></th>   
                    <th style="text-align:center; vertical-align: middle; width=03%;"><small>MAY     </small></th>
                    <th style="text-align:center; vertical-align: middle; width=03%;"><small>JUN     </small></th>

                    <th style="text-align:center; vertical-align: middle; width=03%;"><small>JUL     </small></th>
                    <th style="text-align:center; vertical-align: middle; width=03%;"><small>AGO     </small></th>
                    <th style="text-align:center; vertical-align: middle; width=03%;"><small>SEP     </small></th>
                    <th style="text-align:center; vertical-align: middle; width=03%;"><small>OCT     </small></th>  
                    <th style="text-align:center; vertical-align: middle; width=03%;"><small>NOV     </small></th>
                    <th style="text-align:center; vertical-align: middle; width:03px;"><small>DIC    </small></th> 

                    <th style="text-align:center; vertical-align: middle; width=05%;"><small>TOTAL   </small></th>
                  </tr>
                  <tr>
                  </tr>
                </thead>

                <tbody>
                  <?php $sum01 = 0; ?>
                  <?php $sum02 = 0; ?>
                  <?php $sum03 = 0; ?>
                  <?php $sum04 = 0; ?>
                  <?php $sum05 = 0; ?>
                  <?php $sum06 = 0; ?>
                  <?php $sum07 = 0; ?>
                  <?php $sum08 = 0; ?>
                  <?php $sum09 = 0; ?>
                  <?php $sum10 = 0; ?>
                  <?php $sum11 = 0; ?>
                  <?php $sum12 = 0; ?>
                  @foreach($regbitacora as $bitacora)
                    <tr>
                      <td style="color:darkgreen;"><small>{{$bitacora->proceso_id.' '.$bitacora->proceso_desc}}</td>
                      <td style="color:darkgreen;"><small>{{$bitacora->funcion_id.' '.$bitacora->funcion_desc}}</small></td>
                      <td style="color:darkgreen;"><small>{{$bitacora->trx_id.' '.$bitacora->trx_desc}}</small></td>
                      <td style="color:darkgreen; text-align:center;"><small>{{$bitacora->ene}}</small></td> 
                      <td style="color:darkgreen; text-align:center;"><small>{{$bitacora->feb}}</small></td>           
                      <td style="color:darkgreen; text-align:center;"><small>{{$bitacora->mar}}</small></td>    
                      <td style="color:darkgreen; text-align:center;"><small>{{$bitacora->abr}}</small></td>            
                      <td style="color:darkgreen; text-align:center;"><small>{{$bitacora->may}}</small></td>    
                      <td style="color:darkgreen; text-align:center;"><small>{{$bitacora->jun}}</small></td>
                      <td style="color:darkgreen; text-align:center;"><small>{{$bitacora->jul}}</small></td>            
                      <td style="color:darkgreen; text-align:center;"><small>{{$bitacora->ago}}</small></td>   
                      <td style="color:darkgreen; text-align:center;"><small>{{$bitacora->sep}}</small></td>             
                      <td style="color:darkgreen; text-align:center;"><small>{{$bitacora->oct}}</small></td>    
                      <td style="color:darkgreen; text-align:center;"><small>{{$bitacora->nov}}</small></td>              
                      <td style="color:darkgreen; text-align:center;"><small>{{$bitacora->dic}}</small></td> 
                      <td style="color:darkgreen; text-align:center;"><small>{{number_format($bitacora->sumatotal,0)}}</small></td>
                    </tr>
                    <?php $sum01 += $bitacora->ene; ?>
                    <?php $sum02 += $bitacora->feb; ?>
                    <?php $sum03 += $bitacora->mar; ?>
                    <?php $sum04 += $bitacora->abr; ?>
                    <?php $sum05 += $bitacora->may; ?>
                    <?php $sum06 += $bitacora->jun; ?>
                    <?php $sum07 += $bitacora->jul; ?>
                    <?php $sum08 += $bitacora->ago; ?>
                    <?php $sum09 += $bitacora->sep; ?>
                    <?php $sum10 += $bitacora->oct; ?>
                    <?php $sum11 += $bitacora->nov; ?>
                    <?php $sum12 += $bitacora->dic; ?>
                  @endforeach
           
                  @foreach($regbitatot as $totales)
                     <tr>
                        <td>                                   </td>
                        <td>                                   </td>
                        <td style="color:green;"><b>TOTALES</b></td>
                        <td style="color:green; text-align:center;"><b>{{number_format($totales->m01,0)}}</b></td>
                        <td style="color:green; text-align:center;"><b>{{number_format($totales->m02,0)}}</b></td>
                        <td style="color:green; text-align:center;"><b>{{number_format($totales->m03,0)}}</b></td>
                        <td style="color:green; text-align:center;"><b>{{number_format($totales->m04,0)}}</b></td>
                        <td style="color:green; text-align:center;"><b>{{number_format($totales->m05,0)}}</b></td>
                        <td style="color:green; text-align:center;"><b>{{number_format($totales->m06,0)}}</b></td>
                        <td style="color:green; text-align:center;"><b>{{number_format($totales->m07,0)}}</b></td>
                        <td style="color:green; text-align:center;"><b>{{number_format($totales->m08,0)}}</b></td>
                        <td style="color:green; text-align:center;"><b>{{number_format($totales->m09,0)}}</b></td>
                        <td style="color:green; text-align:center;"><b>{{number_format($totales->m10,0)}}</b></td>
                        <td style="color:green; text-align:center;"><b>{{number_format($totales->m11,0)}}</b></td>
                        <td style="color:green; text-align:center;"><b>{{number_format($totales->m12,0)}}</b></td>
                        <td style="color:green; text-align:center;"><b>{{number_format($totales->totalgeneral,0)}} </b></td>
                     </tr>
                  @endforeach
                </tbody>
              </table>
            </div>
          </div>
        </div>

        <!-- Grafica de barras 2-->
        <div class="row">
          <div class="col-md-12">
            <div class="box">
              <div class="box box-success">
                <div class="box-header with-border">
                  <!--<h3 class="box-title" style="text-align:center;">Gráfica Modelado del proceso 2D </h3>  -->
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
          ['meses', 'Total'],
          @foreach($regbitatxmes as $tmes)
             ['{{$tmes->mes_desc}}', {{$tmes->totalgeneral}} ],
          @endforeach          
        ]);

        var options = {                 
          title: 'Por Mes',
          width: 500,                   // Alto de la pantalla horizontal
          height: 500,                  // Ancho de la pantalla '75%',
          colors: ['green'],          // Naranja #e7711c
          legend: { position: 'none' },
          chart: { title: 'Gráfica del modelado de proceso (bitacora) 2D',
                   subtitle: 'Demanda de transacciones por proceso, función y trx' },
          bars: 'horizontal', // Required for Material Bar Charts.
          axes: {
            x: {
              0: { side: 'top', label: 'Total de transacciones'} // Top x-axis.
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