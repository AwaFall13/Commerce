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
    public function definition(): array
    {
        return [
            'name' => $this->faker->word(),
            'description' => $this->faker->sentence(),
            'price' => $this->faker->randomFloat(2, 5, 200),
            'stock' => $this->faker->numberBetween(1, 100),
            'image' => $this->faker->imageUrl(200, 200, 'products'),
            'category_id' => 1, // à ajuster dans le seeder
        ];
    }
}
