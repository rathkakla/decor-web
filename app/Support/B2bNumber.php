<?php

namespace App\Support;

use Illuminate\Support\Facades\DB;

/**
 * Penomoran dokumen B2B: PREFIX-YYYYMM-### (contoh: RFQ-B2B-202611-001).
 *
 * Nomor urut di-reset setiap bulan dan dihitung dari nomor terakhir pada
 * tabel terkait, sehingga tetap berurutan meski data dihapus.
 */
class B2bNumber
{
    public const RFQ = 'RFQ-B2B';
    public const QUOTATION = 'QTO-B2B';
    public const ORDER = 'B2B-ORD';
    public const SURAT_JALAN = 'SJ-B2B';
    public const BAST = 'BAST-B2B';

    public static function make(string $prefix, string $table, string $column): string
    {
        $period = now()->format('Ym');
        $last = DB::table($table)
            ->where($column, 'like', "{$prefix}-{$period}-%")
            ->orderByDesc($column)
            ->value($column);

        $sequence = 1;
        if ($last) {
            $sequence = (int) substr($last, strrpos($last, '-') + 1) + 1;
        }

        return sprintf('%s-%s-%03d', $prefix, $period, $sequence);
    }
}
