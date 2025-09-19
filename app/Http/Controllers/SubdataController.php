<?php

namespace App\Http\Controllers;

use App\Models\Subdata;
use App\Models\IkeConfig;
use Illuminate\Http\Request;

class SubdataController extends Controller
{
    public function index()
    {
        $title = 'Settings';
        $subdata = Subdata::first() ?? new Subdata($this->getDefaultValues());
        $ikeConfigs = IkeConfig::all();

        // Default values per kategori
        $defaults = [
            'Sangat Efisien' => ['batas_bawah' => 0, 'batas_atas' => 70, 'warna' => '#00FF00'],
            'Efisien' => ['batas_bawah' => 70.01, 'batas_atas' => 100, 'warna' => '#FFFF00'],
            'Cukup Efisien' => ['batas_bawah' => 100.01, 'batas_atas' => 130, 'warna' => '#FFA500'],
            'Boros' => ['batas_bawah' => 130.01, 'batas_atas' => 99999, 'warna' => '#FF0000'],
        ];

        // Replace kosong dengan default
        foreach ($ikeConfigs as $ike) {
            if (is_null($ike->batas_bawah) && isset($defaults[$ike->kategori])) {
                $ike->batas_bawah = $defaults[$ike->kategori]['batas_bawah'];
            }
            if (is_null($ike->batas_atas) && isset($defaults[$ike->kategori])) {
                $ike->batas_atas = $defaults[$ike->kategori]['batas_atas'];
            }
            if (empty($ike->warna) && isset($defaults[$ike->kategori])) {
                $ike->warna = $defaults[$ike->kategori]['warna'];
            }
        }

        return view('pages.settings.index', compact('title', 'subdata', 'ikeConfigs'));
    }

    public function update(Request $request)
    {
        // Validasi untuk Subdata
        $validated = $request->validate([
            'hargaKwh' => 'required|numeric',
            'co2eq' => 'required|numeric',
            'hargaPdam' => 'required|numeric',
            'kwhAirPerMeterKubik' => 'required|numeric',
            'trees_eq' => 'required|numeric',
            'coal_eq' => 'required|numeric',
            'luas_bangunan' => 'nullable|numeric|min:1',
            'decimal_sep' => 'required|string',
            'thousand_sep' => 'required|string',
        ]);

        // Update atau buat Subdata
        $subdata = Subdata::first();
        if ($subdata) {
            $subdata->update($validated);
        } else {
            Subdata::create($validated);
        }

        // Update IKE Configs
        if ($request->has('ike_configs')) {
            foreach ($request->input('ike_configs') as $config) {
                \App\Models\IkeConfig::where('kategori', $config['kategori'])->update([
                    'batas_bawah' => $config['batas_bawah'],
                    'batas_atas' => $config['batas_atas'],
                    'warna' => $config['warna'],
                ]);
            }
        }

        return redirect()->route('subdatas.index')->with('success', 'Settings updated successfully.');
    }


    public function reset()
    {
        // Reset Subdata
        $subdata = Subdata::first();
        if ($subdata) {
            $subdata->update($this->getDefaultValues());
        } else {
            Subdata::create($this->getDefaultValues());
        }

        // Default values IKE configs per kategori
        $defaults = [
            'Sangat Efisien' => ['batas_bawah' => 0, 'batas_atas' => 70, 'warna' => '#00FF00'],
            'Efisien' => ['batas_bawah' => 70.01, 'batas_atas' => 100, 'warna' => '#FFFF00'],
            'Cukup Efisien' => ['batas_bawah' => 100.01, 'batas_atas' => 130, 'warna' => '#FFA500'],
            'Boros' => ['batas_bawah' => 130.01, 'batas_atas' => 99999, 'warna' => '#FF0000'],
        ];

        // Hapus semua data lama agar tidak dobel
        IkeConfig::truncate();

        // Reset IkeConfig ke default
        foreach ($defaults as $kategori => $values) {
            IkeConfig::create([
                'kategori' => $kategori,
                'batas_bawah' => $values['batas_bawah'],
                'batas_atas' => $values['batas_atas'],
                'warna' => $values['warna'],
            ]);
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
            'luas_bangunan' => 2400,
            'decimal_sep' => ',',
            'thousand_sep' => '.',
        ];
    }
}
