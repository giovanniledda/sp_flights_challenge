<?php

namespace Database\Seeders;

use Illuminate\Support\Arr;
use function fake;
use function floor;
use function mt_rand;

final class Utils
{
    public static array $airports = ['AAA', 'BBB', 'CCC', 'DDD', 'EEE', 'FFF', 'GGG', 'HHH', 'III', 'LLL', 'MMM', 'NNN'];

    public static function generateGenericFlightsWithRandomPrice(int $totalFlights, ?int $priceParam = null): array
    {
        $result = [];

        for ($i = 0; $i < $totalFlights; $i++) {

            $price = $priceParam ?: mt_rand(50, 200);

            $airport1 = fake()->randomElement(self::$airports);

            $airport2 = fake()->randomElement(Arr::except(self::$airports, $airport1));

            $airport3 = fake()->randomElement(Arr::except(self::$airports, [$airport1, $airport2]));

            $airport4 = fake()->randomElement(Arr::except(self::$airports, [$airport1, $airport2, $airport3]));

            // Generate direct flights
            $result[] = [
                'dep' => $airport1,
                'arr' => $airport2,
                'price' => $price,
            ];

            // one step-over  AAA -> CCC -> BBB, price * 2
            $result[] = [
                'dep' => $airport1,
                'arr' => $airport3,
                'price' => floor($price / 2),
            ];

            $result[] = [
                'dep' => $airport3,
                'arr' => $airport2,
                'price' => floor($price / 2),
            ];

            // two step-over  AAA -> DDD -> CCC -> BBB, price * 3
            $result[] = [
                'dep' => $airport1,
                'arr' => $airport4,
                'price' => floor($price / 3),
            ];

            $result[] = [
                'dep' => $airport4,
                'arr' => $airport3,
                'price' => floor($price / 3),
            ];

            $result[] = [
                'dep' => $airport3,
                'arr' => $airport2,
                'price' => floor($price / 3),
            ];
        }

        return $result;
    }
}
