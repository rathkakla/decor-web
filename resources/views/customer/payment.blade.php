<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment — {{ env('APP_NAME', 'DECOR') }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: '#B5733A',
                        secondary: '#E3DCD6',
                    }
                }
            }
        }
    </script>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;600;700&display=swap');
        body { font-family: 'Plus Jakarta Sans', sans-serif; background-color: #ffffff; }
        .content-container { max-width: 1200px; margin: 0 auto; }
    </style>
</head>
<body class="text-gray-800 flex flex-col min-h-screen">

    @include('customer.partials.navbar')

    <main class="py-20 content-container px-6 flex-1 flex flex-col items-center">
        <h1 class="text-4xl font-bold tracking-tighter mb-4">Complete Your Payment</h1>
        <p class="text-gray-500 mb-8">Please review your payment details below.</p>

        <div class="w-full max-w-2xl bg-gray-50 rounded-[2rem] p-10 border border-gray-100 mb-8">
            <div class="flex justify-between items-center border-b border-gray-200 pb-6 mb-6">
                <div>
                    <h4 class="text-[10px] font-bold uppercase tracking-[0.2em] text-primary">Order ID</h4>
                    <p class="text-lg font-bold">#{{ $order->id }}</p>
                </div>
                <div class="text-right">
                    <h4 class="text-[10px] font-bold uppercase tracking-[0.2em] text-primary">Total Amount</h4>
                    <p class="text-2xl font-black text-primary">Rp {{ number_format($order->total_price, 0, ',', '.') }}</p>
                </div>
            </div>

            @if($order->payment_method === 'bank_transfer')
                <div class="text-center py-6">
                    <i class="fa-solid fa-building-columns text-4xl text-primary mb-4"></i>
                    <h3 class="text-xl font-bold mb-2">Bank Transfer</h3>
                    <p class="text-sm text-gray-500 mb-6">Please transfer the exact amount to the following Virtual Account number:</p>
                    
                    <div class="bg-white border border-primary p-6 rounded-xl inline-block w-full max-w-sm mx-auto mb-6">
                        <p class="text-[10px] font-bold uppercase tracking-widest text-gray-400 mb-2">BCA Virtual Account</p>
                        <p class="text-3xl font-black tracking-widest text-gray-800">8077 1234 5678</p>
                    </div>

                    @if($order->status === 'waiting_verification')
                        <div class="bg-orange-50 border border-orange-200 p-6 rounded-xl text-orange-700">
                            <i class="fa-solid fa-clock-rotate-left mb-2 text-2xl"></i>
                            <p class="text-sm font-bold">Waiting for Verification</p>
                            <p class="text-[10px] mt-1 opacity-80">Your payment proof is being reviewed by the seller. We will notify you once it is approved.</p>
                        </div>
                    @else
                        <form action="{{ route('customer.payment.confirm', $order->id) }}" method="POST" enctype="multipart/form-data" class="mt-8 space-y-6">
                            @csrf
                            <div class="max-w-md mx-auto">
                                <label class="block text-[10px] font-bold uppercase tracking-widest text-gray-400 mb-3 text-left">Upload Payment Proof</label>
                                <div class="relative group">
                                    <input type="file" name="payment_proof" id="payment_proof" required class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-10">
                                    <div class="border-2 border-dashed border-gray-200 rounded-xl p-8 group-hover:border-primary group-hover:bg-primary/5 transition-all flex flex-col items-center justify-center">
                                        <i class="fa-solid fa-cloud-arrow-up text-3xl text-gray-300 group-hover:text-primary mb-3 transition-colors"></i>
                                        <p id="file-name" class="text-[10px] font-bold text-gray-400 group-hover:text-primary tracking-widest uppercase text-center">Click to browse or drag & drop</p>
                                        <p class="text-[9px] text-gray-300 mt-1 uppercase tracking-widest">Max size 2MB (JPG, PNG)</p>
                                    </div>
                                </div>
                                @error('payment_proof')
                                    <p class="text-red-500 text-[10px] mt-2 font-bold">{{ $message }}</p>
                                @enderror
                            </div>

                            <button type="submit" class="w-full md:w-auto px-12 bg-primary text-white py-4 rounded-sm text-xs font-bold uppercase tracking-widest hover:bg-opacity-90 transition shadow-xl shadow-primary/30 mt-8">
                                Confirm & Upload Proof
                            </button>
                        </form>
                        <script>
                            document.getElementById('payment_proof').addEventListener('change', function(e) {
                                const fileName = e.target.files[0] ? e.target.files[0].name : 'Click to browse or drag & drop';
                                document.getElementById('file-name').textContent = fileName;
                                document.getElementById('file-name').classList.add('text-primary');
                            });
                        </script>
                    @endif
                </div>
            @else
                <div class="text-center py-6">
                    <i class="fa-solid fa-wallet text-4xl text-primary mb-4"></i>
                    <h3 class="text-xl font-bold mb-2">Cash on Delivery</h3>
                    <p class="text-sm text-gray-500 mb-6">Please prepare the exact amount in cash when the courier arrives at your address.</p>
                    
                    <form action="{{ route('customer.payment.confirm', $order->id) }}" method="POST" class="text-center mt-8">
                        @csrf
                        <button type="submit" class="w-full md:w-auto px-12 bg-primary text-white py-4 rounded-sm text-xs font-bold uppercase tracking-widest hover:bg-opacity-90 transition shadow-xl shadow-primary/30">
                            Confirm Order
                        </button>
                    </form>
                </div>
            @endif
        </div>
    </main>

    <footer class="bg-primary text-white py-12 px-6 mt-auto">
        <div class="max-w-6xl mx-auto">
            <div class="pt-8 text-[10px] text-white/60 font-medium tracking-widest text-center">
                <p>©️ 2026 DECOR MARKETPLACE. ALL RIGHTS RESERVED.</p>
            </div>
        </div>
    </footer>

</body>
</html>