<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Invoice #DEC-{{ str_pad($consultation->id, 5, '0', STR_PAD_LEFT) }}</title>
    <style>
        body { font-family: 'Helvetica', sans-serif; color: #333; line-height: 1.6; }
        .invoice-box { max-width: 800px; margin: auto; padding: 30px; border: 1px solid #eee; }
        .header { display: flex; justify-content: space-between; margin-bottom: 40px; }
        .title { color: #B5733A; font-size: 28px; font-weight: bold; text-transform: uppercase; }
        .info-section { margin-bottom: 40px; width: 100%; border-collapse: collapse; }
        .info-section td { vertical-align: top; width: 50%; }
        .item-table { width: 100%; border-collapse: collapse; margin-bottom: 40px; }
        .item-table th { background: #F8F6F4; color: #B5733A; text-align: left; padding: 12px; font-size: 12px; text-transform: uppercase; }
        .item-table td { padding: 12px; border-bottom: 1px solid #eee; font-size: 14px; }
        .total-section { float: right; width: 250px; }
        .total-row { display: flex; justify-content: space-between; padding: 8px 0; }
        .grand-total { border-top: 2px solid #B5733A; margin-top: 8px; padding-top: 8px; font-weight: bold; color: #B5733A; font-size: 18px; }
        .footer { text-align: center; margin-top: 60px; color: #999; font-size: 10px; }
    </style>
</head>
<body>
    <div class="invoice-box">
        <table style="width: 100%;">
            <tr>
                <td class="title">DECOR</td>
                <td style="text-align: right;">
                    <strong>Invoice #DEC-{{ str_pad($consultation->id, 5, '0', STR_PAD_LEFT) }}</strong><br>
                    Date: {{ $consultation->created_at->format('M d, Y') }}<br>
                </td>
            </tr>
        </table>

        <hr style="border: 0; border-top: 1px solid #eee; margin: 20px 0;">

        <table class="info-section">
            <tr>
                <td>
                    <p style="color: #999; text-transform: uppercase; font-size: 10px; margin-bottom: 5px;">From:</p>
                    <strong>{{ $consultation->designer->studio_name ?? $consultation->designer->user->full_name }}</strong><br>
                    {{ $consultation->designer->specialty }}<br>
                    Designer Partner at DECOR
                </td>
                <td style="text-align: right;">
                    <p style="color: #999; text-transform: uppercase; font-size: 10px; margin-bottom: 5px;">To:</p>
                    <strong>{{ $consultation->customer->user->full_name }}</strong><br>
                    {{ $consultation->customer->user->email }}<br>
                    Customer
                </td>
            </tr>
        </table>

        <table class="item-table">
            <thead>
                <tr>
                    <th>Description</th>
                    <th style="text-align: right;">Amount</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>
                        <strong>{{ $consultation->title }}</strong><br>
                        <span style="font-size: 11px; color: #666;">Interior Design Consultation Fee (Initial Commitment)</span>
                    </td>
                    <td style="text-align: right;">Rp 500.000</td>
                </tr>
                @php $total = 500000; @endphp
                @foreach($consultation->quotes as $quote)
                    @if($quote->status == 'accepted')
                        <tr>
                            <td>
                                <strong>Project Fee: {{ $quote->notes ?? 'Final Design Implementation' }}</strong><br>
                                <span style="font-size: 11px; color: #666;">Agreed Project Implementation Cost</span>
                            </td>
                            <td style="text-align: right;">Rp {{ number_format($quote->amount, 0, ',', '.') }}</td>
                        </tr>
                        @php $total += $quote->amount; @endphp
                    @endif
                @endforeach
            </tbody>
        </table>

        <div class="total-section">
            <div style="text-align: right;">
                <span style="font-size: 12px; color: #999;">TOTAL AMOUNT</span><br>
                <span class="grand-total">Rp {{ number_format($total, 0, ',', '.') }}</span>
            </div>
        </div>

        <div style="clear: both;"></div>

        <div class="footer">
            <p>Thank you for choosing DECOR for your interior design journey.<br>
            This is a computer-generated invoice and no signature is required.</p>
        </div>
    </div>
</body>
</html>