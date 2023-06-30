<?php

namespace Tests\Unit;

use App\Models\Airport;
use App\Models\Flight;
use function collect;
use Illuminate\Database\QueryException;
use Illuminate\Foundation\Testing\RefreshDatabase;
use function range;
use Tests\TestCase;

class FlightTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @test
     */
    public function it_has_a_departure_and_arrival_airport()
    {
        $departureAirport = Airport::factory()->create();

        $arrivalAirport = Airport::factory()->create();

        $flight = Flight::factory()->create([
            'code_departure' => $departureAirport->code,
            'code_arrival' => $arrivalAirport->code,
        ]);

        $this->assertEquals($departureAirport->code, $flight->departure_airport->code);

        $this->assertEquals($arrivalAirport->code, $flight->arrival_airport->code);
    }

    /**
     * @test
     */
    public function flights_with_same_departure_and_arrival_must_have_different_prices()
    {
        $departureAirport = Airport::factory()->create();

        $arrivalAirport = Airport::factory()->create();

        collect(range(1, 15))->each(function (int $index) use ($arrivalAirport, $departureAirport) {

            Flight::factory()->create([
                'code_departure' => $departureAirport->code,
                'code_arrival' => $arrivalAirport->code,
                'price' => $index * 100,
            ]);
        });

        $this->assertCount(15, Flight::all());

        $this->expectException(QueryException::class);

        Flight::factory()->create([
            'code_departure' => $departureAirport->code,
            'code_arrival' => $arrivalAirport->code,
            'price' => 100,
        ]);

        $this->assertCount(15, Flight::all());
    }
}
