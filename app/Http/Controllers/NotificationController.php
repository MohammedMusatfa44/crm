<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Notification;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    public function index()
    {
        $notifications = Notification::with('user', 'customer')->get();
        return view('notifications.index', compact('notifications'));
    }

    public function create()
    {
        return view('notifications.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string',
            'message' => 'required|string',
            'customer_id' => 'nullable|exists:customers,id',
            'remind_at' => 'nullable|date',
        ]);
        Notification::create([
            'title' => $validated['title'],
            'message' => $validated['message'],
            'customer_id' => $validated['customer_id'] ?? null,
            'user_id' => Auth::id(),
            'remind_at' => $validated['remind_at'] ?? null,
            'is_read' => false,
            'is_triggered' => false,
        ]);
        // Optionally: broadcast real-time notification event here
        return redirect()->route('notifications.index')->with('success', 'تم إضافة التنبيه بنجاح');
    }

    public function markAsRead($id)
    {
        $notification = Notification::findOrFail($id);
        $notification->is_read = true;
        $notification->save();
        return redirect()->back()->with('success', 'تم تعليم التنبيه كمقروء');
    }

    public function realTime()
    {
        // This would be used for real-time updates (e.g., via Pusher)
        return response()->json(Notification::where('is_read', false)->latest()->take(10)->get());
    }

    public function destroy($id)
    {
        $notification = Notification::findOrFail($id);
        $notification->delete();
        return redirect()->route('notifications.index')->with('success', 'تم حذف التنبيه بنجاح');
    }
}
