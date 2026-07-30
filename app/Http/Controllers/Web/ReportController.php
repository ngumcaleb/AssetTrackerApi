<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Asset;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Response;

class ReportController extends Controller
{
    /**
     * Get base64 encoded string of Royalty World logo for DomPDF rendering.
     */
    private function getLogoBase64(): string
    {
        $path = public_path('images/logo.png');
        if (file_exists($path)) {
            $type = pathinfo($path, PATHINFO_EXTENSION);
            $data = file_get_contents($path);
            return 'data:image/' . $type . ';base64,' . base64_encode($data);
        }
        return '';
    }

    /**
     * Export all assets as a PDF inventory list.
     */
    public function exportAssetsPdf(): Response
    {
        $assets = Asset::with('category')->orderBy('created_at', 'desc')->get();
        $logoBase64 = $this->getLogoBase64();

        $pdf = Pdf::loadView('reports.pdf.assets-list', compact('assets', 'logoBase64'));
        $pdf->setPaper('a4', 'portrait');

        return $pdf->download('RoyaltyWorld_Asset_Inventory_' . now()->format('Y-m-d') . '.pdf');
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

        $logoBase64 = $this->getLogoBase64();

        $pdf = Pdf::loadView('reports.pdf.assets-status', compact('checkedOutAssets', 'availableAssets', 'logoBase64'));
        $pdf->setPaper('a4', 'portrait');

        return $pdf->download('RoyaltyWorld_Asset_Status_Audit_' . now()->format('Y-m-d') . '.pdf');
    }
}
