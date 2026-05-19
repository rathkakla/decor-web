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
            ->with(['designer.user'])
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
            'budget_range' => 'required|string',
        ]);

        $user = Auth::user();
        $customer = Customer::firstOrCreate(['user_id' => $user->id]);

        $consultation = Consultation::create([
            'customer_id' => $customer->id,
            'designer_id' => $request->designer_id,
            'title' => $request->title,
            'description' => $request->description ?? '',
            'budget_range' => $request->budget_range,
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
                    $consultation->update(['status' => Consultation::STATUS_WAITING_CONSULTATION_FEE]);
                    
                    // Post RAB file to chat history as a message & attachment from the designer
                    $items = is_string($quote->items) ? json_decode($quote->items, true) : $quote->items;
                    if ($items && isset($items['file_path'])) {
                        $filePath = $items['file_path'];
                        
                        // 1. Create text message from designer
                        \App\Models\ConsultationMessage::create([
                            'consultation_id' => $consultation->id,
                            'sender_id' => $consultation->designer->user_id,
                            'message' => "Halo! Project Agreement & RAB telah disetujui. Berikut adalah file RAB resmi untuk proyek kita.",
                        ]);
                        
                        // 2. Create attachment message from designer
                        \App\Models\ConsultationAttachment::create([
                            'consultation_id' => $consultation->id,
                            'uploaded_by' => $consultation->designer->user_id,
                            'file_url' => $filePath,
                            'file_type' => 'document',
                        ]);
                    }
                } elseif ($request->status == 'rejected') {
                    $consultation->update(['status' => Consultation::STATUS_REJECTED]);
                } else { // revision
                    $consultation->update(['status' => Consultation::STATUS_ACTIVE]);
                    
                    $senderId = Auth::id() ?? ($consultation->customer ? $consultation->customer->user_id : null);
                    if ($senderId) {
                        \App\Models\ConsultationMessage::create([
                            'consultation_id' => $consultation->id,
                            'sender_id' => $senderId,
                            'message' => "🔄 Permintaan Revisi RAB:\n\"" . $request->revision_notes . "\"",
                        ]);
                    }
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

    public function pay($id)
    {
        $consultation = Consultation::findOrFail($id);
        
        if ($consultation->status == Consultation::STATUS_WAITING_CONSULTATION_FEE) {
            // Distinguish between initial Fee and Final Project Payment
            $hasAcceptedQuote = $consultation->quotes()->where('status', 'accepted')->exists();
            
            if ($hasAcceptedQuote) {
                // This is the Final Payment
                $status = Consultation::STATUS_COMPLETED;
            } else {
                // This is the Initial Consultation Fee (500k)
                $status = (empty($consultation->description)) 
                    ? Consultation::STATUS_WAITING_BRIEF 
                    : Consultation::STATUS_ACTIVE;
            }
        } else {
            return response()->json(['success' => false, 'message' => 'Invalid status for payment'], 400);
        }

        $consultation->update(['status' => $status]);

        return response()->json([
            'success' => true,
            'message' => 'Payment successful.',
            'data' => $consultation
        ]);
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
}