<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Asset;
use Illuminate\Http\Request;
use Illuminate\View\View;

class SearchController extends Controller
{
    public function index(Request $request): View
    {
        $query = $request->get('q');

        $assets = collect();
        if ($query && strlen($query) >= 2) {
            $assets = Asset::with('category')
                ->where(function ($q) use ($query) {
                    $q->where('name', 'like', "%{$query}%")
                      ->orWhere('asset_tag', 'like', "%{$query}%")
                      ->orWhere('serial', 'like', "%{$query}%")
                      ->orWhere('brand', 'like', "%{$query}%")
                      ->orWhere('model', 'like', "%{$query}%")
                      ->orWhere('location', 'like', "%{$query}%");
                })
                ->latest()
                ->take(20)
                ->get();
        }

        return view('search.index', compact('query', 'assets'));
    }
}
