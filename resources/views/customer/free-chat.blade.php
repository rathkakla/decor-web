@php 
    $site_name = "DECOR"; 
@endphp
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Free Consultation Chat — {{ $site_name }}</title>
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
        body { font-family: 'Plus Jakarta Sans', sans-serif; background-color: #f8f6f4; }
        .chat-scroll::-webkit-scrollbar { width: 4px; }
        .chat-scroll::-webkit-scrollbar-thumb { background: #E5E7EB; border-radius: 10px; }
    </style>
</head>
<body class="text-gray-800 flex flex-col h-screen overflow-hidden">

    <!-- Header -->
    @include('customer.partials.navbar')

    <!-- Chat Area -->
    <main class="flex-grow flex flex-col max-w-4xl mx-auto w-full bg-white shadow-sm border-x border-gray-50 relative">
        <div class="flex-grow p-8 overflow-y-auto chat-scroll space-y-6 flex flex-col" id="chatContainer">
            
            <!-- Welcome System Message -->
            <div class="flex justify-center mb-4">
                <div class="bg-gray-50 border border-gray-100 px-6 py-3 rounded-full text-[10px] font-bold text-gray-400 uppercase tracking-widest italic">
                    10-minute free consultation started
                </div>
            </div>

            <!-- Designer Greeting -->
            <div class="flex justify-start">
                <div class="max-w-[70%] bg-gray-50 border border-gray-100 rounded-[2rem] rounded-tl-none px-6 py-4 text-[13px] leading-relaxed text-gray-700 shadow-sm">
                    Hello! I'm {{ $designer->user->full_name }}. How can I help you with your design project today? You have 10 minutes of free consultation.
                </div>
            </div>

            <!-- Messages from Database -->
            @foreach($messages as $msg)
                <div class="flex {{ $msg->sender_id == Auth::id() ? 'justify-end' : 'justify-start' }}">
                    <div class="max-w-[70%] {{ $msg->sender_id == Auth::id() ? 'bg-primary text-white rounded-[2rem] rounded-tr-none shadow-lg shadow-primary/10' : 'bg-gray-50 border border-gray-100 rounded-[2rem] rounded-tl-none text-gray-700 shadow-sm' }} px-6 py-4 text-[13px] leading-relaxed">
                        @if($msg->attachment)
                            @php
                                $ext = pathinfo($msg->attachment, PATHINFO_EXTENSION);
                                $isImage = in_array(strtolower($ext), ['jpg', 'jpeg', 'png', 'gif']);
                            @endphp
                            <div class="mb-2">
                                @if($isImage)
                                    <a href="{{ asset('storage/' . $msg->attachment) }}" target="_blank">
                                        <img src="{{ asset('storage/' . $msg->attachment) }}" class="rounded-xl max-w-full h-auto border border-white/20 shadow-sm">
                                    </a>
                                @else
                                    <a href="{{ asset('storage/' . $msg->attachment) }}" target="_blank" class="flex items-center gap-2 p-3 bg-white/10 rounded-xl border border-white/20 hover:scale-[1.02] transition-all">
                                        <i class="fa-solid fa-file-lines"></i>
                                        <span class="truncate">{{ basename($msg->attachment) }}</span>
                                    </a>
                                @endif
                            </div>
                        @endif
                        {{ $msg->message }}
                        <div class="text-[9px] mt-2 opacity-70 {{ $msg->sender_id == Auth::id() ? 'text-right text-white' : 'text-left text-gray-500' }} font-bold">
                            {{ $msg->created_at->format('H:i') }}
                        </div>
                    </div>
                </div>
            @endforeach
            
        </div>

        <!-- Input Area -->
        <div class="p-6 bg-white border-t border-gray-50">
            <form action="{{ route('customer.chat-seller.send') }}" method="POST" enctype="multipart/form-data" class="bg-gray-50 rounded-full flex items-center px-4 py-1 focus-within:bg-white focus-within:border-gray-200 transition-all border border-transparent">
                @csrf
                <input type="hidden" name="receiver_id" value="{{ $designer->user_id }}">
                <input type="file" name="attachment" id="chat-attachment" class="hidden" onchange="this.form.submit()">
                
                <button type="button" onclick="document.getElementById('chat-attachment').click()" class="p-3 text-gray-400 hover:text-primary transition-colors"><i class="fa-solid fa-paperclip"></i></button>
                <input type="text" name="message" id="messageInput" placeholder="Tulis pesan..." autocomplete="off" class="flex-grow bg-transparent border-none outline-none px-4 py-3 text-sm font-medium placeholder:text-gray-400">
                <button type="submit" class="w-10 h-10 bg-primary text-white rounded-full flex items-center justify-center shadow-md hover:scale-105 transition-transform">
                    <i class="fa-solid fa-paper-plane text-[11px]"></i>
                </button>
            </form>
        </div>

        <!-- Time Up Modal overlay -->
        <div id="timeUpModal" class="absolute inset-0 z-50 bg-white/80 backdrop-blur-sm hidden flex items-center justify-center p-6">
            <div class="max-w-md w-full bg-white border border-gray-100 rounded-[3rem] p-10 shadow-2xl text-center transform scale-95 opacity-0 transition-all duration-500" id="modalContent">
                <div class="w-20 h-20 bg-red-50 rounded-[2rem] flex items-center justify-center text-red-500 mx-auto mb-6">
                    <i class="fa-regular fa-clock text-3xl"></i>
                </div>
                <h3 class="text-2xl font-black italic mb-3">Waktu Habis!</h3>
                <p class="text-[12px] text-gray-500 leading-relaxed mb-8 italic">Sesi free consultation 10 menit Anda telah selesai. Lanjutkan diskusi dengan booking consultation.</p>
                
                <a href="{{ route('customer.designers.book', $designer->id) }}" class="block w-full bg-primary text-white py-5 rounded-2xl text-[11px] font-black uppercase tracking-[0.3em] shadow-xl shadow-primary/20 hover:scale-[1.03] transition-all">
                    Book Consultation
                </a>
                
                <a href="{{ route('customer.designer.profile', $designer->id) }}" class="block w-full mt-4 py-4 rounded-2xl text-[10px] font-bold text-gray-400 uppercase tracking-widest hover:text-gray-600 transition-colors">
                    Kembali ke Profil
                </a>
            </div>
        </div>
    </main>

    <script>
        // Timer initialized from backend
        let timeLeft = {{ $timeLeft }}; // Time remaining in seconds
        const timerElement = document.getElementById('countdown');
        const timeUpModal = document.getElementById('timeUpModal');
        const modalContent = document.getElementById('modalContent');
        const messageInput = document.getElementById('messageInput');
        const chatContainer = document.getElementById('chatContainer');

        if(chatContainer) {
            chatContainer.scrollTop = chatContainer.scrollHeight;
        }

        function updateTimer() {
            if (timeLeft <= 0) {
                clearInterval(timerInterval);
                timerElement.textContent = '00:00';
                showTimeUpModal();
                return;
            }

            const minutes = Math.floor(timeLeft / 60);
            const seconds = Math.floor(timeLeft % 60);
            timerElement.textContent = `${minutes.toString().padStart(2, '0')}:${seconds.toString().padStart(2, '0')}`;
            
            if (timeLeft <= 60) {
                timerElement.parentElement.classList.remove('bg-primary/10', 'border-primary/20', 'text-primary');
                timerElement.parentElement.classList.add('bg-red-50', 'border-red-100', 'text-red-500', 'animate-pulse');
                timerElement.previousElementSibling.classList.remove('text-primary');
                timerElement.previousElementSibling.classList.add('text-red-500');
                timerElement.classList.remove('text-primary');
                timerElement.classList.add('text-red-500');
            }

            timeLeft--;
        }

        const timerInterval = setInterval(updateTimer, 1000);

        function showTimeUpModal() {
            timeUpModal.classList.remove('hidden');
            // Small delay for transition
            setTimeout(() => {
                modalContent.classList.remove('scale-95', 'opacity-0');
                modalContent.classList.add('scale-100', 'opacity-100');
            }, 10);
            messageInput.disabled = true;
        }

        // Auto reload to fetch new messages if no input is focused
        setTimeout(() => {
            if (document.activeElement !== messageInput && timeLeft > 0) {
                window.location.reload();
            }
        }, 10000); // 10 seconds auto-refresh
    </script>
</body>
</html>