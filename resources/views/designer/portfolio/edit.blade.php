<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Decor Designer - Edit Masterpiece</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600;700;800&display=swap');
        body { background-color: #F8F6F4; font-family: 'Plus Jakarta Sans', sans-serif; overflow-x: hidden; }
        .bg-primary { background-color: #B5733A; }
        .text-primary { color: #B5733A; }
        .active-link { background-color: rgba(181, 115, 58, 0.1); color: #B5733A; border-right: 4px solid #B5733A; }
        .sidebar-transition { transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1); }
        .sidebar-closed { transform: translateX(-100%); }
        .main-full { margin-left: 0 !important; }
        input[type="file"] { display: none; }
        .custom-scroll::-webkit-scrollbar { width: 4px; }
        .custom-scroll::-webkit-scrollbar-thumb { background: #E5E7EB; border-radius: 10px; }
    </style>
</head>
<body class="text-gray-800">

    @include('designer.partials.sidebar')

    <main id="main-content" class="ml-64 flex flex-col min-h-screen sidebar-transition">
        <!-- HEADER -->
        <header class="h-16 bg-primary flex items-center justify-between px-8 sticky top-0 z-40 text-white">
            <div class="flex items-center">
                <i onclick="toggleSidebar()" class="fa-solid fa-bars-staggered text-xl mr-4 cursor-pointer hover:scale-110 transition-transform"></i>
                <h2 class="font-black text-[10px] uppercase tracking-widest leading-none">Portfolio Management</h2>
            </div>
            <div class="flex items-center space-x-6 text-white">
                <i class="fa-regular fa-bell text-xl"></i>
                <div class="flex items-center space-x-3 bg-white/10 px-3 py-1.5 rounded-xl border border-white/20">
                    <span class="text-[10px] font-black uppercase tracking-widest">Elena Vance</span>
                    <div class="w-8 h-8 rounded-lg bg-white text-primary flex items-center justify-center font-bold">EV</div>
                </div>
            </div>
        </header>

        <!-- CONTENT -->
        <div class="p-10 flex-1">
            <div class="max-w-6xl mx-auto mb-10">
                <a href="{{ route('designer.portfolio.index') }}" class="flex items-center space-x-2 text-gray-400 hover:text-primary transition-all group mb-4">
                    <i class="fa-solid fa-arrow-left-long text-xs group-hover:-translate-x-1 transition-transform"></i>
                    <span class="text-[10px] font-black uppercase tracking-widest">Back to Portfolio</span>
                </a>
                <div class="flex items-center space-x-4">
                    <h3 class="text-2xl font-black text-gray-900 tracking-tight leading-none">Edit Masterpiece</h3>
                    <!-- Dynamic Status Badge -->
                    <span class="text-[8px] bg-red-500 text-white px-3 py-1.5 rounded-lg font-black uppercase tracking-widest shadow-lg shadow-red-200">Rejected</span>
                </div>
            </div>

            <!-- POV 1: ADMIN REJECTION FEEDBACK (Shows if status is rejected) -->
            <div class="max-w-6xl mx-auto mb-8 bg-red-50 border-l-4 border-red-500 p-6 rounded-2xl shadow-sm">
                <div class="flex items-start">
                    <div class="flex-shrink-0">
                        <i class="fa-solid fa-circle-exclamation text-red-500 text-xl"></i>
                    </div>
                    <div class="ml-4">
                        <h4 class="text-xs font-black text-red-600 uppercase tracking-widest leading-none">Rejection Note</h4>
                        <p class="text-[11px] text-red-500 font-bold italic mt-2">"Admin: Foto produk terlalu gelap dan deskripsi material kurang detail. Mohon diperbaiki bebs!"</p>
                    </div>
                </div>
            </div>

            <!-- POV 2: REGULAR UPDATE INFO (Shows if status is Live/Draft) -->
            <div class="hidden max-w-6xl mx-auto mb-8 bg-primary/5 border-l-4 border-primary p-6 rounded-2xl shadow-sm">
                <div class="flex items-start">
                    <div class="flex-shrink-0">
                        <i class="fa-solid fa-circle-info text-primary text-xl"></i>
                    </div>
                    <div class="ml-4">
                        <h4 class="text-xs font-black text-primary uppercase tracking-widest leading-none">Important Update Note</h4>
                        <p class="text-[11px] text-primary/70 font-bold italic mt-2">Mengubah data pada karya yang sudah Live akan membuat statusnya kembali ke 'Pending' untuk divalidasi ulang oleh admin.</p>
                    </div>
                </div>
            </div>

            <form action="{{ route('designer.portfolio.update', $id) }}" method="POST" enctype="multipart/form-data" class="max-w-6xl mx-auto grid grid-cols-1 lg:grid-cols-5 gap-12">
                @csrf
                @method('PUT')

                <!-- LEFT COLUMN: UPDATE VISUALS -->
                <div class="lg:col-span-2 space-y-6">
                    <h3 class="text-[9px] font-black text-gray-400 uppercase tracking-widest">Update Visuals</h3>
                    <label class="block w-full aspect-square bg-gray-100 rounded-[40px] relative overflow-hidden cursor-pointer border-2 border-transparent hover:border-primary transition-all shadow-sm group">
                        <input type="file" name="cover_image">
                        <img src="https://images.unsplash.com/photo-1592078615290-033ee584e267?q=80&w=1000" class="w-full h-full object-cover opacity-60 group-hover:scale-110 transition-transform duration-700">
                        <div class="absolute inset-0 flex flex-col items-center justify-center text-gray-900">
                            <i class="fa-solid fa-sync text-2xl mb-2 group-hover:rotate-180 transition-transform duration-500"></i>
                            <span class="text-[10px] font-black uppercase tracking-widest">Change Cover</span>
                        </div>
                    </label>

                    <div class="grid grid-cols-2 gap-4">
                        <div class="aspect-square bg-white rounded-3xl relative overflow-hidden border border-gray-100 group shadow-sm">
                            <img src="https://images.unsplash.com/photo-1592078615290-033ee584e267?q=80&w=500" class="w-full h-full object-cover">
                            <button type="button" class="absolute top-3 right-3 w-7 h-7 bg-red-500 text-white rounded-xl text-[10px] flex items-center justify-center hover:scale-110 transition-all shadow-lg shadow-red-200">
                                <i class="fa-solid fa-trash-can"></i>
                            </button>
                        </div>
                        <label class="aspect-square bg-gray-50 border-2 border-dashed border-gray-200 rounded-3xl flex flex-col items-center justify-center cursor-pointer hover:border-gray-400 transition-colors">
                            <input type="file" name="gallery[]" accept="image/*" multiple>
                            <i class="fa-solid fa-plus text-gray-300 text-xl"></i>
                            <span class="text-[8px] font-black text-gray-400 uppercase tracking-widest mt-2">Add Photo</span>
                        </label>
                    </div>
                </div>

                <!-- RIGHT COLUMN: DETAILED DATA -->
                <div class="lg:col-span-3 bg-white p-10 rounded-[48px] shadow-sm space-y-8 border border-gray-100">
                    
                    <!-- SERVICE TYPE SELECTOR -->
                    <div>
                        <label class="text-[9px] font-black text-primary uppercase tracking-widest mb-3 block ml-2">Service Category <span class="text-red-500">*</span></label>
                        <select id="service-type" name="service_type" required class="w-full bg-primary/5 border-2 border-primary/10 rounded-2xl py-4 px-6 text-xs font-black text-primary uppercase tracking-widest outline-none appearance-none cursor-pointer">
                            <option value="interior">Interior Consultation</option>
                            <option value="product" selected>Product Design Concept</option>
                        </select>
                    </div>

                    <!-- Row 1: Title & Style -->
                    <div class="grid grid-cols-2 gap-6">
                        <div>
                            <label class="text-[9px] font-black text-gray-400 uppercase tracking-widest mb-2 block ml-2">Project Title <span class="text-red-500">*</span></label>
                            <input type="text" name="title" value="Mid-Century Armchair" required class="w-full bg-gray-50 border-none rounded-2xl py-4 px-6 text-sm font-bold text-gray-900 focus:ring-2 focus:ring-primary/20 outline-none">
                        </div>
                        <div>
                            <label class="text-[9px] font-black text-gray-400 uppercase tracking-widest mb-2 block ml-2">Design Style <span class="text-red-500">*</span></label>
                            <input type="text" name="design_style" value="Mid-Century Modern" required class="w-full bg-gray-50 border-none rounded-2xl py-4 px-6 text-sm font-bold text-gray-900 focus:ring-2 focus:ring-primary/20 outline-none">
                        </div>
                    </div>

                    <!-- Row 2: Dynamic Technical Fields -->
                    <div class="grid grid-cols-1">
                        <div>
                            <label class="text-[9px] font-black text-gray-400 uppercase tracking-widest mb-2 block ml-2 text-primary">Est. Duration <span class="text-red-500">*</span></label>
                            <select name="duration" required class="w-full bg-gray-50 border-none rounded-2xl py-4 px-6 text-xs font-bold text-gray-900 focus:ring-2 focus:ring-primary/20 outline-none appearance-none">
                                <option value="1-3 Months" {{ $designer->portfolios->first()->duration == '1-3 Months' ? 'selected' : '' }}>1-3 Months</option>
                                <option value="1-6 Months" {{ $designer->portfolios->first()->duration == '1-6 Months' ? 'selected' : '' }}>1-6 Months</option>
                                <option value="1-9 Months" {{ $designer->portfolios->first()->duration == '1-9 Months' ? 'selected' : '' }}>1-9 Months</option>
                                <option value="1-12 Months" {{ $designer->portfolios->first()->duration == '1-12 Months' ? 'selected' : '' }}>1-12 Months</option>
                            </select>
                        </div>
                    </div>

                    <!-- Row 3: Design Fee & Year -->
                    <div class="grid grid-cols-2 gap-6">
                        <div>
                            <label class="text-[9px] font-black text-gray-400 uppercase tracking-widest mb-2 block ml-2 text-primary">Design Fee (IDR) <span class="text-red-500">*</span></label>
                            <div class="flex items-center bg-gray-50 rounded-2xl px-6 focus-within:ring-2 focus-within:ring-primary/20 transition-all">
                                <span class="text-xs font-black text-gray-400 uppercase tracking-widest mr-2">Rp</span>
                                <input type="number" name="price" value="4500000" required class="w-full bg-transparent border-none py-4 text-xs font-bold text-gray-900 outline-none">
                            </div>
                        </div>
                        <div>
                            <label class="text-[9px] font-black text-gray-400 uppercase tracking-widest mb-2 block ml-2">Completion Year <span class="text-red-500">*</span></label>
                            <input type="number" name="year" value="2024" required class="w-full bg-gray-50 border-none rounded-2xl py-4 px-6 text-xs font-bold text-gray-900 focus:ring-2 focus:ring-primary/20 outline-none">
                        </div>
                    </div>

                    <!-- Row 4: Category & Sub-Category -->
                    <div class="grid grid-cols-2 gap-6">
                        <div>
                            <label class="text-[9px] font-black text-gray-400 uppercase tracking-widest mb-2 block ml-2 text-primary">Main Category <span class="text-red-500">*</span></label>
                            <select id="main-category" name="category" required class="w-full bg-gray-50 border-none rounded-2xl py-4 px-6 text-xs font-bold text-gray-900 focus:ring-2 focus:ring-primary/20 outline-none appearance-none">
                                <option value="Furniture" selected>Furniture</option>
                                <option value="Residential">Residential</option>
                                <option value="Commercial">Commercial</option>
                            </select>
                        </div>
                        <div>
                            <label class="text-[9px] font-black text-gray-400 uppercase tracking-widest mb-2 block ml-2 text-primary">Service Detail <span class="text-red-500">*</span></label>
                            <select id="sub-category" name="sub_category" required class="w-full bg-gray-50 border-none rounded-2xl py-4 px-6 text-xs font-bold text-gray-900 focus:ring-2 focus:ring-primary/20 outline-none appearance-none">
                                <option value="Lounge" selected>Lounge</option>
                            </select>
                        </div>
                    </div>

                    <!-- Row 5: Material & Lead Designer -->
                    <div class="grid grid-cols-2 gap-6">
                        <div>
                            <label class="text-[9px] font-black text-gray-400 uppercase tracking-widest mb-2 block ml-2 text-primary">Key Materials</label>
                            <input type="text" name="material" value="Teak Wood, Velvet Fabric" class="w-full bg-gray-50 border-none rounded-2xl py-4 px-6 text-xs font-bold text-gray-900 focus:ring-2 focus:ring-primary/20 outline-none">
                        </div>
                        <div>
                            <label class="text-[9px] font-black text-gray-400 uppercase tracking-widest mb-2 block ml-2 text-primary">Lead Designer <span class="text-red-500">*</span></label>
                            <input type="text" name="lead_designer" value="Elena Vance" required class="w-full bg-gray-50 border-none rounded-2xl py-4 px-6 text-xs font-bold text-gray-900 focus:ring-2 focus:ring-primary/20 outline-none">
                        </div>
                    </div>

                    <!-- Row 6: Link & Concept Note -->
                    <div class="space-y-6">
                        <div>
                            <label class="text-[9px] font-black text-gray-400 uppercase tracking-widest mb-2 block ml-2 flex items-center"><i class="fa-solid fa-link mr-2"></i> External Link</label>
                            <input type="url" name="external_link" value="https://behance.net/elena-vance" class="w-full bg-gray-50 border-none rounded-2xl py-4 px-6 text-[10px] font-bold text-gray-900 focus:ring-2 focus:ring-primary/20 outline-none">
                        </div>
                        <div>
                            <label class="text-[9px] font-black text-gray-400 uppercase tracking-widest mb-2 block ml-2">Design Philosophy / Concept Note <span class="text-red-500">*</span></label>
                            <textarea name="description" rows="3" required class="w-full bg-gray-50 border-none rounded-3xl py-5 px-6 text-xs font-bold text-gray-900 focus:ring-2 focus:ring-primary/20 outline-none resize-none custom-scroll">Sofa minimalis dengan aksen kayu jati solid. Menggunakan busa high-density yang tidak mudah kempes...</textarea>
                        </div>
                    </div>

                    <!-- Form Actions -->
                    <div class="pt-6 border-t border-gray-50 flex items-center justify-between">
                        <label class="flex items-center cursor-pointer group">
                            <div class="relative">
                                <input type="checkbox" name="is_live" class="sr-only" checked>
                                <div class="block-toggle bg-green-500 w-12 h-7 rounded-full transition-colors"></div>
                                <div class="dot absolute left-1 top-1 bg-white w-5 h-5 rounded-full transition-transform translate-x-5 shadow-sm"></div>
                            </div>
                            <div class="ml-4">
                                <p id="submit-subtext-main" class="text-[10px] font-black text-gray-900 uppercase tracking-widest">Resubmit for Approval</p>
                                <p class="text-[8px] text-gray-400 font-bold italic">Status will return to Pending Review.</p>
                            </div>
                        </label>
                        <div class="space-x-4 flex">
                            <a href="{{ route('designer.portfolio.index') }}" class="px-8 py-4 border border-gray-200 rounded-2xl text-[10px] font-black uppercase tracking-widest text-gray-400 hover:bg-gray-50 transition-all text-center">Discard Changes</a>
                            <button id="submit-btn-label" type="submit" class="bg-primary text-white px-10 py-4 rounded-2xl text-[10px] font-black uppercase tracking-widest shadow-xl shadow-primary/30 hover:scale-105 transition-all">Resubmit for Approval</button>
                        </div>
                    </div>
                </div>
            </form>
        </div>

        <footer class="p-8 border-t border-gray-100 text-[9px] font-black text-gray-400 uppercase tracking-widest bg-white mt-auto text-center">
            © 2026 DECOR DESIGNER PORTAL. ALL RIGHTS RESERVED.
        </footer>
    </main>

    <script>
        function toggleSidebar() {
            document.getElementById('sidebar').classList.toggle('sidebar-closed');
            document.getElementById('main-content').classList.toggle('main-full');
        }

        const subCategories = {
            'Furniture': ['Lounge', 'Chair', 'Table', 'Cabinet', 'Bed'],
            'Residential': ['Living Room', 'Bedroom', 'Kitchen', 'Studio'],
            'Commercial': ['Cafe', 'Office', 'Retail Store', 'Lobby']
        };

        const serviceType = document.getElementById('service-type');
        const techLabel = document.getElementById('tech-label-1');
        const techInput = document.getElementById('tech-input-1');
        const techSuffix = document.getElementById('tech-suffix-1');
        const mainCat = document.getElementById('main-category');
        const subCat = document.getElementById('sub-category');

        // Technical fields logic removed as per area input removal

        mainCat.addEventListener('change', function() {
            const selected = this.value;
            subCat.innerHTML = '<option value="" disabled selected>Select Sub</option>';
            if (subCategories[selected]) {
                subCategories[selected].forEach(item => {
                    const opt = document.createElement('option');
                    opt.value = item;
                    opt.innerHTML = item;
                    subCat.appendChild(opt);
                });
            }
        });

        // Initialize state
        updateTechnicalFields(serviceType.value);

        const toggleInput = document.querySelector('input[name="is_live"]');
        const toggleDot = document.querySelector('.dot');
        const toggleBg = document.querySelector('.block-toggle');
        toggleInput.addEventListener('change', function() {
            if(this.checked) {
                toggleDot.classList.add('translate-x-5');
                toggleBg.classList.replace('bg-gray-200', 'bg-green-500');
            } else {
                toggleDot.classList.remove('translate-x-5');
                toggleBg.classList.replace('bg-green-500', 'bg-gray-200');
            }
        });
    </script>
</body>
</html>