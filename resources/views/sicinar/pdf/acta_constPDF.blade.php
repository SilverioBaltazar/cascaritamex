@extends('sicinar.pdf.layoutActaConstitutiva')

@section('content')
<div class='container'>
    <div class='row'>
        <div class='col-sm-2'>
            <a href='/admin'>< Back to admin</a>
        </div>
        <!--<div class='col-sm-8'>
            {{ $file }}
            <embed src="{{ Storage::url(/images/$file->file_path) }}" style="width:600px; height:800px;" frameborder="0">
        </div> -->
    </div>
</div>
@endsection