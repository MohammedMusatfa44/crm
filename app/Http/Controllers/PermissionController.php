<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Models\User;
use Illuminate\Support\Facades\Log;
use Spatie\Permission\Traits\HasRoles;

class PermissionController extends Controller
{
    public function index()
    {
        /** @var \App\Models\User $user */
        $user = auth()->user();

        // Filter roles based on user role
        if ($user->hasRole('super_admin')) {
            // Super Admin can see all roles
            $roles = Role::with('permissions')->get();
        } elseif ($user->hasRole('admin')) {
            // Admin can only see employee role
            $roles = Role::with('permissions')->where('name', 'employee')->get();
        } else {
            // Other users see no roles
            $roles = collect();
        }

        $permissions = Permission::all();

        // Filter users based on role
        if ($user->hasRole('super_admin')) {
            // Super Admin can see all users
            $users = User::all();
        } elseif ($user->hasRole('admin')) {
            // Admin can only see employees (not other admins or super_admins)
            $users = User::whereHas('roles', function($query) {
                $query->where('name', 'employee');
            })->get();
        } else {
            // Other users see no users
            $users = collect();
        }

        return view('permissions.index', compact('roles', 'permissions', 'users'));
    }

    public function assign(Request $request)
    {
        try {
            $request->validate([
                'user_id' => 'required|exists:users,id',
                'role_id' => 'required|exists:roles,id',
            ]);

            $user = User::findOrFail($request->user_id);
            $role = Role::findOrFail($request->role_id);
            /** @var \App\Models\User $currentUser */
            $currentUser = auth()->user();

            // Check if user is trying to assign super_admin role
            if ($role->name === 'super_admin' && !$currentUser->hasRole('super_admin')) {
                return response()->json([
                    'success' => false,
                    'message' => 'فقط مدير النظام يمكنه تعيين دور مدير عام'
                ], 403);
            }

            // Check if admin is trying to assign admin role
            if ($role->name === 'admin' && $currentUser->hasRole('admin') && !$currentUser->hasRole('super_admin')) {
                return response()->json([
                    'success' => false,
                    'message' => 'لا يمكن للمدير تعيين دور مدير'
                ], 403);
            }

            // Assign the role to the user (this will replace any existing roles)
            $user->syncRoles([$role->name]);

            return response()->json([
                'success' => true,
                'message' => 'تم تعيين الدور للمستخدم بنجاح'
            ]);
        } catch (\Exception $e) {
            Log::error('Role assignment error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'حدث خطأ أثناء تعيين الدور: ' . $e->getMessage()
            ], 500);
        }
    }

    public function storeRole(Request $request)
    {
        try {
            /** @var \App\Models\User $user */
            $user = auth()->user();

            // Only super_admin can create roles
            if (!$user->hasRole('super_admin')) {
                return response()->json([
                    'success' => false,
                    'message' => 'فقط مدير النظام يمكنه إنشاء أدوار جديدة'
                ], 403);
            }

            $request->validate([
                'role_name' => 'required|string|unique:roles,name',
                'permissions' => 'required|array',
            ]);

            $role = Role::create(['name' => $request->role_name]);
            $role->syncPermissions($request->permissions);

            return response()->json([
                'success' => true,
                'message' => 'تم إضافة الدور بنجاح'
            ]);
        } catch (\Exception $e) {
            Log::error('Role creation error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'حدث خطأ أثناء إضافة الدور: ' . $e->getMessage()
            ], 500);
        }
    }

    public function updateRole(Request $request)
    {
        try {
            /** @var \App\Models\User $user */
            $user = auth()->user();

            // Only super_admin can edit roles
            if (!$user->hasRole('super_admin')) {
                return response()->json([
                    'success' => false,
                    'message' => 'فقط مدير النظام يمكنه تعديل الأدوار'
                ], 403);
            }

            Log::info('Update role request received', $request->all());

            $request->validate([
                'role_id' => 'required|exists:roles,id',
                'role_name' => 'required|string',
                'permissions' => 'required|array',
            ]);

            $role = Role::findOrFail($request->role_id);

            // Check if role name is being changed and if it's unique
            if ($role->name !== $request->role_name) {
                $request->validate([
                    'role_name' => 'unique:roles,name,' . $role->id,
                ]);
            }

            $role->name = $request->role_name;
            $role->save();

            $role->syncPermissions($request->permissions);

            return response()->json([
                'success' => true,
                'message' => 'تم تحديث الدور بنجاح'
            ]);
        } catch (\Exception $e) {
            Log::error('Role update error: ' . $e->getMessage());
            Log::error('Stack trace: ' . $e->getTraceAsString());
            return response()->json([
                'success' => false,
                'message' => 'حدث خطأ أثناء تحديث الدور: ' . $e->getMessage()
            ], 500);
        }
    }

    public function getRolePermissions($roleId)
    {
        try {
            $role = Role::with('permissions')->findOrFail($roleId);
            $allPermissions = Permission::all();
            $rolePermissions = $role->permissions->pluck('name')->toArray();

            $html = view('permissions.partials.permissions_checkboxes', [
                'allPermissions' => $allPermissions,
                'rolePermissions' => $rolePermissions
            ])->render();

            return response()->json([
                'success' => true,
                'html' => $html
            ]);
        } catch (\Exception $e) {
            Log::error('Get role permissions error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'حدث خطأ أثناء تحميل صلاحيات الدور: ' . $e->getMessage()
            ], 500);
        }
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
