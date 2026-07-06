<?php

namespace Database\Factories;

use App\Models\Company;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<Company>
 */
class CompanyFactory extends Factory
{
    public function definition(): array
    {
        return [
            'name' => $this->faker->company(),
            'code' => strtoupper(Str::random(7)),
            'created_at' => fake()->dateTimeBetween('-5 weeks'),
            'description' => fake()->sentence(12),
        ];
    }
}
