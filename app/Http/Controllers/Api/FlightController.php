<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\FlightScannerService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use function response;

class FlightController extends Controller
{
    public function index(Request $request, string $departureCode, string $arrivalCode, FlightScannerService $flightScanner)
    {
        Validator::make([
            'depCode' => $departureCode,
            'arrCode' => $arrivalCode,
        ], [
            'depCode' => 'exists:airports,code',
            'arrCode' => 'exists:airports,code',
        ])->validate();

        return response()
            ->json($flightScanner->aggregateDataForApi($departureCode, $arrivalCode));
    }
}
