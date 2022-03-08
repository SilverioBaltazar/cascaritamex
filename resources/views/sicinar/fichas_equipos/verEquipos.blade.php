@extends('sicinar.principal')

@section('title','Ver equipos')

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
            <h1><i class="fa fa-dashboard"></i>&nbsp;&nbsp;Menú.
                <small>3. Finalizar registro   </small>
            </h1>
            <ol class="breadcrumb">
                <li><img src="{{ asset('images/b3.jpg') }}" border="0" width="30" height="30">Finalizar registro de equipo en el torneo</li> 
            </ol>            
        </section>
        <section class="content">
            <div class="row">
                <div class="col-md-12">
                    <div class="box">
                        <p align="justify"><b style="color:red;">
                            FINALIZAR REGISTRO:</b> <b style="color:green;">
                            La plataforma proporcionará un folio de registro por equipo al finalizar el proceso de registro. 
                            En este momento la plataforma envía la “Ficha de confirmación de registro”, misma que deberán 
                            presentar el día de su participación en la sede regional correspondiente.
                            </b>
                        </p>              

                        <div class="page-header" style="text-align:right;">      
                        @if(session()->get('rango') == '4')                          
                            {{ Form::open(['route' => 'buscarficha', 'method' => 'GET', 'class' => 'form-inline pull-right']) }}
                                <div class="form-group">
                                    {{ Form::text('name', null, ['class' => 'form-control', 'placeholder' => 'Nombre equipo']) }}
                                </div>
                                <div class="form-group">
                                    {{ Form::text('bio', null, ['class' => 'form-control', 'placeholder' => 'Representante']) }}
                                </div>
                                <div class="form-group">
                                    <button type="submit" class="btn btn-default">
                                        <span class="glyphicon glyphicon-search"></span>
                                    </button>
                                </div>
                                <div class="form-group">
                                    <a href="{{route('exportequiposexcel')}}" class="btn btn-success" title="Exportar fichas de equipos a formato Excel"><i class="fa fa-file-excel-o"></i>Excel</a>                            
                                    <a href="{{route('exportequipospdf')}}"   class="btn btn-danger"  title="Exportar fichas de equipos a formato PDF  "><i class="fa fa-file-pdf-o"  ></i><small>PDF</small></a>
                                </div>                                
                            {{ Form::close() }}
                        @endif
                        </div>

                        <div class="box-body">
                            <table id="tabla1" class="table table-hover table-striped">
                                <thead style="color: brown;" class="justify">
                                    <tr>
                                        <th style="text-align:left;   vertical-align: middle;">Folio            </th>
                                        <th style="text-align:left;   vertical-align: middle;">Equipo de futbol </th>
                                        <th style="text-align:left;   vertical-align: middle;">Representante    </th>     
                                        <th style="text-align:left;   vertical-align: middle;">Teléfono         </th>     
                                        <th style="text-align:left;   vertical-align: middle;">e-mail           </th>     
                                        <th style="text-align:left;   vertical-align: middle;">Rama             </th>     
                                        <th style="text-align:left;   vertical-align: middle;">Categoria        </th>     
                                        <th style="text-align:left;   vertical-align: middle;">Municipio        </th>     
                                        <th style="text-align:left;   vertical-align: middle;">Región           </th>     
                                        <th style="text-align:left;   vertical-align: middle;">Arc.  <br>Doctos.</th>

                                        <th style="text-align:center; vertical-align: middle; width:100px;">Finalizar<br>Registro</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($regficha as $ficha)
                                    <tr>
                                        <td style="text-align:left; vertical-align: middle;">{{$ficha->eq_id}}            </td>
                                        <td style="text-align:left; vertical-align: middle;">{{Trim($ficha->eq_desc)}}    </td>
                                        <td style="text-align:left; vertical-align: middle;">{{Trim($ficha->eq_nombrecomp_rep)}}</td>
                                        <td style="text-align:left; vertical-align: middle;">{{$ficha->eq_tel_rep}}  </td>
                                        <td style="text-align:left; vertical-align: middle;">{{$ficha->eq_email_rep}}</td>
                                        @if($ficha->eq_rama == 'V')
                                            <td style="color:darkblue;text-align:center; vertical-align: middle;font-size:09px;" title="Varonil">VARONIL</td>                                            
                                        @else
                                            @if($ficha->eq_rama == 'F')
                                               <td style="color:darkpink; text-align:center; vertical-align: middle;font-size:09px;" title="Femenil">FEMENIL </td>  
                                            @else
                                               <td style="color:darkred; text-align:center; vertical-align: middle;font-size:09px;" title="Femenil">** </td>  
                                            @endif
                                        @endif                                                                                
                                        <td style="text-align:left; vertical-align: middle; font-size:09px;"> 
                                            @foreach($regcategoria as $categ)
                                                @if($categ->cate_id == $ficha->cate_id)
                                                    {{Trim($categ->cate_desc)}}
                                                    @break 
                                                @endif
                                            @endforeach 
                                        </td>                                          
                                        <td style="text-align:left; vertical-align: middle; font-size:09px;">
                                            {{$ficha->municipio_id}}   
                                            @foreach($regmunicipio as $mun)
                                                @if($mun->municipioid == $ficha->municipio_id)
                                                    {{Trim($mun->municipio)}}
                                                    @break 
                                                @endif
                                            @endforeach 
                                        </td>   
                                        <td style="text-align:left; vertical-align: middle; font-size:09px;">
                                            @foreach($regregiones as $region)
                                                @if($region->region_id == $ficha->region_id)
                                                    {{Trim($region->desc_region).' '.Trim($region->roma_region)}}
                                                    @break 
                                                @endif
                                            @endforeach 
                                        </td>              
                                        @if(!empty($ficha->eq_arc1)&&(!is_null($ficha->eq_arc1)))
                                            <td style="color:darkgreen;text-align:center; vertical-align:middle;font-size:10px;" title="Archivo digital de ficha del equipo en formato PDF">
                                                <a href="/storage/{{$ficha->eq_arc1}}" class="btn btn-danger" title="Archivo digital de ficha del equipo en formato PDF"><i class="fa fa-file-pdf-o"></i>
                                                </a>
                                                <a href="{{route('editarficha1',$ficha->eq_id)}}" class="btn badge-warning" title="Editar Archivo digital de ficha del equipo en formato PDF"><i class="fa fa-edit"></i>
                                                </a>
                                            </td>
                                        @else
                                            <td style="color:darkred; text-align:center; vertical-align: middle;font-size:10px;" title="Archivo digital de ficha del equipo en formato PDF"><i class="fa fa-times"></i>
                                                <a href="{{route('editarficha1',$ficha->eq_id)}}" class="btn badge-warning" title="Editar Archivo digital de ficha del equipo en formato PDF"><i class="fa fa-edit"></i>
                                                </a>
                                            </td>   
                                        @endif
                                        
                                        <td style="text-align:center;">
                                            <a href="{{route('exportequipopdf',$ficha->eq_id)}}" class="btn btn-danger" title="Finalizar registro del equipo en el torneo" onclick="return confirm('¿Seguro que desea concluir el registro del equipo en el torneo estatal de futbol. Una vez concluido ya no podra realizar cambios?')"><i class="fa fa-file-pdf-o"></i>
                                            <small> PDF</small>
                                            </a>
                                        </td>                                                                                
                                    </tr>
                                    @endforeach
                                    @if(session()->get('rango') == '0')
                                    <tr>
                                            <td style="text-align:left; rowspan=2;">
                                                <a href="{{route('verpadron')}}">                                        
                                                <img src="{{ asset('images/b2.jpg') }}"   border="0" width="25" height="30" title="Registrar jugadores del equipo">
                                                </a>                                            
                                            </td>
                                            <td style="text-align:left;"  >
                                                <a href="{{route('verpadron')}}">                                        
                                                <img src="{{ asset('images/fant.jpg') }}" border="0" width="30" height="30" title="Anterior registrar jugadores del equipo">
                                                </a>                                                                                      
                                            </td>
                                            <td style="text-align:left;"  ></td>
                                            <td style="text-align:center;"></td>
                                            <td style="text-align:center;"></td>
                                            <td style="text-align:center;"></td>
                                            <td style="text-align:center;"></td>                                            
                                            <td style="text-align:right;"></td>
                                            <td style="text-align:right;"></td>
                                            <td style="text-align:right;"></td>
                                            <td style="text-align:right; ">
                                                <a href="{{route('verficha')}}">                                        
                                                <img src="{{ asset('images/fsig.jpg') }}" border="0" width="30" height="30" title="Siguiente ficha del equipo">
                                                <img src="{{ asset('images/b1.jpg') }}"   border="0" width="25" height="30" title="Ficha del equipo">
                                                </a>
                                            </td>
                                    </tr>
                                    @endif                                    
                                </tbody>
                            </table>
                            {!! $regficha->appends(request()->input())->links() !!}
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
@endsection

@section('request')
@endsection

@section('javascrpt')
@endsection