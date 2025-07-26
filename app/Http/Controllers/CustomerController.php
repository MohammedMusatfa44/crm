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
        $users = User::whereIn('role', ['employee', 'admin'])->get();
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
        $customer = Customer::with(['subDepartment.department', 'assignedEmployee'])->findOrFail($id);
        return response()->json($customer);
    }

    public function reports()
    {
        $customers = Customer::with(['subDepartment.department', 'assignedEmployee'])->get();

        // Calculate statistics
        $totalCustomers = $customers->count();
        $newCustomers = $customers->where('status', 'new')->count();
        $activeCustomers = $customers->whereIn('status', ['in_progress', 'follow_up'])->count();
        $closedCustomers = $customers->where('status', 'closed')->count();

        // Calculate status counts for reports
        $statusCounts = [
            'no_answer' => $customers->where('status', 'new')->count(),
            'hot' => $customers->where('status', 'hot')->count(),
            'western' => $customers->where('status', 'western')->count(),
            'follow' => $customers->where('status', 'follow_up')->count(),
            'deposits' => $customers->where('status', 'in_progress')->count(),
            'not_interested' => 0,
            'no_answer2' => 0,
            'no_answer1' => 0,
        ];

        return view('customers.reports', compact('customers', 'totalCustomers', 'newCustomers', 'activeCustomers', 'closedCustomers', 'statusCounts'));
    }

    public function bulkUpdate(Request $request)
    {
        $request->validate([
            'customer_ids' => 'required|array',
            'customer_ids.*' => 'exists:customers,id',
            'status' => 'required|in:new,in_progress,follow_up,western,hot,closed'
        ]);

        try {
            $customerIds = $request->input('customer_ids');
            $newStatus = $request->input('status');

            // Update all selected customers
            Customer::whereIn('id', $customerIds)->update([
                'status' => $newStatus,
                'updated_at' => now()
            ]);

            return response()->json([
                'success' => true,
                'message' => 'تم تحديث حالة ' . count($customerIds) . ' عميل بنجاح'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'حدث خطأ أثناء تحديث حالة العملاء'
            ], 500);
        }
    }

    public function bulkAssign(Request $request)
    {
        $request->validate([
            'customer_ids' => 'required|array',
            'customer_ids.*' => 'exists:customers,id',
            'assigned_employee_id' => 'required|exists:users,id'
        ]);

        try {
            $customerIds = $request->input('customer_ids');
            $assignedEmployeeId = $request->input('assigned_employee_id');

            // Update all selected customers
            Customer::whereIn('id', $customerIds)->update([
                'assigned_employee_id' => $assignedEmployeeId,
                'updated_at' => now()
            ]);

            return response()->json([
                'success' => true,
                'message' => 'تم تخصيص ' . count($customerIds) . ' عميل للموظف بنجاح'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'حدث خطأ أثناء تخصيص العملاء'
            ], 500);
        }
    }
}
