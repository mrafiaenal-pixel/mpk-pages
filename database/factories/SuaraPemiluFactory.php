<?php

namespace Database\Factories;

use App\Models\CalonPemilu;
use App\Models\SuaraPemilu;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<SuaraPemilu>
 */
class SuaraPemiluFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'id_users' => User::factory(),
            'id_calon' => CalonPemilu::factory(),
        ];
    }
}
