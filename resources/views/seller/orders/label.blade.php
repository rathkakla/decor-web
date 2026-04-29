<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Shipping Label - DECOR</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@700;800&display=swap');
        body { font-family: 'Plus Jakarta Sans', sans-serif; color: #000; }
        @media print { 
            .no-print { display: none; } 
            body { padding: 0; background: white; }
            * { -webkit-print-color-adjust: exact !important; print-color-adjust: exact !important; }
        }
        .dashed-border { border: 3px dashed #000; }
    </style>
</head>
<body class="bg-gray-100 p-10">

    <div class="no-print mb-6 text-center">
        <button onclick="window.print()" class="bg-black text-white px-6 py-2 rounded-lg text-[10px] font-bold uppercase tracking-widest">Print Resi</button>
    </div>

    <div class="w-[550px] mx-auto bg-white dashed-border p-10">
        <div class="flex justify-between items-center border-b-2 border-black pb-4 mb-6">
            <h1 class="text-3xl font-black italic tracking-tighter text-black">DECOR.</h1>
            <div class="text-right">
                <p class="text-[9px] font-bold uppercase text-gray-600 tracking-widest">Order ID</p>
                <p class="text-lg font-black leading-none text-black">#782911</p>
            </div>
        </div>

        <div class="space-y-6">
            <div class="border-l-4 border-black pl-4">
                <p class="text-[8px] font-bold uppercase tracking-widest text-gray-600">Sender (Pengirim):</p>
                <p class="text-sm font-black uppercase mt-1 text-black">DECOR Official Store</p>
                <p class="text-[10px] font-semibold text-black">Bandung Central Warehouse - 081122334455</p>
            </div>

            <div class="bg-black text-white p-6">
                <p class="text-[8px] font-bold uppercase tracking-[0.3em] opacity-80">To (Penerima):</p>
                <p class="text-xl font-black mt-2 uppercase tracking-tight text-white">Siti Aminah</p>
                <p class="text-xs mt-3 leading-snug font-semibold text-white">
                    Jl. Sukajadi No. 123, Kel. Pasteur, Kec. Sukajadi, Kota Bandung, Jawa Barat, 40162
                </p>
                <p class="text-sm font-black mt-3 text-white">+62 812 3456 7890</p>
            </div>

            <div class="border-2 border-black h-24 flex flex-col items-center justify-center space-y-2">
                <div class="flex space-x-1">
                    @for($i=0; $i<45; $i++)
                        <div class="h-12 bg-black" style="width: {{ rand(1, 4) }}px"></div>
                    @endfor
                </div>
                <p class="text-[10px] font-black tracking-[0.6em] text-black uppercase">ORD782911SITI</p>
            </div>

            <div class="flex justify-between items-center pt-4 border-t border-black">
                <div>
                    <p class="text-[8px] font-bold text-gray-600 uppercase">Logistic</p>
                    <p class="text-lg font-black italic tracking-tighter uppercase text-black">JNE TRUCKING</p>
                </div>
                <div class="text-right">
                    <p class="text-[10px] font-bold uppercase border-2 border-black px-3 py-1 text-black">FRAGILE</p>
                </div>
            </div>
        </div>
    </div>

</body>
</html>