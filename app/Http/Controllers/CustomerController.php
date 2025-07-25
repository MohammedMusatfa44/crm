<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Imports\CustomerImport;
use App\Exports\CustomerExport;
use Maatwebsite\Excel\Facades\Excel;
use App\Events\CustomerAdded;
use App\Models\Customer;
use App\Models\SubDepartment;
use App\Models\User;

class CustomerController extends Controller
{
    public function index()
    {
        $customers = Customer::with(['subDepartment.department', 'assignedEmployee'])->get();
        $subDepartments = SubDepartment::with('department')->get();
        $users = User::where('is_active', true)->get();
        return view('customers.index', compact('customers', 'subDepartments', 'users'));
    }

    public function create()
    {
        return view('customers.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'ac_number' => 'required|unique:customers,ac_number',
            'full_name' => 'required|string',
            'mobile_number' => 'required',
            'email' => 'nullable|email',
            'status' => 'required',
        ]);
        $customer = Customer::create($validated);
        event(new CustomerAdded($customer));
        return redirect()->route('customers.index')->with('success', 'تم إضافة العميل بنجاح');
    }

    public function edit($id)
    {
        $customer = Customer::findOrFail($id);
        $subDepartments = SubDepartment::with('department')->get();
        $users = User::where('is_active', true)->get();
        return view('customers.edit', compact('customer', 'subDepartments', 'users'));
    }

    public function update(Request $request, $id)
    {
        $customer = Customer::findOrFail($id);
        $validated = $request->validate([
            'ac_number' => 'required|unique:customers,ac_number,' . $customer->id,
            'full_name' => 'required|string',
            'mobile_number' => 'required',
            'email' => 'nullable|email',
            'status' => 'required',
            'sub_department_id' => 'required|exists:sub_departments,id',
            'assigned_employee_id' => 'required|exists:users,id',
            'nationality' => 'nullable|string',
            'city' => 'nullable|string',
            'contact_method' => 'nullable|string',
            'complaint_reason' => 'nullable|string',
            'comment' => 'nullable|string',
            'lead_date' => 'nullable|date',
            'contacted_other_party' => 'nullable|boolean',
        ]);

        $customer->update($validated);
        return redirect()->route('customers.index')->with('success', 'تم تحديث العميل بنجاح');
    }

    public function destroy($id)
    {
        $customer = Customer::findOrFail($id);
        $customer->delete();
        return redirect()->route('customers.index')->with('success', 'تم حذف العميل بنجاح');
    }

    public function import(Request $request)
    {
        $request->validate(['file' => 'required|mimes:xlsx']);
        Excel::import(new CustomerImport, $request->file('file'));
        return back()->with('success', 'تم استيراد العملاء بنجاح');
    }

    public function export()
    {
        return Excel::download(new CustomerExport, 'customers.xlsx');
    }

    public function show($id)
    {
        $customer = Customer::with(['subDepartment.department', 'assignedEmployee', 'notes'])->findOrFail($id);

        if (request()->wantsJson()) {
            return response()->json($customer);
        }

        return view('customers.show', compact('customer'));
    }
}
