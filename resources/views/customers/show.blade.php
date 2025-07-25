@extends('layouts.layout')

@section('title', $customer->full_name)

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
    }
    .top-actions .btn {
        border-radius: 1.2rem;
        font-weight: 500;
        font-size: 1rem;
        padding: 0.5rem 1.2rem;
    }
    .top-actions .btn-edit {
        background: #ffc107;
        color: #000;
        border: none;
    }
    .top-actions .btn-edit:hover {
        background: #e0a800;
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
    .stat-card.notes {
        background: linear-gradient(135deg, #4f5bd5 0%, #7f53ac 100%);
    }
    .stat-card.days {
        background: linear-gradient(135deg, #4f5bd5 0%, #43cea2 100%);
    }
    .stat-card.status {
        background: linear-gradient(135deg, #4f5bd5 0%, #ff6b6b 100%);
    }
    .info-card {
        background: #fff;
        border-radius: 1.2rem;
        box-shadow: 0 2px 12px rgba(33,150,243,0.08);
        padding: 1.5rem;
        margin-bottom: 1.5rem;
    }
    .info-card .card-title {
        font-size: 1.2rem;
        font-weight: 600;
        margin-bottom: 1rem;
        color: #222;
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
    .status-badge {
        font-size: 0.9rem;
        padding: 0.4rem 0.8rem;
    }
    .notes-section {
        margin-top: 2rem;
    }
    .notes-title {
        font-size: 1.3rem;
        font-weight: 600;
        margin-bottom: 1.2rem;
        color: #222;
    }
    .note-card {
        background: #fff;
        border-radius: 1.2rem;
        box-shadow: 0 2px 12px rgba(33,150,243,0.08);
        padding: 1.5rem;
        margin-bottom: 1rem;
        border-left: 4px solid #0b58ca;
    }
    .note-header {
        display: flex;
        justify-content: between;
        align-items: center;
        margin-bottom: 0.5rem;
    }
    .note-author {
        font-weight: 500;
        color: #0b58ca;
    }
    .note-date {
        font-size: 0.9rem;
        color: #888;
    }
</style>
@endsection

@section('content')
<div class="dashboard-bg">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-12">
                <div class="dashboard-header-card" style="background:#fff; border-radius:1.2rem; box-shadow:0 2px 12px rgba(33,150,243,0.07); padding:1.5rem 2rem 1.2rem 2rem; margin-bottom:1.5rem; display:flex; flex-wrap:wrap; align-items:center; justify-content:space-between;">
                    <div>
                        <div class="dashboard-title">{{ $customer->full_name }}</div>
                        <div class="dashboard-subtitle">رقم الحساب: {{ $customer->ac_number }}</div>
                    </div>
                    <div class="top-actions">
                        <a href="{{ route('customers.edit', $customer->id) }}" class="btn btn-edit">تعديل العميل</a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Statistics Cards -->
        <div class="row mb-4 g-4">
            <div class="col-md-3">
                <div class="stat-card notes">
                    <div class="stat-label">عدد الملاحظات</div>
                    <div class="stat-value">{{ $customer->notes->count() }}</div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="stat-card days">
                    <div class="stat-label">أيام في النظام</div>
                    <div class="stat-value">{{ $customer->created_at ? $customer->created_at->diffInDays(now()) : 0 }}</div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="stat-card status">
                    <div class="stat-label">الحالة</div>
                    <div class="stat-value">{{ $customer->status }}</div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="stat-card">
                    <div class="stat-label">القسم الفرعي</div>
                    <div class="stat-value">{{ $customer->subDepartment->name ?? 'غير محدد' }}</div>
                </div>
            </div>
        </div>

        <!-- Customer Information -->
        <div class="row">
            <div class="col-md-6">
                <div class="info-card">
                    <div class="card-title">معلومات العميل</div>
                    <div class="info-row">
                        <span class="info-label">الاسم الكامل:</span>
                        <span class="info-value">{{ $customer->full_name }}</span>
                    </div>
                    <div class="info-row">
                        <span class="info-label">رقم الحساب:</span>
                        <span class="info-value">{{ $customer->ac_number }}</span>
                    </div>
                    <div class="info-row">
                        <span class="info-label">رقم الهاتف:</span>
                        <span class="info-value">{{ $customer->mobile_number }}</span>
                    </div>
                    @if($customer->email)
                    <div class="info-row">
                        <span class="info-label">البريد الإلكتروني:</span>
                        <span class="info-value">{{ $customer->email }}</span>
                    </div>
                    @endif
                    <div class="info-row">
                        <span class="info-label">الحالة:</span>
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
                    </div>
                    @if($customer->nationality)
                    <div class="info-row">
                        <span class="info-label">الجنسية:</span>
                        <span class="info-value">{{ $customer->nationality }}</span>
                    </div>
                    @endif
                    @if($customer->city)
                    <div class="info-row">
                        <span class="info-label">المدينة:</span>
                        <span class="info-value">{{ $customer->city }}</span>
                    </div>
                    @endif
                </div>
            </div>

            <div class="col-md-6">
                <div class="info-card">
                    <div class="card-title">معلومات إضافية</div>
                    <div class="info-row">
                        <span class="info-label">القسم الرئيسي:</span>
                        <span class="info-value">{{ $customer->subDepartment->department->name ?? 'غير محدد' }}</span>
                    </div>
                    <div class="info-row">
                        <span class="info-label">القسم الفرعي:</span>
                        <span class="info-value">{{ $customer->subDepartment->name ?? 'غير محدد' }}</span>
                    </div>
                    <div class="info-row">
                        <span class="info-label">الموظف المسؤول:</span>
                        <span class="info-value">{{ $customer->assignedEmployee->name ?? 'غير محدد' }}</span>
                    </div>
                    @if($customer->contact_method)
                    <div class="info-row">
                        <span class="info-label">طريقة التواصل:</span>
                        <span class="info-value">{{ $customer->contact_method }}</span>
                    </div>
                    @endif
                    @if($customer->lead_date)
                    <div class="info-row">
                        <span class="info-label">تاريخ الريادة:</span>
                        <span class="info-value">{{ $customer->lead_date }}</span>
                    </div>
                    @endif
                    <div class="info-row">
                        <span class="info-label">تاريخ الإنشاء:</span>
                        <span class="info-value">{{ $customer->created_at->format('Y-m-d H:i') }}</span>
                    </div>
                    <div class="info-row">
                        <span class="info-label">آخر تحديث:</span>
                        <span class="info-value">{{ $customer->updated_at->format('Y-m-d H:i') }}</span>
                    </div>
                </div>
            </div>
        </div>

        @if($customer->comment)
        <div class="row">
            <div class="col-12">
                <div class="info-card">
                    <div class="card-title">التعليق</div>
                    <p class="mb-0">{{ $customer->comment }}</p>
                </div>
            </div>
        </div>
        @endif

        <!-- Notes Section -->
        <div class="notes-section">
            <div class="notes-title">ملاحظات العميل</div>
            @if($customer->notes->count() > 0)
                @foreach($customer->notes as $note)
                    <div class="note-card">
                        <div class="note-header">
                            <span class="note-author">{{ $note->user->name }}</span>
                            <span class="note-date">{{ $note->created_at->format('Y-m-d H:i') }}</span>
                        </div>
                        <p class="mb-0">{{ $note->note }}</p>
                    </div>
                @endforeach
            @else
                <div class="text-center text-muted py-5">
                    <i class="bi bi-chat-text" style="font-size: 3rem; opacity: 0.5;"></i>
                    <p class="mt-3">لا توجد ملاحظات لهذا العميل</p>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
