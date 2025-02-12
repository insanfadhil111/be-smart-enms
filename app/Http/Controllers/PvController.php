<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\MppData;
use App\Models\Subdata;
use App\Models\PvSubdata;
use App\Models\EnergyCost;
use App\Models\GoodweData;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Http\Controllers\MdpController;
use App\Http\Controllers\EnergyController;
use App\Http\Controllers\SubdataController;
use App\Http\Controllers\MdpSyahrulController;

class PvController extends Controller
{
    /* 
        for Pages 
    */
    public function nreIndex()
    {
        $title = 'NRE Dashboard';

        $data_mpp = $this->mppTodayGeneration();
        $kwh_today_mpp = $data_mpp['totalEnergy'];
        $msg_mpp = $data_mpp['message'] ?? null;
        $lastUpdated_mpp = $data_mpp['lastUpdated'];

        $data_gw = $this->gwTodayGeneration();
        $kwh_today_gw = $data_gw['totalEnergy']; //
        $msg_gw = $data_gw['message'] ?? null;
        $lastUpdated_gw = $data_gw['lastUpdated'];

        // Jika ada data, maka msg = null
        $msg_pv = "";
        if ($msg_gw != null && $msg_mpp != null) {
            $msg_pv = "There is no today's data from all PVs";
        }

        // Today Data
        $today_kwh = round($kwh_today_mpp + $kwh_today_gw, 2);
        $profitPerKwh = PvSubdata::latest()->first()->profit;
        $today_income = round($today_kwh * $profitPerKwh, 0);

        // All Time Data
        $mpp_alltime_mpp = $this->mppAlltimeGeneration();
        $gw_alltime = $this->gwAlltimeGeneration();
        $alltime_kwh = round($mpp_alltime_mpp + $gw_alltime, 2);
        $alltime_income = round($alltime_kwh * $profitPerKwh, 0);

        // Mpp dan Gw Data (for charts)
        [$datesMpp, $powerMpp] = $this->getMppLastWeekData();
        [$datesGw, $powerGw] = $this->getGwLastWeekData();

        $names = ["PV Generation Today", "Income", "Total Generation", "Total Income"];
        $satuan = ["kWh", "IDR", "kWh", "IDR"];
        $values = [$today_kwh, $today_income, $alltime_kwh, $alltime_income];

        $decSep = Subdata::latest()->first()->decimal_sep;
        $thSep = Subdata::latest()->first()->thousand_sep;

        return view("pages.nre.index", compact('title', 'names', 'satuan', 'values', 'msg_mpp', 'msg_gw', 'lastUpdated_mpp', 'lastUpdated_gw', 'msg_pv', 'datesMpp', 'powerMpp', 'datesGw', 'powerGw', 'decSep', 'thSep'));
    }


    /* 
        for APIs 
    */
    public function mppTodayGeneration()
    {
        try {
            $today = Carbon::today();

            $dataPoints = MppData::whereDate('created_at', $today)->latest()->get();

            // Jika tidak ada data untuk hari ini, ambil data terakhir yang tersedia
            if ($dataPoints->isEmpty()) {
                $message = "Tidak ada data Hallway hari ini";
                $totalEnergy = 0;
                $lastUpdated = MppData::latest()->first()->created_at->format('Y-m-d H:i:s');
            } else {
                // Jika ada data untuk hari ini, hitung total energi
                $totalEnergy = 0;
                $previousTimestamp = null;
                $lastUpdated = $dataPoints->first()->created_at->format('Y-m-d H:i:s');

                foreach ($dataPoints as $data) {
                    if ($previousTimestamp) {
                        // Hitung selisih waktu dalam jam
                        $timeDifference = $previousTimestamp->diffInMinutes($data->created_at) / 60;

                        // Hitung kontribusi energi untuk interval ini
                        $energyContribution = $data->P * $timeDifference;
                        $totalEnergy += $energyContribution;
                    }
                    $previousTimestamp = $data->created_at;
                }
                // Tidak perlu pesan jika data ada
                $message = null;
            }
            return [
                'totalEnergy' => $totalEnergy / 1000,
                'message' => $message,
                'lastUpdated' => $lastUpdated,
            ];
        } catch (\Throwable $th) {
            return [
                'totalEnergy' => 0,
                'message' => 'Terjadi kesalahan dalam pengambilan data.',
            ];
        }
    }

    /**
     * Calculate the all-time generation of MPP data.
     *
     * @return float The total energy generated in kilowatt-hours (kWh).
     */
    public function mppAlltimeGeneration()
    {
        $dataPoints = MppData::all();
        $totalEnergy = 0;
        $previousTimestamp = null;

        foreach ($dataPoints as $data) {
            if ($previousTimestamp) {
                // Calculate the time difference in hours
                $timeDifference = $previousTimestamp->diffInMinutes($data->created_at) / 60;

                // Calculate energy contribution for this interval
                $energyContribution = $data->P * $timeDifference;
                $totalEnergy += $energyContribution;
            }
            $previousTimestamp = $data->created_at;
        }
        return $totalEnergy / 1000;
    }


    /**
     * Calculate the total energy generated today by the PV system.
     *
     * @return float The total energy generated today in kilowatt-hours (kWh).
     */
    public function gwTodayGeneration()
    {
        try {
            $today = Carbon::today();

            $dataPoints = GoodweData::whereDate('created_at', $today)
                ->orderBy('created_at')
                ->first();

            // Jika tidak ada data untuk hari ini, ambil data terakhir yang tersedia
            if ($dataPoints === null) {
                $message = "Tidak ada data Rooftop hari ini";
                $totalEnergy = 0;
                $lastUpdated = GoodweData::latest()->first()->created_at->format('Y-m-d H:i:s');
            } else {
                $totalEnergy = $dataPoints->today_generation / 1000; // in kWh
                $message = null; // Tidak perlu pesan jika data ada
                $lastUpdated = $dataPoints->created_at->format('Y-m-d H:i:s');
            }
            return [
                'totalEnergy' => $totalEnergy,
                'message' => $message,
                'lastUpdated' => $lastUpdated,
            ];
        } catch (\Throwable $th) {
            return [
                'totalEnergy' => 0,
                'message' => 'Terjadi kesalahan dalam pengambilan data.',
                'error' => $th->getMessage(),
            ];
        }
    }


    /**
     * Calculate the total energy generated by the PV system over time.
     *
     * @return float The total energy generated in kilowatt-hours (kWh).
     */
    public function gwAlltimeGeneration()
    {
        $dataPoints = GoodweData::all();
        $totalEnergy = 0;
        $previousTimestamp = null;

        foreach ($dataPoints as $data) {
            if ($previousTimestamp) {
                // Calculate the time difference in hours
                $timeDifference = $previousTimestamp->diffInMinutes($data->created_at) / 60;

                // Calculate energy contribution for this interval
                $energyContribution = $data->P * $timeDifference;
                $totalEnergy += $energyContribution;
            }
            $previousTimestamp = $data->created_at;
        }

        return $totalEnergy / 1000;
    }


    /**
     * Store a new MppData record.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function addMppData(Request $request)
    {
        try {
            $data = new MppData();
            $data->P = $request->P;
            $data->gridVoltageR = $request->gridVoltageR;
            $data->gridpowerR = $request->gridpowerR;
            $data->gridFreqR = $request->gridFreqR;
            $data->gridCurrR = $request->gridCurrR;
            $data->ACOutVolR = $request->ACOutVolR;
            $data->ACOutPowR = $request->ACOutPowR;
            $data->ACOutFreqR = $request->ACOutFreqR;
            $data->ACOutCurrR = $request->ACOutCurrR;
            $data->OUTLoadPerc = $request->OutLoadPerc;
            $data->PVInPow1 = $request->PVInPow1;
            $data->PVInPow2 = $request->PVInPow2;
            $data->PVInVol1 = $request->PVInVol1;
            $data->PVInVol2 = $request->PVInVol2;
            $data->temp = $request->temp;
            $data->devstatus = $request->devstatus;

            $data->save();
            return response()->json([
                'message' => 'Data added successfully',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Failed to save data',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Retrieve the MPP data.
     *
     * This method retrieves the latest 500 MPP data records from the database,
     * formats the timestamp field, and returns the formatted data as a JSON response.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getMppData(int $limit)
    {
        $data = MppData::latest()->take($limit)->get();
        $formattedData = $data->map(function ($item) {
            $item->timestamp = $item->created_at->format('d-m-Y H:i:s');
            return $item;
        });
        $formattedData->makeHidden(['created_at', 'updated_at']);
        return $formattedData;
    }

    /**
     * Add GW data to the database.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function addGwData(Request $request)
    {
        try {
            $data = new GoodweData();
            $data->P = $request->P;
            $data->today_generation = $request->today_generation;
            $data->total_generation = $request->total_generation;
            $data->today_income = $request->today_income;
            $data->total_income = $request->total_income;
            $data->save();
            return response()->json([
                'message' => 'Data added successfully',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Failed to save data',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Retrieve Goodwe data from the database.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getGwData(int $limit)
    {
        $data = GoodweData::latest()->take($limit)->get();

        $formattedData = $data->map(function ($item) {
            $item->timestamp = $item->created_at->format('d-m-Y H:i:s');
            return $item;
        });

        $formattedData->makeHidden(['created_at', 'updated_at']);

        return $formattedData;
    }

    public function getMppLastWeekData()
    {
        $today = Carbon::today();
        $lastWeek = $today->subDays(7);
        $datesMpp = [];
        $powerMpp = [];

        $mppData = DB::table('mpp_data')
            ->select('P', 'created_at')
            ->where('created_at', '>=', $lastWeek)
            ->get();

        if ($mppData->isEmpty()) {
            $mppData = DB::table('mpp_data')
                ->select('P', 'created_at')
                ->latest()->take(2016) // 7 days * 24 hours * 12 data per hour
                ->get();

            foreach ($mppData as $data) {
                array_push($datesMpp, $data->created_at);
                array_push($powerMpp, $data->P);
            }
            return [$datesMpp, $powerMpp];
        }

        foreach ($mppData as $data) {
            array_push($datesMpp, $data->created_at);
            array_push($powerMpp, $data->P);
        }

        return [$datesMpp, $powerMpp];
    }

    public function getGwLastWeekData()
    {
        $today = Carbon::today();
        $lastWeek = $today->subDays(7);
        $datesGw = [];
        $powerGw = [];

        $gwData = DB::table('goodwe_data')
            ->select('P', 'created_at')
            ->where('created_at', '>=', $lastWeek)
            ->get();

        if ($gwData->isEmpty()) {
            $gwData = DB::table('goodwe_data')
                ->select('P', 'created_at')
                ->latest()->take(2016) // 7 days * 24 hours * 12 data per hour
                ->get();

            foreach ($gwData as $data) {
                array_push($datesGw, $data->created_at);
                array_push($powerGw, $data->P);
            }
            return [$datesGw, $powerGw];
        }

        foreach ($gwData as $data) {
            array_push($datesGw, $data->created_at);
            array_push($powerGw, $data->P);
        }

        return [$datesGw, $powerGw];
    }

    public function getPvChartFilter(Request $request)
    {
        try {
            $startDate = $request->input('startDate');
            $endDate = $request->input('endDate');

            $mppData = DB::table('mpp_data')
                ->select('P', 'created_at')
                ->whereBetween('created_at', [$startDate, $endDate])
                ->orderBy('created_at')
                ->get();

            $gwData = DB::table('goodwe_data')
                ->select('P', 'created_at')
                ->whereBetween('created_at', [$startDate, $endDate])
                ->orderBy('created_at')
                ->get();

            if ($mppData->isEmpty() && $gwData->isEmpty()) {
                // If no data found for the given date range, fetch last 7 days of data
                $mppData = DB::table('mpp_data')
                    ->select('P', 'created_at')
                    ->latest()->take(2016)
                    ->orderBy('created_at')
                    ->get();

                $gwData = DB::table('goodwe_data')
                    ->select('P', 'created_at')
                    ->latest()->take(2016)
                    ->orderBy('created_at')
                    ->get();
            }

            $datesMpp = $mppData->pluck('created_at')->map(function ($date) {
                return $date instanceof Carbon ? $date->format('Y-m-d H:i:s') : $date;
            })->toArray();
            $powerMpp = $mppData->pluck('P')->toArray();

            $datesGw = $gwData->pluck('created_at')->map(function ($date) {
                return $date instanceof Carbon ? $date->format('Y-m-d H:i:s') : $date;
            })->toArray();
            $powerGw = $gwData->pluck('P')->toArray();

            return response()->json([
                'datesMpp' => $datesMpp,
                'powerMpp' => $powerMpp,
                'datesGw' => $datesGw,
                'powerGw' => $powerGw
            ]);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function getPvChartToday()
    {
        $today = Carbon::today();
        $mppData = MppData::whereDate('created_at', $today)->latest()->get(['P', 'created_at']);
        $gwData = GoodweData::whereDate('created_at', $today)->latest()->get(['P', 'created_at']);

        foreach ($mppData as $data) {
            $data->timestamp = $data->created_at->format('Y-m-d H:i:s');
        }

        foreach ($gwData as $data) {
            $data->timestamp = $data->created_at->format('Y-m-d H:i:s');
        }

        if ($mppData->isEmpty() && $gwData->isEmpty()) {
            return response()->json([
                'mpp' => $mppData,
                'goodwe' => $gwData,
                'message' => 'No data available for today',
            ], 404);
        } else if ($mppData->isEmpty()) {
            return response()->json([
                'mpp' => $mppData,
                'goodwe' => $gwData,
                'message' => 'No MPP data available for today',
            ], 404);
        } else if ($gwData->isEmpty()) {
            return response()->json([
                'mpp' => $mppData,
                'goodwe' => $gwData,
                'message' => 'No Goodwe data available for today',
            ], 404);
        }

        $response = [
            'mpp' => $mppData,
            'goodwe' => $gwData,
            'message' => 'Data retrieved successfully',
        ];

        return response()->json($response);
    }

    public function getPvChartWeek()
    {
        $today = Carbon::today();
        $lastWeek = $today->subDays(7);

        $mppData = DB::table('mpp_data')
            ->select('P', 'created_at')
            ->where('created_at', '>=', $lastWeek)
            ->get();
        $gwData = DB::table('goodwe_data')
            ->select('P', 'created_at')
            ->where('created_at', '>=', $lastWeek)
            ->get();

        if ($mppData->isEmpty() && $gwData->isEmpty()) {
            return response()->json([
                'mpp' => $mppData,
                'goodwe' => $gwData,
                'message' => 'No data available for the past week',
            ], 404);
        } else if ($mppData->isEmpty()) {
            return response()->json([
                'mpp' => $mppData,
                'goodwe' => $gwData,
                'message' => 'No MPP data available for the past week',
            ], 404);
        } else if ($gwData->isEmpty()) {
            return response()->json([
                'mpp' => $mppData,
                'goodwe' => $gwData,
                'message' => 'No Goodwe data available for the past week',
            ], 404);
        }

        $response = [
            'mpp' => $mppData,
            'goodwe' => $gwData,
            'message' => 'Data retrieved successfully',
        ];

        return response()->json($response);
    }

    public function getPVChartMonth()
    {
        // get PV Data for the last 30 days as daily data
        $startDate = Carbon::today()->subDays(30);
        $endDate = Carbon::today();
        $mppData = MppData::whereBetween('created_at', [$startDate, $endDate])
            ->selectRaw('DATE(created_at) as date, SUM(P) as total_power')
            ->groupBy('date')
            ->get();
        $gwData = GoodweData::whereBetween('created_at', [$startDate, $endDate])
            ->selectRaw('DATE(created_at) as date, SUM(P) as total_power')
            ->groupBy('date')
            ->get();

        return $mppData;

        if ($mppData->isEmpty() && $gwData->isEmpty()) {
            return response()->json([
                'message' => 'No data available for the last 30 days',
            ], 404);
        } else if ($mppData->isEmpty()) {
            return response()->json([
                'message' => 'No MPP data available for the last 30 days',
            ], 404);
        } else if ($gwData->isEmpty()) {
            return response()->json([
                'message' => 'No Goodwe data available for the last 30 days',
            ], 404);
        }

        $response = [
            'mpp' => $mppData,
            'goodwe' => $gwData,
            'message' => 'Data retrieved successfully',
        ];
        return response()->json($response);
    }
}
