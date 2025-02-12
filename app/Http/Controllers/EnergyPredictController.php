<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Models\EnergyPredict;
use App\Http\Controllers\Controller;
use App\Models\EnergyPredictMonthly;

class EnergyPredictController extends Controller
{
    public function takeDailyPredictions(Request $request)
    {
        // Process the received predictions
        $predictions = $request->all();

        // Store or update the predictions in the database
        foreach ($predictions as $prediction) {
            // Look for an existing record with the same date
            $existingPrediction = EnergyPredict::where('date', $prediction['predicted_day'])->first();

            if ($existingPrediction) {
                // Update the existing prediction record
                $existingPrediction->update(['prediction' => $prediction['predicted_kwh']]);
            } else {
                // Create a new prediction record
                EnergyPredict::create([
                    'date' => $prediction['predicted_day'],
                    'prediction' => $prediction['predicted_kwh'],
                ]);
            }
        }
        // Return a response
        return response()->json(['message' => 'Predictions stored or updated successfully'], 200);
    }

    public function getDailyPredictions()
    {
        $data = EnergyPredict::orderBy('id', 'asc')->get();

        return $data;
    }

    public function takeMonthlyPredictions(Request $request)
    {
        // Process the received predictions
        $predictions = $request->all();

        foreach ($predictions as $prediction) {
            $existingPrediction = EnergyPredictMonthly::where('month', $prediction['predicted_month'])->first();

            if ($existingPrediction) {
                // Update the existing prediction
                $existingPrediction->update(['prediction' => $prediction['predicted_kwh']]);
            } else {
                // Create a new prediction
                EnergyPredictMonthly::create([
                    'month' => $prediction['predicted_month'],
                    'prediction' => $prediction['predicted_kwh']
                ]);
            }
        }
        // Return a response
        return response()->json(['message' => 'Predictions stored or updated successfully'], 200);
    }

    public function getMonthlyPrediction()
    {
        $data = EnergyPredictMonthly::orderBy('id', 'asc')->get();

        return $data;
    }

    public function getMonthlyPredictionsByYear($year)
    {
        $data = EnergyPredictMonthly::where('month', 'LIKE', $year . '%')->orderBy('id', 'asc')->get();

        $n = count($data);
        for ($i = 0; $i < $n; $i++) {
            // Convert $data[$i]->month to Carbon object
            $data[$i]->bulan = Carbon::parse($data[$i]->month)->format('M');
            $data[$i]->tahun = Carbon::parse($data[$i]->month)->format('Y');
        }

        return $data;
    }

    public function getDailyPredictionsByYear($year)
    {
        $data = EnergyPredict::whereYear('date', $year)->orderBy('id', 'asc')->get();

        return $data;
    }
}
