<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Invoice - DECOR Marketplace</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600;700;800&display=swap');
        body { font-family: 'Plus Jakarta Sans', sans-serif; color: #000; }
        /* Memaksa warna hitam pekat saat print */
        @media print {
            .no-print { display: none; }
            body { padding: 0; background: white; color: #000; }
            .print-container { border: none !important; shadow: none !important; }
            /* Memastikan warna background box muncul */
            * { -webkit-print-color-adjust: exact !important; print-color-adjust: exact !important; }
        }
    </style>
</head>
<body class="bg-gray-50 p-10">

    <div class="no-print mb-10 text-center">
        <button onclick="window.print()" class="bg-[#B5733A] text-white px-8 py-3 rounded-xl text-xs font-bold uppercase tracking-widest hover:opacity-90 transition-all shadow-lg">
            <i class="fa-solid fa-print mr-2"></i> Print Official Invoice
        </button>
    </div>

    <div class="max-w-4xl mx-auto bg-white p-16 border border-gray-200 shadow-sm print-container">
        <div class="flex justify-between items-start border-b-4 border-black pb-8">
            <div>
                <h1 class="text-4xl font-black tracking-tighter text-black italic">DECOR.</h1>
                <p class="text-[10px] font-bold uppercase tracking-[0.4em] text-gray-600 mt-2">Premium Furniture Marketplace</p>
            </div>
            <div class="text-right">
                <h2 class="text-xl font-bold uppercase tracking-widest text-black">Official Invoice</h2>
                <p class="text-sm font-semibold text-gray-600 mt-1 italic">#ORD-782911</p>
            </div>
        </div>

        <div class="grid grid-cols-2 gap-10 py-12">
            <div>
                <p class="text-[10px] font-bold text-gray-500 uppercase tracking-widest mb-3">Sold By (Nama Toko):</p>
                <h3 class="text-xl font-black text-[#B5733A] uppercase">DECOR Official Store</h3>
                <p class="text-xs text-gray-700 mt-1 font-semibold italic underline">Verified Merchant - Bandung Warehouse</p>
            </div>
            <div class="text-right">
                <p class="text-[10px] font-bold text-gray-500 uppercase tracking-widest mb-3">Billed To:</p>
                <p class="font-bold text-lg text-black">Siti Aminah</p>
                <p class="text-xs text-gray-700 leading-relaxed mt-1 font-medium">Jl. Sukajadi No. 123, Pasteur,<br>Kota Bandung, 40162</p>
            </div>
        </div>

        <table class="w-full text-left mt-4">
            <thead>
                <tr class="border-b-2 border-black">
                    <th class="py-4 text-[10px] font-bold uppercase tracking-widest text-gray-600">Product Description</th>
                    <th class="py-4 text-[10px] font-bold uppercase tracking-widest text-gray-600 text-center">Qty</th>
                    <th class="py-4 text-[10px] font-bold uppercase tracking-widest text-gray-600 text-right">Unit Price</th>
                    <th class="py-4 text-[10px] font-bold uppercase tracking-widest text-gray-600 text-right">Subtotal</th>
                </tr>
            </thead>
            <tbody class="text-sm font-semibold text-black">
                <tr class="border-b border-gray-200">
                    <td class="py-8">
                        <p class="font-bold text-black">Emerald Velvet Armchair</p>
                        <p class="text-[10px] text-gray-600 font-bold uppercase mt-1">SKU: DCR-KT-042</p>
                    </td>
                    <td class="py-8 text-center">1</td>
                    <td class="py-8 text-right">Rp 4.250.000</td>
                    <td class="py-8 text-right font-black">Rp 4.250.000</td>
                </tr>
            </tbody>
        </table>

        <div class="flex justify-end pt-12">
            <div class="w-72 space-y-4">
                <div class="flex justify-between text-xs text-gray-600 font-bold uppercase tracking-widest">
                    <span>Subtotal</span>
                    <span class="text-black">Rp 4.250.000</span>
                </div>
                <div class="flex justify-between text-xs text-gray-600 font-bold uppercase tracking-widest border-b border-gray-200 pb-4">
                    <span>Shipping</span>
                    <span class="text-black">Rp 150.000</span>
                </div>
                <div class="flex justify-between items-center pt-2">
                    <p class="text-xs font-black uppercase tracking-[0.2em] text-black">Grand Total</p>
                    <p class="text-3xl font-black text-black tracking-tighter">Rp 4.400.000</p>
                </div>
            </div>
        </div>

        <div class="mt-40 pt-10 border-t border-gray-200 flex justify-between items-center">
            <div>
                <p class="text-[10px] font-bold text-black uppercase tracking-widest">Thank you for your purchase.</p>
                <p class="text-[8px] font-semibold text-gray-600 italic">This invoice is a valid proof of transaction on DECOR Marketplace.</p>
            </div>
            <p class="text-[10px] font-bold text-black">© 2026 DECOR.</p>
        </div>
    </div>
</body>
</html>