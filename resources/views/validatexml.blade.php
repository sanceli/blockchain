@extends('layouts.app')

@section('content')

    <div class="header bg-gradient-primary pb-8 pt-5 pt-md-8">
        <div class="container-fluid">
            <div class="header-body">
                <div class="row">
                    <div class="col">
                        <div class="card">
                            <div class="card-header border-0">
                                <h3 class="mb-0">Validar archivo XML con el hash de blockchain</h3>
                            </div>
                            <div class="table-responsive">
                                <table class="table align-items-center table-flush">
                                    <thead class="thead-light">
                                    <tr>
                                        <th scope="col" class="sort" data-sort="hash">XML con Hash Blockchain</th>
                                        <th scope="col" class="sort" data-sort="hash">Hash Blockchain</th>
                                        <th scope="col"></th>
                                    </tr>
                                    </thead>
                                    <tbody class="list">
                                    @foreach ($xml as $x)
                                        <tr>

                                            @if( $x->ishash == '1')
                                                <td>
                                                    <a href="{{  '/xml_files/' . 'signedhash_' . $x->filename }}" target="_blank">
                                                        <i class="ni ni-single-copy-04" aria-hidden="true" style="font-size: 24px;"></i>
                                                    </a>
                                                </td>
                                            @else
                                                <td style="color: darkred">NO</td>
                                            @endif

                                            @if( \PHPUnit\Framework\isEmpty($x->hash))
                                                <td>
                                                    <a href="https://sepolia.etherscan.io/tx/{{ $x->hash }}" target="_blank">
                                                        {{$x->hash}}
                                                    </a>
                                                </td>

                                            @else
                                                <td> </td>
                                            @endif
                                            <th>
                                                <div class="row">
                                                    <form action="{{ url('/validate/' . $x->id) }}" method="post">
                                                        @csrf
                                                        @if( $x->ishash == '1' )
                                                            <button type="submit" class="btn btn-primary btn-sm" >Verificar Integridad</button>
                                                        @endif
                                                    </form>

                                                </div>
                                            </th>
                                        </tr>
                                    @endforeach

                                    </tbody>
                                </table>

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

                                <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js"></script>
                                <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.11.0/umd/popper.min.js"></script>
                                <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>

                                <script>
                                    $(document).ready(function() {
                                        @if(session('status'))
                                        $('#statusModal').modal('show');
                                        @endif
                                    });
                                </script>
                            </div>
                        </div>
                    </div>
                </div>






            </div>
        </div>
    </div>
    @include('layouts.footers.auth')
@endsection
