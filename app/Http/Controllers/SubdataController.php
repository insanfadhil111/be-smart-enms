<?php

namespace App\Http\Controllers;

use App\Models\Subdata;
use Illuminate\Http\Request;

class SubdataController extends Controller
{
    public function index()
    {
        $title = 'Settings';
        $subdata = Subdata::first() ?? new Subdata($this->getDefaultValues());
        return view('pages.settings.index', compact('title', 'subdata'));
    }

    public function update(Request $request)
    {
        $validated = $request->validate([
            'hargaKwh' => 'required|numeric',
            'co2eq' => 'required|numeric',
            'hargaPdam' => 'required|numeric',
            'kwhAirPerMeterKubik' => 'required|numeric',
            'trees_eq' => 'required|numeric',
            'coal_eq' => 'required|numeric',
            'decimal_sep' => 'required|string',
            'thousand_sep' => 'required|string',
        ]);

        $subdata = Subdata::first();
        if ($subdata) {
            $subdata->hargaKwh = $validated['hargaKwh'];
            $subdata->co2eq = $validated['co2eq'];
            $subdata->hargaPdam = $validated['hargaPdam'];
            $subdata->kwhAirPerMeterKubik = $validated['kwhAirPerMeterKubik'];
            $subdata->trees_eq = $validated['trees_eq'];
            $subdata->coal_eq = $validated['coal_eq'];
            $subdata->decimal_sep = $validated['decimal_sep'];
            $subdata->thousand_sep = $validated['thousand_sep'];
            $subdata->save();
        } else {
            Subdata::create($validated);
        }

        return redirect()->route('subdatas.index')->with('success', 'Settings updated successfully.');
    }


    public function reset()
    {
        $subdata = Subdata::first();
        if ($subdata) {
            $subdata->update($this->getDefaultValues());
        } else {
            Subdata::create($this->getDefaultValues());
        }

        return redirect()->route('subdatas.index')->with('success', 'Settings reset to default values.');
    }

    public function formatNumber($number, $decimal = 2)
    {
        $subdata = Subdata::first() ?? new Subdata($this->getDefaultValues());
        $rounded = round($number, $decimal);

        $formatted = number_format($rounded, $decimal, $subdata->decimal_sep, $subdata->thousand_sep);

        return $formatted;
    }

    private function getDefaultValues()
    {
        return [
            'hargaKwh' => 1440,
            'co2eq' => 0.85,
            'hargaPdam' => 1575,
            'kwhAirPerMeterKubik' => 0.039,
            'trees_eq' => 2,
            'coal_eq' => 0.538,
            'decimal_sep' => ',',
            'thousand_sep' => '.',
        ];
    }
}
