@extends('layouts.app', ['class' => 'g-sidenav-show bg-gray-100'])

@section('content')
@include('layouts.navbars.auth.topnav', ['title' => 'Overview'])
<div class="container-fluid py-4">
    {{-- Section Real Time Info --}}
    <div class="row">
        <div class="col-xl-3 col-sm-6 mb-xl-0 mb-4">
            <div class="card my-2">
                <div class="card-body p-3">
                    <div class="row">
                        <div class="col-9">
                            <div class="numbers">
                                <p class="text-sm mb-2 text-uppercase font-weight-bold">
                                Today Energy Usage <span style="color: orange;">(MDP)</span>
                                </p>
                                <h5 class="font-weight-bolder">
                                    {{ $todayKwh }} kWh
                                </h5>
                                <p class="mb-0">
                                    <span class="text-success text-sm font-weight-bolder">+ 10%</span>
                                    <small> than average </small>
                                </p>
                            </div>
                        </div>
                        <div class="col-3 text-end mt-2 p-0">
                            <div class="icon icon-shape bg-gradient-warning text-center rounded-circle">
                                <i class="fa-solid fa-plug-circle-bolt text-lg opacity-10" aria-hidden="true"
                                    style="color: orange"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-sm-6 mb-xl-0 mb-4">
            <div class="card my-2">
                <div class="card-body p-3">
                    <div class="row">
                        <div class="col-9">
                            <div class="numbers">
                                <p class="text-sm mb-2 text-uppercase font-weight-bold">
                                Today Energy Usage <span style="color: orange;">1st Floor AC</span>
                                </p>
                                <h5 class="font-weight-bolder">
                                    {{ $todayKwh2 }} kWh
                                </h5>
                                <p class="mb-0">
                                    <span class="text-success text-sm font-weight-bolder">+ 10%</span>
                                    <small> than average </small>
                                </p>
                            </div>
                        </div>
                        <div class="col-3 text-end mt-2 p-0">
                            <div class="icon icon-shape bg-gradient-warning text-center rounded-circle">
                                <i class="fa-solid fa-plug-circle-bolt text-lg opacity-10" aria-hidden="true"
                                    style="color: orange"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-sm-6 mb-xl-0 mb-4">
            <div class="card my-2">
                <div class="card-body p-3">
                    <div class="row">
                        <div class="col-9">
                            <div class="numbers">
                                <p class="text-sm mb-2 text-uppercase font-weight-bold">
                                Today Energy Usage <span style="color: orange;">2nd Floor AC</span>
                                </p>
                                <h5 class="font-weight-bolder">
                                    {{ $todayKwh3 }} kWh
                                </h5>
                                <p class="mb-0">
                                    <span class="text-success text-sm font-weight-bolder">+ 10%</span>
                                    <small> than average </small>
                                </p>
                            </div>
                        </div>
                        <div class="col-3 text-end mt-2 p-0">
                            <div class="icon icon-shape bg-gradient-warning text-center rounded-circle">
                                <i class="fa-solid fa-plug-circle-bolt text-lg opacity-10" aria-hidden="true"
                                    style="color: orange"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!--<div class="col-xl-3 col-sm-6 mb-xl-0 mb-4">-->
        <!--    <div class="card my-2">-->
        <!--        <div class="card-body p-3">-->
        <!--            <div class="row">-->
        <!--                <div class="col-9">-->
        <!--                    <div class="numbers">-->
        <!--                        <p class="text-sm mb-2 text-uppercase font-weight-bold">-->
        <!--                        Today Electricity <span style="color: orange;">kWh 4</span>-->
        <!--                        </p>-->
        <!--                        <h5 class="font-weight-bolder">-->
        <!--                            {{ $todayKwh4 }} kWh-->
        <!--                        </h5>-->
        <!--                        <p class="mb-0">-->
        <!--                            <span class="text-success text-sm font-weight-bolder">+ 10%</span>-->
        <!--                            <small> than average </small>-->
        <!--                        </p>-->
        <!--                    </div>-->
        <!--                </div>-->
        <!--                <div class="col-3 text-end mt-2 p-0">-->
        <!--                    <div class="icon icon-shape bg-gradient-warning text-center rounded-circle">-->
        <!--                        <i class="fa-solid fa-plug-circle-bolt text-lg opacity-10" aria-hidden="true"-->
        <!--                            style="color: orange"></i>-->
        <!--                    </div>-->
        <!--                </div>-->
        <!--            </div>-->
        <!--        </div>-->
        <!--    </div>-->
        <!--</div>-->
        <!--<div class="col-xl-3 col-sm-6 mb-xl-0 mb-4">-->
        <!--    <div class="card my-2">-->
        <!--        <div class="card-body p-3">-->
        <!--            <div class="row">-->
        <!--                <div class="col-9">-->
        <!--                    <div class="numbers">-->
        <!--                        <p class="text-sm mb-2 text-uppercase font-weight-bold">-->
        <!--                        Today Electricity <span style="color: orange;">kWh 5</span>-->
        <!--                        </p>-->
        <!--                        <h5 class="font-weight-bolder">-->
        <!--                            {{ $todayKwh5 }} kWh-->
        <!--                        </h5>-->
        <!--                        <p class="mb-0">-->
        <!--                            <span class="text-success text-sm font-weight-bolder">+ 10%</span>-->
        <!--                            <small> than average </small>-->
        <!--                        </p>-->
        <!--                    </div>-->
        <!--                </div>-->
        <!--                <div class="col-3 text-end mt-2 p-0">-->
        <!--                    <div class="icon icon-shape bg-gradient-warning text-center rounded-circle">-->
        <!--                        <i class="fa-solid fa-plug-circle-bolt text-lg opacity-10" aria-hidden="true"-->
        <!--                            style="color: orange"></i>-->
        <!--                    </div>-->
        <!--                </div>-->
        <!--            </div>-->
        <!--        </div>-->
        <!--    </div>-->
        <!--</div>-->
        <div class="col-xl-3 col-sm-6 mb-xl-0 mb-4">
            <div class="card my-2">
                <div class="card-body p-3">
                    <div class="row">
                        <div class="col-9">
                            <div class="numbers">
                                <p class="text-sm mb-2 text-uppercase font-weight-bold">Water Usage</p>
                                <h5 class="font-weight-bolder">
                                    {{ $todayWater }} m<sup>3</sup>
                                </h5>
                                <p class="mb-0">
                                    <span class="text-success text-sm font-weight-bolder">+ 10%</span>
                                    <small> than average </small>
                                </p>
                            </div>
                        </div>
                        <div class="col-3 text-end mt-2">
                            <div class="icon icon-shape bg-gradient-primary shadow-danger text-center rounded-circle">
                                <i class="fa-solid fa-water text-lg opacity-10" aria-hidden="true"
                                    style="color: white"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-sm-6 mb-xl-0 mb-4">
            <div class="card my-2">
                <div class="card-body p-3">
                    <div class="row">
                        <div class="col-9">
                            <div class="numbers">
                                <p class="text-sm mb-2 text-uppercase font-weight-bold">PV Generated</p>
                                <h5 class="font-weight-bolder">
                                    {{ $todayPv }} kWh
                                </h5>
                                <p class="mb-0">
                                    <span class="text-success text-sm font-weight-bolder">+ 10%</span>
                                    <small> than average </small>
                                </p>
                            </div>
                        </div>
                        <div class="col-3 text-end mt-2">
                            <div class="icon icon-shape bg-gradient-warning shadow-danger text-center rounded-circle">
                                <i class="fa-solid fa-bolt text-lg opacity-10" aria-hidden="true"
                                    style="color: orange"></i>

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-sm-6 mb-xl-0 mb-4">
            <div class="card my-2">
                <div class="card-body p-3">
                    <div class="row">
                        <div class="col-9">
                            <div class="numbers">
                                <p class="text-sm mb-2 text-uppercase font-weight-bold">Today Income</p>
                                <h5 class="font-weight-bolder">
                                    {{ $todayIncome }} IDR
                                </h5>
                                <p class="mb-0">
                                    <span class="text-success text-sm font-weight-bolder">+ 10%</span>
                                    <small> than average </small>
                                </p>
                            </div>
                        </div>
                        <div class="col-3 text-end mt-2">
                            <div class="icon icon-shape bg-gradient-success shadow-success text-center rounded-circle">
                                <i class="fa-solid fa-money-bill-wave text-lg opacity-10" aria-hidden="true"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    {{-- Section Graph --}}
    <div class="row mt-4">
        <div class="col-lg-7 mb-lg-0 mb-4">
            <div class="card z-index-2 h-100">
                <div class="card-header pb-0 pt-3 bg-transparent">
                    <h6 class="text-capitalize">Energy Consumption (Monthly)</h6>
                    <p class="text-sm mb-0">
                        <i class="fa fa-arrow-up text-success"></i>
                        <span class="font-weight-bold">4% more</span> than previous month
                    </p>
                </div>
                <div class="card-body p-3">
                    <div class="chart">
                        <canvas id="chart-line" class="chart-canvas" height="300"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-5">
            <div class="card">
                <div class="card-body pb-0 mb-0 pt-3 bg-transparent">
                    <h6 class="text-capitalize mb-0">Devices Status</h6>
                </div>
                <div class="card-body pt-3">
                    @foreach ($items as $item)
                    <div class="d-flex justify-content-between bg-gradient-light my-2 p-2 border-radius-md">
                        <div class="text-dark fw-bold">{{ $item->device }}</div>
                        @if ($item->status == 1)
                        <span class="badge badge-sm bg-gradient-success">ON</span>
                        @else
                        <span class="badge badge-sm bg-gradient-secondary">OFF</span>
                        @endif
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>
@include('layouts.footers.auth.footer')
@endsection

@push('js')
<script src="./assets/js/plugins/chartjs.min.js"></script>
<script>
    var months = JSON.parse('{!! json_encode($months) !!}');
    var monthlyKwh = JSON.parse('{!! json_encode($monthlyKwh) !!}');

    var ctx1 = document.getElementById("chart-line").getContext("2d");

    var gradientStroke1 = ctx1.createLinearGradient(0, 230, 0, 50);

    gradientStroke1.addColorStop(1, 'rgba(251, 99, 64, 0.2)');
    gradientStroke1.addColorStop(0.2, 'rgba(251, 99, 64, 0.0)');
    gradientStroke1.addColorStop(0, 'rgba(251, 99, 64, 0)');
    new Chart(ctx1, {
        type: "line",
        data: {
            labels: months,
            datasets: [{
                label: "Energy (kWh)",
                data: monthlyKwh,
                tension: 0.1,
                borderWidth: 0,
                pointRadius: 0,
                borderColor: "#fb6340",
                backgroundColor: gradientStroke1,
                borderWidth: 3,
                fill: true,
                maxBarThickness: 6
            }],
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: false,
                }
            },
            interaction: {
                intersect: false,
                mode: 'index',
            },
            scales: {
                y: {
                    position: 'right',
                    grid: {
                        drawBorder: false,
                        display: true,
                        drawOnChartArea: true,
                        drawTicks: false,
                        borderDash: [5, 5]
                    },
                    title: {
                        display: true,
                        text: '(kWh)'
                    },
                    ticks: {
                        display: true,
                        font: {
                            size: 10,
                            family: "Open Sans",
                            style: 'normal',
                            lineHeight: 2
                        },
                    }
                },
                x: {
                    grid: {
                        drawBorder: false,
                        display: false,
                        drawOnChartArea: false,
                        drawTicks: false,
                        borderDash: [5, 5]
                    },
                    ticks: {
                        display: true,
                        padding: 20,
                        font: {
                            size: 11,
                            family: "Open Sans",
                            style: 'normal',
                            lineHeight: 2
                        },
                    }
                },
            },
        },
    });
</script>
@endpush