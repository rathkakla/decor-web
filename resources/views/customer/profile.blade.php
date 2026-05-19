@php 
    $site_name = "DECOR"; 
    $avatar_url = Auth::user()->avatar_url;
@endphp
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Profile — {{ $site_name }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
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
        [x-cloak] { display: none !important; }
    </style>
</head>
<body class="text-gray-800 flex flex-col min-h-screen" 
      x-data="{ 
          openAddress: false, 
          openInfo: false,
          editMode: false,
          addressForm: { id: null, label: '', recipient_name: '', phone_number: '', city: '', full_address: '', is_main: false },
          openAddAddress() {
              this.addressForm = { id: null, label: '', recipient_name: '', phone_number: '', city: '', full_address: '', is_main: false };
              this.editMode = false;
              this.openAddress = true;
          },
          openEditAddress(addr) {
              this.addressForm = { 
                  id: addr.id, 
                  label: addr.label, 
                  recipient_name: addr.recipient_name || '',
                  phone_number: addr.phone_number || '',
                  city: addr.city || '',
                  full_address: addr.full_address, 
                  is_main: addr.is_main == 1 
              };
              this.editMode = true;
              this.openAddress = true;
          }
      }">

    <header class="bg-white border-b border-gray-100 sticky top-0 z-50">
        <div class="content-container flex justify-between items-center py-4 px-6 mx-auto max-w-[1200px]">
            <div class="flex items-center space-x-8 flex-1">
                <a href="{{ route('homepage') }}" class="text-2xl font-black tracking-tighter uppercase text-primary hover:opacity-80 transition-all">
                    {{ $site_name }}
                </a>
                <form action="{{ route('customer.catalog') }}" method="GET" class="hidden lg:flex items-center bg-gray-50 border border-gray-100 rounded-md px-4 py-2 w-full max-w-[180px] group focus-within:bg-white focus-within:border-primary/30 transition-all">
                    <i class="fa-solid fa-magnifying-glass text-gray-400 text-[10px] mr-2"></i>
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Search..." class="bg-transparent border-none outline-none text-[10px] w-full placeholder:text-gray-400">
                </form>
            </div>

            <nav class="hidden md:flex items-center space-x-10 text-[13px] font-medium text-gray-500 tracking-wide">
                <a href="{{ route('customer.catalog') }}" class="hover:text-primary transition-all">Collections</a>
                <a href="{{ route('customer.designers') }}" class="hover:text-primary transition-all">Designers</a>
                <a href="{{ route('customer.design-lab') }}" class="hover:text-primary transition-all">AI Studio</a>
            </nav>

            <div class="flex items-center space-x-6 flex-1 justify-end">
                @auth
                    <div class="flex items-center gap-4 border-r pr-6 border-gray-100">
                        <div class="text-right hidden sm:block">
                            <p class="text-[9px] uppercase tracking-widest text-gray-400 font-bold leading-none mb-1">Welcome back</p>
                            <p class="text-xs font-bold text-primary capitalize">{{ Auth::user()->full_name }}</p>
                        </div>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="text-gray-400 hover:text-red-500 transition-colors" title="Logout">
                                <i class="fa-solid fa-power-off text-sm"></i>
                            </button>
                        </form>
                    </div>
                    <a href="{{ route('customer.cart') }}" class="text-primary hover:scale-110 transition-transform">
                        <i class="fa-solid fa-bag-shopping text-lg"></i>
                    </a>
                    <div class="w-9 h-9 rounded-md overflow-hidden border border-gray-200 cursor-pointer hover:border-primary transition-all">
                        <a href="{{ route('customer.profile') }}" class="block w-full h-full">
                            <img src="{{ $avatar_url }}" class="w-full h-full bg-slate-100 object-cover">
                        </a>
                    </div>
                @endauth
            </div>
        </div>
    </header>

    <main class="flex-grow flex content-container w-full bg-white">
        <aside class="w-72 border-r border-gray-50 p-10 bg-gray-50/20 shrink-0">
            <div class="text-center mb-10 group">
                <div class="relative inline-block">
                    <img src="{{ $avatar_url }}" 
                         class="w-20 h-20 rounded-2xl mx-auto mb-4 bg-white shadow-sm border border-gray-100 object-cover">
                    
                    <button onclick="document.getElementById('avatar-input').click()" 
                            class="absolute -bottom-1 -right-1 w-7 h-7 bg-primary text-white rounded-lg shadow-lg flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity">
                        <i class="fa-solid fa-pencil text-[10px]"></i>
                    </button>
                </div>
                <h3 class="font-bold text-lg">{{ $user->full_name }}</h3>
                <p class="text-[9px] text-gray-400 uppercase tracking-widest mt-1">Member since {{ $user->created_at->format('Y') }}</p>

                {{-- Form Tersembunyi untuk Upload --}}
                <form id="avatar-form" action="{{ route('customer.profile.update-avatar') }}" method="POST" enctype="multipart/form-data" class="hidden">
                    @csrf
                    <input type="file" name="profile_image" id="avatar-input" accept="image/*" onchange="document.getElementById('avatar-form').submit()">
                </form>
            </div>

            <nav class="space-y-1">
                <a href="{{ route('customer.profile') }}" class="flex items-center space-x-4 px-4 py-3 bg-white text-primary font-bold rounded-xl shadow-sm border border-gray-100">
                    <i class="fa-regular fa-user text-xs"></i> <span class="text-[11px] uppercase tracking-widest">Profile</span>
                </a>
                <a href="{{ route('customer.orders') }}" class="flex items-center space-x-4 px-4 py-3 text-gray-400 hover:text-primary transition-colors">
                    <i class="fa-solid fa-box-archive text-xs"></i> <span class="text-[11px] uppercase tracking-widest">Orders</span>
                </a>
                <a href="{{ route('customer.my-consultations') }}" class="flex items-center space-x-4 px-4 py-3 {{ Route::is('customer.my-consultations') ? 'bg-white text-primary font-bold rounded-xl shadow-sm border border-gray-100' : 'text-gray-400 hover:text-primary transition-colors' }}">
                    <i class="fa-solid fa-wand-magic-sparkles text-xs"></i> <span class="text-[11px] uppercase tracking-widest">Consultations</span>
                </a>
                <a href="{{ route('customer.return-request') }}" class="flex items-center space-x-4 px-4 py-3 text-gray-400 hover:text-primary transition-colors">
                    <i class="fa-solid fa-rotate-left text-xs"></i> <span class="text-[11px] uppercase tracking-widest">Returns</span>
                </a>
                <a href="{{ route('customer.product-favorite') }}" class="flex items-center space-x-4 px-4 py-3 bg-white text-gray-400 font-medium rounded-xl hover:text-primary transition-colors">
                    <i class="fa-regular fa-heart text-xs"></i>
                    <span class="text-[11px] uppercase tracking-widest">Product Favorite</span>
                </a>
                <div class="pt-6 mt-6 border-t border-gray-100">
                    <p class="px-4 text-[9px] font-black text-gray-300 uppercase tracking-widest mb-2">Chat History</p>
                    <a href="{{ route('customer.chat-seller.with') }}" class="flex items-center space-x-4 px-4 py-3 rounded-xl {{ (request()->is('customer/chat-seller*')) ? 'bg-white text-primary font-bold shadow-sm border border-gray-100' : 'text-gray-400 hover:text-primary' }}">
                        <i class="fa-solid fa-shop text-xs"></i> 
                        <span class="text-[11px] uppercase tracking-widest">Seller</span>
                    </a>
                </div>
            </nav>
        </aside>

        <div class="flex-grow p-12 bg-[#fafafa]/30">
            @if(session('success'))
                <div class="mb-6 bg-green-50 text-green-600 p-4 rounded-xl text-sm font-bold border border-green-100 flex items-center">
                    <i class="fa-solid fa-circle-check mr-3 text-lg"></i> {{ session('success') }}
                </div>
            @endif

            <section class="mb-16">
                <div class="flex flex-col md:flex-row justify-between items-start gap-10">
                    <div class="max-w-xs">
                        <h1 class="text-4xl font-bold tracking-tighter mb-4 leading-none text-gray-900">Personal<br>Details</h1>
                        <p class="text-[11px] text-gray-400 leading-relaxed">Refine your digital curatorial presence and contact preferences.</p>
                    </div>
                    
                    <div class="flex-grow grid grid-cols-2 gap-x-12 gap-y-8 bg-white rounded-2xl p-10 border border-gray-100 shadow-sm">
                        <div class="space-y-1">
                            <p class="text-[9px] font-black text-gray-300 uppercase tracking-widest">Full Name</p>
                            <p class="text-sm font-bold">{{ $user->full_name }}</p>
                        </div>
                        <div class="space-y-1">
                            <p class="text-[9px] font-black text-gray-300 uppercase tracking-widest">Email Address</p>
                            <p class="text-sm font-bold">{{ $user->email }}</p>
                        </div>
                        <div class="space-y-1">
                            <p class="text-[9px] font-black text-gray-300 uppercase tracking-widest">Phone Number</p>
                            <p class="text-sm font-bold">{{ $customer->phone ?? 'Not provided yet' }}</p>
                        </div>
                        <div class="flex items-end">
                            <button @click="openInfo = true" class="text-[10px] font-bold text-primary border-b border-primary uppercase tracking-widest hover:opacity-80 transition-opacity">Edit Info</button>
                        </div>
                    </div>
                </div>
            </section>

            <section class="mb-16">
                <div class="flex justify-between items-center mb-8">
                    <h2 class="text-xl font-bold tracking-tight">Delivery Destinations</h2>
                    <button @click="openAddAddress()" class="text-[10px] font-bold text-primary uppercase tracking-widest hover:opacity-80 transition-opacity">+ Add New</button>
                </div>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    @forelse($customer->addresses as $address)
                    <div class="p-8 border border-gray-100 rounded-2xl relative bg-white shadow-sm group">
                        <div class="absolute top-8 right-8 flex space-x-3 opacity-0 group-hover:opacity-100 transition-opacity text-gray-300">
                            <i @click="openEditAddress({{ json_encode($address) }})" class="fa-solid fa-pen-to-square hover:text-primary cursor-pointer text-xs transition-colors"></i>
                            <form action="{{ route('customer.delete-address', $address->id) }}" method="POST" onsubmit="return confirm('Hapus alamat ini?')">
                                @csrf @method('DELETE')
                                <button type="submit"><i class="fa-solid fa-trash-can hover:text-red-400 cursor-pointer text-xs transition-colors"></i></button>
                            </form>
                        </div>
                        <div class="flex justify-between items-start mb-4">
                            @if($address->is_main)
                            <span class="text-[8px] font-bold px-3 py-1 bg-primary/10 text-primary rounded-full uppercase tracking-widest">Default</span>
                            @else
                            <span class="text-[8px] font-bold px-3 py-1 bg-gray-50 text-gray-400 border border-gray-100 rounded-full uppercase tracking-widest">{{ $address->label }}</span>
                            @endif
                        </div>
                        <p class="font-bold text-sm mb-1">{{ $address->recipient_name ?? $user->full_name }}</p>
                        <p class="text-[11px] text-gray-500 mb-2 font-medium">{{ $address->phone_number ?? '-' }}</p>
                        <p class="text-[11px] text-gray-400 leading-relaxed">{{ $address->full_address }}</p>
                        <p class="text-[11px] text-gray-400 font-bold mt-2 italic">{{ $address->city ?? 'Indonesia' }}</p>
                    </div>
                    @empty
                    <div class="md:col-span-2 py-20 text-center bg-white rounded-2xl border border-dashed border-gray-200">
                        <p class="text-xs text-gray-400">Belum ada alamat pengiriman. Silakan tambah alamat baru.</p>
                    </div>
                    @endforelse
                </div>
            </section>
        </div>

        <!-- MODAL: ADD / EDIT ADDRESS -->
        <div x-show="openAddress" x-cloak x-transition.opacity class="fixed inset-0 z-[100] flex items-center justify-center p-6 bg-black/40 backdrop-blur-md">
            <div @click.away="openAddress = false" class="bg-white w-full max-w-lg rounded-[3rem] p-12 shadow-2xl overflow-y-auto max-h-[90vh]">
                <div class="mb-10">
                    <h3 class="text-3xl font-bold tracking-tighter" x-text="editMode ? 'Edit Destination' : 'New Destination'"></h3>
                    <p class="text-[11px] text-gray-400 mt-2">Manage your shipping destinations for a faster checkout experience.</p>
                </div>
                <form :action="editMode ? '{{ url('customer/address') }}/' + addressForm.id : '{{ route('customer.store-address') }}'" method="POST" class="space-y-6">
                    @csrf
                    <template x-if="editMode">
                        <input type="hidden" name="_method" value="PATCH">
                    </template>

                    <div class="grid grid-cols-2 gap-4">
                        <div class="space-y-1">
                            <label class="text-[9px] font-black text-gray-300 uppercase tracking-widest">Label (Home, Office)</label>
                            <input type="text" name="label" x-model="addressForm.label" required class="w-full bg-gray-50 border border-gray-100 rounded-2xl px-6 py-4 text-sm font-medium focus:border-primary/30 outline-none transition-all">
                        </div>
                        <div class="space-y-1">
                            <label class="text-[9px] font-black text-gray-300 uppercase tracking-widest">City / Region</label>
                            <input type="text" name="city" x-model="addressForm.city" required class="w-full bg-gray-50 border border-gray-100 rounded-2xl px-6 py-4 text-sm font-medium focus:border-primary/30 outline-none transition-all">
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div class="space-y-1">
                            <label class="text-[9px] font-black text-gray-300 uppercase tracking-widest">Recipient Name</label>
                            <input type="text" name="recipient_name" x-model="addressForm.recipient_name" required class="w-full bg-gray-50 border border-gray-100 rounded-2xl px-6 py-4 text-sm font-medium focus:border-primary/30 outline-none transition-all">
                        </div>
                        <div class="space-y-1">
                            <label class="text-[9px] font-black text-gray-300 uppercase tracking-widest">Phone Number</label>
                            <input type="text" name="phone_number" x-model="addressForm.phone_number" required class="w-full bg-gray-50 border border-gray-100 rounded-2xl px-6 py-4 text-sm font-medium focus:border-primary/30 outline-none transition-all">
                        </div>
                    </div>

                    <div class="space-y-1">
                        <label class="text-[9px] font-black text-gray-300 uppercase tracking-widest">Full Address Details</label>
                        <textarea name="full_address" x-model="addressForm.full_address" rows="3" required class="w-full bg-gray-50 border border-gray-100 rounded-2xl px-6 py-4 text-sm font-medium focus:border-primary/30 outline-none transition-all"></textarea>
                    </div>

                    <div class="flex items-center gap-3">
                        <input type="checkbox" name="is_main" id="is_main" x-model="addressForm.is_main" class="w-4 h-4 accent-primary">
                        <label for="is_main" class="text-[10px] font-bold text-gray-500 uppercase tracking-widest">Set as Main Address</label>
                    </div>

                    <div class="flex gap-4 pt-4">
                        <button type="button" @click="openAddress = false" class="flex-1 py-5 rounded-xl text-[10px] font-black uppercase tracking-widest border border-gray-100 hover:bg-gray-50 transition-colors">Cancel</button>
                        <button type="submit" class="flex-1 py-5 rounded-xl text-[10px] font-black uppercase tracking-widest bg-primary text-white shadow-lg shadow-primary/20 hover:bg-opacity-90 transition-all" x-text="editMode ? 'Save Changes' : 'Add Address'"></button>
                    </div>
                </form>
            </div>
        </div>

        <!-- MODAL: EDIT PERSONAL INFO -->
        <div x-show="openInfo" x-cloak x-transition.opacity class="fixed inset-0 z-[100] flex items-center justify-center p-6 bg-black/40 backdrop-blur-md">
            <div @click.away="openInfo = false" class="bg-white w-full max-w-lg rounded-[3rem] p-12 shadow-2xl">
                <div class="mb-10">
                    <h3 class="text-3xl font-bold tracking-tighter">Personal Info</h3>
                    <p class="text-[11px] text-gray-400 mt-2">Update your contact information and identity details.</p>
                </div>
                <form action="{{ route('customer.profile.update-info') }}" method="POST" class="space-y-8">
                    @csrf @method('PATCH')
                    <div class="space-y-1">
                        <label class="text-[9px] font-black text-gray-300 uppercase tracking-widest">Full Name</label>
                        <input type="text" name="full_name" value="{{ $user->full_name }}" class="w-full bg-gray-50 border border-gray-100 rounded-2xl px-6 py-4 text-sm font-medium focus:border-primary/30 outline-none transition-all">
                    </div>
                    <div class="space-y-1">
                        <label class="text-[9px] font-black text-gray-300 uppercase tracking-widest">Phone Number</label>
                        <input type="text" name="phone" value="{{ $customer->phone }}" placeholder="e.g. +62 812..." class="w-full bg-gray-50 border border-gray-100 rounded-2xl px-6 py-4 text-sm font-medium focus:border-primary/30 outline-none transition-all">
                    </div>
                    <div class="flex gap-4 pt-4">
                        <button type="button" @click="openInfo = false" class="flex-1 py-5 rounded-xl text-[10px] font-black uppercase tracking-widest border border-gray-100 hover:bg-gray-50 transition-colors">Cancel</button>
                        <button type="submit" class="flex-1 py-5 rounded-xl text-[10px] font-black uppercase tracking-widest bg-primary text-white shadow-lg shadow-primary/20 hover:bg-opacity-90 transition-all">Update Details</button>
                    </div>
                </form>
            </div>
        </div>
    </main>

    <footer class="bg-primary text-white py-12 px-6">
        <div class="max-w-6xl mx-auto"> 
            <div class="flex flex-col md:flex-row justify-between items-start border-b border-white/20 pb-10 gap-10">
                <div class="space-y-4">
                    <div class="flex items-center space-x-2 text-white">
                        <div class="w-8 h-8 bg-white/20 rounded flex items-center justify-center">
                            <i class="fa-solid fa-couch text-sm"></i>
                        </div>
                        <span class="text-xl font-bold tracking-widest uppercase">{{ $site_name }}</span>
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
                         <a href="{{ route('customer.help-center') ?? '#' }}" class="hover:text-white/70 transition-colors">Help Center</a>
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
                <p>©️ 2026 {{ $site_name }} MARKETPLACE. ALL RIGHTS RESERVED.</p>
            </div>
        </div>
    </footer>
</body>
</html>