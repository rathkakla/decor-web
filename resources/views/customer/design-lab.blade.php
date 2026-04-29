<?php $site_name = "DECOR"; ?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>AI Design Lab — <?= $site_name ?></title>
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
<body class="text-gray-800">

    <header class="bg-white border-b border-gray-100 sticky top-0 z-50">
        <div class="content-container flex justify-between items-center py-4 px-6">
            <div class="flex items-center space-x-8 flex-1">
                <a href="{{ route('homepage') }}" class="text-2xl font-black tracking-tighter uppercase text-primary hover:opacity-80 transition-all">
                    <?= $site_name ?>
                </a>
                <div class="hidden lg:flex items-center bg-gray-50 border border-gray-100 rounded-md px-4 py-2 w-full max-w-[180px] group focus-within:bg-white focus-within:border-primary/30 transition-all">
                    <i class="fa-solid fa-magnifying-glass text-gray-400 text-[10px] mr-2"></i>
                    <input type="text" placeholder="Search..." class="bg-transparent border-none outline-none text-[10px] w-full placeholder:text-gray-400">
                </div>
            </div>
            <nav class="hidden md:flex items-center space-x-10 text-[13px] font-medium text-gray-500 tracking-wide">
                <a href="{{ route('customer.catalog') }}" class="hover:text-primary transition-all">Collections</a>
                <a href="{{ route('customer.designers') }}" class="hover:text-primary transition-all">Designers</a>
                <a href="{{ route('customer.design-lab') }}" class="hover:text-primary transition-all">AI Studio</a>
               
            </nav>
            <div class="flex items-center space-x-6 flex-1 justify-end">
                <a href="{{ route('customer.cart') }}" class="text-primary hover:scale-110 transition-transform">
                    <i class="fa-solid fa-bag-shopping text-lg"></i>
                </a>
                <button class="text-primary hover:scale-110 transition-transform">
                    <i class="fa-regular fa-bell text-lg"></i>
                </button>
                <div class="w-9 h-9 rounded-md overflow-hidden border border-gray-200 cursor-pointer">
<a href="{{ route('customer.profile') }}" class="block">
    <div class="w-9 h-9 rounded-md overflow-hidden border border-gray-200 cursor-pointer hover:border-primary transition-all">
        <img src="https://api.dicebear.com/7.x/avataaars/svg?seed=Felix" class="w-full h-full bg-slate-100">
    </div>
</a>
                </div>
            </div>
        </div>
    </header>

    <main class="py-16 content-container px-6">
        <div class="grid grid-cols-1 lg:grid-cols-[400px_1fr] gap-16 items-center mb-24">
            <div class="space-y-8">
                <h1 class="text-7xl font-bold tracking-tighter leading-[0.9]">Reimagine<br><span class="text-primary italic">Your Space</span></h1>
                <p class="text-gray-400 text-sm leading-relaxed">
                    Upload a photo of your room and let our generative AI architect curate a professional interior design tailored to your architectural bones.
                </p>
                
                <div class="border-2 border-dashed border-gray-200 rounded-3xl p-10 text-center hover:border-primary transition-colors cursor-pointer group">
                    <div class="w-12 h-12 bg-orange-50 rounded-full flex items-center justify-center mx-auto mb-4 group-hover:bg-primary transition-colors">
                        <i class="fa-solid fa-camera-retro text-primary group-hover:text-white"></i>
                    </div>
                    <p class="text-sm font-bold">Upload Room Photo</p>
                    <p class="text-[10px] text-gray-400 mt-1 uppercase tracking-widest">JPEG, PNG or HEIC up to 20MB</p>
                </div>
            </div>

            <div class="relative aspect-video rounded-[2.5rem] overflow-hidden shadow-2xl">
                <img src="https://images.unsplash.com/photo-1618221195710-dd6b41faaea6?w=1200" class="w-full h-full object-cover">
                <div class="absolute inset-0 bg-black/20"></div>
                <div class="absolute inset-y-0 left-1/2 w-1  shadow-xl flex items-center justify-center">
                    
                </div>
                
                <div class="absolute top-6 right-6 bg-primary/80 backdrop-blur-md px-4 py-1 rounded-full text-[10px] text-white font-bold uppercase tracking-widest">AI Reimagined</div>
            </div>
        </div>

        <section class="mb-24">
            <div class="flex justify-between items-end mb-12">
                <div class="space-y-2">
                    <h2 class="text-3xl font-bold tracking-tight">Select Architectural Style</h2>
                    <p class="text-sm text-gray-400 max-w-md">Our AI filters through thousands of design monographs to apply specific aesthetic principles to your layout.</p>
                </div>
               
            </div>

            <div class="grid grid-cols-2 md:grid-cols-5 gap-4">
                <?php 
                $styles = [
                    ['name' => 'Scandinavian', 'desc' => 'Light & Organic', 'img' => 'https://images.unsplash.com/photo-1594026112284-02bb6f3352fe?w=400'],
                    ['name' => 'Industrial', 'desc' => 'Raw & Structural', 'img' => 'https://images.unsplash.com/photo-1505015920881-0f83c2f7c95e?w=400'],
                    ['name' => 'Minimalist', 'desc' => 'Pure & Essential', 'img' => 'https://images.unsplash.com/photo-1512918728675-ed5a9ecdebfd?w=400'],
                    ['name' => 'Modern', 'desc' => 'Sleek & Fluid', 'img' => 'https://images.unsplash.com/photo-1493809842364-78817add7ffb?w=400'],
                    ['name' => 'Classic', 'desc' => 'Timeless & Ornate', 'img' => 'https://images.unsplash.com/photo-1533090161767-e6ffed986c88?w=400'],
                ];
                foreach($styles as $s):
                ?>
                <div class="group cursor-pointer">
                    <div class="aspect-[4/5] rounded-2xl overflow-hidden bg-gray-100 mb-4 border-2 border-transparent group-hover:border-primary transition-all">
                        <img src="<?= $s['img'] ?>" class="w-full h-full object-cover">
                    </div>
                    <h4 class="text-sm font-bold"><?= $s['name'] ?></h4>
                    <p class="text-[10px] text-gray-400 uppercase tracking-widest"><?= $s['desc'] ?></p>
                </div>
                <?php endforeach; ?>
            </div>
        </section>

        <section class="mb-20">
            <div class="flex justify-between items-center mb-12">
                <h2 class="text-3xl font-bold tracking-tight">Shop the Look</h2>
                <a href="#" class="text-[10px] font-bold uppercase tracking-widest text-primary border-b border-primary">View All 12 Items</a>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
                <?php 
                $looks = [
                    ['name' => 'Aurelius Lounge Chair', 'price' => '2,450', 'img' => 'https://images.unsplash.com/photo-1592078615290-033ee584e267?w=400'],
                    ['name' => 'Zenith Pendant Light', 'price' => '890', 'img' => 'https://images.unsplash.com/photo-1524484485831-a92ffc0de03f?w=400'],
                    ['name' => 'Nordic Floating Credenza', 'price' => '1,720', 'img' => 'https://images.unsplash.com/photo-1595428774223-ef52624120d2?w=400'],
                    ['name' => 'Handwoven Wool Rug', 'price' => '640', 'img' => 'https://images.unsplash.com/photo-1575414003591-ece8d0416c7a?w=400'],
                ];
                foreach($looks as $l):
                ?>
                <div class="space-y-4">
                    <div class="aspect-square rounded-2xl overflow-hidden bg-gray-50">
                        <img src="<?= $l['img'] ?>" class="w-full h-full object-cover hover:scale-110 transition duration-700">
                    </div>
                    <div class="flex justify-between items-start">
                        <div>
                            <h5 class="text-xs font-bold"><?= $l['name'] ?></h5>
                            <p class="text-[9px] text-gray-400 mt-1 uppercase tracking-widest">Architectural Series</p>
                        </div>
                        <span class="text-xs font-bold text-primary">$<?= $l['price'] ?></span>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        </section>
    </main>

     <footer class="bg-primary text-white py-12 px-6 mt-12">
        <div class="max-w-6xl mx-auto"> 
            <div class="flex flex-col md:flex-row justify-between items-start border-b border-white/20 pb-10 gap-10">
                <div class="space-y-4">
                    <div class="flex items-center space-x-2 text-white">
                        <div class="w-8 h-8 bg-white/20 rounded flex items-center justify-center">
                            <i class="fa-solid fa-couch text-sm"></i>
                        </div>
                        <span class="text-xl font-bold tracking-widest uppercase">DECOR</span>
                    </div>
                    <div class="text-sm text-white/90 space-y-2">
                        <p class="flex items-center"><i class="fa-regular fa-envelope mr-3 text-xs"></i> decorofficial@gmail.com</p>
                        <p class="flex items-center"><i class="fa-brands fa-instagram mr-3 text-xs"></i> @decor_official</p>
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-x-16 gap-y-4 text-[10px] font-bold tracking-widest uppercase text-white/90">
                    <div class="flex flex-col space-y-3">
                        <a href="#" class="hover:text-white/70">Terms of Service</a>
                        <a href="#" class="hover:text-white/70">Privacy Policy</a>
                    </div>
                    <div class="flex flex-col space-y-3">
                         <a href="{{ route('customer.help-center') }}" class="hover:text-white/70 transition-colors">Help Center</a>
                        <a href="#" class="hover:text-white/70">FAQ</a>
                    </div>
                </div>

                <div class="flex flex-col items-end space-y-6">
                    <span class="text-[10px] font-bold tracking-widest uppercase text-white/40">Portal Version 1.0</span>
                    <div class="flex space-x-4">
                        <a href="#" class="w-10 h-10 border border-white/40 flex items-center justify-center rounded-full hover:bg-white hover:text-primary transition-all"><i class="fa-brands fa-instagram"></i></a>
                        <a href="#" class="w-10 h-10 border border-white/40 flex items-center justify-center rounded-full hover:bg-white hover:text-primary transition-all"><i class="fa-regular fa-paper-plane"></i></a>
                    </div>
                </div>
            </div>
            <div class="pt-8 text-[10px] text-white/60 font-medium tracking-widest">
                <p>©️ 2026 DECOR MARKETPLACE. ALL RIGHTS RESERVED.</p>
            </div>
        </div>
    </footer>

</body>
</html>