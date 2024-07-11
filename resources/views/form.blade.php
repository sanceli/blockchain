@extends('layouts.app')

@section('content')

    <div class="header bg-gradient-primary pb-8 pt-5 pt-md-8">
        <div class="container-fluid">
            <div class="header-body">
                <form method="post" action="{{ route('formulario') }}">
                    @csrf

                </form>





            </div>
        </div>
    </div>
    @include('layouts.footers.auth')
@endsection
