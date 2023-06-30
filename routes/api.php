<?php

use App\Http\Controllers\Api\FlightController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::get('/flights/{depCode}/{arrCode}', [FlightController::class, 'index'])
    ->where([
        'depCode' => '[A-Z]{3,4}',
        'arrCode' => '[A-Z]{3,4}',
    ])
    ->name('api.flights.index');
