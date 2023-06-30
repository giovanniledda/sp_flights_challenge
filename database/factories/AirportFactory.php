<?php

namespace Database\Factories;

use function fake;
use Illuminate\Database\Eloquent\Factories\Factory;

class AirportFactory extends Factory
{
    public function definition()
    {
        return [
            'name' => fake()->sentence(3),
            'code' => fake()->unique()->regexify('[A-Z]{3}'),
            'lat' => fake()->latitude(),
            'lng' => fake()->longitude(),
        ];
    }
}
