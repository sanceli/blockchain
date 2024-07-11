@extends('layouts.app')

@section('content')

    <div class="header bg-gradient-primary pb-8 pt-5 pt-md-8">
        <div class="container-fluid">
            <div class="header-body">
                <div class="row">
                    <div class="col">
                        <div class="card">
                            <div class="card-header border-0">
                                <h3 class="mb-0">Lista de Archivos XML Subidos</h3>
                            </div>
                            <div class="table-responsive">
                                <table class="table align-items-center table-flush">
                                    <thead class="thead-light">
                                        <tr>
{{--                                            <th scope="col" class="sort" data-sort="id">Id</th>--}}
{{--                                            <th scope="col" class="sort" data-sort="path">Path</th>--}}
                                            <th scope="col" class="sort" data-sort="filename">Archivo XML</th>
                                            <th scope="col" class="sort" data-sort="sign">Firmado Digital</th>
                                            <th scope="col" class="sort" >Archivo XML Firmado</th>
                                            <th scope="col" class="sort" data-sort="hash">Publicado red Blockchain</th>
                                            <th scope="col" class="sort" data-sort="hash">XML con Hash Blockchain</th>
                                            <th scope="col" class="sort" data-sort="hash">Hash Blockchain</th>
                                            <th scope="col"></th>
                                        </tr>
                                    </thead>
                                    <tbody class="list">
                                        @foreach ($xml as $x)
                                            <tr>
{{--                                                <td>{{ $x->id }}</td>--}}
{{--                                                <td>{{ $x->path }}</td>--}}
                                                <td>
                                                    <a href="{{  '/xml_files/' . $x->filename }}" target="_blank">
                                                        <i class="ni ni-single-copy-04" aria-hidden="true" style="font-size: 24px;"></i>
                                                    </a>
                                                </td>
                                                @if( $x->issign == '1')
                                                    <td style="color: darkgreen">SI</td>
                                                    <td>
                                                        <a href="{{  '/xml_files/' . 'signed_' . $x->filename }}" target="_blank">
                                                            <i class="ni ni-single-copy-04" aria-hidden="true" style="font-size: 24px;"></i>
                                                        </a>
                                                    </td>
                                                @else
                                                    <td style="color: darkred">NO</td>
                                                    <td>

                                                    </td>
                                                @endif

                                                @if( $x->ishash == '1')
                                                    <td style="color: darkgreen">SI</td>
                                                @else
                                                    <td style="color: darkred">NO</td>
                                                @endif

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
                                                    <form action="{{ url('/digitalsign/' . $x->id) }}" method="post">
                                                        @csrf
                                                        @if( $x->issign == '1' )
                                                            <button type="submit" class="btn btn-primary btn-sm" disabled>Firmar XML</button>
                                                        @else
                                                            <button type="submit" class="btn btn-primary btn-sm">Firmar XML</button>
                                                        @endif
                                                    </form>
                                                    <form action="{{ url('/publish-hash/' . $x->id  ) }}" method="post">
                                                        @csrf
                                                        @if( $x->ishash == '1' )
                                                            <button type="submit" class="btn btn-secondary btn-sm" disabled>Publicar BlockChain</button>
                                                        @else
                                                            <button type="submit" class="btn btn-secondary btn-sm" >Publicar BlockChain</button>
                                                        @endif
                                                    </form>
                                                    </div>
                                                </th>
                                            </tr>
                                        @endforeach

                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>






            </div>
        </div>
    </div>
    @include('layouts.footers.auth')
@endsection
