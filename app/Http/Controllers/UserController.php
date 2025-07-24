<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\UserSession;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class UserController extends Controller
{
    public function index()
    {
        $users = User::all();
        return view('users.index', compact('users'));
    }

    public function create()
    {
        $roles = Role::all();
        return view('users.create', compact('roles'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:6',
            'role' => 'required',
            'phone' => 'nullable',
        ]);
        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'role' => $validated['role'],
            'phone' => $validated['phone'],
            'is_active' => true,
        ]);
        $user->assignRole($validated['role']);
        return redirect()->route('users.index')->with('success', 'تم إضافة المستخدم بنجاح');
    }

    public function edit($id)
    {
        $user = User::findOrFail($id);
        $roles = Role::all();
        return view('users.edit', compact('user', 'roles'));
    }

    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);
        $validated = $request->validate([
            'name' => 'required|string',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'password' => 'nullable|min:6',
            'role' => 'required',
            'phone' => 'nullable',
        ]);
        $user->name = $validated['name'];
        $user->email = $validated['email'];
        if (!empty($validated['password'])) {
            $user->password = Hash::make($validated['password']);
        }
        $user->role = $validated['role'];
        $user->phone = $validated['phone'];
        $user->save();
        $user->syncRoles([$validated['role']]);
        return redirect()->route('users.index')->with('success', 'تم تحديث المستخدم بنجاح');
    }

    public function destroy($id)
    {
        $user = User::findOrFail($id);
        $user->delete();
        return redirect()->route('users.index')->with('success', 'تم حذف المستخدم بنجاح');
    }

    public function toggleStatus($id)
    {
        $user = User::findOrFail($id);
        $user->is_active = !$user->is_active;
        $user->save();
        return redirect()->back()->with('success', 'تم تغيير حالة المستخدم');
    }

    public function sessions($id)
    {
        $user = User::findOrFail($id);
        $sessions = UserSession::where('user_id', $user->id)->get();
        return view('sessions.index', compact('sessions'));
    }
}
