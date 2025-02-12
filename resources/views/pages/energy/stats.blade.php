@extends('layouts.app', ['class' => 'g-sidenav-show bg-gray-100'])

@section('content')
@include('layouts.navbars.auth.topnav', ['title' => $title])
<div class="container-fluid py-4" style="overflow-x: hidden;">
    <div class="row">
        <div class="col-12">
            <div class="card">
                @include('pages.energy.nav')
                <div class="card-body pt-0">
                    {{-- Section Graph --}}
                    <div class="row">
                        <div class="col-lg-12 mb-lg-0 mb-4">
                            <div class="card">
                                <div class="card-header pb-0 pt-3 bg-transparent">
                                    <div class="row d-flex justify-content-between my-0 py-0">
                                        <div class="col">
                                            <h6 class="text-capitalize">Energy Consumption (Daily)</h6>
                                        </div>
                                        <div class="col text-end">
                                            <button type="button" onclick="changeToDailyChart()"
                                                class="btn btn-sm bg-gradient-info p-1">Daily</button>
                                            <button type="button" onclick="changeToMonthlyChart()"
                                                class="btn btn-sm bg-gradient-info p-1">Monthly</button>
                                        </div>
                                    </div>
                                    <p class="text-sm mb-0">
                                        @if($energyDiffStatus == 'naik')
                                        <i class="fa fa-arrow-up text-success"></i>
                                        <span class="font-weight-bold">{{ $energyDiff }}% more</span> than median in the
                                        previous
                                        {{ $todayName }}
                                        @else
                                        <i class="fa fa-arrow-down text-danger"></i>
                                        <span class="font-weight-bold">{{ $energyDiff }}% less</span> than median in the
                                        previous
                                        {{ $todayName }}
                                        @endif
                                    </p>
                                </div>
                                <div class="card-body p-3">
                                    <div class="row">
                                        <div id="chart-daily" style="height: 300px;"></div>
                                        <div id="chart-monthly" style=" height: 300px; display: none;"></div>
                                    </div>
                                    <div class="row">
                                        @foreach ($keysEn as $i => $keyEn)
                                        <div class="col-sm-4 pe-4 py-0 m-0">
                                            <div class="d-flex justify-content-between">
                                                <p class="text-sm text-uppercase text-dark">{{ $collection2[$i] }}</p>
                                                <p class="text-sm text-bold text-dark">{{
                                                    number_format($mdpEn[$keyEn],0,$decSep,$thSep)
                                                    }} <span><small class="text-warning ms-2">{{ $units2[$i]
                                                            }}</small></span>
                                                </p>
                                            </div>
                                        </div>
                                        @endforeach
                                        <!-- Tambahan manual karena belum ada di controller
                                        <div class="col-sm-4 pe-4 py-0 m-0">
                                            <div class="d-flex justify-content-between">
                                                <p class="text-sm text-uppercase text-dark">Comparison</p>
                                                <p class="text-sm text-uppercase text-success">SAVE</p>
                                                <p class="text-sm text-uppercase text-danger">INCREASE</p>
                                                <p class="text-sm text-bold text-dark">{{
                                                    number_format(1234567,0,$decSep,$thSep)
                                                    }} <span><small class="text-warning ms-2">IDR</small></span>
                                                </p>
                                            </div>
                                        </div> -->
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Section Electricity Bill --}}
                    <div class="row mt-4">
                        <div class="col-lg-12 mb-lg-0 mb-4">
                            <div class="card z-index-2 h-100">
                                <div class="card-header pb-0 pt-3 bg-transparent">
                                    <div class="d-flex justify-content-around">
                                        <div class="col-md">
                                            <h6 class="text-capitalize">Electricity Bill</h6>
                                        </div>
                                        <div class="col-md text-end">
                                            <a href="{{ route('export-monthly-kwh') }}"> <button type="button"
                                                    class="btn btn-sm bg-gradient-info p-1">Export
                                                    XLSX</button></a>
                                        </div>

                                    </div>
                                </div>
                                <div class="card-body p-3">
                                    <table class="table table-striped table-hover">
                                        <tr>
                                            <th class="text-center" width="15%">Bulan</th>
                                            <th class="text-center" width="20%">Tahun</th>
                                            <th class="text-center" width="20%">Energi (KWH)</th>
                                            <th class="text-center" width="10%"></th>
                                            <th class="text-center" width="15%">Total</th>
                                            <th class="text-center" width="20%">Than Last Month</th>
                                        </tr>
                                        @foreach ($paginatedData as $item)
                                        <tr>
                                            <td class="text-start">{{$item['bulan']}}</td>
                                            <td class="text-center">{{$item['tahun']}}</td>
                                            <td class="text-center">{{ $item['total'] }}</td>
                                            <td class="text-end">Rp </td>
                                            <td class="text-end">{{$item['bill']}}</td>
                                            @if($item['diffStatus']=='naik')
                                            <td class="text-center text-sm mb-0"><i
                                                    class="fa-solid fa-sort-up text-danger "></i><span class="mx-2">+
                                                    {{$item['diff'] }} %</span></td>
                                            @elseif ($item['diffStatus']=='turun')
                                            <td class="text-center text-sm my-0 mx-2"><i
                                                    class="fa-solid fa-sort-down text-success "></i>
                                                <span class="mx-2"> {{$item['diff'] }} %</span>
                                            </td>
                                            @else()
                                            <td class="text-center text-sm mb-0"></td>
                                            @endif
                                        </tr>
                                        @endforeach
                                    </table>
                                    <!-- Add pagination links -->
                                    <div>
                                        {{ $paginatedData->links() }}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
@push('js')
<script src="https://cdn.jsdelivr.net/npm/echarts@5.4.2/dist/echarts.min.js"></script>

<script>
    function changeToDailyChart() {
        document.getElementById('chart-daily').style.display = 'block';
        document.getElementById('chart-monthly').style.display = 'none';
        if (window.dailyChart) {
            window.dailyChart.resize();
        }
    }

    function changeToMonthlyChart() {
        document.getElementById('chart-daily').style.display = 'none';
        document.getElementById('chart-monthly').style.display = 'block';
        if (window.monthlyChart) {
            window.monthlyChart.resize();
        }
    }
</script>

<script>
    var dailyChart = echarts.init(document.getElementById('chart-daily'));

    var actualData = @json($daily -> map(function ($item) {
        return ['date' => $item['date'], 'value' => $item['total']];
    }));
    var predictData = @json($predicts -> map(function ($item) {
        return ['date' => $item['date'], 'value' => $item['prediction']];
    }));

    var allDates = [...new Set([...actualData.map(d => d.date), ...predictData.map(d => d.date)])].sort();

    var dailyOptions = {
        tooltip: {
            trigger: 'axis',
            formatter: function (params) {
                var date = params[0].axisValue;
                var error = @json($errors).find(e => e.date === date);
                return date + '<br/>' +
                    params.map(p => p.seriesName + ': ' + p.value[1]).join('<br/>') +
                    (error ? '<br/>Error: ' + error.error + '<br/>Percentage: ' + error.percentage + '%' : '');
            }
        },
        legend: {
            data: ['Actual', 'Forecasted']
        },
        xAxis: {
            type: 'category',
            data: allDates,
            axisLabel: {
                formatter: function (value) {
                    return new Date(value).toLocaleDateString('en-US', { day: '2-digit', month: 'short' });
                }
            }
        },
        yAxis: {
            type: 'value'
        },
        series: [
            {
                name: 'Actual',
                type: 'line',
                data: actualData.map(d => [d.date, d.value])
            },
            {
                name: 'Forecasted',
                type: 'line',
                data: predictData.map(d => [d.date, d.value])
            }
        ]
    };

    dailyChart.setOption(dailyOptions);
</script>

<script>
    var monthlyChart = echarts.init(document.getElementById('chart-monthly'));

    var asli = (@json($monthlyActuals)).map(item => ({ bulan: item.bulan, value: item.kwh }));
    var prediksi = (@json($monthlyPredicts)).map(item => ({ bulan: item.bulan, value: item.prediction }));

    var months = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];

    var monthlyOption = {
        tooltip: {
            trigger: 'axis',
            formatter: function (params) {
                var bulan = params[0].axisValue;
                var error = @json($monthlyErrors).find(e => e.bulan === bulan);
                return bulan + '<br/>' +
                    params.map(p => p.seriesName + ': ' + p.value[1]).join('<br/>') +
                    (error ? '<br/>Error: ' + error.error + '<br/>Percentage: ' + error.percentage + '%' : '');
            }
        },
        legend: {
            data: ['Actual', 'Predict']
        },
        xAxis: {
            type: 'category',
            data: months,
        },
        yAxis: {
            type: 'value',
            name: 'kWh'
        },
        series: [
            {
                name: 'Actual',
                type: 'bar',
                data: asli.map(d => [d.bulan, d.value]),
                color: '#91cc75'
            },
            {
                name: 'Predict',
                type: 'line',
                data: prediksi.map(d => [d.bulan, d.value]),
                color: '#f45b5b'
            }
        ]
    }
    monthlyChart.setOption(monthlyOption);


</script>

@endpush