@extends('layouts.app', ['class' => 'g-sidenav-show bg-gray-100'])

@section('tambahanHead')
<meta name="csrf-token" content="{{ csrf_token() }}">
@endsection

@section('content')
@include('layouts.navbars.auth.topnav', ['title' => $title])
<div class="container-fluid py-4" style="overflow: hidden;">
    <div class="row">
        <div class="card">
            @include('pages.energy.nav')
            <div class="row-12">
                <div class="card-header my-0 py-0 d-flex justify-content-between align-items-center">
                    <div class="col-auto">
                        <h6>Real Time Monitoring</h6>
                        <p>
                            <small class="mdp-value">Last Updated : {{ $latestUpdatedMdp }}</small>
                            <small class="ac1-value" style="display: none;">Last Updated :
                                {{ $latestUpdatedAc1 }}</small>
                            <small class="ac2-value" style="display: none;">Last Updated :
                                {{ $latestUpdatedAc2 }}</small>
                            <small class="util1-value" style="display: none;">Last Updated : {{ $latestUpdatedUtil1
                                }}</small>
                            <small class="util2-value" style="display: none;">Last Updated : {{ $latestUpdatedUtil2
                                }}</small>
                        </p>
                    </div>
                    <div class="col-auto">
                        <div class=" dropdown">
                            <button class="btn bg-light border-shadow dropdown-toggle p-1" type="button"
                                id="dataSourceDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                                MDP
                            </button>
                            <ul class="dropdown-menu" aria-labelledby="dataSourceDropdown">
                                <li><a class="dropdown-item" href="#" data-value="mdp">MDP (Total)</a></li>
                                <li><a class="dropdown-item" href="#" data-value="ac1">SDP AC 1</a></li>
                                <li><a class="dropdown-item" href="#" data-value="ac2">SDP AC 2</a></li>
                                <li><a class="dropdown-item" href="#" data-value="util1">Utilitas Lt.1</a></li>
                                <li><a class="dropdown-item" href="#" data-value="util2">Utilitas Lt.2</a></li>
                            </ul>
                        </div>
                    </div>

                </div>

                <div class="card-body pt-0">
                    <!-- Section Real Time -->
                    <div class="row row-cols-1 row-cols-sm-2 row-cols-md-3 row-cols-lg-4 row-cols-xl-6 g-3"
                        id="dataDisplay">
                        @foreach ($keys as $i => $key)
                        <div class="col py-2 px-0 border border-shadow h-100"
                            style="border-radius: 1rem; background-color:white">
                            <div class="numbers px-1 text-center">
                                <p class="text-sm mb-2 text-uppercase">
                                    {{ $collection[$i] }}
                                </p>
                                <p class="text-lg font-weight-bolder text-dark m-0 p-0">
                                    <span class="mdp-value">{{ number_format($mdp->$key, 1, $decSep, $thSep)
                                        }}</span>
                                    <span class="ac1-value" style="display: none;">{{ number_format($ac1->$key, 1,
                                        $decSep, $thSep) }}</span>
                                    <span class="ac2-value" style="display: none;">{{ number_format($ac2->$key, 1,
                                        $decSep, $thSep) }}</span>
                                    <span class="util1-value" style="display: none;">{{ number_format($util1->$key, 1,
                                        $decSep, $thSep) }}</span>
                                    <span class="util2-value" style="display: none;">{{ number_format($util2->$key, 1,
                                        $decSep, $thSep) }}</span>
                                    <span><small class="text-warning">{{ $units[$i] }}</small></span>
                                </p>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>

            <div class="row-12">
                <div class="card-header my-0 py-0">
                    <h6>Energy Usage</h6>
                </div>
                <div class="card-body pt-0">
                    <div class="d-flex justify-content-center">
                        @foreach ($keysEn as $i => $keyEn)
                        <div class="col mx-2 p-2 border border-shadow"
                            style="border-radius: 1rem; background-color:white">
                            <div class="numbers text-center">
                                <p class="text-sm mb-2 text-uppercase font-weight-bold">
                                    {{ $collection2[$i] }}
                                </p>
                                <h6 class="font-weight-bolder text-dark m-0 p-0">
                                    <span class="mdp-value">{{ number_format($mdpEn[$keyEn],0,$decSep,$thSep) }}</span>
                                    <span class="ac1-value" style="display: none;">{{ number_format($ac1En[$keyEn],
                                        0, $decSep, $thSep) }}</span>
                                    <span class="ac2-value" style="display: none;">{{ number_format($ac2En[$keyEn],
                                        0, $decSep, $thSep) }}</span>
                                    <span class="util1-value" style="display: none;">{{ number_format($util1En[$keyEn],
                                        0, $decSep, $thSep) }}</span>
                                    <span class="util2-value" style="display: none;">{{ number_format($util2En[$keyEn],
                                        0, $decSep, $thSep) }}</span>
                                    <span><small class="text-warning">{{ $units2[$i] }}</small></span>
                                </h6>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>

            {{-- Section Graph --}}
            <div class="row-12 mt-0">
                <div class="col-lg-12 mb-lg-0 mb-2">
                    <div class="card">
                        <div
                            class="card-header pb-0 pt-2 bg-transparent d-flex justify-content-between align-items-center">
                            <h6 class="text-capitalize">Charts</h6>
                            <div class="filter-controls">
                                <label for="startDate">Start Date:</label>
                                <input type="date" id="startDate">

                                <label for="endDate">End Date:</label>
                                <input type="date" id="endDate">
                            </div>
                            <button id="filterButton">Apply Filter
                            </button>
                            <div class="magnitude">
                                <label for="magnitudeSelect">Units:</label>
                                <select id="magnitudeSelect">
                                    <option value="Van">Van</option>
                                    <option value="Vbn">Vbn</option>
                                    <option value="Vcn">Vcn</option>
                                    <option value="Ia">Ia</option>
                                    <option value="Ib">Ib</option>
                                    <option value="Ic">Ic</option>
                                    <option value="It">It</option>
                                    <option value="Pa">Pa</option>
                                    <option value="Pb">Pb</option>
                                    <option value="Pc">Pc</option>
                                    <option value="Pt">Pt</option>
                                    <option value="Qa">Qa</option>
                                    <option value="Qb">Qb</option>
                                    <option value="Qc">Qc</option>
                                    <option value="Qt">Qt</option>
                                    <option value="pf">pf</option>
                                    <option value="f">f</option>
                                </select>
                            </div>

                            <button id="exportButton">Export Data</button>
                        </div>
                        <div class="card-body p-3">
                            <div id="chart" style="height: 300px;"></div>
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
<script src="https://cdnjs.cloudflare.com/ajax/libs/echarts/5.4.2/echarts.min.js"></script>
<script type="text/javascript" src="https://canvasjs.com/assets/script/jquery-1.11.1.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.17.0/xlsx.full.min.js"></script>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const dropdownItems = document.querySelectorAll('.dropdown-item');
        const dropdownButton = document.getElementById('dataSourceDropdown');
        const valueTypes = ['mdp', 'ac1', 'ac2', 'util1', 'util2'];
        const valueElements = valueTypes.reduce((acc, type) => {
            acc[type] = document.querySelectorAll(`.${type}-value`);
            return acc;
        }, {});

        dropdownItems.forEach(item => {
            item.addEventListener('click', function (e) {
                e.preventDefault();
                const selectedValue = this.getAttribute('data-value');
                dropdownButton.textContent = this.textContent;

                Object.values(valueElements).forEach(elements =>
                    elements.forEach(el => el.style.display = 'none')
                );

                if (valueElements[selectedValue]) {
                    valueElements[selectedValue].forEach(el => el.style.display = 'inline');
                }
            });
        });

        const chartData = @json($chartData);
        // const chartData = $chartData;
        const chartContainer = document.getElementById('chart');
        const magnitudeSelect = document.getElementById('magnitudeSelect');
        const startDateInput = document.getElementById('startDate');
        const endDateInput = document.getElementById('endDate');
        const filterButton = document.getElementById('filterButton');
        const exportButton = document.getElementById('exportButton');

        let chart;
        let filteredData = { ...chartData };

        if (chartContainer) {
            chart = echarts.init(chartContainer);

            function updateChart(magnitude) {
                const option = {
                    title: {
                        text: `${magnitude} vs Time for 5 Devices`,
                        left: 'center',
                        top: 10,
                    },
                    tooltip: { trigger: 'axis' },
                    legend: {
                        data: Object.keys(filteredData.devices),
                        top: 40,
                        left: 'center',
                    },
                    xAxis: {
                        type: 'category',
                        data: filteredData.dates
                    },
                    yAxis: {
                        type: 'value',
                        name: magnitude
                    },
                    series: Object.keys(filteredData.devices).map(device => ({
                        name: device,
                        type: 'line',
                        data: filteredData.devices[device][magnitude]
                    })),
                    dataZoom: [
                        { type: 'inside', start: 0, end: 100 },
                        { show: true, type: 'slider', top: '90%', start: 0, end: 100 }
                    ]
                };

                chart.setOption(option);
            }

            function applyDateFilter() {
                const startDate = startDateInput.value;
                const endDate = endDateInput.value;

                if (startDate && endDate) {
                    fetch('api/mdp-chart-filter', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                        },
                        body: JSON.stringify({ startDate, endDate })
                    })
                        .then(response => {
                            if (!response.ok) throw new Error(`HTTP error! status: ${response.status}`);
                            return response.json();
                        })
                        .then(data => {
                            filteredData = data;
                            updateChart(magnitudeSelect.value);
                        })
                        .catch(error => console.error('Error:', error));
                }
            }

            function exportChartData() {
                const selectedMagnitude = magnitudeSelect.value;
                const ws = XLSX.utils.aoa_to_sheet([
                    ['Timestamp', ...Object.keys(filteredData.devices)],
                    ...filteredData.dates.map((date, index) => [
                        date,
                        ...Object.keys(filteredData.devices).map(device =>
                            filteredData.devices[device][selectedMagnitude][index]
                        )
                    ])
                ]);
                const wb = XLSX.utils.book_new();
                XLSX.utils.book_append_sheet(wb, ws, "ChartData");
                XLSX.writeFile(wb, `${selectedMagnitude}_chart_data.xlsx`);
            }

            // Set initial date range
            const dates = chartData.dates.map(date => new Date(date));
            const maxDate = new Date(Math.max(...dates));
            maxDate.setDate(maxDate.getDate() + 1); // Add one day to maxDate
            const minDate = new Date(maxDate);
            minDate.setDate(minDate.getDate() - 7); // 7 days before maxDate

            startDateInput.value = minDate.toISOString().split('T')[0];
            endDateInput.value = maxDate.toISOString().split('T')[0];

            // Event listeners
            magnitudeSelect.addEventListener('change', () => updateChart(magnitudeSelect.value));
            filterButton.addEventListener('click', applyDateFilter);
            exportButton.addEventListener('click', exportChartData);
            window.addEventListener('resize', () => chart.resize());

            // Initial chart render
            applyDateFilter();
        }
    });

</script>
@endpush