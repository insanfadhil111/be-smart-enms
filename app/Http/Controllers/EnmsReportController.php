<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\MdpKwh;
use App\Models\Subdata;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Http\Controllers\IkeController;
use App\Http\Controllers\WaterController;

class EnmsReportController extends Controller
{
    public function index()
    {
        $title = 'EnMS Report';

        $thisYear = Carbon::now()->year;
        $tarif = Subdata::latest()->pluck('hargaKwh')->first();
        $oldestYear = 2018;

        $thisYearKwh = MdpKwh::whereYear('created_at', $thisYear)->orderByDesc('updated_at')->first()->kwh_1 / 1000 ?? 0;

        /* 
            NOTE!
            Gonna need optimization for this
        */
        $donutChartData = [];
        $chartData = [];

        $wCon = new WaterController();

        for ($year = $oldestYear; $year <= $thisYear; $year++) {
            ${"kwh_$year"} = $this->getMonthlyKwhReport($year);

            $elecData = json_decode(${"kwh_$year"}, true);
            $co2eq = Subdata::latest()->pluck('co2eq')->first();
            $elecCost = round(collect($elecData)->sum('cost'), 0);  // Use collect() to make it work with sum()
            $elecKwh = round(collect($elecData)->sum('kwh'), 2);
            $elecToCo2eq = round($elecKwh * $co2eq / 1000, 2);

            // WaterData
            $waterData = $wCon->getThisYearWaterUsage($thisYear);
            $waterUsage = $waterData["annualVol"];
            $waterCost = $waterData["annualCost"];
            $waterCarbon = round($waterData["co2eq"] / 1000, 2); //in tonnes
            $waterKwh = $waterData["kwhAir"];

            $chartData[] = [
                $year => ${"kwh_$year"},
            ];
            $donutChartData[] = [
                $year => [
                    'cost' => [
                        'total' => $elecCost,
                        'breakdown' => [
                            'electricity' => $elecCost,
                            'water' => $waterCost,
                        ],
                        'projected' => $elecCost + $waterCost,
                        'increase' => 10,
                    ],
                    'consumption' => [
                        'total' => $elecKwh,
                        'breakdown' => [
                            'electricity' => $elecKwh,
                            'water' => $waterUsage,
                        ],
                        'projected' => $elecKwh + $waterUsage,
                        'increase' => 10,
                    ],
                    'carbon' => [
                        'total' => $elecToCo2eq,
                        'breakdown' => [
                            'electricity' => $elecToCo2eq,
                            'water' => $waterCarbon,
                        ],
                        'projected' => round($elecToCo2eq + $waterCarbon, 2),
                        'increase' => 10,
                    ],
                ]
            ];
        }

        return view('pages.enms_report.index', [
            'title' => $title,
            'thisYear' => $thisYear,
            'tarif' => $tarif,
            'oldestYear' => $oldestYear,
            'thisYearKwh' => $thisYearKwh,
            'donutChartData' => $donutChartData,
            'chartData' => $chartData,
        ]);
    }

    public function getMonthlyKwhReport($year)
    {
        $data = MdpKwh::select(
            DB::raw('DATE_FORMAT(created_at, "%Y") as tahun'),
            'kwh_1',
            'created_at as timestamp'
        )
            ->whereYear('created_at', $year)
            ->whereIn('id', function ($query) {
                $query->select(DB::raw('MAX(id)'))
                    ->from('mdp_kwh')
                    ->groupBy(DB::raw('YEAR(created_at), MONTH(created_at)'));
            })
            ->orderBy('created_at', 'asc') // Terlama lebih dahulu
            ->get();

        $length = count($data);
        $thresholdDate = Carbon::create(2024, 11, 1);
        $tarif = Subdata::latest()->pluck('hargaKwh')->first();
        $ikeCon = new IkeController();

        // Jika baru ada 1 data di bulan ini, maka kurangi dengan data tahun sebelumnya jika ada
        if ($length == 1) {
            $lastYearData = MdpKwh::select('kwh_1')
                ->whereYear('created_at', $year - 1)
                ->orderBy('created_at', 'desc')
                ->first();
            if ($lastYearData) {
                $data[0]->kwh = $data[0]->kwh_1 - $lastYearData->kwh_1;
            } else {
                $data[0]->kwh = $data[0]->kwh_1;
            }
            $ike = $ikeCon->ikeMonthlyClassification($data[0]->kwh);
            $data[0]->angka_ike = $ike['angka_ike'];
            $data[0]->ike = $ike['ike'];
            $data[0]->ike_color = $ike['color'];
            $data[0]->cost = $data[0]->kwh * $tarif;
            $data[0]->bulan = Carbon::parse($data[0]->timestamp)->format('M');

            return $data;
        }

        for ($i = 1; $i < $length; $i++) {
            $currentDate = Carbon::parse($data[$i]->timestamp);

            if ($currentDate->lt($thresholdDate)) {
                // Data before November 2024 (non-incremental)
                $data[$i]->kwh = $data[$i]->kwh_1;
            } else {
                // Data from November 2024 onward (incremental)
                $data[$i]->kwh = $data[$i]->kwh_1 - $data[$i - 1]->kwh_1;
            }

            $ike = $ikeCon->ikeMonthlyClassification($data[$i]->kwh);
            $data[$i]->angka_ike = $ike['angka_ike'];
            $data[$i]->ike = $ike['ike'];
            $data[$i]->ike_color = $ike['color'];
            $data[$i]->cost = $data[$i]->kwh * $tarif;
            $data[$i]->bulan = $currentDate->format('M');
        }
        // Data Januari = Kwh Januari - Kwh Desember tahun sebelumnya (Khusus di atas tahun 2024)
        if ($year > 2024) {
            $lastYearData = MdpKwh::select('kwh_1')
                ->whereYear('created_at', $year - 1)
                ->whereMonth('created_at', 12)
                ->orderBy('created_at', 'desc')
                ->first();
            if ($lastYearData) {
                $data[0]->kwh = $data[0]->kwh_1 - $lastYearData->kwh_1;
            } else {
                $data[0]->kwh = $data[0]->kwh_1;
            }
        } else {
            $data[0]->kwh = $data[0]->kwh_1;
        }
        $ike = $ikeCon->ikeMonthlyClassification($data[0]->kwh);
        $data[0]->angka_ike = $ike['angka_ike'];
        $data[0]->ike = $ike['ike'];
        $data[0]->ike_color = $ike['color'];
        $data[0]->cost = $data[0]->kwh * $tarif;
        $data[0]->bulan = Carbon::parse($data[0]->timestamp)->format('M');


        $data->makeHidden(['kwh_1']);
        return $data;
    }

    public function getAnnualKwhReport()
    {
        $oldestYear = 2018;
        $thisYear = Carbon::now()->year;
        $annualData = [];
        $ikeCon = new IkeController();

        for ($year = $oldestYear; $year <= $thisYear; $year++) {
            ${"kwh_$year"} = $this->getMonthlyKwhReport($year);
            $consumption = ${"kwh_$year"}->sum('kwh_1');
            $cost = ${"kwh_$year"}->sum('cost');
            $ike = $ikeCon->ikeAnnualClassification($consumption);

            $annualData[] = [
                'year' => $year,
                'consumption' => $consumption,
                'cost' => $cost,
                'ike' => $ike['ike'],
                'ike_color' => $ike['color'],
            ];
        }
        return $annualData;
    }

    public function getDonutChartData($year)
    {
        $data = $this->getMonthlyKwhReport($year);
        $data = json_decode($data, true);

        $co2eq = Subdata::latest()->pluck('co2eq')->first();

        $cost = collect($data)->sum('cost');  // Use collect() to make it work with sum()
        $consumption = collect($data)->sum('kwh');
        $carbon = $consumption * $co2eq;

        $data = [
            'cost' => [
                'total' => $cost,
                'breakdown' => [
                    'electricity' => $cost,
                    'water' => 0,
                ],
                'projected' => $cost + 150000,
                'increase' => 10,
            ],
            'consumption' => [
                'total' => $consumption,
                'breakdown' => [
                    'electricity' => $consumption,
                    'water' => 0,
                ],
                'projected' => $consumption + 1000,
                'increase' => 10,
            ],
            'carbon' => [
                'total' => $carbon,
                'breakdown' => [
                    'electricity' => $carbon,
                    'water' => 0,
                ],
                'projected' => $carbon + 1000,
                'increase' => 10,
            ],
        ];
        return $data;
    }
}
