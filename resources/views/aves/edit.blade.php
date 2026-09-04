@extends('layouts.app')

@section('contenido')
<div class="container-fluid">

    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <div>
            <h1 class="h3 mb-0 text-gray-800 font-weight-bold">Editar Lote: {{ $lote->codigo_lote }}</h1>
            <p class="text-muted small mb-0">Actualizar población actual o estado del grupo</p>
        </div>
        <a href="{{ route('aves.index') }}" class="btn btn-secondary rounded-pill shadow-sm px-4">
            <i class="fas fa-arrow-left mr-1"></i> Volver
        </a>
    </div>

    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card card-iot bg-white shadow-sm border-0 rounded-4 p-4 p-md-5">
                <form action="{{ route('aves.update', $lote->id) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="row">
                        <div class="col-md-6 form-group mb-3">
                            <label class="font-weight-bold text-dark small">Código de Lote</label>
                            <input type="text" name="codigo_lote" class="form-control" value="{{ old('codigo_lote', $lote->codigo_lote) }}" required>
                        </div>

                        <div class="col-md-6 form-group mb-3">
                            <label class="font-weight-bold text-dark small">Raza / Especie</label>
                            <input type="text" name="raza" class="form-control" value="{{ old('raza', $lote->raza) }}" required>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 form-group mb-3">
                            <label class="font-weight-bold text-dark small">Cantidad Actual de Aves Vivas</label>
                            <input type="number" name="cantidad_actual" class="form-control" value="{{ old('cantidad_actual', $lote->cantidad_actual) }}" min="0" required>
                            <small class="text-muted">Inicialmente eran {{ $lote->cantidad_inicial }} aves.</small>
                        </div>

                        <div class="col-md-6 form-group mb-3">
                            <label class="font-weight-bold text-dark small">Edad (Semanas)</label>
                            <input type="number" name="edad_semanas" class="form-control" value="{{ old('edad_semanas', $lote->edad_semanas) }}" min="1" required>
                        </div>
                    </div>

                    <div class="form-group mb-3">
                        <label class="font-weight-bold text-dark small">Estado Productivo</label>
                        <select name="estado" class="form-control" required>
                            <option value="En Postura" {{ $lote->estado == 'En Postura' ? 'selected' : '' }}>En Postura</option>
                            <option value="En Crecimiento" {{ $lote->estado == 'En Crecimiento' ? 'selected' : '' }}>En Crecimiento</option>
                            <option value="Finalizado" {{ $lote->estado == 'Finalizado' ? 'selected' : '' }}>Finalizado / Retirado</option>
                        </select>
                    </div>

                    <div class="form-group mb-4">
                        <label class="font-weight-bold text-dark small">Observaciones</label>
                        <textarea name="observaciones" class="form-control" rows="2">{{ old('observaciones', $lote->observaciones) }}</textarea>
                    </div>

                    <button type="submit" class="btn btn-primary btn-block rounded-pill py-2 font-weight-bold shadow-sm">
                        <i class="fas fa-sync-alt mr-1"></i> Actualizar Lote
                    </button>
                </form>
            </div>
        </div>
    </div>

</div>
@endsection