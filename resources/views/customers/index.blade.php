@extends('layouts.layout')

@section('title', 'إدارة العملاء')

@section('styles')
<style>
    .dashboard-bg {
        background: #f8f9fa;
        min-height: 100vh;
        padding: 2rem 0;
    }
    .dashboard-title {
        font-size: 2.1rem;
        font-weight: 700;
        color: #222;
        margin-bottom: 0.2rem;
    }
    .dashboard-subtitle {
        color: #888;
        font-size: 1.1rem;
        margin-bottom: 1.5rem;
    }
    .top-actions {
        display: flex;
        gap: 0.7rem;
        justify-content: flex-end;
        margin-bottom: 1.2rem;
        flex-wrap: wrap;
    }
    .top-actions .btn {
        border-radius: 1.2rem;
        font-weight: 500;
        font-size: 0.9rem;
        padding: 0.5rem 1rem;
    }
    .stat-card {
        background: linear-gradient(135deg, #4f5bd5 0%, #5f8fff 100%);
        color: #fff;
        border: none;
        border-radius: 1.2rem;
        box-shadow: 0 4px 24px rgba(33,150,243,0.10);
        transition: transform 0.2s, box-shadow 0.2s;
        position: relative;
        overflow: hidden;
        margin-bottom: 1.5rem;
        min-height: 110px;
        display: flex;
        flex-direction: column;
        justify-content: center;
        align-items: flex-start;
        padding: 1.2rem 1.5rem;
    }
    .stat-card .stat-label {
        font-size: 1.1rem;
        font-weight: 500;
        margin-bottom: 0.3rem;
        opacity: 0.9;
    }
    .stat-card .stat-value {
        font-size: 2.2rem;
        font-weight: bold;
        opacity: 0.95;
    }
    .stat-card.no-answer {
        background: linear-gradient(135deg, #4f5bd5 0%, #5f8fff 100%);
    }
    .stat-card.hot {
        background: linear-gradient(135deg, #ff6b6b 0%, #ff8e8e 100%);
    }
    .stat-card.western {
        background: linear-gradient(135deg, #ffa726 0%, #ffcc02 100%);
    }
    .stat-card.follow {
        background: linear-gradient(135deg, #7e57c2 0%, #9c27b0 100%);
    }
    .stat-card.deposits {
        background: linear-gradient(135deg, #4f5bd5 0%, #5f8fff 100%);
    }
    .stat-card.not-interested {
        background: linear-gradient(135deg, #9e9e9e 0%, #757575 100%);
    }
    .stat-card.no-answer2 {
        background: linear-gradient(135deg, #4f5bd5 0%, #5f8fff 100%);
    }
    .stat-card.no-answer1 {
        background: linear-gradient(135deg, #4f5bd5 0%, #5f8fff 100%);
    }
    .modern-table {
        background: #fff;
        border-radius: 1.2rem;
        box-shadow: 0 2px 12px rgba(33,150,243,0.08);
        overflow: hidden;
    }
    .table-header {
        background: linear-gradient(135deg, #4f5bd5 0%, #5f8fff 100%);
        color: #fff;
        padding: 1rem 1.5rem;
        font-weight: 600;
        font-size: 1.1rem;
    }
    .table-responsive {
        border-radius: 1.2rem;
    }
    .table {
        margin-bottom: 0;
        border-collapse: separate;
        border-spacing: 0;
    }
    .table th {
        background: #f8f9fa;
        border: none;
        font-weight: 600;
        color: #555;
        padding: 1.2rem 1rem;
        font-size: 0.9rem;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        border-bottom: 2px solid #e9ecef;
    }
    .table td {
        border: none;
        border-bottom: 1px solid #f0f0f0;
        padding: 1.2rem 1rem;
        vertical-align: middle;
        font-size: 0.95rem;
        color: #333;
    }
    .table tbody tr {
        transition: all 0.2s ease;
        cursor: pointer;
    }
    .table tbody tr:hover {
        background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
        transform: translateY(-1px);
        box-shadow: 0 4px 12px rgba(0,0,0,0.1);
    }
    .table tbody tr:nth-child(even) {
        background: #fafbfc;
    }
    .table tbody tr:nth-child(even):hover {
        background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
    }
    .status-badge {
        font-size: 0.75rem;
        padding: 0.4rem 0.8rem;
        border-radius: 1rem;
        font-weight: 500;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }
    .btn-action {
        font-size: 0.75rem;
        padding: 0.4rem 0.8rem;
        margin: 0.3rem;
        border-radius: 0.8rem;
        width: 100%;
        font-weight: 500;
        transition: all 0.2s ease;
    }
    .btn-action:hover {
        transform: translateY(-1px);
        box-shadow: 0 4px 8px rgba(0,0,0,0.15);
    }
    .customer-sidebar {
        background: #fff;
        border-radius: 1.2rem;
        box-shadow: 0 2px 12px rgba(33,150,243,0.08);
        overflow: hidden;
    }
    .sidebar-header {
        background: linear-gradient(135deg, #4f5bd5 0%, #5f8fff 100%);
        color: #fff;
        padding: 1rem 1.5rem;
        font-weight: 600;
    }
    .sidebar-body {
        padding: 1.5rem;
    }
    .info-row {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 0.5rem 0;
        border-bottom: 1px solid #f0f0f0;
    }
    .info-row:last-child {
        border-bottom: none;
    }
    .info-label {
        font-weight: 500;
        color: #666;
    }
    .info-value {
        color: #222;
    }

    /* DataTables Custom Styling */
    .dataTables_wrapper {
        padding: 1.5rem;
    }
    .dataTables_length {
        margin-bottom: 1rem;
    }
    .dataTables_length select {
        border: 2px solid #e9ecef;
        border-radius: 0.5rem;
        padding: 0.5rem;
        background: #fff;
        font-weight: 500;
    }
    .dataTables_filter {
        margin-bottom: 1rem;
    }
    .dataTables_filter input {
        border: 2px solid #e9ecef;
        border-radius: 0.5rem;
        padding: 0.5rem 1rem;
        background: #fff;
        font-weight: 500;
        width: 250px;
        transition: all 0.2s ease;
    }
    .dataTables_filter input:focus {
        border-color: #4f5bd5;
        box-shadow: 0 0 0 0.2rem rgba(79, 91, 213, 0.25);
        outline: none;
    }
    .dataTables_info {
        margin-top: 1rem;
        font-weight: 500;
        color: #666;
    }
    .dataTables_paginate {
        margin-top: 1rem;
    }
    .dataTables_paginate .paginate_button {
        border: 2px solid #e9ecef;
        border-radius: 0.5rem;
        padding: 0.5rem 1rem;
        margin: 0 0.2rem;
        background: #fff;
        color: #333;
        font-weight: 500;
        transition: all 0.2s ease;
    }
    .dataTables_paginate .paginate_button:hover {
        background: #4f5bd5;
        color: #fff;
        border-color: #4f5bd5;
    }
    .dataTables_paginate .paginate_button.current {
        background: #4f5bd5;
        color: #fff;
        border-color: #4f5bd5;
    }
    .dataTables_paginate .paginate_button.disabled {
        color: #ccc;
        cursor: not-allowed;
    }

    /* Table Row Highlighting */
    .table tbody tr.selected {
        background: linear-gradient(135deg, #e3f2fd 0%, #bbdefb 100%);
        border-left: 4px solid #4f5bd5;
    }

    /* Responsive improvements */
    @media (max-width: 768px) {
        .dataTables_filter input {
            width: 100%;
        }
        .table th, .table td {
            padding: 0.8rem 0.5rem;
            font-size: 0.85rem;
        }
        .btn-action {
            font-size: 0.7rem;
            width: 100%;
            padding: 0.3rem 0.6rem;
            margin: 0.5rem;
        }
        .dashboard-title {
            font-size: 1.5rem;
        }
        .dashboard-subtitle {
            font-size: 1rem;
        }
        .top-actions {
            flex-direction: column;
            gap: 0.5rem;
        }
        .top-actions .btn {
            width: 100%;
            font-size: 0.85rem;
            padding: 0.6rem 1rem;
        }
        .stat-card {
            min-height: 90px;
            padding: 1rem;
        }
        .stat-card .stat-label {
            font-size: 1rem;
        }
        .stat-card .stat-value {
            font-size: 1.8rem;
        }
        .modern-table {
            margin: 0 -0.5rem;
        }
        .table-responsive {
            border-radius: 0;
        }
        .table-header {
            padding: 0.8rem 1rem;
            font-size: 1rem;
        }
        .dataTables_wrapper {
            padding: 1rem;
        }
        .dataTables_length, .dataTables_filter {
            margin-bottom: 0.8rem;
        }
        .dataTables_info, .dataTables_paginate {
            margin-top: 0.8rem;
        }
        .dataTables_paginate .paginate_button {
            padding: 0.4rem 0.8rem;
            font-size: 0.85rem;
        }
        .customer-sidebar {
            margin-top: 1rem;
        }
        .sidebar-header {
            padding: 0.8rem 1rem;
        }
        .sidebar-body {
            padding: 1rem;
        }
        .info-row {
            flex-direction: column;
            align-items: flex-start;
            gap: 0.3rem;
        }
        .info-label {
            font-weight: 600;
        }
    }

    @media (max-width: 576px) {
        .dashboard-bg {
            padding: 1rem 0;
        }
        .dashboard-header-card {
            padding: 1rem !important;
            margin-bottom: 1rem !important;
        }
        .stat-card {
            min-height: 80px;
            padding: 0.8rem;
        }
        .stat-card .stat-label {
            font-size: 0.9rem;
        }
        .stat-card .stat-value {
            font-size: 1.5rem;
        }
        .table th, .table td {
            padding: 0.6rem 0.3rem;
            font-size: 0.8rem;
        }
        .btn-action {
            font-size: 0.65rem;
            width: 100%;
            padding: 0.25rem 0.5rem;
            margin: 0.3rem;
        }
        .status-badge {
            font-size: 0.7rem;
            padding: 0.3rem 0.6rem;
        }
        .dataTables_wrapper {
            padding: 0.8rem;
        }
        .dataTables_filter input {
            font-size: 0.9rem;
        }
        .dataTables_length select {
            font-size: 0.9rem;
        }
        .top-actions .btn {
            font-size: 0.8rem;
            padding: 0.5rem 0.8rem;
        }
        .top-actions .btn i {
            margin-right: 0.3rem;
        }
    }

    /* Hide some columns on mobile */
    @media (max-width: 768px) {
        .table-responsive {
            overflow-x: auto;
        }
        .table {
            min-width: 600px;
        }
    }

    /* Improve sidebar responsiveness */
    @media (max-width: 992px) {
        #sidebarContainer .col-md-9 {
            margin-bottom: 1rem;
        }
        .customer-sidebar {
            position: sticky;
            top: 1rem;
        }
    }
</style>
@endsection

@section('content')
<div class="dashboard-bg">
    <div class="container-fluid">
        <div class="row mb-2 col-12 w-100">
            <div class="col-12 w-100">
                <div class="dashboard-header-card" style="background:#fff; border-radius:1.2rem; box-shadow:0 2px 12px rgba(33,150,243,0.07); padding:1.5rem 2rem 1.2rem 2rem; margin-bottom:1.5rem; display:flex; flex-wrap:wrap; align-items:center; justify-content:space-between;">
                    <div>
                        <div class="dashboard-title">إدارة العملاء</div>
                        <div class="dashboard-subtitle">جميع العملاء في النظام</div>
                    </div>
                    <div class="top-actions">
                        <button class="btn btn-outline-primary" data-bs-toggle="modal" data-bs-target="#importModal">
                            <i class="bi bi-upload"></i> رفع إكسل
                        </button>
                        <button class="btn btn-outline-success" id="exportExcelBtn">
                            <i class="bi bi-download"></i> استخراج البيانات
                        </button>
                        @can('clients.edit')
                        <button class="btn btn-outline-warning" id="bulkStatusBtn" disabled>
                            <i class="bi bi-arrow-repeat"></i> تغيير الحالة (<span id="selectedCount">0</span>)
                        </button>
                        @endcan
                        @can('clients.edit')
                        <button class="btn btn-outline-info" id="bulkAssignBtn" disabled>
                            <i class="bi bi-people"></i> تخصيص للموظفين (<span id="selectedCount2">0</span>)
                        </button>
                        @endcan
                        @can('clients.create')
                        <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#addCustomerModal">
                            <i class="bi bi-plus"></i> إضافة عميل
                        </button>
                        @endcan
                    </div>
                </div>
            </div>
        </div>
        <!-- Reports Button -->
        <div class="row mb-3">
            <div class="col-12">
                <a href="{{ route('customers.reports') }}" class="btn btn-outline-primary">
                    <i class="fas fa-chart-bar"></i> عرض التقارير
                </a>
            </div>
        </div>
        <!-- Customers Table Section -->
        <div class="row">
            <div class="col-12" id="tableContainer">
                <div class="modern-table">
                    <div class="table-header">قائمة العملاء</div>
                    <div class="table-responsive">
                        <table id="customersTable" class="table table-hover">
                            <thead>
                                <tr>
                                    <th><input type="checkbox" id="selectAll"></th>
                                    <th>رقم الحساب</th>
                                    <th>الاسم الكامل</th>
                                    <th>رقم الجوال</th>
                                    <th>البريد الإلكتروني</th>
                                    <th>الحالة</th>
                                    <th>الموظف المسؤول</th>
                                    <th>القسم الفرعي</th>
                                    <th>المدينة</th>
                                    <th>الإجراءات</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($customers as $customer)
                                <tr data-customer-id="{{ $customer->id }}">
                                    <td><input type="checkbox" class="select-checkbox"></td>
                                    <td><strong>{{ $customer->ac_number }}</strong></td>
                                    <td>{{ $customer->full_name }}</td>
                                    <td>{{ $customer->mobile_number }}</td>
                                    <td>{{ $customer->email ?? '-' }}</td>
                                    <td>
                                        @php
                                            $statusColors = [
                                                'new' => 'bg-secondary',
                                                'in_progress' => 'bg-primary',
                                                'follow_up' => 'bg-info',
                                                'western' => 'bg-warning',
                                                'hot' => 'bg-danger',
                                                'closed' => 'bg-success'
                                            ];
                                            $statusColor = $statusColors[$customer->status] ?? 'bg-secondary';
                                        @endphp
                                        <span class="badge {{ $statusColor }} status-badge">{{ $customer->status }}</span>
                                    </td>
                                    <td>{{ $customer->assignedEmployee->name ?? 'غير محدد' }}</td>
                                    <td>{{ $customer->subDepartment->name ?? 'غير محدد' }}</td>
                                    <td>{{ $customer->city ?? '-' }}</td>
                                    <td>
                                        @can('clients.view')
                                        <a href="{{ route('customers.show', $customer->id) }}" class="btn btn-sm btn-primary btn-action">عرض</a>
                                        @endcan
                                        @can('clients.edit')
                                        <a href="{{ route('customers.edit', $customer->id) }}" class="btn btn-sm btn-warning btn-action">تعديل</a>
                                        @endcan
                                        @can('clients.delete')
                                        <button class="btn btn-sm btn-danger btn-action" onclick="deleteCustomer({{ $customer->id }}, '{{ $customer->full_name }}')">حذف</button>
                                        @endcan
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Customer Sidebar (Hidden initially) -->
        <div class="row" id="sidebarContainer" style="display:none;">
            <div class="col-md-9">
                <div class="modern-table">
                    <div class="table-header">قائمة العملاء</div>
                    <div class="table-responsive">
                        <table id="customersTableSidebar" class="table table-hover">
                            <thead>
                                <tr>
                                    <th>رقم الحساب</th>
                                    <th>الاسم الكامل</th>
                                    <th>رقم الجوال</th>
                                    <th>البريد الإلكتروني</th>
                                    <th>الحالة</th>
                                    <th>الموظف المسؤول</th>
                                    <th>القسم الفرعي</th>
                                    <th>المدينة</th>
                                    <th>الإجراءات</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($customers as $customer)
                                <tr data-customer-id="{{ $customer->id }}">
                                    <td><strong>{{ $customer->ac_number }}</strong></td>
                                    <td>{{ $customer->full_name }}</td>
                                    <td>{{ $customer->mobile_number }}</td>
                                    <td>{{ $customer->email ?? '-' }}</td>
                                    <td>
                                        @php
                                            $statusColors = [
                                                'new' => 'bg-secondary',
                                                'in_progress' => 'bg-primary',
                                                'follow_up' => 'bg-info',
                                                'western' => 'bg-warning',
                                                'hot' => 'bg-danger',
                                                'closed' => 'bg-success'
                                            ];
                                            $statusColor = $statusColors[$customer->status] ?? 'bg-secondary';
                                        @endphp
                                        <span class="badge {{ $statusColor }} status-badge">{{ $customer->status }}</span>
                                    </td>
                                    <td>{{ $customer->assignedEmployee->name ?? 'غير محدد' }}</td>
                                    <td>{{ $customer->subDepartment->name ?? 'غير محدد' }}</td>
                                    <td>{{ $customer->city ?? '-' }}</td>
                                    <td>
                                        @can('clients.view')
                                        <a href="{{ route('customers.show', $customer->id) }}" class="btn btn-sm btn-primary btn-action">عرض</a>
                                        @endcan
                                        @can('clients.edit')
                                        <a href="{{ route('customers.edit', $customer->id) }}" class="btn btn-sm btn-warning btn-action">تعديل</a>
                                        @endcan
                                        @can('clients.delete')
                                        <button class="btn btn-sm btn-danger btn-action" onclick="deleteCustomer({{ $customer->id }}, '{{ $customer->full_name }}')">حذف</button>
                                        @endcan
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="customer-sidebar" id="customerSidebar">
                    <div class="sidebar-header d-flex justify-content-between align-items-center">
                        <span>معلومات العميل</span>
                        <button type="button" class="btn-close btn-close-white" onclick="closeSidebar()" aria-label="إغلاق"></button>
                    </div>
                    <div class="sidebar-body" id="customerSidebarContent">
                        <!-- تفاصيل العميل ستظهر هنا -->
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal: إضافة عميل -->
@can('clients.create')
<div class="modal fade" id="addCustomerModal" tabindex="-1" aria-labelledby="addCustomerModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addCustomerModalLabel">إضافة عميل جديد</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="إغلاق"></button>
            </div>
            <div class="modal-body">
                <form action="{{ route('customers.store') }}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label">رقم الحساب</label>
                        <input type="text" class="form-control" name="ac_number" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">الاسم الكامل</label>
                        <input type="text" class="form-control" name="full_name" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">رقم الجوال</label>
                        <input type="text" class="form-control" name="mobile_number" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">البريد الإلكتروني</label>
                        <input type="email" class="form-control" name="email">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">الحالة</label>
                        <select class="form-select" name="status" required>
                            <option value="new">جديد</option>
                            <option value="in_progress">قيد المعالجة</option>
                            <option value="follow_up">متابعة</option>
                            <option value="western">غربي</option>
                            <option value="hot">ساخن</option>
                            <option value="closed">مغلق</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">القسم الفرعي</label>
                        <select class="form-select" name="sub_department_id" required>
                            @foreach($subDepartments as $subDepartment)
                                <option value="{{ $subDepartment->id }}">{{ $subDepartment->name }} - {{ $subDepartment->department->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">الموظف المسؤول</label>
                        <select class="form-select" name="assigned_employee_id">
                            <option value="">اختر موظف</option>
                            @foreach($users as $user)
                                <option value="{{ $user->id }}">{{ $user->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">المدينة</label>
                        <input type="text" class="form-control" name="city">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">ملاحظات</label>
                        <textarea class="form-control" name="notes" rows="3"></textarea>
                    </div>
                    <button type="submit" class="btn btn-success w-100">حفظ العميل</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endcan

<!-- Modal: رفع إكسل -->
<div class="modal fade" id="importModal" tabindex="-1" aria-labelledby="importModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="importModalLabel">رفع ملف إكسل</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="إغلاق"></button>
            </div>
            <div class="modal-body">
                <form action="{{ route('customers.import') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="mb-3">
                        <label for="subDepartmentSelect" class="form-label">القسم الفرعي</label>
                        <select class="form-select" id="subDepartmentSelect" name="sub_department_id" required>
                            <option value="">اختر القسم الفرعي</option>
                            @foreach($subDepartments as $subDepartment)
                                <option value="{{ $subDepartment->id }}">
                                    {{ $subDepartment->department->name }} - {{ $subDepartment->name }}
                                </option>
                            @endforeach
                        </select>
                        <small class="form-text text-muted">سيتم إضافة جميع العملاء المرفوعين إلى هذا القسم الفرعي</small>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">اختر ملف الإكسل</label>
                        <input type="file" class="form-control" name="file" accept=".xlsx,.xls" required>
                        <small class="form-text text-muted">يجب أن يحتوي الملف على الأعمدة: رقم الحساب (مطلوب)، الاسم الكامل، رقم الجوال، البريد الإلكتروني، التعليق، الحالة، سبب الشكوى، الجنسية، المدينة، طريقة التواصل، تواصل مع طرف آخر، طرق الدفع، تاريخ الريادة</small>
                        <div class="mt-2">
                            <a href="{{ route('customers.template') }}" class="btn btn-outline-secondary btn-sm">
                                <i class="fas fa-download"></i> تحميل نموذج الإكسل
                            </a>
                        </div>
                    </div>
                    <button type="submit" class="btn btn-primary w-100">رفع</button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Bulk Update Modal -->
<div class="modal fade" id="bulkUpdateModal" tabindex="-1" aria-labelledby="bulkUpdateModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="bulkUpdateModalLabel">تغيير حالة العملاء المحددين</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="bulkUpdateForm" method="POST" action="{{ route('customers.bulk-update') }}">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="newStatus" class="form-label">الحالة الجديدة</label>
                        <select class="form-select" id="newStatus" name="status" required>
                            <option value="">اختر الحالة</option>
                            <option value="new">جديد</option>
                            <option value="in_progress">قيد التقدم</option>
                            <option value="follow_up">متابعة</option>
                            <option value="western">Western</option>
                            <option value="hot">Hot</option>
                            <option value="closed">مغلق</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <p class="text-muted">سيتم تغيير حالة <strong><span id="modalSelectedCount">0</span></strong> عميل</p>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">إلغاء</button>
                    <button type="submit" class="btn btn-primary">تأكيد التغيير</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Bulk Assignment Modal -->
<div class="modal fade" id="bulkAssignModal" tabindex="-1" aria-labelledby="bulkAssignModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="bulkAssignModalLabel">تخصيص العملاء المحددين لموظف</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="bulkAssignForm" method="POST" action="{{ route('customers.bulk-assign') }}">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="assignedEmployee" class="form-label">الموظف المسؤول</label>
                        <select class="form-select" id="assignedEmployee" name="assigned_employee_id" required>
                            <option value="">اختر الموظف</option>
                            @foreach($users as $user)
                                @if($user->hasRole('employee') || $user->hasRole('admin'))
                                    <option value="{{ $user->id }}">{{ $user->name }}</option>
                                @endif
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <p class="text-muted">سيتم تخصيص <strong><span id="modalSelectedCount2">0</span></strong> عميل للموظف المحدد</p>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">إلغاء</button>
                    <button type="submit" class="btn btn-primary">تأكيد التخصيص</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Delete Customer Form -->
<form id="deleteCustomerForm" method="POST" style="display: none;">
    @csrf
    @method('DELETE')
</form>

<!-- Duplicate Detection Modal -->
<div class="modal fade" id="duplicateDetectionModal" tabindex="-1" aria-labelledby="duplicateDetectionModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="duplicateDetectionModalLabel">
                    @if(session('import_detection') && isset(session('import_detection')['total_duplicates']) && session('import_detection')['total_duplicates'] > 0)
                        العملاء المكررين - اختر الإجراء المطلوب
                    @else
                        تأكيد استيراد العملاء الجدد
                    @endif
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="إغلاق"></button>
            </div>
            <div class="modal-body">
                @if(session('import_detection'))
                    @php $detection = session('import_detection'); @endphp

                    <div class="row mb-4">
                        <div class="col-md-6">
                            <div class="card bg-success text-white">
                                <div class="card-body text-center">
                                    <h4>{{ $detection['total_new'] ?? 0 }}</h4>
                                    <p class="mb-0">عملاء جدد سيتم إضافتهم</p>
                                </div>
                            </div>
                        </div>
                        @if(isset($detection['total_duplicates']) && $detection['total_duplicates'] > 0)
                            <div class="col-md-6">
                                <div class="card bg-warning text-white">
                                    <div class="card-body text-center">
                                        <h4>{{ $detection['total_duplicates'] ?? 0 }}</h4>
                                        <p class="mb-0">عملاء مكررين - اختر الإجراء</p>
                                    </div>
                                </div>
                            </div>
                        @else
                            <div class="col-md-6">
                                <div class="card bg-info text-white">
                                    <div class="card-body text-center">
                                        <h4>0</h4>
                                        <p class="mb-0">عملاء مكررين</p>
                                    </div>
                                </div>
                            </div>
                        @endif
                    </div>

                    @if(isset($detection['duplicates']) && count($detection['duplicates']) > 0)
                        <form id="duplicateActionForm" action="{{ route('customers.handle-duplicates') }}" method="POST">
                            @csrf
                            @if(session('cache_key'))
                                <input type="hidden" name="cache_key" value="{{ session('cache_key') }}">
                            @endif
                            <div class="mb-3">
                                <div class="alert alert-info">
                                    <i class="fas fa-info-circle"></i>
                                    <strong>ملاحظة:</strong> العملاء غير المحددين سيتم تخطيهم تلقائياً
                                </div>
                                <div class="btn-group" role="group">
                                    <input type="radio" class="btn-check" name="action" id="actionUpdate" value="update" checked>
                                    <label class="btn btn-outline-warning" for="actionUpdate">
                                        <i class="fas fa-edit"></i> تحديث العملاء المحددين
                                    </label>

                                    <input type="radio" class="btn-check" name="action" id="actionSkip" value="skip">
                                    <label class="btn btn-outline-secondary" for="actionSkip">
                                        <i class="fas fa-times"></i> تخطي العملاء المحددين
                                    </label>
                                </div>
                            </div>

                            <div class="table-responsive">
                                <table class="table table-sm table-hover">
                                    <thead class="table-light">
                                        <tr>
                                            <th>
                                                <input type="checkbox" id="selectAllDuplicates" class="form-check-input">
                                            </th>
                                            <th>رقم الحساب</th>
                                            <th>الاسم الموجود</th>
                                            <th>رقم الجوال الموجود</th>
                                            <th>الاسم الجديد</th>
                                            <th>رقم الجوال الجديد</th>
                                            <th>الحالة</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($detection['duplicates'] as $index => $duplicate)
                                            <tr>
                                                <td>
                                                    <input type="checkbox" name="selected_duplicates[]" value="{{ $index }}" class="form-check-input duplicate-checkbox">
                                                </td>
                                                <td>{{ $duplicate['ac_number'] }}</td>
                                                <td>{{ $duplicate['existing_customer']['full_name'] ?? 'غير محدد' }}</td>
                                                <td>{{ $duplicate['existing_customer']['mobile_number'] ?? 'غير محدد' }}</td>
                                                <td>{{ $duplicate['new_data']['full_name'] ?? 'غير محدد' }}</td>
                                                <td>{{ $duplicate['new_data']['mobile_number'] ?? 'غير محدد' }}</td>
                                                <td>
                                                    <span class="badge bg-warning">مكرر</span>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </form>
                    @else
                        <!-- All new customers - simple confirmation -->
                        <form id="duplicateActionForm" action="{{ route('customers.handle-duplicates') }}" method="POST">
                            @csrf
                            @if(session('cache_key'))
                                <input type="hidden" name="cache_key" value="{{ session('cache_key') }}">
                            @endif
                            <input type="hidden" name="action" value="import_new">
                            <input type="hidden" name="selected_duplicates" value="">

                            <div class="alert alert-success">
                                <i class="fas fa-check-circle"></i>
                                <strong>جميع العملاء جدد!</strong> سيتم إضافة {{ $detection['total_new'] ?? 0 }} عميل جديد إلى النظام.
                            </div>

                            <div class="text-center">
                                <p class="text-muted">اضغط "تأكيد الإجراء" لإضافة جميع العملاء الجدد</p>
                            </div>
                        </form>
                    @endif
                @endif
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">إلغاء</button>
                <button type="submit" form="duplicateActionForm" class="btn btn-primary" id="confirmActionBtn">
                    <i class="fas fa-check"></i> تأكيد الإجراء
                </button>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    $(document).ready(function() {
        // Initialize DataTable for main table
        var table = $('#customersTable').DataTable({
            language: {
                url: '//cdn.datatables.net/plug-ins/1.13.6/i18n/ar.json'
            },
            pageLength: 25,
            lengthMenu: [[10, 25, 50, 100, -1], [10, 25, 50, 100, "الكل"]],
            order: [[0, 'desc']],
            responsive: true,
            dom: '<"row"<"col-sm-12 col-md-6"l><"col-sm-12 col-md-6"f>>' +
                 '<"row"<"col-sm-12"tr>>' +
                 '<"row"<"col-sm-12 col-md-5"i><"col-sm-12 col-md-7"p>>',
            initComplete: function () {
                // Add custom search functionality
                this.api().columns().every(function () {
                    var column = this;
                    var title = column.header().textContent;

                    // Create search input for each column
                    if (title !== 'الإجراءات') {
                        var input = $('<input class="form-control form-control-sm" type="text" placeholder="بحث في ' + title + '" />')
                            .appendTo($(column.header()))
                            .on('keyup change', function () {
                                if (column.search() !== this.value) {
                                    column.search(this.value).draw();
                                }
                            });
                    }
                });
            },
            drawCallback: function() {
                // Add row selection highlighting
                $('#customersTable tbody tr').click(function() {
                    $('#customersTable tbody tr').removeClass('selected');
                    $(this).addClass('selected');
                });
            }
        });

        // Initialize DataTable for sidebar table
        var tableSidebar = $('#customersTableSidebar').DataTable({
            language: {
                url: '//cdn.datatables.net/plug-ins/1.13.6/i18n/ar.json'
            },
            pageLength: 25,
            lengthMenu: [[10, 25, 50, 100, -1], [10, 25, 50, 100, "الكل"]],
            order: [[0, 'desc']],
            responsive: true,
            dom: '<"row"<"col-sm-12 col-md-6"l><"col-sm-12 col-md-6"f>>' +
                 '<"row"<"col-sm-12"tr>>' +
                 '<"row"<"col-sm-12 col-md-5"i><"col-sm-12 col-md-7"p>>',
            initComplete: function () {
                // Add custom search functionality
                this.api().columns().every(function () {
                    var column = this;
                    var title = column.header().textContent;

                    // Create search input for each column
                    if (title !== 'الإجراءات') {
                        var input = $('<input class="form-control form-control-sm" type="text" placeholder="بحث في ' + title + '" />')
                            .appendTo($(column.header()))
                            .on('keyup change', function () {
                                if (column.search() !== this.value) {
                                    column.search(this.value).draw();
                                }
                            });
                    }
                });
            },
            drawCallback: function() {
                // Add row selection highlighting
                $('#customersTableSidebar tbody tr').click(function() {
                    $('#customersTableSidebar tbody tr').removeClass('selected');
                    $(this).addClass('selected');
                });
            }
        });

        // عند الضغط على صف في الجدول الرئيسي، عرض تفاصيل العميل في الشريط الجانبي
        $('#customersTable tbody').on('click', 'tr', function (e) {
            // Don't trigger if clicking on checkbox or its label
            if ($(e.target).is('input[type="checkbox"]') || $(e.target).is('label') || $(e.target).closest('label').length) {
                return;
            }

            var customerId = $(this).data('customer-id');
            if (customerId) {
                loadCustomerDetails(customerId);
                $('#tableContainer').hide();
                $('#sidebarContainer').show();
            }
        });

        // عند الضغط على صف في جدول الشريط الجانبي، عرض تفاصيل العميل
        $('#customersTableSidebar tbody').on('click', 'tr', function () {
            var customerId = $(this).data('customer-id');
            if (customerId) {
                loadCustomerDetails(customerId);
            }
        });

        // Add keyboard navigation
        $(document).keydown(function(e) {
            if (e.keyCode === 27) { // ESC key
                closeSidebar();
            }
        });

        // Add search highlight functionality
        $('.dataTables_filter input').on('keyup', function() {
            var searchTerm = $(this).val();
            if (searchTerm.length > 0) {
                $('.dataTables_filter input').addClass('searching');
            } else {
                $('.dataTables_filter input').removeClass('searching');
            }
        });

        // Bulk selection functionality
        $('#selectAll').on('change', function() {
            var isChecked = $(this).is(':checked');
            $('.select-checkbox').prop('checked', isChecked);
            updateBulkButton();
        });

        $(document).on('change', '.select-checkbox', function() {
            updateBulkButton();

            // Update select all checkbox
            var totalCheckboxes = $('.select-checkbox').length;
            var checkedCheckboxes = $('.select-checkbox:checked').length;

            if (checkedCheckboxes === 0) {
                $('#selectAll').prop('indeterminate', false).prop('checked', false);
            } else if (checkedCheckboxes === totalCheckboxes) {
                $('#selectAll').prop('indeterminate', false).prop('checked', true);
            } else {
                $('#selectAll').prop('indeterminate', true);
            }
        });

        // Bulk update button click
        $('#bulkStatusBtn').on('click', function() {
            var selectedIds = getSelectedCustomerIds();
            if (selectedIds.length > 0) {
                $('#modalSelectedCount').text(selectedIds.length);
                $('#bulkUpdateModal').modal('show');
            }
        });

        // Bulk update form submission
        $('#bulkUpdateForm').on('submit', function(e) {
            e.preventDefault();

            var selectedIds = getSelectedCustomerIds();
            var newStatus = $('#newStatus').val();

            if (selectedIds.length === 0) {
                alert('الرجاء تحديد العملاء المراد تغيير حالتهم');
                return;
            }

            if (!newStatus) {
                alert('الرجاء اختيار الحالة الجديدة');
                return;
            }

            // Add selected IDs to form
            selectedIds.forEach(function(id) {
                $('<input>').attr({
                    type: 'hidden',
                    name: 'customer_ids[]',
                    value: id
                }).appendTo('#bulkUpdateForm');
            });

            // Submit form
            $.ajax({
                url: $('#bulkUpdateForm').attr('action'),
                method: 'POST',
                data: $('#bulkUpdateForm').serialize(),
                success: function(response) {
                    if (response.success) {
                        $('#bulkUpdateModal').modal('hide');
                        alert('تم تحديث حالة العملاء بنجاح');
                        location.reload(); // Refresh page to show updated data
                    } else {
                        alert('حدث خطأ أثناء تحديث حالة العملاء');
                    }
                },
                error: function() {
                    alert('حدث خطأ أثناء تحديث حالة العملاء');
                }
            });
        });

        // Show duplicate detection modal if there are duplicates
        @if(session('import_detection'))
            $('#duplicateDetectionModal').modal('show');
        @endif

        // Handle duplicate action form submission
        $('#duplicateActionForm').on('submit', function(e) {
            e.preventDefault();
            console.log('Form submission started');

            var formData = $(this).serialize();
            console.log('Form data:', formData);

            // Show loading state
            $('#confirmActionBtn').prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i> جاري المعالجة...');

            $.ajax({
                url: $(this).attr('action'),
                method: 'POST',
                data: formData,
                success: function(response) {
                    console.log('Success response:', response);
                    $('#duplicateDetectionModal').modal('hide');
                    location.reload(); // Refresh to show new data
                },
                error: function(xhr, status, error) {
                    console.log('Error:', xhr.responseText);
                    alert('حدث خطأ أثناء معالجة الطلب. يرجى المحاولة مرة أخرى.');
                    $('#confirmActionBtn').prop('disabled', false).html('<i class="fas fa-check"></i> تأكيد الإجراء');
                }
            });
        });

        // Duplicate selection functionality
        $('#selectAllDuplicates').on('change', function() {
            var isChecked = $(this).is(':checked');
            $('.duplicate-checkbox').prop('checked', isChecked);
        });

        // Fallback button click handler
        $('#confirmActionBtn').on('click', function(e) {
            console.log('Button clicked');
            // The form submit handler should take care of this, but this is a fallback
        });

        $(document).on('change', '.duplicate-checkbox', function() {
            // Update select all checkbox
            var totalCheckboxes = $('.duplicate-checkbox').length;
            var checkedCheckboxes = $('.duplicate-checkbox:checked').length;

            if (checkedCheckboxes === 0) {
                $('#selectAllDuplicates').prop('checked', false);
            } else if (checkedCheckboxes === totalCheckboxes) {
                $('#selectAllDuplicates').prop('checked', true);
            } else {
                $('#selectAllDuplicates').prop('checked', false);
            }
        });

        function updateBulkButton() {
            var selectedCount = $('.select-checkbox:checked').length;
            $('#selectedCount').text(selectedCount);
            $('#selectedCount2').text(selectedCount);

            if (selectedCount > 0) {
                $('#bulkStatusBtn').prop('disabled', false);
                $('#bulkAssignBtn').prop('disabled', false);
            } else {
                $('#bulkStatusBtn').prop('disabled', true);
                $('#bulkAssignBtn').prop('disabled', true);
            }
        }

        function getSelectedCustomerIds() {
            var ids = [];
            $('.select-checkbox:checked').each(function() {
                var customerId = $(this).closest('tr').data('customer-id');
                if (customerId) {
                    ids.push(customerId);
                }
            });
            return ids;
        }

        // Bulk assignment button click
        $('#bulkAssignBtn').on('click', function() {
            var selectedIds = getSelectedCustomerIds();
            if (selectedIds.length > 0) {
                $('#modalSelectedCount2').text(selectedIds.length);
                $('#bulkAssignModal').modal('show');
            }
        });

        // Bulk assignment form submission
        $('#bulkAssignForm').on('submit', function(e) {
            e.preventDefault();

            var selectedIds = getSelectedCustomerIds();
            var assignedEmployeeId = $('#assignedEmployee').val();

            if (selectedIds.length === 0) {
                alert('الرجاء تحديد العملاء المراد تخصيصهم');
                return;
            }

            if (!assignedEmployeeId) {
                alert('الرجاء اختيار الموظف المسؤول');
                return;
            }

            // Add selected IDs to form
            selectedIds.forEach(function(id) {
                $('<input>').attr({
                    type: 'hidden',
                    name: 'customer_ids[]',
                    value: id
                }).appendTo('#bulkAssignForm');
            });

            // Submit form
            $.ajax({
                url: $('#bulkAssignForm').attr('action'),
                method: 'POST',
                data: $('#bulkAssignForm').serialize(),
                success: function(response) {
                    if (response.success) {
                        $('#bulkAssignModal').modal('hide');
                        alert('تم تخصيص العملاء للموظف بنجاح');
                        location.reload(); // Refresh page to show updated data
                    } else {
                        alert('حدث خطأ أثناء تخصيص العملاء');
                    }
                },
                error: function() {
                    alert('حدث خطأ أثناء تخصيص العملاء');
                }
            });
        });

        // Export filtered data
        $('#exportExcelBtn').on('click', function() {
            var filteredData = table.rows({ search: 'applied' }).data();
            var customerIds = [];
            for (var i = 0; i < filteredData.length; i++) {
                // The first column is the checkbox, the second is ac_number, so get the row's data-customer-id
                var row = table.row(i, { search: 'applied' }).node();
                if (row) {
                    var customerId = $(row).data('customer-id');
                    if (customerId) customerIds.push(customerId);
                }
            }
            if (customerIds.length === 0) {
                alert('لا يوجد عملاء لتصديرهم');
                return;
            }
            // Create a form and submit it
            var form = $('<form>', {
                method: 'POST',
                action: '{{ route('customers.export') }}'
            });
            form.append('@csrf');
            customerIds.forEach(function(id) {
                form.append($('<input>', {
                    type: 'hidden',
                    name: 'customer_ids[]',
                    value: id
                }));
            });
            $('body').append(form);
            form.submit();
        });
    });

    function loadCustomerDetails(customerId) {
        $('#customerSidebarContent').html('<p>جاري تحميل تفاصيل العميل...</p>');

        $.ajax({
            url: '{{ url("customers") }}/' + customerId,
            method: 'GET',
            success: function(response) {
                var customer = response;
                var statusColors = {
                    'new': 'bg-secondary',
                    'in_progress': 'bg-primary',
                    'follow_up': 'bg-info',
                    'western': 'bg-warning',
                    'hot': 'bg-danger',
                    'closed': 'bg-success'
                };
                var statusColor = statusColors[customer.status] || 'bg-secondary';

                var html = `
                    <div class="info-row">
                        <span class="info-label">الاسم الكامل:</span>
                        <span class="info-value">${customer.full_name}</span>
                    </div>
                    <div class="info-row">
                        <span class="info-label">رقم الحساب:</span>
                        <span class="info-value">${customer.ac_number}</span>
                    </div>
                    <div class="info-row">
                        <span class="info-label">رقم الهاتف:</span>
                        <span class="info-value">${customer.mobile_number}</span>
                    </div>
                    <div class="info-row">
                        <span class="info-label">البريد الإلكتروني:</span>
                        <span class="info-value">${customer.email || '-'}</span>
                    </div>
                    <div class="info-row">
                        <span class="info-label">الحالة:</span>
                        <span class="badge ${statusColor} status-badge">${customer.status}</span>
                    </div>
                    <div class="info-row">
                        <span class="info-label">الموظف المسؤول:</span>
                        <span class="info-value">${customer.assigned_employee ? customer.assigned_employee.name : 'غير محدد'}</span>
                    </div>
                    <div class="info-row">
                        <span class="info-label">القسم الفرعي:</span>
                        <span class="info-value">${customer.sub_department ? customer.sub_department.name : 'غير محدد'}</span>
                    </div>
                    <div class="info-row">
                        <span class="info-label">القسم الرئيسي:</span>
                        <span class="info-value">${customer.sub_department && customer.sub_department.department ? customer.sub_department.department.name : 'غير محدد'}</span>
                    </div>
                    <div class="info-row">
                        <span class="info-label">الجنسية:</span>
                        <span class="info-value">${customer.nationality || '-'}</span>
                    </div>
                    <div class="info-row">
                        <span class="info-label">المدينة:</span>
                        <span class="info-value">${customer.city || '-'}</span>
                    </div>
                    <div class="info-row">
                        <span class="info-label">طريقة التواصل:</span>
                        <span class="info-value">${customer.contact_method || '-'}</span>
                    </div>
                    <div class="info-row">
                        <span class="info-label">تاريخ الإنشاء:</span>
                        <span class="info-value">${customer.created_at}</span>
                    </div>
                    <div class="info-row">
                        <span class="info-label">آخر تحديث:</span>
                        <span class="info-value">${customer.updated_at}</span>
                    </div>
                    ${customer.comment ? `
                    <div class="info-row">
                        <span class="info-label">التعليق:</span>
                        <span class="info-value">${customer.comment}</span>
                    </div>
                    ` : ''}
                    <div class="mt-3">
                        <a href="{{ url('customers') }}/${customerId}" class="btn btn-primary btn-sm w-100 mb-2">عرض التفاصيل الكاملة</a>
                        <a href="{{ url('customers') }}/${customerId}/edit" class="btn btn-warning btn-sm w-100">تعديل العميل</a>
                    </div>
                `;

                $('#customerSidebarContent').html(html);
            },
            error: function() {
                $('#customerSidebarContent').html('<p class="text-danger">حدث خطأ في تحميل تفاصيل العميل</p>');
            }
        });
    }

    function closeSidebar() {
        $('#tableContainer').show();
        $('#sidebarContainer').hide();
    }

    function deleteCustomer(id, name) {
        if (confirm('هل أنت متأكد من حذف العميل "' + name + '"؟')) {
            const form = document.getElementById('deleteCustomerForm');
            form.action = '{{ url("customers") }}/' + id;
            form.submit();
        }
    }
</script>
@endsection
