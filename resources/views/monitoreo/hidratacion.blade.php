@extends('layouts.app')

@section('contenido')
<div class="container-fluid">

    <!-- Encabezado con Estado -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <div>
            <h1 class="h3 mb-0 text-gray-800 font-weight-bold">Sector 2: Control de Hidratación y Tanque de Agua</h1>
            <p class="text-muted small mb-0">Monitoreo de volumen de agua, abastecimiento continuo y control del relé de la bomba</p>
        </div>
        <div class="d-flex align-items-center gap-2 mt-2 mt-sm-0">
            <span class="badge badge-info px-3 py-2 text-uppercase shadow-sm text-white">
                <i class="fas fa-faucet mr-1"></i> Bomba Relé: En Línea
            </span>
        </div>
    </div>

    <!-- 4 TARJETAS DE MÉTRICAS DEL AGUA -->
    <div class="row mb-4">

        <!-- 1. NIVEL ACTUAL EN EL TANQUE -->
        <div class="col-xl-3 col-md-6 mb-3">
            <div class="card card-iot bg-white border-left-info shadow-sm p-3 h-100">
                <div class="d-flex justify-content-between align-items-center mb-1">
                    <span class="text-xs font-weight-bold text-info text-uppercase">Volumen en Tanque</span>
                    <i class="fas fa-tint text-info fa-lg"></i>
                </div>
                <h2 class="font-weight-bold text-dark my-1">
                    <span id="txt-nivel">{{ number_format($nivel, 2) }}</span> <small class="text-muted fs-6">L</small>
                </h2>
                <div class="progress my-2" style="height: 7px; border-radius: 10px;">
                    <div id="barra-agua" class="progress-bar bg-info" role="progressbar" style="width: {{ $porcentaje }}%;"></div>
                </div>
                <div class="d-flex justify-content-between small text-muted font-weight-bold">
                    <span>Nivel: <strong id="txt-porcentaje">{{ $porcentaje }}%</strong></span>
                    <span class="{{ $porcentaje > 25 ? 'text-info' : 'text-danger' }}">
                        {{ $porcentaje > 25 ? 'Abastecido' : 'Alerta: Tanque Bajo' }}
                    </span>
                </div>
            </div>
        </div>

        <!-- 2. CONSUMO DE AGUA HOY -->
        <div class="col-xl-3 col-md-6 mb-3">
            <div class="card card-iot bg-white border-left-primary shadow-sm p-3 h-100">
                <div class="d-flex justify-content-between align-items-center mb-1">
                    <span class="text-xs font-weight-bold text-primary text-uppercase">Suministro Hoy</span>
                    <i class="fas fa-hand-holding-water text-primary fa-lg"></i>
                </div>
                <h2 class="font-weight-bold text-dark my-1">
                    <span id="txt-consumo">{{ number_format($consumoHoyLitros, 2) }}</span> <small class="text-muted fs-6">Litros</small>
                </h2>
                <div class="small text-muted font-weight-bold">
                    <i class="fas fa-sync-alt text-primary mr-1"></i> {{ $despachosHoy }} Activaciones de bomba
                </div>
                <div class="small text-primary mt-1 font-weight-bold">
                    Dosis: 250 ml / ciclo
                </div>
            </div>
        </div>

        <!-- 3. POBLACIÓN EN HIDRATACIÓN -->
        <div class="col-xl-3 col-md-6 mb-3">
            <div class="card card-iot bg-white border-left-success shadow-sm p-3 h-100">
                <div class="d-flex justify-content-between align-items-center mb-1">
                    <span class="text-xs font-weight-bold text-success text-uppercase">Población en Galpón</span>
                    <i class="fas fa-kiwi-bird text-success fa-lg"></i>
                </div>
                <h2 class="font-weight-bold text-dark my-1">{{ number_format($poblacionAves) }} <small class="text-muted fs-6">aves</small></h2>
                <div class="small text-muted font-weight-bold">Bebederos automáticos</div>
                <div class="small text-success mt-1 font-weight-bold">
                    <i class="fas fa-check-circle mr-1"></i> Hidratación continua
                </div>
            </div>
        </div>

        <!-- 4. TIEMPO DE BOMBEO -->
        <div class="col-xl-3 col-md-6 mb-3">
            <div class="card card-iot bg-white border-left-warning shadow-sm p-3 h-100">
                <div class="d-flex justify-content-between align-items-center mb-1">
                    <span class="text-xs font-weight-bold text-warning text-uppercase">Tiempo de Bombeo</span>
                    <i class="fas fa-stopwatch text-warning fa-lg"></i>
                </div>
                <h3 class="font-weight-bold text-dark my-1">3.0 <small class="text-muted fs-6">segundos</small></h3>
                <div class="small text-muted font-weight-bold">Relé: GPIO 22</div>
                <div class="small text-muted mt-1">Cierre automático anti-desborde</div>
            </div>
        </div>

    </div>

    <!-- ACCIONES MANUALES Y GRÁFICA -->
    <div class="row mb-4">

        <!-- CONTROL MANUAL DE BOMBA (AJAX) -->
        <div class="col-lg-4 mb-4">
            <div class="card card-iot bg-white shadow-sm p-4 h-100">
                <h6 class="font-weight-bold text-dark mb-3">
                    <i class="fas fa-hand-pointer text-info mr-2"></i> Accionamiento Manual de Bomba
                </h6>
                <p class="text-muted small mb-4">Envía una orden directa al ESP32 para activar el relé y bombear agua hacia los bebederos del galpón.</p>

                <div class="d-grid gap-3">
                    <!-- Botón Despachar Agua -->
                    <button type="button" onclick="despacharAgua()" id="btn-despachar-agua" class="btn btn-info btn-block py-3 rounded-pill shadow-sm font-weight-bold text-white mb-3">
                        <i class="fas fa-faucet mr-2"></i> Activar Bomba (250 ml)
                    </button>

                    <!-- Botón Llenar Tanque -->
                    <button type="button" onclick="recargarTanque()" id="btn-recargar-agua" class="btn btn-outline-info btn-block py-3 rounded-pill font-weight-bold">
                        <i class="fas fa-sync-alt mr-2"></i> Llenar Tanque (5.00 L)
                    </button>
                </div>

                <div class="alert alert-light border small text-muted mt-4 mb-0 rounded-3">
                    <i class="fas fa-info-circle text-info mr-1"></i> El relé encenderá la bomba física durante 3 segundos y luego se apagará automáticamente.
                </div>
            </div>
        </div>

        <!-- GRÁFICA RADIAL DE NIVEL DE AGUA -->
        <div class="col-lg-8 mb-4">
            <div class="card card-iot bg-white shadow-sm p-4 h-100">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h6 class="font-weight-bold text-dark mb-0">
                        <i class="fas fa-chart-pie text-info mr-2"></i> Nivel de Capacidad del Tanque de Agua
                    </h6>
                    <span class="badge badge-light border">Capacidad Max: 5.0 Litros</span>
                </div>
                <div id="chart-tanque" style="min-height: 260px;"></div>
            </div>
        </div>

    </div>

    <!-- TABLA DE HISTORIAL DE HIDRATACIÓN -->
    <div class="card card-iot bg-white shadow-sm border-0 rounded-4">
        <div class="card-header bg-white py-3 border-0 d-flex justify-content-between align-items-center">
            <h6 class="m-0 font-weight-bold text-primary">
                <i class="fas fa-history mr-1"></i> Registro Histórico de Despachos de Agua
            </h6>
            <span class="badge badge-info px-3 py-1 text-white">Eventos Registrados</span>
        </div>
        <div class="card-body p-4 pt-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="bg-light text-uppercase small text-muted">
                        <tr>
                            <th class="border-0">Hora / Fecha</th>
                            <th class="border-0">Origen de Orden</th>
                            <th class="border-0">Nivel Restante</th>
                            <th class="border-0">Estado del Tanque</th>
                            <th class="border-0 text-center">Bomba Relé</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($historial as $item)
                        <tr>
                            <td class="font-weight-bold text-dark">{{ $item->created_at ? $item->created_at->format('d/m/Y H:i:s') : 'Reciente' }}</td>
                            <td>
                                @if($item->device_id == 'WEB_ACTION')
                                    <span class="badge badge-primary px-2 py-1"><i class="fas fa-mouse mr-1"></i> Manual Web</span>
                                @elseif($item->device_id == 'SISTEMA_RECARGA')
                                    <span class="badge badge-info px-2 py-1 text-white"><i class="fas fa-sync mr-1"></i> Recarga Tanque</span>
                                @else
                                    <span class="badge badge-light border text-muted"><i class="fas fa-microchip mr-1"></i> {{ $item->device_id ?? 'ESP32_AUTOMATICO' }}</span>
                                @endif
                            </td>
                            <td class="font-weight-bold text-info">{{ number_format($item->nivel_agua, 2) }} L</td>
                            <td>
                                @if($item->nivel_agua > 1.5)
                                    <span class="badge badge-success px-3 py-1">Nivel Óptimo</span>
                                @else
                                    <span class="badge badge-danger px-3 py-1">Nivel Bajo</span>
                                @endif
                            </td>
                            <td class="text-center text-muted small">
                                <i class="fas fa-check-circle text-success"></i> Activado
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="text-center py-4 text-muted">No hay registros de hidratación aún.</td>
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
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<script>
    // ============================================================
    // 1. FUNCIÓN AJAX: ACTIVAR BOMBA DE AGUA
    // ============================================================
    function despacharAgua() {
        let btn = $('#btn-despachar-agua');
        btn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin mr-2"></i> Activando bomba...');

        $.ajax({
            url: "{{ route('agua.despachar') }}",
            type: 'POST',
            data: { _token: '{{ csrf_token() }}' },
            success: function(res) {
                btn.prop('disabled', false).html('<i class="fas fa-faucet mr-2"></i> Activar Bomba (250 ml)');
                
                if (res.success) {
                    let nuevoNivel = parseFloat(res.nuevo_nivel);
                    $('#txt-nivel').text(nuevoNivel.toFixed(2));
                    
                    // Actualizar barra de progreso
                    let nuevoPorcentaje = Math.min(100, Math.max(0, Math.round((nuevoNivel / 5.0) * 100)));
                    $('#barra-agua').css('width', nuevoPorcentaje + '%');
                    $('#txt-porcentaje').text(nuevoPorcentaje + '%');

                    // Actualizar gráfica radial
                    chartTanque.updateSeries([nuevoPorcentaje]);

                    alert("¡Éxito! Orden enviada. La bomba se activará durante 3 segundos.");
                }
            },
            error: function() {
                btn.prop('disabled', false).html('<i class="fas fa-faucet mr-2"></i> Activar Bomba (250 ml)');
                alert("Error al comunicar la orden de agua con el servidor.");
            }
        });
    }

    // ============================================================
    // 2. FUNCIÓN AJAX: RECARGAR TANQUE A 5L
    // ============================================================
    function recargarTanque() {
        let btn = $('#btn-recargar-agua');
        btn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin mr-2"></i> Llenando...');

        $.ajax({
            url: "{{ route('agua.recargar') }}",
            type: 'POST',
            data: { _token: '{{ csrf_token() }}' },
            success: function(res) {
                btn.prop('disabled', false).html('<i class="fas fa-sync-alt mr-2"></i> Llenar Tanque (5.00 L)');
                
                if (res.success) {
                    $('#txt-nivel').text('5.00');
                    $('#barra-agua').css('width', '100%');
                    $('#txt-porcentaje').text('100%');
                    chartTanque.updateSeries([100]);

                    alert("¡Tanque de agua rellenado al 100% (5.00 L)!");
                }
            },
            error: function() {
                btn.prop('disabled', false).html('<i class="fas fa-sync-alt mr-2"></i> Llenar Tanque (5.00 L)');
                alert("Error al registrar la recarga de agua.");
            }
        });
    }

    // ============================================================
    // 3. GRÁFICA RADIAL APEXCHARTS DE AGUA
    // ============================================================
    var optionsTanque = {
        series: [{{ $porcentaje }}],
        chart: {
            height: 270,
            type: 'radialBar',
            fontFamily: 'Nunito, sans-serif'
        },
        plotOptions: {
            radialBar: {
                startAngle: -135,
                endAngle: 135,
                dataLabels: {
                    name: {
                        fontSize: '16px',
                        color: '#64748b',
                        offsetY: 10
                    },
                    value: {
                        offsetY: -15,
                        fontSize: '24px',
                        fontWeight: 'bold',
                        formatter: function (val) {
                            return val + "%";
                        }
                    }
                }
            }
        },
        fill: {
            type: 'gradient',
            gradient: {
                shade: 'dark',
                type: 'horizontal',
                shadeIntensity: 0.15,
                gradientToColors: ['#0284c7'],
                inverseColors: false,
                opacityFrom: 1,
                opacityTo: 1,
                stops: [0, 100]
            },
        },
        stroke: { dashArray: 4 },
        labels: ['Nivel Tanque'],
        colors: ['#0ea5e9']
    };

    var chartTanque = new ApexCharts(document.querySelector("#chart-tanque"), optionsTanque);
    chartTanque.render();
</script>
@endsection