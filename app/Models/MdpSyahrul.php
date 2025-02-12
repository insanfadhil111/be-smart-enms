<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MdpSyahrul extends Model
{
    use HasFactory;
    protected $connection = 'mysql_2';
    protected $table = 'mdp';
    public $timestamps = false;
}
