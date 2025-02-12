<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MdpDataDebug extends Model
{
    use HasFactory;
    protected $connection = 'mysql';
    protected $table = 'mdp_data_debugs';
    protected $fillable = [
        'id_kwh',
        'Van',
        'Vbn',
        'Vcn',
        'Ia',
        'Ib',
        'Ic',
        'It',
        'Pa',
        'Pb',
        'Pc',
        'Pt',
        'Qa',
        'Qb',
        'Qc',
        'Qt',
        'pf',
        'f',
    ];
}
