@extends('layouts.app')

@section('contenido')
<div class="container-fluid">

    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <div>
            <h1 class="h3 mb-0 text-gray-800 font-weight-bold">Control de Población y Lotes de Aves</h1>
            <p class="text-muted small mb-0">Gestión de codornices, edades, estados y registros del galpón</p>
        </div>
        <a href="{{ route('aves.create') }}" class="btn btn-primary rounded-pill shadow-sm px-4">
            <i class="fas fa-plus-circle mr-1"></i> Registrar Nuevo Lote
        </a>
    </div>

    <!-- TARJETAS DE RESUMEN SUPERIOR -->
    <div class="row mb-4">
        <div class="col-md-6 col-xl-4 mb-3">
            <div class="card card-iot bg-white border-left-success p-3 shadow-sm">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <span class="text-xs font-weight-bold text-success text-uppercase">Población Total Activa</span>
                        <h2 class="font-weight-bold text-dark mb-0">{{ number_format($totalAvesActivas) }} <small class="text-muted fs-6">Aves</small></h2>
                    </div>
                    <i class="fas fa-kiwi-bird fa-2x text-success"></i>
                </div>
            </div>
        </div>
        <div class="col-md-6 col-xl-4 mb-3">
            <div class="card card-iot bg-white border-left-primary p-3 shadow-sm">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <span class="text-xs font-weight-bold text-primary text-uppercase">Lotes Registrados</span>
                        <h2 class="font-weight-bold text-dark mb-0">{{ $totalLotes }} <small class="text-muted fs-6">Grupos</small></h2>
                    </div>
                    <i class="fas fa-layer-group fa-2x text-primary"></i>
                </div>
            </div>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show shadow-sm border-0" role="alert">
            <i class="fas fa-check-circle mr-2"></i> {{ session('success') }}
            <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        </div>
    @endif

    <!-- TABLA DE LOTES -->
    <div class="card card-iot bg-white shadow-sm border-0 rounded-4">
        <div class="card-body p-4">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="bg-light text-uppercase small text-muted">
                        <tr>
                            <th class="border-0">Código Lote</th>
                            <th class="border-0">Raza / Especie</th>
                            <th class="border-0">Cantidad Actual</th>
                            <th class="border-0">Edad</th>
                            <th class="border-0">Fecha Ingreso</th>
                            <th class="border-0">Estado</th>
                            <th class="border-0 text-center">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($lotes as $lote)
                        <tr>
                            <td class="font-weight-bold text-primary">{{ $lote->codigo_lote }}</td>
                            <td>{{ $lote->raza }}</td>
                            <td class="font-weight-bold text-dark">
                                {{ $lote->cantidad_actual }} / <small class="text-muted">{{ $lote->cantidad_inicial }} inicial</small>
                            </td>
                            <td>{{ $lote->edad_semanas }} semanas</td>
                            <td class="text-muted small">{{ \Carbon\Carbon::parse($lote->fecha_ingreso)->format('d/m/Y') }}</td>
                            <td>
                                @if($lote->estado == 'En Postura')
                                    <span class="badge badge-success px-3 py-1">En Postura</span>
                                @elseif($lote->estado == 'En Crecimiento')
                                    <span class="badge badge-warning px-3 py-1 text-dark">En Crecimiento</span>
                                @else
                                    <span class="badge badge-secondary px-3 py-1">Finalizado</span>
                                @endif
                            </td>
                            <td class="text-center">
                                <a href="{{ route('aves.edit', $lote->id) }}" class="btn btn-outline-primary btn-sm rounded-circle mr-1" title="Editar">
                                    <i class="fas fa-pencil-alt"></i>
                                </a>
                                <form action="{{ route('aves.destroy', $lote->id) }}" method="POST" class="d-inline" onsubmit="return confirm('¿Eliminar este lote?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-outline-danger btn-sm rounded-circle" title="Eliminar">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="text-center py-4 text-muted">No hay lotes de aves registrados.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

</div>
@endsection