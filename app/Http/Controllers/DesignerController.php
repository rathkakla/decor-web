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
    public function dashboard()
    {
        $user = Auth::user();
        $designer = Designer::where('user_id', $user->id)->firstOrFail();

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

        // Rating (Contoh dummy karena review designer mungkin belum ada tabelnya atau pakai relasi review produk)
        // Kita asumsikan ada relasi reviews ke designer atau produk yang dikerjakan designer
        $avgRating = 4.9; // Default if no reviews

        // 2. Transaksi Terbaru
        $recentTransactions = \App\Models\ConsultationQuote::with(['consultation.customer.user'])
            ->whereHas('consultation', function($q) use ($designer) {
                $q->where('designer_id', $designer->id);
            })
            ->where('status', 'accepted')
            ->latest()
            ->take(5)
            ->get();

        // 3. Data Grafik (Dummy 6 bulan terakhir)
        $revenueData = [2100, 1800, 3200, 2900, 4250, 3800];

        return view('designer.dashboard', compact(
            'designer',
            'activeConsultations', 
            'pendingRequests', 
            'monthlyEarnings', 
            'avgRating', 
            'recentTransactions',
            'revenueData'
        ));
    }

    public function consultationIndex()
    {
        $user = Auth::user();
        $designer = Designer::where('user_id', $user->id)->firstOrFail();

        // Get consultations
        $consultations = Consultation::where('designer_id', $designer->id)
            ->with(['customer.user'])
            ->latest()
            ->get();

        return view('designer.consultation.index', compact('consultations'));
    }

    public function consultationShow($id)
    {
        $user = Auth::user();
        $designer = Designer::where('user_id', $user->id)->firstOrFail();

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
        $request->validate([
            'amount' => 'required|numeric|min:0',
            'notes' => 'nullable|string',
            'rab_file' => 'required|file|mimes:pdf,xlsx,xls,csv,doc,docx,jpg,jpeg,png|max:10240',
        ]);

        $filePath = null;
        $fileName = null;
        if ($request->hasFile('rab_file')) {
            $file = $request->file('rab_file');
            $filePath = $file->store('consultations/quotes', 'public');
            $fileName = $file->getClientOriginalName();
        }

        $quote = \App\Models\ConsultationQuote::create([
            'consultation_id' => $id,
            'amount' => $request->amount,
            'notes' => $request->notes,
            'items' => json_encode([
                'file_path' => $filePath,
                'file_name' => $fileName,
            ]),
            'status' => 'pending',
        ]);

        // Set status to Under Review (2) so client sees it's being negotiated
        $consultation = Consultation::find($id);
        if ($consultation) {
            $consultation->update(['status' => Consultation::STATUS_UNDER_REVIEW]);
        }

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

        $statusLabel = Consultation::getStatusLabel($request->status);
        return back()->with('success', "Status konsultasi berhasil diperbarui menjadi: $statusLabel");
    }

    public function sendConsultationMessage(Request $request, $id)
    {
        $request->validate([
            'message' => 'nullable|string',
            'attachment' => 'nullable|file|max:10240',
        ]);

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

    public function reviews()
    {
        return view('designer.reviews');
    }

    public function chatIndex($userId = null)
    {
        $designerId = Auth::id();

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

        $activeChat = null;
        $messages = [];

        if ($userId) {
            $activeChat = \App\Models\User::findOrFail($userId);
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
        return view('designer.chat.chat', compact('conversations', 'activeChat', 'messages'));
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

    public function updateSettings(Request $request)
    {
        $designer = Designer::where('user_id', Auth::id())->firstOrFail();

        $request->validate([
            'studio_name' => 'nullable|string|max:150',
            'specialty' => 'nullable|string|max:100',
            'bio' => 'nullable|string',
            'education' => 'nullable|string',
            'awards' => 'nullable|string',
            'designer_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'banner_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'is_open' => 'nullable',
            'instagram_url' => 'nullable|url|max:255',
            'linkedin_url' => 'nullable|url|max:255',
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

    public function submitSupport(Request $request)
    {
        $request->validate([
            'subject' => 'required|string|max:255',
            'message' => 'required|string',
        ]);

        Support::create([
            'user_id' => Auth::id(),
            'subject' => $request->subject,
            'message' => $request->message,
            'status' => 'pending',
        ]);

        return back()->with('success', 'Komplain Anda telah dikirim ke Admin. Mohon tunggu respon kami.');
    }
}