<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Royalty World - Master Asset Inventory Audit</title>
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
            margin-bottom: 20px;
            font-size: 10.5px;
            color: #3a3a3a;
        }
        .executive-summary h3 {
            margin: 0 0 5px 0;
            font-size: 12px;
            color: #800020;
            text-transform: uppercase;
        }

        .kpi-table {
            width: 100%;
            margin-bottom: 20px;
        }
        .kpi-box {
            background-color: #ffffff;
            border: 1px solid #e0d0d3;
            border-radius: 4px;
            padding: 8px 10px;
            text-align: center;
        }
        .kpi-value {
            font-size: 15px;
            font-weight: bold;
            color: #800020;
        }
        .kpi-label {
            font-size: 9px;
            color: #555555;
            text-transform: uppercase;
            margin-top: 3px;
            letter-spacing: 0.5px;
        }

        table.data-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 5px;
        }
        table.data-table th {
            background-color: #4a0012;
            color: #ffffff;
            font-weight: bold;
            font-size: 10px;
            text-align: left;
            padding: 7px 8px;
            text-transform: uppercase;
            border: 1px solid #4a0012;
        }
        table.data-table td {
            padding: 6px 8px;
            border-bottom: 1px solid #e5d5d8;
            font-size: 10px;
        }
        table.data-table tr:nth-child(even) {
            background-color: #faf5f6;
        }

        .badge {
            display: inline-block;
            padding: 2px 6px;
            border-radius: 3px;
            font-size: 8.5px;
            font-weight: bold;
            text-transform: uppercase;
        }
        .badge-active { background-color: #e6f4ea; color: #137333; }
        .badge-checked_out { background-color: #fef7e0; color: #b06000; }
        .badge-maintenance { background-color: #feefe3; color: #c5221f; }
        .badge-archived { background-color: #f1f3f4; color: #5f6368; }

        .signature-block {
            margin-top: 35px;
            width: 100%;
        }
        .signature-title {
            font-size: 10px;
            font-weight: bold;
            color: #800020;
            margin-bottom: 30px;
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
                <div class="org-tagline">Asset Management & Inventory Control Division</div>
            </td>
            <td class="doc-meta" width="45%">
                <strong>Official Audit Document</strong><br>
                <strong>Ref ID:</strong> RW-INV-{{ now()->format('Ymd-His') }}<br>
                <strong>Generated Date:</strong> {{ now()->format('F d, Y \a\t H:i') }}<br>
                <strong>Prepared By:</strong> {{ auth()->user()->name ?? 'System Administrator' }}
            </td>
        </tr>
    </table>

    {{-- Document Title Bar --}}
    <div class="document-title-bar">
        <div class="document-title">Master Asset Inventory Audit Report</div>
        <div class="document-subtitle">Comprehensive registry of all capital assets, equipment, and company property</div>
    </div>

    {{-- Preamble Writeup --}}
    <div class="executive-summary">
        <h3>Official Statement & Executive Summary</h3>
        <p style="margin: 0 0 6px 0;">
            This document represents the certified physical and digital inventory register of <strong>Royalty World</strong> as of <strong>{{ now()->format('F d, Y') }}</strong>. 
            All items listed herein have been classified, assigned tracking tags, and verified under official asset management protocols.
        </p>
        <p style="margin: 0;">
            This report serves as an authentic institutional record for internal auditing, valuation assessment, and custody compliance. Any unauthorized modification or removal of registered items is strictly prohibited.
        </p>
    </div>

    {{-- Summary KPIs --}}
    <table class="kpi-table">
        <tr>
            <td width="32%">
                <div class="kpi-box">
                    <div class="kpi-value">{{ $assets->count() }}</div>
                    <div class="kpi-label">Registered Assets</div>
                </div>
            </td>
            <td width="2%"></td>
            <td width="32%">
                <div class="kpi-box">
                    <div class="kpi-value">{{ number_format($assets->sum('purchase_price'), 0, ',', ' ') }} FCFA</div>
                    <div class="kpi-label">Total Portfolio Value</div>
                </div>
            </td>
            <td width="2%"></td>
            <td width="32%">
                <div class="kpi-box">
                    <div class="kpi-value">{{ $assets->where('status', 'checked_out')->count() }}</div>
                    <div class="kpi-label">Currently In Custody (OUT)</div>
                </div>
            </td>
        </tr>
    </table>

    {{-- Data Table --}}
    <table class="data-table">
        <thead>
            <tr>
                <th width="15%">Tag ID</th>
                <th width="23%">Asset Name</th>
                <th width="17%">Category</th>
                <th width="17%">Brand / Model</th>
                <th width="13%">Location</th>
                <th width="15%">Value (FCFA)</th>
            </tr>
        </thead>
        <tbody>
            @forelse($assets as $asset)
                <tr>
                    <td><strong>{{ $asset->asset_tag }}</strong></td>
                    <td>{{ $asset->name }}</td>
                    <td>{{ $asset->category->name ?? 'Unassigned' }}</td>
                    <td>{{ $asset->brand ? $asset->brand . ' ' . $asset->model : '-' }}</td>
                    <td>{{ $asset->location ?? 'Main Depot' }}</td>
                    <td>{{ number_format($asset->purchase_price ?? 0, 0, ',', ' ') }} FCFA</td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" style="text-align: center; padding: 15px; color: #777777;">No assets currently registered in the database.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    {{-- Official Authorization / Signature Block --}}
    <table class="signature-block">
        <tr>
            <td width="50%">
                <div class="signature-title">Prepared By (Inventory Officer):</div>
                <div style="height: 35px;"></div>
                <div class="signature-line">
                    Signature: ___________________________<br>
                    Name: {{ auth()->user()->name ?? 'Administrator' }}<br>
                    Date: {{ now()->format('d/m/Y') }}
                </div>
            </td>
            <td width="50%">
                <div class="signature-title">Approved By (Operations Management):</div>
                <div style="height: 35px;"></div>
                <div class="signature-line">
                    Signature: ___________________________<br>
                    Name: Royalty World Management<br>
                    Stamp & Seal:
                </div>
            </td>
        </tr>
    </table>

    <div class="footer">
        Royalty World &bull; Confidential Internal Audit Document &bull; Printed Copy Retained for Records
    </div>

</body>
</html>
