<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Part>
 */
class PartFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'code' => $this->faker->unique()->bothify('P-#####'), // Código de peça único
            'name' => $this->faker->randomElement([
                'Filtro de Óleo', 'Bateria 60Ah', 'Pastilha de Freio', 
                'Correia Dentada', 'Velas de Ignição', 'Radiador', 'Amortecedor',
                'Pneu Aro 14', 'Pneu Aro 15', 'Pneu Aro 16', 'Pneu Aro 17',
            ]),
            'quantity' => $this->faker->numberBetween(5, 50),
            'price' => $this->faker->randomFloat(2, 50, 500),
            'description' => $this->faker->sentence,
        ];
    }
}
