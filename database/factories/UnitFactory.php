<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Unit>
 */
class UnitFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'trade_name' => fake()->name(),
            'legal_name' => fake()->company(),
            'cnpj' => fake('pt_BR')->cnpj(false),
            'brand_id' => 1,
        ];
    }
}
