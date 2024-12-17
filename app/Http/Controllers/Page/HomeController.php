<?php

namespace App\Http\Controllers\Page;

use App\Http\Controllers\Controller;
use App\Models\User;

class HomeController extends Controller
{
    public function index()
    {
        $users = User::all();
        return view('pages.home', ['users' => $users]); // Notez le changement ici: 'pages.home'
    }
}
