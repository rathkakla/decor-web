<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Decor Seller - Update Bank Info</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600;700;800&display=swap');
        body { background-color: #F8F6F4; font-family: 'Plus Jakarta Sans', sans-serif; }
        .bg-primary { background-color: #B5733A; }
    </style>
</head>
<body class="text-gray-800">
    <main class="max-w-2xl mx-auto py-20 space-y-12">
        <div class="flex items-center justify-between">
            <a href="{{ route('seller.settings') }}" class="text-[10px] font-black uppercase tracking-widest text-gray-400 hover:text-primary transition-colors">
                <i class="fa-solid fa-arrow-left mr-2"></i> Back to Settings
            </a>
            <h2 class="text-xs font-black uppercase tracking-[0.3em] text-primary italic">Financial Security</h2>
        </div>

        <div class="bg-white p-12 rounded-[48px] border border-gray-100 shadow-sm space-y-10">
            <div>
                <h3 class="text-3xl font-black tracking-tight text-gray-800">Update Bank Account</h3>
                <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest mt-1">This info is used for your revenue withdrawal</p>
            </div>

            <form class="space-y-8">
                <div class="space-y-2">
                    <label class="text-[10px] font-black uppercase text-gray-400 ml-4 tracking-widest">Select Bank</label>
                    <select class="w-full bg-gray-50 border-none rounded-3xl p-5 text-xs font-bold outline-none focus:ring-2 focus:ring-primary/20 transition-all">
                        <option>Bank Central Asia (BCA)</option>
                        <option>Bank Mandiri</option>
                        <option>Bank Rakyat Indonesia (BRI)</option>
                    </select>
                </div>
                <div class="space-y-2">
                    <label class="text-[10px] font-black uppercase text-gray-400 ml-4 tracking-widest">Account Number</label>
                    <input type="number" placeholder="e.g. 8820033102" class="w-full bg-gray-50 border-none rounded-3xl p-5 text-xs font-bold outline-none focus:ring-2 focus:ring-primary/20">
                </div>
                <div class="space-y-2">
                    <label class="text-[10px] font-black uppercase text-gray-400 ml-4 tracking-widest">Account Holder Name</label>
                    <input type="text" placeholder="Full name as printed in bank book" class="w-full bg-gray-50 border-none rounded-3xl p-5 text-xs font-bold outline-none focus:ring-2 focus:ring-primary/20">
                </div>
                
                <button type="submit" class="w-full bg-primary text-white py-6 rounded-3xl text-[10px] font-black uppercase tracking-[0.3em] shadow-2xl shadow-primary/30 hover:opacity-90 transition-all">
                    Verify & Save Details
                </button>
            </form>
        </div>
    </main>
</body>
</html>