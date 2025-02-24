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
            'status' => $this->faker->randomElement([0, 1, 2]),
            'description' => $this->faker->sentence(),
            'service_price' => $this->faker->randomFloat(2, 50, 500), 
            'total_parts_price' => 0, 
            'final_total' => 0,
            

        ];
    }

    public function configure()
    {
        return $this->afterCreating(function (\App\Models\Order $order) {
            $parts = \App\Models\Part::inRandomOrder()->limit(rand(1, 5))->get();
            $totalPartsPrice = 0;
            foreach ($parts as $part) {
                $quantity = rand(1, 3);
                $unitPrice = $part->price;
                $totalPrice = $quantity * $unitPrice;

                // Associando peça à ordem com preço unitário correto
                $order->parts()->attach($part->id, [
                    'quantity' => $quantity,
                    'unit_price' => $unitPrice,
                    'total_price' => $totalPrice,
                ]);

                // Atualiza o total das peças
                $totalPartsPrice += $totalPrice;
            }

            // Atualiza os valores na order
            $order->update([
                'total_parts_price' => $totalPartsPrice,
                'final_total' => $totalPartsPrice + $order->service_price,
            ]);
        });
    }

}
