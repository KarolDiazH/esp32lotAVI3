@extends('layouts.app')

@section('contenido')
<div class="container-fluid">

    <!-- Encabezado con Estado de la Banda -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <div>
            <h1 class="h3 mb-0 text-gray-800 font-weight-bold">Sector 4: Control de Postura y Banda Transportadora</h1>
            <p class="text-muted small mb-0">Monitoreo óptico de recolección de huevos, empaquetado en maples y cálculo de eficiencia de postura</p>
        </div>
        <div class="d-flex align-items-center gap-2 mt-2 mt-sm-0">
            <span class="badge badge-success px-3 py-2 text-uppercase shadow-sm">
                <i class="fas fa-conveyor-belt mr-1"></i> Sensor de Banda: Activo
            </span>
        </div>
    </div>

    <!-- 4 TARJETAS DE MÉTRICAS DE POSTURA -->
    <div class="row mb-4">

        <!-- 1. TOTAL HUEVOS RECOLECTADOS HOY -->
        <div class="col-xl-3 col-md-6 mb-3">
            <div class="card card-iot bg-white border-left-success shadow-sm p-3 h-100">
                <div class="d-flex justify-content-between align-items-center mb-1">
                    <span class="text-xs font-weight-bold text-success text-uppercase">Producción de Hoy</span>
                    <i class="fas fa-egg text-success fa-lg"></i>
                </div>
                <h2 class="font-weight-bold text-dark my-1">
                    {{ number_format($totalHoy) }} <small class="text-muted fs-6">unidades</small>
                </h2>
                <div class="small text-muted font-weight-bold">
                    <i class="fas fa-calendar-check text-success mr-1"></i> Cosecha acumulada hoy
                </div>
                <div class="small text-success mt-1 font-weight-bold">
                    <i class="fas fa-arrow-up mr-1"></i> Recolección en marcha
                </div>
            </div>
        </div>

        <!-- 2. CONVERSIÓN A MAPLES (30 HUEVOS) -->
        <div class="col-xl-3 col-md-6 mb-3">
            <div class="card card-iot bg-white border-left-primary shadow-sm p-3 h-100">
                <div class="d-flex justify-content-between align-items-center mb-1">
                    <span class="text-xs font-weight-bold text-primary text-uppercase">Empaque en Maples</span>
                    <i class="fas fa-boxes text-primary fa-lg"></i>
                </div>
                <h2 class="font-weight-bold text-dark my-1">
                    {{ $maples }} <small class="text-muted fs-6">maples</small>
                </h2>
                <div class="small text-muted font-weight-bold">
                    + {{ $sueltos }} huevos en bandeja actual
                </div>
                <div class="small text-primary mt-1 font-weight-bold">
                    Capacidad: 30 huevos / maple
                </div>
            </div>
        </div>

        <!-- 3. EFICIENCIA / TASA DE POSTURA -->
        <div class="col-xl-3 col-md-6 mb-3">
            <div class="card card-iot bg-white border-left-warning shadow-sm p-3 h-100">
                <div class="d-flex justify-content-between align-items-center mb-1">
                    <span class="text-xs font-weight-bold text-warning text-uppercase">Tasa de Postura</span>
                    <i class="fas fa-percentage text-warning fa-lg"></i>
                </div>
                <h2 class="font-weight-bold text-dark my-1">
                    {{ $eficiencia }}%
                </h2>
                <div class="progress my-2" style="height: 6px;">
                    <div class="progress-bar bg-warning" role="progressbar" style="width: {{ $eficiencia }}%;"></div>
                </div>
                <div class="small text-muted font-weight-bold">
                    Base: <strong>{{ $poblacionAves }}</strong> codornices activas
                </div>
            </div>
        </div>

        <!-- 4. ESTADO DE LA BANDA TRANSPORTADORA -->
        <div class="col-xl-3 col-md-6 mb-3">
            <div class="card card-iot bg-white border-left-info shadow-sm p-3 h-100">
                <div class="d-flex justify-content-between align-items-center mb-1">
                    <span class="text-xs font-weight-bold text-info text-uppercase">Banda Transportadora</span>
                    <i class="fas fa-cogs text-info fa-lg"></i>
                </div>
                <h3 class="font-weight-bold text-dark my-1">Detección Óptica</h3>
                <div class="small text-muted font-weight-bold">Sensor Infrarrojo / Balanza</div>
                <div class="small text-info mt-1 font-weight-bold">
                    <i class="fas fa-check-circle mr-1"></i> Telemetría sincronizada
                </div>
            </div>
        </div>

    </div>

    <!-- SECCIÓN DE GRÁFICA Y BANDEJA DE EMPAQUE -->
    <div class="row mb-4">

        <!-- GRÁFICA DE PRODUCCIÓN (APEXCHARTS) -->
        <div class="col-lg-8 mb-4">
            <div class="card card-iot bg-white shadow-sm p-4 h-100">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h6 class="font-weight-bold text-dark mb-0">
                        <i class="fas fa-chart-bar text-primary mr-2"></i> Flujo de Recolección en Banda por Horas
                    </h6>
                    <span class="badge badge-light border">Auditoría en Vivo</span>
                </div>
                <!-- Contenedor ApexChart -->
                <div id="chart-produccion-huevos" style="min-height: 280px;"></div>
            </div>
        </div>

        <!-- VISUALIZADOR DE PROGRESO DE MAPLE -->
        <div class="col-lg-4 mb-4">
            <div class="card card-iot bg-white shadow-sm p-4 h-100">
                <h6 class="font-weight-bold text-dark mb-3">
                    <i class="fas fa-th text-success mr-2"></i> Llenado del Maple Actual
                </h6>
                <p class="text-muted small mb-3">Progreso de llenado de la bandeja en la salida de la banda:</p>

                @php
                    $porcentajeBandeja = round(($sueltos / 30) * 100);
                @endphp

                <div class="text-center my-3">
                    <h1 class="display-4 font-weight-bold text-success mb-0">{{ $sueltos }}<small class="text-muted fs-6">/30</small></h1>
                    <span class="badge badge-light border text-muted px-3 py-1">Huevos colocados</span>
                </div>

                <div class="progress mb-3" style="height: 12px; border-radius: 10px;">
                    <div class="progress-bar bg-success progress-bar-striped progress-bar-animated" role="progressbar" style="width: {{ $porcentajeBandeja }}%;"></div>
                </div>

                <div class="d-flex justify-content-between small text-muted font-weight-bold">
                    <span>Llenado: {{ $porcentajeBandeja }}%</span>
                    <span>Faltan: {{ 30 - $sueltos }} u.</span>
                </div>

                <div class="alert alert-light border small text-muted mt-4 mb-0 rounded-3">
                    <i class="fas fa-info-circle text-primary mr-1"></i> Cada vez que se completan 30 unidades, se contabiliza automáticamente como <strong>1 Maple listo para bodega</strong>.
                </div>
            </div>
        </div>

    </div>

    <!-- TABLA DE HISTORIAL DE DETECCIONES DE LA BANDA -->
    <div class="card card-iot bg-white shadow-sm border-0 rounded-4">
        <div class="card-header bg-white py-3 border-0 d-flex justify-content-between align-items-center">
            <h6 class="m-0 font-weight-bold text-primary">
                <i class="fas fa-history mr-1"></i> Registro Cronológico de Detecciones en Banda
            </h6>
            <span class="badge badge-success px-3 py-1">Lecturas ESP32</span>
        </div>
        <div class="card-body p-4 pt-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="bg-light text-uppercase small text-muted">
                        <tr>
                            <th class="border-0">Hora / Fecha</th>
                            <th class="border-0">Dispositivo</th>
                            <th class="border-0">Cantidad Detectada</th>
                            <th class="border-0">Peso Registro</th>
                            <th class="border-0">Equivalencia</th>
                            <th class="border-0 text-center">Estado</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($historial as $item)
                        <tr>
                            <td class="font-weight-bold text-dark">{{ $item->created_at ? $item->created_at->format('d/m/Y H:i:s') : 'Reciente' }}</td>
                            <td><span class="badge badge-light border text-muted">{{ $item->device_id ?? 'ESP32_BANDA' }}</span></td>
                            <td class="font-weight-bold text-success">+{{ $item->cantidad }} huevos</td>
                            <td class="text-muted">{{ $item->weight_raw ?? 'N/A' }} g</td>
                            <td class="small text-muted font-weight-bold">
                                {{ floor($item->cantidad / 30) }} maples + {{ $item->cantidad % 30 }} u.
                            </td>
                            <td class="text-center text-muted small">
                                <i class="fas fa-check-circle text-success"></i> Contabilizado
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="text-center py-4 text-muted">No se han registrado eventos de recolección de huevos aún.</td>
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
    // Extraer datos cronológicos de postura para la gráfica
    @php
        $ultimosRegistros = $historial->reverse();
        $horas = $ultimosRegistros->map(fn($h) => $h->created_at ? $h->created_at->format('H:i') : '')->values();
        $cantidades = $ultimosRegistros->pluck('cantidad')->values();
    @endphp

    var horasData = {!! json_encode($cantidades->isNotEmpty() ? $horas : ['08:00', '10:00', '12:00', '14:00', '16:00', 'Ahora']) !!};
    var huevosData = {!! json_encode($cantidades->isNotEmpty() ? $cantidades : [5, 12, 25, 18, 20, 15]) !!};

    // GRÁFICA DE BARRAS DE POSTURA
    var optionsProduccion = {
        series: [{
            name: 'Huevos Recolectados',
            data: huevosData
        }],
        chart: {
            height: 280,
            type: 'bar',
            toolbar: { show: false },
            fontFamily: 'Nunito, sans-serif'
        },
        colors: ['#2d6a4f'],
        plotOptions: {
            bar: {
                borderRadius: 6,
                columnWidth: '45%',
                dataLabels: { position: 'top' }
            }
        },
        dataLabels: {
            enabled: true,
            formatter: function (val) {
                return "+" + val;
            },
            offsetY: -20,
            style: {
                fontSize: '12px',
                colors: ["#2d6a4f"]
            }
        },
        xaxis: {
            categories: horasData,
            labels: { style: { colors: '#64748b' } }
        },
        yaxis: {
            labels: { style: { colors: '#64748b' } }
        },
        grid: {
            borderColor: '#f1f5f9'
        }
    };

    var chartProduccion = new ApexCharts(document.querySelector("#chart-produccion-huevos"), optionsProduccion);
    chartProduccion.render();
</script>
@endsection