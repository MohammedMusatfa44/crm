@extends('layouts.layout')

@section('title', 'إدارة المستخدمين')

@section('styles')
<style>
    .dashboard-bg {
        background: #f8f9fa;
        min-height: 100vh;
        padding: 2rem 0;
    }
    .dashboard-header-card {
        background: #fff;
        border-radius: 1.2rem;
        box-shadow: 0 2px 12px rgba(33,150,243,0.08);
        padding: 2rem;
        margin-bottom: 2rem;
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
    }
    .top-actions .btn {
        border-radius: 1.2rem;
        font-weight: 500;
        font-size: 1rem;
        padding: 0.5rem 1.2rem;
    }
    .top-actions .btn-add {
        background: #0b58ca;
        color: #fff;
        border: none;
    }
    .top-actions .btn-add:hover {
        background: #1976d2;
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
    .stat-card.super-admin {
        background: linear-gradient(135deg, #4f5bd5 0%, #7f53ac 100%);
    }
    .stat-card.admin {
        background: linear-gradient(135deg, #4f5bd5 0%, #43cea2 100%);
    }
    .stat-card.employee {
        background: linear-gradient(135deg, #4f5bd5 0%, #5f8fff 100%);
    }
    .stat-card.active {
        background: linear-gradient(135deg, #4f5bd5 0%, #28a745 100%);
    }
    .modern-table {
        background: #fff;
        border-radius: 1.2rem;
        box-shadow: 0 2px 12px rgba(33,150,243,0.08);
        overflow: hidden;
        margin-bottom: 2rem;
    }
    .table-header {
        background: linear-gradient(135deg, #4f5bd5 0%, #5f8fff 100%);
        color: #fff;
        padding: 1.5rem;
        font-size: 1.2rem;
        font-weight: 600;
    }
    .table-responsive {
        border-radius: 1.2rem;
        overflow: hidden;
    }
    .table {
        margin-bottom: 0;
    }
    .table th {
        background: #f8f9fa;
        border: none;
        padding: 1rem;
        font-weight: 600;
        color: #333;
        font-size: 0.9rem;
        text-align: center;
    }
    .table td {
        border: none;
        padding: 1rem;
        vertical-align: middle;
        text-align: center;
        border-bottom: 1px solid #f1f3f4;
    }
    .table tbody tr:hover {
        background: #f8f9fa;
        transform: scale(1.01);
        transition: all 0.2s ease;
    }
    .table tbody tr:nth-child(even) {
        background: #fafbfc;
    }
    .status-badge {
        padding: 0.4rem 0.8rem;
        border-radius: 0.8rem;
        font-size: 0.8rem;
        font-weight: 500;
    }
    .status-badge.active {
        background: #d4edda;
        color: #155724;
    }
    .status-badge.inactive {
        background: #f8d7da;
        color: #721c24;
    }
    .role-badge {
        padding: 0.4rem 0.8rem;
        border-radius: 0.8rem;
        font-size: 0.8rem;
        font-weight: 500;
    }
    .role-badge.super-admin {
        background: #e3d5f5;
        color: #5a2d82;
    }
    .role-badge.admin {
        background: #d1ecf1;
        color: #0c5460;
    }
    .role-badge.employee {
        background: #d4edda;
        color: #155724;
    }
    .btn-action {
        padding: 0.4rem 0.8rem;
        border-radius: 0.6rem;
        font-size: 0.8rem;
        font-weight: 500;
        margin: 0.1rem;
        border: none;
        transition: all 0.2s ease;
    }
    .btn-action:hover {
        transform: translateY(-1px);
    }
    .btn-edit {
        background: #007bff;
        color: #fff;
    }
    .btn-edit:hover {
        background: #0056b3;
    }
    .btn-delete {
        background: #dc3545;
        color: #fff;
    }
    .btn-delete:hover {
        background: #c82333;
    }
    .btn-toggle {
        background: #ffc107;
        color: #212529;
    }
    .btn-toggle:hover {
        background: #e0a800;
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
            padding: 0.3rem 0.6rem;
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
            padding: 0.25rem 0.5rem;
            margin: 0.1rem;
        }
        .status-badge, .role-badge {
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
</style>
@endsection

@section('content')
<div class="dashboard-bg">
    <div class="container-fluid">
        <!-- Header Card -->
        <div class="dashboard-header-card">
            <div class="row align-items-center">
                <div class="col-md-6">
                    <h1 class="dashboard-title">إدارة المستخدمين</h1>
                    <p class="dashboard-subtitle">عرض وإدارة جميع المستخدمين في النظام</p>
                </div>
                <div class="col-md-6">
                    <div class="top-actions">
                        <button class="btn btn-add" data-bs-toggle="modal" data-bs-target="#addUserModal">
                            <i class="fas fa-plus"></i> إضافة مستخدم جديد
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Statistics Cards -->
        <div class="row">
            <div class="col-md-3">
                <div class="stat-card super-admin">
                    <div class="stat-label">مدير عام</div>
                    <div class="stat-value">{{ $users->where('role', 'super_admin')->count() }}</div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="stat-card admin">
                    <div class="stat-label">مدير</div>
                    <div class="stat-value">{{ $users->where('role', 'admin')->count() }}</div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="stat-card employee">
                    <div class="stat-label">موظف</div>
                    <div class="stat-value">{{ $users->where('role', 'employee')->count() }}</div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="stat-card active">
                    <div class="stat-label">نشط</div>
                    <div class="stat-value">{{ $users->where('is_active', true)->count() }}</div>
                </div>
            </div>
        </div>

        <!-- Users Table -->
        <div class="modern-table">
            <div class="table-header">
                <i class="fas fa-users"></i> قائمة المستخدمين
            </div>
            <div class="table-responsive">
                <table id="usersTable" class="table table-hover">
                    <thead>
                        <tr>
                            <th>الاسم</th>
                            <th>البريد الإلكتروني</th>
                            <th>الدور</th>
                            <th>الحالة</th>
                            <th>رقم الجوال</th>
                            <th>الجلسات النشطة</th>
                            <th>آخر تسجيل دخول</th>
                            <th>خيارات</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($users as $user)
                            <tr>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="avatar-sm me-2">
                                            <div class="avatar-title bg-primary rounded-circle">
                                                {{ strtoupper(substr($user->name, 0, 1)) }}
                                            </div>
                                        </div>
                                        <div>
                                            <div class="fw-bold">{{ $user->name }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td>{{ $user->email }}</td>
                                <td>
                                    @if($user->role == 'super_admin')
                                        <span class="role-badge super-admin">مدير عام</span>
                                    @elseif($user->role == 'admin')
                                        <span class="role-badge admin">مدير</span>
                                    @else
                                        <span class="role-badge employee">موظف</span>
                                    @endif
                                </td>
                                <td>
                                    @if($user->is_active)
                                        <span class="status-badge active">نشط</span>
                                    @else
                                        <span class="status-badge inactive">غير نشط</span>
                                    @endif
                                </td>
                                <td>{{ $user->phone ?? '-' }}</td>
                                <td>
                                    <span class="badge bg-info">{{ $user->sessions()->where('is_active', true)->count() }}</span>
                                </td>
                                <td>
                                    @php
                                        $lastSession = $user->sessions()->latest('login_at')->first();
                                    @endphp
                                    @if($lastSession)
                                        <small class="text-muted">{{ $lastSession->login_at->diffForHumans() }}</small>
                                    @else
                                        <span class="text-muted">-</span>
                                    @endif
                                </td>
                                <td>
                                    <button class="btn btn-action btn-edit" title="تعديل">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                    <button class="btn btn-action btn-toggle" title="تغيير الحالة">
                                        <i class="fas fa-toggle-on"></i>
                                    </button>
                                    <button class="btn btn-action btn-delete" title="حذف">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Modal: إضافة مستخدم -->
<div class="modal fade" id="addUserModal" tabindex="-1" aria-labelledby="addUserModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addUserModalLabel">إضافة مستخدم جديد</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="إغلاق"></button>
            </div>
            <div class="modal-body">
                <form>
                    <div class="mb-3">
                        <label class="form-label">الاسم</label>
                        <input type="text" class="form-control">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">البريد الإلكتروني</label>
                        <input type="email" class="form-control">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">الدور</label>
                        <select class="form-select">
                            <option value="super_admin">مدير عام</option>
                            <option value="admin">مدير</option>
                            <option value="employee">موظف</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">رقم الجوال</label>
                        <input type="text" class="form-control">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">كلمة المرور</label>
                        <input type="password" class="form-control">
                    </div>
                    <button type="submit" class="btn btn-success w-100">حفظ</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
$(document).ready(function() {
    $('#usersTable').DataTable({
        language: {
            url: '//cdn.datatables.net/plug-ins/1.13.6/i18n/ar.json'
        },
        pageLength: 10,
        lengthMenu: [[10, 25, 50, -1], [10, 25, 50, "الكل"]],
        order: [[0, 'asc']],
        responsive: true,
        dom: '<"row"<"col-sm-12 col-md-6"l><"col-sm-12 col-md-6"f>>' +
             '<"row"<"col-sm-12"tr>>' +
             '<"row"<"col-sm-12 col-md-5"i><"col-sm-12 col-md-7"p>>',
        initComplete: function () {
            // Remove individual column search
        }
    });
});
</script>
@endsection
