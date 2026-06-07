<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Consultation;
use App\Models\ConsultationAttachment;
use App\Models\ConsultationMessage;
use App\Models\Customer;
use App\Models\Designer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class ConsultationController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $customer = Customer::where('user_id', $user->id)->first();
        
        if (!$customer) {
            return response()->json(['success' => false, 'message' => 'Customer not found'], 404);
        }

        $consultations = Consultation::where('customer_id', $customer->id)
            ->with(['designer.user', 'messages', 'quotes', 'attachments'])
            ->latest()
            ->get();

        return response()->json([
            'success' => true,
            'data' => $consultations
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'designer_id' => 'required|exists:designers,id',
            'title' => 'required|string|max:150',
            'description' => 'nullable|string',
            'budget_range' => 'required_if:consultation_type,request_proposal|nullable|string',
            'consultation_type' => 'nullable|string',
        ]);

        $user = Auth::user();
        $customer = Customer::firstOrCreate(['user_id' => $user->id]);

        $consultation = Consultation::create([
            'customer_id' => $customer->id,
            'designer_id' => $request->designer_id,
            'title' => $request->title,
            'description' => $request->description ?? '',
            'budget_range' => $request->budget_range ?? '-',
            'consultation_type' => $request->consultation_type ?? 'chat_consultation',
            'status' => Consultation::STATUS_WAITING_APPROVAL,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Consultation requested. Waiting for designer approval.',
            'data' => $consultation
        ], 201);
    }

    public function show($id)
    {
        $consultation = Consultation::with(['designer.user', 'messages.sender', 'attachments', 'quotes'])->findOrFail($id);

        return response()->json([
            'success' => true,
            'data' => $consultation
        ]);
    }

    public function respondToQuote(Request $request, $id)
    {
        try {
            $request->validate([
                'status' => 'required|in:accepted,rejected,revision',
                'revision_notes' => 'nullable|string'
            ]);

            $quote = \App\Models\ConsultationQuote::findOrFail($id);
            
            $updateData = ['status' => $request->status];
            if ($request->status == 'revision') {
                $updateData['revision_notes'] = $request->revision_notes;
            }
            $quote->update($updateData);

            $consultation = Consultation::find($quote->consultation_id);
            if ($consultation) {
                if ($request->status == 'accepted') {
                    $consultation->update(['status' => Consultation::STATUS_WAITING_FINAL_PAYMENT]);
                    
                    // Post RAB file to chat history as a message & attachment from the designer
                    $items = is_string($quote->items) ? json_decode($quote->items, true) : $quote->items;
                    if ($items && isset($items['file_path'])) {
                        $filePath = $items['file_path'];
                        
                        // System messages and attachments removed to keep chat pure
                    }
                } elseif ($request->status == 'rejected') {
                    $consultation->update(['status' => Consultation::STATUS_REJECTED]);
                } else { // revision
                    $consultation->update(['status' => Consultation::STATUS_ACTIVE]);
                }
            }

            return response()->json([
                'success' => true,
                'message' => 'Quote ' . $request->status . ' successfully.',
                'data' => $quote
            ]);
        } catch (\Throwable $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ], 500);
        }
    }

    public function sendMessage(Request $request, $id)
    {
        $request->validate([
            'message' => 'required|string',
        ]);

        $consultation = Consultation::findOrFail($id);
        if ($consultation->is_chat_expired) {
            return response()->json(['success' => false, 'message' => 'Waktu chat konsultasi telah habis.'], 400);
        }

        $message = ConsultationMessage::create([
            'consultation_id' => $id,
            'sender_id' => Auth::id(),
            'message' => $request->message,
        ]);

        return response()->json([
            'success' => true,
            'data' => $message->load('sender')
        ]);
    }

    public function uploadAttachment(Request $request, $id)
    {
        $request->validate([
            'file' => 'required|file|max:10240', // 10MB
            'file_type' => 'required|string',
        ]);

        $consultation = Consultation::findOrFail($id);
        if ($consultation->is_chat_expired) {
            return response()->json(['success' => false, 'message' => 'Waktu chat konsultasi telah habis.'], 400);
        }

        if ($request->hasFile('file')) {
            $path = $request->file('file')->store('consultations/attachments', 'public');
            
            $attachment = ConsultationAttachment::create([
                'consultation_id' => $id,
                'uploaded_by' => Auth::id(),
                'file_url' => $path,
                'file_type' => $request->file_type,
            ]);

            return response()->json([
                'success' => true,
                'data' => $attachment
            ]);
        }

        return response()->json(['success' => false, 'message' => 'No file uploaded'], 400);
    }

    public function pay(Request $request, $id)
    {
        $request->validate([
            'payment_proof' => 'required|file|image|max:10240',
        ]);

        $consultation = Consultation::findOrFail($id);
        
        if ($consultation->status != Consultation::STATUS_WAITING_CONSULTATION_FEE && 
            $consultation->status != Consultation::STATUS_WAITING_FINAL_PAYMENT) {
            return response()->json(['success' => false, 'message' => 'Invalid status for payment'], 400);
        }

        if ($request->hasFile('payment_proof')) {
            $path = $request->file('payment_proof')->store('payment_proofs', 'public');
            $consultation->payment_proof = $path;
            $consultation->save();

            $priceText = "";
            if ($consultation->status == Consultation::STATUS_WAITING_CONSULTATION_FEE) {
                $priceText = "fee sebesar Rp " . number_format($consultation->consultation_fee, 0, ',', '.');
            } else {
                $quote = $consultation->quotes()->latest()->first();
                $amount = $quote ? $quote->amount : 0;
                $priceText = "proyek sebesar Rp " . number_format($amount, 0, ',', '.');
            }

            // System message and attachment creation removed

            return response()->json([
                'success' => true,
                'message' => 'Bukti pembayaran berhasil diunggah. Menunggu validasi desainer.',
                'data' => $consultation
            ]);
        }

        return response()->json(['success' => false, 'message' => 'Gagal mengunggah bukti pembayaran'], 400);
    }

    public function submitBrief(Request $request, $id)
    {
        $request->validate([
            'description' => 'required|string',
        ]);

        $consultation = Consultation::findOrFail($id);
        
        if ($consultation->status != Consultation::STATUS_WAITING_BRIEF) {
            return response()->json(['success' => false, 'message' => 'Invalid status for brief submission'], 400);
        }

        $consultation->update([
            'description' => $request->description,
            'status' => Consultation::STATUS_ACTIVE,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Brief submitted successfully.',
            'data' => $consultation
        ]);
    }

    public function downloadInvoice($id)
    {
        $consultation = Consultation::with(['designer.user', 'customer.user', 'quotes'])->findOrFail($id);
        
        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('invoices.consultation', compact('consultation'));
        
        return $pdf->download('invoice-consultation-' . $id . '.pdf');
    }

    public function downloadRab($quote_id)
    {
        $quote = \App\Models\ConsultationQuote::findOrFail($quote_id);
        
        $items = is_string($quote->items) ? json_decode($quote->items, true) : $quote->items;
        if ($items && isset($items['file_path'])) {
            $filePath = $items['file_path'];
            $fileName = $items['file_name'] ?? 'RAB-Project-' . $quote->consultation_id;
            
            if (\Illuminate\Support\Facades\Storage::disk('public')->exists($filePath)) {
                return \Illuminate\Support\Facades\Storage::disk('public')->download($filePath, $fileName);
            }
        }
        
        return response()->json(['success' => false, 'message' => 'File RAB tidak ditemukan.'], 404);
    }

    public function startFreeChat($id)
    {
        $designer = \App\Models\Designer::with('user')->findOrFail($id);
        $customer = \App\Models\Customer::where('user_id', \Illuminate\Support\Facades\Auth::id())->first();
        
        if (!$customer) {
            return response()->json(['success' => false, 'message' => 'Customer profile not found'], 404);
        }

        $freeConsultation = \App\Models\FreeConsultation::where('customer_id', $customer->id)
            ->where('designer_id', $id)
            ->first();
            
        if (!$freeConsultation) {
            $totalFreeUsed = \App\Models\FreeConsultation::where('customer_id', $customer->id)->count();
            if ($totalFreeUsed >= 3) {
                return response()->json([
                    'success' => false,
                    'message' => 'Anda telah mencapai batas maksimal 3x Free Consultation.',
                ], 403);
            }

            $freeConsultation = \App\Models\FreeConsultation::create([
                'customer_id' => $customer->id,
                'designer_id' => $id,
                'expires_at' => now()->addMinutes(10),
            ]);
        }
        
        if ($freeConsultation->is_completed || $freeConsultation->expires_at->isPast()) {
            if (!$freeConsultation->is_completed) {
                $freeConsultation->update(['is_completed' => true]);
            }
            return response()->json([
                'success' => false,
                'message' => 'Free consultation has expired or completed.',
                'data' => $freeConsultation
            ]);
        }
        
        $timeLeft = (int) now()->diffInSeconds($freeConsultation->expires_at);

        return response()->json([
            'success' => true,
            'message' => 'Free consultation active.',
            'data' => [
                'free_consultation' => $freeConsultation,
                'time_left' => $timeLeft
            ]
        ]);
    }

    public function submitReview(Request $request, $id)
    {
        $request->validate([
            'rating'           => 'required|integer|min:1|max:5',
            'comment'          => 'required|string',
            'project_duration' => 'required|string|max:100',
        ]);

        $consultation = \App\Models\Consultation::with('customer')->findOrFail($id);
        $customer = \App\Models\Customer::where('user_id', Auth::id())->first();
        
        if (!$customer || $consultation->customer_id !== $customer->id) {
            return response()->json(['success' => false, 'message' => 'Unauthorized access.'], 403);
        }

        if ($consultation->status !== \App\Models\Consultation::STATUS_COMPLETED) {
            return response()->json(['success' => false, 'message' => 'Hanya konsultasi yang sudah selesai yang dapat diulas.'], 400);
        }

        if (\App\Models\ConsultationReview::where('consultation_id', $id)->exists()) {
            return response()->json(['success' => false, 'message' => 'Anda sudah memberikan ulasan untuk konsultasi ini.'], 400);
        }

        $review = \App\Models\ConsultationReview::create([
            'consultation_id'  => $id,
            'customer_id'      => Auth::id(),
            'designer_id'      => $consultation->designer_id,
            'rating'           => $request->rating,
            'comment'          => $request->comment,
            'project_duration' => $request->project_duration,
        ]);

        $portfolio = \App\Models\DesignerPortfolio::where('consultation_id', $id)->first();
        if ($portfolio) {
            $portfolio->update([
                'duration' => $request->project_duration
            ]);
        }

        return response()->json([
            'success' => true,
            'message' => 'Terima kasih! Ulasan Anda berhasil dikirim.',
            'data' => $review
        ]);
    }
}