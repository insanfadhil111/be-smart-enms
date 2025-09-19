@extends('layouts.app', ['class' => 'g-sidenav-show bg-gray-100'])

@section('tambahanHead')
<meta name="csrf-token" content="{{ csrf_token() }}">
@endsection

@section('content')
@include('layouts.navbars.auth.topnav', ['title' => $title])
<div class="container-fluid py-4">
    <div class="row">
        <div class="card">
            <div class="row-12 p-2 my-2">
                <div class="card-title mt-0 mb-4 py-0">
                    <h6 class="m-0">Overview Monitoring</h6>
                    @if($msg_pv != null)
                    <p class="text-sm m-0 p-0 text-warning">{{ $msg_pv }}</p>
                    <p class="text-sm m-0 p-0">Last update Rooftop : {{ $lastUpdated_gw }}</p>
                    <p class="text-sm m-0 p-0">Last update Hallway : {{ $lastUpdated_mpp }}</p>
                    @elseif ($msg_mpp != null)
                    <p class="text-sm m-0 p-0 text-warning">{{ $msg_mpp }}</p>
                    <p class="text-sm m-0 p-0">Last update Hallway : {{ $lastUpdated_mpp }}</p>
                    @elseif ($msg_gw != null)
                    <p class="text-sm m-0 p-0 text-warning">{{ $msg_gw }}</p>
                    <p class="text-sm m-0 p-0">Last update Rooftop : {{ $lastUpdated_gw }}</p>
                    @else
                    <p class="text-success m-0 p-0 text-sm">Successfully collected today's data!</p>
                    @endif
                </div>

                <div class="card-body pt-0">
                    <!-- Section Real Time -->
                    <div class="d-flex justify-content-center">
                        @php
                        $i=0;
                        foreach ($values as $value) : @endphp <div class="col mx-2 p-2 border border-shadow"
                            style="border-radius: 1rem;">
                            <div class="numbers px-2">
                                <p class="text-sm mb-2 text-uppercase">
                                    @php echo $names[$i]; @endphp
                                </p>
                                <h6 class="text-lg font-weight-bolder text-dark m-0 p-0">
                                    {{ number_format($value, 2, $decSep, $thSep) }}
                                    <span><small class="text-warning">{{ $satuan[$i] }}</small></span>
                                </h6>
                            </div>
                        </div>
                        @php
                        $i++;
                        endforeach
                        @endphp
                    </div>
                </div>
            </div>

            <!-- Section Graph  -->
            <div class="row-12 mt-0">
                <div class="col-lg-12 mb-lg-0 mb-2">
                    <div class="card z-index-2 h-100">
                        <div class="card-header pb-0 pt-2 bg-transparent d-flex justify-content-between">
                            <h6 class="text-capitalize">Power Chart</h6>
                            <div class="filter-controls">
                                <meta name="csrf-token" content="{{ csrf_token() }}">
                                <label for="startDate">Start Date:</label>
                                <input type="date" id="startDate">

                                <label for="endDate">End Date:</label>
                                <input type="date" id="endDate">

                                <button id="filterButton">Apply Filter
                                </button>
                            </div>
                            <button id="export-nre">Export Data</button>
                        </div>
                        <div class="card-body p-3">
                            <div id="chart-power" style="width: 100%; height: 300px;"></div>
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
<script type="text/javascript" src="https://canvasjs.com/assets/script/jquery-1.11.1.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/echarts/5.4.2/echarts.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.17.0/xlsx.full.min.js"></script>

<script>
    var datesMpp = JSON.parse('{!! json_encode($datesMpp) !!}');
    var powerMpp = JSON.parse('{!! json_encode($powerMpp) !!}');
    var datesGw = JSON.parse('{!! json_encode($datesGw) !!}');
    var powerGw = JSON.parse('{!! json_encode($powerGw) !!}');

    let myChart;
    let currentFilteredData = {
        datesMpp: datesMpp,
        powerMpp: powerMpp,
        datesGw: datesGw,
        powerGw: powerGw
    };

    document.addEventListener('DOMContentLoaded', function () {
        var chartDom = document.getElementById('chart-power');
        myChart = echarts.init(chartDom);

        updateChart(datesMpp, powerMpp, datesGw, powerGw);

        document.getElementById('filterButton').addEventListener('click', applyDateFilter);
        document.getElementById('export-nre').addEventListener('click', exportToExcel);

        window.addEventListener('resize', function () {
            myChart.resize();
        });
    });

    function formatData(dates, powers) {
        return dates.map((date, index) => [date, powers[index]]);
    }

    function updateChart(datesMpp, powerMpp, datesGw, powerGw) {
        const option = {
            tooltip: {
                trigger: 'axis',
                axisPointer: {
                    type: 'cross'
                },
                formatter: function (params) {
                    var result = params[0].axisValue + '<br/>';
                    params.forEach(function (param) {
                        result += param.marker + ' ' + param.seriesName + ': ' + param.data[1] + '<br/>';
                    });
                    return result;
                }
            },
            legend: {
                data: ['Hallway (W)', 'Rooftop (W)'],
                textStyle: {
                color: '#888' // warna abu-abu agak terang, bisa diganti sesuka hati
                }
            },
            xAxis: {
                type: 'category',
                data: [...new Set([...datesMpp, ...datesGw])].sort(),
                axisLabel: {
                    formatter: function (value) {
                        return value.split(' ')[1]; // Show only time part
                    }
                }
            },
            yAxis: {
                type: 'value',
                name: 'Power (W)'
            },
            series: [
                {
                    name: 'Hallway (W)',
                    type: 'line',
                    data: formatData(datesMpp, powerMpp),
                    emphasis: {
                        itemStyle: {
                            color: '#ff3333'
                        }
                    }
                },
                {
                    name: 'Rooftop (W)',
                    type: 'line',
                    data: formatData(datesGw, powerGw),
                    emphasis: {
                        itemStyle: {
                            color: '#33ff33'
                        }
                    }
                }
            ],
            dataZoom: [
                {
                    type: 'inside',
                    start: 0,
                    end: 100
                },
                {
                    show: true,
                    type: 'slider',
                    top: '90%',
                    start: 0,
                    end: 100
                }
            ]
        };

        myChart.setOption(option);
    }

    function applyDateFilter() {
        const startDate = document.getElementById('startDate').value;
        const endDate = document.getElementById('endDate').value;

        if (startDate && endDate) {
            fetch('api/pv-chart-filter', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                },
                body: JSON.stringify({ startDate: startDate, endDate: endDate })
            })
                .then(response => {
                    if (!response.ok) {
                        throw new Error(`HTTP error! status: ${response.status}`);
                    }
                    return response.json();
                })
                .then(data => {
                    console.log('Received data:', data);
                    currentFilteredData = data;
                    updateChart(data.datesMpp, data.powerMpp, data.datesGw, data.powerGw);
                })
                .catch(error => {
                    console.error('Error:', error);
                });
        }
    }

    function exportToExcel() {
        const { datesMpp, powerMpp, datesGw, powerGw } = currentFilteredData;

        const exportData = [['Timestamp MPP', 'MPP Power', 'Timestamp GW', 'GW Power']];
        const maxLength = Math.max(datesMpp.length, datesGw.length);

        for (let i = 0; i < maxLength; i++) {
            exportData.push([
                datesMpp[i] || '',
                powerMpp[i] || '',
                datesGw[i] || '',
                powerGw[i] || ''
            ]);
        }

        // Reverse the data (excluding the header)
        const reversedData = [exportData[0], ...exportData.slice(1).reverse()];

        const ws = XLSX.utils.aoa_to_sheet(reversedData);
        const wb = XLSX.utils.book_new();
        XLSX.utils.book_append_sheet(wb, ws, "Power Data");

        XLSX.writeFile(wb, "PV Data Exports.xlsx");
    }

</script>



@endpush