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

        // Debug: Log user info
        Log::info('NotificationController@index - User info', [
            'user_id' => $user->id,
            'user_name' => $user->name,
            'user_role' => $user->getRoleNames()->first()
        ]);

        // ALL users (super_admin, admin, employee) see only their own notifications
        $notifications = Notification::with(['user', 'customer'])
            ->where('user_id', $user->id)
            ->latest()
            ->get();

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

        // TEMPORARY: Show all customers for testing
        $customers = Customer::all();

        // If no customers exist, create some sample customers for testing
        if ($customers->isEmpty()) {

            $sampleCustomers = [
                ['full_name' => 'أحمد محمد', 'mobile_number' => '0501234567', 'email' => 'ahmed@example.com', 'ac_number' => 'AC001'],
                ['full_name' => 'فاطمة علي', 'mobile_number' => '0502345678', 'email' => 'fatima@example.com', 'ac_number' => 'AC002'],
                ['full_name' => 'محمد حسن', 'mobile_number' => '0503456789', 'email' => 'mohammed@example.com', 'ac_number' => 'AC003'],
            ];

            foreach ($sampleCustomers as $customerData) {
                Customer::create([
                    'ac_number' => $customerData['ac_number'],
                    'full_name' => $customerData['full_name'],
                    'mobile_number' => $customerData['mobile_number'],
                    'email' => $customerData['email'],
                    'created_by' => $user->id,
                    'status' => 'new'
                ]);
            }

            $customers = Customer::all();
        }

        // Debug: Log customers info
        Log::info('NotificationController@index - Customers info', [
            'total_customers' => $customers->count(),
            'user_role' => $user->getRoleNames()->first(),
            'customers_sample' => $customers->take(3)->pluck('full_name', 'id')->toArray()
        ]);

        // Debug: Log notifications info
        Log::info('NotificationController@index - Notifications info', [
            'total_notifications' => $notifications->count(),
            'user_id' => $user->id,
            'user_role' => $user->getRoleNames()->first(),
            'notifications_sample' => $notifications->take(3)->pluck('title', 'id')->toArray()
        ]);

        return view('notifications.index', compact('notifications', 'customers'));
    }

    public function create()
    {
        return view('notifications.create');
    }

    public function show($id)
    {
        try {
            /** @var \App\Models\User $user */
            $user = auth()->user();
            $notification = Notification::with(['user', 'customer'])->findOrFail($id);

            // ALL users can only view their own notifications
            if ($notification->user_id !== $user->id) {
                return response()->json([
                    'success' => false,
                    'message' => 'ليس لديك صلاحية لعرض هذا التنبيه'
                ], 403);
            }

            return response()->json([
                'success' => true,
                'notification' => $notification
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'حدث خطأ أثناء عرض التنبيه'
            ], 500);
        }
    }

    public function edit($id)
    {
        try {
            /** @var \App\Models\User $user */
            $user = auth()->user();
            $notification = Notification::findOrFail($id);

            // ALL users can only edit their own notifications
            if ($notification->user_id !== $user->id) {
                return response()->json([
                    'success' => false,
                    'message' => 'ليس لديك صلاحية لتعديل هذا التنبيه'
                ], 403);
            }

            return response()->json([
                'success' => true,
                'notification' => $notification
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'حدث خطأ أثناء تحميل التنبيه'
            ], 500);
        }
    }

    public function update(Request $request, $id)
    {
        try {
            /** @var \App\Models\User $user */
            $user = auth()->user();
            $notification = Notification::findOrFail($id);

            // ALL users can only update their own notifications
            if ($notification->user_id !== $user->id) {
                return response()->json([
                    'success' => false,
                    'message' => 'ليس لديك صلاحية لتعديل هذا التنبيه'
                ], 403);
            }

            $validated = $request->validate([
                'title' => 'required|string|max:255',
                'message' => 'required|string',
                'customer_id' => 'nullable|exists:customers,id',
                'remind_at' => 'nullable|date',
            ]);

            // Convert remind_at to the correct timezone if provided
            $remindAt = null;
            if (!empty($validated['remind_at'])) {
                $remindAt = \Carbon\Carbon::parse($validated['remind_at'], 'Asia/Riyadh');
            }

            $notification->update([
                'title' => $validated['title'],
                'message' => $validated['message'],
                'customer_id' => $validated['customer_id'] ?? null,
                'remind_at' => $remindAt,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'تم تحديث التنبيه بنجاح'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'حدث خطأ أثناء تحديث التنبيه: ' . $e->getMessage()
            ], 500);
        }
    }

    public function store(Request $request)
    {
        try {
            Log::info('NotificationController@store - Request data', $request->all());

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

            Log::info('NotificationController@store - Notification created', [
                'notification_id' => $notification->id,
                'user_id' => $notification->user_id,
                'title' => $notification->title
            ]);

            return redirect('/notifications')->with('success', 'تم إضافة التنبيه بنجاح');

        } catch (\Exception $e) {
            Log::error('NotificationController@store - Error', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return redirect('/notifications')->with('error', 'حدث خطأ أثناء إضافة التنبيه: ' . $e->getMessage());
        }
    }

    public function markAsRead($id)
    {
        try {
            /** @var \App\Models\User $user */
            $user = auth()->user();
            $notification = Notification::findOrFail($id);

            // ALL users can only mark their own notifications as read
            if ($notification->user_id !== $user->id) {
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

    public function markAsReadByTitle($title)
    {
        try {
            /** @var \App\Models\User $user */
            $user = auth()->user();

            $notification = Notification::where('user_id', $user->id)
                ->where('title', $title)
                ->where('is_read', false)
                ->first();

            if ($notification) {
                $notification->is_read = true;
                $notification->save();
            }

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

    public function trigger($id)
    {
        try {
            /** @var \App\Models\User $user */
            $user = auth()->user();
            $notification = Notification::findOrFail($id);

            // ALL users can only trigger their own notifications
            if ($notification->user_id !== $user->id) {
                return response()->json([
                    'success' => false,
                    'message' => 'ليس لديك صلاحية لتشغيل هذا التنبيه'
                ], 403);
            }

            // For manual triggering, allow regardless of time
            // Only check time for automatic triggering
            // if ($notification->remind_at > now()) {
            //     return response()->json([
            //         'success' => false,
            //         'message' => 'التنبيه لم يحن وقته بعد'
            //     ], 400);
            // }

            // Mark as triggered
            $notification->is_triggered = true;
            $notification->save();

            return response()->json([
                'success' => true,
                'message' => 'تم تشغيل التنبيه بنجاح',
                'notification' => $notification
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'حدث خطأ أثناء تشغيل التنبيه: ' . $e->getMessage()
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
        try {
            $user = auth()->user();

            if (!$user) {
                Log::info('getTriggeredNotifications - No user authenticated');
                return response()->json([
                    'success' => true,
                    'notifications' => [],
                    'count' => 0,
                    'message' => 'No user authenticated'
                ]);
            }

            Log::info('getTriggeredNotifications - User authenticated', [
                'user_id' => $user->id,
                'user_name' => $user->name
            ]);

            // Get notifications that are already triggered and unread
            $triggeredNotifications = Notification::with('customer')
                ->where('user_id', $user->id)
                ->where('is_triggered', true)
                ->where('is_read', false)
                ->select(['id', 'title', 'message', 'remind_at', 'created_at', 'customer_id'])
                ->latest()
                ->take(5)
                ->get();

            // Also check for notifications that have reached their time but haven't been triggered yet
            $timeBasedNotifications = Notification::with('customer')
                ->where('user_id', $user->id)
                ->where('is_triggered', false)
                ->where('is_read', false)
                ->where('remind_at', '<=', now())
                ->select(['id', 'title', 'message', 'remind_at', 'created_at', 'customer_id'])
                ->latest()
                ->take(5)
                ->get();

            // Log for debugging
            Log::info('getTriggeredNotifications - Debug', [
                'user_id' => $user->id,
                'triggered_count' => $triggeredNotifications->count(),
                'time_based_count' => $timeBasedNotifications->count(),
                'current_time' => now()->toDateTimeString(),
                'time_based_notifications' => $timeBasedNotifications->pluck('title', 'remind_at')->toArray()
            ]);

            // Auto-trigger time-based notifications
            foreach ($timeBasedNotifications as $notification) {
                $notification->is_triggered = true;
                $notification->save();

                Log::info('Auto-triggered notification', [
                    'notification_id' => $notification->id,
                    'title' => $notification->title,
                    'remind_at' => $notification->remind_at
                ]);
            }

            // Combine both sets of notifications
            $allNotifications = $triggeredNotifications->concat($timeBasedNotifications)->unique('id');

            Log::info('getTriggeredNotifications - Success', [
                'total_notifications' => $allNotifications->count(),
                'user_id' => $user->id
            ]);

            return response()->json([
                'success' => true,
                'notifications' => $allNotifications,
                'count' => $allNotifications->count(),
                'debug' => [
                    'triggered_count' => $triggeredNotifications->count(),
                    'time_based_count' => $timeBasedNotifications->count(),
                    'total_count' => $allNotifications->count(),
                    'current_time' => now()->toDateTimeString()
                ]
            ]);
        } catch (\Exception $e) {
            Log::error('getTriggeredNotifications - Error', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'user_id' => auth()->user() ? auth()->user()->id : null
            ]);

            return response()->json([
                'success' => false,
                'message' => 'حدث خطأ أثناء عرض التنبيه: ' . $e->getMessage(),
                'notifications' => [],
                'count' => 0
            ], 500);
        }
    }

    public function getNotificationCount()
    {
        try {
            $user = auth()->user();

            Log::info('getNotificationCount - User check', [
                'user_id' => $user ? $user->id : null,
                'authenticated' => $user ? true : false
            ]);

            if (!$user) {
                Log::info('getNotificationCount - No user authenticated');
                return response()->json([
                    'success' => true,
                    'count' => 0
                ]);
            }

            // Count triggered and unread notifications
            $triggeredCount = Notification::where('user_id', $user->id)
                ->where('is_triggered', true)
                ->where('is_read', false)
                ->count();

            // Count notifications that have reached their time but haven't been triggered
            $timeBasedCount = Notification::where('user_id', $user->id)
                ->where('is_triggered', false)
                ->where('is_read', false)
                ->where('remind_at', '<=', now())
                ->count();

            $totalCount = $triggeredCount + $timeBasedCount;

            Log::info('getNotificationCount - Found count', [
                'user_id' => $user->id,
                'triggered_count' => $triggeredCount,
                'time_based_count' => $timeBasedCount,
                'total_count' => $totalCount
            ]);

            return response()->json([
                'success' => true,
                'count' => $totalCount
            ]);
        } catch (\Exception $e) {
            Log::error('getNotificationCount - Exception', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'success' => true,
                'count' => 0
            ]);
        }
    }

    public function testSession()
    {
        try {
            $user = auth()->user();

            return response()->json([
                'success' => true,
                'authenticated' => $user ? true : false,
                'user_id' => $user ? $user->id : null,
                'user_name' => $user ? $user->name : null,
                'session_id' => session()->getId()
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function forceTrigger()
    {
        try {
            /** @var \App\Models\User $user */
            $user = auth()->user();

            if (!$user) {
                return response()->json([
                    'success' => false,
                    'message' => 'User not authenticated'
                ]);
            }

            // Force trigger all unread notifications for the current user
            $notifications = Notification::where('user_id', $user->id)
                ->where('is_read', false)
                ->where('is_triggered', false)
                ->get();

            foreach ($notifications as $notification) {
                $notification->is_triggered = true;
                $notification->save();
            }

            return response()->json([
                'success' => true,
                'message' => 'تم تفعيل ' . $notifications->count() . ' تنبيه بنجاح'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'حدث خطأ أثناء إجبار التفعيل: ' . $e->getMessage()
            ], 500);
        }
    }

    public function destroy($id)
    {
        try {
            /** @var \App\Models\User $user */
            $user = auth()->user();
            $notification = Notification::findOrFail($id);

            // ALL users can only delete their own notifications
            if ($notification->user_id !== $user->id) {
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
