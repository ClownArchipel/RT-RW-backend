<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class ProductFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'name' => $this->faker->word(8),
            'price' => $this->faker->numberBetween(2000, 100000),
            'duration'=>rand(1,5),
        ];
    }
}
