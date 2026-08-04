@extends('layouts.app')

@section('contenido')
<div class="container-fluid">
    <h1 class="h3 mb-4 text-gray-800">Ajustes de Control (ESP32)</h1>

    @if(session('success'))
        <div class="alert alert-success shadow">{{ session('success') }}</div>
    @endif

    <div class="card shadow mb-4 border-left-primary">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Umbrales de Automatización</h6>
        </div>
        <div class="card-body">
            <form action="{{ route('granja.config.guardar') }}" method="POST">
                @csrf
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="font-weight-bold">Minimo Comida (Gramos)</label>
                        <input type="number" step="0.01" name="umbral_comida" class="form-control" value="{{ $config->umbral_comida_gramos ?? 2.00 }}">
                        <small class="text-muted">Si el peso es menor a este valor, se activa el motor.</small>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="font-weight-bold">Minimo Agua (Litros)</label>
                        <input type="number" step="0.01" name="umbral_agua" class="form-control" value="{{ $config->umbral_agua_litros ?? 0.50 }}">
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="font-weight-bold">Temperatura Maxima (°C)</label>
                        <input type="number" step="0.1" name="temp_max" class="form-control" value="{{ $config->temp_maxima ?? 30.0 }}">
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="font-weight-bold">Peso Promedio Huevo (Gramos)</label>
                        <input type="number" step="0.01" name="peso_huevo" class="form-control" value="{{ $config->peso_promedio_huevo ?? 4.00 }}">
                    </div>
                </div>
                <hr>
                <button type="submit" class="btn btn-primary btn-icon-split">
                    <span class="icon text-white-50"><i class="fas fa-save"></i></span>
                    <span class="text">Guardar Configuracion</span>
                </button>
            </form>
        </div>
    </div>
</div>
@endsection