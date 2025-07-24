<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Imports\CustomerImport;
use App\Exports\CustomerExport;
use Maatwebsite\Excel\Facades\Excel;
use App\Events\CustomerAdded;
use App\Models\Customer;

class CustomerController extends Controller
{
    public function index()
    {
        $customers = Customer::all();
        return view('customers.index', compact('customers'));
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
        return view('customers.edit', compact('customer'));
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
}
