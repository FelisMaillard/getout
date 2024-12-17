<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        if (User::count() === 0) {
            User::factory()->elouanAdmin()->create();
            User::factory()->felisAdmin()->create();
            User::factory()->mainAdmin()->create();

            // Optionnel: Création d'utilisateurs standard supplémentaires
            // User::factory(10)->create();
        }
    }
}
