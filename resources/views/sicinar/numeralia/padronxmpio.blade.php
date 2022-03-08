@extends('sicinar.principal')

@section('title','Estadística de padrón de beneficiarios por municipio')

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
        <small>Padrón por municipio</small>
      </h1>
      <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> Menú</a></li>
        
        <li><a href="#">Estadísticas</a></li>
        <li class="active">Padrón de beneficiarios por municipio</li>
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
                    <th rowspan="2" style="text-align:left;"  >MUNICIPIO </th>
                    <th rowspan="2" style="text-align:center;">TOTAL     </th>
                  </tr>
                  <tr>
                  </tr>
                </thead>

                <tbody>
                  @foreach($regpadron as $padron)
                    <tr>
                      @if($padron->municipio_id == 0)
                         <td style="color:darkgreen;">{{$padron->municipio_id}}</td>
                         <td style="color:darkgreen;">{{$padron->municipio}}   </td>
                         <td style="color:darkgreen; text-align:center;">{{$padron->total}}</td>
                      @endif
                      @if($padron->municipio_id == 1)
                         <td style="color:red;">{{$padron->municipio_id}}</td>
                         <td style="color:red;">{{$padron->municipio}}   </td>
                         <td style="color:red; text-align:center;">{{$padron->total}}      </td>
                      @endif
                      @if($padron->municipio_id == 2)
                         <td style="color:orange;">{{$padron->municipio_id}}</td>
                         <td style="color:orange;">{{$padron->municipio}}   </td>
                         <td style="color:orange; text-align:center;">{{$padron->total}}   </td>
                      @endif
                      @if($padron->municipio_id == 3)
                         <td style="color:blue;">{{$padron->municipio_id}}</td>
                         <td style="color:blue;">{{$padron->municipio}}   </td>
                         <td style="color:blue; text-align:center;">{{$padron->total}}     </td>
                      @endif
                      @if($padron->municipio_id == 4)
                         <td style="color:grey;">{{$padron->municipio_id}}</td>
                         <td style="color:grey;">{{$padron->municipio}}   </td>
                         <td style="color:grey; text-align:center;">{{$padron->total}}     </td>
                      @endif
                      @if($padron->municipio_id == 5)
                         <td style="color:purple;">{{$padron->municipio_id}}</td>
                         <td style="color:purple;">{{$padron->municipio}}   </td>
                         <td style="color:purple; text-align:center;">{{$padron->total}}   </td>
                      @endif
                      @if($padron->municipio_id == 6)
                         <td style="color:dodgerblue;">{{$padron->municipio_id}}</td>
                         <td style="color:dodgerblue;">{{$padron->municipio}}   </td>
                         <td style="color:dodgerblue; text-align:center;">{{$padron->total}}</td>
                      @endif
                      @if($padron->municipio_id == 7)
                         <td style="color:darkgreen;">{{$padron->municipio_id}}</td>
                         <td style="color:darkgreen;">{{$padron->municipio}}   </td>
                         <td style="color:darkgreen; text-align:center;">{{$padron->total}}</td>
                      @endif
                      @if($padron->municipio_id == 8)
                         <td style="color:red;">{{$padron->municipio_id}}</td>
                         <td style="color:red;">{{$padron->municipio}}   </td>
                         <td style="color:red; text-align:center;">{{$padron->total}}      </td>
                      @endif
                      @if($padron->municipio_id == 9)
                         <td style="color:orange;">{{$padron->municipio_id}}</td>
                         <td style="color:orange;">{{$padron->municipio}}   </td>
                         <td style="color:orange; text-align:center;">{{$padron->total}}   </td>
                      @endif
                      @if($padron->municipio_id == 10)
                         <td style="color:blue;">{{$padron->municipio_id}}</td>
                         <td style="color:blue;">{{$padron->municipio}}   </td>
                         <td style="color:blue; text-align:center;">{{$padron->total}}     </td>
                      @endif
                      @if($padron->municipio_id == 11)
                         <td style="color:grey;">{{$padron->municipio_id}}</td>
                         <td style="color:grey;">{{$padron->municipio}}   </td>
                         <td style="color:grey; text-align:center;">{{$padron->total}}     </td>
                      @endif
                      @if($padron->municipio_id == 12)
                         <td style="color:purple;">{{$padron->municipio_id}}</td>
                         <td style="color:purple;">{{$padron->municipio}}   </td>
                         <td style="color:purple; text-align:center;">{{$padron->total}}   </td>
                      @endif
                      @if($padron->municipio_id == 13)
                         <td style="color:dodgerblue;">{{$padron->municipio_id}}</td>
                         <td style="color:dodgerblue;">{{$padron->municipio}}   </td>
                         <td style="color:dodgerblue; text-align:center;">{{$padron->total}}</td>
                      @endif 
                      @if($padron->municipio_id == 14)
                         <td style="color:darkgreen;">{{$padron->municipio_id}}</td>
                         <td style="color:darkgreen;">{{$padron->municipio}}   </td>
                         <td style="color:darkgreen; text-align:center;">{{$padron->total}}</td>
                      @endif
                      @if($padron->municipio_id == 15)
                         <td style="color:red;">{{$padron->municipio_id}}</td>
                         <td style="color:red;">{{$padron->municipio}}   </td>
                         <td style="color:red; text-align:center;">{{$padron->total}}      </td>
                      @endif
                      @if($padron->municipio_id == 16)
                         <td style="color:orange;">{{$padron->municipio_id}}</td>
                         <td style="color:orange;">{{$padron->municipio}}   </td>
                         <td style="color:orange; text-align:center;">{{$padron->total}}   </td>
                      @endif
                      @if($padron->municipio_id == 17)
                         <td style="color:blue;">{{$padron->municipio_id}}</td>
                         <td style="color:blue;">{{$padron->municipio}}   </td>
                         <td style="color:blue; text-align:center;">{{$padron->total}}     </td>
                      @endif
                      @if($padron->municipio_id == 18)
                         <td style="color:grey;">{{$padron->municipio_id}}</td>
                         <td style="color:grey;">{{$padron->municipio}}   </td>
                         <td style="color:grey; text-align:center;">{{$padron->total}}     </td>
                      @endif
                      @if($padron->municipio_id == 19)
                         <td style="color:purple;">{{$padron->municipio_id}}</td>
                         <td style="color:purple;">{{$padron->municipio}}   </td>
                         <td style="color:purple; text-align:center;">{{$padron->total}}   </td>
                      @endif
                      @if($padron->municipio_id == 20)
                         <td style="color:dodgerblue;">{{$padron->municipio_id}}</td>
                         <td style="color:dodgerblue;">{{$padron->municipio}}   </td>
                         <td style="color:dodgerblue; text-align:center;">{{$padron->total}}</td>
                      @endif         

                      @if($padron->municipio_id == 30)
                         <td style="color:darkgreen;">{{$padron->municipio_id}}</td>
                         <td style="color:darkgreen;">{{$padron->municipio}}   </td>
                         <td style="color:darkgreen; text-align:center;">{{$padron->total}}</td>
                      @endif
                      @if($padron->municipio_id == 31)
                         <td style="color:red;">{{$padron->municipio_id}}</td>
                         <td style="color:red;">{{$padron->municipio}}   </td>
                         <td style="color:red; text-align:center;">{{$padron->total}}      </td>
                      @endif
                      @if($padron->municipio_id == 32)
                         <td style="color:orange;">{{$padron->municipio_id}}</td>
                         <td style="color:orange;">{{$padron->municipio}}   </td>
                         <td style="color:orange; text-align:center;">{{$padron->total}}   </td>
                      @endif
                      @if($padron->municipio_id == 33)
                         <td style="color:blue;">{{$padron->municipio_id}}</td>
                         <td style="color:blue;">{{$padron->municipio}}   </td>
                         <td style="color:blue; text-align:center;">{{$padron->total}}     </td>
                      @endif
                      @if($padron->municipio_id == 34)
                         <td style="color:grey;">{{$padron->municipio_id}}</td>
                         <td style="color:grey;">{{$padron->municipio}}   </td>
                         <td style="color:grey; text-align:center;">{{$padron->total}}     </td>
                      @endif
                      @if($padron->municipio_id == 35)
                         <td style="color:purple;">{{$padron->municipio_id}}</td>
                         <td style="color:purple;">{{$padron->municipio}}   </td>
                         <td style="color:purple; text-align:center;">{{$padron->total}}   </td>
                      @endif
                      @if($padron->municipio_id == 36)
                         <td style="color:dodgerblue;">{{$padron->municipio_id}}</td>
                         <td style="color:dodgerblue;">{{$padron->municipio}}   </td>
                         <td style="color:dodgerblue; text-align:center;">{{$padron->total}}</td>
                      @endif                     
                      @if($padron->municipio_id == 37)
                         <td style="color:darkgreen;">{{$padron->municipio_id}}</td>
                         <td style="color:darkgreen;">{{$padron->municipio}}   </td>
                         <td style="color:darkgreen; text-align:center;">{{$padron->total}}</td>
                      @endif
                      @if($padron->municipio_id == 38)
                         <td style="color:red;">{{$padron->municipio_id}}</td>
                         <td style="color:red;">{{$padron->municipio}}   </td>
                         <td style="color:red; text-align:center;">{{$padron->total}}      </td>
                      @endif
                      @if($padron->municipio_id == 39)
                         <td style="color:orange;">{{$padron->municipio_id}}</td>
                         <td style="color:orange;">{{$padron->municipio}}   </td>
                         <td style="color:orange; text-align:center;">{{$padron->total}}   </td>
                      @endif
                      @if($padron->municipio_id == 40)
                         <td style="color:blue;">{{$padron->municipio_id}}</td>
                         <td style="color:blue;">{{$padron->municipio}}   </td>
                         <td style="color:blue; text-align:center;">{{$padron->total}}     </td>
                      @endif
                      @if($padron->municipio_id == 41)
                         <td style="color:grey;">{{$padron->municipio_id}}</td>
                         <td style="color:grey;">{{$padron->municipio}}   </td>
                         <td style="color:grey; text-align:center;">{{$padron->total}}     </td>
                      @endif
                      @if($padron->municipio_id == 42)
                         <td style="color:purple;">{{$padron->municipio_id}}</td>
                         <td style="color:purple;">{{$padron->municipio}}   </td>
                         <td style="color:purple; text-align:center;">{{$padron->total}}   </td>
                      @endif
                      @if($padron->municipio_id == 43)
                         <td style="color:dodgerblue;">{{$padron->municipio_id}}</td>
                         <td style="color:dodgerblue;">{{$padron->municipio}}   </td>
                         <td style="color:dodgerblue; text-align:center;">{{$padron->total}}</td>
                      @endif    
                                                                                                 @if($padron->municipio_id == 0)
                         <td style="color:darkgreen;">{{$padron->municipio_id}}</td>
                         <td style="color:darkgreen;">{{$padron->municipio}}   </td>
                         <td style="color:darkgreen; text-align:center;">{{$padron->total}}</td>
                      @endif
                      @if($padron->municipio_id == 44)
                         <td style="color:red;">{{$padron->municipio_id}}</td>
                         <td style="color:red;">{{$padron->municipio}}   </td>
                         <td style="color:red; text-align:center;">{{$padron->total}}      </td>
                      @endif
                      @if($padron->municipio_id == 45)
                         <td style="color:orange;">{{$padron->municipio_id}}</td>
                         <td style="color:orange;">{{$padron->municipio}}   </td>
                         <td style="color:orange; text-align:center;">{{$padron->total}}   </td>
                      @endif
                      @if($padron->municipio_id == 46)
                         <td style="color:blue;">{{$padron->municipio_id}}</td>
                         <td style="color:blue;">{{$padron->municipio}}   </td>
                         <td style="color:blue; text-align:center;">{{$padron->total}}     </td>
                      @endif
                      @if($padron->municipio_id == 47)
                         <td style="color:grey;">{{$padron->municipio_id}}</td>
                         <td style="color:grey;">{{$padron->municipio}}   </td>
                         <td style="color:grey; text-align:center;">{{$padron->total}}     </td>
                      @endif
                      @if($padron->municipio_id == 48)
                         <td style="color:purple;">{{$padron->municipio_id}}</td>
                         <td style="color:purple;">{{$padron->municipio}}   </td>
                         <td style="color:purple; text-align:center;">{{$padron->total}}   </td>
                      @endif
                      @if($padron->municipio_id == 49)
                         <td style="color:dodgerblue;">{{$padron->municipio_id}}</td>
                         <td style="color:dodgerblue;">{{$padron->municipio}}   </td>
                         <td style="color:dodgerblue; text-align:center;">{{$padron->total}}</td>
                      @endif
                      @if($padron->municipio_id == 50)
                         <td style="color:darkgreen;">{{$padron->municipio_id}}</td>
                         <td style="color:darkgreen;">{{$padron->municipio}}   </td>
                         <td style="color:darkgreen; text-align:center;">{{$padron->total}}</td>
                      @endif
                      @if($padron->municipio_id == 51)
                         <td style="color:red;">{{$padron->municipio_id}}</td>
                         <td style="color:red;">{{$padron->municipio}}   </td>
                         <td style="color:red; text-align:center;">{{$padron->total}}      </td>
                      @endif
                      @if($padron->municipio_id == 52)
                         <td style="color:orange;">{{$padron->municipio_id}}</td>
                         <td style="color:orange;">{{$padron->municipio}}   </td>
                         <td style="color:orange; text-align:center;">{{$padron->total}}   </td>
                      @endif
                      @if($padron->municipio_id == 53)
                         <td style="color:blue;">{{$padron->municipio_id}}</td>
                         <td style="color:blue;">{{$padron->municipio}}   </td>
                         <td style="color:blue; text-align:center;">{{$padron->total}}     </td>
                      @endif
                      @if($padron->municipio_id == 54)
                         <td style="color:grey;">{{$padron->municipio_id}}</td>
                         <td style="color:grey;">{{$padron->municipio}}   </td>
                         <td style="color:grey; text-align:center;">{{$padron->total}}     </td>
                      @endif
                      @if($padron->municipio_id == 55)
                         <td style="color:purple;">{{$padron->municipio_id}}</td>
                         <td style="color:purple;">{{$padron->municipio}}   </td>
                         <td style="color:purple; text-align:center;">{{$padron->total}}   </td>
                      @endif
                      @if($padron->municipio_id == 56)
                         <td style="color:dodgerblue;">{{$padron->municipio_id}}</td>
                         <td style="color:dodgerblue;">{{$padron->municipio}}   </td>
                         <td style="color:dodgerblue; text-align:center;">{{$padron->total}}</td>
                      @endif       
                      @if($padron->municipio_id == 57)
                         <td style="color:darkgreen;">{{$padron->municipio_id}}</td>
                         <td style="color:darkgreen;">{{$padron->municipio}}   </td>
                         <td style="color:darkgreen; text-align:center;">{{$padron->total}}</td>
                      @endif
                      @if($padron->municipio_id == 58)
                         <td style="color:red;">{{$padron->municipio_id}}</td>
                         <td style="color:red;">{{$padron->municipio}}   </td>
                         <td style="color:red; text-align:center;">{{$padron->total}}      </td>
                      @endif
                      @if($padron->municipio_id == 59)
                         <td style="color:orange;">{{$padron->municipio_id}}</td>
                         <td style="color:orange;">{{$padron->municipio}}   </td>
                         <td style="color:orange; text-align:center;">{{$padron->total}}   </td>
                      @endif
                      @if($padron->municipio_id == 60)
                         <td style="color:blue;">{{$padron->municipio_id}}</td>
                         <td style="color:blue;">{{$padron->municipio}}   </td>
                         <td style="color:blue; text-align:center;">{{$padron->total}}     </td>
                      @endif
                      @if($padron->municipio_id == 61)
                         <td style="color:grey;">{{$padron->municipio_id}}</td>
                         <td style="color:grey;">{{$padron->municipio}}   </td>
                         <td style="color:grey; text-align:center;">{{$padron->total}}     </td>
                      @endif
                      @if($padron->municipio_id == 62)
                         <td style="color:purple;">{{$padron->municipio_id}}</td>
                         <td style="color:purple;">{{$padron->municipio}}   </td>
                         <td style="color:purple; text-align:center;">{{$padron->total}}   </td>
                      @endif
                      @if($padron->municipio_id == 63)
                         <td style="color:dodgerblue;">{{$padron->municipio_id}}</td>
                         <td style="color:dodgerblue;">{{$padron->municipio}}   </td>
                         <td style="color:dodgerblue; text-align:center;">{{$padron->total}}</td>
                      @endif     
                      @if($padron->municipio_id == 64)
                         <td style="color:darkgreen;">{{$padron->municipio_id}}</td>
                         <td style="color:darkgreen;">{{$padron->municipio}}   </td>
                         <td style="color:darkgreen; text-align:center;">{{$padron->total}}</td>
                      @endif
                      @if($padron->municipio_id == 65)
                         <td style="color:red;">{{$padron->municipio_id}}</td>
                         <td style="color:red;">{{$padron->municipio}}   </td>
                         <td style="color:red; text-align:center;">{{$padron->total}}      </td>
                      @endif
                      @if($padron->municipio_id == 66)
                         <td style="color:orange;">{{$padron->municipio_id}}</td>
                         <td style="color:orange;">{{$padron->municipio}}   </td>
                         <td style="color:orange; text-align:center;">{{$padron->total}}   </td>
                      @endif
                      @if($padron->municipio_id == 67)
                         <td style="color:blue;">{{$padron->municipio_id}}</td>
                         <td style="color:blue;">{{$padron->municipio}}   </td>
                         <td style="color:blue; text-align:center;">{{$padron->total}}     </td>
                      @endif
                      @if($padron->municipio_id == 68)
                         <td style="color:grey;">{{$padron->municipio_id}}</td>
                         <td style="color:grey;">{{$padron->municipio}}   </td>
                         <td style="color:grey; text-align:center;">{{$padron->total}}     </td>
                      @endif
                      @if($padron->municipio_id == 69)
                         <td style="color:purple;">{{$padron->municipio_id}}</td>
                         <td style="color:purple;">{{$padron->municipio}}   </td>
                         <td style="color:purple; text-align:center;">{{$padron->total}}   </td>
                      @endif
                      @if($padron->municipio_id == 70)
                         <td style="color:dodgerblue;">{{$padron->municipio_id}}</td>
                         <td style="color:dodgerblue;">{{$padron->municipio}}   </td>
                         <td style="color:dodgerblue; text-align:center;">{{$padron->total}}</td>
                      @endif     
                      @if($padron->municipio_id == 71)
                         <td style="color:darkgreen;">{{$padron->municipio_id}}</td>
                         <td style="color:darkgreen;">{{$padron->municipio}}   </td>
                         <td style="color:darkgreen; text-align:center;">{{$padron->total}}</td>
                      @endif
                      @if($padron->municipio_id == 72)
                         <td style="color:red;">{{$padron->municipio_id}}</td>
                         <td style="color:red;">{{$padron->municipio}}   </td>
                         <td style="color:red; text-align:center;">{{$padron->total}}      </td>
                      @endif
                      @if($padron->municipio_id == 73)
                         <td style="color:orange;">{{$padron->municipio_id}}</td>
                         <td style="color:orange;">{{$padron->municipio}}   </td>
                         <td style="color:orange; text-align:center;">{{$padron->total}}   </td>
                      @endif
                      @if($padron->municipio_id == 74)
                         <td style="color:blue;">{{$padron->municipio_id}}</td>
                         <td style="color:blue;">{{$padron->municipio}}   </td>
                         <td style="color:blue; text-align:center;">{{$padron->total}}     </td>
                      @endif
                      @if($padron->municipio_id == 75)
                         <td style="color:grey;">{{$padron->municipio_id}}</td>
                         <td style="color:grey;">{{$padron->municipio}}   </td>
                         <td style="color:grey; text-align:center;">{{$padron->total}}     </td>
                      @endif
                      @if($padron->municipio_id == 76)
                         <td style="color:purple;">{{$padron->municipio_id}}</td>
                         <td style="color:purple;">{{$padron->municipio}}   </td>
                         <td style="color:purple; text-align:center;">{{$padron->total}}   </td>
                      @endif
                      @if($padron->municipio_id == 77)
                         <td style="color:dodgerblue;">{{$padron->municipio_id}}</td>
                         <td style="color:dodgerblue;">{{$padron->municipio}}   </td>
                         <td style="color:dodgerblue; text-align:center;">{{$padron->total}}</td>
                      @endif
                      @if($padron->municipio_id == 78)
                         <td style="color:darkgreen;">{{$padron->municipio_id}}</td>
                         <td style="color:darkgreen;">{{$padron->municipio}}   </td>
                         <td style="color:darkgreen; text-align:center;">{{$padron->total}}</td>
                      @endif
                      @if($padron->municipio_id == 79)
                         <td style="color:red;">{{$padron->municipio_id}}</td>
                         <td style="color:red;">{{$padron->municipio}}   </td>
                         <td style="color:red; text-align:center;">{{$padron->total}}      </td>
                      @endif
                      @if($padron->municipio_id == 80)
                         <td style="color:orange;">{{$padron->municipio_id}}</td>
                         <td style="color:orange;">{{$padron->municipio}}   </td>
                         <td style="color:orange; text-align:center;">{{$padron->total}}   </td>
                      @endif
                      @if($padron->municipio_id == 81)
                         <td style="color:blue;">{{$padron->municipio_id}}</td>
                         <td style="color:blue;">{{$padron->municipio}}   </td>
                         <td style="color:blue; text-align:center;">{{$padron->total}}     </td>
                      @endif
                      @if($padron->municipio_id == 82)
                         <td style="color:grey;">{{$padron->municipio_id}}</td>
                         <td style="color:grey;">{{$padron->municipio}}   </td>
                         <td style="color:grey; text-align:center;">{{$padron->total}}     </td>
                      @endif
                      @if($padron->municipio_id == 83)
                         <td style="color:purple;">{{$padron->municipio_id}}</td>
                         <td style="color:purple;">{{$padron->municipio}}   </td>
                         <td style="color:purple; text-align:center;">{{$padron->total}}   </td>
                      @endif
                      @if($padron->municipio_id == 84)
                         <td style="color:dodgerblue;">{{$padron->municipio_id}}</td>
                         <td style="color:dodgerblue;">{{$padron->municipio}}   </td>
                         <td style="color:dodgerblue; text-align:center;">{{$padron->total}}</td>
                      @endif                      
                      @if($padron->municipio_id == 85)
                         <td style="color:darkgreen;">{{$padron->municipio_id}}</td>
                         <td style="color:darkgreen;">{{$padron->municipio}}   </td>
                         <td style="color:darkgreen; text-align:center;">{{$padron->total}}</td>
                      @endif
                      @if($padron->municipio_id == 86)
                         <td style="color:red;">{{$padron->municipio_id}}</td>
                         <td style="color:red;">{{$padron->municipio}}   </td>
                         <td style="color:red; text-align:center;">{{$padron->total}}      </td>
                      @endif
                      @if($padron->municipio_id == 87)
                         <td style="color:orange;">{{$padron->municipio_id}}</td>
                         <td style="color:orange;">{{$padron->municipio}}   </td>
                         <td style="color:orange; text-align:center;">{{$padron->total}}   </td>
                      @endif
                      @if($padron->municipio_id == 88)
                         <td style="color:blue;">{{$padron->municipio_id}}</td>
                         <td style="color:blue;">{{$padron->municipio}}   </td>
                         <td style="color:blue; text-align:center;">{{$padron->total}}     </td>
                      @endif
                      @if($padron->municipio_id == 89)
                         <td style="color:grey;">{{$padron->municipio_id}}</td>
                         <td style="color:grey;">{{$padron->municipio}}   </td>
                         <td style="color:grey; text-align:center;">{{$padron->total}}     </td>
                      @endif
                      @if($padron->municipio_id == 90)
                         <td style="color:purple;">{{$padron->municipio_id}}</td>
                         <td style="color:purple;">{{$padron->municipio}}   </td>
                         <td style="color:purple; text-align:center;">{{$padron->total}}   </td>
                      @endif
                      @if($padron->municipio_id == 91)
                         <td style="color:dodgerblue;">{{$padron->municipio_id}}</td>
                         <td style="color:dodgerblue;">{{$padron->municipio}}   </td>
                         <td style="color:dodgerblue; text-align:center;">{{$padron->total}}</td>
                      @endif                      
                      @if($padron->municipio_id == 92)
                         <td style="color:darkgreen;">{{$padron->municipio_id}}</td>
                         <td style="color:darkgreen;">{{$padron->municipio}}   </td>
                         <td style="color:darkgreen; text-align:center;">{{$padron->total}}</td>
                      @endif
                      @if($padron->municipio_id == 93)
                         <td style="color:red;">{{$padron->municipio_id}}</td>
                         <td style="color:red;">{{$padron->municipio}}   </td>
                         <td style="color:red; text-align:center;">{{$padron->total}}      </td>
                      @endif
                      @if($padron->municipio_id == 94)
                         <td style="color:orange;">{{$padron->municipio_id}}</td>
                         <td style="color:orange;">{{$padron->municipio}}   </td>
                         <td style="color:orange; text-align:center;">{{$padron->total}}   </td>
                      @endif
                      @if($padron->municipio_id == 95)
                         <td style="color:blue;">{{$padron->municipio_id}}</td>
                         <td style="color:blue;">{{$padron->municipio}}   </td>
                         <td style="color:blue; text-align:center;">{{$padron->total}}     </td>
                      @endif
                      @if($padron->municipio_id == 96)
                         <td style="color:grey;">{{$padron->municipio_id}}</td>
                         <td style="color:grey;">{{$padron->municipio}}   </td>
                         <td style="color:grey; text-align:center;">{{$padron->total}}     </td>
                      @endif
                      @if($padron->municipio_id == 97)
                         <td style="color:purple;">{{$padron->municipio_id}}</td>
                         <td style="color:purple;">{{$padron->municipio}}   </td>
                         <td style="color:purple; text-align:center;">{{$padron->total}}   </td>
                      @endif
                      @if($padron->municipio_id == 98)
                         <td style="color:dodgerblue;">{{$padron->municipio_id}}</td>
                         <td style="color:dodgerblue;">{{$padron->municipio}}   </td>
                         <td style="color:dodgerblue; text-align:center;">{{$padron->total}}</td>
                      @endif      
                      @if($padron->municipio_id == 99)
                         <td style="color:darkgreen;">{{$padron->municipio_id}}</td>
                         <td style="color:darkgreen;">{{$padron->municipio}}   </td>
                         <td style="color:darkgreen; text-align:center;">{{$padron->total}}</td>
                      @endif
                      @if($padron->municipio_id == 100)
                         <td style="color:red;">{{$padron->municipio_id}}</td>
                         <td style="color:red;">{{$padron->municipio}}   </td>
                         <td style="color:red; text-align:center;">{{$padron->total}}      </td>
                      @endif
                      @if($padron->municipio_id == 101)
                         <td style="color:orange;">{{$padron->municipio_id}}</td>
                         <td style="color:orange;">{{$padron->municipio}}   </td>
                         <td style="color:orange; text-align:center;">{{$padron->total}}   </td>
                      @endif
                      @if($padron->municipio_id == 102)
                         <td style="color:blue;">{{$padron->municipio_id}}</td>
                         <td style="color:blue;">{{$padron->municipio}}   </td>
                         <td style="color:blue; text-align:center;">{{$padron->total}}     </td>
                      @endif
                      @if($padron->municipio_id == 103)
                         <td style="color:grey;">{{$padron->municipio_id}}</td>
                         <td style="color:grey;">{{$padron->municipio}}   </td>
                         <td style="color:grey; text-align:center;">{{$padron->total}}     </td>
                      @endif
                      @if($padron->municipio_id == 104)
                         <td style="color:purple;">{{$padron->municipio_id}}</td>
                         <td style="color:purple;">{{$padron->municipio}}   </td>
                         <td style="color:purple; text-align:center;">{{$padron->total}}   </td>
                      @endif
                      @if($padron->municipio_id == 105)
                         <td style="color:dodgerblue;">{{$padron->municipio_id}}</td>
                         <td style="color:dodgerblue;">{{$padron->municipio}}   </td>
                         <td style="color:dodgerblue; text-align:center;">{{$padron->total}}</td>
                      @endif    
                                                                             @if($padron->municipio_id == 0)
                         <td style="color:darkgreen;">{{$padron->municipio_id}}</td>
                         <td style="color:darkgreen;">{{$padron->municipio}}   </td>
                         <td style="color:darkgreen; text-align:center;">{{$padron->total}}</td>
                      @endif
                      @if($padron->municipio_id == 106)
                         <td style="color:red;">{{$padron->municipio_id}}</td>
                         <td style="color:red;">{{$padron->municipio}}   </td>
                         <td style="color:red; text-align:center;">{{$padron->total}}      </td>
                      @endif
                      @if($padron->municipio_id == 107)
                         <td style="color:orange;">{{$padron->municipio_id}}</td>
                         <td style="color:orange;">{{$padron->municipio}}   </td>
                         <td style="color:orange; text-align:center;">{{$padron->total}}   </td>
                      @endif
                      @if($padron->municipio_id == 108)
                         <td style="color:blue;">{{$padron->municipio_id}}</td>
                         <td style="color:blue;">{{$padron->municipio}}   </td>
                         <td style="color:blue; text-align:center;">{{$padron->total}}     </td>
                      @endif
                      @if($padron->municipio_id == 109)
                         <td style="color:grey;">{{$padron->municipio_id}}</td>
                         <td style="color:grey;">{{$padron->municipio}}   </td>
                         <td style="color:grey; text-align:center;">{{$padron->total}}     </td>
                      @endif
                      @if($padron->municipio_id == 110)
                         <td style="color:purple;">{{$padron->municipio_id}}</td>
                         <td style="color:purple;">{{$padron->municipio}}   </td>
                         <td style="color:purple; text-align:center;">{{$padron->total}}   </td>
                      @endif
                      @if($padron->municipio_id == 111)
                         <td style="color:dodgerblue;">{{$padron->municipio_id}}</td>
                         <td style="color:dodgerblue;">{{$padron->municipio}}   </td>
                         <td style="color:dodgerblue; text-align:center;">{{$padron->total}}</td>
                      @endif

                      @if($padron->municipio_id == 112)
                         <td style="color:darkgreen;">{{$padron->municipio_id}}</td>
                         <td style="color:darkgreen;">{{$padron->municipio}}   </td>
                         <td style="color:darkgreen; text-align:center;">{{$padron->total}}</td>
                      @endif
                      @if($padron->municipio_id == 113)
                         <td style="color:red;">{{$padron->municipio_id}}</td>
                         <td style="color:red;">{{$padron->municipio}}   </td>
                         <td style="color:red; text-align:center;">{{$padron->total}}      </td>
                      @endif
                      @if($padron->municipio_id == 114)
                         <td style="color:orange;">{{$padron->municipio_id}}</td>
                         <td style="color:orange;">{{$padron->municipio}}   </td>
                         <td style="color:orange; text-align:center;">{{$padron->total}}   </td>
                      @endif
                      @if($padron->municipio_id == 115)
                         <td style="color:blue;">{{$padron->municipio_id}}</td>
                         <td style="color:blue;">{{$padron->municipio}}   </td>
                         <td style="color:blue; text-align:center;">{{$padron->total}}     </td>
                      @endif
                      @if($padron->municipio_id == 116)
                         <td style="color:grey;">{{$padron->municipio_id}}</td>
                         <td style="color:grey;">{{$padron->municipio}}   </td>
                         <td style="color:grey; text-align:center;">{{$padron->total}}     </td>
                      @endif
                      @if($padron->municipio_id == 117)
                         <td style="color:purple;">{{$padron->municipio_id}}</td>
                         <td style="color:purple;">{{$padron->municipio}}   </td>
                         <td style="color:purple; text-align:center;">{{$padron->total}}   </td>
                      @endif
                      @if($padron->municipio_id == 118)
                         <td style="color:dodgerblue;">{{$padron->municipio_id}}</td>
                         <td style="color:dodgerblue;">{{$padron->municipio}}   </td>
                         <td style="color:dodgerblue; text-align:center;">{{$padron->total}}</td>
                      @endif                      
                      @if($padron->municipio_id == 119)
                         <td style="color:darkgreen;">{{$padron->municipio_id}}</td>
                         <td style="color:darkgreen;">{{$padron->municipio}}   </td>
                         <td style="color:darkgreen; text-align:center;">{{$padron->total}}</td>
                      @endif
                      @if($padron->municipio_id == 120)
                         <td style="color:red;">{{$padron->municipio_id}}</td>
                         <td style="color:red;">{{$padron->municipio}}   </td>
                         <td style="color:red; text-align:center;">{{$padron->total}}      </td>
                      @endif
                      @if($padron->municipio_id == 121)
                         <td style="color:orange;">{{$padron->municipio_id}}</td>
                         <td style="color:orange;">{{$padron->municipio}}   </td>
                         <td style="color:orange; text-align:center;">{{$padron->total}}   </td>
                      @endif
                      @if($padron->municipio_id == 122)
                         <td style="color:blue;">{{$padron->municipio_id}}</td>
                         <td style="color:blue;">{{$padron->municipio}}   </td>
                         <td style="color:blue; text-align:center;">{{$padron->total}}     </td>
                      @endif
                      @if($padron->municipio_id == 123)
                         <td style="color:grey;">{{$padron->municipio_id}}</td>
                         <td style="color:grey;">{{$padron->municipio}}   </td>
                         <td style="color:grey; text-align:center;">{{$padron->total}}     </td>
                      @endif
                      @if($padron->municipio_id == 124)
                         <td style="color:purple;">{{$padron->municipio_id}}</td>
                         <td style="color:purple;">{{$padron->municipio}}   </td>
                         <td style="color:purple; text-align:center;">{{$padron->total}}   </td>
                      @endif
                      @if($padron->municipio_id == 125)
                         <td style="color:dodgerblue;">{{$padron->municipio_id}}</td>
                         <td style="color:dodgerblue;">{{$padron->municipio}}   </td>
                         <td style="color:dodgerblue; text-align:center;">{{$padron->total}}</td>
                      @endif                      
                    </tr>
                  @endforeach
                  @foreach($regtotxmpio as $totales)
                     <tr>
                         <td></td>
                         <td style="color:green;"><b>TOTAL</b></td>                         
                         <td style="color:green; text-align:center;"><b>{{$totales->totalxmpio}} </b></td>                      
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
            <div class="box box-danger">
              <div class="box-header with-border">
                <!--<h3 class="box-title" style="text-align:center;">Gráfica por Municipio 2D </h3>  -->
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
          ['Municipios', 'Total'],
          @foreach($regpadron as $padron)
             ['{{$padron->municipio}}', {{$padron->total}} ],
          @endforeach          
          //["King's pawn (e4)", 44],
          //["Queen's pawn (d4)", 31],
          //["Knight to King 3 (Nf3)", 12],
          //["Queen's bishop pawn (c4)", 10],
          //['Other', 3]
          //colors:['red','#004411'],
          //color    : 'orange',
          //highlight: 'orange',
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
                 
          title: 'Padrón de beneficiarios por municipio',
          width: 900,                   // Ancho de la pantalla horizontal
          height: 900,                  // Alto de la pantall '75%',
          colors: ['#e7711c'],
          //backgroundColor:'#fdc', 
          //stroke:'green',
          //color    : 'orange',
          //highlight: 'orange',
          legend: { position: 'none' },
          chart: { title: 'Gráfica de padrón de beneficiarios por municipio 2D',
                   subtitle: 'Cantidad de beneficiarios por municipio' },
          bars: 'horizontal', // Required for Material Bar Charts.
          //bars: 'vertical', // Required for Material Bar Charts.
          //chartArea:{left:20, top:0, width:'50%', height:'75%', backgroundColor:'#fdc', stroke:'green'},
          axes: {
            x: {
              0: { side: 'top', label: 'Total de beneficiarios'} // Top x-axis.
              //1: { side: 'top', label: 'Total de IAPS'} // Top x-axis.
              //distance: {label: 'Total'}, // Bottom x-axis.
              //brightness: {side: 'top', label: 'Total de IAPS'} // Top x-axis.
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
