<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Chat;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ChatController extends Controller
{
    public function getConversations()
    {
        $userId = Auth::id();

        $conversations = Chat::where('sender_id', $userId)
            ->orWhere('receiver_id', $userId)
            ->with(['sender', 'receiver'])
            ->latest()
            ->get()
            ->map(function ($chat) use ($userId) {
                return $chat->sender_id == $userId ? $chat->receiver : $chat->sender;
            })
            ->unique('id')
            ->values();

        return response()->json([
            'status' => 'success',
            'data' => $conversations
        ]);
    }

    public function getMessages($receiverId)
    {
        $senderId = Auth::id();

        $messages = Chat::where(function ($q) use ($senderId, $receiverId) {
                $q->where('sender_id', $senderId)->where('receiver_id', $receiverId);
            })
            ->orWhere(function ($q) use ($senderId, $receiverId) {
                $q->where('sender_id', $receiverId)->where('receiver_id', $senderId);
            })
            ->with('product')
            ->oldest()
            ->get();

        // Mark as read
        Chat::where('sender_id', $receiverId)
            ->where('receiver_id', $senderId)
            ->where('is_read', false)
            ->update(['is_read' => true]);

        return response()->json([
            'status' => 'success',
            'data' => $messages
        ]);
    }
    
    public function sendMessage(Request $request)
    {
        $request->validate([
            'receiver_id' => 'required|exists:users,id',
            'message' => 'required|string',
            'product_id' => 'nullable|exists:products,id'
        ]);

        $chat = Chat::create([
            'sender_id' => Auth::id(),
            'receiver_id' => $request->receiver_id,
            'message' => $request->message,
            'product_id' => $request->product_id,
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Message sent successfully',
            'data' => $chat
        ]);
    }
}