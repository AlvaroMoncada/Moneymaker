<?php

namespace Database\Seeders;

use App\Models\Sell;
use App\Models\Client;
use App\Models\Comission;
use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Crear usuarios con clientes, comisiones y ventas asociadas
        User::factory(6)->create()->each(function ($user) {
            $clients = Client::factory(3)->create(['user_id' => $user->id]); // Cada usuario tendrá 3 clientes
            $comissions = Comission::factory(3)->create(['user_id' => $user->id]); // Cada usuario tendrá 3 comisiones

            // Crear 3 ventas por usuario, asociadas a sus clientes y comisiones
            Sell::factory(3)->create([
                'user_id' => $user->id,
                'client_id' => $clients->random()->id, // Asigna aleatoriamente uno de sus clientes
                'comission_id' => $comissions->random()->id, // Asigna aleatoriamente una de sus comisiones
            ]);
        });
    }
}
