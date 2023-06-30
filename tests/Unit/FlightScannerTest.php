<?php

namespace Tests\Unit;

use App\Services\FlightScannerService;
use Database\Seeders\Utils;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use function collect;

final class FlightScannerTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @test
     */
    public function it_gets_min_price_over_direct_flights()
    {
        $service = new FlightScannerService();

        $flightsData = Utils::generateStaticDirectFlights(5, 3);

        $this->createAirportsAndFlightsFactories($flightsData);

        $this->assertEquals(3, $service->getMinPriceForDirectFlightsByAirportCodes('AAA', 'BBB'));

        // no flight
        $this->assertEquals(0, $service->getMinPriceForDirectFlightsByAirportCodes('ZZZ', 'XXX'));
    }

    /**
     * @test
     */
    public function it_gets_min_price_over_single_stopover_flights()
    {
        $service = new FlightScannerService();

        $flightsData = Utils::generateStaticSingleStopoverFlights(5, 3);

        $this->createAirportsAndFlightsFactories($flightsData);

        //     3   +  3   =   6
        // AAA -> CCC -> BBB
        $this->assertEquals(6, $service->getMinPriceForSingleStopoverFlightsByAirportCodes('AAA', 'BBB'));

        // no flights
        $this->assertEquals(0, $service->getMinPriceForDirectFlightsByAirportCodes('AAA', 'BBB'));

        $this->assertEquals(0, $service->getMinPriceForSingleStopoverFlightsByAirportCodes('ZZZ', 'XXX'));
    }

    /**
     * @test
     */
    public function it_gets_min_price_over_double_stopover_flights()
    {
        $service = new FlightScannerService();

        $flightsData = Utils::generateStaticDoubleStopoverFlights(5, 3);

        $this->createAirportsAndFlightsFactories($flightsData);

        //     3   +  3   +  3 =  9
        // AAA -> CCC -> DDD -> BBB
        $this->assertEquals(9, $service->getMinPriceForDoubleStopoverFlightsByAirportCodes('AAA', 'BBB'));

        // no flights
        $this->assertEquals(0, $service->getMinPriceForDirectFlightsByAirportCodes('AAA', 'BBB'));

        $this->assertEquals(0, $service->getMinPriceForSingleStopoverFlightsByAirportCodes('AAA', 'BBB'));

        $this->assertEquals(0, $service->getMinPriceForSingleStopoverFlightsByAirportCodes('ZZZ', 'XXX'));
    }

    /**
     * @test
     */
    public function it_handles_when_flights_are_missing()
    {
        $service = new FlightScannerService();

        $flightsData[] = [
            'dep' => 'AAA',
            'arr' => 'BBB',
            'price' => 100,
        ];

        $this->createAirportsAndFlightsFactories($flightsData);

        $this->assertNull($service->generalMinPriceOptimizedSearch('AAA', 'CCC'));
    }

    /**
     * @test
     */
    public function it_gets_min_over_all_flights()
    {
        $service = new FlightScannerService();

        $flightsData = Utils::generateStaticGenericFlightsWithRandomPrice(5, 10);

        $this->createAirportsAndFlightsFactories($flightsData);

        $this->assertEquals(8, $service->generalMinPriceOptimizedSearch('AAA', 'BBB'));
    }

    /**
     * @test
     */
    public function it_gets_min_over_all_flights_with_random_data_when_bet_flight_is_direct()
    {
        $service = new FlightScannerService();

        $flightsData = Utils::generateStaticGenericFlightsWithRandomPrice(10);

        // best price is in a direct flight: AAA -> BBB: price 1
        $flightsData[] = [
            'dep' => 'AAA',
            'arr' => 'BBB',
            'price' => 1,
        ];

        $this->createAirportsAndFlightsFactories($flightsData);

        $this->assertEquals(1, $service->generalMinPriceOptimizedSearch('AAA', 'BBB'));
    }

    /**
     * @test
     */
    public function it_gets_min_over_all_flights_with_random_data_when_best_flight_is_single_stopover()
    {
        $service = new FlightScannerService();

        $flightsData = Utils::generateStaticGenericFlightsWithRandomPrice(10);

        // best price is in a single stopover flight: AAA -> CCC -> BBB: price 2

        $flightsData[] = [
            'dep' => 'AAA',
            'arr' => 'CCC',
            'price' => 1,
        ];

        $flightsData[] = [
            'dep' => 'CCC',
            'arr' => 'BBB',
            'price' => 1,
        ];

        $this->createAirportsAndFlightsFactories($flightsData);

        $this->assertEquals(2, $service->generalMinPriceOptimizedSearch('AAA', 'BBB'));
    }

    /**
     * @test
     */
    public function it_gets_min_over_all_flights_with_random_data_when_best_flight_is_double_stopover()
    {
        $service = new FlightScannerService();

        $flightsData = Utils::generateStaticGenericFlightsWithRandomPrice(10);

        // best price is in a single stopover flight: AAA -> CCC -> DDD -> BBB: price 3

        $flightsData[] = [
            'dep' => 'AAA',
            'arr' => 'CCC',
            'price' => 1,
        ];

        $flightsData[] = [
            'dep' => 'CCC',
            'arr' => 'DDD',
            'price' => 1,
        ];

        $flightsData[] = [
            'dep' => 'DDD',
            'arr' => 'BBB',
            'price' => 1,
        ];

        $this->createAirportsAndFlightsFactories($flightsData);

        $this->assertEquals(3, $service->generalMinPriceOptimizedSearch('AAA', 'BBB'));
    }


    /**
     * @test
     */
    public function it_gets_min_over_all_flights_with_random_data()
    {
        $service = new FlightScannerService();

        $flightsData = Utils::generateGenericFlightsWithRandomPrice(100);

        $this->createAirportsAndFlightsFactories($flightsData);

        $departureCode = 'BBB';

        $arrivalCode = 'BBB';

        $minDirect = collect($service->getDirectFlightsByAirportCodesStructured($departureCode, $arrivalCode))->min('price');

        $minSingle = collect($service->getSingleStopoverFlightsByAirportCodesStructured($departureCode, $arrivalCode))->min('price');

        $minDouble = collect($service->getDoubleStopoverFlightsByAirportCodesStructured($departureCode, $arrivalCode))->min('price');

        $this->assertEquals(min($minDirect, $minSingle, $minDouble), $service->generalMinPriceOptimizedSearch($departureCode, $arrivalCode));
    }

}
