@extends('layouts.app')

@section('contenido')
<div class="container-fluid">

    <!-- Encabezado con Filtro de Fecha y Botón Imprimir -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <div>
            <h1 class="h3 mb-0 text-gray-800 font-weight-bold">Reporte Diario de la Granja</h1>
            <p class="text-muted small mb-0">Consolidado de telemetría ambiental, recolección y suministros</p>
        </div>

        <div class="d-flex align-items-center gap-2 mt-2 mt-sm-0">
            <!-- Formulario Filtro de Fecha -->
            <form action="{{ route('reportes.diario') }}" method="GET" class="d-flex align-items-center mr-2">
                <input type="date" name="fecha" value="{{ $fecha }}" class="form-control form-control-sm rounded-pill shadow-sm" onchange="this.form.submit()">
            </form>

            <button onclick="window.print()" class="btn btn-sm btn-primary rounded-pill shadow-sm px-3">
                <i class="fas fa-print fa-sm text-white-50 mr-1"></i> Imprimir / Exportar PDF
            </button>
        </div>
    </div>

    <!-- TARJETAS DE INDICADORES REALES -->
    <div class="row mb-4">

        <!-- 1. CLIMA PROMEDIO REAL DE LA BD -->
        <div class="col-xl-3 col-md-6 mb-3">
            <div class="card card-iot bg-white border-left-warning shadow-sm p-3 h-100">
                <div class="d-flex justify-content-between align-items-center mb-1">
                    <span class="text-xs font-weight-bold text-warning text-uppercase">Clima Promedio (DHT11)</span>
                    <i class="fas fa-temperature-high fa-lg text-warning"></i>
                </div>
                <h2 class="font-weight-bold text-dark my-1">{{ $tempPromedio }}°<small class="fs-6">C</small> <span class="text-gray-400">|</span> {{ $humPromedio }}% <small class="fs-6">HR</small></h2>
                <div class="small text-muted font-weight-bold">
                    Mín: <span class="text-primary">{{ $tempMin }}°C</span> | Máx: <span class="text-danger">{{ $tempMax }}°C</span>
                </div>
                <div class="small text-muted mt-1">
                    <i class="fas fa-database mr-1"></i> {{ $totalLecturas }} lecturas registradas
                </div>
            </div>
        </div>

        <!-- 2. HUEVOS Y MAPLES -->
        <div class="col-xl-3 col-md-6 mb-3">
            <div class="card card-iot bg-white border-left-success shadow-sm p-3 h-100">
                <div class="d-flex justify-content-between align-items-center mb-1">
                    <span class="text-xs font-weight-bold text-success text-uppercase">Postura de Huevos</span>
                    <i class="fas fa-egg fa-lg text-success"></i>
                </div>
                <h2 class="font-weight-bold text-dark my-1">{{ number_format($totalHuevos) }} <small class="text-muted fs-6">huevos</small></h2>
                <div class="small text-muted font-weight-bold">
                    <i class="fas fa-box text-success mr-1"></i> {{ $maples }} Maples <small>({{ $huevosSueltos }} sueltos)</small>
                </div>
                <div class="small text-success mt-1">
                    Eficiencia: <strong>{{ $eficienciaPostura }}%</strong>
                </div>
            </div>
        </div>

        <!-- 3. ALIMENTO -->
        <div class="col-xl-3 col-md-6 mb-3">
            <div class="card card-iot bg-white border-left-primary shadow-sm p-3 h-100">
                <div class="d-flex justify-content-between align-items-center mb-1">
                    <span class="text-xs font-weight-bold text-primary text-uppercase">Alimento Dispensado</span>
                    <i class="fas fa-seedling fa-lg text-primary"></i>
                </div>
                <h2 class="font-weight-bold text-dark my-1">{{ number_format($gramosConsumidos) }} <small class="text-muted fs-6">g</small></h2>
                <div class="small text-muted font-weight-bold">
                    Equivale a: <strong>{{ round($gramosConsumidos / 1000, 2) }} kg</strong>
                </div>
                <div class="small text-muted mt-1">Silo en monitoreo</div>
            </div>
        </div>

        <!-- 4. AGUA -->
        <div class="col-xl-3 col-md-6 mb-3">
            <div class="card card-iot bg-white border-left-info shadow-sm p-3 h-100">
                <div class="d-flex justify-content-between align-items-center mb-1">
                    <span class="text-xs font-weight-bold text-info text-uppercase">Agua Dispensada</span>
                    <i class="fas fa-tint fa-lg text-info"></i>
                </div>
                <h2 class="font-weight-bold text-dark my-1">{{ number_format($litrosConsumidos, 2) }} <small class="text-muted fs-6">L</small></h2>
                <div class="small text-muted font-weight-bold">Bomba de agua operativa</div>
                <div class="small text-info mt-1">Hidratación continua</div>
            </div>
        </div>

    </div>

    <!-- TABLA CON LECTURAS REALES DEL ESP32 -->
    <div class="card card-iot bg-white shadow-sm border-0 rounded-4">
        <div class="card-header bg-white py-3 border-0 d-flex justify-content-between align-items-center">
            <h6 class="m-0 font-weight-bold text-primary">
                <i class="fas fa-history mr-1"></i> Muestreo de Telemetría Ambiental del Día ({{ \Carbon\Carbon::parse($fecha)->format('d/m/Y') }})
            </h6>
            <span class="badge badge-success px-3 py-1">Registros ESP32</span>
        </div>
        <div class="card-body p-4 pt-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="bg-light text-uppercase small text-muted">
                        <tr>
                            <th class="border-0">Hora</th>
                            <th class="border-0">Dispositivo</th>
                            <th class="border-0">Temperatura</th>
                            <th class="border-0">Humedad</th>
                            <th class="border-0">Diagnóstico Térmico</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($lecturasClima as $item)
                        <tr>
                            <td class="font-weight-bold text-dark">{{ $item->created_at->format('H:i:s') }}</td>
                            <td><span class="badge badge-light border text-muted">{{ $item->device_id }}</span></td>
                            <td class="font-weight-bold {{ $item->temperature > 27 ? 'text-danger' : 'text-dark' }}">
                                {{ $item->temperature }} °C
                            </td>
                            <td class="font-weight-bold text-info">{{ $item->humidity }} %</td>
                            <td>
                                @if($item->temperature > 27)
                                    <span class="badge badge-danger px-3 py-1"><i class="fas fa-exclamation-triangle mr-1"></i> Estrés por Calor</span>
                                @elseif($item->temperature < 18)
                                    <span class="badge badge-primary px-3 py-1">Baja Temperatura</span>
                                @else
                                    <span class="badge badge-success px-3 py-1"><i class="fas fa-check-circle mr-1"></i> Confort Térmico</span>
                                @endif
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="text-center py-4 text-muted">No hay lecturas registradas en la fecha seleccionada.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

</div>

<!-- FORMATO DE IMPRESIÓN PARA PDF -->
<style>
@media print {
    .sidebar, .topbar, .btn, form, footer { display: none !important; }
    #content-wrapper { background: #fff !important; margin: 0 !important; padding: 0 !important; }
    .card { box-shadow: none !important; border: 1px solid #ddd !important; }
}
</style>
@endsection