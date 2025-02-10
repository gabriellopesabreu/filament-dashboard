<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Vehicle>
 */
class VehicleFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'customer_id' => \App\Models\Customer::factory(),
            'brand' => $this->faker->randomElement(['Honda', 'Yamaha', 'Suzuki', 'Kawasaki']),
            'model' => $this->faker->word,
            'year' => $this->faker->year,
            'plate' => strtoupper($this->faker->unique()->bothify('???-####')),
            'color' => $this->faker->colorName,
            'observations' => $this->faker->sentence,
            'images' => [
                'https://via.placeholder.com/150',
                'https://via.placeholder.com/150',
                'https://via.placeholder.com/150',
            ],
        ];
    }
}
