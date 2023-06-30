<?php

namespace Database\Factories;

use function fake;
use Illuminate\Database\Eloquent\Factories\Factory;

class FlightFactory extends Factory
{
    public function definition()
    {
        return [
            'price' => fake()->randomNumber(5),
        ];
    }
}
