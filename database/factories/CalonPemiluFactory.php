<?php

namespace Database\Factories;

use App\Models\CalonPemilu;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<CalonPemilu>
 */
class CalonPemiluFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'nama_calon' => fake()->name(),
            'nomor_urut' => fake()->unique()->numberBetween(1, 99),
            'slogan' => fake()->sentence(4),
            'visi_misi' => fake()->paragraph(),
            'foto' => null,
        ];
    }
}
