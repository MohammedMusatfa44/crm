<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Customer;
use App\Models\User;
use App\Models\Department;
use App\Models\Notification;

class DashboardController extends Controller
{
    public function index()
    {
        $customerCount = Customer::count();
        $userCount = User::count();
        $departmentCount = Department::count();
        $alertCount = Notification::where('is_read', false)->count();
        return view('dashboard', compact('customerCount', 'userCount', 'departmentCount', 'alertCount'));
    }

    public function statistics(Request $request)
    {
        // Return statistics data for charts (stub)
        return response()->json([
            'customers' => Customer::count(),
            'users' => User::count(),
            'departments' => Department::count(),
        ]);
    }

    public function reports(Request $request)
    {
        // Return reports data (stub)
        return response()->json([
            'report' => 'data',
        ]);
    }
}
