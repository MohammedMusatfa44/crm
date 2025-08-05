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
use Spatie\Permission\Traits\HasRoles;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        /** @var \App\Models\User $user */
        $user = auth()->user();

        // Get filter parameters
        $departmentId = $request->get('department_id');
        $subDepartmentId = $request->get('sub_department_id');
        $startDate = $request->get('start_date');
        $endDate = $request->get('end_date');

        // Base queries with role-based filtering
        $customerQuery = $this->getFilteredCustomerQuery($user);
        $userQuery = $this->getFilteredUserQuery($user);
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

        // Apply date filters
        if ($startDate) {
            $customerQuery->whereDate('created_at', '>=', $startDate);
            $userQuery->whereDate('created_at', '>=', $startDate);
        }

        if ($endDate) {
            $customerQuery->whereDate('created_at', '<=', $endDate);
            $userQuery->whereDate('created_at', '<=', $endDate);
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
        $customerStatusCounts = $this->getCustomerStatusCounts($user, $departmentId, $subDepartmentId, $startDate, $endDate);

        // Get chart data
        $chartData = $this->getChartData($user, $departmentId, $subDepartmentId, $startDate, $endDate);

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
            'subDepartmentId',
            'startDate',
            'endDate'
        ));
    }

    private function getFilteredCustomerQuery($user)
    {
        $query = Customer::query();

        if ($user->hasRole('super_admin')) {
            // Super Admin sees all customers
            return $query;
        } elseif ($user->hasRole('admin')) {
            // Admin sees only customers they created
            return $query->where('created_by', $user->id);
        } elseif ($user->hasRole('employee')) {
            // Employee sees only customers assigned to them
            return $query->where('assigned_employee_id', $user->id);
        } else {
            // Default: no customers
            return $query->where('id', 0); // This will return no results
        }
    }

    private function getFilteredUserQuery($user)
    {
        $query = User::query();

        if ($user->hasRole('super_admin')) {
            // Super Admin sees all users
            return $query;
        } elseif ($user->hasRole('admin')) {
            // Admin sees only employees (not other admins or super_admins)
            return $query->whereHas('roles', function($q) {
                $q->where('name', 'employee');
            });
        } elseif ($user->hasRole('employee')) {
            // Employee sees no users
            return $query->where('id', 0); // This will return no results
        } else {
            // Default: no users
            return $query->where('id', 0); // This will return no results
        }
    }

    private function getCustomerStatusCounts($user, $departmentId = null, $subDepartmentId = null, $startDate = null, $endDate = null)
    {
        // Start with a filtered query based on user role
        $query = $this->getFilteredCustomerQuery($user);

        // Apply filters only if they are provided
        if ($departmentId) {
            $query->whereHas('subDepartment', function($q) use ($departmentId) {
                $q->where('department_id', $departmentId);
            });
        }

        if ($subDepartmentId) {
            $query->where('sub_department_id', $subDepartmentId);
        }

        // Apply date filters
        if ($startDate) {
            $query->whereDate('created_at', '>=', $startDate);
        }

        if ($endDate) {
            $query->whereDate('created_at', '<=', $endDate);
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

    private function getChartData($user, $departmentId = null, $subDepartmentId = null, $startDate = null, $endDate = null)
    {
        // Get last 6 months
        $months = [];
        $customerData = [];
        $userData = [];
        $departmentData = [];
        $subDepartmentData = [];
        $notificationData = [];

        for ($i = 5; $i >= 0; $i--) {
            $date = Carbon::now()->subMonths($i);
            $months[] = $date->format('M');

            // Customer data with role-based filtering
            $customerQuery = $this->getFilteredCustomerQuery($user)
                ->whereYear('created_at', $date->year)
                ->whereMonth('created_at', $date->month);

            if ($departmentId) {
                $customerQuery->whereHas('subDepartment', function($q) use ($departmentId) {
                    $q->where('department_id', $departmentId);
                });
            }
            if ($subDepartmentId) {
                $customerQuery->where('sub_department_id', $subDepartmentId);
            }
            // Apply date filters for chart data
            if ($startDate) {
                $customerQuery->whereDate('created_at', '>=', $startDate);
            }
            if ($endDate) {
                $customerQuery->whereDate('created_at', '<=', $endDate);
            }
            $customerData[] = $customerQuery->count();

            // User data with role-based filtering
            $userQuery = $this->getFilteredUserQuery($user)
                ->whereYear('created_at', $date->year)
                ->whereMonth('created_at', $date->month);
            // Apply date filters for chart data
            if ($startDate) {
                $userQuery->whereDate('created_at', '>=', $startDate);
            }
            if ($endDate) {
                $userQuery->whereDate('created_at', '<=', $endDate);
            }
            $userData[] = $userQuery->count();

            // Department data
            $departmentData[] = Department::whereYear('created_at', $date->year)
                                        ->whereMonth('created_at', $date->month)->count();

            // Sub-department data
            $subDeptQuery = SubDepartment::whereYear('created_at', $date->year)
                                       ->whereMonth('created_at', $date->month);
            if ($departmentId) {
                $subDeptQuery->where('department_id', $departmentId);
            }
            // Apply date filters for chart data
            if ($startDate) {
                $subDeptQuery->whereDate('created_at', '>=', $startDate);
            }
            if ($endDate) {
                $subDeptQuery->whereDate('created_at', '<=', $endDate);
            }
            $subDepartmentData[] = $subDeptQuery->count();

            // Notification data (reminders)
            $notificationQuery = Notification::whereYear('created_at', $date->year)
                                           ->whereMonth('created_at', $date->month);
            // Apply date filters for chart data
            if ($startDate) {
                $notificationQuery->whereDate('created_at', '>=', $startDate);
            }
            if ($endDate) {
                $notificationQuery->whereDate('created_at', '<=', $endDate);
            }
            $notificationData[] = $notificationQuery->count();
        }

        return [
            'months' => $months,
            'customers' => $customerData,
            'users' => $userData,
            'departments' => $departmentData,
            'sub_departments' => $subDepartmentData,
            'notifications' => $notificationData,
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
        /** @var \App\Models\User $user */
        $user = auth()->user();

        $departmentId = $request->get('department_id');
        $subDepartmentId = $request->get('sub_department_id');
        $startDate = $request->get('start_date');
        $endDate = $request->get('end_date');

        $chartData = $this->getChartData($user, $departmentId, $subDepartmentId, $startDate, $endDate);
        $statusCounts = $this->getCustomerStatusCounts($user, $departmentId, $subDepartmentId, $startDate, $endDate);

        return response()->json([
            'chartData' => $chartData,
            'statusCounts' => $statusCounts,
        ]);
    }

    public function reports(Request $request)
    {
        /** @var \App\Models\User $user */
        $user = auth()->user();

        $departmentId = $request->get('department_id');
        $subDepartmentId = $request->get('sub_department_id');
        $startDate = $request->get('start_date');
        $endDate = $request->get('end_date');

        $statusCounts = $this->getCustomerStatusCounts($user, $departmentId, $subDepartmentId, $startDate, $endDate);

        return response()->json($statusCounts);
    }
}
