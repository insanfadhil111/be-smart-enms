<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class EnvironmentController extends Controller
{
    public function monitor()
    {
        $title = 'Monitoring';
        $collection = []; // Jika kamu pakai data sensor lokal, sesuaikan

        $apiKey = '34c775e9ab061e3ada11988a48659689';
        $city = 'Surakarta';
        $url = "https://api.openweathermap.org/data/2.5/weather?q=$city&appid=$apiKey&units=metric&lang=id";

        try {
            $response = Http::withoutVerifying()->get($url);

            if ($response->successful()) {
                $data = $response->json();

                // Ambil data dari API
                $cityName = $data['name'];
                $weatherDesc = $data['weather'][0]['description'];
                $weatherIcon = $data['weather'][0]['icon'];

                $temp = $data['main']['temp'] . " °C";
                $humidity = $data['main']['humidity'] . " %";
                $windSpeed = $data['wind']['speed'] . " m/s";
                $feelsLike = $data['main']['feels_like'] . "°C";
                $pressure = $data['main']['pressure'] . " hPa";
                $cloudiness = $data['clouds']['all'] . "%";

                $value = [$temp, $humidity, $windSpeed];

                // Data tambahan
                $pressure = $data['main']['pressure'] . " hPa";
                $cloudiness = $data['clouds']['all'] . " %";
                $feelsLike = $data['main']['feels_like'] . " °C";

                return view('pages.envi.sense', compact(
                    'title',
                    'collection',
                    'value',
                    'cityName',
                    'weatherDesc',
                    'weatherIcon',
                    'pressure',
                    'cloudiness',
                    'feelsLike'
                ));
            } else {
                return view('pages.envi.sense')->withErrors('Gagal mengambil data cuaca.');
            }
        } catch (\Exception $e) {
            return view('pages.envi.sense')->withErrors('Terjadi kesalahan: ' . $e->getMessage());
        }
    }
}
