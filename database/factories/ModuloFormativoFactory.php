<?php

namespace Database\Factories;

use App\Models\CicloFormativo;
use App\Models\FamiliaProfesional;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User>
 */
class ModuloFormativoFactory extends Factory
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
            'ciclo_formativo_id' => CicloFormativo::factory(),
            'nombre' => fake()->word(),
            'codigo' => fake()->word(),
            'horas_totales' => fake()->numberBetween(100, 1000),
            'curso_escolar' => fake()->year(),
            'centro' => fake()->word(),
            'docente_id' => fake()->randomNumber(),
            'descripcion' => fake()->sentence(),
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
