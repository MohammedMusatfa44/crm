<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Customer;
use App\Models\User;
use App\Models\Department;
use App\Models\SubDepartment;
use App\Models\Notification;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        // Get filter parameters
        $departmentId = $request->get('department_id');
        $subDepartmentId = $request->get('sub_department_id');

        // Base queries
        $customerQuery = Customer::query();
        $userQuery = User::query();
        $departmentQuery = Department::query();
        $subDepartmentQuery = SubDepartment::query();

        // Apply filters
        if ($departmentId) {
            $customerQuery->whereHas('subDepartment', function($q) use ($departmentId) {
                $q->where('department_id', $departmentId);
            });
            $subDepartmentQuery->where('department_id', $departmentId);
        }

        if ($subDepartmentId) {
            $customerQuery->where('sub_department_id', $subDepartmentId);
        }

        // Get counts
        $customerCount = $customerQuery->count();
        $userCount = $userQuery->count();
        $departmentCount = $departmentQuery->count();
        $subDepartmentCount = $subDepartmentQuery->count();
        $alertCount = Notification::where('is_read', false)->count();

        // Get departments for filter dropdown
        $departments = Department::all();
        $subDepartments = $subDepartmentId ? SubDepartment::where('department_id', $departmentId)->get() : collect();

        // Get customer status counts for reports
        $customerStatusCounts = $this->getCustomerStatusCounts($departmentId, $subDepartmentId);

        // Get chart data
        $chartData = $this->getChartData($departmentId, $subDepartmentId);

        return view('dashboard', compact(
            'customerCount',
            'userCount',
            'departmentCount',
            'subDepartmentCount',
            'alertCount',
            'departments',
            'subDepartments',
            'customerStatusCounts',
            'chartData',
            'departmentId',
            'subDepartmentId'
        ));
    }

    private function getCustomerStatusCounts($departmentId = null, $subDepartmentId = null)
    {
        // Start with a fresh query
        $query = Customer::query();

        // Apply filters only if they are provided
        if ($departmentId) {
            $query->whereHas('subDepartment', function($q) use ($departmentId) {
                $q->where('department_id', $departmentId);
            });
        }

        if ($subDepartmentId) {
            $query->where('sub_department_id', $subDepartmentId);
        }

        // Get the base query for counting
        $baseQuery = clone $query;

        return [
            'no_answer' => (clone $baseQuery)->where('status', 'new')->count(),
            'hot' => (clone $baseQuery)->where('status', 'hot')->count(),
            'western' => (clone $baseQuery)->where('status', 'western')->count(),
            'follow' => (clone $baseQuery)->where('status', 'follow_up')->count(),
            'deposits' => (clone $baseQuery)->where('status', 'in_progress')->count(),
            'not_interested' => 0, // Will be calculated as customers not in other statuses
            'no_answer2' => 0, // Will be calculated as customers not in other statuses
            'no_answer1' => 0, // Will be calculated as customers not in other statuses
        ];
    }

    private function getChartData($departmentId = null, $subDepartmentId = null)
    {
        // Get last 6 months
        $months = [];
        $customerData = [];
        $userData = [];
        $departmentData = [];
        $subDepartmentData = [];

        for ($i = 5; $i >= 0; $i--) {
            $date = Carbon::now()->subMonths($i);
            $months[] = $date->format('M');

            // Customer data
            $customerQuery = Customer::whereYear('created_at', $date->year)
                                   ->whereMonth('created_at', $date->month);
            if ($departmentId) {
                $customerQuery->whereHas('subDepartment', function($q) use ($departmentId) {
                    $q->where('department_id', $departmentId);
                });
            }
            if ($subDepartmentId) {
                $customerQuery->where('sub_department_id', $subDepartmentId);
            }
            $customerData[] = $customerQuery->count();

            // User data
            $userData[] = User::whereYear('created_at', $date->year)
                             ->whereMonth('created_at', $date->month)->count();

            // Department data
            $departmentData[] = Department::whereYear('created_at', $date->year)
                                        ->whereMonth('created_at', $date->month)->count();

            // Sub-department data
            $subDeptQuery = SubDepartment::whereYear('created_at', $date->year)
                                       ->whereMonth('created_at', $date->month);
            if ($departmentId) {
                $subDeptQuery->where('department_id', $departmentId);
            }
            $subDepartmentData[] = $subDeptQuery->count();
        }

        return [
            'months' => $months,
            'customers' => $customerData,
            'users' => $userData,
            'departments' => $departmentData,
            'sub_departments' => $subDepartmentData,
        ];
    }

    public function getSubDepartments(Request $request)
    {
        $departmentId = $request->get('department_id');
        $subDepartments = SubDepartment::where('department_id', $departmentId)->get();
        return response()->json($subDepartments);
    }

    public function statistics(Request $request)
    {
        $departmentId = $request->get('department_id');
        $subDepartmentId = $request->get('sub_department_id');

        $chartData = $this->getChartData($departmentId, $subDepartmentId);
        $statusCounts = $this->getCustomerStatusCounts($departmentId, $subDepartmentId);

        return response()->json([
            'chartData' => $chartData,
            'statusCounts' => $statusCounts,
        ]);
    }

    public function reports(Request $request)
    {
        $departmentId = $request->get('department_id');
        $subDepartmentId = $request->get('sub_department_id');

        $statusCounts = $this->getCustomerStatusCounts($departmentId, $subDepartmentId);

        return response()->json($statusCounts);
    }
}
