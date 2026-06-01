<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Laporan Pendapatan Desainer - {{ $designer->user->full_name }}</title>
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
        .text-right { text-align: right; }
    </style>
</head>
<body>
    <div class="header">
        <h1>Laporan Pendapatan Desainer</h1>
        <p>{{ $designer->user->full_name }} - ID: #DES-{{ str_pad($designer->id, 4, '0', STR_PAD_LEFT) }}</p>
        <p>Periode: {{ date('d F Y', strtotime($start_date)) }} - {{ date('d F Y', strtotime($end_date)) }}</p>
    </div>

    <table class="summary">
        <tr>
            <td>
                <div class="label">Total Pendapatan</div>
                <div class="value">Rp {{ number_format($periodRevenue, 0, ',', '.') }}</div>
            </td>
            <td>
                <div class="label">Proyek Selesai</div>
                <div class="value">{{ $projectsCompleted }} Proyek</div>
            </td>
            <td>
                <div class="label">Rata-rata Nilai Proyek</div>
                <div class="value">Rp {{ number_format($avgProjectValue, 0, ',', '.') }}</div>
            </td>
        </tr>
    </table>

    <table class="data">
        <thead>
            <tr>
                <th>Tanggal Selesai</th>
                <th>Project ID</th>
                <th>Nama Klien</th>
                <th>Layanan</th>
                <th class="text-right">Nominal Pendapatan</th>
            </tr>
        </thead>
        <tbody>
            @forelse($consultations as $consult)
            <tr>
                <td>{{ $consult->updated_at->format('d/m/Y') }}</td>
                <td>#DEC-{{ str_pad($consult->id, 5, '0', STR_PAD_LEFT) }}</td>
                <td>{{ $consult->customer->user->full_name ?? 'Client' }}</td>
                <td>Consultation & Quote</td>
                <td class="text-right">Rp {{ number_format($consult->quotes->first()->amount ?? 0, 0, ',', '.') }}</td>
            </tr>
            @empty
            <tr>
                <td colspan="5" class="text-center" style="padding: 10px; text-align: center;">Tidak ada proyek selesai pada periode ini.</td>
            </tr>
            @endforelse
        </tbody>
        <tfoot>
            <tr style="background: #f9f9f9; font-weight: bold;">
                <td colspan="4" class="text-right" style="padding: 10px;">TOTAL PENDAPATAN</td>
                <td class="text-right" style="padding: 10px;">Rp {{ number_format($periodRevenue, 0, ',', '.') }}</td>
            </tr>
        </tfoot>
    </table>

    <div class="footer">
        <p>Laporan dicetak pada: {{ date('d/m/Y H:i') }}</p>
        <p>Dokumen ini dihasilkan secara otomatis oleh DECOR Designer Portal.</p>
        <p>Â© 2026 DECOR - All Rights Reserved</p>
    </div>
</body>
</html>