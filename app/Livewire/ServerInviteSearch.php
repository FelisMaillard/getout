<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class ServerInviteSearch extends Component
{
    public $server;
    public $search = '';
    public $friends = [];

    public function mount($server)
    {
        $this->server = $server;
    }

    public function updatedSearch()
    {
        if (strlen($this->search) >= 1) {
            $this->friends = Auth::user()->friends()
                ->whereHas('friend', function ($query) {
                    $query->where('tag', 'LIKE', "%{$this->search}%")
                        ->orWhere('nom', 'LIKE', "%{$this->search}%")
                        ->orWhere('prenom', 'LIKE', "%{$this->search}%");
                })
                ->whereHas('friend', function ($query) {
                    $query->whereDoesntHave('memberServers', function ($subQuery) {
                        $subQuery->where('server_id', $this->server->id);
                    });
                })
                ->with('friend')
                ->get();
        } else {
            $this->friends = [];
        }
    }

    public function invite($friendId)
    {
        // Validation de base
        if (!$this->server->members()->where('user_id', $friendId)->exists()) {
            // Vérifier si une invitation existe déjà
            $existingInvite = $this->server->invites()
                ->where('invitee_id', $friendId)
                ->whereNull('accepted_at')
                ->whereNull('rejected_at')
                ->where(function ($query) {
                    $query->whereNull('expires_at')
                        ->orWhere('expires_at', '>', now());
                })
                ->exists();

            if (!$existingInvite) {
                $this->server->invites()->create([
                    'inviter_id' => Auth::id(),
                    'invitee_id' => $friendId,
                    'expires_at' => now()->addDays(30),
                ]);

                $this->dispatch('invite-sent');
            }
        }
    }

    public function render()
    {
        return view('livewire.server-invite-search');
    }
}
