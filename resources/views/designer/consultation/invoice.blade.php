<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invoice #{{ $consultation_id }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600;700;800&display=swap');
        body { background-color: #E5E7EB; font-family: 'Plus Jakarta Sans', sans-serif; }
        .invoice-paper { background: white; box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.15); min-height: 29.7cm; width: 21cm; margin: 40px auto; padding: 80px; }
    </style>
</head>
<body class="p-10">
    <div class="invoice-paper">
        <div class="flex justify-between items-start mb-20 border-b border-gray-50 pb-10">
            <div>
                <h1 class="text-4xl font-black text-[#B5733A] tracking-[0.2em] leading-none uppercase">DECOR</h1>
                <p class="text-[10px] text-gray-400 mt-2 uppercase tracking-widest font-bold italic">Official Project Invoice</p>
            </div>
            <div class="text-right"><span class="bg-green-50 text-green-600 px-6 py-2 rounded-full text-[10px] font-black uppercase tracking-widest border border-green-100">Paid</span></div>
        </div>

        <div class="grid grid-cols-2 gap-10 mb-20">
            <div>
                <p class="text-[10px] text-gray-400 uppercase font-black tracking-widest mb-3 italic font-bold">Billed To:</p>
                <h3 class="text-lg font-black text-gray-900 uppercase leading-none mb-1">Elena Rodriguez</h3>
                <p class="text-sm text-gray-500 font-medium italic">elena.rod@example.com</p>
            </div>
            <div class="text-right">
                <p class="text-[10px] text-gray-400 uppercase font-black tracking-widest mb-3 italic font-bold">Order Details:</p>
                <p class="text-sm font-bold text-gray-900 uppercase">Invoice ID: <span class="text-[#B5733A]">#{{ $consultation_id }}</span></p>
                <p class="text-sm font-medium text-gray-500 mt-1 uppercase font-bold italic">Date: April 28, 2026</p>
            </div>
        </div>

        <div class="mb-40">
            <table class="w-full">
                <thead><tr class="border-b-2 border-gray-900"><th class="text-left pb-5 text-[10px] font-black uppercase tracking-widest">Description</th><th class="text-right pb-5 text-[10px] font-black uppercase tracking-widest">Amount</th></tr></thead>
                <tbody><tr class="border-b border-gray-50"><td class="py-10"><p class="font-black text-gray-900 uppercase leading-none mb-1">Penthouse Interior Concept</p><p class="text-xs text-gray-400 italic font-bold uppercase tracking-tighter">Residential consultation & moodboard development.</p></td><td class="py-10 text-right font-black text-gray-900">$1,250.00</td></tr></tbody>
                <tfoot><tr><td class="pt-10 text-right text-[10px] font-black uppercase text-gray-400 italic font-bold">Total Paid</td><td class="pt-10 text-right text-3xl font-black text-[#B5733A] tracking-tighter">$1,250.00</td></tr></tfoot>
            </table>
        </div>

        <div class="mt-40 pt-10 border-t border-gray-50 text-center"><p class="text-[10px] font-black text-gray-300 uppercase tracking-[0.4em] italic font-bold">Thank you for curating excellence with DECOR.</p><p class="text-[8px] text-gray-200 mt-4 font-bold uppercase tracking-widest">© 2026 DECOR. ALL RIGHTS RESERVED.</p></div>
    </div>
</body>
</html>