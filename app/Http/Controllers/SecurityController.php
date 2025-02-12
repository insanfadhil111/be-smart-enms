<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class SecurityController extends Controller
{
    public function index()
    {
        return view ('pages.security.camera');
    }
    public function doorlock()
    {
        return view ('pages.security.doorlock');
    }
}
