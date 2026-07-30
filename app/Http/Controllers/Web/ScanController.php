<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Asset;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ScanController extends Controller
{
    public function index(): View
    {
        return view('scan.index');
    }

    public function lookup(Request $request)
    {
        $request->validate(['q' => 'required|string']);

        $q = trim($request->q);

        $asset = Asset::where('asset_tag', $q)
            ->orWhere('id', is_numeric($q) ? $q : 0)
            ->orWhere('serial', $q)
            ->first();

        if ($asset) {
            return redirect()->route('assets.show', $asset);
        }

        return redirect()->route('scan.index')
            ->with('error', "No asset found matching \"{$q}\".");
    }
}
