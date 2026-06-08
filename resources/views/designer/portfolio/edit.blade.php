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

    <!-- SIDEBAR -->
    @include('designer.partials.sidebar')

    <main id="main-content" class="ml-64 flex flex-col min-h-screen sidebar-transition">
        <!-- HEADER -->
        <header class="h-16 bg-primary flex items-center justify-between px-8 sticky top-0 z-40 text-white">
            <div class="flex items-center">
                <i onclick="toggleSidebar()" class="fa-solid fa-bars-staggered text-xl mr-4 cursor-pointer hover:scale-110 transition-transform"></i>
                <h2 class="font-black text-[10px] uppercase tracking-widest leading-none">Edit Masterpiece</h2>
            </div>
            <div class="flex items-center space-x-6 text-white">
                <div class="flex items-center space-x-3 bg-white/10 px-3 py-1.5 rounded-xl border border-white/20">
                    <span class="text-[10px] font-black uppercase tracking-widest">{{ Auth::user()->full_name }}</span>
                    <div class="w-8 h-8 rounded-lg bg-white text-primary flex items-center justify-center font-bold">
                        {{ strtoupper(substr(Auth::user()->full_name, 0, 2)) }}
                    </div>
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
                <h3 class="text-2xl font-black text-gray-900 tracking-tight leading-none">Edit Your Masterpiece</h3>
            </div>

            <form action="{{ route('designer.portfolio.update', $portfolio->id) }}" method="POST" enctype="multipart/form-data" class="max-w-6xl mx-auto grid grid-cols-1 lg:grid-cols-5 gap-12">
                @csrf 
                @method('PUT')

                <!-- LEFT COLUMN: IMAGE UPLOADER & 360 TOGGLE -->
                <div class="lg:col-span-2 space-y-6">
                    <div>
                        <h3 class="text-[9px] font-black text-gray-400 uppercase tracking-widest mb-3">Project Visual</h3>
                        <label id="drop-area" class="block w-full aspect-square bg-white border-2 @error('image') border-red-500 @else border-dashed border-gray-200 @enderror rounded-[40px] flex flex-col items-center justify-center cursor-pointer hover:border-primary transition-colors group relative overflow-hidden shadow-sm">
                            <input type="file" name="image" accept="image/*" onchange="previewImage(this)">
                            @if($portfolio->image_url)
                                <img id="image-preview" src="{{ asset('storage/' . $portfolio->image_url) }}" class="absolute inset-0 w-full h-full object-cover">
                                <div id="upload-placeholder" class="hidden text-center group-hover:scale-105 transition-transform">
                                    <div class="w-16 h-16 bg-primary/10 text-primary rounded-full flex items-center justify-center mx-auto mb-4 text-2xl">
                                        <i class="fa-solid fa-camera"></i>
                                    </div>
                                    <h4 class="text-sm font-black text-gray-900 uppercase">Change Image</h4>
                                    <p class="text-[10px] text-gray-400 font-bold mt-1 italic">JPG, PNG up to 100MB</p>
                                </div>
                            @else
                                <img id="image-preview" class="hidden absolute inset-0 w-full h-full object-cover">
                                <div id="upload-placeholder" class="text-center group-hover:scale-105 transition-transform">
                                    <div class="w-16 h-16 bg-primary/10 text-primary rounded-full flex items-center justify-center mx-auto mb-4 text-2xl">
                                        <i class="fa-solid fa-camera"></i>
                                    </div>
                                    <h4 class="text-sm font-black text-gray-900 uppercase">Upload Image</h4>
                                    <p class="text-[10px] text-gray-400 font-bold mt-1 italic">JPG, PNG up to 100MB</p>
                                </div>
                            @endif
                        </label>
                        @error('image') <p class="text-[10px] text-red-500 font-bold mt-2 ml-2"><i class="fa-solid fa-circle-exclamation mr-1"></i> Format atau ukuran gambar tidak valid.</p> @enderror
                    </div>

                    <!-- 360 DEGREE TOGGLE -->
                    <div class="bg-white p-6 rounded-[32px] border border-gray-100 shadow-sm">
                        <label class="flex items-center justify-between cursor-pointer group">
                            <div class="flex-1 pr-4">
                                <h4 class="text-xs font-black text-gray-900 uppercase tracking-wider flex items-center">
                                    <i class="fa-solid fa-vr-cardboard text-primary mr-2 text-sm"></i>
                                    360° Panorama
                                </h4>
                                <p class="text-[9px] text-gray-400 font-bold italic mt-1 leading-normal">
                                    Aktifkan jika gambar yang diupload adalah foto panorama 360° (equirectangular) agar customer bisa berputar & melihat ruangan secara interaktif.
                                </p>
                            </div>
                            <div class="relative flex-shrink-0">
                                <input type="checkbox" name="is_360" id="is_360" value="1" class="sr-only peer" {{ $portfolio->is_360 ? 'checked' : '' }}>
                                <div class="w-12 h-7 bg-gray-200 rounded-full transition-colors duration-300 peer-checked:bg-primary"></div>
                                <div class="absolute left-1 top-1 bg-white w-5 h-5 rounded-full transition-transform duration-300 shadow-sm peer-checked:translate-x-5"></div>
                            </div>
                        </label>
                    </div>
                </div>

                <!-- RIGHT COLUMN: PROJECT DETAILS -->
                <div class="lg:col-span-3 bg-white p-10 rounded-[48px] border border-gray-100 shadow-sm space-y-8">
                    
                    <div>
                        <label class="text-[9px] font-black text-gray-400 uppercase tracking-widest mb-2 block ml-2">Project Title <span class="text-red-500">*</span></label>
                        <input type="text" name="title" value="{{ old('title', $portfolio->title) }}" placeholder="e.g., Modern Scandinavian Living Room" class="w-full bg-gray-50 @error('title') border border-red-500 @else border-none @enderror rounded-2xl py-4 px-6 text-sm font-bold text-gray-900 focus:ring-2 focus:ring-primary/20 outline-none">
                        @error('title') <p class="text-[10px] text-red-500 font-bold mt-2 ml-2"><i class="fa-solid fa-circle-exclamation mr-1"></i> Judul portfolio tidak boleh kosong.</p> @enderror
                    </div>

                    <div class="grid grid-cols-2 gap-6">
                        <div>
                            <label class="text-[9px] font-black text-gray-400 uppercase tracking-widest mb-2 block ml-2">Category <span class="text-red-500">*</span></label>
                            <select name="category" required class="w-full bg-gray-50 border-none rounded-2xl py-4 px-6 text-xs font-bold text-gray-900 focus:ring-2 focus:ring-primary/20 outline-none appearance-none">
                                <option value="Residential" {{ old('category', $portfolio->category) == 'Residential' ? 'selected' : '' }}>Residential</option>
                                <option value="Commercial" {{ old('category', $portfolio->category) == 'Commercial' ? 'selected' : '' }}>Commercial</option>
                                <option value="Hospitality" {{ old('category', $portfolio->category) == 'Hospitality' ? 'selected' : '' }}>Hospitality</option>
                            </select>
                        </div>
                        <div>
                            <label class="text-[9px] font-black text-gray-400 uppercase tracking-widest mb-2 block ml-2">Budget Est. / Harga <span class="text-red-500">*</span></label>
                            <input type="text" name="budget" value="{{ old('budget', $portfolio->budget) }}" placeholder="e.g., 50jt - 100jt" class="w-full bg-gray-50 @error('budget') border border-red-500 @else border-none @enderror rounded-2xl py-4 px-6 text-xs font-bold text-gray-900 focus:ring-2 focus:ring-primary/20 outline-none">
                            @error('budget') <p class="text-[10px] text-red-500 font-bold mt-2 ml-2"><i class="fa-solid fa-circle-exclamation mr-1"></i> {{ $message }}</p> @enderror
                        </div>
                    </div>

                    <div class="grid grid-cols-1">
                        <div>
                            <label class="text-[9px] font-black text-gray-400 uppercase tracking-widest mb-2 block ml-2 text-primary">Est. Duration <span class="text-red-500">*</span></label>
                            <select name="duration" required class="w-full bg-gray-50 border-none rounded-2xl py-4 px-6 text-xs font-bold text-gray-900 focus:ring-2 focus:ring-primary/20 outline-none appearance-none">
                                <option value="" disabled {{ !old('duration', $portfolio->duration) ? 'selected' : '' }}>Select pengerjaan...</option>
                                <option value="1-3 Months" {{ old('duration', $portfolio->duration) == '1-3 Months' ? 'selected' : '' }}>1-3 Months</option>
                                <option value="1-6 Months" {{ old('duration', $portfolio->duration) == '1-6 Months' ? 'selected' : '' }}>1-6 Months</option>
                                <option value="1-9 Months" {{ old('duration', $portfolio->duration) == '1-9 Months' ? 'selected' : '' }}>1-9 Months</option>
                                <option value="1-12 Months" {{ old('duration', $portfolio->duration) == '1-12 Months' ? 'selected' : '' }}>1-12 Months</option>
                            </select>
                        </div>
                    </div>

                    <div>
                        <label class="text-[9px] font-black text-gray-400 uppercase tracking-widest mb-2 block ml-2">Project Description <span class="text-red-500">*</span></label>
                        <textarea name="description" rows="4" placeholder="Describe the concept and story behind this masterpiece..." class="w-full bg-gray-50 @error('description') border border-red-500 @else border-none @enderror rounded-3xl py-5 px-6 text-xs font-bold text-gray-900 focus:ring-2 focus:ring-primary/20 outline-none resize-none custom-scroll">{{ old('description', $portfolio->description) }}</textarea>
                        @error('description') <p class="text-[10px] text-red-500 font-bold mt-2 ml-2"><i class="fa-solid fa-circle-exclamation mr-1"></i> Deskripsi portfolio tidak boleh kosong.</p> @enderror
                    </div>

                    <div class="pt-6 border-t border-gray-50 flex items-center justify-end space-x-4">
                        <a href="{{ route('designer.portfolio.index') }}" class="px-8 py-4 border border-gray-200 rounded-2xl text-[10px] font-black uppercase tracking-widest text-gray-400 hover:bg-gray-50 transition-all text-center">Cancel</a>
                        <button type="submit" class="bg-primary text-white px-10 py-4 rounded-2xl text-[10px] font-black uppercase tracking-widest shadow-xl shadow-primary/30 hover:scale-105 transition-all">Update Masterpiece</button>
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

        function previewImage(input) {
            const preview = document.getElementById('image-preview');
            const placeholder = document.getElementById('upload-placeholder');
            
            if (input.files && input.files[0]) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    preview.src = e.target.result;
                    preview.classList.remove('hidden');
                    if (placeholder) placeholder.classList.add('hidden');
                }
                reader.readAsDataURL(input.files[0]);
            }
        }
    </script>
</body>
</html>