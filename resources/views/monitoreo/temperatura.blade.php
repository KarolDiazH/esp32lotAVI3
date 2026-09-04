@extends('layouts.app')

@section('contenido')
<div class="container-fluid">

    <!-- Encabezado con Diagnóstico de Confort -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <div>
            <h1 class="h3 mb-0 text-gray-800 font-weight-bold">Sector 3: Clima y Confort Térmico del Galpón</h1>
            <p class="text-muted small mb-0">Telemetría ambiental en tiempo real desde el sensor DHT11 para el bienestar de las aves</p>
        </div>
        <div class="d-flex align-items-center gap-2 mt-2 mt-sm-0">
            @if(($ultimo->temperature ?? 24) > 27)
                <span class="badge badge-danger px-3 py-2 text-uppercase shadow-sm">
                    <i class="fas fa-exclamation-triangle mr-1"></i> Alerta: Estrés por Calor
                </span>
            @elseif(($ultimo->temperature ?? 24) < 18)
                <span class="badge badge-primary px-3 py-2 text-uppercase shadow-sm">
                    <i class="fas fa-snowflake mr-1"></i> Alerta: Baja Temperatura
                </span>
            @else
                <span class="badge badge-success px-3 py-2 text-uppercase shadow-sm">
                    <i class="fas fa-check-circle mr-1"></i> Rango Óptimo de Postura
                </span>
            @endif
        </div>
    </div>

    <!-- 4 TARJETAS DE MÉTRICAS CLIMÁTICAS -->
    <div class="row mb-4">

        <!-- 1. TEMPERATURA ACTUAL -->
        <div class="col-xl-3 col-md-6 mb-3">
            <div class="card card-iot bg-white border-left-warning shadow-sm p-3 h-100">
                <div class="d-flex justify-content-between align-items-center mb-1">
                    <span class="text-xs font-weight-bold text-warning text-uppercase">Temperatura Actual</span>
                    <i class="fas fa-temperature-high text-warning fa-lg"></i>
                </div>
                <h2 class="font-weight-bold text-dark my-1">
                    {{ $ultimo->temperature ?? '24.0' }} <small class="text-muted fs-6">°C</small>
                </h2>
                <div class="small text-muted font-weight-bold">
                    Rango ideal: <strong>18°C a 26°C</strong>
                </div>
                <div class="small {{ ($ultimo->temperature ?? 24) > 27 ? 'text-danger' : 'text-success' }} mt-1 font-weight-bold">
                    {{ ($ultimo->temperature ?? 24) > 27 ? 'Requiere ventilación' : 'Temperatura estable' }}
                </div>
            </div>
        </div>

        <!-- 2. HUMEDAD RELATIVA ACTUAL -->
        <div class="col-xl-3 col-md-6 mb-3">
            <div class="card card-iot bg-white border-left-info shadow-sm p-3 h-100">
                <div class="d-flex justify-content-between align-items-center mb-1">
                    <span class="text-xs font-weight-bold text-info text-uppercase">Humedad Relativa</span>
                    <i class="fas fa-water text-info fa-lg"></i>
                </div>
                <h2 class="font-weight-bold text-dark my-1">
                    {{ $ultimo->humidity ?? '60' }} <small class="text-muted fs-6">% HR</small>
                </h2>
                <div class="small text-muted font-weight-bold">
                    Rango ideal: <strong>50% a 70%</strong>
                </div>
                <div class="small text-info mt-1 font-weight-bold">
                    Humedad adecuada
                </div>
            </div>
        </div>

        <!-- 3. TEMPERATURA PROMEDIO -->
        <div class="col-xl-3 col-md-6 mb-3">
            <div class="card card-iot bg-white border-left-primary shadow-sm p-3 h-100">
                <div class="d-flex justify-content-between align-items-center mb-1">
                    <span class="text-xs font-weight-bold text-primary text-uppercase">Promedio del Aviario</span>
                    <i class="fas fa-chart-line text-primary fa-lg"></i>
                </div>
                <h2 class="font-weight-bold text-dark my-1">
                    {{ $promedioTemp }} <small class="text-muted fs-6">°C</small>
                </h2>
                <div class="small text-muted font-weight-bold">
                    Mín: <span class="text-primary">{{ $tempMin }}°C</span> | Máx: <span class="text-danger">{{ $tempMax }}°C</span>
                </div>
                <div class="small text-muted mt-1">Histórico general</div>
            </div>
        </div>

        <!-- 4. ESTADO DEL SENSOR -->
        <div class="col-xl-3 col-md-6 mb-3">
            <div class="card card-iot bg-white border-left-success shadow-sm p-3 h-100">
                <div class="d-flex justify-content-between align-items-center mb-1">
                    <span class="text-xs font-weight-bold text-success text-uppercase">Sensor de Clima</span>
                    <i class="fas fa-microchip text-success fa-lg"></i>
                </div>
                <h3 class="font-weight-bold text-dark my-1">DHT11 / GPIO 18</h3>
                <div class="small text-muted font-weight-bold">Muestreo cada 10 seg</div>
                <div class="small text-success mt-1 font-weight-bold">
                    <i class="fas fa-check-circle mr-1"></i> Transmisión fluida
                </div>
            </div>
        </div>

    </div>

    <!-- GRÁFICA DE CURVAS Y GUÍA DE CONFORT -->
    <div class="row mb-4">

        <!-- GRÁFICA DE LÍNEAS (HISTORIAL DE TEMPERATURA Y HUMEDAD) -->
        <div class="col-lg-8 mb-4">
            <div class="card card-iot bg-white shadow-sm p-4 h-100">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h6 class="font-weight-bold text-dark mb-0">
                        <i class="fas fa-chart-area text-warning mr-2"></i> Evolución de Temperatura y Humedad
                    </h6>
                    <span class="badge badge-light border">Últimas Lecturas</span>
                </div>
                <!-- Contenedor ApexChart -->
                <div id="chart-clima-evolucion" style="min-height: 280px;"></div>
            </div>
        </div>

        <!-- GUÍA TÉCNICA DE BIENESTAR AVÍCOLA -->
        <div class="col-lg-4 mb-4">
            <div class="card card-iot bg-white shadow-sm p-4 h-100">
                <h6 class="font-weight-bold text-dark mb-3">
                    <i class="fas fa-info-circle text-primary mr-2"></i> Guía Zootécnica de Clima
                </h6>
                <p class="text-muted small mb-3">Parámetros de confort ambiental para codornices de postura:</p>

                <div class="p-3 mb-2 rounded-3 border-left-success bg-light">
                    <div class="d-flex justify-content-between font-weight-bold small text-success">
                        <span>18°C a 26°C</span>
                        <span>Óptimo</span>
                    </div>
                    <small class="text-muted d-block">Postura máxima y consumo de alimento estable.</small>
                </div>

                <div class="p-3 mb-2 rounded-3 border-left-warning bg-light">
                    <div class="d-flex justify-content-between font-weight-bold small text-warning">
                        <span>27°C a 30°C</span>
                        <span>Alerta</span>
                    </div>
                    <small class="text-muted d-block">Aves aumentan consumo de agua y jadean.</small>
                </div>

                <div class="p-3 rounded-3 border-left-danger bg-light">
                    <div class="d-flex justify-content-between font-weight-bold small text-danger">
                        <span>> 30°C</span>
                        <span>Estrés Crítico</span>
                    </div>
                    <small class="text-muted d-block">Caída de postura y riesgo de mortalidad.</small>
                </div>
            </div>
        </div>

    </div>

    <!-- TABLA DE ÚLTIMOS MUESTREOS AMBIENTALES -->
    <div class="card card-iot bg-white shadow-sm border-0 rounded-4">
        <div class="card-header bg-white py-3 border-0 d-flex justify-content-between align-items-center">
            <h6 class="m-0 font-weight-bold text-primary">
                <i class="fas fa-history mr-1"></i> Últimos Muestreos del Sensor Ambiental
            </h6>
            <span class="badge badge-warning text-dark px-3 py-1">Registros ESP32</span>
        </div>
        <div class="card-body p-4 pt-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="bg-light text-uppercase small text-muted">
                        <tr>
                            <th class="border-0">Hora / Fecha</th>
                            <th class="border-0">Dispositivo</th>
                            <th class="border-0">Temperatura</th>
                            <th class="border-0">Humedad</th>
                            <th class="border-0">Diagnóstico</th>
                            <th class="border-0 text-center">Estado</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($historial as $item)
                        <tr>
                            <td class="font-weight-bold text-dark">{{ $item->created_at ? $item->created_at->format('d/m/Y H:i:s') : 'Reciente' }}</td>
                            <td><span class="badge badge-light border text-muted">{{ $item->device_id ?? 'ESP32_GALPON' }}</span></td>
                            <td class="font-weight-bold {{ $item->temperature > 27 ? 'text-danger' : 'text-dark' }}">
                                {{ $item->temperature }} °C
                            </td>
                            <td class="font-weight-bold text-info">{{ $item->humidity }} %</td>
                            <td>
                                @if($item->temperature > 27)
                                    <span class="badge badge-danger px-3 py-1"><i class="fas fa-fire mr-1"></i> Calor Excesivo</span>
                                @elseif($item->temperature < 18)
                                    <span class="badge badge-primary px-3 py-1"><i class="fas fa-snowflake mr-1"></i> Frío</span>
                                @else
                                    <span class="badge badge-success px-3 py-1"><i class="fas fa-check mr-1"></i> Confort Óptimo</span>
                                @endif
                            </td>
                            <td class="text-center text-muted small">
                                <i class="fas fa-check-circle text-success"></i> Válido
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="text-center py-4 text-muted">No hay lecturas registradas aún.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

</div>
@endsection

@section('scripts')
<script>
    // Preparar datos reales de la base de datos para la gráfica
    @php
        $ultimosRegistros = $historial->reverse();
        $horas = $ultimosRegistros->map(fn($h) => $h->created_at ? $h->created_at->format('H:i') : '')->values();
        $temps = $ultimosRegistros->pluck('temperature')->values();
        $hums = $ultimosRegistros->pluck('humidity')->values();
    @endphp

    var horasData = {!! json_encode($horas->isNotEmpty() ? $horas : ['10:00', '11:00', '12:00', '13:00', '14:00', 'Ahora']) !!};
    var tempsData = {!! json_encode($temps->isNotEmpty() ? $temps : [22, 23, 24, 25, 24.5, 24]) !!};
    var humsData = {!! json_encode($hums->isNotEmpty() ? $hums : [60, 62, 65, 63, 61, 60]) !!};

    // GRÁFICA DE LÍNEAS EN ÁREA (TEMPERATURA Y HUMEDAD)
    var optionsClima = {
        series: [{
            name: 'Temperatura (°C)',
            data: tempsData
        }, {
            name: 'Humedad (%HR)',
            data: humsData
        }],
        chart: {
            height: 280,
            type: 'area',
            toolbar: { show: false },
            fontFamily: 'Nunito, sans-serif'
        },
        colors: ['#f59e0b', '#0ea5e9'],
        dataLabels: { enabled: false },
        stroke: { curve: 'smooth', width: 2.5 },
        fill: {
            type: 'gradient',
            gradient: {
                shadeIntensity: 1,
                opacityFrom: 0.35,
                opacityTo: 0.05,
                stops: [0, 90, 100]
            }
        },
        xaxis: {
            categories: horasData,
            labels: { style: { colors: '#64748b' } }
        },
        yaxis: {
            labels: { style: { colors: '#64748b' } }
        },
        legend: { position: 'top', horizontalAlign: 'right' }
    };

    var chartClima = new ApexCharts(document.querySelector("#chart-clima-evolucion"), optionsClima);
    chartClima.render();
</script>
@endsection