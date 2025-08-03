<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\UserSession;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Traits\HasRoles;

class UserController extends Controller
{
    public function index()
    {
        /** @var \App\Models\User $user */
        $user = auth()->user();

        // Filter users based on user role
        if ($user->hasRole('super_admin')) {
            // Super Admin sees all users
            $users = User::with(['roles', 'createdBy'])->get();
        } elseif ($user->hasRole('admin')) {
            // Admin sees only users they created
            $users = User::with(['roles', 'createdBy'])->where('created_by', $user->id)->get();
        } else {
            // Other users see no users
            $users = collect();
        }

        return view('users.index', compact('users'));
    }

    public function create()
    {
        $roles = Role::all();
        return view('users.create', compact('roles'));
    }

    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'name' => 'required|string|max:255',
                'email' => 'required|email|unique:users,email',
                'password' => 'required|min:6|confirmed',
                'role' => 'required|in:super_admin,admin,employee',
                'phone' => 'nullable|string|max:20',
            ]);

            // Check if user is trying to create a super_admin
            if ($validated['role'] === 'super_admin' && !auth()->user()->roles->contains('name', 'super_admin')) {
                return response()->json([
                    'success' => false,
                    'message' => 'فقط مدير النظام يمكنه إنشاء مدير عام'
                ], 403);
            }

            // Check if user has permission to create the specified role
            /** @var \App\Models\User $user */
            $user = auth()->user();

            if ($validated['role'] === 'admin' && !$user->can('users.create_admin')) {
                return response()->json([
                    'success' => false,
                    'message' => 'ليس لديك صلاحية لإنشاء مدير'
                ], 403);
            }

            if ($validated['role'] === 'employee' && !$user->can('users.create_employee')) {
                return response()->json([
                    'success' => false,
                    'message' => 'ليس لديك صلاحية لإنشاء موظف'
                ], 403);
            }

            $user = User::create([
                'name' => $validated['name'],
                'email' => $validated['email'],
                'password' => Hash::make($validated['password']),
                'phone' => $validated['phone'],
                'is_active' => true,
                'created_by' => auth()->id(),
            ]);

            $user->assignRole($validated['role']);

            return response()->json([
                'success' => true,
                'message' => 'تم إضافة المستخدم بنجاح'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'حدث خطأ أثناء إضافة المستخدم: ' . $e->getMessage()
            ], 500);
        }
    }

    public function show($id)
    {
        $user = User::findOrFail($id);
        return view('users.show', compact('user'));
    }

    public function edit($id)
    {
        /** @var \App\Models\User $user */
        $user = auth()->user();
        $targetUser = User::findOrFail($id);

        // Check if user has permission to edit this user
        if ($user->hasRole('super_admin')) {
            // Super Admin can edit any user
        } elseif ($user->hasRole('admin')) {
            // Admin can only edit users they created
            if ($targetUser->created_by !== $user->id) {
                abort(403, 'ليس لديك صلاحية لتعديل هذا المستخدم');
            }
        } else {
            abort(403, 'ليس لديك صلاحية لتعديل المستخدمين');
        }

        // Filter roles based on user role
        if ($user->hasRole('super_admin')) {
            $roles = Role::all();
        } elseif ($user->hasRole('admin')) {
            // Admin can only assign employee role
            $roles = Role::where('name', 'employee')->get();
        } else {
            $roles = collect();
        }

        return view('users.edit', compact('targetUser', 'roles'));
    }

    public function update(Request $request, $id)
    {
        /** @var \App\Models\User $user */
        $user = auth()->user();
        $targetUser = User::findOrFail($id);

        // Check if user has permission to update this user
        if ($user->hasRole('super_admin')) {
            // Super Admin can update any user
        } elseif ($user->hasRole('admin')) {
            // Admin can only update users they created
            if ($targetUser->created_by !== $user->id) {
                abort(403, 'ليس لديك صلاحية لتعديل هذا المستخدم');
            }
        } else {
            abort(403, 'ليس لديك صلاحية لتعديل المستخدمين');
        }

        $validated = $request->validate([
            'name' => 'required|string',
            'email' => 'required|email|unique:users,email,' . $targetUser->id,
            'password' => 'nullable|min:6',
            'role' => 'required',
            'phone' => 'nullable',
        ]);

        // Check if admin is trying to assign admin role
        if ($validated['role'] === 'admin' && $user->hasRole('admin') && !$user->hasRole('super_admin')) {
            abort(403, 'لا يمكن للمدير تعيين دور مدير');
        }

        $targetUser->name = $validated['name'];
        $targetUser->email = $validated['email'];
        if (!empty($validated['password'])) {
            $targetUser->password = Hash::make($validated['password']);
        }
        $targetUser->phone = $validated['phone'];
        $targetUser->save();
        $targetUser->syncRoles([$validated['role']]);
        return redirect()->route('users.index')->with('success', 'تم تحديث المستخدم بنجاح');
    }

    public function destroy($id)
    {
        try {
            /** @var \App\Models\User $user */
            $user = auth()->user();
            $targetUser = User::findOrFail($id);

            // Check if user has permission to delete this user
            if ($user->hasRole('super_admin')) {
                // Super Admin can delete any user
            } elseif ($user->hasRole('admin')) {
                // Admin can only delete users they created
                if ($targetUser->created_by !== $user->id) {
                    return response()->json([
                        'success' => false,
                        'message' => 'ليس لديك صلاحية لحذف هذا المستخدم'
                    ], 403);
                }
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'ليس لديك صلاحية لحذف المستخدمين'
                ], 403);
            }

            $targetUser->delete();

            return response()->json([
                'success' => true,
                'message' => 'تم حذف المستخدم بنجاح'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'حدث خطأ أثناء حذف المستخدم'
            ], 500);
        }
    }

    public function toggleStatus($id)
    {
        try {
            /** @var \App\Models\User $user */
            $user = auth()->user();
            $targetUser = User::findOrFail($id);

            // Check if user has permission to toggle status of this user
            if ($user->hasRole('super_admin')) {
                // Super Admin can toggle status of any user
            } elseif ($user->hasRole('admin')) {
                // Admin can only toggle status of users they created
                if ($targetUser->created_by !== $user->id) {
                    return response()->json([
                        'success' => false,
                        'message' => 'ليس لديك صلاحية لتغيير حالة هذا المستخدم'
                    ], 403);
                }
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'ليس لديك صلاحية لتغيير حالة المستخدمين'
                ], 403);
            }

            $targetUser->is_active = !$targetUser->is_active;
            $targetUser->save();

            return response()->json([
                'success' => true,
                'message' => 'تم تغيير حالة المستخدم بنجاح',
                'is_active' => $targetUser->is_active
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'حدث خطأ أثناء تغيير حالة المستخدم'
            ], 500);
        }
    }

    public function sessions($id)
    {
        $user = User::findOrFail($id);
        $sessions = UserSession::where('user_id', $user->id)->get();
        return view('sessions.index', compact('sessions'));
    }
}
