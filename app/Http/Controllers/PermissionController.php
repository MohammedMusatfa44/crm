<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Models\User;

class PermissionController extends Controller
{
    public function index()
    {
        $roles = Role::with('permissions')->get();
        $permissions = Permission::all();
        $users = User::all();
        return view('permissions.index', compact('roles', 'permissions', 'users'));
    }

    public function assign(Request $request, $userId)
    {
        $user = User::findOrFail($userId);
        $request->validate([
            'permissions' => 'required|array',
        ]);
        $user->syncPermissions($request->permissions);
        return redirect()->back()->with('success', 'تم تعيين الصلاحيات للمستخدم');
    }

    public function manage(Request $request)
    {
        $request->validate([
            'role' => 'required',
            'permissions' => 'required|array',
        ]);
        $role = Role::findByName($request->role);
        $role->syncPermissions($request->permissions);
        return redirect()->back()->with('success', 'تم تحديث صلاحيات الدور');
    }
}
