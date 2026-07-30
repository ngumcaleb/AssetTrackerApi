<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Asset;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class ReportController extends Controller
{
    /**
     * Export all assets as a PDF inventory list.
     */
    public function exportAssetsPdf(): Response
    {
        $assets = Asset::with('category')->orderBy('created_at', 'desc')->get();

        $pdf = Pdf::loadView('reports.pdf.assets-list', compact('assets'));
        $pdf->setPaper('a4', 'portrait');

        return $pdf->download('ScanTrack_Asset_Inventory_' . now()->format('Y-m-d') . '.pdf');
    }

    /**
     * Export IN vs OUT asset status document as a PDF.
     */
    public function exportStatusPdf(): Response
    {
        $checkedOutAssets = Asset::with(['category', 'activeCheckout'])
            ->where('status', 'checked_out')
            ->orderBy('updated_at', 'desc')
            ->get();

        $availableAssets = Asset::with('category')
            ->where('status', '!=', 'checked_out')
            ->where('status', '!=', 'archived')
            ->orderBy('name', 'asc')
            ->get();

        $pdf = Pdf::loadView('reports.pdf.assets-status', compact('checkedOutAssets', 'availableAssets'));
        $pdf->setPaper('a4', 'portrait');

        return $pdf->download('ScanTrack_Asset_Status_Report_' . now()->format('Y-m-d') . '.pdf');
    }
}
