<?php

namespace Tests;

use function array_rand;
use function floor;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use function mt_rand;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;

    // TODO: code is not DRY, but I need it this way in order to have clearer test

    public function generateStaticDirectFlights(int $totalFlights, ?int $priceParam = null): array
    {
        $result = [];

        for ($i = 0; $i < $totalFlights; $i++) {

            $price = $priceParam ?: mt_rand(50, 200);

            $result[] = [
                'dep' => 'AAA',
                'arr' => 'BBB',
                'price' => $price,
            ];

            $result[] = [
                'dep' => 'AAA',
                'arr' => 'BBB',
                'price' => $price * 2,
            ];

            $result[] = [
                'dep' => 'AAA',
                'arr' => 'BBB',
                'price' => $price * 3,
            ];
        }

        return $result;
    }

    public function generateStaticSingleStopoverFlights(int $totalFlights, ?int $priceParam = null): array
    {
        $result = [];

        for ($i = 0; $i < $totalFlights; $i++) {

            $price = $priceParam ?: mt_rand(50, 200);

            // flight 1: AAA -> CCC -> BBB
            $result[] = [
                'dep' => 'AAA',
                'arr' => 'CCC',
                'price' => $price,
            ];

            $result[] = [
                'dep' => 'CCC',
                'arr' => 'BBB',
                'price' => $price,
            ];

            // flight 2: AAA -> CCC -> BBB, price * 2
            $result[] = [
                'dep' => 'AAA',
                'arr' => 'CCC',
                'price' => $price * 2,
            ];

            $result[] = [
                'dep' => 'CCC',
                'arr' => 'BBB',
                'price' => $price * 2,
            ];

            // flight 3: AAA -> CCC -> BBB, price * 3
            $result[] = [
                'dep' => 'AAA',
                'arr' => 'CCC',
                'price' => $price * 3,
            ];

            $result[] = [
                'dep' => 'CCC',
                'arr' => 'BBB',
                'price' => $price * 3,
            ];

        }

        return $result;
    }

    public function generateStaticDoubleStopoverFlights(int $totalFlights, ?int $priceParam = null): array
    {
        $result = [];

        for ($i = 0; $i < $totalFlights; $i++) {

            $price = $priceParam ?: mt_rand(50, 200);

            // flight 1: AAA -> CCC -> DDD -> BBB
            $result[] = [
                'dep' => 'AAA',
                'arr' => 'CCC',
                'price' => $price,
            ];

            $result[] = [
                'dep' => 'CCC',
                'arr' => 'DDD',
                'price' => $price,
            ];

            $result[] = [
                'dep' => 'DDD',
                'arr' => 'BBB',
                'price' => $price,
            ];

            // flight 2: AAA -> CCC -> DDD -> BBB, price * 2
            $result[] = [
                'dep' => 'AAA',
                'arr' => 'CCC',
                'price' => $price * 2,
            ];

            $result[] = [
                'dep' => 'CCC',
                'arr' => 'DDD',
                'price' => $price * 2,
            ];

            $result[] = [
                'dep' => 'DDD',
                'arr' => 'BBB',
                'price' => $price * 2,
            ];

            // flight 3: AAA -> CCC -> DDD -> BBB, price * 3
            $result[] = [
                'dep' => 'AAA',
                'arr' => 'CCC',
                'price' => $price * 3,
            ];

            $result[] = [
                'dep' => 'CCC',
                'arr' => 'DDD',
                'price' => $price * 3,
            ];

            $result[] = [
                'dep' => 'DDD',
                'arr' => 'BBB',
                'price' => $price * 3,
            ];
        }

        return $result;
    }

    public function generateStaticGenericFlightsWithRandomPrice(int $totalFlights, ?int $priceParam = null): array
    {

        $result = [];

        for ($i = 0; $i < $totalFlights; $i++) {

            $price = $priceParam ?: mt_rand(50, 200);

            // Generate direct flights
            $result[] = [
                'dep' => 'AAA',
                'arr' => 'BBB',
                'price' => $price,
            ];

            // one step-over  AAA -> CCC -> BBB, price * 2
            $result[] = [
                'dep' => 'AAA',
                'arr' => 'CCC',
                'price' => floor($price / 2),
            ];

            $result[] = [
                'dep' => 'CCC',
                'arr' => 'BBB',
                'price' => floor($price / 2),
            ];

            // two step-over  AAA -> DDD -> CCC -> BBB, price * 3
            $result[] = [
                'dep' => 'AAA',
                'arr' => 'DDD',
                'price' => floor($price / 3),
            ];

            $result[] = [
                'dep' => 'DDD',
                'arr' => 'CCC',
                'price' => floor($price / 3),
            ];

            $result[] = [
                'dep' => 'CCC',
                'arr' => 'BBB',
                'price' => floor($price / 3),
            ];
        }

        return $result;
    }

    public function generateGenericFlights(int $totalFlights): array
    {
        $airports = ['AAA', 'BBB', 'CCC', 'DDD', 'EEE', 'FFF', 'GGG', 'HHH', 'III', 'LLL', 'MMM', 'NNN'];

        $directFlights = 10;
        $stopoverFlights = 10;
        $remainingFlights = $totalFlights - ($directFlights + $stopoverFlights);

        $result = [];

        // Generate direct flights
        for ($i = 0; $i < $directFlights; $i++) {
            $depAirport = $airports[array_rand($airports)];
            $arrAirport = $airports[array_rand($airports)];

            // Avoid generating flights where departure airport is equal to arrival airport
            while ($depAirport === $arrAirport) {
                $arrAirport = $airports[array_rand($airports)];
            }

            $price = mt_rand(50, 200); // Generate a random price between 50 and 200

            $result[] = [
                'dep' => $depAirport,
                'arr' => $arrAirport,
                'price' => $price,
            ];
        }

        // Generate one-stopover flights
        for ($i = 0; $i < $stopoverFlights; $i++) {
            $depAirport = $airports[array_rand($airports)];
            $arrAirport = $airports[array_rand($airports)];

            // Find a transit airport different from the departure and arrival airports
            do {
                $transitAirport = $airports[array_rand($airports)];
            } while ($transitAirport === $depAirport || $transitAirport === $arrAirport);

            $price = mt_rand(50, 200); // Generate a random price between 50 and 200

            // Generate the one-stopover flight as two separate direct flights
            $result[] = [
                'dep' => $depAirport,
                'arr' => $transitAirport,
                'price' => $price,
            ];

            $result[] = [
                'dep' => $transitAirport,
                'arr' => $arrAirport,
                'price' => $price,
            ];
        }

        // Generate remaining flights
        for ($i = 0; $i < $remainingFlights; $i++) {
            $depAirport = $airports[array_rand($airports)];
            $arrAirport = $airports[array_rand($airports)];

            // Avoid generating flights where departure airport is equal to arrival airport
            while ($depAirport === $arrAirport) {
                $arrAirport = $airports[array_rand($airports)];
            }

            $price = mt_rand(50, 200); // Generate a random price between 50 and 200

            $result[] = [
                'dep' => $depAirport,
                'arr' => $arrAirport,
                'price' => $price,
            ];
        }

        return $result;
    }
}
