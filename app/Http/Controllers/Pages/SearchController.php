<?php

namespace App\Http\Controllers\Pages;

use App\Http\Controllers\Controller;

class SearchController extends Controller
{
    public function index()
    {
        return view('search.index');
    }
}
