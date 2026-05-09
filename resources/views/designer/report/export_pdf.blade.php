<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Financial Report - {{ $start_date }} to {{ $end_date }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600;700;800&display=swap');
        body { background-color: #ffffff; font-family: 'Plus Jakarta Sans', sans-serif; color: #1a1a1a; }
        .pdf-container { width: 21cm; min-height: 29.7cm; margin: 0 auto; padding: 2cm; }
        .bg-primary { background-color: #B5733A; }
        .text-primary { color: #B5733A; }
        @media print {
            body { background: none; padding: 0; }
            .pdf-container { margin: 0; box-shadow: none; width: 100%; }
        }
    </style>
</head>
<body>

    <div class="pdf-container">
        <!-- REPORT HEADER -->
        <div class="flex justify-between items-start border-b-2 border-gray-100 pb-10 mb-10">
            <div>
                <h1 class="text-3xl font-black text-primary tracking-[0.2em] uppercase">DECOR</h1>
                <p class="text-[10px] text-gray-400 mt-1 uppercase font-bold italic tracking-widest">Official Financial Statement</p>
            </div>
            <div class="text-right">
                <h2 class="text-sm font-black uppercase tracking-widest">Designer Report</h2>
                <p class="text-xs text-gray-500 font-bold mt-1 uppercase italic">{{ $start_date }} — {{ $end_date }}</p>
            </div>
        </div>

        <!-- DESIGNER IDENTITY -->
        <div class="mb-12">
            <p class="text-[9px] font-black text-gray-400 uppercase tracking-widest mb-2 italic">Issued For:</p>
            <h3 class="text-lg font-black uppercase">Elena Vance</h3>
            <p class="text-xs text-gray-500 font-medium italic">ID: #DES-ELV2026</p>
        </div>

        <!-- FINANCIAL SUMMARY CARDS -->
        <div class="grid grid-cols-3 gap-6 mb-12">
            <div class="bg-gray-50 p-6 rounded-3xl border border-gray-100">
                <p class="text-[8px] font-black text-gray-400 uppercase tracking-widest mb-1">Total Revenue</p>
                <p class="text-xl font-black text-primary">$8,450.00</p>
            </div>
            <div class="bg-gray-50 p-6 rounded-3xl border border-gray-100">
                <p class="text-[8px] font-black text-gray-400 uppercase tracking-widest mb-1">Completed Projects</p>
                <p class="text-xl font-black text-gray-900">24</p>
            </div>
            <div class="bg-gray-50 p-6 rounded-3xl border border-gray-100">
                <p class="text-[8px] font-black text-gray-400 uppercase tracking-widest mb-1">Avg. Project Value</p>
                <p class="text-xl font-black text-gray-900">$352.00</p>
            </div>
        </div>

        <!-- TRANSACTION TABLE -->
        <div class="mb-12">
            <h4 class="text-[10px] font-black uppercase tracking-[0.2em] mb-6 border-l-4 border-primary pl-4">Transaction Details</h4>
            <table class="w-full text-left">
                <thead>
                    <tr class="border-b border-gray-900">
                        <th class="py-4 text-[9px] font-black uppercase tracking-widest">Date</th>
                        <th class="py-4 text-[9px] font-black uppercase tracking-widest">ID</th>
                        <th class="py-4 text-[9px] font-black uppercase tracking-widest">Description</th>
                        <th class="py-4 text-right text-[9px] font-black uppercase tracking-widest">Amount</th>
                    </tr>
                </thead>
                <tbody class="text-[10px] font-bold uppercase italic text-gray-700">
                    <tr class="border-b border-gray-50">
                        <td class="py-4 italic tracking-tighter">Apr 28, 2026</td>
                        <td class="py-4 text-primary">#DEC-88219</td>
                        <td class="py-4">Penthouse Interior - Elena Rodriguez</td>
                        <td class="py-4 text-right">$1,250.00</td>
                    </tr>
                    <tr class="border-b border-gray-50">
                        <td class="py-4 italic tracking-tighter">Apr 25, 2026</td>
                        <td class="py-4 text-primary">#DEC-88102</td>
                        <td class="py-4">Mid-Century Loft - Marcus Thorne</td>
                        <td class="py-4 text-right">$2,400.00</td>
                    </tr>
                    <tr class="border-b border-gray-50">
                        <td class="py-4 italic tracking-tighter">Apr 20, 2026</td>
                        <td class="py-4 text-primary">#DEC-87944</td>
                        <td class="py-4">Modern Office - Luminary Studios</td>
                        <td class="py-4 text-right">$3,100.00</td>
                    </tr>
                </tbody>
            </table>
        </div>

        <!-- SIGNATURE AREA -->
        <div class="mt-20 pt-10 flex justify-between items-end border-t border-gray-50">
            <div class="text-[9px] text-gray-300 font-bold uppercase leading-relaxed">
                Report generated on: May 4, 2026<br>
                Official document of DECOR DESIGNER PORTAL
            </div>
            <div class="text-center w-48">
                <p class="text-[10px] font-black uppercase tracking-widest mb-16 italic">Authorized Signature</p>
                <div class="h-px bg-gray-900 mb-2"></div>
                <p class="text-[9px] font-black uppercase tracking-widest text-primary leading-none">Finance Department</p>
            </div>
        </div>

        <!-- PDF FOOTER -->
        <div class="mt-40 text-center">
            <p class="text-[8px] font-black text-gray-200 uppercase tracking-[0.5em]">
                Excellence in curation. Excellence in design.
            </p>
        </div>
    </div>

</body>
</html>