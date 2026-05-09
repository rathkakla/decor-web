<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Laporan Penjualan - {{ $seller->store_name }}</title>
    <style>
        body { font-family: sans-serif; font-size: 12px; color: #333; }
        .header { text-align: center; margin-bottom: 30px; border-bottom: 2px solid #B5733A; padding-bottom: 20px; }
        .header h1 { color: #B5733A; margin: 0; font-size: 24px; text-transform: uppercase; }
        .header p { margin: 5px 0; color: #666; }
        .summary { margin-bottom: 30px; width: 100%; border-collapse: collapse; }
        .summary td { padding: 10px; background: #f9f9f9; border: 1px solid #eee; }
        .summary .label { font-weight: bold; color: #666; font-size: 10px; text-transform: uppercase; }
        .summary .value { font-size: 16px; font-weight: bold; color: #333; }
        table.data { width: 100%; border-collapse: collapse; margin-top: 20px; }
        table.data th { background: #B5733A; color: white; padding: 10px; text-align: left; text-transform: uppercase; font-size: 10px; }
        table.data td { padding: 10px; border-bottom: 1px solid #eee; }
        .footer { margin-top: 50px; text-align: center; font-size: 10px; color: #999; }
        .status { padding: 3px 8px; border-radius: 10px; font-size: 9px; font-weight: bold; text-transform: uppercase; }
        .completed { background: #e6f4ea; color: #1e7e34; }
        .other { background: #f1f3f4; color: #5f6368; }
        .text-right { text-align: right; }
    </style>
</head>
<body>
    <div class="header">
        <h1>Laporan Penjualan</h1>
        <p>{{ $seller->store_name }}</p>
        <p>Periode: {{ date('d F Y', strtotime($startDate)) }} - {{ date('d F Y', strtotime($endDate)) }}</p>
    </div>

    <table class="summary">
        <tr>
            <td>
                <div class="label">Total Pendapatan</div>
                <div class="value">Rp {{ number_format($totalRevenue, 0, ',', '.') }}</div>
            </td>
            <td>
                <div class="label">Total Transaksi</div>
                <div class="value">{{ $transactions->count() }} Item</div>
            </td>
            <td>
                <div class="label">Tanggal Cetak</div>
                <div class="value">{{ date('d/m/Y H:i') }}</div>
            </td>
        </tr>
    </table>

    <table class="data">
        <thead>
            <tr>
                <th>Tanggal</th>
                <th>Order ID</th>
                <th>Produk</th>
                <th class="text-right">Qty</th>
                <th class="text-right">Harga</th>
                <th class="text-right">Subtotal</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            @foreach($transactions as $item)
            <tr>
                <td>{{ $item->order->created_at->format('d/m/Y') }}</td>
                <td>#{{ $item->order->id }}</td>
                <td>{{ $item->product->name }}</td>
                <td class="text-right">{{ $item->quantity }}</td>
                <td class="text-right">Rp {{ number_format($item->price, 0, ',', '.') }}</td>
                <td class="text-right">Rp {{ number_format($item->price * $item->quantity, 0, ',', '.') }}</td>
                <td>
                    <span class="status {{ $item->order->status == 'completed' ? 'completed' : 'other' }}">
                        {{ strtoupper($item->order->status) }}
                    </span>
                </td>
            </tr>
            @endforeach
        </tbody>
        <tfoot>
            <tr style="background: #f9f9f9; font-weight: bold;">
                <td colspan="5" class="text-right" style="padding: 10px;">TOTAL PENDAPATAN</td>
                <td class="text-right" style="padding: 10px;">Rp {{ number_format($totalRevenue, 0, ',', '.') }}</td>
                <td></td>
            </tr>
        </tfoot>
    </table>

    <div class="footer">
        <p>Dokumen ini dihasilkan secara otomatis oleh Decor Seller Portal.</p>
        <p>Â© 2026 DECOR - All Rights Reserved</p>
    </div>
</body>
</html>