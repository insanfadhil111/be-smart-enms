@extends('layouts.app', ['class' => 'g-sidenav-show bg-gray-100'])

@section('content')
@include('layouts.navbars.auth.topnav', ['title' => $title])
<div class="container-fluid py-4">
    <div class="row">
        <div class="card">
            <div class="row-12 my-4">
                <div class="card-header my-0 py-0">
                    <h6>Energy Usage Total</h6>
                </div>
                <div class="card-body pt-0">
                    <div class="d-flex justify-content-center">
                        @foreach ($names as $i => $name)
                        <div class="col mx-2 p-2 border border-shadow"
                            style="border-radius: 1rem;">
                            <div class="numbers text-center">
                                <p class="text-sm mb-2 text-uppercase font-weight-bold">
                                    @php echo $name; @endphp
                                </p>
                                <h6 class="font-weight-bolder text-dark">
                                    {{ number_format($values[$i], 0, $decSep, $thSep)}} <span><small
                                            class="text-warning">{{
                                            $units[$i] }}</small></span>
                                </h6>

                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>

            {{-- Section Graph --}}
            <div class="row">
                <div class="col-lg-12 mb-lg-0 mb-4">
                    <div class="card z-index-2 h-100">
                        <div class="card-header pb-0 pt-3 bg-transparent d-flex justify-content-between">
                            <div class="col-md">
                                <h6 class="text-capitalize">Energy Consumption (Monthly)</h6>
                                <!-- <p class="text-sm mb-0">
                                    <i class="fa fa-arrow-up text-success"></i>
                                    <span class="font-weight-bold ">4% more</span> than previous year
                                </p> -->
                            </div>
                            <div class="col-md text-end">
                                <label for="selectedYear">Year :</label>
                                <select id="selectedYear" onchange="updateMonthlyChart()">
                                    @foreach($monthlyChartData as $yearData)
                                    @foreach($yearData as $year => $monthlyData)
                                    <option value="{{ $year }}" {{ $year=='2024' ? 'selected' : '' }}>{{ $year }}
                                    </option>
                                    @endforeach
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="card-body p-3">
                            <div id="chart-monthly" class="chart"
                                style="width: 100%; height: 300px; min-height: 300px;"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row mt-4 mb-2">
            <div class="col-lg-12 mb-lg-0 mb-4">
                <div class="card z-index-2 h-100">
                    <div class="card-header pb-0 pt-3 bg-transparent">
                        <h6 class="text-capitalize">Energy Consumption (Annualy)</h6>
                        <!-- <p class="text-sm mb-0">
                                <i class="fa fa-arrow-up text-success"></i>
                                <span class="font-weight-bold ">4% more</span> than previous year
                            </p> -->
                    </div>
                    <div class="card-body p-3">
                        <div id="chart-annual" class="chart" style="width: 100%; height: 300px;">
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
<script src="{{ asset('assets/js/plugins/chartjs.min.js') }}"></script>
<script src="https://cdn.jsdelivr.net/npm/echarts@5.4.2/dist/echarts.min.js"></script>

<script>
    var monthlyData = @json($monthlyChartData);
    var monthlyChart = echarts.init(document.getElementById('chart-monthly'));

    function updateMonthlyChart() {
        var selectedYear = document.getElementById('selectedYear').value;
        // Get the first array element that contains the year data
        var selectedData = monthlyData.find(yearData => yearData[selectedYear]);
        
        if (!selectedData || !selectedData[selectedYear]) {
            console.error('No data found for year:', selectedYear);
            return;
        }

        var months = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];
        var selectedKwh = new Array(12).fill(0);
        var selectedCost = new Array(12).fill(0);
        var selectedIke = new Array(12).fill('');
        var colors = new Array(12).fill('#ccc');

        selectedData[selectedYear].forEach(item => {
            var date = new Date(item.timestamp);
            var month = date.getMonth();
            selectedKwh[month] = item.kwh;
            selectedCost[month] = item.cost;
            selectedIke[month] = item.ike;
            colors[month] = item.ike_color || '#ccc';
        });

        var option = {
            tooltip: {
                trigger: 'axis',
                axisPointer: {
                    type: 'shadow'
                },
                formatter: function (params) {
                    var monthIndex = params[0].dataIndex;
                    return months[monthIndex] + '<br/>' +
                        'kWh: ' + params[0].value + '<br/>' +
                        'IKE: ' + selectedIke[monthIndex] + '<br/>' +
                        'Cost: ' + (selectedCost[monthIndex] || 0).toLocaleString();
                }
            },
            xAxis: {
                type: 'category',
                data: months
            },
            yAxis: {
                type: 'value',
                name: 'kwh'
            },
            series: [{
                name: 'kwh',
                data: selectedKwh,
                type: 'bar',
                itemStyle: {
                    color: function (params) {
                        return colors[params.dataIndex];
                    }
                }
            }]
        };

        monthlyChart.setOption(option);
    }

    window.addEventListener('resize', function() {
        monthlyChart.resize();
    });

    updateMonthlyChart();
</script>



<script>
    var annualData = @json($annualChartData);
    var annualChart = echarts.init(document.getElementById('chart-annual'));

    var years = annualData.map(item => item.year);
    var consumption = annualData.map(item => item.consumption);
    var costs = annualData.map(item => item.cost);
    var colors = annualData.map(item => item.ike_color);

    var option = {
        tooltip: {
            trigger: 'axis',
            formatter: function (params) {
                var dataIndex = params[0].dataIndex;
                return annualData[dataIndex].year + '<br/>' +
                    'Consumption: ' + annualData[dataIndex].consumption + ' kWh<br/>' +
                    'Cost: ' + annualData[dataIndex].cost.toLocaleString() + '<br/>' +
                    'IKE: ' + annualData[dataIndex].ike;
            }
        },
        xAxis: {
            type: 'category',
            data: years
        },
        yAxis: {
            type: 'value',
            name: 'kWh'
        },
        series: [{
            data: consumption,
            type: 'bar',
            itemStyle: {
                color: function (params) {
                    return colors[params.dataIndex];
                }
            }
        }]
    };

    annualChart.setOption(option);
</script>

@endpush