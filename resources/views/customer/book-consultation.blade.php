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
        
        .terms-container {
            height: 300px;
            overflow-y: scroll;
            padding: 20px;
            background: #F9FAFB;
            border: 1px solid #E5E7EB;
            border-radius: 8px;
            font-size: 0.875rem;
            line-height: 1.6;
            color: #374151;
        }
        .terms-container::-webkit-scrollbar { width: 6px; }
        .terms-container::-webkit-scrollbar-track { background: #F1F1F1; }
        .terms-container::-webkit-scrollbar-thumb { background: #B5733A; border-radius: 10px; }
    </style>
</head>
<body class="text-gray-800 flex flex-col min-h-screen">

    @include('customer.partials.navbar')

    <main class="flex-grow flex items-center justify-center py-20 px-6">
        <div class="max-w-xl w-full bg-white border border-gray-100 rounded-2xl p-10 shadow-sm text-left">
            <div class="mb-8 text-center">
                <span class="text-[10px] font-bold uppercase tracking-widest text-[#B5733A] mb-2 block">REQUEST PROPOSAL</span>
                <h1 class="text-3xl font-bold tracking-tight mb-2 text-gray-900">Form Pengajuan Konsultasi</h1>
                <p class="text-gray-500 text-sm">Isi formulir berikut untuk mengirimkan request kepada desainer.</p>
            </div>

            <form action="{{ route('customer.designers.book.store', $designer->id) }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                @csrf
                <div>
                    <label class="block text-sm font-semibold text-gray-900 mb-2">Nama Proyek</label>
                    <input type="text" name="title" required placeholder="Contoh: Renovasi Ruang Tamu Minimalis" 
                           class="w-full bg-white border border-gray-200 rounded-lg px-4 py-3 text-sm outline-none focus:border-[#B5733A] transition-colors">
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-900 mb-2">Jenis Konsultasi</label>
                    <select id="consultation-type" name="consultation_type" required class="w-full bg-white border border-gray-200 rounded-lg px-4 py-3 text-sm outline-none focus:border-[#B5733A] transition-colors appearance-none bg-[url('data:image/svg+xml;charset=US-ASCII,%3Csvg%20width%3D%2224%22%20height%3D%2224%22%20viewBox%3D%220%200%2024%2024%22%20fill%3D%22none%22%20xmlns%3D%22http%3A%2F%2Fwww.w3.org%2F2000%2Fsvg%22%3E%3Cpath%20d%3D%22M6%209L12%2015L18%209%22%20stroke%3D%22%236B7280%22%20stroke-width%3D%222%22%20stroke-linecap%3D%22round%22%20stroke-linejoin%3D%22round%22%2F%3E%3C%2Fsvg%3E')] bg-[length:20px_20px] bg-no-repeat bg-[position:right_1rem_center]">
                        <option value="" disabled selected>Pilih Jenis Konsultasi</option>
                        <option value="chat_consultation">Chat Consultation (Rp 50.000)</option>
                        <option value="request_proposal">Request Proposal (Rp 250.000)</option>
                    </select>
                </div>

                <div id="brief-container" style="display: none;">
                    <label class="block text-sm font-semibold text-gray-900 mb-2">Deskripsi Brief</label>
                    <textarea name="description" rows="4" placeholder="Tuliskan ukuran ruangan, nuansa warna, dan perabot yang Anda inginkan..." 
                              class="w-full bg-white border border-gray-200 rounded-lg px-4 py-3 text-sm outline-none focus:border-[#B5733A] transition-colors"></textarea>
                </div>

                <div id="budget-container" style="display: none;">
                    <label class="block text-sm font-semibold text-gray-900 mb-2">Estimasi Budget Renovasi</label>
                    <select id="budget-select" name="budget_range" class="w-full bg-white border border-gray-200 rounded-lg px-4 py-3 text-sm outline-none focus:border-[#B5733A] transition-colors appearance-none bg-[url('data:image/svg+xml;charset=US-ASCII,%3Csvg%20width%3D%2224%22%20height%3D%2224%22%20viewBox%3D%220%200%2024%2024%22%20fill%3D%22none%22%20xmlns%3D%22http%3A%2F%2Fwww.w3.org%2F2000%2Fsvg%22%3E%3Cpath%20d%3D%22M6%209L12%2015L18%209%22%20stroke%3D%22%236B7280%22%20stroke-width%3D%222%22%20stroke-linecap%3D%22round%22%20stroke-linejoin%3D%22round%22%2F%3E%3C%2Fsvg%3E')] bg-[length:20px_20px] bg-no-repeat bg-[position:right_1rem_center]">
                        <option value="" disabled selected>Pilih Budget</option>
                        <option value="Rp 5jt - 10jt">Rp 5jt - 10jt</option>
                        <option value="Rp 10jt - 50jt">Rp 10jt - 50jt</option>
                        <option value="Rp 50jt - 100jt">Rp 50jt - 100jt</option>
                        <option value="> Rp 100jt">> Rp 100jt</option>
                    </select>
                </div>

                <!-- Terms & Conditions Section (Dynamic) -->
                <div id="terms-section" style="display: none;">
                    <div class="flex items-center space-x-3">
                        <input type="checkbox" id="terms-checkbox" disabled class="w-4 h-4 text-[#B5733A] border-gray-300 rounded focus:ring-[#B5733A] cursor-not-allowed">
                        <label for="terms-checkbox" class="text-sm text-gray-500">
                            Saya menyetujui <button type="button" onclick="openTerms()" class="text-[#B5733A] hover:underline font-medium">Syarat & Ketentuan Konsultasi</button>.
                        </label>
                    </div>
                </div>

                <button type="submit" id="submit-btn" class="w-full bg-[#B5733A] text-white py-3.5 rounded-lg text-sm font-bold shadow-sm hover:bg-[#9a6130] transition-colors mt-2">
                    Kirim Permintaan Konsultasi
                </button>
            </form>
        </div>
    </main>

    <!-- Terms Modal -->
    <div id="terms-modal" class="fixed inset-0 z-50 hidden overflow-y-auto bg-black/60 backdrop-blur-sm transition-opacity duration-300">
        <div class="flex items-center justify-center min-h-screen p-4">
            <div class="bg-white rounded-[2.5rem] max-w-2xl w-full p-10 shadow-2xl border border-gray-100">
                <div class="flex justify-between items-center mb-6">
                    <div>
                        <span class="text-[9px] font-black uppercase tracking-[0.3em] text-primary block mb-1">DECOR STUDIO</span>
                        <h3 class="text-2xl font-black tracking-tighter text-gray-900 italic">Syarat & Ketentuan Konsultasi</h3>
                    </div>
                    <button type="button" onclick="closeTerms()" class="text-gray-400 hover:text-gray-600 transition-colors"><i class="fa-solid fa-xmark text-xl"></i></button>
                </div>
                <div id="modal-terms-box" class="terms-container h-[350px] custom-scrollbar rounded-2xl">
                    <p class="mb-4 font-bold text-gray-700">Harap baca dan pahami syarat & ketentuan berikut sebelum melanjutkan pemesanan layanan Request Proposal:</p>
                    
                    <h4 class="font-black text-primary mt-6 mb-2 uppercase text-[10px] tracking-wider italic">1. Batasan Revisi Proposal</h4>
                    <ul class="list-disc pl-5 space-y-2 text-gray-600">
                        <li class="font-bold text-gray-900">Customer hanya mendapatkan hak untuk melakukan revisi sebanyak 1 (satu) kali setelah proposal desain dikirimkan oleh Desainer.</li>
                        <li>Revisi yang diajukan harus mencakup umpan balik yang komprehensif dan jelas untuk menghindari kesalahpahaman.</li>
                        <li>Setiap permintaan revisi tambahan di luar batas 1x revisi akan dikenakan biaya tambahan sesuai kesepakatan dengan Desainer.</li>
                    </ul>

                    <h4 class="font-black text-gray-800 mt-6 mb-2 uppercase text-[10px] tracking-wider italic">2. Pembayaran dan Biaya</h4>
                    <ul class="list-disc pl-5 space-y-2 text-gray-600">
                        <li>Biaya pemesanan Request Proposal sebesar Rp 250.000 wajib dibayarkan setelah request disetujui oleh Desainer.</li>
                        <li>Biaya konsultasi yang telah dibayarkan bersifat non-refundable (tidak dapat dikembalikan) jika proses pengerjaan proposal telah dimulai.</li>
                    </ul>

                    <h4 class="font-black text-gray-800 mt-6 mb-2 uppercase text-[10px] tracking-wider italic">3. Hak Cipta & Kepemilikan Desain</h4>
                    <ul class="list-disc pl-5 space-y-2 text-gray-600">
                        <li>Hak kekayaan intelektual atas proposal desain awal tetap berada pada Desainer sampai proyek disetujui sepenuhnya untuk eksekusi.</li>
                        <li>Customer dilarang menyebarluaskan, menjual kembali, atau menggunakan proposal desain untuk kepentingan komersial tanpa izin tertulis dari Desainer dan platform DECOR.</li>
                    </ul>

                    <h4 class="font-black text-gray-800 mt-6 mb-2 uppercase text-[10px] tracking-wider italic">4. Pembatalan Transaksi</h4>
                    <ul class="list-disc pl-5 space-y-2 text-gray-600">
                        <li>Jika Customer membatalkan pesanan secara sepihak setelah Desainer mulai mengerjakan proposal, biaya konsultasi tidak dapat dikembalikan.</li>
                    </ul>
                    
                    <div class="mt-8 p-4 bg-primary/5 border border-primary/20 rounded-2xl">
                        <p class="text-primary font-black text-center italic text-[10px] uppercase tracking-wider">Silakan scroll hingga akhir halaman untuk menyetujui ketentuan.</p>
                    </div>
                </div>
                <div class="mt-6 flex justify-end">
                    <button type="button" id="btn-modal-close" disabled onclick="acceptTerms()" class="bg-gray-200 text-gray-500 px-8 py-4 rounded-2xl font-black text-[10px] uppercase tracking-widest cursor-not-allowed transition-all duration-300">Close & Accept</button>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const consultationTypeSelect = document.getElementById('consultation-type');
            const briefContainer = document.getElementById('brief-container');
            const budgetContainer = document.getElementById('budget-container');
            const budgetSelect = document.getElementById('budget-select');
            
            const termsSection = document.getElementById('terms-section');
            const termsCheckbox = document.getElementById('terms-checkbox');
            const submitBtn = document.getElementById('submit-btn');

            function updateSubmitState() {
                if (consultationTypeSelect.value === 'request_proposal') {
                    if (termsCheckbox.checked) {
                        submitBtn.disabled = false;
                        submitBtn.classList.remove('opacity-50', 'cursor-not-allowed');
                        submitBtn.classList.add('hover:scale-[1.02]');
                    } else {
                        submitBtn.disabled = true;
                        submitBtn.classList.add('opacity-50', 'cursor-not-allowed');
                        submitBtn.classList.remove('hover:scale-[1.02]');
                    }
                } else {
                    submitBtn.disabled = false;
                    submitBtn.classList.remove('opacity-50', 'cursor-not-allowed');
                    submitBtn.classList.add('hover:scale-[1.02]');
                }
            }

            consultationTypeSelect.addEventListener('change', function() {
                if (this.value === 'request_proposal') {
                    briefContainer.style.display = 'block';
                    briefContainer.querySelector('textarea').setAttribute('required', 'required');
                    
                    budgetContainer.style.display = 'block';
                    budgetSelect.setAttribute('required', 'required');
                    
                    termsSection.style.display = 'block';
                    termsCheckbox.setAttribute('required', 'required');
                    updateSubmitState();
                } else {
                    briefContainer.style.display = 'none';
                    briefContainer.querySelector('textarea').removeAttribute('required');
                    briefContainer.querySelector('textarea').value = '';
                    
                    budgetContainer.style.display = 'none';
                    budgetSelect.removeAttribute('required');
                    budgetSelect.value = '';
                    
                    termsSection.style.display = 'none';
                    termsCheckbox.removeAttribute('required');
                    termsCheckbox.checked = false;
                    updateSubmitState();
                }
            });

            termsCheckbox.addEventListener('change', updateSubmitState);

            // Modal Scroll logic
            const modalTermsBox = document.getElementById('modal-terms-box');
            const modalCloseBtn = document.getElementById('btn-modal-close');

            if (modalTermsBox) {
                modalTermsBox.addEventListener('scroll', function() {
                    const isAtBottom = modalTermsBox.scrollHeight - modalTermsBox.scrollTop <= modalTermsBox.clientHeight + 15;
                    if (isAtBottom) {
                        if (modalCloseBtn) {
                            modalCloseBtn.disabled = false;
                            modalCloseBtn.classList.remove('bg-gray-200', 'text-gray-500', 'cursor-not-allowed');
                            modalCloseBtn.classList.add('bg-primary', 'text-white');
                        }
                        if (termsCheckbox) {
                            termsCheckbox.disabled = false;
                            termsCheckbox.classList.remove('cursor-not-allowed');
                        }
                    }
                });
            }

            // Expose modal functions to window so inline onclick works
            window.openTerms = function() {
                document.getElementById('terms-modal').classList.remove('hidden');
                document.body.style.overflow = 'hidden';
            }

            window.closeTerms = function() {
                document.getElementById('terms-modal').classList.add('hidden');
                document.body.style.overflow = 'auto';
            }

            window.acceptTerms = function() {
                termsCheckbox.checked = true;
                updateSubmitState();
                window.closeTerms();
            }
        });
    </script>
</body>
</html>