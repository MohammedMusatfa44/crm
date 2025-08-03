<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Notification;
use App\Models\Customer;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Spatie\Permission\Traits\HasRoles;

class NotificationController extends Controller
{
    public function index()
    {
        /** @var \App\Models\User $user */
        $user = auth()->user();

        // Filter notifications based on user role
        if ($user->hasRole('super_admin')) {
            // Super Admin sees all notifications
            $notifications = Notification::with(['user', 'customer'])->get();
        } elseif ($user->hasRole('admin')) {
            // Admin sees only notifications they created
            $notifications = Notification::with(['user', 'customer'])
                ->where('user_id', $user->id)
                ->get();
        } elseif ($user->hasRole('employee')) {
            // Employee sees only notifications they created
            $notifications = Notification::with(['user', 'customer'])
                ->where('user_id', $user->id)
                ->get();
        } else {
            $notifications = collect();
        }

        // Get customers for the dropdown (filtered by role)
        if ($user->hasRole('super_admin')) {
            $customers = Customer::all();
        } elseif ($user->hasRole('admin')) {
            $customers = Customer::where('created_by', $user->id)->get();
        } elseif ($user->hasRole('employee')) {
            $customers = Customer::where('assigned_employee_id', $user->id)->get();
        } else {
            $customers = collect();
        }

        return view('notifications.index', compact('notifications', 'customers'));
    }

    public function create()
    {
        return view('notifications.create');
    }

    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'title' => 'required|string|max:255',
                'message' => 'required|string',
                'customer_id' => 'nullable|exists:customers,id',
                'remind_at' => 'nullable|date|after:now',
            ]);

            // Convert remind_at to the correct timezone if provided
            $remindAt = null;
            if (!empty($validated['remind_at'])) {
                $remindAt = \Carbon\Carbon::parse($validated['remind_at'], 'Asia/Riyadh');
            }

            $notification = Notification::create([
                'title' => $validated['title'],
                'message' => $validated['message'],
                'customer_id' => $validated['customer_id'] ?? null,
                'user_id' => Auth::id(),
                'remind_at' => $remindAt,
                'is_read' => false,
                'is_triggered' => false,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'تم إضافة التنبيه بنجاح'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'حدث خطأ أثناء إضافة التنبيه: ' . $e->getMessage()
            ], 500);
        }
    }

    public function markAsRead($id)
    {
        try {
            /** @var \App\Models\User $user */
            $user = auth()->user();
            $notification = Notification::findOrFail($id);

            // Check if user has permission to mark this notification as read
            if ($user->hasRole('super_admin')) {
                // Super Admin can mark any notification as read
            } elseif ($notification->user_id !== $user->id) {
                return response()->json([
                    'success' => false,
                    'message' => 'ليس لديك صلاحية لتعديل هذا التنبيه'
                ], 403);
            }

            $notification->is_read = true;
            $notification->save();

            return response()->json([
                'success' => true,
                'message' => 'تم تعليم التنبيه كمقروء'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'حدث خطأ أثناء تحديث التنبيه'
            ], 500);
        }
    }

    public function realTime()
    {
        // This would be used for real-time updates (e.g., via Pusher)
        return response()->json(Notification::where('is_read', false)->latest()->take(10)->get());
    }

    public function getTriggeredNotifications()
    {
        /** @var \App\Models\User $user */
        $user = auth()->user();

        // Get triggered notifications for the current user
        $notifications = Notification::where('user_id', $user->id)
            ->where('is_triggered', true)
            ->where('is_read', false)
            ->with(['customer'])
            ->latest()
            ->take(5)
            ->get();

        // Add debugging information
        Log::info('Triggered notifications check', [
            'user_id' => $user->id,
            'user_name' => $user->name,
            'notifications_count' => $notifications->count(),
            'notifications' => $notifications->toArray()
        ]);

        return response()->json([
            'success' => true,
            'notifications' => $notifications,
            'count' => $notifications->count(),
            'debug' => [
                'user_id' => $user->id,
                'user_name' => $user->name,
                'check_time' => now()->format('Y-m-d H:i:s')
            ]
        ]);
    }

    public function destroy($id)
    {
        try {
            /** @var \App\Models\User $user */
            $user = auth()->user();
            $notification = Notification::findOrFail($id);

            // Check if user has permission to delete this notification
            if ($user->hasRole('super_admin')) {
                // Super Admin can delete any notification
            } elseif ($notification->user_id !== $user->id) {
                return response()->json([
                    'success' => false,
                    'message' => 'ليس لديك صلاحية لحذف هذا التنبيه'
                ], 403);
            }

            $notification->delete();

            return response()->json([
                'success' => true,
                'message' => 'تم حذف التنبيه بنجاح'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'حدث خطأ أثناء حذف التنبيه'
            ], 500);
        }
    }
}
