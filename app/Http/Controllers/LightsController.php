<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class LightsController extends Controller
{
    public function showControl()
    {
        $title = 'Lights Control';
        $devices = ["Main Lamp", "Second Lamp"];
        $status = [1, 0];

        return view("pages.envi.lights", compact('devices', 'status', 'title'));
    }
}
