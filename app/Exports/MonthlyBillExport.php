<?php

namespace App\Exports;

use App\Http\Controllers\EnergyController;
use App\Http\Controllers\SubdataController;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\FromCollection;

class MonthlyBillExport implements FromCollection, WithMapping, WithHeadings
{
    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        $eCon = new EnergyController();
        $monthlyKwh = $eCon->getMonthlyEnergy();
        $subCon = new SubdataController();

        $n = count($monthlyKwh);
        if ($n > 1) {
            for ($i = 0; $i < $n - 1; $i++) {
                $monthlyKwh[$i]->diffStatus = ($monthlyKwh[$i]->total > $monthlyKwh[$i + 1]->total) ? 'naik' : 'turun';
                $monthlyKwh[$i]->diff = $subCon->formatNumber(abs(($monthlyKwh[$i]->total - $monthlyKwh[$i + 1]->total) / $monthlyKwh[$i + 1]->total) * 100, 2);
            }
        }
        $monthlyKwh[$n - 1]->diffStatus = 'turun';
        $monthlyKwh[$n - 1]->diff = 0;

        $monthlyKwh->makeHidden(['color', 'timestamp']);

        return $monthlyKwh;
    }
    public function map($monthlyKwh): array
    {
        return [
            $monthlyKwh->bulan,
            $monthlyKwh->tahun,
            $monthlyKwh->total,
            $monthlyKwh->bill,
            $monthlyKwh->diffStatus,
            $monthlyKwh->diff,
            $monthlyKwh->angka_ike,
            $monthlyKwh->ike,
        ];
    }
    public function headings(): array
    {
        return [
            'Bulan',
            'Tahun',
            'KWh',
            'Biaya',
            'Naik/Turun',
            '% Naik/Turun',
            'Angka IKE',
            'Kategori IKE',
        ];
    }
}
