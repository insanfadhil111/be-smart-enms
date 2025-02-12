<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Water;
use App\Models\MdpKwh;
use App\Models\MdpControl;
use App\Http\Controllers\Controller;
use App\Http\Controllers\PvController;
use App\Http\Controllers\MdpController;

class HomeController extends Controller
{
    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $subCon = new SubdataController();
        /* Real Time Data */
        $MdpCon = new MdpController();
        $todayKwh = $MdpCon->totalMdpKwhToday();
        $PvCon = new PvController();
        $todayMpp = $PvCon->mppTodayGeneration()['totalEnergy'];
        $todayMppGenerated = $subCon->formatNumber($todayMpp, 2);
        $todayGw = $PvCon->gwTodayGeneration()['totalEnergy'];
        $todayGwGenerated = $subCon->formatNumber($todayGw, 2);

        $todayPv = $todayMpp + $todayGw;
        $todayIncome = $subCon->formatNumber($todayPv * 1440, 0);

        $waterCon = new WaterController();
        $data = $waterCon->getTodayWater(119);
        $data2 = $waterCon->getTodayWater(148);
        $todayWater = $data->todayVol + $data2->todayVol;
        if ($data->message && $data2->message) {
            $msgWater = "There is no data today for all devices";
        } else {
            $msgWater = $data->message . $data2->message;
        }

        /* Grafik Monthly */
        $thisYear = Carbon::now()->year;
        $enCon = new EnmsReportController();
        // Menampilkan kwh per bulan tahun ini
        $thisYearKwh = $enCon->getMonthlyKwhReport($thisYear);
        $monthlyKwh = [];
        $months = [];
        foreach ($thisYearKwh as $item) {
            array_push($monthlyKwh, $item->kwh);
            $f_month = Carbon::parse($item->timestamp)->format('M');
            array_push($months, $f_month);
        }

        /* Device Status */
        $items = MdpControl::oldest()->get();


        return view('pages.dashboard', compact('todayKwh', 'todayPv', 'todayIncome', 'todayWater', 'items', 'monthlyKwh', 'months'));
    }

    public function debugFunc()
    {
        $startDate = Carbon::now()->subDays(7)->toDateString();
        $endDate = Carbon::now()->toDateString();

        $dataTimur = Water::select(
            'volume',
            'created_at as timestamp'
        )
            ->where('id_dev', 119)
            ->whereBetween('created_at', [$startDate, $endDate])
            ->orderBy('created_at', 'asc')  // Terlama lebih dulu
            ->get();

        $dataBarat = Water::select(
            'volume',
            'created_at as timestamp'
        )
            ->where('id_dev', 148)
            ->whereBetween('created_at', [$startDate, $endDate])
            ->orderBy('created_at', 'asc')  // Terlama lebih dulu
            ->get();

        $lenTimur = count($dataTimur);
        $lenBarat = count($dataBarat);

        if ($lenTimur > 0) {
            for ($i = 1; $i < $lenTimur; $i++) {
                $dataTimur[$i]->nowVol = $dataTimur[$i]->volume - $dataTimur[$i - 1]->volume;
            }

            $latestDataBeforeLastweek = Water::select('volume')
                ->where('id_dev', 119)
                ->whereDate('created_at', '=', Carbon::parse($startDate)->subDay())
                ->latest()->first();
            if ($latestDataBeforeLastweek === null) {
                // $latestDataBeforeLastweek = (object)['volume' => 0];
                $latestDataBeforeLastweek = Water::select('volume')
                    ->where('id_dev', 119)
                    ->where('created_at', '<', $dataTimur[0]->timestamp)
                    ->latest()->first();
            }

            $dataTimur[0]->nowVol = $dataTimur[0]->volume - $latestDataBeforeLastweek->volume;

            // If Anomali (karena data di DB loncat karen logger mati selama lebih dari seminggu)
            if ($dataTimur[0]->nowVol > 10) {
                $dataTimur[0]->nowVol = $dataTimur[1]->nowVol;
            }
        }
        if ($lenBarat > 0) {
            for ($i = 1; $i < $lenBarat; $i++) {
                $dataBarat[$i]->nowVol = $dataBarat[$i]->volume - $dataBarat[$i - 1]->volume;
            }

            $latestDataBeforeLastweek = Water::select('volume')
                ->where('id_dev', 148)
                ->whereDate('created_at', '=', Carbon::parse($startDate)->subDay())
                ->latest()->first();
            if ($latestDataBeforeLastweek === null) {
                // $latestDataBeforeLastweek = (object)['volume' => 0];
                Water::select('volume')
                    ->where('id_dev', 148)
                    ->where('created_at', '<', $dataBarat[0]->timestamp)
                    ->latest()->first();
            }

            $dataBarat[0]->nowVol = $dataBarat[0]->volume - $latestDataBeforeLastweek->volume;
        }
        return $dataTimur;
    }
}
