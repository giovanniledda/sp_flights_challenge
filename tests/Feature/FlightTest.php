<?php

namespace Tests\Feature;

use App\Models\Airport;
use Database\Seeders\Utils;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Testing\Fluent\AssertableJson;
use Tests\TestCase;
use function ray;

class FlightTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @test
     */
    public function guests_can_access_flight_price_url()
    {
        $departureAirport = Airport::factory()->create();

        $arrivalAirport = Airport::factory()->create();

        $this->getJson("api/v1/flights/{$departureAirport->code}/{$arrivalAirport->code}")
            ->assertStatus(200);
    }

    /**
     * @test
     */
    public function flight_departure_code_must_be_valid()
    {

        $arrivalAirport = Airport::factory()->create();

        $this->getJson("api/v1/flights/XXX/{$arrivalAirport->code}")
            ->assertStatus(422)
            ->assertJsonValidationErrorFor('depCode');
    }

    /**
     * @test
     */
    public function flight_arrival_code_must_be_valid()
    {
        $departureAirport = Airport::factory()->create();

        $this->getJson("api/v1/flights/{$departureAirport->code}/XXX")
            ->assertStatus(422)
            ->assertJsonValidationErrorFor('arrCode');
    }

    /**
     * @test
     */
    public function flights_list_will_be_ordered_by_price_and_grouped_by_stopovers()
    {

        $flightsData = Utils::generateStaticGenericFlightsWithRandomPrice(5, 10);

        $this->createAirportsAndFlightsFactories($flightsData);

        $response = $this->getJson('api/v1/flights/AAA/BBB')
            ->assertStatus(200);

        ray($response->json());

        $response
            ->assertJson(fn (AssertableJson $json) => $json
                ->where('data.stopovers.0.0.price', 10)
                ->where('data.stopovers.0.0.stopover_codes', null)
                ->where('data.stopovers.1.0.price', 8)  // there is AAA -> CCC of price "5" and CCC -> BBB of price "3"
                ->where('data.stopovers.1.0.stopover_codes', 'CCC')
                ->where('data.stopovers.2.0.price', 9)
                ->where('data.stopovers.2.0.stopover_codes.0', 'DDD')
                ->where('data.stopovers.2.0.stopover_codes.1', 'CCC')
                ->etc()
            );
    }
}
