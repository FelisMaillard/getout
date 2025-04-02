<?php

namespace Database\Seeders;

use App\Models\TypeReportUser;
use Illuminate\Database\Seeder;

class TypeReportUserSeeder extends Seeder
{
    public function run(): void
    {
        $types = [
            [
                'name' => 'harassment',
                'description' => 'Harcèlement ou intimidation',
                'active' => true
            ],
            [
                'name' => 'inappropriate_content',
                'description' => 'Contenu inapproprié ou offensant',
                'active' => true
            ],
            [
                'name' => 'spam',
                'description' => 'Spam ou contenu commercial non sollicité',
                'active' => true
            ],
            [
                'name' => 'fake_account',
                'description' => 'Compte factice ou usurpation d\'identité',
                'active' => true
            ],
            [
                'name' => 'hate_speech',
                'description' => 'Propos haineux ou discriminatoires',
                'active' => true
            ],
            [
                'name' => 'other',
                'description' => 'Autre violation des conditions d\'utilisation',
                'active' => true
            ],
        ];

        foreach ($types as $type) {
            TypeReportUser::create($type);
        }
    }
}
