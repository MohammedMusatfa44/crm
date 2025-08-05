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

// Test routes for debugging
Route::get('test-notification', function() {
    return response()->json([
        'success' => true,
        'message' => 'Test route working',
        'user' => auth()->user() ? auth()->user()->id : null
    ]);
});

Route::get('test-simple', function() {
    return response()->json([
        'success' => true,
        'message' => 'Simple test working'
    ]);
});

Route::get('test-session', function() {
    return response()->json([
        'success' => true,
        'session_id' => session()->getId(),
        'user' => auth()->user() ? auth()->user()->id : null,
        'authenticated' => auth()->check()
    ]);
});

Route::get('test-notification-count', function() {
    return response()->json([
        'success' => true,
        'count' => 5,
        'user' => auth()->user() ? auth()->user()->id : null,
        'authenticated' => auth()->check()
    ]);
});

// Notification count route (temporarily outside middleware for testing)
Route::get('notifications/count', [App\Http\Controllers\NotificationController::class, 'getNotificationCount']);
Route::get('notifications/triggered', [App\Http\Controllers\NotificationController::class, 'getTriggeredNotifications']);

// Test route for notifications
Route::get('test-notifications', function() {
    try {
        $user = auth()->user();
        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'No user authenticated'
            ]);
        }

        $notifications = \App\Models\Notification::where('user_id', $user->id)->get();

        return response()->json([
            'success' => true,
            'user_id' => $user->id,
            'total_notifications' => $notifications->count(),
            'notifications' => $notifications->take(3)->toArray()
        ]);
    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'error' => $e->getMessage(),
            'trace' => $e->getTraceAsString()
        ]);
    }
});

// Dashboard routes
Route::middleware(['auth', 'active.user'])->group(function () {
    Route::get('/dashboard', [App\Http\Controllers\DashboardController::class, 'index'])->name('dashboard');
    Route::get('/dashboard/statistics', [App\Http\Controllers\DashboardController::class, 'statistics']);
    Route::get('/dashboard/reports', [App\Http\Controllers\DashboardController::class, 'reports']);
    Route::get('/dashboard/sub-departments', [App\Http\Controllers\DashboardController::class, 'getSubDepartments']);

    // Customer routes
    Route::get('customers/reports', [App\Http\Controllers\CustomerController::class, 'reports'])->name('customers.reports');
    Route::get('customers/test', function() { return 'Test route works!'; })->name('customers.test');
    Route::resource('customers', App\Http\Controllers\CustomerController::class);
    Route::post('customers/import', [App\Http\Controllers\CustomerController::class, 'import'])->name('customers.import');
    Route::post('customers/handle-duplicates', [App\Http\Controllers\CustomerController::class, 'handleDuplicates'])->name('customers.handle-duplicates');
    Route::get('customers/template', [App\Http\Controllers\CustomerController::class, 'template'])->name('customers.template');
    Route::post('customers/export', [App\Http\Controllers\CustomerController::class, 'export'])->name('customers.export');
    Route::post('customers/assign', [App\Http\Controllers\CustomerController::class, 'assign']);
    Route::post('customers/bulk-update', [App\Http\Controllers\CustomerController::class, 'bulkUpdate'])->name('customers.bulk-update');
    Route::post('customers/bulk-assign', [App\Http\Controllers\CustomerController::class, 'bulkAssign'])->name('customers.bulk-assign');

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
    Route::post('notifications/{notification}/mark-read', [App\Http\Controllers\NotificationController::class, 'markAsRead']);
    Route::post('notifications/{notification}/mark-as-read', [App\Http\Controllers\NotificationController::class, 'markAsRead']);
    Route::post('notifications/{notification}/read', [App\Http\Controllers\NotificationController::class, 'markAsRead']);
    Route::post('notifications/mark-read/{id}', [App\Http\Controllers\NotificationController::class, 'markAsRead']);
    Route::post('notifications/{notification}/trigger', [App\Http\Controllers\NotificationController::class, 'trigger']);
    Route::get('notifications/real-time', [App\Http\Controllers\NotificationController::class, 'realTime']);
    Route::get('notifications/triggered', [App\Http\Controllers\NotificationController::class, 'getTriggeredNotifications']);
    Route::get('notifications/count', [App\Http\Controllers\NotificationController::class, 'getNotificationCount']);
    Route::get('notifications/test-session', [App\Http\Controllers\NotificationController::class, 'testSession']);
    Route::post('notifications/force-trigger', [App\Http\Controllers\NotificationController::class, 'forceTrigger']);

    // Support routes
    Route::resource('support-tickets', App\Http\Controllers\SupportTicketController::class);
    Route::post('support-tickets/{ticket}/reply', [App\Http\Controllers\SupportTicketController::class, 'reply']);
    Route::post('support-tickets/{ticket}/change-status', [App\Http\Controllers\SupportTicketController::class, 'changeStatus']);

    // Permission routes
    Route::get('permissions', [App\Http\Controllers\PermissionController::class, 'index'])->name('permissions.index');
    Route::post('permissions/assign', [App\Http\Controllers\PermissionController::class, 'assign'])->name('permissions.assign');
    Route::post('permissions/manage', [App\Http\Controllers\PermissionController::class, 'manage'])->name('permissions.manage');
    Route::post('permissions/roles', [App\Http\Controllers\PermissionController::class, 'storeRole'])->name('permissions.roles.store');
    Route::post('permissions/roles/update', [App\Http\Controllers\PermissionController::class, 'updateRole'])->name('permissions.roles.update');
    Route::get('permissions/roles/{role}/permissions', [App\Http\Controllers\PermissionController::class, 'getRolePermissions'])->name('permissions.roles.permissions');

    // Reporting routes
    Route::get('reports/user-performance', [App\Http\Controllers\ReportsController::class, 'userPerformance']);
    Route::get('reports/customer-cases', [App\Http\Controllers\ReportsController::class, 'customerCases']);
    Route::get('reports/department-reports', [App\Http\Controllers\ReportsController::class, 'departmentReports']);
    Route::get('reports/export-excel', [App\Http\Controllers\ReportsController::class, 'exportExcel']);
    Route::get('reports/export-pdf', [App\Http\Controllers\ReportsController::class, 'exportPDF']);
});
