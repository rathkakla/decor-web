<?php

namespace App\Http\Controllers;

use App\Models\Consultation;
use App\Models\Designer;
use App\Models\DesignerPortfolio;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use App\Models\Support;

class DesignerController extends Controller
{
    public function dashboard(Request $request)
    {
        $user = Auth::user();
        $designer = Designer::where('user_id', $user->id)->firstOrFail();

        // Auto-complete expired chat consultations
        \App\Models\Consultation::where('designer_id', $designer->id)
            ->where('consultation_type', 'chat_consultation')
            ->where('status', \App\Models\Consultation::STATUS_ACTIVE)
            ->whereNotNull('chat_expires_at')
            ->where('chat_expires_at', '<', now())
            ->update(['status' => \App\Models\Consultation::STATUS_COMPLETED]);
        $selectedYear = $request->get('year', date('Y'));

        // 1. Stats Utama
        $activeConsultations = Consultation::where('designer_id', $designer->id)
            ->whereNotIn('status', [Consultation::STATUS_COMPLETED, Consultation::STATUS_REJECTED])
            ->count();

        $pendingRequests = Consultation::where('designer_id', $designer->id)
            ->whereIn('status', [Consultation::STATUS_WAITING_APPROVAL, Consultation::STATUS_WAITING_CONSULTATION_FEE])
            ->count();

        // Penghasilan (Hanya yang sudah disetujui quotenya)
        $monthlyEarnings = \App\Models\ConsultationQuote::whereHas('consultation', function($q) use ($designer) {
                $q->where('designer_id', $designer->id);
            })
            ->where('status', 'accepted')
            ->sum('amount');

        // Rating
        $avgRating = \App\Models\ConsultationReview::where('designer_id', $designer->id)->avg('rating') ?: 4.9;

        // 2. Transaksi Terbaru
        $recentTransactions = \App\Models\ConsultationQuote::with(['consultation.customer.user'])
            ->whereHas('consultation', function($q) use ($designer) {
                $q->where('designer_id', $designer->id);
            })
            ->where('status', 'accepted')
            ->latest()
            ->take(5)
            ->get();

        // 3. Data Grafik Berdasarkan Tahun
        $months = ['JAN', 'FEB', 'MAR', 'APR', 'MAY', 'JUN', 'JUL', 'AUG', 'SEP', 'OCT', 'NOV', 'DEC'];
        $revenueData = [];

        for ($m = 1; $m <= 12; $m++) {
            $start = "$selectedYear-" . str_pad($m, 2, '0', STR_PAD_LEFT) . "-01 00:00:00";
            $end = date("Y-m-t 23:59:59", strtotime($start));

            $rev = \App\Models\ConsultationQuote::whereHas('consultation', function($q) use ($designer) {
                    $q->where('designer_id', $designer->id);
                })
                ->where('status', 'accepted')
                ->whereBetween('updated_at', [$start, $end])
                ->sum('amount');

            $revenueData[] = (float) $rev;
        }

        // Ambil daftar tahun dari quotes
        $dbYears = \App\Models\ConsultationQuote::selectRaw('YEAR(updated_at) as year')
            ->whereHas('consultation', function($q) use ($designer) {
                $q->where('designer_id', $designer->id);
            })
            ->where('status', 'accepted')
            ->distinct()
            ->pluck('year')
            ->toArray();
            
        $currentYear = (int)date('Y');
        $defaultYears = [$currentYear, $currentYear - 1, $currentYear - 2];
        
        $availableYears = array_unique(array_merge($dbYears, $defaultYears));
        rsort($availableYears);

        return view('designer.dashboard', compact(
            'designer',
            'activeConsultations', 
            'pendingRequests', 
            'monthlyEarnings', 
            'avgRating', 
            'recentTransactions',
            'months',
            'revenueData',
            'selectedYear',
            'availableYears'
        ));
    }

    public function consultationIndex(Request $request)
    {
        $user = Auth::user();
        $designer = Designer::where('user_id', $user->id)->firstOrFail();

        // Auto-complete expired chat consultations
        \App\Models\Consultation::where('designer_id', $designer->id)
            ->where('consultation_type', 'chat_consultation')
            ->where('status', \App\Models\Consultation::STATUS_ACTIVE)
            ->whereNotNull('chat_expires_at')
            ->where('chat_expires_at', '<', now())
            ->update(['status' => \App\Models\Consultation::STATUS_COMPLETED]);

        $query = Consultation::where('designer_id', $designer->id)
            ->with(['customer.user']);

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhereHas('customer.user', function ($q2) use ($search) {
                      $q2->where('full_name', 'like', "%{$search}%");
                  });
            });
        }

        $consultations = $query->latest()->get();

        return view('designer.consultation.index', compact('consultations'));
    }

    public function consultationShow($id)
    {
        $user = Auth::user();
        $designer = Designer::where('user_id', $user->id)->firstOrFail();

        // Auto-complete expired chat consultations
        \App\Models\Consultation::where('designer_id', $designer->id)
            ->where('consultation_type', 'chat_consultation')
            ->where('status', \App\Models\Consultation::STATUS_ACTIVE)
            ->whereNotNull('chat_expires_at')
            ->where('chat_expires_at', '<', now())
            ->update(['status' => \App\Models\Consultation::STATUS_COMPLETED]);

        $consultation = Consultation::where('id', $id)
            ->where('designer_id', $designer->id)
            ->with(['customer.user', 'messages.sender', 'attachments', 'quotes'])
            ->firstOrFail();

        return view('designer.consultation.show', compact('consultation'));
    }

    public function downloadRabTemplate()
    {
        $files = [];
        
        // Periksa folder public/templates (jamak)
        $dirPlural = public_path('templates');
        if (file_exists($dirPlural)) {
            $files = array_merge($files, array_filter(glob($dirPlural . '/*'), 'is_file'));
        }
        
        // Periksa folder public/template (tunggal)
        $dirSingular = public_path('template');
        if (file_exists($dirSingular)) {
            $files = array_merge($files, array_filter(glob($dirSingular . '/*'), 'is_file'));
        }
        
        \Log::info("Files found in RAB template folders: " . json_encode($files));
        
        if (count($files) === 1) {
            // Jika hanya ada satu file, unduh langsung
            return response()->download($files[0]);
        } elseif (count($files) > 1) {
            // Jika ada lebih dari satu file, gabungkan ke dalam satu file ZIP secara dinamis
            $zipFileName = 'template-dan-contoh-rab-decor.zip';
            $zipPath = storage_path('app/' . $zipFileName);
            
            if (file_exists($zipPath)) {
                @unlink($zipPath);
            }
            
            $zip = new \ZipArchive();
            if ($zip->open($zipPath, \ZipArchive::CREATE | \ZipArchive::OVERWRITE) === true) {
                foreach ($files as $file) {
                    $zip->addFile($file, basename($file));
                }
                $zip->close();
                
                \Log::info("Zipped " . count($files) . " files to: " . $zipPath);
                return response()->download($zipPath)->deleteFileAfterSend(true);
            }
        }

        // Fallback jika tidak ada template kustom (mengunduh CSV dinamis)
        $headers = [
            "Content-type"        => "text/csv",
            "Content-Disposition" => "attachment; filename=template-rab-decor.csv",
            "Pragma"              => "no-cache",
            "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
            "Expires"             => "0"
        ];

        $columns = ['No', 'Nama Pekerjaan / Item Description', 'Volume / Qty', 'Satuan / Unit', 'Harga Satuan / Unit Price', 'Total Harga'];

        $callback = function() use($columns) {
            $file = fopen('php://output', 'w');
            fputcsv($file, $columns);
            
            // Add a sample row
            fputcsv($file, [1, 'Pekerjaan Desain Living Room', 1, 'Ls', 1500000, 1500000]);
            fputcsv($file, [2, 'Pekerjaan Desain Bedroom', 1, 'Ls', 1200000, 1200000]);
            
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    public function sendQuote(Request $request, $id)
    {
        $consultation = Consultation::findOrFail($id);
        if ($consultation->consultation_type == 'chat_consultation') {
            return back()->with('error', 'Anda tidak dapat mengirimkan RAB/Project Agreement untuk tipe Chat Consultation.');
        }

        $request->validate([
            'amount' => 'required|numeric|min:0',
            'notes' => 'nullable|string',
            'rab_file' => 'required|file|mimes:pdf,xlsx,xls,csv,doc,docx,jpg,jpeg,png|max:10240',
            'design_image' => 'nullable|array',
            'design_image.*' => 'image|max:10240',
        ]);

        $filePath = null;
        $fileName = null;
        if ($request->hasFile('rab_file')) {
            $file = $request->file('rab_file');
            $filePath = $file->store('consultations/quotes', 'public');
            $fileName = $file->getClientOriginalName();
        }

        $designImagePaths = [];
        if ($request->hasFile('design_image')) {
            $files = $request->file('design_image');
            if (!is_array($files)) $files = [$files];
            
            // Use same timestamp so blade groups them into one collage
            $groupTime = now();
            
            foreach ($files as $file) {
                $path = $file->store('consultations/designs', 'public');
                $designImagePaths[] = $path;

                // Create attachment with identical created_at so collage logic groups them
                $attachment = \App\Models\ConsultationAttachment::create([
                    'consultation_id' => $id,
                    'uploaded_by' => Auth::id(),
                    'file_url' => $path,
                    'file_type' => 'image',
                ]);
                // Force same timestamp for collage grouping
                $attachment->created_at = $groupTime;
                $attachment->updated_at = $groupTime;
                $attachment->saveQuietly();
            }

            // Add an accompanying message
            \App\Models\ConsultationMessage::create([
                'consultation_id' => $id,
                'sender_id' => Auth::id(),
                'message' => "🖼️ Berikut adalah " . count($designImagePaths) . " gambar hasil desain terbaru untuk proyek kita.",
            ]);
        }

        $quote = \App\Models\ConsultationQuote::create([
            'consultation_id' => $id,
            'amount' => $request->amount,
            'notes' => $request->notes,
            'items' => json_encode([
                'file_path' => $filePath,
                'file_name' => $fileName,
            ]),
            'design_image' => !empty($designImagePaths) ? json_encode($designImagePaths) : null,
            'status' => 'pending',
        ]);

        // Set status to Offer Received (8) so client sees it's being negotiated
        $consultation = Consultation::find($id);
        if ($consultation) {
            $consultation->update(['status' => Consultation::STATUS_OFFER_RECEIVED]);
        }

        // Send a message to chat about RAB
        \App\Models\ConsultationMessage::create([
            'consultation_id' => $id,
            'sender_id' => Auth::id(),
            'message' => "📄 RAB & Project Agreement telah dikirim. Silakan cek di Halaman Track Consultation.",
        ]);

        return back()->with('success', 'RAB dan Project Agreement berhasil dikirim ke customer');
    }

    public function updateConsultationStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|integer|between:0,9',
        ]);

        $consultation = Consultation::findOrFail($id);
        
        // Custom logic for rejection if needed
        if ($request->status == Consultation::STATUS_REJECTED) {
            // Rejection logic (maybe notify customer)
        }

        $consultation->update([
            'status' => $request->status,
        ]);

        if ($request->status == Consultation::STATUS_COMPLETED) {
            $latestQuote = $consultation->quotes()->whereNotNull('design_image')->latest()->first();
            if ($latestQuote) {
                // Decode JSON array to get the first image
                $designImages = json_decode($latestQuote->design_image, true);
                $firstImage = is_array($designImages) && count($designImages) > 0 ? $designImages[0] : $latestQuote->design_image;

                $existingPortfolio = \App\Models\DesignerPortfolio::where('consultation_id', $consultation->id)->first();
                if (!$existingPortfolio) {
                    \App\Models\DesignerPortfolio::create([
                        'designer_id' => $consultation->designer_id,
                        'consultation_id' => $consultation->id,
                        'title' => $consultation->title,
                        'image_url' => $firstImage,
                        'description' => $consultation->description,
                        'status' => 'approved',
                    ]);
                } else {
                    $existingPortfolio->update([
                        'image_url' => $firstImage,
                        'status' => 'approved',
                    ]);
                }
            }
        }

        $statusLabel = Consultation::getStatusLabel($request->status);
        return back()->with('success', "Status konsultasi berhasil diperbarui menjadi: $statusLabel");
    }

    public function validatePayment(Request $request, $id)
    {
        $request->validate([
            'action' => 'required|in:approve,reject',
        ]);

        $designer = Designer::where('user_id', Auth::id())->firstOrFail();
        $consultation = Consultation::where('id', $id)
            ->where('designer_id', $designer->id)
            ->firstOrFail();

        if ($request->action === 'approve') {
            if ($consultation->status == Consultation::STATUS_WAITING_CONSULTATION_FEE) {
                // Initial Consultation Fee approved
                if (empty($consultation->description)) {
                    $consultation->status = Consultation::STATUS_WAITING_BRIEF;
                } else {
                    $consultation->status = Consultation::STATUS_ACTIVE;
                }
            } elseif ($consultation->status == Consultation::STATUS_WAITING_FINAL_PAYMENT) {
                // Final Payment approved
                $consultation->status = Consultation::STATUS_ACTIVE;
            }
            $consultation->payment_proof = null; // Clear payment proof on approval
            $consultation->save();

            return back()->with('success', 'Pembayaran berhasil disetujui!');
        } else {
            // Rejected
            $consultation->payment_proof = null; // Reset payment proof so they can upload again
            $consultation->save();

            return back()->with('error', 'Pembayaran ditolak.');
        }
    }

    public function sendConsultationMessage(Request $request, $id)
    {
        $request->validate([
            'message' => 'nullable|string',
            'attachment' => 'nullable|file|max:10240',
        ]);

        $consultation = Consultation::findOrFail($id);
        if ($consultation->is_chat_expired) {
            return back()->with('error', 'Waktu chat konsultasi telah habis.');
        }

        if ($request->hasFile('attachment')) {
            $path = $request->file('attachment')->store('consultations/attachments', 'public');
            \App\Models\ConsultationAttachment::create([
                'consultation_id' => $id,
                'uploaded_by' => Auth::id(),
                'file_url' => $path,
                'file_type' => $request->file('attachment')->getClientMimeType(),
            ]);
        }

        if ($request->filled('message')) {
            \App\Models\ConsultationMessage::create([
                'consultation_id' => $id,
                'sender_id' => Auth::id(),
                'message' => $request->message,
            ]);
        }

        return back()->with('success', 'Pesan berhasil dikirim');
    }

    public function uploadConsultationAttachment(Request $request, $id)
    {
        $request->validate([
            'file' => 'required|file|max:10240',
        ]);

        if ($request->hasFile('file')) {
            $path = $request->file('file')->store('consultations/attachments', 'public');
            
            \App\Models\ConsultationAttachment::create([
                'consultation_id' => $id,
                'uploaded_by' => Auth::id(),
                'file_url' => $path,
                'file_type' => $request->file('file')->getClientMimeType(),
            ]);

            return back()->with('success', 'File berhasil diunggah');
        }

        return back()->with('error', 'Gagal mengunggah file');
    }

    public function portfolioIndex()
    {
        $designer = Designer::where('user_id', Auth::id())->firstOrFail();
        $portfolios = DesignerPortfolio::where('designer_id', $designer->id)->latest()->get();

        $manualPortfoliosCount = DesignerPortfolio::where('designer_id', $designer->id)
            ->whereNull('consultation_id')
            ->count();
        $maxManualPortfolios = 5;

        return view('designer.portfolio.index', compact('portfolios', 'designer', 'manualPortfoliosCount', 'maxManualPortfolios'));
    }

    public function portfolioCreate()
    {
        $designer = Designer::where('user_id', Auth::id())->firstOrFail();
        $manualPortfoliosCount = DesignerPortfolio::where('designer_id', $designer->id)
            ->whereNull('consultation_id')
            ->count();
        $maxManualPortfolios = 5;

        if ($manualPortfoliosCount >= $maxManualPortfolios) {
            return redirect()->route('designer.portfolio.index')
                ->with('error', "Batas upload portofolio manual Anda telah tercapai ($maxManualPortfolios). Selesaikan proyek dengan customer untuk menambah portofolio secara otomatis!");
        }

        return view('designer.portfolio.create');
    }

    public function portfolioStore(Request $request)
    {
        $designer = Designer::where('user_id', Auth::id())->firstOrFail();

        $manualPortfoliosCount = DesignerPortfolio::where('designer_id', $designer->id)
            ->whereNull('consultation_id')
            ->count();
        $maxManualPortfolios = 5;

        if ($manualPortfoliosCount >= $maxManualPortfolios) {
            return redirect()->route('designer.portfolio.index')
                ->with('error', "Batas upload portofolio manual Anda telah tercapai ($maxManualPortfolios). Selesaikan proyek dengan customer untuk menambah portofolio secara otomatis!");
        }

        $request->validate([
            'title' => 'required|string|max:150',
            'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:102400',
            'description' => 'nullable|string',
            'category' => 'nullable|string',
            'budget' => 'nullable|string',
            'area' => 'nullable|string',
            'duration' => 'nullable|string',
            'is_360' => 'nullable',
        ]);

        $path = $request->file('image')->store('designers/portfolios', 'public');

        DesignerPortfolio::create([
            'designer_id' => $designer->id,
            'consultation_id' => null,
            'title' => $request->title,
            'image_url' => $path,
            'is_360' => $request->has('is_360'),
            'description' => $request->description,
            'category' => $request->category,
            'budget' => $request->budget,
            'area' => $request->area,
            'duration' => $request->duration,
        ]);

        return redirect()->route('designer.portfolio.index')->with('success', 'Portfolio berhasil ditambahkan!');
    }

    public function portfolioEdit($id)
    {
        $designer = Designer::where('user_id', Auth::id())->firstOrFail();
        $portfolio = DesignerPortfolio::where('id', $id)
            ->where('designer_id', $designer->id)
            ->whereNull('consultation_id')
            ->firstOrFail();

        return view('designer.portfolio.edit', compact('portfolio'));
    }

    public function portfolioUpdate(Request $request, $id)
    {
        $designer = Designer::where('user_id', Auth::id())->firstOrFail();
        $portfolio = DesignerPortfolio::where('id', $id)
            ->where('designer_id', $designer->id)
            ->whereNull('consultation_id')
            ->firstOrFail();

        $request->validate([
            'title' => 'required|string|max:150',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:102400',
            'description' => 'nullable|string',
            'category' => 'nullable|string',
            'budget' => 'required|string',
            'area' => 'nullable|string',
            'duration' => 'nullable|string',
            'is_360' => 'nullable',
        ], [
            'budget.required' => 'Harga wajib diisi saat edit portofolio manual.'
        ]);

        if ($request->hasFile('image')) {
            if ($portfolio->image_url) {
                \Illuminate\Support\Facades\Storage::disk('public')->delete($portfolio->image_url);
            }
            $portfolio->image_url = $request->file('image')->store('designers/portfolios', 'public');
        }

        $portfolio->update([
            'title' => $request->title,
            'image_url' => $portfolio->image_url,
            'is_360' => $request->has('is_360'),
            'description' => $request->description,
            'category' => $request->category,
            'budget' => $request->budget,
            'area' => $request->area,
            'duration' => $request->duration,
        ]);

        return redirect()->route('designer.portfolio.index')->with('success', 'Portfolio berhasil diupdate!');
    }

    public function portfolioDestroy($id)
    {
        $designer = Designer::where('user_id', Auth::id())->firstOrFail();
        $portfolio = DesignerPortfolio::where('id', $id)->where('designer_id', $designer->id)->firstOrFail();

        if ($portfolio->image_url) {
            Storage::disk('public')->delete($portfolio->image_url);
        }

        $portfolio->delete();

        return back()->with('success', 'Portfolio berhasil dihapus!');
    }

    public function portfolioUpdateArea(Request $request, $id)
    {
        $designer = Designer::where('user_id', Auth::id())->firstOrFail();
        $portfolio = DesignerPortfolio::where('id', $id)->where('designer_id', $designer->id)->firstOrFail();

        $request->validate([
            'area' => 'nullable|string|max:100',
        ]);

        $portfolio->update([
            'area' => $request->area,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Luas area berhasil diperbarui!',
            'area' => $portfolio->area ?? '-',
        ]);
    }

    public function reviews(Request $request)
    {
        $user = Auth::user();
        $designer = Designer::where('user_id', $user->id)->firstOrFail();

        $query = \App\Models\ConsultationReview::with(['customer', 'consultation'])
            ->where('designer_id', $designer->id)
            ->latest();

        // Search filter
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('comment', 'like', "%{$search}%")
                  ->orWhereHas('customer', fn($q2) => $q2->where('full_name', 'like', "%{$search}%"));
            });
        }

        // Rating filter
        if ($request->filled('rating')) {
            $query->where('rating', $request->rating);
        }

        $reviews = $query->get();

        $totalReviews   = $reviews->count();
        $avgRating      = $totalReviews > 0 ? round($reviews->avg('rating'), 1) : 0;
        $ratingCounts   = [];
        for ($i = 5; $i >= 1; $i--) {
            $cnt = $reviews->where('rating', $i)->count();
            $ratingCounts[$i] = [
                'count'   => $cnt,
                'percent' => $totalReviews > 0 ? round(($cnt / $totalReviews) * 100) : 0,
            ];
        }
        $repliedCount   = $reviews->whereNotNull('designer_reply')->count();
        $responseRate   = $totalReviews > 0 ? round(($repliedCount / $totalReviews) * 100, 1) : 0;

        return view('designer.reviews', compact(
            'designer', 'reviews', 'totalReviews', 'avgRating',
            'ratingCounts', 'responseRate'
        ));
    }

    public function replyReview(Request $request, $reviewId)
    {
        $request->validate([
            'reply' => 'required|string|max:1000',
        ], [
            'reply.required' => 'Gagal dikirim! Wajib mengisi teks balasan.'
        ]);

        $designer = Designer::where('user_id', Auth::id())->firstOrFail();
        $review   = \App\Models\ConsultationReview::where('id', $reviewId)
                        ->where('designer_id', $designer->id)
                        ->firstOrFail();

        $review->update([
            'designer_reply'      => $request->reply,
            'designer_replied_at' => now(),
        ]);

        return back()->with('success', 'Balasan Anda berhasil dikirim!');
    }

    public function chatIndex(Request $request, $userId = null)
    {
        $designerId = Auth::id();
        $search = $request->input('search');

        // Get unique users who have messaged this designer
        $conversations = \App\Models\Chat::where('receiver_id', $designerId)
            ->orWhere('sender_id', $designerId)
            ->with(['sender', 'receiver'])
            ->latest()
            ->get()
            ->map(function ($chat) use ($designerId) {
                return $chat->sender_id == $designerId ? $chat->receiver : $chat->sender;
            })
            ->unique('id')
            ->values();

        if ($search) {
            $conversations = $conversations->filter(function ($user) use ($search) {
                return $user && stripos($user->full_name, $search) !== false;
            })->values();
        }

        $activeChat = null;
        $messages = [];
        $timeLeft = 0;
        $isFreeChat = false;

        if ($userId) {
            $activeChat = \App\Models\User::findOrFail($userId);
            
            // Check if there is an active free consultation
            $customer = \App\Models\Customer::where('user_id', $userId)->first();
            $designer = \App\Models\Designer::where('user_id', Auth::id())->first();

            if ($customer && $designer) {
                $freeConsultation = \App\Models\FreeConsultation::where('customer_id', $customer->id)
                    ->where('designer_id', $designer->id)
                    ->first();
                
                if ($freeConsultation) {
                    $isFreeChat = true;
                    if (!$freeConsultation->is_completed && !$freeConsultation->expires_at->isPast()) {
                        $timeLeft = (int) now()->diffInSeconds($freeConsultation->expires_at);
                    }
                }
            }
            $messages = \App\Models\Chat::where(function ($q) use ($designerId, $userId) {
                $q->where('sender_id', $designerId)->where('receiver_id', $userId);
            })
                ->orWhere(function ($q) use ($designerId, $userId) {
                    $q->where('sender_id', $userId)->where('receiver_id', $designerId);
                })
                ->with('product')
                ->oldest()
                ->get();

            // Mark as read
            \App\Models\Chat::where('sender_id', $userId)
                ->where('receiver_id', $designerId)
                ->where('is_read', false)
                ->update(['is_read' => true]);
        }

        // We will use the same view path or create an index view
        // Since `designer.chat.chat` is the static one, I'll pass data to it and we'll update it
        return view('designer.chat.chat', compact('conversations', 'activeChat', 'messages', 'timeLeft', 'isFreeChat'));
    }

    public function sendChatMessage(Request $request)
    {
        $request->validate([
            'receiver_id' => 'required|exists:users,id',
            'message' => 'nullable|string',
            'attachment' => 'nullable|file|mimes:jpeg,png,jpg,pdf,doc,docx,zip|max:5120'
        ]);

        if (!$request->message && !$request->hasFile('attachment')) {
            return back()->with('error', 'Pesan atau lampiran harus diisi.');
        }

        $attachmentPath = null;
        if ($request->hasFile('attachment')) {
            $attachmentPath = $request->file('attachment')->store('chat_attachments', 'public');
        }

        \App\Models\Chat::create([
            'sender_id' => Auth::id(),
            'receiver_id' => $request->receiver_id,
            'message' => $request->message ?? '',
            'attachment' => $attachmentPath,
        ]);

        return redirect()->back();
    }

    public function settings()
    {
        $designer = Designer::where('user_id', Auth::id())->firstOrFail();
        return view('designer.settings.index', compact('designer'));
    }

    public function settingsBank()
    {
        $designer = Designer::where('user_id', Auth::id())->firstOrFail();
        return view('designer.settings.bank', compact('designer'));
    }

    public function updateBankSettings(Request $request)
    {
        $designer = Designer::where('user_id', Auth::id())->firstOrFail();

        $request->validate([
            'bank_name' => 'nullable|string|max:100',
            'account_number' => 'required|string|max:50',
            'digital_signature' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ], [
            'account_number.required' => 'Nomor rekening wajib diisi.',
        ]);

        $designer->bank_name = $request->bank_name;
        $designer->account_number = $request->account_number;

        if ($request->hasFile('digital_signature')) {
            if ($designer->digital_signature) {
                Storage::disk('public')->delete($designer->digital_signature);
            }
            $designer->digital_signature = $request->file('digital_signature')->store('designers/signatures', 'public');
        }

        $designer->save();

        return back()->with('success', 'Bank & Signature settings updated successfully!');
    }

    public function updateSettings(Request $request)
    {
        $designer = Designer::where('user_id', Auth::id())->firstOrFail();

        $request->validate([
            'studio_name' => 'required|string|max:150',
            'specialty' => 'nullable|string|max:100',
            'bio' => 'nullable|string',
            'education' => 'nullable|string',
            'awards' => 'nullable|string',
            'designer_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'banner_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'is_open' => 'nullable',
            'instagram_url' => 'nullable|url|max:255',
            'linkedin_url' => 'nullable|url|max:255',
        ], [
            'studio_name.required' => 'Nama studio tidak boleh kosong.',
        ]);

        $designer->studio_name = $request->studio_name;
        $designer->specialty = $request->specialty;
        $designer->bio = $request->bio;
        $designer->education = $request->education;
        $designer->awards = $request->awards;
        $designer->is_open = $request->has('is_open');
        $designer->instagram_url = $request->instagram_url;
        $designer->linkedin_url = $request->linkedin_url;

        if ($request->hasFile('designer_image')) {
            if ($designer->designer_image) {
                Storage::disk('public')->delete($designer->designer_image);
            }
            $designer->designer_image = $request->file('designer_image')->store('designers/avatars', 'public');
        }

        if ($request->hasFile('banner_image')) {
            if ($designer->banner_image) {
                Storage::disk('public')->delete($designer->banner_image);
            }
            $designer->banner_image = $request->file('banner_image')->store('designers/banners', 'public');
        }

        $designer->save();

        return back()->with('success', 'Settings updated successfully!');
    }

    public function downloadInvoice($id)
    {
        $consultation = Consultation::with(['designer.user', 'customer.user', 'quotes'])->findOrFail($id);
        
        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('invoices.consultation', compact('consultation'));
        
        return $pdf->download('invoice-consultation-' . $id . '.pdf');
    }

    public function downloadDesignImages($quoteId)
    {
        $quote = \App\Models\ConsultationQuote::findOrFail($quoteId);

        if (!$quote->design_image) {
            return back()->with('error', 'Tidak ada gambar desain untuk diunduh.');
        }

        $images = json_decode($quote->design_image, true);
        if (!is_array($images)) $images = [$quote->design_image];
        $images = array_filter($images); // remove nulls

        if (count($images) === 1) {
            // Single image — stream directly
            $path = Storage::disk('public')->path($images[0]);
            if (!file_exists($path)) abort(404);
            return response()->download($path, 'design-' . basename($images[0]));
        }

        // Multiple images — zip them
        $zipFileName = 'design-images-quote-' . $quoteId . '.zip';
        $zipPath = storage_path('app/' . $zipFileName);

        if (file_exists($zipPath)) @unlink($zipPath);

        $zip = new \ZipArchive();
        if ($zip->open($zipPath, \ZipArchive::CREATE | \ZipArchive::OVERWRITE) === true) {
            foreach ($images as $idx => $img) {
                $filePath = Storage::disk('public')->path($img);
                if (file_exists($filePath)) {
                    $zip->addFile($filePath, 'design-' . ($idx + 1) . '-' . basename($img));
                }
            }
            $zip->close();
        }

        return response()->download($zipPath, $zipFileName)->deleteFileAfterSend(true);
    }

    public function submitSupport(Request $request)
    {
        $request->validate([
            'subject' => 'required|string|max:255',
            'message' => 'required|string',
        ], [
            'message.required' => 'Pesan detail komplain tidak boleh dikosongkan.'
        ]);

        Support::create([
            'user_id' => Auth::id(),
            'subject' => $request->subject,
            'message' => $request->message,
            'status' => 'pending',
        ]);

        return back()->with('success', 'Komplain Anda telah dikirim ke Admin. Mohon tunggu respon kami.');
    }

    public function reportIndex(Request $request)
    {
        $designer = \App\Models\Designer::where('user_id', Auth::id())->firstOrFail();
        
        $startDate = $request->input('start_date', now()->subDays(30)->format('Y-m-d'));
        $endDate = $request->input('end_date', now()->format('Y-m-d'));

        if ($startDate > $endDate) {
            return redirect()->route('designer.reports')
                ->with('warning', 'Peringatan: Start Date tidak boleh mendahului/melebihi End Date. Sistem telah mereset filter ke 30 hari terakhir.');
        }

        $consultations = \App\Models\Consultation::with(['quotes' => function($q) {
                $q->where('status', 'accepted');
            }, 'customer.user'])
            ->where('designer_id', $designer->id)
            ->whereBetween('updated_at', [$startDate . ' 00:00:00', $endDate . ' 23:59:59'])
            ->where('status', \App\Models\Consultation::STATUS_COMPLETED)
            ->orderBy('updated_at', 'desc')
            ->get();
            
        $periodRevenue = 0;
        foreach($consultations as $c) {
            $quote = $c->quotes->first();
            if ($quote) $periodRevenue += $quote->amount;
        }

        $projectsCompleted = $consultations->count();
        $avgProjectValue = $projectsCompleted > 0 ? $periodRevenue / $projectsCompleted : 0;

        $totalConsultationsPeriod = \App\Models\Consultation::where('designer_id', $designer->id)
            ->whereBetween('created_at', [$startDate . ' 00:00:00', $endDate . ' 23:59:59'])
            ->count();
            
        $leadConversion = $totalConsultationsPeriod > 0 ? round(($projectsCompleted / $totalConsultationsPeriod) * 100) : 0;

        // Group by day for simple line chart
        $chartLabels = [];
        $chartData = [];
        $grouped = $consultations->groupBy(function($item) {
            return $item->updated_at->format('M d');
        });

        if ($consultations->count() > 0) {
            foreach ($grouped->sortBy(function($val, $key) { return \Carbon\Carbon::parse($key)->timestamp; }) as $key => $items) {
                $chartLabels[] = $key;
                $dailySum = 0;
                foreach($items as $item) {
                    $q = $item->quotes->first();
                    if ($q) $dailySum += $q->amount;
                }
                $chartData[] = $dailySum;
            }
        } else {
            $chartLabels = ['Week 1', 'Week 2', 'Week 3', 'Week 4'];
            $chartData = [0, 0, 0, 0];
        }

        return view('designer.report.index', compact('startDate', 'endDate', 'consultations', 'periodRevenue', 'projectsCompleted', 'avgProjectValue', 'leadConversion', 'chartLabels', 'chartData', 'designer'));
    }

    public function reportExport(Request $request)
    {
        $designer = \App\Models\Designer::where('user_id', Auth::id())->firstOrFail();
        
        $startDate = $request->input('start_date', now()->subDays(30)->format('Y-m-d'));
        $endDate = $request->input('end_date', now()->format('Y-m-d'));

        if ($startDate > $endDate) {
            return redirect()->route('designer.reports')
                ->with('warning', 'Peringatan: Start Date tidak boleh mendahului/melebihi End Date. Sistem telah mereset filter ke 30 hari terakhir.');
        }

        $consultations = \App\Models\Consultation::with(['quotes' => function($q) {
                $q->where('status', 'accepted');
            }, 'customer.user'])
            ->where('designer_id', $designer->id)
            ->whereBetween('updated_at', [$startDate . ' 00:00:00', $endDate . ' 23:59:59'])
            ->where('status', \App\Models\Consultation::STATUS_COMPLETED)
            ->orderBy('updated_at', 'desc')
            ->get();
            
        $periodRevenue = 0;
        foreach($consultations as $c) {
            $quote = $c->quotes->first();
            if ($quote) $periodRevenue += $quote->amount;
        }

        $projectsCompleted = $consultations->count();
        $avgProjectValue = $projectsCompleted > 0 ? $periodRevenue / $projectsCompleted : 0;
        
        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('designer.report.export_pdf', [
            'start_date' => $startDate,
            'end_date' => $endDate,
            'consultations' => $consultations,
            'periodRevenue' => $periodRevenue,
            'projectsCompleted' => $projectsCompleted,
            'avgProjectValue' => $avgProjectValue,
            'designer' => $designer
        ]);
        
        return $pdf->download('report_' . $startDate . '_to_' . $endDate . '.pdf');
    }
}