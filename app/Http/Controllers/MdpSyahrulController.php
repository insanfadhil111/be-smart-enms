<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\MdpSyahrul;
use Illuminate\Http\Request;
use App\Models\MdpUtil1Syahrul;
use App\Models\MdpUtil2Syahrul;
use App\Http\Controllers\Controller;
use App\Models\EnergyCost;

class MdpSyahrulController extends Controller
{
    public function getMdpSyahrul()
    {
        $allData = MdpSyahrul::orderBy('id', 'desc')->take(100)->get();

        // kalau data yang direturn collection/array, maka jadi JSON
        return $allData;

        // data apapun yang direturn bakal jadi json (lebih konsisten)
        // return response()->json($allData);
    }

    public function getNowMdpSyahrul()
    {
        $data = MdpSyahrul::orderBy('id', 'desc')->first();
        if ($data) {
            $data = $data->toArray(); // Convert the Eloquent model to an array
            // Rename keys
            $data['Van'] = $data['data_Ua1'];
            unset($data['data_Ua1']); // Remove the old key if necessary
            $data['Vbn'] = $data['data_Ub1'];
            unset($data['data_Ub1']);
            $data['Vcn'] = $data['data_Uc1'];
            unset($data['data_Uc1']);
            $data['Ia'] = $data['data_Ia1'];
            unset($data['data_Ia1']);
            $data['Ib'] = $data['data_Ib1'];
            unset($data['data_Ib1']);
            $data['Ic'] = $data['data_Ic1'];
            unset($data['data_Ic1']);
            $data['It'] = $data['data_It1'];
            unset($data['data_It1']);
            $data['Pa'] = $data['data_Pa1'];
            unset($data['data_Pa1']);
            $data['Pb'] = $data['data_Pb1'];
            unset($data['data_Pb1']);
            $data['Pc'] = $data['data_Pc1'];
            unset($data['data_Pc1']);
            $data['Pt'] = $data['data_Pt1'];
            unset($data['data_Pt1']);
            $data['Qa'] = $data['data_Qa1'];
            unset($data['data_Qa1']);
            $data['Qb'] = $data['data_Qb1'];
            unset($data['data_Qb1']);
            $data['Qc'] = $data['data_Qc1'];
            unset($data['data_Qc1']);
            $data['Qt'] = $data['data_Qt1'];
            unset($data['data_Qt1']);
            $data['pf'] = $data['data_Pft1'];
            unset($data['data_Pft1']);
            $data['f'] = $data['data_Hz1'];
            unset($data['data_Hz1']);

            unset($data['data_Sa1']);
            unset($data['data_Sb1']);
            unset($data['data_Sc1']);
            unset($data['data_St1']);
            unset($data['data_Pfa1']);
            unset($data['data_Pfb1']);
            unset($data['data_Pfc1']);
            // unset($data['data_pkWh1']);
            unset($data['data_nkWh1']);
            unset($data['data_pkVarh1']);
            unset($data['data_nkVarh1']);
        }

        return $data;
    }

    public function getNowUtil1Syahrul()
    {
        $data = MdpUtil1Syahrul::class::orderBy('id', 'desc')->first();
        if ($data) {
            $data = $data->toArray();
            $data['Van'] = $data['data_Ua2'];
            unset($data['data_Ua2']); // Remove the old key if necessary
            $data['Vbn'] = $data['data_Ub2'];
            unset($data['data_Ub2']);
            $data['Vcn'] = $data['data_Uc2'];
            unset($data['data_Uc2']);
            $data['Ia'] = $data['data_Ia2'];
            unset($data['data_Ia2']);
            $data['Ib'] = $data['data_Ib2'];
            unset($data['data_Ib2']);
            $data['Ic'] = $data['data_Ic2'];
            unset($data['data_Ic2']);
            $data['It'] = $data['data_It2'];
            unset($data['data_It2']);
            $data['Pa'] = $data['data_Pa2'];
            unset($data['data_Pa2']);
            $data['Pb'] = $data['data_Pb2'];
            unset($data['data_Pb2']);
            $data['Pc'] = $data['data_Pc2'];
            unset($data['data_Pc2']);
            $data['Pt'] = $data['data_Pt2'];
            unset($data['data_Pt2']);
            $data['Qa'] = $data['data_Qa2'];
            unset($data['data_Qa2']);
            $data['Qb'] = $data['data_Qb2'];
            unset($data['data_Qb2']);
            $data['Qc'] = $data['data_Qc2'];
            unset($data['data_Qc2']);
            $data['Qt'] = $data['data_Qt2'];
            unset($data['data_Qt2']);
            $data['pf'] = $data['data_Pft2'];
            unset($data['data_Pft2']);
            $data['f'] = $data['data_Hz2'];
            unset($data['data_Hz2']);

            unset($data['data_Sa2']);
            unset($data['data_Sb2']);
            unset($data['data_Sc2']);
            unset($data['data_St2']);
            unset($data['data_Pfa2']);
            unset($data['data_Pfb2']);
            unset($data['data_Pfc2']);
            // unset($data['data_pkWh2']);
            unset($data['data_nkWh2']);
            unset($data['data_pkVarh2']);
            unset($data['data_nkVarh2']);
        }
        return $data;
    }

    public function getNowUtil2Syahrul()
    {
        $data = MdpUtil2Syahrul::class::orderBy('id', 'desc')->first();
        if ($data) {
            $data = $data->toArray();
            $data['Van'] = $data['data_Ua3'];
            unset($data['data_Ua3']); // Remove the old key if necessary
            $data['Vbn'] = $data['data_Ub3'];
            unset($data['data_Ub3']);
            $data['Vcn'] = $data['data_Uc3'];
            unset($data['data_Uc3']);
            $data['Ia'] = $data['data_Ia3'];
            unset($data['data_Ia3']);
            $data['Ib'] = $data['data_Ib3'];
            unset($data['data_Ib3']);
            $data['Ic'] = $data['data_Ic3'];
            unset($data['data_Ic3']);
            $data['It'] = $data['data_It3'];
            unset($data['data_It3']);
            $data['Pa'] = $data['data_Pa3'];
            unset($data['data_Pa3']);
            $data['Pb'] = $data['data_Pb3'];
            unset($data['data_Pb3']);
            $data['Pc'] = $data['data_Pc3'];
            unset($data['data_Pc3']);
            $data['Pt'] = $data['data_Pt3'];
            unset($data['data_Pt3']);
            $data['Qa'] = $data['data_Qa3'];
            unset($data['data_Qa3']);
            $data['Qb'] = $data['data_Qb3'];
            unset($data['data_Qb3']);
            $data['Qc'] = $data['data_Qc3'];
            unset($data['data_Qc3']);
            $data['Qt'] = $data['data_Qt3'];
            unset($data['data_Qt3']);
            $data['pf'] = $data['data_Pft3'];
            unset($data['data_Pft3']);
            $data['f'] = $data['data_Hz3'];
            unset($data['data_Hz3']);

            unset($data['data_Sa3']);
            unset($data['data_Sb3']);
            unset($data['data_Sc3']);
            unset($data['data_St3']);
            unset($data['data_Pfa3']);
            unset($data['data_Pfb3']);
            unset($data['data_Pfc3']);
            // unset($data['data_pkWh3']);
            unset($data['data_nkWh3']);
            unset($data['data_pkVarh3']);
            unset($data['data_nkVarh3']);
        }
        return $data;
    }

    public function getEnergyMdp()
    {
        $cost = EnergyCost::first()->harga;
        $nowKwh = MdpSyahrul::orderBy('id', 'desc')->first()->data_pkWh1;
        $yesterday = Carbon::yesterday()->format('Y-m-d');
        $yesterdayKwh = MdpSyahrul::whereRaw("STR_TO_DATE(tanggal, '%d/%m/%y') = ?", [$yesterday])->orderBy('id', 'desc')->first()->data_pkWh1;
        $todayKwh = $nowKwh - $yesterdayKwh;

        $last2MonthKwh = 15500;

        $lastMonth = Carbon::now()->subMonth()->format('m');
        $lastMonthTotalKwh = MdpSyahrul::whereRaw("MONTH(STR_TO_DATE(tanggal, '%d/%m/%y')) = ?", [$lastMonth])->orderBy('id', 'desc')->first()->data_pkWh1;
        $lastMonthKwh = $lastMonthTotalKwh - $last2MonthKwh;
        $lastMonthCost = $lastMonthKwh * $cost;

        $thisMonthKwh = $nowKwh - $lastMonthTotalKwh;
        $thisMonthCost = $thisMonthKwh * $cost;

        return response()->json([
            'todayKwh' => $todayKwh,
            // 'nowKwh' => $nowKwh,
            // 'lastMonthTotalKwh' => $lastMonthTotalKwh,
            'lastMonthKwh' => $lastMonthKwh,
            'thisMonthKwh' => $thisMonthKwh,
            'tariff' => $cost,
            'lastMonthCost' => $lastMonthCost,
            'thisMonthCost' => $thisMonthCost
        ]);
    }

    public function getEnergyUtil1()
    {
        $cost = EnergyCost::first()->harga;
        $nowKwh = MdpUtil1Syahrul::orderBy('id', 'desc')->first()->data_pkWh2;
        $yesterday = Carbon::yesterday()->format('Y-m-d');
        $yesterdayKwh = MdpUtil1Syahrul::whereRaw("STR_TO_DATE(tanggal, '%d/%m/%y') = ?", [$yesterday])->orderBy('id', 'desc')->first()->data_pkWh2;
        $todayKwh = $nowKwh - $yesterdayKwh;

        $last2MonthKwh = 15500;

        $lastMonth = Carbon::now()->subMonth()->format('m');
        $lastMonthTotalKwh = MdpUtil1Syahrul::whereRaw("MONTH(STR_TO_DATE(tanggal, '%d/%m/%y')) = ?", [$lastMonth])->orderBy('id', 'desc')->first()->data_pkWh2;
        $lastMonthKwh = round($lastMonthTotalKwh - $last2MonthKwh, 2);
        $lastMonthCost = $lastMonthKwh * $cost;

        $thisMonthKwh = $nowKwh - $lastMonthTotalKwh;
        $thisMonthCost = $thisMonthKwh * $cost;

        return response()->json([
            'todayKwh' => $todayKwh,
            // 'nowKwh' => $nowKwh,
            // 'lastMonthTotalKwh' => $lastMonthTotalKwh,
            'lastMonthKwh' => $lastMonthKwh,
            'thisMonthKwh' => $thisMonthKwh,
            'tariff' => $cost,
            'lastMonthCost' => $lastMonthCost,
            'thisMonthCost' => $thisMonthCost
        ]);
    }

    public function getEnergyUtil2()
    {
        $cost = EnergyCost::first()->harga;
        $nowKwh = MdpUtil2Syahrul::orderBy('id', 'desc')->first()->data_pkWh3;
        $yesterday = Carbon::yesterday()->format('Y-m-d');
        $yesterdayKwh = MdpUtil2Syahrul::whereRaw("STR_TO_DATE(tanggal, '%d/%m/%y') = ?", [$yesterday])->orderBy('id', 'desc')->first()->data_pkWh3;
        $todayKwh = $nowKwh - $yesterdayKwh;

        $last2MonthKwh = 15500;

        $lastMonth = Carbon::now()->subMonth()->format('m');
        $lastMonthTotalKwh = MdpUtil2Syahrul::whereRaw("MONTH(STR_TO_DATE(tanggal, '%d/%m/%y')) = ?", [$lastMonth])->orderBy('id', 'desc')->first()->data_pkWh3;
        $lastMonthKwh = round($lastMonthTotalKwh - $last2MonthKwh, 2);
        $lastMonthCost = $lastMonthKwh * $cost;

        $thisMonthKwh = $nowKwh - $lastMonthTotalKwh;
        $thisMonthCost = $thisMonthKwh * $cost;

        return response()->json([
            'todayKwh' => $todayKwh,
            // 'nowKwh' => $nowKwh,
            // 'lastMonthTotalKwh' => $lastMonthTotalKwh,
            'lastMonthKwh' => $lastMonthKwh,
            'thisMonthKwh' => $thisMonthKwh,
            'tariff' => $cost,
            'lastMonthCost' => $lastMonthCost,
            'thisMonthCost' => $thisMonthCost
        ]);
    }
}
