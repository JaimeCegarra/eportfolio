<?php

namespace Database\Factories;

use App\Models\CicloFormativo;
use App\Models\FamiliaProfesional;
use App\Models\ModuloFormativo;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User>
 */
class ResultadoAprendizajeFactory extends Factory
{
    /**
     * The current password being used by the factory.
     */
    protected static ?string $password;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'modulo_formativo_id' => ModuloFormativo::factory(),
            'codigo' => fake()->word(),
            'descripcion' => fake()->sentence(),
            'peso_porcentaje' => fake()->numberBetween(1, 100),
            'orden' => fake()->numberBetween(1, 10),
        ];
    }

    /**
     * Indicate that the model's email address should be unverified.
     */
    public function unverified(): static
    {
        return $this->state(fn (array $attributes) => [
            'email_verified_at' => null,
        ]);
    }
}
