<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MdpKwhDebug extends Model
{
    use HasFactory;
    protected $connection = 'mysql';
    protected $table = 'mdp_kwh_debugs';
}
