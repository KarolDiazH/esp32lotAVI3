@extends('layouts.app')

@section('contenido')
<div class="container-fluid">

    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <div>
            <h1 class="h3 mb-0 text-gray-800 font-weight-bold">Historial y Búsqueda de Telemetría</h1>
            <p class="text-muted small mb-0">Auditoría de todos los registros enviados por el sensor DHT11</p>
        </div>

        <!-- Formulario con Filtros Múltiples -->
        <form action="{{ route('reportes.historial') }}" method="GET" class="d-flex flex-wrap align-items-center gap-2 mt-2 mt-sm-0">
            <input type="date" name="fecha_inicio" value="{{ $fechaInicio }}" class="form-control form-control-sm rounded-pill shadow-sm" style="width: auto;">
            <span class="text-muted small mx-1">a</span>
            <input type="date" name="fecha_fin" value="{{ $fechaFin }}" class="form-control form-control-sm rounded-pill shadow-sm" style="width: auto;">
            
            <select name="estado" class="form-control form-control-sm rounded-pill shadow-sm ml-2" style="width: auto;">
                <option value="todos" {{ $filtroEstado == 'todos' ? 'selected' : '' }}>Todos los estados</option>
                <option value="optimo" {{ $filtroEstado == 'optimo' ? 'selected' : '' }}>Solo Rango Óptimo</option>
                <option value="calor" {{ $filtroEstado == 'calor' ? 'selected' : '' }}>Solo Estrés por Calor (>27°C)</option>
                <option value="frio" {{ $filtroEstado == 'frio' ? 'selected' : '' }}>Solo Frío (<18°C)</option>
            </select>

            <button type="submit" class="btn btn-sm btn-primary rounded-pill shadow-sm px-3 ml-2">
                <i class="fas fa-search mr-1"></i> Buscar
            </button>
        </form>
    </div>

    <!-- TABLA DE HISTORIAL DE LA BD -->
    <div class="card card-iot bg-white shadow-sm border-0 rounded-4">
        <div class="card-body p-4">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="bg-light text-uppercase small text-muted">
                        <tr>
                            <th class="border-0">ID Registro</th>
                            <th class="border-0">Fecha y Hora</th>
                            <th class="border-0">Dispositivo</th>
                            <th class="border-0">Temperatura</th>
                            <th class="border-0">Humedad</th>
                            <th class="border-0">Estado Térmico</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($historialClima as $item)
                        <tr>
                            <td class="font-weight-bold text-muted">#{{ $item->id }}</td>
                            <td class="font-weight-bold text-dark">{{ $item->created_at->format('d/m/Y H:i:s') }}</td>
                            <td><span class="badge badge-light border text-muted">{{ $item->device_id }}</span></td>
                            <td class="font-weight-bold {{ $item->temperature > 27 ? 'text-danger' : 'text-dark' }}">
                                {{ $item->temperature }} °C
                            </td>
                            <td class="font-weight-bold text-info">{{ $item->humidity }} %</td>
                            <td>
                                @if($item->temperature > 27)
                                    <span class="badge badge-danger px-3 py-1">Estrés por Calor</span>
                                @elseif($item->temperature < 18)
                                    <span class="badge badge-primary px-3 py-1">Baja Temperatura</span>
                                @else
                                    <span class="badge badge-success px-3 py-1">Confort Óptimo</span>
                                @endif
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="text-center py-4 text-muted">No se encontraron lecturas para los filtros seleccionados.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Paginación -->
            <div class="d-flex justify-content-end mt-4">
               {{ $historialClima->appends(request()->query())->links('pagination::bootstrap-4') }}
            </div>
        </div>
    </div>

</div>
@endsection