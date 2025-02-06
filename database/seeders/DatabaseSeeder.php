<?php

namespace Database\Seeders;

use App\Models\ServerMember;
use App\Models\User;
use App\Models\Server;
use App\Models\Channel;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1. Créer les utilisateurs
        if (User::count() === 0) {
            User::factory()->mainAdmin()->create();
            User::factory()->felisAdmin()->create();
            User::factory()->elouanAdmin()->create();
            User::factory(10)->create();
        }

        // Récupérer les IDs des admins via leurs emails
        $mainAdminId = User::where('email', 'admin@getout.fr')->first()->id;
        $felisId = User::where('email', 'felis.maillard@gmail.com')->first()->id;
        $elouanId = User::where('email', 'tusseauelouan@gmail.com')->first()->id;

        // 2. Créer le serveur avec l'ID de l'admin principal
        if (Server::count() === 0) {
            $server = Server::create([
                'name' => 'GetOut Team',
                'slug' => 'getout-team',
                'description' => 'Serveur de l\'équipe GetOut',
                'owner_id' => $mainAdminId,
                'privacy_type' => 'private'
            ]);
        }

        // 3. Créer le channel général
        if (Channel::count() === 0) {
            Channel::create([
                'name' => 'Général',
                'server_id' => $server->id
            ]);
        }

        // 4. Créer les membres du serveur
        if (ServerMember::count() === 0) {
            // Administrateur principal
            ServerMember::create([
                'server_id' => $server->id,
                'user_id' => $mainAdminId,
                'role' => 'owner'
            ]);

            // Félis admin
            ServerMember::create([
                'server_id' => $server->id,
                'user_id' => $felisId,
                'role' => 'admin'
            ]);

            // Elouan admin
            ServerMember::create([
                'server_id' => $server->id,
                'user_id' => $elouanId,
                'role' => 'admin'
            ]);
        }
    }
}
