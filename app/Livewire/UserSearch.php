<?php

namespace App\Livewire;

use App\Models\User;
use Livewire\Component;

class UserSearch extends Component
{
    public $query = '';

    public function render()
    {
        $users = collect();

        if (strlen($this->query) >= 1) {
            $users = User::where(function($q) {
                    $q->where('tag', 'LIKE', "%{$this->query}%")
                      ->orWhere('nom', 'LIKE', "%{$this->query}%")
                      ->orWhere('prenom', 'LIKE', "%{$this->query}%");
                })
                ->limit(10)
                ->get();
        }

        return view('livewire.user-search', [
            'users' => $users
        ]);
    }
}
