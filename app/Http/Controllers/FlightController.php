<?php

namespace App\Http\Controllers;

use App\Http\Requests\FlightRequest;
use App\Models\Airport;
use App\Services\FlightScannerService;
use function compact;
use function response;

class FlightController extends Controller
{
    public function index()
    {
        $airportCodes = Airport::getCodes();

        return response()->view('flights.index', compact('airportCodes'));
    }

    public function search(FlightRequest $request, FlightScannerService $flightScanner)
    {
        $departureCode = $request->get('depCode');

        $arrivalCode = $request->get('arrCode');

        $bestPrice = $flightScanner->generalMinPriceOptimizedSearch($departureCode, $arrivalCode);

        // TODO: will be good to handle pagination, but not for now!
        $flightsData = $flightScanner->aggregateDataForApi($departureCode, $arrivalCode);

        return response()->view('flights.search-results', compact('flightsData', 'departureCode', 'arrivalCode', 'bestPrice'));
    }
}
