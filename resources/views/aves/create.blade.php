@extends('layouts.app')

@section('contenido')
<div class="container-fluid">

    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <div>
            <h1 class="h3 mb-0 text-gray-800 font-weight-bold">Registrar Nuevo Lote de Codornices</h1>
            <p class="text-muted small mb-0">Ingreso de nueva población al aviario</p>
        </div>
        <a href="{{ route('aves.index') }}" class="btn btn-secondary rounded-pill shadow-sm px-4">
            <i class="fas fa-arrow-left mr-1"></i> Volver
        </a>
    </div>

    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card card-iot bg-white shadow-sm border-0 rounded-4 p-4 p-md-5">
                <form action="{{ route('aves.store') }}" method="POST">
                    @csrf

                    <div class="row">
                        <div class="col-md-6 form-group mb-3">
                            <label class="font-weight-bold text-dark small">Código de Lote</label>
                            <input type="text" name="codigo_lote" class="form-control @error('codigo_lote') is-invalid @enderror" value="{{ old('codigo_lote', 'LOTE-'.rand(10,99)) }}" placeholder="Ej: LOTE-01" required>
                            @error('codigo_lote') <small class="text-danger">{{ $message }}</small> @enderror
                        </div>

                        <div class="col-md-6 form-group mb-3">
                            <label class="font-weight-bold text-dark small">Raza / Especie</label>
                            <input type="text" name="raza" class="form-control" value="{{ old('raza', 'Codorniz Japónica (Coturnix coturnix)') }}" required>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-4 form-group mb-3">
                            <label class="font-weight-bold text-dark small">Cantidad de Aves</label>
                            <input type="number" name="cantidad_inicial" class="form-control" value="{{ old('cantidad_inicial', 100) }}" min="1" required>
                        </div>

                        <div class="col-md-4 form-group mb-3">
                            <label class="font-weight-bold text-dark small">Edad (Semanas)</label>
                            <input type="number" name="edad_semanas" class="form-control" value="{{ old('edad_semanas', 6) }}" min="1" required>
                        </div>

                        <div class="col-md-4 form-group mb-3">
                            <label class="font-weight-bold text-dark small">Fecha de Ingreso</label>
                            <input type="date" name="fecha_ingreso" class="form-control" value="{{ date('Y-m-d') }}" required>
                        </div>
                    </div>

                    <div class="form-group mb-3">
                        <label class="font-weight-bold text-dark small">Estado Productivo</label>
                        <select name="estado" class="form-control" required>
                            <option value="En Postura" selected>En Postura (Produciendo huevos)</option>
                            <option value="En Crecimiento">En Crecimiento</option>
                            <option value="Finalizado">Finalizado / Retirado</option>
                        </select>
                    </div>

                    <div class="form-group mb-4">
                        <label class="font-weight-bold text-dark small">Observaciones / Ubicación en Galpón</label>
                        <textarea name="observaciones" class="form-control" rows="2" placeholder="Ej: Jaulas fila superior, buena adaptación"></textarea>
                    </div>

                    <button type="submit" class="btn btn-primary btn-block rounded-pill py-2 font-weight-bold shadow-sm">
                        <i class="fas fa-save mr-1"></i> Guardar Lote
                    </button>
                </form>
            </div>
        </div>
    </div>

</div>
@endsection