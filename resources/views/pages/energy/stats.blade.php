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
                                            <p class="text-xs text-secondary mb-0">Last Updated : {{ $lastUpdateTime }} WIB</p>
                                        </div>
                                        <div class="col text-end">
                                            <!--<button type="button" onclick="updateDailyPrediction()"-->
                                            <!--    class="btn btn-sm bg-gradient-success p-1">Daily Update</button>-->
                                            <!--<button type="button" onclick="updateMonthlyPrediction()"-->
                                            <!--    class="btn btn-sm bg-gradient-success p-1">Monthly Update</button>-->
                                            <button type="button" onclick="changeToDailyChart()"
                                                class="btn btn-sm bg-gradient-info p-1">Daily</button>
                                            <button type="button" onclick="changeToMonthlyChart()"
                                                class="btn btn-sm bg-gradient-info p-1">Monthly</button>
                                        </div>
                                    </div>
                                    <h6 class="text-sm mb-0">
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
                                    </h6>
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
                                                <h6 class="text-sm text-bold text-dark">{{
                                                    number_format($mdpEn[$keyEn],0,$decSep,$thSep)
                                                    }} <span><small class="text-warning ms-2">{{ $units2[$i]
                                                            }}</small></span>
                                                </h6>
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
                    
                    {{-- Section Electricity Bill Daily --}}
                    <div class="row mt-4">
                        <div class="col-lg-12 mb-lg-0 mb-4">
                            <div class="card z-index-2 h-100">
                                <div class="card-header pb-0 pt-3 bg-transparent">
                                    <div class="d-flex justify-content-around">
                                        <div class="col-md">
                                            <h6 class="text-capitalize">Electricity Bill Daily</h6>
                                            <p class="text-xs text-secondary mb-0">Last Updated : {{ $lastUpdateTime }} WIB</p>
                                        </div>
                                        <div class="col-md text-end">
                                            <a href="{{ route('export-daily-kwh', ['month' => $selectedMonth]) }}">
                                                <button type="button" class="btn btn-sm bg-gradient-info p-1">Export XLSX</button>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                                <div class="card-body p-3">
                                    <!-- Filter Bulan -->
                                    <form method="GET" class="mb-3">
                                        <div class="row g-2 align-items-center">
                                            <div class="col-auto">
                                                <label for="month" class="form-label fw-bold mb-0">Pilih Bulan:</label>
                                            </div>
                                            <div class="col-auto">
                                                <select name="month" id="month" class="form-select" onchange="this.form.submit()">
                                                    @foreach($monthOptions as $option)
                                                        <option value="{{ $option['value'] }}" {{ $selectedMonth == $option['value'] ? 'selected' : '' }}>
                                                            {{ $option['label'] }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                    </form>

                                    <!-- Tabel Energi Harian -->
                                    <div class="table-responsive">
                                        <table class="table table-striped table-hover align-items-center">
                                            <thead>
                                                <tr class="text-center">
                                                    <th>Hari dan Tanggal</th>
                                                    <th>Energy (kWh)</th>
                                                    <th>Cost (Rp)</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @forelse($paginatedDaily as $row)
                                                <tr class="text-center">
                                                    <td>{{ \Carbon\Carbon::parse($row['date'])->translatedFormat('l, d M Y') }}</td>
                                                    <td>{{ number_format($row['total'], 2, $decSep, $thSep) }}</td>
                                                    <td>Rp {{ $row['bill'] }}</td>
                                                </tr>
                                                @empty
                                                <tr>
                                                    <td colspan="3" class="text-center">Tidak ada data untuk bulan ini.</td>
                                                </tr>
                                                @endforelse
                                            </tbody>
                                        </table>
                                    </div>

                                    {{-- Pagination --}}
                                    <div class="d-flex justify-content-center">
                                        {{ $paginatedDaily->withQueryString()->links() }}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Section Electricity Bill Monthly --}}
                    <div class="row mt-4">
                        <div class="col-lg-12 mb-lg-0 mb-4">
                            <div class="card z-index-2 h-100">
                                <div class="card-header pb-0 pt-3 bg-transparent">
                                    <div class="d-flex justify-content-around">
                                        <div class="col-md">
                                            <h6 class="text-capitalize">Electricity Bill Monthly</h6>
                                            <p class="text-xs text-secondary mb-0">Last Updated : {{ $lastUpdateTime }} WIB</p>
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
    // Fungsi untuk update harian
    // function updateDailyPrediction() {
    //     fetch('http://203.6.149.118:5000/trigger-daily-update', {
    //         method: 'POST',
    //         headers: {
    //             'Content-Type': 'application/json'
    //         }
    //     })
    //     .then(response => response.json())
    //     .then(data => {
    //         if (data.status === 'success') {
    //             alert('Prediksi harian berhasil diperbarui!');
    //         } else {
    //             alert('Gagal memperbarui prediksi harian: ' + data.message);
    //         }
    //     })
    //     .catch(error => {
    //         console.error('Error:', error);
    //         alert('Terjadi kesalahan saat memperbarui prediksi.');
    //     });
    // }
    
    // // Fungsi untuk update bulanan
    // function updateMonthlyPrediction() {
    //     fetch('http://203.6.149.118:5000/trigger-monthly-update', {
    //         method: 'POST',
    //         headers: {
    //             'Content-Type': 'application/json'
    //         }
    //     })
    //     .then(response => response.json())
    //     .then(data => {
    //         if (data.status === 'success') {
    //             alert('Prediksi bulanan berhasil diperbarui!');
    //         } else {
    //             alert('Gagal memperbarui prediksi bulanan: ' + data.message);
    //         }
    //     })
    //     .catch(error => {
    //         console.error('Error:', error);
    //         alert('Terjadi kesalahan saat memperbarui prediksi.');
    //     });
    // }

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

    // Data dari PHP
    var actualData = @json($daily->map(function ($item) {
        return ['date' => $item['date'], 'value' => $item['total']];
    }));
    var predictData = @json($predicts->map(function ($item) {
        return ['date' => $item['date'], 'value' => $item['prediction']];
    }));

    // Error metrics dari controller
    var mae = {{ $mae }};
    var mape = {{ $mape }};
    var rmse = {{ $rmse }};

    var allDates = [...new Set([...actualData.map(d => d.date), ...predictData.map(d => d.date)])].sort();

    var dailyOptions = {
        tooltip: {
            trigger: 'axis',
            formatter: function (params) {
                var date = params[0].axisValue;
                var actualValue = null;
                var forecastedValue = null;

                // Loop through each series
                params.forEach(p => {
                    if (p.seriesName === 'Actual') {
                        actualValue = p.value[1];
                    }
                    if (p.seriesName === 'Forecasted') {
                        forecastedValue = p.value[1];
                    }
                });

                var error = null;
                var percentageError = null;
                var accuracyPercentage = null;

                if (actualValue !== null && forecastedValue !== null) {
                    error = actualValue - forecastedValue;
                    percentageError = (Math.abs(error) / actualValue) * 100;
                    accuracyPercentage = (1 - Math.abs(error) / actualValue) * 100;
                }

                var formattedDate = new Date(date).toLocaleDateString('en-US', { 
                    weekday: 'long',
                    day: '2-digit',
                    month: 'short',
                    year: 'numeric'
                });

                var result = formattedDate + '<br/>';
                if (actualValue !== null) {
                    result += 'Actual: ' + actualValue + ' kWh<br/>';
                }
                if (forecastedValue !== null) {
                    result += 'Forecasted: ' + forecastedValue + ' kWh<br/>';
                }

                if (error !== null && percentageError !== null) {
                    result += 'Error: ' + error.toFixed(2) + ' kWh<br/>';
                    result += 'Percentage of Error: ' + percentageError.toFixed(2) + '%<br/>';
                    result += 'Accuracy Percentage: ' + accuracyPercentage.toFixed(2) + '%<br/>';
                } else {
                    result += 'No error calculation available for this data point.<br/>';
                }

                // Tambahan MAE, RMSE, MAPE dari controller
                result += '<br/><b>MAE</b>: ' + mae.toFixed(2) + ' kWh<br/>';
                result += '<b>RMSE</b>: ' + rmse.toFixed(2) + ' kWh<br/>';
                result += '<b>MAPE</b>: ' + mape.toFixed(2) + ' %';

                return result;
            }
        },
        legend: {
            data: ['Actual', 'Forecasted'],
            textStyle: {
                color: '#888' // warna abu-abu agak terang, bisa diganti sesuka hati
            }
        },
        xAxis: {
            type: 'category',
            data: allDates,
            axisLabel: {
                formatter: function (value) {
                    return new Date(value).toLocaleDateString('en-US', { 
                        day: '2-digit', 
                        month: 'short',
                        year: 'numeric' 
                    });
                }
            }
        },
        yAxis: {
            type: 'value'
        },
        dataZoom: [
            {
                type: 'slider',
                show: true,
                xAxisIndex: [0],
                start: 0,
                end: 100,
            },
            {
                type: 'inside',
                xAxisIndex: [0],
            }
        ],
        series: [
            {
                name: 'Actual',
                type: 'line',
                data: actualData.map(d => [d.date, d.value]),
                symbol: 'circle',
                symbolSize: 4
            },
            {
                name: 'Forecasted',
                type: 'line',
                data: predictData.map(d => [d.date, d.value]),
                symbol: 'circle',
                symbolSize: 4
            }
        ]
    };

    dailyChart.setOption(dailyOptions);
</script>


<script>
    var monthlyChart = echarts.init(document.getElementById('chart-monthly'));

    // Data dari PHP
    var asli = (@json($monthlyActuals)).map(item => ({ bulan: item.bulan, value: item.kwh }));
    var prediksi = (@json($monthlyPredicts)).map(item => ({ bulan: item.bulan, value: item.prediction }));

    // Error metrics dari controller
    var mae = {{ $mae }};
    var mape = {{ $mape }};
    var rmse = {{ $rmse }};

    var months = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];

    var monthlyOption = {
        tooltip: {
            trigger: 'axis',
            formatter: function (params) {
                var bulan = params[0].axisValue;
                var actualValue = params[0].value[1];  // Actual value
                var predictValue = params[1].value[1]; // Predict value
                var error = actualValue - predictValue; // Error = Actual - Predict
                var accuratePercentage = ((1 - Math.abs(actualValue - predictValue) / actualValue) * 100).toFixed(2); // Accurate Percentage

                // Ensure percentage is between 0 and 100
                accuratePercentage = Math.max(0, Math.min(100, accuratePercentage));

                // Format tooltip text
                var result = bulan + '<br/>';
                result += params.map(p => p.seriesName + ': ' + p.value[1]).join('<br/>') +
                    '<br/>Error: ' + error.toFixed(2) + 
                    '<br/>Accurate Percentage: ' + accuratePercentage + '%<br/>';

                // Tambahkan MAE, RMSE, MAPE di tooltip
                result += '<br/><b>MAE</b>: ' + mae.toFixed(2) + ' kWh<br/>';
                result += '<b>RMSE</b>: ' + rmse.toFixed(2) + ' kWh<br/>';
                result += '<b>MAPE</b>: ' + mape.toFixed(2) + ' %';

                return result;
            }
        },
        legend: {
            data: ['Actual', 'Predict'],
            textStyle: {
                color: '#888' // warna abu-abu agak terang, bisa diganti sesuka hati
            }
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
    };

    monthlyChart.setOption(monthlyOption);
</script>


@endpush