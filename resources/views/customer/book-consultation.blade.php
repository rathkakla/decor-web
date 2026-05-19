@php $site_name = "DECOR"; @endphp
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Book Consultation — <?= $site_name ?></title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: { primary: '#B5733A', secondary: '#E3DCD6' }
                }
            }
        }
    </script>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;600;700;800&display=swap');
        body { font-family: 'Plus Jakarta Sans', sans-serif; background-color: #ffffff; }
        .content-container { max-width: 1200px; margin: 0 auto; }
    </style>
</head>
<body class="text-gray-800 flex flex-col min-h-screen">

    <header class="bg-white border-b border-gray-100 sticky top-0 z-50">
        <div class="content-container flex justify-between items-center py-4 px-6">
            <a href="{{ route('homepage') }}" class="text-2xl font-black tracking-tighter uppercase text-primary"><?= $site_name ?></a>
        </div>
    </header>

    <main class="flex-grow flex items-center justify-center py-20 px-6">
        <div class="max-w-2xl w-full bg-white border border-gray-100 rounded-[3rem] p-16 shadow-[0_30px_100px_rgba(0,0,0,0.05)] text-center">
            <div class="mb-12">
                <span class="text-[10px] font-black uppercase tracking-[0.4em] text-primary mb-4 block italic">Phase 1: Request Approval</span>
                <h1 class="text-5xl font-black tracking-tighter mb-4 italic">Start Your Project</h1>
                <p class="text-gray-400 text-sm italic">Kirim permintaan konsultasi ke desainer. Setelah disetujui, Anda dapat melanjutkan ke pembayaran consultation fee.</p>
            </div>

            <form action="{{ route('customer.designers.book.store', $designer->id) }}" method="POST" enctype="multipart/form-data" class="space-y-10 text-left">
                @csrf
                <div class="space-y-4">
                    <label class="text-[10px] font-black uppercase tracking-widest text-gray-400 ml-4 italic">Nama Proyek</label>
                    <input type="text" name="title" required placeholder="Contoh: Renovasi Ruang Tamu Minimalis" 
                           class="w-full bg-gray-50 border-none rounded-3xl px-8 py-6 text-sm font-bold outline-none focus:ring-2 focus:ring-primary/20 transition-all">
                </div>

                <div class="space-y-4">
                    <label class="text-[10px] font-black uppercase tracking-widest text-gray-400 ml-4 italic">Jenis Konsultasi</label>
                    <select id="consultation-type" name="consultation_type" required class="w-full bg-gray-50 border-none rounded-3xl px-8 py-6 text-sm font-bold outline-none focus:ring-2 focus:ring-primary/20 transition-all appearance-none">
                        <option value="" disabled selected>Pilih Jenis Konsultasi</option>
                        <option value="chat_consultation">Chat Consultation (Rp 50.000)</option>
                        <option value="request_proposal">Request Proposal (Rp 250.000)</option>
                    </select>
                </div>

                <div class="space-y-4" id="brief-container" style="display: none;">
                    <label class="text-[10px] font-black uppercase tracking-widest text-gray-400 ml-4 italic">Project Brief</label>
                    <textarea name="description" rows="3" placeholder="Deskripsikan detail ruangan, preferensi warna, dan gaya desain yang Anda inginkan." 
                              class="w-full bg-gray-50 border-none rounded-3xl px-8 py-6 text-sm font-bold outline-none focus:ring-2 focus:ring-primary/20 transition-all"></textarea>
                </div>

                <div class="space-y-4">
                    <label class="text-[10px] font-black uppercase tracking-widest text-gray-400 ml-4 italic">Estimasi Budget</label>
                    <select name="budget_range" required class="w-full bg-gray-50 border-none rounded-3xl px-8 py-6 text-sm font-bold outline-none focus:ring-2 focus:ring-primary/20 transition-all appearance-none">
                        <option value="Rp 5jt - 10jt">Rp 5jt - 10jt</option>
                        <option value="Rp 10jt - 50jt">Rp 10jt - 50jt</option>
                        <option value="Rp 50jt - 100jt">Rp 50jt - 100jt</option>
                        <option value="> Rp 100jt">> Rp 100jt</option>
                    </select>
                </div>

                <button type="submit" class="w-full bg-primary text-white py-6 rounded-3xl text-[11px] font-black uppercase tracking-[0.4em] shadow-2xl shadow-primary/30 hover:scale-[1.02] active:scale-[0.98] transition-all">
                    Kirim Request Konsultasi
                </button>

                <p class="text-center text-[10px] text-gray-400 font-bold uppercase tracking-widest italic mt-8">
                    <i class="fa-solid fa-shield-halved mr-2"></i> Aman & Terpercaya di DECOR
                </p>
            </form>
        </div>
    </main>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const consultationTypeSelect = document.getElementById('consultation-type');
            const briefContainer = document.getElementById('brief-container');

            consultationTypeSelect.addEventListener('change', function() {
                if (this.value === 'request_proposal') {
                    briefContainer.style.display = 'block';
                    briefContainer.querySelector('textarea').setAttribute('required', 'required');
                } else {
                    briefContainer.style.display = 'none';
                    briefContainer.querySelector('textarea').removeAttribute('required');
                }
            });
        });
    </script>
</body>
</html>