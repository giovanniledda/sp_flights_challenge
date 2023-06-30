<?php

namespace Database\Seeders;

use App\Models\Airport;
use App\Models\Flight;
use Illuminate\Database\Seeder;

class FlightSeeder extends Seeder
{
    public function run(): void
    {
        foreach (Utils::generateGenericFlightsWithRandomPrice(100) as $flight) {

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
