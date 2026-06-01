<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Designer;
use Illuminate\Http\Request;

class DesignerController extends Controller
{
    public function index()
    {
        // Get all designers with their user details and portfolios (including reviews)
        $designers = Designer::with(['user', 'portfolios' => function ($q) {
            $q->where('status', 'approved')->with('consultation.review.customer');
        }])->get();

        return response()->json([
            'success' => true,
            'message' => 'List of all designers',
            'data' => $designers->map(function ($designer) {
                return [
                    'id' => $designer->id,
                    'studio_name' => $designer->studio_name ?? $designer->user->full_name,
                    'full_name' => $designer->user->full_name,
                    'specialty' => $designer->specialty,
                    'bio' => $designer->bio,
                    'experience_years' => $designer->experience_years,
                    'is_open' => $designer->is_open,
                    'education' => $designer->education,
                    'awards' => $designer->awards,
                    'instagram_url' => $designer->instagram_url,
                    'linkedin_url' => $designer->linkedin_url,
                    'projects_completed' => $designer->consultations()->where('status', 4)->count(),
                    'average_project_duration' => $designer->average_project_duration,
                    'rating' => (double) $designer->average_rating,
                    'image' => $designer->designer_image ? asset('storage/' . $designer->designer_image) : 'https://ui-avatars.com/api/?name='.urlencode($designer->user->full_name).'&background=B5733A&color=fff',
                    'banner' => $designer->banner_image ? asset('storage/' . $designer->banner_image) : null,
                    'portfolios' => $designer->portfolios->map(function($portfolio) {
                        return [
                            'id' => $portfolio->id,
                            'title' => $portfolio->title,
                            'image_url' => asset('storage/' . $portfolio->image_url),
                            'description' => $portfolio->description,
                            'category' => $portfolio->category,
                            'budget' => $portfolio->budget,
                            'area' => $portfolio->area,
                            'duration' => $portfolio->duration,
                            'is_360' => $portfolio->is_360,
                            'review' => ($portfolio->consultation && $portfolio->consultation->review) ? [
                                'rating' => $portfolio->consultation->review->rating,
                                'comment' => $portfolio->consultation->review->comment,
                                'customer_name' => $portfolio->consultation->review->customer->full_name,
                                'created_at' => $portfolio->consultation->review->created_at->format('d M Y')
                            ] : null,
                        ];
                    })
                ];
            })
        ]);
    }

    public function show($id)
    {
        $designer = Designer::with(['user', 'portfolios' => function ($q) {
            $q->where('status', 'approved')->with('consultation.review.customer');
        }])->findOrFail($id);

        $freeConsultation = null;
        $canFreeChat = true;
        if (\Illuminate\Support\Facades\Auth::guard('sanctum')->check()) {
            $user = \Illuminate\Support\Facades\Auth::guard('sanctum')->user();
            $customer = \App\Models\Customer::where('user_id', $user->id)->first();
            if ($customer) {
                $fc = \App\Models\FreeConsultation::where('customer_id', $customer->id)
                    ->where('designer_id', $designer->id)
                    ->first();
                if ($fc) {
                    $freeConsultation = [
                        'id' => $fc->id,
                        'expires_at' => $fc->expires_at,
                        'is_completed' => $fc->is_completed,
                        'is_active' => !$fc->is_completed && !$fc->expires_at->isPast(),
                        'time_left' => !$fc->is_completed && !$fc->expires_at->isPast() ? (int) now()->diffInSeconds($fc->expires_at) : 0,
                    ];
                } else {
                    $totalFreeUsed = \App\Models\FreeConsultation::where('customer_id', $customer->id)->count();
                    if ($totalFreeUsed >= 3) {
                        $canFreeChat = false;
                    }
                }
            }
        }

        return response()->json([
            'success' => true,
            'message' => 'Designer detail',
            'data' => [
                'id' => $designer->id,
                'user_id' => $designer->user->id,
                'studio_name' => $designer->studio_name ?? $designer->user->full_name,
                'full_name' => $designer->user->full_name,
                'specialty' => $designer->specialty,
                'bio' => $designer->bio,
                'experience_years' => $designer->experience_years,
                'is_open' => $designer->is_open,
                'education' => $designer->education,
                'awards' => $designer->awards,
                'instagram_url' => $designer->instagram_url,
                'linkedin_url' => $designer->linkedin_url,
                'projects_completed' => $designer->consultations()->where('status', 4)->count(),
                'average_project_duration' => $designer->average_project_duration,
                'rating' => (double) $designer->average_rating,
                'image' => $designer->designer_image ? asset('storage/' . $designer->designer_image) : 'https://ui-avatars.com/api/?name='.urlencode($designer->user->full_name).'&background=B5733A&color=fff',
                'banner' => $designer->banner_image ? asset('storage/' . $designer->banner_image) : null,
                'portfolios' => $designer->portfolios->map(function($portfolio) {
                    return [
                        'id' => $portfolio->id,
                        'title' => $portfolio->title,
                        'image_url' => asset('storage/' . $portfolio->image_url),
                        'description' => $portfolio->description,
                        'category' => $portfolio->category,
                        'budget' => $portfolio->budget,
                        'area' => $portfolio->area,
                        'duration' => $portfolio->duration,
                        'is_360' => $portfolio->is_360,
                        'review' => ($portfolio->consultation && $portfolio->consultation->review) ? [
                            'rating' => $portfolio->consultation->review->rating,
                            'comment' => $portfolio->consultation->review->comment,
                            'customer_name' => $portfolio->consultation->review->customer->full_name,
                            'created_at' => $portfolio->consultation->review->created_at->format('d M Y')
                        ] : null,
                    ];
                }),
                'free_consultation' => $freeConsultation,
                'can_free_chat' => $canFreeChat
            ]
        ]);
    }
}