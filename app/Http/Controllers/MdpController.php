<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\MdpKwh;
use App\Models\MdpData;
use App\Models\MdpControl;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

class MdpController extends Controller
{
    /* 
        for Pages
    */


    /* 
        for API
    */
    public function addMdpData(Request $request)
    {
        /**
         * Save MdpData 
         *
         * @param  \Illuminate\Http\Request  $request
         * @param  int  $id
         * @return \Illuminate\Http\JsonResponse
         */
        try {
            $mdp = new MdpData;

            $mdp->id_kwh = $request->id_kwh;
            $mdp->Van = $request->Van;
            $mdp->Vbn = $request->Vbn;
            $mdp->Vcn = $request->Vcn;
            $mdp->Ia = $request->Ia;
            $mdp->Ib = $request->Ib;
            $mdp->Ic = $request->Ic;
            $mdp->It = $request->It;
            $mdp->Pa = $request->Pa;
            $mdp->Pb = $request->Pb;
            $mdp->Pc = $request->Pc;
            $mdp->Pt = $request->Pt;
            $mdp->Qa = $request->Qa;
            $mdp->Qb = $request->Qb;
            $mdp->Qc = $request->Qc;
            $mdp->Qt = $request->Qt;
            $mdp->pf = $request->pf;
            $mdp->f = $request->f;
            $mdp->save();

            return response()->json([
                "message" => "Data already saved"
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Failed to save data',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function getMdpData()
    {
        /**
         * Retrieve the latest 500 MdpData records and return them as an array.
         *
         * @return array
         */
        $mdp = MdpData::latest()->take(500)->get();
        $formattedData = $mdp->map(function ($item) {
            $item->timestamp = $item->created_at->format('d M Y H:i:s');
            return $item;
        });
        $formattedData->makeHidden(['created_at', 'updated_at']);
        return $formattedData;
    }

    public function getMdpDataById($id)
    {
        /**
         * Retrieve MDP data for a given ID.
         *
         * @param int $id The ID of the MDP data to retrieve.
         * @return \Illuminate\Http\JsonResponse|array The MDP data as a JSON response or an array.
         */
        $mdp = MdpData::where('id_kwh', $id)->latest()->take(100)->get();

        if ($mdp->isEmpty() || $mdp->contains('error')) {
            return response()->json([
                "message" => "Data not found"
            ], 404);
        }

        $formattedData = $mdp->map(function ($item) {
            $item->timestamp = $item->created_at->format('d M Y H:i:s');
            return $item;
        });
        $formattedData->makeHidden(['created_at', 'updated_at']);
        return $formattedData;
    }

    public function getMdpDataByIdLimit($id, $limit)
    {
        /**
         * Retrieve the latest MdpData records based on the given id and limit.
         *
         * @param int $id The id of the MdpData record.
         * @param int $limit The maximum number of records to retrieve.
         * @return \Illuminate\Http\JsonResponse|array The JSON response containing the MdpData records or an empty array if no records are found.
         */
        $mdp = MdpData::where('id_kwh', $id)->latest()->take($limit)->get();

        if ($mdp->isEmpty()) {
            return response()->json([
                "message" => "Data not found"
            ], 404);
        }

        $formattedData = $mdp->map(function ($item) {
            $item->timestamp = $item->created_at->format('d M Y H:i:s');
            return $item;
        });
        $formattedData->makeHidden(['created_at', 'updated_at']);
        return $formattedData;
    }

    public function getLatestMdpDataById(int $id_kwh)
    {
        /**
         * Retrieve the latest MdpData record for a given ID.
         *
         * @param int $id_kwh The ID of the MdpData record.
         * @return \Illuminate\Http\JsonResponse|array The JSON response containing the latest MdpData record or an empty array if no records are found.
         */
        $mdp = DB::table('mdp_data')
            ->where('id_kwh', $id_kwh)
            ->latest()
            ->first();

        return $mdp;
    }

    public function addMdpKwh(Request $request)
    {
        try {
            $mdp = new MdpKwh;

            $mdp->kwh_1 = $request->kwh_1;
            $mdp->kwh_2 = $request->kwh_2;
            $mdp->kwh_3 = $request->kwh_3;
            $mdp->kwh_4 = 0; // Karena belum ada kwh meter
            $mdp->kwh_5 = 0; // Karena Default dari ESP ikut kwh_3
            $mdp->save();

            return response()->json([
                "message" => "Data saved"
            ], 201);
        } catch (\Throwable $th) {
            //throw $th;
            return response()->json([
                "message" => "Failed to save data",
                "error" => $th->getMessage()
            ], 500);
        }
    }

    public function getMdpKwh()
    {
        /**
         * Retrieve the latest 500 MdpKwh records.
         *
         * @return array
         */
        $mdp = MdpKwh::orderBy('id', 'desc')->take(500)->get();
        $formattedData = $mdp->map(function ($item) {
            $item->timestamp = $item->created_at->format('d M Y H:i:s');
            return $item;
        });
        $formattedData->makeHidden(['created_at', 'updated_at']);
        return $formattedData;
    }

    public function getMdpKwhById($id)
    {
        /**
         * Retrieve the latest 100 records of a specific MdpKwh instance.
         *
         * @param int $id The ID of the MdpKwh instance.
         * @return \Illuminate\Http\JsonResponse|array The JSON response containing the latest 100 records of the MdpKwh instance, or an empty array if no records are found.
         */
        $mdp = MdpKwh::latest()->take(100)->get('kwh_' . $id);

        if ($mdp->isEmpty()) {
            return response()->json([
                "message" => "Data not found"
            ], 404);
        }

        return $mdp->toArray();
    }

    public function getMdpKwhByIdLimit($id, $limit)
    {
        /**
         * Retrieve the latest MdpKwh records for a given id and limit.
         *
         * @param int $id The id of the MdpKwh record.
         * @param int $limit The maximum number of records to retrieve.
         * @return \Illuminate\Http\JsonResponse|array The JSON response containing the MdpKwh records or an empty array if no records are found.
         */
        $mdp = MdpKwh::where('id_kwh', $id)->latest()->take($limit)->get();

        if ($mdp->isEmpty()) {
            return response()->json([
                "message" => "Data not found"
            ], 404);
        }

        return $mdp->toArray();
    }

    public function totalMdpKwhToday()
    {
        /**
         * Retrieve the total energy consumption for today.
         *
         * @return \Illuminate\Http\JsonResponse|array The total energy consumption for today.
         */
        $today = Carbon::today();
        $yesterday = Carbon::yesterday();

        $energyToday = DB::transaction(function () use ($today, $yesterday) {
            // Subquery for today's latest record
            $todayData = MdpKwh::whereDate('created_at', $today)
                ->latest()
                ->first();

            // Subquery for yesterday's latest record
            $yesterdayData = MdpKwh::whereDate('created_at', $yesterday)
                ->latest()
                ->first();

            if ($todayData && $yesterdayData) {
                // Calculate the difference
                return [
                    'kwh_1' => $todayData->kwh_1 - $yesterdayData->kwh_1,
                    'kwh_2' => $todayData->kwh_2 - $yesterdayData->kwh_2,
                    'kwh_3' => $todayData->kwh_3 - $yesterdayData->kwh_3,
                    'kwh_4' => $todayData->kwh_4 - $yesterdayData->kwh_4,
                    'kwh_5' => $todayData->kwh_5 - $yesterdayData->kwh_5,
                ];
            }

            return null;
        });
        if ($energyToday == null) {
            $energyToday = 0;
            return $energyToday;
        }
        $todayKwh = number_format(array_sum($energyToday), 2, ',', '.');

        return $todayKwh;
    }

    public function getControlState()
    {
        $mdp = MdpControl::oldest()->get();
        $mdp->makeHidden(['updated_at', 'created_at', 'device']);
        return response()->json($mdp);
    }

    /**
     * Switch the status of the MDP control with the given ID.
     *
     * @param int $id The ID of the MDP control.
     * @return \Illuminate\Http\JsonResponse The JSON response containing the status of the MDP control.
     */
    public function switchMdp($id)
    {
        $mdp = MdpControl::find($id);

        if ($mdp) {
            $mdp->status = !$mdp->status;
            $mdp->save();

            return response()->json([
                "status" => $mdp->status
            ], 200);
        }

        return response()->json([
            "message" => "Data not found"
        ], 404);
    }

    /**
     * Retrieve MDP chart data for a given ID.
     *
     * @param int $id The ID of the MDP data.
     * @return array An array containing the MDP chart data.
     */
    public function getMdpChartData()
    {
        $subCon = new SubdataController();
        $sevenDaysAgo = Carbon::now()->subDays(7);

        $devices = ['MDP', 'SDP AC 1', 'SDP AC 2', 'Util 1', 'Util 2',];
        $magnitudes = ['Van', 'Vbn', 'Vcn', 'Ia', 'Ib', 'Ic', 'It', 'Pa', 'Pb', 'Pc', 'Pt', 'Qa', 'Qb', 'Qc', 'Qt', 'pf', 'f'];

        $chartData = [
            'dates' => [],
            'devices' => array_fill_keys($devices, array_fill_keys($magnitudes, []))
        ];

        foreach ($devices as $index => $device) {
            $deviceData = MdpData::where('id_kwh', $index + 1)
                ->where('created_at', '>=', $sevenDaysAgo)
                ->orderBy('created_at', 'asc')
                ->get();

            foreach ($deviceData as $data) {
                $timestamp = Carbon::parse($data->created_at)->format('Y-m-d H:i:s');

                // Only add the timestamp once (for the first device)
                if ($index === 0) {
                    $chartData['dates'][] = $timestamp;
                }

                foreach ($magnitudes as $magnitude) {
                    $chartData['devices'][$device][$magnitude][] =  $data->$magnitude;
                }
            }
        }

        return $chartData;
    }

    public function getMdpChartFilter(Request $request)
    {
        try {
            $startDate = $request->input('startDate');
            $endDate = $request->input('endDate');

            $devices = ['MDP', 'SDP AC 1', 'SDP AC 2', 'Util 1', 'Util 2',];
            $magnitudes = ['Van', 'Vbn', 'Vcn', 'Ia', 'Ib', 'Ic', 'It', 'Pa', 'Pb', 'Pc', 'Pt', 'Qa', 'Qb', 'Qc', 'Qt', 'pf', 'f'];

            $chartData = [
                'dates' => [],
                'devices' => array_fill_keys($devices, array_fill_keys($magnitudes, []))
            ];

            foreach ($devices as $index => $device) {
                $deviceData = MdpData::where('id_kwh', $index + 1)
                    ->whereBetween('created_at', [$startDate, $endDate])
                    ->orderBy('created_at', 'asc')
                    ->get();

                foreach ($deviceData as $data) {
                    $timestamp = Carbon::parse($data->created_at)->format('Y-m-d H:i:s');

                    // Only add the timestamp once (for the first device)
                    if ($index === 0) {
                        $chartData['dates'][] = $timestamp;
                    }

                    foreach ($magnitudes as $magnitude) {
                        $chartData['devices'][$device][$magnitude][] = $data->$magnitude;
                    }
                }
            }

            return $chartData;
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }
}
