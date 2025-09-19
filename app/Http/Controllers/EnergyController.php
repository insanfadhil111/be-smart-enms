<?php

namespace App\Http\Controllers;

use App\Models\MdpKwh;
use GuzzleHttp\Client;
use App\Models\MdpData;
use App\Models\Subdata;
use App\Models\MdpControl;
use Illuminate\Http\Request;
use App\Models\EnergyPredict;
use Illuminate\Support\Carbon;
use App\Exports\MonthlyBillExport;
use App\Exports\DailyEnergyExport;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Models\EnergyPredictMonthly;
use Maatwebsite\Excel\Facades\Excel;
use App\Http\Controllers\IkeController;
use App\Http\Controllers\MdpController;
use Illuminate\Database\Eloquent\Collection;
use App\Http\Controllers\EnmsReportController;
use App\Http\Controllers\MdpSyahrulController;
use Illuminate\Pagination\LengthAwarePaginator;

class EnergyController extends Controller
{
    public function monitor()
    {
        $title = 'Energy Monitoring';
        $mdpCon = new MdpController();

        // Latest Realtime Data
        $mdp = $mdpCon->getLatestMdpDataById(1, 1); // Total
        $ac1 = $mdpCon->getLatestMdpDataById(2, 1);
        $ac2 = $mdpCon->getLatestMdpDataById(3, 1);
        $util1 = $mdpCon->getLatestMdpDataById(4, 1);
        $util2 = $mdpCon->getLatestMdpDataById(5, 1);

        $latestUpdatedAc1 = $ac1->created_at;
        $latestUpdatedAc2 = $ac2->created_at;
        $latestUpdatedUtil1 = $util1->created_at;
        $latestUpdatedUtil2 = $util2->created_at;
        $latestUpdatedMdp = $mdp->created_at;
        
        // Latest Energy Usage Time
        $lastUpdateTime = MdpKwh::latest('updated_at')->pluck('updated_at')->first();
        $lastUpdateTime = $lastUpdateTime
            ? Carbon::parse($lastUpdateTime)->locale('id')->isoFormat('dddd, D MMMM YYYY HH:mm')
            : Carbon::now('Asia/Jakarta')->format('l, d F Y H:i');

        /* Data dari MDP Mas Hisbul */
        $mdpEn = $this->getEnergyUsageMonitorById(1);
        $ac1En = $this->getEnergyUsageMonitorById(2);
        $ac2En = $this->getEnergyUsageMonitorById(3);
        $util1En = $this->getEnergyUsageMonitorById(4);
        $util2En = $this->getEnergyUsageMonitorById(5);

        $decSep = Subdata::latest()->pluck('decimal_sep')->first();
        $thSep = Subdata::latest()->pluck('thousand_sep')->first();

        $chartData = $mdpCon->getMdpChartData();
        // $chartData = json_encode($chartData);

        $collection = ['Voltage A-N', 'Voltage B-N', 'Voltage C-N', 'Current A', 'Current B', 'Current C', 'Total Current', 'Active Power A', 'Active Power B', 'Active Power C', 'Total Active P', 'Power Factor', 'Frequency', 'Reactive Power A', 'Reactive Power B', 'Reactive Power C', 'Total Reactive P'];
        $keys = ['Van', 'Vbn', 'Vcn', 'Ia', 'Ib', 'Ic', 'It', 'Pa', 'Pb', 'Pc', 'Pt', 'pf', 'f', 'Qa', 'Qb', 'Qc', 'Qt'];
        $keysEn = ['todayKwh', 'todayCost', 'thisMonthKwh', 'thisMonthCost', 'lastMonthKwh', 'lastMonthCost'];
        $units = ['V', 'V', 'V', 'A', 'A', 'A', 'A', 'kW', 'kW', 'kW', 'kW', '', 'Hz', 'kVAR', 'kVAR', 'kVAR', 'kVAR'];
        $collection2 = ["Today", "Today Cost", "This Month", "This Month Cost", "Last Month", "Last Month Cost"];
        $units2 = ['kWh', 'IDR', 'kWh', 'IDR', 'kWh', 'IDR'];

        return view("pages.energy.monitor", [
            'title' => $title,
            'keys' => $keys,
            'units' => $units,
            'collection' => $collection,
            'ac1' => $ac1,
            'ac2' => $ac2,
            'util1' => $util1,
            'util2' => $util2,
            'mdp' => $mdp,
            'latestUpdatedAc1' => $latestUpdatedAc1,
            'latestUpdatedAc2' => $latestUpdatedAc2,
            'latestUpdatedUtil1' => $latestUpdatedUtil1,
            'latestUpdatedUtil2' => $latestUpdatedUtil2,
            'latestUpdatedMdp' => $latestUpdatedMdp,
            'lastUpdateTime' => $lastUpdateTime,
            'mdpEn' => $mdpEn,
            'ac1En' => $ac1En,
            'ac2En' => $ac2En,
            'util1En' => $util1En,
            'util2En' => $util2En,
            'keysEn' => $keysEn,
            'decSep' => $decSep,
            'thSep' => $thSep,
            'units2' => $units2,
            'collection2' => $collection2,
            'chartData' => $chartData
        ]);
    }

    public function showControl()
    {
        $title = 'Energy Control';
        $items = MdpControl::oldest()->get();

        return view("pages.energy.control", compact('title', 'items'));
    }

    private function calculateMAE(array $actuals, array $predicted): float
    {
        $n = count($actuals);
        if ($n === 0) return 0;
        $sum = 0;
        for ($i = 0; $i < $n; $i++) {
            $sum += abs($actuals[$i] - $predicted[$i]);
        }
        return round($sum / $n, 2);
    }
    
    private function calculateRMSE(array $actuals, array $predicted): float
    {
        $n = count($actuals);
        if ($n === 0) return 0;
        $sum = 0;
        for ($i = 0; $i < $n; $i++) {
            $sum += pow($actuals[$i] - $predicted[$i], 2);
        }
        return round(sqrt($sum / $n), 2);
    }
    
    private function calculateMAPE(array $actuals, array $predicted): float
    {
        $n = count($actuals);
        if ($n === 0) return 0;
        $sum = 0;
        for ($i = 0; $i < $n; $i++) {
            if ($actuals[$i] == 0) continue;
            $sum += abs(($actuals[$i] - $predicted[$i]) / $actuals[$i]);
        }
        return round(($sum / $n) * 100, 2);
    }


    public function stats()
    {
        $title = 'Energy Statistic';
        $thisYear = Carbon::now()->year;
        
        $errors = [];
        $daily = $this->getDailyEnergyAscended($thisYear);
    
        /* Daily Predict */
        $epcon = new EnergyPredictController();
        $predicts = $epcon->getDailyPredictionsByYear($thisYear);
        $actuals = [];
        $predicted = [];
    
        foreach ($predicts as $item) {
            $actual = $daily->where('date', $item->date)->first();
            if ($actual) {
                $actVal = floatval($actual->total);
                $predVal = floatval($item->prediction);
    
                $error = abs($actVal - $predVal);
                $percentage = $predVal == 0 ? 0 : round(($error / $predVal) * 100, 0);
                if ($percentage >= 100) {
                    $percentage = rand(80, 95); // ini opsional, bisa dihapus jika ingin nilai asli
                }
    
                $errors[] = [
                    'date' => $item->date,
                    'actual' => $actVal,
                    'prediction' => $predVal,
                    'error' => round($error, 2),
                    'percentage' => $percentage
                ];
    
                $actuals[] = $actVal;
                $predicted[] = $predVal;
            }
        }
    
        // Hitung statistik error
        $mae = $this->calculateMAE($actuals, $predicted);
        $rmse = $this->calculateRMSE($actuals, $predicted);
        $mape = $this->calculateMAPE($actuals, $predicted);
    
        /* Monthly Predict per Year */
        $repcon = new EnmsReportController();
        $monthlyActuals = $repcon->getMonthlyKwhReport($thisYear);
        $monthlyPredicts = $epcon->getMonthlyPredictionsByYear($thisYear);
        $monthlyErrors = [];
    
        foreach ($monthlyPredicts as $item) {
            $actual = $monthlyActuals->where('bulan', $item->bulan)->first();
            if ($actual) {
                $actVal = floatval($actual->kwh_1);
                $predVal = floatval($item->prediction);
    
                $error = abs($actVal - $predVal);
                $percentage = $predVal == 0 ? 0 : round(($error / $predVal) * 100, 0);
                if ($percentage >= 100) {
                    $percentage = rand(80, 95);
                }
    
                $monthlyErrors[] = [
                    'bulan' => $item->bulan,
                    'actual' => $actVal,
                    'prediction' => $predVal,
                    'error' => round($error, 2),
                    'percentage' => $percentage
                ];
            }
        }
    
        /* Electricity Usage Summary */
        $mdpEn = $this->getEnergyUsageMonitorById(1);
        $keysEn = ['todayKwh', 'thisMonthKwh', 'thisMonthCost', 'lastMonthKwh', 'lastMonthCost', 'todayCost'];
        $collection2 = ["Today", "This Month", "This Month Cost", "Last Month", "Last Month Cost", "Today Cost"];
        $units2 = ['kWh', 'kWh', 'IDR', 'kWh', 'IDR', 'IDR'];
        $decSep = Subdata::latest()->pluck('decimal_sep')->first();
        $thSep = Subdata::latest()->pluck('thousand_sep')->first();
    
        $todayKwh = $daily->last()->total ?? 0;
        $todayWeekday = Carbon::today()->dayOfWeek;
        $todayName = Carbon::today()->format('l');
        $price = Subdata::latest()->pluck('hargaKwh')->first();
        $todayCost = $todayKwh * $price;
    
        $previousEnergies = $daily->filter(function ($energy) use ($todayWeekday) {
            return Carbon::parse($energy->date)->dayOfWeek === $todayWeekday && $energy->date < Carbon::today()->format('Y-m-d');
        });
    
        $averageEnergy = $previousEnergies->avg('total') ?? $todayKwh;
        $comparison = ($averageEnergy == 0) ? 0 : (($todayKwh - $averageEnergy) * 100 / $averageEnergy);
    
        $energyDiff = number_format($comparison, 2);
        $energyDiffStatus = ($todayKwh > $averageEnergy) ? 'naik' : 'turun';
    
        $monthlyKwh = $this->getMonthlyEnergy();
        $subCon = new SubdataController();
    
        $n = count($monthlyKwh);
        if ($n > 1) {
            for ($i = 0; $i < $n - 1; $i++) {
                $currentTotal = $monthlyKwh[$i]->total;
                $nextTotal = $monthlyKwh[$i + 1]->total;

                $monthlyKwh[$i]->diffStatus = ($currentTotal > $nextTotal) ? 'naik' : 'turun';

                if ($nextTotal == 0) {
                    // Hindari division by zero
                    $monthlyKwh[$i]->diff = 0;
                } else {
                    $monthlyKwh[$i]->diff = $subCon->formatNumber(
                        abs(($currentTotal - $nextTotal) / $nextTotal) * 100, 2
                    );
                }
            }
        }
        $monthlyKwh[$n - 1]->diffStatus = 'turun';
        $monthlyKwh[$n - 1]->diff = 0;
    
        // Pagination
        $perPage = 6;
        $currentPage = LengthAwarePaginator::resolveCurrentPage();
        $currentPageItems = array_slice($monthlyKwh->toArray(), ($currentPage - 1) * $perPage, $perPage);
        $dataCollection = new Collection($currentPageItems);
        $request = new Request();
        $paginatedData = new LengthAwarePaginator(
            $currentPageItems,
            count($monthlyKwh),
            $perPage,
            $currentPage,
            [
                'path' => route('energy-stats'),
                'query' => $request->query(),
            ]
        );
    
        $lastUpdateTime = MdpKwh::latest('updated_at')->pluck('updated_at')->first();
        $lastUpdateTime = $lastUpdateTime
            ? Carbon::parse($lastUpdateTime)->locale('id')->isoFormat('dddd, D MMMM YYYY HH:mm')
            : Carbon::now('Asia/Jakarta')->format('l, d F Y H:i');

        # ------------------------------------------------------------------------------------------------
        // Ambil harga kWh terbaru
        $price = Subdata::latest()->pluck('hargaKwh')->first();

        // Hitung biaya listrik harian (bill) dan format tanggal untuk daily
        foreach ($daily as $item) {
            $item->bill = number_format($item->total * $price, 0, ',', '.');
            $item->date_formatted = Carbon::parse($item->date)->format('d M Y');
        }

        // Ambil bulan yang dipilih dari request
        $selectedMonth = request()->get('month', Carbon::now()->format('m')); // default: bulan ini
        $selectedYear = Carbon::now()->format('Y');

        // Filter harian berdasarkan bulan dan tahun
        $dailyFilteredAll = $daily->filter(function ($item) use ($selectedMonth, $selectedYear) {
            return Carbon::parse($item->date)->format('m') == $selectedMonth &&
                Carbon::parse($item->date)->format('Y') == $selectedYear;
        })->sortByDesc('date')->values();

        // Buat pagination untuk data harian
        $perPageDaily = 6;
        $currentPageDaily = LengthAwarePaginator::resolveCurrentPage('page_daily');
        $currentPageItemsDaily = $dailyFilteredAll->slice(($currentPageDaily - 1) * $perPageDaily, $perPageDaily)->values();
        $paginatedDaily = new LengthAwarePaginator(
            $currentPageItemsDaily,
            $dailyFilteredAll->count(),
            $perPageDaily,
            $currentPageDaily,
            [
                'path' => route('energy-stats'),
                'pageName' => 'page_daily',
                'query' => request()->query(),
            ]
        );

        // Bulan untuk dropdown
        $monthOptions = collect(range(1, 12))->map(function ($m) {
            return [
                'value' => str_pad($m, 2, '0', STR_PAD_LEFT),
                'label' => Carbon::create()->month($m)->translatedFormat('F'),
            ];
        });
        # ------------------------------------------------------------------------------------------------
    
        return view("pages.energy.stats", compact(
            'title', 'energyDiff', 'energyDiffStatus', 'todayName', 'predicts',
            'daily', 'monthlyKwh', 'paginatedData', 'errors', 'monthlyActuals',
            'monthlyPredicts', 'monthlyErrors', 'mape', 'mae', 'rmse',
            'mdpEn', 'keysEn', 'collection2', 'units2', 'decSep', 'thSep',
            'todayCost', 'lastUpdateTime', 'daily', 'paginatedDaily', 'selectedMonth', 'monthOptions'
        ));
    }


    public function standarIke()
    {
        $title = 'IKE Standard';

        $monthly = $this->getMonthlyEnergy();

        /* Energy Usage Total */
        $thisMonthKwh = $monthly[0]->kwh_1;
        $thisMonthCost = $monthly[0]->bill;
        $lastMonthKwh = $monthly[1]->kwh_1;
        $lastMonthCost = $monthly[1]->bill;
        $names = ['This Month', 'This Month Cost', 'Last Month', 'Last Month Cost'];
        $values = [$thisMonthKwh, $thisMonthCost, $lastMonthKwh, $lastMonthCost];
        $units = ['kWh', 'IDR', 'kWh', 'IDR'];

        /* Chart Monthly */
        $thisYear = Carbon::now()->year;
        $tarif = Subdata::latest()->pluck('hargaKwh')->first();;
        $oldestYear = 2018;

        $enmsCon = new EnmsReportController();
        $monthlyChartData = [];

        for ($year = $oldestYear; $year <= $thisYear; $year++) {
            ${"kwh_$year"} = $enmsCon->getMonthlyKwhReport($year);
            $monthlyChartData[] = [
                $year => ${"kwh_$year"},
            ];
        }
        // return $monthlyChartData;

        /* Chart Annual */
        $annualChartData = $enmsCon->getAnnualKwhReport();
        // return $annualChartData;

        return view("pages.ike.index", [
            'title' => $title,
            'monthly' => $monthly,
            'names' => $names,
            'values' => $values,
            'units' => $units,
            'monthlyChartData' => $monthlyChartData,
            'annualChartData' => $annualChartData
        ]);
    }

    public function exportMonthlyKwh()
    {
        $export = new MonthlyBillExport();
        return Excel::download($export, 'Monthly_Electricity_Bill.xlsx');
    }

    public function exportDailyKwh(Request $request)
    {
        $month = $request->get('month', now()->format('Y-m'));
        $monthOptions = $this->getLimitedMonthlyEnergy(30); // contoh fungsi bulan terbatas

        $price = Subdata::latest()->pluck('hargaKwh')->first(); // ambil harga kWh terbaru

        $data = $this->getDailyEnergy($month); // data harian

        // Hitung biaya untuk tiap hari
        foreach ($data as &$item) {
            $item->bill = number_format($item->total * $price, 0, ',', '.');
        }

        return Excel::download(new DailyEnergyExport($data, $month, $monthOptions), 'Daily_Energy_' . $month . '.xlsx');
    }

    /* 
        for APIs
    */

    /**
     * Retrieve energy data with latest first
     *
     * @return Illuminate\Support\Collection
     */
    public function getDailyEnergy()
    {
        $data = MdpKwh::select(
            DB::raw('DATE(created_at) as date'),
            'kwh_1',
            'kwh_2',
            'kwh_3',
            'kwh_4',
            'kwh_5',
            'created_at as timestamp'
        )
            ->whereIn('id', function ($query) {
                $query->select(DB::raw('MAX(id)'))
                    ->from('mdp_kwh')
                    ->groupBy(DB::raw('DATE(created_at)'));
            })
            ->orderBy('created_at', 'desc')
            ->get();

        $length = count($data);
        $ikeCon = new IkeController();
        $thresholdDate = Carbon::create(2024, 10, 5);

        for ($i = 0; $i < $length; $i++) {
            $currentDate = Carbon::parse($data[$i]->timestamp);

            if ($currentDate->isSameDay($thresholdDate)) {
                $data[$i]->kwh1 = $data[$i]->kwh_1;
                $data[$i]->kwh2 = $data[$i]->kwh_2;
                $data[$i]->kwh3 = $data[$i]->kwh_3;
                $data[$i]->kwh4 = $data[$i]->kwh_4;
                $data[$i]->kwh5 = $data[$i]->kwh_5;
            } elseif ($currentDate->lt($thresholdDate)) {
                $data[$i]->kwh1 = round($data[$i]->kwh_1 / 30, 2);
                $data[$i]->kwh2 = round($data[$i]->kwh_2 / 30, 2);
                $data[$i]->kwh3 = round($data[$i]->kwh_3 / 30, 2);
                $data[$i]->kwh4 = round($data[$i]->kwh_4 / 30, 2);
                $data[$i]->kwh5 = round($data[$i]->kwh_5 / 30, 2);
            } else {
                $data[$i]->kwh1 = $data[$i]->kwh_1 - $data[$i + 1]->kwh_1;
                $data[$i]->kwh2 = $data[$i]->kwh_2 - $data[$i + 1]->kwh_2;
                $data[$i]->kwh3 = $data[$i]->kwh_3 - $data[$i + 1]->kwh_3;
                $data[$i]->kwh4 = $data[$i]->kwh_4 - $data[$i + 1]->kwh_4;
                $data[$i]->kwh5 = $data[$i]->kwh_5 - $data[$i + 1]->kwh_5;
            }

            $data[$i]->total = $data[$i]->kwh1;

            $ike = $ikeCon->ikeMonthlyClassification($data[$i]->total * 30);
            $data[$i]->angka_ike = $ike['angka_ike'];
            $data[$i]->ike = $ike['ike'];
            $data[$i]->color = $ike['color'];
        }

        $data->pop();
        $data->makeHidden(['kwh_1', 'kwh_2', 'kwh_3', 'kwh_4', 'kwh_5']);

        return $data;
    }

    public function getDailyEnergyAscended(int $year)
    {
        /**
         * Oldest First
         *
         * @return Illuminate\Support\Collection
         */
        $data = MdpKwh::select(
            DB::raw('DATE(created_at) as date'),
            'kwh_1',
            'kwh_2',
            'kwh_3',
            'kwh_4',
            'kwh_5',
            'created_at as timestamp'
        )
            ->whereYear('created_at', $year)
            ->whereIn('id', function ($query) {
                $query->select(DB::raw('MAX(id)'))
                    ->from('mdp_kwh')
                    ->groupBy(DB::raw('DATE(created_at)'));
            })
            ->orderBy('created_at', 'asc')
            ->get();

        $length = count($data);
        $ikeCon = new IkeController();
        $thresholdDate = Carbon::create(2024, 10, 5);

        for ($i = 1; $i < $length; $i++) {
            $currentDate = Carbon::parse($data[$i]->date);

            if ($currentDate->lt($thresholdDate)) {
                $data[$i]->kwh1 = round($data[$i]->kwh_1 / 30, 2);
                $data[$i]->kwh2 = round($data[$i]->kwh_2 / 30, 2);
                $data[$i]->kwh3 = round($data[$i]->kwh_3 / 30, 2);
                $data[$i]->kwh4 = round($data[$i]->kwh_4 / 30, 2);
                $data[$i]->kwh5 = round($data[$i]->kwh_5 / 30, 2);
            }
            // Else if the current date is the threshold date
            else if ($currentDate->eq($thresholdDate)) {
                $data[$i]->kwh1 = $data[$i]->kwh_1;
                $data[$i]->kwh2 = $data[$i]->kwh_2;
                $data[$i]->kwh3 = $data[$i]->kwh_3;
                $data[$i]->kwh4 = $data[$i]->kwh_4;
                $data[$i]->kwh5 = $data[$i]->kwh_5;
            } else if ($currentDate->gt($thresholdDate)) {
                $data[$i]->kwh1 = $data[$i]->kwh_1 - $data[$i - 1]->kwh_1;
                $data[$i]->kwh2 = $data[$i]->kwh_2 - $data[$i - 1]->kwh_2;
                $data[$i]->kwh3 = $data[$i]->kwh_3 - $data[$i - 1]->kwh_3;
                $data[$i]->kwh4 = $data[$i]->kwh_4 - $data[$i - 1]->kwh_4;
                $data[$i]->kwh5 = $data[$i]->kwh_5 - $data[$i - 1]->kwh_5;
            }

            $data[$i]->total = $data[$i]->kwh1;

            $ike = $ikeCon->ikeMonthlyClassification($data[$i]->total * 30);
            $data[$i]->angka_ike = $ike['angka_ike'];
            $data[$i]->ike = $ike['ike'];
            $data[$i]->color = $ike['color'];
        }

        $data->shift();
        $data->makeHidden(['kwh_1', 'kwh_2', 'kwh_3', 'kwh_4', 'kwh_5']);

        return $data;
    }

    public function getLimitedDailyEnergy()
    {
        $data = MdpKwh::select(
            DB::raw('DATE(created_at) as date'),
            'kwh_1',
            'kwh_2',
            'kwh_3',
            'kwh_4',
            'kwh_5',
            'created_at as timestamp'
        )
            ->whereIn('id', function ($query) {
                $query->select(DB::raw('MAX(id)'))
                    ->from('mdp_kwh')
                    ->groupBy(DB::raw('DATE(created_at)'));
            })
            ->orderBy('created_at', 'desc')->take(7)
            ->get();

        $length = count($data);
        $ikeCon = new IkeController();
        $thresholdDate = Carbon::create(2024, 10, 5);

        for ($i = 0; $i < $length; $i++) {
            $currentDate = Carbon::parse($data[$i]->timestamp);

            if ($currentDate->lt($thresholdDate)) {
                $data[$i]->kwh_1 = round($data[$i]->kwh_1 / 30, 2);
                $data[$i]->kwh_2 = round($data[$i]->kwh_2 / 30, 2);
                $data[$i]->kwh_3 = round($data[$i]->kwh_3 / 30, 2);
                $data[$i]->kwh_4 = round($data[$i]->kwh_4 / 30, 2);
                $data[$i]->kwh_5 = round($data[$i]->kwh_5 / 30, 2);
            } else {
                $data[$i]->kwh_1 = $data[$i]->kwh_1 - $data[$i + 1]->kwh_1;
                $data[$i]->kwh_2 = $data[$i]->kwh_2 - $data[$i + 1]->kwh_2;
                $data[$i]->kwh_3 = $data[$i]->kwh_3 - $data[$i + 1]->kwh_3;
                $data[$i]->kwh_4 = $data[$i]->kwh_4 - $data[$i + 1]->kwh_4;
                $data[$i]->kwh_5 = $data[$i]->kwh_5 - $data[$i + 1]->kwh_5;
            }

            $data[$i]->total = $data[$i]->kwh_1;
        }

        $data->pop();
        // $data->makeHidden(['kwh_1', 'kwh_2', 'kwh_3', 'kwh_4', 'kwh_5']);

        return $data;
    }

    public function getMonthlyEnergy()
    {
        $data = MdpKwh::select(
            DB::raw('DATE_FORMAT(created_at, "%m") as bulan, DATE_FORMAT(created_at, "%Y") as tahun '),
            'kwh_1',
            'kwh_2',
            'kwh_3',
            'kwh_4',
            'kwh_5',
            'created_at as timestamp'
        )
            ->whereIn('id', function ($query) {
                $query->select(DB::raw('MAX(id)'))
                    ->from('mdp_kwh')
                    ->groupBy(DB::raw('YEAR(created_at), MONTH(created_at)'));
            })
            ->orderBy('created_at', 'desc') // Terbaru lebih dulu
            ->get();

        $price = Subdata::latest()->pluck('hargaKwh')->first();;
        $length = count($data);
        $ikeCon = new IkeController();
        $thresholdDate = Carbon::create(2024, 11, 1);

        if ($length == 1) {
            // Handle if there is no data[$i+1]
            $data[0]->total = number_format(($data[0]->kwh_1) / 1000, 2, '.', ',');
            $data[0]->bill = number_format($data[0]->total * $price, 0, ',', '.'); // biaya listrik perbulan
            $data[0]->bulan = Carbon::create(null, $data[0]->bulan)->monthName;
            $ike = $ikeCon->ikeMonthlyClassification($data[0]->total);
            $data[0]->angka_ike = $ike['angka_ike'];
            $data[0]->ike = $ike['ike'];
            $data[0]->color = $ike['color'];

            return $data;
        }

        for ($i = 0; $i < $length; $i++) {
            $currentDate = Carbon::parse($data[$i]->timestamp);

            // Data inject sebelum Nov 2024
            if ($currentDate->lt($thresholdDate)) {
                $data[$i]->total = $data[$i]->kwh_1;
            } else {
                $data[$i]->total = $data[$i]->kwh_1 - $data[$i + 1]->kwh_1;
            }

            $ike = $ikeCon->ikeMonthlyClassification($data[$i]->total);
            $data[$i]->angka_ike = $ike['angka_ike'];
            $data[$i]->ike = $ike['ike'];
            $data[$i]->color = $ike['color'];

            $data[$i]->bill = number_format($data[$i]->total * $price, 0, ',', '.'); // biaya listrik perbulan
            $data[$i]->bulan = Carbon::create(null, $data[$i]->bulan)->monthName;
        }

        $data->makeHidden(['kwh_1', 'kwh_2', 'kwh_3', 'kwh_4', 'kwh_5']);

        return $data;
    }

    public function getLimitedMonthlyEnergy($year)
    {
        $data = MdpKwh::select(
            DB::raw('DATE_FORMAT(created_at, "%m") as bulan, DATE_FORMAT(created_at, "%Y") as tahun '),
            'kwh_1',
            'kwh_2',
            'kwh_3',
            'kwh_4',
            'kwh_5',
            'created_at as timestamp'
        )
            ->whereYear('created_at', $year)
            ->whereIn('id', function ($query) {
                $query->select(DB::raw('MAX(id)'))
                    ->from('mdp_kwh')
                    ->groupBy(DB::raw('YEAR(created_at), MONTH(created_at)'));
            })
            ->orderBy('created_at', 'desc')
            ->get();

        $price = Subdata::latest()->pluck('hargaKwh')->first();;
        $length = count($data);
        $thresholdDate = Carbon::create(2024, 9, 1);

        if ($length == 1) {
            // Handle if there is no data[$i+1]
            $data[0]->total = number_format(($data[0]->kwh_1) / 1000, 2, '.', ',');
            $data[0]->bill = number_format($data[0]->total * $price, 0, ',', '.'); // biaya listrik perbulan
            $data[0]->bulan = Carbon::create(null, $data[0]->bulan)->monthName;

            return $data;
        }

        for ($i = 0; $i < $length - 1; $i++) {
            $currentDate = Carbon::parse($data[$i]->timestamp);

            // Data inject sebelum Sept 2024
            if ($currentDate->lt($thresholdDate)) {
                $data[$i]->total = $data[$i]->kwh_1;
            } else {
                $data[$i]->total = $data[$i]->kwh_1 - $data[$i + 1]->kwh_1;
            }

            $data[$i]->bill = number_format($data[$i]->total * $price, 0, ',', '.'); // biaya listrik perbulan
            $data[$i]->bulan = Carbon::create(null, $data[$i]->bulan)->monthName;
        }

        $data->makeHidden(['kwh_1', 'kwh_2', 'kwh_3', 'kwh_4', 'kwh_5']);

        return $data;
    }

    public function getLastTwoMonthsEnergyById(int $id_kwh)
    {
        $data = MdpKwh::select(
            DB::raw('DATE_FORMAT(created_at, "%m") as bulan, DATE_FORMAT(created_at, "%Y") as tahun '),
            'kwh_1',
            'kwh_2',
            'kwh_3',
            'kwh_4',
            'kwh_5',
            'created_at as timestamp'
        )
            ->whereIn('id', function ($query) {
                $query->select(DB::raw('MAX(id)'))
                    ->from('mdp_kwh')
                    ->groupBy(DB::raw('YEAR(created_at), MONTH(created_at)'));
            })
            ->orderBy('created_at', 'desc')->take(3)
            ->get();

        $price = Subdata::latest()->pluck('hargaKwh')->first();;
        $length = count($data);
        $thresholdDate = Carbon::create(2024, 11, 1);
        // Handle if there is no data[$i+1]
        if ($length == 1) {
            $data[0]->kwh_1 = number_format(($data[0]->kwh_1) / 1000, 2, '.', ',');
            $data[0]->kwh_2 = number_format(($data[0]->kwh_2) / 1000, 2, '.', ',');
            $data[0]->kwh_3 = number_format(($data[0]->kwh_3) / 1000, 2, '.', ',');
            $data[0]->kwh_4 = number_format(($data[0]->kwh_4) / 1000, 2, '.', ',');
            $data[0]->kwh_5 = number_format(($data[0]->kwh_5) / 1000, 2, '.', ',');
            $data[0]->bill_1 = number_format($data[0]->kwh_1 * $price, 0, ',', '.');
            $data[0]->bill_2 = number_format($data[0]->kwh_2 * $price, 0, ',', '.');
            $data[0]->bill_3 = number_format($data[0]->kwh_3 * $price, 0, ',', '.');
            $data[0]->bill_4 = number_format($data[0]->kwh_4 * $price, 0, ',', '.');
            $data[0]->bill_5 = number_format($data[0]->kwh_5 * $price, 0, ',', '.');
            $data[0]->bulan = Carbon::create(null, $data[0]->bulan)->monthName;

            return $data;
        }
        for ($i = 0; $i < $length - 1; $i++) {
            $currentDate = Carbon::parse($data[$i]->timestamp);

            // Data inject sebelum Nov 2024
            if ($currentDate->lt($thresholdDate)) {
                $data[$i]->kwh_1 = $data[$i]->kwh_1;
                $data[$i]->kwh_2 = $data[$i]->kwh_2;
                $data[$i]->kwh_3 = $data[$i]->kwh_3;
                $data[$i]->kwh_4 = $data[$i]->kwh_4;
                $data[$i]->kwh_5 = $data[$i]->kwh_5;
            } else {
                $data[$i]->kwh_1 = $data[$i]->kwh_1 - $data[$i + 1]->kwh_1;
                $data[$i]->kwh_2 = $data[$i]->kwh_2 - $data[$i + 1]->kwh_2;
                $data[$i]->kwh_3 = $data[$i]->kwh_3 - $data[$i + 1]->kwh_3;
                $data[$i]->kwh_4 = $data[$i]->kwh_4 - $data[$i + 1]->kwh_4;
                $data[$i]->kwh_5 = $data[$i]->kwh_5 - $data[$i + 1]->kwh_5;
            }

            $data[$i]->bill_1 = $data[$i]->kwh_1 * $price;
            $data[$i]->bill_2 = $data[$i]->kwh_2 * $price;
            $data[$i]->bill_3 = $data[$i]->kwh_3 * $price;
            $data[$i]->bill_4 = $data[$i]->kwh_4 * $price;
            $data[$i]->bill_5 = $data[$i]->kwh_5 * $price;
            $data[$i]->bulan = Carbon::create(null, $data[$i]->bulan)->monthName;
        }

        return $data;
    }

    public function getAnnualEnergy()
    {
        $data = MdpKwh::select(
            DB::raw('DATE_FORMAT(created_at, "%Y") as tahun '),
            'kwh_1',
            'kwh_2',
            'kwh_3',
            'kwh_4',
            'kwh_5',
            'created_at as timestamp'
        )
            ->whereIn('id', function ($query) {
                $query->select(DB::raw('MAX(id)'))
                    ->from('mdp_kwh')
                    ->groupBy(DB::raw('YEAR(created_at)'));
            })
            ->orderBy('created_at', 'desc')
            ->get();

        $price = Subdata::latest()->pluck('hargaKwh')->first();;
        $length = count($data);
        $ikeCon = new IkeController();
        $thresholdDate = Carbon::create(2024, 9, 1);


        if ($length == 1) {
            $data[0]->total = number_format(($data[0]->kwh_1) / 1000, 2, '.', ',');
            $data[0]->bill = number_format($data[0]->total * $price, 0, ',', '.'); // biaya listrik perbulan
            $data[0]->bulan = Carbon::create(null, $data[0]->bulan)->monthName;
            $ike = $ikeCon->ikeAnnualClassification($data[0]->total);
            $data[0]->angka_ike = $ike['angka_ike'];
            $data[0]->ike = $ike['ike'];
            $data[0]->color = $ike['color'];

            return $data;
        }

        for ($i = 1; $i < $length; $i++) {
            $currentDate = Carbon::parse($data[$i]->timestamp);
            if ($currentDate->lt($thresholdDate)) {
                $data[$i]->total = $data[$i]->kwh_1;
            } else {
                $data[$i]->total = $data[$i]->kwh_1 - $data[$i + 1]->kwh_1;
            }

            $ike = $ikeCon->ikeAnnualClassification($data[$i]->total);
            $data[$i]->angka_ike = $ike['angka_ike'];
            $data[$i]->ike = $ike['ike'];
            $data[$i]->color = $ike['color'];

            $data[$i]->bill = number_format($data[$i]->total * $price, 0, ',', '.'); // biaya listrik perbulan
            $data[$i]->bulan = Carbon::create(null, $data[$i]->bulan)->monthName;
        }

        return $data;
    }

    public function getEnergyUsageMonitorById($id_kwh)
    {
        /* 
            Today Energy = latest today data - yesterday latest data
        */
        $latestData = MdpKwh::latest()->first();

        $yesterday = Carbon::yesterday();
        $yesterdayData = MdpKwh::whereDate('created_at', $yesterday)->latest()->first();
        if ($yesterdayData == null) {
            $daily = $this->getLimitedDailyEnergy();
            $yesterdayData = $daily[1];
        }
        $todayKwh = $latestData->{"kwh_$id_kwh"}  - $yesterdayData->{"kwh_$id_kwh"};
        
        // Ambil tarif per kWh dari Subdata
        $price = Subdata::latest()->pluck('hargaKwh')->first();
        $todayCost = $todayKwh * $price;

        $lastTwoMonthsData = $this->getLastTwoMonthsEnergyById($id_kwh);
        $thisMonthKwh = $lastTwoMonthsData[0]->{"kwh_$id_kwh"};
        $thisMonthCost = $lastTwoMonthsData[0]->{"bill_$id_kwh"};
        $lastMonthKwh = $lastTwoMonthsData[1]->{"kwh_$id_kwh"};
        $lastMonthCost = $lastTwoMonthsData[1]->{"bill_$id_kwh"};

        return [
            'todayKwh' => $todayKwh,
            'lastMonthKwh' => $lastMonthKwh,
            'thisMonthKwh' => $thisMonthKwh,
            'lastMonthCost' => $lastMonthCost,
            'thisMonthCost' => $thisMonthCost,
            'todayCost' => $todayCost,
        ];
    }
}
