<?php

namespace App\Http\Controllers\Page;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class SearchController extends Controller
{
    public function index()
    {
        return view('search.index');
    }

    public function search(Request $request)
    {
        $query = $request->get('query');

        // Validation
        if (strlen($query) < 3) {
            return response()->json([
                'results' => []
            ]);
        }

        // Recherche
        $results = User::where('searchable', true)
            ->where(function($q) use ($query) {
                $q->where('tag', 'LIKE', "%{$query}%")
                  ->orWhere('nom', 'LIKE', "%{$query}%")
                  ->orWhere('prenom', 'LIKE', "%{$query}%");
            })
            ->select('id', 'nom', 'prenom', 'tag', 'profile_photo_url') // Uniquement les champs nécessaires
            ->limit(10)
            ->get();

        return response()->json([
            'results' => $results
        ]);
    }
}
