@extends('sicinar.principal')

@section('title','Ver Documentos')

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
            <h1>Catálogos
                <small> Seleccionar para editar o registrar</small>
            </h1>
            <ol class="breadcrumb">
                <li><a href="#"><i class="fa fa-dashboard"></i> Menú</a></li>
                <li><a href="#">Catálogos   </a></li>
                <li><a href="#">Documentos  </a></li>         
            </ol>
        </section>
        <section class="content">
            <div class="row">
                <div class="col-md-12">
                    <div class="box">

                        <div class="page-header" style="text-align:right;">
                            Busqueda  
                            {{ Form::open(['route' => 'buscarDocto', 'method' => 'GET', 'class' => 'form-inline pull-right']) }}
                                <div class="form-group">
                                    {{ Form::text('name', null, ['class' => 'form-control', 'placeholder' => 'documento']) }}
                                </div>
                                <div class="form-group">
                                    <button type="submit" class="btn btn-default">
                                    <span class="glyphicon glyphicon-search"></span>
                                    </button>
                                </div>
                                <div class="form-group">   
                                   <a href="{{route('catDoctosExcel')}}" class="btn btn-success" title="Exportar catálogo de documentos (formato Excel)"><i class="fa fa-file-excel-o"></i> Excel
                                   </a>                            
                                   <a href="{{route('catDoctosPDF')}}" class="btn btn-danger" title="Exportar catálogo de documentos (formato PDF)"><i class="fa fa-file-pdf-o"></i> PDF
                                   </a>  
                                   <a href="{{route('nuevoDocto')}}"   class="btn btn-primary btn_xs" title="Nuevo documento"><i class="fa fa-file-new-o"></i><span class="glyphicon glyphicon-plus"></span>Nuevo documento
                                   </a>
                                </div>                                
                            {{ Form::close() }}

                        </div>

                        <div class="box-body">
                            <table id="tabla1" class="table table-hover table-striped">
                                <thead style="color: brown;" class="justify">
                                    <tr>
                                        <th style="text-align:left;   vertical-align: middle;">Id.          </th>
                                        <th style="text-align:left;   vertical-align: middle;">Documento    </th>
                                        <th style="text-align:center; vertical-align: middle;">Periodicidad </th>
                                        <th style="text-align:center; vertical-align: middle;">Frec.        </th>
                                        <th style="text-align:center; vertical-align: middle;">Control      </th>
                                        <th style="text-align:center; vertical-align: middle;">Tipo         </th> 
                                        <th style="text-align:center; vertical-align: middle;">Formato      </th>
                                        <th style="text-align:center; vertical-align: middle;">Archivo<br>Digital</th>
                                        <th style="text-align:center; vertical-align: middle;">Observaciones</th>
                                        <th style="text-align:center; vertical-align: middle;">Activo <br>Inact. </th>
                                        
                                        <th style="text-align:center; vertical-align: middle; width:100px;">Acciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($regdocto as $docto)
                                    <tr>
                                        <td style="text-align:left; vertical-align: middle;"><small>{{$docto->doc_id}}</small>
                                        </td>
                                        <td style="text-align:left; vertical-align: middle;"><small>{{Trim($docto->doc_desc)}} </small>
                                        </td>
                                        <td style="text-align:left; vertical-align: middle;"> <small>
                                            @foreach($regper as $frec)
                                                @if($frec->per_id == $docto->per_id)
                                                    {{$frec->per_desc}}
                                                    @break
                                                @endif
                                            @endforeach 
                                        </small>
                                        </td> 
                                        <td style="text-align:center;vertical-align:middle;"><small>{{Trim($docto->per_frec)}} </small>
                                        </td>
                                        @if($docto->doc_status2 == 'S')
                                            <td style="text-align:center; vertical-align:middle;"><small>INTERNO</small>
                                            </td>                                            
                                        @else
                                            <td style="text-align:center; vertical-align:middle;"><small>EXTERNO</small>
                                            </td>                                            
                                        @endif
                                        @if($docto->doc_status3 == 'S')
                                            <td style="text-align:center; vertical-align:middle;"><small>OBLIGATORIO</small>
                                            </td>
                                        @else
                                            <td style="text-align:center; vertical-align:middle;"><small>OPCIONAL</small>
                                            </td>                                            
                                        @endif                                                             
                                        <td style="text-align:center; vertical-align: middle;"><small>
                                            @foreach($regformato as $formato)
                                                @if($formato->formato_id == $docto->formato_id)
                                                    {{$formato->formato_desc}}
                                                    @break
                                                @endif
                                            @endforeach </small>
                                        </td>
                                        
                                        @if(isset($docto->doc_file))
                                            @switch($docto->formato_id)
                                            @case(1)
                                                <td style="color:darkgreen;text-align:center; vertical-align: middle;" title="Archivo de documento en formato excel">
                                                    <a href="/images/{{$docto->doc_file}}" class="btn btn-success" title="Documento en formato excel"><i class="fa fa-file-excel-o"></i><small>Excel</small>
                                                    </a>
                                                    <a href="{{route('editarDocto1',$docto->doc_id)}}" class="btn badge-warning" title="Editar archivo de documento en formato excel"><i class="fa fa-edit"></i>
                                                    </a>
                                                </td>
                                                @break
                                            @case(2)
                                                <td style="color:darkgreen;text-align:center; vertical-align: middle;" title="Archivo de documento en formato PDF">
                                                    <a href="/images/{{$docto->doc_file}}" class="btn btn-danger" title="Archivo de documento en formato PDF"><i class="fa fa-file-pdf-o"></i><small>PDF</small>
                                                    </a>
                                                    <a href="{{route('editarDocto1',$docto->doc_id)}}" class="btn badge-warning" title="Archivo de documento en formato PDF"><i class="fa fa-edit"></i>
                                                    </a>
                                                </td>
                                                @break
                                            @case(3)  
                                                <td style="color:darkgreen;text-align:center; vertical-align: middle;" title="Archivo de documento Imagen o Fotografía">
                                                    <a href="/images/{{$docto->doc_file}}" class="btn btn-success" title="Imagen o fotografía"><i class="fa-file-image-o"></i>gif, jpeg o png
                                                    </a>
                                                    <a href="{{route('editarDocto1',$docto->doc_id)}}" class="btn badge-warning" title="Editar archivo de documento en Imagen o Fotografía"><i class="fa fa-edit"></i>
                                                    </a>
                                                </td>
                                                @break 
                                            @case(4)  
                                                <td style="color:darkgreen;text-align:center; vertical-align: middle;" title="Archivo en formato word">
                                                    <a href="/images/{{$docto->doc_file}}" class="btn btn-success" title="Archivo del documento en formato word"><i class="fa fa-file-word-o"></i>Word
                                                    </a>
                                                    <a href="{{route('editarDocto1',$docto->doc_id)}}" class="btn badge-warning" title="Editar Archivo del documento en word"><i class="fa fa-edit"></i>
                                                    </a>
                                                </td>
                                                @break 
                                            @case(5)  
                                                <td style="color:darkgreen;text-align:center; vertical-align: middle;" title="Archivo en formato PowerPoint">
                                                    <a href="/images/{{$docto->doc_file}}" class="btn btn-success" title="Archivo del documento en formato Powerpoint"><i class="fa fa-file-powerpoint-o"></i>PowerPoint
                                                    </a>
                                                    <a href="{{route('editarDocto1',$docto->doc_id)}}" class="btn badge-warning" title="Editar Archivo del documento en PowerPoint"><i class="fa fa-edit"></i>
                                                    </a>
                                                </td>
                                                @break                              
                                            @endswitch
                                            @if($docto->formato_id == 1)  
                                            @else
                                                @if($docto->formato_id == 2)  
                                                @else
                                                @endif
                                            @endif
                                        @else
                                            <td style="color:darkred; text-align:center; vertical-align: middle;" title="Sin formato de archivo"><i class="fa fa-times">{{$docto->doc_file}} </i>
                                            <a href="{{route('editarDocto1',$docto->doc_id)}}" class="btn badge-warning" title="Editar documento"><i class="fa fa-edit"></i>
                                            </a>
                                        @endif   
                                        <td style="text-align:justify;vertical-align:middle;"><small>{{Trim($docto->doc_obs)}} </small>
                                        </td>                                                                                
                                        @if($docto->doc_status == 'S')
                                            <td style="color:darkgreen;text-align:center; vertical-align: middle;" title="Activo"><i class="fa fa-check"></i>
                                            </td>                                            
                                        @else
                                            <td style="color:darkred; text-align:center; vertical-align: middle;" title="Inactivo"><i class="fa fa-times"></i>
                                            </td>                                            
                                        @endif
                                        

                                        <td style="text-align:center;">
                                            <a href="{{route('editarDocto',$docto->doc_id)}}" class="btn badge-warning" title="Editar documento"><i class="fa fa-edit"></i>
                                            </a>
                                            <a href="{{route('borrarDocto',$docto->doc_id)}}" class="btn badge-danger" title="Borrar documento" onclick="return confirm('¿Seguro que desea borrar el documento?')"><i class="fa fa-times"></i>
                                            </a>
                                        </td>                                                                                
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                            {!! $regdocto->appends(request()->input())->links() !!}
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