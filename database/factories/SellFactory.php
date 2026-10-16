<?php

namespace Database\Factories;

use App\Models\Client;
use App\Models\Comission;
use App\Models\Sell;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class SellFactory extends Factory
{
    protected $model = Sell::class;

    public function definition(): array
    {
        $user = User::factory()->create(); // Genera un usuario y lo asigna

        return [
            'price' => $this->faker->numberBetween(1, 100),
            'title' => $this->faker->sentence(3),
            'comission' => $this->faker->numberBetween(1, 20),
            'client_id' => Client::factory()->create(['user_id' => $user->id])->id, // Cliente del mismo usuario
            'user_id' => $user->id, // Asigna el usuario autenticado
        ];        
    }
}

