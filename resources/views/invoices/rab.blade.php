<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>RAB Project #{{ $quote->consultation_id }}</title>
    <style>
        body { font-family: 'Helvetica', 'Arial', sans-serif; font-size: 12px; color: #333; }
        .header { text-align: center; margin-bottom: 30px; border-bottom: 2px solid #D97706; padding-bottom: 10px; }
        .header h1 { margin: 0; color: #D97706; font-size: 24px; text-transform: uppercase; letter-spacing: 2px; }
        .header p { margin: 5px 0 0 0; color: #666; }
        .info-table { width: 100%; margin-bottom: 30px; }
        .info-table td { padding: 5px; vertical-align: top; }
        .info-label { font-weight: bold; width: 120px; color: #555; }
        .rab-table { width: 100%; border-collapse: collapse; margin-bottom: 30px; }
        .rab-table th, .rab-table td { border: 1px solid #ddd; padding: 10px; text-align: left; }
        .rab-table th { background-color: #fef3c7; color: #b45309; text-transform: uppercase; font-size: 10px; letter-spacing: 1px; }
        .rab-table td { font-size: 11px; }
        .text-right { text-align: right !important; }
        .text-center { text-align: center !important; }
        .total-row th { background-color: #fffbeb; font-size: 12px; }
        .notes-section { background-color: #f9fafb; padding: 15px; border-left: 4px solid #D97706; border-radius: 4px; margin-bottom: 30px; }
        .notes-section h4 { margin-top: 0; color: #D97706; text-transform: uppercase; font-size: 10px; letter-spacing: 1px; }
        .footer { text-align: center; margin-top: 50px; font-size: 10px; color: #999; border-top: 1px solid #eee; padding-top: 20px; }
    </style>
</head>
<body>

    <div class="header">
        <h1>Rencana Anggaran Biaya (RAB)</h1>
        <p>Project Agreement & Bill of Quantities</p>
    </div>

    <table class="info-table">
        <tr>
            <td class="info-label">Project ID</td>
            <td>: #DEC-{{ str_pad($quote->consultation_id, 5, '0', STR_PAD_LEFT) }}</td>
            <td class="info-label">Date</td>
            <td>: {{ $quote->created_at->format('d F Y') }}</td>
        </tr>
        <tr>
            <td class="info-label">Project Name</td>
            <td>: {{ $quote->consultation->title ?? '-' }}</td>
            <td class="info-label">Status</td>
            <td>: <strong style="text-transform: uppercase; color: #D97706;">{{ $quote->status }}</strong></td>
        </tr>
        <tr>
            <td class="info-label">Designer</td>
            <td>: {{ $quote->consultation->designer->studio_name ?? $quote->consultation->designer->user->full_name ?? '-' }}</td>
            <td class="info-label">Customer</td>
            <td>: {{ $quote->consultation->customer->user->full_name ?? '-' }}</td>
        </tr>
    </table>

    <table class="rab-table">
        <thead>
            <tr>
                <th style="width: 5%;" class="text-center">No</th>
                <th style="width: 40%;">Description / Item Name</th>
                <th style="width: 10%;" class="text-center">Qty</th>
                <th style="width: 15%;" class="text-center">Unit</th>
                <th style="width: 15%;" class="text-right">Unit Price</th>
                <th style="width: 15%;" class="text-right">Total</th>
            </tr>
        </thead>
        <tbody>
            @php 
                $items = json_decode($quote->items, true); 
                $totalRAB = 0;
            @endphp
            @if(is_array($items) && count($items) > 0)
                @foreach($items as $index => $item)
                    @php
                        $qty = intval($item['qty'] ?? 1);
                        $price = floatval($item['price'] ?? 0);
                        $total = $qty * $price;
                        $totalRAB += $total;
                    @endphp
                    <tr>
                        <td class="text-center">{{ $index + 1 }}</td>
                        <td>{{ $item['name'] ?? '-' }}</td>
                        <td class="text-center">{{ $qty }}</td>
                        <td class="text-center">{{ $item['unit'] ?? 'Ls' }}</td>
                        <td class="text-right">Rp {{ number_format($price, 0, ',', '.') }}</td>
                        <td class="text-right">Rp {{ number_format($total, 0, ',', '.') }}</td>
                    </tr>
                @endforeach
            @else
                <tr>
                    <td colspan="6" class="text-center" style="padding: 20px; color: #999;">No detailed items provided.</td>
                </tr>
            @endif
        </tbody>
        <tfoot>
            <tr class="total-row">
                <th colspan="5" class="text-right">TOTAL RAB</th>
                <th class="text-right" style="color: #D97706;">Rp {{ number_format($quote->amount, 0, ',', '.') }}</th>
            </tr>
        </tfoot>
    </table>

    @if($quote->notes)
    <div class="notes-section">
        <h4>Additional Notes / Syarat & Ketentuan</h4>
        <p style="margin-bottom: 0;">{{ $quote->notes }}</p>
    </div>
    @endif

    <div style="margin-top: 50px;">
        <table style="width: 100%;">
            <tr>
                <td style="width: 50%; text-align: center;">
                    <p>Disetujui oleh (Customer),</p>
                    <br><br><br>
                    <p><strong>{{ $quote->consultation->customer->user->full_name ?? '.......................' }}</strong></p>
                </td>
                <td style="width: 50%; text-align: center;">
                    <p>Dibuat oleh (Designer),</p>
                    <br><br><br>
                    <p><strong>{{ $quote->consultation->designer->studio_name ?? $quote->consultation->designer->user->full_name ?? '.......................' }}</strong></p>
                </td>
            </tr>
        </table>
    </div>

    <div class="footer">
        Generated by Decor Web System &copy; {{ date('Y') }}
    </div>

</body>
</html>