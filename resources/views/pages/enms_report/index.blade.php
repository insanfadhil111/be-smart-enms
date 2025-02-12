@extends('layouts.app', ['class' => 'g-sidenav-show bg-gray-100'])

@section('content')
@include('layouts.navbars.auth.topnav', ['title' => $title])
<div class="container-fluid py-4">
    <div class="row">
        <div class="card">
            <div class="row-12 my-4">
                <div class="card-header my-0 py-0">
                    <h6>Annual Overview</h6>
                </div>
                <div class="card-body pt-0">
                    <select id="yearSelect" onchange="updateDonutCharts()">
                        @foreach($donutChartData as $yearData)
                        @foreach($yearData as $year => $content)
                        <option value="{{ $year }}" {{ $year=='2024' ? 'selected' : '' }}>{{ $year }}</option>
                        @endforeach
                        @endforeach
                    </select>
                    <div class="chart-container" style="display: flex; justify-content: space-around;flex-wrap: wrap;">
                        <div class="">
                            <div id="costChart" class="doughnut-chart"
                                style=" width: 300px;height: 300px;margin: 20px;"></div>
                            <div id="costProjected" class="projected-figure"></div>
                        </div>
                        <div class="">
                            <div id="consumptionChart" class="doughnut-chart"
                                style=" width: 300px;height: 300px;margin: 20px;"></div>
                            <div id="consumptionProjected" class="projected-figure"></div>
                        </div>
                        <div class="">
                            <div id="carbonChart" class="doughnut-chart"
                                style=" width: 300px;height: 300px;margin: 20px;">
                            </div>
                            <div id="carbonProjected" class="projected-figure"></div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row-12">
                <div class="card-header my-0 py-0 d-flex justify-content-between">
                    <div class="col-md">
                        <h6>Electricity Chart</h6>
                    </div>
                    <div class="col-md text-end">
                        <div class="row d-flex justify-content-center">
                            <div class="col">
                                <label for="baselineYear">Baseline Year:</label>
                                <select id="baselineYear" onchange="updateChart()">
                                    @foreach($chartData as $yearlyData)
                                    @foreach($yearlyData as $year => $monthlyData)
                                    <option value="{{ $year }}" {{ $year=='2018' ? 'selected' : '' }}>{{ $year }}
                                    </option>
                                    @endforeach
                                    @endforeach
                                </select>
                            </div>
                            <div class="col">
                                <label for="comparedYear">Compared Year:</label>
                                <select id="comparedYear" onchange="updateChart()">
                                    @foreach($chartData as $yearlyData)
                                    @foreach($yearlyData as $year => $monthlyData)
                                    <option value="{{ $year }}" {{ $year=='2024' ? 'selected' : '' }}>{{ $year }}
                                    </option>
                                    @endforeach
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-body p-3">
                    <div id="electricity-chart" style="width: 100%; height: 300px;"></div>
                </div>
            </div>
        </div>
    </div>
</div>

@include('layouts.footers.auth.footer')
</div>
@endsection
@push('js')
<script src=" {{ asset('assets/js/plugins/chartjs.min.js') }}">
</script>
<script src="https://cdn.jsdelivr.net/npm/echarts@5.4.2/dist/echarts.min.js"></script>

<!-- Dougnut Chart -->
<script>
    var allData = @json($donutChartData);
    var currentYear = Object.keys(allData[0])[0];

    function updateDonutCharts() {
        currentYear = document.getElementById('yearSelect').value;
        var yearData = allData.find(item => item.hasOwnProperty(currentYear))[currentYear];

        createDoughnutChart('costChart', yearData.cost, 'Cost (inc VAT)', 'IDR', 'costProjected');
        createDoughnutChart('consumptionChart', yearData.consumption, 'Consumption (kWh)', 'kWh', 'consumptionProjected');
        createDoughnutChart('carbonChart', yearData.carbon, 'Carbon (tonnes CO₂e)', 'tonnes', 'carbonProjected');
    }

    function createDoughnutChart(elementId, data, title, unit, projectedElementId) {
        var chart = echarts.init(document.getElementById(elementId));

        var total = data.total;
        var breakdownData = [];
        for (var key in data.breakdown) {
            breakdownData.push({
                value: data.breakdown[key],
                name: key.charAt(0).toUpperCase() + key.slice(1)
            });
        }

        var option = {
            title: {
                text: title,
                left: 'center',
                top: 0,
                textStyle: {
                    fontWeight: 'bold'
                }
            },
            tooltip: {
                trigger: 'item',
                formatter: '{a} <br/>{b}: ' + '{c} ' + unit + ' ({d}%)'
            },
            legend: {
                orient: 'vertical',
                left: 'center',
                top: '90%'
            },
            color: title === 'Cost (inc VAT)' ? ['#FF4500', '#8B0000', '#FF8C00'] :
                title === 'Consumption (kWh)' ? ['#00CED1', '#4682B4', '#20B2AA'] :
                    ['#32CD32', '#006400', '#90EE90'],
            series: [
                {
                    name: title,
                    type: 'pie',
                    radius: ['40%', '70%'],
                    avoidLabelOverlap: false,
                    label: {
                        show: false,
                        position: 'center',
                    },
                    emphasis: {
                        label: {
                            show: true,
                            fontSize: '18',
                            fontWeight: 'bold',
                        }
                    },
                    labelLine: {
                        show: false
                    },
                    data: breakdownData
                }
            ]
        };
        chart.setOption(option);

        // Set projected figure
        var projectedElement = document.getElementById(projectedElementId);
        var projectedText = `Projected figure<br><strong>${data.projected.toLocaleString()} ${unit} Per Year.</strong><br>`;
        projectedText += `<span style="color: ${data.increase > 0 ? 'green' : 'red'};">${data.increase}% `;
        projectedText += `${data.increase > 0 ? 'increase' : 'decrease'} on 2019 ${data.increase > 0 ? '↑' : '↓'}</span>`;
        projectedElement.innerHTML = projectedText;
    }

    // Initial chart creation
    updateDonutCharts();
</script>

<!-- Line Chart -->
<script>
    // Initialize chart
    var chart = echarts.init(document.getElementById('electricity-chart'));

    var chartData = @json($chartData);

    function updateChart() {
        var baselineYear = document.getElementById('baselineYear').value;
        var comparedYear = document.getElementById('comparedYear').value;

        var baselineData = chartData.find(item => item.hasOwnProperty(baselineYear))[baselineYear];
        var comparedData = chartData.find(item => item.hasOwnProperty(comparedYear))[comparedYear];

        var months = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];
        var baselineKwh = new Array(12).fill(0);
        var comparedKwh = new Array(12).fill(0);
        var baselineCost = new Array(12).fill(0);
        var comparedCost = new Array(12).fill(0);

        var monthsMap = {
            'Jan': 0, 'Feb': 1, 'Mar': 2, 'Apr': 3, 'May': 4, 'Jun': 5,
            'Jul': 6, 'Aug': 7, 'Sep': 8, 'Oct': 9, 'Nov': 10, 'Dec': 11
        };
        baselineData.forEach(item => {
            var index = monthsMap[item.bulan];
            if (index !== undefined) {  // Check if month name is valid
                baselineKwh[index] = item.kwh;
                baselineCost[index] = item.cost;
            }
        });

        comparedData.forEach(item => {
            var index = monthsMap[item.bulan];
            if (index !== undefined) {  // Check if month name is valid
                comparedKwh[index] = item.kwh;
                comparedCost[index] = item.cost;
            }
        });

        var option = {
            title: {
                // text: 'Monthly Electricity Consumption Comparison'
            },
            tooltip: {
                trigger: 'axis',
                formatter: function (params) {
                    var baselineIndex = params[0].dataIndex;
                    var comparedIndex = params[1].dataIndex;
                    return months[baselineIndex] + '<br />' +
                        baselineYear + ': ' + baselineKwh[baselineIndex] + ' kWh (Cost: Rp ' + baselineCost[baselineIndex] + ')<br />' +
                        comparedYear + ': ' + comparedKwh[comparedIndex] + ' kWh (Cost: Rp ' + comparedCost[comparedIndex] + ')';
                }
            },
            legend: {
                data: ['Baseline', 'Compared Year']
            },
            xAxis: {
                type: 'category',
                data: months
            },
            yAxis: {
                type: 'value',
                name: 'kWh'
            },
            series: [
                {
                    name: 'Baseline',
                    type: 'line',
                    data: baselineKwh,
                    color: '#91cc75'
                },
                {
                    name: 'Compared Year',
                    type: 'line',
                    data: comparedKwh,
                    color: '#f45b5b'
                }
            ]
        };

        chart.setOption(option);
    }

    // Initial chart update
    updateChart();
</script>


@endpush