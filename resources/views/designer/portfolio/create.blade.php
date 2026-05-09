<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Decor Designer - Add New Masterpiece</title>
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

    <!-- SIDEBAR -->
    <aside id="sidebar" class="w-64 bg-white border-r border-gray-200 flex flex-col fixed h-full z-50 sidebar-transition">
        <div class="p-8">
            <h1 class="text-2xl font-bold tracking-widest text-primary uppercase leading-none">DECOR</h1>
            <p class="text-[10px] text-gray-400 mt-1 uppercase tracking-widest italic font-bold">Designer Portal</p>
        </div>
        
        <nav class="flex-1 px-4 space-y-1">
            <a href="{{ route('designer.dashboard') }}" class="flex items-center px-4 py-3 text-xs font-bold text-gray-400 hover:text-primary transition-all rounded-lg"><i class="fa-solid fa-table-columns mr-3 w-5 text-center"></i> Dashboard</a>
            <a href="{{ route('designer.portfolio.index') }}" class="flex items-center px-4 py-3 text-xs font-bold active-link transition-all rounded-lg"><i class="fa-solid fa-briefcase mr-3 w-5 text-center"></i> Kelola Portofolio</a>
            <a href="{{ route('designer.consultations.index') }}" class="flex items-center px-4 py-3 text-xs font-bold text-gray-400 hover:text-primary transition-all rounded-lg"><i class="fa-solid fa-calendar-check mr-3 w-5 text-center"></i>Konsultasi</a>
            <a href="{{ route('designer.chats') }}" class="flex items-center px-4 py-3 text-xs font-bold text-gray-400 hover:text-primary transition-all rounded-lg"><i class="fa-solid fa-comment-dots mr-3 w-5 text-center"></i>Chat</a>
            <a href="{{ route('designer.reviews') }}" class="flex items-center px-4 py-3 text-xs font-bold text-gray-400 hover:text-primary transition-all rounded-lg"><i class="fa-solid fa-star mr-3 w-5 text-center"></i> Review & Rating</a>
            <a href="{{ route('designer.reports') }}" class="flex items-center px-4 py-3 text-xs font-bold text-gray-400 hover:text-primary transition-all rounded-lg"><i class="fa-solid fa-chart-line mr-3 w-5 text-center"></i> Laporan</a>
        </nav>
        <div class="p-4 border-t border-gray-100 space-y-1">
            <a href="{{ route('designer.settings') }}" class="flex items-center px-4 py-3 text-xs font-bold text-gray-400 hover:text-primary transition-all rounded-lg"><i class="fa-solid fa-gear mr-3 w-5 text-center"></i> Settings</a>
            <a href="{{ route('designer.support') }}" class="flex items-center px-4 py-3 text-xs font-bold text-gray-400 hover:text-primary transition-all rounded-lg"><i class="fa-solid fa-circle-question mr-3 w-5 text-center"></i> Support</a>
        </div>
    </aside>

    <main id="main-content" class="ml-64 flex flex-col min-h-screen sidebar-transition">
        <!-- HEADER -->
        <header class="h-16 bg-primary flex items-center justify-between px-8 sticky top-0 z-40 text-white">
            <div class="flex items-center">
                <i onclick="toggleSidebar()" class="fa-solid fa-bars-staggered text-xl mr-4 cursor-pointer hover:scale-110 transition-transform"></i>
                <h2 class="font-black text-[10px] uppercase tracking-widest leading-none">Add New Masterpiece</h2>
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
                <h3 class="text-2xl font-black text-gray-900 tracking-tight leading-none">Create Your Masterpiece</h3>
            </div>

            <form action="{{ route('designer.portfolio.store') }}" method="POST" enctype="multipart/form-data" class="max-w-6xl mx-auto grid grid-cols-1 lg:grid-cols-5 gap-12">
                @csrf 

                <!-- LEFT COLUMN: MULTI-IMAGE UPLOADER -->
                <div class="lg:col-span-2 space-y-6">
                    <div>
                        <h3 class="text-[9px] font-black text-gray-400 uppercase tracking-widest mb-3">Project Visuals</h3>
                        <label class="block w-full aspect-square bg-white border-2 border-dashed border-gray-200 rounded-[40px] flex flex-col items-center justify-center cursor-pointer hover:border-primary transition-colors group relative overflow-hidden shadow-sm">
                            <input type="file" name="cover_image" accept="image/*">
                            <div class="text-center group-hover:scale-105 transition-transform">
                                <div class="w-16 h-16 bg-primary/10 text-primary rounded-full flex items-center justify-center mx-auto mb-4 text-2xl">
                                    <i class="fa-solid fa-camera"></i>
                                </div>
                                <h4 class="text-sm font-black text-gray-900 uppercase">Upload Cover</h4>
                                <p class="text-[10px] text-gray-400 font-bold mt-1 italic">Main thumbnail for grid</p>
                            </div>
                        </label>
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <label class="aspect-square bg-gray-50 border-2 border-dashed border-gray-200 rounded-3xl flex flex-col items-center justify-center cursor-pointer hover:border-gray-400 transition-colors">
                            <input type="file" name="gallery[]" accept="image/*" multiple>
                            <i class="fa-solid fa-plus text-gray-300 text-xl"></i>
                            <span class="text-[8px] font-black text-gray-400 uppercase tracking-widest mt-2">Add Photo</span>
                        </label>
                        <label class="aspect-square bg-gray-50 border-2 border-dashed border-gray-200 rounded-3xl flex flex-col items-center justify-center cursor-pointer hover:border-gray-400 transition-colors">
                            <input type="file" name="gallery[]" accept="image/*" multiple>
                            <i class="fa-solid fa-plus text-gray-300 text-xl"></i>
                            <span class="text-[8px] font-black text-gray-400 uppercase tracking-widest mt-2">Add Photo</span>
                        </label>
                    </div>
                </div>

                <!-- RIGHT COLUMN: UPDATED PROJECT DETAILS (DESIGN SERVICE FOCUS) -->
                <div class="lg:col-span-3 bg-white p-10 rounded-[48px] border border-gray-100 shadow-sm space-y-8">
                    
                    <!-- NEW: SERVICE TYPE SELECTOR -->
                    <div>
                        <label class="text-[9px] font-black text-primary uppercase tracking-widest mb-3 block ml-2">What service are you offering? <span class="text-red-500">*</span></label>
                        <select id="service-type" name="service_type" required class="w-full bg-primary/5 border-2 border-primary/10 rounded-2xl py-4 px-6 text-xs font-black text-primary uppercase tracking-widest outline-none appearance-none cursor-pointer">
                            <option value="interior">Interior Consultation</option>
                            <option value="product">Product Design Concept</option>
                        </select>
                    </div>

                    <!-- Row 1: Title & Design Style -->
                    <div class="grid grid-cols-2 gap-6">
                        <div>
                            <label class="text-[9px] font-black text-gray-400 uppercase tracking-widest mb-2 block ml-2">Project Title <span class="text-red-500">*</span></label>
                            <input type="text" name="title" placeholder="e.g., Industrial Loft" required class="w-full bg-gray-50 border-none rounded-2xl py-4 px-6 text-sm font-bold text-gray-900 focus:ring-2 focus:ring-primary/20 outline-none">
                        </div>
                        <div>
                            <label class="text-[9px] font-black text-gray-400 uppercase tracking-widest mb-2 block ml-2">Design Style <span class="text-red-500">*</span></label>
                            <input type="text" name="design_style" placeholder="e.g., Industrial Chic" required class="w-full bg-gray-50 border-none rounded-2xl py-4 px-6 text-sm font-bold text-gray-900 focus:ring-2 focus:ring-primary/20 outline-none">
                        </div>
                    </div>

                    <!-- Row 2: Dynamic Technical Fields (Area or Dimensions) -->
                    <div class="grid grid-cols-2 gap-6">
                        <div>
                            <label id="tech-label-1" class="text-[9px] font-black text-gray-400 uppercase tracking-widest mb-2 block ml-2 text-primary">Area ($sqm$) <span class="text-red-500">*</span></label>
                            <div class="flex items-center bg-gray-50 rounded-2xl px-6 focus-within:ring-2 focus-within:ring-primary/20 transition-all">
                                <input type="text" id="tech-input-1" name="technical_value" placeholder="210" required class="w-full bg-transparent border-none py-4 text-xs font-bold text-gray-900 outline-none">
                                <span id="tech-suffix-1" class="text-[10px] font-black text-gray-400 uppercase tracking-widest ml-2">$sqm$</span>
                            </div>
                        </div>
                        <div>
                            <label class="text-[9px] font-black text-gray-400 uppercase tracking-widest mb-2 block ml-2 text-primary">Est. Duration <span class="text-red-500">*</span></label>
                            <input type="text" name="duration" placeholder="e.g., 5 Months" required class="w-full bg-gray-50 border-none rounded-2xl py-4 px-6 text-xs font-bold text-gray-900 focus:ring-2 focus:ring-primary/20 outline-none">
                        </div>
                    </div>

                    <!-- Row 3: Design Fee & Year -->
                    <div class="grid grid-cols-2 gap-6">
                        <div>
                            <label class="text-[9px] font-black text-gray-400 uppercase tracking-widest mb-2 block ml-2 text-primary">Design Fee (IDR) <span class="text-red-500">*</span></label>
                            <div class="flex items-center bg-gray-50 rounded-2xl px-6 focus-within:ring-2 focus-within:ring-primary/20 transition-all">
                                <span class="text-xs font-black text-gray-400 uppercase tracking-widest mr-2">Rp</span>
                                <input type="number" name="price" placeholder="4.500.000" required class="w-full bg-transparent border-none py-4 text-xs font-bold text-gray-900 outline-none">
                            </div>
                        </div>
                        <div>
                            <label class="text-[9px] font-black text-gray-400 uppercase tracking-widest mb-2 block ml-2">Completion Year <span class="text-red-500">*</span></label>
                            <input type="number" name="year" placeholder="2026" required class="w-full bg-gray-50 border-none rounded-2xl py-4 px-6 text-xs font-bold text-gray-900 focus:ring-2 focus:ring-primary/20 outline-none">
                        </div>
                    </div>

                    <!-- Row 4: Category & Sub-Category -->
                    <div class="grid grid-cols-2 gap-6">
                        <div>
                            <label class="text-[9px] font-black text-gray-400 uppercase tracking-widest mb-2 block ml-2 text-primary">Main Category <span class="text-red-500">*</span></label>
                            <select id="main-category" name="category" required class="w-full bg-gray-50 border-none rounded-2xl py-4 px-6 text-xs font-bold text-gray-900 focus:ring-2 focus:ring-primary/20 outline-none appearance-none">
                                <option value="" disabled selected>Select Category</option>
                                <option value="Furniture">Furniture</option>
                                <option value="Residential">Residential</option>
                                <option value="Commercial">Commercial</option>
                            </select>
                        </div>
                        <div>
                            <label class="text-[9px] font-black text-gray-400 uppercase tracking-widest mb-2 block ml-2 text-primary">Service Detail <span class="text-red-500">*</span></label>
                            <select id="sub-category" name="sub_category" required class="w-full bg-gray-50 border-none rounded-2xl py-4 px-6 text-xs font-bold text-gray-900 focus:ring-2 focus:ring-primary/20 outline-none appearance-none">
                                <option value="" disabled selected>Select Sub</option>
                            </select>
                        </div>
                    </div>

                    <!-- Row 5: Material & Lead Designer -->
                    <div class="grid grid-cols-2 gap-6">
                        <div>
                            <label class="text-[9px] font-black text-gray-400 uppercase tracking-widest mb-2 block ml-2 text-primary">Key Materials</label>
                            <input type="text" name="material" placeholder="e.g., Brick, Iron, Oak" class="w-full bg-gray-50 border-none rounded-2xl py-4 px-6 text-xs font-bold text-gray-900 focus:ring-2 focus:ring-primary/20 outline-none">
                        </div>
                        <div>
                            <label class="text-[9px] font-black text-gray-400 uppercase tracking-widest mb-2 block ml-2 text-primary">Lead Designer <span class="text-red-500">*</span></label>
                            <input type="text" name="lead_designer" placeholder="e.g., Elena Vance" required class="w-full bg-gray-50 border-none rounded-2xl py-4 px-6 text-xs font-bold text-gray-900 focus:ring-2 focus:ring-primary/20 outline-none">
                        </div>
                    </div>

                    <!-- Row 6: Link & Concept Note -->
                    <div class="space-y-6">
                        <div>
                            <label class="text-[9px] font-black text-gray-400 uppercase tracking-widest mb-2 block ml-2 flex items-center"><i class="fa-solid fa-link mr-2"></i> External Link (Behance/Pinterest)</label>
                            <input type="url" name="external_link" placeholder="https://behance.net/..." class="w-full bg-gray-50 border-none rounded-2xl py-4 px-6 text-[10px] font-bold text-gray-900 focus:ring-2 focus:ring-primary/20 outline-none">
                        </div>
                        <div>
                            <label class="text-[9px] font-black text-gray-400 uppercase tracking-widest mb-2 block ml-2">Design Philosophy / Concept Note <span class="text-red-500">*</span></label>
                            <textarea name="description" rows="3" placeholder="Describe the story behind this design..." required class="w-full bg-gray-50 border-none rounded-3xl py-5 px-6 text-xs font-bold text-gray-900 focus:ring-2 focus:ring-primary/20 outline-none resize-none custom-scroll"></textarea>
                        </div>
                    </div>

                    <!-- Form Actions -->
                    <div class="pt-6 border-t border-gray-50 flex items-center justify-between">
                        <label class="flex items-center cursor-pointer group">
                            <div class="relative">
                                <input type="checkbox" name="is_live" class="sr-only" checked>
                                <div class="block-toggle bg-gray-200 w-12 h-7 rounded-full group-hover:bg-gray-300 transition-colors"></div>
                                <div class="dot absolute left-1 top-1 bg-white w-5 h-5 rounded-full transition-transform translate-x-5 shadow-sm"></div>
                            </div>
                            <div class="ml-4">
                                <p class="text-[10px] font-black text-gray-900 uppercase tracking-widest">Submit for Review</p>
                                <p class="text-[8px] text-gray-400 font-bold italic">Admin will validate this masterpiece.</p>
                            </div>
                        </label>
                        <div class="space-x-4 flex">
                            <a href="{{ route('designer.portfolio.index') }}" class="px-8 py-4 border border-gray-200 rounded-2xl text-[10px] font-black uppercase tracking-widest text-gray-400 hover:bg-gray-50 transition-all text-center">Cancel</a>
                            <button type="submit" class="bg-primary text-white px-10 py-4 rounded-2xl text-[10px] font-black uppercase tracking-widest shadow-xl shadow-primary/30 hover:scale-105 transition-all">Submit Masterpiece</button>
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
            'Furniture': ['Chair', 'Table', 'Sofa', 'Cabinet', 'Bed'],
            'Residential': ['Living Room', 'Bedroom', 'Kitchen', 'Studio'],
            'Commercial': ['Cafe', 'Office', 'Retail Store', 'Lobby']
        };

        const serviceType = document.getElementById('service-type');
        const techLabel = document.getElementById('tech-label-1');
        const techInput = document.getElementById('tech-input-1');
        const techSuffix = document.getElementById('tech-suffix-1');

        const mainCat = document.getElementById('main-category');
        const subCat = document.getElementById('sub-category');

        // Logic for Dynamic Fields (Interior vs Product)
        serviceType.addEventListener('change', function() {
            if(this.value === 'interior') {
                techLabel.innerText = 'Area ($sqm$) *';
                techInput.placeholder = '210';
                techSuffix.innerText = '$sqm$';
                techSuffix.classList.remove('hidden');
            } else {
                techLabel.innerText = 'Dimensions (LxWxH) *';
                techInput.placeholder = '85x90x75cm';
                techSuffix.classList.add('hidden');
            }
        });

        // Logic for Dynamic Sub-Categories
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

        // Toggle Switch Animation Logic
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

        // Initial check for toggle
        if(toggleInput.checked) {
            toggleBg.classList.replace('bg-gray-200', 'bg-green-500');
        }
    </script>
</body>
</html>