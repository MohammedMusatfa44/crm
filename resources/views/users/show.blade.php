@extends('layouts.layout')

@section('title', 'تفاصيل المستخدم')

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
    .user-details-card {
        background: #fff;
        border-radius: 1.2rem;
        box-shadow: 0 2px 12px rgba(33,150,243,0.08);
        padding: 2rem;
        margin-bottom: 2rem;
    }
    .user-avatar {
        width: 120px;
        height: 120px;
        border-radius: 50%;
        background: linear-gradient(135deg, #4f5bd5 0%, #5f8fff 100%);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 3rem;
        font-weight: bold;
        color: #fff;
        margin: 0 auto 1.5rem;
    }
    .detail-row {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 1rem 0;
        border-bottom: 1px solid #f1f3f4;
    }
    .detail-row:last-child {
        border-bottom: none;
    }
    .detail-label {
        font-weight: 600;
        color: #333;
        font-size: 1rem;
    }
    .detail-value {
        color: #666;
        font-size: 1rem;
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
    .btn-back {
        background: #6c757d;
        color: #fff;
        border: none;
        border-radius: 0.8rem;
        padding: 0.5rem 1.5rem;
        font-weight: 500;
        text-decoration: none;
        display: inline-block;
        transition: background 0.2s;
    }
    .btn-back:hover {
        background: #5a6268;
        color: #fff;
        text-decoration: none;
    }
    .btn-edit {
        background: #007bff;
        color: #fff;
        border: none;
        border-radius: 0.8rem;
        padding: 0.5rem 1.5rem;
        font-weight: 500;
        text-decoration: none;
        display: inline-block;
        transition: background 0.2s;
    }
    .btn-edit:hover {
        background: #0056b3;
        color: #fff;
        text-decoration: none;
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
                    <h1 class="dashboard-title">تفاصيل المستخدم</h1>
                    <p class="dashboard-subtitle">عرض معلومات المستخدم بالتفصيل</p>
                </div>
                <div class="col-md-6 text-end">
                    <a href="{{ route('users.index') }}" class="btn-back me-2">
                        رجوع
                    </a>
                    @can('users.edit')
                    <a href="{{ route('users.edit', $user->id) }}" class="btn-edit">
                        تعديل
                    </a>
                    @endcan
                </div>
            </div>
        </div>

        <!-- User Details Card -->
        <div class="user-details-card">
            <div class="user-avatar">
                {{ strtoupper(substr($user->name, 0, 1)) }}
            </div>

            <div class="row">
                <div class="col-md-8 mx-auto">
                    <div class="detail-row">
                        <span class="detail-label">الاسم:</span>
                        <span class="detail-value">{{ $user->name }}</span>
                    </div>

                    <div class="detail-row">
                        <span class="detail-label">البريد الإلكتروني:</span>
                        <span class="detail-value">{{ $user->email }}</span>
                    </div>

                    <div class="detail-row">
                        <span class="detail-label">رقم الجوال:</span>
                        <span class="detail-value">{{ $user->phone ?? 'غير محدد' }}</span>
                    </div>

                    <div class="detail-row">
                        <span class="detail-label">الدور:</span>
                        <span class="detail-value">
                            @php
                                $userRole = $user->roles->first();
                                $roleName = $userRole ? $userRole->name : 'employee';
                            @endphp
                            @if($roleName == 'super_admin')
                                <span class="role-badge super-admin">مدير عام</span>
                            @elseif($roleName == 'admin')
                                <span class="role-badge admin">مدير</span>
                            @else
                                <span class="role-badge employee">موظف</span>
                            @endif
                        </span>
                    </div>

                    <div class="detail-row">
                        <span class="detail-label">الحالة:</span>
                        <span class="detail-value">
                            @if($user->is_active)
                                <span class="status-badge active">نشط</span>
                            @else
                                <span class="status-badge inactive">غير نشط</span>
                            @endif
                        </span>
                    </div>

                    <div class="detail-row">
                        <span class="detail-label">تاريخ الإنشاء:</span>
                        <span class="detail-value">{{ $user->created_at->format('d/m/Y H:i') }}</span>
                    </div>

                    <div class="detail-row">
                        <span class="detail-label">آخر تحديث:</span>
                        <span class="detail-value">{{ $user->updated_at->format('d/m/Y H:i') }}</span>
                    </div>

                    <div class="detail-row">
                        <span class="detail-label">الجلسات النشطة:</span>
                        <span class="detail-value">{{ $user->sessions()->where('is_active', true)->count() }}</span>
                    </div>

                    <div class="detail-row">
                        <span class="detail-label">آخر تسجيل دخول:</span>
                        <span class="detail-value">
                            @php
                                $lastSession = $user->sessions()->latest('login_at')->first();
                            @endphp
                            @if($lastSession)
                                {{ $lastSession->login_at->format('d/m/Y H:i') }}
                            @else
                                لم يسجل دخول بعد
                            @endif
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
// Any additional JavaScript can be added here
</script>
@endsection
