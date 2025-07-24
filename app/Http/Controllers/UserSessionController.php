<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\UserSession;
use Illuminate\Support\Facades\Auth;

class UserSessionController extends Controller
{
    public function index()
    {
        $sessions = UserSession::where('user_id', Auth::id())->get();
        return view('sessions.index', compact('sessions'));
    }

    public function terminate($id)
    {
        $session = UserSession::findOrFail($id);
        if ($session->user_id == Auth::id()) {
            $session->is_active = false;
            $session->logout_at = now();
            $session->save();
            // Optionally: invalidate session in Laravel
        }
        return redirect()->back()->with('success', 'تم إنهاء الجلسة بنجاح');
    }

    public function securityAlerts()
    {
        // Example: show suspicious logins, multiple device detection, etc.
        $alerts = [];
        return view('sessions.security_alerts', compact('alerts'));
    }
}
