<?php

namespace Database\Factories;

use App\Models\FotoDokumentasi;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<FotoDokumentasi>
 */
class FotoDokumentasiFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'judul' => fake()->sentence(3),
            'keterangan' => fake()->optional()->sentence(),
            'foto' => 'dokumentasi/'.fake()->uuid().'.jpg',
        ];
    }
}
