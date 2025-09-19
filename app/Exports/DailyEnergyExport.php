<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class DailyEnergyExport implements FromView
{
    protected $data;
    protected $month;
    protected $monthOptions;

    public function __construct($data, $month, $monthOptions)
    {
        $this->data = $data;
        $this->month = $month;
        $this->monthOptions = $monthOptions;
    }

    public function view(): View
    {
        return view('exports.daily_energy', [
            'data' => $this->data,
            'selectedMonth' => $this->month,
            'monthOptions' => $this->monthOptions,
        ]);
    }
}
