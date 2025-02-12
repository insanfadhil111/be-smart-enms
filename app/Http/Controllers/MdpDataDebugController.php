<?php

namespace App\Http\Controllers;

use App\Models\MdpDataDebug;
use App\Models\MdpKwhDebug;
use Illuminate\Http\Request;

class MdpDataDebugController extends Controller
{
    public function addMdpDataDebug(Request $request)
    {
        try {
            $mdp = new MdpDataDebug();

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

    public function addMdpKwhDebug(Request $request)
    {
        try {
            $mdp = new MdpKwhDebug();

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

    public function getMdpDataDebug()
    {
        $mdp = MdpDataDebug::latest()->take(500)->get();
        $formattedData = $mdp->map(function ($item) {
            $item->timestamp = $item->created_at->format('d M Y H:i:s');
            return $item;
        });
        $formattedData->makeHidden(['created_at', 'updated_at']);
        return $formattedData;
    }

    public function getMdpKwhDebug()
    {
        $mdp = MdpKwhDebug::orderBy('id', 'desc')->take(500)->get();
        $formattedData = $mdp->map(function ($item) {
            $item->timestamp = $item->created_at->format('d M Y H:i:s');
            return $item;
        });
        $formattedData->makeHidden(['created_at', 'updated_at']);
        return $formattedData;
    }
}
