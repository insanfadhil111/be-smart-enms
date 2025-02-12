<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Subdata extends Model
{
    use HasFactory;
    protected $connection = 'mysql';
    protected $table = 'subdata';
    protected $fillable = [
        'hargaKwh',
        'co2eq',
        'hargaPdam',
        'kwhAirPerMeterKubik',
        'trees_eq',
        'coal_eq',
        'decimal_sep',
        'thousand_sep',
    ];
}
