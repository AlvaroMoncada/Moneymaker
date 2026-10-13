<?php

namespace Database\Factories;

use App\Models\Comission;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class ComissionFactory extends Factory
{
    protected $model = Comission::class;

    public function definition()
    {
        return [
            'comissionPercent' => $this->faker->randomFloat(2, 0, 100), // Genera un porcentaje entre 0 y 100 con dos decimales
            'user_id' => User::factory(), // Asigna automáticamente un usuario aleatorio
        ];
    }    
}

