<?php

namespace Database\Factories;

use App\Models\Travel;
use Illuminate\Database\Eloquent\Factories\Factory;

class TravelFactory extends Factory
{
    protected $model = Travel::class;

    public function definition(): array
    {
        return [
            'name' => $this->faker->name,
            'is_public' => $this->faker->boolean,
            'description' => $this->faker->text(100),
            'number_of_days' => rand(1,9),
        ];
    }
}
