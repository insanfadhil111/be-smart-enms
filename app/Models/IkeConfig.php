<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class IkeConfig extends Model
{
    use HasFactory;

    protected $table = 'ike_configs';
    protected $connection = 'mysql';
    protected $fillable = [
        'kategori',
        'batas_bawah',
        'batas_atas',
        'warna',
    ];
}
