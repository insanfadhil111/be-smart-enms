<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PvController;
use App\Http\Controllers\MdpController;
use App\Http\Controllers\ResetPassword;
use App\Http\Controllers\ChangePassword;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\PageController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\WaterController;
use App\Http\Controllers\EnergyController;
use App\Http\Controllers\LightsController;
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\SecurityController;
use App\Http\Controllers\EnmsReportController;
use App\Http\Controllers\EnvironmentController;
use App\Http\Controllers\IkeController;
use App\Http\Controllers\SubdataController;
use App\Http\Controllers\UserProfileController;

Route::get('/', function () {
	return redirect('/dashboard');
})->middleware('auth');

Route::get('/register', [RegisterController::class, 'create'])->middleware('guest')->name('register');
Route::post('/register', [RegisterController::class, 'store'])->middleware('guest')->name('register.perform');
Route::get('/login', [LoginController::class, 'show'])->middleware('guest')->name('login');
Route::post('/login', [LoginController::class, 'login'])->middleware('guest')->name('login.perform');


Route::group(['middleware' => 'auth'], function () {
	Route::get('/dashboard', [HomeController::class, 'index'])->name('home')->middleware('auth');

	Route::get('/energy-monitor', [EnergyController::class, 'monitor'])->name('energy-monitor');
	Route::get('/energy-control', [EnergyController::class, 'showControl'])->name('energy-control');
	Route::get('/energy-stats', [EnergyController::class, 'stats'])->name('energy-stats');

	Route::get('/nre', [PvController::class, 'nreIndex'])->name('nreIndex');

	// Route::get('/standar-ike', [EnergyController::class, 'standarIke'])->name('standar-ike');
	Route::get('/standar-ike', [IkeController::class, 'index'])->name('standar-ike');

	Route::get('/water-monitor', [WaterController::class, 'monitor'])->name('water-monitor');

	Route::get('/enms-report', [EnmsReportController::class, 'index'])->name('enms-report');

	// Settings
	Route::get('subdatas', [SubdataController::class, 'index'])->name('subdatas.index');
	Route::put('subdatas', [SubdataController::class, 'update'])->name('subdatas.update');
	Route::get('subdatas/reset', [SubdataController::class, 'reset'])->name('subdatas.reset');

	Route::get('/security-camera', [SecurityController::class, 'index'])->name('security-camera');
	Route::get('/security-doorlock', [SecurityController::class, 'doorlock'])->name('security-doorlock');

	// Device Control
	Route::get('switch-mdp/{id}', [MdpController::class, 'switchMdp'])->name('switch-mdp');
	Route::get('switch-light/{id}', [EnvironmentController::class, 'switchLight'])->name('switch-light');

	/* Export */
	Route::get('/export-monthly-kwh', [EnergyController::class, 'exportMonthlyKwh'])->name('export-monthly-kwh');

	Route::get('/envi-sense', [EnvironmentController::class, 'monitor'])->name('envi-sense');
	Route::get('/envi-lights', [LightsController::class, 'showControl'])->name('envi-lights');

	Route::get('/reset-password', [ResetPassword::class, 'show'])->middleware('guest')->name('reset-password');
	Route::post('/reset-password', [ResetPassword::class, 'send'])->middleware('guest')->name('reset.perform');
	Route::get('/change-password', [ChangePassword::class, 'show'])->middleware('guest')->name('change-password');
	Route::post('/change-password', [ChangePassword::class, 'update'])->middleware('guest')->name('change.perform');
	Route::get('/profile', [UserProfileController::class, 'show'])->name('profile');
	Route::post('/profile', [UserProfileController::class, 'update'])->name('profile.update');
	Route::get('/profile-static', [PageController::class, 'profile'])->name('profile-static');
	Route::get('/sign-in-static', [PageController::class, 'signin'])->name('sign-in-static');
	Route::get('/sign-up-static', [PageController::class, 'signup'])->name('sign-up-static');
	Route::get('/pages/{page}', [PageController::class, 'index'])->name('page');
	Route::post('logout', [LoginController::class, 'logout'])->name('logout');
});

Route::fallback(function () {
	abort(404);
})->name('404');
