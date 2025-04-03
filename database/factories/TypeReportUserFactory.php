<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\TypeReportUser>
 */
class TypeReportUserFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => $this->faker->randomElement(['harassment', 'spam', 'inappropriate_content']),
            'description' => $this->faker->sentence(),
            'active' => $this->faker->boolean(80),
        ];
    }
}
