<?php

namespace Database\Factories;

use App\Models\Tour;
use App\Models\Travel;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;

class TourFactory extends Factory
{
    protected $model = Tour::class;

    public function definition(): array
    {
        return [
            'travel_id' => Travel::factory(),
            'name' => $this->faker->name(),
            'starting_date' => Carbon::now(),
            'ending_date' => now()->addDays(rand(1, 10)),
            'price' => $this->faker->randomFloat(2, 10, 999),
        ];
    }
}
