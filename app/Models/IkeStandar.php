<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class IkeStandar extends Model
{
    use HasFactory;

    protected $table = 'ike_standard';
    protected $fillable = ['total_energy', 'created_at', 'updated_at'];
}
