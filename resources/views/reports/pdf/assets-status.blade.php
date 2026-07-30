<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Royalty World - Asset Custody Audit Report</title>
    <style>
        @page {
            margin: 30px 40px 50px 40px;
        }
        body {
            font-family: 'Georgia', 'Helvetica Neue', Helvetica, Arial, sans-serif;
            font-size: 11px;
            color: #2c2c2c;
            line-height: 1.4;
        }

        /* Oxblood Branding Colors: #800020 (Primary Oxblood), #4a0012 (Dark Maroon), #fdf8f8 (Background Tint) */

        .header-table {
            width: 100%;
            border-bottom: 3px solid #800020;
            padding-bottom: 12px;
            margin-bottom: 15px;
        }
        .logo-img {
            max-height: 55px;
            width: auto;
        }
        .org-title {
            font-size: 22px;
            font-weight: bold;
            color: #800020;
            text-transform: uppercase;
            letter-spacing: 1px;
            margin: 0;
        }
        .org-tagline {
            font-size: 10px;
            color: #666666;
            margin-top: 2px;
            font-style: italic;
        }
        .doc-meta {
            text-align: right;
            font-size: 10px;
            color: #4a4a4a;
            line-height: 1.5;
        }
        .doc-meta strong {
            color: #800020;
        }

        .document-title-bar {
            background-color: #800020;
            color: #ffffff;
            padding: 8px 14px;
            margin-bottom: 15px;
            border-radius: 4px;
        }
        .document-title {
            font-size: 15px;
            font-weight: bold;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin: 0;
        }
        .document-subtitle {
            font-size: 9px;
            opacity: 0.9;
            margin-top: 2px;
        }

        .executive-summary {
            background-color: #fdf8f8;
            border-left: 4px solid #800020;
            border-right: 1px solid #f5e6e8;
            border-top: 1px solid #f5e6e8;
            border-bottom: 1px solid #f5e6e8;
            padding: 10px 14px;
            margin-bottom: 15px;
            font-size: 10.5px;
            color: #3a3a3a;
        }
        .executive-summary h3 {
            margin: 0 0 5px 0;
            font-size: 12px;
            color: #800020;
            text-transform: uppercase;
        }

        .section-banner {
            font-size: 12px;
            font-weight: bold;
            padding: 6px 10px;
            border-radius: 3px;
            margin-top: 20px;
            margin-bottom: 8px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        .banner-out {
            background-color: #4a0012;
            color: #ffffff;
        }
        .banner-in {
            background-color: #800020;
            color: #ffffff;
        }

        table.data-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 10px;
        }
        table.data-table th {
            background-color: #fdf8f8;
            color: #800020;
            font-weight: bold;
            font-size: 9.5px;
            text-align: left;
            padding: 6px 8px;
            text-transform: uppercase;
            border-bottom: 2px solid #800020;
            border-top: 1px solid #e0d0d3;
        }
        table.data-table td {
            padding: 6px 8px;
            border-bottom: 1px solid #e5d5d8;
            font-size: 9.5px;
        }
        table.data-table tr:nth-child(even) {
            background-color: #faf5f6;
        }

        .signature-block {
            margin-top: 30px;
            width: 100%;
        }
        .signature-title {
            font-size: 10px;
            font-weight: bold;
            color: #800020;
            margin-bottom: 25px;
            text-transform: uppercase;
        }
        .signature-line {
            border-top: 1px solid #800020;
            width: 80%;
            padding-top: 4px;
            font-size: 9px;
            color: #555555;
        }

        .footer {
            position: fixed;
            bottom: -20px;
            left: 0;
            right: 0;
            height: 25px;
            border-top: 1px solid #e0d0d3;
            text-align: center;
            font-size: 8.5px;
            color: #777777;
            line-height: 25px;
        }
    </style>
</head>
<body>

    {{-- Official Letterhead --}}
    <table class="header-table">
        <tr>
            <td width="55%">
                @if($logoBase64)
                    <img src="{{ $logoBase64 }}" class="logo-img" alt="Royalty World">
                @else
                    <h1 class="org-title">Royalty World</h1>
                @endif
                <div class="org-tagline">Equipment Custody & Audit Division</div>
            </td>
            <td class="doc-meta" width="45%">
                <strong>Custody Reconciliation Audit</strong><br>
                <strong>Ref ID:</strong> RW-CUST-{{ now()->format('Ymd-His') }}<br>
                <strong>Audit Date:</strong> {{ now()->format('F d, Y \a\t H:i') }}<br>
                <strong>Auditor:</strong> {{ auth()->user()->name ?? 'System Administrator' }}
            </td>
        </tr>
    </table>

    {{-- Document Title Bar --}}
    <div class="document-title-bar">
        <div class="document-title">Asset Custody Audit Document (IN vs OUT)</div>
        <div class="document-subtitle">Formal reconciliation of checked-out equipment vs available inventory</div>
    </div>

    {{-- Preamble Writeup --}}
    <div class="executive-summary">
        <h3>Audit Statement & Compliance Preamble</h3>
        <p style="margin: 0 0 6px 0;">
            This document outlines the current custody status of equipment belonging to <strong>Royalty World</strong>. 
            Section 1 reconciles all items currently issued or checked out (<strong>OUT</strong>), detailing active handlers, dates, and expected returns. 
            Section 2 registers all equipment physically verified in storage (<strong>IN</strong>).
        </p>
        <p style="margin: 0;">
            Holders of checked-out assets assume full responsibility for their care. This physical copy must be signed and retained in the permanent administration records.
        </p>
    </div>

    {{-- SECTION 1: CHECKED OUT ASSETS (OUT) --}}
    <div class="section-banner banner-out">
        Section 1: Assets Currently Checked Out (OUT) &bull; Total: {{ $checkedOutAssets->count() }}
    </div>
    <table class="data-table">
        <thead>
            <tr>
                <th width="15%">Tag ID</th>
                <th width="22%">Asset Name</th>
                <th width="25%">Assigned User / Custodian</th>
                <th width="18%">Checkout Date</th>
                <th width="20%">Expected Return</th>
            </tr>
        </thead>
        <tbody>
            @forelse($checkedOutAssets as $asset)
                @php $checkout = $asset->activeCheckout; @endphp
                <tr>
                    <td><strong>{{ $asset->asset_tag }}</strong></td>
                    <td>{{ $asset->name }}</td>
                    <td>{{ $checkout->assigned_to ?? $asset->location ?? 'External Staff' }}</td>
                    <td>{{ $checkout ? \Carbon\Carbon::parse($checkout->checked_out_at)->format('d/m/Y H:i') : '-' }}</td>
                    <td>{{ $checkout && $checkout->expected_return_at ? \Carbon\Carbon::parse($checkout->expected_return_at)->format('d/m/Y') : 'Unspecified' }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="5" style="text-align: center; color: #777777; padding: 12px;">
                        No assets are currently checked out. All equipment is verified in storage.
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>

    {{-- SECTION 2: IN-STOCK ASSETS (IN) --}}
    <div class="section-banner banner-in">
        Section 2: Available In-Stock Assets (IN) &bull; Total: {{ $availableAssets->count() }}
    </div>
    <table class="data-table">
        <thead>
            <tr>
                <th width="15%">Tag ID</th>
                <th width="25%">Asset Name</th>
                <th width="20%">Category</th>
                <th width="22%">Storage Location</th>
                <th width="18%">Condition</th>
            </tr>
        </thead>
        <tbody>
            @forelse($availableAssets as $asset)
                <tr>
                    <td><strong>{{ $asset->asset_tag }}</strong></td>
                    <td>{{ $asset->name }}</td>
                    <td>{{ $asset->category->name ?? 'General' }}</td>
                    <td>{{ $asset->location ?? 'Main Storage' }}</td>
                    <td>{{ ucfirst($asset->condition ?? 'Good') }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="5" style="text-align: center; color: #777777; padding: 12px;">
                        No assets currently available in stock.
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>

    {{-- Official Authorization / Signature Block --}}
    <table class="signature-block">
        <tr>
            <td width="50%">
                <div class="signature-title">Audited By (Inventory Officer):</div>
                <div style="height: 30px;"></div>
                <div class="signature-line">
                    Signature: ___________________________<br>
                    Name: {{ auth()->user()->name ?? 'Auditor' }}<br>
                    Date: {{ now()->format('d/m/Y') }}
                </div>
            </td>
            <td width="50%">
                <div class="signature-title">Verified By (Operations Management):</div>
                <div style="height: 30px;"></div>
                <div class="signature-line">
                    Signature: ___________________________<br>
                    Name: Royalty World Operations<br>
                    Stamp & Seal:
                </div>
            </td>
        </tr>
    </table>

    <div class="footer">
        Royalty World &bull; Confidential Asset Custody Audit Document &bull; Official Physical Record
    </div>

</body>
</html>
