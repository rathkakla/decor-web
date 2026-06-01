<?php 
    $site_name = "DECOR"; 
    $user = Auth::user(); 
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Review Product — {{ $site_name }}</title>
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
        .rating-stars input[type="radio"] { display: none; }
        .rating-stars label { color: #ccc; cursor: pointer; transition: color 0.2s; }
        .rating-stars label:hover, .rating-stars label:hover ~ label, .rating-stars input[type="radio"]:checked ~ label {
            color: #B5733A;
        }
        .rating-stars { direction: rtl; display: inline-flex; }
    </style>
</head>
<body class="text-gray-800 flex flex-col min-h-screen">

    @include('customer.partials.navbar')

    <main class="flex-grow flex content-container w-full bg-white">
        <div class="flex-grow p-12">
            <div class="max-w-2xl mx-auto">
                <header class="mb-12 text-center">
                    <h1 class="text-4xl font-bold tracking-tighter mb-2 text-gray-900">Review Product</h1>
                    <p class="text-[11px] text-gray-400 uppercase tracking-[0.2em] font-bold">Share your thoughts on this product.</p>
                </header>

                <form action="{{ route('customer.review.submit', $product->id) }}" method="POST">
                    @csrf
                    <section class="mb-8">
                        <div class="p-6 bg-gray-50/50 rounded-2xl border border-gray-100 flex items-center gap-6">
                            <div class="w-20 h-20 bg-white rounded-xl overflow-hidden border border-gray-100 shrink-0 flex items-center justify-center">
                                <img src="{{ $product->images->first()->img_url ?? 'https://via.placeholder.com/200' }}" class="w-full h-full object-cover">
                            </div>
                            <div class="flex-grow">
                                <p class="text-[10px] text-gray-400 uppercase font-bold tracking-widest">{{ $product->category->name ?? 'Category' }}</p>
                                <h4 class="font-bold text-sm">{{ $product->name }}</h4>
                            </div>
                            <div class="text-right">
                                <p class="text-sm font-bold text-primary">Rp {{ number_format($product->price, 0, ',', '.') }}</p>
                            </div>
                        </div>
                    </section>

                    <section class="space-y-8">
                        <div class="space-y-6">
                            
                            <div class="grid grid-cols-1 gap-6">
                                <div class="space-y-2 text-center">
                                    <label class="text-[10px] font-bold uppercase text-gray-400 tracking-widest block mb-2">Your Rating</label>
                                    <div class="rating-stars text-3xl">
                                        <input type="radio" id="star5" name="rating" value="5" required />
                                        <label for="star5" class="fa-solid fa-star"></label>
                                        <input type="radio" id="star4" name="rating" value="4" />
                                        <label for="star4" class="fa-solid fa-star"></label>
                                        <input type="radio" id="star3" name="rating" value="3" />
                                        <label for="star3" class="fa-solid fa-star"></label>
                                        <input type="radio" id="star2" name="rating" value="2" />
                                        <label for="star2" class="fa-solid fa-star"></label>
                                        <input type="radio" id="star1" name="rating" value="1" />
                                        <label for="star1" class="fa-solid fa-star"></label>
                                    </div>
                                </div>

                                <div class="space-y-2">
                                    <label class="text-[10px] font-bold uppercase text-gray-400 tracking-widest">Review Comment</label>
                                    <textarea name="comment" rows="5" placeholder="What did you like or dislike about this product?" class="w-full bg-gray-50 border border-gray-100 rounded-xl px-4 py-4 text-xs outline-none focus:border-primary transition-all resize-none"></textarea>
                                </div>
                            </div>
                        </div>

                        <div class="flex items-center justify-end gap-4 pt-4 border-t border-gray-100">
                            <a href="{{ route('customer.orders') }}" class="px-8 py-3 text-[10px] font-bold uppercase tracking-widest text-gray-400 hover:text-gray-600 transition-all">Cancel</a>
                            <button type="submit" class="px-10 py-3 bg-primary text-white text-[10px] font-bold uppercase tracking-widest rounded-xl shadow-lg shadow-primary/20 hover:opacity-90 transition-all">Submit Review</button>
                        </div>
                    </section>
                </form>
            </div>
        </div>
    </main>
</body>
</html>