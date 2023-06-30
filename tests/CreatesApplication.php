<?php

namespace Tests;

use App\Models\Airport;
use App\Models\Flight;
use Illuminate\Contracts\Console\Kernel;
use Illuminate\Foundation\Application;

trait CreatesApplication
{
    /**
     * Creates the application.
     */
    public function createApplication(): Application
    {
        $app = require __DIR__.'/../bootstrap/app.php';

        $app->make(Kernel::class)->bootstrap();

        return $app;
    }

    public function createAirportsAndFlightsFactories(array $flightsData): void
    {
        foreach ($flightsData as $flight) {

            try {
                $departureAirport = Airport::factory()->create([
                    'code' => $flight['dep'],
                ]);
            } catch (\Exception $e) {

            }

            try {
                $arrivalAirport = Airport::factory()->create([
                    'code' => $flight['arr'],
                ]);
            } catch (\Exception $e) {

            }

            try {
                Flight::factory()->create([
                    'code_departure' => $flight['dep'],
                    'code_arrival' => $flight['arr'],
                    'price' => $flight['price'],
                ]);
            } catch (\Exception $e) {

            }
        }
    }
}
