<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User>
 */
class UserFactory extends Factory
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
            'prenom' => fake()->firstName(),
            'nom' => fake()->lastName(),
            'email' => fake()->unique()->safeEmail(),
            'email_verified_at' => now(),
            'password' => static::$password ??= Hash::make('password'),
            'remember_token' => Str::random(10),
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

    /**
     * Configure the model factory for Elouan admin.
     */
    public function elouanAdmin(): static
    {
        return $this->state(fn (array $attributes) => [
            'prenom' => 'Elouan',
            'tag' => 'elouan_getout',
            'nom' => 'Tusseau',
            'email' => 'tusseauelouan@gmail.com',
            'password' => Hash::make('#20Admin@Elouan25$'),
        ]);
    }

    /**
     * Configure the model factory for Felis admin.
     */
    public function felisAdmin(): static
    {
        return $this->state(fn (array $attributes) => [
            'prenom' => 'Félis',
            'tag' => 'felis_getout',
            'nom' => 'Maillard',
            'email' => 'felis.maillard@gmail.com',
            'password' => Hash::make('*02FelisAdmin2025$'),
        ]);
    }

    /**
     * Configure the model factory for main admin.
     */
    public function mainAdmin(): static
    {
        return $this->state(fn (array $attributes) => [
            'prenom' => 'Admin',
            'tag' => 'admin_getout',
            'nom' => 'Admin',
            'email' => 'admin@getout.fr',
            'password' => Hash::make('*02FeNalAdmin#Getout@2025$'),
        ]);
    }
}
