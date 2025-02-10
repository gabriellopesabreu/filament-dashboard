<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Order>
 */
class OrderFactory extends Factory
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
            'vehicle_id' => \App\Models\Vehicle::factory(),
            'status' => $this->faker->randomElement(['Aberto', 'Em andamento', 'Concluído']),
            'description' => $this->faker->sentence(),
            'valor_total' => $this->faker->randomFloat(2, 100, 1000),
            // 'customer_id' => \App\Models\Customer::factory(),
            // 'parts' => [
            //     [
            //         'part_id' => \App\Models\Part::factory(),
            //         'quantity' => $this->faker->numberBetween(1, 5),
            //     ],
            //     [
            //         'part_id' => \App\Models\Part::factory(),
            //         'quantity' => $this->faker->numberBetween(1, 5),
            //     ],
            //     [
            //         'part_id' => \App\Models\Part::factory(),
            //         'quantity' => $this->faker->numberBetween(1, 5),
            //     ],
            // ],
        ];
    }

    public function configure()
{
    return $this->afterCreating(function (\App\Models\Order $order) {
        $parts = \App\Models\Part::inRandomOrder()->limit(rand(1, 5))->get();
        foreach ($parts as $part) {
            $order->parts()->attach($part->id, ['quantity' => rand(1, 3)]);
        }
    });
}

}
