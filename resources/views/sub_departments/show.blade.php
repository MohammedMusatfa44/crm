@extends('layouts.layout')

@section('title', $subDepartment->name)

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
    .customers-section {
        margin-top: 2rem;
    }
    .customers-title {
        font-size: 1.3rem;
        font-weight: 600;
        margin-bottom: 1.2rem;
        color: #222;
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
    }
    .table th {
        background: #f8f9fa;
        border: none;
        font-weight: 600;
        color: #555;
        padding: 1rem;
    }
    .table td {
        border: none;
        border-bottom: 1px solid #f0f0f0;
        padding: 1rem;
        vertical-align: middle;
    }
    .table tbody tr:hover {
        background: #f8f9fa;
    }
    .status-badge {
        font-size: 0.8rem;
        padding: 0.3rem 0.6rem;
        border-radius: 0.5rem;
    }
    .btn-action {
        font-size: 0.8rem;
        padding: 0.3rem 0.6rem;
        margin: 0 0.2rem;
        border-radius: 0.5rem;
    }
    .empty-state {
        text-align: center;
        padding: 3rem;
        color: #888;
    }
    .empty-state i {
        font-size: 3rem;
        opacity: 0.5;
        margin-bottom: 1rem;
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
                        <div class="dashboard-title">{{ $subDepartment->name }}</div>
                        <div class="dashboard-subtitle">القسم الرئيسي: {{ $subDepartment->department->name }}</div>
                    </div>
                    <div class="top-actions">
                        <button class="btn btn-edit" data-bs-toggle="modal" data-bs-target="#editSubDepartmentModal">تعديل القسم الفرعي</button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Statistics Cards -->
        <div class="row mb-4 g-4">
            <div class="col-md-3">
                <div class="stat-card">
                    <div class="stat-label">إجمالي العملاء</div>
                    <div class="stat-value">{{ $customers->count() }}</div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="stat-card">
                    <div class="stat-label">الحالات الجديدة</div>
                    <div class="stat-value">{{ $customers->where('status', 'new')->count() }}</div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="stat-card">
                    <div class="stat-label">الحالات النشطة</div>
                    <div class="stat-value">{{ $customers->whereIn('status', ['in_progress', 'follow_up'])->count() }}</div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="stat-card">
                    <div class="stat-label">الحالات المغلقة</div>
                    <div class="stat-value">{{ $customers->where('status', 'closed')->count() }}</div>
                </div>
            </div>
        </div>

        <!-- Reports Section -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="modern-table">
                    <div class="table-header">التقارير حسب الحالة</div>
                    <div class="p-4">
                        <div class="row g-3">
                            <div class="col-md-3 col-sm-6">
                                <div class="stat-card no-answer">
                                    <div class="stat-label">No answer</div>
                                    <div class="stat-value">{{ $customers->where('status', 'new')->count() }}</div>
                                </div>
                            </div>
                            <div class="col-md-3 col-sm-6">
                                <div class="stat-card hot">
                                    <div class="stat-label">Hot</div>
                                    <div class="stat-value">{{ $customers->where('status', 'hot')->count() }}</div>
                                </div>
                            </div>
                            <div class="col-md-3 col-sm-6">
                                <div class="stat-card western">
                                    <div class="stat-label">Western</div>
                                    <div class="stat-value">{{ $customers->where('status', 'western')->count() }}</div>
                                </div>
                            </div>
                            <div class="col-md-3 col-sm-6">
                                <div class="stat-card follow">
                                    <div class="stat-label">Follow</div>
                                    <div class="stat-value">{{ $customers->where('status', 'follow_up')->count() }}</div>
                                </div>
                            </div>
                            <div class="col-md-3 col-sm-6">
                                <div class="stat-card deposits">
                                    <div class="stat-label">Deposits</div>
                                    <div class="stat-value">{{ $customers->where('status', 'in_progress')->count() }}</div>
                                </div>
                            </div>
                            <div class="col-md-3 col-sm-6">
                                <div class="stat-card not-interested">
                                    <div class="stat-label">Not interested</div>
                                    <div class="stat-value">0</div>
                                </div>
                            </div>
                            <div class="col-md-3 col-sm-6">
                                <div class="stat-card no-answer2">
                                    <div class="stat-label">No answer2</div>
                                    <div class="stat-value">0</div>
                                </div>
                            </div>
                            <div class="col-md-3 col-sm-6">
                                <div class="stat-card no-answer1">
                                    <div class="stat-label">No answer1</div>
                                    <div class="stat-value">0</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Customers Section -->
        <div class="customers-section">
            <div class="customers-title">العملاء في هذا القسم الفرعي</div>

            @if($customers->count() > 0)
                <div class="modern-table">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>رقم الحساب</th>
                                    <th>الاسم الكامل</th>
                                    <th>رقم الهاتف</th>
                                    <th>البريد الإلكتروني</th>
                                    <th>الحالة</th>
                                    <th>الموظف المسؤول</th>
                                    <th>تاريخ الإنشاء</th>
                                    <th>الإجراءات</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($customers as $customer)
                                <tr>
                                    <td>
                                        <strong>{{ $customer->ac_number }}</strong>
                                    </td>
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
                                    <td>{{ $customer->created_at->format('Y-m-d') }}</td>
                                    <td>
                                        <a href="{{ route('customers.show', $customer->id) }}" class="btn btn-sm btn-primary btn-action">عرض</a>
                                        <a href="{{ route('customers.edit', $customer->id) }}" class="btn btn-sm btn-warning btn-action">تعديل</a>
                                        <button class="btn btn-sm btn-danger btn-action" onclick="deleteCustomer({{ $customer->id }}, '{{ $customer->full_name }}')">حذف</button>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            @else
                <div class="modern-table">
                    <div class="empty-state">
                        <i class="bi bi-people"></i>
                        <p>لا يوجد عملاء في هذا القسم الفرعي</p>
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>

<!-- Edit Sub-Department Modal -->
<div class="modal fade" id="editSubDepartmentModal" tabindex="-1" aria-labelledby="editSubDepartmentModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editSubDepartmentModalLabel">تعديل القسم الفرعي: {{ $subDepartment->name }}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="إغلاق"></button>
            </div>
            <div class="modal-body">
                <form action="{{ route('sub-departments.update', $subDepartment->id) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <input type="hidden" name="department_id" value="{{ $subDepartment->department_id }}">
                    <div class="mb-3">
                        <label class="form-label">اسم القسم الفرعي</label>
                        <input type="text" class="form-control" name="name" value="{{ $subDepartment->name }}" required>
                    </div>
                    <button type="submit" class="btn btn-warning w-100">تحديث</button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Delete Customer Form -->
<form id="deleteCustomerForm" method="POST" style="display: none;">
    @csrf
    @method('DELETE')
</form>

@endsection

@section('scripts')
<script>
function deleteCustomer(id, name) {
    if (confirm('هل أنت متأكد من حذف العميل "' + name + '"؟')) {
        const form = document.getElementById('deleteCustomerForm');
        form.action = '{{ url("customers") }}/' + id;
        form.submit();
    }
}
</script>
@endsection
