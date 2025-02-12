<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class EnvironmentController extends Controller
{
    public function monitor()
    {
        $title = 'Environment Sensing';
        $collection = ["Temperature", "Humidity", "Light Intensity"];
        $value = ["25 C", "60 RH", "1000 Lux"];

        return view("pages.envi.sense", compact('title', 'collection', 'value'));
    }

    
}
