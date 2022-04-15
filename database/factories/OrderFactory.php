<?php

namespace Database\Factories;

use App\Models\Order;
use Illuminate\Database\Eloquent\Factories\Factory;

class OrderFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Order::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'number' => $this->faker->randomNumber(8),
            'total_price' => $this->faker->numberBetween(25000, 200000),
            'quantity' => $this->faker->numberBetween(1, 10),
            'user_id'=>rand(1,10),
            'product_id'=>rand(1,10),
            'payment_status' => 1,
        ];
    }
}
