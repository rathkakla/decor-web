<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    public function index()
    {
        $notifications = Auth::user()->notifications()->latest()->paginate(20);

        // Mark as read
        Auth::user()->unreadNotifications->markAsRead();

        $role = Auth::user()->role;

        if ($role == 'admin') {
            return view('Admin.notifications', compact('notifications'));
        } elseif ($role == 'designer') {
            return view('designer.notifications', compact('notifications'));
        } elseif ($role == 'seller') {
            return view('seller.notifications', compact('notifications'));
        } else {
            return view('customer.notifications', compact('notifications'));
        }
    }
}