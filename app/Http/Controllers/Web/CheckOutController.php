<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Asset;
use App\Models\CheckOut;
use App\Models\ActivityLog;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class CheckOutController extends Controller
{
    public function index(Request $request): View
    {
        $query = CheckOut::with('asset', 'user');

        if ($request->status === 'active') {
            $query->whereNull('returned_at');
        } elseif ($request->status === 'returned') {
            $query->whereNotNull('returned_at');
        }

        if ($request->search) {
            $query->whereHas('asset', function ($q) use ($request) {
                $q->where('name', 'like', "%{$request->search}%")
                  ->orWhere('asset_tag', 'like', "%{$request->search}%");
            });
        }

        $checkouts = $query->latest()->paginate(15);
        return view('checkouts.index', compact('checkouts'));
    }

    public function create(): View
    {
        $assets = Asset::where('status', 'active')->get();
        return view('checkouts.create', compact('assets'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'asset_id' => ['required', 'exists:assets,id'],
            'assignee_name' => ['required', 'string', 'max:255'],
            'department' => ['nullable', 'string', 'max:255'],
            'purpose' => ['nullable', 'string', 'max:255'],
            'destination' => ['nullable', 'string', 'max:255'],
            'expected_return' => ['nullable', 'date'],
            'notes' => ['nullable', 'string'],
        ]);

        $validated['user_id'] = Auth::id();

        $checkout = CheckOut::create($validated);

        $checkout->asset->update(['status' => 'checked_out']);

        ActivityLog::create([
            'type' => 'asset_checked_out',
            'asset_id' => $checkout->asset_id,
            'user_id' => Auth::id(),
            'description' => "Checked out {$checkout->asset->name} to {$checkout->assignee_name}",
        ]);

        return redirect()->route('checkouts.index')->with('success', 'Asset checked out successfully.');
    }

    public function show(CheckOut $checkout): View
    {
        $checkout->load('asset', 'user');
        return view('checkouts.show', compact('checkout'));
    }

    public function returnForm(CheckOut $checkout): View
    {
        $checkout->load('asset');
        return view('checkouts.return', compact('checkout'));
    }

    public function returnAsset(Request $request, CheckOut $checkout): RedirectResponse
    {
        $request->validate([
            'return_notes' => ['nullable', 'string'],
        ]);

        $checkout->update([
            'returned_at' => now(),
            'return_notes' => $request->return_notes,
        ]);

        $checkout->asset->update(['status' => 'active']);

        ActivityLog::create([
            'type' => 'asset_checked_in',
            'asset_id' => $checkout->asset_id,
            'user_id' => Auth::id(),
            'description' => "Checked in {$checkout->asset->name} from {$checkout->assignee_name}",
        ]);

        return redirect()->route('checkouts.index')->with('success', 'Asset checked in successfully.');
    }
}
