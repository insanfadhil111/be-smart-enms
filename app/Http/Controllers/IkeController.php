<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\MdpKwh;
use App\Models\Subdata;
use App\Models\EnergyCost;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Http\Controllers\EnmsReportController;

class IkeController extends Controller
{
    public function index()
    {
        $title = 'IKE Standard';

        /* Monthly Data */
        $thisYear = Carbon::now()->year;
        $oldestYear = 2018;

        $enmsCon = new EnmsReportController();
        $monthlyChartData = [];

        for ($year = $oldestYear; $year <= $thisYear; $year++) {
            ${"kwh_$year"} = $enmsCon->getMonthlyKwhReport($year);
            $monthlyChartData[] = [
                $year => ${"kwh_$year"},
            ];
        }

        /* Energy Usage Total */
        $thisMonthKwh = ${"kwh_$thisYear"}[count(${"kwh_$thisYear"}) - 1]->kwh;
        $thisMonthCost = ${"kwh_$thisYear"}[count(${"kwh_$thisYear"}) - 1]->cost;
        $lastMonthKwh = ${"kwh_$thisYear"}[count(${"kwh_$thisYear"}) - 2]->kwh;
        $lastMonthCost = ${"kwh_$thisYear"}[count(${"kwh_$thisYear"}) - 2]->cost;
        $names = ['This Month', 'This Month Cost', 'Last Month', 'Last Month Cost'];
        $values = [$thisMonthKwh, $thisMonthCost, $lastMonthKwh, $lastMonthCost];
        $units = ['kWh', 'IDR', 'kWh', 'IDR'];

        /* Chart Monthly */
        // return $monthlyChartData;

        /* Chart Annual */
        $annualChartData = $enmsCon->getAnnualKwhReport();
        // return $annualChartData;

        $decSep = Subdata::first()->decimal_sep;
        $thSep = Subdata::first()->thousand_sep;

        return view("pages.ike.index", [
            'title' => $title,
            // 'monthly' => $monthly,
            'names' => $names,
            'values' => $values,
            'units' => $units,
            'monthlyChartData' => $monthlyChartData,
            'annualChartData' => $annualChartData,
            'decSep' => $decSep,
            'thSep' => $thSep,
        ]);
    }

    public function getLastTwoMonthKwh()
    {
        $data = MdpKwh::select(
            DB::raw('DATE_FORMAT(created_at, "%m") as bulan, DATE_FORMAT(created_at, "%Y") as tahun '),
            'kwh_1',
            'created_at as timestamp'
        )
            ->whereIn('id', function ($query) {
                $query->select(DB::raw('MAX(id)'))
                    ->from('mdp_kwh')
                    ->groupBy(DB::raw('YEAR(created_at), MONTH(created_at)'));
            })
            ->orderBy('created_at', 'desc')
            ->take(3)->get();

        $price = EnergyCost::latest()->first()->pokok;

        for ($i = 0; $i < count($data) - 1; $i++) {
            $parsedMonth = Carbon::parse($data[$i]->timestamp)->format('m-Y');

            if ($parsedMonth < '09-2024') {
                $data[$i]->kwh = $data[$i]->kwh_1;
            } else {
                $data[$i]->kwh = $data[$i]->kwh_1 - $data[$i + 1]->kwh_1;
            }

            $ike = $this->ikeMonthlyClassification($data[$i]->kwh);

            $data[$i]->angka_ike = $ike['angka_ike'];
            $data[$i]->ike = $ike['ike'];
            $data[$i]->color = $ike['color'];
            $data[$i]->bill = number_format($data[$i]->kwh_1 * $price, 0, ',', '.');
        }
        $data->makeHidden(['kwh_1']);
        $data->pop();

        return $data;
    }

    public function ikeMonthlyClassification($kwh)
    {
        $luasArea = 2400;

        $angka_ike = round($kwh / $luasArea, 2);
        switch ($angka_ike) {
            case $angka_ike <= 7.92:
                $ike = 'Sangat Efisien';
                $color = '#00ff00';
                break;
            case $angka_ike > 7.92 && $angka_ike <= 12.08:
                $ike = 'Efisien';
                $color = '#009900';
                break;
            case $angka_ike > 12.08 && $angka_ike <= 14.58:
                $ike = 'Cukup Efisien';
                $color = '#ffff00';
                break;
            case $angka_ike > 14.58 && $angka_ike <= 19.17:
                $ike = 'Agak Boros';
                $color = '#ff9900';
                break;
            case $angka_ike > 19.17 && $angka_ike <= 23.75:
                $ike = 'Boros';
                $color = '#ff3300';
                break;
            default:
                $ike = 'Sangat Boros';
                $color = '#800000';
                break;
        }

        return [
            'angka_ike' => $angka_ike,
            'ike' => $ike,
            'color' => $color,
        ];
    }

    public function ikeAnnualClassification($kwh)
    {
        $luasArea = 2400;

        $angka_ike = round($kwh / $luasArea, 2);
        switch ($angka_ike) {
            case $angka_ike <= 95:
                $ike = 'Sangat Efisien';
                $color = '#00ff00';
                break;
            case $angka_ike > 95 && $angka_ike <= 145:
                $ike = 'Efisien';
                $color = '#009900';
                break;
            case $angka_ike > 145 && $angka_ike <= 175:
                $ike = 'Cukup Efisien';
                $color = '#ffff00';
                break;
            case $angka_ike > 175 && $angka_ike <= 285:
                $ike = 'Agak Boros';
                $color = '#ff9900';
                break;
            case $angka_ike > 285 && $angka_ike <= 450:
                $ike = 'Boros';
                $color = '#ff3300';
                break;
            default:
                $ike = 'Sangat Boros';
                $color = '#800000';
                break;
        }

        return [
            'angka_ike' => $angka_ike,
            'ike' => $ike,
            'color' => $color,
        ];
    }
}
