<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

// Route::get('/', function () {
//     return view('login');
// });

// Authentication routes
Route::get('/', [App\Http\Controllers\AuthController::class, 'showLoginForm'])->name('login');
Route::post('login', [App\Http\Controllers\AuthController::class, 'login']);
Route::post('logout', [App\Http\Controllers\AuthController::class, 'logout'])->name('logout');

// Dashboard routes
Route::middleware(['auth', 'active.user'])->group(function () {
    Route::get('/dashboard', [App\Http\Controllers\DashboardController::class, 'index'])->name('dashboard');
    Route::get('/dashboard/statistics', [App\Http\Controllers\DashboardController::class, 'statistics']);
    Route::get('/dashboard/reports', [App\Http\Controllers\DashboardController::class, 'reports']);

    // Customer routes
    Route::resource('customers', App\Http\Controllers\CustomerController::class);
    Route::post('customers/import', [App\Http\Controllers\CustomerController::class, 'import'])->name('customers.import');
    Route::get('customers/export', [App\Http\Controllers\CustomerController::class, 'export'])->name('customers.export');
    Route::post('customers/assign', [App\Http\Controllers\CustomerController::class, 'assign']);
    Route::post('customers/bulk-update', [App\Http\Controllers\CustomerController::class, 'bulkUpdate']);

    // User routes
    Route::resource('users', App\Http\Controllers\UserController::class);
    Route::post('users/{user}/toggle-status', [App\Http\Controllers\UserController::class, 'toggleStatus']);
    Route::get('users/{user}/sessions', [App\Http\Controllers\UserController::class, 'sessions']);

    // Session monitoring routes
    Route::get('sessions', [App\Http\Controllers\UserSessionController::class, 'index']);
    Route::post('sessions/{id}/terminate', [App\Http\Controllers\UserSessionController::class, 'terminate']);
    Route::get('sessions/security-alerts', [App\Http\Controllers\UserSessionController::class, 'securityAlerts']);

    // Department routes
    Route::resource('departments', App\Http\Controllers\DepartmentController::class);
    Route::get('departments/{id}', [App\Http\Controllers\DepartmentController::class, 'show'])->name('departments.show');
    Route::resource('sub-departments', App\Http\Controllers\SubDepartmentController::class);
    Route::get('sub-departments/{id}', [App\Http\Controllers\SubDepartmentController::class, 'show'])->name('sub-departments.show');

    // Notification routes
    Route::resource('notifications', App\Http\Controllers\NotificationController::class);
    Route::post('notifications/{notification}/mark-as-read', [App\Http\Controllers\NotificationController::class, 'markAsRead']);
    Route::get('notifications/real-time', [App\Http\Controllers\NotificationController::class, 'realTime']);

    // Support routes
    Route::resource('support-tickets', App\Http\Controllers\SupportTicketController::class);
    Route::post('support-tickets/{ticket}/reply', [App\Http\Controllers\SupportTicketController::class, 'reply']);
    Route::post('support-tickets/{ticket}/change-status', [App\Http\Controllers\SupportTicketController::class, 'changeStatus']);

    // Permission routes
    Route::resource('permissions', App\Http\Controllers\PermissionController::class);
    Route::post('permissions/{user}/assign', [App\Http\Controllers\PermissionController::class, 'assign']);
    Route::post('permissions/manage', [App\Http\Controllers\PermissionController::class, 'manage']);

    // Reporting routes
    Route::get('reports/user-performance', [App\Http\Controllers\ReportsController::class, 'userPerformance']);
    Route::get('reports/customer-cases', [App\Http\Controllers\ReportsController::class, 'customerCases']);
    Route::get('reports/department-reports', [App\Http\Controllers\ReportsController::class, 'departmentReports']);
    Route::get('reports/export-excel', [App\Http\Controllers\ReportsController::class, 'exportExcel']);
    Route::get('reports/export-pdf', [App\Http\Controllers\ReportsController::class, 'exportPDF']);
});
