<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Customer;
use App\Models\Part;
use App\Models\Vehicle;
use App\Models\Order;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        // User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);

        Part::factory(20)->create(); // Cria 20 peças no estoque

        Customer::factory(10)->has(
            Vehicle::factory()->count(2)->has(
                Order::factory()->count(3)
            )
        )->create();
    }
}
