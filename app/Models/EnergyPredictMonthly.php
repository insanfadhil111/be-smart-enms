<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EnergyPredictMonthly extends Model
{
    use HasFactory;

    protected $connection = 'mysql';
    protected $table = 'energy_predict_monthlies';
    protected $fillable = ['month', 'prediction'];
}
