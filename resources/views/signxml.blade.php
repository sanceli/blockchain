@extends('layouts.app')

@section('content')

    <div class="header  pb-1 pt-1 pt-md-6">
        <div class="container-fluid">
            <div class="header-body">
                <form method="post" action="{{ route('uploadxml') }}" enctype="multipart/form-data">
                    @csrf
                    <div class="form-group">
                        <label for="xml_file">Cargar el archivo XML firmado</label>
                        <input type="file" class="form-control-file" id="xml_file" name="xml_file"  accept="text/xml"required>
                    </div>
                    <button type="submit" class="btn btn-primary">Subir Archivo XML</button>
                </form>
            </div>
        </div>
    </div>
    @include('layouts.footers.auth')
@endsection
