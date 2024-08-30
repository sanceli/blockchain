<!-- resources/views/data_entry/create.blade.php -->

@extends('layouts.app')

@section('content')
    <div class="container">
        <h1>Ingreso de Datos</h1>

        @if(session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        <form method="POST" action="{{ route('data_entry.store') }}">
            @csrf

            <div class="mb-3">
                <label for="fecha" class="form-label">Fecha</label>
                <input type="text" class="form-control" id="fecha" name="fecha" value="{{ old('fecha') }}" required>
                @error('fecha')
                <div class="text-danger">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label for="hora" class="form-label">Hora</label>
                <input type="text" class="form-control" id="hora" name="hora" value="{{ old('hora') }}" required>
                @error('hora')
                <div class="text-danger">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label for="nombre" class="form-label">Nombre</label>
                <input type="text" class="form-control" id="nombre" name="nombre" value="{{ old('nombre') }}" required>
                @error('nombre')
                <div class="text-danger">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label for="identificacion" class="form-label">Identificacion</label>
                <input type="text" class="form-control" id="identificacion" name="identificacion" value="{{ old('identificacion') }}" required>
                @error('identificacion')
                <div class="text-danger">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label for="nombredestinatario" class="form-label">Nombre Destinatario</label>
                <input type="text" class="form-control" id="nombredestinatario" name="nombredestinatario" value="{{ old('nombredestinatario') }}" required>
                @error('nombredestinatario')
                <div class="text-danger">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label for="recibidopor" class="form-label">Recibido Por:</label>
                <input type="text" class="form-control" id="recibidopor" name="recibidopor" value="{{ old('recibidopor') }}" required>
                @error('recibidopor')
                <div class="text-danger">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label for="email" class="form-label">Email</label>
                <input type="email" class="form-control" id="email" name="email" value="{{ old('email') }}" required>
                @error('email')
                <div class="text-danger">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label for="telefono" class="form-label">Teléfono</label>
                <input type="text" class="form-control" id="telefono" name="telefono" value="{{ old('telefono') }}" required>
                @error('telefono')
                <div class="text-danger">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label for="direccion" class="form-label">Dirección</label>
                <textarea class="form-control" id="direccion" name="direccion" rows="3" required>{{ old('direccion') }}</textarea>
                @error('direccion')
                <div class="text-danger">{{ $message }}</div>
                @enderror
            </div>

            <button type="submit" class="btn btn-primary">Guardar Datos</button>
        </form>
    </div>
@endsection
