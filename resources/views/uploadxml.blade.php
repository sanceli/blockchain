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

    <!-- Modal -->
    <div class="modal fade" id="statusModal" tabindex="-1" role="dialog" aria-labelledby="statusModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="statusModalLabel">Estado</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    {{ session('status') }}
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                </div>
            </div>
        </div>
    </div>
    <!-- Script para ejecutar el Modal -->

    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.11.0/umd/popper.min.js"></script>
    <script>
        $(document).ready(function() {
            @if(session('status'))
            $('#statusModal').modal('show');
            @endif
        });
    </script>

    @include('layouts.footers.auth')
@endsection
