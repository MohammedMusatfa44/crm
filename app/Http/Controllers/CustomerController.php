<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Imports\CustomerImportDetector;
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
        $request->validate([
            'file' => 'required|mimes:xlsx,xls',
            'sub_department_id' => 'required|exists:sub_departments,id'
        ]);
        
        // First, detect duplicates without importing
        try {
            Excel::import(new CustomerImportDetector($request->sub_department_id), $request->file('file'));
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('Import error: ' . $e->getMessage());
            return back()->with('error', 'حدث خطأ أثناء قراءة الملف: ' . $e->getMessage());
        }
        
        // Get detection results
        $detectionResults = session('import_detection', []);
        
        // Debug: Log detection results
        \Illuminate\Support\Facades\Log::info('Detection results after import:', [
            'total_new' => $detectionResults['total_new'] ?? 'not set',
            'total_duplicates' => $detectionResults['total_duplicates'] ?? 'not set',
            'new_customers_count' => isset($detectionResults['new_customers']) ? count($detectionResults['new_customers']) : 'not set',
            'duplicates_count' => isset($detectionResults['duplicates']) ? count($detectionResults['duplicates']) : 'not set',
            'session_keys' => array_keys($detectionResults),
            'session_id' => session()->getId()
        ]);
        
        // Always store data in cache for consistent access
        $cacheKey = 'import_detection_' . auth()->id() . '_' . time();
        \Illuminate\Support\Facades\Cache::put($cacheKey, $detectionResults, 300); // 5 minutes
        
        if (isset($detectionResults['total_duplicates']) && $detectionResults['total_duplicates'] > 0) {
            // Show duplicates for user decision
            return back()->with('warning', "تم العثور على {$detectionResults['total_duplicates']} عميل مكرر. يرجى مراجعة القائمة أدناه واختيار الإجراء المطلوب.")->with('import_detection', $detectionResults)->with('cache_key', $cacheKey);
        } else {
            // No duplicates, but still show confirmation modal
            return back()->with('success', "تم العثور على {$detectionResults['total_new']} عميل جديد. يرجى تأكيد الاستيراد.")->with('import_detection', $detectionResults)->with('cache_key', $cacheKey)->with('all_new', true);
        }
    }

    public function export(Request $request)
    {
        $customerIds = $request->input('customer_ids');
        if ($customerIds && is_array($customerIds) && count($customerIds) > 0) {
            return \Maatwebsite\Excel\Facades\Excel::download(new \App\Exports\CustomerExport($customerIds), 'customers.xlsx');
        }
        return \Maatwebsite\Excel\Facades\Excel::download(new \App\Exports\CustomerExport, 'customers.xlsx');
    }

    public function template()
    {
        $headers = [
            'ac_number',
            'full_name', 
            'mobile_number',
            'email',
            'comment',
            'status',
            'complaint_reason',
            'nationality',
            'city',
            'contact_method',
            'contacted_other_party',
            'payment_methods',
            'lead_date'
        ];
        
        $sampleData = [
            [
                'CUST001',
                'أحمد محمد علي',
                '0501234567',
                'ahmed@example.com',
                'عميل جديد',
                'new',
                'مشكلة في الخدمة',
                'سعودي',
                'الرياض',
                'هاتف',
                'نعم',
                'بطاقة ائتمان',
                '2024-01-15'
            ]
        ];
        
        return \Maatwebsite\Excel\Facades\Excel::download(
            new \App\Exports\CustomerTemplateExport($headers, $sampleData), 
            'customer_template.xlsx'
        );
    }

    public function processImport($detectionResults)
    {
        // Import new customers
        $newCustomers = $detectionResults['new_customers'] ?? [];
        $created = 0;
        
        foreach ($newCustomers as $customerData) {
            Customer::create($customerData);
            $created++;
        }
        
        $message = "تم استيراد العملاء بنجاح. تم إنشاء {$created} عميل جديد.";
        return back()->with('success', $message);
    }

    public function handleDuplicates(Request $request)
    {
        $action = $request->input('action'); // 'update', 'skip', or 'import_new'
        $selectedDuplicates = $request->input('selected_duplicates', []);
        
        // Try to get data from cache first, then session as fallback
        $cacheKey = $request->input('cache_key') ?? session('cache_key');
        $detectionResults = [];
        
        if ($cacheKey && \Illuminate\Support\Facades\Cache::has($cacheKey)) {
            $detectionResults = \Illuminate\Support\Facades\Cache::get($cacheKey);
            \Illuminate\Support\Facades\Log::info('Using cache data for import detection');
        } else {
            $detectionResults = session('import_detection', []);
            \Illuminate\Support\Facades\Log::info('Using session data for import detection');
        }
        
        // Debug: Log session data (commented out for production)
        // \Illuminate\Support\Facades\Log::info('Import detection session data:', $detectionResults);
        // \Illuminate\Support\Facades\Log::info('Selected duplicates:', $selectedDuplicates);
        // \Illuminate\Support\Facades\Log::info('Action:', [$action]);
        
        $updated = 0;
        $skipped = 0;
        
        // Handle different actions
        if ($action === 'import_new') {
            // All customers are new - just import them
            $updated = 0;
            $skipped = 0;
        } else {
            // Handle duplicates
            if (isset($detectionResults['duplicates']) && is_array($detectionResults['duplicates'])) {
                foreach ($detectionResults['duplicates'] as $index => $duplicate) {
                    if (in_array($index, $selectedDuplicates)) {
                        // User explicitly selected this duplicate
                        if ($action === 'update') {
                            // Update existing customer
                            $existingCustomer = Customer::find($duplicate['existing_customer']['id']);
                            if ($existingCustomer) {
                                $existingCustomer->update($duplicate['new_data']);
                                $updated++;
                            }
                        } else {
                            // Action is 'skip' - do nothing but count as skipped
                            $skipped++;
                        }
                    } else {
                        // User did NOT select this duplicate - skip by default
                        $skipped++;
                    }
                }
            } else {
                // If no duplicates in session, just count selected as skipped
                if ($action === 'skip') {
                    $skipped = count($selectedDuplicates);
                }
            }
        }
        
        // Also import new customers
        $newCustomers = $detectionResults['new_customers'] ?? [];
        $created = 0;
        
        // Debug: Log new customers data
        \Illuminate\Support\Facades\Log::info('New customers to import:', [
            'count' => count($newCustomers),
            'data' => $newCustomers,
            'session_keys' => array_keys($detectionResults),
            'session_exists' => session()->has('import_detection')
        ]);
        
        foreach ($newCustomers as $customerData) {
            try {
                Customer::create($customerData);
                $created++;
            } catch (\Exception $e) {
                \Illuminate\Support\Facades\Log::error('Error creating customer: ' . $e->getMessage(), $customerData);
            }
        }
        
        if ($action === 'import_new') {
            $message = "تم استيراد العملاء بنجاح. تم إنشاء {$created} عميل جديد.";
        } else {
            $message = "تم استيراد العملاء بنجاح. ";
            if ($created > 0) {
                $message .= "تم إنشاء {$created} عميل جديد. ";
            }
            if ($updated > 0) {
                $message .= "تم تحديث {$updated} عميل موجود. ";
            }
            if ($skipped > 0) {
                $message .= "تم تخطي {$skipped} عميل مكرر (بما في ذلك العملاء غير المحددين). ";
            }
        }
        
        // If no session data was found, show error
        if (empty($detectionResults)) {
            return back()->with('error', 'لم يتم العثور على بيانات الاستيراد. يرجى المحاولة مرة أخرى.');
        }
        
        // Clear the detection session and cache after processing
        session()->forget('import_detection');
        if ($cacheKey) {
            \Illuminate\Support\Facades\Cache::forget($cacheKey);
        }
        
        return back()->with('success', $message);
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
