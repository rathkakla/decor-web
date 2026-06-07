<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Designer;
use App\Models\Seller;
use App\Models\Customer;
use App\Models\Support;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function dashboard()
    {
        // 1. Stats Utama
        $totalSales = \App\Models\Order::sum('total_price');
        $totalConsultations = \App\Models\ConsultationQuote::where('status', 'accepted')->sum('amount');
        
        $adminFees = ($totalSales + $totalConsultations) * 0.05;
        
        $stats = [
            'total_fees'    => $adminFees,
            'active_sellers' => \App\Models\Seller::count(),
            'active_designers' => \App\Models\Designer::count(),
            'total_transactions' => \App\Models\Order::count() + \App\Models\Consultation::where('status', \App\Models\Consultation::STATUS_COMPLETED)->count(),
        ];

        // 2. Data Grafik (Dummy 8 bulan terakhir)
        $revenueData = [1.2, 2.1, 1.8, 2.8, 2.2, 3.5, 3.2, 4.5]; // Dalam juta
        
        // 3. Transaksi Produk Terbaru
        $recentSellerTransactions = \App\Models\OrderItem::with(['product.seller.user', 'order'])
            ->latest()
            ->take(2)
            ->get();
            
        // 4. Transaksi Desainer Terbaru
        $recentDesignerTransactions = \App\Models\ConsultationQuote::with(['consultation.designer.user'])
            ->where('status', 'accepted')
            ->latest()
            ->take(2)
            ->get();

        return view('Admin.dashboard', compact('stats', 'revenueData', 'recentSellerTransactions', 'recentDesignerTransactions'));
    }

    public function userManagement(Request $request)
    {
        $roleFilter = $request->get('role');

        $query = User::query();

        if ($roleFilter && $roleFilter !== 'semua') {
            $query->where('role', strtolower($roleFilter));
        }

        if ($request->has('search')) {
            $search = $request->get('search');
            $query->where(function ($q) use ($search) {
                $q->where('full_name', 'like', "%$search%")
                    ->orWhere('email', 'like', "%$search%");
            });
        }

        $users = $query->latest()->get();

        $stats = [
            'total' => User::count(),
            'designers' => User::where('role', 'designer')->count(),
            'sellers' => User::where('role', 'seller')->count(),
            'customers' => User::where('role', 'customer')->count(),
        ];

        return view('Admin.user-management', compact('users', 'stats', 'roleFilter'));
    }

    public function warnUser(Request $request, $id)
    {
        $user = User::findOrFail($id);
        $message = $request->get('message', 'Anda telah melakukan pelanggaran peraturan platform.');
        
        // Notify the user
        $user->notify(new \App\Notifications\ViolationWarning($message));
        
        return back()->with('success', 'Warning notification has been sent to ' . $user->full_name);
    }

    public function deleteUser($id)
    {
        $user = User::findOrFail($id);
        $user->delete();
        return back()->with('success', 'User has been banned and deleted from the platform.');
    }

    public function productValidation(Request $request)
    {
        $statusFilter = $request->get('status', 'semua');
        
        $query = \App\Models\Product::with(['seller.user', 'category']);
        
        if ($statusFilter && $statusFilter !== 'semua') {
            $query->where('status', $statusFilter);
        }
        
        $products = $query->latest()->get();
        
        $stats = [
            'pending' => \App\Models\Product::where('status', 'pending')->count(),
            'approved' => \App\Models\Product::where('status', 'approved')->count(),
            'rejected' => \App\Models\Product::where('status', 'rejected')->count(),
        ];
        
        return view('Admin.product-validation', compact('products', 'stats', 'statusFilter'));
    }

    public function approveProduct($id)
    {
        $product = \App\Models\Product::findOrFail($id);
        $product->update(['status' => 'approved']);
        
        return back()->with('success', 'Produk ' . $product->name . ' telah disetujui dan dipublikasikan.');
    }

    public function rejectProduct(Request $request, $id)
    {
        $request->validate([
            'message' => 'required|string|max:1000',
        ], [
            'message.required' => 'Alasan penolakan harus diisi.'
        ]);

        $product = \App\Models\Product::findOrFail($id);
        $product->update(['status' => 'rejected']);
        
        // Notify seller
        $message = $request->get('message');
        $product->seller->user->notify(new \App\Notifications\ViolationWarning($message));
        
        return back()->with('success', 'Produk ' . $product->name . ' telah ditolak.');
    }

    public function sellerMonitor(Request $request)
    {
        $sellers = Seller::with(['user'])->get()->map(function($seller) {
            // Get orders for this seller's products
            $orders = \App\Models\Order::whereHas('orderItems.product', function($q) use ($seller) {
                $q->where('seller_id', $seller->id);
            })->where('status', 'completed');

            $totalSales = $orders->sum('total_price');
            $transactionCount = $orders->count();
            $lastOrder = $orders->latest()->first();
            $lastTransactionDate = $lastOrder ? $lastOrder->created_at : null;
            
            // Status logic: Warning if no transactions in 30 days
            $status = 'Active';
            if (!$lastTransactionDate || $lastTransactionDate->diffInDays(now()) > 30) {
                $status = 'Warning';
            }
            
            // If seller is actually banned (not in DB yet, but let's assume is_active check)
            // if (!$seller->user->is_active) $status = 'Banned';

            return (object)[
                'id' => 'SEL-' . str_pad($seller->id, 3, '0', STR_PAD_LEFT),
                'db_id' => $seller->id,
                'user_id' => $seller->user_id,
                'name' => $seller->store_name,
                'owner' => $seller->user->full_name,
                'total_sales' => $totalSales,
                'transactions' => $transactionCount,
                'rating' => $seller->rating ?? 0.0,
                'status' => $status,
                'last_active' => $lastTransactionDate ? $lastTransactionDate->diffForHumans() : 'No transactions',
                'joined' => $seller->created_at->format('M Y'),
                'admin_fee' => $totalSales * 0.05,
            ];
        });

        $stats = [
            'total' => $sellers->count(),
            'active' => $sellers->where('status', 'Active')->count(),
            'warning' => $sellers->where('status', 'Warning')->count(),
            'banned' => $sellers->where('status', 'Banned')->count(),
        ];

        return view('Admin.seller-monitor', compact('sellers', 'stats'));
    }

    public function designerMonitor(Request $request)
    {
        $designers = Designer::with(['user', 'portfolios'])->get()->map(function($designer) {
            // Get consultation stats
            $consultations = \App\Models\Consultation::where('designer_id', $designer->id);
            $projectCount = $consultations->where('status', 'done')->count();
            
            // Revenue from accepted quotes
            $revenue = \App\Models\ConsultationQuote::whereHas('consultation', function($q) use ($designer) {
                $q->where('designer_id', $designer->id);
            })->where('status', 'accepted')->sum('amount');

            return (object)[
                'id' => 'DES-' . str_pad($designer->id, 3, '0', STR_PAD_LEFT),
                'db_id' => $designer->id,
                'name' => $designer->user->full_name,
                'spec' => $designer->specialty,
                'projects' => $projectCount,
                'rate' => 0, // Placeholder
                'rating' => 4.9, // Placeholder
                'status' => $designer->status === 'approved' ? 'Verified' : ucfirst($designer->status),
                'revenue' => 'Rp ' . number_format($revenue / 1000000, 1) . 'M',
                'joined' => $designer->created_at->format('M Y'),
            ];
        });

        $stats = [
            ['label' => 'Total Designers', 'value' => $designers->count(), 'icon' => 'pen-tool', 'note' => 'registered'],
            ['label' => 'Verified', 'value' => $designers->where('status', 'Verified')->count(), 'icon' => 'check-circle', 'note' => 'active partners'],
            ['label' => 'Suspended', 'value' => $designers->where('status', 'Suspended')->count(), 'icon' => 'pause-circle', 'note' => 'under review'],
            ['label' => 'Banned', 'value' => $designers->where('status', 'Banned')->count(), 'icon' => 'x-circle', 'note' => 'removed'],
        ];

        return view('Admin.designer-monitor', compact('designers', 'stats'));
    }
    public function accountValidation(Request $request)
    {
        $type = $request->get('type', 'seller'); // 'seller' or 'designer'
        $status = $request->get('status', 'pending');

        if ($type === 'seller') {
            $accounts = Seller::with('user')->where('status', $status)->latest()->get();
        } else {
            $accounts = Designer::with('user')->where('status', $status)->latest()->get();
        }

        $stats = [
            'pending_sellers' => Seller::where('status', 'pending')->count(),
            'pending_designers' => Designer::where('status', 'pending')->count(),
        ];

        return view('Admin.account-validation', compact('accounts', 'stats', 'type', 'status'));
    }

    public function approveAccount(Request $request, $id)
    {
        $type = $request->get('type');
        if ($type === 'seller') {
            $account = Seller::findOrFail($id);
        } else {
            $account = Designer::findOrFail($id);
        }

        $account->update(['status' => 'approved', 'rejection_reason' => null]);

        return back()->with('success', 'Account has been approved successfully.');
    }

    public function rejectAccount(Request $request, $id)
    {
        $request->validate([
            'type' => 'required|in:seller,designer',
            'reason' => 'required|string|max:500'
        ]);

        if ($request->type === 'seller') {
            $account = Seller::findOrFail($id);
        } else {
            $account = Designer::findOrFail($id);
        }

        $account->update([
            'status' => 'rejected',
            'rejection_reason' => $request->reason
        ]);

        return redirect()->route('admin.account.validation', ['type' => $request->type])->with('success', 'Akun berhasil ditolak.');
    }

    public function sellerDetail($id)
    {
        $seller = Seller::with(['user', 'products.images'])->findOrFail($id);
        return view('Admin.seller-detail', compact('seller'));
    }

    public function designerDetail($id)
    {
        $designer = Designer::with(['user', 'portfolios'])->findOrFail($id);
        return view('Admin.designer-detail', compact('designer'));
    }
    public function customerSupport(Request $request)
    {
        $statusFilter = $request->get('status', 'semua');
        $search = $request->get('search');

        $query = Support::whereHas('user', function($q) {
            $q->where('role', 'customer');
        })->with('user');

        if ($statusFilter && $statusFilter !== 'semua') {
            $query->where('status', $statusFilter);
        }

        if ($search) {
            $query->where(function($q) use ($search) {
                $q->where('subject', 'like', "%$search%")
                  ->orWhere('message', 'like', "%$search%")
                  ->orWhereHas('user', function($uq) use ($search) {
                      $uq->where('full_name', 'like', "%$search%");
                  });
            });
        }

        $tickets = $query->latest()->get();

        $stats = [
            'open' => Support::whereHas('user', fn($q) => $q->where('role', 'customer'))->where('status', 'pending')->count(),
            'replied' => Support::whereHas('user', fn($q) => $q->where('role', 'customer'))->where('status', 'replied')->count(),
            'resolved' => Support::whereHas('user', fn($q) => $q->where('role', 'customer'))->where('status', 'resolved')->count(),
        ];

        return view('Admin.customer-support', compact('tickets', 'stats', 'statusFilter'));
    }

    public function sellerSupport(Request $request)
    {
        $statusFilter = $request->get('status', 'semua');
        $search = $request->get('search');
        
        $query = Support::whereHas('user', function($q) {
            $q->where('role', 'seller');
        })->with('user');

        if ($statusFilter && $statusFilter !== 'semua') {
            $query->where('status', $statusFilter);
        }

        if ($search) {
            $query->where(function($q) use ($search) {
                $q->where('subject', 'like', "%$search%")
                  ->orWhere('message', 'like', "%$search%")
                  ->orWhereHas('user', function($uq) use ($search) {
                      $uq->where('full_name', 'like', "%$search%");
                  });
            });
        }

        $tickets = $query->latest()->get();

        $stats = [
            'open' => Support::whereHas('user', fn($q) => $q->where('role', 'seller'))->where('status', 'pending')->count(),
            'replied' => Support::whereHas('user', fn($q) => $q->where('role', 'seller'))->where('status', 'replied')->count(),
            'resolved' => Support::whereHas('user', fn($q) => $q->where('role', 'seller'))->where('status', 'resolved')->count(),
        ];

        return view('Admin.seller-support', compact('tickets', 'stats', 'statusFilter'));
    }

    public function designerSupport(Request $request)
    {
        $statusFilter = $request->get('status', 'semua');
        $search = $request->get('search');
        
        $query = Support::whereHas('user', function($q) {
            $q->where('role', 'designer');
        })->with('user');

        if ($statusFilter && $statusFilter !== 'semua') {
            $query->where('status', $statusFilter);
        }

        if ($search) {
            $query->where(function($q) use ($search) {
                $q->where('subject', 'like', "%$search%")
                  ->orWhere('message', 'like', "%$search%")
                  ->orWhereHas('user', function($uq) use ($search) {
                      $uq->where('full_name', 'like', "%$search%");
                  });
            });
        }

        $tickets = $query->latest()->get();

        $stats = [
            'open' => Support::whereHas('user', fn($q) => $q->where('role', 'designer'))->where('status', 'pending')->count(),
            'replied' => Support::whereHas('user', fn($q) => $q->where('role', 'designer'))->where('status', 'replied')->count(),
            'resolved' => Support::whereHas('user', fn($q) => $q->where('role', 'designer'))->where('status', 'resolved')->count(),
        ];

        return view('Admin.designer-support', compact('tickets', 'stats', 'statusFilter'));
    }

    public function replySupport(Request $request, $id)
    {
        $support = Support::findOrFail($id);
        $support->update([
            'admin_reply' => $request->reply,
            'status' => 'replied'
        ]);

        return back()->with('success', 'Balasan berhasil dikirim!');
    }

    public function updateSupportStatus(Request $request, $id)
    {
        $support = Support::findOrFail($id);
        $support->update(['status' => $request->status]);

        return response()->json(['success' => true]);
    }

    public function portfolioValidation(Request $request)
    {
        $statusFilter = $request->get('status', 'pending'); // Default to pending

        $query = \App\Models\DesignerPortfolio::with(['designer.user']);

        if ($statusFilter && $statusFilter !== 'semua') {
            $query->where('status', $statusFilter);
        }

        $portfolios = $query->latest()->get();

        $stats = [
            'pending' => \App\Models\DesignerPortfolio::where('status', 'pending')->count(),
            'approved' => \App\Models\DesignerPortfolio::where('status', 'approved')->count(),
            'rejected' => \App\Models\DesignerPortfolio::where('status', 'rejected')->count(),
            'total' => \App\Models\DesignerPortfolio::count(),
        ];

        return view('Admin.portofolio-validation', compact('portfolios', 'stats', 'statusFilter'));
    }

    public function approvePortfolio($id)
    {
        $portfolio = \App\Models\DesignerPortfolio::findOrFail($id);
        $portfolio->update(['status' => 'approved']);

        return back()->with('success', 'Portofolio telah disetujui dan dipublikasikan.');
    }

    public function rejectPortfolio(Request $request, $id)
    {
        $portfolio = \App\Models\DesignerPortfolio::findOrFail($id);
        $portfolio->update(['status' => 'rejected']);

        return back()->with('success', 'Portofolio telah ditolak.');
    }

    public function updateSettings(Request $request)
    {
        $user = auth()->user();

        $request->validate([
            'full_name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'current_password' => 'nullable|required_with:new_password|string',
            'new_password' => 'nullable|min:8|confirmed',
        ], [
            'current_password.required_with' => 'Password saat ini wajib diisi jika Anda ingin mengganti password baru.',
            'new_password.min' => 'Password baru minimal harus terdiri dari 8 karakter.',
            'new_password.confirmed' => 'Konfirmasi password baru tidak sesuai.',
        ]);

        if ($request->filled('new_password')) {
            if (!\Illuminate\Support\Facades\Hash::check($request->current_password, $user->password)) {
                return back()->withErrors(['current_password' => 'Password saat ini salah.']);
            }
            $user->password = \Illuminate\Support\Facades\Hash::make($request->new_password);
        }

        $user->full_name = $request->full_name;
        $user->email = $request->email;
        $user->save();

        return back()->with('success', 'Profil berhasil diperbarui.');
    }
}