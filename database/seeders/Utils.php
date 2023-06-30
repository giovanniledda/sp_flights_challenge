<?php

namespace Database\Seeders;

use Illuminate\Support\Arr;
use function fake;
use function floor;
use function mt_rand;

// FIXME: code is not DRY, but I need it this way in order to have clearer test
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

  public static function generateStaticGenericFlightsWithRandomPrice(int $totalFlights, ?int $priceParam = null): array
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

    public static function generateStaticDoubleStopoverFlights(int $totalFlights, ?int $priceParam = null): array
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

    public static function generateStaticSingleStopoverFlights(int $totalFlights, ?int $priceParam = null): array
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

    public static function generateStaticDirectFlights(int $totalFlights, ?int $priceParam = null): array
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
}
