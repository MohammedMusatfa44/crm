<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\SubDepartment;
use App\Models\Department;

class SubDepartmentController extends Controller
{
    public function index()
    {
        $subDepartments = SubDepartment::with('department')->get();
        return view('sub_departments.index', compact('subDepartments'));
    }

    public function create()
    {
        $departments = Department::all();
        return view('sub_departments.create', compact('departments'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string',
            'description' => 'nullable|string',
            'department_id' => 'required|exists:departments,id',
            'is_active' => 'boolean',
        ]);
        SubDepartment::create($validated);
        return redirect()->route('sub-departments.index')->with('success', 'تم إضافة القسم الفرعي بنجاح');
    }

    public function edit($id)
    {
        $subDepartment = SubDepartment::findOrFail($id);
        $departments = Department::all();
        return view('sub_departments.edit', compact('subDepartment', 'departments'));
    }

    public function update(Request $request, $id)
    {
        $subDepartment = SubDepartment::findOrFail($id);
        $validated = $request->validate([
            'name' => 'required|string',
            'description' => 'nullable|string',
            'department_id' => 'required|exists:departments,id',
            'is_active' => 'boolean',
        ]);
        $subDepartment->update($validated);
        return redirect()->route('sub-departments.index')->with('success', 'تم تحديث القسم الفرعي بنجاح');
    }

    public function destroy($id)
    {
        $subDepartment = SubDepartment::findOrFail($id);
        $subDepartment->delete();
        return redirect()->route('sub-departments.index')->with('success', 'تم حذف القسم الفرعي بنجاح');
    }
}
