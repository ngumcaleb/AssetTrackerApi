<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Asset;
use App\Models\Category;
use App\Models\ActivityLog;
use Endroid\QrCode\QrCode;
use Endroid\QrCode\Writer\PngWriter;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\View\View;

class AssetController extends Controller
{
    public function index(Request $request): View
    {
        $query = Asset::with('category', 'creator', 'currentCheckout')->whereNotIn('status', ['archived', 'discarded']);

        if ($request->search) {
            $query->where(function ($q) use ($request) {
                $q->where('name', 'like', "%{$request->search}%")
                  ->orWhere('asset_tag', 'like', "%{$request->search}%")
                  ->orWhere('serial', 'like', "%{$request->search}%");
            });
        }

        if ($request->category) {
            $query->where('category_id', $request->category);
        }

        if ($request->status) {
            $query->where('status', $request->status);
        }

        $assets = $query->latest()->paginate(15);
        $categories = Category::where('is_active', true)->get();

        return view('assets.index', compact('assets', 'categories'));
    }

    public function show(Asset $asset): View
    {
        $asset->load('category', 'creator', 'checkouts.user', 'activityLogs.user');
        return view('assets.show', compact('asset'));
    }

    public function create(): View
    {
        $categories = Category::where('is_active', true)->get();
        $nextTag = 'AST-' . now()->year . '-' . str_pad(Asset::max('id') + 1 ?? 1, 4, '0', STR_PAD_LEFT);
        return view('assets.create', compact('categories', 'nextTag'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'asset_tag' => ['required', 'string', 'unique:assets'],
            'serial' => ['required', 'string', 'unique:assets'],
            'category_id' => ['required', 'exists:categories,id'],
            'brand' => ['nullable', 'string', 'max:255'],
            'model' => ['nullable', 'string', 'max:255'],
            'purchase_date' => ['nullable', 'date'],
            'purchase_price' => ['nullable', 'numeric'],
            'supplier' => ['nullable', 'string', 'max:255'],
            'location' => ['nullable', 'string', 'max:255'],
            'condition' => ['nullable', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'photo' => ['nullable', 'image', 'max:5120'],
        ]);

        $validated['status'] = 'active';
        $validated['created_by'] = Auth::id();

        $asset = Asset::create($validated);

        if ($request->hasFile('photo')) {
            $asset->photo_url = $request->file('photo')->store('assets/photos', 'public');
            $asset->save();
        }

        ActivityLog::create([
            'type' => 'asset_created',
            'asset_id' => $asset->id,
            'user_id' => Auth::id(),
            'description' => "Registered asset {$asset->name} ({$asset->asset_tag})",
        ]);

        return redirect()->route('assets.show', $asset)->with('success', 'Asset registered successfully.');
    }

    public function edit(Asset $asset): View
    {
        $categories = Category::where('is_active', true)->get();
        return view('assets.edit', compact('asset', 'categories'));
    }

    public function update(Request $request, Asset $asset): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'asset_tag' => ['required', 'string', 'unique:assets,asset_tag,' . $asset->id],
            'serial' => ['required', 'string', 'unique:assets,serial,' . $asset->id],
            'category_id' => ['required', 'exists:categories,id'],
            'brand' => ['nullable', 'string', 'max:255'],
            'model' => ['nullable', 'string', 'max:255'],
            'purchase_date' => ['nullable', 'date'],
            'purchase_price' => ['nullable', 'numeric'],
            'supplier' => ['nullable', 'string', 'max:255'],
            'location' => ['nullable', 'string', 'max:255'],
            'condition' => ['nullable', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'photo' => ['nullable', 'image', 'max:5120'],
        ]);

        if ($request->hasFile('photo')) {
            $asset->photo_url = $request->file('photo')->store('assets/photos', 'public');
        }

        $asset->update($validated);

        ActivityLog::create([
            'type' => 'asset_updated',
            'asset_id' => $asset->id,
            'user_id' => Auth::id(),
            'description' => "Updated asset {$asset->name} ({$asset->asset_tag})",
        ]);

        return redirect()->route('assets.show', $asset)->with('success', 'Asset updated successfully.');
    }

    public function archive(Request $request, Asset $asset): RedirectResponse
    {
        $request->validate(['reason' => ['required', 'string', 'max:255']]);

        $asset->update([
            'status' => 'archived',
            'archived_at' => now(),
            'archived_reason' => $request->reason,
        ]);

        ActivityLog::create([
            'type' => 'asset_archived',
            'asset_id' => $asset->id,
            'user_id' => Auth::id(),
            'description' => "Archived asset {$asset->name} — {$request->reason}",
        ]);

        return redirect()->route('assets.index')->with('success', 'Asset archived.');
    }

    public function discard(Request $request, Asset $asset): RedirectResponse
    {
        $request->validate(['reason' => ['required', 'string', 'max:255']]);

        $asset->update([
            'status' => 'discarded',
            'discarded_at' => now(),
            'discarded_reason' => $request->reason,
        ]);

        ActivityLog::create([
            'type' => 'asset_discarded',
            'asset_id' => $asset->id,
            'user_id' => Auth::id(),
            'description' => "Discarded asset {$asset->name} — {$request->reason}",
        ]);

        return redirect()->route('assets.index')->with('success', 'Asset marked as discarded.');
    }

    public function destroy(Asset $asset): RedirectResponse
    {
        $asset->checkouts()->delete();
        $asset->activityLogs()->delete();
        $asset->delete();

        return redirect()->route('assets.index')->with('success', 'Asset deleted permanently.');
    }

    public function restore(Asset $asset): RedirectResponse
    {
        $asset->update([
            'status' => 'active',
            'archived_at' => null,
            'archived_reason' => null,
        ]);

        ActivityLog::create([
            'type' => 'asset_restored',
            'asset_id' => $asset->id,
            'user_id' => Auth::id(),
            'description' => "Restored asset {$asset->name} from archive",
        ]);

        return redirect()->route('assets.show', $asset)->with('success', 'Asset restored.');
    }

    public function archived(Request $request): View
    {
        $query = Asset::with('category')->where('status', 'archived');

        if ($request->search) {
            $query->where(function ($q) use ($request) {
                $q->where('name', 'like', "%{$request->search}%")
                  ->orWhere('asset_tag', 'like', "%{$request->search}%")
                  ->orWhere('serial', 'like', "%{$request->search}%");
            });
        }

        $assets = $query->latest('archived_at')->paginate(15);
        return view('assets.archived', compact('assets'));
    }

    public function printQr(Asset $asset): View
    {
        $qrCode = new QrCode(route('assets.show', $asset));
        $writer = new PngWriter();
        $qrDataUri = $writer->write($qrCode)->getDataUri();
        return view('assets.print-qr', compact('asset', 'qrDataUri'));
    }

    public function history(Asset $asset): View
    {
        $asset->load('activityLogs.user');
        return view('assets.history', compact('asset'));
    }
}
