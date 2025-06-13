<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Theme;

class ThemeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Créer des thèmes par défaut
        Theme::create([
            'name' => 'Informatique',
            'description' => 'Thème pour les serveurs liés à l\'informatique et à la technologie.',
            'color' => '#1a1a1a',
        ]);

        Theme::create([
            'name' => 'Jeux Vidéo',
            'description' => 'Thème pour les serveurs de jeux vidéo.',
            'color' => '#ff4500',
        ]);

        Theme::create([
            'name' => 'Musique',
            'description' => 'Thème pour les serveurs de musique et d\'artistes.',
            'color' => '#1db954',
        ]);

        Theme::create([
            'name' => 'Sports',
            'description' => 'Thème pour les serveurs de sports et d\'activités physiques.',
            'color' => '#007bff',
        ]);
    }
}
