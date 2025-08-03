<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\UserSession;

class AuthController extends Controller
{
    public function showLoginForm()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if (Auth::attempt($credentials, $request->filled('remember'))) {
            $request->session()->regenerate();
            // Track session
            UserSession::create([
                'user_id' => Auth::id(),
                'session_id' => session()->getId(),
                'ip_address' => $request->ip(),
                'mac_address' => null, // You can set this if you have a way to get MAC
                'user_agent' => $request->userAgent(),
                'login_at' => now(),
                'is_active' => true,
            ]);
            return redirect()->intended('/dashboard');
        }
        return back()->with('error', 'بيانات الدخول غير صحيحة');
    }

    public function logout(Request $request)
    {
        // Mark session as inactive
        UserSession::where('session_id', session()->getId())
            ->where('user_id', Auth::id())
            ->update(['is_active' => false, 'logout_at' => now()]);
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/');
    }
}
