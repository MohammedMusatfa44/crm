<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Department;
use App\Models\Customer;
use Spatie\Permission\Traits\HasRoles;

class DepartmentController extends Controller
{
    public function index()
    {
        $departments = Department::all();
        return view('departments.index', compact('departments'));
    }

    public function create()
    {
        return view('departments.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string',
            'description' => 'nullable|string',
            'is_active' => 'boolean',
        ]);
        Department::create($validated);
        return redirect()->route('departments.index')->with('success', 'تم إضافة القسم بنجاح');
    }

    public function edit($id)
    {
        $department = Department::findOrFail($id);
        return view('departments.edit', compact('department'));
    }

    public function update(Request $request, $id)
    {
        $department = Department::findOrFail($id);
        $validated = $request->validate([
            'name' => 'required|string',
            'description' => 'nullable|string',
            'is_active' => 'boolean',
        ]);
        $department->update($validated);
        return redirect()->route('departments.index')->with('success', 'تم تحديث القسم بنجاح');
    }

    public function destroy($id)
    {
        $department = Department::findOrFail($id);
        $department->delete();
        return redirect()->route('departments.index')->with('success', 'تم حذف القسم بنجاح');
    }

    public function show($id)
    {
        /** @var \App\Models\User $user */
        $user = auth()->user();

        $department = Department::with('subDepartments')->findOrFail($id);

        // Filter customers based on user role
        $customersQuery = Customer::whereHas('subDepartment', function($query) use ($id) {
            $query->where('department_id', $id);
        })->with(['assignedEmployee', 'createdBy']);

        if ($user->hasRole('super_admin')) {
            // Super Admin sees all customers
            $customers = $customersQuery->get();
        } elseif ($user->hasRole('admin')) {
            // Admin sees only customers they created
            $customers = $customersQuery->where('created_by', $user->id)->get();
        } elseif ($user->hasRole('employee')) {
            // Employee sees only customers assigned to them
            $customers = $customersQuery->where('assigned_employee_id', $user->id)->get();
        } else {
            // Default: no customers
            $customers = collect();
        }

        // Calculate filtered customer counts for each sub-department
        $subDepartmentCustomerCounts = [];
        foreach ($department->subDepartments as $subDepartment) {
            $subCustomersQuery = Customer::where('sub_department_id', $subDepartment->id);

            if ($user->hasRole('super_admin')) {
                $subDepartmentCustomerCounts[$subDepartment->id] = $subCustomersQuery->count();
            } elseif ($user->hasRole('admin')) {
                $subDepartmentCustomerCounts[$subDepartment->id] = $subCustomersQuery->where('created_by', $user->id)->count();
            } elseif ($user->hasRole('employee')) {
                $subDepartmentCustomerCounts[$subDepartment->id] = $subCustomersQuery->where('assigned_employee_id', $user->id)->count();
            } else {
                $subDepartmentCustomerCounts[$subDepartment->id] = 0;
            }
        }

        return view('departments.show', compact('department', 'customers', 'subDepartmentCustomerCounts'));
    }
}
