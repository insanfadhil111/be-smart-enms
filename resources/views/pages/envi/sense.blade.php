@extends('layouts.app', ['class' => 'g-sidenav-show bg-gray-100'])

@section('content')
@include('layouts.navbars.auth.topnav', ['title' => $title])
<div class="container-fluid py-4">
    <div class="row">
        <div class="card">
            @include('pages.envi.nav')

            <div class="row-12">
                <div class="card-header my-0 py-0">
                    <h6>Real Time Monitoring</h6>
                </div>

                <div class="card-body pt-0">
                    <div class="row mb-4">
                    {{-- Suhu --}}
                    <div class="col-md-3">
                        <div class="card border shadow-sm">
                            <div class="card-body text-center">
                                <h6 class="mb-1">
                                    🌡️ <strong>Suhu</strong>
                                </h6>
                                <p class="fs-4 mb-0">{{ $value[0] }}</p>
                            </div>
                        </div>
                    </div>

                    {{-- Terasa --}}
                    <div class="col-md-3">
                        <div class="card border shadow-sm">
                            <div class="card-body text-center">
                                <h6 class="mb-1">
                                    🔥 <strong>Terasa</strong>
                                </h6>
                                <p class="fs-4 mb-0">{{ $feelsLike }}</p>
                            </div>
                        </div>
                    </div>

                    {{-- Cuaca --}}
                    <div class="col-md-3">
                        <div class="card border shadow-sm">
                            <div class="card-body text-center">
                                <h6 class="mb-1">
                                    🌥️ <strong>Cuaca</strong>
                                </h6>
                                <p class="text-capitalize mb-1">{{ $weatherDesc }}</p>
                                <!-- <img src="http://openweathermap.org/img/wn/{{ $weatherIcon }}@2x.png" alt="icon cuaca" width="40"> -->
                            </div>
                        </div>
                    </div>

                    {{-- Tekanan --}}
                    <div class="col-md-3">
                        <div class="card border shadow-sm">
                            <div class="card-body text-center">
                                <h6 class="mb-1">
                                    🎯 <strong>Tekanan</strong>
                                </h6>
                                <p class="fs-5 mb-0">{{ $pressure }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row mb-4">
                    {{-- Kelembapan --}}
                    <div class="col-md-3">
                        <div class="card border shadow-sm">
                            <div class="card-body text-center">
                                <h6 class="mb-1">
                                    💧 <strong>Kelembapan</strong>
                                </h6>
                                <p class="fs-4 mb-0">{{ $value[1] }}</p>
                            </div>
                        </div>
                    </div>

                    {{-- Angin --}}
                    <div class="col-md-3">
                        <div class="card border shadow-sm">
                            <div class="card-body text-center">
                                <h6 class="mb-1">
                                    💨 <strong>Angin</strong>
                                </h6>
                                <p class="fs-4 mb-0">{{ $value[2] }}</p>
                            </div>
                        </div>
                    </div>

                    {{-- Awan --}}
                    <div class="col-md-3">
                        <div class="card border shadow-sm">
                            <div class="card-body text-center">
                                <h6 class="mb-1">
                                    ☁️ <strong>Awan</strong>
                                </h6>
                                <p class="fs-4 mb-0">{{ $cloudiness }}</p>
                            </div>
                        </div>
                    </div>

                    {{-- Kota --}}
                    <div class="col-md-3">
                        <div class="card border shadow-sm">
                            <div class="card-body text-center">
                                <h6 class="mb-1">
                                    📍 <strong>Kota</strong>
                                </h6>
                                <p class="fs-5 mb-0">{{ $cityName }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                </div>
            </div>

        </div>
    </div>
</div>


@include('layouts.footers.auth.footer')
</div>
@endsection

@push('js')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
<script src="{{ asset('assets/js/plugins/chartjs.min.js') }}"></script>
<script>
    const tempData = [{{ $value[0] }}];
    const humidityData = [{{ $value[1] }}];
    const windData = [{{ $value[2] }}];

    function createChart(id, label, data, color) {
        const ctx = document.getElementById(id).getContext("2d");
        const gradient = ctx.createLinearGradient(0, 230, 0, 50);
        gradient.addColorStop(1, color + "0.2)");
        gradient.addColorStop(0.2, color + "0.0)");
        gradient.addColorStop(0, color + "0)");

        new Chart(ctx, {
            type: "line",
            data: {
                labels: ["Sekarang"],
                datasets: [{
                    label: label,
                    tension: 0.4,
                    borderWidth: 3,
                    pointRadius: 5,
                    borderColor: color.replace('0.2)', '1)'),
                    backgroundColor: gradient,
                    fill: true,
                    data: data,
                }],
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: { legend: { display: false } },
                interaction: { intersect: false, mode: 'index' },
                scales: {
                    y: { beginAtZero: true },
                    x: { grid: { display: false } },
                }
            }
        });
    }

    createChart("chart-temp", "Suhu (°C)", tempData, "rgba(251, 99, 64, ");
    createChart("chart-humidity", "Kelembapan (%)", humidityData, "rgba(54, 162, 235, ");
    createChart("chart-wind", "Angin (km/jam)", windData, "rgba(75, 192, 192, ");
</script>
@endpush
